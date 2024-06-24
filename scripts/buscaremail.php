<?php	
	include('validar.php');
	include('coneccion.php');	
	$arr = array('success'=>false,'mensaje'=>'');
	$usuario = $_POST['param'];
	$sql = "SELECT usuario FROM usuarios WHERE usuario = '$usuario'";
	
	$rs = mysql_query($sql);
	if(mysql_num_rows($rs) > 0){
		$arr = array('success'=>true,'mensaje'=>'Email existente');
	}
	echo json_encode($arr);
?>