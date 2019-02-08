<?php

namespace application\models;

use application\core\ConnectDb;
use \PDO;

class Converter
{
    public static function getCurrentCourse ($value) {
        $data = file_get_contents("https://api.privatbank.ua/p24api/pubinfo?json&exchange&coursid=5");

        if (!$data)
            return false;

        $content = json_decode($data, true);

        foreach ($content as $course) {
            if($course['ccy'] == $value) {
                $courseCurent = $course['buy'];
                break ;
            }
        }
        return $courseCurent;
    }

    public static function typeConvert($type) {
        switch ($type) {
            case 'usd-uah':
                return "USD -> UAH";
                break;
            case 'uah-usd':
                return "UAH -> USD";
                break;
            case 'eur-uah':
                return "EUR -> UAH";
                break;
            case 'uah-eur':
                return "UAH -> EUR";
                break;
            case 'uah-rur':
                return "UAH -> RUR";
                break;
            case 'rur-uah':
                return "RUR -> UAH";
                break;
            case 'btc-usd':
                return "BTC -> USD";
                break;
            case 'usd-btc':
                return "USD -> BTC";
                break;
            default:
                return "USD -> UAH";
                break;
        }
        return true;
    }

    public static function getCurrentValue($type)
    {
        switch ($type) {
            case 'usd-uah':
                return "USD";
                break;
            case 'uah-usd':
                return "USD";
                break;
            case 'eur-uah':
                return "EUR";
                break;
            case 'uah-eur':
                return "EUR";
                break;
            case 'uah-rur':
                return "RUR";
                break;
            case 'rur-uah':
                return "RUR";
                break;
            case 'btc-usd':
                return "BTC";
                break;
            case 'usd-btc':
                return "BTC";
                break;
        }
        return true;
    }

    public static function getValueOfConvert($type, $currentCourse, $valueConvert) {
        switch ($type) {
            case 'USD -> UAH':
                return $valueConvert * $currentCourse;
                break;
            case 'UAH -> USD':
                return $valueConvert / $currentCourse;
                break;
            case 'EUR -> UAH':
                return $valueConvert * $currentCourse;
                break;
            case 'UAH -> EUR':
                return $valueConvert / $currentCourse;
                break;
            case 'UAH -> RUR':
                return $valueConvert / $currentCourse;
                break;
            case 'RUR -> UAH':
                return $valueConvert * $currentCourse;
                break;
            case 'BTC -> USD':
                return $valueConvert * $currentCourse;
                break;
            case 'USD -> BTC':
                return $valueConvert / $currentCourse;
                break;
        }
        return true;
    }

    public static function addOperation($type, $finalValue, $currentValue) {
        $instance = ConnectDb::getInstance();
        $db = $instance->getConnection();

        $sql = "INSERT INTO converter (operations, current_value, final_value) VALUE (?, ?, ?);";

        $result = $db->prepare($sql);
        $result->execute(array($type, $currentValue, $finalValue));

        if($result) {
            return $db->lastInsertId();
        }
        return 0;
    }

    public static function setUserOperation($userId, $convertId) {
        $instance = ConnectDb::getInstance();
        $db = $instance->getConnection();

        $sql = "INSERT INTO user_converter (id_operation, id_user) VALUE (?, ?);";

        $result = $db->prepare($sql);
        $result->execute(array($convertId, $userId));
        if($result) {
            return $db->lastInsertId();
        }
        return 0;
    }

    public static function getLastConvertin() {

        $instance = ConnectDb::getInstance();
        $db = $instance->getConnection();

        $sql = "SELECT u.login, u.email, c.id, c.operations, c.current_value, c.final_value, c.date FROM user u, converter c INNER JOIN user_converter uc WHERE u.ID = uc.id_user && c.ID = uc.id_operation ORDER by c.date DESC LIMIT 10";

        $result = $db->prepare($sql);
        $result->execute();
        $i = 0;
        $operation = array();
        while ($row = $result->fetch()){
            $operation[$i]['id'] = $row['id'];
            $operation[$i]['login'] = $row['login'];
            $operation[$i]['email'] = $row['email'];
            $operation[$i]['operations'] = $row['operations'];
            $operation[$i]['current_value'] = $row['current_value'];
            $operation[$i]['final_value'] = $row['final_value'];
            $operation[$i]['date'] = $row['date'];
            $i++;
        }
        return $operation;
    }

