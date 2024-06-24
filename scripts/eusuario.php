<?php 
include('validar.php');	
include('coneccion.php');
if(isset($_SESSION['susuario'])){

	mysql_query("SET AUTOCOMMIT=0");
	mysql_query("START TRANSACTION");
	
	$sql = "UPDATE usuarios SET estado = 1 WHERE idusuario = $idusuario";	
	$rs = mysql_query($sql);	

	if($perfil==1){		
		$sql = "UPDATE persona SET estado = 1 WHERE idpersona = $idpersona";
		$rs1 = mysql_query($sql);

		$sql = "UPDATE cliente SET estado = 1 WHERE idcliente = $idcliente";
		$rs2 = mysql_query($sql);


		$sql = "UPDATE establecimiento SET estado = 1 WHERE idestablecimiento = $idestablecimiento";	
		$rs3 = mysql_query($sql);

	}
	if($perfil==1){		
		if($rs && $rs1 && $rs2 && $rs3){
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
