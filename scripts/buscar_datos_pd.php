<?php	
	include('validar.php');
	include('coneccion.php');	
	if(isset($_SESSION['susuario'])){
		$idpd = $_SESSION['sidpd'];
		$sql = "SELECT idpd, ubicacion, arreo, camion, total, balanza, lugarcamion, ";
		$sql .= "observaciones ";
		$sql .= "FROM pesada_definitiva WHERE idpd = $idpd";
		$rs = mysql_query($sql);
		if(mysql_num_rows($rs)>0){
			while($fila=mysql_fetch_array($rs)){
				$arrpd = array('success'=>true,
					'ubicacion'=>$fila[1],
					'arreo'=>$fila[2],
					'camion'=>$fila[3],
					'total'=>$fila[4],
					'balanza'=>$fila[5],
					'lugarcamion'=>$fila[6],
					'osvervacionesd'=>$fila[7]);
			}
		}else{
			$arrpd = array('success'=>false);
		}
	}else{
		$arrpd = array('success'=>false);
	}
	//echo json_encode($arr);
?>