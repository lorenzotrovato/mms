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
            $this->id = $cat['id'];
            $this->name = $cat['name'];
            $this->discount = $cat['discount'];
            $this->docType = $cat['docType'];
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
