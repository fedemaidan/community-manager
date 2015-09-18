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

function confComportamiento() {

    inicializarTagit();
    configurarAtajosDeTeclado();
    configurarClickPost();
    configurarClickComentario();
    configurarBotonCalificar();
    configurarBotonMore();
    //configurarScrollComments();
    configurarBotonMoreComments();
    configurarTagRapido();
    configurarLinks();
    configurarDestacar();
}

function inicializarTagit() {
       var arrowSelection = false;
       
       $('#tags').tagit({
    	   				minLength: 3,
                       allowSpaces: true,
                       singleFieldNode: $(".tags-input"),
                       
                       beforeTagAdded: function (event,ui) {
                    	   if(ui.tagLabel.length < 3) {
                    		   return false;
                    	   }
                       },
       					afterTagAdded: function (event,ui) {
       						if(!ui.duringInitialization) {
       							actualizarTags(ui.tagLabel,0, null);
       						}
       					},
                       afterTagRemoved: function(event, ui) {
                    	   if(!ui.duringInitialization) {
      							actualizarTags(ui.tagLabel,1, null);
      						}
                       },
                       autocomplete: {
                               delay:300,
                               minLength: 0,
                               focus: function (event, ui) {
                                       arrowSelection = true;
                                       this.value = ui.item.value;
                                       event.preventDefault();
                                       return false;
                               },
                               source: function ( request, response) {
                                       $.getJSON("PT/retrieveTags", {
                                               term:request.term
                                       }, response);
                               },
                               search: function (event, ui) {
                                       if (arrowSelection) {
                                               arrowSelection = false;
                                               return false;
                                       }
                               },
                               messages: {
                                       noResults: '',
                                       results: function () {}
                               }
                       }
               });
}



