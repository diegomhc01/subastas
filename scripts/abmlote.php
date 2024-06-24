<?php	
	include('validar.php');	
	include('coneccion.php');	
	if(isset($_SESSION['susuario'])){
		
		$cantidaderror = false;
		$cantidad = 0;
		//SI ESTA SETEADA LA VARIABLE DE SESION DE IDREAMTE
		if(isset($_SESSION['sidremate']))
			$idremate = $_SESSION['sidremate'];
		
		//SI PUDO ASIGNARLE UN VALOR A IDREMATE
		if($idremate>0){
			//PREGUNTO SI ESTA LA ACCION EN LA SESION
			if(isset($_SESSION['saccion'])){
				$accion = $_SESSION['saccion'];
			}else{			
				//SI NO TOMO LA ACCION QUE VIENE POR POST
				// Y CONTROLO QUE SEAN SOLO LOS VALORES QUE ACEPTO
				//a=NUEVO LOTE
				//e=ELIMINAR LOTE
				//x=MODIFICAR HACIENDA DE LOTE
				if(isset($_POST['param']) && 					
					($_POST['param']=='a' || $_POST['param']=='e' || $_POST['param']=='x')){
					$accion = $_POST['param'];
				}
			}
			//SI VOY A ELIMINAR LOTE, LLAMO AL SCRIPT ELIMINAR_LOTE
			if($accion=='e'){
				include('eliminar_lote.php');
			}else{
				//SI NO, FILTRO Y SANEO LOS DATOS QUE VIENE DE LA PAGINA
				$arr  = array('success'=>false,'mensaje'=>'error');
				if(isset($_SESSION['snrolote'])){
					$nrolote = $_SESSION['snrolote'];
				}else{
					$nrolote = filter_input(INPUT_POST, 'nrolote', FILTER_VALIDATE_INT);
					if($nrolote===FALSE || is_null($nrolote)) $nrolote = 0;
				}
				$tipoentrega = filter_input(INPUT_POST, 'tipoentrega', FILTER_VALIDATE_INT);
				if($tipoentrega===FALSE || is_null($tipoentrega)) $tipoentrega = 0;

				$tipoprecio = filter_input(INPUT_POST, 'tipoprecio', FILTER_VALIDATE_INT);
				if($tipoprecio===FALSE || is_null($tipoprecio)) $tipoprecio = 0;

				$precioinicio = filter_input(INPUT_POST, 'precioinicio', FILTER_VALIDATE_FLOAT);
				if($precioinicio===FALSE || is_null($precioinicio)) $precioinicio = 0;

				//SI HAY HACIENDA SELECCIONADA PARA EL LOTE
				if(isset($_POST['arr'])){					
					$arrhl = json_decode($_POST['arr']);					
					$cantidadlotes = count($arrhl);					
					//RECORRO EL ARREGLO CON LOS DATOS DE HACIENDA Y LOS SANEO
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
				mysql_query("SET AUTOCOMMIT=0");
				mysql_query("START TRANSACTION");
				//SI ESTA TODO OK


				if($nrolote>0 && $precioinicio>0 && 
					$cantidad>0 && !$cantidaderror){			
					//SI ES ELIMINAR O MODIFICAR HACIENDA EN LOTE
					//TOMO EL VALOR DE IDLOTE DE LA SESION					
					if($accion=='e' || $accion=='x')
						$idlote = $_SESSION['sidlote'];
					if($accion=='a' || $accion=='x'){
						
						//SI ES ALTA O MODIFICACION DE HACIENDA EN LOTE
						//BUSCO LOS INCREMENTOS EN TIPOPRECIO
						$sql = "SELECT inc1, inc2, inc3 FROM tipoprecio WHERE idtp = $tipoprecio";
						
						$rstp = mysql_query($sql);
						if(mysql_num_rows($rstp)>0){
							$filatp = mysql_fetch_row($rstp);
							$inc1 = $filatp[0];
							$inc2 = $filatp[1];
							$inc3 = $filatp[2];
							//SI ES NUEVO LOTE, BUSCO QUE NO EXISTE UN LOTE CON UN NUMERO
							//DE LOTE IGUAL
							if($accion=='a'){						
								$sql = "SELECT nrolote FROM lotes WHERE idremate = $idremate and nrolote = $nrolote";
							
								$rs = mysql_query($sql);
								//SI NO EXISTE, HAGO EL INSERT DEL LOTE
								if(mysql_num_rows($rs)==0){
									$sql = "INSERT INTO lotes (cantcabezas, estado, idremate, orden, tipoentrega, nrolote, precioinicio, idtp, inc1, inc2, inc3, incremento) VALUES ";
									$sql .= "($cantidad, 4, $idremate, 0, $tipoentrega, $nrolote, $precioinicio, $tipoprecio, $inc1, $inc2, $inc3, $inc3)";									
									$rslote = mysql_query($sql);
								}
							}
							//SI MODIFICO EL LOTE, HAGO EL UPDATE DEL LOTE
							if($accion=='x'){
								$sqlupdlote = "UPDATE lotes SET "; 
								$sqlupdlote .= "idtp = $tipoprecio, "; 
								$sqlupdlote .= "nrolote = $nrolote, "; 
								$sqlupdlote .= "tipoentrega = $tipoentrega, ";
								$sqlupdlote .= "precioinicio = $precioinicio, ";
								$sqlupdlote .= "inc1 = $inc1, ";
								$sqlupdlote .= "inc2 = $inc2, ";
								$sqlupdlote .= "inc3 = $inc3, ";
								$sqlupdlote .= "incremento = $inc3 ";
								$sqlupdlote .= "WHERE idlote = $idlote AND (estado = 0 OR estado = 4)";								
								$rslote = mysql_query($sqlupdlote);
							}
						}
					}
					//PARA CUALQUIERA DE LOS CASOS
					//VOY A AGREGAR LA HACIENDA AL LOTE
					if($rslote){
						//SI INSERTE UN NUEVO LOTE, BUSCO EL ID INSERTADO
						if($accion=='a'){
							$idlote = mysql_insert_id();
						}
						//SI TENGO EL IDLOTE
						if(isset($idlote)){							
							//LA ACCION ES DISTINTA DE ALTA
							if($accion!='a'){
								//BUSCO LA CANTIDAD E IDHACIENDA DE LOS LOTES EN 
								//HACIENDA_LOTE
								$sqlcantidadhac = "SELECT cantidad, idhacienda FROM hacienda_lote ";
								$sqlcantidadhac .= "WHERE idlote = $idlote";								
								$rscanthl = mysql_query($sqlcantidadhac);
								
								//VOY SUMANDO AL RESTO EN HACIENDA
								//LA CANTIDAD DE ANIMALES EN CADA LOTE
								//PARA QUE QUEDE EL RESTO CORRECTO
								while($fila = mysql_fetch_array($rscanthl)){
									$cantidadhl = $fila[0];
									$idhacienda = $fila[1];
									$sqlresto = "UPDATE hacienda ";
									$sqlresto .= "SET resto = resto + $cantidadhl ";
									$sqlresto .= "WHERE idhacienda = $idhacienda";									
									$rshacienda = mysql_query($sqlresto);
								}
								//UNA VEZ QUE SUMO AL RESTO DE HACIENDA,
								//ELIMINO TODOS LAS HACIENDA DE ESE LOTES
								$sqldelhl = "DELETE FROM hacienda_lote WHERE idlote = $idlote";
								$rshlel = mysql_query($sqldelhl);
							}	
							//EMPIEZO A RECORRER LA HACIENDA ACTUAL DEL LOTE													
							for($i=0;$i<$cantidadlotes;$i++){
								$idhacienda = $arrhl[$i]->id;
								$cantidadhl = $arrhl[$i]->canl;
								$totalcantidadhl += $cantidadhl;
								//INSERT EN HACIENDA_LOTE, EL LOTE, LA HACIENDA Y LA CANTIDAD
								$sqlinshl = "INSERT INTO hacienda_lote (idhacienda, idlote, cantidad) ";
								$sqlinshl .= "VALUES ($idhacienda, $idlote, $cantidadhl)";								
								$rshl = mysql_query($sqlinshl);
								//ACTUALIZO EL RESTO EN HACIENDA
								$sqlupdhac = "UPDATE hacienda ";
								$sqlupdhac .= "SET resto = resto - $cantidadhl ";
								$sqlupdhac .= "WHERE idhacienda = $idhacienda";
								$rshaciendaadd = mysql_query($sqlupdhac);								
							}
							if($totalcantidadhl>0){
								//ACTUALIZO LA CANTIDAD TOTAL DE HACIENDA PARA EL LOTE
								$sqlupdcanlote = "UPDATE lotes SET cantcabezas = $totalcantidadhl WHERE idlote = $idlote";
								$rschl = mysql_query($sqlupdcanlote);
							}
						}
					}
				}
				if($rshl && $rshaciendaadd && $rschl){
					mysql_query("COMMIT");
					$arr = array('success'=>true,'mensaje'=>'OK');				
				}else{
					mysql_query("ROLLBACK");		
					$arr = array('success'=>false,'mensaje'=>'error rollback',
						'sqlupdlote'=>$sqlupdlote,'sqlcantidadhac'=>$sqlcantidadhac,
						'sqlresto'=>$sqlresto,'sqldelhl'=>$sqldelhl,
						'sqlinshl'=>$sqlinshl,'sqlupdhac'=>$sqlupdhac,
						'sqlupdcanlote'=>$sqlupdcanlote);				
				}
			}
		}
				

		echo json_encode($arr);	
		unset($_SESSION['saccion']);
		unset($_SESSION['snrolote']);
		unset($_SESSION['sidlote']);

	}
?>