<?php

namespace login\controller;

require_once("./login/model/LoginModel.php");
require_once("./login/view/LoginView.php");
require_once './login/model/UserCredentials.php';
require_once("./login/model/LoginDAL.php");

class LoginController {
	
	private $model;

	private $view;
	
	private $array;
	
	private $DAL;
	
	private $db;
	
	private $mysqli;
	
	public function __construct(\login\view\LoginView $view) {
		$this->model = new \login\model\LoginModel();
		$this->view = $view;
		$this->mysqli = new \mysqli("localhost", "root", "", "usersLabb3");
	}
	
	
	public function isLoggedIn() {
		return $this->model->isLoggedIn();
	}
	
	public function getLoggedInUser() {
		return $this->model->getLoggedInUser();
	}
	public function isAddingMember()
	{
		return $this->model->isAddingMember();
	}
	public function doToggleLogin() {
		$users = array();
		$this->DAL = new \model\LoginDAL($this->mysqli);
					
		if($this->addUser())
		{
			$userName = $this->view->getUserName();	
			$password = $this->view->getPassword();					

			$this->model->unsetSession();
			$this->view->addMemberFailed();
				
			if(!$this->view->addFail() && !$this->view->emptyForm() && !$this->exist())	{				
				$this->DAL->addUser($this->view->getUserName(), $this->view->getPassword());
				$this->view->setAnothertMsg();
			}				
			
		}
		if ($this->model->isLoggedIn()) {
			\Debug::log("We are logged in");
			if ($this->view->isLoggingOut() ) {
				$this->model->doLogout();
				$this->view->doLogout();
				\Debug::log("We logged out");
			}
		} else {
			\Debug::log("We are not logged in");
			if ($this->view->isLoggingIn() ) {
				try {
					$credentials = $this->view->getUserCredentials();
					$this->model->doLogin($credentials, $this->view);
					\Debug::log("Login succeded");
				} catch (\Exception $e) {
					\Debug::log("Login failed", false, $e->getMessage());
					$this->view->LoginFailed();
				}
			}
		}
	}
		public function exist($value='')
			{
				
				$users = $this->DAL->getUsers($this->view->getUserName());
				for($i = 0; $i<count($users); $i++)
			{
				if($this->view->getUserName() == $users[$i] || $this->view->getUserName()=="Admin")
				{
					$this->view->setMsg();
					return true;
					
				}
			}
			}
			
	public function addUser()
	{
		$this->model->setAddMemberSession();
		return $this->view->isAddingMember();
	}
}
