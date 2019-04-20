<?php
	namespace MMS;
    require_once '../../includes/autoload.php';
    use MMS\Security;
    use MMS\Database;
    use MMS\User as User;
    Security::init();
    if(!Security::isAdmin()){
        header('Location: signin.php');
    }
?>

<style>
    .cursorHand {
		cursor: pointer;
	}
	@media only screen and (max-width: 768px) {
	    #qrTicketCodePreview{
			width: 85vw;
			height: 50vh;
		}
	}
	
	@media only screen and (max-width: 1200px) {
	    #qrTicketCodePreview{
			width: 65vw;
			height: 50vh;
		}
	}
	
	@media only screen and (min-width: 1201px) {
	    #qrTicketCodePreview{
			width: 35vw;
			height: 50vh;
		}
	}
	
</style>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
	<h1>Validazione biglietti</h1>
</div>

<div class="row">
	<div class="col-12 col-sm-12 col-xl-6">
		<h2><small class="text-muted">Verifica biglietto manualmente</small></h2>
		<form id="checkTicketForm">
			<div class="form-row">
				<div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6">
					<div class="form-group">
						<input type="text" class="form-control" id="idTicket" placeholder="0000-0000-0000-0000-0000-0000-0000-0000">
					</div>
				</div>
				<div class="col-12 col-sm-12 col-md-6 col-lg-3 col-xl-2">
					<button class="btn btn-primary" id="btnVerTicket">Verifica</button>
				</div>
				<div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6">
					<label class="text-muted float-right" id="idTicketCharCount">0/32</label>
				</div>
			</div>
		</form>
		<br><br>
		<h6 id="ticketVerMessage">In attesa...</h6>
		<div class="border">
			<h6>Data di validità: <span id="ticketDateVal">00/00/0000</span></h6>
			<h6>Data di acquisto: <span id="ticketDatePrc">00/00/0000</span></h6>
			<h6>Fascia oraria: <span id="ticketTimeSlot">00:00 - 24:00</span></h6>
			<h6>Accessori: <ul id="ticketAccessories">
				
			</ul></h6>
		</div>
	</div>
	<div class="col-12 col-sm-12 col-xl-6">
		<h2><small class="text-muted">Verifica biglietto con QR</small></h2>
		<h6 id="qrScanMessages"></h6>
		<div class="col-12 col-sm-12 align-items-center">
			<video id="qrTicketCodePreview" class="border"></video>
		</div>
	</div>
</div>

<script type="text/javascript" src="./js/instascan.min.js"></script>
<script>
	var charcount = 1;
	$('#idTicket').on('keyup',function(e){
		var v = $(this).val().replace(/ /g,'');
		charcount = $(this).val().replace(new RegExp('-', 'g'), '').length;
		if(charcount>32){
			$(this).val(v.substring(0,39));
		}
		if(v.endsWith('-')){
			$(this).val(v.substring(0,v.length-1));
		}
		if(e.keyCode!=8 && e.keyCode!=46){
			if(v.length!=0 && charcount<32){
				if(((v.length+1) % 5)==0){
					v+="-";
				}
				$(this).val(v);
			}
		}
		$('#idTicketCharCount').text(charcount + "/32");
	});
	
	function sendVer(key){
		resetTicketInfo();
		$.ajax({
			type: "GET",
			cache: false,
			url: "./includes/router.php",
			data: {"action":"verTicketKey","key":key},
			success: function(response) {
				if(response.startsWith('{')){
					var data = JSON.parse(response);
					loadTicketInfo(data);
					$('#ticketVerMessage').html('<strong>Biglietto verificato</strong>');
					$('#ticketVerMessage').addClass('text-success');
				}else{
					$('#ticketVerMessage').html('<strong>Errore: '+response+'</strong>');
					$('#ticketVerMessage').addClass('text-danger');
				}
			},
			error: function() {
				
			}
    	});
	}
	
	$('#btnVerTicket').on('click',function(){
		var v = $('#idTicket').val().replace(new RegExp('-', 'g'), '');
		if(v.length){
			sendVer(v);
		}else{
			
		}
		console.log(v);
		return false;
	});
	
	function loadTicketInfo(data){
		$('#ticketDateVal').text(data.dateValidity);
		$('#ticketDatePrc').text(data.datePurchase);
		$('#ticketTimeSlot').text(data.timeSlot.startHour + " - " + data.timeSlot.endHour);
		$('#ticketAccessories').html("");
		for(var i=0;i<data.accessories.length;i++){
			$('#ticketAccessories').html($('#ticketAccessories').html() + '<li>' + data.accessories[i].qta + 'x ' + data.accessories[i].name + ' - €' + data.accessories[i].price + '</li>');
		}
	}
	
	function resetTicketInfo(){
		$('#ticketVerMessage').removeClass('text-danger');
		$('#ticketVerMessage').removeClass('text-success');
		$('#ticketVerMessage').html("In attesa...");
		$('#ticketDateVal').text("00/00/0000");
		$('#ticketDatePrc').text("00/00/0000");
		$('#ticketTimeSlot').text("00:00 - 00:00");
		$('#ticketAccessories').html("");
	}
	
	let scanner = new Instascan.Scanner({ video: document.getElementById('qrTicketCodePreview'), mirror: false});
	scanner.addListener('scan', function (content) {
		console.log(content);
		if(content.length==32){
			//invia verifica
			sendVer(content);
			$('#idTicket').val(content);
		}
	});
	Instascan.Camera.getCameras().then(function (cameras) {
		if (cameras.length > 0) {
			scanner.start(cameras[0]);
		} else {
			$('#qrScanMessages').text('Nessuna videocamera/webcam disponibile');
			$('#qrScanMessages').addClass('text-danger');
		}
	}).catch(function (e) {
		console.error(e);
		$('#qrScanMessages').text('Errore:' +e.message);
		$('#qrScanMessages').addClass('text-danger');
	});
</script>