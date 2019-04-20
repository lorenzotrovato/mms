<?php
    namespace MMS;
	require_once $_SERVER["DOCUMENT_ROOT"].'/src/includes/autoload.php';
	use MMS\Database as DB;
	use MMS\Category as Category;
	use MMS\Accessory as Accessory;
	use MMS\User as User;
	use MMS\TimeSlot as TimeSlot;
	use MMS\Security as Security;
	
	class Ticket{
		private static $mysqli;

		private $id;
		private $codUser;
		private $codCat;
		private $codTimeSlot;
		private $datePurchase;
		private $dateValidity;
		private $totalPrice;
		private $validation;
		private $accessories;
		
		
		/** funzione costruttore
		 * Le variabili vengono scaricate dal database
		 * @param $id id del biglietto
		 */
		public function __construct($id){
			self::init();
			$biglietto = self::$mysqli->querySelect('select * from biglietto where id='.$id);
			if(count($biglietto) == 1){
				$this->id = $biglietto[0]['id'];
				$this->codUser = new User($biglietto[0]['codUser']);
				$this->codCat = new Category($biglietto[0]['codCat']);
				$this->codTimeSlot = new TimeSlot($biglietto[0]['codTimeSlot']);
				$this->datePurchase = $biglietto[0]['datePurchase'];
				$this->dateValidity = $biglietto[0]['dateValidity'];
				$this->totalPrice = $biglietto[0]['totalPrice'];
				$this->validation = $biglietto[0]['validation'];
				
				$this->accessories = array();
				$idAcc = self::$mysqli->querySelect("select codAccessory from bigliettoAccessorio where codTicket='".$this->id."'");
				foreach($idAcc as $acc){
					$this->accessories[] = new Accessory($acc['codAccessory']);
				}
			}else{
				throw new Exception('Id non valido');
			}
		}
		
		public static function init(){
			self::$mysqli = DB::init();
		}
		
		/**
		 * aggiunge un biglietto dopo l'acquisto
		 * @param User $user l'utente che effettua l'acquisto
		 * @param Category $cat la categoria di biglietto
		 * @param TimeSlot $ts la fascia oraria corrispondente
		 * @param date $dv la data di validità del biglietto
		 * @param float $tp prezzo totale del biglietto
		 * @param integer $qta Numero biglietti
		 * @return Ticket l'oggetto ticket creato oppure l'errore
		 */
		public static function addTicket($user,$cat,$ts,$dv,$tp){
			self::init();
			$datep = Security::escape(date('Y-m-d H:i:s',time()));
			$datev = Security::escape(date('Y-m-d',strtotime($dv)));
			$val = Security::escape(md5(($user->getId()).($cat).mt_rand(0,99999999999)));
			$queryIns = 'INSERT INTO biglietto(codUser, codCat, codTimeSlot, datePurchase, dateValidity, totalPrice, validation) VALUES ("'.$user->getId().'","'.$cat.'","'.$ts.'","'.$datep.'","'.$datev.'","'.floatval($tp).'","'.$val.'")';
			if(self::$mysqli->queryDML($queryIns)==1){
				return self::$mysqli->getInsertId();
			}else{
				return "error";
			}
		}

		/** funzione getId
		 * restituisce l'id del biglietto
		 * @return $this->id
		 */
		public function getId(){
		    return $this->id;
		}

		/** funzione getCodUser
		 * restituisce l'oggetto User (utente) che ha acquistato il biglietto
		 * @return $this->codUser
		 */
		public function getCodUser(){
		    return $this->codUser;
		}

		/** funzione getCodCat
		 * restituisce l'oggetto Category (categoria) del biglietto
		 * @return $this->codCat
		 */
		public function getCodCat(){
		    return $this->codCat;
		}

		/** funzione getCodTimeSlot
		 * restituisce l'oggetto TimeSlot (fascia oraria) del biglietto
		 * @return $this->codTimeSlot
		 */
		public function getTimeSlot(){
		    return $this->codTimeSlot;
		}

		/** funzione getDatePurchase
		 * restituisce la data di acquisto del biglietto
		 * @return $this->datePurchase
		 */
		public function getDatePurchase(){
		    return $this->datePurchase;
		}

		/** funzione getDateValidity
		 * restituisce la data di validità del biglietto
		 * @return $this->dateValidity
		 */
		public function getDateValidity(){
		    return $this->dateValidity;
		}
		
		/** funzione getTotalPrice
		 * restituisce il costo totale del biglietto
		 * @return $this->totalPrice
		 */
		public function getTotalPrice(){
		    return $this->totalPrice;
		}

		/** funzione getAccessories
		 * restituisce l'array di oggetti Accessory (accessori) collegati al biglietto
		 * @return $this->accessories
		 */
		public function getAccessories(){
			return $this->accessories;
		}
		
		/**
		 * funzione getEvent
		 * @return Expo l'oggetto esposizione collegato al biglietto
		 */
		public function getEvent(){
			//var_dump($this->getTimeSlot());
			return new Expo($this->getTimeSlot()->getCodEvent());
		}
		
		/**
		 * @return int la quantità per il singolo accessorio nel bilglietto
		 */
		public function getQtaForAcc($accId){
			Security::init();
			$ai = intval(Security::escape($accId));
			$query = self::$mysqli->querySelect('SELECT * FROM bigliettoAccessorio WHERE codTicket="'.$this->id.'" AND codAccessory="'.$ai.'"');
			if(count($query)==1){
				return $query[0]['qta'];
			}else{
				return false;
			}
		}
		
		/**
		 * @return array l'oggetto come array associativo
		 */
		public function asAssocArray(){
			$accArr = array();
			self::init();
			Category::init();
			TimeSlot::init();
			foreach($this->accessories as $a){
				$accArr []= array('id'=>$a->getId(),'name'=>$a->getName(),'price'=>$a->getPrice(),'qta'=>$this->getQtaForAcc($a->getId()));
			}
			return array('id'=>$this->id,'datePurchase'=>date('d/m/Y',strtotime($this->datePurchase)),'dateValidity'=>date('d/m/Y',strtotime($this->dateValidity)),
			'categoryId'=>$this->codCat->getId(),'categoryName'=>$this->codCat->getName(),'timeSlot'=>$this->codTimeSlot->getArray(),'accessories'=>$accArr,
			'valkey'=>$this->validation);
		}
	}//class
?>
