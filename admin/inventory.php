<?php

require_once("../helper.php");

echo getHeader();
echo "<a href='/codem'>Home</a> >> Inventoy Management<br/><br/>";
$products = getAllProducts();
if(count($products)) {
    echo "<table>
            <tr>
            <th>Product Name</th>
            <th>Quantity</th>
            </tr>";
    foreach($products as $product) {
        echo "<tr><td>".$product['product']."</td><td>".$product['quantity']."</td></tr>";
    }
    echo "</table>";

}else {    
    echo "<br/><br/> Here is the Default inventory setup file:<a href='sample.csv'>sample.csv</a> Replace the content of this file to modify the inventory
    <br/>
    <br/>
    <input type='button' id='populateinventory' value='Click here'> to populate the inventory table with the sample.csv file
    <div id='importresult'></div>";
}

echo getFooter();