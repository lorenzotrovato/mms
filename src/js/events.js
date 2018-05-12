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

			$('body').css({
				'overflow-y': 'hidden'
			})
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
						$('body').css('overflow', 'auto');
						ghostcard.remove();
					}, 200);
				});
			});
		}
	});
	
	$('#datetimepicker').datetimepicker({
        format: 'L',
        locale: 'it'
    });

	$(document).on('click', '.eventbuy', function(event) {
		var eventid = $(this).attr('eventid');
		$.ajax({
			type: "GET",
			async: true,
			cache: false,
			url: "./includes/router.php",
			data: "action=getEventInfo&eventid="+eventid,
			success: function(response) {
				var data = JSON.parse(response);
				var currentDate = new Date();
				var startDate = new Date(data['startDate']);
				var endDate = new Date(data['endDate']);
				if(currentDate > startDate){
					startDate = currentDate;
				}
				var disabledDays = [];
				for(var i = 1; i <= 7; i++){
					if(data['timeSlots'][i].length == 0){
						if(i == 7){
							disabledDays.push(0); 
						}else{
							disabledDays.push(i);
						}
					}
				}
				if(disabledDays.length < 7){
					$('#datetimepicker').datetimepicker('minDate', startDate.getDate() + '/' + startDate.getMonth() + '/' + startDate.getFullYear());
					$('#datetimepicker').datetimepicker('maxDate', endDate.getDate() + '/' + endDate.getMonth() + '/' + endDate.getFullYear());
					$('#datetimepicker').datetimepicker('daysOfWeekDisabled', disabledDays);
				    $('#modalBuy').modal('show');	
				}
			}
		});
	})
});