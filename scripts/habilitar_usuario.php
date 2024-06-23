<?php
	include('validar.php');	
	include('coneccion.php');		
	if($_POST['param']!='' && $_POST['param1']!=''){
		$idusuario = 0;
		$tipo = '';			
		$idusuario = $_POST['param'];
		$tipo = $_POST['param1'];
		if($tipo == 'd'){
			$sql = "UPDATE usuarios SET estado = 2 WHERE idusuario = $idusuario";
		}
		if($tipo == 'h'){
			$sql = "UPDATE usuarios SET estado = 0 WHERE idusuario = $idusuario";
		}
		$rs = mysql_query($sql);	
		if($rs)
			echo json_encode(array('success'=>true, 'idusuario'=>$idusuario));
		else
			echo json_encode(array('success'=>false, 'idusuario'=>$idusuario));
	}	
?>
