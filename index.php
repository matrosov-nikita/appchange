<?php 
ini_set('display_errors',1);
error_reporting(E_ALL);

define('ROOT',dirname(__FILE__));
require_once(ROOT.'/components/Db.php');
$GLOBALS['max_posts']=12;
require_once(ROOT.'/components/router.php');

$time = date('H:i:s:m', time());
file_put_contents("log.txt",'начало запроса'.$time."\r\n",FILE_APPEND);

$router = new Router();
echo $router->run();

$time = date('H:i:s:m', time());
file_put_contents("log.txt",'конец запроса'.$time."\r\n",FILE_APPEND);


?>