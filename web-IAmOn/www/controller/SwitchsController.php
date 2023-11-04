SwitchsController.php

<?php
//file: controller/PostController.php
require_once(__DIR__."/../model/switchs.php");
require_once(__DIR__."/../model/switchsMapper.php");
require_once(__DIR__."/../model/User.php");

require_once(__DIR__."/../core/ViewManager.php");
require_once(__DIR__."/../controller/BaseController.php");

/**
* Class PostsController
*
* Controller to make a CRUDL of Posts entities
*
* @author lipido <lipido@gmail.com>
*/
class switchsController extends BaseController {

	/**
	* Reference to the switchsMapper to interact
	* with the database
	*
	* @var switchsMapper
	*/
	private $switchsMapper;

	public function __construct() {
		echo("Dentro del constructor del switchsController");
		parent::__construct();

		$this->switchsMapper = new switchsMapper();
	}

	/**
	* Action to list posts
	*
	* Loads all the posts from the database.
	* No HTTP parameters are needed.
	*
	* The views are:
	* <ul>
	* <li>posts/index (via include)</li>
	* </ul>
	*/
	public function index() {

		echo("Dentro de la action index de switchsController.php ");
		print_r($this->currentUser->getAlias());

		if (!isset($this->currentUser)) {
			throw new Exception("Not in session. Adding posts requires login");
		}
		// obtain the data from the database
		$switchs = $this->switchsMapper->findAll($this->currentUser);

		// put the array containing Post object to the view
		$this->view->setVariable("Switchs", $switchs);

		// render the view (/view/posts/index.php)
		$this->view->render("posts", "index");
	}

	/**
	* Action to view a given post
	*
	* This action should only be called via GET
	*
	* The expected HTTP parameters are:
	* <ul>
	* <li>id: Id of the post (via HTTP GET)</li>
	* </ul>
	*
	* The views are:
	* <ul>
	* <li>posts/view: If post is successfully loaded (via include).	Includes these view variables:</li>
	* <ul>
	*	<li>post: The current Post retrieved</li>
	*	<li>comment: The current Comment instance, empty or
	*	being added (but not validated)</li>
	* </ul>
	* </ul>
	*
	* @throws Exception If no such post of the given id is found
	* @return void
	*
	*/
	/*COMPLETAR*/ 
	public function view(){
		$user = $this->currentUser->getAlias();

		if (!isset($_GET["SwitchName"])) {
			throw new Exception("id is mandatory");
		}

		$switchsPK = $_GET["Public_UUID"];

		// find the Post object in the database
		$switchs = $this->SwitchsMapper->findAll($user);

		if ($switchs == NULL) {
			throw new Exception("no such post with id: ".$switchs);
		}

		// put the Post object to the view
		$this->view->setVariable("switchs", $switchs);

		// check if comment is already on the view (for example as flash variable)
		// if not, put an empty Comment for the view
		//$comment = $this->view->getVariable("comment");
		//$this->view->setVariable("comment", ($comment==NULL)?new Comment():$comment);

		// render the view (/view/posts/view.php)
		$this->view->render("switchs", "view");

	}

