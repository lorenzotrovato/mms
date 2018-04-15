<?php
	namespace MMS;
	require 'Database.php';
	use MMS\Database as DB;

	class User{

		private $mysqli;

		private $id;
		private $name;
		private $email;
		private $role;

		/**
		 * Costruttore. Le variabili vengono scaricate dal database
		 * @param integer $userId l'identificativo numerico dell'utente a cui fare riferimento sulla relazione utente
		 */
		function __construct($userId){
			$this->mysqli = DB::init();
			$sql = "SELECT * FROM utenti WHERE id = $userId";
			$userData = $this->mysqli->querySelect($sql);
			if(count($userData) == 1){
				$this->id = $userId;
				$this->name = $userData[0]['name'];
				$this->email = $userData[0]['mail'];
				$this->role = $userData[0]['role'];
			}else{
				throw new Exception("ID utente non esistente");
			}
		}

		/**
		 * @return integer l'identificativo numerico
		 */
		function getId(){
			return $this->id;
		}

		/**
		 * @return string il nome
		 */
		function getName(){
			return $this->name;
		}

		/**
		 * @return string l'email
		 */
		function getMail(){
			return $this->email;
		}

		/**
		 * @return integer il ruolo
		 */
		function getRole(){
			return $this->role;
		}

		/**
		 * Modifica la variabile privata $name
		 * @param string $name il nome
		 */
		function setName($name){
			$this->name=$name;
		}

		/**
		 * Modifica la variabile privata $email
		 * @param string $email l'email
		 */
		function setMail($email){
			$this->email=$email;
		}

		/**
		 * Modifica la variabile privata $role
		 * @param integer $role il ruolo
		 */
		function setRole($role){
			$this->role=$role;
		}

		/**
		 * Modifica la password presente sul database
		 * @param string $password la password
		 */
		function setPassword($password){
			$userId = $this->id;
			$hash = password_hash($password, PASSWORD_DEFAULT);
			$this->mysqli->queryDML("UPDATE utenti SET pass = '$hash' WHERE id = $userId");
		}
		
		/**
		 * Sovrascrive i dati presenti sul Database con quelli presenti nell'oggetto
		 * @return mixed se l'operazione è andata a buon fine ritorna il numero di righe affette (integer) altrimenti ritorna l'errore (string)
		 */
		function merge(){
			$sql = "UPDATE utente SET name = '$this->name', mail = '$this->email', role = '$this->role' WHERE id = $this->id";
			return $this->mysqli->queryDML();
		}
	}
?>
