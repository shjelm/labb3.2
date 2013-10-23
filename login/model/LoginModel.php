<?php

namespace login\model;

require_once("UserCredentials.php");
require_once("UserList.php");
require_once("LoginInfo.php");
require_once './login/model/LoginDAL.php';


class LoginModel {
	private static $loggedInUser = "LoginModel::loggedInUser";
	
	private $allUsers;
	
	
	public function __construct() {
		assert(isset($_SESSION));
		
		$this->allUsers = new UserList();
	}
	
	public function doLogin(UserCredentials $fromClient, 
							LoginObserver $observer) {

		try {
			$validUser = $this->allUsers->findUser($fromClient);

			$validUser->newTemporaryPassword();

			$this->allUsers->update($validUser);

			$this->setLoggedIn($validUser, $observer);

			$observer->loginOK($validUser->getTemporaryPassword());
		} catch (\Exception $e) {
			\Debug::log("Login Failed", false, $e->getMessage());
			$observer->LoginFailed();
			throw $e;
		}
	}

	public function isLoggedIn() {
		if (isset($_SESSION[self::$loggedInUser])) {

			if ($_SESSION[self::$loggedInUser]->isSameSession()) {
				return true;
			}
		}
		return false;
	}
	public function isAddingMember()
	{
		if(isset($_SESSION["addMember"])){
			return true;
		}
	}
	
	public function setAddMemberSession()
	{
		$_SESSION["addMember"] = true;
	}

	public function getLoggedInUser() {
		return $_SESSION[self::$loggedInUser]->user;
	}
	

	public function doLogout() {
		unset($_SESSION[self::$loggedInUser]);
	}
	
	private function setLoggedIn(UserCredentials $info) {
		$_SESSION[self::$loggedInUser] = new LoginInfo($info);
	}
	
	public function unsetSession()
	{
		unset($_SESSION["addMember"]);
	}
}
