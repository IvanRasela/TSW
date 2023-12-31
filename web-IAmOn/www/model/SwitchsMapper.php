switchsMapper.php

<?php
// file: model/PostMapper.php
require_once(__DIR__."/../core/PDOConnection.php");
require_once(__DIR__."/../model/User.php");
require_once(__DIR__."/../model/switchs.php");

/**
* Class PostMapper
*
* Database interface for Post entities
*
* @author lipido <lipido@gmail.com>
*/
class switchsMapper {

	/**
	* Reference to the PDO connection
	* @var PDO
	*/
	private $db;

	public function __construct() {
		$this->db = PDOConnection::getInstance();
	}

	/**
	* Retrieves all posts
	*
	* Note: Comments are not added to the Post instances
	*
	* @throws PDOException if a database error occurs
	* @return mixed Array of switchs instances (without comments)
	*/
	public function findAll($user) {
		
		$stmt = $this->db->prepare("SELECT * FROM Switchs WHERE Switchs.AliasUser=?");
		$stmt->execute(array($user->getAlias()));
		$switchs_db = $stmt->fetchAll(PDO::FETCH_ASSOC);

		$switchs = array();

		foreach ($switchs_db as $switch) {
			$alias = new User($switch["AliasUser"]);
			array_push($switchs, new switchs($switch["SwitchName"], $switch["Private_UUID"], $switch["Public_UUID"],$alias, $switch["DescriptionSwitch"], $switch["LastTimePowerOn"], $switch["MaxTimePowerOn"]));
		}

		return $switchs;
	}

	public function findIfSuscribe($user) {
		$switchList = [];

		$stmt = $this->db->prepare("SELECT * FROM Suscriptores WHERE Suscriptores.SuscriptorAlias=?");
		$stmt->execute(array($user->getAlias()));
		$switchs_db = $stmt->fetchAll(PDO::FETCH_ASSOC);

		$switchs = array();

		foreach ($switchs_db as $switch){
			$sw = $this->findById($switch["Public_UUID"]);
			$switchList[] = $sw;
		}

		foreach ($switchList as $switch) {
			//$alias = new User($switch->getAliasUser());
			array_push($switchs, new switchs($switch->getSwitchsName(), $switch->getPrivate_UUID(), $switch->getPublic_UUID(),$switch->getAliasUser(), $switch->getDescriptionswitchs(), $switch->getLastTimePowerOn(), $switch->getMaxTimePowerOn()));		}

		return $switchs;
	}

	/**
	* Loads a Post from the database given its id
	*
	* Note: Comments are not added to the Post
	*
	* @throws PDOException if a database error occurs
	* @return Post The switchs instances (without comments). NULL
	* if the Post is not found
	*/
	public function findById($publicuuid){
		$stmt = $this->db->prepare("SELECT * FROM Switchs WHERE Public_UUID=?");
		$stmt->execute(array($publicuuid));
		$switchs = $stmt->fetch(PDO::FETCH_ASSOC);

		if($switchs != null) {
			return new Switchs(
			$switchs["SwitchName"],
			$switchs["Private_UUID"],
			$switchs["Public_UUID"],
			new User($switchs["AliasUser"]),
			$switchs["DescriptionSwitch"],
			$switchs["LastTimePowerOn"],
			$switchs["MaxTimePowerOn"]);
		} else {
			return NULL;
		}
	}

	public function findByIdPrivate($uuid){
		$stmt = $this->db->prepare("SELECT * FROM Switchs WHERE Private_UUID=?");
		$stmt->execute(array($uuid));
		$switchs = $stmt->fetch(PDO::FETCH_ASSOC);

		if($switchs != null) {
			return new Switchs(
			$switchs["SwitchName"],
			$switchs["Private_UUID"],
			$switchs["Public_UUID"],
			new User($switchs["AliasUser"]),
			$switchs["DescriptionSwitch"],
			$switchs["LastTimePowerOn"],
			$switchs["MaxTimePowerOn"]);
		} else {
			return NULL;
		}
	}

