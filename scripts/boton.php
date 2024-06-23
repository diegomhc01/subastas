<?php
	include('validar.php');
	if(isset($_SESSION['smensaje'])){
		echo json_encode(array('mensaje'=>$_SESSION['smensaje']));
	}else{
		echo json_encode(array('mensaje'=>''));
	}
?>