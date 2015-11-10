<?php
include_once ROOT.'/models/User.php';
include_once ROOT.'/models/Advert.php';
include_once ROOT.'/models/Category.php';
include_once ROOT.'/models/Paginator.php';
class AdvertController {

    public static function actionCreate() {
       $res = Advert::createAdvert();
       return $res;
    }

    public static function actionGet($count) {
        $adverts = array();
        $adverts = Advert::getAllAdverts($count);
        $itemHTML = include_once(ROOT.'/views/advert/index.php');
        $dateBack = (string)$itemHTML;
        return "end";
    }

    public static function actionUpdate() {
        $res = Advert::updateAdvert();
        return $res;
    }
    public static function  actionCount() {
        $res  =  Paginator::getCountPages();
        return $res;

    }
    public static function actionProfile() {
        $my_adverts = array();
        $my_adverts = Advert::getAdvertsByLogin();
        require_once(ROOT.'/views/advert/profile_advert.php');
        return "end";
    }

    public static function  actionDetails() {
        $res = Advert::watchDetails();
        return $res;
    }


}