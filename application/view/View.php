<?php

namespace application\view;

require_once("common/view/Page.php");
require_once("SwedishDateTimeView.php");



class View {
	private $loginView;

	private $timeView;
	
	private static $REGISTRATE = "addUser";
	
	public function __construct(\login\view\LoginView $loginView) {
		$this->loginView = $loginView;
		$this->timeView = new SwedishDateTimeView();
	}
	
	public function getLoggedOutPage() {
		$html = $this->getHeader(false);
		$reg = $this->getRegistrate();
		$loginBox = $this->loginView->getLoginBox(); 
		
		$html.=$reg;
		$html .= "<h2>Ej Inloggad</h2>
				  	$loginBox
				 ";
		$html .= $this->getFooter();

		return new \common\view\Page("Laboration. Inte inloggad", $html);
	}
	
	public function getLoggedInPage(\login\model\UserCredentials $user) {
		$html = $this->getHeader(true);
		$logoutButton = $this->loginView->getLogoutButton(); 
		$userName = $user->getUserName();
		$html .= "
				<h2>$userName är inloggad</h2>
				 	$logoutButton
				 ";
		$html .= $this->getFooter();

		return new \common\view\Page("Laboration. Inloggad", $html);
	}
	
	public function getAddMemberPage()
	{
		$html = $this->getHeader(true);
		$html .= '
				<h2>Ej inloggad. Registrera användare</h2>
				';
		$html .= $this->loginView->getAddMember($this->loginView->getBack());
		$html .= $this->getFooter();	
		
		return new \common\view\Page("Ej inloggad. Registrera användare", $html);
	}
	
	private function getHeader($isLoggedIn) {
		$ret =  "<h1>Laborationskod xx222aa</h1>";
		return $ret;
		
	}
	
	private function getRegistrate()
	{
		$regString = "<a href='?" . self::$REGISTRATE . "'>Registrera användare</a>";
		return $regString;		
	}
	

	private function getFooter() {
		$timeString = $this->timeView->getTimeString(time());
		return "<p>$timeString<p>";
	}
	
	
	
}
