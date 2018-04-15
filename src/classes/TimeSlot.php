<?php
	namespace MMS;
	require 'Database.php';
	use MMS\Database as DB;
	class TimeSlot{
		private $mysqli;

		private $id;
		private $codEvent;
		private $startHour;
		private $minutes;
		private $day;

		/**
		 * Costruttore completo. Se viene inserito solamente l'id crea l'oggetto scaricando le informazioni dal database.
		 * @param integer $id        id del record interno alla relazione fasciaoraria
		 * @param integer $codEvent  (optional) codice dell'evento di riferimento
		 * @param integer $startHour (optional) ora di inizio della fascia oraria
		 * @param integer $minutes   (optional) durata della fascia oraria in minuti
		 * @param integer $day       (optional) giorno della settimana (0 < $day < 7) a cui la fascia oraria fa riferimento
		 */
		public function __construct($id, $codEvent, $startHour, $minutes, $day){
			$this->mysqli = DB::init();
			if(!empty($codEvent) && !empty($startHour) && !empty($minutes) && !empty($day)){
				$this->id = $id;
				$this->startHour = $startHour;
				$this->minutes = $minutes;
				$this->day = $day;
			}else{
				$sql = "SELECT * FROM fasciaoraria WHERE id=$id";
				$row = $this->mysqli->querySelect($sql)[0];

				$this->codEvent = $row['codEvent'];
				$this->startHour = $row['startHour'];
				$this->minutes = $row['minutes'];
				$this->day = $row['day'];
			}
		}

		/**
		 * @param  integer $codEvent il codice dell'evento
		 * @return array un array di fascieorarie che fanno riferimento all'evento inserito
		 */
		public static function getSlots($codEvent){
			$slots = array();

			$sql = "SELECT * FROM fasciaoraria WHERE codEvent=$codEvent";
			$rows = $this->mysqli->querySelect($sql);

			foreach($rows as $row){
				$slots[] = new TimeSlot($row['id'], $row['codEvent'], $row['minutes'], $row['day']);
			}

			return $slots;
		}

		/**
		 * @return integer l'identificativo numerico
		 */
		public function getId(){
			return $this->id;
		}

		/**
		 * @return integer il codice dell'evento a cui fa riferimento
		 */
		public function getCodEvent(){
			return $this->codEvent;
		}

		/**
		 * @return integer l'ora d'inizio
		 */
		public function getStartHour(){
			return $this->startHour;
		}

		/**
		 * @return integer la durata in minuti
		 */
		public function getMinutes(){
			return $this->minutes;
		}

		/**
		 * @return integer il giorno della settimana in cui è valida la fascia oraria
		 */
		public function getDay(){
			return $this->day;
		}

		/**
		 * Modifica la variabile privata $codEvent
		 * @param integer $codEvent il codice dell'evento a cui fa riferimento
		 */
		public function setCodEvent($codEvent){
			$this->codEvent = $codEvent;
		}

		/**
		 * Modifica la variabile privata $startHour
		 * @param integer $startHour l'ora d'inizio
		 */
		public function setStartHour($startHour){
			$this->startHour = $startHour;
		}

		/**
		 * Modifica la variabile privata $minutes
		 * @param integer $minutes la durata in minuti
		 */
		public function setMinutes($minutes){
			$this->minutes = $minutes;
		}

		/**
		 * Modifica la variabile privata $day
		 * @param integer $day il giorno della settimana in cui è valida la fascia oraria
		 */
		public function setDay($day){
			$this->day = $day;
		}

		/**
		 * Sovrascrive i dati presenti sul Database con quelli presenti nell'oggetto
		 * @return mixed se l'operazione è andata a buon fine ritorna il numero di righe affette (integer) altrimenti ritorna l'errore (string)
		 */
		public function merge(){
			$sql = "UPDATE fasciaoraria SET codEvent=$this->codEvent, startHour=$this->startHour, minutes=$this->minutes, day=$this->day WHERE id=$this->id";
			return $this->mysqli->queryDML($sql);
		}
	}
?>
