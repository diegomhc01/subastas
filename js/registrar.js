jQuery(document).ready(function(){
	jQuery('#emailc').on('blur', function(){
		jQuery('#femailc').removeClass('has-error has-feedback');
		jQuery('#erroremail').html('');
		jQuery.ajax({
			type:'post',
			url:'scripts/buscaremail.php',
			data:{param:jQuery(this).val()},
			dataType:'json',
			success:function(r){
				if(r.success){
					jQuery('#femailc').addClass('has-error has-feedback');
					jQuery('#erroremail').html(r.mensaje);
				}
			}
		});

	});
	jQuery('#btngrabarusuario').on('click', function(e){
		var classerror = 'has-error has-feedback';
		var ret = false;
		var ret1 = false;
		var ret2 = false;
		jQuery('#errorModal').html('');
		if(jQuery('#apellidoc').val()==''){
  			jQuery('#fapellidoc').addClass(classerror); 
  			ret = true;
		}else{
			jQuery('#fapellidoc').removeClass(classerror); 
		}
		if(jQuery('#nombrec').val()==''){
  			jQuery('#fnombrec').addClass(classerror); 
  			ret = true;
		}else{
			jQuery('#fnombrec').removeClass(classerror); 
		}
		if(jQuery('#telefonoc').val()==''){
  			jQuery('#ftelefonoc').addClass(classerror);   			
  			ret = true;
		}else{
			jQuery('#ftelefonoc').removeClass(classerror); 
		}
		if(jQuery('#emailc').val()==''){
  			jQuery('#femailc').addClass(classerror); 
  			ret = true;
		}else{
			jQuery('#femailc').removeClass(classerror); 
		}
		if(jQuery('#clavec').val()==''){
  			jQuery('#fclavec').addClass(classerror); 
  			ret = true;
		}else{
			jQuery('#fclavec').removeClass(classerror); 
		}
		if(jQuery('#clave1c').val()==''){			
  			jQuery('#fclave1c').addClass(classerror); 
  			ret = true;
		}else{
			jQuery('#fclave1c').removeClass(classerror); 
		}
		if(!ret){			
			if(jQuery('#clave1c').val()!=jQuery('#clavec').val()){
	  			jQuery('#fclavec').addClass(classerror); 
	  			jQuery('#fclave1c').addClass(classerror); 
	  			ret = true;			
			}else{
				jQuery('#fclavec').removeClass(classerror);
				jQuery('#fclave1c').removeClass(classerror);
			}
		}

		if(!ret){
			jQuery.ajax({
				type:'post',
				url:'scripts/buscaremail.php',
				data:{param:jQuery('#emailc').val()},
				dataType:'json',
				success:function(r){
					ret2 = r.success;
					if(ret2){
						jQuery('#errorModal').html('<div class="alert alert-danger"><strong>Usuario existente</strong></div>');
					}
					if(!ret && !ret2){
						jQuery.ajax({
							type:'post',
							data:jQuery('#formregistrar').serialize(),
							url:'scripts/abmusuarior.php',
							dataType:'json',
							success:function(r){
								if(r.success){
									jQuery('#apellidoc').val('');
									jQuery('#nombrec').val('');
									jQuery('#emailc').val('');
									jQuery('#telefonoc').val('');
									jQuery('#clavec').val('');
									jQuery('#clave1c').val('');
									jQuery('#rematesactivo').empty();
									jQuery('#divregistrarse').empty();
									jQuery('#portadaimg').empty();
									jQuery('#rematesactivo').css('min-height', '486px');									
									jQuery('#rematesactivo').load('formularios/inforegistro.php');
									jQuery('#msgregistro').html('<div class="alert alert-success" style="width:380px;padding:5px;"><strong>Se ha registrado con éxito</strong> Se ha enviado un mensaje a su correo</div>');
								}else{
									jQuery('#errorModal').html('<div class="alert alert-danger"><strong>' + r.mensaje + '</strong></div>');												
								}
							}
						})
					}
				}
			});
		}
	});
});