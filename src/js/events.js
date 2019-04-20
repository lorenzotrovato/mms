$(document).ready(function() {
	feather.replace();
	
	var clicked = false;
	var card;
	var ghostcard;
	$(document).on('click', '.discover-btn', function(e) {
		e.preventDefault();
		if (clicked === false) {
			clicked = true;
			card = $(this).parent().parent().parent();
			ghostcard = card.clone(true, true);
			var scroll = $(document).scrollTop();
			
			
			var eventId = card.attr('eventid');
			var cardPosition = card.offset().top - $(window).scrollTop();
			var cardWidth = card.outerWidth();
			ghostcard.outerWidth(cardWidth);
			ghostcard.css({
				'position': 'fixed',
				'z-index': '999',
				'max-width': '1000px',
				'top': cardPosition + 'px',
				'left': '50%',
				'transform': 'translateX(-50%)'
			});
			
			card.parent().parent().parent().append(ghostcard);
			card.css('visibility', 'hidden');

			var cardTop = $('header').outerHeight();
			var cardNewHeight = $('body').height() - $('header').outerHeight();
			var cardNewWidth = $('body').width();

			ghostcard.children('.card-img-left').animate({
				'opacity': '0'
			}, 150);

			ghostcard.children('.card-body').animate({
				'opacity': '0'
			}, 150, function() {

				ghostcard.css({
					'background-color': 'white',
					'border': '0'
				});
				ghostcard.animate({
					'top': cardTop + 'px',
					'height': cardNewHeight + 'px',
					'width': '100%',
					'max-width': '1000px',
					'border-radius': '0',
				}, 250, function() {
					$('body').css('overflow-y','hidden');
					ghostcard.css({
						'height': 'calc(100% - ' + $('header').outerHeight() + 'px)',
					});
					ghostcard.children('.card-img-left').hide();
					ghostcard.children('.card-body').hide();
					ghostcard.html('<div class="card-content"></div>');
					var content = ghostcard.children('.card-content');
					content.load('pages/event.php?id=' + eventId, function() {
						feather.replace();
						$(this).animate({
							'opacity': 1
						}, 250);
					});
				});
			});

		} else {
			clicked = false;
			var content = ghostcard.children('.card-content');
			content.animate({
				'opacity': '0'
			}, 150, function() {
				card.css({
					'opacity': '0',
					'visibility': 'visible'
				});
				ghostcard.animate({
					'top': (card.offset().top - $(window).scrollTop()) + 'px',
					'width': card.outerWidth() + 'px',
					'height': card.outerHeight() + 'px',
					'border-radius': '.25rem'
				}, 250, function() {
					ghostcard.css({
						'background-color': 'rgba(255, 255, 255, 0.65)'
					});
					setTimeout(function() {
						card.css({
							'opacity': '1'
						});
						ghostcard.remove();
						$('body').css('overflow-y','auto');
					}, 200);
				});
			});
		}
	});
	
	$('#datetimepicker').datetimepicker({
        format: 'L',
        locale: 'it'
    });
    
    function getFormattedDate(date){
    	return date.getDate() + '/' + ((date.getMonth() + 1) < 10 ? '0'+(date.getMonth() + 1) : (date.getMonth() + 1)) + '/' + date.getFullYear();
    }
    
    var eventid=null;
    var eventInfo=null;
    var dateTicket=null;
    var selectedSlot=null;
    var nTickets=null;
    var nAccessories=null;
    
    $(document).on('click', '.eventbuy', function(event) {
    	eventid = $(this).attr('eventid');
		nTickets={};
    	nAccessories={};
    	selectedSlot=null;
    	dateTicket=null;
		$.ajax({
			type: "GET",
			async: true,
			cache: false,
			url: "./includes/router.php",
			data: "action=getBuyNeeds&eventid="+eventid,
			success: function(response) {
				if(response != "false"){
					eventInfo = JSON.parse(response);
					console.table(eventInfo);
					var currentDate = new Date();
					currentDate.setDate(currentDate.getDate() + 1)
					var startDate = new Date(eventInfo['startDate']);
					var endDate = new Date(eventInfo['endDate']);
					if(currentDate > startDate){
						startDate = currentDate;
					}
					var disabledWeekDays = [];
					var disabledDays = [];
					var timeslots = eventInfo['timeSlots'];
					for(var i = 1; i <= 7; i++){
						if(timeslots[i].length == 0){
							if(i == 7){
								disabledWeekDays.push(0); 
							}else{
								disabledWeekDays.push(i);
							}
						}
					}
					for(var date in eventInfo['occupiedSeats']){
						var maxAvSeats = 0;
						var row = eventInfo['occupiedSeats'][date];
						for(var i = 0; i<(Object.keys(row).length-1); i++){
							var temp=eventInfo['maxSeats']-row[i];
							if(temp > maxAvSeats){
								maxAvSeats = temp;
							}
						}
						if(maxAvSeats == 0){
							disabledDays.push(getFormattedDate(new Date(date)));
						}
					}
					if(disabledWeekDays.length < 7){
						if(startDate < endDate){
							$('#modalBuy').find('#js-container').html(`
							<div class="modal-body">
								<p class="startDate"></p>
								<p>Scegli il giorno in cui desideri partecipare all'evento:</p>
								<div class="form-group">
									<div class="input-group date" id="datetimepicker" data-target-input="nearest">
										<input type="text" id="chosenDate" class="form-control datetimepicker-input" data-target="#datetimepicker" onkeydown="return false;"/>
										<div class="input-group-append" data-target="#datetimepicker" data-toggle="datetimepicker">
											<div class="input-group-text"><i data-feather="calendar"></i></div>
										</div>
									</div>
								</div>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-secondary" data-dismiss="modal">Annulla</button>
								<button type="button" class="btn btn-primary" id="goTimeSlots">Prosegui</button>
							</div>`);
							feather.replace();
							$('#datetimepicker').datetimepicker({
								format: 'L',
								locale: 'it'
							});
							$('#datetimepicker').datetimepicker('minDate', false);
							$('#datetimepicker').datetimepicker('maxDate', false);
							$('#datetimepicker').datetimepicker('daysOfWeekDisabled', false);
							var fRealStartDate = getFormattedDate(new Date(eventInfo['startDate']));
							var fStartDate = getFormattedDate(startDate);
							var fEndDate = getFormattedDate(endDate)
							try{
								$('#datetimepicker').datetimepicker('minDate', fStartDate);
								$('#datetimepicker').datetimepicker('maxDate', fEndDate);
								$('#datetimepicker').datetimepicker('daysOfWeekDisabled', disabledWeekDays);
								$('#datetimepicker').datetimepicker('disabledDates', disabledDays);
								$('#modalBuy').find('.startDate').html("Periodo evento: <b>"+fRealStartDate+"</b> - <b>"+fEndDate+"</b>");
								$('#modalBuy').modal('show');
							}catch(err){
								alert("L'evento non ha più giorni disponibili");
								$('#datetimepicker').datetimepicker('destroy');
								$('#datetimepicker').datetimepicker({
							        format: 'L',
							        locale: 'it'
							    });
							}
						}else{
							alert("Evento terminato");
						}
					}else{
						alert("L'evento non è disponibile");
					}
				}else{
					alert("Evento non trovato");
				}
			}
		});
	});
	
	$(document).on('click', '#goTimeSlots', function(event) {
		if(eventid!=null && eventInfo!=null){
			var content = $('#modalBuy').find('#js-container');
			var date = content.find('#chosenDate').val();
			var dateArray = date.split('/');
			var dateString = dateArray[2] + '-' + dateArray[1] + '-' + dateArray[0];
			dateTicket = new Date(dateString);
			var occupiedSeats = eventInfo['occupiedSeats'][dateString];
			var timeslots = eventInfo['timeSlots'];
			var dayOfWeek = occupiedSeats['dayOfWeek'];
			var output='<div class="modal-body">Data selezionata: <b>'+date+'</b><br/><br/>';
			output+='Scegli una fascia oraria:<br/><div class="list-group">';
			for(i = 0; i < timeslots[dayOfWeek].length; i++){
				var slot=timeslots[dayOfWeek][i];
				output+='<span class="list-group-item list-group-item-action slotTile text-left" slotId="'+slot['id']+'" infoHour="'+slot['startHour']+' - '+slot['endHour']+'">'+slot['startHour']+' - '+slot['endHour']+' Posti disponibili: '+(eventInfo['maxSeats'] - occupiedSeats[i])+'</span>';
			}
			output+=`</div></div>
				<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Annulla</button>
				<button type="button" class="btn btn-primary" id="goNumbers" disabled>Prosegui</button>
				</div>`;
			content.fadeOut(300, function(){
				content.html(output);
				content.fadeIn(300);
			});
		}
    });
	
	$(document).on('click', '#goNumbers', function(event) {
		if(eventid!=null && eventInfo!=null && selectedSlot!=null && dateTicket !=null){
			var content = $('#modalBuy').find('#js-container');
			selectedSlot = [selectedSlot, $('.slotTile[slotId="'+selectedSlot+'"]').attr('infoHour')];
			console.table(selectedSlot);
			var categories = eventInfo['categories'];
			var output='<div class="modal-body">';
			output+='Inserisci il numero di biglietti:<br/><div class="list-group">';
			for(i = 0; i < categories.length; i++){
				var cat=categories[i];
				output+=`<div class="form-group">
							<label for="recipient-name" class="col-form-label">`+cat['name']+`</label>
							<input type="number" class="form-control nTicketField" min="0" id="recipient-name" typeId="`+cat['id']+`" catName="`+cat['name']+`" value="0">
						</div>`;
			}
			output+=`</div></div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Annulla</button>
					<button type="button" class="btn btn-primary" id="goAccessories" disabled>Prosegui</button>
				</div>`;
			content.fadeOut(300, function(){
				content.html(output);
				content.fadeIn(300);
			});
		
		}
    });
    
    $(document).on('change keyup mouseup click focus', '.nTicketField', function(event){
		var somma = 0;
		var err = false;
		$('.nTicketField').each(function(i){
			if($(this).val() >= 0 && !err){
				somma+=$(this).val();
			}else{
				err=true;
				somma=0;
			}
		});
		if(somma > 0){
			$('#goAccessories').prop('disabled', false);
		}else{
			$('#goAccessories').prop('disabled', true);
		}
    });
    
    $(document).on('change keyup mouseup click focus', '.nAccessoryField', function(event){
		var somma = 0;
		var err = false;
		$('.nAccessoryField').each(function(i){
			if($(this).val() >= 0 && !err){
				somma+=$(this).val();
			}else{
				err = true;
			}
		});
		if(!err){
			$('#goFinish').prop('disabled', false);
		}else{
			$('#goFinish').prop('disabled', true);
		}
    });
    
    $(document).on('click', '#goAccessories', function(event) {
		if(eventid!=null && eventInfo!=null && selectedSlot!=null && dateTicket!=null){
			var content = $('#modalBuy').find('#js-container');
			var somma = 0;
			var err = false;
			$('.nTicketField').each(function(){
				if($(this).val() >= 0 && !err){
					if($(this).val() > 0){
						nTickets[$(this).attr('typeId')] = [$(this).attr('catName'), $(this).val()];
						somma+=$(this).val();
					}
				}else{
					err = true;
					somma = 0;
				}
			});
			if(somma > 0){
				var accessories = eventInfo['accessories'];
				var output='<div class="modal-body">';
				output+='Scegli un accessorio:<br/><div class="list-group">';
				for(i = 0; i < accessories.length; i++){
					var acc=accessories[i];
					output+=`<div class="form-group">
								<label for="recipient-name" class="col-form-label">`+acc['name']+`</label>
								<input type="number" class="form-control nAccessoryField" min="0" id="recipient-name" typeId="`+acc['id']+`" accName="`+acc['name']+`" value="0">
							</div>`;
				}
				output+=`</div></div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Annulla</button>
						<button type="button" class="btn btn-primary" id="goFinish">Prosegui</button>
					</div>`;
				content.fadeOut(300, function(){
					content.html(output);
					content.fadeIn(300);
				});
			}
		
		}
    });
    
    $(document).on('click', '#goFinish', function(event){
		if(eventid!=null && eventInfo!=null && selectedSlot!=null && dateTicket!=null && nTickets != null){
			var content = $('#modalBuy').find('#js-container');
			var somma = 0;
			var err = false;
			$('.nAccessoryField').each(function(){
				if($(this).val() >= 0 && !err){
					if($(this).val() > 0){
						nAccessories[$(this).attr('typeId')] = [$(this).attr('accName'), $(this).val()];
						somma+=$(this).val();
					}
				}else{
					err = true;
					somma = 0;
				}
			});
			if(!err){
				var strTickets='';
				for(var x in nTickets){
					strTickets+=nTickets[x][0]+': '+nTickets[x][1]+'<br/>';
				}
				var strAccs='';
				console.log(nAccessories);
				if(Object.keys(nAccessories).length > 0){
					for(var x in nAccessories){
						strAccs+=nAccessories[x][0]+': '+nAccessories[x][1]+'<br/>';
					}
				}else{
					strAccs='Nessun accessorio selezionato';
				}
				var output='<div class="modal-body">';
				output+=`Riepilogo:<br/>
						<table class="text-left recapBought">
							<tr>
								<th>Nome Evento</th>
								<td>`+eventInfo['name']+`</td>
							</tr>
							<tr>
								<th>Numero Biglietti</th>
								<td>`+strTickets+`</td>
							</tr>
							<tr>
								<th>Data biglietto</th>
								<td>`+getFormattedDate(dateTicket)+`</td>
							</tr>
							<tr>
								<th>Fascia oraria</th>
								<td>`+selectedSlot[1]+`</td>
							</tr>
							<tr>
								<th>Elenco accessori</th>
								<td>`+strAccs+`</td>
							</tr>
						`;
				/*for(i = 0; i < accessories.length; i++){
					var acc=accessories[i];
					output+=`<div class="form-group">
								<label for="recipient-name" class="col-form-label">`+acc['name']+`</label>
								<input type="number" class="form-control nAccessoryField" min="0" id="recipient-name" typeId="`+acc['id']+`" value="0">
							</div>`;
				}*/
				output+=`</table></div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Annulla</button>
						<button type="button" class="btn btn-primary" id="sendPayment">Paga</button>
					</div>`;
				content.fadeOut(300, function(){
					content.html(output);
					content.fadeIn(300);
				});
			}else{
				nAccessories = null;
			}
		
		}
    });
    
	$(document).on('click', '#sendPayment', function(event){
		if(eventid!=null && eventInfo!=null && selectedSlot!=null && dateTicket!=null && nTickets != null){
			for(var x in nTickets){
				nTickets[x]=nTickets[x][1];
			}
			if(nAccessories != null){
				for(var x in nAccessories){
					nAccessories[x]=nAccessories[x][1];
				}
			}else{
				nAccessories = [];
			}
			dateTicket = getFormattedDate(dateTicket);
			selectedSlot = selectedSlot[0];
			redirectWithData('purchase.php', {timeSlotId: selectedSlot, dateValidity: dateTicket, nTickets: JSON.stringify(nTickets).replace(/"/g, '&quot;'), nAccessories: JSON.stringify(nAccessories).replace(/"/g, '&quot;')});
		}
	});
	
	$(document).on('click', '.normalbuy', function(event) {
    	eventid = -1;
		nTickets={};
    	nAccessories={};
    	selectedSlot=null;
    	dateTicket=null;
		$.ajax({
			type: "GET",
			async: true,
			cache: false,
			url: "./includes/router.php",
			data: "action=getBuyNeeds&eventid="+eventid,
			success: function(response) {
				if(response != "false"){
					eventInfo = JSON.parse(response);
					console.table(eventInfo);
					var currentDate = new Date();
					currentDate.setDate(currentDate.getDate() + 1)
					var startDate = currentDate;
					var endDate = new Date();
					endDate.setDate(endDate.getDate() + 31);
					var disabledWeekDays = [];
					var disabledDays = [];
					var timeslots = eventInfo['timeSlots'];
					for(var i = 1; i <= 7; i++){
						if(timeslots[i].length == 0){
							if(i == 7){
								disabledWeekDays.push(0); 
							}else{
								disabledWeekDays.push(i);
							}
						}
					}
					for(var date in eventInfo['occupiedSeats']){
						var maxAvSeats = 0;
						var row = eventInfo['occupiedSeats'][date];
						for(var i = 0; i<(Object.keys(row).length-1); i++){
							var temp=eventInfo['maxSeats']-row[i];
							if(temp > maxAvSeats){
								maxAvSeats = temp;
							}
						}
						if(maxAvSeats == 0){
							disabledDays.push(getFormattedDate(new Date(date)));
						}
					}
					if(disabledWeekDays.length < 7){
						$('#modalBuy').find('#js-container').html(`
						<div class="modal-body">
							<p class="startDate"></p>
							<p>Scegli il giorno in cui desideri partecipare alla visita:</p>
							<div class="form-group">
								<div class="input-group date" id="datetimepicker" data-target-input="nearest">
									<input type="text" id="chosenDate" class="form-control datetimepicker-input" data-target="#datetimepicker" onkeydown="return false;"/>
									<div class="input-group-append" data-target="#datetimepicker" data-toggle="datetimepicker">
										<div class="input-group-text"><i data-feather="calendar"></i></div>
									</div>
								</div>
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-dismiss="modal">Annulla</button>
							<button type="button" class="btn btn-primary" id="goTimeSlotsNormal">Prosegui</button>
						</div>`);
						feather.replace();
						$('#datetimepicker').datetimepicker({
							format: 'L',
							locale: 'it'
						});
						var fStartDate = getFormattedDate(startDate);
						var fEndDate = getFormattedDate(endDate);
						$('#datetimepicker').datetimepicker('minDate', false);
						$('#datetimepicker').datetimepicker('maxDate', false);
						$('#datetimepicker').datetimepicker('daysOfWeekDisabled', false);
						try{
							$('#datetimepicker').datetimepicker('minDate', fStartDate);
							$('#datetimepicker').datetimepicker('maxDate', fEndDate);
							$('#datetimepicker').datetimepicker('daysOfWeekDisabled', disabledWeekDays);
							$('#datetimepicker').datetimepicker('disabledDates', disabledDays);
							$('#modalBuy').modal('show');
						}catch(err){
							alert("La visita non ha giorni disponibili");
							$('#datetimepicker').datetimepicker('destroy');
							$('#datetimepicker').datetimepicker({
						        format: 'L',
						        locale: 'it'
						    });
						}
					}else{
						alert("La visita non è disponibile");
					}
				}else{
					alert("Visita non trovato");
				}
			}
		});
	});
	
	$(document).on('click', '#goTimeSlotsNormal', function(event) {
		if(eventid!=null && eventInfo!=null){
			var content = $('#modalBuy').find('#js-container');
			var date = content.find('#chosenDate').val();
			var dateArray = date.split('/');
			var dateString = dateArray[2] + '-' + dateArray[1] + '-' + dateArray[0];
			dateTicket = new Date(dateString);
			var occupiedSeats = eventInfo['occupiedSeats'][dateString];
			var timeslots = eventInfo['timeSlots'];
			var dayOfWeek = occupiedSeats['dayOfWeek'];
			var output='<div class="modal-body">Data selezionata: <b>'+date+'</b><br/><br/>';
			output+='Scegli una fascia oraria:<br/><div class="list-group">';
			for(i = 0; i < timeslots[dayOfWeek].length; i++){
				var slot=timeslots[dayOfWeek][i];
				output+='<span class="list-group-item list-group-item-action slotTileNormal text-left" slotId="'+slot['id']+'" infoHour="'+slot['startHour']+' - '+slot['endHour']+'">'+slot['startHour']+' - '+slot['endHour']+' Posti disponibili: '+(eventInfo['maxSeats'] - occupiedSeats[i])+'</span>';
			}
			output+=`</div></div>
				<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Annulla</button>
				<button type="button" class="btn btn-primary" id="goNumbersNormal" disabled>Prosegui</button>
				</div>`;
			content.fadeOut(300, function(){
				content.html(output);
				content.fadeIn(300);
			});
		}
    });
	
	$(document).on('click', '#goNumbersNormal', function(event) {
		if(eventid!=null && eventInfo!=null && selectedSlot!=null && dateTicket !=null){
			var content = $('#modalBuy').find('#js-container');
			selectedSlot = [selectedSlot, $('.slotTileNormal[slotId="'+selectedSlot+'"]').attr('infoHour')];
			console.table(selectedSlot);
			var categories = eventInfo['categories'];
			var output='<div class="modal-body">';
			output+='Inserisci il numero di biglietti:<br/><div class="list-group">';
			for(i = 0; i < categories.length; i++){
				var cat=categories[i];
				output+=`<div class="form-group">
							<label for="recipient-name" class="col-form-label">`+cat['name']+`</label>
							<input type="number" class="form-control nTicketFieldNormal" min="0" id="recipient-name" typeId="`+cat['id']+`" catName="`+cat['name']+`" value="0">
						</div>`;
			}
			output+=`</div></div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Annulla</button>
					<button type="button" class="btn btn-primary" id="goAccessoriesNormal" disabled>Prosegui</button>
				</div>`;
			content.fadeOut(300, function(){
				content.html(output);
				content.fadeIn(300);
			});
		
		}
    });
    
    $(document).on('change keyup mouseup click focus', '.nTicketFieldNormal', function(event){
		var somma = 0;
		var err = false;
		$('.nTicketFieldNormal').each(function(i){
			if($(this).val() >= 0 && !err){
				somma+=$(this).val();
			}else{
				err=true;
				somma=0;
			}
		});
		if(somma > 0){
			$('#goAccessoriesNormal').prop('disabled', false);
		}else{
			$('#goAccessoriesNormal').prop('disabled', true);
		}
    });
    
    $(document).on('change keyup mouseup click focus', '.nAccessoryFieldNormal', function(event){
		var somma = 0;
		var err = false;
		$('.nAccessoryFieldNormal').each(function(i){
			if($(this).val() >= 0 && !err){
				somma+=$(this).val();
			}else{
				err = true;
			}
		});
		if(!err){
			$('#goFinishNormal').prop('disabled', false);
		}else{
			$('#goFinishNormal').prop('disabled', true);
		}
    });
    
    $(document).on('click', '#goAccessoriesNormal', function(event) {
		if(eventid!=null && eventInfo!=null && selectedSlot!=null && dateTicket!=null){
			var content = $('#modalBuy').find('#js-container');
			var somma = 0;
			var err = false;
			$('.nTicketFieldNormal').each(function(){
				if($(this).val() >= 0 && !err){
					if($(this).val() > 0){
						nTickets[$(this).attr('typeId')] = [$(this).attr('catName'), $(this).val()];
						somma+=$(this).val();
					}
				}else{
					err = true;
					somma = 0;
				}
			});
			if(somma > 0){
				var accessories = eventInfo['accessories'];
				var output='<div class="modal-body">';
				output+='Scegli un accessorio:<br/><div class="list-group">';
				for(i = 0; i < accessories.length; i++){
					var acc=accessories[i];
					output+=`<div class="form-group">
								<label for="recipient-name" class="col-form-label">`+acc['name']+`</label>
								<input type="number" class="form-control nAccessoryFieldNormal" min="0" id="recipient-name" typeId="`+acc['id']+`" accName="`+acc['name']+`" value="0">
							</div>`;
				}
				output+=`</div></div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Annulla</button>
						<button type="button" class="btn btn-primary" id="goFinishNormal">Prosegui</button>
					</div>`;
				content.fadeOut(300, function(){
					content.html(output);
					content.fadeIn(300);
				});
			}
		
		}
    });
    
    $(document).on('click', '#goFinishNormal', function(event){
		if(eventid!=null && eventInfo!=null && selectedSlot!=null && dateTicket!=null && nTickets != null){
			var content = $('#modalBuy').find('#js-container');
			var somma = 0;
			var err = false;
			$('.nAccessoryFieldNormal').each(function(){
				if($(this).val() >= 0 && !err){
					if($(this).val() > 0){
						nAccessories[$(this).attr('typeId')] = [$(this).attr('accName'), $(this).val()];
						somma+=$(this).val();
					}
				}else{
					err = true;
					somma = 0;
				}
			});
			if(!err){
				var strTickets='';
				for(var x in nTickets){
					strTickets+=nTickets[x][0]+': '+nTickets[x][1]+'<br/>';
				}
				var strAccs='';
				console.log(nAccessories);
				if(Object.keys(nAccessories).length > 0){
					for(var x in nAccessories){
						strAccs+=nAccessories[x][0]+': '+nAccessories[x][1]+'<br/>';
					}
				}else{
					strAccs='Nessun accessorio selezionato';
				}
				var output='<div class="modal-body">';
				output+=`Riepilogo:<br/>
						<table class="text-left recapBought">
							<tr>
								<th>Nome</th>
								<td>Visita</td>
							</tr>
							<tr>
								<th>Numero Biglietti</th>
								<td>`+strTickets+`</td>
							</tr>
							<tr>
								<th>Data biglietto</th>
								<td>`+getFormattedDate(dateTicket)+`</td>
							</tr>
							<tr>
								<th>Fascia oraria</th>
								<td>`+selectedSlot[1]+`</td>
							</tr>
							<tr>
								<th>Elenco accessori</th>
								<td>`+strAccs+`</td>
							</tr>
						`;
				/*for(i = 0; i < accessories.length; i++){
					var acc=accessories[i];
					output+=`<div class="form-group">
								<label for="recipient-name" class="col-form-label">`+acc['name']+`</label>
								<input type="number" class="form-control nAccessoryField" min="0" id="recipient-name" typeId="`+acc['id']+`" value="0">
							</div>`;
				}*/
				output+=`</table></div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Annulla</button>
						<button type="button" class="btn btn-primary" id="sendPaymentNormal">Paga</button>
					</div>`;
				content.fadeOut(300, function(){
					content.html(output);
					content.fadeIn(300);
				});
			}else{
				nAccessories = null;
			}
		
		}
    });
    
	$(document).on('click', '#sendPaymentNormal', function(event){
		if(eventid!=null && eventInfo!=null && selectedSlot!=null && dateTicket!=null && nTickets != null){
			for(var x in nTickets){
				nTickets[x]=nTickets[x][1];
			}
			if(nAccessories != null){
				for(var x in nAccessories){
					nAccessories[x]=nAccessories[x][1];
				}
			}else{
				nAccessories = [];
			}
			dateTicket = getFormattedDate(dateTicket);
			selectedSlot = selectedSlot[0];
			redirectWithData('purchase.php', {timeSlotId: selectedSlot, dateValidity: dateTicket, nTickets: JSON.stringify(nTickets).replace(/"/g, '&quot;'), nAccessories: JSON.stringify(nAccessories).replace(/"/g, '&quot;')});
		}
	});
    
    function redirectWithData(location, args)
    {
        var form = '';
        $.each(args, function( key, value ) {
            form += '<input type="hidden" name="'+key+'" value="'+value+'">';
        });
        var formElement = $('<form action="'+location+'" method="POST" style="display: none">'+form+'</form>');
        $('body').append(formElement);
        formElement.submit();
    }
    
    $(document).on('click', '.slotTile', function(event){
    	$('.slotTile').each(function(){
    		$(this).removeClass('active');
    	})
    	$(this).addClass('active');
    	selectedSlot=$(this).attr('slotId');
    	if(selectedSlot!=null){
    		$('#goNumbers').prop('disabled', false);
    	}
    });
    
    $(document).on('click', '.slotTileNormal', function(event){
    	$('.slotTileNormal').each(function(){
    		$(this).removeClass('active');
    	})
    	$(this).addClass('active');
    	selectedSlot=$(this).attr('slotId');
    	if(selectedSlot!=null){
    		$('#goNumbersNormal').prop('disabled', false);
    	}
    });
});