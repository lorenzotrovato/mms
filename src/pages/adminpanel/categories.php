<?php
    namespace MMS;
    require_once '../../includes/autoload.php';
    use MMS\Security;
    use MMS\Database;
    Security::init();
    if(!Security::isAdmin()){
        header('Location: signin.php');
    }
    
    //tbody tabella categorie
    $mysqli = Database::init();
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
	<h1>Categorie</h1>
</div>
<h2><small class="text-muted">Inserisci una categoria di utenti</small></h2>
<div id="formSuc" class="alert alert-success d-none col-12 col-sm-12 col-md-12 col-lg-12 col-xl-6"></div>
<div id="formErr" class="alert alert-danger d-none col-12 col-sm-12 col-md-12 col-lg-12 col-xl-6"></div>
<form id="insertCategory">
    <div class="form-row">
        <div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6">
            <div class="form-group">
                <label for="nameCat">Nome della categoria</label>
                <input type="text" class="form-control" id="nameCat" placeholder="Nome">
            </div>
        </div>
        <div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6">
            <div class="form-group">
                <label for="discountCat">Sconto applicato alla categoria (%)</label>
                <input type="number" class="form-control" id="discountCat" step="1" placeholder="Sconto applicato" min="1" max="100">
            </div>
        </div>
    </div>
    <div class="form-row">
        <div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6">
			<div class="form-group">
				<label for="docTypeCat">Documento da esibire</label>
				<div class="input-group">
					<input type="text" class="form-control" id="customDocTypeCat" placeholder="Nuovo documento">
					<select class="form-control custom-select" id="docTypeCat">
					</select>
				</div>
			</div>
		</div>
		<div class="col-6 col-sm-6 col-md-6 col-lg-3 col-xl-3">
		    <label for="btnResetCategory" class="invisible">Resetta</label>
			<button class="btn btn-block btn-danger" id="btnResetCategory">Resetta il form</button>
		</div>
		<div class="col-6 col-sm-6 col-md-6 col-lg-3 col-xl-3">
		    <label for="btnInsertCategory" class="invisible">Inserisci</label>
			<button class="btn btn-block btn-primary" id="btnInsertCategory">Inserisci categoria</button>
		</div>
    </div>
</form>
<hr class="invisible">
<h1 class="h2"><small class="text-muted">Modifica o visualizza una categoria già inserita</small></h1>
<div id="delSuc" class="alert alert-success d-none col-12 col-sm-12 col-md-12 col-lg-12 col-xl-6"></div>
<div id="delErr" class="alert alert-danger d-none col-12 col-sm-12 col-md-12 col-lg-12 col-xl-6"></div>
<div id="actSuc" class="alert alert-success d-none col-12 col-sm-12 col-md-12 col-lg-12 col-xl-6"></div>
<div id="actErr" class="alert alert-danger d-none col-12 col-sm-12 col-md-12 col-lg-12 col-xl-6"></div>
<div id="editSuc" class="alert alert-success d-none col-12 col-sm-12 col-md-12 col-lg-12 col-xl-6"></div>
<div id="editErr" class="alert alert-danger d-none col-12 col-sm-12 col-md-12 col-lg-12 col-xl-6"></div>
<table id="catTable" class="table d-none">
	<thead class="thead-dark">
		<th>#</th>
		<th>Nome categoria</th>
		<th>Sconto applicato [%]</th>
		<th>Documento da esibire</th>
		<th></th>
	</thead>
	<tbody id="catTableRows">
	</tbody>
</table>

<!-- modal eliminazione -->
<div class="modal fade" id="modalDeleteCat" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Disabilita categoria</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Chiudi">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body" id="deleteCatModalBody">

			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Chiudi</button>
				<button type="button" id="deleteCatModalBtn" class="btn btn-danger">Disabilita</button>
			</div>
		</div>
	</div>
</div>

<!-- modal riattivazione -->
<div class="modal fade" id="modalActiveCat" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Abilita categoria</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Chiudi">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body" id="activeCatModalBody">
		
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Chiudi</button>
				<button type="button" id="activeCatModalBtn" class="btn btn-success">Abilita</button>
			</div>
		</div>
	</div>
