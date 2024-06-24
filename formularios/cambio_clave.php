<script>
jQuery(document).ready(function(){
	jQuery("#divcambio").css("display", "block");
	jQuery("#divcambio").dialog({ 
		closeOnEscape: true,
		resizable: false,
		modal: true,
		title: 'Ingreso',
		height: 250, 
		width: 350			
	});
});	
</script>
<div id="divcambio">
	<form method="post">
		<div id="cambio">
			<h4><label for="Clave" class="label">Clave Actual</label></h4>
			<input type="password" name="txtclaveactual" id="txtclaveactual">
			<h4><label for="Clave" class="label">Clave Nueva</label></h4>
			<input type="password" name="txtclavenueva1" id="txtclavenueva1">
			<h4><label for="Clave" class="label">Repita Clave Nueva</label></h4>
			<input type="password" name="txtclavenueva2" id="txtclavenueva2">
			<input type="submit" name="btncambiar" id="btncambiar" value="Modificar Clave" class="btn btn-default">
		</div>
	</form>
</div>