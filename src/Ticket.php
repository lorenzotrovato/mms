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
			if (count($biglietto)){
				$this->id = $biglietto[0]['id'];
				$this->codUser = $biglietto[0]['codUser'];
				$this->codCat = new Category($biglietto[0]['codCat']);
				$this->codTimeSlot = $biglietto[0]['codTimeSlot'];
				$this->datePurchase = $biglietto[0]['datePurchase'];
				$this->dateValidity = $biglietto[0]['dateValidity'];
				$this->totalPrice = $biglietto[0]['totalPrice'];
			}else{
				throw new Exception('Id non valido');
			}
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
