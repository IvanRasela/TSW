Switch.php
<?php
// file: model/Post.php

require_once(__DIR__."/../core/ValidationException.php");

/**
* Class Post
*
* Represents a Post in the blog. A Post was written by an
* specific User (author) and contains a list of Comments
*
* @author lipido <lipido@gmail.com>
*/
class Post {

	/**
	* The name of this switch
	* @var string
	*/
	private $SwitchName;

	/**
	* The private uuid
	* @var string
	*/
	private $Private_UUID;

	/**
	* The public uuid
	* @var string
	*/
	private $Public_UUID;

	/**
	* The author of this switch
	* @var User
	*/
	private $AliasUser;

	/**
	* The description of this switch
	* @var string
	*/
	private $DescriptionSwitch;

	/**
	* The state of this switch
	* @var enum
	*/
	private $SwitchState;

	/**
	* The last time power on
	* @var time
	*/
	private $LastTimePowerOn;

	/**
	* The max time power on
	* @var time
	*/
	private $MaxTimePowerOn;

	/**
	* The constructor
	*
	* @param string $SwitchName The name of the switch
	* @param string $Private_UUID The private uuid
	* @param string $Public_UUID The public uuid
	* @param User $AliasUser The alias of user
	* @param string $DescriptionSwitch The description of this switch
	* @param enum $SwitchState The state ot this switch
	* @param time $LastTimePowerOn The last time power on
	* @param time $MaxTimePowerOn The max time power on
	*/
	public function __construct($SwitchName=NULL, $Private_UUID=NULL, $Public_UUID=NULL, User $AliasUser=NULL, $DescriptionSwitch=NULL, enum $SwitchState=NULL, time $LastTimePowerOn=NULL, time $MaxTimePowerOn=NULL) {
		$this->SwitchName = $SwitchName;
		$this->Private_UUID = $Private_UUID;
		$this->Public_UUID = $Public_UUID;
		$this->AliasUser = $AliasUser;
		$this->DescriptionSwitch = $DescriptionSwitch;
		$this->SwitchState = $SwitchState;
		$this->LastTimePowerOn = $LastTimePowerOn;
		$this->MaxTimePowerOn = $MaxTimePowerOn;

	}

	/**
	* Gets the id of this post
	*
	* @return string The id of this post
	*/
	public function getSwitchName() {
		return $this->SwitchName;
	}

	/**
	* Gets the title of this post
	*
	* @return string The title of this post
	*/
	public function getPrivate_UUID() {
		return $this->Private_UUID;
	}

	/**
	* Gets the title of this post
	*
	* @return string The title of this post
	*/
	public function getPublic_UUID() {
		return $this->Public_UUID;
	}

	/**
	* Sets the title of this post
	*
	* @param string $title the title of this post
	* @return void
	*/
	public function setPrivate_UUID($Private_UUID) {
		$this->Private_UUID = $Private_UUID;
	}

	/**
	* Sets the title of this post
	*
	* @param string $title the title of this post
	* @return void
	*/
	public function setPublic_UUID($Public_UUID) {
		$this->Public_UUID = $Public_UUID;
	}

	/**
	* Gets the content of this post
	*
	* @return User The content of this post
	*/
	public function getAliasUser() {
		return $this->AliasUser;
	}

	/**
	* Sets the content of this post
	*
	* @param string $content the content of this post
	* @return void
	*/
	public function setAliasUser(User $AliasUser) {
		$this->AliasUser = $AliasUser;
	}

	/**
	* Gets the author of this post
	*
	* @return string The author of this post
	*/
	public function getDescriptionSwitch() {
		return $this->DescriptionSwitch;
	}

	/**
	* Sets the author of this post
	*
	* @param string $author the author of this post
	* @return void
	*/
	public function setDescriptionSwitch($DescriptionSwitch) {
		$this->DescriptionSwitch = $DescriptionSwitch;
	}

	/**
	* Gets the author of this post
	*
	* @return string The author of this post
	*/
	public function getStateSwitch() {
		return $this->StateSwitch;
	}

	/**
	* Sets the author of this post
	*
	* @param string $author the author of this post
	* @return void
	*/
	public function setStateSwitch($StateSwitch) {
		$this->StateSwitch = $StateSwitch;
	}

	/**
	* Gets the list of comments of this post
	*
	* @return time The list of comments of this post
	*/
	public function getLastTimePowerOn() {
		return $this->LastTimePowerOn;
	}

	/**
	* Sets the comments of the post
	*
	* @param time $comments the comments list of this post
	* @return void
	*/
	public function setLastTimePowerOn(time $LastTimePowerOn) {
		$this->LastTimePowerOn = $LastTimePowerOn;
	}

	/**
	* Gets the list of comments of this post
	*
	* @return time The list of comments of this post
	*/
	public function getMaxTimePowerOn() {
		return $this->MaxTimePowerOn;
	}

	/**
	* Sets the comments of the post
	*
	* @param time $comments the comments list of this post
	* @return void
	*/
	public function setMaxTimePowerOn(time $MaxTimePowerOn) {
		$this->MaxTimePowerOn = $MaxTimePowerOn;
	}

	/**
	* Checks if the current instance is valid
	* for being updated in the database.
	*
	* @throws ValidationException if the instance is
	* not valid
	*
	* @return void
	*/
	public function checkIsValidForCreate() {
		$errors = array();
		if (strlen(trim($this->SwitchName)) == 0 ) {
			$errors["SwitchName"] = "SwitchName is mandatory";
		}

		if (sizeof($errors) > 0){
			throw new ValidationException($errors, "Switch is not valid");
		}
	}

	/**
	* Checks if the current instance is valid
	* for being updated in the database.
	*
	* @throws ValidationException if the instance is
	* not valid
	*
	* @return void
	*/
	public function checkIsValidForUpdate() {
		$errors = array();

		if (!isset($this->id)) {
			$errors["id"] = "id is mandatory";
		}

		try{
			$this->checkIsValidForCreate();
		}catch(ValidationException $ex) {
			foreach ($ex->getErrors() as $key=>$error) {
				$errors[$key] = $error;
			}
		}
		if (sizeof($errors) > 0) {
			throw new ValidationException($errors, "post is not valid");
		}
	}
}