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
			}else{
				throw new Exception("ID utente non esistente");
			}
		}
		
		function merge($fields){
			$userId = $this->id;
			$stringUpdate='';
			foreach($fields as $field => $value){
				if($field != 'id'){
					$this->$field = $value;
					$stringUpdate .= "$field = $value, ";
				}
			}
			$stringUpdate = substr($stringUpdate, 0, -2);
			$thid->mysqli->queryDML("UPDATE utenti SET $stringUpdate WHERE id = $userId");
			
		}

		function getId(){
			return $this->id;
		}
		
		function getName(){
			return $this->name;
		}
		
		function getMail(){
			return $this->mail;
		}
		
		function getRole(){
			return 
		}
	}
?>
