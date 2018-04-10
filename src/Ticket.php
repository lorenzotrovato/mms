<?php
    namespace MMS;
	require 'Database.php';
	require 'Category.php';
	use MMS\Database as DB;
	use MMS\Category; 
	
	class Ticket{
		private $mysqli;

		private $id;
		private $codUser;
		private $codCat;
		private $codTimeSlot;
		private $datePurchase;
		private $dateValidity;
		private $totalPrice;

		public function __construct($id){
			$this->mysqli = DB::init();
			$biglietto = $this->mysqli->querySelect('select * from biglietto where id='.$id);
			$this->id = $biglietto['id'];
			$this->codUser = $biglietto['codUser'];
			$this->codCat = new Category($biglietto['codCat']);
			$this->codTimeSlot = $biglietto['codTimeSlot'];
			$this->datePurchase = $biglietto['datePurchase'];
			$this->dateValidity = $biglietto['dateValidity'];
			$this->totalPrice = $biglietto['totalPrice'];
		}

		public function getId(){
		    return $this->id;
		}

		public function getCodUser(){
		    return $this->codUser;
		}

		public function getCodCat(){
		    return $this->codCat;
		}

		public function getCodTimeSlot(){
		    return $this->codTimeSlot;
		}

		public function getDatePurchase(){
		    return $this->datePurchase;
		}

		public function getDateValidity(){
		    return $this->dateValidity;
		}
		
		public function getTotalPrice(){
		    return $this->totalPrice;
		}
	}//class
?>
