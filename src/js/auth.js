$(document).ready(function() {
	$('#loginform').submit(function(e) {
		var user = $('#userIn').val();
		var pass = $('#passIn').val();
		var check = document.getElementById('autologin').checked;
		$.ajax({
			type: "GET",
			async: true,
			cache: false,
			url: "./includes/router.php",
			data: "action=login&userIn="+user+"&passIn="+pass+"&autologin="+check,
			success: function(response) {
				if(response!=""){
					if(response == 'success'){
						$(window).attr('location','index.php');
					}else if(response == 'success-admin'){
						$(window).attr('location','dashboard.php');
					}else{
						resetAlerts();
						$('#err').html('<strong>Errore:</strong> '+response);
						$('#err').removeClass('d-none');
					}
				}else{
					alert('lol');
				}
			},
			error: function() {
				resetAlerts();
				$('#warn').html("<strong>Avviso:</strong> impossibile stabilire una connessione con il server.");
				$('#warn').removeClass('d-none');
			}
		});
		e.preventDefault();
	})

	function resetAlerts() {
		//$('#succ').addClass('d-none');
		$('#err').addClass('d-none');
		$('#warn').addClass('d-none');
	}

	$('#registerform').submit(function(e) {
		var user = $('#userIn').val();
		var mail = $('#mailIn').val();
		var pass = $('#passIn').val();
		//var check = $('#autologin').attr('checked');
		$.ajax({
			type: "GET",
			cache: false,
			url: "./includes/router.php",
			data: "action=register&userIn="+user+"&mailIn="+mail+"&passIn="+pass,
			success: function(response) {
				console.log(response);
				if(response == 'success'){
					resetAlerts();
					$('#succ').html('Registrazione avvenuta con <strong>successo</strong>. Controlla la tua casella di posta per verificare l\'indirizzo email');
					$('#succ').removeClass('d-none');
					$('#signupbtn').text('Chiudi');
					$('#signupbtn').on('click',function(){
						window.close();
					});
				}else{
					resetAlerts();
					$('#err').html('<strong>Errore:</strong> '+response);
					$('#err').removeClass('d-none');
				}
			},
			error: function(jqXHR, textStatus, errorThrown) {
				resetAlerts();
				$('#warn').html("<strong>Avviso:</strong> impossibile stabilire una connessione con il server.");
				$('#warn').removeClass('d-none');
			}
		});
		e.preventDefault();
	})
});