var param1 = 'a';
var faltandatos=false;
$(document).ready(function(){
	var oTable = inicioTabla();
	jQuery('#btnaddusuario').on('click', function(){
        param1 = 'a';
        limpiarcampos();
        jQuery('#seguridadModal').modal();
	});
    jQuery('#btngrabarusuario').on('click', function(){        
        var datos = jQuery('#formusuario').serialize();
        datos = datos + '&param1=' + param1;
        controlardatos(datos);
        if(!faltandatos){
            jQuery.ajax({
                type:"POST",        
                data:datos,
                url: "scripts/abmusuario.php",
                dataType:'json',
                success: function(r){
                    RefreshTable('#tusuarios', 'scripts/listarusuarios.php');
                    jQuery('#btnagregarest').html('')
                    jQuery('#seguridadModal').modal('hide');
                }
            });            
        }
    });
    jQuery('#tusuarios tbody').on('click', '.clsusuarios', function(){
        var param = jQuery(this).attr('id').substring(1);        
        param1 = jQuery(this).attr('id').substring(0,1);
        if(param1=='a' || param1=='m'){
            if(param1=='m') 
                param1 = 'mo';
            frmabmusuario(param);
        }
        if(param1=='e'){
            jQuery.ajax({
                type:"POST",
                data:{param:param,param1:'el'},
                url: "scripts/abmusuario.php",
                dataType:'json',
                 success: function(r){
                    RefreshTable('#tusuarios', 'scripts/listarusuarios.php');
                }
            });
        }
        if(param1=='d' || param1=='h'){
            jQuery.ajax({
                type:"POST",        
                data:{param : param, param1 : param1},
                url: "scripts/habilitar_usuario.php",
                dataType:'json',
                 success: function(r){                  
                    RefreshTable('#tusuarios', 'scripts/listarusuarios.php');                    
                }
            });
        }        
        if(param1=='r'){
            var datos = {param:param,
                param2:jQuery(this).closest('tr').find('td:eq(0)').text(),
                param3:jQuery(this).closest('tr').find('td:eq(1)').text()
            };
            jQuery.ajax({
                type:"POST",        
                data:datos,
                url: "scripts/resetear_usuario.php",
                dataType:'json',
                success: function(r){                     
                    if(r.success){
                        jQuery('#usuarioreset').val(r.usuario);
                        jQuery('#apeynomreset').val(r.apeynom);
                        jQuery('#resetModal').modal();
                    }            
                }
            });
        }
        if(param1=='c' || param1=='s'){
            jQuery.ajax({
                type:"POST",        
                data:{param : param, param1 : param1},
                url: "scripts/habilitar_cliente.php",
                dataType:'json',
                 success: function(r){                  
                    RefreshTable('#tusuarios', 'scripts/listarusuarios.php');
                }
            });
        }
        if(param1=='l'){
            jQuery.ajax({
                type:'post',
                data:{param:param},
                url:'scripts/iestablecimiento.php',
                dataType:'json',
                success:function(r){
                    if(r.success){                        
                        inicioTablaEstablecimientos();
                        limpiarcamposest();
                        jQuery('#establecimientoModal').modal('show');        
                    }
                }
            });            
        }
    });
    jQuery('#testablecimientos tbody').on('click', '.clsce', function(){
        var idestablecimiento = jQuery(this).attr('id').substring(1);
        var accion = jQuery(this).attr('id').substring(0,1);
        jQuery.ajax({
            type:'post',
            data:{param:idestablecimiento,param1:accion},
            url:'scripts/buscar_establecimiento.php',
            dataType:'json',
            success:function(r){
                jQuery('#renspav').val(r.renspa);
                jQuery('#establecimientov').val(r.detalle);
                LlenarComboProvincias(r.codprov);
                LlenarComboLocalidad(r.codprov, r.idlocalidad);
                jQuery('#latv').val(r.lat);
                jQuery('#lonv').val(r.lon);
                if(accion=='m'){
                    jQuery('#btngrabarest').removeClass('btn btn-default');
                    jQuery('#btngrabarest').removeClass('btn btn-danger'); 
                    jQuery('#btngrabarest').addClass('btn btn-warning');
                    jQuery('#btngrabarest').html('Modificar');
                }
                if(accion=='e'){
                    jQuery('#btngrabarest').removeClass('btn btn-default');
                    jQuery('#btngrabarest').removeClass('btn btn-warning');
                    jQuery('#btngrabarest').addClass('btn btn-danger'); 
                    jQuery('#btngrabarest').html('Eliminar');
                }
            }
        });        
    });
    jQuery('#usuario').on('blur', function(){
        if(!isNaN(jQuery(this).val())){
            if(param1!='a' && param1!='m' && param1!='e'){            
                param1 = 'me';
                var param = jQuery(this).val();
                frmabmusuario(param);
            }
        }
    });
    jQuery('#perfil').on('change', function(){
        if(jQuery(this).val()==1){
            jQuery('#datoscliente').css('display','block');            
        }else{
            jQuery('#datoscliente').css('display','none');
        }
    });
    jQuery('#provinciav').on('change', function(){
        LlenarComboLocalidad(jQuery(this).val(),'');
    });
    jQuery('#btngrabarest').on('click', function(){
        var datos = jQuery('#formest').serialize();
        datos = datos + '&param1=' + param1;
        controlardatosest(datos);
        jQuery.ajax({
            type:'post',
            data:datos,
            url:'scripts/abmestablecimiento.php',
            dataType:'json',
            success:function(r){
                RefreshTable('#testablecimientos', 'scripts/listar_establecimientos.php');
                limpiarcamposest();
            }
        });
    });
    jQuery('#btngrabarclave').on('click',function(){
        jQuery('#clave').removeClass('has-error has-feedback')        
        if(jQuery('#clave').val()==''){
            jQuery('#clave').addClass('has-error has-feedback')
        }else{            
            jQuery.ajax({
                    type:"POST",        
                    data:{param : jQuery('#clave').val()},
                    url: "scripts/resetear_usuario1.php",
                    dataType:'json',
                     success: function(r){                  
                        RefreshTable('#tusuarios', 'scripts/listarusuarios.php');
                        jQuery('#resetModal').modal('hide');
                    }
            });
        }
    });
});

