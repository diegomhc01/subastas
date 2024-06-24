<?php
	include('validar.php');	
	include('coneccion.php');	
	if(isset($_SESSION['susuario'])){
		$arrlotes = array();
		$cantidadenlote = 0;
		$idhacienda = $_SESSION['sidhacienda'];
		$cantidad = $_POST['cantidad'];
		$sql = "SELECT resto, cantidad FROM hacienda WHERE idhacienda = $idhacienda";
		$rs0 = mysql_query($sql);
		if(mysql_num_rows($rs0)>0){
			$cantidadactual = mysql_fetch_row($rs0);
			$sql = "SELECT cantidad, idlote FROM hacienda_lote WHERE idhacienda = $idhacienda";
			$rs1 = mysql_query($sql);
			if(mysql_num_rows($rs1)>0){				
				while($lotes = mysql_fetch_array($rs1)){		
					print_r($lotes);
					$cantidadenlote += $lotes[0];
					$arrlotes[] = $lotes;
				}
			}
			if($cantidad>$cantidadactual[1]){
				$restonuevo = $cantidad - $cantidadenlote;
				$sql = "UPDATE hacienda SET cantidad = $cantidad, resto = $restonuevo WHERE idhacienda = $idhacienda";
				$rs2 = mysql_query($sql);
				if($rs2){
					$arr = array('success'=>true);
				}
			}
			if($cantidad<$cantidadactual[1]){
				$restonuevo = $cantidad - $cantidadenlote;
				if($restonuevo > 0){
					$sql = "UPDATE hacienda SET cantidad = $cantidad, resto = $restonuevo WHERE idhacienda = $idhacienda";
					$rs3 = mysql_query($sql);
					if($rs3){
						$arr = array('success'=>true);
					}
				}else{
					$sql = "UPDATE hacienda SET cantidad = $cantidad, resto = 0 WHERE idhacienda = $idhacienda";
					$rs3 = mysql_query($sql);
					if($rs3){
						$sql = "UPDATE hacienda_lote SET cantidad = $cantidad WHERE idlote = ".$arrlotes[1]. " and idhacienda = $idhacienda";
						$rs4 = mysql_query($sql);
						if($rs4){
							$sql = "UPDATE lotes SET cantcabezas = (SELECT SUM(cantidad) FROM hacienda_lote WHERE idhacienda = $idhacienda) WHERE idlote = ".$arrlotes[1];
							$rs5 = mysql_query($sql);
							if($rs5){								
								$arr = array('success'=>true);
							}
						}						
					}
				}
			}
		}
		include('abmhacienda.php');
	}
?>