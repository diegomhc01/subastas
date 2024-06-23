<?php
	include('validar.php');
	include('coneccion.php');
	if(isset($_SESSION['susuario'])){
		$vusuario = $_SESSION['susuario'];
		//$voperador = $_SESSION['operador'];
		$sql = "SELECT * FROM uconectados WHERE usuario = '$vusuario'";
		$rs1 = mysql_query($sql);							
		if(mysql_num_rows($rs1)>0){
			$sql = "UPDATE uconectados SET idsesion = '".session_id()."', estado = 0 WHERE usuario = '$vusuario'";
		}else{
			$sql = "INSERT INTO uconectados (usuario, idsesion, estado) VALUES ('$vusuario', '".session_id()."',0)";
		}				
		$rs2 = mysql_query($sql);										
		if($rs2){
			$sql = "SELECT * FROM uconectadosoa WHERE usuario = '$vusuario'";
			$rs3 = mysql_query($sql);
			if(mysql_num_rows($rs3)>0){
				$sql = "UPDATE uconectadosoa SET idsesion = '".session_id()."', estado = 0 WHERE usuario = '$vusuario'";	
			}else{
				$sql = "INSERT INTO uconectadosoa (usuario, idsesion, estado) VALUES ('$vusuario', '".session_id()."',0)";	
			}
			$rs4 = mysql_query($sql);
		}	
	}
?>