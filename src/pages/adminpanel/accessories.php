<?php
    namespace MMS;
    require_once '../../includes/autoload.php';
    use MMS\Security as Security;
    use MMS\Database as Database;
    use MMS\Accessory as Accessory;
    Security::init();
    if(!Security::isAdmin()){
        header('Location: signin.php');
    }
?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
	<h1>Accessori</h1>
</div>
<h2><small class="text-muted" id="addEditAcc">Inserisci un accessorio</small></h2>
<div id="formSuc" class="alert alert-success d-none col-12 col-sm-12 col-md-12 col-lg-12 col-xl-6"></div>
<div id="formErr" class="alert alert-danger d-none col-12 col-sm-12 col-md-12 col-lg-12 col-xl-6"></div>
<form id="insertAcc">
	<div class="form-row">
		<div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6">
			<div class="form-group">
				<label for="nameAcc">Nome dell'accessorio</label>
				<input type="text" class="form-control" id="nameAcc" placeholder="Nome">
			</div>
		</div>
		<div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6">
			<div class="form-group">
				<label for="typeAcc">Tipo di accessorio</label>
				<select class="form-control custom-select" id="typeAcc">
					<option disabled selected value> Scegli </option>
					<option class="text-primary font-weight-bold" value="servizio">Servizio</option>
					<option class="text-warning font-weight-bold" value="accessorio">Accessorio</option>
				</select>
			</div>
		</div>
	</div>
	<div class="form-row">
		<div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6">
			<div class="form-group">
				<label for="priceAcc">Prezzo del singolo accessorio (&euro;)</label>
				<input type="number" class="form-control" id="priceAcc" placeholder="Prezzo accessorio" step="1">
			</div>
		</div>
		<div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6">
			<div class="form-group">
				<label for="nAvailableAcc">Numero di accessori disponibili</label>
				<input type="number" class="form-control" id="nAvailableAcc" placeholder="Massimo accessori" step="1">
			</div>
		</div>
	</div>
	<div class="form-row">
		<div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6">
			<div class="custom-control custom-checkbox mr-sm-2">
        		<input type="checkbox" class="custom-control-input" id="returnableAcc">
        		<label class="custom-control-label" for="returnableAcc">Accessorio da restituire</label>
    		</div>
		</div>
		<div class="col-6 col-sm-6 col-md-6 col-lg-3 col-xl-3">
			<button class="btn btn-block btn-danger" id="btnResetAccessory">Resetta il form</button>
		</div>
		<div class="col-6 col-sm-6 col-md-6 col-lg-3 col-xl-3">
			<button class="btn btn-block btn-primary" id="btnInsertAccessory">Inserisci accessorio</button>
		</div>
	</div>
</form>
<hr class="invisible">
<h2><small class="text-muted">Accessori già inseriti</small></h2>
<div class="row">
	<div id="delSuc" class="alert alert-success d-none col-12 col-sm-12 col-md-12 col-lg-12 col-xl-6"></div>
	<div id="delErr" class="alert alert-danger d-none col-12 col-sm-12 col-md-12 col-lg-12 col-xl-6"></div>
	<table id="accTable" class="table d-none">
		<thead class="thead-dark">
			<th>#</th>
			<th>Accessorio</th>
			<th>Prezzo [&euro;]</th>
			<th>Disponibilità</th>
			<th class="text-center">Da Restituire</th>
			<th></th>
		</thead>
		<tbody id="accTableRows">
		</tbody>
	</table>
</div>

<div class="modal fade" id="modalDeleteAcc" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Elimina accessorio</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Chiudi">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body" id="deleteAccModalBody">

			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Chiudi</button>
				<button type="button" id="deleteAccModalBtn" class="btn btn-danger">Elimina</button>
			</div>
		</div>
	</div>
</div>

