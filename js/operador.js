var valselabierto=0;
var validusuario = '';
var vidUC = 0;
var entro = 0;
var valincremento = 0;
var vmontooferta = new Number();
var valor;
var entroAPrecioInicial=0;
var total = 0;
var vcantusuconec = 0;
var conectado = false;
var res;
var precioiniciocambio = 0;
var envio = false;
jQuery(document).ready(function(){	
	conectarcliente();
	total = 0;


	var vidchat = 0;
	var vidoferta = 0;
	var vidds = 0;
	
	var entroAPrecioInicial = 0;
	var oTableUsuariosCredito = inicioTablaUsuariosCredito(); //operador
	var oTableHistoricoOfertas = inicioTablaHistoricoOfertas();

	
	buscarremate();
	
	jQuery('#txtusuario').focus();
	jQuery('#listadohistoricoop').empty();

	
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
		}
		if(validusuario==0){
			if(jQuery('#txtmsg').val()!=''){
				envio = true;
				jQuery('#msgtodos').css('display','block');
				jQuery('#msgtodos').dialog({
					closeOnEscape: true,
				    resizable: false,
				    modal: true, 
				    title: "Enviar Mensaje", 
				    height: 150, 
				    width: 320,
				    buttons:{
				    	"Si":function(){
				    		enviaratodos(jQuery('#txtmsg').val());
				    		jQuery(this).dialog('close');
				    	},
				    	"No":function(){
				    		jQuery(this).dialog('close');
				    	}
				    }
				});
			}			
		}else{
			if(jQuery('#txtmsg').val()!=''){
				envio = true;
				var msg = {'accion':1001,'texto':jQuery('#txtmsg').val(),'usrr':validusuario};
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
	
	//MODIFICAR CREDITO DE LOS CLIENTES
	jQuery('#dtcredito').on('click', '.clscredito', function() {
		var vidcredito = jQuery(this).attr('id').substring(2);
		var ac = jQuery(this).attr('id').substring(0,2);
		if(ac=='cs' || ac=='co'){			
			jQuery.ajax({
				type:"POST",
				data: {idcredito: vidcredito },
			    url: "scripts/buscar_monto_credito.php",
			    dataType:'json',
			    success: function(r){
			    	if(r.success){
						jQuery('#montocredito').val(r.monto);
					}
			    }				    
			});
			jQuery("#updcredito").css("display", "block");		
			jQuery("#updcredito").dialog({ 
				closeOnEscape: true,
				resizable: false,
				modal: true, 
				title: "Modificar Credito", 
				height: 210, 
				width: 240,
				buttons:{								
					"Aceptar": function() {	
						var msg = {'accion':4000,'idcredito': vidcredito,'monto':jQuery('#montocredito').val()};
						jQuery.ajax({
							type:"POST",
						    data:msg,
						    url: "scripts/subasta.php",
						    dataType : 'json',
						    success : function(r){						    	
								socket.send(JSON.stringify(r));
								RefreshTable('#dtcredito','scripts/listar_usuario_creditos.php');
						    }
						});						
						jQuery(this).dialog("close")
					},					
					"Cerrar": function() {
						jQuery(this).dialog( "close" );
					}
				}
			});
		}
		if(ac=='uh' || ac=='ud'){
			var msg = {'accion':4002,'param' : vidcredito, 'param1' : ac};
			jQuery.ajax({
				type:"POST",
			    data:msg,
			    url: "scripts/subasta.php",
			    dataType: 'json',
			    success: function(r){			    	
			    	socket.send(JSON.stringify(r));
					RefreshTable('#dtcredito','scripts/listar_usuario_creditos.php');					
			    }
			});	
		}
	});
	//OPERADOR
	//SELECCIONAR UN USUARIO POR PARTE DEL OPERADOR PARA ENVIAR UN MENSAJE POR CHAT
	jQuery('#usuarios').on('click', '.clsbtnchatusuario', function() {
    	var validusuario1 = jQuery(this).attr('id').substring(14);
    	jQuery('.clsbtnchatusuario').css({'background-color':'#DDDDDD','color':'black'});
    	if(validusuario!=validusuario1){
    		jQuery('#'+jQuery(this).attr('id')).css({'background-color':'#0099CC','color':'white'});    	
    		validusuario = validusuario1;
	    	jQuery.ajax({
				type:"POST",
				data: {param: validusuario },
			    url: "scripts/usuario_chat_seleccionado.php",
			    dataType:'json',
			    success: function(r){
			    }	
			});
    	}else{
    		validusuario=0;
    	}
	});	
	jQuery('#btnofertar_op').on('click', function(){
		var montouo = Number(jQuery('#txtuo_op').val());
		vmontooferta = jQuery('#txtmonto_op').val();
		if(valselabierto > 0){
			if(montouo < vmontooferta){
				var msg = {accion:4001, monto : vmontooferta};
				jQuery.ajax({
					type:"POST",
				    data:msg,
				    url: "scripts/subasta.php",
				    dataType : 'json',
				    success : function(r){
				    	refrescarmejoroferta(r);
						socket.send(JSON.stringify(r)); 
				    }
				});				
			}else{
				jQuery('#msgcliente').empty();
				jQuery('#msgcliente').append('<p><strong>MONTO MENOR A LA OFERTA GANADORA</strong></p>');
			}
		}else{			
			jQuery('#msgcliente').empty();
			jQuery('#msgcliente').append('<p><strong>NO EXISTEN LOTES ABIERTOS</strong></p>');
		}
	});
	jQuery('#dtofertaclienteop').on('click', '.clsclienteof', function(){
		var param1  = jQuery(this).attr('id').substring(0,1);
		var param  = jQuery(this).attr('id').substring(1);
		jQuery.ajax({
			type:'post',
			data:{accion:4100,param:param,param1:param1,monto:jQuery('#mcredito').val()},
			url:'scripts/subasta.php',
			dataType:'json',
			success:function(r){
				if(r.success){
					socket.send(JSON.stringify(r));
					refrescarofertasclientes();
					refrescarestadocredito();
				}
			}
		});
	});
	jQuery('#bmcredito').on('click', function(){
		jQuery.ajax({
			type:'post',
			data:{'accion':4050,param:jQuery('#mcredito').val(),param1:valselabierto},
			url:'scripts/subasta.php',
			dataType:'json',
			success:function(r){
				refrescarofertasclientes();
				refrescarestadocredito();
			}
		});
	});
	jQuery('#btnofertar_cerrar_op').on('click', function(){
		jQuery('#cerrarofertarModal').modal();
	});
	jQuery('#btngrabarco').on('click',function(){
		if(jQuery('#lslotesco').val()!='' && jQuery('#montoofertaco').val()!=''){			
			jQuery.ajax({
				type:'post',
				data:{accion:9007,param:jQuery('#lslotesco').val(),param1:jQuery('#montoofertaco').val()},
				url:'scripts/subasta.php',
				dataType:'json',
				success:function(r){
					alert(r.detalle);
					if(r.success){
						socket.send(JSON.stringify(r));
						jQuery('#montoofertaco').val('');
						jQuery('#cerrarofertarModal').modal('hide');
					}
				}
			});
		}
	});
});
function buscarremate(){
	jQuery.ajax({
		type:"POST",	    
	    url: "scripts/buscar_remate_abierto.php",
	    dataType:'json',
	    success: function(r){
		}		    
	});		
};
function BuscarDatosLote(r){
	if(r.success){	
		jQuery('#infolote_ope').empty();
		jQuery('#infolote_ope').append('<p>Nro de Lote <strong>' + r.nrolote + '</strong></p>');
		jQuery('#infolote_ope').append('<p id="pincremento">Incremento <strong>' + r.incremento + '</strong></p>');
		jQuery('#infolote_ope').append('<p>Cantidad de Cabezas <strong>' + r.cantcabezas + '</strong></p>');
		jQuery('#infolote_ope').append('<p>Categor\u00EDa <strong>' + r.categoria + '</strong></p>');
		jQuery('#infolote_ope').append('<p>Ubicaci\u00F3n <strong>' + r.localidad + ' - ' + r.provincia + '</strong></p>');
		r.trazados == 'SI' ? jQuery('#infolote_ope').append('<p style="float:left; margin-right:10px;"><strong>Trazados</strong></p>') : '';
		r.marcaliquida == 'SI' ? jQuery('#infolote_ope').append('<p><strong>Con Marca L\u00EDquida</strong></p>') : '';
		jQuery('#infolote_ope').append('<p>Tipo de Entrega <strong>' + r.tipoentrega + '</strong></p>');
		jQuery('#infolote_ope').append('<p>Plazo <strong>' + r.plazo + '</strong></p>');
		jQuery('#infolote_ope').append('<p id="ptipoprecio">Tipo de Precio <strong>' + r.tipoprecio + '</strong></p>');
		jQuery('#infolote_ope').append('<p>Promedio <strong>' + r.promedio + '</strong></p>');
		jQuery('#infolote_ope').append('<p>Evaluador <strong>' + r.evaluador + '</strong></p>');
	
	}
};
function inicioTablaUsuariosCredito(){ 
	oTableUsuariosCredito = jQuery('#dtcredito').DataTable({
        "destroy":true,
        "responsive": true,
        "ajax": "scripts/listar_usuario_creditos.php",
        "deferRender": true,        
        "scrollY": 266,
        "paging": false,
        "scrollCollapse": true,
        "dom": '<"top"f>rt<"bottom"><"clear">',
        "ordering": false,
        "columns": [
          {data:"usuario", title: "Cliente"},
          {data:"monto", title: "Monto"},
          {data:"estado", title: "Estado"},
          {data:"otorgar", title: "Otorgar"},
          {data:"bloquear", title: "Bloquear"}
        ],
        "language":{
                "sProcessing":     "Procesando...",
                "sZeroRecords":    "No hay usuarios conectados",
                "sEmptyTable":     "No hay usuarios conectados",
                "sInfoPostFix":    "",
                "sSearch":         "Buscar:"
            }
    });  	       
    return oTableUsuariosCredito;
};
function inicioTablaHistoricoOfertas(){ 
	oTableHistoricoOfertas = jQuery('#dtofertaclienteop').DataTable({
        "destroy":true,
        "responsive": true,
        "ajax": "scripts/listar_ofertas_historico_op.php",
        "deferRender": true,        
        "scrollY": 156,
        "paging": false,
        "scrollCollapse": true,
        "dom": '<"top">rt<"bottom"><"clear">',
        "ordering": false,
        "columns": [
          {data:"cliente", title: "Cliente"},
          {data:"total", title: "Ofertado"},
          {data:"disponible", title: "Disponible"},
          {data:"boton1", title: ""},
          {data:"boton2", title: ""}
        ],
        "language":{
                "sProcessing":     "Procesando...",
                "sZeroRecords":    "No hay ofertas",
                "sEmptyTable":     "No hay ofertas",
                "sInfoPostFix":    "",
                "sSearch":         "Buscar:"
            }
    });  	       
    return oTableHistoricoOfertas;	
};
function RefreshTable(tableId, urlData){
  //Retrieve the new data with jQuery.getJSON. You could use it ajax too
  jQuery.getJSON(urlData, null, function( json )
  {
    table = jQuery(tableId).dataTable();
    oSettings = table.fnSettings();    
    table.fnClearTable(this);

    for (var i=0; i<json.aaData.length; i++)
    {
      table.oApi._fnAddData(oSettings, json.aaData[i]);
    }

    oSettings.aiDisplay = oSettings.aiDisplayMaster.slice();    
    table.fnDraw();    
  });
};
function enviaratodos(p){
	var msg = {'accion':1010,'texto':p,'usrr':validusuario};
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
function refrescarprecioinicial(r){

	if(r.precioinicio > 0){ // && r.precioinicio > vmontooferta){
		var precio = Number(r.precioinicio);
    	if(precio > 0){
	    	if(entroAPrecioInicial==0 || precio != precioiniciocambio){
	    		jQuery('#txtmonto_op').val(parseFloat(precio.toFixed(2)));
	    		entroAPrecioInicial = 1;
	    		vmontooferta = parseFloat(precio);
	    		precioiniciocambio = precio;
	    		if(r.incremento>0 || typeof r.incremento!=='undefined')
	    			vmontooferta += parseFloat(r.incremento);
	    			valincremento = r.incremento;						    		
	    	}
	    }else{
	    	//jQuery('#txtmonto').val('');
	    }
	}
};
function refrescarincremento(r){	
	valincremento = Number(r.incremento);
	if(valincremento>0){
		jQuery('#infolote_ope #pincremento').html('Incremento <strong>' + valincremento.toFixed(2) + '</strong>');
	}
};
function refrescartipoprecio(r){
	if(r.success){		
		var vinc = parseFloat(r.inc3);
		jQuery('#infolote_ope #ptipoprecio').html('<p id="ptipoprecio">Tipo de Precio <strong>' + r.descripcion + '</strong></p>');
		jQuery('#infolote_ope #pincremento').html('Incremento <strong>' + vinc.toFixed(2) + '</strong>');
		valincremento = r.inc1;		
	}
};
function refrescarchat(r){
	if(r.success===true){
 		vidchat = r.idchat;
 		jQuery('#charla').append('<span class="usuario badge" style="' + r.color + '">' + r.usre + '<img src="images/enviar.png" height:"16" width="16">' + r.apeynomr + '</span><br>' + r.hora + ': ' + r.mensaje + '<br>');
		jQuery('#charla').scrollTop(jQuery('#charla').prop("scrollHeight"));
		if(!envio){
			jQuery('#not_audio')[0].play();			
		}
		envio = false;
	}
};
function errorchat(){
	jQuery('#msgerror').empty();			
	jQuery('#msgerror').css('display','block');
	jQuery('#msgerror').append('<p>Debe seleccionar un cliente</p>')
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
function refrescarchatinicio(r){
	if(r.aaData[0].success){
		jQuery.each(r.aaData, function(k,v){	
	 		jQuery('#charla').append('<span class="usuario badge" style="' + v.color + '">' + v.usre + '<img src="images/enviar.png" height:"16" width="16">' + v.usrr + '</span><br>' + v.hora + ': ' + v.mensaje + '<br>');
			jQuery('#charla').scrollTop(jQuery('#charla').prop("scrollHeight"));		
	    });
	}
};
function refrescarmejoroferta(r){
   	if(valselabierto > 0){
   		if(typeof r.incremento!=='undefined'){
			jQuery('#infolote_ope #pincremento').html('Incremento <strong>' + r.incremento + '</strong>');
	    	valincremento = parseFloat(r.incremento);
		}
    	if(r.monto > 0){
	    	var vmonto = parseFloat(r.monto);
	    	vmontoincremento = parseFloat(vmonto) + parseFloat(valincremento);
	    	jQuery('#txtuo_op').val(vmonto.toFixed(2));
	    	if(vmontoincremento > vmontooferta || !isNaN(vmontooferta)) {
	    		vmontooferta = vmontoincremento;
    			jQuery('#txtmonto_op').val(Number(vmontooferta.toFixed(2)));
    			jQuery('#msgcliente').empty();
				//jQuery('#msgcliente').append('<p><strong>SU OFERTA FUE REALIZADA</strong></p>');
	    	}
	    }else{				    	
	    	var precio = Number(r.precioinicial);
	    	if(precio > 0){
		    	if(entroAPrecioInicial==0){
		    		jQuery('#txtmonto_op').val(parseFloat(precio.toFixed(2)));
		    		entroAPrecioInicial = 1;				    		
	    			jQuery('#msgcliente').empty();
					//jQuery('#msgcliente').append('<p><strong>SU OFERTA FUE REALIZADA</strong></p>');
		    	}
		    }else{
		    	jQuery('#txtmonto_op').val('');
		    }
	    }
	}
};
function refrescarusuariosconectados(r){
	total = r[0].cantidad;
	if(!r[0].success){
		jQuery('#usuarios').empty(); 
		jQuery('#totalconectados').empty();
		jQuery('#totalconectados').append('Conectactos 0');
	}else{
		jQuery('#totalconectados').empty();
		jQuery('#totalconectados').append('Conectactos ' + total);
		if(vcantusuconec != total){
			vcantusuconec = total;
			var prevState1 = '';
		    jQuery('#usuarios').html('');
			for(i=0;i<total;i++){
				prevState1 = jQuery('#usuarios').html();
				vidUC = r[i].iduconectados;
				jQuery('#usuarios').html(prevState1 + r[i].usuarios);
				jQuery('#usuarios').scrollTop(jQuery('#usuarios').prop("scrollHeight"));							
			}
		}
	}
};
function refrescardesarrolloremate(r){	
	if(r.success){	
    	var mensaje = r.mensajeop;
    	estilo = r.tipo=='ds' ? "color:black;font-weight: bold;" : '';
    	if(r.tipo=='ds')
			vidds = r.idDS;
		if(r.tipo=='dh')
			vidoferta = r.idDS;
		jQuery('#listadohistoricoop').append('<span style="' + estilo + '">' + mensaje + "</span><br>");
		jQuery('#listadohistoricoop').scrollTop(jQuery('#listadohistoricoop').prop("scrollHeight"));
	   	if(typeof r.detalle2!=='undefined'){
    		mensaje = r.detalle2;
			jQuery('#listadohistoricoop').append('<span style="' + estilo + '">' + mensaje + "</span><br>");
			jQuery('#listadohistoricoop').scrollTop(jQuery('#listadohistoricoop').prop("scrollHeight"));
    	}
		refrescarestadocredito();
		refrescarofertasclientes();
	}
};
function refrescarofertasclientes(){	
	inicioTablaHistoricoOfertas();
	inicioTablaUsuariosCredito();
};
function refrescardesarrolloremateinicio(r){
	if(r.aaData[0].success){
		jQuery.each(r.aaData, function(k,v){
			estilo = v.tipo=='ds' ? "color:black;font-weight: bold;" : '';
			jQuery('#listadohistoricoop').append('<span style="' + estilo + '">' + v.detalle + "</span><br>");
			jQuery('#listadohistoricoop').scrollTop(jQuery('#listadohistoricoop').prop("scrollHeight"));	
		});	
	}
};
function refrescarestadocredito(){
	RefreshTable('#dtcredito','scripts/listar_usuario_creditos.php');	
};
function refrescarusuarionuevo(r){	
	var entro = false;
	jQuery('.clsusuariosop').each(function(idx, el) {
		if(jQuery(el).attr('id')==jQuery(r.usuarios).attr('id')){
	   		entro = true;	   		
	   	}
	});
	if(!entro){
		jQuery('#usuarios').append(r.usuarios);
		jQuery('#usuarios').scrollTop(jQuery('#usuarios').prop("scrollHeight"));		
		total ++;
		jQuery('#totalconectados').empty();
		jQuery('#totalconectados').append('Conectados ' + total);
	}
	RefreshTable('#dtcredito','scripts/listar_usuario_creditos.php');
};
function desconectarusuario(r){
	var entro = false;
	jQuery('.clsusuariosop').each(function(idx, el) {
		if(jQuery(el).attr('id')==jQuery(r.usuarios).attr('id')){
	   		entro = true;
	   		var idel = jQuery(el).attr('id');
	   		jQuery('#'+idel).remove('.clsusuariosop');
	   	} 
	});
	if(entro){	
		jQuery('#usuarios').scrollTop(jQuery('#usuarios').prop("scrollHeight"));
		total--;
		jQuery('#totalconectados').empty();
		jQuery('#totalconectados').append('Conectados ' + total);
	}
	RefreshTable('#dtcredito','scripts/listar_usuario_creditos.php');
};
function refrescarloteabierto(r){
	valselabierto = r.idlote;
	jQuery.ajax({
		type:'post',
		data:{param:r.idlote},
		url:'scripts/refrescar_lote_abierto.php',
		dataType:'json'
	});
 	if(r.success){
 		if(r.idlote>0){ 			
     		BuscarDatosLote(r);
     		valincremento = r.incremento;
     		if(r.montooferta>0){
     			vmontooferta = parseFloat(r.montooferta);
     			jQuery('#txtuo_op').val(vmontooferta.toFixed(2));
     			vmontoincremento = parseFloat(vmontooferta) + parseFloat(valincremento);
     			vmontoincremento = parseFloat(vmontoincremento).toFixed(2);
	     		jQuery('#txtmonto_op').val(vmontoincremento);
     			
     		}else{     			
	     		jQuery('#txtmonto_op').val('');
	     		//jQuery('#txtmonto_op').val(parseFloat(r.precioinicio).toFixed(2));
	 			vmontooferta = parseFloat(r.precioinicio);
	 			jQuery('#txtmonto_op').val(vmontooferta.toFixed(2));
     		}
 		}else{
     		jQuery('#txtuo_op').val('');
     		jQuery('#txtmonto_op').val('');
     		jQuery('#infolote_ope').empty();
 			entro = 0;
 		}
 	}
};
function refrescarpasarlote(r){
	if(r.success){
		jQuery('#infolote_ope').empty();
		jQuery('#txtuo_op').val('');
		jQuery('#txtmonto_op').val('');
	}
};
function refrescaraceptaroferta(r){
	if(r.success){
		jQuery('#txtuo_op').val(r.monto);
		jQuery('#txtmonto_op').val(r.monto);
	}
};
function refrescarcerrarlote(r){
	if(r.success){
		valselabierto = 0;
		valincremento = 0;		
		vmontooferta = 0;
		jQuery('#infolote_ope').empty();
		jQuery('#txtuo_op').val('');
		jQuery('#txtmonto_op').val('');
		jQuery('#btnofertar_op').css('background-color','#808080');
	}
};
function refrescarofertapropiainicio(r){
	if(r.success){
		//jQuery('#txtmonto_op').val(r.monto);
	}
};
function refrescaranularoferta(r){
	if(r.success){
		var vmonto = Number(r.monto);
		vmontooferta = vmonto + valincremento; 
		jQuery('#txtuo_op').val(parseFloat(vmontooferta).toFixed(2));
		jQuery('#txtmonto_op').val(vmonto.toFixed(2));
		refrescardesarrolloremate(r);
	}
};
function conectarcliente(){
	if(!conectado){
		iniciar();
		init();
	}
	/*
	console.log(conectado);
	if(!conectado){		
		init();		
		setTimeout(function(){
			conectarcliente();
		}, 5000);
	}else{
		clearTimeout();
	}
	console.log(conectado);
	*/
};
function iniciar(){
	jQuery.ajax({
		type:"POST",
	    url: "scripts/subasta.php",
	    dataType : 'json',
	    success : function(r){
	    	res = r;
			refrescarchatinicio(r['chatinicio']);
	    	refrescarusuariosconectados(r['usuariosconectados']);
	    	refrescardesarrolloremateinicio(r['desarrolloremateop']);
		    refrescarmejoroferta(r['mejoroferta']);
		    refrescarprecioinicial(r['lote']);
	    	refrescarloteabierto(r['lote']);
		    refrescarofertapropiainicio(r['ofertapropia']);
	    }
	});	
};
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
			var obj = jQuery.parseJSON(msg.data);			
			if(typeof obj == 'undefined'){
				accion = -1;
				udesc ='undefined';
			}else{
				if(typeof obj[0] == 'undefined'){
					accion = obj.accion;
					udesc = obj.usdesc;
				}else{
					accion = obj[0].accion;
					udesc = obj[0].usdesc;
				}
			}

			if(typeof accion==='undefined')
				accion = 0;
			
			console.log(accion);
			console.log(udesc);
			switch(accion) {
				case 0:				
					refrescarchatinicio(obj);					
					break;
			    case 1000:
			    	//BUSCAR PRECIO INICIAL
			    	refrescarprecioinicial(obj);
			        break;
			    case 1100:
			    	//CHAT
			    	refrescarchat(obj);
			        break;
			    case 1200:
					//MOSTRAR USUARIOS CONECTADOS
					refrescarusuariosconectados(obj);
			        break;
			    case 1201:
					refrescarusuarionuevo(obj);
			        break;
			    case 1203:
					refrescardesarrolloremateinicio(obj);
			        break;
				case 1204:
			    	refrescarloteabierto(obj);
			    	refrescardesarrolloremate(obj);
			    	refrescarofertasclientes();
			        break;
			    case 1205:
			    	refrescarmejoroferta(obj);
			        break;			    
			    case 1206:
			        break;					        
			    case 2002:
			    	refrescarestadocredito(obj);
			    	refrescardesarrolloremate(obj);
			        break;			    
			    case 4000:
			    	refrescarestadocredito(obj);
			    	refrescardesarrolloremate(obj);
			    	refrescarofertasclientes();
			        break;
			    case 4001:
			    	refrescarmejoroferta(obj);
			        break;
			    case 4400:
			    	refrescardesarrolloremate(obj);
			        break;
			    case 5003:
			    	refrescarloteabierto(obj);
					refrescarofertasclientes();
			    	//refrescardesarrolloremate(obj);
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
			    	refrescarofertasclientes();
			        break;    
			    case 5009:
			    	refrescarmejoroferta(obj);
			    	refrescardesarrolloremate(obj);
			    	refrescarofertasclientes();
			        break;
			    case 5010:
			    	refrescardesarrolloremate(obj);
			        break;				        
			    case 5011:
			    	refrescarprecioinicial(obj);
			    	refrescardesarrolloremate(obj);
			        break;							
			    case 5012:
			    	refrescarincremento(obj);
			    	refrescardesarrolloremate(obj);
			        break;			        
			    case 5013:
			    	refrescartipoprecio(obj);
			    	refrescardesarrolloremate(obj);
			        break;
			    case 5014:
			    	refrescaranularoferta(obj);
			        break;
			    case 9999:
			    	desconectarusuario(obj);
			        break;			        
			};
		}
		socket.onclose   = function(msg) {
			conectado = false;
			conectarcliente();
		};
	}
	catch(ex){
		console.log(ex);
	}
};