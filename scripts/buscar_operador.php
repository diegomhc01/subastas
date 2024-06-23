<?php
	session_start();
	include('coneccion.php');		
	$operador = $_SESSION['operador'];
	echo json_encode(array('operador'=>$operador));
?>