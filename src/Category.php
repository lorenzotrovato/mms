<?php
    namespace MMS;
    require 'Database.php';
	use MMS\Database as DB;
    
    class Category{
        private $mysqli;
        
        private $id;
        private $name;
        private $discount;
        private $docType;
        
        public function __construct($id){
            $this->mysqli = DB::init();
            $cat = $this->mysqli->querySelect('select * from categoria where id='.$id);
			if (count($cat) == 1){
            	$this->id = $cat[0]['id'];
            	$this->name = $cat[0]['name'];
            	$this->discount = $cat[0]['discount'];
            	$this->docType = $cat[0]['docType'];
			}else{
				throw new Exception('Id non valido');
			}
        }
        
        public function getId(){
            return $this->id;
        }
        
        public function getName(){
            return $this->name;
        }
        
        public function getDiscount(){
            return $this->discount;
        }
        
        public function getDocType(){
            return $this->docType;
        }
        
        public function setName($newName){
            $this->name = $newName;
        }
        
        public function setDiscount($newDiscount){
            $this->discount = $newDiscount;
        }
        
        public function setDocType($newDocType){
            $this->docType = $newDocType;
        }
        
        public function merge(){
            $id = $this->id;
            $name = $this->name;
            $discount = $this->discount;
            $docType = $this->docType;
            return $this->mysqli->queryDML("update categoria set name='$name',discount='$discount',docType='$docType' where id='$id'") > 0;
        }
    }//class
?>
