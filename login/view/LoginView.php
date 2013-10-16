<?php

namespace login\view;

require_once("./common/Filter.php");
require_once("./login/model/LoginObserver.php");

class LoginView implements \login\model\LoginObserver {

	private static $LOGOUT = "logout";
	private static $LOGIN = "login";	
	private static $REGISTRATE = "addUser";	
	private static $BACK = "back";
	private static $USERNAME = "LoginView::UserName";
	private static $PASSWORD = "LoginView::Password";
	private static $CHECKED = "LoginView::Checked";
	private static $REPEATPASSWORD = "repeatPassword";

	private $message = "";
	
	
	public function getLoginBox() {
		
		 
		$user = $this->getUserName();
		$checked = $this->userWantsToBeRemembered() ? "checked=checked" : "";
		
		$html = "
			<form action='?" . self::$LOGIN . "' method='post' enctype='multipart/form-data'>
				<fieldset>
					$this->message
					<legend>Login - Skriv in användarnamn och lösenord</legend>
					<label for='UserNameID' >Användarnamn :</label>
					<input type='text' size='20' name='" . self::$USERNAME . "' id='UserNameID' value='$user' />
					<label for='PasswordID' >Lösenord  :</label>
					<input type='password' size='20' name='" . self::$PASSWORD . "' id='PasswordID' value='' />
					<label for='AutologinID' >Håll mig inloggad  :</label>
					<input type='checkbox' name='" . self::$CHECKED . "' id='AutologinID' $checked/>
					<input type='submit' name=''  value='Logga in' />
				</fieldset>
			</form>";
			
		return $html;

	}	
	
	public function isLoggingOut() {
		return isset($_GET[self::$LOGOUT]);
	}
	
	public function isLoggingIn() {
		if (isset($_GET[self::$LOGIN])) {
			return true;
		} else if ($this->hasCookies()) {
			return true;
		} else {
			return false;
		}
	}
	
	public function getLogoutButton() {
		return "$this->message <a href='?" . self::$LOGOUT . "'>Logga ut</a>";
	}
	
	public function isAddingMember()
	{
		return isset($_GET[self::$REGISTRATE]);
	}
	
	public function getUserCredentials() {
		if ($this->hasCookies()) {
			return \login\model\UserCredentials::createWithTempPassword(new \login\model\UserName($this->getUserName()), 
																	  $this->getTemporaryPassword());
		} else {
			return \login\model\UserCredentials::createFromClientData(	new \login\model\UserName($this->getUserName()), 
																	\login\model\Password::fromCleartext($this->getPassword()));
		}
	}
	
	public function loginFailed() {
		if ($this->hasCookies()) {
			$this->message = "<p>Felaktig information i cookie</p>";
			$this->removeCookies();
		} else { 
			
			if ($this->getUserName() == "") {
				$this->message .= "<p>Användarnamn saknas</p>";
			} else if ($this->getPassword() == "") {
				$this->message .= "<p>Lösenord saknas</p>";
			} else {
				$this->message = "<p>Felaktigt användarnamn och/eller lösenord</p>";
			}
		}
	}
	
	public function addMemberFailed()
	{
		if($_POST){
			if($this->getUserName() == "" || strlen($this->getUserName()) < 3)
			{
				$this->message ="<p>Användarnamnet är för kort. Minst 3 tecken.</p>";
			}
			if($this->getPassword() == "" || strlen($this->getPassword()) < 6 ){
				$this->message .= "<p>Lösenordet är för kort. Minst 6 tecken.</p>";
			}
			if($this->getPassword() != $this->getRepeatedPassword()){
				$this->message .= "<p>Lösenorden matchar inte.</p>";
			}
			if(isset($_POST[self::$USERNAME])){
				if(\Common\Filter::hasTags($_POST[self::$USERNAME])){
				$this->message .= "<p>Användarnamnet innehåller ogiltiga tecken</p>";
				}
			}
		}
	}
	
