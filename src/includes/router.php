<?php
	namespace MMS;
	require_once './autoload.php';
	use MMS\Security as Security;
	use MMS\Expo as Expo;
	use MMS\Accessory as Accessory;
	use MMS\Database as DB;
	use MMS\TimeSlot as TimeSlot;
	
	// se non è impostata la variabile che definisce l'azione chiudo lo script
	isset($_GET['action']) ?: die();
	$action = $_GET['action'];
	Security::init();
	switch($action){
		case 'login':
			$user = 'false';
			if(!Security::verSession())
				if(!empty($_GET['userIn']) && !empty($_GET['passIn'])){
					$user = Security::login($_GET['userIn'], $_GET['passIn']);
					$loggedUser = Security::getUserFromSession();
					if($loggedUser){
						if(!empty($_GET['autologin']) && $_GET['autologin']=="true"){
							Security::setAutoLoginCookies($loggedUser);
						}
						if($loggedUser->getRole()>1){
							$user = 'success-admin';
						}
					}
				}else{
					$user = 'devi riempire tutti i campi.';
				}
			echo $user;
			break;
			
		case 'register':
			$user = 'false';
			if(!Security::verSession()) {
				if(!empty($_GET['userIn']) && !empty($_GET['passIn']) && !empty($_GET['mailIn'])){
					$user = Security::register($_GET['userIn'], $_GET['mailIn'], $_GET['passIn'], 0);
				}else{
					$user = 'devi riempire tutti i campi.';
				}
			}
			echo $user;
			break;
			
		case 'vermail':
			if(!empty($_GET['mailuser']) && !empty($_GET['mailkey'])){
				$vermailRes = Security::verMail($_GET['mailuser'],$_GET['mailkey']);
				if($vermailRes===0){
					header('location: ../index.php');
				}elseif($vermailRes===1){
					header('location: ../signin.php?err_mail_ver=1');
				}else{
					header('location: ../signin.php?err_mail_ver=2');
				}
			}else{
				header('location: ../signin.php?err_mail_ver=3');
			}
			break;
		case 'getEventInfo':
			if(!empty($_GET['eventid'])){
				$id=$_GET['eventid'];
				$expo = new Expo($id);
				$array = array(
					'name' => $expo->getName(),
					'startDate' => $expo->getStartDate(),
					'endDate' => $expo->getEndDate(),
					'price' => $expo->getPrice(),
					'timeSlots' => array()
				);
				$timeslots = $expo->getTimeSlots();
				for($i = 1; $i <= 7; $i++){
					$array['timeSlots'][$i] = array();
					foreach($timeslots[$i] as $slot){
						$array['timeSlots'][$i][] = $slot->getArray();
					}
				}
				echo json_encode($array, 1);
			}
			break;
		case 'finTable':
		    if(Security::isAdmin() && isset($_GET['year'])){
			    $mysqli = Database::init();
			    $anno = Security::escape($_GET['year'], 4);
			    $where = "";
			    if($anno != 'all') {
			    	$where = "WHERE YEAR(datePurchase) = '$anno'";
			    }
			    // costruisco la tabella per visualizzare tutti i dati nel dettaglio
			    $queryDatiGrafico = $mysqli->querySelect("SELECT u.name AS utente, b.datePurchase, c.name AS categoria, c.discount, b.totalPrice AS tot FROM utente AS u INNER JOIN biglietto AS b ON u.id = b.codUser LEFT JOIN categoria AS c ON b.codCat = c.id $where ORDER BY b.datePurchase DESC");
			    if(count($queryDatiGrafico) > 0){
			        for($i = 0; $i < count($queryDatiGrafico); $i++) {
			        	$row = $queryDatiGrafico[$i];
			        	echo '<tr>';
			        	echo '<th scope="row">'.($i+1).'</th>';
			        	echo '<td>'.$row['utente'].'</td>';
			        	echo '<td>'.date('d/m/Y H:i:s', strtotime($row['datePurchase'])).'</td>';
			        	echo '<td>'.$row['categoria'].'</td>';
			        	echo '<td>'.$row['discount'].'</td>';
			        	echo '<td>'.(isset($row['discount']) ? ($row['tot'] * $row['discount'] / 100) : $row['tot']).'</td>';
			        	echo '</tr>';
			        }
			    }else{
			    	echo 'il Database è vuoto.';
			    }
		    }
			break;
		case 'finGraph':
			if(!Security::isAdmin()){
		        header('Location: index.php');
		        die();
		    }
		    $mysqli = Database::init();
		    $anno = Security::escape($_GET['year']);
		    $where = "";
		    if($anno != 'all') {
		    	$where = "WHERE YEAR(datePurchase) = '$anno'";
		    }
		    
		    // Totale entrate mensili per anno selezionato
		    $datiGrafico = array(1 => "0",2 => "0",3 => "0",4 => "0",5 => "0",6 => "0",7 => "0",8 => "0",9 => "0",10 => "0",11 => "0",12 => "0");
		    $queryDatiGrafico = $mysqli->querySelect("SELECT SUM(totalPrice) AS tot, discount, DATE_FORMAT(datePurchase,'%Y%m') AS dateP, MONTH(datePurchase) AS month FROM biglietto LEFT JOIN categoria ON biglietto.codCat = categoria.id $where GROUP BY dateP");
		    if(count($queryDatiGrafico) > 0) {
		        foreach($queryDatiGrafico as $bigliettiMese){
		        	$earn = $bigliettiMese['tot'];
		        	if(isset($bigliettiMese['discount'])) {
		        		$earn *= $bigliettiMese['discount']/100;
		        	}
		            $datiGrafico[$bigliettiMese['month']] += $earn;
		        }
		    }
		   	echo json_encode($datiGrafico);
			break;
		case 'addExpo':
			if(empty($_GET['nomeExpo']) || empty($_GET['descExpo']) || empty($_GET['dateStartExpo']) || empty($_GET['dateEndExpo']) || empty($_GET['priceExpo']) || empty($_GET['maxSeatsExpo'])){
				die('riempi tutti i campi');
			}
			$newExpo = Expo::addExpo($_GET['nomeExpo'],$_GET['descExpo'],$_GET['dateStartExpo'],$_GET['dateEndExpo'],$_GET['priceExpo'],$_GET['maxSeatsExpo']);
			if($newExpo instanceof Expo){
				//aggiunta fascie orarie
				if(isset($_GET['timeslots'])){
					try{
						TimeSlot::init();
						foreach($_GET['timeslots'] as $dkey => $day){
							foreach($day as $ts){
								if(!is_null($ts)){
									TimeSlot::addSlot($newExpo->getId(),$ts["startHour"],$ts["minutes"],str_replace("day","",$dkey));
								}
							}
						}
					}catch(\Exception $e){
						die($e->getMessage());
					}
				}
				echo 'success';
			}else{
				echo $newExpo;
			}
			break;
		case 'editExpo':
			if(empty($_GET['idExpo']) || empty($_GET['nomeExpo']) || empty($_GET['descExpo']) || empty($_GET['dateStartExpo']) || empty($_GET['dateEndExpo']) || empty($_GET['priceExpo']) || empty($_GET['maxSeatsExpo'])){
				die('riempi tutti i campi');
			}
			try{
				$expo = new Expo($_GET['idExpo']);
				if($expo instanceof Expo){
					Security::init();
					$mysqli = Database::init();
					$expo->setName(Security::escape($_GET['nomeExpo'],63));
					$expo->setDescription(Security::escape($_GET['descExpo']));
					$expo->setStartDate($_GET['dateStartExpo']);
					$expo->setEndDate($_GET['dateEndExpo']);
					$expo->setPrice(floatval(abs($_GET['priceExpo'])));
					$expo->setMaxSeats(intval(abs($_GET['maxSeatsExpo'])));
					
					//riaggiunta fascie orarie
					if(isset($_GET['timeslots'])){
						$timeslots = $_GET['timeslots'];
						try{
							TimeSlot::init();
							foreach($timeslots as $dkey => $day){
								foreach($day as $ts){
									if(!is_null($ts)){
										$nts = new TimeSlot($ts['id']);
										if($nts instanceof TimeSlot){
											$nts->setStartHour($ts["startHour"]);
											$nts->setMinutes($ts["minutes"]);
											$nts->setDay(str_replace("day","",$dkey));
											$nts->merge();
										}else{
											TimeSlot::addSlot($expo->getId(),$ts["startHour"],$ts["minutes"],str_replace("day","",$dkey));
										}
									}
								}
							}
							die(var_dump($mysqli->error()));
						}catch(\Exception $e){
							die($e->getMessage());
						}
					}else{
						die(var_dump($_GET));
					}
					$expo->merge();
					echo 'success-edit';
				}else{
					echo $expo;
				}
			}catch(\Exception $e){
				die($e->getMessage());
			}
			break;
		case 'addAccessory':
			if(!empty($_GET['name']) && !empty($_GET['type']) && !empty($_GET['price']) && !empty($_GET['navacc']) && isset($_GET['returnable'])){
				if(Accessory::insAccessory($_GET['name'], $_GET['price'], $_GET['type'], $_GET['navacc'], $_GET['returnable'])){
					die('success');
				}
				die('fail');
			}else{
				foreach($_GET as $g){
					echo $g;
				}
			}
			break;
		case 'accTable':
			$mysqli = DB::init();
			$accs = $mysqli->querySelect("SELECT * FROM accessorio");
			print_r($accs);
			$tbody = '';
			for($i = 0; $i < count($accs); $i++){
				/*$acc = $accs[$i];
				$tbody .= '	<tr>
								<th scope="row">'.($i+1).'</th>
								<td><b class="text-bold '.(($acc['type'] == 'servizio') ? 'text-primary' : 'text-warning').'" data-toggle="tooltip" data-placement="top" title="'.(($acc['type']=='servizio') ? 'Servizio' : 'Accessorio').'">'.$acc['name'].'</b></td>
								
						';
				
			*/
				echo '<tr>';
				echo '<th role="row">'.($i+1).'</th>';
				echo '<td><b '.($accs[$i]['type'] == 'servizio' ? 'class="text-primary text-bold" data-toggle="tooltip" data-placement="top" title="Servizio">' : 'class="text-warning text-bold" data-toggle="tooltip" data-placement="top" title="Accessorio">').$accs[$i]['name'].'</b></td>';
				echo '<td>'.$accs[$i]['price'].'</td>';
				echo '<td>'.$accs[$i]['nAvailable'].'</td>';
				echo '<td class="text-center">'.($accs[$i]['returnable'] ? '<i class="text-success" data-feather="check-circle"></i>' : '<i data-feather="circle"></i>').'</td>';
				echo '	<td class="text-center">
							<a class="accEditBtn handCursor text-info text-weight-bold mr-3"><i data-feather="edit"></i></a>
					  		<a class="accDelBtn handCursor text-danger text-weight-bold ml-3" onclick="console.log(\'sis\');"><i data-feather="x"></i></a>
					  	</td>';
				echo '</tr>';
			}
			break;
		case 'addCategory':
			if (!empty($_GET['name']) && !empty($_GET['discount']) && !empty($_GET['docType'])){
				if (Category::insCategory($_GET['name'], $_GET['discount'], $_GET['docType'])){
					die('success');
				}
				$s = '';
				foreach($_GET as $g)
					$s .= $g;
				die("$s");
			//}else{
				
			}
			break;
		case 'loadTableExpos':
			if(Security::isAdmin()){
				$tbody = '';
			    Expo::init();
			    $events = Expo::getExpoList();
			    for($i = 0; $i < count($events); $i++){
			    	$event = $events[$i];
			    	if($event->getId()!=0){
			    		if(strtotime($event->getStartDate())<time() && strtotime($event->getEndDate())>time()){
			    			$coloreRiga = 'text-success';
			    		}elseif(strtotime($event->getStartDate())>time()){
			    			$coloreRiga = 'text-primary';
			    		}else{
			    			$coloreRiga = '';
			    		}
			    		$popover = '<span data-container="body" data-toggle="popover" data-placement="right" data-content="'.$event->getDescription().'">';
				    	$tbody .= "	<tr id=\"".$event->getId()."expocontainer\" class=\"expocontainer\">
				    					<th scope='row'>".($i+1)."</th>
				    					<td id=\"".$event->getId()."expotitle\" class=\"".$coloreRiga."\"><strong>".(strlen($event->getName())>50 ? substr($event->getName(),0,50) . "..." : $event->getName())."</strong></td>
				    					<td id=\"".$event->getId()."expodesc\" class=\"tdExpoDesc\" data-alldesc=\"".$event->getDescription()."\">".(strlen($event->getDescription())>70 ? $popover . substr($event->getDescription(),0,70) . "..." : $event->getDescription())."</span></td>
				    					<td id=\"".$event->getId()."exposdate\" data-realdate=\"".$event->getStartDate()."\">".(empty($event->getStartDate()) ? 'N/D' : date('d/m/Y',strtotime($event->getStartDate())))."</td>
				    					<td id=\"".$event->getId()."expoedate\" data-realdate=\"".$event->getEndDate()."\">".(empty($event->getEndDate()) ? 'N/D' : date('d/m/Y',strtotime($event->getEndDate())))."</td>
				    					<td id=\"".$event->getId()."expoprice\">".$event->getPrice()."</td>
				    					<td id=\"".$event->getId()."expomseats\">".$event->getMaxSeats()."</td>
				    					<td id=\"".$event->getId()."expoeditbtn\"><a class=\"editExpoBtn\" data-expoid=\"".$event->getId()."\"><span class=\"cursorHand\"><span data-feather='edit' class='text-info'></span></span></a></td>
			    						<td id=\"".$event->getId()."expodeletebtn\"><span class=\"cursorHand\" onclick=\"loadDeleteExpoModal('".($coloreRiga == 'text-success' ? $event->getId() . "',true" : $event->getId() . "',false").");\"><span data-feather='x' class='text-danger'></span></span></td>
				    				</tr>";
			    	}
			    }
			    echo $tbody;
			}
			break;
		case 'loadTimeSlots':
			if(Security::isAdmin()){
				Expo::init();
				TimeSlot::init();
				$events = Expo::getExpoList();
				$ret = array();
				foreach($events as $event){
					$tslots = $event->getTimeSlots();
					$ret['event'.$event->getId()] = array();
					foreach($tslots as $ts){
						foreach($ts as $day){
							//$days = array('1'=>'Lun','2'=>'Mar','3'=>'Mer','4'=>'Gio','5'=>'Ven','6'=>'Sab','7'=>'Dom');
							$ret['event'.$event->getId()]['day'.$day->getDay()] []= array('id'=>$day->getId(),'startHour'=>$day->getStartHour(),'minutes'=>$day->getMinutes());
						}
					}
				}
				echo json_encode($ret);
			}
			break;
		case 'catTable':
			if(Security::isAdmin()){
				$tbody = '';
				Category::init();
				$categories = Category::getCategoryList();
				for($i = 0; $i < count($categories); $i++){
					$cat = $categories[$i];
					$tbody .= '	<tr>
									<th scope="row">'.($i+1).'</td>
									<td id="'.$cat->getId().'cattitle">'.$cat->getName().'</td>
									<td id="'.$cat->getId().'catdiscount">'.$cat->getDiscount().'</td>
									<td id="'.$cat->getId().'catdoctype">'.(($cat->getDocType() == '') ? 'Nessun documento' : $cat->getDocType()).'</td>
									<td>
										<a class="catEditBtn handCursor text-info text-weight-bold mr-3" data-catid="'.$cat->getId().'"><span data-feather="edit"></span></a>
										<a class="catDelBtn handCursor text-danger text-weight-bold ml-3" data-catid="'.$cat->getId().'"><span data-feather="x"></span></a>
									</td>
								</tr>';
				}
				echo $tbody;
			}
			break;
		case 'dashGraph':
			if(Security::isAdmin()){
				$mysqli = Database::init();
			    $anno = Security::escape($_GET['year']);
			    $where = "";
			    if($anno != 'all') {
			    	$where = "WHERE YEAR(datePurchase) = '$anno'";
			    }
			    
			    //biglietti per mese
			    $datiGrafico = array(1 => "0",2 => "0",3 => "0",4 => "0",5 => "0",6 => "0",7 => "0",8 => "0",9 => "0",10 => "0",11 => "0",12 => "0");
			    $queryDatiGrafico = $mysqli->querySelect("SELECT count(id) AS cnt, month(datePurchase) AS meseA FROM biglietto $where GROUP BY meseA");
			    if(count($queryDatiGrafico) > 0){
			        foreach($queryDatiGrafico as $bigliettiMese){
			            $datiGrafico[$bigliettiMese['meseA']] = $bigliettiMese['cnt'];
			        }
			    }
			    echo json_encode($datiGrafico);
			}
			break;
	}
?>