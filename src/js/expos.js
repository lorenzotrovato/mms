var timeslots = {};
var selectedEvent = null;
var editing = false;
var range = document.getElementById('rangetimeslots');
var rnSettings = {
	start: [0],
	step: 5,
	connect: false,
	orientation: 'horizontal',
	behaviour: 'tap-drag',
	range: {
		'min': 0,
		'max': 1439
	}
};
noUiSlider.create(range, rnSettings);

$(document).ready(function() {
	$('[data-toggle="popover"]').popover();
	//caricamento tabella esposizioni
	loadTableExpoAndTimeSlot();
});

function loadTableExpoAndTimeSlot(){
	$.ajax({
		type: "GET",
		cache: false,
		url: "./includes/router.php?action=loadTableExpos",
		success: function(response) {
			if (response != "") {
				$('#tableExpoList').html(response);
				feather.replace();
				$('.tdExpoDesc').on("mouseover", function() {
					$(this).find("span").popover('show');
				});
				$('.tdExpoDesc').on("mouseleave", function() {
					$(this).find("span").popover('hide');
				});
				$('.editExpoBtn').on('click',loadEditExpoForm);
			}
		},
		error: function() {
			$('#tableExpoList').html('<tr><td>0</td><td><strong>Errore del server</strong></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>');
		}
	});

	//caricamento fascie orarie
	$.ajax({
		type: "GET",
		cache: false,
		url: "./includes/router.php?action=loadTimeSlots",
		success: function(response) {
			timeslots = JSON.parse(response);
		},
		error: function() {

		}
	});
}

function resetFormExpo(){
	document.getElementById('insertExpo').reset();
	timeslots = [];
	selectedEvent = null;
	$('#timeslotsdayselect').val(0);
	range.noUiSlider.destroy();
	rnSettings.start=[0];
	rnSettings.connect=false;
	noUiSlider.create(range, rnSettings);
	range.setAttribute('disabled',true);
	$('#rangetimeslotsadd').css('display','none');
	$('#rangetimeslotslabel').html('Orari di apertura<br>&nbsp;&nbsp;Seleziona un giorno');
	if(editing){
		$('#titleCreateEditExpo').addClass('text-muted');
		$('#titleCreateEditExpo').html('Aggiungi un\'esposizione');
		$('#btnResetExpoForm').html('Resetta il form');
		$('#btnInsertExpo').html('Inserisci esposizione');
		$('#btnInsertExpo').addClass('btn-primary');
		$('#btnInsertExpo').removeClass('btn-warning');
		$('.expocontainer').removeClass('table-warning');
	}
	$('#formErrExpo').html("");
	$('#formErrExpo').addClass('d-none');
	$('#formErrExpo').removeClass('alert-danger');
	$('#formErrExpo').removeClass('alert-success');
	return false;
}

$('#btnResetExpoForm').on('click',resetFormExpo);

$('#btnInsertExpo').on('click', function() {
	$('#formErrExpo').addClass('d-none');
	$('#formErrExpo').removeClass('alert-danger');
	$('#formErrExpo').removeClass('alert-success');

	var nomeExpo = $('#nameExpo').val();
	var descExpo = $('#descExpo').val();
	var dataStart = $('#dateStartExpo').val();
	var dataEnd = $('#dateEndExpo').val();
	var priceExpo = $('#priceExpo').val();
	var postiMaxExpo = $('#maxSeatsExpo').val();
	var formExpo = {
		'action': 'addExpo',
		'idExpo': selectedEvent,
		'nomeExpo': nomeExpo,
		'descExpo': descExpo,
		'dateStartExpo': dataStart,
		'dateEndExpo': dataEnd,
		'priceExpo': priceExpo,
		'maxSeatsExpo': postiMaxExpo,
		'timeslots': timeslots['event'+selectedEvent]
	};
	if(editing){
		formExpo.action = 'editExpo';
		formExpo.timeslots = timeslots['event'+selectedEvent];
		formExpo.idExpo = selectedEvent;
	}
	console.log(formExpo);
	console.log(timeslots['event'+selectedEvent]);
	$.ajax({
		type: "GET",
		cache: false,
		url: "./includes/router.php",
		data: formExpo,
		success: function(response) {
			if(response == 'success-edit'){
				resetFormExpo();
				loadTableExpoAndTimeSlot();
				$('#formErrExpo').html("Esposizione modificata con <strong>successo</strong>");
				$('#formErrExpo').addClass('alert-success');
				$('#formErrExpo').removeClass('d-none');
				editing=false;
			}else if (response == 'success') {
				$('#formErrExpo').html("Esposizione inserita con <strong>successo</strong>");
				$('#formErrExpo').addClass('alert-success');
				$('#formErrExpo').removeClass('d-none');
				resetFormExpo();
				loadTableExpoAndTimeSlot();
			} else {
				$('#formErrExpo').html("<strong>Avviso:</strong> " + response);
				$('#formErrExpo').addClass('alert-danger');
				$('#formErrExpo').removeClass('d-none');
			}

			console.log(response);
		},
		error: function() {
			$('#formErrExpo').html("<strong>Avviso:</strong> impossibile stabilire una connessione con il server.");
			$('#formErrExpo').addClass('alert-danger');
			$('#formErrExpo').removeClass('d-none');
		}
	});
	return false;
});

