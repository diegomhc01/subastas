var valselabierto=0;
var valincremento = 0;
var valsellotecat = 0;
var vidoperador = '';
var vmontooferta = new Number();
var entro = 0;
var precioiniciocambio = new Number();
var entroAPrecioInicial = 0;
var socket;
var valor;
var player;
var done = false;
var arrvideos = new Array();	
var conectado = false;
var res;
var envio = false;
var validusuario = 0;
jQuery(document).ready(function(){
	jQuery('#myElement').each(function(index){
		console.log(index);
		if(index==0){
			jQuery('p').remove();
		}
	});
	buscarvideos();
	buscarremate();
	precioiniciocambio = 0;	
	conectarcliente();
	jQuery(".fancybox").fancybox();

	jQuery('#txtusuario').focus();
	//BUSCAR OPERADOR PARA EL CLIENTE LOGUEADO
	jQuery('#desarrollo_sub').empty();
	jQuery.ajax({
			type:"POST",	    
		    url: "scripts/buscar_operador.php",
		    dataType:'json',
		     success: function(r){
				vidoperador = r.operador;			
		    }
	});
	
	var vidDS = 0;
	var vidchat = 0;
	var oTableLotesCatalogo = inicioTablaLotesCatalogo();

	ControlarMontoOferta();

	var entroAPrecioInicial = 0;
	//SELECCIONAR UN USUARIO POR PARTE DEL OPERADOR PARA ENVIAR UN MENSAJE POR CHAT
	jQuery('#usuarios').on('click', '.clsbtnchatusuario', function() {
		if(validusuario!=='undefined' || validusuario!=0){
			jQuery('#btnchatusuario'+validusuario).css({'background-color':'#0099CC','color':'white'});	
		}
    	validusuario = jQuery(this).attr('id').substring(14);    	
    	jQuery('#'+jQuery(this).attr('id')).css({'background-color':'#669966','color':'white'});
    	jQuery.ajax({
			type:"POST",
			data: {param: validusuario },
		    url: "scripts/usuario_chat_seleccionado.php",
		    dataType:'json',
		    success: function(r){
		    }	
		});
	});	
	//EVENTO DEL A TECLA ENTER CAPTURADO EN EL CUADRO DE TEXTO DE MENSAJE DE CHAT PARA ENVIAR UN MENSAJE
	jQuery('#txtmsg').keypress(function(event){
		if(event.which==13){
			jQuery('#btnmsg').click();
			jQuery('#txtmsg').val('');
			event.preventDefault();
		}
	});
	//TODOS
	//EVENTO DEL BOTON ENVIAR MENSAJE DE CHAT PARA ENVIAR UN MENSAJE
	jQuery('#btnmsg').on('click',function(){
		if(typeof validusuario==='undefined'){
			errorchat();			
		}else{
			if(jQuery('#txtmsg').val()!=''){
				envio = true;
				var vtexto = jQuery('#txtmsg').val();
				var msg = {'accion':1001,'texto':vtexto,'usrr':validusuario};
				jQuery.ajax({
					type:"POST",
				    data:msg,
				    url: "scripts/subasta.php",
				    dataType : 'json',
				    success : function(r){					
						socket.send(JSON.stringify(r));
						refrescarchat(r);
						jQuery('#txtmsg').val('');
				    }
				});	
			}
		}
	});	
	//OFERTAR EL VALOR QUE DESEE EL COMPRADOR MAS EL INCREMENTO
	jQuery('#btnmontos').on('click', function(){	
		vmontooferta = parseFloat(vmontooferta) + parseFloat(valincremento);
		jQuery('#txtmonto').val(vmontooferta.toFixed(2));
	});	
	//OFERTAR EL VALOR QUE DESEE EL COMPRADOR MENOS EL INCREMENTO
	jQuery('#btnmontob').on('click', function(){		
		var montouo = Number(jQuery('#txtuo').val());
		if(isNaN(montouo))
			montouo = 0;
		else
			montouo = parseFloat(montouo);		
		
		vmontooferta = parseFloat(vmontooferta) - parseFloat(valincremento);
		//jQuery('#txtuo').val()=='' &&
		if(montouo < vmontooferta &&  vmontooferta > 0){
			jQuery('#txtmonto').val(vmontooferta.toFixed(2));
		}else{			
			vmontooferta = parseFloat(vmontooferta) + parseFloat(valincremento);
		}
	});
	//CONTROLAR QUE EL INGRESO MANUAL DEL COMPRADOR SEA MAYOR AL MONTO DE LA ULTIMA OFERTA ACEPTADA
	jQuery('#txtmonto').on('blur', function(){
		var montouo = Number(jQuery('#txtuo').val());
		var vmontooferta = Number(jQuery('#txtmonto').val());
		if(montouo < vmontooferta){
			jQuery('#txtmonto').val(vmontooferta.toFixed(2));
		}else{
			alert('La oferta debe ser mayor que la oferta aceptada');
			if(parseFloat(montouo)<=0){
				vmontooferta = parseFloat(precioiniciocambio);
			}else{
				vmontooferta = parseFloat(montouo) + parseFloat(valincremento);
			}
			jQuery('#txtmonto').val(vmontooferta.toFixed(2));		
		}
	});
	//SOLICITAR CREDITO AL OPERADOR
	jQuery('#btnsolicitarcredito').on('click', function(){
		var msg = {accion: '2002'};
		jQuery.ajax({
				type:"POST",
			    data:msg,
			    url: "scripts/subasta.php",
			    dataType : 'json',
			    success : function(r){
					socket.send(JSON.stringify(r)); 
					jQuery('#btnsolicitarcredito').css('background-color', 'yellow');
			    }
			});	
	});
	jQuery('#btnvercredito').on('click', function(){
		jQuery.ajax({
			type:"POST",
		    url: "scripts/ver_credito.php",
		    dataType:'json',
		    success: function(r){
		    	if(r.success){
		    		jQuery('#infolote_cre').empty();
		    		jQuery('#infolote_cre').fadeIn();
					jQuery('#infolote_cre').append('<p  id="creditop">Su cr\u00E9dito actual es $ <strong>' + r.monto + '</strong></p>');
					jQuery('#infolote_cre').fadeOut(30000, function(){});
				}
		    }			
		});
	})
	//ENVIA UNA OFERTA AL REMATADOR
	jQuery('#btnofertar').on('click', function(){
		if(jQuery('#txtmonto').val()>0){
			if(navigator.appName!='Opera'){
				var colorfondo = rgb2hex(jQuery('#btnofertar').css('background-color'));
			}else{
				var colorfondo = jQuery('#btnofertar').css('background-color');
			}
			if(colorfondo!='#008000' && colorfondo!='#ffff00'){
				if(!isNaN(jQuery('#txtmonto').val())){				
					var msg = {'accion': 2001,'monto' : jQuery('#txtmonto').val(), 'idlote' : valselabierto};				
					jQuery.ajax({
						type:"POST",
					    data:msg,
					    url: "scripts/subasta.php",
					    dataType : 'json',
					    success : function(r){
							socket.send(JSON.stringify(r)); 							
					    	refrescaroferta(r);
					    }
					});	
				}
			}
		}else{
			alert('La oferta debe ser mayor a 0');
		}
	});
	//LISTADO DE LOS LOTES A SUBASTAR DEL REMATE ACTUAL QUE PERMITE 
	//VER EL DETALLE DE CADA LOTE
	jQuery('#dtlotecatalogo').on('click', '.clslotescat', function() {
		LimpiarCamposDetalleLote();
		
    	valsellotecat = jQuery(this).attr('id').substring(4);    	
    	jQuery.ajax({
			type:"POST",
			data: {param: valsellotecat },
		    url: "scripts/buscar_detalle_lote.php",
		    dataType:'json',
		    success: function(r){
		    	if(r.success){		    		
		    		var pdpromedio = 0;
		    		var pdmaximo = 0;
		    		var pdminimo = 0;
		    		var cvplazo = '';
		    		var vedad = '';
					if(r.pi!=null){
						pdpromedio = r.pi[0].promedio;
						pdmaximo = r.pi[0].maximo;
						pdminimo = r.pi[0].minimo;
					}
					if(r.cv!=null){					
						cvplazo = r.cv[0].plazo;
					}
					if(r.edad!='0'){
						vedad = r.edad;
					}
		    		jQuery("#tabs").tabs();
		    		jQuery('#cajahacienda').empty();
		    		jQuery('#cajahacienda').append('<div class="clsdetallelote"><p>N\u00B0 de Lote <strong>' + r.nrolote + '</strong></p></div>');
					jQuery('#cajahacienda').append('<div class="clsdetallelote"><p><strong>' + r.trazados + '</strong></p></div>');
					jQuery('#cajahacienda').append('<div class="clsdetallelote"><p>Cabezas <strong>' + r.cantcabezas + '</strong></p></div>');
					jQuery('#cajahacienda').append('<div class="clsdetallelote"><p><strong>' + r.marcaliquida + '</strong></p></div>');
					jQuery('#cajahacienda').append('<div class="clsdetallelote"><p>Categor\u00EDa <strong>' + r.categoria + '</strong></p></div>');
					jQuery('#cajahacienda').append('<div class="clsdetallelote"><p><strong>' + r.destetados + '</strong></p></div>');
					jQuery('#cajahacienda').append('<div class="clsdetallelote"><p>Raza/Tipo <strong>' + r.razatipo + '</strong></p></div>');
					jQuery('#cajahacienda').append('<div class="clsdetallelote"><p><strong>' + r.alimentacion + '</strong></p></div>');
					jQuery('#cajahacienda').append('<div class="clsdetallelote"><p>Edad <strong>' + vedad + '</strong></p></div>');
					jQuery('#cajahacienda').append('<div class="clsdetallelote"><p>Promedio <strong>' + pdpromedio + '</strong></p></div>');
					jQuery('#cajahacienda').append('<div class="clsdetallelote"><p>Entrega <strong>' + r.tipoentrega + '</strong></p></div>');					
					jQuery('#cajahacienda').append('<div class="clsdetallelote"><p>M\u00EDnimo <strong>' + pdminimo + '</strong></p></div>');
					jQuery('#cajahacienda').append('<div class="clsdetallelote"><p>Plazo <strong>' + cvplazo + '</strong></p></div>');
					jQuery('#cajahacienda').append('<div class="clsdetallelote"><p>M\u00E1ximo <strong>' + pdmaximo + '</strong></p></div>');
					jQuery('#cajahacienda').append('<div class="clsdetallelote" style="width:200px; height:169px; margin-top:-144px;"><p>Evaluador <p style="font-size:14px;"><strong>' + r.evaluador[0].apellido + ', ' + r.evaluador[0].nombre + '</strong></p></p><div style="width:180px;padding-left:30px;"><img src="images/eva' + r.evaluador[0].idevaluador + '.gif"></div></div>');
					jQuery('#cajahacienda').append('<div class="clsdetallelote" style="clear:both;width: 570px;margin-top:0px;height:88px;"><p>Observaciones <strong>' + r.observaciones + '</strong></p></div>');
					if(r.vientre!==[]){
						jQuery('#cajavientre').empty();
						jQuery('#cajavientre').append('<div class="clsdetallelote"><p>Pre\u00F1ados <strong>' + r.vientre.preniados + '</strong></p></div>');
						jQuery('#cajavientre').append('<div class="clsdetallelote"><p>Vac\u00EDos <strong>' + r.vientre.vacios + '</strong></p></div>');
						jQuery('#cajavientre').append('<div class="clsdetallelote"><p>Con Servicio <strong>' + r.vientre.conservicio + '</strong></p></div>');
						jQuery('#cajavientre').append('<div class="clsdetallelote"><p>Garant\u00EDa <strong>' + r.vientre.garantia + '</strong></p></div>');
						jQuery('#cajavientre').append('<div class="clsdetallelote"><p>IA <strong>' + r.vientre.ia + '</strong></p></div>');
						jQuery('#cajavientre').append('<div class="clsdetallelote"><p>Natural <strong>' + r.vientre.naturals + '</strong></p></div>');
						jQuery('#cajavientre').append('<div class="clsdetallelote"><p>Desde <strong>' + r.vientre.desde + '</strong></p></div>');
						jQuery('#cajavientre').append('<div class="clsdetallelote"><p>Hasta <strong>' + r.vientre.hasta + '</strong></p></div>');
						jQuery('#cajavientre').append('<div class="clsdetallelote"><p>Tipo Sangre Toro <strong>' + r.vientre.sangre + '</strong></p></div>');
						jQuery('#cajavientre').append('<div class="clsdetallelote"><p>Raza/Tipo Toro <strong>' + r.vientre.razatipo + '</strong></p></div>');
					}
					if(r.pi!=null){
						jQuery('#cajapi').empty();
						jQuery('#cajapi').append('<div class="clsdetallelote"><p>Forma <strong>' + r.pi[0].forma + '</strong></p></div>');
						jQuery('#cajapi').append('<div class="clsdetallelote"><p>Hora <strong>' + r.pi[0].hora + '</strong></p></div>');
						jQuery('#cajapi').append('<div class="clsdetallelote"><p>Desbaste <strong>' + r.pi[0].desbaste + '</strong></p></div>');
						jQuery('#cajapi').append('<div class="clsdetallelote"><p>Promedio <strong>' + r.pi[0].promedio + '</strong></p></div>');
						jQuery('#cajapi').append('<div class="clsdetallelote"><p>M\u00E1ximo <strong>' + r.pi[0].maximo + '</strong></p></div>');
						jQuery('#cajapi').append('<div class="clsdetallelote"><p>M\u00EDnimo <strong>' + r.pi[0].minimo + '</strong></p></div>');
					}
					if(r.pd!=null){
						jQuery('#cajapd').empty();
						jQuery('#cajapd').append('<div class="clsdetallelote"><p>Ubicaci\u00F3n <strong>' + r.pd[0].ubicacion + '</strong></p></div>');
						jQuery('#cajapd').append('<div class="clsdetallelote"><p>Arreo <strong>' + r.pd[0].arreo + '</strong></p></div>');
						jQuery('#cajapd').append('<div class="clsdetallelote"><p>Cami\u00F3n <strong>' + r.pd[0].camion + '</strong></p></div>');
						jQuery('#cajapd').append('<div class="clsdetallelote"><p>Balanza <strong>' + r.pd[0].balanza + '</strong></p></div>');
						jQuery('#cajapd').append('<div class="clsdetallelote"><p>Lugar Cami\u00F3n <strong>' + r.pd[0].lugarcamion + '</strong></p></div>');
						jQuery('#cajapd').append('<div class="clsdetallelote"><p>Desbaste <strong>' + r.pd[0].desbaste + '</strong></p></div>');
						jQuery('#cajapd').append('<div class="clsdetallelote"><p>Promedio <strong>' + r.pd[0].promedio + '</strong></p></div>');
						jQuery('#cajapd').append('<div class="clsdetallelote"><p>M\u00E1ximo <strong>' + r.pd[0].maximo + '</strong></p></div>');
						jQuery('#cajapd').append('<div class="clsdetallelote"><p>M\u00EDnimo <strong>' + r.pd[0].minimo + '</strong></p></div>');
						jQuery('#cajapd').append('<div class="clsdetallelote"><p>Observaciones <strong>' + r.pd[0].observaciones + '</strong></p></div>');
					}
					if(r.evaluacion!=null){						
						jQuery('#cajaevaluacion').empty();
						jQuery('#cajaevaluacion').append('<div class="clsdetallelote"><p>Calidad <strong>' + r.evaluacion[0].calidad + '</strong></p></div>');
						jQuery('#cajaevaluacion').append('<div class="clsdetallelote"><p>Estado <strong>' + r.evaluacion[0].estadoeva + '</strong></p></div>');
						jQuery('#cajaevaluacion').append('<div class="clsdetallelote"><p>Sanidad <strong>' + r.evaluacion[0].sanidad + '</strong></p></div>');
						jQuery('#cajaevaluacion').append('<div class="clsdetallelote"><p>Uniformidad <strong>' + r.evaluacion[0].uniformidad + '</strong></p></div>');
					}
					if(r.cv!=null){
						jQuery('#cajacv').empty();
						jQuery('#cajacv').append('<div class="clsdetallelote"><p>Plazo <strong>' + r.cv[0].plazo + '</strong></p></div>');
						//jQuery('#cajacv').append('<div class="clsdetallelote"><p>Precio Inicial <strong>' + r.cv[0].precioinicial + '</strong></p></div>');
						jQuery('#cajacv').append('<div class="clsdetallelote"><p>Tipo de Precio <strong>' + r.cv[0].tipoprecio + '</strong></p></div>');
					}
					if(r.localidad!=null && r.provincia!=null){
						jQuery('#cajalocalidad').empty();
						jQuery('#cajalocalidad').append('<div class="clsdetallelote"><p>Localidad <strong>' + r.localidad[0].nombre + '</strong></p></div>');
						jQuery('#cajalocalidad').append('<div class="clsdetallelote"><p>Provincia <strong>' + r.provincia[0].nombre + '</strong></p></div>');						
					}
					r.imagen.length > 4 ? cantidad = 4 : cantidad = r.imagen.length;
					var j=0;
					for(i=0;i<cantidad;i++){
						jQuery('#foto' + i).attr('src', r.imagen[i].imagen);
						jQuery('#afoto' + i).attr('href', r.imagen[i].imagen);
						j=i;
					}

					jQuery('#videosdetallelote').empty();
					jQuery('#videosdetallelote').append('<div id="player"></div>');
					if(r.video[0].idvideo!=0){
						var cont = 0;

						for(i=0;i<arrvideos.videos.length;i++){
							if(arrvideos.videos[i].idvideo==r.video[0][0].idvideo){								
								cont = i;
								break;
							}							
						}
						var player = {
							playVideo: function(container, videoId) {
							    if (typeof(YT) == 'undefined' || typeof(YT.Player) == 'undefined') {
							        window.onYouTubePlayerAPIReady = function() {
							            player.loadPlayer(container, videoId);
							        };
							        jQuery.getScript('//www.youtube.com/player_api');
							    } else {
							        player.loadPlayer(container, videoId);							        
							    }
							},
							loadPlayer: function(container, videoId) {
							    window.myPlayer = new YT.Player(container, {
							        playerVars: {
							        	autohide: 1,
							        	controls: 0,
							        	fs: 0,
							        	loop: 1,
							            modestbranding: 1,
							            rel: 0,
							            showinfo: 0,
							            autoplay: 0
							        },
							        height: 260,
							        width: 380,
							        videoId: videoId,
							        events: {
							            'onStateChange': function (event) {
									        if (event.data == YT.PlayerState.PLAYING && !done) {									          
									          done = true;
									        }
									      }
							        }
							    });
							} 
						};						
						done = false;
						player.playVideo('player', arrvideos.videos[cont].video.toString());
					}
				}
		    }				    
		});
    	jQuery("#detallelote").css("display", "block");
			jQuery("#detallelote").dialog(
			{ 
				closeOnEscape: true,
				resizable: false,
				modal: true, 
				title: "Detalle del Lote", 
				height: 475, 
				width: 900,
				buttons:{										
					"Cerrar": function() {
						jQuery(this).dialog( "close" );
					}
			}
		});		
	});
	//ABRE UNA VENTANA EN FORMA MODAL QUE MUESTRA LAS IMAGENES MAS GRANDES
	jQuery('#detallelote input[type="image"]').on('click', function(){
		jQuery('#idmostrarfoto').attr('src',jQuery(this).attr('src'));
		jQuery("#mostrarfoto").css("display", "block");

			jQuery("#mostrarfoto").dialog(
			{ 
				closeOnEscape: true,
				resizable: false,
				modal: true, 				
				height: 727, 
				width: 485,
				buttons:{										
					"Cerrar": function() {
						jQuery(this).dialog( "close" );
					}
			}
		});		
	});
	jQuery('#detallelote').on('dialogclose', function(event, ui){
		if(typeof jQuery('#player')[0].contentWindow!=='undefined'){
			jQuery('#player')[0].contentWindow.postMessage('{"event":"command","func":"' + 'stopVideo' + '","args":""}', '*');
		}
	});
	
});

