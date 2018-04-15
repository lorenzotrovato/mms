<?php
	namespace MMS;
	require 'Database.php';
	require 'TimeSlot.php';
	use MMS\Database as DB;
	use MMS\TimeSlot;
	class Expo{
		private $mysqli;

		private $id;
		private $name;
		private $startDate;
		private $endDate;
		private $price;
		private $maxSeats;

		private $timeSlot;

		/**
		 * Costruttore. crea un nuovo oggetto scaricando le informazioni dal database
		 * @param integer $id la chiave primaria della relazione evento a cui riferirsi sul database
		 */
		public function __construct($id){
			$this->mysqli = DB::init();
			$sql = "SELECT * FROM evento WHERE id=$id";
			$row = $this->mysqli->querySelect($sql)[0];

			$this->id = $id;
			$this->name = $row['name'];
			$this->startDate= $row['startDate'];
			$this->endDate= $row['endDate'];
			$this->price = $row['price'];
			$this->maxSeats = $row['maxSeats'];

			$this->timeSlot = TimeSlot::getSlots($this->id);
		}

		/**
		 * @return integer l'identificativo numerico
		 */
		public function getId(){
			return $this->id;
		}

		/**
		 * @return string il nome
		 */
		public function getName(){
			return $this->name;
		}

		/**
		 * @return float il prezzo
		 */
		public function getPrice(){
			return $this->price;
		}

		/**
		 * @return integer il numero massimo di posti
		 */
		public function getMaxSeats(){
			return $this->maxSeats;
		}

		/**
		 * @return integer la data d'inizio
		 */
		public function getStartDate(){
			return $this->startDate;
		}

		/**
		 * @return integer la data di fine
		 */
		public function getEndDate(){
			return $this->endDate;
		}

		/**
		 * @return array le fascie orarie
		 */
		public function getTimeSlot(){
			return clone($this->timeSlot);
		}

		/**
		 * Modifica la variabile privata $name
		 * @param string $name il nome
		 */
		public function setName($name){
			$this->name = $name;
		}

		/**
		 * Modifica la variabile privata $price
		 * @param float $price il prezzo
		 */
		public function setPrice($price){
			$this->price = $price;
		}

		/**
		 * Modifica la variabile privata maxSeats
		 * @param integer $maxSeats il numero massimo di posti
		 */
		public function setMaxSeats($maxSeats){
			$this->maxSeats = $maxSeats;
		}

		/**
		 * Modifica la variabile privata $startDate
		 * @param integer $startDate la data d'inizio
		 */
		public function setStartDate($startDate){
			$this->startDate = $startDate;
		}

		/**
		 * Modifica la variabile privata $endDate
		 * @param integer $endDate la data di fine
		 */
		public function setEndDate($endDate){
			$this->endDate = $endDate;
		}

		/**
		 * Modifica la variabile privata $timeslot
		 * @param array $timeSlot le fascie orarie
		 */
		public function setTimeSlot($timeSlot){
			$this->timeSlot = clone($timeSlot);
		}

		/**
		 * Sovrascrive i dati presenti sul Database con quelli presenti nell'oggetto
		 * @return mixed se l'operazione è andata a buon fine ritorna il numero di righe affette (integer) altrimenti ritorna l'errore (string)
		 */
		public function merge(){
			$sql = "UPDATE evento SET name='$this->name', startDate='$this->startDate', endDate='$this->endDate', price=$this->price, maxSeats=$this->maxSeats WHERE id=$this->id";
			return $this->mysqli->queryDML($sql);
		}
	}
?>
