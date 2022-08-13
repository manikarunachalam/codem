<?php
require_once("config.php");
require_once('MysqlConnector.php');

/**
 * builds header html for all over the site
 */
function getHeader() {
    return "<html>
                <head>
                    <title>CODEM</title>
                    <script src='/codem/js/jquery_v3.6.0.js'></script>
                    <link rel='stylesheet' href='/codem/css/custom.css'/>
                </head>
                <body>
                    <center>
                    <br/>
                    <h3>CODEM</h3>";
}


/**
 * builds footer html for all over the site
 */
function getFooter() {
    return "<script src='/codem/js/custom.js'></script></center>
            </body>
            </html>";
}

//Getting Mysql Connector object
function getMysqlConnector() {
    global $mysqlCredentials;
    try{
        $MysqlConnectorInstance = MysqlConnection::getConnector($mysqlCredentials);
        return $MysqlConnectorInstance->getMysqlConnector();
    }
    catch(Exception $e) {
        die("Mysql Exception:".$e->getMessage()."<br/><br/><b> Please make sure the db connection parameters are right as per your local mysql settings with the values used in MysqlConnection construct method while creating the connection.<b>");
        
    }
}

/**
 * Gets all products from inventory
 */
function getAllProducts() {
    global $inventoryTableName;
    return getMysqlConnector()->query("select * from $inventoryTableName where quantity > 0 order by product asc")->fetchAll();
}


/**
 * Checks if the given order is valid
 */
function isValidOrder($order) {
    global $productQtyMin,$productQtyMax;
    $total=0;
    if(count($order['products'])<1)
        return false;
    $valid = true;
    foreach ($order['products'] as $product) {
        //print_r($product);
        if(($product['qty']<$productQtyMin)||($product['qty']>$productQtyMax)||!isValidProduct($product))  
            $valid =false;          
    }
    return $valid;
}

/**
 * Check if the given product is valid
 */
function isValidProduct($product) {
    return true;//comment this for backorder disabling;
    global $inventoryTableName;
    $dbProduct = getMysqlConnector()->prepare("select id,quantity from $inventoryTableName  where id = ?");
    $dbProduct->execute(array($product['product_id']));
    $dbProduct = $dbProduct->fetch(PDO::FETCH_ASSOC);
    //print_r("db inventory:".$product['qty']."::".$dbProduct['quantity']);
    if($dbProduct && ($product['qty']<=$dbProduct['quantity']))
        return true;
    return false;
}