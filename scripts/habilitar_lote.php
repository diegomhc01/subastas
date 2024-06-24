<?php
	include('validar.php');	
	include('coneccion.php');	
	if(isset($_SESSION['susuario'])){
		if(!isset($_SESSION['sidlote']))
			$_SESSION['sidlote'] = $_POST['param'];

			$idlote = $_SESSION['sidlote'];	
			$tipo = $_POST['param1'];

			$sql = "SELECT idhacienda FROM hacienda_lote WHERE idlote = $idlote";
			$rs0 = mysql_query($sql);
			
			if($tipo == 'd'){
				$sql = "UPDATE lotes SET estado = 4 WHERE idlote = $idlote";
			}
			if($tipo == 'h'){
				$sql = "UPDATE lotes SET estado = 0 WHERE idlote = $idlote";
			}
			$rs = mysql_query($sql);	
			if($rs){
				unset($_SESSION['sidlote']);
				echo json_encode(array('success'=>true, 'idlote'=>$idlote));
			}else{
				echo json_encode(array('success'=>false, 'idlote'=>$idlote));
			}
			
	}	
?>
