<?php
	define('PAGENAME','signup');
	require_once 'classes/Security.php';
	use MMS\Security;
	Security::init();
	if(Security::verSession()){
		header('location: index.php');
	}
?>
<!DOCTYPE html>
<html lang="it">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta name="description" content="">
		<meta name="author" content="">
		<link rel="icon" href="../../../../favicon.ico">
		
		<title>Sign up | Musetek</title>
		
		<!-- Bootstrap core CSS -->
		<link href="./css/bootstrap.min.css" rel="stylesheet">
		
		<!-- Custom styles for this template -->
		<link href="./css/auth.css" rel="stylesheet">
	</head>
	
	<body>
		<form class="form-signin" id="registerform">
			<div class="text-center mb-4">
				<img class="mb-4" src="https://getbootstrap.com/assets/brand/bootstrap-solid.svg" alt="" width="72" height="72">
				<h1 class="h3 mb-3 font-weight-normal">Registrati</h1>
				<p>Per effettuare acquisti online devi avere un account</p>
			</div>
			<div class="alert alert-success d-none" role="alert" id="succ">
			</div>
			<div class="alert alert-danger d-none" role="alert" id="err">
			</div>
			<div class="alert alert-warning d-none" role="alert" id="warn">
			</div>
			<div class="form-label-group">
				<input id="userIn" type="text" class="form-control" placeholder="Username" required>
				<label for="userIn">Username</label>
			</div>
			
			<div class="form-label-group">
				<input id="mailIn" type="email" class="form-control" placeholder="Email" required>
				<label for="mailIn">Email</label>
			</div>
			
			<div class="form-label-group">
				<input id="passIn" type="password" class="form-control" placeholder="Password" required>
				<label for="passIn">Password</label>
			</div>
			<button id="signupbtn" class="btn btn-lg btn-primary btn-block">Registrati</button>
			<p class="mt-5 mb-3 text-muted text-center">&copy; 2017-2018</p>
		</form>
		
		<script src="./js/jquery-3.3.1.min.js"></script>
		<script src="./js/bootstrap.bundle.min.js"></script>
		<script src="./js/auth.js"></script>
	</body>
</html>
