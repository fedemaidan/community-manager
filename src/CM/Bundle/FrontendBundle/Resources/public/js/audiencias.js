$(document).ready(function() {
		$(document).ajaxStart(function () {
			$.get('session/check',{},function (data) {
				if (data !== 'true' ) {
					window.location.replace('/');
				}
			});
		});
		
		
	   configurarCamposHabilitacion();
       loadDefaultDate();
});

function configurarCamposHabilitacion() {
	$(".textCapa").find('input').attr('disabled','disabled');
	$("#submit").attr('disabled','disabled');
	
	$("#canalDeInteraccionID").change( function () {
			if ($("#canalDeInteraccionID").find('option[value="post"]:selected').length != 0) {
				$(".textCapa").find('input').attr('disabled',false);
			} else {
				$(".textCapa").find('input').attr('disabled',true);
			}
			
			if ($("#canalDeInteraccionID").find('option:selected').length != 0) {
				$("#submit").attr('disabled',false);
				$('.alerta').removeClass('alertaNoVisible');
				$('.alerta').removeClass('alertaVisible');
				$('.alerta').addClass('alertaNoVisible');
			} else {
				$("#submit").attr('disabled',true);
				$('.alerta').removeClass('alertaNoVisible');
				$('.alerta').removeClass('alertaVisible');
				$('.alerta').addClass('alertaVisible');
				
				
			}
		}
	);
}

function configurarClickNoHabilitado() {
	$(document).on('click','.deshabilitado',{}, function (e) {
		 console.log("Debe cargar campos obligatorios que estan definidos con un * delante");
	 });	
	
}

function loadDefaultDate() {

	var defaultDate = new Date();
	var startDate = defaultDate.getFullYear()+'-'+defaultDate.getMonthTwoDigits()+'-'+defaultDate.getDateTwoDigits();
	var endDate = startDate;
	$('input[name="daterange"]').daterangepicker(
	{
	format: 'YYYY-MM-DD',
	startDate: startDate,
	endDate: endDate,
	showDropdowns: true
	}
	);
	$('input[name="daterange"]').on('hide.daterangepicker', function(ev, picker) {
	//do something, like clearing an input
	$('input[name="daterange"]').val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format('YYYY-MM-DD'))
	});
	$('input[name="daterange"]').val(startDate + ' - ' + endDate);

	}

	 

	Date.prototype.getMonthTwoDigits = function()
	{
	  var retval = this.getMonth();
	  if (retval < 10)
	  {
	    return ("0" + retval.toString());
	  }
	  else
	  {
	    return retval.toString();
	  }
	}

	 

	Date.prototype.getDateTwoDigits = function()
	{
	  var retval = this.getDate();
	  if (retval < 10)
	  {
	    return ("0" + retval.toString());
	  }
	  else
	  {
	    return retval.toString();
	  }
	}
