<?php

namespace login\model;

class LoginInfo {
	public $user;

	public $ipAdress;

	public $userAgent;

	
	public function __construct(UserCredentials $user) {
		$this->user = $user;
		$this->ipAdress = $ipAdress = $_SERVER["REMOTE_ADDR"];
		$this->userAgent = $userAgent = $_SERVER["HTTP_USER_AGENT"];
	}

	public function isSameSession() {
		if($this->ipAdress != $_SERVER["REMOTE_ADDR"] || 
		   $this->userAgent != $_SERVER["HTTP_USER_AGENT"]) {

			\Debug::log("wrong ip or adress, session hijacking? $ipAdress $userAgent");
			$time = time();
			error_log("Session hijacking attempt at $time, $ipAdress $userAgent", 0, "log.txt");
			return false;
		}

		return true;
	}
}