	/**
	* Action to add a new post
	*
	* When called via GET, it shows the add form
	* When called via POST, it adds the post to the
	* database
	*
	* The expected HTTP parameters are:
	* <ul>
	* <li>title: Title of the post (via HTTP POST)</li>
	* <li>content: Content of the post (via HTTP POST)</li>
	* </ul>
	*
	* The views are:
	* <ul>
	* <li>posts/add: If this action is reached via HTTP GET (via include)</li>
	* <li>posts/index: If post was successfully added (via redirect)</li>
	* <li>posts/add: If validation fails (via include). Includes these view variables:</li>
	* <ul>
	*	<li>post: The current Post instance, empty or
	*	being added (but not validated)</li>
	*	<li>errors: Array including per-field validation errors</li>
	* </ul>
	* </ul>
	* @throws Exception if no user is in session
	* @return void
	*/
	public function add() {
		if (!isset($this->currentUser)) {
			throw new Exception("Not in session. Adding switchs requires login");
		}

		$switch = new Switchs();

		if (isset($_POST["submit"])) { // reaching via HTTP Post...

			// populate the Post object with data form the form
			$switch->setSwitchsName($_POST["SwitchName"]);
			$switch->setDescriptionswitchs($_POST["Description"]);

			// The user of the Post is the currentUser (user in session)
			$switch->setAliasUser($this->currentUser);

			$publicuuid = $this->switchsMapper->createUUID();
			$switch->setPublic_UUID($publicuuid);
			$privateuuid = $this->switchsMapper->createUUID();
			$switch->setPrivate_UUID($privateuuid);
			$maxtime = 120;
			$switch->setMaxTimePowerOn($maxtime);
			$lasttime = null;
			$switch->setLastTimePowerOn($lasttime);

			try {
				// validate Post object
				$switch->checkIsValidForCreate(); // if it fails, ValidationException

				// save the Post object into the database
				$this->switchsMapper->save($switch);

				// POST-REDIRECT-GET
				// Everything OK, we will redirect the user to the list of posts
				// We want to see a message after redirection, so we establish
				// a "flash" message (which is simply a Session variable) to be
				// get in the view after redirection.
				$this->view->setFlash(sprintf(("Switch \"%s\" successfully added."),$switch ->getSwitchsName()));

				// perform the redirection. More or less:
				// header("Location: index.php?controller=posts&action=index")
				// die();
				$this->view->redirect("switchs", "index");

			}catch(ValidationException $ex) {
				// Get the errors array inside the exepction...
				$errors = $ex->getErrors();
				// And put it to the view as "errors" variable
				$this->view->setVariable("errors", $errors);
			}
		}

		// Put the Post object visible to the view
		$this->view->setVariable("switchs", $switch);

		// render the view (/view/posts/add.php)
		$this->view->render("posts", "add");

	}

	/**
	* Action to edit a post
	*
	* When called via GET, it shows an edit form
	* including the current data of the Post.
	* When called via POST, it modifies the post in the
	* database.
	*
	* The expected HTTP parameters are:
	* <ul>
	* <li>id: Id of the post (via HTTP POST and GET)</li>
	* <li>title: Title of the post (via HTTP POST)</li>
	* <li>content: Content of the post (via HTTP POST)</li>
	* </ul>
	*
	* The views are:
	* <ul>
	* <li>posts/edit: If this action is reached via HTTP GET (via include)</li>
	* <li>posts/index: If post was successfully edited (via redirect)</li>
	* <li>posts/edit: If validation fails (via include). Includes these view variables:</li>
	* <ul>
	*	<li>post: The current Post instance, empty or being added (but not validated)</li>
	*	<li>errors: Array including per-field validation errors</li>
	* </ul>
	* </ul>
	* @throws Exception if no id was provided
	* @throws Exception if no user is in session
	* @throws Exception if there is not any post with the provided id
	* @throws Exception if the current logged user is not the author of the post
	* @return void
	*/
	public function edit() {
		if (!isset($_REQUEST["Public_UUID"])) {
			throw new Exception("A Public UUID is mandatory");
		}

		if (!isset($this->currentUser)) {
			throw new Exception("Not in session. Editing posts requires login");
		}


		// Get the Post object from the database
		$suscribeuuid = $_REQUEST["Public_UUID"];
		$switchs = $this->SwitchsMapper->findById($suscribeuuid);

		// Does the post exist?
		if ($switchs == NULL) {
			throw new Exception("no such switch with id: ".$suscribeuuid);
		}

		// Check if the Post author is the currentUser (in Session)
		if ($switchs->getAliasUser() == $this->currentUser) {
			throw new Exception("u cant suscribe to your own switch".$suscribeuuid);
		}

		if (isset($_POST["submit"])) { // reaching via HTTP Post...

			// Agregarlo a un array de Switches del usuario

			try {
				// validate Post object
				$post->checkIsValidForUpdate(); // if it fails, ValidationException

				// update the Post object in the database
				$this->postMapper->update($post);

				// POST-REDIRECT-GET
				// Everything OK, we will redirect the user to the list of posts
				// We want to see a message after redirection, so we establish
				// a "flash" message (which is simply a Session variable) to be
				// get in the view after redirection.
				$this->view->setFlash(sprintf(i18n("Post \"%s\" successfully updated."),$post ->getTitle()));

				// perform the redirection. More or less:
				// header("Location: index.php?controller=posts&action=index")
				// die();
				$this->view->redirect("Switchs", "index");

			}catch(ValidationException $ex) {
				// Get the errors array inside the exepction...
				$errors = $ex->getErrors();
				// And put it to the view as "errors" variable
				$this->view->setVariable("errors", $errors);
			}
		}

		// Put the Post object visible to the view
		$this->view->setVariable("post", $post);

		// render the view (/view/posts/add.php)
		$this->view->render("posts", "edit");
	}