function loadDeleteExpoModal(id, inuse) {
	if (!inuse) {
		$('#deleteExpoModalBody').html('Sei sicuro di voler eliminare l\'esposizione "' + $('#' + id + 'expotitle').text() + '"?<br>Verranno eliminati dati e foto riguardanti l\'esposizione');
		$('#modalDeleteExpo').modal('show');
	} else {
		$('#deleteExpoModalBtn').attr('disabled', 'disabled');
		$('#deleteExpoModalBody').html('Impossibile eliminare l\'esposizione "' + $('#' + id + 'expotitle').text() + '".<br>L\'esposizione è in corso');
		$('#modalDeleteExpo').modal('show');
	}

}


feather.replace();

//gestione slider fascie orarie

$('#rangetimeslotsadd').on('click', function() {
	var day = timeslots["event" + selectedEvent]["day" + $('#timeslotsdayselect').val()];
	if(totalMinutesUsedForDay(day)<=1309 && (hourToMinutes(day[day.length-1].startHour)+parseInt(day[day.length-1].minutes)<1338)){
		if(day.length>0){
			day.push({
				'id': null,
				'startHour': getTimeFormat(hourToMinutes(day[day.length-1].startHour)+50+parseInt(day[day.length-1].minutes)),
				'minutes': 150
			});
		}else{
			day.push({
				'id': null,
				'startHour': '00:05',
				'minutes': 150
			});
		}
		parseTimeSlotsForDay(selectedEvent, $('#timeslotsdayselect').val());
	}else{
		
	}
});

function updateTimeSlots(values, handle) {
	var day = timeslots["event" + selectedEvent]["day" + $('#timeslotsdayselect').val()];
	var label = "Orari di Apertura<br>";
	if (day != null) {
		var newvalues = [];
		for (var i = 0; i < values.length; i += 2) {
			newvalues.push([values[i], values[i + 1]]);
		}
		for (var i = 0; i < day.length; i++) {
			if (newvalues[i][0] == newvalues[i][1] && day.length == 1) {
				label = "Orari di Apertura<br>&nbsp;&nbsp;Chiuso";
			} else if (newvalues[i][0] == newvalues[i][1]) {
				day[i] = null;
				day.splice(i, 1);
				timeslots["event" + selectedEvent]["day" + $('#timeslotsdayselect').val()] = day;
				parseTimeSlotsForDay(selectedEvent, $('#timeslotsdayselect').val());
				return;
			} else {
				day[i].startHour = getTimeFormat(newvalues[i][0]);
				day[i].minutes = newvalues[i][1] - newvalues[i][0];
				label += "&nbsp;&nbsp;-dalle " + day[i].startHour + " alle " + getTimeFormat(hourToMinutes(day[i].startHour) + parseInt(day[i].minutes)) + "<br>";
			}
		}
		if(day.length != 1 || newvalues[0][0] != newvalues[0][1])
			label += "per un totale di " + totalMinutesUsedForDay(day) + " minuti";
	} else {
		if (values[0] != 0 || values[1] != 0) {
			day = [];
			day.push({
				'id': null,
				'startHour': getTimeFormat(values[0]),
				'minutes': (values[1] - values[0])
			});
		}
	}
	timeslots["event" + selectedEvent]["day" + $('#timeslotsdayselect').val()] = day;
	$('#rangetimeslotslabel').html(label);
	tsApplyToAll();
}

function totalMinutesUsedForDay(day){
	var total = 0;
	for(var i=0;i<day.length;i++){
		total+= parseInt(day[i].minutes);
	}
	return total;
}

function getTimeFormat(minutes) {
	var hours = Math.floor(minutes / 60);
	var min = minutes % 60;
	if (hours < 10)
		hours = "0" + hours;
	if (min < 10)
		min = "0" + min;
	return hours + ":" + min;
}

function hourToMinutes(hour) {
	var comp = hour.split(":");
	return parseInt(comp[0]) * 60 + parseInt(comp[1]);
}

