<?php 
include('validar.php');	
include('coneccion.php');
if(isset($_SESSION['susuario'])){
	$idfirma = $_SESSION['sidfirma'];
	mysql_query("SET AUTOCOMMIT=0");
	mysql_query("START TRANSACTION");
	
	$sql = "INSERT INTO usuarios (usuario, clave, perfil, estado, apellido, nombre, idfirma) ";
	$sql .= "VALUES ('$usuario', '$clave', $perfil, 0, '$apellido', '$nombre', $idfirma)";

	$rs = mysql_query($sql);
	$idusuario = mysql_insert_id();

	if($perfil==1){
		$apeynomv = $apellido.', '.$nombre;
		$sql = "INSERT INTO persona (apeynom, estado) VALUES ('$apeynomv', 0)";
		$rs1 = mysql_query($sql);
		$idpersona = mysql_insert_id();

		$sql = "INSERT INTO cliente (estado, cuit, contacto, telefono, email, idpersona, idusuario, idfirma) VALUES ";
		$sql .= "(0, ";
		$sql .= "'$cuitv', ";
		$sql .= "'$contactov', ";
		$sql .= "'$telefonov', ";
		$sql .= "'$emailv', ";
		$sql .= "$idpersona, ";
		$sql .= "$idusuario, ";
		$sql .= "$idfirma)";

		$rs2 = mysql_query($sql);
		$idcliente = mysql_insert_id();
/*
		$sql = "INSERT INTO establecimiento (detalle, idlocalidad, codprov, lat, lon, estado) VALUES ";				
		$sql .= "('$establecimientov', "; 
		$sql .= "$localidadv, "; 
		$sql .= "'$provinciav', ";
		$sql .= "'$lat', ";
		$sql .= "'$lon', 0)"; 
		$rs3 = mysql_query($sql);
		$idestablecimiento = mysql_insert_id();

		$sql = "INSERT INTO cliente_establecimiento (idcliente, idestablecimiento, renspa) VALUES ($idcliente,$idestablecimiento,'$renspav')";
		$rs4 = mysql_query($sql);
*/
		$sql = "INSERT INTO creditos (usuario, monto, estado) VALUES ('$usuario', 0, 0)";
		$rs5 = mysql_query($sql);

	}
	if($perfil==1){		
		if($rs && $rs1 && $rs2 && $rs5){
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
