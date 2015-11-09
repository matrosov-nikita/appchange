<?php
class User {

	public static function  logoutUser() {
		if(self::checkUser()) {
			setcookie("login",'',time()-300);
			setcookie("password",'',time()-300);
		}
	}

	public static function getIdByLogin($login) {
		$db = $GLOBALS['Db']::getConnection();
			$stmt = $db->prepare("SELECT id FROM users where login= :login");
			$stmt->execute(array('login'=>$login));
			$data = $stmt->fetch(PDO::FETCH_LAZY);
			return $data['id'];

	}
	public static function getLoginById($id) {
		$db = $GLOBALS['Db']::getConnection();
		$stmt = $db->prepare("SELECT login FROM users where id= :id");
		$stmt->execute(array('id'=>$id));
		$data = $stmt->fetch(PDO::FETCH_LAZY);
		return $data['login'];
	}
	public static function checkUser() {
		$db = $GLOBALS['Db']::getConnection();
		if (isset($_COOKIE['login']))
		{
			$stmt = $db->prepare("SELECT * FROM users WHERE login= :login LIMIT 1");
			$stmt->execute(array('login'=>$_COOKIE['login']));
			while ($row = $stmt->fetch(PDO::FETCH_LAZY)) {
				if ($row ['login'] == $_COOKIE['login']) {
					return true;
				}
			}
			return false;
		}
		else
		{
			return false;
		}
	}



	public static function enterUser() {
		$db = $GLOBALS['Db']::getConnection();
		$dataBack = array();
		if (isset($_POST))
		{

			$stmt = $db->prepare("SELECT id,login, password FROM users where login= :login");
			$stmt->execute(array('login'=>$_POST['login']));
			$data = $stmt->fetch(PDO::FETCH_LAZY);
			if ($data['password']==md5(md5($_POST['password'])))
			{
				setcookie("login",$data['login'],time()+30*24*60*60);
				setcookie( "password",$data['password'],time()+30*24*60*60);
				$dataBack['success']  = true;
			}
			else {
				$dataBack['success']  = false;
			}

		}
		return $dataBack;
	}

	public static function registerUser() {
		$db = $GLOBALS['Db']::getConnection();
		$errors = array();
		$dataBack = array();

		if (isset($_POST)) {
			//проверка логина
			if(!preg_match("/^[a-zA-Z0-9]+$/",$_POST['login']))
			{
				$errors['login'] = "Логин может состоять только из букв английского алфавита и цифр";
			}
			if(strlen($_POST['login']) < 3 or strlen($_POST['login']) > 30)
			{
				$errors['login'] = "Логин должен быть не меньше 3-х символов и не больше 30";
			}
			//проверка пароля
			if(strlen($_POST['password']) < 5)
			{
				$errors['password'] = "Пароль должен быть не меньше 5 символов";
			}

			//проверка e-mail

			if (!preg_match("/^([a-z0-9_-]+\.)*[a-z0-9_-]+@[a-z0-9_-]+(\.[a-z0-9_-]+)*\.[a-z]{2,6}$/",$_POST['email']))
			{
				$errors['email'] = "Некорректный email";
			}


			//проверка существования пользователя с таким же логином
			$stmt = $db->prepare("SELECT COUNT(id) FROM users WHERE login= :login");
			$stmt->execute(array('login'=>$_POST['login']));

			if($stmt->fetchColumn() > 0)
			{
				$errors['login'] = "Пользователь с таким логином уже существует в базе данных";
			}

			if(!empty($errors))
			{
				$dataBack['success'] = false;
				$dataBack['errors']  = $errors;
			}
			else
			{
				# двойное шифрование пароля
				$login = $_POST['login'];
				$password = md5(md5($_POST['password']));
				$email = $_POST['email'];
				$company_name = $_POST['company_name'];
				$stmt = $db->prepare("INSERT into users(login,password,email,company_name) VALUES(:login,:password,:email,:company_name)");
				$stmt->execute(array('login'=>	$login,'password'=>$password,'email'=>$email,'company_name'=>$company_name));
				setcookie("login",$login,time()+30*24*60*60);
				setcookie( "password",$password,time()+30*24*60*60);
				$dataBack['success'] = true;
				$dataBack['message'] = "Регистрация прошла успешно";
			}

		}

		return json_encode($dataBack);
	}

}