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
    }//class
?> 
