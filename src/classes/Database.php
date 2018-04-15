<?php

	class Database{
		private static $instance;
		private $mysqli;

		/**
		 * Costruttore vuoto. Crea la connessione con il database
		 */
		public function __construct(){
			$this->mysqli = new mysqli('localhost','5ia22','5ia22','5ia22');
		}

		/**
		 * @return Database se una connessione è già stata istanziata ritorna l'istanza già presente, altrimenti ne istanza una nuova e la ritorna.
		 */
		public static function init(){
			if(is_null(self::$instance)){
			self::$instance = new Database();
			}
			return self::$instance;
		}

		/**
		 * @param  string $query la query in linguaggio SQL di tipo SELECT
		 * @return mixed se l'operazione è andata a buon fine ritorna un array bidimensionale (array) altrimenti ritorna l'errore (string)
		 */
		public function querySelect($query){
			try{
				$ris = $this->mysqli->query($query);
				return $ris->fetch_all(MYSQLI_ASSOC);
			}catch(Exception $e){
				return $this->mysqli->error;
			}
		}

		/**
		 * @param  string $query la query in linguaggio SQL di tipo DML
		 * @return mixed se l'operazione è andata a buon fine ritorna il numero di righe affette (integer) altrimenti ritorna l'errore (string)
		 */
		public function queryDML($query){
			try{
				$this->mysqli->query($query);
				return $this->mysqli->affectedRows;
			}catch(Exception $e){
				return $this->mysqli->error;
			}
		}

		/**
		 * @param  string $query la query in linguaggio SQL
		 * @return mixed se non ci sono errori il numero di righe ritornate dalla query (integer), altrimenti l'errore (string)
		 */
		public function numRows($query){
			try{
				$ris = $this->mysqli->query($query);
				return $ris->num_rows;
			}catch(Exception $e){
				return $this->mysqli->error;
			}
		}

		/**
		 * @return array una lista di errori, ogni elemento è un array associativo contenente errno, error, e sqlstate.
		 */
		public function error(){
			return $this->mysqli->error_list;
		}

		/**
		 * @param  string $raw la stringa da processare
		 * @return string la stringa originale processata con la funzione real_escape_string della classe mysqli
		 */
		public function escape($raw){
			return $this->mysqli->real_escape_string($raw);
		}
	}
?>
