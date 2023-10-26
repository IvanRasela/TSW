<?php
// file: model/User.php

require_once(__DIR__."/../core/ValidationException.php");

class User {

	private $alias;
	private $passwd;
    private $email;

	public function __construct($alias=NULL, $passwd=NULL, $email=NULL) {
		$this->alias = $alias;
		$this->passwd = $passwd;
        $this->email = $email;
	}

	public function getAlias() {
		return $this->alias;
	}

	public function setAlias($alias) {
		$this->alias = $alias;
	}

	public function getPasswd() {
		return $this->passwd;
	}

	public function setPassword($passwd) {
		$this->passwd = $passwd;
	}

    public function getEmail() {
		return $this->email;
	}

    public function setEmail($email) {
		$this->email = $email;
	}

	public function checkIsValidForRegister() {
		$errors = array();
		if (strlen($this->alias) < 5) {
			$errors["alias"] = "alias must be at least 5 characters length";

		}
		if (strlen($this->passwd) < 5) {
			$errors["passwd"] = "Password must be at least 5 characters length";
		}
		if (sizeof($errors)>0){
			throw new ValidationException($errors, "user is not valid");
		}
	}
}