function stopVideo() {
	player.stopVideo();
};
function onPlayerReady(event) {
	event.target.playVideo();
};
function buscarvideos(){
	jQuery.ajax({
		type:"POST",	    
	    url: "scripts/buscar_videos_hacienda.php",
	    dataType:'json',
	    success: function(r){
		    arrvideos = r;		    
		}		
	});	
};
function BuscarDatosLote(r){
	if(r.success){	
		jQuery('#infolote_cli').empty();
		jQuery('#infolote_cli').append('<p>Nro de Lote <strong>' + r.nrolote + '</strong></p>');		    		
		jQuery('#infolote_cli').append('<p id="pincremento">Incremento <strong>' + r.incremento + '</strong></p>');
		jQuery('#infolote_cli').append('<p>Cantidad de Cabezas <strong>' + r.cantcabezas + '</strong></p>');
		jQuery('#infolote_cli').append('<p>Descripci\u00F3n <strong>' + r.categoria + '</strong></p>');
		jQuery('#infolote_cli').append('<p>Ubicaci\u00F3n <strong>' + r.localidad + ' - ' + r.provincia + '</strong></p>');
		r.trazados == 'SI' ? jQuery('#infolote_cli').append('<p style="float:left; margin-right:10px;"><strong>Trazados</strong></p>') : '';
		r.marcaliquida == 'SI' ? jQuery('#infolote_cli').append('<p><strong>Con Marca L\u00EDquida</strong></p>') : '';
		jQuery('#infolote_cli').append('<p>Tipo de Entrega <strong>' + r.tipoentrega + '</strong></p>');
		jQuery('#infolote_cli').append('<p>Plazo <strong>' + r.plazo + '</strong></p>');
		jQuery('#infolote_cli').append('<p id="ptipoprecio">Tipo de Precio <strong>' + r.tipoprecio + '</strong></p>');
		jQuery('#infolote_cli').append('<p>Promedio <strong>' + r.promedio + ' Kg</strong></p>');
	}
	jQuery('#infolote_cli #pincremento').html('Incremento <strong>' + r.incremento + '</strong>');
	cargarvideoloteabierto(r);
};
function cargarvideoloteabierto(r){	
	jQuery('#videolote').empty();
	jQuery('#videolote').append('<div id="playerlote"></div>');
	var cont = 0;

	for(i=0;i<arrvideos.videos.length;i++){
		if(arrvideos.videos[i].idvideo==r.video[0]){								
			cont = i;
			break;
		}							
	}
	var player = {
		playVideo: function(container, videoId) {
		    if (typeof(YT) == 'undefined' || typeof(YT.Player) == 'undefined') {
		        window.onYouTubePlayerAPIReady = function() {
		            player.loadPlayer(container, videoId);
		        };
		        jQuery.getScript('//www.youtube.com/player_api');
		    } else {
		        player.loadPlayer(container, videoId);							        
		    }
		},
		loadPlayer: function(container, videoId) {
		    window.myPlayer = new YT.Player(container, {
		        playerVars: {
		        	autohide: 1,
		        	controls: 0,
		        	fs: 0,
		        	loop: 1,
		            modestbranding: 1,
		            rel: 0,
		            showinfo: 0,
		            autoplay: 1
		            //,
		        	//playlist:videoId
		        },
		        height: 341,
		        width: 614,
		        videoId: videoId,
		        events: {

		            'onStateChange': function (event) {		            
				        if (event.data == YT.PlayerState.PLAYING && !done) {									          
				          //done = true;						  
				        }
				      },
				    'onReady': function(event){
				    	event.target.setVolume(0);
				    }
		        }
		    });
		} 
	};						
	done = false;
	player.playVideo('playerlote', arrvideos.videos[cont].video.toString());
}
function inicioTablaLotesCatalogo(){ 
	oTableLotesCatalogo = jQuery('#dtlotecatalogo').DataTable({
        "destroy":true,
        "responsive": true,
        "ajax": "scripts/catalogo_cli.php",
        "deferRender": true,        
        "scrollY": 286,
        "paging": false,
        "scrollCollapse": true,
        "dom": '<"top">rt<"bottom"><"clear">',
        "ordering": false,
        "columns": [
          {data:"lote", title: "Detalle de lotes"},
          {data:"boton", title: ""}
        ],
        "language":{
                "sProcessing":     "Procesando...",
                "sLengthMenu":     "Mostrar _MENU_ registros",
                "sZeroRecords":    "No se encontraron resultados",
                "sEmptyTable":     "Ning\u00FAn dato disponible en esta tabla",
                "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
                "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
                "sInfoPostFix":    "",
                "sSearch":         "Buscar:",
                "sUrl":            "",
                "sInfoThousands":  ",",
                "sLoadingRecords": "Cargando...",
                "oPaginate": {
                    "sFirst":    "Primero",
                    "sLast":     "\u00DAltimo",
                    "sNext":     "Siguiente",
                    "sPrevious": "Anterior"
                },
                "oAria": {
                    "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
                    "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                }
            }
    	});  
    return oTableLotesCatalogo;
};
function ControlarMontoOferta(){
	if(jQuery('#txtuo').val()!=''){
		jQuery('#txtmonto').val(jQuery('#txtuo').val());
	};
};
function LimpiarCamposDetalleLote(){
	jQuery('#txtnrolote').val('');
	jQuery('#txtcabezas').val('');
	jQuery('#txttrazado').val('');
	jQuery('#txtcategoria').val('');
	jQuery('#txttipotntrega').val('');
	jQuery('#txtmarcaliquida').val('');
	jQuery('#txtedad').val('');
	jQuery('#txtrazatipo').val('');
	jQuery('#txtpelaje').val('');
	jQuery('#txtdestetados').val('');
	jQuery('#txtalimentacion').val('');
	jQuery('#txtobservaciones').val('');	
	for(i=0;i<4;i++){
		jQuery('#foto' + i).attr('src', 'fotos/sinimagen.jpg');
	}
};
function rgb2hex(rgb) {	
	try{
	    rgb = rgb.match(/^rgb\((\d+),\s*(\d+),\s*(\d+)\)$/);
	    function hex(x) {
	        return ("0" + parseInt(x).toString(16)).slice(-2);
	    }
	    return "#" + hex(rgb[1]) + hex(rgb[2]) + hex(rgb[3]);
	}catch(e){
		console.log(e);
	}
};
function errorchat(){
	jQuery('#msgerror').empty();			
	jQuery('#msgerror').css('display','block');
	jQuery('#msgerror').append('<p>Debe seleccionar un operador</p>')
	jQuery('#msgerror').dialog({
		closeOnEscape: true,
	    resizable: false,
	    modal: true, 
	    title: "Error Chat", 
	    height: 150, 
	    width: 250,
	    buttons:{
	    	"Cerrar":function(){
	    		jQuery(this).dialog('close');
	    	}
	    }
	});
};
function buscarremate(){
	jQuery.ajax({
		type:"POST",	    
	    url: "scripts/buscar_remate_abierto.php",
	    dataType:'json',
	    success: function(r){
	    	console.log(r);
		}		
	});		
};
function refrescarprecioinicial(r){		
	if(r.precioinicio!=0){ // && r.precioinicio > vmontooferta){
		var precio = Number(r.precioinicio);
    	if(precio > 0){
	    	if(precio != precioiniciocambio){
	    		jQuery('#txtmonto').val(parseFloat(precio).toFixed(2));
	    		entroAPrecioInicial = 1;
	    		vmontooferta = precio;
	    		precioiniciocambio = precio;
	    		if(r.incremento>0)
	    			valincremento = Number(r.incremento);
	    	}
	    }else{
	    	//jQuery('#txtmonto').val('');
	    }
	}
};
function refrescarincremento(r){
	if(r.incremento>0){
		var vinc = parseFloat(r.incremento);
		jQuery('#infolote_cli #pincremento').html('Incremento <strong>' + vinc.toFixed(2) + '</strong>');
		valincremento = r.incremento;
	}
};
function refrescartipoprecio(r){
	if(r.success){		
		var vinc = parseFloat(r.inc3);
		jQuery('#infolote_cli #ptipoprecio').html('<p id="ptipoprecio">Tipo de Precio <strong>' + r.descripcion + '</strong></p>');
		jQuery('#infolote_cli #pincremento').html('Incremento <strong>' + vinc.toFixed(2) + '</strong>');
		valincremento = r.inc3;		
	}
};
function refrescarchat(r){
	if(r.success){		
 		vidchat = r.idchat;
 		jQuery('#charla').append('<span class="usuario badge" style="' + r.color + ';">' + r.usre + '<img src="images/enviar.png" height:"16" width="16">' + r.apeynomr + '</span><br>' + r.hora + ': ' + r.mensaje + '<br>');
		jQuery('#charla').scrollTop(jQuery('#charla').prop("scrollHeight"));
		if(!envio){
			jQuery('#not_audio')[0].play();
		}
		envio = false;
	}
};
function refrescarchatinicio(r){
	if(r.aaData[0].success){		
		jQuery.each(r.aaData, function(k,v){
	 		jQuery('#charla').append('<span class="usuario badge" style="' + v.color + ';">' + v.usre + '<img src="images/enviar.png" height:"16" width="16">' + v.usrr + '</span><br>' + v.hora + ': ' + v.mensaje + '<br>');
			jQuery('#charla').scrollTop(jQuery('#charla').prop("scrollHeight"));		
	    });
	}
};
function refrescarusuariosconectados(r){
	if(!r[0].success){
		jQuery('#usuarios').empty(); 
	}else{
		var total = r[0].cantidad;
		var prevState1 = '';
	    jQuery('#usuarios').html('');
		for(i=0;i<total;i++){
			prevState1 = jQuery('#usuarios').html();
			vidUC = r[i].iduconectados;
			jQuery('#usuarios').html(prevState1 + r[i].usuarios);
			jQuery('#usuarios').scrollTop(jQuery('#usuarios').prop("scrollHeight"));							
		}
	}
};
function refrescaroperador(r){
	var id = r.usuario;
	jQuery('#usuarios li').each(function(idx, el) {
		if(jQuery(el).attr('id')==id){
	   		jQuery('#btnchatusuario'+ id).attr('style',r.estilo);
	   		jQuery('#btnchatusuario'+ id).attr('value',r.valor);
	   		jQuery('#btnchatusuario'+ id).removeAttr('disabled');
	   	} 
	});
};
function desconectarusuario(r){
	console.log(r);
	var id = r.usuario;
	if(r.success){
		jQuery('#usuarios li').each(function(idx, el) {
			if(jQuery(el).attr('id')==r.usuario){
		   		jQuery('#btnchatusuario'+ id).attr('style',r.estilo);
		   		jQuery('#btnchatusuario'+ id).attr('value',r.valor);
		   		jQuery('#btnchatusuario'+ id).attr('disabled',r.habilitado);
		   	} 
		});
	}
};
function refrescarloteabierto(r){
	valselabierto = r.idlote;
 	if(r.success){
 		if(r.idlote>0){			
     		BuscarDatosLote(r);
     		if(jQuery('#txtmonto').attr('disabled')=='disabled'){
     			jQuery('#txtmonto').removeAttr('disabled');
 				jQuery('#btnmontos').removeAttr('disabled');
 				jQuery('#btnmontob').removeAttr('disabled');
 				jQuery('#txtmonto').val(parseFloat(r.precioinicio).toFixed(2));
 				vmontooferta = parseFloat(r.precioinicio);
 				valincremento = r.incremento;
 				if(r.idoferta>0){
 					refrescarmejoroferta(r);
 				}
     		}
 		}			     		
 	}else{
 		jQuery('#txtmonto').attr('disabled','disabled');
 		jQuery('#txtmonto').val('');
 		jQuery('#btnmontos').attr('disabled','disabled');
 		jQuery('#btnmontob').attr('disabled','disabled');
 		jQuery('#txtuo').val('');
 		jQuery('#txtmonto').val('');			     		
 		jQuery('#infolote_cli').empty();
 		entro = 0;
 	}
};
function refrescarestadocredito(r){
	jQuery('#desarrollo_sub').append('<p style="padding-bottom:0;margin-bottom:0;"><span class="usuario">' + r.hora + '</span>' + ': <span style="color:blue;font-weight: bold;">' + r.mensaje + '</span></p>');
	jQuery('#desarrollo_sub').scrollTop(jQuery('#desarrollo_sub').prop("scrollHeight"));	
};
function refrescaraumentocredito(r){
	jQuery('#desarrollo_sub').append('<p style="padding-bottom:0;margin-bottom:0;"><span class="usuario">' + r.hora + '</span>' + ': <span style="color:blue;font-weight: bold;">' + r.detalle + '</span></p>');
	jQuery('#desarrollo_sub').scrollTop(jQuery('#desarrollo_sub').prop("scrollHeight"));
	jQuery('#btnsolicitarcredito').css('background-color', '#D2D2D2');	 
};
function refrescaroferta(r){
	if(valselabierto > 0){
	 	if(r.estado==0){
				jQuery('#btnofertar').css('background-color','#ffff00');
				estilo = "color:red;font-weight: bold;";
				if(r.detalle!='')
					mensaje = r.detalle;
				jQuery('#desarrollo_sub').append('<span class="usuario">' + r.hora + '</span>' + ': <span style="' + estilo + '">' + mensaje + "</span><br>");
				jQuery('#desarrollo_sub').scrollTop(jQuery('#desarrollo_sub').prop("scrollHeight"));
	 	}	     	
		if(r.estado==3){ //3=oferta aceptada
			jQuery('#btnofertar').css('background-color','#008000');
			vmontooferta = parseFloat(r.monto) + parseFloat(valincremento);
			jQuery('#txtmonto').val(parseFloat(vmontooferta).toFixed(2));
			jQuery('#txtuo').val(parseFloat(r.monto).toFixed(2));
		}
		if(r.estado==1){  //1=rechazado : 		
			jQuery('#btnofertar').css('background-color','#808080');
			estilo = "color:black;font-weight: bold;";
		}
		if(r.estado==2){//2=oferta aceptada pero ganó otra oferta	
			jQuery('#btnofertar').css('background-color','#808080');
			estilo = "color:black;font-weight: bold;";
			vmontooferta = parseFloat(r.monto) + parseFloat(valincremento);
			jQuery('#txtmonto').val(parseFloat(vmontooferta).toFixed(2));
			jQuery('#txtuo').val(parseFloat(r.monto).toFixed(2));			
		}
		if(r.estado==4){
			vmontooferta = parseFloat(r.monto) + parseFloat(valincremento);
			jQuery('#txtmonto').val(parseFloat(vmontooferta).toFixed(2));
			jQuery('#txtuo').val(parseFloat(r.monto).toFixed(2));			
		}
		if(r.estado==5){
			jQuery('#btnofertar').css('background-color','#808080');
			vmontooferta = parseFloat(r.monto) + parseFloat(valincremento);
			jQuery('#txtmonto').val(parseFloat(vmontooferta).toFixed(2));
			jQuery('#txtuo').val(parseFloat(r.monto).toFixed(2));			
		}
	}else{
		jQuery('#btnofertar').css('background-color','gray');
	}
};
function refrescardesarrolloremate(r){
	console.log(r);
	if(r.success){
		var estilo = '';
		var mensaje = '';	
		if(r.detalle!='')
			mensaje = r.detalle;

		if(mensaje!=''){
			if(mensaje=='SU OFERTA FUE SUPERADA'){
				estilo = "color:red;font-weight: bold;";
				jQuery('#btnofertar').css('background-color','#808080');
				estilo = "color:black;font-weight: bold;";				
			}
			if(mensaje=='SU OFERTA FUE ENVIADA AL REMATADOR'){
				estilo = "color:black;font-weight: bold;";
			}
			if(mensaje=='SU OFERTA ES LA MEJOR'){
				estilo = "color:green;font-weight: bold;";
				jQuery('#btnofertar').css('background-color','#008000');
			}							
			if(mensaje.substring(0,5)=='SE AB'){
				estilo = "color:green;font-weight: bold;";
			}
			if(mensaje=='EL REMATADOR ESTA POR BAJAR EL MARTILLO'){
				estilo = "color:red;font-weight: bold;";
			}
			if(mensaje.substring(0,6)==='LOTE N'){
				estilo = "color:green;font-weight: bold;";
			}
			if(mensaje.substring(0,5)==='SE CE'){
				estilo = "color:green;font-weight: bold;";
			}		
			if(mensaje=='LOTE SIN VENDER'){
				estilo = "color:black;font-weight: bold;";
			}
			if(mensaje.substring(0,5)==='SE RE'){
				estilo = "color:red;font-weight: bold;";
			}
			if(mensaje.substring(0,26)=='SU CREDITO FUE AUMENTADO A'){
				estilo = "color:green;font-weight: bold;";	
			}
			if(mensaje=='MEJOR OFERTA EN PISTA'){
				estilo = "color:red;font-weight: bold;";
			}			
			jQuery('#desarrollo_sub').append('<span class="usuario">' + r.hora + '</span>' + ': <span style="' + estilo + '">' + mensaje + "</span><br>");
			jQuery('#desarrollo_sub').scrollTop(jQuery('#desarrollo_sub').prop("scrollHeight"));
		}
	}	
};
function refrescardesarrolloremateinicio(r){	
	//if(r.aaData[0].success){
		jQuery('#desarrollo_sub').empty()
		jQuery.each(r.aaData, function(k,v){
			if(typeof v.idDS!=='undefined'){
				vidDS = v.idDS;
				var estilo = '';
				var mensaje = '';
				if(v.detalle!='')
					mensaje = v.detalle;				
				if(mensaje!=''){
					if(mensaje=='SU OFERTA FUE SUPERADA'){
						estilo = "color:red;font-weight: bold;";
					}
					if(mensaje=='SU OFERTA FUE ENVIADA AL REMATADOR'){
						estilo = "color:black;font-weight: bold;";
					}
					if(mensaje=='SU OFERTA ES LA MEJOR'){
						estilo = "color:green;font-weight: bold;";
					}							
					if(mensaje.substring(0,5)=='SE AB'){
						estilo = "color:green;font-weight: bold;";
					}
					if(mensaje=='EL REMATADOR ESTA POR BAJAR EL MARTILLO'){
						estilo = "color:red;font-weight: bold;";
					}
					if(mensaje.substring(0,6)==='LOTE N'){
						estilo = "color:green;font-weight: bold;";
					}
					if(mensaje=='LOTE SIN VENDER'){
						estilo = "color:black;font-weight: bold;";
					}
					if(mensaje.substring(0,5)==='SE RE'){
						estilo = "color:red;font-weight: bold;";
					}
					jQuery('#desarrollo_sub').append('<span class="usuario">' + v.hora + '</span>' + ': <span style="' + estilo + '">' + mensaje + "</span><br>");
					jQuery('#desarrollo_sub').scrollTop(jQuery('#desarrollo_sub').prop("scrollHeight"));
				}
			}
		});
	//}
};
function refrescarmejoroferta(r){	
	if(r.success){
		jQuery('#infolote_cli #pincremento').html('Incremento <strong>' + r.incremento + '</strong>');
		if(r.monto > 0){
	    	var vmonto = Number(r.monto);
	    	valincremento = parseFloat(r.incremento);
	    	var vmontoincremento = parseFloat(vmonto) + parseFloat(valincremento);
	    	jQuery('#txtuo').val(parseFloat(vmonto.toFixed(2)));
	    	if(vmontoincremento > vmontooferta || !isNaN(vmontooferta)) {
	    		vmontooferta = vmontoincremento;
				jQuery('#txtmonto').val(parseFloat(vmontooferta.toFixed(2)));
	    	}
	    }else{
	    	var precio = Number(r.precioinicial);
	    	if(precio > 0){
		    	if(entroAPrecioInicial==0 || precio != precioiniciocambio){					
		    		jQuery('#txtmonto').val(parseFloat(precio.toFixed(2)));
		    		entroAPrecioInicial = 1;
		    		vmontooferta = precio;
		    		precioiniciocambio = precio;
		    		valincremento = Number(r.incremento);
		    	}
		    }else{
		    	jQuery('#txtmonto').val('');
		    }
	    }
	}
};
function refrescaranularoferta(r){	
	console.log(r);
	if(r.success){
		if(r.idoferta>0){
			var vmonto = Number(r.monto);
			vmontooferta = parseFloat(vmonto) + parseFloat(valincremento); 
			jQuery('#txtuo').val(parseFloat(vmonto).toFixed(2));
			jQuery('#txtmonto').val(parseFloat(vmontooferta).toFixed(2));
		}else{
			vmontooferta = 0;
			var vmonto = Number(r.monto);
			jQuery('#txtuo').val('');
			jQuery('#txtmonto').val(parseFloat(vmonto));			
		}
		refrescardesarrolloremate(r);
	}
};
function refrescarpasarlote(r){
	if(r.success){
		jQuery('#btnofertar').css('background-color','#808080');
		jQuery('#infolote_cli').empty();
		jQuery('#txtuo').val('');
		jQuery('#txtmonto').val('');
		jQuery('#txtmonto').attr('disabled','disabled');
   		entroAPrecioInicial = 0;
	   	precioiniciocambio = 0;
	   	jQuery('#videolote').empty();
		jQuery('#videolote').append('<div id="playerlote"></div>');
	}
};
function refrescarcerrarlote(r){
	if(r.success){
		valselabierto = 0;
		valincremento = 0;		
		vmontooferta = 0;
		jQuery('#infolote_cli').empty();
		jQuery('#txtuo').val('');
		jQuery('#txtmonto').val('');
		jQuery('#txtmonto').attr('disabled','disabled');
		jQuery('#btnofertar').css('background-color','#808080');
	   	jQuery('#videolote').empty();
		jQuery('#videolote').append('<div id="playerlote"></div>');		
	}
};
function refrescarofertapropiainicio(r){
	if(r.success){
		if(r.estado=='0'){
			jQuery('#btnofertar').css('background-color','#ffff00');
		}
		if(r.estado=='2' || r.estado=='1'){
			jQuery('#btnofertar').css('background-color','#808080');
		}
		if(r.estado=='3'){
			jQuery('#btnofertar').css('background-color','#008000');
		}
		jQuery('#txtmonto').val(r.monto);
	}else{
		jQuery('#btnofertar').css('background-color','');
	}
};
function conectarcliente(){		
	if(!conectado){
		iniciar();
		init();
	}
};
function RefreshTable(tableId, urlData){
  //Retrieve the new data with jQuery.getJSON. You could use it ajax too
  jQuery.getJSON(urlData, null, function( json )
  {
    table = jQuery(tableId).dataTable();
    oSettings = table.fnSettings();    
    table.fnClearTable(this);

    for (var i=0; i<json.data.length; i++)
    {
      table.oApi._fnAddData(oSettings, json.data[i]);
    }

    oSettings.aiDisplay = oSettings.aiDisplayMaster.slice();    
    table.fnDraw();    
  });
};
function refrescarremate(r){
	if(r.mensaje='OK'){
		jQuery.ajax({
			type:"POST",	    
		    url: "scripts/buscar_remate_abierto.php",
		    dataType:'json',
		    success: function(r){	    	
				jQuery('#principal').submit();		
			}		
		});	
	}
};
function iniciar(){
	jQuery.ajax({
		type:"POST",
	    url: "scripts/subasta.php",
	    dataType : 'json',
	    success : function(r){
	    	res = r;
	    	refrescarchatinicio(r['chatinicio']);
	    	refrescarusuariosconectados(r['operadoresconectados']);
	    	refrescardesarrolloremateinicio(r['desarrolloremate']);
		    refrescarprecioinicial(r['lote']);
	    	refrescarloteabierto(r['lote']);
		    refrescarofertapropiainicio(r['ofertapropia']);
		    refrescarmejoroferta(r['mejoroferta']);
	    }
	});
}
function init() {

	//var host = "ws://127.0.0.1:9000/echobot";
	//var host = "ws://70.38.29.197:9000/echobot";
	var host = "ws://204.197.252.106:9000/echobot";
	try {
		socket = new WebSocket(host);		
		socket.onopen    = function(msg) {
			conectado = true;
			jQuery.ajax({
				type:"POST",
			    url: "scripts/macrito.php",
			    dataType:'json',
			    success: function(r){
			    	valor = JSON.stringify(r.s);			    	
					socket.send(valor);					
				}			
			});
		};
		socket.onmessage = function(msg) {			
			var obj;
			if(msg.data.length>0)
				obj = jQuery.parseJSON(msg.data);
						
			if(typeof obj === 'undefined'){
				accion = -1;
				udesc = 'undefined';
			}else{
				if(obj === null){
					accion = -1;
				}else{
					if(typeof obj[0] === 'undefined'){
						accion = obj.accion;
						udesc = obj.usdesc;
					}else{					
						accion = obj[0].accion;
						udesc = obj[0].usdesc;
					}
				}

			}
			if(typeof accion==='undefined')
				accion = 0;

			console.log('accion ' + accion);
			console.log('user ' + udesc);
			switch(accion) {
				case 0:
					//refrescarchatinicio(obj);
					break;
			    case 1000:
			    	//refrescarprecioinicial(obj);
			        break;
			    case 1100:			    	
			    	refrescarchat(obj);
			        break;
			    case 1110:			    	
			    	refrescarchat(obj);
			        break;			        
			    case 1200:
					//refrescarusuariosconectados(obj);
			        break;
			    case 1202:
					//MOSTRAR USUARIOS CONECTADOS
					refrescarusuariosconectados(obj);
			        break;			        
			    case 1203:					
					//refrescardesarrolloremateinicio(obj);
			        break;
			    case 1204:
			    	refrescarloteabierto(obj);
			    	refrescarprecioinicial(obj);
			    	refrescardesarrolloremate(obj);
			        break;
			    case 1205:			    	
			    	//refrescarofertapropiainicio(obj);
			        break;			        
			    case 1206:
			    	//refrescarmejoroferta(obj);
			        break;
				case 1210:
					refrescaroperador(obj);
					break;			        
			    case 2002:
			    	refrescarestadocredito(obj);
			        break;
			    case 2001:
			    	refrescaroferta(obj);
			        break;
			    case 4000:
			    	refrescaraumentocredito(obj);			    
			        break;
			    case 4001:
			    	refrescaroferta(obj);
			    	refrescardesarrolloremate(obj);
			        break;		   			
			    case 5003:
			    	refrescarloteabierto(obj);
			    	refrescardesarrolloremate(obj);
			        break;		   			
			    case 5005:
			    	refrescarpasarlote(obj);
			    	refrescardesarrolloremate(obj);
			        break;		   			
			    case 5007:
			    	refrescardesarrolloremate(obj);
			        break;
			    case 5008:
			    	refrescarcerrarlote(obj);
			    	refrescardesarrolloremate(obj);
			        break;
			    case 5009:			    	
			    	refrescaroferta(obj);
			    	refrescardesarrolloremate(obj);
			        break;
			    case 5010:
			    	refrescaroferta(obj);
			    	refrescardesarrolloremate(obj);
			        break;
			    case 5011:
			    	refrescarprecioinicial(obj);
			        break;
			    case 5012:
			    	refrescarincremento(obj);
			        break;
			    case 5013:
			    	refrescartipoprecio(obj);
			        break;
			    case 5014:
			    	refrescaranularoferta(obj);
			        break;					        
			    case 9999:
			    	desconectarusuario(obj);
			        break;
			    case 10000:
			    	refrescarremate(obj);
			        break;					        
			   	}			
		};
		socket.onclose   = function(msg) {
			conectado = false;
			conectarcliente();
		};
	}
	catch(ex){}
};