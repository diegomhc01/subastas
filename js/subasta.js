jQuery(document).ready(function(){
	var opcion = 0;
	jQuery('#txtusuario').focus();
	jQuery(".numeric").numeric();
	jQuery(".integer").numeric(false, function() { alert("Integers only"); 	this.value = ""; this.focus(); });
	jQuery(".positive-integer").numeric({ decimal: false, negative: false }, function() { alert("Positive integers only"); this.value = ""; this.focus(); });
    jQuery(".decimal-2-places").numeric({ decimalPlaces: 2 });
    
	jQuery('#portada').on('click', function(){
		jQuery('#mensajep').html('');
		jQuery('#msgregistro').html('');
		jQuery('#rematesactivo').empty();
		jQuery('#portadaimg').empty();
		jQuery('#rematesactivo').css('min-height', '0');
		jQuery('#portadaimg').append('<img src="images/home11.jpg" style="margin:0 auto; width:1248px;">');
	});

	jQuery('#remates').on('click', function(){
		opcion = 1;
		jQuery('#msgregistro').html('');
		jQuery('#mensajep').html('');
		jQuery('#portadaimg').empty();
		jQuery('#rematesactivo').empty();
		jQuery('#rematesactivo').css({'min-height':'488px','display':'block'});
		jQuery('#rematesactivo').load('formularios/producto.php');
	});

	jQuery('#ingresar').on('click', function(){
		opcion = 1;
		jQuery('#mensajep').html('');
		jQuery('#msgregistro').html('');
		jQuery('#portadaimg').empty();
		jQuery('#rematesactivo').empty();
		jQuery('#rematesactivo').css({'min-height':'486px'});
		jQuery('#rematesactivo').load('formularios/login1.php');		
	});
	jQuery('#registrarse').on('click', function(){	
		opcion = 1;
		jQuery('#mensajep').html('');
		jQuery('#msgregistro').html('');
		jQuery('#portadaimg').empty();
		jQuery('#rematesactivo').empty();
		jQuery('#rematesactivo').css({'min-height':'486px'});
		jQuery('#rematesactivo').load('formularios/registrar.php');		
	}); 
	jQuery.ajax({
		url:'scripts/boton.php',
		dataType:'json',
		success:function(r){
			if(r.mensaje!=''){
				jQuery('#mensajep').html('');
				jQuery('#portadaimg').empty();
				jQuery('#msgregistro').html('');
				jQuery('#rematesactivo').empty();
				jQuery('#rematesactivo').css({'min-height':'486px'});
				jQuery('#rematesactivo').load('formularios/login1.php');
				jQuery('#mensajep').html('<div class="alert alert-danger"> <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Error </strong>' + r.mensaje + '</div>');
			}else{
				if(opcion==0){
					jQuery('#mensajep').html('');
					jQuery('#msgregistro').html('');
					jQuery('#rematesactivo').empty();
					jQuery('#portadaimg').empty();
					jQuery('#rematesactivo').css('min-height', '0');
					jQuery('#portadaimg').append('<img src="images/home11.jpg" style="margin:0 auto; width:1248px;">');
				}
			}
			
		}
	});
});