function colorRangeTS() {
	var selected = range.querySelectorAll('.noUi-connect');
	var basens = range.querySelectorAll('.noUi-base');
	for (var i = 0; i < selected.length; i++) {
		selected[i].classList.add('timeslotOpen');
	}
	basens[0].classList.add('timeslotClosed');
}

function parseTimeSlotsForDay(eventid, day) {
	var tss;
	if(eventid!=null){
		tss = timeslots["event" + eventid]["day" + day];	
	}else{
		timeslots["eventnew"] = {};
		timeslots["eventnew"]["day" + day] = null;
		selectedEvent = "new";
		tss = timeslots["eventnew"]["day" + day];
	}

	var label = "Orari di Apertura<br>";
	if (tss != null) {
		rnSettings.start = [];
		rnSettings.connect = [false];
		for (var i = 0; i < tss.length; i++) {
			rnSettings.start.push(hourToMinutes(tss[i].startHour));
			rnSettings.start.push(hourToMinutes(tss[i].startHour) + parseInt(tss[i].minutes));
			console.table(rnSettings);
			if (tss.length == 1) {
				rnSettings.connect = true;
			} else {
				rnSettings.connect.push(true);
				rnSettings.connect.push(false);
			}
			label += "&nbsp;&nbsp;-dalle " + tss[i].startHour + " alle " + getTimeFormat(hourToMinutes(tss[i].startHour) + parseInt(tss[i].minutes)) + "<br>";
		}
		label += "per un totale di " + totalMinutesUsedForDay(tss) + " minuti";
	} else {
		rnSettings.start = [0, 0];
		rnSettings.connect = true;
		label = "Orari di Apertura<br>&nbsp&nbsp;Chiuso";
	}
	range.noUiSlider.destroy();
	noUiSlider.create(range, rnSettings);
	range.noUiSlider.on('change', updateTimeSlots);
	range.noUiSlider.on('slide', updateTimeSlots);
	$('#rangetimeslotslabel').html(label);
	colorRangeTS();
}

$('#timeslotsdayselect').on('change', function() {
	if($(this).val()!=0){
		parseTimeSlotsForDay(selectedEvent, $(this).val());
		range.removeAttribute('disabled');
		$('#rangetimeslotsadd').css('display','block');
		$('#tsCopyAllCont').css('display','block');
	}else{
		range.noUiSlider.destroy();
		rnSettings.start=[0];
		rnSettings.connect=false;
		noUiSlider.create(range, rnSettings);
		range.setAttribute('disabled',true);
		$('#rangetimeslotsadd').css('display','none');
		$('#tsCopyAllCont').css('display','none');
		$('#rangetimeslotslabel').html('Orari di apertura<br>&nbsp;&nbsp;Seleziona un giorno');
	}
});

$('#tsCopyAllCont').on('change',tsApplyToAll);

function tsApplyToAll(){
	var currentConf = timeslots["event" + selectedEvent]["day" + $('#timeslotsdayselect').val()];
	if($('#tsCopyAllCont').children('input')[0].checked){
		for(var i=1;i<=7;i++){
			timeslots["event" + selectedEvent]["day" + i] = currentConf;
		}
		parseTimeSlotsForDay(selectedEvent, $('#timeslotsdayselect').val());
	}
}


//gestione modifica delle esposizioni

function loadEditExpoForm(event){
	resetFormExpo();
	editing = true;
	//reset avvisi
	$('#formErrExpo').html("");
	$('#formErrExpo').addClass('d-none');
	$('#formErrExpo').removeClass('alert-danger');
	$('#formErrExpo').removeClass('alert-success');
	var expoid = event.currentTarget.getAttribute('data-expoid');
	$('#titleCreateEditExpo').html('Modifica un\'esposizione');
	$('#titleCreateEditExpo').removeClass('text-muted');
	$('#btnInsertExpo').removeClass('btn-primary');
	$('#btnInsertExpo').addClass('btn-warning');
	$('#btnResetExpoForm').html('Annulla');
	$('#btnInsertExpo').html('Modifica l\'esposizione');
	$('.expocontainer').removeClass('table-warning');
	$('#'+expoid+'expocontainer').addClass('table-warning');
	//riempimento campi
	$('#nameExpo').val($('#'+expoid+'expotitle').text());
	$('#descExpo').val($('#'+expoid+'expodesc').attr('data-alldesc'));
	$('#dateStartExpo').val($('#'+expoid+'exposdate').attr('data-realdate'));
	$('#dateEndExpo').val($('#'+expoid+'expoedate').attr('data-realdate'));
	$('#priceExpo').val($('#'+expoid+'expoprice').text());
	$('#maxSeatsExpo').val($('#'+expoid+'expomseats').text());
	//caricamento timeslots
	selectedEvent = expoid;
}