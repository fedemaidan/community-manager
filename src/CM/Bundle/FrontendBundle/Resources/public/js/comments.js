$(document).ready(function() {
		$(document).ajaxStart(function () {
			$.get('session/check',{},function (data) {
				if (data !== 'true' ) {
					window.location.replace('/');
				}
			});
		});
		
       confComportamiento();
       init();
});

function init() {
	$(document).find("li.comentario[data-calificacion=0]:first").click();
	moverScrollAlComentarioACalificar();
}

function configurarClickComentario() {
	 $(document).on('click','li.comentario',{},function (e) {
		//cargo id del comentario clickeado
		 $comentario_id = $(this).data('comentarioId');
		 
		 //si ya estaba seleccionado no hago anda
		 if($comentario_id == $("li.comentario.active").data('comentarioId')) {
			 return false;
		 }
		 //des-selecciono el antiguo seleccionado
		 $("li.comentario.active").removeClass('active');
		 
		 //selecciono el nuevo seleccionado
		 $(this).addClass('active');
	 });
}


function confComportamiento() {

//    inicializarTagit();
      configurarAtajosDeTeclado();
      configurarClickComentario();
      configurarBotonCalificar();
      configurarBotonMoreComments();
      configurarBotonBorrarComentario();
      configurarDestacar();	
      loadDefaultDate();
}


function configurarBotonCalificar() {
	
$(document).on('click','.bton-qualify',{},function(e) {
    e.stopPropagation();
    var _this = this;
    var comentario_id = $(this).parents('li.comentario').data('comentarioId');
    var row = $(this).parents('li.comentario').find('.row');
    var calificacion = $(this).data('calificacion');

    $.post('CM/qualify', {
            comentario_id : comentario_id,
            calificacion : calificacion
    }, function(data) {
            if (data) {
            		var comentario = $(_this).parents('li.comentario');
            		if (calificacion == $(comentario).data("calificacion"))
            				calificacion = 0;
            		
            		$(comentario).data("calificacion",calificacion);
                    $(comentario).attr("data-calificacion",calificacion); 
                    $(row).removeClass('colorCalificacion-blue');
                    $(row).removeClass('colorCalificacion-green');
                    $(row).removeClass('colorCalificacion-red');
                    $(row).removeClass('colorCalificacion-white');
                    
                    var seleccionado = $(comentario).find('.circle-selected');
                    $(seleccionado).removeClass('circle-selected');
                    
                    
                    if( calificacion == 1) {
                    	//boton delete invisible
                    	$(comentario).find('.bton-delete').removeClass('visible');
                    	$(comentario).find('.bton-delete').addClass('invisible');
                    	//color fondo row
                    	$(row).addClass('colorCalificacion-blue');
                    	//agrego estilo boton seleccionado
                    	$(_this).addClass('circle-selected');
                    	
                    }
                    else if (calificacion == 2) {
                    	//boton delete invisible
                    	$(comentario).find('.bton-delete').removeClass('visible');
                    	$(comentario).find('.bton-delete').addClass('invisible');
                    	//color fondo row
                    	$(row).addClass('colorCalificacion-green');
                    	//agrego estilo boton seleccionado
                    	$(_this).addClass('circle-selected');

                    }
                    else if (calificacion == 3) {
                    	//boton delete invisible
                    	$(comentario).find('.bton-delete').removeClass('invisible');
                    	$(comentario).find('.bton-delete').addClass('visible');
                    	//color fondo row
                    	$(row).addClass('colorCalificacion-red');
                    	//agrego estilo boton seleccionado
                    	$(_this).addClass('circle-selected');
                    }
                    else if (calificacion == 0) {
                    	$(comentario).find('.bton-delete').addClass('invisible');
                    	$(comentario).find('.bton-delete').removeClass('visible');
                    	//color fondo panelMensaje
                    	$(row).addClass('colorCalificacion-white');
                    	
                    	
                    }
                    
            } else  {
                    
            }
            (comentario).click();
            moveToNextComment();
    });
    
    
});
}

function configurarClickComentario() {
	 $(document).on('click','li.comentario',{},function (e) {
		//cargo id del comentario clickeado
		 $comentario_id = $(this).data('comentarioId');
		 
		 //si ya estaba seleccionado no hago anda
		 if($comentario_id == $("li.comentario.active").data('comentarioId')) {
			 return false;
		 }
		 //des-selecciono el antiguo seleccionado
		 $("li.comentario.active").removeClass('active');
		 
		 //selecciono el nuevo seleccionado
		 $(this).addClass('active');
	 });
}

