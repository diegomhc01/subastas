<?php
	include('validar.php');
	include('coneccion.php');	
	mysql_set_charset('utf8');
	$sql = "SELECT u.idusuario, CONCAT(u.apellido, ', ', u.nombre) as apeynom  FROM usuarios u WHERE perfil = 6 and (u.estado = 0 or u.estado = 9) ORDER BY 2";
	$rs = mysql_query($sql);	
	$arr = array();
	if(mysql_num_rows($rs)>0){
		while($fila = mysql_fetch_array($rs)){
			$arr[] = $fila;
		}
	}
	$arr1 = array('aaData'=>$arr);
	echo json_encode($arr1);
?>