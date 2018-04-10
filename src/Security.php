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
			$query_exs= 'SELECT * FROM utenti WHERE name="'. self::escape($username) .'"';
			return self::$mysqli->numRows($query_exs)>0;
		}
		
		public static function verLogin(){
			if (session_status() == PHP_SESSION_NONE) {
				session_start();
			}
			return isset($_SESSION['logged_user']) && $_SESSION['logged_user']!="";
		}
		
		
	}
?>
