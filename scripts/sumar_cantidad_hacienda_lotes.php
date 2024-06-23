<?php
	include('validar.php');	
	include('coneccion.php');	
	if(isset($_SESSION['susuario'])){
		$idhacienda = $_SESSION['sidhacienda'];
		$i=0;
		$arr = array('success'=>false);
		if(isset($_POST['cantidadlotenueva'])){	
			$cantidadnueva = $_POST['cantidadnueva'];
			$lotescantidad = $_POST['cantidadlotenueva'];
			foreach ($lotescantidad as $cantidad){
				$cantidadtotal += $cantidad;
			}
			if($cantidadnueva >= $cantidadtotal){				
				$sql = "SELECT idlote FROM hacienda_lote WHERE idhacienda = $idhacienda ORDER BY 1";
				$rs = mysql_query($sql);
				if(mysql_num_rows($rs)>0){
					while($lote = mysql_fetch_array($rs)){						
						$cantidadnuevalote = $lotescantidad[$i];
						if($cantidadnuevalote>0){
							$sql = "UPDATE hacienda_lote SET cantidad = $cantidad WHERE idlote = ".$lote[0]." and idhacienda = $idhacienda";
							$rs1 = mysql_query($sql);
							$sql = "UPDATE lote SET cantcabezas = $cantidad WHERE idlote = ".$lote[0];
							$rs2 = mysql_query($sql);
						}
						$i++;
					}
				}
				$resto = $cantidadnueva - $cantidadtotal;
				$sql = "UPDATE hacienda SET cantidad = $cantidadnueva, resto = $resto WHERE idhacienda = $idhacienda";
				$rs3 = mysql_query($sql);
				$arr = array('success'=>true);
			}
		}
		echo json_encode($arr);
	}
?>