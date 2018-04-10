<?php
	namespace MMS;
	require 'Database.php';
	require 'TimeSlot.php';
	use MMS\Database as DB;

	class Expo{
		private $mysqli;

		private $id;
		private $name;
		private $startDate;
		private $endDate;
		private $price;
		private $maxSeats;

		private $timeSlot;

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

		public function getStartDate(){
			return $this->startDate;
		}

		public function getEndDate(){
			return $this->endDate;
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

		public function setStartDate($startDate){
			$this->startDate = $startDate;
		}

		public function setEndDate($endDate){
			$this->endDate = $endDate;
		}

		public function setTimeSlot($timeSlot){
			$this->timeSlot = clone($timeSlot);
		}

		public function merge(){
			$sql = "UPDATE evento SET name='$this->name', startDate='$this->startDate', endDate='$this->endDate', price=$this->price, maxSeats=$this->maxSeats WHERE id=$this->id";
			$res = $this->mysqli->queryDML($sql);
			return $res;
		}
	}
?>
