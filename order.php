<?php

require_once("helper.php");

echo getHeader();
echo "<a href='/codem'>Home</a> >> MarketPlace Order Generator<br/><br/>
<i> This table has a list of products and the quantity available, currently third column can have the quantity to be order. If any quantity is greater than zero then button will be enabled to create order, Multiple order can be created and placed all at once. Order can be placed if atleast one order is created.<br/><br/>";
$products = getAllProducts();
if(count($products)) {
    $sourceDropdown = "<input list='source_list' id='source' placeholder='Select a source'/><br/><datalist id='source_list'>";
    foreach($sources as $source) {
        $sourceDropdown.="<option value='".$source."'></option>";
    }
    $sourceDropdown.="</datalist>";
    echo "<table>
            <tr>
            <th>Product</th>
            <th>Quantity</th>
            <th>".$sourceDropdown."<input type='button' id='addOrder' data-index=0 value='add an order' disabled ></th>
            </tr>";
            $jsproducts = array();
    foreach($products as $product) {
        array_push($jsproducts,$product['product']);
        echo "<tr><td>".$product['product']."</td><td>".$product['quantity']."</td><td><input alt='product quantity' id=product_qty_".$product['id']." style='width:40px' size=1 max=5 class='orders_qty' type='number' value=0 data-product=".$product['product']." data-product_id=".$product['id']." /></td></tr>";
    }
    echo "</table>
        <br/>  
            <div id='orders'></div><br/><input id='placeOrder' style='display:none' type='button' value='Place the orders' />";
    echo "<script type='text/javascript'>
        var products=".json_encode($jsproducts).";
        var sources=".json_encode($sources).";
    </script>";

}else {
    echo "Sorry, We have ran out of Inventory";
}
echo getFooter();