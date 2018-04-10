<?php
	namespace MMS;
	require 'Database.php';
	use MMS\Database as DB;

	class Visit{
		private $mysqli;

		private $id;
		private $name;
		private $startDate;
		private $endDate;
		private $price;
		private $maxSeats;

		public function __construct(){
			$this->mysqli = DB::init();
			$sql = "SELECT * FROM evento WHERE id=0";
			$row = $mysqli->querySelect($sql)[0];

			$this->id = 0;
			$this->name = $row['name'];
			$this->startDate = $row['startDate'];
			$this->endDate = $row['endDate'];
			$this->price = $row['price'];
			$this->maxSeats = $row['maxSeats'];
		}
	}
?>
