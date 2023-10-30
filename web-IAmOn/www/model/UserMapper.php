UserMapper.php

<?php
// file: model/UserMapper.php

require_once(__DIR__."/../core/PDOConnection.php");

/**
* Class UserMapper
*
* Database interface for User entities
*
* @author lipido <lipido@gmail.com>
*/
class UserMapper {

	/**
	* Reference to the PDO connection
	* @var PDO
	*/
	private $db;

	public function __construct() {
		$this->db = PDOConnection::getInstance();
	}


	/**
	* Saves a User into the database
	*
	* @param User $user The user to be saved
	* @throws PDOException if a database error occurs
	* @return void
	*/
	public function save($user) {
		$stmt = $this->db->prepare("INSERT INTO usuario values (?,?,?)");
		$stmt->execute(array($user->getAlias(), $user->getPasswd()), $user->getEmail());
	}

	/**
	* Checks if a given username is already in the database
	*
	* @param string $username the username to check
	* @return boolean true if the username exists, false otherwise
	*/
	public function aliasExists($alias) {
		$stmt = $this->db->prepare("SELECT count(alias) FROM usuario where alias=?");
		$stmt->execute(array($alias));

		if ($stmt->fetchColumn() > 0) {
			return true;
		}
	}

	public function emailExists($email) {
		$stmt = $this->db->prepare("SELECT count(email) FROM usuario where email=?");
		$stmt->execute(array($email));

		if ($stmt->fetchColumn() > 0) {
			return true;
		}
		//En php, si no se especifica un valor de retorno, la función
		//	devolverá automáticamente null al final de su ejecución. 
	}



	/**
	* Checks if a given pair of alias/password/email exists in the database
	*
	* @param string $username the username
	* @param string $passwd the password
	* @return boolean true the username/passwrod exists, false otherwise.
	*/
	public function isValidUser($alias, $passwd, $email) {
		$stmt = $this->db->prepare("SELECT count(alias) FROM usuario where alias=? and passwd=? and email=?");
		$stmt->execute(array($username, $passwd, $email));

		if ($stmt->fetchColumn() > 0) {
			return true;
		}
	}
}