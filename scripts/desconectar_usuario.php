<?php
	include('validar.php');
	include('coneccion.php');
	if(isset($_SESSION['susuario'])){
		$sql = "DELETE FROM uconectados WHERE usuario = '".$_SESSION['susuario']."'";
		$rs1 = mysql_query($sql);
		$sql = "DELETE FROM uconectadosoa WHERE usuario = '".$_SESSION['susuario']."'";
		$rs2 = mysql_query($sql);
	}
?>