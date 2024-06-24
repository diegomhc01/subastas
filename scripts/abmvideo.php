<?php
	include('validar.php');	
	include('coneccion.php');		
	if(isset($_SESSION['susuario'])){
		$arr = array('success'=>false);
		if(isset($_SESSION['sidhacienda'])){
			$idhacienda = $_SESSION['sidhacienda'];
			if(isset($_SESSION['svideo'])){
				$video = $_SESSION['svideo'];
				$tipo = 'a';
				if($tipo == 'a'){
					$sql = "INSERT INTO hacienda_video (video, idhacienda, estado) VALUES ('$video', $idhacienda, 0)";
				}
				if($tipo == 'e'){
					$sql = "DELETE FROM hacienda_video WHERE idvideo = $idvideo";
				}
				$rs = mysql_query($sql);
				if($rs){
					$arr = array('success'=>true);
				}
				unset($_SESSION['svideo']);
			}
		}
	}
	//echo json_encode($arr);
?>