function configurarAtajosDeTeclado() {
	 $(document).keydown(function(event) {
		
		 //negativo
		 if(event.keyCode == 40 && event.ctrlKey) {
			 event.preventDefault();
			 $("li.comentario.active").find('.bton-qualify[data-calificacion=3]').click();
		 }
		 
		 //positivo
		 if (event.keyCode == 38 && event.ctrlKey) {
			 event.preventDefault();
			 $("li.comentario.active").find('.bton-qualify[data-calificacion=2]').click();
		 }
		 
		 //neutro
		 if ((event.keyCode == 37 || event.keyCode == 39) && event.ctrlKey ) {
			 event.preventDefault();
			 $("li.comentario.active").find('.bton-qualify[data-calificacion=1]').click();
		 }
	 });
}

function configurarBotonMoreComments() {
	 $(document).on('click','.bton-mas-comentarios', {}, function(e)  {
		 var comentariosLength = $('ul.comentarios li').length;
		 var comentarios =$('ul.comentarios');
		 var created_time = $(comentarios).find('li.comentario').filter(':last').data('createdTime');
		 
		 $.ajax({
			type: "POST",
			url: "CM/addComments",
			data: {start: comentariosLength},
		 }).done (function(data){
			 $(comentarios).append(data);
			 if (data == "") {
				 $('.bton-mas-comentarios').hide();
			 }
			 else {
				 moveToNextComment();
			 }
		 });
	 });		 
}

function moveToNextComment() {
	var nextComment = $('li.comentario.active').next();
	moverScrollAlComentarioACalificar();
	
	if ($(nextComment).length != 0) {
		$(nextComment).click();
	}
	else  {
		$('.bton-more-comments-after').click();
		$(nextComment).click();
	}
 }

function moverScrollAlComentarioACalificar() {
	 $('.col-center').animate({
			scrollTop: $('li.comentario.active').offset().top - $('.col-center').offset().top + $('.col-center').scrollTop()
		});
}

function configurarBotonBorrarComentario() {
	
	 $(document).on('click','.bton-delete',{},function (e) {
		 	 e.stopPropagation();
		 	 $(this).parents('li.comentario').click();
			 var comentario_id = $(this).parents('li.comentario').data('comentarioId');
			 
			 $.ajax({
					type: "POST",
					url: "CM/deleteComments",
					data: {comentario_id: comentario_id},
				 }).done (function(data){
					 $("li.comentario.active").hide();
					 moveToNextComment();
				 });
		    });
}

function configurarDestacar() {
 		$(document).on('click','.destacado', {} ,function(e){
		 	var _this = this;
		 	 $(this).parents('li.comentario').click();
			 var comentario_id = $(this).parents('li.comentario').data('comentarioId');
			 var direccionNoDestacado = $(this).data("direccionNoDestacado");
			 $(this).addClass('noDestacado');
			 $(this).removeClass('destacado');
			 $.ajax({
					type: "POST",
					url: "CM/destacar",
					data: {comentario_id: comentario_id, valor: false},
				 }).done (function(data){

				 	console.log(direccionNoDestacado);
					_this.src = direccionNoDestacado;

					$(this).addClass('noDestacado');
					$(this).removeClass('destacado');
				 });
		    });

 		$(document).on('click','.noDestacado', {} ,function(e){
		 	var _this = this;

		 	$(this).parents('li.comentario').click();
			var comentario_id = $(this).parents('li.comentario').data('comentarioId');
			var direccionDestacado = $(this).data("direccionDestacado");
			$(this).addClass('destacado');
			$(this).removeClass('noDestacado');				 

			 $.ajax({
					type: "POST",
					url: "CM/destacar",
					data: {comentario_id: comentario_id, valor: true},
				 }).done (function(data){
				 	console.log(direccionDestacado);
					_this.src = direccionDestacado;
				 });
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
	//$('input[name="daterange"]').val(startDate + ' - ' + endDate);

	}

	 

	Date.prototype.getMonthTwoDigits = function()
	{
	  var retval = this.getMonth()+1;
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
