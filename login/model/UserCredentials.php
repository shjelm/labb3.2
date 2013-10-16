<?php

namespace login\model;

require_once("TemporaryPassword.php");
require_once("TemporaryPasswordServer.php");
require_once("TemporaryPasswordClient.php");
require_once("UserName.php");
require_once("Password.php");

class UserCredentials {
	
	private $userName; 
	private $password; 
	private $temporaryPassword; 
	

	private function __construct(UserName $userName, 
								 Password $password, 
								 TemporaryPassword $temporaryPassword) {
		$this->userName = $userName;
		$this->password = $password;
		
		
		if ($temporaryPassword != null) {
			$this->temporaryPassword = $temporaryPassword;
		}
	}

	public static function create(UserName $userName, Password $password) {
		return new UserCredentials($userName, $password, new TemporaryPasswordServer());
	}

	public static function createFromClientData(UserName $userName, 
												Password $password) {
		return new UserCredentials($userName, $password, TemporaryPasswordClient::emptyPassword());
	}

	public static function createWithTempPassword(UserName $userName, 
												  TemporaryPasswordClient $temporaryPassword) {
		return new UserCredentials($userName, Password::emptyPassword(), $temporaryPassword);
	}

	
    public static function fromString($string) {
        $unencoded = urldecode($string);
        $parts = explode("<>", $unencoded);
        return new UserCredentials( new UserName($parts[0]), 
        							Password::fromEncryptedString($parts[1]), 
        							TemporaryPasswordServer::fromString($parts[2]));
    }
	
	public function getUserName() {
		return $this->userName;
	}
	
	public function getTemporaryPassword() {
		return $this->temporaryPassword;
	}
	
	public function newTemporaryPassword() {
		$this->temporaryPassword = new TemporaryPasswordServer();
	}
	
	public function isSame(UserCredentials $other) {
		$userNameIsSame = $this->userName == $other->userName;
		$encryptedPWSame = $this->password == $other->password;
		$tempPasswordsMatch =  	$this->temporaryPassword->doMatch($other->temporaryPassword);	
		
		if ($userNameIsSame && ($encryptedPWSame || $tempPasswordsMatch)) {
			return true;
		}
		return false;
	}
	
	public function toString() {
            return urlencode($this->userName . "<>" . 
                       $this->password . "<>" . 
                       $this->temporaryPassword->toString());
    }
    
    
}
