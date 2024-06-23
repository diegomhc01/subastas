<?php
	include('validar.php');	
	include('coneccion.php');		
	if(isset($_SESSION['susuario'])){
		if($_POST['param']!='' && $_POST['param1']!=''){
			if(!isset($_SESSION['sidremate']))
				$_SESSION['sidremate'] = $_POST['param'];

			$idremate = $_SESSION['sidremate'];

			$sql = "SELECT idlote FROM lotes WHERE idremate = $idremate and estado = 1";
			$rs0 = mysql_query($sql);
			if(mysql_num_rows($rs0)==0){
				$sql = "UPDATE remate SET estado = 0 WHERE idremate = $idremate";
				$rs1 = mysql_query($sql);
				if($rs1){
					$tipo = $_POST['param1'];
					if($tipo == 'd'){
						$sql = "UPDATE remate SET estado = 1 WHERE idremate = $idremate";
						$rs = mysql_query($sql);	
						if($rs){
							unset($_SESSION['sidremate']);
							echo json_encode(array('success'=>true, 'mensaje'=>'OK'));
						}else{
							echo json_encode(array('success'=>false, 'mensaje'=>'ERROR'));
						}				
					}else{
						unset($_SESSION['sidremate']);
						echo json_encode(array('success'=>true, 'mensaje'=>'OK'));
					}
				}
				
			}else{
				echo json_encode(array('success'=>false, 'mensaje'=>'LOTE REMATANDO'));
			}

		}
	}
?>
