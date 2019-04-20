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
</style>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
	<h1>Utenti</h1>
</div>

<h2><small class="text-muted">Nomina amministratori o declassa a utente</small></h2>
<div id="editUsersErr" class="alert col-12 col-md-6 offset-md-3 d-none text-center alert-danger"></div>
<div class="table-responsive">
	<table class="table">
		<thead class="thead-dark">
			<th>#</th>
			<th>Username</th>
			<th>Email</th>
			<th>Ruolo</th>
			<th></th>
		</thead>
		<tbody id="tableUsersList">
			<tr>
				<td>0</td>
				<td><strong>Nessun utente</strong></td>
				<td>N/D</td>
				<td>N/D</td>
				<td></td>
			</tr>
		</tbody>
	</table>
</div>

<script>
    $(document).ready(function(){
        loadTableUsers();
    });
    
    function loadTableUsers(){
        $('#editUsersErr').html('');
        $('#editUsersErr').addClass('d-none');
        $.ajax({
    		type: "GET",
    		cache: false,
    		url: "./includes/router.php?action=loadTableUsers",
    		success: function(response) {
    			$('#tableUsersList').html(response);
    			feather.replace();
    			$('.editUserBtn').on('click',function(){
                    var userid = $(this).attr('data-userid');
                    var userrole = $(this).attr('data-userrole');
                    changeUserRole(userid, userrole);
                });
    		},
    		error: function() {
                $('#editUsersErr').html('<strong>Avviso</strong>: impossibile caricare i dati');
                $('#editUsersErr').removeClass('d-none');
    		}
    	});
    }
    
    function changeUserRole(userid, oldrole){
        $.ajax({
    		type: "GET",
    		cache: false,
    		url: "./includes/router.php",
    		data: {"action": "editUserRole","userid":userid,"oldrole":oldrole},
    		success: function(response) {
    			if(response == 'success-edit'){
    			    if(oldrole != 1){
    			        $('#'+userid+'username').removeClass('text-primary');
    			        $('#'+userid+'userrole').text('Utente normale');
    			        $('#'+userid+'usereditbtn .editUserBtn .cursorHand').html("<span data-feather='chevrons-up' class='text-success'></span>");
    			        $('#'+userid+'usereditbtn .editUserBtn').attr('data-userrole',1);
    			    }else{
    			        $('#'+userid+'username').addClass('text-primary');
    			        $('#'+userid+'userrole').text('Amministratore');
    			        $('#'+userid+'usereditbtn .editUserBtn .cursorHand').html("<span data-feather='chevrons-down' class='text-warning'></span>");
    			        $('#'+userid+'usereditbtn .editUserBtn').attr('data-userrole',2);
    			    }
    			    feather.replace();
    			}else{
    			    $('#editUsersErr').html('<strong>Avviso</strong>: ' + response);
                    $('#editUsersErr').removeClass('d-none');
    			}
    		},
    		error: function() {
                $('#editUsersErr').html('<strong>Avviso</strong>: impossibile caricare i dati');
                $('#editUsersErr').removeClass('d-none');
    		}
    	});
    }
</script>