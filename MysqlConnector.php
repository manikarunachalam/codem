<?php

/**
 * This is a singleton class for mysql connection
 */

class MysqlConnection {

    //static instance which is going to be initialised only once.
    private static $MysqlConnectorInstance = null;

    //Mysql connection object
    private $MysqlConnector;

    //Making private4 constructor does not allow creating object outside the class.
    private function __construct(){
        try{
            $this->MysqlConnector = new PDO("mysql:host=".$mysqlCredentials['host'].";"."dbname".$mysqlCredentials['dbname'].";charset=UTF8",$mysqlCredentials['username'],$mysqlCredentials['password']);
        }
        catch(Exception $e) {
            throw $e;
        }
    }

    //This is responsible for this class to be a singleton and it creates only first time. 
    public static function getConnector() {
        if(!isset(self::$MysqlConnectorInstance)) {
            self::$MysqlConnectorInstance = new MysqlConnection();
        }
        return self::$MysqlConnectorInstance;
    }

    //this is to get the connector object in/outside the class
    public function getMysqlConnector() {
        return $this->MysqlConnector;
    }
     
}