<?php
	include('scripts/validar.php');
	include('scripts/coneccion.php');
	$sql = "SELECT u.idusuario, CONCAT(u.apellido, ', ', u.nombre) AS apeynom FROM usuarios u WHERE u.perfil = 6 and estado = 0";
	mysql_set_charset('utf8');
	$rs = mysql_query($sql);
	echo '<label for="evaluador">Evaluador</label>';
	echo '<select id="evaluador" name="evaluador">';		
	if($rs){
		while($fila = mysql_fetch_array($rs)){
			echo '<option value="'.$fila[0].'">'.$fila[1].'</option>';
		}
	}
	echo '</select><br>';
?>