<?php	
	include('validar.php');
	include('coneccion.php');	
	if(isset($_SESSION['usuario'])){
		$idservicio = $_SESSION['sidservicio'];
		$sql = "SELECT idservicio, desde, hasta, garantia, idtoro, ia, naturals FROM servicio WHERE idservicio = $idservicio";
		$rs = mysql_query($sql);
		if(mysql_num_rows($rs)>0){
			while($fila=mysql_fetch_array($rs)){
				$arrse = array('success'=>true,
					'desde'=>$fila[1],
					'hasta'=>$fila[2],
					'garantia'=>$fila[3],
					'ia'=>$fila[5],
					'natural'=>$fila[6]);
					$_SESSION['sidtoro'] = $fila[4];
					include('buscar_datos_toro.php');					
			}
		}else{
			$arrse = array('success'=>false);
		}
	}else{
		$arrse = array('success'=>false);
	}
	//echo json_encode($arr);
?>