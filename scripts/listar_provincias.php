<?php
	include('validar.php');
	include('coneccion.php');
	mysql_set_charset('utf8');
	$sql = "SELECT codprov, nombre FROM provincias ORDER BY nombre";
	$rs = mysql_query($sql);
	$cantidad = mysql_num_rows($rs);
	$i=0;
	$arr = array();
	while($fila = mysql_fetch_array($rs)){		
		$arr[] = $fila;
	}
	$arr1 = array('aaData' => $arr);
	echo json_encode($arr1);	
?>