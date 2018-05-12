<?php
 	namespace MMS;
    require_once $_SERVER["DOCUMENT_ROOT"].'/src/includes/autoload.php';
	use MMS\Database as DB;
	
	class Accessory{
		private $mysqli;
		
		private $id;
		private $name;
		private $price;
		private $type;
		private $nAvailable;
		private $returnable;
		
		/** funzione costruttore
		 * Le variabili vengono scaricate dal database
		 * @param $id id dell'accessorio aggiuntivo
		 */
		public function __construct($id) {
			$this->mysqli = DB::init();
			$acc = $this->mysqli->querySelect('select * from acessorio where id='.$id);
			if (count($acc) == 1){
				$this->id = $acc[0]['id'];
				$this->name = $acc[0]['name'];
				$this->price = $acc[0]['price'];
				$this->type = $acc[0]['type'];
				$this->nAvailable = $acc[0]['nAvailable'];
				$this->returnable = $acc[0]['returnable'];
			}else{
				throw new Exception('Id non valido');
			}
		}
		
		/** funzione getId
		 * restituisce l'id dell'accessorio
		 * @return $this->id
		 */
		public function getID() {
			return $this->id;
		}
		
		/** funzione getName
		 * restituisce il nome dell'accessorio
		 * @return $this->name
		 */
		public function getName() {
			return $this->name;
		}
		
		/** funzione getPrice
		 * restituisce il prezzo dell'accessorio
		 * @return $this->price
		 */
		public function getPrice() {
			return $this->price;
		}
		
		/** funzione getType
		 * restituisce il tipo dell'accessorio
		 * @return $this->type
		 */
		public function getType() {
			return $this->type;
		}
		
		/** funzione getNAvailable
		 * restituisce il numero di accessori rimanenti per quel tipo di accessorio
		 * @return $this->nAvailable
		 */
		public function getNAvailable() {
			return $this->nAvailable;
		}
		
		/** funzione getReturnable
		 * restituisce un valore booleano che indica se l'accessorio va restituito
		 * @return $this->returnable
		 */
		public function getReturnable() {
			return $this->returnable;
		}
		
		/** funzione setName
		 * modifica il nome dell'accessorio
		 * @param $newName nuovo nome dell'accessorio
		 */
		public function setName($newName) {
			$this->name = $newName;
		}
		
		/** funzione setPrice
		 * modifica il prezzo dell'accessorio
		 * @param $newPrice nuovo prezzo dell'accessorio
		 */
		public function setPrice($newPrice) {
			$this->price = $newPrice;
		}
		
		/** funzione setType
		 * modifica il tipo dell'accessorio
		 * @param $newType nuovo tipo dell'accessorio
		 */
		public function setType($newType) {
			$this->type = $newType;
		}
		
		/** funzione setNAvailable
		 * modifica il numero di pezzi disponibili per un determinato accessorio
		 * @param $newNAvailable nuovo numero di pezzi disponibili per un determinato accessorio
		 */
		public function setNAvailable($newNAvailable) {
			$this->nAvailable = $newNAvailable;
		}
		
		/** funzione setReturnable
		 * modifica il valore booleano che indica se l'accessorio va restituito
		 * @param $newReturnable nuovo valore
		 */
		public function setReturnable($newReturnable) {
			$this->returnable = $newReturnable;
		}
		
		/** funzione merge
		 * aggiorna i dati nel database, modificandoli con quelli attuali dell'oggetto
		 * @return true se la modifica è andata a buon fine, false altrimenti
		 */
		public function merge() {
			$id = $this->id;
			$name = $this->name;
			$price = $this->price;
			$type = $this->type;
			$nAvailable = $this->nAvailable;
			$returnable = $this->returnable;
			return $this->mysqli->queryDML("update accessorio set name='$name', price='$price', type='$type', nAvailable='$nAvailable', returnable='$returnable' where id='$id'") > 0;
		}
		
		/**
		 * inserisce un nuovo accessorio nel database
		 * @param string $name il nome
		 * @param float $price il prezzo
		 * @param mixed $type se string il tipo ('servizio' o 'accessorio'). Se integer 0 -> 'servizio', 1 -> 'accessorio'
		 * @param integer $nAvailable il numero di accessori disponibili
		 * @param boolean $returnable true se l'accessorio va restituito, false altrimenti.
		 * @return l'oggetto contenente il nuovo accessorio inserito nel DB
		 */
		public static function insAccessory($name, $price, $type, $nAvailable, $returnable) {
			$db = DB::init();
			$n = $db->getObj()->real_escape_string($name);
			$p = (is_numeric($price) ? $price : 0);
			$t = (is_numeric($type) ? (($type < 1) ? 'accessorio' : 'servizio') : (($type == 'servizio') ? 'servizio' : 'accessorio'));
			$a = ((is_numeric($nAvailable) && $nAvailable >= 0) ? $nAvailable : 0); 
			$r = ($returnable == 'true' ? 1 : 0);
			$sql = "INSERT INTO accessorio (name, price, type, nAvailable, returnable) VALUES ('$n', $p, '$t', $a, $r)";
			if($db->queryDML($sql) > 0) {
				return new Accessory($db->getInsertId());
			}
			return false;
		}
		
		public static function getAccessoryList(){
			$sql = "SELECT * FROM accessorio";
			$rows = self::$mysqli->querySelect($sql);
			$result = array();
			foreach($rows as $acc){
				$result[] = new Accessory($acc['id']);
			}
			return $result;
		}
	}//class
?>