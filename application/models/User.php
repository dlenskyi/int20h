<?php

namespace application\models;

use application\core\ConnectDb;
use \PDO;

class User
{
    public static function checkPassword($password){
        if(strlen($password) >= 6){
            return true;
        }
        return false;
    }

    public static function checkEmailExists($email){
        $instance = ConnectDb::getInstance();
        $db = $instance->getConnection();

        $sql = 'SELECT count(*) FROM user WHERE email = :email';

        $result = $db->prepare($sql);
        $result->bindParam(':email', $email, PDO::PARAM_STR);
        $result->execute();
        if($result->fetchColumn()){
            return true;
        }
        return false;
    }

    public static function checkEmail($email){
        if(filter_var($email, FILTER_VALIDATE_EMAIL)){
            return true;
        }
        return false;
    }

    public static function auth($userId, $userName){

        $_SESSION['user'] = $userId;
        $_SESSION['name'] = $userName;
    }

    public static function checkLogin(){
        if(isset($_SESSION['user']) && $_SESSION['user'] != 1){
            return true;
        }
        return false;
    }


    public static function checkUserData($email, $password){

        $instance = ConnectDb::getInstance();
        $db = $instance->getConnection();

        $sql = 'SELECT * FROM user WHERE email=:email AND password = :password';

        $result = $db->prepare($sql);
        $result->bindParam(':email', $email, PDO::PARAM_STR);
        $result->bindParam(':password', $password, PDO::PARAM_STR);
        $result->execute();
        $user = $result->fetch();
        if($user){
            return $user['id'];
        }
        return false;
    }

    public static function getUserId() {
        return $_SESSION['user'];
    }

    public static function registerUser($options) {
        $instance = ConnectDb::getInstance();
        $db = $instance->getConnection();

        $sql = 'INSERT INTO user (login, email, password)'
            .'VALUES (?, ?, ?);';

        $result = $db->prepare($sql);
        $result = $result->execute(array($options['name'], $options['email'], $options['password']));
        if($result) {
            return $db->lastInsertId();
        }
        return 0;
    }


    public static function setStatus($userId, $status=0) {
        $instance = ConnectDb::getInstance();
        $db = $instance->getConnection();

        $sql = "INSERT INTO status_user (user_id, status) VALUE (?, ?);";

        $result = $db->prepare($sql);
        $result->execute(array($userId, $status));
        if($result) {
            return $db->lastInsertId();
        }
        return 0;
    }

    public static function getUserName($userId) {
        $instance = ConnectDb::getInstance();
        $db = $instance->getConnection();

        $sql = "SELECT login FROM user WHERE id=:user_id";

        $result = $db->prepare($sql);
        $result->execute(['user_id' => $userId]);
        $userName = $result->fetch(PDO::FETCH_ASSOC);
        return $userName;
    }


    public static function setMaxElemetToUser($max, $userId) {
        $instance = ConnectDb::getInstance();
        $db = $instance->getConnection();

        $sql = 'UPDATE user_seting SET max_element=? WHERE user_id=?';

        $result = $db->prepare($sql);
        $result = $result->execute(array($max, $userId));
        return $result;
    }

    public static function setUserSeting($userId, $userCurrencyPairs)
    {
        $instance = ConnectDb::getInstance();
        $db = $instance->getConnection();

        $sql = 'UPDATE user_seting SET user_seting=? WHERE user_id=?';

        $result = $db->prepare($sql);
        $result = $result->execute(array($userCurrencyPairs, $userId));
        return $result;
    }

    public static function checkExist($userId) {
        $instance = ConnectDb::getInstance();
        $db = $instance->getConnection();

        $sql = 'SELECT * FROM user_seting WHERE user_id=?';

        $result = $db->prepare($sql);
        $result->execute(array($userId));
        $userData = $result->fetch();
        return $userData;
    }

    public static function addUserSeting($userId, $userSeting, $max = 5, $typeConverting = "uah-usd"){
        $instance = ConnectDb::getInstance();
        $db = $instance->getConnection();

        $sql = 'INSERT INTO user_seting (user_id, user_seting, max_element, type_convertyng) VALUE (?, ?, ?, ?)';

        $result = $db->prepare($sql);
        $result->execute(array($userId, $userSeting, $max, $typeConverting));
        if($result) {
            return $db->lastInsertId();
        }
        return 0;
    }

    public static function getUserMaxElement($userId){
        $instance = ConnectDb::getInstance();
        $db = $instance->getConnection();

        $sql = 'SELECT max_element FROM user_seting WHERE user_id=?';

        $result = $db->prepare($sql);
        $result->execute(array($userId));
        $maxElement = $result->fetch();
        return $maxElement;
    }

    public static function getUserSeting($userId){
        $instance = ConnectDb::getInstance();
        $db = $instance->getConnection();

        $sql = 'SELECT user_seting FROM user_seting WHERE user_id=?';

        $result = $db->prepare($sql);
        $result->execute(array($userId));
        $userSeting = $result->fetch();
        return $userSeting;
    }
}