	public function suscribe() {
		if (!isset($_REQUEST["Public_UUID"])) {
			throw new Exception("A Public UUID is mandatory");
		}

		if (!isset($this->currentUser)) {
			throw new Exception("Not in session. Editing posts requires login");
		}


		// Get the Post object from the database
		$suscribeuuid = $_REQUEST["Public_UUID"];
		$switchs = $this->SwitchsMapper->findById($suscribeuuid);

		// Does the post exist?
		if ($switchs == NULL) {
			throw new Exception("no such switch with id: ".$suscribeuuid);
		}

		// Check if the Post author is the currentUser (in Session)
		if ($switchs->getAliasUser() == $this->currentUser) {
			throw new Exception("u cant suscribe to your own switch".$suscribeuuid);
		}

		if (isset($_POST["submit"])) { // reaching via HTTP Post...

			// Agregarlo a un array de Switches del usuario

			try {
				// validate Post object
				$post->checkIsValidForUpdate(); // if it fails, ValidationException

				// update the Post object in the database
				$this->postMapper->update($post);

				// POST-REDIRECT-GET
				// Everything OK, we will redirect the user to the list of posts
				// We want to see a message after redirection, so we establish
				// a "flash" message (which is simply a Session variable) to be
				// get in the view after redirection.
				$this->view->setFlash(sprintf(i18n("Post \"%s\" successfully updated."),$post ->getTitle()));

				// perform the redirection. More or less:
				// header("Location: index.php?controller=posts&action=index")
				// die();
				$this->view->redirect("Switchs", "index");

			}catch(ValidationException $ex) {
				// Get the errors array inside the exepction...
				$errors = $ex->getErrors();
				// And put it to the view as "errors" variable
				$this->view->setVariable("errors", $errors);
			}
		}

		// Put the Post object visible to the view
		$this->view->setVariable("post", $post);

		// render the view (/view/posts/add.php)
		$this->view->render("posts", "edit");
	}

	/**
	* Action to delete a post
	*
	* This action should only be called via HTTP POST
	*
	* The expected HTTP parameters are:
	* <ul>
	* <li>id: Id of the post (via HTTP POST)</li>
	* </ul>
	*
	* The views are:
	* <ul>
	* <li>posts/index: If post was successfully deleted (via redirect)</li>
	* </ul>
	* @throws Exception if no id was provided
	* @throws Exception if no user is in session
	* @throws Exception if there is not any post with the provided id
	* @throws Exception if the author of the post to be deleted is not the current user
	* @return void
	*/
	public function delete() {
		if (!isset($_POST["Private_UUID"])) {
			throw new Exception("Private_UUID is mandatory");
		}
		if (!isset($this->currentUser)) {
			throw new Exception("Not in session. Delete switchs requires login");
		}
		
		// Get the Post object from the database
		$switchid = $_REQUEST["Private_UUID"];
		$switch = $this->switchsMapper->findByIdPrivate($switchid);

		// Does the post exist?
		if ($switch == NULL) {
			throw new Exception("no such switch with Private_UUID: ".$switchid);
		}

		// Check if the Post author is the currentUser (in Session)
		/*if ($switch->getAliasUser()->getAlias() != $this->currentUser) {
			throw new Exception("Switch owner is not the logged user");
		}*/

		// Delete the Post object from the database
		$this->switchsMapper->delete($switch);

		// POST-REDIRECT-GET
		// Everything OK, we will redirect the user to the list of posts
		// We want to see a message after redirection, so we establish
		// a "flash" message (which is simply a Session variable) to be
		// get in the view after redirection.
		$this->view->setFlash(sprintf(("Switch \"%s\" successfully deleted."),$switch->getSwitchsName()));

		// perform the redirection. More or less:
		// header("Location: index.php?controller=posts&action=index")
		// die();
		$this->view->redirect("Switchs", "index");

	}
}