<?php
	include('validar.php');	
	include('coneccion.php');	
	if(isset($_SESSION['susuario'])){
		$cantidad = $_POST['cantidadlote'];
		$idlote = $_POST['lotes'];
		$sql = "SELECT cantidad FROM lotes WHERE idlote = $idlote";
		$rs0 = mysql_query($sql);
		if(mysql_num_rows($rs0)>0){
			$fila = mysql_fetch_row($rs0);
			$sql = "UPDATE hacienda SET cantidad = $cantidad WHERE idhacienda = ".$_SESSION['sidhacienda'];;
			$rs = mysql_query($sql);
			$sql = "UPDATE lotes SET cantidad = $cantidad WHERE idlote = $idlote";
			$rs1 = mysql_query($sql);
			$cantidad = $cantidad - $fila[0];
			$sql = "UPDATE remate SET cabezas = cabezas + $cantidad WHERE idremate = ".$_SESSION['sidremate'];
			$rs2 = mysql_query($sql);
		}
		include('abmhacienda.php');
	}
?>