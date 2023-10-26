<?php
// file: model/UserMapper.php

require_once(__DIR__."/../core/PDOConnection.php");

class UserMapper {

	private $db;

	public function __construct() {
		$this->db = PDOConnection::getInstance();
	}

	public function save($user) {
		$stmt = $this->db->prepare("INSERT INTO users values (?,?)");
		$stmt->execute(array($user->getAlias(), $user->getPasswd(), $user->getEmail()));
	}

	public function aliasExists($alias) {
		$stmt = $this->db->prepare("SELECT count(alias) FROM users where alias=?");
		$stmt->execute(array($alias));

		if ($stmt->fetchColumn() > 0) {
			return true;
		}
	}

	public function isValidUser($alias, $passwd) {
		$stmt = $this->db->prepare("SELECT count(alias) FROM users where alias=? and passwd=?");
		$stmt->execute(array($alias, $passwd));

		if ($stmt->fetchColumn() > 0) {
			return true;
		}
	}
}