<?php

namespace login\model;

class UserName {
	const MINIMUM_USERNAME_LENGTH = 3;
	const MAXIMUM_USERNAME_LENGTH = 25; 

	public function __construct($userName) {
		if ($this->isOkUserName($userName) == false) {
			throw new \Exception("UserName::__construct : Tried to create user with faulty username");
		}
		$this->userName = $userName;
	}

	public function __toString() {
		return $this->userName;
	}

	private function isOkUserName($string) {
		if (\Common\Filter::hasTags($string) == true) {
			return false;
		} else if (strlen($string) < self::MINIMUM_USERNAME_LENGTH) {
			return false;
		} else if (strlen($string) > self::MAXIMUM_USERNAME_LENGTH) {
			return false;
		}
		
		return true;
	}
}