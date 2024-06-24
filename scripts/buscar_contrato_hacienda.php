<?php	
	include('validar.php');
	include('coneccion.php');	
	if(isset($_SESSION['susuario'])){
		$contrato = $_POST['param'];
		$sql = "SELECT idhacienda FROM hacienda WHERE nrocontrato = $contrato";
		
		$rs = mysql_query($sql);
		if(mysql_num_rows($rs) > 0){
			$arr = array('success'=>true,'mensaje'=>'Ya existe un contrato con ese numero','cantidad'=>mysql_num_rows($rs));
		}else{
			$arr = array('success'=>false,'mensaje'=>'ok','cantidad'=>mysql_num_rows($rs));
		}
	}else{
		$arr = array('success'=>false,'mensaje'=>'No autorizado','cantidad'=>-1);
	}
	echo json_encode($arr);
?>