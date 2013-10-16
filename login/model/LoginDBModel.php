<?php

namespace model;

require_once './login/model/LoginDAL.php';

class LoginDBModel {
	private static $loggedInUser = "LoginModel::loggedInUser";
	
	private $allUsers;
	
	private $DAL;
	
	public function __construct( \model\LoginDAL $dal) {
		
		$this->DAL = $dal;
		
	}
	
	public function saveMember($value='')
	{
		
	}
}