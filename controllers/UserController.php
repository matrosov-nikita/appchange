<?php
include_once ROOT.'/models/User.php';
class UserController
{

	public function actionRegister()
	{
		$res = User::registerUser();
		return $res;
	}
	public function actionEnter() {
		$res = User::enterUser();
		return $res;
	}

	public function actionCheck() {
		$res = User::checkUser();
		return $res;
	}
	public function actionLogout() {
		User::logoutUser();
		header('Location: /get/1');
		return true;
	}

}