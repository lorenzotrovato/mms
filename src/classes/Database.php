<?php

	class Database{
		private static $instance;
		private $mysqli;

		public function __construct(){
			$this->mysqli = new mysqli('localhost','5ia22','5ia22','5ia22');
		}

		public static function init(){
			if(is_null(self::$instance)){
			self::$instance = new Database();
			}
			return self::$instance;
		}

		public function querySelect($query){
			$ret = array();
			try{
				$ris = $this->mysqli->query($query);
				return $ris->fetch_all(MYSQLI_ASSOC);
			}catch(Exception $e){
				return $this->mysqli->error;
			}
		}

		public function queryDML($query){
			try{
				$this->mysqli->query($query);
				return $this->mysqli->affectedRows;
			}catch(Exception $e){
				return $this->mysqli->error;
			}
		}

		public function numRows($query){
			try{
				$ris = $this->mysqli->query($query);
				return $ris->num_rows;
			}catch(Exception $e){
				return $this->mysqli->error;
			}
		}

		public function error(){
			return $this->mysqli->error_list;
		}
	}
?>
