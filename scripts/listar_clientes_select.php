<?php
	include('validar.php');
	include('coneccion.php');	
	mysql_set_charset('utf8');
	$sql = "SELECT DISTINCT c.idcliente, p.apeynom ";
	$sql .= "FROM cliente c, persona p, cliente_establecimiento ce ";
	$sql .= "WHERE p.idpersona = c.idpersona and ce.idcliente = c.idcliente ";
	$sql .= "ORDER BY apeynom";
	$rs = mysql_query($sql);
	$cantidad = mysql_num_rows($rs);
	$i=0;
	$arr = array();
	while($fila = mysql_fetch_array($rs)){
		if($fila[2]!=='')
			$arr[] = $fila;
	}
	$arr1 = array('aaData'=>$arr);
	echo json_encode($arr1);
?>