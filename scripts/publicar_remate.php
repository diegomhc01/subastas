<?php
	include('validar.php');	
	include('coneccion.php');		
	if(isset($_SESSION['susuario'])){
		if($_POST['param']!='' && $_POST['param1']!=''){
			if(!isset($_SESSION['sidremate']))
				$_SESSION['sidremate'] = $_POST['param'];

			$idremate = $_SESSION['sidremate'];

			$sql = "SELECT idlote FROM lotes WHERE idremate = $idremate";
			$rs0 = mysql_query($sql);
			$accion = $_POST['param1'];

			if(mysql_num_rows($rs0)>0){
				if($accion=='p')
					$sql = "UPDATE remate SET publicado = 1 WHERE idremate = $idremate";
				if($accion=='n')				
					$sql = "UPDATE remate SET publicado = 0 WHERE idremate = $idremate";

				$rs = mysql_query($sql);	

				if($rs){
					unset($_SESSION['sidremate']);
					echo json_encode(array('success'=>true, 'idremate'=>$idremate));
				}else{
					echo json_encode(array('success'=>false, 'idremate'=>$idremate));
				}
			}
		}
	}
?>
