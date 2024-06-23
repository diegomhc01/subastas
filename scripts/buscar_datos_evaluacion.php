<?php	
	include('validar.php');
	include('coneccion.php');	
	if(isset($_SESSION['susuario'])){
		$idevaluacion = $_SESSION['sidevaluacion'];
		$sql = "SELECT idevaluacion, calidad, estadoeva, sanidad, uniformidad, observaciones FROM evaluacion WHERE idevaluacion = $idevaluacion";
		$rs = mysql_query($sql);
		if(mysql_num_rows($rs)>0){
			while($fila=mysql_fetch_array($rs)){
				$arrev = array('success'=>true,
					'calidad'=>$fila[1],
					'estadoeva'=>$fila[2],
					'sanidad'=>$fila[3],
					'uniformidad'=>$fila[4],
					'observacioneseva'=>$fila[5]);
			}
		}else{
			$arrev = array('success'=>false);
		}
	}else{
		$arrev = array('success'=>false);
	}
	//echo json_encode($arr);
?>