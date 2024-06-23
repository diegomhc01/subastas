<?php
	include('validar.php');	
	include('coneccion.php');	
	if(isset($_SESSION['susuario'])){
		if(isset($_SESSION['sidremate'])){
			$success = false;
			$cantidad = count($_POST['param']);
			foreach ($_POST['param'] as $variable) {
				$sql = "UPDATE lotes SET nrolote = ".$variable['r']."  WHERE idlote = ".$variable['l'];
				$rs = mysql_query($sql);
				if($rs){
					$success = true;
				}else{
					$success = false;
					break;
				}
			}			
		}
		echo json_encode(array('success'=>$success));
	}	
?>
