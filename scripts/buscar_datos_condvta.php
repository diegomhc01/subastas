<?php	
	include('validar.php');
	include('coneccion.php');	
	if(isset($_SESSION['susuario'])){
		$idcv = $_SESSION['sidcv'];
		$sql = "SELECT idcv, plazo, precioinicial, tipoprecio FROM condiciones_vta WHERE idcv = $idcv";
		$rs = mysql_query($sql);
		if(mysql_num_rows($rs)>0){
			while($fila=mysql_fetch_array($rs)){
				$arrcv = array('success'=>true,
					'plazo'=>$fila[1],
					'precioinicial'=>$fila[2],
					'tipoprecio'=>$fila[3]);
			}
		}else{
			$arrcv = array('success'=>false);
		}
	}else{
		$arrcv = array('success'=>false);
	}
	//echo json_encode($arr);
?>