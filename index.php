<?php
/**
 * Created by PhpStorm.
 * User: vbudnik
 * Date: 11/7/18
 * Time: 7:47 PM
 */

require 'application/lib/Dew.php';

use application\core\Router;
use application\core\Db;

spl_autoload_register(function ($class) {
    $path = str_replace('\\', '/', $class. '.php');
    if(file_exists($path)){
        require $path;
    }
});

session_start();

$router = new Router();

$router->run();