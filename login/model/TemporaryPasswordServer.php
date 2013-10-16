<?php

namespace login\model;

class TemporaryPasswordServer extends TemporaryPassword {
	
	protected $temporaryPassword;
	
	private $expireDate;
	
	
	public function __construct() {
		$this->expireDate = time() + 60*60*24*30; 
		$this->temporaryPassword = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 40);
	}

	public function getExpireDate() {
		return $this->expireDate;
	}

	public function toString() {
		return "$this->temporaryPassword><$this->expireDate";
	}
	
	public static function fromString($serverString) {
		$ret = new TemporaryPasswordServer();
		$parts = explode("><", $serverString);
		$ret->temporaryPassword = $parts[0];
		$ret->expireDate = $parts[1];
		return $ret;
	}
		
	public function doMatch(TemporaryPasswordClient $fromCookie) {
		
		if (time() > $this->expireDate) {
			\Debug::log("cookie expired ");
			return false;
		}
		
		if (strcmp($this->temporaryPassword, $fromCookie->temporaryPassword) != 0){
			\Debug::log("passwords not matching [$this->temporaryPassword] && [$fromCookie->temporaryPassword]");
			return false;
		}
		
		return true;
	}
}
