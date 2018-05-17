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
<script>
    $(document).ready(function(){
    	loadCatTable();
    	loadDocTypes();
        $('#btnResetCategory').click(function(e) {
			e.preventDefault();
			resetForm();
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
                        loadDocTypes();
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
		});
		removeRed();
	}
	
	//si può sistemare o no? comunque con la soluzione drastica funziona perfettamente
	function isFormFilled() {
		var itIs = true;
		/*$('#insertCategory input, #insertCategory select').each(function() {
			if(!$(this).val()){
				$(this).addClass('border border-danger');
				itIs = false;
			}
		});
		
			soluzione drastica
		*/
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
	
	function loadDocTypes(){
		$.ajax({
			type: 'GET',
			cache: false,
			url: "./includes/router.php",
			data: {
				action: 'loadDocTypes'
			},
			success: function(options){
				$('#docTypeCat').html(options);
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
</script>
<!-- Icons -->
<script src="./js/feather.min.js"></script>
<script>
    feather.replace();
</script>
