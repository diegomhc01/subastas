<?php	
include('validar.php');	
include('coneccion.php');	
if(isset($_SESSION['susuario'])){
	$accion = 'a';
	$arreva = array('success'=>false,'de'=>'abmevaluacion');
	if(isset($_SESSION['saccion']))
		$accion = $_SESSION['saccion'];
	
	if($accion=='mo' || $accion=='el')
		$idevaluacion = $_SESSION['sidevaluacion'];

	// EVALUACION 
	$evacalidad = filter_input(INPUT_POST, 'evacalidad', FILTER_VALIDATE_INT);
	if($evacalidad===FALSE || is_null($evacalidad)) $evacalidad = 0;		

	$evaestado = filter_input(INPUT_POST, 'evaestado', FILTER_VALIDATE_INT);
	if($evaestado===FALSE || is_null($evaestado)) $evaestado = 0;		

	$evasanidad = filter_input(INPUT_POST, 'evasanidad', FILTER_VALIDATE_INT);
	if($evasanidad===FALSE || is_null($evasanidad)) $evasanidad = 0;		

	$evauniformidad = filter_input(INPUT_POST, 'evauniformidad', FILTER_VALIDATE_INT);
	if($evauniformidad===FALSE || is_null($evauniformidad)) $evauniformidad = 0;		

	$evaobservaciones = filter_input(INPUT_POST, 'evaobservaciones', FILTER_SANITIZE_SPECIAL_CHARS);		
	if($evaobservaciones===FALSE || is_null($evaobservaciones)) $evaobservaciones = '';		

	if($accion=='a'){			
		$sql = "INSERT INTO evaluacion (calidad, estadoeva, sanidad, uniformidad, observaciones, estado) VALUES ";
		$sql .= "($evacalidad, $evaestado, $evasanidad, $evauniformidad, '$evaobservaciones', 0)";
	}

	if($accion=='mo'){
		$sql = "UPDATE evaluacion SET "; 
		$sql .= "calidad = $evacalidad, ";
		$sql .= "estadoeva = $evaestado, ";
		$sql .= "sanidad = $evasanidad, ";
		$sql .= "uniformidad = $evauniformidad, ";
		$sql .= "observaciones = '$evaobservaciones' WHERE idevaluacion = $idevaluacion";
	}	      
	if($accion=='el'){
		$sql = "DELETE FROM evaluacion WHERE idevaluacion = $idevaluacion AND estado = 0";
	}

	$rs = mysql_query($sql);
	if($rs){
		if($accion=='a'){
			$_SESSION['sidevaluacion'] = mysql_insert_id();
		}			
		$arreva = array('success'=>true,'de'=>'abmevaluacion');
	}
}
?>