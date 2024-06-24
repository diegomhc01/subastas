<?php
	include('validar.php');
	include('coneccion.php');	
	if(isset($_SESSION['susuario'])){
		$detalle = addslashes($_POST['texto']);
		$idremate = $_SESSION['sidremate'];
		$sql = "INSERT INTO desarrollosub (detalle, idremate, hora, estado, usuario) VALUES ('$detalle',$idremate, DATE_FORMAT(CURRENT_TIMESTAMP(), '%H:%i:%s') ,0, 'todos')";
		$rs = mysql_query($sql,$coneccion);
		if($rs){
			$arr = array('success'=>true);	
		}else{
			$arr = array('success'=>false);	
		}
		echo json_encode($arr);
	}	
?>