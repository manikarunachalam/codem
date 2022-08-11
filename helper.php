<?php
require_once("config.php");
require_once('MysqlConnector.php');

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
        echo "Mysql Exception:".$e->getMessage()."<br/><br/><b> Please make sure the db connection parameters are right as per your local mysql settings with the values used in MysqlConnection construct method while creating the connection.<b>";
        die();
    }
}

function getAllProducts() {
    global $inventoryTableName;
    return getMysqlConnector()->query("select * from $inventoryTableName order by product asc")->fetchAll();
    
}