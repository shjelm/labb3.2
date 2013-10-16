<?php

namespace login\model;


class TemporaryPasswordClient extends TemporaryPassword{

	private function __construct($cookieString) {
		$this->temporaryPassword = $cookieString;
	}
	
	public static function fromString($cookieString) {
		return new TemporaryPasswordClient($cookieString);
	}
	
	public static function emptyPassword() {
		return new TemporaryPasswordClient("");
	}
}