<script>
	$(document).ready(function() {
		loadTable();
		
		$('.accEditBtn').click(function() {
			var row = $(this).parent().parent();
		})
		
		$('#btnResetAccessory').click(function(e) {
			e.preventDefault();
			resetForm();
		});
		
		$('#typeAcc').change(function() {
			var chosen = $(this).val();
		})
		
		$('#btnInsertAccessory').click(function(e) {
			e.preventDefault();
			if(!isFormFilled()){
				$('#formErr').html('<strong>Errore</strong>, compila prima tutti i campi. <a class="float-right close closeErr"><span aria-hidden="true">&times;</span></a>');
				$('#formErr').removeClass('d-none');
				$('.closeErr').click(function() {
					$(this).parent().addClass('d-none');
				});
				$('#insertAcc .border.border-danger').change(function() {
					$(this).removeClass('border border-danger');
				});
			}else{
				$.ajax({
		            type : 'GET',
		            cache : false,
		            url : './includes/router.php',
		            data : {
		                name : $('#nameAcc').val(),
		                type : $('#typeAcc').val(),
		                price : $('#priceAcc').val(),
		                navacc : $('#nAvailableAcc').val(),
		                returnable : $('#returnableAcc').is(':checked'),
		                action : 'addAccessory'
		            },
		            success : function(data) {
		                if(data == 'success') {
		                	$('#formSuc').html('Accessorio inserito con <strong>successo</strong>.<a class="float-right close closeSuc"><span aria-hidden="true">&times;</span></a>');
							$('#formSuc').removeClass('d-none');
							$('.closeSuc').click(function() {
								$(this).parent().addClass('d-none');
							});
							resetForm();
		                }else{
		                	$('#formErr').html('<strong>Errore</strong>, i dati immessi non sono validi. <a class="float-right close closeErr"><span aria-hidden="true">&times;</span></a>');
							$('#formErr').removeClass('d-none');
							$('.closeErr').click(function() {
								$(this).parent().addClass('d-none');
							});
		                }
		                loadTable();
		            },
		            error : function(e) {
		            	console.log('no')
		            }
		        });
			}
		});
		
		$('#deleteAccModalBtn').click(function(){
			$.ajax({
				type: 'GET',
				cache: false,
				url: './includes/router.php',
				data: {
					action: 'deleteAcc',
					id: $('#deleteAccModalBtn').data('id')
				},
				success : function(data) {
					if(data == 'success') {
						$('#delSuc').html('Accessorio eliminato con <strong>successo</strong>.<a class="float-right close closeDelSuc"><span aria-hidden="true">&times;</span></a>');
						$('#delSuc').removeClass('d-none');
						$('.closeDelSuc').click(function() {
							$(this).parent().addClass('d-none');
						});
					}else{
						$('#delErr').html('<strong>Errore</strong>, i dati immessi non sono validi. <a class="float-right close closeDelErr"><span aria-hidden="true">&times;</span></a>');
						$('#delErr').removeClass('d-none');
						$('.closeDelErr').click(function() {
							$(this).parent().addClass('d-none');
						});
	                }
	                loadTable();
	            },
	            error : function(e) {
	            	console.log('no')
	            }
			});
		});
	});
	
	function resetForm() {
		$('#insertAcc input, #insertAcc select').each(function() {
			$(this).val($(this).prop('defaultValue'));	
		});
		removeRed();
	}
	
	function isFormFilled() {
		var itIs = true;
		$('#insertAcc input, #insertAcc select').each(function() {
			if(!$(this).val() && $(this).attr('id') != 'returnableAcc'){
				$(this).addClass('border border-danger');
				itIs = false;
			}
		});
		return itIs;
	}
	
	function removeRed() {
		$('#formErr').addClass('d-none');
		$('#insertAcc .border.border-danger').each(function() {
			$(this).removeClass('border border-danger');
		});
	}
	
	function loadTable(){
		$.ajax({
            type : 'GET',
            cache : false,
            url : './includes/router.php',
            data : {
                action : 'accTable'
            },
            success : function(data) {
            	$('#accTableRows').html(data);
            	$('#accTable').removeClass('d-none');
    			feather.replace();
				$('[data-toggle="tooltip"]').tooltip();
            },
            error : function(e) {
            	console.log('no');
            }
        });
	}
	
	function loadDeleteAccModal(id) {
		$('#deleteAccModalBtn').data('id',id);
		$('#deleteAccModalBody').html('Sei sicuro di voler eliminare l\'accessorio "' + $('#' + id + 'acctitle').text() + '"?');
		$('#modalDeleteAcc').modal('show');
	}
	
	function loadEditAccForm(id){
		
	}
</script>