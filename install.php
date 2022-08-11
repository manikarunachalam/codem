<?php
require_once("helper.php");

echo getHeader();

echo "<a href='/codem'>Home</a> >> Installation<br/><br/>";
echo "<i>Setting up the required infrastructure... Please make sure mysql server is running.</i><br/>";

echo "<br/>Mysql Connection :";
// Create connection
$connection = new mysqli($mysqlCredentials['host'], $mysqlCredentials['username'], $mysqlCredentials['password']);
// Check connection
if ($connection->connect_error) {
  die (" Failed. <br/> Exiting the setup, Reason:" . $connection->connect_error);
}
echo " Estalished. <br/>"; 

//Dropping the existing database if flush param is set
if(isset($_GET['flush'])) {
    echo "<br/>Flushing the existing Database : ";
    $sql = "DROP DATABASE IF EXISTS ".$mysqlCredentials['dbname'];
    if ($connection->query($sql) === TRUE) {
      echo $mysqlCredentials['dbname']." is dropped.";
    } else {
      echo "Failed. Reason : " . $connection->error;
    }
}

// Creating the database
echo "<br/>Creating the Database : ";
$sql = "CREATE DATABASE ".$mysqlCredentials['dbname'];
if ($connection->query($sql) === TRUE) {
  echo $mysqlCredentials['dbname']." is created.";
} else {
  echo "Failed (add ?flush along with the url to flush the existingdb). Reason : " . $connection->error;
}

$sql = "USE ".$mysqlCredentials['dbname'];
if ($connection->query($sql) != TRUE) {
  echo "<br/>".$mysqlCredentials['dbname']." is not selected";die();
}


// Creating the table inventory
/**
 * id int auto increment
 * product varcar product name
 * quantity int available quantity
 */
echo "<br/>Creating the Inventory Table: ";
$sql = "CREATE TABLE $inventoryTableName (
  id int(5) AUTO_INCREMENT PRIMARY KEY, 
  product varchar(5) NOT NULL, 
  quantity int(3) NOT NULL DEFAULT 0)";
if ($connection->query($sql) === TRUE) {
  echo $inventoryTableName." is created.";
} else {
  echo "Failed. Reason : " . $connection->error;
}


// Creating the table orders
echo "<br/>Creating the Orders Table: ";
/**
 * id int auto increment order id
 * user_id int user id
 * total float order total
 * payment_id int payment id
 * source varchar order source
 */
$sql = "CREATE TABLE $orderTableName (
  id int(5) AUTO_INCREMENT PRIMARY KEY, 
  user_id int(5) NOT NULL, 
  total float(3) NOT NULL, 
  payment_id float(3) NOT NULL, 
  source varchar(5) NOT NULL)";
if ($connection->query($sql) === TRUE) {
  echo $orderTableName." is created.";
} else {
  echo "Failed. Reason : " . $connection->error;
}


/**
 * id int line items id
 * product_id int product id
 * quantity int ordered quantity
 * price float line item price
 */
echo "<br/>Creating the Order line items Table: ";
$sql = "CREATE TABLE $orderItemsTableName (
  id int(5) AUTO_INCREMENT PRIMARY KEY, 
  product_id int(3) NOT NULL, 
  quantity int(3) NOT NULL DEFAULT 0, 
  price float(3) NOT NULL)";
if ($connection->query($sql) === TRUE) {
  echo $orderItemsTableName." is created.";
} else {
  echo "Failed. Reason : " . $connection->error;
}
$connection->close();

echo "<br/><br/> Here is the Default inventory setup file:<a href='sample.csv'>sample.csv</a> Replace the content of this file to modify the inventory
      <br/>
      <br/>
      <input type='button' id='populateinventory' value='Click here'> to populate the inventory table with the sample.csv file
      <div id='importresult'></div>";
echo getFooter();
