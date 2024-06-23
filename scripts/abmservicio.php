<?php	
include('validar.php');	
include('coneccion.php');	
if(isset($_SESSION['susuario'])){
	$arrservicio = array('success'=>false,'de'=>'abmservicio');
	$accion = 'a';
	$patronfecha = "/^(([1-9]{1})|([0]{1}[1-9]{1})|([1-3]{1}[0-1]{1})|([1-2]{1}[0-9]{1}))([-]|[\/])(([1-9]{1})|([0-0]{1}[1-9]{1})|([1-1]{1}[0-2]{1}))([-]|[\/])([0-9]{4})$/";
	
	if(isset($_SESSION['saccion']))
		$accion = $_SESSION['saccion'];
	
	if($accion=='mo' || $accion=='el'){
		$idservicio = isset($_SESSION['sidservicio']) ? $_SESSION['sidservicio'] : 0;
		$idtoro = isset($_SESSION['sidtoro']) ? $_SESSION['sidtoro'] : 0;
	}	
	
	// SERVICIO
	$ia = filter_input(INPUT_POST, 'ia', FILTER_VALIDATE_INT);
	if($ia===FALSE || is_null($ia)) $ia = 0;

	$natural = filter_input(INPUT_POST, 'natural', FILTER_VALIDATE_INT);
	if($natural===FALSE || is_null($natural)) $natural = 0;
	
	$serviciodesde = filter_input(INPUT_POST, 'serviciodesde');
	if($serviciodesde===FALSE || is_null($serviciodesde))
		$serviciodesdeok = preg_match($patronfecha, $serviciodesde);

	$serviciohasta = filter_input(INPUT_POST, 'serviciohasta');
	if($serviciohasta===FALSE || is_null($serviciohasta))
		$serviciohastaok = preg_match($patronfecha, $serviciodesde);

	$garantia = filter_input(INPUT_POST, 'garantia', FILTER_VALIDATE_INT);
	if($garantia===FALSE || is_null($garantia)) $garantia = 0;

	if($accion=='a'){
		include('abmtoro.php');
		$idtoro = isset($_SESSION['sidtoro']) ? $_SESSION['sidtoro'] : 0;
		
		$sql = "INSERT INTO servicio (ia, naturals, desde, hasta, garantia, estado, idtoro) ";
		$sql .= "VALUES ($ia, $natural, '$serviciodesde', '$serviciohasta', $garantia, 0, $idtoro)";
	}

	if($accion=='mo'){
		include('abmtoro.php');
		if($idservicio==0){
			$sql = "INSERT INTO servicio (ia, naturals, desde, hasta, garantia, estado, idtoro) ";
			$sql .= "VALUES ($ia, $natural, '$serviciodesde', '$serviciohasta', $garantia, 0, $idtoro)";
		}else{			
			$sql = "UPDATE servicio SET "; 
			$sql .= "ia = $ia, ";
			$sql .= "naturals = $natural, ";
			$sql .= "desde = '$serviciodesde', ";
			$sql .= "hasta = '$serviciohasta', ";
			$sql .= "garantia = $garantia,";
			$sql .= "idtoro = $idtoro WHERE idservicio = $idservicio";
		}
	}	      
	if($accion=='el'){
		include('abmtoro.php');
		
		$sql = "DELETE FROM servicio WHERE idservicio = $idservicio AND estado = 0";
	}

	$rs = mysql_query($sql);
	if($rs){
		if($accion=='a' || ($idservicio==0 && $accion=='mo')){
			$_SESSION['sidservicio'] = mysql_insert_id();
		}
		$arrservicio = array('success'=>true,'de'=>'abmservicio');
	}else{
		
	}
}	
?>