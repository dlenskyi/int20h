<?php
/**
 * Created by PhpStorm.
 * User: remote_user
 * Date: 08.02.2019
 * Time: 11:14
 */

namespace application\models;

use application\core\ConnectDb;
use \PDO;

class Emotion
{
    public static function getTotalCountPhoto($f) {
        $params = ([
            'user_id' => '144522605@N06',
            'in_gallery' => '72157674388093532',
            'tag' => 'int20h',
            'per_page' => '1'
        ]);

        $res = $f->photos_search($params);
        return $res['total'];
    }

    public static function getCountPage($totalPhoto) {
        return ceil($totalPhoto / 500);
    }

    public static function getPhotoList($f) {
        $params = ([
            'user_id' => '144522605@N06',
            'in_gallery' => '72157674388093532',
            'tag' => 'int20h',
            'per_page' => '500'
        ]);
        $res = $f->photos_search($params);
        return $res['photo'];
    }

    public static function setUrlList($photoList, $urlList) {
        $i = 0;
        $urls = array();
        while($i < count($photoList)) {
            $urls[$i] ='https://farm'.$photoList[$i]['farm'];
            $urls[$i] .='.staticflickr.com/'.$photoList[$i]['server'].'/'.$photoList[$i]['id'].'_'.$photoList[$i]['secret'].'.jpg';
            if(in_array($urls[$i], $urlList) == true) {
                $i++;
                continue;
            }
            else {
                $idUrl = self::addUrl($urls[$i]);
                $userEmotion = self::getPhotoEmotion($urls[$i]);
                $userEmotion = json_decode($userEmotion);
                if (isset($userEmotion) && json_last_error() === 0) {
                    $gradeEmotion = array();
                    foreach ($userEmotion as $key => $value) {
                        $gradeEmotion[] = $value;
                    }
                    $maxGradeEmotion = max($gradeEmotion);
                    $emotionPhoto = '';
                    foreach ($userEmotion as $key => $value) {
                        if ($value == $maxGradeEmotion){
                            $emotionPhoto = $key;
                            $emotionId = self::getEmotionId($emotionPhoto);
                            $emotionUrlId = self::setEmotionUrl($idUrl, $emotionId['id']);
//                            DELETE FROM image_url;DELETE FROM emotion_url;
                        }
                    }
                } else {
//                    debug("lol");
                    echo  "lol";
                    $emotionPhoto = 'none';
                    $emotionId = self::getEmotionId($emotionPhoto);
                    $emotionUrlId = self::setEmotionUrl($idUrl, $emotionId['id']);
                    print_r($emotionUrlId);
                }
            }
            $i++;
        }
        return $urls;
    }

    public static function checkAvailability($url) {
        $instance = ConnectDb::getInstance();
        $db = $instance->getConnection();

        $sql = "SELECT * FROM image_url WHERE img_url=:url";

        $result = $db->prepare($sql);
        $result->bindParam(':url', $url, PDO::PARAM_STR);
        $result->execute();
        if($result->fetchColumn()){
            return true;
        }
        return false;
    }

    public static function addUrl($url) {
        $instance = ConnectDb::getInstance();
        $db = $instance->getConnection();

        $sql = "INSERT INTO image_url (img_url) VALUES (:img_url)";

        $result = $db->prepare($sql);
        $result->bindParam(':img_url', $url);
        $result->execute();
        if($result) {
            return $db->lastInsertId();
        }
        return 0;
    }

    public static function getUrlList() {
        $instance = ConnectDb::getInstance();
        $db = $instance->getConnection();

        $sql = "SELECT img_url FROM image_url";
        $result = $db->prepare($sql);
        $result->execute();
        $i = 0;
        $urlList = array();
        while ($row = $result->fetch()){
            $urlList[$i]['img_url'] = $row['img_url'];
            $i++;
        }
        return $urlList;
    }

    public static function getPhotoEmotion($urls) {
        $url = 'https://api-us.faceplusplus.com/facepp/v3/detect';
        $data = array(
            'api_key' => 'tL8dNz6l0BH1se-leGivo6zjZD7deIc5', // api key
            'api_secret' => 'nrwL6eBIxN2LHvR7WZldyd07JCjTU0mN',  // api secret
            'image_url' => $urls, // url for img parsing from page
            'return_landmark' => '1',  //return value
            'return_attributes' => 'gender,emotion', // return atribut
        );

        $fields_string = http_build_query($data);
        $ch = curl_init();

        curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_POST, count($data));
        curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        $decod = json_decode($result);

        $res = array();
        foreach ($decod as $key => $value){
            if ($key == 'faces') {
                foreach ($value as $key2 => $val) {
                    if ($key2 == 'attributes') {
                        foreach ($val as $finkey => $finval) {
                            if ($finkey == 'attributes') {
                                foreach ($finval as $lastkey => $lastvalue) {
                                    if ($lastkey == 'emotion'){
                                        $res = json_encode($lastvalue);
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        return $res;
    }

    public static function getEmotionId($emotion) {
        $instance = ConnectDb::getInstance();
        $db = $instance->getConnection();

        $sql = "SELECT id FROM emotion WHERE emotion=:emotion";

        $result = $db->prepare($sql);
        $result->execute(['emotion' => $emotion]);
        $id = $result->fetch(PDO::FETCH_ASSOC);
        return $id;
    }

    public static function setEmotionUrl ($idUrl, $idEmotion) {
        $instance = ConnectDb::getInstance();
        $db = $instance->getConnection();

        $sql = "INSERT INTO emotion_url (id_emotion, id_image_url) VALUES (:id_emotion, :id_image_url)";

        $result = $db->prepare($sql);
        $result->bindParam(':id_emotion', $idEmotion);
        $result->bindParam(':id_image_url', $idUrl);
        $result->execute();
        if($result) {
            return $db->lastInsertId();
        }
        return 0;
    }

    public static function getEmotionUrlList() {
//        SELECT i.img_url, e.emotion FROM image_url AS i, emotion AS e JOIN emotion_url AS eu WHERE i.id=eu.id_image_url AND e.id=eu.id_emotion ORDER BY i.date DESC;
        $instance = ConnectDb::getInstance();
        $db = $instance->getConnection();

        $sql = "SELECT i.img_url, e.emotion FROM image_url AS i, emotion AS e JOIN emotion_url AS eu WHERE i.id=eu.id_image_url AND e.id=eu.id_emotion ORDER BY i.date DESC;";
        $result = $db->prepare($sql);
        $result->execute();
        $i = 0;
        $emotionUrlList = array();
        while ($row = $result->fetch()){
            $emotionUrlList[$i]['img_url'] = $row['img_url'];
            $emotionUrlList[$i]['emotion'] = $row['emotion'];
            $i++;
        }
        return $emotionUrlList;
    }

}