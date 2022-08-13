<?php

// mysql configurations.
$mysqlCredentials = array(
    'host'=>'127.0.0.1',
    'username'=>'root',
    'password'=>'',
    'dbname'=>'codem'
);

$inventoryTableName = 'inventory';
$orderTableName = 'orders';
$orderItemsTableName = 'order_items';

$productQtyMin = 1;

$productQtyMax = 5;


$sources = array('Amazon','AJIO','Ebay','Flipkart','Myntra');