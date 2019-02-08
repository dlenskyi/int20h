<?php

namespace application\controller;

use application\core\Controller;
use application\models\Emotion;
use application\core\phpFlickr;

class MainController extends Controller
{
    public function indexAction()
    {
        $f = new phpFlickr("a8785d392cb67796e36a9e78030423d2", "06cb90bace15bda0", true);

        $totalPhoto = Emotion::getTotalCountPhoto($f);
        $totalPage = Emotion::getCountPage($totalPhoto);
        $i = 0;
        $photoList = array();
        while($i < $totalPage) {
            array_push($photoList,  Emotion::getPhotoList($f));
            $i++;
        }
        $photoList = $photoList[0];
        $urlListArray = Emotion::getUrlList();
        $urlList = array();
        $i = 0;
        while ($i < count($urlListArray)) {
            $urlList[$i] = $urlListArray[$i]['img_url'];
            $i++;
        }
        $urls = Emotion::setUrlList($photoList, $urlList);


        $emotionUrlList = Emotion::getEmotionUrlList();

        $vars = $emotionUrlList;

        $this->view->render('INT20H EMOTION | Main', $vars);
        return true;
    }
}
