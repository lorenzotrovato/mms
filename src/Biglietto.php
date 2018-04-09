<?php
    namespace MMS;
	require 'Database.php';
	use MMS\Database as DB;
	
	class Biglietto{
		private $db;

		private $id;
		private $codUser;
		private $codCat;
		private $codTimeSlot;
		private $datePurchase;
		private $dateValidity;
		private $totalPrice;

		public __construct($id){
			$this->db = DB::init();
			$biglietto = $this->db->querySelect("select * from biglietto where id=".$id);
			$this->id = $biglietto['id'];
			$this->codUser = $biglietto['codUser'];
			$this->codCat = $biglietto['codCat'];
			$this->codTimeSlot = $biglietto['codTimeSlot'];
			$this->datePurchase = $biglietto['datePurchase'];
			$this->dateValidity = $biglietto['dateValidity'];
			$this->totalPrice = $biglietto['totalPrice'];
		}

		public getId(){
		    return $this->id;
		}

		public getCodUser(){
		    return $this->codUser;
		}

		public getCodCat(){
		    return $this->codCat;
		}

		public getCodTimeSlot(){
		    return $this->codTimeSlot;
		}

		public getDatePurchase(){
		    return $this->datePurchase;
		}

		public getDateValidity(){
		    return $this->dateValidity;
		}
		
		public getTotalPrice(){
		    return $this->totalPrice;
		}
	}//class
?>
