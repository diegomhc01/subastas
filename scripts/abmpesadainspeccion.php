<?php	
include('validar.php');	
include('coneccion.php');	
if(isset($_SESSION['susuario'])){
	$accion = 'a';
	$errorpi = false;
	$arrpi = array('success'=>false,'de'=>'abmpesadainspeccion');
	
	if(isset($_SESSION['saccion']))
		$accion = $_SESSION['saccion'];
	
	if($accion=='mo' || $accion=='el')
		$idpi = $_SESSION['sidpi'];

	// Pesada Inspeccion
	$forma = filter_input(INPUT_POST, 'forma', FILTER_VALIDATE_INT);
	if($forma===FALSE || is_null($forma)) $forma = 0;
	
	$hora = filter_input(INPUT_POST, 'hora', FILTER_SANITIZE_SPECIAL_CHARS);
	
	$desbastei = filter_input(INPUT_POST, 'desbastei', FILTER_VALIDATE_FLOAT);
	if($desbastei===FALSE || is_null($desbastei)) $desbastei = 0;
	
	$promedio = filter_input(INPUT_POST, 'promedio', FILTER_VALIDATE_INT);
	if($promedio===FALSE || is_null($promedio)) $errorpi = true;

	$maximo = filter_input(INPUT_POST, 'maximo', FILTER_VALIDATE_INT);
	if($maximo===FALSE || is_null($maximo)) $errorpi = true;

	$minimo = filter_input(INPUT_POST, 'minimo', FILTER_VALIDATE_INT);
	if($minimo===FALSE || is_null($minimo)) $errorpi = true;
	
	if(!$errorpi){		
		if($accion=='a'){			
			$sql = "INSERT INTO pesada_inspeccion (forma, hora, desbaste, promedio, maximo, minimo, estado) ";
			$sql .= "VALUES ($forma, '$hora', $desbastei, $promedio, $maximo, $minimo, 0)";
		}

		if($accion=='mo'){
			$sql = "UPDATE pesada_inspeccion SET "; 
			$sql .= "forma = $forma, ";
			$sql .= "hora = '$hora', ";
			$sql .= "desbaste = $desbastei, ";
			$sql .= "promedio = $promedio, ";
			$sql .= "maximo = $maximo, ";
			$sql .= "minimo = $minimo WHERE idpi = $idpi";
		} 
		if($accion=='el'){
			$sql = "DELETE FROM pesada_inspeccion WHERE idpi = $idpi AND estado = 0";
		}

		$rs = mysql_query($sql);
		if($rs){
			if($accion=='a'){
				$_SESSION["sidpi"] = mysql_insert_id();
			}		
			$arrpi = array('success'=>true,'de'=>$sql);
		}else{
			$arrpi = array('success'=>true,'de'=>$sql);			
		}
	}
}
?>