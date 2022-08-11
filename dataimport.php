<?php
require_once("helper.php");

//This wil be called only by ajax which requires the inventory table should be created
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $csvFilePath = "sample.csv";
    $rows=0;
    $success=0;
    $message = "";
    try {
        $stmt = getMysqlConnector()->prepare("DELETE FROM $inventoryTableName");
        $message = ($stmt->execute()?"<br/>Old inventory is flushed":"");
        $file = fopen($csvFilePath, "r");
        while (($row = fgetcsv($file)) !== FALSE) {
            $rows++;
            $product = explode("=",$row[0]);
            //this can be improved by single query execution
            $stmt = getMysqlConnector()->prepare("INSERT INTO $inventoryTableName (product, quantity) VALUES (?, ?)");
            $stmt->execute($product)?$success++:($message.="<br/>Product $product[0] : ".$stmt->errorinfo()[2]);
        }
        if($rows==$success) 
            $message.="<br/><b>New Data Import is Successfull</b>";
        else
            $message.="<br/><b>Data Import is failed</b>";
    }
    catch(Exception $e) {
        $message.= "<br/>Import Error. Reason:".$e->getMessage();
    }

    echo $message;
}