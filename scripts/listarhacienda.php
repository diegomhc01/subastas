<?php
include('validar.php');	
//include('coneccion.php');	

if(isset($_SESSION['susuario'])){	
	$_SESSION['sasignar'] = false;
	$_SESSION['srematadas'] = false;
	$_SESSION['srematar'] = false;
	if(isset($_POST['chkasignar']) && $_POST['chkasignar']==1) $_SESSION['sasignar'] = true;	
	if(isset($_POST['chkrematadas']) && $_POST['chkrematadas']==1) $_SESSION['srematadas'] = true;
	if(isset($_POST['chkrematar']) && $_POST['chkrematar']) $_SESSION['srematar'] = true;
	echo json_encode(array('r'=>true));
}
?>