<?php
	include('validar.php');	
	include('coneccion.php');		
	if(isset($_SESSION['susuario'])){		
		if($_POST['param']!='' && $_POST['param1']!=''){
			$idusuario = 0;
			$tipo = '';			
			$idusuario = $_POST['param'];
			$tipo = $_POST['param1'];
			if($tipo == 'c' || $tipo=='ud'){
				$sql = "UPDATE cliente SET estado = 1 WHERE idusuario = $idusuario";
			}
			if($tipo == 's' || $tipo=='uh'){
				$sql = "UPDATE cliente SET estado = 0 WHERE idusuario = $idusuario";
			}
			$rs = mysql_query($sql);	
			if($rs)
				echo json_encode(array('success'=>true, 'idusuario'=>$idusuario,'tipo'=>$tipo,'sql'=>$sql));
			else
				echo json_encode(array('success'=>false, 'idusuario'=>$idusuario,'tipo'=>$tipo,'sql'=>$sql));
		}	
	}
?>