	/**
	* Loads a Post from the database given its id
	*
	* It includes all the comments
	*
	* @throws PDOException if a database error occurs
	* @return Post The Post instances (without comments). NULL
	* if the Post is not found
	*/
	public function findByIdWithComments($postid){
		$stmt = $this->db->prepare("SELECT
			P.id as 'post.id',
			P.title as 'post.title',
			P.content as 'post.content',
			P.author as 'post.author',
			C.id as 'comment.id',
			C.content as 'comment.content',
			C.post as 'comment.post',
			C.author as 'comment.author'

			FROM posts P LEFT OUTER JOIN comments C
			ON P.id = C.post
			WHERE
			P.id=? ");

			$stmt->execute(array($postid));
			$post_wt_comments= $stmt->fetchAll(PDO::FETCH_ASSOC);

			if (sizeof($post_wt_comments) > 0) {
				$post = new Post($post_wt_comments[0]["post.id"],
				$post_wt_comments[0]["post.title"],
				$post_wt_comments[0]["post.content"],
				new User($post_wt_comments[0]["post.author"]));
				$comments_array = array();
				if ($post_wt_comments[0]["comment.id"]!=null) {
					foreach ($post_wt_comments as $comment){
						$comment = new Comment( $comment["comment.id"],
						$comment["comment.content"],
						new User($comment["comment.author"]),
						$post);
						array_push($comments_array, $comment);
					}
				}
				$post->setComments($comments_array);

				return $post;
			}else {
				return NULL;
			}
		}

		/**
		* Saves a Post into the database
		*
		* @param Post $post The post to be saved
		* @throws PDOException if a database error occurs
		* @return int The mew post id
		*/
		public function save(Switchs $switchs) {
			$stmt = $this->db->prepare("INSERT INTO Switchs(SwitchName, Private_UUID, Public_UUID, LastTimePowerOn, MaxTimePowerOn, DescriptionSwitch, AliasUser) values (?,?,?,?,?,?,?)");
			$stmt->execute(array($switchs->getswitchsName(), $switchs->getPrivate_UUID(), $switchs->getPublic_UUID(),$switchs->getLastTimePowerOn(),$switchs->getMaxTimePowerOn(),$switchs->getDescriptionswitchs(),$switchs->getAliasUser()->getAlias()));
			return $this->db->lastInsertId();
		}

		public function suscribeTo(Switchs $switch) {
			$stmt = $this->db->prepare("INSERT INTO Suscriptores(SuscriptorAlias, Public_UUID) values (?,?)");
			$stmt->execute(array($switchs->getswitchsName(),$switchs->getPublic_UUID()));
			return $this->db->lastInsertId();
		}

		/**
		* Updates a Post in the database
		*
		* @param Post $post The post to be updated
		* @throws PDOException if a database error occurs
		* @return void
		*/
		public function update(Post $post) {
			$stmt = $this->db->prepare("UPDATE posts set title=?, content=? where id=?");
			$stmt->execute(array($post->getTitle(), $post->getContent(), $post->getId()));
		}

		/**
		* Deletes a Post into the database
		*
		* @param switchs $switchs The switchs to be deleted
		* @throws PDOException if a database error occurs
		* @return void
		*/
		public function delete(Switchs $switchs) {
			$stmt = $this->db->prepare("DELETE from Switchs WHERE Private_UUID=?");
			$stmt->execute(array($switchs->getPrivate_UUID()));
		}

		public function desuscribeTo(Switchs $switchs) {
			$stmt = $this->db->prepare("DELETE from Suscriptores WHERE Public_UUID=?");
			$stmt->execute(array($switchs->getPublic_UUID()));
		}

		public function createUUID(){
			do {
				$miUUID = $this->generarUUID();
			} while ($this->itsOnUse($miUUID));
			
    		return $miUUID;
		}

		public function generarUUID() {
			return sprintf(
				'%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
				mt_rand(0, 0xffff), mt_rand(0, 0xffff),
				mt_rand(0, 0xffff),
				mt_rand(0, 0x0fff) | 0x4000,
				mt_rand(0, 0x3fff) | 0x8000,
				mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
			);
		}

		public function itsOnUse($uuid){
			$stmt = $this->db->prepare("SELECT * FROM Switchs WHERE Public_UUID=?");
			$stmt->execute(array($uuid));
			$switchs = $stmt->fetch(PDO::FETCH_ASSOC);

			$stmt = $this->db->prepare("SELECT * FROM Switchs WHERE Private_UUID=?");
			$stmt->execute(array($uuid));
			$switchs = $stmt->fetch(PDO::FETCH_ASSOC);
			if($switchs != null) {
				return true;
			} else {
				return false;
			}
		}

	}