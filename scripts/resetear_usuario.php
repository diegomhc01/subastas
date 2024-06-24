<?php
include('validar.php');	
if($_SESSION['susuario']){
	$error = false;
	$arr = array('success'=>false,'usuario'=>'','apeynom'=>'');
	$usuario = $_POST['param2'];
	$apeynom = $_POST['param3'];
	$idusuario = filter_input(INPUT_POST, 'param', FILTER_VALIDATE_INT);
	if($idusuario===FALSE || is_null($idusuario) || strlen($idusuario)==0) $error=true;	
	if(!$error){
		$_SESSION['sidusuariog'] = $idusuario;
		$arr = array('success'=>true,'usuario'=>$usuario,'apeynom'=>$apeynom);
	}

	echo json_encode($arr);
}		
?>
