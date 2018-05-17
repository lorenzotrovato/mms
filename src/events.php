<?php
	namespace MMS;
	define('PAGENAME', 'events');
	require_once 'includes/autoload.php';
	use MMS\Expo as Expo;
	use MMS\Category as Category;
	Expo::init();
	Category::init();
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

		<!-- Custom styles for this template -->
		<link href="./css/cover.css" rel="stylesheet">
		<link href="./css/style.css" rel="stylesheet">
		<link href="./css/tempusdominus-bootstrap-4.min.css" rel="stylesheet">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" integrity="sha384-DNOHZ68U8hZfKXOrtjWvjxusGo9WQnrNx2sqG0tfsghAvtVlRW3tvkXWZh58N9jp" crossorigin="anonymous">
		<link rel="icon" type="image/png" href="./favicon.png"/>
	</head>

	<body class="text-center">
		<div class="container-fluid cover-container d-flex h-100 mx-auto flex-column">
			<?php
				include './includes/header.php';
			?>
			<main role="main">
				<?php
					$events = Expo::getExpoList();
					foreach($events as $event){
						$image = 'images/covers/'.md5($event->getId()).'.jpg';
						if(!is_file(__DIR__.'/'.$image)){
							$image = 'images/covers/'.md5($event->getId()).'.png';
						}
						echo'
						<div class="row">
							<div class="col col-sm-10 col-lg-8 col-xl-6 offset-sm-1 offset-lg-2 offset-xl-3 h-100">
								<div class="card card-event mb-3 mx-auto" eventId="'.$event->getId().'">
									<img class="card-img-left" src="'.$image.'">
									<div class="card-body">
										<h5 class="card-title">'.$event->getName().'</h5>
										<p class="card-text block-with-text">'.$event->getDescription().'</p>
										<p class="card-text btn-container">
											<button type="button" class="btn btn-secondary event-btn discover-btn mx-auto d-block d-md-inline mb-2 mb-md-0 mr-md-2">Scopri di più</button>
											<button type="button" class="btn btn-primary event-btn mx-auto d-block d-md-inline eventbuy" eventid="'.$event->getId().'">Acquista per € '.$event->getPrice().'</button>
										</p>
									</div>
								</div>
							</div>
						</div>';
						
					}
				?>
			</main>
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
		<script src="./js/jquery-3.3.1.min.js"></script>
		<script src="./js/bootstrap.bundle.min.js"></script>
		<script src="./js/feather.min.js"></script>
		<script src="./js/events.js"></script>
		<script src="./js/moment-with-locales.js"></script>
		<script src="./js/tempusdominus-bootstrap-4.min.js"></script>
	</body>
</html>
