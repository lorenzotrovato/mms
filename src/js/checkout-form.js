$(document).ready(function() {
	validate();
	$('#cardTypeRadio input').change(function() {
		$('#cardType').html($('input[name=paymentMethod]:checked').val());
	});
	
	$('#m-expiration').on('keypress', function(e) {
		var chars = $(this).val();
		if((e.keyCode < 48 || e.keyCode > 57) || ((chars.length+1) > 2)) {
			e.preventDefault();
			e.stopPropagation();
		}
	});
	
	$('#y-expiration').on('keypress', function(e) {
		var chars = $(this).val();
		if((e.keyCode < 48 || e.keyCode > 57) || ((chars.length+1) > 4)) {
			e.preventDefault();
			e.stopPropagation();
		}
	});
	
	$('#cc-number').on('keypress', function(e) {
		console.log($(this));
		var chars = $(this).val();
		if((e.keyCode < 48 || e.keyCode > 57) || ((chars.length+1) > 16)) {
			e.preventDefault();
			e.stopPropagation();
		}
	});
	
	$('#cc-cvv').on('keypress', function(e) {
		var chars = $(this).val();
		if((e.keyCode < 48 || e.keyCode > 57) || ((chars.length+1) > 3)) {
			e.preventDefault();
			e.stopPropagation();
		}
	});
});

// if(e.keyCode >= 48 && e.keyCode <= 57) { 

function validate(){
	var forms = document.getElementsByClassName('needs-validation');
			
	// Loop over them and prevent submission
	var validation = Array.prototype.filter.call(forms, function(form) {
		form.addEventListener('submit', function(event) {
			if (form.checkValidity() === false) {
				event.preventDefault();
				event.stopPropagation();
				form.classList.add('was-validated');
			}
		}, false);
	});
}