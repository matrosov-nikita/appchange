<?php

class Category
{
    public static function getListCategories()
    {
        $db = $GLOBALS['Db']::getConnection();
        $stmt = $db->prepare("SELECT * FROM category");
        $stmt->execute();
        return  $stmt;
    }

    public static function getCategoryById($id) {
        $db = $GLOBALS['Db']::getConnection();
        $stmt = $db->prepare("SELECT name FROM category WHERE id = :id");
        $stmt->execute(array('id'=>$id));
        $res = $stmt->fetch(PDO::FETCH_LAZY);
        return $res['name'];
    }



}