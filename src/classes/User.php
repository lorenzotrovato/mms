<?php
	namespace MMS;
	require_once $_SERVER["DOCUMENT_ROOT"].'/src/includes/autoload.php';
	use MMS\Database as DB;
	use MMS\Ticket as Ticket;
	use MMS\Security as Security;
	class User{

		private static $mysqli;

		private $id;
		private $name;
		private $email;
		private $role;
		private $pwd;

		/**
		 * Costruttore. Le variabili vengono scaricate dal database
		 * @param integer $userId l'identificativo numerico dell'utente a cui fare riferimento sulla relazione utente
		 */
		function __construct($userId){
			self::$mysqli = DB::init();
			$sql = "SELECT * FROM utente WHERE id = $userId";
			$userData = self::$mysqli->querySelect($sql);
			if($userData){
				$this->id = $userId;
				$this->name = $userData[0]['name'];
				$this->email = $userData[0]['mail'];
				$this->role = $userData[0]['role'];
				$this->pwd = $userData[0]['pass'];
			}else{
				throw new Exception("ID utente non esistente");
			}
		}
		
		public static function init(){
			self::$mysqli = DB::init();
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
		 * @return string l'hash della password (bcrypt)
		 */
		function getPass(){
			return $this->pwd;
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
			$userId =Security::escape($this->id);
			$hash = Security::escape(password_hash($password, PASSWORD_DEFAULT));
			self::$mysqli->queryDML("UPDATE utente SET pass = '$hash' WHERE id = $userId");
			$this->pwd = $hash;
		}
		
		/**
		 * Sovrascrive i dati presenti sul Database con quelli presenti nell'oggetto
		 * @return mixed se l'operazione è andata a buon fine ritorna il numero di righe affette (integer) altrimenti ritorna l'errore (string)
		 */
		function merge(){
			$name=Security::escape($this->name);
			$email=Security::escape($this->email);
			$role=Security::escape($this->role);
			$id=Security::escape($this->id);
			$sql = "UPDATE utente SET name = '$name', mail = '$email', role = '$role' WHERE id = $id";
			return self::$mysqli->queryDML($sql);
		}
		
		/**
		 * aggiunge un nuovo utente al database
		 * @param string $newuser nuovo username da inserire
		 * @param string $newmail nuova email da inserire
		 * @param string $newpass nuova password criptata
		 * @param int $newrole nuovo ruolo dell'utente
		 * @return mixed se l'inserimento dovesse riuscire il nuovo utente, atrimenti false
		 */
		public static function addUser($newuser,$newmail,$newpass,$newrole){
			$msg = 'nouser';
			if(!Security::userExists($newuser)){
				$query_insert = "INSERT INTO utente (name,mail,pass,role) VALUES ('$newuser','$newmail','$newpass','$newrole')";
				if(self::$mysqli->queryDML($query_insert)>0){
					$msg = new User(self::$mysqli->getInsertId());
				}else{
					$msg = self::$mysqli->error();
				}
			}			
			return $msg;
		}
		
		/**
		 * @return array User la lista degli utenti
		 */
		public static function getUserList(){
			self::init();
			$list = self::$mysqli->querySelect('SELECT * FROM utente');
			$ret = array();
			for($i=0;$i<count($list);$i++){
				array_push($ret, new User($list[$i]['id']));
			}
			return $ret;
		}
		
		/**
		 * @return array un array dei biglietti acquistati dall'utente
		 */
		public function getUserTickets(){
			$tickets = array();
			$allts = self::$mysqli->querySelect('SELECT * FROM biglietto WHERE codUser="'.$this->id.' AND codTimeSlot IN (SELECT id FROM fasciaoraria)"');
			Ticket::init();
			for($i=0;$i<count($allts);$i++){
				try{
					array_push($tickets,new Ticket($allts[$i]['id']));
				}catch(Exception $e){
					
				}
			}
			//var_dump($tickets);
			return $tickets;
		}
	}
?>
