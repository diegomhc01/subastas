<?php 
include('validar.php');	
include('coneccion.php');
if(isset($_SESSION['susuario'])){

	mysql_query("SET AUTOCOMMIT=0");
	mysql_query("START TRANSACTION");
	
	$sql = "UPDATE usuarios SET ";
	$sql .= "perfil = $perfil, ";
	$sql .= "apellido = '$apellido', ";
	$sql .= "nombre = '$nombre' ";
	$sql .= "WHERE idusuario = $idusuario";	
	$rs = mysql_query($sql);	

	if($perfil==1){
		$apeynomv = $apellido.', '.$nombre;
		$sql = "UPDATE persona SET "; 
		$sql .= "apeynom = '$apeynomv' WHERE idpersona = $idpersona";
		$rs1 = mysql_query($sql);

		$sql = "UPDATE cliente SET "; 
		$sql .= "cuit = '$cuitv', ";
		$sql .= "contacto = '$contactov',";
		$sql .= "telefono = '$telefonov', ";
		$sql .= "email = '$emailv' WHERE idcliente = $idcliente";
		$rs2 = mysql_query($sql);

/*
		$sql = "UPDATE establecimiento SET "; 
		$sql .= "detalle = '$establecimientov', ";
		$sql .= "idlocalidad = $localidadv, ";
		$sql .= "codprov = '$provinciav', ";
		$sql .= "lat = '$lat', ";
		$sql .= "lon = '$lon' "; 
		$sql .= "WHERE idestablecimiento = $idestablecimiento";	
		$rs3 = mysql_query($sql);
*/
	}
	if($perfil==1){		
		if($rs && $rs1 && $rs2){
			mysql_query("COMMIT");
			$mensaje = "SE HA GRABADO CORRECTAMENTE";
			$arr = array('success'=>true,'mensaje'=>$mensaje);
		}else{
			mysql_query("ROLLBACK");
			$mensaje = "ERROR";
			$arr = array('success'=>false,'mensaje'=>$mensaje);
		}
	}
	if($perfil>1 && $perfil<7){
		if($rs){
			mysql_query("COMMIT");
			$mensaje = "SE HA GRABADO CORRECTAMENTE";
			$arr = array('success'=>true,'mensaje'=>$mensaje);
		}else{
			mysql_query("ROLLBACK");
			$mensaje = "ERROR";
			$arr = array('success'=>false,'mensaje'=>$mensaje);
		}		
	}
}
?>		
