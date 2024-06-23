<?php	
	include('validar.php');	
	include('coneccion.php');	
	if(isset($_SESSION['susuario'])){
		$idfirma = $_SESSION['sidfirma'];		

		$arr = array('success'=>false,'idremate'=>-1);

		$sql = "SELECT idremate, tipo FROM remate WHERE estado = 1 and idfirma = $idfirma";
		
		$rs = mysql_query($sql);
		if(mysql_num_rows($rs) > 0){
			$fremate = mysql_fetch_row($rs);
			$arr = array('success'=>true,'idremate'=>$fremate[0]);
			$_SESSION['sidremate'] = $fremate[0];
			$_SESSION['stiporemate'] = $fremate[1];
		}
		echo json_encode($arr);
	}
?>