</div>

<!-- modifica categorie -->
<div class="modal fade" id="modalEditCat" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Modifica categoria</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Chiudi">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form id="suggestions" class="mb-0">
				<div class="modal-body" id="catEditModalBody">
					<div class="form-row">
						<div class="form-group col-12">
							<label for="newNameCat">Nome della categoria</label>
							<input type="text" class="form-control" id="newNameCat" placeholder="Nome della categoria">
						</div>
					</div>
					<div class="form-row">
						<div class="form-group col-12">
							<label for="newDiscountCat">Sconto applicato alla categoria (%)</label>
							<input type="number" class="form-control" id="newDiscountCat" placeholder="Sconto applicato alla categoria" step="1" max="100">
						</div>
					</div>
					<div class="form-row">
						<div class="form-group col-12">
							<label for="newDocTypeCat">Documento da esibire</label>
							<div class="input-group">
								<input type="text" class="form-control" id="newCustomDocTypeCat" placeholder="Nuovo documento">
								<select class="form-control custom-select" id="newDocTypeCat">
								</select>
							</div>
						</div>
					</div>
					
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Chiudi</button>
				<button type="button" id="editCatModalBtn" class="btn btn-info">Modifica</button>
				</div>
			</form>
		</div>
	</div>
</div>
<script>
    $(document).ready(function(){
    	loadCatTable();
    	loadDocTypes(3);
        $('#btnResetCategory').click(function(e) {
			e.preventDefault();
			resetForm();
		});
		
		$('#customDocTypeCat').change(function(){
			if ($(this).val().length > 0){
				$('#docTypeCat').attr('disabled','disabled');
			}else{
				$('#docTypeCat').removeAttr('disabled');
			}
		});
		
		$('#docTypeCat').change(function(){
			if ($(this).val() != 'Scegli un documento'){
				$('#customDocTypeCat').attr('disabled','disabled');
			}
		});
		
		$('#newCustomDocTypeCat').change(function(){
			if ($(this).val().length > 0){
				$('#newDocTypeCat').attr('disabled','disabled');
			}else{
				$('#newDocTypeCat').removeAttr('disabled');
			}
		});
		
		$('#newDocTypeCat').change(function(){
			console.log($(this).val());
			if ($(this).val() != 'Scegli un documento' && $(this).val() != 'Altro'){
				$('#newCustomDocTypeCat').attr('disabled','disabled');
			}else{
				$('#newCustomDocTypeCat').removeAttr('disabled');
			}
		});
		
		
		$('#btnInsertCategory').click(function(e){
            e.preventDefault();
            if(!isFormFilled()){
                $('#formErr').html('<strong>Errore</strong>, compila prima tutti i campi. <a class="float-right close closeErr"><span aria-hidden="true">&times;</span></a>');
				$('#formErr').removeClass('d-none');
				$('.closeErr').click(function() {
					$(this).parent().addClass('d-none');
				});
				$('#insertCategory .border.border-danger').change(function() {
					$(this).removeClass('border border-danger');
				});
            }else{
            	//controllo se il valore del campo di testo è presente nei valori del select
            	var values = getOptions();
            	var dt = '';
            	if ($('#customDocTypeCat').val() != ''){
            		var index = $.inArray($('#customDocTypeCat').val(),values);
            		if (index > 0){
            			dt = values[index];
            		}else{
            			dt = $('#customDocTypeCat').val();
            		}
            	}else{
            		dt = $('#docTypeCat').val();
            	}
                $.ajax({
                    type: "GET",
                    cache: false,
                    url : './includes/router.php',
                    data: {
                        name: $('#nameCat').val(),
                        discount: $('#discountCat').val(),
                        docType: dt,
                        action: 'addCategory'
                    },
                    success: function(data){
                        if (data == 'success'){
                            $('#formSuc').html('Categoria inserita con <strong>successo</strong>.<a class="float-right close closeSuc"><span aria-hidden="true">&times;</span></a>');
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
                        loadCatTable();
                        loadDocTypes(3);
                    },
                    error: function(e){
                    	console.log(e);
                    }
                });
            }
        });
        
        $('#deleteCatModalBtn').click(function(){
        	$.ajax({
				type: 'GET',
				cache: false,
				url: './includes/router.php',
				data: {
					action: 'deleteCat',
					id: $('#deleteCatModalBtn').data('id')
				},
				success : function(data) {
					if(data == 'success') {
						$('#delSuc').html('Categoria disabilitata con <strong>successo</strong>.<a class="float-right close closeDelSuc"><span aria-hidden="true">&times;</span></a>');
						$('#delSuc').removeClass('d-none');
						$('.closeDelSuc').click(function() {
							$(this).parent().addClass('d-none');
						});
					}else{
						$('#delErr').html('<strong>Errore</strong>, nella disabilitazione della categoria <a class="float-right close closeDelErr"><span aria-hidden="true">&times;</span></a>');
						$('#delErr').removeClass('d-none');
						$('.closeDelErr').click(function() {
							$(this).parent().addClass('d-none');
						});
	                }
	                loadCatTable();
	                $('#modalDeleteCat').modal('hide');
	            },
	            error : function(e) {
	            	console.log(e);
	            }
			});
        });
        
        $('#activeCatModalBtn').click(function(){
        	$.ajax({
        		type: 'GET',
        		cache: false,
        		url: './includes/router.php',
        		data: {
        			action: 'activeCat',
        			id: $('#activeCatModalBtn').data('id')
        		},
        		success : function(data) {
					if(data == 'success') {
						$('#actSuc').html('Categoria riattivata con <strong>successo</strong>.<a class="float-right close closeActSuc"><span aria-hidden="true">&times;</span></a>');
						$('#actSuc').removeClass('d-none');
						$('.closeActSuc').click(function() {
							$(this).parent().addClass('d-none');
						});
					}else{
						console.log(data);
						$('#actErr').html('<strong>Errore</strong>, nella riattivazione della categoria. <a class="float-right close closeActErr"><span aria-hidden="true">&times;</span></a>');
						$('#actErr').removeClass('d-none');
						$('.closeActErr').click(function() {
							$(this).parent().addClass('d-none');
						});
	                }
	                loadCatTable();
	                $('#modalActiveCat').modal('hide');
	            },
	            error : function(e) {
	            	console.log(e);
	            }
        	});
        });
        
        $('#editCatModalBtn').click(function(){
        	if (isEditCatFormFilled()){
        		var values = getOptions();
            	var newDt = '';
            	if ($('#newCustomDocTypeCat').val() != ''){
            		var index = $.inArray($('#newCustomDocTypeCat').val(),values);
            		if (index > 0){
            			newDt = values[index];
            		}else{
            			newDt = $('#newCustomDocTypeCat').val();
            		}
            	}else{
            		newDt = $('#newDocTypeCat').val();
            	}
        		$.ajax({
        			type: 'GET',
        			cache: false,
        			url: './includes/router.php',
        			data: {
        				action: 'editCat',
        				id: $('#editCatModalBtn').data('id'),
        				newName: $('#newNameCat').val(),
        				newDiscount: $('#newDiscountCat').val(),
        				newDocType: newDt
        			},
        			success: function(data){
        				if(data == 'success') {
							$('#editSuc').html('Categoria modificata con <strong>successo</strong>.<a class="float-right close closeEditSuc"><span aria-hidden="true">&times;</span></a>');
							$('#editSuc').removeClass('d-none');
							$('.closeEditSuc').click(function() {
								$(this).parent().addClass('d-none');
							});
						}else{
							$('#editErr').html('<strong>Errore</strong>, nella modifica della categoria <a class="float-right close closeEditErr"><span aria-hidden="true">&times;</span></a>');
							$('#editErr').removeClass('d-none');
							$('.closeEditErr').click(function() {
								$(this).parent().addClass('d-none');
							});
		                }
        				loadCatTable();
	                	$('#modalEditCat').modal('hide');
        			},
        			error: function(e){
        				console.log(e);
        			}
        		});
        	}
        });
    });
	
	function resetForm() {
		$('#insertCategory input, #insertCategory select').each(function() {
			$(this).val($(this).prop('defaultValue'));
			$(this).removeAttr('disabled');
		});
		removeRed();
	}
	
	function isFormFilled() {
		var itIs = true;
		if (!$('#nameCat').val()){
			$('#nameCat').addClass('border border-danger');
			itIs = false;
		}
		if (!$('#discountCat').val()){
			$('#discountCat').addClass('border border-danger');
			itIs = false;
		}
		if (!($('#docTypeCat').val() || $('#customDocTypeCat').val())){
			$('#docTypeCat').addClass('border border-danger');
			$('#customDocTypeCat').addClass('border border-danger');
			itIs = false;
		}
		return itIs;
	}
	
	function isEditCatFormFilled() {
		var itIs = true;
		if (!$('#newNameCat').val()){
			$('#newNameCat').addClass('border border-danger');
			itIs = false;
		}
		if (!$('#newDiscountCat').val()){
			$('#newDiscountCat').addClass('border border-danger');
			itIs = false;
		}
		if (!($('#newDocTypeCat').val() || $('#newCustomDocTypeCat').val())){
			$('#newDocTypeCat').addClass('border border-danger');
			$('#newCustomDocTypeCat').addClass('border border-danger');
			itIs = false;
		}
		return itIs;
	}
	
	function removeRed() {
		$('#formErrIns').addClass('d-none');
		$('#insertCategory .border.border-danger').each(function() {
			$(this).removeClass('border border-danger');
		});
	}
	
	function loadCatTable(){
		$.ajax({
			type: "GET",
			cache: false,
			url: "./includes/router.php",
			data: {
				action: 'catTable'
			},
			success: function(data){
				$('#catTableRows').html(data);
				$('#catTable').removeClass('d-none');
				feather.replace();
			},
			error : function(e) {
            	console.log(e);
            }
		});
	}
	
	function loadDocTypes(editing, callback){
		$.ajax({
			type: 'GET',
			cache: false,
			url: "./includes/router.php",
			data: {
				action: 'loadDocTypes',
				edit: editing
			},
			success: function(op){
				var opt = JSON.parse(op);
				if (opt.edit == 2){
					$('#newDocTypeCat').html(opt.options);
					callback();
				}else{
					$('#docTypeCat').html(opt.options);
				}
			},
			error : function(e) {
            	console.log(e);
            }
		});
	}
	
	function getOptions(){
		values = [];
		$("#docTypeCat option").each(function(){
			values.push($(this).val());
		});
		return values;
	}
	
	function loadDeleteCatModal(id){
		$('#deleteCatModalBtn').data('id',id);
		$('#deleteCatModalBody').html('Sei sicuro di voler eliminare la categoria "' + $('#' + id + 'cattitle').text() + '"?');
		$('#modalDeleteCat').modal('show');
	}
	
	function catActivation(id){
		$('#activeCatModalBtn').data('id',id);
		$('#activeCatModalBody').html('Sei sicuro di voler riattivare la categoria "' + $('#' + id + 'cattitle').text() + '"?');
		$('#modalActiveCat').modal('show');
	}
	
	function loadEditCatModal(id){
		$('#editCatModalBtn').data('id',id);
		$('#newNameCat').val($('#'+id+'cattitle').text());
		$('#newDiscountCat').val($('#'+id+'catdiscount').text());
		loadDocTypes(2, function(){
			$('.docTypeOption').each(function(i){
				if($(this).text() == $('#'+id+'catdoctype').text()){
					$(this).attr('selected', true);
					return false;
				}
			});
			$('#modalEditCat').modal('show');		
		});
	}
</script>
<!-- Icons -->
<script src="./js/feather.min.js"></script>
<script>
    feather.replace();
</script>