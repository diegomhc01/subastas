<?php
	include('validar.php');
	include('coneccion.php');
	$idcliente = $_POST['param1'];
	mysql_set_charset('utf8');
	$sql = "SELECT ce.idestablecimiento, detalle ";
	$sql .= "FROM cliente_establecimiento ce, establecimiento e ";
	$sql .= "WHERE ce.idcliente = $idcliente and ";
	$sql .= "e.idestablecimiento = ce.idestablecimiento and ";
	$sql .= "estado = 0 ORDER BY 2";
	$rs = mysql_query($sql);
	$cantidad = mysql_num_rows($rs);
	$i=0;
	$arr = array();
	while($fila = mysql_fetch_array($rs)){
		$arr[] = $fila;
	}
	$arr1 = array('aaData'=>$arr);
	echo json_encode($arr1);
?>