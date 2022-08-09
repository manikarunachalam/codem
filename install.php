<?php
require_once("helper.php");

echo "
    <center>
            <br/>
            <h3>CODEM</h3>
            <br/>";

echo "Setting up the required infrastructure... Please make sure mysql server is running.<br/>";

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


// Creating the table inventory
echo "<br/>Creating the Inventory : ";
$sql = "CREATE TABLE ".$mysqlCredentials['dbname'];
if ($connection->query($sql) === TRUE) {
  echo $mysqlCredentials['dbname']." is created.";
} else {
  echo "Failed (add ?flush along with the url to flush the existingdb). Reason : " . $connection->error;
}



$connection->close();


echo "            
    </center>";