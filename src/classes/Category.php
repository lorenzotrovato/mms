<?php
    namespace MMS;
    require_once $_SERVER["DOCUMENT_ROOT"].'/src/includes/autoload.php';
	use MMS\Database as DB;
	use MMS\Security as Security;
    
    class Category{
        private static $mysqli=null;
        
        private $id;
        private $name;
        private $discount;
        private $docType;
        private $priority;
        
        
        /** funzione costruttore
		 * Le variabili vengono scaricate dal database
		 * @param id id della categoria
		 */
        public function __construct($id){
            self::$mysqli = DB::init();
            $cat = self::$mysqli->querySelect('SELECT * FROM categoria WHERE id='.$id);
		    if(count($cat) == 1){
            	$this->id = $cat[0]['id'];
            	$this->name = $cat[0]['name'];
            	$this->discount = $cat[0]['discount'];
            	$this->docType = $cat[0]['docType'];
            	$this->priority = $cat[0]['priority'];
			}else{
				throw new Exception('Id non valido');
			}
        }
        
        public static function init(){
 	 		self::$mysqli = DB::init();
 	 	}
        
        /** funzione getId
         * restituisce l'id della categoria
         * @return $this->id
         */
        public function getId(){
            return $this->id;
        }
        
        /** funzione getName
         * restituisce il nome della categoria
         * @return $this->name
         */
        public function getName(){
            return $this->name;
        }
        
        /** funzione getDiscount
         * restituisce lo sconto della categoria
         * @return $this->discount
         */
        public function getDiscount(){
            return $this->discount;
        }
        
        /** funzione getDocType
         * restituisce il tipo di documento da esibire per dimostrare di rientrare in una categoria
         * @return $this->docType
         */
        public function getDocType(){
            return $this->docType;
        }
        
        public function getPriority(){
            return $this->priority;
        }
        
        /** funzione setName
         * modifica il nome della categoria
         * @param $newName nuovo nome della categoria
         */
        public function setName($newName){
            $this->name = $newName;
        }
        
        /** funzione setDiscount
         * modifica lo sconto applicato alla categoria
         * @param $newDiscount nuovo sconto applicato alla categoria
         */
        public function setDiscount($newDiscount){
            $this->discount = $newDiscount;
        }
        
        /** funzione setDocType
         * modifica il tipo di documento da esibire per dimostrare di rientrare in una categoria
         * @param $newDocType nuovo documento della categoria
         */
        public function setDocType($newDocType){
            $this->docType = $newDocType;
        }
        
        public function setPriority($priority){
            $this->priority = $priority;
        }
        
        /** funzione merge
		 * aggiorna i dati nel database, modificandoli con quelli attuali dell'oggetto
		 * @return true se la modifica è andata a buon fine, false altrimenti
		 */
        public function merge(){
            $id = $this->id;
            $name = $this->name;
            $discount = $this->discount;
            $docType = Security::escape($this->docType);
            $priority = $this->priority;
            return (self::$mysqli->queryDML("UPDATE categoria SET name='$name', discount='$discount', docType='$docType', priority='$priority' where id='$id'") > 0);
        }
        
        /**
         * Aggiunge una nuova categoria al database e ritorna l'oggetto corrispondente
         */
        public static function insCategory($name, $discount, $docType){
            $n = Security::escape($name);
            $s = Security::escape(((is_numeric($discount)) ? $discount : 0));
            $d = Security::escape($docType);
            $sql = "INSERT INTO categoria(name, discount, docType) VALUES ('$n',$s,'$d')";
            if (self::$mysqli->queryDML($sql) > 0){
                return new Category(self::$mysqli->getInsertId());
            }
            return false;
        }
        
        public static function getCategoryList(){
            $sql = "SELECT * FROM categoria WHERE priority >= 0 ORDER by priority, id";
            $rows = self::$mysqli->querySelect($sql);
            $result = array();
            foreach($rows as $cat){
                $result[] = new Category($cat['id']);
            }
            return $result;
        }
        
        public static function getDeletedCategoryList(){
            $sql = "SELECT * FROM categoria WHERE priority < 0 ORDER by priority, id";
            $rows = self::$mysqli->querySelect($sql);
            $result = array();
            foreach($rows as $cat){
                $result[] = new Category($cat['id']);
            }
            return $result;
        }
        
        public static function getCategoryListArray(){
            $sql = "SELECT * FROM categoria WHERE priority >= 0 ORDER by priority, id";
            $rows = self::$mysqli->querySelect($sql);
            $result = array();
            foreach($rows as $cat){
                $result[] = $cat;
            }
            return $result;
        }
        
        public function deleteCategory(){
            $this->priority = -1;
            return $this->merge();
        }
        
        public function activeCategory(){
            $this->priority = 0;
            return $this->merge();
        }
        
        public function updateCategory($name,$discount,$docType){
            $this->name = $name;
            $this->discount = $discount;
            $this->docType = $docType;
            return $this->merge();
        }
    }//class
?>