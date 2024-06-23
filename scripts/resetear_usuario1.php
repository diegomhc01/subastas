<?php
	include('validar.php');	
	include('coneccion.php');		
	if(isset($_SESSION['susuario'])){
		$error = false;
		$arr = array('success'=>false, 'idusuario'=>-1);
		if(isset($_SESSION['sidusuariog'])){
			$idusuario = $_SESSION['sidusuariog'];
			$clave = filter_input(INPUT_POST, 'param', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
			if($clave===FALSE || is_null($clave) || strlen($clave)==0) $error = true;

			if(!$error){				
				$sql = "UPDATE usuarios SET clave = '$clave' WHERE idusuario = $idusuario";
				$rs = mysql_query($sql);	
				if($rs){
					$arr = array('success'=>true, 'idusuario'=>$idusuario);
					unset($_SESSION['sidusuariog']);
				}
			}
		}
		echo json_encode($arr);
	}	
?>
