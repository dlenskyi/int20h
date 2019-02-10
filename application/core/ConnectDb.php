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
    // mysql://bd053e007855d2:221e3d0b@us-cdbr-iron-east-03.cleardb.net/heroku_57235a458eda35a?reconnect=true
    private function __construct()
    {
        if ($_SERVER['SERVER_NAME'] == "thawing-island-242342379.herokuapp.com") {
            $url = parse_url(getenv("CLEARDB_DATABASE_URL"));
            $this->host = $url["host"];
            $this->user = $url["user"];
            $this->pass = $url["pass"];
            $this->name = substr($url["path"], 1);
        } else {
            $this->host = 'us-cdbr-iron-east-03.cleardb.net';
            $this->user = 'bd053e007855d2';
            $this->pass = '221e3d0b';
            $this->name = 'heroku_57235a458eda35a';
        }

        $this->conn = new PDO("mysql:host={$this->host}; dbname={$this->name}", $this->user,$this->pass,
            array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
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
