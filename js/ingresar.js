jQuery(document).ready(function(){
	jQuery('#btningresar').on('click', function(){
		var classerror = 'has-error has-feedback';
		var ret = false;
		var ret1 = false;
		var ret2 = false;
		jQuery('#errorModal').html('');
		if(jQuery('#txtusuario').val()==''){
  			ret = true;
		}
		if(jQuery('#txtclave').val()==''){  		
  			ret = true;
		}
		if(ret){
			jQuery('#errorModal').html('<div class="alert alert-danger"><strong>Usuario o Contraseña incorrectos</strong></div>');
		}else{
			console.log(jQuery('#formingresar').serialize());
			jQuery.ajax({
				type:'post',
				data:jQuery('#formingresar').serialize(),
				url:'scripts/usuario.php',
				dataType:'json',
				success:function(r){
					
				}
			})
		}		
	});
});