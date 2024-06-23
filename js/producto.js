var player;
var arrvideos = new Array();	
var player;
jQuery(document).ready(function(){
	buscarvideos();	
	jQuery(".fancybox").fancybox();
	jQuery('#dtrematescatalogo tbody tr').on('click',function(){
		console.log(jQuery(this).context.lastElementChild.value);		
    	jQuery.ajax({
    		type:'post',
    		data:{param:jQuery(this).context.lastElementChild.value},
    		url:'scripts/selecremate.php',
    		dataType:'json',
    		success:function(r){
    			if(r.mensaje) {
    				jQuery('#divremates').hide();
    				inicioTablaLotesCatalogo();
    				jQuery('#producto').css('display','block');
    			}
    		}
    	})
	});
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
		    		//console.log(r);
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
					if(r.localidad!=null && r.provincia!=null){
						jQuery('#cajalocalidad').empty();
						jQuery('#cajalocalidad').append('<div class="clsdetallelote"><p>Localidad <strong>' + r.localidad[0].nombre + '</strong></p></div>');
						jQuery('#cajalocalidad').append('<div class="clsdetallelote"><p>Provincia <strong>' + r.provincia[0].nombre + '</strong></p></div>');						
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
					r.imagen.length > 4 ? cantidad = 4 : cantidad = r.imagen.length;
					var j=0;
					for(i=0;i<cantidad;i++){					
						//jQuery('#foto' + i).attr('src', r.imagen[i].imagen.substring(0,r.imagen[i].imagen.length-4)+'_n.jpg');
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
						player = {
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
	jQuery('#detallelote').on('dialogclose', function(event, ui){		
		if(typeof jQuery('#player')[0].contentWindow!=='undefined'){
			jQuery('#player')[0].contentWindow.postMessage('{"event":"command","func":"' + 'stopVideo' + '","args":""}', '*');
		}
	});
});
function inicioTablaLotesCatalogo(){ 
	oTableLotesCatalogo = jQuery('#dtlotecatalogo').DataTable({
		"processing":true,
        "destroy":true,
        "responsive": true,
        "ajax": "scripts/catalogo_cli.php",
        "deferRender": true,        
        "scrollY": 435,
        "paging": false,
        "scrollCollapse": true,
        "dom": '<"top">rt<"bottom"><"clear">',
        "ordering": false,
        "columns": [
          {data:"lote", title: "Detalle de lotes",align:'center'},
          {data:"boton", title: ""}
        ],
        "language":{
                "sProcessing":     "Procesando...",
                "sZeroRecords":    "No se encontraron lotes",
                "sEmptyTable":     "No se encontraron lotes"
            }
    	});  
    return oTableLotesCatalogo;
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
function buscarvideos(){
	console.log('videos');
	jQuery.ajax({
		type:"POST",	    
	    url: "scripts/buscar_videos_hacienda.php",
	    dataType:'json',
	    success: function(r){
	    	console.log(r);
		    arrvideos = r;		    
		},
		error:function(x,t,e){
			console.log(e);
		}
	});	
};