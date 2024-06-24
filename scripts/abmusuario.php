<?php
include('validar.php');	
include('coneccion.php');
if(isset($_SESSION['susuario'])){
	$arr = array('success'=>false,'mensaje'=>'');

	$idfirma = $_SESSION['sidfirma'];
	$accion = 'a';	

	if(isset($_SESSION['grabarusuario']))
		$accion = $_SESSION['grabarusuario']; 
	if(isset($_POST['param1']))
		$accion = $_POST['param1'];

	if($accion=='a' || $accion=='mo'){

		if($accion=='a'){		
			$usuario = filter_input(INPUT_POST, 'usuario', FILTER_SANITIZE_SPECIAL_CHARS);
			if($usuario===FALSE || is_null($usuario)) $error = true;
		}
		$apellido = filter_input(INPUT_POST, 'apellido', FILTER_SANITIZE_SPECIAL_CHARS);
		if($apellido===FALSE || is_null($apellido)) $error = true;	
		$nombre = filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_SPECIAL_CHARS);
		if($nombre===FALSE || is_null($nombre)) $error = true;	
		$perfil = filter_input(INPUT_POST, 'perfil', FILTER_VALIDATE_INT);
		if($perfil===FALSE || is_null($perfil)) $error = true;
		if($error!==FALSE && $perfil==1){		
			$cuitv = filter_input(INPUT_POST, 'cuitv', FILTER_VALIDATE_FLOAT);
			if($cuitv===FALSE || is_null($cuitv)) $error = true;
			$contactov = filter_input(INPUT_POST, 'contactov', FILTER_SANITIZE_SPECIAL_CHARS);
			if($apellido===FALSE || is_null($apellido)) $contactov = '';	
			$telefonov = filter_input(INPUT_POST, 'telefonov', FILTER_SANITIZE_SPECIAL_CHARS);
			if($telefonov===FALSE || is_null($telefonov)) $telefonov = '';
			$emailv = filter_input(INPUT_POST, 'emailv', FILTER_VALIDATE_EMAIL);
			if($emailv===FALSE || is_null($emailv)) $error = true;
			$contactov = strtoupper($contactov);
			/*
			$renspav = filter_input(INPUT_POST, 'renspav', FILTER_VALIDATE_FLOAT);
			if($renspav===FALSE || is_null($renspav)) $renspav = '';
			$establecimientov = filter_input(INPUT_POST, 'establecimientov', FILTER_SANITIZE_SPECIAL_CHARS);
			if($establecimientov===FALSE || is_null($establecimientov)) $error = true;	
			$provinciav = filter_input(INPUT_POST, 'provinciav', FILTER_SANITIZE_SPECIAL_CHARS);
			if($provinciav===FALSE || is_null($provinciav)) $error = true;	
			$localidadv = filter_input(INPUT_POST, 'localidadv', FILTER_VALIDATE_INT);
			if($localidadv===FALSE || is_null($localidadv)) $error = true;		
			$lon = filter_input(INPUT_POST, 'lonv', FILTER_SANITIZE_SPECIAL_CHARS);
			if($lon===FALSE || is_null($lon)) $lon = '';	
			$lat = filter_input(INPUT_POST, 'latv', FILTER_SANITIZE_SPECIAL_CHARS);
			if($lat===FALSE || is_null($lat)) $lat = '';
			*/
		}	
	}
	if($error!==FALSE){
		$apellido = strtoupper($apellido);
		$nombre = strtoupper($nombre);
		if($accion == 'a'){
			$clave = $usuario;
			$sql = "SELECT usuario FROM usuarios WHERE usuario = '$usuario'";
			$rsusuario = mysql_query($sql);
			if(mysql_num_rows($rsusuario)==0){
				include('ausuario.php');
			}else{
				$arr = array('success'=>false,'mensaje'=>'USUARIO REPETIDO');
			}
		}	
		if($accion == 'mo'){
			if(isset($_SESSION['sidusuariog'])) $idusuario = $_SESSION['sidusuariog'];
			if(isset($_SESSION['sidpersona'])) $idpersona = $_SESSION['sidpersona'];
			if(isset($_SESSION['sidcliente'])) $idcliente = $_SESSION['sidcliente'];
			//if(isset($_SESSION['sidestablecimiento'])) $idestablecimiento = $_SESSION['sidestablecimiento'];
			if(isset($idusuario) && isset($idpersona) && isset($idcliente) && isset($idestablecimiento)){
				include('musuario.php');
			}
		}
		if($accion == 'el'){
			if(isset($_SESSION['sidusuariog'])) $idusuario = $_SESSION['sidusuariog'];
			//if(isset($_SESSION['sidpersona'])) $idpersona = $_SESSION['sidpersona'];
			//if(isset($_SESSION['sidcliente'])) $idcliente = $_SESSION['sidcliente'];
			//if(isset($_SESSION['sidestablecimiento'])) $idestablecimiento = $_SESSION['sidestablecimiento'];
			if(isset($idusuario)){ // && isset($idpersona) && isset($idcliente)
				//include('eusuario.php');
				include('eliminarusuario.php');
			}
		}		
	}
	
	echo json_encode($arr);
}	
?>
