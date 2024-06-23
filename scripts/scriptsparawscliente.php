	//BUSCAR PRECIO INICIAL
	setInterval(function(){
		buscarprecioinicial();
	}, 1000);
	//CHAT
	setInterval(function(){
			jQuery.ajax({
				type:"POST",				
				data:{param : vidchat, idusuario:vidoperador},
			    url: "scripts/cargar.php",
			    dataType:'json',
			    success: function(r){
			    	if(r.success===true){			    		
			     		vidchat = r.idchat;
			     		jQuery('#charla').append('<span class="usuario" style="color:' + r.color + ';">' + r.usre + '</span>' + ' : ' + r.hora + ': ' + r.mensaje + '<br>');
						jQuery('#charla').scrollTop(jQuery('#charla').prop("scrollHeight"));
					}
				}
			});
	} ,1000);
	//MOSTRAR USUARIOS CONECTADOS
	setInterval(function(){		
		jQuery.ajax({
			type:"POST",
		    url: "scripts/buscar_operadores_conectados.php",
		    dataType:'json',
		    success: function(r){		  
				if(r.success==false){
					jQuery('#usuarios').html(''); 
					jQuery('#usuarios').scrollTop(jQuery('#usuarios').prop("scrollHeight"));
				}else{
					var prevState1 = '';
				    jQuery('#usuarios ul').html('');
					for(i=0;i<r.length;i++){
						prevState1 = jQuery('#usuarios ul').html();
						vidUC = r[i].iduconectados;
						jQuery('#usuarios ul').html(prevState1 + r[i].usuarios);
						jQuery('#usuarios ul').scrollTop(jQuery('#usuarios').prop("scrollHeight"));							
    				
					}
				}
			}
		});
	} , 1000);
   	//BUSCAR IDLOTE PARA LOS CLIENTES
	setInterval(function(){		
   			jQuery.ajax({
 				type:"POST",				
			    url: "scripts/buscar_lote_seleccionado.php",
			    dataType:'json',
			     success: function(r){			     	
		     		valselabierto = r.idlote;
			     	if(r.success){
			     		if(r.idlote>0){
				     		BuscarDatosLote(valselabierto);
				     		if(jQuery('#txtmonto').attr('disabled')=='disabled'){
				     			jQuery('#txtmonto').removeAttr('disabled');
			     				jQuery('#btnmontos').removeAttr('disabled');
			     				jQuery('#btnmontob').removeAttr('disabled');
				     		}
				     		clearInterval(buscarprecioinicial);
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
			    }
   			});
   	}, 1000);
   	//BUSCAR ESTADO EN SOLICITUD DE CREDITO
	setInterval(function(){
   			jQuery.ajax({
 				type:"POST",				
			    url: "scripts/buscar_estado_credito.php",
			    dataType:'json',
			     success: function(r){			     	
			     	if(r!=null){
				     	if(r.success){
							var prevState1 = '';						
							prevState1 = jQuery('#desarrollo_sub').html();
							prevState1 = prevState1 + '<br>';												
							jQuery('#desarrollo_sub').html(prevState1 + '<span class="usuario">' + r.hora + '</span>' + ': <span style="color:blue;font-weight: bold;">EL CREDITO FUE AUMENTADO A ' + r.monto + '</span>');						
				     		jQuery('#desarrollo_sub').scrollTop(jQuery('#desarrollo_sub').prop("scrollHeight"));
				     		jQuery('#btnsolicitarcredito').css('background-color', 'gray');
				     	}
				     }
			    }
   			});
   	}, 1000);    	
   	//MIRAR OFERTAS PARA CAMBIAR EL COLOR DE BOTON OFERTAR
   	setInterval(function(){   		
   			jQuery.ajax({
 				type:"POST",
 				data:{param:valselabierto},
			    url: "scripts/mirar_ofertas.php",
			    dataType:'json', 				
			     success: function(r){
			     	if(valselabierto > 0){
				     	if(r.estado=="0"){
			     			jQuery('#btnofertar').css('background-color','yellow');
				     	}	     	
			     		if(r.estado=="3"){ //3=oferta aceptada
			     			jQuery('#btnofertar').css('background-color','green');
			     			jQuery('#txtuo').val(r.monto);
			     		}
			     		if(r.estado=="1" || r.estado=="2"){ //1=rechazado : 2=oferta aceptada pero ganó otra oferta
			     			jQuery('#btnofertar').css('background-color','gray');
			     			if(r.estado == "2"){
			     				jQuery('#txtuo').val(r.monto);
			     			}
			     		}
			     	}else{
						jQuery('#btnofertar').css('background-color','gray');
			     	}
			    }
   			});
   	}, 1000);
	//MOSTRAR DESARROLLO DEL REMATE
	setInterval(function(){
			jQuery.ajax({
				type:"POST",
				data:{idDS:vidDS},
			    url: "scripts/buscar_desarrollo_remate.php",
			    dataType:'json',
			    success: function(r){
			    	if(typeof r.idDS!=='undefined'){
						vidDS = r.idDS;
						var estilo = '';
						var mensaje = '';
						if(r.detalle!='')
							mensaje = r.detalle;
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

							jQuery('#desarrollo_sub').append('<span class="usuario">' + r.hora + '</span>' + ': <span style="' + estilo + '">' + mensaje + "</span><br>");
							jQuery('#desarrollo_sub').scrollTop(jQuery('#desarrollo_sub').prop("scrollHeight"));
						}else{
							jQuery('#desarrollo_sub').empty();
						}
					}
		    	}
			});
	} , 2000);
	//BUSCAR MEJOR OFERTA
	setInterval(function(){
			jQuery.ajax({
				type:"POST",
			    url: "scripts/buscar_mejor_oferta.php",
			    dataType:'json',
			    success: function(r){
			    	if(valselabierto > 0){
			    		jQuery('#infolote_cli #pincremento').html('Incremento <strong>' + r.incremento + '</strong>');
				    	if(r.monto > 0){
					    	var vmonto = parseFloat(r.monto);
					    	valincremento = parseFloat(r.incremento);
					    	var vmontoincremento = parseFloat(vmonto + valincremento);
					    	jQuery('#txtuo').val(vmonto.toFixed(2));
					    	if(vmontoincremento > vmontooferta || isNaN(vmontooferta)) {
					    		vmontooferta = vmontoincremento;
				    			jQuery('#txtmonto').val(Number(vmontooferta.toFixed(2)));
					    	}
					    }else{
					    	var precio = Number(r.precioinicial);
					    	if(precio > 0){
						    	if(entroAPrecioInicial==0 || precio != precioiniciocambio){
						    		jQuery('#txtmonto').val(precio.toFixed(2));
						    		entroAPrecioInicial = 1;
						    		vmontooferta = precio;
						    		precioiniciocambio = precio;
						    		valincremento = r.incremento;						    		
						    	}
						    }else{
						    	jQuery('#txtmonto').val('');
						    }
					    }
					}
			    },
			    error : function(r){
			    }
			});
	} , 1000);


****************************************************************************************************
****************************************************************************************************
****************************************************************************************************
****************************************************************************************************
SEND
			if(valselabierto>0){
			if(colorfondo!='#008000' && colorfondo!='#ffff00'){
				if(!isNaN(jQuery('#txtmonto').val())){
					jQuery.ajax({
							type:"POST",
						    
						    url: "scripts/realizar_oferta.php",
						    dataType:'json',
						    success: function(r){
						    	if(r.success){
									jQuery('#btnofertar').css('background-color', 'yellow');
								}
						    }				    
						});			
				}else{
					var prevState1 = '';						
					prevState1 = jQuery('#desarrollo_sub').html();
					prevState1 = prevState1 + '<br>';												
					jQuery('#desarrollo_sub').html(prevState1 + '<span class="usuario"></span> <span style="background-color:red;color:white;font-weight: bold;margin-left:30px;">NO EXISTEN LOTES ABIERTOS</span>');
					jQuery('#desarrollo_sub').scrollTop(jQuery('#desarrollo_sub').prop("scrollHeight"));
				}
			}
		}
