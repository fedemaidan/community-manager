$(document).ready(function() {
	$(document).ajaxStart(function () {
		$.get('session/check',{},function (data) {
			if (data !== 'true' ) {
				window.location.replace('/');
			}
		});
	});
		initVisualizacionFormularios();

});

function initVisualizacionFormularios() {
	$('.password').hide();
		$(document).on('click','.btn-alta',{},function (e) {
			mostrarAlta();
		 });
		
		$(document).on('click','.btn-lista',{},function (e) {
			mostrarLista();
		 });
		
		$(document).on('click','.btn-modificar',{},function (e) {
			mostrarModificacion();
		 });
		
		$(document).on('click','.changePass',{},function (e) {
			$('.password').toggle();
		 });
		
}


function mostrarAlta() {
	$('.lista').hide();
	$('.modificacion').hide();
	$('.alta').show();
}

function mostrarModificacion() {

	$('.lista').hide();
	$('.modificacion').show();
	$('.alta').hide();
}

function mostrarLista() {
	$('.lista').show();
	$('.modificacion').hide();
	$('.alta').hide();
}