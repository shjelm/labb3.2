<?php

namespace login\model;

abstract class TemporaryPassword {

	protected $temporaryPassword;


	public function getTemporaryPassword() {
		return $this->temporaryPassword;
	}
}