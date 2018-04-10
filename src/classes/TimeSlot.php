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

		public function __construct($id, $codEvent, $startHour, $minutes, $day){
			$this->mysqli = DB::init();
			if(isset($codEvent) && isset(startHour) && isset($minutes) && isset($day) ){
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

		public static function getSlots($codEvent){
			$slots = array();

			$sql = "SELECT * FROM fasciaoraria WHERE codEvent=$codEvent";
			$rows = $this->mysqli->querySelect($sql);

			foreach($rows as $row){
				$slots[] = new TimeSlot($row['id'], $row['codEvent'], $row['minutes'], $row['day']);
			}

			return $slots;
		}

		public function getId(){
			return $this->id;
		}

		public function getCodEvent(){
			return $this->codEvent;
		}

		public function getStartHour(){
			return $this->startHour;
		}

		public function getMinutes(){
			return $this->minutes;
		}

		public function getDay(){
			return $this->day;
		}

		public function setCodEvent($codEvent){
			$this->codEvent = $codEvent;
		}

		public function setStartHour($startHour){
			$this->startHour = $startHour;
		}

		public function setMinutes($minutes){
			$this->minutes = $minutes;
		}

		public function setDay($day){
			$this->day = $day;
		}

		public function merge(){
			$sql = "UPDATE fasciaoraria SET codEvent=$this->codEvent, startHour=$this->startHour, minutes=$this->minutes, day=$this->day WHERE id=$this->id";
			$res = $this->mysqli->queryDML($sql);
			return $res;
		}
	}
?>
