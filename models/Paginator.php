<?php

class Paginator
{

    public static function getCountPages() {
        $db = $GLOBALS['Db']::getConnection();
        $stmt = $db->prepare("SELECT * from adverts");
        $stmt->execute();

        return ceil($stmt->rowCount()/$GLOBALS['max_posts']);
    }
}