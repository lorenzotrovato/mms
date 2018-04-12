<?php
 	namespace MMS;
    require 'Database.php';
	use MMS\Database as DB;
	
	class Accessory{
		private $mysqli;
		
		private $id;
		private $name;
		private $price;
		private $type;
		private $nAvailable;
		private $returnable;
		
		public function __construct($id){
			$this->mysqli = DB::init();
			$acc = $this->mysqli->querySelect('select * from acessorio where id='.$id);
			if (count($acc) == 1){
				$this->id = $acc[0]['id'];
				$this->name = $acc[0]['name'];
				$this->price = $acc[0]['price'];
				$this->type = $acc[0]['type'];
				$this->nAvailable = 0; // messo 0 solo per evitare errori di sintassi con l'IDE che uso. Che famo se è NULL ?
				$this->returnable = $acc[0]['returnable'];
			}else{
				throw new Exception('Id non valido');
			}
		}
		
		public function getID(){
			return $this->id;
		}
		
		public function getName(){
			return $this->name;
		}
		
		public function getPrice(){
			return $this->price;
		}
		
		public function getType(){
			return $this->type;
		}
		
		public function getNAvailable(){
			return $this->nAvailable;
		}
		
		public function getReturnable(){
			return $this->returnable;
		}
		
		public function setName($newName){
			$this->name = $newName;
		}
		
		public function setPrice($newPrice){
			$this->price = $newPrice;
		}
		
		public function setType($newType){
			$this->type = $newType;
		}
		
		public function setNAvailable($newNAvailable){
			$this->nAvailable = $newNAvailable;
		}
		
		public function setReturnable($newReturnable){
			$this->returnable = $newReturnable;
		}
		
		public function merge(){
			$id = $this->id;
			$name = $this->name;
			$price = $this->price;
			$type = $this->type;
			$nAvailable = $this->nAvailable;
			$returnable = $this->returnable;
			return $this->mysqli->queryDML("update accessorio set name='$name', price='$price', type='$type', nAvailable='$nAvailable', returnable='$returnable' where id='$id'") > 0;
		}
	}//class
?>
