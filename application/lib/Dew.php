<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);
ini_set('max_execution_time', 900);

function debug($str){
    echo '<pre>';

    var_dump($str);

    echo '</pre>';
    exit;
}