<?php

namespace login\model;

class Password {
	const MINIMUM_PASSWORD_CHARACTERS = 6;
	const MAXIMUM_PASSWORD_CHARACTERS = 16;

	private $encryptedPassword;

	private function __construct() {

	}

	public static function fromEncryptedString($encryptedPassword) {
		$ret = new Password();
		$ret->encryptedPassword = $encryptedPassword;
		return $ret;	
		
	}

	public static function fromCleartext($cleartext) {
		if (self::isOkPassword($cleartext) == true ) {
			$ret = new Password();
			$ret->encryptedPassword = $ret->encryptPassword($cleartext, "salty salt");
			return $ret;
		} 
		throw new \Exception("Tried to create user with faulty password");
	}

	public static function emptyPassword() {
		return new Password();
	}

	public function __toString() {
		return $this->encryptedPassword;
	}

	private static function isOkPassword($string) {
		
		if (strlen($string) < self::MINIMUM_PASSWORD_CHARACTERS) {
			return false;
		} else if (strlen($string) > self::MAXIMUM_PASSWORD_CHARACTERS) {
			return false;
		}
		return true;
	}
	
	private function encryptPassword($rawPassword, $salt) {
		return sha1($rawPassword . "Password_SALT");
	}
}