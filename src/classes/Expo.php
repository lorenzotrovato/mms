<?php
	namespace MMS;
	require_once $_SERVER["DOCUMENT_ROOT"].'/src/includes/autoload.php';
	use MMS\Expo as Expo;
	use MMS\Database as DB;
	use MMS\TimeSlot as TimeSlot;
	use MMS\Security as Security;
	class Expo{
		private static $mysqli=null;

		private $id;
		private $name;
		private $description;
		private $startDate;
		private $endDate;
		private $price;
		private $maxSeats;

		private $timeSlots;

		/**
		 * Costruttore. crea un nuovo oggetto scaricando le informazioni dal database
		 * @param integer $id la chiave primaria della relazione evento a cui riferirsi sul database
		 */
		public function __construct($id){
			if(is_null(self::$mysqli)){
				self::$mysqli = DB::init();
			}
			$sql = "SELECT * FROM evento WHERE id=$id";
			$row = self::$mysqli->querySelect($sql)[0];

			$this->id = $id;
			$this->name = $row['name'];
			$this->description = $row['description'];
			$this->startDate= $row['startDate'];
			$this->endDate= $row['endDate'];
			$this->price = $row['price'];
			$this->maxSeats = $row['maxSeats'];
			TimeSlot::init();
			$this->timeSlots = TimeSlot::getSlots($id);
		}

		public static function init(){
 	 		self::$mysqli = DB::init();
 	 	}
 	 	
 	 	/** aggiunge un'esposizione al database
 	 	 * @param string $n il nome dell'esposizione
 	 	 * @param string $ds la descrizione dell'esposizione
 	 	 * @param date $sd la data di inizio
 	 	 * @param date $ed la data di fine
 	 	 * @param float $pr il prezzo del biglietto base per l'esposizione
 	 	 * @param int $ms il numero massimo di posti
 	 	 * @return Expo l'oggetto esposizione se viene aggiunto correttamente, altrimenti string l'errore dell'SQL
 	 	 */
 	 	public static function addExpo($n,$ds,$sd,$ed,$pr,$ms){
 	 		self::init();
 	 		Security::init();
 	 		//controllo campi
 	 		$n = Security::escape($n,63);
 	 		$ds = Security::escape($ds);
 	 		$sd = Security::escape($sd);
 	 		$ed = Security::escape($ed);
 	 		$pr = floatval(Security::escape($pr));
 	 		$ms = intval(Security::escape($ms));
 	 		//controllo valori date
 	 		if(strtotime($sd)>strtotime($ed)){
 	 			$tmp = $sd;
 	 			$sd = $ed;
 	 			$ed = $tmp;
 	 		}
 	 		//creazione query
 	 		$queryExpoIns = 'INSERT INTO evento (name, description, startDate, endDate, price, maxSeats) VALUES ("'.$n.'","'.$ds.'","'.$sd.'","'.$ed.'","'.$pr.'","'.abs($ms).'")';
 	 		//esecuzione query
 	 		if(self::$mysqli->queryDML($queryExpoIns)==1){
 	 			return new Expo(self::$mysqli->getInsertId());
 	 		}else{
 	 			return print_r(self::$mysqli->error());
 	 		}
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
		 * @return string la descrizione
		 */
		public function getDescription(){
			return $this->description;
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
		 * @return array la copia dell'array delle fascie orarie
		 */
		public function getTimeSlots(){
			return (new \ArrayIterator($this->timeSlots))->getArrayCopy();
		}

		/**
		 * Modifica la variabile privata $name
		 * @param string $name il nome
		 */
		public function setName($name){
			$this->name = $name;
		}
		
		/**
		 * Modifica la variabile privata $name
		 * @param string $name il nome
		 */
		public function setDescription($description){
			$this->description = $description;
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
			return self::$mysqli->queryDML($sql);
		}
		
		public static function getVisit(){
			$sql = "SELECT * FROM evento WHERE id == 0";
			$row = self::$mysqli->querySelect($sql)[0];
			return $row;
		}
		
		public static function getExpoList(){
			$sql = "SELECT * FROM evento WHERE id <> 0 ORDER BY startDate, endDate DESC";
			$rows = self::$mysqli->querySelect($sql);
			$result = array();
			foreach($rows as $event){
				$result []= new Expo($event['id']);
			}
			return $result;
		}
	}
?>
