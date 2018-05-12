<?php
    namespace MMS;
	require 'Database.php';
	require 'Category.php';
	require 'Accessory.php';
	require 'User.php';
	require 'TimeSlot.php';
	use MMS\Database as DB;
	use MMS\Category;
	use MMS\Accessory;
	use MMS\User;
	use MMS\TimeSlot;
	
	class Ticket{
		private $mysqli;

		private $id;
		private $codUser;
		private $codCat;
		private $codTimeSlot;
		private $datePurchase;
		private $dateValidity;
		private $totalPrice;
		private $accessories;
		
		
		/** funzione costruttore
		 * Le variabili vengono scaricate dal database
		 * @param $id id del biglietto
		 */
		public function __construct($id){
			$this->mysqli = DB::init();
			$biglietto = $this->mysqli->querySelect('select * from biglietto where id='.$id);
			if (count($biglietto) == 1){
				$this->id = $biglietto[0]['id'];
				$this->codUser = new User($biglietto[0]['codUser']);
				$this->codCat = new Category($biglietto[0]['codCat']);
				$this->codTimeSlot = new TimeSlot($biglietto[0]['codTimeSlot']);
				$this->datePurchase = $biglietto[0]['datePurchase'];
				$this->dateValidity = $biglietto[0]['dateValidity'];
				$this->totalPrice = $biglietto[0]['totalPrice'];
				$this->accessories = array();
				$idAcc = $this->mysqli->querySelect("select codAccessory from bigliettoAccessorio where codTicket='".$this->id."'");
				foreach($idAcc as $acc){
					$this->accessories[] = new Accessory($acc['id']);
				}
			}else{
				throw new Exception('Id non valido');
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
			return $this->acessories;
		}
	}//class
?>
