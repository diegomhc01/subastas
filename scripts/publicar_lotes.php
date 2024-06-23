<?php
	include('validar.php');	
	include('coneccion.php');		
	if(isset($_SESSION['susuario'])){		
		if(isset($_SESSION['sidremate'])){
			$idremate = $_SESSION['sidremate'];
			$sql = "UPDATE lotes SET estado = 0 WHERE idremate = $idremate";
			$rs = mysql_query($sql);	
			if($rs){
				echo json_encode(array('success'=>true));
			}else{
				echo json_encode(array('success'=>false));
			}
		}
	}
?>
