<?php	
	include('validar.php');
	include('coneccion.php');	
	$arr = array('success'=>false,'mensaje'=>'');
	$cuit = $_POST['param'];
	$sql = "SELECT cuit FROM cliente WHERE cuit = '$cuit'";

	$rs = mysql_query($sql);
	if(mysql_num_rows($rs)>0){
		$arr = array('success'=>true,'mensaje'=>'CUIT existente');
	}
	echo json_encode($arr);	
?>