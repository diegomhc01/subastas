<?php
	include('validar.php');
	include('coneccion.php');
	if($_SESSION['susuario']){		
		$idestablecimiento = $_POST['param'];
		$_SESSION['sidestablecimiento'] = $_POST['param'];
		$_SESSION['saccion']=$_POST['param1'];
		mysql_set_charset('utf8');
		$sql = "SELECT ce.renspa, e.detalle, e.codprov, ";
		$sql .= "e.idlocalidad, e.lat, e.lon ";
		$sql .= "FROM cliente_establecimiento ce, establecimiento e ";
		$sql .= "WHERE e.idestablecimiento = $idestablecimiento and ";
		$sql .= "ce.idestablecimiento = e.idestablecimiento";
		$rs = mysql_query($sql);
		while($fila = mysql_fetch_assoc($rs)){
			$arr = array();
			$arr = $fila;
		}
		echo json_encode($arr);
	}
?>