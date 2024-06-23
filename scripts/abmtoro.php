<?php	
include('validar.php');	
include('coneccion.php');	
if(isset($_SESSION['susuario'])){
	$arrtoro = array('success'=>false,'de'=>'abmtoro');		
	$accion = 'a';

	if(isset($_SESSION['saccion']))
		$accion = $_SESSION['saccion'];
	
	if($accion=='mo' || $accion=='el')
		$idtoro = isset($_SESSION['sidtoro']) ? $_SESSION['sidtoro'] : 0;

	// TORO
	$sangretoro = filter_input(INPUT_POST, 'sangretoro', FILTER_SANITIZE_SPECIAL_CHARS);
	$sangretoro = $sangretoro;
	$razatipotoro = filter_input(INPUT_POST, 'razatipotoro', FILTER_SANITIZE_SPECIAL_CHARS);
	$razatipotoro = $razatipotoro;
	if($accion=='a'){			
		$sql = "INSERT INTO toro (sangre, razatipo, estado) VALUES ('$sangretoro', '$razatipotoro', 0)";
	}

	if($accion=='mo'){
		if($idtoro==0){
			$sql = "INSERT INTO toro (sangre, razatipo, estado) VALUES ('$sangretoro', '$razatipotoro', 0)";
		}else{		
			$sql = "UPDATE toro SET "; 
			$sql .= "sangre = '$sangretoro', ";
			$sql .= "razatipo = '$razatipotoro' WHERE idtoro = $idtoro";
		}
	}	      
	if($accion=='el'){
		$sql = "DELETE FROM toro WHERE idtoro = $idtoro AND estado = 0";
	}

	$rs = mysql_query($sql);
	if($rs){
		if($accion=='a' || ($idtoro==0 && $accion=='mo')){
			$_SESSION['sidtoro'] = mysql_insert_id();
		}
		$arrtoro = array('success'=>true,'de'=>'abmtoro');
	}	
}
?>