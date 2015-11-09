<?php
include_once ROOT.'/models/User.php';
class Advert
{

    private static function img_resize($type, $destin, $quality=100)
    {

        if ($type == 'image/jpeg')
            $source = imagecreatefromjpeg($destin);
        elseif ($type == 'image/png')
            $source = imagecreatefrompng( $destin);
        elseif ($type == 'image/gif')
            $source = imagecreatefromgif($destin);
        else
            return false;
        $src = $source;
        $w_src = imagesx($src);
        $h_src = imagesy($src);
        $w=900;
        if ($w_src>$w) {
            $ratio = $w_src/$w;
            $w_dest = round($w_src/$ratio);
            $h_dest = round($h_src/$ratio);
            $dest = imagecreatetruecolor($w_dest, $h_dest);
            imagecopyresampled($dest, $src, 0, 0, 0, 0, $w_dest, $h_dest, $w_src, $h_src);
            imagejpeg($dest, $destin, $quality);
            imagedestroy($dest);
            imagedestroy($src);
        }

        return true;
    }
    public static function createAdvert()
    {
        $db = $GLOBALS['Db']::getConnection();
        $dateBack = array();
        $errors = array();
        if (isset($_POST)) {
            $header = $_POST['header'];
            $description = $_POST['description'];

            $stmt = $db->prepare("SELECT COUNT(id) FROM category");
            $stmt->execute();
            $count = $stmt->fetchColumn();

            $category = $_POST['category'];
            if ($category <= 0 or $category > $count) {
                $errors['category'] = "Неверно выбрана категория";
            }

            $date_public = date("Y-m-d H:i:s");

            if (User::checkUser()) {
                $author = User::getIdByLogin($_COOKIE['login']);
            } else {
                $errors['author'] = "Вы не авторизованы";
            }
            if (!empty($errors)) {
                $dataBack['success'] = false;
                $dataBack['errors'] = $errors;
            } else {
                $stmt = $db->prepare("INSERT into adverts(header,description,category,date_public,author,watches) VALUES(:header,:description,:category,:date_public,:author,:watches)");
                $stmt->execute(array('header' => $header, 'description' => $description, 'category' => $category, 'date_public' => $date_public, 'author' => $author,'watches'=>'0'));
                $id = $db->lastInsertId();
                foreach ($_FILES['upload']['error'] as $key => $error) {

                    if ($_FILES['upload']['size'][$key]>0)
                    {
                        if(($_FILES['upload']['type'][$key] == "image/gif") or ($_FILES['upload']['type'][$key] == "image/png") or ($_FILES['upload']['type'][$key] == "image/jpg") or ($_FILES['upload']['type'][$key] == "image/jpeg"))
                        {

                            $blacklist = array(".php", ".phtml", ".php3", ".php4");
                            foreach ($blacklist as $item)
                            {
                                if(preg_match("/$item\$/i", $_FILES['upload']['name'][$key]))
                                {
                                    echo "Нельзя загружать скрипты. <a href='javascript: history.back(-1);'>назад</a>";
                                    exit;
                                }
                            }

                            $uploaddir = 'app/uploads/';
                            $newFileName=$id."_".$key.".".substr(strrchr($_FILES['upload']['name'][$key], '.'), 1);
                            $upload = $uploaddir.$newFileName;
                            if (move_uploaded_file($_FILES['upload']['tmp_name'][$key], $upload))
                            {

                                $stmt = $db->prepare("INSERT into advert_image(advert,image) VALUES(:advert,:image)");
                                $stmt->execute(array('advert' =>  $id, 'image' => "/".$upload));
                            }
                            else
                                echo "Ошибка загрузки файла. <a href='javascript: history.back(-1);'>назад</a>\n";

                            $newPrevFileName=$id."_".$key.".".substr(strrchr($_FILES['upload']['name'][$key], '.'), 1);

                            self::img_resize($_FILES['upload']['type'][$key], $uploaddir.$id."_".$key.".".substr(strrchr($_FILES['upload']['name'][$key], '.'), 1));
                        }
                        else
                        {
                            echo 'Тип загруженного файла запрещен к загрузке. <a href="javascript: history.back(-1);">назад</a>';
                            exit;
                        }
                    }
                }


                $item = array('id'=>$id, 'header' => $header, 'description' => $description, 'category' => $category, 'date_public' => $date_public, 'author' => $author,'watches'=>0);
                ob_start();
                include_once(ROOT.'/views/advert/item.php');
                $output = ob_get_contents();
                ob_end_clean();
                $dateBack['html'] = $output;
            }
            return $dateBack;
        }
    }

        public static function updateAdvert() {
            $dateBack = array();

            if(isset($_POST))
            {
                $header = $_POST['header'];
                $description = $_POST['description'];
                $category = intval($_POST['category']);
                $id = intval($_POST['id']);
                $db = $GLOBALS['Db']::getConnection();
                file_put_contents('log.txt',".$id.",FILE_APPEND);
                $stmt = $db->prepare("UPDATE adverts SET header=?,description=?,category=? WHERE id=?");
                $stmt->execute(array($header, $description,$category,$id));
                $dateBack['success'] = true;
            }
            return $dateBack;
        }
        public static function getAllAdverts($count)
        {
            $db = $GLOBALS['Db']::getConnection();
            $limit =(int) $GLOBALS['max_posts'];
            $offset = $limit * ((int) $count - 1);
            $adverts = array();
            $sth = $db->prepare('SELECT * FROM adverts ORDER BY date_public DESC LIMIT :limit OFFSET :offset');
            $sth->bindValue(':limit', (int) $limit, PDO::PARAM_INT);
            $sth->bindValue(':offset', (int) $offset, PDO::PARAM_INT);
            $sth->execute();
            $adverts = $sth->fetchAll();
            return $adverts;
        }

        public static function getAdvertsByLogin()
        {
            $db = $GLOBALS['Db']::getConnection();
            $adverts = array();
            if (User::checkUser()) {
                $sth = $db->prepare('SELECT * FROM adverts WHERE author=:author ORDER BY date_public DESC');
                $sth->execute(array("author"=>User::getIdByLogin($_COOKIE['login'])));
                $adverts = $sth->fetchAll();
            }
            return $adverts;
        }

    public static function getAllImagesByID($id) {
        $db = $GLOBALS['Db']::getConnection();
        $images = array();
        $sth = $db->prepare('SELECT * FROM advert_image WHERE advert= :advert');
        $sth->execute(array('advert'=>$id));
        $images = $sth->fetchAll();
        return $images;
    }

    public static function watchDetails() {
        $db = $GLOBALS['Db']::getConnection();
        if (isset($_POST))
        {
            $id = $_POST['id'];
            $stmt = $db->prepare("update adverts set watches = watches + 1 where id=:id");
            $stmt->execute(array('id' => $id));

            return true;
        }
    }


    }



