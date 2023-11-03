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
		
		$stmt = $this->db->query("SELECT * FROM Switchs WHERE Switchs.AliasUser=?");
		$stmt->execute(array($user));
		$switchs_db = $stmt->fetch(PDO::FETCH_ASSOC);

		$switchs = array();

		foreach ($switchs_db as $switch) {
			$alias = new User($switch["AliasUser"]);
			array_push($switchs, new switchs($switch["SwitchName"], $switch["Private_UUID"], $switch["Public_UUID"],$alias, $switch["DescriptionSwitch"], $switch["LastTimePowerOn"], $switch["MaxTimePowerOn"]));
		}

		return $switchs;
	}

	public function findIfSuscribe() {
		$stmt = $this->db->query("SELECT * FROM Suscritos, usuario WHERE usuario.Alias = Suscritos.SuscriptorAlias");
		$switchs_db = $stmt->fetchAll(PDO::FETCH_ASSOC);

		$switchs = array();

		foreach ($switchs_db as $switch) {
			$alias = new User($switch["alias"]);
			array_push($switchs, new switchs($switch["SwitchName"], $switch["Private_UUID"], $switch["Public_UUID"],$alias, $switch["DescriptionSwitch"], $switch["LastTimePowerOn"], $switch["MaxTimePowerOn"]));		}

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
			return $switchs;
		} else {
			return NULL;
		}
	}

	public function findByIdPrivate($uuid){
		$stmt = $this->db->prepare("SELECT * FROM Switchs WHERE PrivateUUID=?");
		$stmt->execute(array($uuid));
		$switchs = $stmt->fetch(PDO::FETCH_ASSOC);

		if($switchs != null) {
			return $switchs;
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
		public function save(switchs $switchs) {
			$stmt = $this->db->prepare("INSERT INTO switchs(switchsName, Private_UUID, Public_UUID, LastTimePowerOn, MaxTimePowerOn, Descriptionswitchs, AliasUser) values (?,?,?,?,?,?,?)");
			$stmt->execute(array($switchs->getswitchsName(), $switchs->getPrivate_UUID(), $switchs->getPublic_UUID(),$switchs->getLastTimePowerOn(),$switchs->getMaxTimePowerOn(),$switchs->getDescriptionSwicth(),$switchs->getAliasUser()->getAlias()));
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
			$stmt = $this->db->prepare("DELETE from Switchs WHERE SwitchName=?");
			$stmt->execute(array($switchs->getSwitchsName()));
		}

		public function createUUID(){
			do{
				$uuid4 = Uuid::uuid4();
			} while (itsOnUse($uuid4));
			
    		return $uuid4->toString();
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