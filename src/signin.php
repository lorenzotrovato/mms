<?php
	namespace MMS;
	require_once 'includes/autoload.php';
	use MMS\Security as Security;
	Security::init();
	if(Security::verSession()){
		header('location: index.php');
	}
	
	define('PAGENAME','signin');
	
	if(isset($_GET['err_mail_ver']) && $_GET['err_mail_ver']==1){
		$divErr='<div class="alert alert-danger" role="alert" id="err">Verifica dell\'email fallita. La chiave di verifica è errata</div>';
	}elseif(isset($_GET['err_mail_ver']) && $_GET['err_mail_ver']==2){
		$divErr='<div class="alert alert-danger" role="alert" id="err">Verifica dell\'email fallita. L\'utente è già verificato</div>';
	}elseif(isset($_GET['err_mail_ver']) && $_GET['err_mail_ver']==3){
		$divErr='<div class="alert alert-danger" role="alert" id="err">Verifica dell\'email fallita. L\'utente è già verificato</div>';
	}else{
		$divErr='<div class="alert alert-danger d-none" role="alert" id="err"></div>';
	}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta name="description" content="">
		<meta name="author" content="">
		
		<title>Sign in | Musetek</title>
		
		<!-- Bootstrap core CSS -->
		<link href="./css/bootstrap.min.css" rel="stylesheet">
		
		<!-- Custom styles for this template -->
		<link href="./css/auth.css" rel="stylesheet">
		
		
		<link href="./css/style.css" rel="stylesheet">
		<link rel="icon" type="image/png" href="./favicon.png"/>
	</head>
	
	<body>
		<form class="form-signin" id="loginform">
			<!--<div class="card bg-dark text-white">
				<div class="card-body">-->
					<div class="text-center mb-4">
						<a href="index.php"><img class="mb-4" src="./favicon.png" alt="" width="72" height="72"></a>
						<h1 class="h3 mb-3 font-weight-normal">Accedi</h1>
						<p>Per effettuare acquisti online devi effettuare l'accesso</p>
					</div>
					<?php echo $divErr; ?>
					<div class="alert alert-warning d-none" role="alert" id="warn">
					</div>
					<div class="form-label-group">
						<input id="userIn" type="text" class="form-control" placeholder="Username o Email" required>
						<label for="userIn">Username o Email</label>
					</div>
					
					<div class="form-label-group">
						<input id="passIn" type="password" class="form-control" placeholder="Password" required>
						<label for="passIn">Password</label>
					</div>
					
					<!--<div class="checkbox mb-3">
						<label>
							<input id="autologin" type="checkbox" value="remember-me"> Ricorda le credenziali
						</label>
					</div>-->
					<div class="custom-control custom-checkbox mb-3">
		        		<input id="autologin" type="checkbox" value="remember-me" class="custom-control-input" >
		       			<label class="custom-control-label" for="autologin"> Ricorda le credenziali</label>
		      		</div>
					<button id="signinbtn" class="btn btn-lg btn-primary btn-block">Accedi</button>
					<br>
					<footer>
						<p class="text-muted">Non hai un account? <a href="signup.php">Registrati</a></p>
					</footer>
				<!--</div>
			</div>-->
		</form>
		<script src="./js/jquery-3.3.1.min.js"></script>
		<script src="./js/bootstrap.bundle.min.js"></script>
		<script src="./js/p5.min.js"></script>
		<!--<script src="./js/particles.js"></script>-->
		<script src="./js/auth.js"></script>
	</body>
</html>

