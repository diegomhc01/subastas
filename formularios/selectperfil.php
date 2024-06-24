<?php
	include('scripts/validar.php');
	include('scripts/coneccion.php');
	$sql = "SELECT perfilid, descripcion FROM perfil WHERE estado = 0 ORDER BY 2";
	mysql_set_charset('utf8');
	$rs = mysql_query($sql);	
	if($rs){
		while($fila = mysql_fetch_array($rs)){
			echo '<option value="'.$fila[0].'">'.$fila[1].'</option>';
		}
	}
?>

	
	