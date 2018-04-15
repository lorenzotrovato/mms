<?php
namespace MMS;
	
	require 'Database.php';
	require 'User.php';
	use MMS\Database as DB;
	use MMS\User as User;
	
	class Security{
		
		private static $mysqli = DB::init();
		
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
		 * @param  string  $username l'utente da verificare
		 * @return boolean se l'utente esiste true, altrimenti false
		 */
		public static function userExists($username){
			$query_exs= 'SELECT * FROM utenti WHERE name="'. self::escape($username) .'"';
			return self::$mysqli->numRows($query_exs)>0;
		}
		
		/**
		 * verifica se è stato effettuato il login
		 * @return boolean se le variabili di sessione necessarie sono state impostate true, altrimenti false
		 */
		public static function verLogin(){
			if (session_status() == PHP_SESSION_NONE) {
				session_start();
			}
			return isset($_SESSION['logged_user']) && $_SESSION['logged_user']!="";
		}
	}
?>
