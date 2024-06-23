<?php	
	include('validar.php');	
	include('coneccion.php');	
	if(isset($_SESSION['susuario'])){
		$idlote = $_SESSION['sidlote'];
		$idhacienda = $_SESSION['sidhacienda'];
		if(!isset($vienelote)){
			if(!$vienelote){
				$cantidad = $_POST['param'];
			}
		}
		
		if(isset($_POST['param1']))
			$_SESSION['saccion'] = $_POST['param1'];

		$accion = $_SESSION['saccion'];

		if($accion=='a'){
			$sql = "SELECT resto, precioinicial FROM hacienda WHERE idhacienda = $idhacienda";
			$rs0 = mysql_query($sql);
			if(mysql_num_rows($rs0) > 0){
				while($fila=mysql_fetch_array($rs0)){
					$precioinicial = $fila[1];
					if($fila[0]>0){
						if($cantidad <= $fila[0]){					
							$sql = "INSERT INTO hacienda_lote (idlote, idhacienda, cantidad) VALUES ($idlote, $idhacienda, $cantidad)";
							$cantidad = $fila[0] - $cantidad; 
							$rs = mysql_query($sql);
							if($rs){
								$sql = "UPDATE hacienda SET resto = $cantidad WHERE idhacienda = $idhacienda";
								$rs1 = mysql_query($sql);
								if($rs1){
									$sql = "SELECT SUM(cantidad) FROM hacienda_lote WHERE idlote = $idlote";
									$rs2 = mysql_query($sql);
									if(mysql_num_rows($rs2) > 0){
										while($suma = mysql_fetch_array($rs2)){
											$sql = "UPDATE lotes SET cantcabezas = ".$suma[0].", precioinicio = $precioinicial WHERE idlote = $idlote";
											$rs3 = mysql_query($sql);
											if($rs3){
												$arr = array('success'=>true,'sql'=>'OK');
											}else{
												$arr = array('success'=>false,'sql'=>mysql_error(),'error'=>'update lotes');
											}
										}
									}else{
										$arr = array('success'=>false,'sql'=>mysql_error(),'error'=>'select sum hacienda_lote');
									}								
								}else{
									$arr = array('success'=>false,'sql'=>mysql_error(),'error'=>'update hacienda resto');
								}
							}else{
								$arr = array('success'=>false,'sql'=>mysql_error(),'error'=>'insert into hacienda_lote');
							}
						}else{
							$arr = array('success'=>false,'sql'=>'cantidad menor al resto');
						}
					}else{
						$arr = array('success'=>false,'sql'=>'resto igual a 0');
					}
				}
			}else{
				$arr = array('success'=>false,'sql'=>mysql_error(),'error'=>'select hacienda resto');
			}
		}		
		if($accion=='m'){
			$sql = "SELECT cantidad FROM hacienda_lote WHERE idlote = $idlote and idhacienda = $idhacienda";
			$rs = mysql_query($sql);
			if(mysql_num_rows($rs) > 0){
				while($cantidadoriginal=mysql_fetch_array($rs)){
					$sql = "UPDATE hacienda SET resto = resto + ".$cantidadoriginal[0]." WHERE idhacienda = idhacienda";
					$rs1 = mysql_query($sql);
					if($rs1){
						$sql = "UPDATE hacienda_lote SET cantidad = $cantidad WHERE idlote = $idlote and idhacienda = $idhacienda";
						$rs2 = mysql_query($sql);
						if($rs2){
							$sql = "UPDATE hacienda SET resto = resto - $cantidad  WHERE idhacienda = idhacienda";
							$rs3 = mysql_query($sql);
							if($rs3){
								$sql = "SELECT SUM(cantidad) FROM hacienda_lote WHERE idlote = $idlote";
								$rs4 = mysql_query($sql);
								if(mysql_num_rows($rs4) > 0){
									while($suma = mysql_fetch_array($rs4)){
										$sql = "UPDATE lotes SET cantcabezas = ".$suma[0]." WHERE idlote = $idlote";
										$rs5 = mysql_query($sql);
										if($rs5){
											$arr = array('success'=>true,'sql'=>mysql_error());			
										}else{
											$arr = array('success'=>false,'sql'=>mysql_error());
										}
									}
								}else{
									$arr = array('success'=>false,'sql'=>mysql_error());
								}	
							}else{
								$arr = array('success'=>false,'sql'=>mysql_error());
							}
						}else{
							$arr = array('success'=>false,'sql'=>mysql_error());
						}
					}else{
						$arr = array('success'=>false,'sql'=>mysql_error());
					}
				}
			}else{
				$arr = array('success'=>false,'sql'=>mysql_error());
			}
		}
		if($accion=='e'){
			$sql = "SELECT cantidad FROM hacienda_lote WHERE idlote = $idlote and idhacienda = $idhacienda";
			$rs = mysql_query($sql);
			if(mysql_num_rows($rs) > 0){
				while($cantidh=mysql_fetch_array($rs)){
					$sql1 = "UPDATE hacienda SET resto = resto + ".$cantidh[0]." WHERE idhacienda = $idhacienda";
					$rs1 = mysql_query($sql1);
					if($rs1){
						$sql2 = "DELETE FROM hacienda_lote WHERE idlote = $idlote and idhacienda = $idhacienda";
						$rs2 = mysql_query($sql2);
						if($rs2){						
							$sql3 = "SELECT SUM(cantidad) FROM hacienda_lote WHERE idlote = $idlote";
							$rs3 = mysql_query($sql3);							
							if(mysql_num_rows($rs3) > 0){
								while($suma = mysql_fetch_array($rs3)){
									if($suma[0]!=null){
										$sql4 = "UPDATE lotes SET cantcabezas = ".$suma[0]." WHERE idlote = $idlote";
										$rs4 = mysql_query($sql4);
										if($rs4){
											$arr = array('success'=>true,'sql'=>mysql_error());
										}else{
											$arr = array('success'=>false,'sql'=>mysql_error());
										}
									}else{
										$sql4 = "UPDATE lotes SET cantcabezas = 0 WHERE idlote = $idlote";
										$rs4 = mysql_query($sql4);
										if($rs4){
											$arr = array('success'=>true,'sql'=>mysql_error());
										}else{
											$arr = array('success'=>false,'sql'=>mysql_error());
										}
									}
								}
							}else{
								$arr = array('success'=>false,'sql'=>mysql_error());
							}	
						}else{
							$arr = array('success'=>false,'sql'=>mysql_error());
						}
					}else{
						$arr = array('success'=>false,'sql'=>mysql_error());
					} 
				}
			}else{
				$arr = array('success'=>false,'sql'=>mysql_error());
			}
		}		
		unset($_SESSION['sidhacienda']);
		unset($_SESSION['saccion']);
		echo json_encode($arr);
	}

?>