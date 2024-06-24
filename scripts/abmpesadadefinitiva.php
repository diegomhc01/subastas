<?php	
include('validar.php');	
include('coneccion.php');	
if(isset($_SESSION['susuario'])){
	$accion = 'a';
	$arrpd = array('success'=>false,'de'=>'abmpesadadefinitiva');

	if(isset($_SESSION['saccion']))
		$accion = $_SESSION['saccion'];
	
	if($accion=='mo' || $accion=='el')
		$idpd = isset($_SESSION['sidpd']) ? $_SESSION['sidpd'] : 0;
		
	$ubicacion = filter_input(INPUT_POST, 'ubicacion', FILTER_SANITIZE_SPECIAL_CHARS);
	$ubicacion = strtoupper($ubicacion);
	$arreo = filter_input(INPUT_POST, 'arreo', FILTER_VALIDATE_INT);
	if($arreo===FALSE || is_null($arreo)) $arreo = 0;
	
	$camion = filter_input(INPUT_POST, 'camion', FILTER_VALIDATE_INT);
	if($camion===FALSE || is_null($camion)) $camion = 0;
			
	$total = $arreo + $camion;

	$balanza = filter_input(INPUT_POST, 'balanza', FILTER_VALIDATE_INT);
	if($balanza===FALSE || is_null($balanza)) $balanza = 0;

	$desbastep = filter_input(INPUT_POST, 'desbastep', FILTER_VALIDATE_FLOAT,FILTER_FLAG_ALLOW_FRACTION);
	if($desbastep===FALSE || is_null($desbastep)) $desbastep = 0;

	$camions = filter_input(INPUT_POST, 'camions', FILTER_VALIDATE_INT);
	if($camions===FALSE || is_null($camions)) $camions = 0;
	
	$observacionesp = filter_input(INPUT_POST, 'observacionesp', FILTER_SANITIZE_SPECIAL_CHARS);
	$observacionesp = $observacionesp;
	if($accion=='a'){			
		$sql = "INSERT INTO pesada_definitiva (ubicacion, arreo, camion, total, balanza, lugarcamion, desbaste, promedio, maximo, minimo, observaciones, estado) 
		VALUES ('$ubicacion', $arreo, $camion, $total, $balanza, $camions, $desbastep, 0, 0, 0, '$observacionesp', 0)";
	}

	if($accion=='mo'){
		if($idpd==0){
			$sql = "INSERT INTO pesada_definitiva (ubicacion, arreo, camion, total, balanza, lugarcamion, desbaste, promedio, maximo, minimo, observaciones, estado) 
			VALUES ('$ubicacion', $arreo, $camion, $total, $balanza, $camions, $desbastep, 0, 0, 0, '$observacionesp', 0)";
		}else{			
			$sql = "UPDATE pesada_definitiva SET "; 
			$sql .= "ubicacion = '$ubicacion', ";
			$sql .= "arreo = $arreo, ";
			$sql .= "camion = $camion, ";
			$sql .= "total = $total, ";
			$sql .= "balanza = $balanza, ";
			$sql .= "camions = $camions, ";
			$sql .= "desbaste = $desbastep, ";
			$sql .= "observaciones = '$observacionesp' WHERE idpd = $idpd";
		}
	}	      
	if($accion=='el'){
		$sql = "DELETE FROM pesada_definitiva WHERE idpd = $idpd AND estado = 0";
	}

	$rs = mysql_query($sql);
	if($rs){
		if($accion=='a' || ($idpd==0 && $accion=='mo')){
				$_SESSION["sidpd"] = mysql_insert_id();
		}
		$arrpd = array('success'=>true,'de'=>'abmpesadadefinitiva');
	}
}
?>