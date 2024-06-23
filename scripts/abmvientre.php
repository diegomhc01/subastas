<?php	
include('validar.php');	
include('coneccion.php');	
if(isset($_SESSION['susuario'])){		
	$arrvientre = array('success'=>false,'de'=>'abmvientre');
	$accion = 'a';
	
	if(isset($_SESSION['saccion']))
		$accion = $_SESSION['saccion'];
	
	if($accion=='mo' || $accion=='el')
		$idvientre = isset($_SESSION['sidvientre']) ? $_SESSION['sidvientre'] : 0;

	///VIENTRES
	$preniados = filter_input(INPUT_POST, 'preniados', FILTER_VALIDATE_INT);
	if($preniados===FALSE || is_null($preniados)) $preniados = 0;

	$preniados = $_POST['preniados'];
	$conservicio = filter_input(INPUT_POST, 'conservicio', FILTER_VALIDATE_INT);
	if($conservicio===FALSE || is_null($conservicio)) $conservicio = 0;

	$conservicio = $_POST['conservicio'];
	$vacios = filter_input(INPUT_POST, 'vacios', FILTER_VALIDATE_INT);
	if($vacios===FALSE || is_null($vacios)) $vacios = 0;

	if($accion=='a'){
		include('abmservicio.php');
		$idservicio = isset($_SESSION['sidservicio']) ? $_SESSION['sidservicio'] : 0;

		$sql = "INSERT INTO vientre (preniados, conservicio, vacios, idservicio, estado) VALUES ($preniados, $conservicio, $vacios, $idservicio, 0)";
	}

	if($accion=='mo'){
		include('abmservicio.php');
		if($idvientre==0){
			$sql = "INSERT INTO vientre (preniados, conservicio, vacios, idservicio, estado) VALUES ($preniados, $conservicio, $vacios, $idservicio, 0)";
		}else{			
			$sql = "UPDATE vientre SET "; 
			$sql .= "preniados = $preniados, ";
			$sql .= "conservicio = $conservicio, ";
			$sql .= "vacios = '$vacios',";
			$sql .= "idservicio = idservicio WHERE idvientre = $idvientre";
		}
	}    
	if($accion=='el'){
		include('abmservicio.php');

		$sql = "DELETE FROM vientre WHERE idvientre = $idvientre AND estado = 0";
	}

	$rs = mysql_query($sql);
	if($rs){
		if($accion=='a'|| ($idvientre==0 && $accion=='mo')){
			$_SESSION['sidvientre'] = mysql_insert_id();						
		}			
		$arrvientre = array('success'=>true,'de'=>'abmvientre');
	}
}
?>