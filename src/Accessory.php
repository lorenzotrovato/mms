<?php
 	namespace MMS;
    require 'Database.php';
	use MMS\Database as DB;
	
	class Accessory{
		private $mysqli;
		
		private $id;
		private $name;
		private $price;
		private $tipe;
		private $nAvailable;
		private $returnable;
		
		public function --construct($id){
			$this->mysqli = DB::init();
			$acc = $this->mysqli->querySelect('select * from acessorio where id='.$id);
			if (count($acc) == 1){
				$this->id = $acc[0]['']
			}else{
				throw new Exception('Id non valido');
			}
		}
	}//class
?>
