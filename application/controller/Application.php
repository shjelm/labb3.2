<?php

namespace application\controller;

require_once("application/view/View.php");
require_once("login/controller/LoginController.php");



class Application {
	private $view;

	private $loginController;
	
	public function __construct() {
		$loginView = new \login\view\LoginView();
		
		$this->loginController = new \login\controller\LoginController($loginView);
		$this->view = new \application\view\View($loginView);
	}
	
	public function doFrontPage() {
		$this->loginController->doToggleLogin();
	
		if ($this->loginController->isLoggedIn()) {
			$loggedInUserCredentials = $this->loginController->getLoggedInUser();
			return $this->view->getLoggedInPage($loggedInUserCredentials);	
		} else if ($this->loginController->isAddingMember()) {
			return $this->view->getAddMemberPage();
		}
		else{
			return $this->view->getLoggedOutPage();
		}
	}
}
