<?php
namespace MMS;
	
	require 'Database.php';
	require 'User.php';
	use MMS\Database as DB;
	use MMS\User as User;
	
	class Security{
		
		private static $mysqli = DB::init();
		
		
		public static function escape($string,$length=0){
			if($length>0){
				$str=substr($string,0,$length);
				return self::$mysqli->getObj()->real_escape_string($str);
			}else{
				return self::$mysqli->getObj()->real_escape_string($string);
			}	
		}
		
		public static function userExists($username){
			$query_exs= 'SELECT * FROM utente WHERE name="'. self::escape($username) .'"';
			return self::$mysqli->numRows($query_exs)>0;
		}
		
		public static function verSession(){
			if (session_status() == PHP_SESSION_NONE) {
				session_start();
			}
			return isset($_SESSION['logged_id']) && $_SESSION['logged_id']!="";
		}
		
		public function getUserFromSession(){
			return new User($_SESSION['logged_id']);
		}
		
		public static function login($us,$psw){
			$us = self::escape($us);
			$users = self::$mysqli->querySelect("SELECT id, name, mail, pass FROM utente WHERE name = $us OR mail = $us");
			if(count($users) == 1){
				if(password_verify($psw, $users[0]['pass'])){
					if (session_status() == PHP_SESSION_NONE) {
						session_start();
					}
					$_SESSION['logged_id'] = $users[0]['id'];
					$_SESSION['logged_user'] = $us;
					return new User($users[0]['id']);
				}else{
					return null;
				}
			}else {
				return null;
			}
		}
		
		public static function register($us,$mail,$psw,$role){
			$us = self::escape($us,65);
			$mail = self::escape($mail,255);
			$hashed = password_hash($psw, PASSWORD_DEFAULT);
			$newuser = User::addUser($us,$mail,$hashed,$role);
			if(!is_null($newuser)){
				return $newuser;
			}
		}
		
	}
?>
