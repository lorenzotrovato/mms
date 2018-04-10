<?php
	namespace MMS;
	require 'Database.php';
	use MMS\Database as DB;

	class Visit{
		private $mysqli;

		private $id;
		private $name;
		private $price;
		private $maxSeats;
		
		private $timeSlot;

		public function __construct(){
			$this->mysqli = DB::init();
			$sql = "SELECT * FROM evento WHERE id=0";
			$row = $this->mysqli->querySelect($sql)[0];

			$this->id = 0;
			$this->name = $row['name'];
			$this->price = $row['price'];
			$this->maxSeats = $row['maxSeats'];
			
			$this->timeSlot = new TimeSlot($this->id);
		}

		public function getId(){
			return $this->id;
		}

		public function getName(){
			return $this->name;
		}

		public function getPrice(){
			return $this->price;
		}

		public function getMaxSeats(){
			return $this->maxSeats;
		}

		public function getTimeSlot(){
			return clone($this->timeSlot);
		}

		public function setName($name){
			$this->name = $name;
		}

		public function setPrice($price){
			$this->price = $price;
		}

		public function setMaxSeats($maxSeats){
			$this->maxSeats = $maxSeats;
		}

		public function setTimeSlot($timeSlot){
			$this->timeSlot = clone($timeSlot);
		}

		public function merge(){
			$sql = "UPDATE evento SET name='$this->name', price=$this->price, maxSeats=$this->maxSeats WHERE id=$this->id";
			$res = $this->mysqli->queryDML($sql);
			return $res;
		}
	}
?>