    public static function getOperationUser($userId, $max, $routs) {

        if (isset($routs)){
            $start = ($routs - 1) * $max;
        } else {
            $start = 0;
        }

        $instance = ConnectDb::getInstance();
        $db = $instance->getConnection();

        $sql = "SELECT u.login, u.email, c.id, c.operations, c.current_value, c.final_value, c.date FROM user u, converter c INNER JOIN user_converter uc WHERE u.ID = uc.id_user && c.ID = uc.id_operation && uc.id_user = :userId ORDER by c.date DESC LIMIT :end OFFSET :start";

        $result = $db->prepare($sql);
        $result->bindParam(':userId', $userId, PDO::PARAM_INT);
        $result->bindParam(':start', $start, PDO::PARAM_INT);
        $result->bindParam(':end', $max, PDO::PARAM_INT);

        $result->execute();

        $i = 0;
        $operation = array();
        while ($row = $result->fetch()){
            $operation[$i]['id'] = $row['id'];
            $operation[$i]['login'] = $row['login'];
            $operation[$i]['email'] = $row['email'];
            $operation[$i]['operations'] = $row['operations'];
            $operation[$i]['current_value'] = $row['current_value'];
            $operation[$i]['final_value'] = $row['final_value'];
            $operation[$i]['date'] = $row['date'];
            $i++;
        }
        return $operation;
    }

    public static function getCurrencyPairsUser ($userId) {
        $instance = ConnectDb::getInstance();
        $db = $instance->getConnection();

        $sql = "SELECT user_seting FROM user_seting WHERE user_id=:userId";

        $result = $db->prepare($sql);
        $result->bindParam(':userId', $userId, PDO::PARAM_INT);
        $result->execute();
        $currencyPairs = $result->fetch();
        return $currencyPairs;
    }

    public static function getCurrencyPairs () {
        $instance = ConnectDb::getInstance();
        $db = $instance->getConnection();

        $sql = "SELECT * FROM currency_pairs";

        $result = $db->prepare($sql);
        $result->execute();
        $i = 0;
        $currencyPairs = array();
        while ($row = $result->fetch()){
            $currencyPairs[$i]['currency_pairs'] = $row['currency_pairs'];
            $currencyPairs[$i]['status'] = $row['status'];
            $currencyPairs[$i]['route'] = $row['route'];
            $i++;
        }
        return $currencyPairs;
    }

    public static function getTypeConvert($userId) {
        $instance = ConnectDb::getInstance();
        $db = $instance->getConnection();

        $sql = "SELECT type_convertyng FROM user_seting WHERE user_id=:userId";

        $result = $db->prepare($sql);
        $result->bindParam(':userId', $userId, PDO::PARAM_INT);
        $result->execute();
        $currencyPairs = $result->fetch();
        return $currencyPairs;
    }

    public static function setTypeConvert($userId, $typeConverting) {
        $instance = ConnectDb::getInstance();
        $db = $instance->getConnection();

        $sql = 'UPDATE user_seting SET type_convertyng=? WHERE user_id=?';

        $result = $db->prepare($sql);
        $result = $result->execute(array($typeConverting, $userId));
        return $result;
    }

    public static function countId($userId){
        $instance = ConnectDb::getInstance();
        $db = $instance->getConnection();

        $sql = "SELECT COUNT(c.id) FROM user u, converter c INNER JOIN user_converter uc WHERE u.ID = uc.id_user && c.ID = uc.id_operation && uc.id_user = :userId";

        $result = $db->prepare($sql);
        $result->bindParam(':userId', $userId, PDO::PARAM_STR);
        $result->execute();
        $id = $result->fetchColumn();
        return $id;
    }


}