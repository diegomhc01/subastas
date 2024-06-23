<?php	
	include('validar.php');
	include('coneccion.php');	
	if(isset($_SESSION['susuario'])){
		$idpi = $_SESSION['sidpi'];
		$sql = "SELECT idpi, forma, hora, desbaste, promedio, maximo, minimo FROM pesada_inspeccion WHERE idpi = $idpi";
		$rs = mysql_query($sql);
		if(mysql_num_rows($rs)>0){
			while($fila=mysql_fetch_array($rs)){
				$arrpi = array('success'=>true,
					'forma'=>$fila[1],
					'hora'=>$fila[2],
					'desbaste'=>$fila[3],
					'promedio'=>$fila[4],
					'maximo'=>$fila[5],
					'minimo'=>$fila[6]);
			}
		}else{
			$arrpi = array('success'=>false);
		}
	}else{
		$arrpi = array('success'=>false);
	}
	//echo json_encode($arr);
?>