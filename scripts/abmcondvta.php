<?php	
include('validar.php');	
include('coneccion.php');	
if(isset($_SESSION['susuario'])){
	$accion = 'a';
	$errorcv = false;
	$arrcv = array('success'=>false,'de'=>'abmcondvta');
	if(isset($_SESSION['saccion']))
		$accion = $_SESSION['saccion'];
	
	if($accion=='mo' || $accion=='el')
		$idcv = $_SESSION['sidcv'];

	// Condiciones de Venta
	$plazo = filter_input(INPUT_POST, 'plazo', FILTER_SANITIZE_SPECIAL_CHARS);

	$precioinicial = filter_input(INPUT_POST, 'precioinicial', FILTER_VALIDATE_FLOAT);
	if($precioinicial===FALSE || is_null($precioinicial)) $errorcv = true;

	$tipoprecio = filter_input(INPUT_POST, 'tipoprecio', FILTER_VALIDATE_INT);
	if($tipoprecio===FALSE || is_null($tipoprecio)) $errorcv = true;			

	if(!$errorcv){
		if($accion=='a'){			
			$sql = "INSERT INTO condiciones_vta (plazo, precioinicial, tipoprecio, estado) VALUES ('$plazo', $precioinicial, $tipoprecio,  0)";
		}

		if($accion=='mo'){
			$sql = "UPDATE condiciones_vta SET "; 
			$sql .= "plazo = '$plazo', ";
			$sql .= "precioinicial = $precioinicial, ";
			$sql .= "tipoprecio = $tipoprecio WHERE idcv = $idcv";
		}	      
		if($accion=='el'){
			$sql = "DELETE FROM condiciones_vta WHERE idcv = $idcv AND estado = 0";
		}

		$rs = mysql_query($sql);
		if($rs){
			if($accion=='a'){
				$_SESSION['sidcv'] = mysql_insert_id();
			}
			$arrcv = array('success'=>true,'de'=>'abmcondvta');
		}else{
			$arrcv = array('success'=>false,'de'=>'abmcondvta','sql'=>$sql);
		}
	}else{
		$arrcv = array('success'=>false,'de'=>'abmcondvta','sql'=>$sql);

	}	
}
?>