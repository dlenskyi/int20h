<?php

namespace application\controller;

use application\core\Controller;
use application\models\Emotion;
use application\core\phpFlickr;

class MainController extends Controller
{
    public function indexAction()
    {
        // make object of class phpFlickr
        $f = new phpFlickr("a8785d392cb67796e36a9e78030423d2", "06cb90bace15bda0", true);

        // get total count of photo in album
        $totalPhoto = Emotion::getTotalCountPhoto($f);
        //get count page
        $totalPage = Emotion::getCountPage($totalPhoto);
        //loop for all page in album
        $i = 0;
        $photoList = array();
        while($i < $totalPage) {
            array_push($photoList,  Emotion::getPhotoList($f));
            $i++;
        }
        //set array with list of photo
        $photoList = $photoList[0];
        // get url list of photo
        $urlListArray = Emotion::getUrlList();
        //loop for get list url
        $urlList = array();
        $i = 0;
        while ($i < count($urlListArray)) {
            $urlList[$i] = $urlListArray[$i]['img_url'];
            $i++;
        }
        // set url adn emotion to database and check availability
        $urls = Emotion::setUrlList($photoList, $urlList);
        // get list of url photo and emotion for view
        $emotionUrlList = Emotion::getEmotionUrlList();
        $vars = $emotionUrlList;
        $this->view->render('INT20H EMOTION | Main', $vars);
        return true;
    }
}
