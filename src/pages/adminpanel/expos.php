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

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
	<h1>Esposizioni</h1>
</div>
<h2><small class="text-muted" id="titleCreateEditExpo">Crea un'esposizione</small></h2>
<div id="formErrExpo" class="alert col-12 col-md-6 offset-md-3 d-none text-center"></div>
<form id="insertExpo">
	<div class="form-row">
		<div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6">
			<div class="form-group">
				<label for="nameExpo">Nome dell'esposizione</label>
				<input type="text" class="form-control" id="nameExpo" placeholder="Nome">
			</div>
		</div>
		<div class="col-12 col-sm-12 col-md-6 col-lg-3 col-xl-3">
			<div class="form-group">
				<label for="dateStartExpo">Data di inizio</label>
				<input type="date" class="form-control" id="dateStartExpo">
			</div>
		</div>
		<div class="col-12 col-sm-12 col-md-3 col-lg-3 col-xl-3">
			<div class="form-group">
				<label for="dateEndExpo">Data di fine</label>
				<input type="date" class="form-control" id="dateEndExpo">
			</div>
		</div>
	</div>
	<div class="form-row">
		<div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6">
			<div class="form-group">
				<label for="descExpo">Descrizione</label>
				<textarea class="form-control" rows="2" id="descExpo" placeholder="Descrizione dell'esposizione"></textarea>
			</div>
		</div>
		<div class="col-12 col-sm-12 col-md-3 col-lg-3 col-xl-3">
			<div class="form-group">
				<label for="priceExpo">Prezzo biglietto base (&euro;)</label>
				<input type="number" class="form-control" id="priceExpo" placeholder="Prezzo biglietto" step=".01">
			</div>
		</div>
		<div class="col-12 col-sm-12 col-md-3 col-lg-3 col-xl-3">
			<div class="form-group">
				<label for="maxSeatsExpo">Numero massimo di posti/fascia oraria</label>
				<input type="number" class="form-control" id="maxSeatsExpo" placeholder="Posti massimi per fascia oraria" step="1" min="1">
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
			<div id="rangetimeslots"></div>
			<h6 id="rangetimeslotslabel">Orari di apertura<br>&nbsp;&nbsp;Seleziona un giorno</h6>
		</div>
		<div class="col-sm-1 col-md-1 col-lg-1 col-xl-1">
			<span data-feather='plus' id="rangetimeslotsadd" class='text-info cursorHand' style="display:none;"></span>
		</div>
	</div>
	<br>
	<div class="form-row border">
		<div class="col-12">
			<h5><small class="text-muted">Modifica immagine di copertina</small></h5>
		</div>
		<br>
		<div class="col-12 col-sm-12 col-md-6 col-xl-2">
		  	<div class="custom-file">
			    <input type="file" class="custom-file-input" id="fileImageExpo" required>
			    <label class="custom-file-label" for="fileImageExpo">Scegli file...</label>
		  	</div>
		</div>
		<div class="col-12 col-sm-12 col-md-6 col-xl-2">
			<img src="" alt="Immagine esposizione" id="expoImage" class="d-none">
			<strong id="expoImagePrev" class="">Nessuna immagine</strong>
		</div>
		<div class="col-12 col-sm-12 col-md-12 col-xl-8">
			<h6 id="expoImageLoadLabel">Caricamento immagine: in attesa</h6>
			<div class="progress">
			  	<div class="progress-bar" id="expoImageLoadProg" style="width:00%"></div>
			</div>
			<br>
		</div>
	</div>
	<br>
	<div class="form-row">
		<div class="col-12 text-center">
			<button class="btn btn-danger m-1" id="btnResetExpoForm" style="width: 300px;">Resetta il form</button>
			<button class="btn btn-primary m-1" id="btnInsertExpo" style="width: 300px;">Inserisci esposizione</button>
		</div>
	</div>
	</div>
</form>
<br>
<br>
<h2><small class="text-muted">Modifica e visualizza le esposizioni</small></h2>
<div id="deleteExpoErr" class="alert col-12 col-md-6 offset-md-3 d-none text-center"></div>
<div class="table-responsive">
	<table class="table">
		<thead class="thead-dark">
			<th>#</th>
			<th>Nome</th>
			<th>Descrizione</th>
			<th>Data d'inizio</th>
			<th>Data di fine</th>
			<th>Prezzo base [€]</th>
			<th>Posti massimi</th>
			<th></th>
			<th></th>
		</thead>
		<tbody id="tableExpoList">
			<tr>
				<td>0</td>
				<td><strong>Nessuna esposizione</strong></td>
				<td>N/D</td>
				<td>N/D</td>
				<td>N/D</td>
				<td>N/D</td>
				<td>N/D</td>
				<td></td>
				<td></td>
			</tr>
		</tbody>
	</table>
</div>

<div class="modal fade" id="modalDeleteExpo" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Elimina esposizione</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Chiudi">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body" id="deleteExpoModalBody">

			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Chiudi</button>
				<button type="button" id="deleteExpoModalBtn" class="btn btn-danger">Elimina</button>
			</div>
		</div>
	</div>
</div>

<script src="js/nouislider.min.js"></script>
<!-- Icons -->
<script src="./js/feather.min.js"></script>
<script src="./js/expos.js"></script>

<script>
	
</script>