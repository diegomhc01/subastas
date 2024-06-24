<?php
	include('validar.php');
	include('coneccion.php');
	$arr = array('success'=>false);
	if(isset($_SESSION['susuario'])){
		$usuarioselec = $_POST['param'];

		if($_SESSION['perfil']==1){
			$sql = "UPDATE uconectados SET operador = '$usuarioselec'  WHERE usuario = '".$_SESSION['susuario']."'"; 
			$rs4 = mysql_query($sql);
			if($rs4){
				$_SESSION['operadorchat'] = $fila[0];
				$arr = array('success'=>true,'sql'=>$sql);
			}else{
				$arr = array('success'=>false,'sql'=>$sql);
			}	
		}
		if($_SESSION['perfil']==3)
			$sql = "UPDATE uconectados SET estado = 0 WHERE operador = (SELECT usuario FROM usuarios WHERE idusuario = ".$_SESSION['operadorn'].")"; 
		if($_SESSION['perfil']==4)
			$sql = "UPDATE uconectadosoa SET estado = 0"; 
		
		$arr = array('success'=>false,'sql'=>$sql);
		$rs = mysql_query($sql);	
		if($rs){
				if($_SESSION['perfil']==3)
					$sql = "UPDATE uconectados SET estado = 1 WHERE usuario = '$usuarioselec' AND operador = (SELECT usuario FROM usuarios WHERE idusuario = ".$_SESSION['operadorn'].")"; 
				if($_SESSION['perfil']==4)
					$sql = "UPDATE uconectadosoa SET estado = 1 WHERE usuario = '$usuarioselec'"; 

				$rs1 = mysql_query($sql, $coneccion);	
				if($rs1){
					$arr = array('success'=>true,'sql'=>$sql);
				}else{
					$arr = array('success'=>false,'sql'=>$sql);
				}
		}else{
			$arr = array('success'=>false,'sql'=>$sql);
		}

	}
	json_encode($arr);
?>