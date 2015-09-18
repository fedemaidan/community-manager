$(document).ready(function() {
	$(document).ajaxStart(function () {
		$.get('session/check',{},function (data) {
			if (data !== 'true' ) {
				window.location.replace('/');
			}
		});
	});
		initVisualizacionFormularios();
		initBotonGenerarToken();

//		botonLogin();
//		btnActualizarFanpage();
});


function initBotonGenerarToken() {
	$(document).on('click','.btnLogin',{},function (e) {
			generarToken();
	});
}
function initVisualizacionFormularios() {

		$(document).on('click','.btn-alta',{},function (e) {
			mostrarAlta();
		 });
		
		$(document).on('click','.btn-lista',{},function (e) {
			mostrarLista();
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

function botonLogin() {
	$(document).on('click','.btnLogin', {} ,function(e){
		var appSecret = document.getElementById("appSecret").value;
		var appId = document.getElementById("appId").value;
		
		$.ajax({
			 type: "POST",
			 url: "loginFacebook",
			 data: {appSecret: appSecret, appId: appId},
		 });
	});
}


function generarToken () {
	 

		  FB.init({
		    appId      : '{% if fanpage.appId is defined%}{{ fanpage.appId }}{% endif %}',
		    cookie     : true,  // enable cookies to allow the server to access 
		                        // the session
		    xfbml      : true,  // parse social plugins on this page
		    version    : 'v2.2' // use version 2.1
		  });
		  
		  FB.getLoginStatus(function(response) {
			    statusChangeCallback(response);
			  });




}
function statusChangeCallback2(response, appId, appSecret, fanpageId) {
     
    // The response object is returned with a status field that lets the
    // app know the current login status of the person.
    // Full docs on the response object can be found in the documentation
    // for FB.getLoginStatus().
    if (response.status === 'connected') {
    	
    	$.ajax({
    		type: "POST",
    		url: "renuevaAccessToken",
    		data: { access_token: response.authResponse.accessToken, appId: appId, appSecret: appSecret, fanpageId: fanpageId}
    	 }).done (function(data){
    		 if (data == "0")
    			 document.getElementById('status').innerHTML = 'Usuario sin permisos para cargar access token';
    		 else {
    			 document.getElementById('status').innerHTML = 'Nuevo access token cargado correctamente';
    			 location.reload(true);
    		 }
    	 });
    	
    } else if (response.status === 'not_authorized') {
      // The person is logged into Facebook, but not your app.
      document.getElementById('status').innerHTML = 'Please log ' +
        'into this app.';
    } else {
      // The person is not logged into Facebook, so we're not sure if
      // they are logged into this app or not.
      document.getElementById('status').innerHTML = 'Please log ' +
        'into Facebook.';
    }
  }