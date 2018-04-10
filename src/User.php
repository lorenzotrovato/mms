<?php
	namespace MMS;
	require 'Database.php';
	use MMS\Database as DB;

	class User{

		private $mysqli;

		private $id;
		private $name;
		private $mail;
		private $role;

		function __construct($userId){
			$this->mysqli = DB:init();
			$sql = "SELECT * FROM utenti WHERE id = $userId";
			$userData = $this->mysqli->querySelect($sql);
			if(count($userData) == 1){
				$this->id = $userId;
				$this->name = $userData[0]['name'];
				$this->mail = $userData[0]['mail'];
				$this->role = $userData[0]['role'];
			}
		}

		function getId(){

		}
	}
?>
