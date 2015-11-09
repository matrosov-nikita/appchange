<?php
	class DataBase {
		public static $db;
		public function __construct()
		{
			$paramsPath = ROOT.'/config/db_config.php';
			$params = include($paramsPath);
			$dsn = "mysql:host={$params['host']};dbname={$params['dbname']}";
			$opt = array(
				PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
				PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
			);
			$dbh = new PDO($dsn, $params['user'],$params['password'],$opt);
			self::$db =  $dbh;
		}
		public static function getConnection()
		{
			return self::$db;

		}
//		private function pdoSet($fields, &$values, $source = array()) {
//			$set = '';
//			$values = array();
//			if (!$source) $source = &$_POST;
//			foreach ($fields as $field) {
//				if (isset($source[$field])) {
//					$set.="`".str_replace("`","``",$field)."`". "=:$field, ";
//					$values[$field] = $source[$field];
//				}
//			}
//			return substr($set, 0, -2);
//		}
//		public static function insert($table_name,$fields, $values)
//		{
//			$sql = "insert into $table_name SET ".pdoSet($fields,$values);
//			$stm = getConnection()->prepare($sql);
//			$stm->execute($values);
//		}
	

	}

$GLOBALS['Db'] = new DataBase();

?>