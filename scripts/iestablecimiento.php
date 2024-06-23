<?php
	include('validar.php');
	include('coneccion.php');

	$idusuario = filter_input(INPUT_POST, 'param', FILTER_VALIDATE_INT);
	if(!$idusuario || is_null($idusuario)){
		$arr =array('success'=>false);	
	}else {
		$sql = "SELECT idcliente FROM cliente WHERE idusuario = $idusuario";
		$rs = mysql_query($sql);
		if(mysql_num_rows($rs)>0){
			$fila = mysql_fetch_row($rs);
			$_SESSION['sidusuariog'] = $idusuario;
			$_SESSION['sidcliente'] = $fila[0];	
		}
		$arr =array('success'=>true);
	}
	echo json_encode($arr);
?>