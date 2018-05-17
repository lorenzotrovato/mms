var timeslots = [];
var original_ts = [];
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
			timeslots = original_ts = JSON.parse(response);
			console.log(timeslots);
		},
		error: function() {

		}
	});
}

function resetFormExpo(){
	document.getElementById('insertExpo').reset();
	selectedEvent = null;
	timeslots = original_ts;
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
	$('#expoImage').attr('src','');
	$('#expoImage').addClass('d-none');
	$('#expoImagePrev').removeClass('d-none');
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
		'timeslots': timeslots[selectedEvent]
	};
	if(editing===true){
		formExpo.action = 'editExpo';
		formExpo.timeslots = timeslots[selectedEvent];
		formExpo.idExpo = selectedEvent;
	}
	console.log(formExpo);
	console.log(timeslots[selectedEvent]);
	
	$.ajax({
		type: "GET",
		cache: false,
		url: "./includes/router.php",
		data: formExpo,
		success: function(response) {
			if(response == 's-edit'){
				resetFormExpo();
				loadTableExpoAndTimeSlot();
				$('#formErrExpo').html("Esposizione modificata con <strong>successo</strong>");
				$('#formErrExpo').addClass('alert-success');
				$('#formErrExpo').removeClass('d-none');
				editing=false;
				
			}else if (response.includes('success')) {
				$('#formErrExpo').html("Esposizione inserita con <strong>successo</strong>");
				$('#formErrExpo').addClass('alert-success');
				$('#formErrExpo').removeClass('d-none');
				if($('#fileImageExpo').prop('files').length==1){
					$('#formErrExpo').html("Esposizione inserita con <strong>successo</strong>. Caricamento immagine di copertina");
					uploadImage($('#fileImageExpo').prop('files')[0],parseInt(response.replace('success','')),function(){
						resetFormExpo();
						loadTableExpoAndTimeSlot();
					});
				}
				
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
		$('#deleteExpoModalBtn').attr('data-expoid',id);
		$('#deleteExpoModalBtn').removeAttr('disabled');
	} else {
		$('#deleteExpoModalBtn').attr('disabled', 'disabled');
		$('#deleteExpoModalBody').html('Impossibile eliminare l\'esposizione "' + $('#' + id + 'expotitle').text() + '".<br>L\'esposizione è in corso');
		$('#modalDeleteExpo').modal('show');
	}

}


$('#deleteExpoModalBtn').on('click',function(event){
	var expoid = event.target.getAttribute('data-expoid');
	$('#deleteExpoModalBtn').attr('disabled','disabled');
	$('#deleteExpoModalBtn').text('Eliminazione...');
	$.ajax({
		type: "GET",
		cache: false,
		url: "./includes/router.php",
		data: {'action': 'deleteExpo','expoid':expoid},
		success: function(response){
			console.log(response);
			$('#modalDeleteExpo').modal('hide');
			$('#deleteExpoModalBtn').removeAttr('disabled');
			$('#deleteExpoModalBtn').text('Elimina');
			$('#deleteExpoErr').removeClass('d-none');
			if(response=="delete-success"){
				$('#deleteExpoErr').addClass('alert-success');
				$('#deleteExpoErr').html("<strong>Avviso:</strong> esposizione eliminata correttamente");
				loadTableExpoAndTimeSlot();
			}else{
				$('#deleteExpoErr').addClass('alert-danger');
				$('#deleteExpoErr').html("<strong>Avviso:</strong> " + response);	
			}
			window.setTimeout(resetDeleteErrExpo, 5000);
		},
		error: function(){
			$('#modalDeleteExpo').modal('hide');
			$('#deleteExpoModalBtn').removeAttr('disabled');
			$('#deleteExpoModalBtn').text('Elimina');
			$('#deleteExpoErr').removeClass('d-none');
			$('#deleteExpoErr').html("<strong>Avviso:</strong> impossibile stabilire una connessione con il server.");	
			window.setTimeout(resetDeleteErrExpo, 5000);
		}
	});
});

function resetDeleteErrExpo(){
	$('#deleteExpoErr').addClass('d-none');
	$('#deleteExpoErr').removeClass('alert-success');
	$('#deleteExpoErr').removeClass('alert-danger');
	$('#deleteExpoErr').html('');
}

feather.replace();

//gestione slider fascie orarie

$('#rangetimeslotsadd').on('click', function() {
	var day = timeslots[selectedEvent][$('#timeslotsdayselect').val()];
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
	var day = timeslots[selectedEvent][$('#timeslotsdayselect').val()];
	var label = "Orari di Apertura<br>";
	if (day != null) {
		var newvalues = [];
		for (var i = 0; i < values.length; i += 2) {
			newvalues.push([values[i], values[i + 1]]);
		}
		for (var i = 0; i < newvalues.length; i++) {
			//console.log(dayEmpty(values));
			if (dayEmpty(values)) {
				label = "Orari di Apertura<br>&nbsp;&nbsp;Chiuso";
			} else if (newvalues[i][0] == newvalues[i][1]) {
				if(editing){
					day[i].minutes = 0;
				}else{
					day[i] = null;
					day.splice(i, 1);
					console.log(day[i]);
					/*newvalues.splice(i, 1);
					i--;*/
				}
				timeslots[selectedEvent][$('#timeslotsdayselect').val()] = day;
				parseTimeSlotsForDay(selectedEvent, $('#timeslotsdayselect').val());
				return;
			} else {
				day[i].startHour = getTimeFormat(newvalues[i][0]);
				day[i].minutes = newvalues[i][1] - newvalues[i][0];
				label += "&nbsp;&nbsp;-dalle " + day[i].startHour + " alle " + getTimeFormat(hourToMinutes(day[i].startHour) + parseInt(day[i].minutes)) + "<br>";
			}
		}
		if(!dayEmpty(values))
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
	timeslots[selectedEvent][$('#timeslotsdayselect').val()] = day;
	$('#rangetimeslotslabel').html(label);
	if($('#tsCopyAllCont').children('input')[0].checked){
		tsApplyToAll();	
	}
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

function dayEmpty(values){
	var day = timeslots[selectedEvent][$('#timeslotsdayselect').val()];
	var diversi = false;
	for (var i = 0; i < values.length; i += 2) {
		if(values[i]!=values[i+1])
			diversi = true;
	}
	return (!editing && day.length==1 && !diversi) || (editing && !diversi);
}

function parseTimeSlotsForDay(eventid, day) {
	var tss;
	if(eventid!=null){
		if(timeslots[eventid]==null){
			timeslots[eventid] = {};
			timeslots[eventid][day] = null;
			tss = timeslots[eventid][day];
		}else{
			tss = timeslots[eventid][day];	
		}
	}else{
		timeslots["new"] = {};
		timeslots["new"][day] = null;
		selectedEvent = "new";
		tss = timeslots["new"][day];
	}

	var label = "Orari di Apertura<br>";
	if (tss != null) {
		if(tss.length>0){
			rnSettings.start = [];
			rnSettings.connect = [false];
			for (var i = 0; i < tss.length; i++) {
				if(parseInt(tss[i].minutes)>0){
					rnSettings.start.push(hourToMinutes(tss[i].startHour));
					rnSettings.start.push(hourToMinutes(tss[i].startHour) + parseInt(tss[i].minutes));
					if (tss.length == 1) {
						rnSettings.connect = true;
					} else {
						rnSettings.connect.push(true);
						rnSettings.connect.push(false);
					}
					label += "&nbsp;&nbsp;-dalle " + tss[i].startHour + " alle " + getTimeFormat(hourToMinutes(tss[i].startHour) + parseInt(tss[i].minutes)) + "<br>";
				}
			}
			label += "per un totale di " + totalMinutesUsedForDay(tss) + " minuti";
		}else{
			rnSettings.start = [0, 0];
			rnSettings.connect = true;
			label = "Orari di Apertura<br>&nbsp&nbsp;Chiuso";
			tss.push({id: null,startHour: "00:00",minutes:0});
		}
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
	var currentConf = timeslots[selectedEvent][$('#timeslotsdayselect').val()];
	for(var i=1;i<=7;i++){
		if(i!=$('#timeslotsdayselect').val()){
			timeslots[selectedEvent][i] = [];
			//inizializzazione giorno
			for(var x=0;x<currentConf.length;x++){
				timeslots[selectedEvent][i].push({
					'id': null,
					'startHour': '00:00',
					'minutes': 0
				});
			}
			for(var x=0;x<timeslots[selectedEvent][i].length;x++){
				timeslots[selectedEvent][i][x].startHour = currentConf[x].startHour;
				timeslots[selectedEvent][i][x].minutes = currentConf[x].minutes;
			}
		}
		
	}
	parseTimeSlotsForDay(selectedEvent, $('#timeslotsdayselect').val());
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
	timeslots = original_ts;
	selectedEvent = expoid;
	//caricamento immagine
	var pathimg = $('#'+expoid+"expocontainer").attr('data-coverimage');
	if(pathimg!=""){
		$('#expoImage').attr('src','images/covers/'+pathimg);
		$('#expoImage').css('width','100px');
		$('#expoImage').css('height','auto');
		$('#expoImage').removeClass('d-none');
		$('#expoImagePrev').addClass('d-none');
	}else{
		$('#expoImage').addClass('d-none');
		$('#expoImagePrev').removeClass('d-none');
	}
	
}


//gestione immagine esposizione
$("input[type=file]").change(function () {
	var fieldVal = $(this).val();
	// Change the node's value by removing the fake path (Chrome)
	fieldVal = fieldVal.replace("C:\\fakepath\\", "");
	if (fieldVal != undefined || fieldVal != "") {
		$(this).next(".custom-file-label").text(fieldVal);
	}
});

function uploadImage(file,expoid,callback){
	if(file.type!="image/jpeg" && file.type!="image/png"){
		$('#expoImageLoadLabel').text('Caricamento immagine: File non supportato');
		console.log(file.type);
		return;
	}
	var ajax = new XMLHttpRequest();
	ajax.addEventListener('load', function(data){
		$('#expoImageLoadLabel').text('Completato');
		console.log(data.target.responseText);
		var msg = JSON.parse(data.target.responseText);
		if(msg.result=="ok"){
			$('#expoImage').attr('src',msg.imagepath);
			$('#expoImage').css('width','100px');
			$('#expoImage').css('height','50px');
			$('#expoImage').removeClass('d-none');
			$('#expoImagePrev').addClass('d-none');
			callback();
		}else{
			$('#expoImageLoadLabel').text('Errore: ' + msg.result);
		}
	});
	
	ajax.upload.addEventListener('progress', function(evt){
		if (evt.lengthComputable) {
            var percentComplete = (evt.loaded / evt.total)*100;
            $('#expoImageLoadProg').css('width',percentComplete.toFixed(2) + "%");
            $('#expoImageLoadLabel').text('Caricamento immagine: ' + percentComplete.toFixed(2) + "%");
        }
	});
	
	var data = new FormData();
	data.append('expoid',expoid);
	data.append('image', file);
	ajax.open('POST', './includes/uploadImageExpo.php');
	ajax.send(data);
}