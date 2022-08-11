<?php

require_once("helper.php");

echo getHeader();
echo "<a href='/codem'>Home</a> >> MarketPlace Order Generator<br/><br/>";
$products = getAllProducts();
if(count($products)) {
    echo "<table>
            <tr>
            <th>Product</th>
            <th>Quantity</th>
            <th><input type='button' id='addOrder' data-index=0 value='add an order' disabled ></th>
            </tr>";
            $jsproducts = array();
    foreach($products as $product) {
        array_push($jsproducts,$product['product']);
        echo "<tr><td>".$product['product']."</td><td>".$product['quantity']."</td><td><input alt='product quantity' style='width:40px' size=1 max=5 class='orders_qty' type='number' value=0 data-product=".$product['product']." /></td></tr>";
    }
    echo "</table>
        <br/>  
            <div id='orders'>
            </div>";
    echo "<script type='text/javascript'>
        var products=".json_encode($jsproducts).";
        var sources=".json_encode($sources).";
    </script>";

}else {
    echo "Sorry, We have ran out of Inventory";
}
echo getFooter();