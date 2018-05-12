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
				<select class="form-control custom-select" id="docTypeCat">
				    <option disabled selected value> Scegli </option>
					<option>Carta d'identità</option>
					<option>Carta del museo</option>
					<!-- altri documenti... -->
				</select>
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
        $('#btnResetCategory').click(function(e) {
			e.preventDefault();
			resetForm();
			//loadCatList();
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
                $.ajax({
                    type: "GET",
                    cache: false,
                    url : './includes/router.php',
                    data: {
                        name: $('#nameCat').val(),
                        discount: $('#discountCat').val(),
                        docType: $('#docTypeCat').val(),
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
	
	function isFormFilled() {
		var itIs = true;
		$('#insertCategory input, #insertCategory select').each(function() {
			if(!$(this).val() && $(this).attr('id') != 'returnableAcc'){
				$(this).addClass('border border-danger');
				itIs = false;
			}
		});
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
</script>
<!-- Icons -->
<script src="./js/feather.min.js"></script>
<script>
    feather.replace();
</script>
