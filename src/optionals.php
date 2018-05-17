<?php
	namespace MMS;
	require_once 'includes/autoload.php';
	use MMS\Security as Security;
?>
<!doctype html>
<html lang="it">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="description" content="">
	<meta name="author" content="">
	<link rel="icon" href="../../../../favicon.ico">

	<title>Pricing example for Bootstrap</title>

	<!-- Bootstrap core CSS -->
	<link href="./css/bootstrap.min.css" rel="stylesheet">

	<link href="./css/pricing.css" rel="stylesheet">
	<link href="./css/style.css" rel="stylesheet">
	<link href="./css/dashboard.css" rel="stylesheet">
	<link rel="icon" type="image/png" href="./favicon.png"/>
</head>
<body>
	
	<div class="navbar navbar-dark sticky-top flex-md-nowrap p-3">  
		<?php
			include 'includes/header.php';
		?>
	</div>

	<div class="pricing-header px-3 py-3 pt-md-5 pb-md-4 mx-auto text-center">
		<h1 class="display-4">Pricing</h1>
		<p class="lead">Quickly build an effective pricing table for your potential customers with this Bootstrap example. It's built with default Bootstrap components and utilities with little customization.</p>
	</div>

	<div class="container">
		<div class="card-deck mb-3 text-center">
			<div class="card mb-4 box-shadow">
				<div class="card-header">
					<h4 class="my-0 font-weight-normal">Free</h4>
				</div>
				<div class="card-body">
					<h1 class="card-title pricing-card-title">$0 <small class="text-muted">/ mo</small></h1>
					<ul class="list-unstyled mt-3 mb-4">
						<li>10 users included</li>
						<li>2 GB of storage</li>
						<li>Email support</li>
						<li>Help center access</li>
					</ul>
					<button type="button" class="btn btn-lg btn-block btn-outline-primary">Sign up for free</button>
				</div>
			</div>
		</div>

		<footer class="pt-4 my-md-5 pt-md-5 border-top">
			<div class="row">
				<div class="col-12 col-md">
					<img class="mb-2" src="https://getbootstrap.com/assets/brand/bootstrap-solid.svg" alt="" width="24" height="24">
					<small class="d-block mb-3 text-muted">&copy; 2017-2018</small>
				</div>
				<div class="col-6 col-md">
					<h5>Features</h5>
					<ul class="list-unstyled text-small">
						<li><a class="text-muted" href="#">Cool stuff</a></li>
						<li><a class="text-muted" href="#">Random feature</a></li>
						<li><a class="text-muted" href="#">Team feature</a></li>
						<li><a class="text-muted" href="#">Stuff for developers</a></li>
						<li><a class="text-muted" href="#">Another one</a></li>
						<li><a class="text-muted" href="#">Last time</a></li>
					</ul>
				</div>
				<div class="col-6 col-md">
					<h5>Resources</h5>
					<ul class="list-unstyled text-small">
						<li><a class="text-muted" href="#">Resource</a></li>
						<li><a class="text-muted" href="#">Resource name</a></li>
						<li><a class="text-muted" href="#">Another resource</a></li>
						<li><a class="text-muted" href="#">Final resource</a></li>
					</ul>
				</div>
				<div class="col-6 col-md">
					<h5>About</h5>
					<ul class="list-unstyled text-small">
						<li><a class="text-muted" href="#">Team</a></li>
						<li><a class="text-muted" href="#">Locations</a></li>
						<li><a class="text-muted" href="#">Privacy</a></li>
						<li><a class="text-muted" href="#">Terms</a></li>
					</ul>
				</div>
			</div>
		</footer>
	</div>


	<!-- Bootstrap core JavaScript
================================================== -->
	<!-- Placed at the end of the document so the pages load faster -->
	<script src="./js/jquery-3.3.1.min.js"></script>
	<script src="./js/bootstrap.bundle.min.js"></script>
	<script src="./js/vendor/holder.min.js"></script>
	<script>
		Holder.addTheme('thumb', {
			bg: '#55595c',
			fg: '#eceeef',
			text: 'Thumbnail'
		});
	</script>
</body>

</html>