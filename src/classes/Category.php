<?php
    namespace MMS;
    require_once $_SERVER["DOCUMENT_ROOT"].'/src/includes/autoload.php';
	use MMS\Database as DB;
    
    class Category{
        private static $mysqli=null;
        
        private $id;
        private $name;
        private $discount;
        private $docType;
        
        
        /** funzione costruttore
		 * Le variabili vengono scaricate dal database
		 * @param id id della categoria
		 */
        public function __construct($id){
            self::$mysqli = DB::init();
            $cat = self::$mysqli->querySelect('select * from categoria where id='.$id);
			if (count($cat) == 1){
            	$this->id = $cat[0]['id'];
            	$this->name = $cat[0]['name'];
            	$this->discount = $cat[0]['discount'];
            	$this->docType = $cat[0]['docType'];
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
        
        /** funzione merge
		 * aggiorna i dati nel database, modificandoli con quelli attuali dell'oggetto
		 * @return true se la modifica è andata a buon fine, false altrimenti
		 */
        public function merge(){
            $id = $this->id;
            $name = $this->name;
            $discount = $this->discount;
            $docType = $this->docType;
            return self::$mysqli->queryDML("update categoria set name='$name',discount='$discount',docType='$docType' where id='$id'") > 0;
        }
        
        /**
         * Aggiunge una nuova categoria al database e ritorna l'oggetto corrispondente
         */
        public static function insCategory($name, $discount, $docType){
            $db = DB::init();
            $n = $db->getObj()->real_escape_string($name);
            $s = ((is_numeric($discount)) ? $discount : 0);
            $d = $db->getObj()->real_escape_string($docType);
            $sql = "INSERT INTO categoria(name, discount, docType) VALUES ('$n',$s,'$d')";
            if ($db->queryDML($sql) > 0){
                return new Category($db->getInsertId());
            }
            return false;
        }
        
        public static function getCategoryList(){
            $sql = "SELECT * FROM categoria ORDER by priority";
            $rows = self::$mysqli->querySelect($sql);
            $result = array();
            foreach($rows as $cat)
                $result[] = new Category($cat['id']);
            return $result;
        }
    }//class
?>