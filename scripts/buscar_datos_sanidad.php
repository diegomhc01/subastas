<?php	
	include('validar.php');
	include('coneccion.php');	
	if(isset($_SESSION['susuario'])){
		$idsanidad = $_SESSION['sidsanidad'];
		$sql = "SELECT tuberculosis, brucelosis FROM sanidad WHERE idsanidad = $idsanidad";
		$rs = mysql_query($sql);
		if(mysql_num_rows($rs)>0){
			while($fila=mysql_fetch_array($rs)){
				$arrsa = array('success'=>true,
					'tuberculosis'=>$fila[0],
					'brucelosis'=>$fila[1]);
			}
		}else{
			$arrsa = array('success'=>false);
		}
	}else{
		$arrsa = array('success'=>false);
	}
?>