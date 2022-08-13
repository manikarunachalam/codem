<?php
require_once("helper.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $_POST = json_decode(file_get_contents('php://input'), true);
    
    $ordersToBePlaced = $_POST['orders'];
    $response = [];
    foreach ($ordersToBePlaced as $orderFrontId => $orderToBePlaced) {
        if(isValidOrder($orderToBePlaced)) {
            $processedOrder = processTheOrder($orderToBePlaced);
            //print(json_encode($processedOrder));
            if($processedOrder['message']) {
                $response[$orderFrontId]=['success'=> 1,'orderData'=> $processedOrder['data']];
            }
            else {                
                $response[$orderFrontId]=['success'=> 0,'error'=> $processedOrder['error']];
            }
        }else {
            $response[$orderFrontId]=['success'=> 0,'error'=> 'Invalid Order'];
        }
    }
    print_r(json_encode($response));

}

/**
 * Proessing the order
 */
function processTheOrder($orderToBePlaced) {
    global $inventoryTableName,$orderTableName,$orderItemsTableName;
    getMysqlConnector()->prepare("START TRANSACTION")->execute();
    $proceed = true;
    $updateQueries = [];
    $RollbackQueries = [];
    $orderProducts = [];
    $backOrderProducts = [];
    foreach($orderToBePlaced['products'] as $product) {
        $dbProduct = getMysqlConnector()->prepare("SELECT id,quantity,price from $inventoryTableName WHERE id = ? FOR UPDATE;");
        $dbProduct->execute(array($product['product_id']));
        $dbProduct = $dbProduct->fetch(PDO::FETCH_ASSOC);
        if(!$dbProduct || $product['qty']>=$dbProduct['quantity']){
            //$proceed = false;break;//comment this for backorder disabling;
            $backOrderProducts[]=$product['product_id'];
        }
        $orderProducts[$dbProduct['id']] = $dbProduct['price']*$product['qty'];
        $RollbackQueries[]= "UPDATE $inventoryTableName SET quantity=quantity+".$product['qty']." WHERE id=".$product['product_id'].";";
        $updateQueries[]= "UPDATE $inventoryTableName SET quantity=quantity-".$product['qty']." WHERE id=".$product['product_id'].";COMMIT;";
    }
    if(!$proceed) {
        getMysqlConnector()->prepare("COMMIT")->execute();
        return ['message'=>false,'error'=>'One ore More Product(s) cannot be ordered'];
    }

    //updating inventory table.
    $updatedProducts = 0;
    foreach($updateQueries as $updateQuery) {
        $updatedProducts+= getMysqlConnector()->prepare($updateQuery)->execute();
    }

    //Creating Order
    $dbOrder = getMysqlConnector()->prepare("INSERT INTO $orderTableName (source,user_id,total) VALUES(?, ?, ?)");
    $dbOrder = $dbOrder->execute(array($orderToBePlaced['source'],'user',array_sum($orderProducts)));
    $orderPlaced = getMysqlConnector()->lastInsertId();
    $rollBackedProducts = 0;
    if(!$dbOrder) {        
        //roll backing quantities or understanding if the order logs could help to place the order with help manually
        //not implemented with making sure how to proceed.
        $rollBackedProducts = 0;
        foreach($RollbackQueries as $RollbackQuery)
            $rollBackedProducts+= getMysqlConnector()->prepare($RollbackQuery)->execute();
        if($rollBackedProducts!=count($orderToBePlaced['products'])) {
            //mailing or doing some logic if fails to let the rollback failure.
        }
        return ['message'=>false,'error'=>'Order Placing failed'];
    }

    //Order Line items creation
    $orderItemsQuery='';
    $orderItems = [];
    foreach($orderToBePlaced['products'] as $product){
        $dbOrderItem = getMysqlConnector()->prepare("INSERT INTO $orderItemsTableName (product_id, order_id, ordered_product_quantity, product_price_total,ordered_product_price) VALUES(?, ?, ?, ?, ?)");
        $dbOrderItem = $dbOrderItem->execute(array($product['product_id'], $orderPlaced,$product['qty'],$orderProducts[$product['product_id']],$orderProducts[$product['product_id']]/$product['qty']));
        $orderItems[] = getMysqlConnector()->lastInsertId();
    }

    return ['message'=>true,'data'=>['products_updated'=>$updatedProducts,'order_id'=>$orderPlaced,'order_total'=>array_sum($orderProducts),'lines'=>$orderItems,'back_order'=>$backOrderProducts]];    
}