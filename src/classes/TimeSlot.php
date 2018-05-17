<?php
	namespace MMS;
	require_once $_SERVER["DOCUMENT_ROOT"].'/src/includes/autoload.php';
	use MMS\Database as DB;
	use MMS\Security as Security;
	class TimeSlot{
		private static $mysqli=null;

		private $id;
		private $codEvent;
		private $startHour;
		private $minutes;
		private $day;

		/**
		 * Costruttore completo. Se viene inserito solamente l'id crea l'oggetto scaricando le informazioni dal database.
		 * @param integer $id        id del record interno alla relazione fasciaoraria
		 * @param integer $codEvent  (optional) codice dell'evento di riferimento
		 * @param string  $startHour (optional) ora di inizio della fascia oraria
		 * @param integer $minutes   (optional) durata della fascia oraria in minuti
		 * @param integer $day       (optional) giorno della settimana (0 < $day < 7) a cui la fascia oraria fa riferimento
		 */
		public function __construct($id, $codEvent=null, $startHour=null, $minutes=null, $day=null){
			if(is_null(self::$mysqli)){
				self::$mysqli = DB::init();
			}
			if(!empty($codEvent) && !is_null($startHour) && !is_null($minutes) && !is_null($day)){
				$this->id = $id;
				$this->startHour = $startHour;
				$this->minutes = $minutes;
				$this->day = $day;
			}else{
				$sql = "SELECT * FROM fasciaoraria WHERE id=$id";
				$row = self::$mysqli->querySelect($sql);
				if(count($row) > 0){
					$this->id = $row[0]['id'];
					//var_dump($row);
					$this->codEvent = $row[0]['codEvent'];
					$this->startHour = $row[0]['startHour'];
					$this->minutes = $row[0]['minutes'];
					$this->day = $row[0]['day'];
				}else{
					throw new \Exception("ID timeslot non esistente");
				}
			}
		}
		
		public static function init(){
			self::$mysqli = DB::init();
			Security::init();
		}

		/**
		 * @param  integer $codEvent il codice dell'evento
		 * @return array un array di fascieorarie che fanno riferimento all'evento inserito
		 */
		public static function getSlots($codEvent){
			$slots = array(1 => array(), 2 => array(), 3 => array(), 4 => array(), 5 => array(), 6 => array(), 7 => array());
			$sql = "SELECT id, codEvent, startHour, minutes, day FROM fasciaoraria WHERE codEvent = $codEvent AND minutes > 0 ORDER BY day, startHour, minutes";
			$rows = self::$mysqli->querySelect($sql);
			/*var_dump($sql);
			var_dump($rows);
			var_dump(self::$mysqli->error());*/
			foreach($rows as $row){
				$slots[$row['day']][] = new TimeSlot($row['id'], $row['codEvent'], $row['startHour'], $row['minutes'], $row['day']);
			}

			return $slots;
		}
		
		/** aggiunge una fascia oraria
		 * @param integer $codEvent il codice dell'evento
		 * @param string $startHour l'orario di inizio nel formato 0h:0m
		 * @param integer $minutes i minuti di durata
		 * @param integer $day il numero del giorno della settimana (1 lun - 7 dom)
		 */
		public static function addSlot($codEvent,$startHour,$minutes,$day){
			$codEvent = intval($codEvent);
			$startHour = Security::escape($startHour,5);
			$minutes = abs(intval($minutes));
			$day = abs(intval($day));
			$queryInsert = 'INSERT INTO fasciaoraria (codEvent,startHour,minutes,day) VALUES ("'.$codEvent.'","'.$startHour.'","'.$minutes.'","'.$day.'")';
			if(self::$mysqli->queryDML($queryInsert)==1){
				return new TimeSlot(self::$mysqli->getInsertId());
 	 		}else{
 	 			return print_r(self::$mysqli->error());
 	 		}
		}
		
		/** rimuove una fascia oraria
		 * ATTENZIONE!! ELIMINARE SOLO SE NON CI SONO BIGLIETTI COLLEGATI (usare hasTickets())
		 * @return boolean true se va a buon fine
		 */
	 	public function deleteSlot(){
	 		return self::$mysqli->queryDML('DELETE FROM fasciaoraria WHERE id="'.$this->id.'"') == 1;
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
		 * @return date l'orario d'inizio
		 */
		public function getStartHour(){
			return date('H:i', strtotime($this->startHour));
		}
		
		/**
		 * @return date l'orario di fine
		 */
		public function getEndHour(){
			return date('H:i', strtotime($this->startHour.' +'.$this->minutes.' minutes'));
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
		 * @param string Data in cui controllare il numero di posti occupati
		 * @return integer il numero di posti occupati
		 */
		public function getOccupiedSeats($date){
			$date=Security::escape($date);
			$sql = "SELECT count(*) as num FROM biglietto WHERE codTimeSlot = ".$this->id." AND dateValidity = '$date'";
			$rows = self::$mysqli->querySelect($sql);
			return $rows[0]['num'];
		}
		
		/**
		 * @return boolean true se sono collegati biglietti alla fascia oraria
		 */
		public function hasTickets(){
			$queryBiglietti = 'SELECT count(*) as nbiglietti FROM biglietto WHERE codTimeSlot = "'.$this->id.'"';
			return self::$mysqli->querySelect($queryBiglietti)[0]['nbiglietti']>0;
		}
		
		public function getDays(){
			
		}
		
		public function getArray(){
			return array('id' => $this->id, 'startHour' => $this->getStartHour(), 'endHour' => $this->getEndHour());	
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
		 * @param string $startHour l'ora d'inizio
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
			$sql = "UPDATE fasciaoraria SET codEvent=$this->codEvent, startHour='$this->startHour', minutes=$this->minutes, day=$this->day WHERE id=$this->id";
			return self::$mysqli->queryDML($sql);
			//return $sql;
		}
	}
?>