function init() {
	$('li.post:first').addClass('active');
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
 
 function configurarClickPost() {
	 
	    $(document).on('click', 'li.post', {}, function(e) {

	    	$('#messages-modal').modal('show');
	    	
	    	var post_id = $(this).data("postId");
	    	var $comentarios = $("ul.comentarios[data-post-id='" + post_id + "']");
	    	
	    	if (post_id != $("li.post.active").data('postId'))  {
	    		
           $(".comentarios").hide();
           $("li.post.active").removeClass('active');
           $("#tags").find(".tagit-choice").remove();

           $(this).addClass('active');
           $comentarios.toggle();

           $.get('PT/getPostTags', {post_id : post_id}, function(data) {
        	   $.each(data,function(k,v) {
        		   $("#tags").tagit('createTag', v, null, true);
        	   });
           });
           
           //seleccionar el primer comentario no calificado 
           $comentarios.find("li.comentario[data-calificacion=0]:first").click();
           $('.bton-more-comments-before').show();
           $('.bton-more-comments-after').show();
           moverScrollAlComentarioACalificar();
           
   }})
	 
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
 function configurarBotonCalificar() {

	    $(document).on('click','.bton-qualify',{},function(e) {
            e.stopPropagation();
            var _this = this;
            var comentario_id = $(this).parents('li.comentario').data('comentarioId');
            var row = $(this).parents('li.comentario').find('.row');
            var calificacion = $(this).data('calificacion');

            $.post('PT/qualify', {
                    comentario_id : comentario_id,
                    calificacion : calificacion
            }, function(data) {
                    if (data) {
                    		var comentario = $(_this).parents('li.comentario');
                            $(comentario).attr("data-calificacion",calificacion);
                            $(row).removeClass('colorCalificacion-blue');
                            $(row).removeClass('colorCalificacion-green');
                            $(row).removeClass('colorCalificacion-red');
                            $(row).removeClass('colorCalificacion-white');
                            
                            var seleccionado = $(comentario).find('.circle-selected');
                            $(seleccionado).removeClass('circle-selected');
                            
                            if( calificacion == 1) {
                            	//color fondo row
                            	$(row).addClass('colorCalificacion-blue');
                            	//agrego estilo boton seleccionado
                            	$(_this).addClass('circle-selected');
                            	
                            }
                            else if (calificacion == 2) {
                            	//color fondo row
                            	$(row).addClass('colorCalificacion-green');
                            	//agrego estilo boton seleccionado
                            	$(_this).addClass('circle-selected');

                            }
                            else if (calificacion == 3) {
                            	//color fondo row
                            	$(row).addClass('colorCalificacion-red');
                            	//agrego estilo boton seleccionado
                            	$(_this).addClass('circle-selected');

                            }

                    } else  {
                            
                    }
                    (comentario).click();
                    moveToNextComment();
            });
            
            
    });
 }
 
 
 function configurarBotonMore() {
	 $(document).on('click','.bton-more', {}, function(e)  {
		 $.ajax({
			 type: "POST",
			 url: "PT/posts",
			 data: {created_time: $('li.post').filter(':last').data('postCreatedTime')},
		 }).done(function (data) {
			 $('.bton-more').before(data);
			 if (data == "" ) {
				 $('.bton-more').hide();
			 }
			 
			 $('.btn-tag').popover();
		 });		 
		 $.ajax({
			type: "POST",
			url: "PT/allCommentsUploaded",
			data: {created_time: $('li.post').filter(':last').data('postCreatedTime')},
		 }).done (function(data){
			 $('.bton-more-comments-after').before(data);
		 });
	 });
 }
 
 
 function configurarBotonMoreComments() {
	 $(document).on('click','.bton-more-comments-before', {}, function(e)  {
		 var post_id = $(document).find('li.post.active').data('postId');
		 var comentarios = $('ul.comentarios[data-post-id='+ post_id + ']');
		 var created_time = $(comentarios).find('li.comentario').filter(':first').data('createdTime');
		 
		 $.ajax({
			type: "POST",
			url: "PT/addComments",
			data: {created_time: created_time, post_id: post_id, type:"prepend" },
		 }).done (function(data){
			 $(comentarios).prepend(data);
			 if (data == "" ) {
				 $('.bton-more-comments-before').hide();
			 }
		 });
	 });
	 $(document).on('click','.bton-more-comments-after', {}, function(e)  {
		 var post_id = $(document).find('li.post.active').data('postId');
		 var comentarios = $('ul.comentarios[data-post-id='+ post_id + ']');
		 var created_time = $(comentarios).find('li.comentario').filter(':last').data('createdTime');
		 
		 $.ajax({
			type: "POST",
			url: "PT/addComments",
			data: {created_time: created_time, post_id: post_id , type: "append"},
		 }).done (function(data){
			 $(comentarios).append(data);
			 if (data == "") {
				 $('.bton-more-comments-after').hide();
			 }
			 else {
				 moveToNextComment();
			 }

			 
		 });
	 });		 

 }
 
 function configurarScrollComments() {
	 $(document).on('scroll','.col-scroll',{}, function (e) {
		 var _this = this;
		 var post_id = $(this).find('li.post.active').data('postId');
		 var created_time = $(this).find('li.comentario[data-post-id=' + post_id+ ']:first').data('createdTime');
		 
		 $.ajax({
			 type: "POST",
			 url: "PT/comments",
			 data: {created_time: created_time }
		 }).done (function(data) {
				 $('.comentarios-container').prepend(data);
		 });
	 });
 }
 function actualizarTags(tagName, remove, post_id) {
	 if (post_id == null) post_id = $("li.post.active").data('postId');
	 
	 $.post('PT/updateTags', {tag_name : tagName, post_id: post_id, remove: remove}, function (data) {
		 displayMessage("Tags guardados.");
	 });
 }
 
 function displayMessage( msg) {
	 $(".label-reponse").text(msg);
	 $(".label-response").show();
	 setTimeout(function() {
		 $(".label-response").hide('fade', {}, 500);
	 }, 2000);
	 
 }
 
 function moveToNextComment() {
	var nextComment = $('li.comentario.active').next();
	var post_id = $('li.post.active').data('postId');
	moverScrollAlComentarioACalificar();
	
	if ($(nextComment).length != 0) {
		$(nextComment).click();
	}
	else  {
		$('.bton-more-comments-after').click();
		$(nextComment).click();
	}
	
	//moveToNextPost();
	
 }
 
 function moveToNextPost() {
	//cargo en variable proximo post
	 var nextPost = $('li.post.active').next();
	 var post_id = $('li.post.active').data('postId');
	 
	 //si es el ultimo cargado hago click en el boton more
	 if ($(nextPost).is('.post:last')) {
		 $('bton-more').click();
	 }
	 
	 //clickeo el prox post
	 $(nextPost).click();
	 
	 //actualizo el scroll
	 $('col-left').animate({
		scrollTop: $('li.post.active').offset().top - $('.col-left').offset().top + $('.col-left').scrollTop 
	 })
 }
 
 function moverScrollAlComentarioACalificar() {
	 $('.col-scroll').animate({
			scrollTop: $('li.comentario.active').offset().top - $('.col-scroll').offset().top + $('.col-scroll').scrollTop()
		});
 }
  
 
 function configurarTagRapido() {
	 $('.btn-tag').popover();
	 $(document).on('click','.btn-tag', {} ,function(e){
			e.stopPropagation();
			
			var post_id = $(this).data('postId');
			var elemento = $(document).find('#tags-'+post_id+':first');
			
			$(elemento).tagit({
					minLength: 3,
					allowSpaces: true,
					singleFieldNode: $(".tags-input"),
					beforeTagAdded: function(event, ui) {
				        if(ui.tagLabel.length < 3) {
				        	return false;
				        }
				    },
   					afterTagAdded: function (event,ui) {
   						if(!ui.duringInitialization) {
   							actualizarTags(ui.tagLabel,0, post_id);
   						}
   					},
                   afterTagRemoved: function(event, ui) {
                	   if(!ui.duringInitialization) {
  							actualizarTags(ui.tagLabel,1, post_id);
  						}
                   },
					autocomplete: {
						delay: 300, 
						minLength: 3,
						focus: function(event, ui) {
							arrowSelection = true;
							this.value = ui.item.value;
							event.preventDefault();
							return false;
				        },
						source: function( request, response ) {
							$.getJSON( "PT/retrieveTags", {
								term: request.term
							}, response );
				      	},
				      	search: function( event, ui ) {
				      		if(arrowSelection) {
				      			arrowSelection = false;
				      			return false; 
				      		}
				      	},
				      	messages: {
					        noResults: '',
					        results: function() {}
					    }
					}
				});
			
			
			$.get('PT/getPostTags', {post_id: post_id}, function(data){
				$.each(data, function(k,v){
					$(elemento).tagit('createTag', v, null, true);
				});
			});

			
		});

 }
 
 function configurarLinks() {
	 $(document).on('click','.link-objeto', {} ,function(e){
		 e.stopPropagation();
	 });
	 
	 $(document).on('click','.fanpage', {} ,function(e){
		 e.stopPropagation();
	 });
	  
 }

 
 