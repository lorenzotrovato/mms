<?php
	namespace MMS;
	define('PAGENAME', 'home');
	require_once 'includes/autoload.php';
	use MMS\Security as Security;
	Security::init();
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
					<a href="#" class="btn btn-lg btn-secondary">Vieni a Trovarci</a>
				</p>
			</main>

			<footer class="mastfoot mt-auto<?=(!Security::verSession() ?: ' invisible')?>">
				<div class="inner">
					<p>Vuoi acquistare un biglietto? <a href="./signin.php" class="text-white">Accedi</a> o <a href="./signup.php" class="text-white">Registrati</a>.</p>
				</div>
			</footer>
		</div>

		<script src="./js/jquery-3.3.1.min.js"></script>
		<script src="./js/p5.min.js"></script>
		<!-- <script src="./js/circlepack.js"></script>
		<script src="./js/Circle.js"></script> -->
		<script src="./js/bootstrap.bundle.min.js"></script
	</body>
</html>
