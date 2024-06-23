var cantidad = 0;
var imagenes = new Array();
var siguiente = 0;
var accion = '';
var datoshacienda = '';
var checked;
var oTableHacienda;
jQuery(document).ready(function(){
  
  controlarcheck();  
  var fileExtension;  
  
  jQuery('#nrocontrato').on('blur', function(){    
    if(accion=='a' || accion=='mo'){
      jQuery.ajax({
        type:"POST",        
        data:{'param' : jQuery(this).val()},
        url: "scripts/buscar_contrato_hacienda.php",
        dataType:'json',
        success: function(r){
          if(r.cantidad > 0 || r.cantidad < 0){
            alert(r.mensaje);
            jQuery('#nrocontrato').focus();
          }else{
            jQuery('#apeynomv').focus();
          }
        }
      });  
    }
  });

  //jQuery('#apeynomv').on('blur', function(){
  jQuery('#apeynomv').on('change', function(){
    if(accion=='a'){      
      if(jQuery(this).selectpicker('val')!=''){
        jQuery.ajax({
          type:"POST",        
          data:{param:jQuery(this).selectpicker('val')},
          url: "scripts/buscar_vendedor.php",
          dataType:'json',
           success: function(r){   
           console.log(r);         
              jQuery('#cuitv').val(r.cuit);
              jQuery('#contactov').val(r.contacto);
              jQuery('#telefonov').val(r.telefono);
              jQuery('#emailv').val(r.email);          
              LlenarComboEstablecimientos(r.idcliente, 0);
              jQuery('#cantidad').focus();

          }
        });
      }else{
        jQuery(this).css('color','red');
      }
    }
  });

	jQuery('#thacienda tbody').on('click', '.clshacienda', function(){    
		var param = jQuery(this).attr('id').substring(2);        
    accion = jQuery(this).attr('id').substring(0,2);
    
    if(accion=='mo'){
        frmabmhacienda(param);
    }
    if(accion=='el'){
      eliminarhacienda(param);
    }
    if(accion=='fo'){ // FOTOS
      imagenes = new Array();
      for(i=0;i<4;i++){
        imagenes[i] = 'img/logob1.jpg';
      }
      jQuery.ajax({
          type:"POST",        
          data:{param : param},
          url: "scripts/buscar_imagenes.php",
          dataType:'json',
          success: function(r){
            console.log(r);
            var j = 0;
            if(r!='undefined'){
              for(i=0;i<r.length-1;i++){
                imagenes[i] = 'fotos/' + r[i].imagen;
                console.log(imagenes[i]);
                j=i;
              }
            }
            CambiaImagen();
            jQuery('#video').val(r[j].video);
            mostrarVideo(r[j].video);           
          }
      });   
      jQuery('#divmultimedia').css('display','block');
      jQuery('#divmultimedia').dialog({ 
        closeOnEscape: true,
        resizable: false,
        modal: true, 
        title: "Fotos", 
        height: 800, 
        width: 800
      });      
    }    
  });
  jQuery('#btnaddhacienda').on('click', function(){    
    accion = 'a';
    limpiarcampos();
    jQuery('#haciendaModal').modal();		
	});
  jQuery('#btngrabarhacienda').on('click', function(){
    var datos = jQuery('#formhacienda').serialize();  
    var datosjson = jQuery('#formhacienda').serializeObject();
    datos = datos + '&param1=' + accion;    
    var falta = false;
    var entro = false;
    if(accion=='a' || accion=='mo'){      
      falta = controlardatoshacienda(datos);
    }
    if(falta){
      alert('Faltan Datos');
    }else{
      if(accion=='a'){
        if(jQuery('#nrocontrato').val()!=''){
          jQuery.ajax({
            type:"POST",        
            data:{'param' : jQuery('#nrocontrato').val()},
            url: "scripts/buscar_contrato_hacienda.php",
            dataType:'json',
            success: function(r){
              if(r.cantidad > 0 || r.cantidad < 0){
                alert(r.mensaje);
                jQuery('#nrocontrato').focus();
                entro = true;
              }
            }
          });
        }
        if(!entro){
          jQuery.ajax({
            type:"POST",        
            data:datos,
            url: "scripts/abmhacienda.php",
            dataType:'json',
            success: function(r){            
              RefreshTable('#thacienda', 'scripts/listar_hacienda.php');
              jQuery('#haciendaModal').modal('hide');
            }
          });
        }
      }      
      if(accion=='mo'){
        jQuery.ajax({
          type:"POST",        
          data:datos,
          url: "scripts/controlar_cantidad_hl.php",
          dataType:'json',
          success: function(r){
            if(r.success){
              datos = datos + '&modifica=' + r.modifica;
              if(r.modifica==0){
                if(r.lotes>0){
                  datos = datos + '&lotes=' + r.lotes + '&cantidadlote=' + r.cantidad;  
                }else{
                  datos = datos + '&cantidadhacienda=' + r.cantidad;  
                }
                jQuery.ajax({
                  type:"POST",        
                  data:datos,
                  url: "scripts/modificar_cantidad_hacienda.php",
                  dataType:'json',
                  success: function(r){                      
                    RefreshTable('#thacienda', 'scripts/listar_hacienda.php');
                    jQuery('#haciendaModal').modal('hide');
                  }
                });                
              }
              if(r.modifica==1){
                if(r.lotes>0){
                  datos = datos + '&lotes=' + r.lotes + '&cantidadlote=' + r.cantidad;  
                }else{
                  datos = datos + '&cantidadhacienda=' + r.cantidad;  
                }
                frmmodificahaciendalote('MODIFICA LA CANTIDAD EN EL LOTE?','MENSAJE', datos);
              }
              if(r.modifica==2 || r.modifica==3){
                frmmodificahaciendalotes('MODIFICA LA CANTIDAD EN LOS LOTES?','MENSAJE', datos, r);                  
              }              
            }              
            jQuery.ajax({
              type:"POST",
              data:datos,
              url: "scripts/abmhacienda.php",
              dataType:'json',
              success: function(r){
                RefreshTable('#thacienda', 'scripts/listar_hacienda.php');                   
                jQuery('#haciendaModal').modal('hide');
              }
            });            
          }
        });
      }
    }      
  }); 
  jQuery('#establecimientoid').on('change', function(){
    jQuery('#provinciah').val('');
    jQuery('#localidadh').val('');
    jQuery('#renspae').val('');
    jQuery('#latitud').val('');
    jQuery('#longitud').val('');
    jQuery.ajax({
      type:"POST",        
        url: "scripts/buscar_datos_establecimiento.php",
        data:{param1: jQuery(this).val(), param2:1},
        dataType:'json',
        success: function(r){
          jQuery('#provinciah').val(r.provincia);
          jQuery('#localidadh').val(r.localidad);
          jQuery('#renspae').val(r.renspa);
          jQuery('#latitud').val(r.lat);
          jQuery('#longitud').val(r.lon);
        }
    });      
  });  
  jQuery(".messages").css('display','none');
  jQuery(".showImage").css('display','none'); 
  jQuery('.chkhacienda').on('click', function(){
    controlarcheck();
  })
  jQuery('#haciendaModal').on('shown.bs.modal', function (){    
    jQuery('#evaluador').focus()
  });

});
function controlarcheck(){
  var datos = jQuery('#formcheck').serialize();
  jQuery.ajax({
    data:datos,
    dataType:'json',
    type:'POST',
    url:'scripts/listarhacienda.php',
    success:function(r){
      inicioTablaHacienda();
    }
  });
};
function inicioTablaHacienda(){
   oTableHacienda = jQuery('#thacienda').DataTable({
        "destroy":true,
        "responsive": true,
        "ajax": "scripts/listar_hacienda.php",
        //"ajax": "scripts/hacienda.txt",
        "deferRender": true,        
        "scrollY": 266,
        "paging": false,
        "scrollCollapse": true,
        "dom": '<"top">rt<"bottom"><"clear">',
        "ordering": false,
        "columns": [
          {data:"nrocontrato", title: "Contrato"},
          {data:"apeynom", title: "Vendedor"},
          {data:"categoria", title: "Cat"},
          {data:"edad", title: "Edad"},
          {data:"razatipo", title: "Raza/Tipo"},
          {data:"pelaje", title: "Pelaje"},
          {data:"cantidad", title: "Cab"},
          {data:"destetados", title: "Dest"},
          {data:"alimentacion", title: "Alim"},
          {data:"precioinicial", title: "Precio"},
          {data:"tipoprecio", title: "Tipo"},
          {data:"modificar", title: ""}, //M
          {data:"eliminar", title: ""}, //E
          {data:"fotos", title: ""}  //VIDEO
        ],
        "language":{
                "sProcessing":     "Procesando...",
                "sZeroRecords":    "No existen datos de hacienda",
                "sEmptyTable":     "No existen datos de hacienda",
            }
    });  
    return oTableHacienda;  
};
function BuscarHacienda(param){
  jQuery.ajax({
        type:"POST",        
        data:{param : param, param1 : accion},
        url: "scripts/buscar_datos_hacienda.php",
        dataType:'json',
        success: function(r){          
          bajardatosahcienda(r);
        }
    });
};
function bajardatosahcienda(r){
  datoshacienda = r;
  limpiarcampos();
  if(r.vendedor.success){
    jQuery('#cuitv').val(r.vendedor.cuit);    
    LlenarComboClientes(r.vendedor.idcliente);
    jQuery('#contactov').val(r.vendedor.contacto);
    jQuery('#telefonov').val(r.vendedor.telefono);
    jQuery('#emailv').val(r.vendedor.email);
  }
  if(r.ce.success){
    jQuery('#renspae').val(r.ce.renspa);
    jQuery('#localidadh').val(r.ce.localidad);
    jQuery('#provinciah').val(r.ce.provincia);
  }
  if(r.hacienda.success){
    LlenarComboEvaluadores(r.hacienda.idevaluador);
    jQuery('#nrocontrato').val(r.hacienda.nrocontrato);
    if(r.vendedor.success)
      LlenarComboEstablecimientos(r.vendedor.idcliente, r.hacienda.idestablecimiento);
    jQuery('#trazados').val(r.hacienda.trazados);
    jQuery('#cantidad').val(r.hacienda.cantidad);
    jQuery('#categoria').selectpicker('val', r.hacienda.categoria);
    jQuery('#categoria').selectpicker('refresh');
    jQuery('#marcaliquida').val(r.hacienda.marcaliquida);
    jQuery('#razatipo').val(r.hacienda.razatipo);
    jQuery('#pelaje').val(r.hacienda.pelaje);
    jQuery('#destetados').selectpicker('val',r.hacienda.destetados);
    jQuery('#destetados').selectpicker('refresh');
    jQuery('#alimentacion').selectpicker('val',r.hacienda.alimentacion);
    jQuery('#alimentacion').selectpicker('refresh');
    jQuery('#mochos').val(r.hacienda.mochos);
    jQuery('#descornados').val(r.hacienda.descornados);
    jQuery('#astados').val(r.hacienda.astados);
    jQuery('#enteros').val(r.hacienda.enteros);
    jQuery('#querato').val(r.hacienda.querato);
    jQuery('#estado').val(r.hacienda.estado);
    jQuery('#edad').val(r.hacienda.edad);
    jQuery('#observaciones').val(r.hacienda.observaciones);      
  }
  if(r.vientre.success){
    jQuery('#preniados').val(r.vientre.preniados);
    jQuery('#conservicio').val(r.vientre.conservicio);
    jQuery('#vacios').val(r.vientre.vacios);
  }
  if(r.servicio.success){
    jQuery('#ia').val(r.servicio.ia);
    jQuery('#natural').val(r.servicio.natural);
    jQuery('#serviciodesde').val(r.servicio.desde);
    jQuery('#serviciohasta').val(r.servicio.hasta);
    jQuery('#garantia').selectpicker('val',r.servicio.garantia);
    jQuery('#garantia').selectpicker('refresh');
  }
  if(r.sanidad.success){
    jQuery('#tuberculosis').selectpicker('val',r.sanidad.tuberculosis);
    jQuery('#tuberculosis').selectpicker('refresh');
    jQuery('#brucelosis').selectpicker('val',r.sanidad.brucelosis);
    jQuery('#brucelosis').selectpicker('refresh');
  }
  if(r.toro.success){
    jQuery('#sangretoro').val(r.toro.sangre);
    jQuery('#razatipotoro').val(r.toro.razatipo);
  }
  if(r.pi.success){
    jQuery('#forma').selectpicker('val',r.pi.forma);
    jQuery('#forma').selectpicker('refresh');
    jQuery('#hora').val(r.pi.hora);
    jQuery('#desbastei').val(r.pi.desbaste);
    jQuery('#promedio').val(r.pi.promedio);
    jQuery('#maximo').val(r.pi.maximo);
    jQuery('#minimo').val(r.pi.minimo);
  }
  if(r.pd.success){
    jQuery('#ubicacion').val(r.pd.ubicacion);
    jQuery('#arreo').val(r.pd.arreo);
    jQuery('#camion').val(r.pd.camion);
    jQuery('#total').val(r.pd.total);
    jQuery('#balanza').selectpicker('val',r.pd.balanza);
    jQuery('#balanza').selectpicker('refresh');
    jQuery('#camions').selectpicker('val',r.pd.lugarcamion);
    jQuery('#camions').selectpicker('refresh');
    jQuery('#observacionesp').val(r.pd.osvervacionesd);
  }
  if(r.evaluacion.success){
    jQuery('#evacalidad').selectpicker('val',r.evaluacion.calidad);
    jQuery('#evacalidad').selectpicker('refresh');
    jQuery('#evaestado').selectpicker('val',r.evaluacion.estadoeva);
    jQuery('#evaestado').selectpicker('refresh');
    jQuery('#evasanidad').selectpicker('val',r.evaluacion.sanidad);
    jQuery('#evasanidad').selectpicker('refresh');
    jQuery('#evauniformidad').selectpicker('val',r.evaluacion.uniformidad);
    jQuery('#evauniformidad').selectpicker('refresh');
    jQuery('#evaobservaciones').val(r.evaluacion.observacioneseva);
  }
  if(r.cv.success){
    jQuery('#plazo').val(r.cv.plazo);
    jQuery('#precioinicial').val(r.cv.precioinicial);
    jQuery('#tipoprecio').selectpicker('val',r.cv.tipoprecio);
    jQuery('#tipoprecio').selectpicker('refresh');
  }
}
function frmabmhacienda(param){  
  BuscarHacienda(param);  
  jQuery('#haciendaModal').modal('show');
  return false;
};
function eliminarhacienda(param){
  jQuery.ajax({
      type:"POST",        
      data:{param:param},
      url: "scripts/controlar_hacienda_el.php",
      dataType:'json',
      success: function(r){
        if(!r.success){  
          jQuery.ajax({
              type:"POST",
              url: "scripts/eliminarhacienda.php",
              dataType:'json',
              success: function(r){
                RefreshTable('#thacienda', 'scripts/listar_hacienda.php');
                jQuery('#haciendaModal').modal('hide');
              }
          });                
        }else{
          msgbox('ELIMINAR HACIENDA', r.mensaje);
        }
      }
  }); 
}
function limpiarcampos(){
  var ok = {'background-color':'#FFFFFF'};
  jQuery('#nrocontrato').val('');
  jQuery('#cuitv').val('');
  jQuery('#renspae').val('');
  jQuery('#contactov').val('');
  jQuery('#establecimientoid').selectpicker('val','');
  jQuery('#establecimientoid').selectpicker('refresh');
  jQuery('#localidadh').val('');
  jQuery('#provinciah').val('');
  jQuery('#telefonov').val('');
  jQuery('#emailv').val('');
  jQuery('#trazados').selectpicker('val','0');
  jQuery('#trazados').selectpicker('refresh');
  jQuery('#cantidad').val('');
  jQuery('#categoria').selectpicker('val', '0');
  jQuery('#categoria').selectpicker('refresh');
  jQuery('#marcaliquida').selectpicker('val','0');
  jQuery('#marcaliquida').selectpicker('refresh');
  jQuery('#razatipo').val('');
  jQuery('#pelaje').val('');
  jQuery('#destetados').selectpicker('val','0');
  jQuery('#destetados').selectpicker('refresh');
  jQuery('#alimentacion').selectpicker('val','3');
  jQuery('#alimentacion').selectpicker('refresh');
  jQuery('#mochos').val('');
  jQuery('#descornados').val('');
  jQuery('#astados').val('');
  jQuery('#enteros').val('');
  jQuery('#querato').val('');
  jQuery('#estado').val('');  
  jQuery('#edad').val('');
  jQuery('#observaciones').val('');
  jQuery('#preniados').val('');
  jQuery('#conservicio').val('');
  jQuery('#vacios').val('');
  jQuery('#ia').val('');
  jQuery('#natural').val('');
  jQuery('#serviciodesde').datepicker({
    dateFormat: 'dd/mm/yy',
    changeMonth: true,
    changeYear: true
    }).inputmask('dd/mm/yyyy');
  jQuery('#serviciodesde').val('');
  jQuery('#serviciohasta').datepicker({
    dateFormat: 'dd/mm/yy',
    changeMonth: true,
    changeYear: true
    }).inputmask('dd/mm/yyyy');  
  jQuery('#serviciohasta').val('');
  jQuery('#garantia').val('0');
  jQuery('#sangretoro').val('');
  jQuery('#razatipotoro').val('');
  jQuery('#forma').selectpicker('val','0');
  jQuery('#forma').selectpicker('refresh');
  jQuery('#hora').val('');
  jQuery('#desbastei').val('');
  jQuery('#promedio').val('');
  jQuery('#maximo').val('');
  jQuery('#minimo').val('');
  jQuery('#ubicacion').val('');
  jQuery('#arreo').val('');
  jQuery('#camion').val('');
  jQuery('#total').val('');
  jQuery('#balanza').selectpicker('val','0');
  jQuery('#balanza').selectpicker('refresh');
  jQuery('#camions').selectpicker('refresh');
  jQuery('#observacionesp').val('');
  jQuery('#evacalidad').selectpicker('val','0');
  jQuery('#evacalidad').selectpicker('refresh');
  jQuery('#evaestado').selectpicker('val','0');
  jQuery('#evaestado').selectpicker('refresh');
  jQuery('#evasanidad').selectpicker('val','0');
  jQuery('#evasanidad').selectpicker('refresh');
  jQuery('#evauniformidad').selectpicker('val','0');
  jQuery('#evauniformidad').selectpicker('refresh');
  jQuery('#evaobservaciones').val('');
  jQuery('#plazo').val('');
  jQuery('#precioinicial').val('');
  jQuery('#tipoprecio').selectpicker('val','0');
  jQuery('#tipoprecio').selectpicker('refresh');
  LlenarComboEvaluadores('');
  LlenarComboClientes('');
  limpiarerrores();
  jQuery('#lievaluador').focus();
};
function sumarcabezas(){
  cantidad = parseInt(jQuery('#mochos').val()) + 
  parseInt(jQuery('#descornados').val()) + 
  parseInt(jQuery('#astados').val()) + 
  parseInt(jQuery('#enteros').val()) + 
  parseInt(jQuery('#querato').val());
  jQuery('#cantidad').val(cantidad);
};
function limpiarerrores(){
  var ok = 'has-error has-feedback';

  jQuery('body').find('a').eq(0).css('color','#337ab7');
  jQuery('body').find('a').eq(1).css('color','#337ab7');
  jQuery('body').find('a').eq(2).css('color','#337ab7');
  jQuery('body').find('a').eq(3).css('color','#337ab7');
  jQuery('body').find('a').eq(4).css('color','#337ab7');
  jQuery('body').find('a').eq(5).css('color','#337ab7');
  jQuery('body').find('a').eq(6).css('color','#337ab7');
  jQuery('body').find('a').eq(7).css('color','#337ab7');
  jQuery('body').find('a').eq(8).css('color','#337ab7');
  jQuery('#fevaluador').removeClass(ok);  
  jQuery('#fapeynomv').removeClass(ok);
  jQuery('#fcuitv').removeClass(ok);
  jQuery('#fcontactov').removeClass(ok);
  jQuery('#ftelefonov').removeClass(ok);
  jQuery('#femailv').removeClass(ok);
  jQuery('#festablecimientoid').removeClass(ok);
  jQuery('#fprovinciah').removeClass(ok);
  jQuery('#flocalidadh').removeClass(ok);
  jQuery('#frenspae').removeClass(ok);
  jQuery('#fcantidad').removeClass(ok);
  jQuery('#fcategoria').removeClass(ok);
  jQuery('#frazatipo').removeClass(ok);
  jQuery('#fpelaje').removeClass(ok);
  jQuery('#fplazo').removeClass(ok);
  jQuery('#fprecioinicial').removeClass(ok);
  jQuery('#ftipoprecio').removeClass(ok);
  jQuery('#fminimo').removeClass(ok);
  jQuery('#fmaximo').removeClass(ok);
  jQuery('#fpromedio').removeClass(ok);
};
function controlardatoshacienda(p){
    
    var arr = [];
    var arr1 = [];
    var cantidad = 0;
    var j = 0;
    var entro = false;
    var arrret = {'ssevaluador':false,'ssapeynomv':false,'ssestablecimientoid':false,
    'sscantidad':false, 'sscategoria':false,'ssrazatipo':false,'sspelaje':false,
    'ssplazo':false, 'sstipoprecio':false, 'ssprecioinicial':false,'ssminimo':false,
    'ssmaximo':false,'sspromedio':false};
    
    arr = p.split('&');

    arrret.ssevaluador = arr[0].split('=')[0] != 'evaluador' ? false : arr[0].split('=')[1]=='' ? false : arr[0].split('=')[1].match(/^\d+$/g) == null ? false : true;    
    arrret.ssapeynomv = arr[2].split('=')[0] != 'apeynomv' ? false : arr[2].split('=')[1]=='' ? false : arr[2].split('=')[1].match(/^\d+$/g) == null ? false : true;    
    arrret.ssestablecimientoid = arr[7].split('=')[0] != 'establecimientoid' ? false : arr[7].split('=')[1]=='' ? false : arr[7].split('=')[1].match(/^\d+$/g) == null ? false : true;
    arrret.sscantidad = arr[11].split('=')[0] != 'cantidad' ? false : arr[11].split('=')[1]=='' ? false : arr[11].split('=')[1].match(/^\d+$/g) == null ? false : true;
    arrret.sscategoria = arr[12].split('=')[0] != 'categoria' ? false : arr[12].split('=')[1]==0  ? false : arr[12].split('=')[1].match(/^\d+$/g) == null ? false : true;    
    arrret.ssrazatipo = arr[13].split('=')[0] != 'razatipo' ? false : arr[13].split('=')[1]==''  ? false :  true;    
    arrret.sspelaje = arr[14].split('=')[0] != 'pelaje' ? false : arr[14].split('=')[1]==''  ? false : true;    
    arrret.ssplazo = arr[26].split('=')[0] != 'plazo' ? false : arr[26].split('=')[1]==''  ? false : true;    
    arrret.ssprecioinicial = arr[27].split('=')[0] != 'precioinicial' ? false : arr[27].split('=')[1]=='' ? false : arr[27].split('=')[1].match(/^\d*\.?\d*$/g) == null ? false : true;
    arrret.sstipoprecio = arr[28].split('=')[0] != 'tipoprecio' ? false : arr[28].split('=')[1]=='0' ? false : arr[28].split('=')[1].match(/^\d+$/g) == null ? false : true;
    arrret.ssminimo = typeof arr[32].split('=')[0] === 'undefined' ? false :  arr[32].split('=')[0] != 'minimo' ? false : arr[32].split('=')[1]=='' ? false : arr[32].split('=')[1].match(/^\d*\.?\d*$/g) == null ? false : true;
    arrret.ssmaximo = typeof arr[33].split('=')[0] === 'undefined' ? false :  arr[33].split('=')[0] != 'maximo' ? false : arr[33].split('=')[1]=='' ? false : arr[33].split('=')[1].match(/^\d*\.?\d*$/g) == null ? false : true;
    arrret.sspromedio = typeof arr[34].split('=')[0] === 'undefined' ? false :  arr[34].split('=')[0] != 'promedio' ? false : arr[34].split('=')[1]=='' ? false : arr[34].split('=')[1].match(/^\d*\.?\d*$/g) == null ? false : true;
    
    return mostrarerrorhacienda(arrret);
};
function mostrarerrorhacienda(r){  
  var classerror = 'has-error has-feedback';
  var ret = false;  
  limpiarerrores();
  if(!r.ssevaluador){ 
    jQuery('body').find('a').eq(0).css('color','#a94442');
    jQuery('body').find('divevaluador').addClass(classerror);
    jQuery('#fevaluador').addClass(classerror);
    ret = true;
  }
  if(!r.ssapeynomv){
    jQuery('body').find('a').eq(1).css('color','#a94442');
    jQuery('#fapeynomv').addClass(classerror);
    jQuery('#fcuitv').addClass(classerror);
    jQuery('#fcontactov').addClass(classerror);
    jQuery('#ftelefonov').addClass(classerror);
    jQuery('#femailv').addClass(classerror);
    ret = true;
  }
  if(!r.ssestablecimientoid){  
    jQuery('body').find('a').eq(2).css('color','#a94442');
    jQuery('#festablecimientoid').addClass(classerror);
    jQuery('#fprovinciah').addClass(classerror);
    jQuery('#flocalidadh').addClass(classerror);
    jQuery('#frenspae').addClass(classerror);
    ret = true;
  }
  if(!r.sscantidad){ jQuery('body').find('a').eq(2).css('color','#a94442'); jQuery('#fcantidad').addClass(classerror);ret = true;}
  if(!r.sscategoria){ jQuery('body').find('a').eq(2).css('color','#a94442'); jQuery('#fcategoria').addClass(classerror);ret = true;}
  if(!r.ssrazatipo){ jQuery('body').find('a').eq(2).css('color','#a94442'); jQuery('#frazatipo').addClass(classerror);ret = true;}
  if(!r.sspelaje){ jQuery('body').find('a').eq(2).css('color','#a94442'); jQuery('#fpelaje').addClass(classerror);ret = true;}
  if(!r.ssplazo){ jQuery('body').find('a').eq(3).css('color','#a94442'); jQuery('#fplazo').addClass(classerror);ret = true;}
  if(!r.sstipoprecio){ jQuery('body').find('a').eq(3).css('color','#a94442'); jQuery('#ftipoprecio').addClass(classerror);ret = true;}
  if(!r.ssprecioinicial){ jQuery('body').find('a').eq(3).css('color','#a94442'); jQuery('#fprecioinicial').addClass(classerror);ret = true;}
  if(!r.ssminimo){ jQuery('body').find('a').eq(4).css('color','#a94442'); jQuery('#fminimo').addClass(classerror);ret = true;}
  if(!r.ssmaximo){ jQuery('body').find('a').eq(4).css('color','#a94442'); jQuery('#fmaximo').addClass(classerror);ret = true;}
  if(!r.sspromedio){ jQuery('body').find('a').eq(4).css('color','#a94442'); jQuery('#fpromedio').addClass(classerror);ret = true;}
  return ret;
};
function seleccionarimagen(file, i){      
      var fileName = file.name;      
      fileExtension = fileName.substring(fileName.lastIndexOf('.') + 1);
      var fileSize = file.size;
      var fileType = file.type;
      showMessage("<span class='info'>Archivo para subir: "+fileName+", peso total: "+fileSize+" bytes.</span>");
};
function showMessage(message){
    jQuery(".messages").html("").show();
    jQuery(".messages").html(message);
};
function isImage(extension){
    switch(extension.toLowerCase()) 
    {
        case 'jpg': case 'gif': case 'png': case 'jpeg':
            return true;
        break;
        default:
            return false;
        break;
    }
};
function LlenarComboEvaluadores(param1){
  jQuery('#evaluador').empty();     
  jQuery('#evaluador').append('<option value="" disabled selected>Seleccione un evaluador</option>');
  jQuery.getJSON('scripts/listar_evaluadores.php',  
    function(data){
        jQuery.each(data.aaData, function(k,v){
            jQuery('#evaluador').append('<option value=' + v.idusuario +  '>' + v.apeynom + '</option>');
        });
        if(param1!='') jQuery('#evaluador').selectpicker('val',param1);
        jQuery('#evaluador').selectpicker('refresh');

    }
  );
};
function LlenarComboEstablecimientos(param1, param2){
  var cant = 0;
  jQuery('#establecimientoid').empty();     
  jQuery('#establecimientoid').append('<option value="" disabled selected>Seleccione un establecimiento</option>');
  jQuery.ajax({
  type:"POST",        
    url: "scripts/listar_establecimientos_select.php",
    data:{param1:  param1},
    dataType:'json',
    success: function(r){     
      if(r!=null){
        jQuery.each(r.aaData, function(k,v){
          cant++;
          jQuery('#establecimientoid').append('<option value=' + v.idestablecimiento +  '>' + v.detalle + '</option>');
        });
        if(param1!='') jQuery('#establecimientoid').selectpicker('val',param2);
        jQuery('#establecimientoid').selectpicker('refresh');

        if(cant==1){
          jQuery.ajax({
            type:"POST",        
            data:{param1 : param1, param2:param2},
            url: "scripts/buscar_datos_establecimiento.php",
            dataType:'json',
            success: function(r){
              jQuery('#establecimientoid').selectpicker('val',param2);
              jQuery('#establecimientoid').selectpicker('refresh');
              jQuery('#provinciah').val(r.provincia);
              jQuery('#localidadh').val(r.localidad);
              jQuery('#renspae').val(r.renspa);
              jQuery('#latitud').val(r.lat);
              jQuery('#longitud').val(r.lon);
            }
          });
        }
      }   
    }
  });  
};
function LlenarComboClientes(param1){  
  jQuery('#apeynomv').empty();     
  jQuery('#apeynomv').append('<option value="" disabled selected>Seleccione un cliente</option>');
  jQuery.ajax({
  type:"POST",        
    url: "scripts/listar_clientes_select.php",    
    dataType:'json',
    success: function(r){     
      if(r!=null){
        jQuery.each(r.aaData, function(k,v){
          jQuery('#apeynomv').append('<option value=' + v.idcliente +  '>' + v.apeynom + '</option>');
        });
        if(param1!='') jQuery('#apeynomv').selectpicker('val',param1);        
        jQuery('#apeynomv').selectpicker('refresh');
      }   
    }
  });  
};
function CambiaImagen(){
  jQuery("#imagen1 img").attr("src", imagenes[0]);
  jQuery("#imagen2 img").attr("src", imagenes[1]);
  jQuery("#imagen3 img").attr("src", imagenes[2]);
  jQuery("#imagen4 img").attr("src", imagenes[3]);
};
function mostrarVideo(video) {
  var playerId = "player1";
  var divId = "div1";
  
  var containerDiv = document.createElement("div");
  containerDiv.setAttribute("id", divId);
  document.getElementById('videoh').appendChild(containerDiv);
  swfobject.embedSWF(
    "http://www.youtube.com/v/" + video + "?enablejsapi=1&version=3&playerapiid=" + playerId,
    divId,
    "400",
    "240",
    "9.0.0",
    null,
    null,
    { "wmode": "opaque", "allowscriptaccess": "always" }, 
    { id: playerId }
  );
};
function RefreshTable(tableId, urlData){
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
function frmmodificahaciendalote (mensaje, titulo, datos) {
  jQuery("#formmsg").css("display", "block");
  jQuery('#msg').html(mensaje);
  jQuery("#formmsg").dialog({ 
    closeOnEscape: true,
    resizable: false,
    modal: true, 
    title : titulo,
    height: 140, 
    width: 360,
    dialogClass: "clsmsg",
    buttons:{
      "SI": function() {
         jQuery.ajax({
            type:"POST",        
            data:datos,
            url: "scripts/sumar_cantidad_hacienda_lote.php",
            dataType:'json',
            success: function(r){            
            }
          });         
        jQuery( this ).dialog( "close" );
        RefreshTable('#thacienda', 'scripts/listar_hacienda.php');
      },
      "NO": function() {
        jQuery.ajax({
          type:"POST",        
          data:datos,
          url: "scripts/sumar_resto_hacienda.php",
          dataType:'json',
          success: function(r){            
          }
        });       
        jQuery( this ).dialog( "close" );
        RefreshTable('#thacienda', 'scripts/listar_hacienda.php');
      },
      "Cerrar": function() {
        jQuery( this ).dialog( "close" );
      }
    }
  });
  return false;
};
function frmmodificahaciendalotes (mensaje, titulo, datos, r) {
  jQuery("#formmsg").css("display", "block");
  jQuery('#msg').html(mensaje);
  jQuery("#formmsg").dialog({ 
    closeOnEscape: true,
    resizable: false,
    modal: true, 
    title : titulo,
    height: 140, 
    width: 360,
    dialogClass: "clsmsg",
    buttons:{
      "SI": function() {
        jQuery('#formloteshacienda').empty();
        lotes = r.lotes;
        jQuery('#formloteshacienda').append('<div class="clsloteshacienda">');
        jQuery('#formloteshacienda').append('<div class="clsloteshacienda">');
          jQuery('#formloteshacienda').append('<div class="clslotehacienda1">');
            jQuery('#formloteshacienda').append('<label>Cantidad hacienda Actual: <input type="text" style="color:red;" id="cantidadactual" name="cantidadactual" value="' + r.cantidad + '" readonly> Nueva: <input type="text" id="cantidadnueva" name="cantidadnueva" style="color:green;" value="' + r.cantidadnueva + '"  readonly>');
            jQuery('#formloteshacienda').append('<label>Resto hacienda    Actual: <input type="text" style="color:red;" id="restoactual" name="restoactual" value="' + r.resto + '" readonly>    Nuevo: <input type="text" style="color:green;" id="restonuevo" name="restonuevo" value="' + r.restonuevo + '"  readonly>');
            jQuery('#formloteshacienda').append('<div id="lineatitulo"></div>');
          jQuery('#formloteshacienda').append('</div>');
        jQuery('#formloteshacienda').append('</div>');        
        for(i=0;i<lotes.length;i++){
          jQuery('#formloteshacienda').append('<label>Lote Nro ' + lotes[i].nrolote + '</label><div class="clsloteshacienda"><div class="clslotehacienda"><label for="a' + lotes[i].idlote + '">Cantidad actual de cabezas ' + lotes[i].nrolote + '</label><input type="text" id="a' + lotes[i].idlote + '" value="' + lotes[i].cantidad + '" name="cantidadloteactual[]" readonly></div><div class="clslotehacienda"><label for="n' + lotes[i].idlote + '">Cantidad nueva de cabezas</label><input name="cantidadlotenueva[]" type="text" id="n' + lotes[i].idlote + '"></div></div>');
        }
        jQuery('#loteshacienda').css('display','block');
        jQuery('#loteshacienda').dialog({ 
          closeOnEscape: true,
          resizable: false,
          modal: true, 
          title: "Datos de Lote", 
          height: 380, 
          width: 380,
          buttons : {
            "Aceptar":function(){
                var datos = jQuery('#formloteshacienda').serialize();                
                jQuery.ajax({
                  type:"POST",        
                  data:datos,
                  url: "scripts/sumar_cantidad_hacienda_lotes.php",
                  dataType:'json',
                  success: function(r){
                    if(!r.success){
                      msgbox('ERROR','La suma de las cantidades de los lotes es mayor a la nueva cantidad de cabezas');
                    }
                  }
                });                
                jQuery('#loteshacienda').dialog('close');
                RefreshTable('#thacienda', 'scripts/listar_hacienda.php');
            },
            "Cerrar":function(){
                jQuery('#loteshacienda').dialog('close');
            }
          }
        });
      },
      "NO": function() {
        jQuery.ajax({
          type:"POST",        
          data:datos,
          url: "scripts/sumar_resto_hacienda.php",
          dataType:'json',
          success: function(r){            
          }
        });    
        jQuery( this ).dialog( "close" );
        RefreshTable('#thacienda', 'scripts/listar_hacienda.php');
      },
      "Cerrar": function() {
        jQuery( this ).dialog( "close" );
      }
    }
  });  
  return false;
};
function msgbox(titulo, mensaje){
  var retorno = false;
  jQuery("#formmsg1").css("display", "block");
  jQuery('#msg1').html(mensaje);
  jQuery("#formmsg1").dialog({ 
    closeOnEscape: true,
    resizable: false,
    modal: true, 
    title : titulo,
    height: 200, 
    width: 360,
    dialogClass: "clsmsg",
    buttons:{
      "Aceptar":function(){
        retorno = true;
        jQuery(this).dialog('close');
      },
      "Cancelar":function(){
        jQuery(this).dialog('close');
      }
    }
  });
  return retorno;
};