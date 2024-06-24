<?php	
include('validar.php');	
include('coneccion.php');	
if(isset($_SESSION['susuario'])){
	$accion = 'a';
	$error = false;
	$arr = array('success'=>false);
	$idcliente = $_SESSION['sidcliente'];
	if(isset($_SESSION['saccion']))
		$accion = $_SESSION['saccion'];
	
	if($accion=='m' || $accion=='e')
		$idestablecimiento = $_SESSION['sidestablecimiento'];

	$renspa = filter_input(INPUT_POST, 'renspav', FILTER_VALIDATE_FLOAT);
	if($renspa===FALSE || is_null($renspa)) $error = true;
    $detalle = filter_input(INPUT_POST, 'establecimientov', FILTER_SANITIZE_SPECIAL_CHARS);
    if($detalle===FALSE || is_null($detalle)) $error = true;
    $codprov=filter_input(INPUT_POST, 'provinciav', FILTER_SANITIZE_SPECIAL_CHARS);
    if($codprov===FALSE || is_null($codprov)) $error = true;
    $idlocalidad = filter_input(INPUT_POST, 'localidadv', FILTER_VALIDATE_INT);
    if($idlocalidad===FALSE || is_null($idlocalidad)) $error = true;
    $lat = filter_input(INPUT_POST, 'latv', FILTER_SANITIZE_SPECIAL_CHARS);
    if($lat===FALSE || is_null($lat)) $lat = '';
    $lon = filter_input(INPUT_POST, 'lonv', FILTER_SANITIZE_SPECIAL_CHARS);
    if($lon===FALSE || is_null($lon)) $lon = '';

	if(!$error){
		$detalle = strtoupper($detalle);
		if($accion=='a'){			
			$sql = "INSERT INTO establecimiento (detalle, idlocalidad, codprov, lat, lon, estado) ";
			$sql .= "VALUES ('$detalle', $idlocalidad, '$codprov', '$lat', '$lon', 0)";
		}

		if($accion=='m'){
			$sql = "UPDATE establecimiento SET ";
			$sql .="detalle = '$detalle', ";
			$sql .="idlocalidad = $idlocalidad, ";
			$sql .="codprov = '$codprov', ";
			$sql .="lat = '$lat', ";
			$sql .="lon = '$lon' ";
			$sql .="WHERE idestablecimiento = $idestablecimiento";			
		}	      
		if($accion=='e'){
			$sql = "UPDATE establecimiento SET estado = 9 WHERE idestablecimiento = $idestablecimiento";
		}

		$rs = mysql_query($sql);
		if($rs){
			if($accion=='a'){
				$idestablecimiento = mysql_insert_id();
				$sql = "INSERT INTO cliente_establecimiento (idcliente, idestablecimiento, renspa) ";
				$sql .="VALUES ($idcliente, $idestablecimiento, '$renspa')";
				$rs1 = mysql_query($sql);
				if($rs1){
					$arr = array('success'=>true);
				}
			}
			if($accion=='m'){
				$sql = "UPDATE cliente_establecimiento ";
				$sql .="SET renspa = '$renspa' ";
				$sql .="WHERE idcliente = $idcliente and idestablecimiento = $idestablecimiento";		
				$rs1 = mysql_query($sql);
				if($rs1){
					$arr = array('success'=>true);
				}
			}
			$arr = array('success'=>true);
		}
	}
	echo json_encode($arr);	
}
?>