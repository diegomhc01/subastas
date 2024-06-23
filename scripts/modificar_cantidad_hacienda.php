<?php
	include('validar.php');	
	include('coneccion.php');	
	if(isset($_SESSION['susuario'])){
		if($_POST['modifica']==0){
			if(!isset($_POST['lotes'])){
				$cantidad = $_POST['cantidadhacienda'];
				$sql = "UPDATE hacienda SET cantidad = $cantidad WHERE idhacienda = ".$_SESSION['sidhacienda'];
				$rs = mysql_query($sql);				
			}else{				
				$cantidad = $_POST['cantidadlote'];
				$sql = "UPDATE hacienda SET cantidad = $cantidad WHERE idhacienda = ".$_SESSION['sidhacienda'];
				$rs = mysql_query($sql);
				$sql = "UPDATE hacienda_lote SET cantidad = $cantidad WHERE idhacienda = ".$_SESSION['sidhacienda']." and idlote = ".$_POST['lotes'];
				$rs1 = mysql_query($sql);
				$sql = "UPDATE lotes SET cantidad = $cantidad WHERE idlote = ".$_POST['lotes'];
				$rs2 = mysql_query($sql);
				$sql = "UPDATE remate SET cabezas = cabezas - $cantidad WHERE idremate = ".$_SESSION['sidremate'];
				$rs2 = mysql_query($sql);				
			}
		}
		include('abmhacienda.php');
		echo json_encode(array('success'=>true));
	}
?>