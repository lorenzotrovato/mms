<?php
	namespace MMS;
    require_once '../../includes/autoload.php';
    use MMS\Security;
    use MMS\Database;
    use MMS\Expo as Expo;
    Security::init();
    if(!Security::isAdmin()){
        header('Location: signin.php');
    }
   
?>
<link href="css/nouislider.min.css" rel="stylesheet" type="text/css" />
<style>
	.cursorHand {
		cursor: pointer;
	}
	#showcase {
		margin: 0 20px;
		text-align: center;
	}
	
	#range {
		height: 15px;
		margin: 0 auto 30px;
	}
	
	#value-span,
	#value-input {
		width: 50%;
		float: left;
		display: block;
		text-align: center;
		margin: 0;
	}
	
	.timeslotClosed {
		background-color: #ef9a9a;
	}
	
	.timeslotOpen {
		background-color: #a5d6a7;
	}
	
	.fileInput{
		border: 1px solid #bec5cc;
	}
</style>
<?php
	$visita = new Expo(0);
?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
	<h1>Visita</h1>
</div>
<h2><small class="text-muted" id="titleCreateEditExpo">Modifica impostazioni e orari</small></h2>
<div id="formErrExpo" class="alert col-12 col-md-6 offset-md-3 d-none text-center"></div>
<form id="insertExpo">
	<div class="form-row">
		<div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6">
			<div class="form-group">
				<label for="descExpo">Descrizione</label>
				<textarea class="form-control" rows="2" id="descExpo" placeholder="Descrizione della visita generica"><?php echo $visita->getDescription(); ?></textarea>
			</div>
		</div>
		<div class="col-12 col-sm-12 col-md-3 col-lg-3 col-xl-3">
			<div class="form-group">
				<label for="priceExpo">Prezzo biglietto base (&euro;)</label>
				<input type="number" class="form-control" id="priceExpo" placeholder="Prezzo biglietto" step=".01" value="<?php echo $visita->getPrice(); ?>">
			</div>
		</div>
		<div class="col-12 col-sm-12 col-md-3 col-lg-3 col-xl-3">
			<div class="form-group">
				<label for="maxSeatsExpo">Numero massimo di posti/fascia oraria</label>
				<input type="number" class="form-control" id="maxSeatsExpo" placeholder="Posti massimi per fascia oraria" step="1" min="1" value="<?php echo $visita->getMaxSeats(); ?>">
			</div>
		</div>
	</div>
	<div class="form-row border">
		<div class="col-12">
			<h5><small class="text-muted">Aggiungi orari di apertura (fasce orarie)</small></h5>
		</div>
		<br>
		<div class="col-sm-12 col-md-3 col-lg-2 col-xl-2">
			<div class="form-row">
				<select class="custom-select" id="timeslotsdayselect">
					<option value="0" selected>Seleziona il giorno</option>
					<option value="1">Lunedì</option>
					<option value="2">Martedì</option>
					<option value="3">Mercoledì</option>
					<option value="4">Giovedì</option>
					<option value="5">Venerdì</option>
					<option value="6">Sabato</option>
					<option value="7">Domenica</option>
				</select>
			</div>
			<div class="form-row">
				<br>
				<div class="custom-control custom-checkbox" style="display:none;" id="tsCopyAllCont">
	        		<input id="timeslotCopyAll" type="checkbox" value="tsCopyAll" class="custom-control-input" >
	       			<label class="custom-control-label" for="timeslotCopyAll">Copia per tutti</label>
	      		</div>
			</div>
			
		</div>
		<div class="col-sm-11 col-md-7 col-lg-8 col-xl-8 offset-md-1">
			<div id="rangetimeslots"></div><br>
			<h6 id="rangetimeslotslabel">Orari di apertura<br>&nbsp;&nbsp;Seleziona un giorno</h6>
		</div>
		<div class="col-sm-1 col-md-1 col-lg-1 col-xl-1">
			<span data-feather='plus' id="rangetimeslotsadd" class='text-info cursorHand' style="display:none;"></span>
		</div>
	</div>
	<br>
	<div class="form-row">
		<div class="col-12 text-center">
			<button class="btn btn-danger m-1" id="btnResetExpoForm" style="width: 300px;">Resetta il form</button>
			<button class="btn btn-primary m-1" id="btnInsertExpo" style="width: 300px;">Modifica Visita</button>
		</div>
	</div>
	</div>
</form>

<script src="js/nouislider.min.js"></script>
<!-- Icons -->
<script src="./js/feather.min.js"></script>
<script src="./js/visit.js"></script>