var vidremate=0;
var vidlote=0;
var faltandatoslote = false;
var faltandatosremate = false;
var ordenlote = false;
var arr = new Array(); 
var hl = {ven:'',cat:'',can:0, id:0};
var arr1 = new Array();
var totallote = 0;
var accion = '';
var oTableLotes;
jQuery(document).ready(function(){
	  jQuery.ajax({
        type:"POST",                
        url: "scripts/listar_datos_hacienda.php",
        dataType:'json',
        success: function(r){
          
        }
    });
	var oTableRemates = inicioTablaRemates();
  jQuery("#dtdatoshl tbody .positive-integer").numeric({ decimal: false, negative: false }, function() { alert("Positive integers only"); this.value = ""; this.focus(); });
	
  jQuery("#dtdatoshl tbody").on('keypress', '.positive-integer', function(e){
    var charCode = (e.which) ? e.which : e.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57))
      return false;
   
    return true;
  });
  jQuery("#dtdatoshl tbody").on('blur', '.positive-integer', function(e){    
    var vid = jQuery(this).attr('id');
    totallote=0;
    for (var i = arr1.length - 1; i >= 0; i--) {      
      if(arr1[i].id==vid) {
        if(parseInt(arr1[i].can) >= parseInt(jQuery(this).val()) && jQuery(this).val()!=''){
          arr1[i].canl=jQuery(this).val();        
        }else{          
          jQuery(this).val(arr1[i].canl);
        }
      }
      totallote += parseInt(arr1[i].canl);
    };
    jQuery('#cantidad').val(totallote);
  });
  jQuery('#dtdatoshl tbody').on('click', '.clshl', function(){
    var param = jQuery(this).attr('id');    
    for (var i = arr1.length - 1; i >= 0; i--) {
      if(arr1[i].id == param)
        arr1.splice(i,1);
    };
    iniciotabladhll(arr1);
  });
  jQuery('#dtremates tbody').on('click', '.clsremate', function(){
    var param = jQuery(this).attr('id').substring(1);        
		vidremate = param;
    var param1 = jQuery(this).attr('id').substring(0,1);
    var dremate = jQuery(this).closest('tr').find('td:eq(1)').text() + ' del día ' +
     jQuery(this).closest('tr').find('td:eq(2)').text() + ' a las ' +
     jQuery(this).closest('tr').find('td:eq(3)').text();
    if(param1=='m' || param1=='e'){
        frmabmremate(param,param1);
    }
    if(param1=='d' || param1=='h'){
        jQuery.ajax({
            type:"POST",        
            data:{accion:10000,param:param,param1:param1},
            url: "scripts/subasta.php",
            dataType:'json',
             success: function(r){
              if(r.mensaje=='OK'){
                RefreshTable('#dtremates', 'scripts/listar_remates.php');
                refrescarremateabierto(r);
              }
              if(r.mensaje!='OK'){
                  alert(r.mensaje);
              }              
            }
        });
    }
    if(param1=='l'){
      jQuery('#btnaddhaciendalote').addClass('disabled');
      jQuery('#btnaddhaciendalote').attr('disabled','disabled');        
      jQuery('#btnaddlote').removeClass('disabled');
      jQuery('#btnaddlote').removeAttr('disabled');      
      jQuery('#tblloteModal').modal();
      inicioTablaLotes(param);
      inicioTablaDatosHacienda();
    }
    if(param1=='p' || param1=='n'){
      jQuery.ajax({
          type:"POST",        
          data:{param : param, param1 : param1},
          url: "scripts/publicar_remate.php",
          dataType:'json',
           success: function(r){
            if(r.idremate > 0){
              RefreshTable('#dtremates', 'scripts/listar_remates.php');
            }else{
              if(r.idremate==-1){
                alert('No existen lotes para el remate seleccionado');
              }
            }
          }
      });
    }
    if(param1=='c'){
      jQuery('#mensajemail').html('Desea enviar las invitaciones del remate ' + dremate + '?')
      jQuery('#mailmodal').modal('show');
    }
	});
	jQuery('#btnaddremate').on('click', function(){
    vidremate = 0;
		frmabmremate(0,'a');
	});
	jQuery('#btnremategrabar').on('click', function(){
    if(controlardatosremate()){
      alert('Faltan Datos');
    }else{ 
  		var datos = jQuery('#formremate').serialize();    
  		jQuery.ajax({
              type:"POST",        
              data:datos,
              url: "scripts/abmremate.php",
              dataType:'json',
               success: function(r){                
                  RefreshTable('#dtremates', 'scripts/listar_remates.php');                
                  jQuery('#remateModal').modal('hide');
              }
          });
    }
	});
  jQuery('#btnaddlote').on('click', function(){
    limpiarcamposlote();        
    buscarultimolote();
    accion = 'a';
    jQuery('#loteModal').modal();
  });
  jQuery('#dtlotes tbody').on('click', '.clslote', function(){
    var param = jQuery(this).attr('id').substring(1);
    vidlote = param;
    var param1 = jQuery(this).attr('id').substring(0,1);
    accion = param1;
    var dremate = jQuery(this).closest('tr').find('td:eq(1)').text() + ' - ' +
     jQuery(this).closest('tr').find('td:eq(2)').text() + ' - ' +
     jQuery(this).closest('tr').find('td:eq(3)').text();
    if(param1=='e'){
      listadatoshaciendalote(param, param1);
      jQuery('#btnlotegrabar').html('Eliminar lote');
      jQuery('#loteModal').modal();        
    }
    if(param1=='d' || param1=='h'){
        jQuery.ajax({
            type:"POST",        
            data:{param : param, param1 : param1},
            url: "scripts/habilitar_lote.php",
            dataType:'json',
            success: function(r){
              if(r.success){
                RefreshTable('#dtlotes', 'scripts/listar_lotes.php');
              }else{
                if(r.idlote==-1){
                  alert('No existe hacienda para el lote seleccionado');
                }
              }
            }
        });        
    }    
    if(param1=='l'){
      jQuery('#abmremate').css('display','none');
      listalotes(param, dremate);
    }
    if(param1=='x'){
      listadatoshaciendalote(param, param1);
      jQuery('#loteModal').modal();
    }
    if(param1=='c'){
      jQuery.ajax({
            type:"POST",        
            data:{param : param, param1 : param1},
            url: "scripts/cambiar_estado_lote.php",
            dataType:'json',
            success: function(r){
                RefreshTable('#dtlotes', 'scripts/listar_lotes.php');
            }
        });  
    }
    if(param1=='b' || param1=='s'){
      jQuery.ajax({
        type:'post',
        url:'scripts/cambiar_orden_lote.php',
        data:{param:param,param1:param1},
        dataType:'json',
        success:function(r){
          if(r.success){
            jQuery('#dtlotes').DataTable().ajax.reload();
            
            //RefreshTable('#dtlotes', 'scripts/listar_lotes.php');
          }
        }
      });
    }
  });
  jQuery('#dtdatoshacienda tbody').on('change', '.clshaciendalote', function(){
    var param = jQuery(this).attr('id').substring(0,4);
    var param1 = jQuery(this).attr('id').substring(4);
    if(param=='addh'){
      if(arr.indexOf(param1)!=-1){      
        arr = jQuery.grep(arr, function(value) {
          return value != param1;
        });
      }else{
        arr.push(param1);        
      }
      var n = jQuery("#dtdatoshacienda tbody tr input:checked").length;

      if(n==0){
        jQuery('#btnaddhaciendalote').addClass('disabled');
        jQuery('#btnaddhaciendalote').attr('disabled','disabled');        
        jQuery('#btnaddlote').removeClass('disabled');
        jQuery('#btnaddlote').removeAttr('disabled');
      }else{        
        jQuery('#btnaddhaciendalote').removeClass('disabled');
        jQuery('#btnaddhaciendalote').removeAttr('disabled');        
        jQuery('#btnaddlote').addClass('disabled');
        jQuery('#btnaddlote').attr('disabled','disabled');
      }
      
    }
  });
  jQuery('#btnaddhaciendalote').on('click', function(){    
    jQuery('#divtablahl').addClass('col-xs-8');
    if(vidlote==0){
      accion = 'a';
      arr1 = new Array();
      limpiarcamposlote();
      buscarultimolote();      
    }
    jQuery("#dtdatoshacienda tbody tr").each(function (index){
        var campo1, campo2, campo3, campo4, campo5;
        jQuery(this).children("td").each(function(index2){
          switch(index2){
            case 1: campo1 = jQuery(this).text();
                    break;
            case 2: campo2 = jQuery(this).text();
                    break;
            case 10: campo5 = jQuery(this).text();
                    break;
            case 12: campo3 = jQuery(this).text();
                    break;
            case 13: campo4 = jQuery(this)[0].firstChild.id.substring(4);
                    break;
          }
        });
        if(arr.indexOf(campo4)!=-1){
          var hl = {ven:'',cat:'',can:0, id:0,precioinicio:0,canl:0};
          hl.ven = campo1;
          hl.cat = campo2;
          hl.can = campo3;
          hl.id = campo4;
          hl.canl = campo3;
          hl.precioinicio = campo5;
          arr1.push(hl);
        }            
    });
    if(arr1.length>0) iniciotabladhll(arr1);
    jQuery('#loteModal').modal();
  });
  jQuery('#btnlotegrabar').on('click', function(){
    if(accion!='e'){
      if(arr1.length>0){        
        var datos = jQuery('#formlotes').serialize();
        datos += '&param='+accion;
        datos += '&arr='+ JSON.stringify(arr1);
        var ret = controlardatoslote(datos);
        if(!ret){
          abmlotes(datos);
        }else{
          //mensaje error
        }
      }else{
        jQuery('#titulo').html('Agregar Lote');
        jQuery('#mensaje').html('No ingresó precio de inicio<br>Desea agregar el lote?');
        jQuery('#msgmodal').modal();
      }
    }else{
      var datos = 'param='+accion;
      abmlotes(datos);        
    }
  });
  jQuery('#btnmsgsi').on('click', function(){
      var datos = jQuery('#formlotes').serialize();
      datos += '&param=a';
      var ret = controlardatoslote(datos);
      if(!ret){        
        jQuery('#msgmodal').modal('hide');
        abmlotes(datos);
      }else{
        //mensaje error
      }
  });
  jQuery('#btnaddhacienda').on('click', function(){
    accion = 'a';
    jQuery('#remateModal').modal();
  });
  jQuery('#btnlotecerrar').on('click',function(){
    jQuery('#loteModal').modal('hide');
  });
  jQuery('#loteModal').on('hide.bs.modal', function(){
    jQuery('#btnaddhaciendalote').addClass('disabled');
    jQuery('#btnaddhaciendalote').attr('disabled','disabled');
    jQuery('#btnaddlote').removeClass('disabled');
    jQuery('#btnaddlote').removeAttr('disabled','disabled');
    jQuery('#btnlotegrabar').html('Grabar');
    RefreshTable('#dtdatoshacienda','scripts/listar_datos_hacienda.php');
    RefreshTable('#dtlotes', 'scripts/listar_lotes.php');
  });
  jQuery('#btnagregarhacienda').on('click', function(){
    jQuery('#loteModal').modal('hide');
  });
  jQuery('#btnreordenar').on('click', function(){
    var l=[];   
    jQuery('.clsinputlote').each(function(){
      var rl = {r:jQuery(this).val(),l:jQuery(this).attr('id').substring(3)};
      l.push(rl);
    });
    jQuery.ajax({
        type:"POST", 
        data:{param:l},
        url: "scripts/reordenar_lotes.php",
        dataType:'json',
        success: function(r){
            RefreshTable('#dtlotes', 'scripts/listar_lotes.php');
        }
    });
  });
  jQuery('#btnpublicar').on('click', function(){
    jQuery.ajax({
        type:"POST",        
        url: "scripts/publicar_lotes.php",
        dataType:'json',
        success: function(r){
            RefreshTable('#dtlotes', 'scripts/listar_lotes.php');
        }
    });
  });
  jQuery('#btnimprimircc').on('click',function(){
    jQuery.ajax({
        type:'post',
        url:'scripts/imprimircc.php',
        dataType:'json',
        success:function(r){
            if(r.success){
                window.open(r.url, '_blank');
            }
        }
    });    
  });
  jQuery('#btnimprimircr').on('click',function(){
    jQuery.ajax({
        type:'post',
        url:'scripts/imprimircr.php',
        dataType:'json',
        success:function(r){
            if(r.success){
                window.open(r.url, '_blank');
            }
        }
    });
  });
  jQuery('#btnmailsi').on('click', function(){
    jQuery.ajax({
      type:'post',
      data:{param:vidremate},
      url:'scripts/enviar_mail.php',
      dataType:'json',
      success:function(r){
        jQuery('#mensajeenviomail').append('<div class="alert alert-success"> <strong> Se han enviado los mensajes con éxtio</strong></div>');        
        jQuery('#mailmodal').modal('hide');
      }
    });
  });
});
function inicioTablaRemates(){
    oTableRemates = jQuery('#dtremates').DataTable({
        "processing": true,
        "destroy":true,
        "responsive": true,
        "ajax": "scripts/listar_remates.php",
        "deferRender": true,        
        "scrollY": 266,
        "paging": false,
        "scrollCollapse": true,
        "dom": '<"top">rt<"bottom"><"clear">',
        "ordering": false,
        "columns": [
          {data:"nro", title: "Nro"},
          {data:"titulo", title: "Titulo"},
          {data:"fecha", title: "Fecha"},
          {data:"hora", title: "Hora"},
          {data:"cabezas", title: "Cabezas"},
          {data:"tipo", title: "Tipo"},
          {data:"boton1", title: ""},
          {data:"boton2", title: ""},
          {data:"boton3", title: ""},
          {data:"boton4", title: ""},
          {data:"boton5", title: ""},
          {data:"boton6", title: ""}
        ],
        "language":{
            "sProcessing":     "Procesando...",
            "sZeroRecords":    "No se encontraron remates",
            "sEmptyTable":     "No se encontraron remates"
        }
    });    
    return oTableRemates;
};
function frmabmremate(param, param1){
  limpiarcamposremate();
	if(param1!='a'){
		BuscarRemate(param, param1);
	}
  if(param1=='e'){
    jQuery('#btnremategrabar').html('Eliminar Remate');
    jQuery('#btnremategrabar').removeClass('btn-default');
    jQuery('#btnremategrabar').addClass('btn-danger');
  }
	jQuery('#remateModal').modal();
	return false;
};
function controlardatosremate(){
  var classerror = 'has-error has-feedback';
  var ret = false; 
  jQuery('#nro').removeClass(classerror);
  jQuery('#tiporemate').removeClass(classerror);
  jQuery('#titulo').removeClass(classerror);
  jQuery("#fecha").removeClass(classerror);
  jQuery('#hora').removeClass(classerror);

  if(jQuery('#nro').val()==''){jQuery('#txtnro').addClass(classerror);ret=true;}
  if(jQuery('#titulo').val()==''){jQuery('#txttitulo').addClass(classerror);ret=true;}
  if(jQuery("#fecha").val()==''){jQuery("#txtfecha").addClass(classerror);ret=true;}
  if(jQuery('#hora').val()==''){jQuery('#txthora').addClass(classerror);ret=true;}
  if(jQuery('#tiporemate').val() < 0 && jQuery('#tiporemate').val() > 3){jQuery('#tiporemate').addClass(classerror);ret=true;}
  var cantidad = 0;
  for(var i=1;i<6;i++){
    if(!jQuery('#chkconcepto'+i).prop('checked')){
      cantidad++;
    }    
    if(cantidad==5){      
      jQuery('.lblconcepto').addClass(classerror);
    }
  }
  return ret;
};
function BuscarRemate(param, param1){
	jQuery.ajax({
        type:"POST",        
        data:{param : param, param1 : param1},
        url: "scripts/buscar_remate.php",
        dataType:'json',
        success: function(r){
	        jQuery('#nro').val(r.numero);
          jQuery('#tiporemate').selectpicker('val',r.tipo);
          jQuery('tiporemate').selectpicker('refresh');
	        jQuery('#titulo').val(r.titulo);
	        jQuery('#fecha').val(r.fecha);
          jQuery('#hora').val(r.hora);	        
	        jQuery('#cabezas').val(r.cabezas);
          for(i=0;i<r.conceptos.length;i++){
            jQuery('#chkconcepto'+r.conceptos[i].idconcepto).prop('checked', true);
          }
	        jQuery('#comentarios').val(r.comentarios);            
        }
    });
};
function inicioTablaDatosHacienda(){
  oTableDatosHacienda = jQuery('#dtdatoshacienda').DataTable({
        "processing": true,
        "destroy":true,
        "responsive": true,
        "ajax": "scripts/listar_datos_hacienda.php",
        "deferRender": true,        
        "scrollY": 166,
        "paging": false,
        "scrollCollapse": true,
        "dom": '<"top">rt<"bottom"><"clear">',
        "ordering": false,
        "columns": [
          {data:"nrocontrato", title: "Nro Cont"},
          {data:"apeynom", title: "Vendedor"},
          {data:"categoria", title: "Cat"},
          {data:"razatipo", title: "Raza/Tipo"},
          {data:"pelaje", title: "Pelaje"},
          {data:"cantidad", title: "Cab"},
          {data:"trazados", title: "Traz"},
          {data:"destetados", title: "Dest"},
          {data:"marcaliquida", title: "ML"},
          {data:"peso", title: "Peso"},
          {data:"precioinicial", title: "Precio"},
          {data:"tipoprecio", title: "Tipo"},
          {data:"resto", title: "Resto"}, 
          {data:"boton", title: ""}
        ],
        "language":{
                "sProcessing":     "Procesando...",
                "sZeroRecords":    "No se encontraron animales",
                "sEmptyTable":     "No se encontraron animales"
            }
    });  
  return oTableDatosHacienda;
};
function inicioTablaLotes(param){
  oTableLotes = jQuery('#dtlotes').DataTable({
        "processing": true,
        "destroy":true,
        "responsive": true,
        "ajax": "scripts/listar_lotes.php",
        "deferRender": true,
        "paging": false,
        "scrollCollapse": true,
        "dom": '<"top">rt<"bottom"><"clear">',
        "ordering": false,
        "scrollY": 166,
        "columns": [
          {data:"boton1", title: "","width": "1%"},
          {data:"nrolote", title: "Nro","width": "5%"},
          {data:"cabezas", title: "Cabezas","width": "10%"},
          {data:"detalle", title: "Detalle","width": "34%"},
          {data:"tipoprecio", title: "Tipo Precio","width": "19%"},
          {data:"entrega", title: "Entrega","width": "10%"},
          {data:"precio", title: "Precio","width": "5%"},
          {data:"estado", title: "Estado","width": "10%"},
          {data:"boton2", title: "","width": "1%"},
          {data:"boton3", title: "","width": "1%"},
          {data:"boton4", title: "","width": "1%"},
          {data:"boton5", title: "","width": "1%"},
          {data:"boton6", title: "","width": "1%"},
          {data:"boton7", title: "","width": "1%"} 
        ],          
        "fnServerParams": function ( aoData ) {
                aoData.push({ "name": "param", "value": param});
        },        
        "language":{
                "sProcessing":     "Procesando...",
                "sZeroRecords":    "No se encontraron lotes",
                "sEmptyTable":     "No se encontraron lotes"
            }
    });  
  return oTableLotes;
};
function listalotes(param, dremate){
  inicioTablaLotes(param);
  return false;
};
function buscarultimolote(){
  jQuery.ajax({
    url:'scripts/buscar_lote.php',
    type:'post',
    dataType:'json',
    success:function(r){
      if(r.idlote>0){
        jQuery('#nrolote').val(r.idlote);
      }
    }
  });
};
function abmlotes(param){
  jQuery.ajax({
    data:param,
    type:'post',
    url:'scripts/abmlote.php',
    dataType:'json',
    success:function(r){
      if(r.success){
        jQuery('#btnaddhaciendalote').addClass('disabled');
        jQuery('#btnaddhaciendalote').attr('disabled','disabled');
        jQuery('#btnaddlote').removeClass('disabled');
        jQuery('#btnaddlote').removeAttr('disabled');
        jQuery('#loteModal').modal('hide');
      }else{
        alert(r.mensaje);
      }

    }
  });  
  return false;
};
function controlardatoslote(p){
  var arrret = {'ssnrolote':false,'sstipoentrega':false,'sstipoprecio':false};
    
    arr = p.split('&');    

    arrret.ssnrolote = arr[0].split('=')[0] != 'nrolote' ? false : arr[0].split('=')[1]=='' ? false : arr[0].split('=')[1].match(/^\d+$/g) == null ? false : true;    
    arrret.sstipoentrega = arr[1].split('=')[0] != 'tipoentrega' ? false : arr[1].split('=')[1]=='' ? false : arr[1].split('=')[1].match(/^\d+$/g) == null ? false : true;    
    arrret.sstipoprecio = arr[2].split('=')[0] != 'tipoprecio' ? false : arr[2].split('=')[1]=='' ? false : arr[2].split('=')[1].match(/^\d+$/g) == null ? false : true;
    return mostrarerrorlote(arrret);
};
function mostrarerrorlote(r){
  var classerror = 'has-error has-feedback';
  var ret = false;  
  limpiarerroreslote();
  if(!r.ssnrolote){jQuery('#fnrolote').addClass(classerror); ret = true; }
  if(!r.sstipoentrega){jQuery('#ftipoentrega').addClass(classerror); ret = true; }
  if(!r.sstipoprecio){jQuery('#ftipoprecio').addClass(classerror); ret = true; }
 
  return ret;
};
function limpiarerroreslote(){
  var ok = 'has-error has-feedback';
  jQuery('#fnrolote').removeClass(ok); 
  jQuery('#ftipoentrega').removeClass(ok); 
  jQuery('#ftipoprecio').removeClass(ok); 
};
function BuscarDatosLote(plotesel, param1){
  jQuery.ajax({
    type:"POST",
      data:{'param' : plotesel, 'param1' : param1},
      url: "scripts/buscar_lote.php",
      dataType:'json',
      success: function(r){
        if(r.success){
          if(r.sql=='nuevo'){
            jQuery('#nrolote').val(r.idlote);
            jQuery('#tipoentrega').focus();
          }else{
            jQuery('#nrolote').val(r.nro);
            jQuery('#tipoentrega').selectpicker('val',r.tipoentrega);
            jQuery('#tipoentrega').selectpicker('refresh');
            jQuery('#selecttipoprecio').selectpicker('val',r.idtp);
            jQuery('#selecttipoprecio').selectpicker('refresh');
            jQuery('#precioinicio').val(r.precioinicio);
          }
          jQuery('#loteModal').modal();
        }
      }
  });
};
function iniciotabladhll(param){  
  var html = '';
  totallote=0;
  for (var i=0; i < param.length; i++) {
    totallote += parseInt(param[i].canl);
    if((i % 2) == 0){
      html += '<tr class="odd" role="row">';
      html +='<td>' + param[i].ven + '</td>';
      html +='<td>' + param[i].cat + '</td>';
      html +='<td>' + param[i].can + '</td>';
      html +='<td>' + param[i].precioinicio + '</td>';
      html +='<td><input type="text" class="form-control input-sm positive-integer" style="width:50px;" maxlength="3" id="' + param[i].id + '" value="' + param[i].canl + '"></td>';
      html +='<td><input type="image" src="images/seleccionar.png" class="clshl" id="' + param[i].id + '"></td>';
      html +='</tr>';
    }else{
      html += '<tr class="even" role="row">';
      html +='<td>' + param[i].ven + '</td>';
      html +='<td>' + param[i].cat + '</td>';
      html +='<td>' + param[i].can + '</td>';
      html +='<td>' + param[i].precioinicio + '</td>';
      html +='<td><input type="text" class="form-control input-sm positive-integer" style="width:50px;" maxlength="3" id="' + param[i].id + '" value="' + param[i].canl + '"></td>';
      html +='<td><input type="image" src="images/seleccionar.png" class="clshl" id="' + param[i].id + '"></td>';
      html +='</tr>';
    }
  }
  jQuery('#dtdatoshl tbody').html(html);
  jQuery('#cantidad').val(totallote);
};
function limpiarcamposremate(){  
  jQuery('#fecha').datepicker({
    dateFormat: 'dd/mm/yy',
    changeMonth: true,
    changeYear: true
    }).inputmask('dd/mm/yyyy');
  jQuery('#nro').val('');
  jQuery('#titulo').val('');
  jQuery('#fecha').val('');
  jQuery('#hora').datepicker({
    dateFormat: 'hh:mm',
    changeMonth: true,
    changeYear: true
    }).inputmask('hh:mm');  
  jQuery('#hora').val('');
  jQuery('#cabezas').val('');
  jQuery('#chkconcepto1').prop('checked', false);
  jQuery('#chkconcepto2').prop('checked', false);
  jQuery('#chkconcepto3').prop('checked', false);
  jQuery('#chkconcepto4').prop('checked', false);
  jQuery('#chkconcepto5').prop('checked', false);
  jQuery('#comentarios').val('');
  jQuery('#btnremategrabar').html('Grabar');
  jQuery('#btnremategrabar').addClass('btn-default');
  jQuery('#btnremategrabar').removeClass('btn-danger');  
};
function limpiarcamposlote(){
  jQuery('#nrolote').val('');
  jQuery('#tipoentrega').selectpicker('val','');
  jQuery('#tipoentrega').selectpicker('refresh');
  jQuery('#selecttipoprecio').selectpicker('val','');
  jQuery('#selecttipoprecio').selectpicker('refresh');
  jQuery('#precioinicio').val('');
  jQuery('#dtdatoshl tbody').html('');
  jQuery('#btnlotegrabar').removeClass('btn-danger');
  jQuery('#btnlotegrabar').addClass('btn-default');  
};
function listadatoshaciendalote(param, param1){
  limpiarcamposlote();
  jQuery.ajax({
    data:{param:param,param1:param1},
    type:'post',
    url:'scripts/buscar_hacienda_lote.php',
    dataType:'json',
    success:function(r){
      arr1 = new Array();
      arr1 = r.arrhl;      
      iniciotabladhll(arr1);
      jQuery('#nrolote').val(r.arrl.nro);      
      jQuery('#tipoentrega').selectpicker('val',r.arrl.tipoentrega);
      jQuery('#tipoentrega').selectpicker('refresh');
      jQuery('#tipoprecio').selectpicker('val',r.arrl.tipoprecio);
      jQuery('#tipoprecio').selectpicker('refresh');
      jQuery('#precioinicio').val(r.arrl.precioinicio);
      if(accion=='e'){
        jQuery('#btnlotegrabar').removeClass('btn-default');
        jQuery('#btnlotegrabar').addClass('btn-danger');
      }else{
        jQuery('#btnlotegrabar').removeClass('btn-danger');
        jQuery('#btnlotegrabar').addClass('btn-default');
      }
    }
  });
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
function refrescarremateabierto(r){
  var host = "ws://204.197.252.106:9000/echobot";
  try {
    socket = new WebSocket(host);
    socket.onopen    = function(msg) {
      conectado = true;    
      socket.send(JSON.stringify(r));
    };
  }catch(ex){}
};