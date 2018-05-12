$(document).ready(function() {
	dashFromUrl(true);
	
	$(document).on('click', 'a.sb-link', function(e) {
		e.preventDefault();
		var href = $(this).attr('href');
		getPageContent(href, true);
	});

	window.onpopstate = function() {
		dashFromUrl(false);
	};
});

function dashFromUrl(hiPush) {
	var sec = getUrlParameter('sec');
	getPageContent(sec, hiPush);
}

function getUrlParameter(sParam) {
	var sPageURL = decodeURIComponent(window.location.search.substring(1)),
		sURLVariables = sPageURL.split('&'),
		sParameterName,
		i;

	for (i = 0; i < sURLVariables.length; i++) {
		sParameterName = sURLVariables[i].split('=');

		if (sParameterName[0] === sParam) {
			return sParameterName[1] === undefined ? true : sParameterName[1];
		}
	}
}

function getPageContent(sec, hiPush) {
	$('#mainsection').fadeOut(200, function(){
		$('#mainsection').load(getPageName(sec, hiPush), function(){
			$(this).fadeIn(200);	
		});	
	});
}

function getPageName(sec, hiPush) {
	var page;
	switch (sec) {
		case 'accessories':
			page = 'pages/adminpanel/accessories.php';
			sec = 'accessories';
			break;
		case 'categories':
			page = 'pages/adminpanel/categories.php';
			sec = 'categories';
			break;
		case 'dash':
			page = 'pages/adminpanel/dash.php';
			sec = 'dash';
			break;
		case 'expos':
			page = 'pages/adminpanel/expos.php';
			sec = 'expos';
			break;
		case 'finances':
			page = 'pages/adminpanel/finances.php';
			sec = 'finances';
			break;
		case 'tickets':
			page = 'pages/adminpanel/tickets.php';
			sec = 'tickets';
			break;
		default:
			page = 'pages/adminpanel/dash.php';
			sec = 'dash';
	}
	refreshSideBar($('a[href="'+sec+'"]'));
	if (hiPush) {
		history.pushState({}, null, window.location.pathname + "?sec=" + sec);
	}
	return page;
}

function refreshSideBar(element) {
	$('a.sb-link.active').removeClass('active');
	element.addClass('active');
}