<?php
include('validar.php');
include('coneccion.php');
if(isset($_SESSION['susuario'])){
	$arr = array('success'=>false);
	if(isset($_SESSION['sidpersona']))
		unset($_SESSION['sidpersona']);
	if(isset($_SESSION['sidcliente']))
		unset($_SESSION['sidcliente']);
	if(isset($_SESSION['sidestablecimiento']))
		unset($_SESSION['sidestablecimiento']);
	
	//$idusuario = filter_input(INPUT_POST, 'param', FILTER_VALIDATE_INT);

	mysql_query("SET AUTOCOMMIT=0");
	mysql_query("START TRANSACTION");
	
	$sql = "UPDATE usuarios SET estado = 99 WHERE idusuario = $idusuario";
	$rsusuario = mysql_query($sql);
	$sql = "UPDATE cliente SET estado = 99 WHERE idusuario = $idusuario";
	$rscliente = mysql_query($sql);
	if($rscliente && $rsusuario){
		mysql_query("COMMIT");
		$arr = array('success'=>true);
	}else{
		mysql_query("ROLLBACK");		
	}
	return $arr;	
/*
	$sql = "SELECT perfil ";
	$sql .= "FROM usuarios  ";
	$sql .= "WHERE idusuario = $idusuario";
	$rs0 = mysql_query($sql);
	if(mysql_num_rows($rs0)>0){
		$fperfil = mysql_fetch_row($rs0);
		$perfil = $fperfil[0];
	}
	
	if($perfil==2){		
	
		$sql = "SELECT DISTINCT c.idpersona, c.idcliente, e.idestablecimiento ";
		$sql .= "FROM cliente c, cliente_establecimiento ec, establecimiento e ";
		$sql .= "WHERE c.idusuario = $idusuario and c.idcliente = ec.idcliente and ";
		$sql .= "e.idestablecimiento = ec.idestablecimiento";

		$rs1 = mysql_query($sql);
		if(mysql_num_rows($rs1)>0){			
			$fila=mysql_fetch_row($rs1);
			$idpersona = $fila[0];
			$idcliente = $fila[1];
			$idestablecimiento = $fila[2];

			mysql_query("SET AUTOCOMMIT=0");
			mysql_query("START TRANSACTION");

			$sql = "DELETE FROM creditos WHERE idusurio = $idusuario";
			$rscreditos = mysql_query($sql);
			
			$sql = "DELETE FROM cliente_establecimiento WHERE idcliente = $idcliente";
			$rsclienteest = mysql_query($sql);

			$sql = "DELETE FROM establecimiento WHERE idestablecimiento = $idestablecimiento";
			$rsestablecimiento = mysql_query($sql);

			$sql = "DELETE FROM cliente WHERE idcliente = $idcliente";
			$rscliente = mysql_query($sql);

			$sql = "DELETE FROM persona WHERE idpersona = $idpersona";
			$rspersona = mysql_query($sql);

			$sql = "DELETE FROM usuarios WHERE idusuario = $idusuario";
			$rsusuario = mysql_query($sql);

			if($rscreditos && $rsclienteest && $rsestablecimiento &&
				$rscliente && $rspersona && $rsusuario){
				mysql_query("COMMIT");
				$arr = array('success'=>true);
			}else{
				mysql_query("ROLLBACK");		
			}
		}
	}else{
		$sql = "DELETE FROM usuarios WHERE idusuario = $idusuario";
		$rsusuario = mysql_query($sql);
		if($rsusuario){
			$arr = array('success'=>true,'sql'=>$sql);
		}
	}
*/
	
}
?>
