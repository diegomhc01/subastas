<?php
	include('scripts/validar.php');
	include('scripts/coneccion.php');
	$sql = "SELECT idtp, descripcion, inc1, inc2, inc3 FROM tipoprecio WHERE estado = 0 ORDER BY 1";
	mysql_set_charset('utf8');
	$rs = mysql_query($sql);
	
	echo '<option value="" disabled selected>Seleccionar</option>';
	if($rs){
		while($fila = mysql_fetch_array($rs)){
			echo '<option value="'.$fila[0].'">'.$fila[1].'</option>';
		}
	}	
?>

	
	