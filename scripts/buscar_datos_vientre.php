<?php	
	include('validar.php');
	include('coneccion.php');	
	if(isset($_SESSION['susuario'])){
		$idvientre = $_SESSION['sidvientre'];
		$sql = "SELECT idvientre, preniados, conservicio, vacios, idservicio FROM vientre WHERE idvientre = $idvientre";
		$rs = mysql_query($sql);
		if(mysql_num_rows($rs)>0){
			while($fila=mysql_fetch_array($rs)){
				$arrvi = array('success'=>true,
					'preniados'=>$fila[1],
					'conservicio'=>$fila[2],
					'vacios'=>$fila[3]);
					$_SESSION['sidservicio'] = $fila[4];
					include('buscar_datos_servicio.php');
			}
		}else{
			$arrvi = array('success'=>false);
		}
	}else{
		$arrvi = array('success'=>false);
	}
	//echo json_encode($arr);
?>