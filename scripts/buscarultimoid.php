<?php
	session_start();
	include('coneccion.php');
	if(isset($_SESSION['susuario'])){
		//EXTRAEMOS CONTENIDO DE CHAT		
		$sql = " SELECT MAX(idchat) AS idchat FROM chat WHERE usrr = '".$_SESSION['usuario']."'";
		$rs = mysql_query($sql);		
		while($row=mysql_fetch_array($rs)){
			$arr[] = array('idchat'=>$row['idchat']);
		}
		echo json_encode($arr);	
	}
?>