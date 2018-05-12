<?php
    namespace MMS;
    require_once '../../includes/autoload.php';
    use MMS\Security;
    use MMS\Database;
    Security::init();
    if(!Security::isAdmin()){
        header('Location: signin.php');
    }
    
    //tbody tabella biglietti
    $mysqli = Database::init();
    $tbody = '';
    $table = $mysqli->querySelect('SELECT u.name AS user, b.dateValidity AS dateV, e.name AS event, f.startHour AS timeslot, f.minutes AS durata FROM utente u INNER JOIN (biglietto b INNER JOIN ( fasciaoraria f INNER JOIN evento e ON f.codEvent=e.id ) ON b.codTimeSlot=f.id ) ON b.codUser=u.id ORDER BY YEAR(dateV) DESC, MONTH(dateV) DESC');
    if(count($table) > 0){
        for($i = 0; $i < count($table); $i++){
        	$row = $table[$i];
        	$tbody .= "	<tr>
        					<th scope='row'>".($i+1)."</th>
        					<td>".$row['user']."</td>
        					<td>".$row['dateV']."</td>
        					<td>".$row['event']."</td>
        					<td>".$row['timeslot']."</td>
        					<td>".$row['durata']." min</td>
        				</tr>";
        }
    }else{
        $tbody = "<tr><td>Il Database è vuoto</td><tr>";
    }
?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
	<h1>Biglietti</h1>
</div>
<table class="table">
	<thead class="thead-dark">
		<th>#</th>
		<th>Utente</th>
		<th>Data Validit&agrave;</th>
		<th>Evento</th>
		<th>Fascia Oraria</th>
		<th>Durata Evento</th>
	</thead>
	<tbody>
		<?php echo $tbody; ?>
	</tbody>
</table>
