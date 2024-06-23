<?php
	include('validar.php');	
	include('coneccion.php');		
	if(isset($_SESSION['susuario'])){
		$arr = array('success'=>false);
		if(isset($_SESSION['sidhacienda'])){
			$idhacienda = $_SESSION['sidhacienda'];
			if(isset($_SESSION['simagen'])){
				$imagen = $_SESSION['simagen'];
				$tipo = 'a';
				if($tipo == 'a'){
					$sql = "INSERT INTO hacienda_img (imagen, idhacienda, estado) VALUES ('$imagen', $idhacienda, 0)";
				}
				if($tipo == 'e'){
					$sql = "DELETE FROM hacienda_img WHERE idhi = $idhi";
				}
				$rs = mysql_query($sql);
				if($rs){
					$arr = array('success'=>true);
				}
				unset($_SESSION['simagen']);
			}
		}
	}
	//echo json_encode($arr);
?>
