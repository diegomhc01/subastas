<?php
	include('validar.php');	
	include('coneccion.php');	
	if(isset($_SESSION['susuario'])){
		if(!isset($_SESSION['sidlote']))
			$_SESSION['sidlote']	= $_POST['param'];

			$idlote = $_SESSION['sidlote'];			

			$sql = "SELECT estado FROM lotes WHERE idlote = $idlote";
			$rs0 = mysql_query($sql);

			if(mysql_num_rows($rs0)>0){
				$fila = mysql_fetch_row($rs0);
				if($fila[0]==3){
					$sql = "UPDATE lotes SET estado = 0 WHERE idlote = $idlote";
					$rs = mysql_query($sql);	
					if($rs)
						echo json_encode(array('success'=>true, 'msg'=>'OK', 'estado'=>$fila[0]));
					else{
						echo json_encode(array('success'=>true, 'msg'=>'ERROR', 'estado'=>$fila[0]));
					}					
				}
				if($fila[0]==1){
					echo json_encode(array('success'=>false, 'msg'=>'LOTE REMATANDO', 'estado'=>$fila[0]));
				}
				if($fila[0]==0){
					echo json_encode(array('success'=>false, 'msg'=>'LOTE ABIERTO', 'estado'=>$fila[0]));
				}
				if($fila[0]==4){
					echo json_encode(array('success'=>false, 'msg'=>'LOTE INHABILITADO', 'estado'=>$fila[0]));
				}
			}else{
				echo json_encode(array('success'=>false, 'msg'=>'NO ENCONTRADO', 'estado'=>-1));
			}		
	}	
?>
