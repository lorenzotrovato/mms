<?php
	namespace MMS;
	define('PAGENAME', 'aboutme');
	require_once 'includes/autoload.php';
	use MMS\Security as Security;
	Security::init();
	if (Security::verSession()){
		$user = Security::getUserFromSession();
	}else{
		header('Location: signin.php');
	}
	
	//caricamento biglietti utente
	$htmlTickets = '';
	$allts = $user->getUserTickets();
	for($i=0;$i<count($allts);$i++){
		$htmlTickets .= '<tr class="user-ticket handCursor" valId="'.$allts[$i]->getId().'"><td>'.($i+1).'</td><td class="ev-name">'.$allts[$i]->getEvent()->getName().'</td><td>'.date('d/m/Y',strtotime($allts[$i]->getDatePurchase())).'</td><td>'.$allts[$i]->getTotalPrice().'</td></tr>';
	}
?>
<!doctype html>
<html lang="it">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta name="description" content="">
		<meta name="author" content="">

		<title>Musetek - Innovation of the past</title>

		<!-- Bootstrap core CSS -->
		<link href="./css/bootstrap.min.css" rel="stylesheet">
		
		<link href="./css/style.css" rel="stylesheet">
		<!-- Custom styles for this template -->
		<link href="./css/cover.css" rel="stylesheet">
		<link rel="icon" type="image/png" href="./favicon.png"/>
	</head>
	
	<body class="text-center">
		
		<!-- Modal -->
		<div class="modal fade" id="modalTick" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="modalTickTitle"></h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body" id="tickBodyData">
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-primary" data-dismiss="modal">Chiudi</button>
					</div>
				</div>
			</div>
		</div>
		
		<div class="container-fluid cover-container d-flex h-100 mx-auto flex-column">
			<?php
				include './includes/header.php';
			?>
			<main role="main">
				<div class="row align-items-center justify-content-center">
					<div class="container mrg-top align-items-center justify-content-center">
						<div class="row mt-3 mt-lg-5">
							<div class="col-12 col-md-6 offset-md-3">
								<div id="formErrProfile" class="alert d-none text-center"></div>
							</div>
							<div class="col-12 col-sm-12 col-md-12 col-lg-6">
								<div class="card" style="background-color: rgba(255, 255, 255, 0.8); color: black;" id="cardProfile">
									<div class="card-body">
										<h5 class="card-title">Il mio Profilo</h5>
										<form class="align-items-center justify-content-center">
											<div class="form-row">
												<div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6">
													<br>
													<h5 class="text-primary">Username: <strong><?=(!Security::isAdmin() ? $user->getName() : $user->getName().' (admin)')?></strong></h5>
												</div>
												<div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6">
													<div class="form-group">
														<label for="profileChangeMail">Cambia Email</label>
														<input type="email" class="form-control" id="profileChangeMail" value="<?php echo $user->getMail(); ?>">
													</div>
												</div>
												<div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6">
													<div class="form-group">
														<label for="profileChangePass1">Cambio password</label>
														<input type="password" class="form-control" id="profileChangePass1" placeholder="Password">
													</div>
												</div>
												<div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6">
													<div class="form-group">
														<label for="profileChangePass2">Ripeti la password</label>
														<input type="password" class="form-control" id="profileChangePass2" placeholder="Password">
													</div>
												</div>
											</div>
											<div class="form-row align-items-center justify-content-center">
												<button class="btn btn-primary m-1" id="btnEditProfile" style="width: 300px;">Applica modifiche</button>
											</div>
										</form>
									</div>
								</div>
							</div>
						<!--
						</div>
						<div class="row">
						+ togliere offset e mettere tutte e due le card a 12 colonne per qualsiasi schermo
						-->
							<div class="col-12 col-sm-12 col-md-12 col-lg-6">
								<div class="card mt-3 mt-lg-0" style="background-color: rgba(255, 255, 255, 0.8); color: black;" id="cardPurchasedTickets">
									<div class="card-body">
										<h5 class="card-title">Biglietti acquistati</h5>
										<h6 class="text-muted">Clicca su un biglietto per avere il codice QR da esibire</h6>
										<div class="table-responsive">
											<table class="table table-hover">
												<thead class="thead-dark">
													<th>#</th>
													<th>Evento</th>
													<th>Data d'acquisto</th>
													<th>Prezzo [€]</th>
												</thead>
												<tbody id="profilePurchasedTickets">
														<?php echo $htmlTickets; ?>
												</tbody>
											</table>
										</div>
									</div>
								</div>
							</div>
						</div>
						
					</div>
				</div>
			</main>
		</div>
		<script src="./js/jquery-3.3.1.min.js"></script>
        <script>
            window.jQuery || document.write('<script src="./js/jquery-3.3.1.min.js"><\/script>')
        </script>
        <script src="./js/bootstrap.bundle.min.js"></script>
        <!-- Icons -->
        <script src="./js/feather.min.js"></script>
        <script>
			feather.replace();
			
			$('#btnEditProfile').on('click',function(){
				$('#formErrProfile').html("");
				if(!$('#formErrProfile').hasClass('d-none')){
					$('#formErrProfile').addClass('d-none');
				}
				$('#formErrProfile').removeClass('alert-danger');
				$('#formErrProfile').removeClass('alert-success');
				var newmail = $('#profileChangeMail').val();
				var psw1 = $('#profileChangePass1').val();
				if(psw1==$('#profileChangePass2').val()){
					$.ajax({
						type: "GET",
						cache: false,
						url: "./includes/router.php",
						data: {'action':'editProfileInfo','newmail':newmail,'newpsw':psw1},
						success: function(response){
							response = JSON.parse(response);
							if(response.message=='success-edit-profile'){
								if(response.changedmail){
									$('#formErrProfile').html('Avviso: dati aggiornati <strong>correttamente</strong>. Ricordati di verificare l\'email per poter continuare ad accedere');
								}else{
									$('#formErrProfile').html('Avviso: dati aggiornati <strong>correttamente</strong>.');
								}
								
								$('#formErrProfile').removeClass('d-none');
								$('#formErrProfile').addClass('alert-success');
								$('#profileChangePass1').val("password");
								$('#profileChangePass2').val("password");
							}else{
								console.log(response);
								$('#formErrProfile').html('Errore: ' + response.message);
								$('#formErrProfile').removeClass('d-none');
								$('#formErrProfile').addClass('alert-danger');
							}
							window.setTimeout(function(){
								$('#formErrProfile').html("");
								if(!$('#formErrProfile').hasClass('d-none')){
									$('#formErrProfile').addClass('d-none');
								}
								$('#formErrProfile').removeClass('alert-danger');
								$('#formErrProfile').removeClass('alert-success');
							}, 5000);
						},
						error: function(){
							$('#formErrProfile').html('Errore: impossibile contattare il server. Riprova più tardi');
							$('#formErrProfile').removeClass('d-none');
							$('#formErrProfile').addClass('alert-danger');
							window.setTimeout(function(){
								$('#formErrProfile').html("");
								if(!$('#formErrProfile').hasClass('d-none')){
									$('#formErrProfile').addClass('d-none');
								}
								$('#formErrProfile').removeClass('alert-danger');
								$('#formErrProfile').removeClass('alert-success');
							}, 5000);
						}
					});
				}else{
					$('#formErrProfile').html('Errore: le due password non coincidono');
					$('#formErrProfile').removeClass('d-none');
					$('#formErrProfile').addClass('alert-danger');
					window.setTimeout(function(){
						$('#formErrProfile').html("");
						if(!$('#formErrProfile').hasClass('d-none')){
							$('#formErrProfile').addClass('d-none');
						}
						$('#formErrProfile').removeClass('alert-danger');
						$('#formErrProfile').removeClass('alert-success');
					}, 5000);
				}
				return false;
			});
			
			$(document).ready(function() {
				$('.user-ticket').click(function() {
					var ticket = $(this);
					var code = ticket.attr('valId');
					$.ajax({
						type: "GET",
						cache: false,
						url: "./includes/router.php",
						data: {'action':'getCodes','idTick': code},
						success: function(response){
							var name = ticket.children('.ev-name').html();
							$('#modalTickTitle').html(name);
							$('#tickBodyData').html(response);
							$('#modalTick').modal('show');
						},
						error: function(){
							
						}
					})
				});
			});
		</script>
	</body>
</html>