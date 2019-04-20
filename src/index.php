<?php
	namespace MMS;
	define('PAGENAME', 'home');
	require_once 'includes/autoload.php';
	use MMS\Security as Security;
	use MMS\Expo as Expo;
	Security::init();
	Expo::init();
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
		<link href="./css/tempusdominus-bootstrap-4.min.css" rel="stylesheet">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" integrity="sha384-DNOHZ68U8hZfKXOrtjWvjxusGo9WQnrNx2sqG0tfsghAvtVlRW3tvkXWZh58N9jp" crossorigin="anonymous">
		<!-- Custom styles for this template -->
		<link href="./css/cover.css" rel="stylesheet">
		<link rel="icon" type="image/png" href="./favicon.png"/>
		
	</head>

	<body class="text-center">
		<div class="background" id="canvCnt"></div>
		<div class="container-fluid cover-container d-flex h-100 mx-auto flex-column">
			<?php
				include './includes/header.php';
			?>
			<main role="main" class="inner cover">
				<h1 class="cover-heading">MuseTek</h1>
				<p class="lead">Vieni a trovarci, e scopri l'innovazione del passato!</p>
				<p class="lead">
					<?php
						if(Security::verSession()){
					?>
						<a href="#" class="btn btn-lg btn-secondary normalbuy">Acquista il biglietto di ingresso</a>
					<?php
						}else{
					?>
						<a href="#" class="btn btn-lg btn-secondary" data-toggle="modal" data-target="#modalInfo">Vieni a trovarci</a>
					<?php
						}
					?>
				</p>
			</main>

			<footer class="mastfoot mt-auto<?=(!Security::verSession() ?: ' invisible')?>">
				<div class="inner">
					<p>Vuoi acquistare un biglietto? <a href="./signin.php" class="text-white">Accedi</a> o <a href="./signup.php" class="text-white">Registrati</a>.</p>
				</div>
			</footer>
		</div>
		
		<div class="modal fade" id="modalBuy" tabindex="-1" role="dialog" aria-labelledby="modalBuyLabel" aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="exampleModalLabel"></h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<section id="js-container">
						
					</section>
				</div>
			</div>
		</div>
		
		<div class="modal fade" id="modalInfo" tabindex="-1" role="dialog" aria-labelledby="modalInfoLabel" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="exampleModalCenterTitle">Vieni a trovarci!</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						Ti aspettano un sacco di sorprese!<br/>
						<?php
							$obj = new Expo(0);
						?>
						Prezzo biglietto ingresso: € <?php echo $obj->getPrice()?>
						<br/><br/>
						Per acquistare i biglietti effettua l'accesso!
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Chiudi</button>
					</div>
				</div>
			</div>
		</div>

		<script src="./js/jquery-3.3.1.min.js"></script>
		<script src="./js/p5.min.js"></script>
		<!-- <script src="./js/circlepack.js"></script>
		<script src="./js/Circle.js"></script> -->
		<script src="./js/bootstrap.bundle.min.js"></script>
		<script src="./js/feather.min.js"></script>
		<script src="./js/events.js"></script>
		<script src="./js/moment-with-locales.js"></script>
		<script src="./js/tempusdominus-bootstrap-4.min.js"></script>
	</body>
</html>
