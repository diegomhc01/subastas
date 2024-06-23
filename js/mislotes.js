jQuery(document).ready(function(){
	inicioMisLotes('T');

	jQuery('.btndlinfo').on('click', function(e){
		e.preventDefault();
		alert(jQuery(this).attr('id'));		
	});

	/*
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
		    		console.log(r);
					jQuery('#txtnrolote').val(r.idlote);
					jQuery('#txtcabezas').val(r.cantcabezas);
					jQuery('#txttrazado').val(r.trazados);
					jQuery('#txtcategoria').val(r.categoria);
					jQuery('#txttipotntrega').val(r.tipoentrega);
					jQuery('#txtmarcaliquida').val(r.marcaliquida);
					jQuery('#txtedad').val(r.edad);
					jQuery('#txtrazatipo').val(r.razatipo);
					jQuery('#txtpelaje').val(r.pelaje);
					jQuery('#txtdestetados').val(r.destetados);
					jQuery('#txtalimentacion').val(r.alimentacion);
					jQuery('#txtobservaciones').val(r.observaciones);
					r.imagen.length > 4 ? cantidad = 4 : cantidad = r.imagen.length;
					var j=0;
					for(i=0;i<cantidad;i++){
						jQuery('#foto' + i).attr('src', r.imagen[i].imagen);
						j=i;
					}
					jQuery('#idiframe').attr('src','https://www.youtube.com/embed/' + r[j].video);
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
				height: 750, 
				width: 850,
				buttons:{										
					"Cerrar": function() {
						jQuery(this).dialog( "close" );
					}
			}
		});		
	});
	*/


});
function inicioMisLotes(param){ 
	jQuery('#detallelotes').empty();
	jQuery.ajax({
		type : 'POST',
		data : {param : param},
		url : "scripts/listar_mis_lotes.php",
		dataType : 'json',
		success : function(r){
			for(i=0;i<r.length;i++){
				jQuery(r[i]).appendTo('#detallelotes');
			}
		}
	});
};
function BuscarDatosLote(plotesel){
	if(plotesel!=0){
		jQuery.ajax({
			type:"POST",
		    data:{'param' : plotesel,
				'idremate':validremate,
				'texto':'COMIENZA EL REMATE DEL LOTE ' + plotesel},
		    url: "scripts/buscardatoslote.php",
		    dataType:'json',
		    success: function(r){
		    	if(r.success){	
		    		if(entro==0){
						entro = 1;
		    			jQuery('#infolote_cli').empty();
			    		jQuery('#infolote_cli').append('<p>Nro de Lote <strong>' + r.nrolote + '</strong></p>');		    		
			    		jQuery('#infolote_cli').append('<p>Incremento <strong>' + r.incremento + '</strong></p>');
			    		jQuery('#infolote_cli').append('<p>Cantidad de Cabezas <strong>' + r.cantcabezas + '</strong></p>');
			    		jQuery('#infolote_cli').append('<p>Categor\u00EDa <strong>' + r.categoria + '</strong></p>');
			    		jQuery('#infolote_cli').append('<p>Llocalidad <strong>' + r.localidad + '</strong></p>');
			    		jQuery('#infolote_cli').append('<p>Provincia <strong>' + r.provincia + '</strong></p>');		    		
		    		}
				}
		    }		    
		});		
	}
};
function inicioTablaLotesCatalogo(){ 
	//"sDom": '<"top">rt<"bottom"p><"clear">',
       oTableLotesCatalogo = jQuery('#dtlotecatalogo').dataTable({  		
        "bProcessing": false,
        "bServerSide": false,
        "bLengthChange": false, 
        "bAutoWidth": false,
        "bPaginate": false,
        "bSort":false,
        "bJqueryUI":false,
        "sAjaxSource" : "scripts/catalogo_cli.php",
      	"sDom": '<"clear">', 
      	"aoColumns": [{ "sTitle": "", "sWidth": "580px", "bSortable": false, "bVisible": true },
      				  { "sTitle": "", "sWidth": "20px", "bSortable": false, "bVisible": true }],
		"oLanguage": {
            "sEmptyTable":     "No hay datos",
            "sInfo":           "",
            "sInfoEmpty":      "Mostrando desde 0 hasta 0 de 0 registros",
            "sInfoFiltered":   "(filtrado de _MAX_ registros en total)",
            "sInfoPostFix":    "",
            "sInfoThousands":  ",",
            "sLengthMenu":     "Mostrar _MENU_ registros",
            "sLoadingRecords": "Cargando...",
            "sProcessing":     "Procesando...",
            "sSearch":         "Buscar:",
            "sZeroRecords":    "No se encontraron resultados"
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

}
