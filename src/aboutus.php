<?php
	namespace MMS;
	define('PAGENAME','aboutus');
    require_once 'includes/autoload.php';
    use MMS\Security;
    Security::init();
?>
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
		<div class="container-fluid cover-container d-flex h-100 mx-auto flex-column">
			<?php
				include './includes/header.php';
			?>
			<main role="main">
				<div class="row">
					<div class="col-12 col-md-12 col-lg-8 offset-lg-2 mt-4 mb-3">
						<h2> Dove siamo?</h2>
						<p>Via Luigi Pettinati, 46, Padova (PD)</p>
						<hr class="invisible">
						<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d9418.675269362955!2d11.907052800750373!3d45.425693094537294!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x477eda837b911c29%3A0x2f516b3b5ce12487!2sIstituto+Tecnico+Industriale+Statale+Francesco+Severi!5e0!3m2!1sit!2sit!4v1524557322019" height="600" frameborder="0" style="border:0;width:100%; border-radius: .35rem" allowfullscreen></iframe>
					</div>
					<div class="col-12 col-md-6 col-lg-5 col-xl-4 offset-lg-1 offset-xl-2">
						<div class="card my-3" style="background-color: rgba(255, 255, 255, 0.8); color: black;">
							<div class="card-body">
								<h5 class="card-title">Il nostro team</h5>
								<table class="table table-fixed table-striped" style="background-color: white; border-radius: .35rem; color: black">
									<thead class="thead-dark">
										<th scope="col">Nome</th>
										<th scope="col">Numero</th>
									</thead>
									<tbody>
										<tr scope="row">
											<td>Valerio Bucci</td>
											<td>+39 333 333 3333</td>
										</tr>
										<tr scope="row">
											<td>Andrea Chierchia</td>
											<td>+39 333 333 3333</td>
										</tr>
										<tr scope="row">
											<td>Mattia Maglie</td>
											<td>+39 333 333 3333</td>
										</tr>
										<tr scope="row">
											<td>Andrea Segala</td>
											<td>+39 333 333 3333</td>
										</tr>
										<tr scope="row">
											<td>Lorenzo Trovato</td>
											<td>+39 333 333 3333</td>
										</tr>
									</tbody>
								</table>
								<button type="button" class="btn btn-primary btn-block" data-toggle="modal" data-target="#exampleModal">
									Contattaci
								</button>
							</div>
						</div>
					</div>
					<div class="col-12 col-md-6 col-lg-5 col-xl-4">
						<div class="card my-3" style="background-color: rgba(255, 255, 255, 0.8); color: black;">
							<div class="card-body">
								<h5 class="card-title">I nostri orari</h5>
								<table class="table table-fixed table-striped text-center" style="background-color: white">
									<thead class="thead-dark">
										<th>Giorno</th>
										<th>Mattina</th>
										<th>Pomeriggio</th>
									</thead>
									<tbody>
										<tr>
											<td>Luned&igrave;</td>
											<td>8:00 - 12:00</td>
											<td>14:00 - 19:00</td>
										</tr>
										<tr>
											<td>Marted&igrave;</td>
											<td>8:00 - 12:00</td>
											<td>14:00 - 19:00</td>
										</tr>
										<tr>
											<td>Mercoled&igrave;</td>
											<td>8:00 - 12:00</td>
											<td>14:00 - 19:00</td>
										</tr>
										<tr>
											<td>Gioved&igrave;</td>
											<td>8:00 - 12:00</td>
											<td>14:00 - 19:00</td>
										</tr>
										<tr>
											<td>Venerd&igrave;</td>
											<td>8:00 - 12:00</td>
											<td>14:00 - 19:00</td>
										</tr>
										<tr>
											<td>Sabato</td>
											<td>8:00 - 12:00</td>
											<td>Chiuso</td>
										</tr>
										<tr>
											<td>Domenica</td>
											<td>8:00 - 12:00</td>
											<td>14:00 - 18:00</td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
				<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
					<div class="modal-dialog" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title text-dark" id="exampleModalLabel">Inviaci i tuoi suggerimenti!</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<form id="suggestions" class="mb-0">
								<div class="modal-body">
									<div class="form-row">
										<div class="form-group col-12">
											<input type="email" class="form-control" id="sugg_email" placeholder="Email">
										</div>
									</div>
									<div class="form-row">
										<div class="form-group col-12">
											<textarea class="form-control" id="sugg_textarea" rows="4" placeholder="Il tuo suggerimento"></textarea>
										</div>
									</div>
								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-secondary" data-dismiss="modal">Chiudi</button>
									<button type="button" class="btn btn-primary">Invia</button>
								</div>
							</form>
						</div>
					</div>
				</div>
			</main>
		</div>

		<script src="./js/jquery-3.3.1.min.js"></script>
		<script src="./js/bootstrap.bundle.min.js"></script>
	</body>
</html>
