<?php
	namespace MMS;
	require_once './autoload.php';
	use MMS\Security as Security;
	use MMS\Expo as Expo;
	use MMS\Accessory as Accessory;
	use MMS\Database as DB;
	use MMS\TimeSlot as TimeSlot;
	use MMS\Category as Category;
	
	// se non è impostata la variabile che definisce l'azione chiudo lo script
	isset($_GET['action']) ?: die();
	$action = $_GET['action'];
	Security::init();
	switch($action){
		case 'login':
			$user = "false";
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
		case 'getBuyNeeds':
			if(!empty($_GET['eventid'])){
				$id=$_GET['eventid'];
				if($id == "-1"){
					$id = 0;
				}
				try{
					$expo = new Expo($id);
					Category::init();
					Accessory::init();
					$array = array(
						'name' => $expo->getName(),
						'startDate' => $expo->getStartDate(),
						'endDate' => $expo->getEndDate(),
						'price' => $expo->getPrice(),
						'maxSeats' => $expo->getMaxSeats(),
						'occupiedSeats' => $expo->getOccupiedSeats(),
						'timeSlots' => array(),
						'categories' => Category::getCategoryListArray(),
						'accessories' => Accessory::getAccessoryListArray()
					);
					$timeslots = $expo->getTimeSlots();
					for($i = 1; $i <= 7; $i++){
						$array['timeSlots'][$i] = array();
						foreach($timeslots[$i] as $slot){
							$array['timeSlots'][$i][] = $slot->getArray();
						}
					}
					echo json_encode($array);
				}catch(\Exception $e){
					echo "false";
				}
				//print_r(Category::getCategoryList());
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
			        	echo '<td>'.$row['tot'].'</td>';
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
		    $queryDatiGrafico = $mysqli->querySelect("SELECT SUM(totalPrice) AS tot, DATE_FORMAT(datePurchase,'%Y%m') AS dateP, MONTH(datePurchase) AS month FROM biglietto LEFT JOIN categoria ON biglietto.codCat = categoria.id $where GROUP BY dateP, month");
		    if(count($queryDatiGrafico) > 0) {
		        foreach($queryDatiGrafico as $bigliettiMese){
		        	$earn = $bigliettiMese['tot'];
				$datiGrafico[$bigliettiMese['month']] += $earn;
		        }
		    }
			echo json_encode($datiGrafico);
			break;
		case 'addExpo':
			if(Security::isAdmin()){
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
					echo 'success'.$newExpo->getId();
				}else{
					echo $newExpo;
				}
			}
			break;
		case 'editExpo':
			if(Security::isAdmin()){
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
								$tslog = "Start ts log";
								foreach($timeslots as $dkey => $day){
									foreach($day as $ts){
										$tslog.="
											Giorno ".$dkey . " e la fascia oraria";
										if(!is_null($ts)){
											$tslog.="
												Esistente";
											$nts = new TimeSlot($ts['id']);
											if($nts instanceof TimeSlot && ($nts->getId()!=null && $nts->getId()!="")){
												$tslog.="
													Modifica fascia oraria ".$nts->getId();
												if(intval(abs($ts["minutes"]))>0 || $nts->hasTickets()){
													$nts->setStartHour(date('H:i:s',strtotime($ts["startHour"])));
													$nts->setMinutes(intval(abs($ts["minutes"])));
													$nts->setDay(intval(abs($dkey)));
													$tslog.= "
													" . $nts->getStartHour();
													$tslog.= "
													". $nts->merge();
												}else{
													$nts->deleteSlot();
												}
											}else{
												$tslog.="
													Aggiunta fascia oraria ";
												TimeSlot::addSlot($expo->getId(),date('H:i:s',strtotime($ts["startHour"])),intval(abs($ts["minutes"])),intval(abs($dkey)));
											}
										}
									}
								}
							}catch(\Exception $e){
								die($e->getMessage());
							}
						}
						$expo->merge();
						echo 's-edit'.$expo->getId();
						//echo $tslog;
					}else{
						echo $expo;
					}
				}catch(\Exception $e){
					die($e->getMessage());
				}
			}
			break;
		case 'editVisit':
			if(Security::isAdmin()){
				if(empty($_GET['descExpo']) || empty($_GET['priceExpo']) || empty($_GET['maxSeatsExpo'])){
					die('riempi tutti i campi');
				}
				try{
					$expo = new Expo(0);
					if($expo instanceof Expo){
						Security::init();
						$mysqli = Database::init();
						$expo->setDescription(Security::escape($_GET['descExpo']));
						$expo->setPrice(floatval(abs($_GET['priceExpo'])));
						$expo->setMaxSeats(intval(abs($_GET['maxSeatsExpo'])));
						
						//riaggiunta fascie orarie
						if(isset($_GET['timeslots'])){
							$timeslots = $_GET['timeslots'];
							try{
								TimeSlot::init();
								$tslog = "Start ts log";
								$tslog .= count($timeslots);
								foreach($timeslots as $dkey => $day){
									if(isset($day)){
										
										foreach($day as $ts){
											//print_r( $ts);
											$tslog.="
												Giorno ".$dkey . " e la fascia oraria";
											if(!is_null($ts)){
												$tslog.="
													Esistente";
												$nts = new TimeSlot($ts['id']);
												if($nts instanceof TimeSlot && ($nts->getId()!=null && $nts->getId()!="")){
													$tslog.="
														Modifica fascia oraria ".$nts->getId();
													if(intval(abs($ts["minutes"]))>0 || $nts->hasTickets()){
														$nts->setStartHour(date('H:i:s',strtotime($ts["startHour"])));
														$nts->setMinutes(intval(abs($ts["minutes"])));
														$nts->setDay(intval(abs($dkey)));
														$tslog.= "
														" . $nts->getStartHour();
														$tslog.= "
														" . $nts->getMinutes();
														$tslog.= "
														". $nts->merge();
													}else{
														$tslog.= "	Eliminazione";
														$nts->deleteSlot();
													}
												}else{
													$tslog.="
														Aggiunta fascia oraria ";
													TimeSlot::addSlot($expo->getId(),date('H:i:s',strtotime($ts["startHour"])),intval(abs($ts["minutes"])),intval(abs($dkey)));
												}
											}
										}
									}
								}
							}catch(\Exception $e){
								die($e->getMessage());
							}
						}
						$expo->merge();
						echo 's-edit';
						//echo $tslog;
					}else{
						echo $expo;
					}
				}catch(\Exception $e){
					die($e->getMessage());
				}
			}
			break;
		case 'deleteExpo':
			if(Security::isAdmin()){
				if(empty($_GET['expoid'])){
					die('dati esposizione non validi');
				}
				$nxp = new Expo(Security::escape($_GET['expoid']));
				if($nxp instanceof Expo && $nxp->getId()!=null){
					if($nxp->deleteExpo()){
						echo 'delete-success';
					}else{
						echo 'delete-error';
					}
				}else{
					die('l\'esposizione non esiste');
				}
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
			if (Security::isAdmin()){
				Accessory::init();
				$accessories = Accessory::getAccessoryList();
				$tbody = '';
				for($i = 0; $i < count($accessories); $i++){
					$acc = $accessories[$i];
					$tbody .= '	<tr>
									<th scope="row">'.($i+1).'</th>
									<td id="'.$acc->getId().'acctitle"><b class="text-bold '.(($acc->getType() == 'servizio' )? 'text-primary' : 'text-warning').'" data-toggle="tooltip" data-placement="top" title="'.(($acc->getType() == 'servizio' )? 'Servizio' : 'Accessorio').'">'.$acc->getName().'</b></td>
									<td id="'.$acc->getId().'accprice">'.$acc->getPrice().'</td>
									<td id="'.$acc->getId().'accnavailable">'.$acc->getNAvailable().'</td>
									<td id="'.$acc->getId().'accreturnable" class="text-center"><span class="'.($acc->getReturnable() ? 'text-success' : '').'" data-feather="'.($acc->getReturnable() ? 'check-circle' : 'circle').'"></span></td>
									<td class="text-center">
										<a class="accEditBtn handCursor text-info text-weight-bold mr-3" onclick="loadEditAccModal('.$acc->getId().')"><span data-feather="edit"></span></a>
										<a class="accDelBtn handCursor text-danger text-weight-bold ml-3 '.(($acc->getNAvailable() == 0) ? 'invisible' : '').'" onclick="loadDeleteAccModal('.$acc->getId().')"><span data-feather="x"></span></a>
									</td>
								</tr>';
				}
				$deleted = Accessory::getDeletedAccessoryList();
				if (count($deleted) > 0){
					$tbody .= '	<tr class="thead-dark text-white">
									<th>#</th>
									<th>Accessorio eliminato</th>
									<th>Prezzo [&euro;]</th>
									<th>Disponibilità</th>
									<th class="text-center">Da Restituire</th>
									<th></th>
								</tr>';
					
					for($i = 0; $i < count($deleted); $i++){
						$acc = $deleted[$i];
						$tbody .= '	<tr>
										<th scope="row">'.($i+1).'</th>
										<td id="'.$acc->getId().'acctitle"><b class="text-bold '.(($acc->getType() == 'servizio' )? 'text-primary' : 'text-warning').'" data-toggle="tooltip" data-placement="top" title="'.(($acc->getType() == 'servizio' )? 'Servizio' : 'Accessorio').'">'.$acc->getName().'</b></td>
										<td id="'.$acc->getId().'accprice">'.$acc->getPrice().'</td>
										<td id="'.$acc->getId().'accnavailable" class="text-danger font-weight-bold">Accessorio eliminato</td>
										<td id="'.$acc->getId().'accreturnable" class="text-center"><span class="'.($acc->getReturnable() ? 'text-success' : '').'" data-feather="'.($acc->getReturnable() ? 'check-circle' : 'circle').'"></span></td>
										<td class="text-center">
											<a class="accEditBtn handCursor text-info text-weight-bold mr-3" onclick="loadEditAccModal('.$acc->getId().')"><span data-feather="edit"></span></a>
											<a class="accDelBtn handCursor text-danger text-weight-bold ml-3 '.(($acc->getNAvailable() == 0) ? 'invisible' : '').'" onclick="loadDeleteAccModal('.$acc->getId().')"><span data-feather="x"></span></a>
										</td>
									</tr>';
					}
				}
				echo $tbody;
			}
			break;
		case 'addCategory':
			if(Security::isAdmin()){
				Category::init();
				if (!empty($_GET['name']) && !empty($_GET['discount']) && !empty($_GET['docType'])){
					if (Category::insCategory($_GET['name'], $_GET['discount'], $_GET['docType'])){
						die('success');
					}
				}
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
			    		$md5 = md5($event->getId());
			    		if(is_file('/var/www/html/src/images/covers/'.$md5.'.png')){
			    			$img = $md5.'.png';
			    		}elseif(is_file('/var/www/html/src/images/covers/'.$md5.'.jpg')){
			    			$img = $md5.'.jpg';
			    		}else{
			    			$img = "";
			    		}
			    		$popover = '<span data-container="body" data-toggle="popover" data-placement="right" data-content="'.$event->getDescription().'">';
				    	$tbody .= "	<tr id=\"".$event->getId()."expocontainer\" class=\"expocontainer\" data-coverimage=\"".$img."\">
				    					<th scope='row'>".($i+1)."</th>
				    					<td id=\"".$event->getId()."expotitle\" class=\"".$coloreRiga."\"><strong>".(strlen($event->getName())>50 ? stripslashes(substr($event->getName(),0,50)) . "..." : stripslashes($event->getName()))."</strong></td>
				    					<td id=\"".$event->getId()."expodesc\" class=\"tdExpoDesc\" data-alldesc=\"".$event->getDescription()."\">".(strlen($event->getDescription())>70 ? $popover . stripslashes(substr($event->getDescription(),0,70)) . "..." : stripslashes($event->getDescription()))."</span></td>
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
					$ret[$event->getId()] = null;
					foreach($tslots as $ts){
						foreach($ts as $day){
							//$days = array('1'=>'Lun','2'=>'Mar','3'=>'Mer','4'=>'Gio','5'=>'Ven','6'=>'Sab','7'=>'Dom');
							$ret[$event->getId()][$day->getDay()] []= array('id'=>$day->getId(),'startHour'=>$day->getStartHour(),'minutes'=>$day->getMinutes());
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
										<a class="catEditBtn handCursor text-info text-weight-bold mr-3 '.($cat->getName() == 'Intero' ? 'invisible' : '').'" onclick="loadEditCatModal('.$cat->getId().')"><span data-feather="edit"></span></a>
										<a class="catDelBtn handCursor text-danger text-weight-bold ml-3 '.(($cat->getPriority() < 0) ? 'invisible' : '').'" onclick="loadDeleteCatModal('.$cat->getId().')"><span data-feather="x"></span></a>
									</td>
								</tr>';
				}
				$deleted = Category::getDeletedCategoryList();
				if (count($deleted) > 0){
					$tbody .= '	<tr class="thead-dark text-white">
									<th>#</th>
									<th>Nome categoria eliminata</th>
									<th>Sconto applicato [%]</th>
									<th>Documento da esibire</th>
									<th></th>
								</tr>';
					for($i = 0; $i < count($deleted); $i++){
						$cat = $deleted[$i];
						$tbody .= '	<tr>
										<th scope="row">'.($i+1).'</td>
										<td id="'.$cat->getId().'cattitle">'.$cat->getName().'</td>
										<td id="'.$cat->getId().'catdiscount">'.$cat->getDiscount().'</td>
										<td id="'.$cat->getId().'catdoctype">'.(($cat->getDocType() == '') ? 'Nessun documento' : $cat->getDocType()).'</td>
										<td>
											<a class="catEditBtn handCursor text-info text-weight-bold mr-3 '.($cat->getName() == 'Intero' ? 'invisible' : '').'" onclick="loadEditCatModal('.$cat->getId().')"><span data-feather="edit"></span></a>
											<a class="catActivationBtn handCursor text-success text-weight-bold ml-3" onclick="catActivation('.$cat->getId().')"><span data-feather="arrow-up"></span></a>
										</td>
									</tr>';
					}
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
		case 'deleteAcc':
			if (Security::isAdmin()){
				$acc = new Accessory(Security::escape($_GET['id']));
				if ($acc->deleteAccessory()){
					echo 'success';
				}else{
					echo 'fail';
				}
			}
			break;
		case 'loadDocTypes':
			if (Security::isAdmin()){
				if(!empty($_GET['edit'])){
					$editing = $_GET['edit'];
					$options = '<option disabled '.($editing == 2 ? '' : 'selected').' value>Scegli un documento</option>';
					$mysqli = Database::init();
					$sql = "SELECT DISTINCT docType FROM categoria";
					$dTs = $mysqli->querySelect($sql);
					foreach($dTs as $dT){
						if ($dT['docType'] != ''){
							$options .= '<option '.($editing == 2 ? 'class="docTypeOption"' : '').'>'.$dT['docType'].'</option>';
						}
					}
					if ($editing == 2){
						$options .= '<option>Altro</option>';
					}
					echo json_encode(array("edit" => $_GET['edit'], "options" => $options));
				}
			}
			break;
		case 'editProfileInfo':
			if(Security::verSession()){
				if(!empty($_GET['newmail']) && !empty($_GET['newpsw'])){
					$user = Security::getUserFromSession();
					$newmail = Security::escape($_GET['newmail'],255);
					$newpsw = Security::escape($_GET['newpsw']);
					$oldmail = $user->getMail();
					$oldrole = $user->getRole();
					$changedmail = false;
					if($user->getMail()!=$newmail){
						$user->setMail($newmail);
						$changedmail = true;
						$user->setRole(0);
						$user->merge();
						if(!Security::sendVerMail($user)){
							$user->setMail($oldmail);
							$user->setRole($oldrole);
							$user->merge();
							die(json_encode(array('message'=>'impossibile inviare la mail di verifica')));
						}
					}
					if($newpsw!="password"){
						$user->setPassword($newpsw);
					}
					Security::removeAutoLoginCookies($user);
					echo json_encode(array('message'=>'success-edit-profile','changedmail'=>$changedmail));
				}else{
					die(json_encode(array('message'=>'riempi tutti i campi')));
				}
			}
			break;
		case 'editAcc':
			if(Security::isAdmin()){
				$acc = new Accessory(Security::escape($_GET['id']));
				$edited = $acc->updateAccessory(Security::escape($_GET['newName']),Security::escape($_GET['newType']),Security::escape($_GET['newPrice']),Security::escape($_GET['newNAvailable']),$_GET['newReturnableAcc']);
				if ($edited){
					echo 'success';
				}else{
					echo 'fail';
				}
			}
			break;
		case 'deleteCat':
			if(Security::isAdmin()){
				$cat = new Category(Security::escape($_GET['id']));
				if ($cat->deleteCategory()){
					echo 'success';
				}else{
					echo 'fail';
				}
			}
			break;
		case 'loadTableUsers':
			if(Security::isAdmin()){
				$userlist = User::getUserList();
				$htmllist = "";
				for($i=0;$i<count($userlist);$i++){
					if($userlist[$i]->getRole()==0){
						$role = "Non attivato";
						$namecolor = "text-warning";
						$action = "<td></td>";
					}elseif($userlist[$i]->getRole()==1){
						$role = "Utente normale";
						$action = "<td id=\"".$userlist[$i]->getId()."usereditbtn\"><a class=\"editUserBtn\" data-userid=\"".$userlist[$i]->getId()."\" data-userrole=\"".$userlist[$i]->getRole()."\"><span class=\"cursorHand\"><span data-feather='chevrons-up' class='text-success'></span></span></a></td>";
		    			$namecolor = "";			
					}else{
						$role = "Amministratore";
						$namecolor = "text-primary";
						$action = "<td id=\"".$userlist[$i]->getId()."usereditbtn\"><a class=\"editUserBtn\" data-userid=\"".$userlist[$i]->getId()."\" data-userrole=\"".$userlist[$i]->getRole()."\"><span class=\"cursorHand\"><span data-feather='chevrons-down' class='text-warning'></span></span></a></td>";
					}
					$htmllist.='<tr>
							<th>'.($i+1).'</th>
							<td id="'.$userlist[$i]->getId().'username" class="'.$namecolor.'"><strong>'.$userlist[$i]->getName().'</strong></td>
							<td>'.$userlist[$i]->getMail().'</td>
							<td id="'.$userlist[$i]->getId().'userrole">'.$role.'</td>
							'.$action.'
						</tr>';
				}
				echo $htmllist;
			}
			break;
		case 'activeCat':
			if(Security::isAdmin()){
				$cat = new Category(Security::escape($_GET['id']));
				$act = $cat->activeCategory();
				if ($act){
					echo 'success';
				}else{
					echo 'fail';
				}
			}
			break;
		case 'editUserRole':
			if(Security::isAdmin()){
				if(empty($_GET['userid']) || empty($_GET['oldrole'])){
					die('richiesta non corretta');
				}
				$us = new User(intval(Security::escape($_GET['userid'])));
				if($us instanceof User && $us!=null){
					$newrole = 1;
					if(abs(intval($_GET['oldrole']))==1){
						$newrole = 2;
					}
					$us->setRole($newrole);
					if($us->merge()==1){
						echo 'success-edit';
					}else{
						die('errore nella modifica del ruolo ' . $us->merge() . ' - ' .abs(intval($_GET['oldrole'])));
					}
				}else{
					die('utente non esistente');
				}
			}
			break;
		case 'verTicketKey':
			if(Security::isAdmin()){
				if(empty($_GET['key'])){
					die('nessuna chiave inserita');
				}
				$mysqli = DB::init();
				$key = Security::escape($_GET['key'],32);
				$query = $mysqli->querySelect('SELECT * FROM biglietto WHERE validation="'.$key.'"');
				if(count($query)==1){
					$newt = new Ticket($query[0]['id']);
					echo json_encode($newt->asAssocArray());
				}else{
					die('chiave non valida');
				}
			}
			break;
		case 'addTicket':
			if(Security::verSession()){
				if(empty($_GET['dati_biglietti'])){
					die('nessun biglietto selezionato');
				}
				
			}
			
			/*[
				ticket0:{
					dateValidity: 0000-00-00,
					totalPrice: 00,
					categoryId: 00,
					timeSlotId: 00
				},
				ticket1:{
					dateValidity: 0000-00-00,
					totalPrice: 00,
					categoryId: 00,
					timeSlotId: 00,
					
				},
				accessories: [
					{id_acc: 00, qta: 10},
					{id_acc: 01, qta: 13}
				]
			]
			
			*/
			break;
		case 'getCodes':
			if(Security::verSession()){
				if(!empty($_GET['idTick'])){
					$mysqli = DB::init();
					$idTick = Security::escape($_GET['idTick']);
					$code = $mysqli->querySelect("SELECT * FROM biglietto WHERE id=$idTick")[0];
					$ts = new TimeSlot($code['codTimeSlot']);
					echo '
					<div class="row justify-content-center text-center">
						Codice biglietto: &nbsp;<strong>'.$code['validation'].'</strong>
						<br><br>
						<div>
							<h6>Data di validità: '.date('d/m/Y',strtotime($code['dateValidity'])).'</h6>
							<h6>Data di acquisto: '.date('d/m/Y - H:i:s',strtotime($code['datePurchase'])).'</h6>
							<h6>Orario: dalle '.$ts->getStartHour().' alle '.$ts->getEndHour().'</h6>
						</div>
					</div>
					<div class="row justify-content-center">
						<img src="https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl='.urlencode($code['validation']).'" title="QR biglietto" />
					</div>';
				}
			}
			break;
		case 'editCat':
			if(Security::isAdmin()){
				$cat = new Category(Security::escape($_GET['id']));
				if ($cat->updateCategory(Security::escape($_GET['newName']),Security::escape($_GET['newDiscount']),Security::escape($_GET['newDocType']))){
					echo 'success';
				}else{
					echo 'fail';
				}
			}
			break;
	}
?>