	public function addFail()
	{
		if($_POST){
			if($this->getUserName() == "" || strlen($this->getUserName()) < 3 ||$this->getPassword() == "" || strlen($this->getPassword()) < 6 
				|| $this->getPassword() != $this->getRepeatedPassword() )
			{
				return true;
			}
			else if(isset($_POST[self::$USERNAME])){
				if(\Common\Filter::hasTags($_POST[self::$USERNAME])){
					return true;
				}
			}
		}		
	}
	
	
	public function loginOK(\login\model\TemporaryPasswordServer $tempCookie) {

		if ($this->userWantsToBeRemembered() || 
			$this->hasCookies()) {
			if ($this->hasCookies()) {
				$this->message  = "<p>Inloggning lyckades via cookies</p>";
			} else {
				$this->message  = "<p>Inloggning lyckades och vi kommer ihåg dig nästa gång</p>";
			}
			
			$expire = $tempCookie->getExpireDate();
			setcookie(self::$USERNAME, $this->getUserName(), $expire);
			setcookie(self::$PASSWORD, $tempCookie->getTemporaryPassword(), $expire);
		} else {
			$this->message  = "<p>Inloggning lyckades</p>";
		} 
		
	}
	
	public function doLogout() {
		$this->removeCookies();
		
		$this->message  = "<p>Du har nu loggat ut</p>";
	}
	
	public function setMsg()
	{
		$this->message = "<p>Användarnamnet är upptaget.</p>";
	}
	
	public function setAnothertMsg()
	{
		$this->message = "<p>Registrering av ny användare lyckades.</p>";
	}
	
	public function getAddMember($regString)
	{
		$user = $this->getUserName();
		
		$html = $regString."
			<form action='?" . self::$REGISTRATE. "' method='post' enctype='multipart/form-data'>
				<fieldset>
					$this->message
					<legend>Registrera ny användare - Skriv in namn och lösenord</legend>
					<p><label for='UserNameID' >Namn :</label>
					<input type='text' size='20' name='" . self::$USERNAME . "' id='UserNameID' value='$user' /></p>
					<p><label for='PasswordID' >Lösenord  :</label>
					<input type='password' size='20' name='" . self::$PASSWORD . "' id='PasswordID' value='' /></p>
					<p><label for='PasswordID' >Repetera lösenord  :</label>
					<input type='password' size='20' name='" . self::$REPEATPASSWORD . "' id='SecondPasswordID' value='' /></p>
					<input type='submit' name=''  value='Registrera' />
				</fieldset>
			</form>";
			
		return $html;
	}
	
	
	public function getBack()
	{
		$regString = "<a href='?" . self::$BACK . "'>Tillbaka</a>";
		return $regString;
	}
	
	public function getUserName() {
		if (isset($_POST[self::$USERNAME]))
			return \Common\Filter::sanitizeString($_POST[self::$USERNAME]);
		else if (isset($_COOKIE[self::$USERNAME]))
			return \Common\Filter::sanitizeString($_COOKIE[self::$USERNAME]);
		else
			return "";
	}
	
	public function emptyForm()
	{
		if(!$_POST){
			return true;
		}
	}
	public function getPassword() {
		if (isset($_POST[self::$PASSWORD]))
			return \Common\Filter::sanitizeString($_POST[self::$PASSWORD]);
		else
			return "";
	}
	
	
	public function getRepeatedPassword()
	{
		if (isset($_POST[self::$REPEATPASSWORD]))
			return \Common\Filter::sanitizeString($_POST[self::$REPEATPASSWORD]);
		else
			return "";
	}
	
	private function userWantsToBeRemembered() {
		return isset($_POST[self::$CHECKED]);
	}

	private function getTemporaryPassword() {
		if (isset($_COOKIE[self::$PASSWORD])) {
			$fromCookieString = \Common\Filter::sanitizeString($_COOKIE[self::$PASSWORD]);
			return \login\model\TemporaryPasswordClient::fromString($fromCookieString);
		} else {
			return \login\model\TemporaryPasswordClient::emptyPassword();
		}
	}
	
	private function hasCookies() {
		return isset($_COOKIE[self::$PASSWORD]) && isset($_COOKIE[self::$USERNAME]);
	}

	private function removeCookies() {

		unset($_COOKIE[self::$USERNAME]);
		unset($_COOKIE[self::$PASSWORD]);
			
		$expireNow = time()-1;
		setcookie(self::$USERNAME, "", $expireNow);
		setcookie(self::$PASSWORD, "", $expireNow);
	}
}