function inicioTabla(){ 
   oTable = jQuery('#tusuarios').DataTable({
        "processing": true,
        "destroy":true,
        "responsive": true,
        "ajax": "scripts/listarusuarios.php",
        "deferRender": true,        
        "scrollY": 230,
        "paging": false,
        "scrollCollapse": true,
        "dom": '<"top"f>rt<"bottom"><"clear">',
        "ordering": false,
        "columns": [
          {data:"usuario", title: "Usuario","width":"10%"},
          {data:"apeynom", title: "Ape y Nom","width":"30%"},
          {data:"perfil", title: "Perfil","width":"10%"},
          {data:"operador", title: "Operador","width":"10%"},
          {data:"clave", title: "Clave","width":"10%"},
          {data:"boton1", title: "","width":"5%"},
          {data:"boton6", title: "","width":"5%"},
          {data:"boton2", title: "","width":"5%"},
          {data:"boton3", title: "","width":"5%"},
          {data:"boton4", title: "","width":"5%"},
          {data:"boton5", title: "","width":"5%"}
        ],
        "language":{
                "sProcessing":     "Procesando...",
                "sZeroRecords":    "No se encontraron usuarios",
                "sEmptyTable":     "No se encontraron usuarios",
                "sSearch":         "Buscar:"
            }
    });  
  return oTable;
};
function inicioTablaEstablecimientos(){ 
   oTable = jQuery('#testablecimientos').DataTable({
        "processing": true,
        "destroy":true,
        "responsive": true,
        "ajax": "scripts/listar_establecimientos.php",
        "deferRender": true,        
        "scrollY": 230,
        "paging": false,
        "scrollCollapse": true,
        "dom": '<"top">rt<"bottom"><"clear">',
        "ordering": false,
        "columns": [
          {data:"renspa", title: "RENSPA"},
          {data:"establecimiento", title: "Establecimiento"},
          {data:"provincia", title: "Provincia"},
          {data:"localidad", title: "Localidad"},
          {data:"latitud", title: "Latitud"},
          {data:"longitud", title: "Longitud"},
          {data:"boton1", title: ""},
          {data:"boton2", title: ""}
        ],
        "language":{
                "sProcessing":     "Procesando...",
                "sZeroRecords":    "No se encontraron establecimientos",
                "sEmptyTable":     "No se encontraron establecimientos",
                "sSearch":         "Buscar:"
            }
    });  
  return oTable;
};
function frmabmusuario(param){
    BuscarUsuario(param);
    limpiarcampos();
    jQuery('#seguridadModal').modal('show');    
};
function BuscarUsuario(param){    
    var url = '';
    if(param1=='me'){        
        url = "scripts/buscar_usuario_x_usuario.php";
    }else{
        url = "scripts/buscar_usuario.php";
    }
    jQuery.ajax({
        type:"POST",        
        data:{param : param, param1 : param1},
        url: url,
        dataType:'json',
         success: function(r){            
            if(typeof r.usuario==='undefined'){
                param1 = 'a';
            }else{
                jQuery('#usuario').val(r.usuario.usuario);
                jQuery('#usuario').attr('disabled','disabled');
                jQuery('#apellido').val(r.usuario.apellido);
                jQuery('#nombre').val(r.usuario.nombre);
                jQuery('#perfil').selectpicker('val',r.usuario.perfil);
                jQuery('#perfil').selectpicker('refresh');
                if(r.usuario.perfil==1){
                    jQuery('#datoscliente').css('display','block');
                    jQuery('#cuitv').val(r.cliente[0].cuit);                    
                    jQuery('#contactov').val(r.cliente[0].contacto);
                    jQuery('#telefonov').val(r.cliente[0].telefono);
                    jQuery('#emailv').val(r.cliente[0].email);
                    if(typeof r.establecimiento[0]!=='undefined'){                        
                        jQuery('#renspav').val(r.establecimiento[0].renspa);
                        jQuery('#establecimientov').val(r.establecimiento[0].detalle);
                        LlenarComboProvincias(r.establecimiento[0].codprov);
                        LlenarComboLocalidad(r.establecimiento[0].codprov,r.establecimiento[0].idlocalidad);
                        jQuery('#latv').val(r.establecimiento[0].lat);
                        jQuery('#lonv').val(r.establecimiento[0].lon);
                    }
                    inicioTablaEstablecimientos();
                    jQuery('#btnagregarest').html('<button type="button" class="btn btn-primary btn-block" name="btnagregarestablecimiento" id="btnagregarestablecimiento">Agregar Establecimiento</button>')
                }
            }
            
        }
    });
	return false;
};
function controlardatos(p){
    var arr = [];
    var arrret = {'ssusario':false,'ssapellido':false,'ssnombre':false,
    'ssperfil':false, 'sscuit':false};
    
    arr = p.split('&');
    if(arr[0].split('=')[0]=='usuario'){
        arrret.ssusario = arr[0].split('=')[0] != 'usuario' ? false : arr[0].split('=')[1]=='' ? false : true;
        arrret.ssapellido = arr[1].split('=')[0] != 'apellido' ? false : arr[1].split('=')[1]==''  ? false :  true;
        arrret.ssnombre = arr[2].split('=')[0] != 'nombre' ? false : arr[2].split('=')[1]==''  ? false :  true;
        arrret.ssperfil = arr[3].split('=')[0] != 'perfil' ? false : arr[3].split('=')[1]=='' ? false : arr[3].split('=')[1].match(/^\d+$/g) == null ? false : true;
        if(jQuery('#perfil').selectpicker('val')==1){
            arrret.sscuit = arr[4].split('=')[0] != 'cuitv' ? false : arr[4].split('=')[1]=='' ? false : arr[4].split('=')[1].match(/^\d{2}\d{8}\d{1}$/g) == null ? false : true;
        }else{
            arrret.sscuit = true;
        }
    }
    if(arr[0].split('=')[0]=='apellido'){
        arrret.ssapellido = arr[0].split('=')[0] != 'apellido' ? false : arr[0].split('=')[1]==''  ? false :  true;
        arrret.ssnombre = arr[1].split('=')[0] != 'nombre' ? false : arr[1].split('=')[1]==''  ? false :  true;
        arrret.ssperfil = arr[2].split('=')[0] != 'perfil' ? false : arr[2].split('=')[1]=='' ? false : arr[2].split('=')[1].match(/^\d+$/g) == null ? false : true;
        if(jQuery('#perfil').selectpicker('val')==1){        
            arrret.sscuit = arr[3].split('=')[0] != 'cuitv' ? false : arr[3].split('=')[1]=='' ? false : arr[3].split('=')[1].match(/^\d{2}\d{8}\d{1}$/g) == null ? false : true;
        }else{
            arrret.sscuit = true;
        }
    } 
    mostrarerrorusuario(arrret);
};
function limpiarerrores(){
    var ok = 'has-error has-feedback';
    jQuery('#fusuario').removeClass(ok);
    jQuery('#fapellido').removeClass(ok);
    jQuery('#fperfil').removeClass(ok);
    jQuery('#fcuitv').removeClass(ok);
};
function limpiarcampos(){
    limpiarerrores();
    jQuery('#usuario').val('');
    jQuery('#usuario').removeAttr('disabled');
    jQuery('#apellido').val('');
    jQuery('#nombre').val('');
    jQuery('#perfil').selectpicker('val',0);
    jQuery('#cuitv').val('');
    jQuery('#contactov').val('');
    jQuery('#telefonov').val('');
    Inputmask("email").mask('#emailv');
    jQuery('#emailv').val('');
    /*
    jQuery('#renspav').val('');
    jQuery('#establecimientov').val('');
    LlenarComboProvincias('');
    LlenarComboLocalidad('',0);
    */
    jQuery('#datoscliente').css('display','none');
};
function mostrarerrorusuario(r){  
    var classerror = 'has-error has-feedback';
    var ret = false;  
    limpiarerrores();
    if(param1=='a'){
        if(!r.ssusario){ jQuery('#fusuario').addClass(classerror); ret = true; }  
    }
    if(!r.ssapellido){ jQuery('#fapellido').addClass(classerror); ret = true; }
    if(!r.ssperfil){ jQuery('#fperfil').addClass(classerror); ret = true; }
    if(!r.sscuit){ jQuery('#fcuitv').addClass(classerror); ret = true; }
    faltandatos = ret;
};
function controlardatosest(p){
    var arr = [];
    var arrret = {'ssrenspa':false,'ssestablecimiento':false,'ssprovincia':false,
    'sslocalidad':false};
    arr = p.split('&');
    arrret.ssrenspa = typeof arr[0]==='undefined' ? false : arr[0].split('=')[0] != 'renspav' ? false : arr[0].split('=')[1]=='' ? false : arr[0].split('=')[1].match(/^\d+$/g) == null ? false : true;
    arrret.ssestablecimiento = typeof arr[1]==='undefined' ? false : arr[1].split('=')[0] != 'establecimientov' ? false : arr[1].split('=')[1]=='' ? false : true;
    arrret.ssprovincia = typeof arr[2]==='undefined' ? false : arr[2].split('=')[0] != 'provinciav' ? false : arr[2].split('=')[1]=='' ? false : arr[2].split('=')[1].match(/^[a-zA-Z\s]*$/g) == null ? false : true;
    arrret.sslocalidad = typeof arr[3]==='undefined' ? false : arr[3].split('=')[0] != 'localidadv' ? false : arr[3].split('=')[1]=='' ? false : arr[3].split('=')[1].match(/^\d+$/g) == null ? false : true;
    mostrarerrorest(arrret);
};
function mostrarerrorest(r){  
    var classerror = 'has-error has-feedback';
    var ret = false;  
    limpiarerroresest();
    if(!r.ssrenspa){ jQuery('#frenspav').addClass(classerror); ret = true; }
    if(!r.ssestablecimiento){ jQuery('#festablecimiento').addClass(classerror); ret = true; }
    if(!r.ssprovincia){ jQuery('#fprovinciav').addClass(classerror); ret = true; }
    if(!r.sslocalidad){ jQuery('#flocalidadv').addClass(classerror); ret = true; }
    faltandatos = ret;
};
function limpiarerroresest(){
    var ok = 'has-error has-feedback';
    jQuery('#frenspav').removeClass(ok);
    jQuery('#festablecimiento').removeClass(ok);
    jQuery('#fprovinciav').removeClass(ok);
    jQuery('#flocalidadv').removeClass(ok);
};
function limpiarcamposest(){
    limpiarerroresest();
    jQuery('#renspav').val('');
    jQuery('#establecimientov').val('');
    LlenarComboProvincias('');
    LlenarComboLocalidad('',0);
    jQuery('#btngrabarest').removeClass('btn btn-danger'); 
    jQuery('#btngrabarest').removeClass('btn btn-warning');
    jQuery('#btngrabarest').addClass('btn btn-default');
    jQuery('#btngrabarest').html('Grabar');    
};
function LlenarComboProvincias(param1){
    jQuery('#provinciav').empty();     
    jQuery('#provinciav').append('<option value="" disabled selected>Seleccione una provincia</option>');
    jQuery.getJSON('scripts/listar_provincias.php',  
    function(data){
        jQuery.each(data.aaData, function(k,v){
            jQuery('#provinciav').append('<option value=' + v.codprov +  '>' + v.nombre + '</option>');
        });
        jQuery('#provinciav').selectpicker('refresh');
        if(param1!='') jQuery('#provinciav').selectpicker('val',param1);
    }
  );
};
function LlenarComboLocalidad(param0, param1){  
  jQuery('#localidadv').empty();
  jQuery('#localidadv').append('<option value="" disabled selected>Seleccione una localidad</option>');
  jQuery.ajax({
    type:"POST",        
    url: "scripts/listar_localidades.php",
    data:{param1:  param0},
    dataType:'json',
    success: function(r){
        if(r!=null){
            jQuery.each(r.aaData, function(k,v){
                jQuery('#localidadv').append('<option value=' + v.idlocalidad +  '>' + v.nombre + '</option>');
            });
            jQuery('#localidadv').selectpicker('refresh');
            if(param1!='') jQuery('#localidadv').selectpicker('val',param1);
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