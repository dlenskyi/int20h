<?php

namespace application\core;

use \PDO;

class ConnectDb {
    // Hold the class instance.
    private static $instance = null;
    private $conn;

    private $host ;
    private $name;
    private $pass;
    private $user;

    // The db connection is established in the private constructor.
    private function __construct()
    {
        if ($_SERVER['SERVER_NAME'] == "thawing-castle-24253.herokuapp.com") {
            $url = parse_url(getenv("CLEARDB_DATABASE_URL"));
            $this->host = $url["host"];
            $this->user = $url["user"];
            $this->pass = $url["pass"];
            $this->name = substr($url["path"], 1);
        } else {
            $this->host = 'localhost';
            $this->user = 'root';
            $this->pass = '11111111';
            $this->name = 'int20h';
        }

        try {
            $this->conn = new PDO("mysql:host=$this->host;dbname=$this->name", $this->user, $this->pass,
                array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
        } catch (\PDOException $e){
            ;
        }

        if ($this->conn != null){
            ;
        } else {
            $this->conn = new PDO("mysql:host={$this->host};", $this->user,$this->pass,
                array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "CREATE DATABASE IF NOT EXISTS int20h";
            $this->conn->exec($sql);
            $this->conn = null;
            $db = new PDO("mysql:host=$this->host;dbname=$this->name", $this->user, $this->pass);
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = file_get_contents("int20h.sql");
            $db->exec($sql);
            $this->conn = null;
            $this->conn = new PDO("mysql:host=$this->host;dbname=$this->name", $this->user, $this->pass,
                array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
        }

    }

    public static function getInstance()
    {
        if(!self::$instance)
        {
            self::$instance = new ConnectDb();
        }

        return self::$instance;
    }

    public function getConnection()
    {
        return $this->conn;
    }
}
