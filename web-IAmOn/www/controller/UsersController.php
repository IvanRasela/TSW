
UsersController.php

<?php

require_once(__DIR__."/../core/ViewManager.php");
//require_once(__DIR__."/../core/I18n.php");
require_once(__DIR__."/../model/User.php");
require_once(__DIR__."/../model/UserMapper.php");

require_once(__DIR__."/../controller/BaseController.php");

/**
* Class UsersController
*
* Controller to login, logout and user registration
*
* @author lipido <lipido@gmail.com>
*/
class UsersController extends BaseController {

	/**
	* Reference to the UserMapper to interact
	* with the database
	*
	* @var UserMapper
	*/
	private $userMapper;

	public function __construct() {
		echo("Dentro del __construct() del UsersController.php ");
		parent::__construct();

		$this->userMapper = new UserMapper();

		// Users controller operates in a "welcome" layout
		// different to the "default" layout where the internal
		// menu is displayed
		$this->view->setLayout("welcome");
	}

	/**
	* Action to login
	*
	* Logins a user checking its creedentials agains
	* the database
	*
	* When called via GET, it shows the login form
	* When called via POST, it tries to login
	*
	* The expected HTTP parameters are:
	* <ul>
	* <li>login: The username (via HTTP POST)</li>
	* <li>passwd: The password (via HTTP POST)</li>
	* </ul>
	*
	* The views are:
	* <ul>
	* <li>posts/login: If this action is reached via HTTP GET (via include)</li>
	* <li>posts/index: If login succeds (via redirect)</li>
	* <li>users/login: If validation fails (via include). Includes these view variables:</li>
	* <ul>
	*	<li>errors: Array including validation errors</li>
	* </ul>
	* </ul>
	*
	* @return void
	*/
	public function login() {
		echo("Dentro de login()");

		if (isset($_POST["alias"])){ // reaching via HTTP Post...
			//process login form
			if ($this->userMapper->isValidUser($_POST["alias"],$_POST["passwd"])) {

				$_SESSION["currentuser"]=$_POST["alias"];
				
				echo("Iniciando sesion");
				// send user to the restricted area (HTTP 302 code)
				$this->view->redirect("switchs", "index");

			}else{
				$errors = array();
				$errors["general"] = "Alias o contraseña no valido/s";
				$this->view->setVariable("errors", $errors);
			}
		}

		$this->view->render("users", "login");
	}

	/**
	* Action to register
	*
	* When called via GET, it shows the register form.
	* When called via POST, it tries to add the user
	* to the database.
	*
	* The expected HTTP parameters are:
	* <ul>
	* <li>login: The username (via HTTP POST)</li>
	* <li>passwd: The password (via HTTP POST)</li>
	* </ul>
	*
	* The views are:
	* <ul>
	* <li>users/register: If this action is reached via HTTP GET (via include)</li>
	* <li>users/login: If login succeds (via redirect)</li>
	* <li>users/register: If validation fails (via include). Includes these view variables:</li>
	* <ul>
	*	<li>user: The current User instance, empty or being added
	*	(but not validated)</li>
	*	<li>errors: Array including validation errors</li>
	* </ul>
	* </ul>
	*
	* @return void
	*/
	public function register() {

		$user = new User();
		echo("Dentro de register()");
		var_dump($_POST);
		if (isset($_POST["alias"]) && isset($_POST["passwd"]) && isset($_POST["email"])){ // reaching via HTTP Post...
			
			// populate the User object with data form the form
			$user->setAlias($_POST["alias"]);
			$user->setPassword($_POST["passwd"]);
			$user->setEmail($_POST["email"]);
			try{
				
				$user->checkIsValidForRegister(); // if it fails, ValidationException

				// check if user exists in the database
				if (!$this->userMapper->aliasExists($_POST["alias"])){

					// save the User object into the database
					$this->userMapper->save($user);

					$this->view->setFlash("Alias ".$user->getAlias()." successfully added. Please login now");

					$this->view->redirect("users", "login");
				} else{
					$errors = array();
					$errors["alias"] = "El alias ya existe";
					$this->view->setVariable("errors", $errors);
				}
			}catch(ValidationException $ex) {
				// Get the errors array inside the exepction...
				$errors = $ex->getErrors();
				// And put it to the view as "errors" variable
				$this->view->setVariable("errors", $errors);
			}
		}

		// Put the User object visible to the view
		$this->view->setVariable("usuario", $user);

		// render the view (/view/users/register.php)
		$this->view->render("users", "Register");

	}

	/**
	* Action to logout
	*
	* This action should be called via GET
	*
	* No HTTP parameters are needed.
	*
	* The views are:
	* <ul>
	* <li>users/login (via redirect)</li>
	* </ul>
	*
	* @return void
	*/
	public function logout() {
		session_destroy();

		// perform a redirection. More or less:
		// header("Location: index.php?controller=users&action=login")
		// die();
		$this->view->redirect("users", "login");

	}

}