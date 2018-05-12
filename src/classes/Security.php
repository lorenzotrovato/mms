<?php
	namespace MMS;
	require_once $_SERVER["DOCUMENT_ROOT"].'/src/includes/autoload.php';
	use MMS\Database as DB;
	use MMS\User as User;
	User::init();
	
	class Security{
		
		private static $mysqli;
		
		
		public static function init(){
 	 		self::$mysqli = DB::init();
 	 	}
		
		/**
 		 * @param string $string la stringa da processare
 		 * @param integer $length (optional) eventuale lunghezza massima alla quale va troncata la stringa
 		 * @return string la stringa originale processata con la funzione real_escape_string della classe mysqli
 	 	*/
 		public static function escape($string,$length=0){
			if($length>0){
				$str=substr($string,0,$length);
				return self::$mysqli->getObj()->real_escape_string($str);
			}else{
				return self::$mysqli->getObj()->real_escape_string($string);
			}	
		}
		
		/**
 		 * Verifica se un utente esiste all'interno del database
 		 * @param  string  $data il nome utente o l'email da verificare
 		 * @return boolean se l'utente esiste true, altrimenti false
 	 */
		public static function userExists($data){
			$query_exs= 'SELECT * FROM utente WHERE name="'. self::escape($data) .'" OR mail="'. self::escape($data) .'"';
			return self::$mysqli->numRows($query_exs)>0;
		}
		
		/**
 		 * verifica se è stato effettuato il login
 		 * @return boolean se le variabili di sessione necessarie sono state impostate true, altrimenti false
 	 */
		public static function verSession(){
			if (session_status() == PHP_SESSION_NONE) {
				session_start();
			}
			return self::verAutoLogin();
		}
		
		/**
		 * @return l'utente attualmente autenticato
		 */
		public static function getUserFromSession(){
			$return = false;
			if(isset($_SESSION['user'])){
				$return = new User($_SESSION['user']);
			}
			return $return;
		}
		
		/**
		 * esegue il login verificando i dati
		 * @param string $us l'username o l'email da verificare
		 * @param string $psw la password da verificare
		 * @return string il messaggio del risultato
		 */
		public static function login($us,$psw){
			$us = self::escape($us);
			$users = self::$mysqli->querySelect("SELECT * FROM utente WHERE name = '$us' OR mail = '$us'");
			if(count($users) == 1 && password_verify($psw, $users[0]['pass'])){
				if($users[0]['role']>0){
					self::verSession();
					$_SESSION['user'] = $users[0]['id'];
					if($users[0]['role']>1){
						$msg = 'success-admin';
					}else{
						$msg = 'success';
					}
				}else{
					$msg = 'devi prima verificare l\'indirizzo mail';
				}
			}else{
				$msg = 'i dati inseriti non sono corretti.';
			}
			return $msg;
		}
		
		/**
		 * esegue la registrazione dell'utente
		 * @param string $us il nuovo username
		 * @param string $mail la nuova email
		 * @param string $psw la nuova password
		 * @param int $role l'id del ruolo dell'utente
		 * @return string il messaggio del risultato
		 */
		public static function register($us,$mail,$psw,$role){
			$us = self::escape($us,65);
			$mail = self::escape($mail,255);
			$hashed = password_hash($psw, PASSWORD_DEFAULT);
			$result = User::addUser($us,$mail,$hashed,$role);
			$msg = false;
			switch($result){
				case 'nouser':
					$msg = 'account già esistente.';
					break;
				case 'dberror':
					$msg = 'impossibile stabilire una connessione con il database.';
					break;
				default:
					if($result instanceof User){
						if(self::sendVerMail($result)){
							$msg = 'success';
						}else{
							//$msg = 'impossibile inviare la mail di verifica';
							$msg = var_dump(error_get_last());
						}
					}else{
						$msg = var_dump($result);
					}
					break;
			}
			return $msg;
		}
		
		/**
		 * attiva l'account tramite ilnk della mail
		 * @param $user string il nome utente
		 * @param $key string la chiave di attivazione
		 * @return int 0 se successo, codice di errore altrimenti
		 */
		public static function verMail($user,$key){
			$us = new User(self::escape($user,65));
			if($us->getRole()==0){
				$realKey= hash('sha256',$us->getId() . $us->getName() . $us->getMail() . $us->getPass());
				if($realKey==$key){
					if (session_status() == PHP_SESSION_NONE) {
						session_start();
					}
					$us->setRole(1);
					$us->merge();
					$_SESSION['user'] = $us->getId();
					return 0;
				}else{
					return 1;
				}
			}else{
				return 2;
			}
		}
		
		/**
		 * invia la mail di conferma all'indirizzo registrato
		 * @param User $user l'oggetto utente della registrazione
		 * @return string il messaggio del risultato
		 */
		private static function sendVerMail($user){
			$to = $user->getMail();
			$subject = "Verifica il tuo account Musetek";
			$linkver = "https://musetek.tk/src/includes/router.php?action=vermail&mailuser=" . $user->getId() . "&mailkey=" . hash('sha256',$user->getId() . $user->getName() . $user->getMail() . $user->getPass());
			$message = "
			<html>
			<head>
			<title>Musetek - Verifica account</title>
			</head>
			<body>
			<p>Per accedere al tuo account Musetek, è necessario verificarlo. Clicca <a href='". $linkver ."'>qui</a>
			oppure copia e incolla questo testo nella barra degli indirizzi del browser</p>
			<span>". $linkver ."</span>
			</body>
			</html>
			";
			$headers = "MIME-Version: 1.0" . "\r\n";
			$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
			$headers .= "From: <noreply@musetek.tk>";
			return mail($to,$subject,$message,$headers);
		}
		
		/**
		 * controlla se l'utente ha effettuato l'accesso ed ha i permessi amministratore
		 * @return boolean se l'utente ha i permessi amministratore true, false altrimenti
		 */
		public static function isAdmin(){
			return self::verSession() && self::getUserFromSession()->getRole()>1;
		}
		
		/**
		 * imposta i cookie per il login automatico
		 * @param User $user l'utente a cui attivare il login automatico
		 */
		public static function setAutoLoginCookies($user){
			$loginKey = hash('sha256',$user->getId() . $user->getName() . $user->getPass());
			setcookie('autologin_name',base_convert($user->getId(), 10, 7), time() + (86400 * 30 * 12 * 5), "/");
			setcookie('autologin_key',$loginKey, time() + (86400 * 30 * 12 * 5), "/");
		}
		
		/**
		 * rimuove i cookie di login automatico
		 * @param User $user l'utente a cui disattivare il login automatico
		 */
	 	public static function removeAutoLoginCookies($user){
	 		if(isset($_COOKIE['autologin_name']) && isset($_COOKIE['autologin_key'])){
	 			setcookie('autologin_name',null, time() - 3600, "/");
				setcookie('autologin_key',null, time() - 3600, "/");
	 		}
	 	}
	 	
	 	/**
	 	 * verifica la variabile di sessione oppure i cookie di autologin
	 	 * @return boolean true se verificato, false altrimenti
	 	 */
		public static function verAutoLogin(){
			if(isset($_SESSION['user'])){
				return true;
			}elseif(isset($_COOKIE['autologin_name']) && isset($_COOKIE['autologin_key'])){
				$userId = base_convert($_COOKIE['autologin_name'], 7, 10);
				$verUser = new User($userId);
				if(!is_null($verUser)){
					$verKey = hash('sha256',$verUser->getId() . $verUser->getName() . $verUser->getPass());
					if($verKey == $_COOKIE['autologin_key']){
						if (session_status() == PHP_SESSION_NONE) {
							session_start();
						}
						$_SESSION['user'] = $userId;
						return true;
					}
				}
			}
			return false;
		}
	}
?>
