<?php	
include('validar.php');	
include('coneccion.php');	
if(isset($_SESSION['susuario'])){
	$accion = 'a';
	$arrsanidad = array('success'=>false,'de'=>'abmsanidad');

	if(isset($_SESSION['saccion']))
		$accion = $_SESSION['saccion'];
	
	if($accion=='mo')
		$idsanidad = $_SESSION['sidsanidad'];

	if(isset($_SESSION['sidsanidad'])){
		$idsanidad = $_SESSION['sidsanidad'];
	}
	// SANIDAD
	$tuberculosis = filter_input(INPUT_POST, 'tuberculosis', FILTER_VALIDATE_INT);
	if($tuberculosis===FALSE || is_null($tuberculosis)) $tuberculosis = 0;

	$brucelosis = filter_input(INPUT_POST, 'brucelosis', FILTER_VALIDATE_INT);
	if($brucelosis===FALSE || is_null($brucelosis)) $brucelosis = 0;
			
	if($accion=='a'){			
		$sql = "INSERT INTO sanidad (tuberculosis, brucelosis, estado) ";
		$sql .= "VALUES ($tuberculosis, $brucelosis, 0)";
	}

	if($accion=='mo'){
		$sql = "UPDATE sanidad SET "; 
		$sql .= "tuberculosis = $tuberculosis, ";
		$sql .= "brucelosis = $brucelosis ";
		$sql .= "WHERE idsanidad = $idsanidad";
	}	      

	$rs = mysql_query($sql);
	if($rs){
		if($accion=='a'){
			$_SESSION['sidsanidad'] = mysql_insert_id();
		}
		$arrsanidad = array('success'=>true,'de'=>'abmsanidad');
	}
}
?>