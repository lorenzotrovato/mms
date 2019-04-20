<?php
	namespace MMS;
	require_once $_SERVER["DOCUMENT_ROOT"].'/src/includes/autoload.php';
	use MMS\Ticket as Ticket;
	use MMS\TimeSlot as TimeSlot;
	use MMS\Expo as Expo;
	use MMS\Accessory as Accessory;
	use MMS\Category as Category;
	use MMS\Security as Security;
	Security::init();
	if(!Security::verSession()){
		header('location: index.php');
	}
	$user=Security::getUserFromSession();
?>
<!DOCTYPE html>
<html lang="en">

	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta name="description" content="">
		<meta name="author" content="">
	
		<title>Acquista il biglietto</title>
	
		<!-- Bootstrap core CSS -->
		<link href="./css/bootstrap.min.css" rel="stylesheet">
	
		<!-- Custom styles for this template -->
		<link href="./css/form-validation.css" rel="stylesheet">
		<link rel="icon" type="image/png" href="./favicon.png" />
	</head>
	
	<body class="bg-light">
		<div class="container">
		<?php
			if((isset($_POST['timeSlotId']) && isset($_POST['dateValidity']) && isset($_POST['nTickets']) && isset($_POST['nAccessories'])) && !isset($_POST['submit'])){
				// 1. il numero di biglietti deve essere superiore a 0
				// 2. il giorno della settimana della data di validità deve corrispondere al giorno della settimana della fascia oraria
				// 3. la somma dei biglietti di ogni categoria deve essere inferiore al numero massimo di posti disponibili (TimeSlot->getOccupiedSeats())
				// 4. controllare se la data di validità è compresa fra la data di inizio e la data di fine dell'evento (esclusi i giorni della settimana in cui non ci sono timeSlot)
				// 5. la data deve essere nel futuro
				// 6. gli accessori scelti devono essere minori degli accessori disponibili
				// 7. gli accessori scelti non devono essere stati disattivati
				
				$timeSlotId = $_POST['timeSlotId'];
				$dateValidity = $_POST['dateValidity'];
				$nTickets = json_decode($_POST['nTickets'], 1); // array('[idcat]' => '[nbiglietti]', '[idcat]' => '[nbiglietti]');

				$nAccessories = json_decode($_POST['nAccessories'], 1); // array('[idAcc]' => '[nAccessori]', '[idAcc]' => '[nAccessori]');
				
				// 1.
				if(count($nTickets) < 1){
					$errore = "devi ordinare almeno un biglietto";
				}else{
					$sumTickets = 0;
					foreach($nTickets as $value){
						$sumTickets+=$value;
					}
					if($sumTickets > 0){
						// 2.
						$time = str_replace('/', '-', $dateValidity);
						$timeDate = strtotime($time);
						$dayw = Expo::getDayOfWeekFromDate($time);
						$exception = false;
						try{
							$timeSlot = new TimeSlot($timeSlotId);
						}catch(\Exception $e){
							$exception = true;
						}
						if(!$exception){
							if($timeSlot->getDay() != $dayw){
								$errore = "c'è stato un errore durante l'elaborazione dei dati";
							}else{
							
								// 3.
								$event = new Expo($timeSlot->getCodEvent());
								$occSeats = $timeSlot->getOccupiedSeats(date('d-m-Y', strtotime($time)));
								$maxSeats = $event->getMaxSeats();
								if(($occSeats + $sumTickets) > $maxSeats){
									$errore = "hai richiesto ".$sumTickets." a fronte di ".($maxSeats-$occSeats)." posti disponibili";
								}else{
									// 4.
									$startEvent = $event->getStartDate();
									$endEvent = $event->getEndDate();
									$timeSlots = $event->getTimeSlots();
									$exists = false;
									for($i = 0; !$exists && $i < count($timeSlots); $i++){
										$exists = $timeSlot->equals($timeSlots[$dayw][$i]);
									}
									//!$exists || ($timeDate>=strtotime($startEvent) && $timeDate<=strtotime($endEvent))
									if((!$exists || $timeDate<strtotime($startEvent) || $timeDate>strtotime($endEvent)) && $startEvent != null && $endEvent != null){
										$errore = "la data selezionata non è valida";
									}else{
									
										// 5.
										if(!($timeDate >= time())){
											$errore = "impossibile selezionare la data attuale";
										}else{
											// 6.
											$accessories = array();
											$overflowAcc = array();
											foreach($nAccessories as $id => $acc){
												$obj = new Accessory($id);
												$accessories[] = [$obj, $acc];
												$busy = Accessory::getNotAvailable($id, $timeSlot->getId());
												$available = ($obj->getNAvailable() - $busy);
												if($available < $acc){
													$overflowAcc[] = $obj->getName();
												}
												$nAccessories[$id] = array($acc, $obj->getPrice());
											}
											
											if(count($overflowAcc) > 0){
												$errore = "Gli accessori ";
												foreach($overflowAcc as $accOv){
													$errore .= $accOv.', ';
												}
												$errore = rtrim($errore, ', ');
												$errore .= " non sono disponibili per questa fascia oraria";
											}else{
											
												// 7.
												$unavailables = array();
												foreach($accessories as $accessory){
													if($accessory[0]->getNAvailable() == 0){
														$unavailables[] = $accessory[0]->getName();
													}
												}
												
												if(count($unavailables) > 0){
													$errore = "Gli accessori ";
													foreach($unavailables as $accUn){
														$errore .= $accUn.', ';
													}
													$errore = rtrim($errore, ', ');
													$errore = " non sono più disponibili per la vendita";
												}
											}
										}
									}
								}
							}
						}else{
							$errore = "impossibile trovare la fascia oraria selezionata.";
						}
					}else{
						$errore = "devi ordinare almeno un biglietto.";
					}
				}
				
				if(isset($errore)){
					die(
					'<br><div class="row justify-content-center" role="alert">
						<div class="alert alert-danger">
							<h4 class="alert-heading">Oops...</h4>
  							<p>
  								Qualcosa è andato storto. La transazione è stata annullata per il seguente motivo:<br>
  								<p class="text-center"><strong>"</strong><i>'.$errore.'.</i><strong>"</strong></p>
  							</p>
							<hr>
							<p class="mb-0"><a href="index.php" class="alert-link">Torna alla home</a> per riprovare.</p>
						</div>
					</div>');
				}
				
				$ntick = 0;
				$nAcc = 0;
				foreach($nTickets as $key=>$tick){
					$ntick += $tick;
				}
				foreach($accessories as $accx){
					$nAcc += $accx[1];
				}
				
				/**
				 * aggiunge un biglietto dopo l'acquisto
				 * @param User $user l'utente che effettua l'acquisto
				 * @param Category $cat la categoria di biglietto
				 * @param TimeSlot $ts la fascia oraria corrispondente
				 * @param date $dv la data di validità del biglietto
				 * @param float $tp prezzo totale del biglietto
				 * @return Ticket l'oggetto ticket creato oppure l'errore
				 */
		?>
			<div class="py-5 text-center">
				<a href="index.php"><img class="d-block mx-auto mb-4" src="./favicon.png" width="72" height="72"></a>
				<h2>Ci siamo quasi...</h2>
				<p class="lead">Ancora pochi passi e il tuo acquisto sarà completato</p>
			</div>
	
			<div class="row">
				<div class="col-md-4 order-md-2 mb-4">
					<h4 class="d-flex justify-content-between align-items-center mb-3">
		            <span class="text-muted">I tuoi acquisti</span>
		            <span class="badge badge-secondary badge-pill"><?=($ntick+$nAcc)?></span>
		          </h4>
					<ul class="list-group mb-3">
						<?php
						$totPrice = 0;
						foreach($nTickets as $id => $number){
							$cat = new Category($id);
							$prezzo = $event->getPrice() - ($event->getPrice()*$cat->getDiscount())/100;
							$nTickets[$id] = array($number, $prezzo);
							echo 
								'<li class="list-group-item d-flex justify-content-between lh-condensed">
									<div>
										<h6 class="my-0">Biglietto '.$cat->getName().'</h6>
										<small class="text-muted">Sconto applicato del '.$cat->getDiscount().'%</small>
										<span class="badge badge-secondary badge-pill" style="position: absolute; right: 18px; bottom: 10px;">x'.$number.'</span>
									</div>
									<span class="text-muted">€'.$prezzo.'</span>
								</li>';
							$totPrice += $prezzo*$number;
						}
						foreach($accessories as $accx){
							echo '
							<li class="list-group-item d-flex justify-content-between lh-condensed">
								<div>
									<h6 class="my-0">'.$accx[0]->getName().'</h6>
									<small class="text-muted">'.$accx[0]->getType().'</small>
									<span class="badge badge-secondary badge-pill" style="position: absolute; right: 18px; bottom: 10px;">x'.$accx[1].'</span>
								</div>
								<span class="text-muted">€'.$accx[0]->getPrice().'</span>
							</li>
							';
							$totPrice += $accx[0]->getPrice()*$accx[1];
						}
						echo '
						<li class="list-group-item d-flex justify-content-between">
							<span>Totale (EUR)</span>
							<strong>€'.$totPrice.'</strong>
						</li>
						';
						?>
					</ul>
				</div>
				
				<div class="col-md-8 order-md-1">
					<form action="#" class="needs-validation" id="formPayment" method="POST" novalidate>
						<hr class="mb-4">
	
						<h4 class="mb-3">Pagamento</h4>
	
						<div class="d-block my-3" id="cardTypeRadio">
							<div class="custom-control custom-radio">
								<input id="credit" name="paymentMethod" type="radio" class="custom-control-input" value="credito" checked required>
								<label class="custom-control-label" for="credit">Carta di Credito</label>
							</div>
							<div class="custom-control custom-radio">
								<input id="debit" name="paymentMethod" type="radio" class="custom-control-input" value="debito" required>
								<label class="custom-control-label" for="debit">Carta di Debito</label>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6 mb-3">
								<label for="cc-name">Intestatario</label>
								<input type="text" name="cc-name" class="form-control" id="cc-name" placeholder="Mario Rossi" required>
								<small class="text-muted">Lo trovi scritto sulla carta</small>
								<div class="invalid-feedback">
									Inserisci il nome dell'intestatario della carta
								</div>
							</div>
							<div class="col-md-6 mb-3">
								<label for="cc-number">Numero carta di <span id="cardType">credito</span></label>
								<input type="text"  name="cc-number" class="form-control" id="cc-number" placeholder="XXXX-XXXX-XXXX-XXXX" required>
								<div class="invalid-feedback">
									Inserisci il numerio della carta
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-3 mb-3">
								<label>Scade il</label>
								<div class="input-group">
									<input type="text" name="m-expiration" class="form-control" id="m-expiration" placeholder="mm" required>
									<input type="text" name="y-expiration" class="form-control" id="y-expiration" placeholder="yyyy" required>
								</div>
								<div class="invalid-feedback">
									Inserisci la data di scadenza
								</div>
							</div>
							<div class="col-md-3 mb-3">
								<label for="cc-cvv">CVV</label>
								<input type="text" name="cc-cvv" class="form-control" id="cc-cvv" placeholder="XXX" required>
								<div class="invalid-feedback">
									Inserisci il codice di sicurezza
								</div>
							</div>
						</div>
	            		<hr class="mb-4">
						<button id="conCheck" class="btn btn-primary btn-lg btn-block" name="submit" type="submit">Completa l'acquisto</button>
					</form>
				</div>
			</div>
			
			<?php
				$_SESSION['order_info']['timeSlot'] = $timeSlotId;
				$_SESSION['order_info']['tickets'] = $nTickets;
				$_SESSION['order_info']['accessories'] = $nAccessories;
				$_SESSION['order_info']['dateValidity'] = str_replace('/', '-', $dateValidity);
				$_SESSION['order_info']['payed'] = false;
			}else if(isset($_POST['submit']) && !empty($_POST['paymentMethod']) && !empty($_POST['cc-name']) && !empty($_POST['cc-number']) && !empty($_POST['m-expiration']) && !empty($_POST['y-expiration']) && !empty($_POST['cc-cvv'])){
				if(isset($_SESSION['order_info'])){
					$_SESSION['order_info']['payed'] = true;
					$newId = null;
					$tempId = null;
					$accSitua = null;
					Security::beginTransaction();
					foreach($_SESSION['order_info']['tickets'] as $cat => $row){
						for($i=0; $i<$row[0] && $tempId != 'error'; $i++){
							$tempId = Ticket::addTicket($user, $cat, $_SESSION['order_info']['timeSlot'], $_SESSION['order_info']['dateValidity'], $row[1]);
							if($newId==null){
								$newId = $tempId;
							}
						}
					}
					if($tempId != "error"){
						foreach($_SESSION['order_info']['accessories'] as $acc => $row){
							$accTemp = Accessory::addAccessoryToUser($newId, $acc, $row[0]);
							if($accTemp == "error"){
								$accSitua = $accTemp;
							}
						}
					}
					if($accSitua === "error" || $tempId === "error"){
						Security::rollback();
						$_SESSION['order_info']['payed'] = false;
					}else{
						Security::commit();
					}
					Security::endTransaction();
					if($_SESSION['order_info']['payed'] == true){
						unset($_SESSION['order_info']);
						echo '
						<div class="alert alert-success mt-3" role="alert">
							<h4 class="alert-heading">Meraviglioso!</h4>
							<p>Molto bene, l\'acquisto è andato a buon fine. Ricordati di portare il telefono con il codice di validazione, o di stampare il codice QR - puoi trovarli nella sezione <a href="aboutme.php">Il mio account</a> - .</p>
							<hr>
							<p class="mb-0">Non vediamo l\'ora di vederti. <a href="index.php">Torna alla home</a></p>
						</div>';
					}else{
						echo '
						<div class="alert alert-danger mt-3" role="alert">
							<h4 class="alert-heading">Ouch!</h4>
							<p>Qualcosa è andato storto... L\'acquisto è stato annullato e non sono stati prelevati soldi dalla tua carta.</p>
							<hr>
							<p class="mb-0">Ci scusiamo per l\'inconveniente. <a href="index.php">Torna alla home</a></p>
						</div>
						';
					}
				}
			}else{
				unset($_SESSION['order_info']);
				die(
					'<br><div class="row justify-content-center" role="alert">
						<div class="alert alert-danger">
							<h4 class="alert-heading">Oops...</h4>
  							<p>
  								Qualcosa è andato storto. La transazione è stata annullata per il seguente motivo:<br>
  								<p class="text-center"><strong>"</strong><i>i dati del tuo ordine sono andati persi.</i><strong>"</strong></p>
  							</p>
							<hr>
							<p class="mb-0"><a href="index.php" class="alert-link">Torna alla home</a> per riprovare.</p>
						</div>
					</div>');
			}
			?>
			
			<footer class="my-5 pt-5 text-muted text-center text-small">
				<p class="mb-1">&copy; 2017-2018 Musetek</p>
			</footer>
		</div>
	
		<!-- Bootstrap core JavaScript
		    ================================================== -->
		<!-- Placed at the end of the document so the pages load faster -->
		<script src="./js/jquery-3.3.1.min.js"></script>
		<script src="./js/bootstrap.bundle.min.js"></script>
		<script src="./js/holder.min.js"></script>
		<script src="./js/checkout-form.js"></script>
	</body>

</html>