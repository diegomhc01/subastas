<?php	
	include('validar.php');	
	include('coneccion.php');	
	if(isset($_SESSION['susuario'])){
		
		$cantidaderror = false;
		$cantidad = 0;
		
		if(isset($_SESSION['sidremate']))
			$idremate = $_SESSION['sidremate'];
		
		if($idremate>0){
			if(isset($_SESSION['saccion'])){
				$accion = $_SESSION['saccion'];
			}else{			
				if(isset($_POST['param']) && 
					($_POST['param']=='a' || $_POST['param']=='e' || $_POST['param']=='x')){
					$accion = $_POST['param'];
				}
			}
			
			if($accion=='e'){
				include('eliminar_lote.php');
			}else{
				$arr  = array('success'=>false,'mensaje'=>'');
				if(isset($_SESSION['snrolote'])){
					$nrolote = $_SESSION['snrolote'];
				}else{
					$nrolote = filter_input(INPUT_POST, 'nrolote', FILTER_VALIDATE_INT);
					if(!$nrolote || is_null($nrolote)) $nrolote = 0;
				}
				$tipoentrega = filter_input(INPUT_POST, 'tipoentrega', FILTER_VALIDATE_INT);
				if(!$tipoentrega || is_null($tipoentrega)) $tipoentrega = 0;

				$tipoprecio = filter_input(INPUT_POST, 'tipoprecio', FILTER_VALIDATE_INT);
				if(!$tipoprecio || is_null($tipoprecio)) $tipoprecio = 0;

				$precioinicio = filter_input(INPUT_POST, 'precioinicio', FILTER_VALIDATE_FLOAT);
				if(!$precioinicio || is_null($precioinicio)) $precioinicio = 0;

				if(isset($_POST['arr'])){
					$arrhl = json_decode($_POST['arr']);
					$cantidadlotes = count($arrhl);				
					for($i=0;$i<$cantidadlotes;$i++){
						$arrhl[$i]->id = filter_var($arrhl[$i]->id, FILTER_VALIDATE_INT);
						$arrhl[$i]->canl = filter_var($arrhl[$i]->canl, FILTER_VALIDATE_INT);
						$arrhl[$i]->can = filter_var($arrhl[$i]->can, FILTER_VALIDATE_INT);
						if($arrhl[$i]->id>0 && !is_null($arrhl[$i]->id) && 
							$arrhl[$i]->can>0 && !is_null($arrhl[$i]->can) && 
							$arrhl[$i]->canl>0 && !is_null($arrhl[$i]->canl)){
							$cantidad += $arrhl[$i]->canl; 
							if($arrhl[$i]->canl>$arrhl[$i]->can){
								$cantidaderror=true;
								break;
							}
						}else{
							$cantidaderror = true;
							break;
						}
					}
				}else{
					$cantidaderror = true;
				}

				if($nrolote>0 && $precioinicio>0 && $cantidad>0 && $cantidaderror!==FALSE){
					if($accion=='m' || $accion=='e' || $accion=='x')
						$idlote = $_SESSION['sidlote'];
					if($accion=='a' || $accion=='m' || $accion=='x'){
						$sql = "SELECT inc1, inc2, inc3 FROM tipoprecio WHERE idtp = $tipoprecio";
						$rstp = mysql_query($sql);
						if(mysql_num_rows($rstp)>0){
							$filatp = mysql_fetch_row($rstp);
							$inc1 = $filatp[0];
							$inc2 = $filatp[1];
							$inc3 = $filatp[2];

							if($accion=='a'){						
								$sql = "SELECT nrolote FROM lotes WHERE idremate = $idremate and nrolote = $nrolote";
							
								$rs = mysql_query($sql);
								if(mysql_num_rows($rs)==0){
									$sql = "INSERT INTO lotes (cantcabezas, estado, idremate, orden, tipoentrega, nrolote, precioinicio, idtp, inc1, inc2, inc3, incremento) VALUES ";
									$sql .= "($cantidad, 4, $idremate, 0, $tipoentrega, $nrolote, $precioinicio, $tipoprecio, $inc1, $inc2, $inc3, $inc3)";
								}
							}					
							if($accion=='m' || $accion=='x'){
								$sql = "UPDATE lotes SET "; 
								$sql .= "idtp = $tipoprecio, "; 
								$sql .= "nrolote = $nrolote, "; 
								$sql .= "tipoentrega = $tipoentrega, ";
								$sql .= "precioinicio = $precioinicio, ";
								$sql .= "inc1 = $inc1, ";
								$sql .= "inc2 = $inc2, ";
								$sql .= "inc3 = $inc3, ";
								$sql .= "incremento = $inc3 ";
								$sql .= "WHERE idlote = $idlote AND (estado = 0 OR estado = 4)";
							}
						}
						$rs = mysql_query($sql);
					}					
					if($rs){
						if($accion=='a'){
							$idlote = mysql_insert_id();
						}
						if(isset($idlote)){

							$totalcantidadhl = 0;
							$cantidadhlanterior = 0;
							$idhaciendahlanterior = 0;

							$sql = "SELECT cantidad, idhacienda FROM hacienda_lote ";
							$sql .= "WHERE idlote = $idlote";
							$rscanthl = mysql_query($sql);
							if(mysql_num_rows($rscanthl)>0){
								while($fcantidadhlanterior=mysql_fetch_array($rscanthl)){
									$cantidadhlanterior = $fcantidadhlanterior[0];
									$idhaciendahlanterior = $fcantidadhlanterior[1];
									$sql = "UPDATE hacienda SET resto = resto + $cantidadhlanterior  WHERE idhacienda = $idhaciendahlanterior";
									$rshacienda = mysql_query($sql);
								}
							}

							$sql = "DELETE FROM hacienda_lote ";
							$sql .= "WHERE idlote = $idlote";
							$rshlel = mysql_query($sql);
							for($i=0;$i<$cantidadlotes;$i++){
								$idhacienda = $arrhl[$i]->id;
								$cantidadhl = $arrhl[$i]->canl;
								$totalcantidadhl += $cantidadhl;
								$sql = "INSERT INTO hacienda_lote (idhacienda, idlote, cantidad) ";
								$sql .= "VALUES ($idhacienda, $idlote, $cantidadhl)";							
								$rshl = mysql_query($sql);
								if($rshl){
									$sql = "UPDATE hacienda SET resto = resto - $cantidadhl WHERE idhacienda = $idhacienda";
									$rshacienda = mysql_query($sql);
								}
							}
							if($totalcantidadhl>0){
								$sql = "UPDATE lotes SET cantcabezas = $totalcantidadhl WHERE idlote = $idlote";
								$rschl = mysql_query($sql);
								if($rschl){
									$arr = array('success'=>true,'mensaje'=>'','nrolote'=>0);
								}
							}
						}
					}
				}
			}
		}
		unset($_SESSION['saccion']);
		unset($_SESSION['snrolote']);
		unset($_SESSION['sidlote']);
		echo json_encode($arr);
	}
?>