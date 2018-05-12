<?php
	namespace MMS;
	require_once '../includes/autoload.php';
	use MMS\Expo as Expo;
	if(isset($_GET['id'])){
		$id = $_GET['id'];
		$expo = new Expo($id);
		$timeslots = $expo->getTimeSlots();
	}else{
		die('Evento non trovato');
	}
	
	function getDay($n){
		switch($n){
			case 1: 
				$day = 'Lunedì';
				break;
			case 2:
				$day = 'Martedì';
				break;
			case 3:
				$day = 'Mercoledì';
				break;
			case 4:
				$day = 'Giovedì';
				break;
			case 5:
				$day = 'Venerdì';
				break;
			case 6:
				$day = 'Sabato';
				break;
			case 7:
				$day = 'Domenica';
				break;
			default:
				$day = 'Errore';
		}
		return $day;
	}
?>
<div class="event-info">
	<div>
		<h2><?=$expo->getName()?></h2>
		<div class="jumbotron pt-3 pb-1 mb-3" style="margin-top: 20px;">
			<div class="container">
				<p class="lead"><?=$expo->getDescription()?></p>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col col-12 col-md-6 col-lg-7">
			<img src="images/covers/<?=md5($id)?>.jpg" class="rounded mb-3" style="width: 100%; height: auto;">
		</div>
		<div class="col col-12 col-md-6 col-lg-5">
			<?php
				echo 'Dal <b>'.date('d-m-Y', strtotime($expo->getStartDate())).'</b> al <b>'.date('d-m-Y', strtotime($expo->getEndDate())).'</b>';
			?>
			<div class="accordion" id="accordion">
				<?php 
					for($i = 1; $i <= 7; $i++){ 
						$day=getDay($i);
				?>
					<div class="card">
						<?php $nSlots = count($timeslots[$i]); ?>
						<div class="card-header" id="heading<?=$day?>" data-toggle="collapse" data-target="#collapse<?=$day?>" aria-expanded="false" aria-controls="collapse<?=$day?>">
							<h5 class="mb-0 text-left">
								<?=$day?> <?=( $nSlots > 0 ? '<span class="badge badge-success float-right">Aperto</span>' : '<span class="badge badge-danger float-right">Chiuso</span>')?>
							</h5>
						</div>
						<?php if($nSlots > 0): ?>
						<div id="collapse<?=$day?>" class="collapse" aria-labelledby="heading<?=$day?>" data-parent="#accordion">
							<div class="card-body">
								<table class="table table-sm table-fixed table-timeslot w-100 mb-0">
									<?php
										foreach($timeslots[$i] as $slot){
											echo'
											<tr>
												<th scope="row">'.$slot->getStartHour().' - '.$slot->getEndHour().'</th>
												<td>'.($expo->getMaxSeats() - $slot->getOccupiedSeats()).' posti disponibili</td>
											</tr>';		
										}
									?>
								</table>
							</div>
						</div>
						<?php endif; ?>
					</div>
				<?php } ?>
			</div>
		</div>
	</div>
	<div class="btn-container-info">
		<button type="button" class="btn btn-secondary discover-btn d-inline ml-1">Chiudi</button>
		<button type="button" class="btn btn-primary d-inline mr-1 eventbuy" eventid="<?=$expo->getId()?>">Acquista biglietto</button>
	</div>
</div>