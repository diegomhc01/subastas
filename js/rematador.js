var valsel = 0;
var valselabierto = 0;
var estadoloteabierto = 0;
var valsellote;
var valsellotecat;
var vidoferta;
var vpeticionesmartillo = 0;
var vidDS = 0;
var vidUC = 0;
var validusuario = 0;
var valincremento = 0;
var montoabrirlote = -1;
var vcantusuconec = 0;
var total = 0;
var entro = false;
var error5005 = false;
var conectado = false;

jQuery(document).ready(function(){	
	conectarcliente();

	var vidchat = 0;
	var monto;	
	
	
	var oTableLotes = inicioTablaLotes(); 
	
	
	//ABRE EL REMATE DEL LOTE SELECCIONADO
	jQuery('#btnabrir').on('click', function(){	
		vidoferta = 0;
		//SE ABRE LA SUBASTA DEL LOTE ' + valselabierto},
		valselabierto = valsellote;
		if(jQuery(this).val().substring(0,1)==='A'){
			var msg = {accion:5003,param: valselabierto};
			jQuery.ajax({
				type:"POST",
			    data:msg,
			    url: "scripts/subasta.php",
			    dataType : 'json',
			    success : function(r){
					socket.send(JSON.stringify(r)); 
					AbrirLoteResponse(r);
			    }
			});	
		}else{
			var msg = {accion:5004,param: valselabierto};
			jQuery.ajax({
				type:"POST",
			    data:msg,
			    url: "scripts/subasta.php",
			    dataType : 'json',
			    success : function(r){				    
					socket.send(JSON.stringify(r)); 
					AbrirLoteResponse(r);
			    }
			});
		}		
	});
	//AVISA A LOS COMPRADORES QUE SE ESTÁ POR CERRAR EL REMATE
	jQuery('#btnbajarmartillo').on('click',function(){
		//EL REMATADOR ESTA POR BAJAR EL MARTILLO'
		var msg = {'accion':5006};
		jQuery.ajax({
			type:"POST",
		    data:msg,
		    url: "scripts/subasta.php",
		    dataType : 'json',
		    success : function(r){
		    	refrescarbajarmartillo(r);
				socket.send(JSON.stringify(r)); 
		    }
		});				
	});
	//CIERRA LA SUBASTA DEL LOTE ABIERTO
	jQuery('#btncerrarsubasta').on('click',function(){
		//'SUBASTA CERRADA'
		var msg = {'accion':5008};
		jQuery.ajax({
			type:"POST",
		    data:msg,
		    url: "scripts/subasta.php",
		    dataType : 'json',
		    success : function(r){	
				socket.send(JSON.stringify(r)); 
		    	refrescarcerrarsubasta(r);	
		    }
		});
	});
	jQuery('#btnpasarlote').on('click', function(){
		var msg = {'accion':5005};
		jQuery.ajax({
			type:"POST",
		    data:msg,
		    url: "scripts/subasta.php",
		    dataType : 'json',
		    success : function(r){			
				socket.send(JSON.stringify(r)); 
		    }
		});
	});
	//SELECCIONA UN LOTE PARA OFRECERLO A SUBASTA DEL LISTADO DE LOTES A SUBASTAR DEL REMATE ACTUAL
	jQuery('#dtlotes').on('click', '.clslotes', function() {
		var idboton = jQuery(this).attr('id');
		valsellote = 0;		
    	valsellote = idboton.substring(idboton.indexOf('R'), 4);     	
    	var msg = {accion:5002,param:valsellote};
		jQuery.ajax({
			type:"POST",
		    data:msg,
		    url: "scripts/subasta.php",
		    dataType : 'json',
		    success : function(r){		    	
				BuscarDatosLoteSel(r);
		    },
		    error:function(x,l,e){
		    	console.log(e);
		    }
		});
	});
	//EVENTOS DE LA LISTA DE OFERTAS ENVIADAS AL REMATADOR POR PARTE DE LOS CLIENTES
	//ACEPTAR: ACEPTA LA OFERTA DEL OFERENTE
	//RECHAZAR:
	//OMITIR: OMITE LA OFERTA DEL OFERENTE
	jQuery('#dtofertas').on('click', '.clsofertas', function() {
		jQuery('#btncerrarsubasta').css('display','none');
		var tipoaccion = jQuery(this).attr('id').substring(0,1);
		var param = jQuery(this).closest('tr').find('td:eq(0)').text();
		vidoferta = jQuery(this).attr('id').substring(1);
		if(tipoaccion=='a'){
			AceptarOferta(vidoferta, param);
		}		
		if(tipoaccion=='o'){
			OmitirOferta(vidoferta, param);
		}
	});	
	jQuery('#selecttipoprecio').on('change', function(){
		var id = jQuery(this).val();		
		var msg = {'accion':9009,'param' : id};
		jQuery.ajax({
			type:"POST",
		    data:msg,
		    url: "scripts/subasta.php",
		    dataType : 'json',
		    success : function(r){
		    	refrescartipoprecio(r);
				
		    }
		});  
	});
	jQuery('#btninc1').on('click', function(){
		jQuery('#txtinc').val(jQuery(this).val());
		actualizarincremento(jQuery(this).val());
	})
	jQuery('#btninc2').on('click', function(){
		jQuery('#txtinc').val(jQuery(this).val());
		actualizarincremento(jQuery(this).val());
	})
	jQuery('#btninc3').on('click', function(){
		jQuery('#txtinc').val(jQuery(this).val());
		actualizarincremento(jQuery(this).val());
	})
	jQuery('#txtinc').on('blur', function(){
		actualizarincremento(jQuery(this).val());
	});

	jQuery('#btnprecioinicial').on('click', function(){		
		if(!isNaN(jQuery('#txtprecioinicial').val())){
			if(jQuery('#txtprecioinicial').val()>0){
				actualizarprecioinicio(jQuery('#txtprecioinicial').val());
			}
		}
	});
	jQuery('#btnrefrescarlotes').on('click', function(){
		RefreshTable('#dtlotes','scripts/buscar_lotes_rem.php');
	});
	//SELECCIONA UNA OFERTA EN HISTORICO PARA ANULAR OFERTA ACEPTADA
	jQuery('#dthisotricorem').on('click', '.clsofertalote', function() {
		anularoferta(jQuery(this).attr('id'));
	});
});
function RefreshTable(tableId, urlData){
  //Retrieve the new data with jQuery.getJSON. You could use it ajax too
  jQuery.getJSON(urlData, null, function( json )
  {
    table = $(tableId).dataTable();
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
function BuscarDatosLoteSel(r){	
	if(typeof valselabierto==='undefined'){
		valselabierto = 0;
	}
	if(r.estado>0 && r.estado<3){
		valselabierto = r.idlote
	}
	
	valsellote = r.idlote;	
	if(r.success){
		jQuery('#infolote_rem').empty();		
		jQuery('#infolote_rem').append('<p>Nro de Lote <strong>' + r.nrolote + '</strong></p>');		    		
		jQuery('#infolote_rem').append('<p>Nro Contrato <strong>' + r.nrocontrato + '</strong></p>');		    		
		jQuery('#infolote_rem').append('<p id="pincremento">Incremento <strong>' + r.incremento + '</strong></p>');
		jQuery('#infolote_rem').append('<p>Cantidad de Cabezas <strong>' + r.cantcabezas + '</strong></p>');
		jQuery('#infolote_rem').append('<p>Descripci\u00F3n <strong>' + r.categoria + '</strong></p>');
		jQuery('#infolote_rem').append('<p>Ubicaci\u00F3n <strong>' + r.localidad + ' - ' + r.provincia + '</strong></p>');
		r.trazados == 'SI' ? jQuery('#infolote_rem').append('<p style="float:left; margin-right:10px;"><strong>Trazados</strong></p>') : '';
		r.marcaliquida == 'SI' ? jQuery('#infolote_rem').append('<p><strong>Con Marca L\u00EDquida</strong></p>') : '';		    		
		jQuery('#infolote_rem').append('<p style="float:left; margin-right:25px;">Tipo de Entrega <strong>' + r.tipoentrega + '</strong></p>');		    		
		jQuery('#infolote_rem').append('<p>Plazo <strong>' + r.plazo + '</strong></p>');		    		
		jQuery('#infolote_rem').append('<p>Tipo de Precio <strong>' + r.tipoprecio + '</strong></p>');		    		
		jQuery('#infolote_rem').append('<p>Promedio <strong>' + r.promedio + '</strong></p>');
		jQuery('#infolote_rem').append('<p id="pprecioinicio">Precio Inicial <strong>' + r.precioinicio + '</strong></p>');
		jQuery('#infolote_rem').append('<p>Evaluador <strong>' + r.evaluador + '</strong></p>');		
		if(r.monto>0)
			jQuery('#infolote_rem').append('<p>Monto Oferta <strong>' + r.monto + '</strong></p>');
		if(valsellote > 0){ 
			if(valselabierto==0){
				if(r.estado==0){					
					jQuery('#infolote_rem').append('<p></p>');
		    		jQuery('#infolote_rem').append('<p></p>');
		    		jQuery('#infolote_rem').append('<p></p>');
		    		jQuery('#infolote_rem').append('<p></p>');
		    		jQuery('#btnabrir').attr('value','Abrir Lote N\u00B0 ' + r.nrolote);
					jQuery('#btnabrir').css({'display':'block','color':'#000000','background-color':'#3399CC'});
					//jQuery('#txtincremento').css('display','block');
				}
				if(r.estado==3){
					jQuery('#btnbajarmartillo').css('display','none');							
					jQuery('#btnpasarlote').css('display','none');
			    	jQuery('#btnabrir').attr('value','Reabrir Lote N\u00B0 ' + r.nrolote);
					jQuery('#btnabrir').css({'display':'block','color':'#FFFFFF','background-color':'red'});
					jQuery('#infolote_rem').append('<p style="color:red;"><strong>Este lote ya fue rematado</strong></p>');
				}
			}		
			if(r.estado==1){
				jQuery('#datosloteabiertorem').empty();
				jQuery('#btnabrir').css('display','none');
				jQuery('#btncerrarsubasta').css('display','none');
				jQuery('#btnbajarmartillo').css('display','block');
				jQuery('#btnpasarlote').css('display','block');					
				jQuery('#precioinicial_rem').css('display','block');				
				jQuery('#tipoprecio').css('display','block');
				jQuery('#selecttipoprecio').val(r.idtp);
				jQuery('#incremento_rem').css('display','block');
				jQuery('#oferta_rem').css('display','block');
				jQuery('#txtprecioinicial').val(r.precioinicio);
				jQuery('#txtinc').val(r.incremento);
				jQuery('#btninc1').val(r.inc1);
				jQuery('#btninc2').val(r.inc2);
				jQuery('#btninc3').val(r.inc3);
				jQuery('#txtoferente').val(r.ganador);
				jQuery('#txtmontor').val(r.monto);
				jQuery('#datosloteabiertorem').append('<p style="font-size: 20px;margin-left:10px;color:#028900;font-weight: bold;padding:0;margin-bottom:0;">Lote Abierto N\u00B0 ' + r.nrolote + '</p>');
				vidoferta = r.idoferta;
			}			
			if(r.estado==2){
				jQuery('#btncerrarsubasta').css('display','block');
				jQuery('#btnbajarmartillo').css('display','none');
				jQuery('#btnpasarlote').css('display','none');					
				jQuery('#precioinicial_rem').css('display','block');				
				jQuery('#tipoprecio').css('display','block');				
				jQuery('#incremento_rem').css('display','block');
				jQuery('#oferta_rem').css('display','block');
				jQuery('#datosloteabiertorem').append('<p style="font-size: 20px;margin-left:10px;color:#028900;font-weight: bold;padding:0;margin-bottom:0;">Lote Abierto N\u00B0 ' + r.nrolote + '</p>');
				jQuery('#txtinc').val(r.incremento);
				jQuery('#btninc1').val(r.inc1);
				jQuery('#btninc2').val(r.inc2);
				jQuery('#btninc3').val(r.inc3);
			}			
			if(r.estado==3 && valselabierto>0){
				jQuery('#infolote_rem').append('<p style="color:red;"><strong>Este lote ya fue rematado</strong></p>');
			}			
		}
	}else{
		jQuery('#infolote_rem').empty();
		jQuery('#infolote_rem').append('<p><strong>NO EXISTE HACIENDA PARA ESE LOTE</strong></p>');
		jQuery('#btnabrir').css('display','none');
	}
};
function AceptarOferta(poferta, param){
	var msg = {'accion':9006,'param' : poferta, 'param1':param};
	jQuery.ajax({
		type:"POST",
	    data:msg,
	    url: "scripts/subasta.php",
	    dataType : 'json',
	    success : function(r){	    	
			socket.send(JSON.stringify(r));
	    	refrescaraceptaroferta(r);
			refrescarlistadohistoricoofertas();
			refrescarlistadoofertas();
	    }
	});  
};
function OmitirOferta(poferta,param){
	var msg = {'accion':9008,'param' : poferta};
	jQuery.ajax({
		type:"POST",
	    data:msg,
	    url: "scripts/subasta.php",
	    dataType : 'json',
	    success : function(r){			
			socket.send(JSON.stringify(r)); 
			refrescarlistadohistoricoofertas();
			refrescarlistadoofertas();
	    }
	});  
};
function AbrirLoteResponse(r){
	inicioTablaHistoricoOfertas();
	inicioTabla();
	jQuery('#btnabrir').css('display','none');
	jQuery('#btnbajarmartillo').css('display','block');
	jQuery('#btnpasarlote').css('display','block');
	jQuery('#incremento_rem').css('display','block');
	jQuery('#tipoprecio').css('display','block');
	jQuery('#precioinicial_rem').css('display','block');	
	jQuery('#oferta_rem').css('display','block');
	jQuery('#txtprecioinicial').val(r.precioinicio);
	jQuery('#selecttipoprecio').val(r.idtp);
	jQuery('#txtinc').val(r.incremento);
	jQuery('#btninc1').val(r.inc1);
	jQuery('#btninc2').val(r.inc2);
	jQuery('#btninc3').val(r.inc3);
	jQuery('#datosloteabiertorem').append('<p style="font-size: 20px;margin-left:10px;color:#028900;font-weight: bold;padding:0;margin-bottom:0;">Lote Abierto N\u00B0 ' + r.nrolote + '</p>');
	jQuery('#detalle_hacienda_rem').empty();	
	for (var i = 0; i<r.lotes.length;i++) {
		if(r.lotes.length>1){			
			jQuery('#detalle_hacienda_rem').append('<label for="chkhaciendaid"' + r.lotes[i].idhacienda + '">' + r.lotes[i].detalle + '</label>');
			jQuery('#detalle_hacienda_rem').append('<input type="checkbox" name="chkhaciendaid' + r.lotes[i].idhacienda + '" id="chkhaciendaid' + r.lotes[i].idhacienda + '" checked="checked">');
		}
	}
};
function AceptoOfertaResponse(r){	
	jQuery('#txtoferente').val(r.usuario);
	jQuery('#txtmonto').val(r.monto);
	return false;
};
function ModiCreditoResponse(r){
	RefreshTable('#dtcredito','scripts/listar_usuario_creditos.php');
	return false;
};
function inicioTabla(){ 
	var oTable;
	if(valselabierto>0){
		oTable = jQuery('#dtofertas').DataTable({
	        "destroy":true,
	        "responsive": true,
	        "ajax": "scripts/listarofertas.php",
	        //"ajax": "scripts/hacienda.txt",
	        "deferRender": true,        
	        "scrollY": 266,
	        "paging": false,
	        "scrollCollapse": true,
	        "dom": '<"top">rt<"bottom"><"clear">',
	        "ordering": false,
	        "fnServerParams": function ( aoData ) {
		            aoData.push({ "name": "param", "value": valselabierto});
		    },
	        "columns": [
	          {data:"cliente", title: "Cliente"},
	          {data:"oferta", title: "Oferta"},
	          {data:"aceptar", title: ""},
	          {data:"rechazar", title: ""}
	        ],
	        "language":{
	                "sProcessing":     "Procesando...",
	                "sZeroRecords":    "No se encontraron ofertas",
	                "sEmptyTable":     "No se encontraron ofertas"
	            }
    	});	      
    }
    return oTable;
};
function inicioTablaHistoricoOfertas(){
	oTableHistoricoOfertas = jQuery('#dthisotricorem').DataTable({
        "destroy":true,
        "responsive": true,
        "ajax": "scripts/listar_ofertas_historico.php",
        "deferRender": true,        
        "scrollY": 213,
        "paging": false,
        "scrollCollapse": true,
        "dom": '<"top">rt<"bottom"><"clear">',
        "ordering": false,
        "columns": [
          {data:"cliente", title: "Cliente"},
          {data:"oferta", title: "Oferta"},
          {data:"estado", title: "Estado"},
          {data:"boton", title: ""}
        ],
        "fnServerParams": function ( aoData ) {
            aoData.push({"name": "param", "value": valselabierto });
        },        
        "language":{
                "sProcessing":     "Procesando...",
                "sZeroRecords":    "No se encontraron ofertas",
                "sEmptyTable":     "No se encontraron ofertas"
            }
    });     
      return oTableHistoricoOfertas;
};
function inicioTablaLotes(){ 
	oTableLotes = jQuery('#dtlotes').DataTable({
        "destroy":true,
        "responsive": true,
        "ajax": "scripts/buscar_lotes_rem.php",
        "deferRender": true,        
        "scrollY": 266,
        "paging": false,
        "scrollCollapse": true,
        "dom": '<"top">rt<"bottom"><"clear">',
        "ordering": false,
        "columns": [
          {data:"boton", title: ""}
        ],
        "language":{
                "sProcessing":     "Procesando...",
                "sZeroRecords":    "No se encontraron lotes",
                "sEmptyTable":     "No se encontraron lotes"
            }
	    });  
      return oTableLotes;
};
function CargarResponse(r){
	//jQuery('#charla').html('');
	jQuery.each(r, function( i, val ) {
		var prevState = jQuery('#charla').html();
		if(prevState.length > 3){
			prevState = prevState + '<br>';
		}
		idchat = val.idchat;
		jQuery('#charla').html(prevState + '<span class="usuario">' + val.usre + '</span>' + ' : ' + val.hora + ': ' + val.mensaje);	  		
		jQuery('#charla').scrollTop(jQuery('#charla').attr("scrollHeight"));
  	});	
	jQuery('#txtmsg').val('');
	return idchat;
};
function actualizarincremento(param){	
	var msg = {'accion':9011,'incremento': param};
	jQuery.ajax({
		type:"POST",
	    data:msg,
	    url: "scripts/subasta.php",
	    dataType : 'json',
	    success : function(r){
	    	var vinc = parseFloat(r.incremento);
	    	jQuery('#infolote_rem #pincremento').html('Incremento <strong>' + vinc.toFixed(2) + '</strong>');
			socket.send(JSON.stringify(r)); 
	    }
	});
};
function actualizarprecioinicio(param){
	var msg = {'accion':9010,'precioinicio': param};
	jQuery.ajax({
		type:"POST",
	    data:msg,
	    url: "scripts/subasta.php",
	    dataType : 'json',
	    success : function(r){
	    	if(typeof r!==null){	    		
			    if(r.success){
			    	refrescarprecioinicio(r);	
					socket.send(JSON.stringify(r));
				}else{
					jQuery('#msganularoferta').css('display','block');
					jQuery('#msganularoferta').html('');
					jQuery('#msganularoferta').append('<p>' + r.mensajeop + '</p>');
					jQuery('#msganularoferta').dialog({
						closeOnEscape: true,
					    resizable: false,
					    modal: true, 
					    title: "Precio Inicial", 
					    height: 180, 
					    width: 270,
					    buttons:{
					    	"Cerrar":function(){
					    		jQuery('#txtprecioinicial').val(r.precioinicio);
					    		jQuery(this).dialog('destroy');
					    		jQuery('#msganularoferta').css('display','none');
					    	}
					    }
					});	
				}
	    	}
	    }
	});  
};
function anularoferta(param1){

	jQuery('#msganularoferta').css('display','block');
	jQuery('#msganularoferta').empty();
	jQuery('#msganularoferta').append('<p><strong>ATENCION: ESTA POR ANULAR UNA OFERTA</strong></p>');
	jQuery('#msganularoferta').dialog({
		closeOnEscape: true,
	    resizable: false,
	    modal: true, 
	    title: "Anular Oferta", 
	    height: 140, 
	    width: 335,
	    buttons:{
	    	"Aceptar":function(){
	    		var msg = {'accion':9012};
				jQuery.ajax({
					type:"POST",
				    data:msg,
				    url: "scripts/subasta.php",
				    dataType : 'json',
				    success : function(r){			
						socket.send(JSON.stringify(r)); 
						refrescaranularoferta(r);
				    }
				});  
				jQuery(this).dialog('destroy');
				jQuery('#msganularoferta').css('display','none');
	    	},
	    	"Cerrar":function(){
	    		jQuery(this).dialog('destroy');
				jQuery('#msganularoferta').css('display','none');
	    	}
	    }
	});
};
function refrescarlistadoofertas(){
	if(valselabierto > 0){
   		RefreshTable('#dtofertas','scripts/listarofertas.php?param=' + valselabierto);	
   	}
};
function refrescarlistadohistoricoofertas(){
	if(valselabierto>0){
		RefreshTable('#dthisotricorem','scripts/listar_ofertas_historico.php?param='+valselabierto);
	}	
};
function refrescarchat(r){	
	if(r.success){
 		vidchat = r.idchat;
 		jQuery('#charla').append('<span class="usuario" style="color:' + r.color + ';">' + r.usre + ' >> ' + r.apeynomr + '</span>' + ' : ' + r.hora + ': ' + r.mensaje + '<br>');
		jQuery('#charla').scrollTop(jQuery('#charla').prop("scrollHeight"));		
	}
};
function refrescarusuariosconectados(r){
	total = r[0].cantidad;
	if(!r[0].success){
		jQuery('#usuarios_rem').empty(); 
		jQuery('#totalconectados_rem').empty();
		jQuery('#totalconectados_rem').append('Conectactos 0');
		//jQuery('#usuarios_rem').scrollTop(jQuery('#usuarios_rem').prop("scrollHeight"));
	}else{
		jQuery('#totalconectados_rem').empty();
		jQuery('#totalconectados_rem').append('Conectactos ' + total);
		if(vcantusuconec != total){
			vcantusuconec = total;
			var prevState1 = '';
		    jQuery('#usuarios_rem').html('');
			for(i=0;i<total;i++){
				prevState1 = jQuery('#usuarios_rem').html();
				vidUC = r[i].iduconectados;
				jQuery('#usuarios_rem').html(prevState1 + r[i].usuarios);
				jQuery('#usuarios_rem').scrollTop(jQuery('#usuarios_rem').prop("scrollHeight"));							
			}
		}
	}
};
function refrescarusuarionuevo(r){
	var entro = false;
	jQuery('.clsusuariosrem').each(function(idx, el) {
		if(jQuery(el).attr('id')==jQuery(r.usuarios).attr('id')){
	   		entro = true;	   		
	   	} 
	});
	if(!entro){
		jQuery('#usuarios_rem').append(r.usuarios);
		jQuery('#usuarios_rem').scrollTop(jQuery('#usuarios_rem').prop("scrollHeight"));
		total++;
		jQuery('#totalconectados_rem').empty();
		jQuery('#totalconectados_rem').append('Conectados ' + total);		
	}		
};
function desconectarusuario(r){	
	var entro = false;
	jQuery('.clsusuariosrem').each(function(idx, el) {
		if(jQuery(el).attr('id')==jQuery(r.usuarios).attr('id')){			
	   		entro = true;
	   		var idel = jQuery(el).attr('id');
			jQuery('#'+idel).remove('.clsusuariosrem');
			jQuery('#usuarios_rem').scrollTop(jQuery('#usuarios_rem').prop("scrollHeight"));
			total--;
			jQuery('#totalconectados_rem').empty();
			jQuery('#totalconectados_rem').append('Conectados ' + total);		
	   	} 
	});
};
function refrescarmejoroferta(r){	
	var vmonto = parseFloat(r.monto);
	valincremento = parseFloat(r.incremento);
	var vmontoincremento = vmonto + valincremento;
	jQuery('#txtoferente').val(r.apeynom);
	jQuery('#txtmontor').val(r.monto);
	if(r.success==true && r.monto>0){
		jQuery('#btnpasarlote').css('display','none');
		jQuery('#btncerrarsubasta').css('display','none');		
		jQuery('#btnbajarmartillo').css('display','block');		
	}else{
		jQuery('#btncerrarsubasta').css('display','none');		
		jQuery('#btnbajarmartillo').css('display','none');		
		jQuery('#btnpasarlote').css('display','none');		
	}
};
function refrescaranularoferta(r){
	if(r.success){
		jQuery('#txtoferente').val(r.apeynommax);
		if(r.apeynommax!=''){
			jQuery('#txtmontor').val(r.monto);
		}else{
			jQuery('#txtmontor').val('');
		}
		vidoferta = r.idoferta;		

		refrescarlistadohistoricoofertas();
	}
};
function refrescarbajarmartillo(r){
	if(r.success){
		jQuery('#btncerrarsubasta').css('display','block');
		jQuery('#btnpasarlote').css('display','none');
		jQuery('#btnbajarmartillo').css('display','none');
	}
};
function refrescarcerrarsubasta(r){
	if(r.success){				
		jQuery('#btncerrarsubasta').css('display','none');
		jQuery('#btnbajarmartillo').css('display','none');
		jQuery('#btnpasarlote').css('display','none');				
		jQuery('#btnabrir').css('display','none');
		jQuery('#btninc1').val('');				
		jQuery('#btninc2').val('');				
		jQuery('#btninc3').val('');
		jQuery('#txtinc').val('');
		jQuery('#incremento_rem').css('display','none');
		jQuery('#tipoprecio').css('display','none');
		jQuery('#precioinicial_rem').css('display','none');
		jQuery('#txtprecioinicial').val('');
		jQuery('#oferta_rem').css('display','none');
		jQuery('p').empty();
		valselabierto = 0;
		montoabrirlote =-1;
		estadoloteabierto = 0;
		vidoferta = 0;
		valincremento = 0;
		jQuery('#txtoferente').val('');
		jQuery('#txtmontor').val('');
		jQuery('#datosloteabiertorem').empty();
		RefreshTable('#dthisotricorem','scripts/listar_ofertas_historico.php?param='+valselabierto);
		RefreshTable('#dtlotes','scripts/buscar_lotes_rem.php');			
	}
};
function refrescarpasarlote(r){
	if(r.success){
		valselabierto = 0;
		montoabrirlote =-1;
		jQuery('#btncerrarsubasta').css('display','none');
		jQuery('#btnpasarlote').css('display','none');
		jQuery('#btnbajarmartillo').css('display','none');
		jQuery('#infolote_rem').empty();
		jQuery('#incremento_rem').css('display','none');
		jQuery('#tipoprecio').css('display','none');
		jQuery('#precioinicial_rem').css('display','none');
		jQuery('#txtprecioinicial').val('');
		jQuery('#txtoferente').val('');
		jQuery('#txtmontor').val('');
		jQuery('#datosloteabiertorem').empty();
		jQuery('#detalle_hacienda_rem').empty();
		jQuery('#oferta_rem').css('display','none');
		RefreshTable('#dtlotes','scripts/buscar_lotes_rem.php');
		RefreshTable('#dtofertas','scripts/listarofertas.php');
		RefreshTable('#dthisotricorem','scripts/listar_ofertas_historico.php?param='+valselabierto);
	}else{
		if(!error5005){
			error5005 = true;
			jQuery('#infolote_rem #mensaje').empty();
			jQuery('#infolote_rem').append('<p id="mensaje" style="color:red;font-size:12px;font-weight:bold;">' + r.detalle + '</p>')
			jQuery('#infolote_rem #mensaje').fadeOut(10000, function(){
				error5005 = false;
			});
		}
	}
};
function refrescaraceptaroferta(r){	
	if(r.success){
		jQuery('#txtoferente').val(r.oferente);
		jQuery('#txtmontor').val(r.monto);
		jQuery('#btnpasarlote').css('display','none');
		jQuery('#btnbajarmartillo').css('display','block');
		vidoferta = r.idoferta;
	}else{
		alert(r.detalle);
	}
};
function refrescartipoprecio(r){
	if(r.success){
		jQuery('#btninc1').val(r.inc1);
		jQuery('#btninc2').val(r.inc2);
		jQuery('#btninc3').val(r.inc3);
		valincremento = parseFloat(r.inc3);
		valincremento = valincremento.toFixed(2);
		jQuery('#txtinc').val(valincremento);
		jQuery('#infolote_rem #pincremento').html('<p id="pincremento">Incremento <strong>' + valincremento + '</strong></p>');
		jQuery('#infolote_rem #pprecioinicio').html('<p id="pprecioinicio">Tipo de Precio <strong>' + r.descripcion + '</strong></p>');	
		socket.send(JSON.stringify(r));
	}else{
		jQuery('#msganularoferta').css('display','block');
		jQuery('#msganularoferta').empty();
		jQuery('#msganularoferta').append('<p>' + r.mensajeop + '</p>');
		jQuery('#msganularoferta').dialog({
			closeOnEscape: true,
		    resizable: false,
		    modal: true, 
		    title: "Tipo de Precio", 
		    height: 180, 
		    width: 270,
		    buttons:{
		    	"Cerrar":function(){
		    		jQuery('#selecttipoprecio').val(r.idtp);
		    		jQuery(this).dialog('close');
		    	}
		    }
		});	
	}
};
function refrescardesarrolloremateinicio(r){
	if(r.aaData[0].success){
		jQuery.each(r.aaData, function(k,v){
			estilo = v.tipo=='ds' ? "color:black;font-weight: bold;" : '';
			jQuery('#desarrollo_sub_rem').append('<span style="' + estilo + '">' + v.detalle + "</span><br>");
			jQuery('#desarrollo_sub_rem').scrollTop(jQuery('#desarrollo_sub_rem').prop("scrollHeight"));	
		});
	}	
};
function refrescardesarrolloremate(r){
	console.log(r);
	if(r.success){
		jQuery('#desarrollo_sub_rem').append('<span style="color:black;font-weight: bold;">' + r.detalle + "</span><br>");
		jQuery('#desarrollo_sub_rem').scrollTop(jQuery('#desarrollo_sub_rem').prop("scrollHeight"));
	}
};
function refrescarlotesinicio(r){
	jQuery('#lote_rem').empty();
	if(r.aaData[0].success){
		jQuery.each(r.aaData, function(k,v){	
	 		jQuery('#lote_rem').append(v.lote);
			jQuery('#lote_rem').scrollTop(jQuery('#lote_rem').prop("scrollHeight"));		
	    });
    }
};
function refrescarchatinicio(r){	
	if(r.aaData[0].success){
		jQuery('#charla').empty();
		jQuery.each(r.aaData, function(k,v){	
			jQuery('#charla').append('<span class="usuario" style="color:' + v.color + ';">' + v.usre + '</span>' + ' : ' + v.hora + ': ' + v.mensaje + '<br>');
			jQuery('#charla').scrollTop(jQuery('#charla').prop("scrollHeight"));		
		});	
	}
};
function refrescarprecioinicio(r){
	if(r.success)
		jQuery('#infolote_rem #pprecioinicio').html('Precio Inicial <strong>' + r.precioinicio + '</strong>');
};
function refrescarincremento(r){
	if(r.incremento>0)
		jQuery('#infolote_rem #pincremento').html('Incremento <strong>' + parseFloat(r.incremento).toFixed(2) + '</strong>');
};
function refrescarloteabierto(r){
 	if(r.success){
 		if(r.idlote>0){ 			
     		BuscarDatosLoteSel(r);
			inicioTabla();
			inicioTablaHistoricoOfertas();
			estadoloteabierto = r.estado;
 		}else{
     		jQuery('#txtuo_op').val('');
     		jQuery('#txtmonto_op').val('');			     		
     		jQuery('#infolote_op').empty();
 			entro = 0;
 		}
 	}
};
function conectarcliente(){
	/*
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
	if(!conectado){
		iniciar();
		init();
	}
};
function iniciar(){
	jQuery.ajax({
		type:"POST",
	    url: "scripts/subasta.php",
	    dataType : 'json',
	    success : function(r){	    	
	    	res = r;
	    	//console.log(r);
			refrescarchatinicio(r['chatinicio']);
	    	refrescarusuariosconectados(r['usuariosconectados']);
	    	refrescardesarrolloremateinicio(r['desarrolloremateop']);
	    	refrescarloteabierto(r['lote']);
		    refrescarprecioinicio(r['lote']);		    
		    refrescarmejoroferta(r['mejoroferta']);	

	    },
	    error:function(x,t,e){
	    	console.log(e);
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
			var obj;
			if(msg.data!='')
				obj = jQuery.parseJSON(msg.data);
						
			if(typeof obj == 'undefined'){
				accion = -1;
			}else{
				if(typeof obj[0] == 'undefined'){
					accion = obj.accion;
				}else{
					accion = obj[0].accion;
				}
			}
			if(typeof accion==='undefined')
				accion = 0;

			console.log('accion ' + accion);			
			switch(accion) {
				case 1100:			    	
			    	refrescarchat(obj);
			        break;									
				case 1201:
					refrescarusuarionuevo(obj);
			        break;
				case 1210:
					refrescarusuarionuevo(obj);
					break;
			    case 2001:
			    	refrescarlistadoofertas();
			        break;
			    case 2004:
			    	refrescarmejoroferta(obj);
			        break;
			    case 2005:
			    	refrescarmartillo(obj);
			        break;
			    case 2006:
			    	refrescarcerrarsubasta(obj);
			    	break;
			    case 2009:
			    	refrescartipoprecio(obj);
			    	break;
			    case 2010:
			    	refrescarincremento(obj);
			    	break;			    	
			    case 2011:
			    	refrescarprecioinicio(obj);
			    	break;	
			    case 4001:
			    	refrescarmejoroferta(obj);
			    	refrescarlistadohistoricoofertas();
			        break;				    	
			    case 4400:
			    //VAMOS A VER QUE HACE ACA, EN REALIDAD ACA obj TRAE LOS DATOS DE DESARROLLO SUB
			    	refrescarchat(obj);
			        break;				    
			    case 5003:
			    	RefreshTable('#dtlotes','scripts/buscar_lotes_rem.php');
			        break;
			    case 5005:
			    	refrescarpasarlote(obj);
			        break;			    
			    case 5007:
			    	refrescarbajarmartillo(obj);
			        break;			    
			    case 5008:
			    	refrescarcerrarsubasta(obj);
			        break;			    
			    case 5012:
			    	refrescarincremento(obj);
			    	break;		
			    case 5013:
			    	refrescartipoprecio(obj);
			        break;
			    case 9007:
			    	refrescardesarrolloremate(obj);
			        break;
			    case 9999:
			    	desconectarusuario(obj);
			    	conectarcliente();
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