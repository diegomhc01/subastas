<?php	
include('validar.php');	
include('coneccion.php');	
if(isset($_SESSION['susuario'])){

	//SE DEBE CONTROLAR QUE VENGAN LOS DATOS NECESIARIOS DE
	//EVALUADOR Y NRO DE CONTRATO
	//DATOS DEL VENDEDOR
	//DATOS DEL ESTABLECIMIENTO
	//DATOS DE LA HACIENDA
	//DATOS DE LAS CONDICIONES DE VENTA
	//DATOS DEL PESAJE DE INSPECCION
	$arr = array('success'=>false,'de'=>'abmhacienda');
	$error = false;
	$accion = 'a';
	$idpi = 0;
	$idcv = 0;
	$idpd=0;
	$idvientre = 0;
	$idsanidad = 0;
	$idevaluacion = 0;

	$idfirma = $_SESSION['sidfirma'];

	if(!isset($_SESSION['saccion'])) $_SESSION['saccion'] = $_POST['param1'];
	
	$accion = $_SESSION['saccion'];
	
	if($accion=='mo' || $accion=='el') $idhacienda = $_SESSION['sidhacienda'];

	$vienelote = false;
	$idvendedor = $_SESSION['sidcliente'];
	if(isset($_POST['param0'])) $vienelote = true;

	//DATOS HACIENDA

	$nrocontrato  = $_POST['nrocontrato']!='' ? $_POST['nrocontrato'] : 0;
	$idevaluador = filter_input(INPUT_POST, 'evaluador', FILTER_VALIDATE_INT);
	if(!$idevaluador || is_null($idevaluador)) $error = true;
	
	$categoria  = filter_input(INPUT_POST, 'categoria', FILTER_VALIDATE_INT);
	if($categoria===FALSE || is_null($categoria)) $error = true;
	
	$cantidad  = filter_input(INPUT_POST, 'cantidad', FILTER_VALIDATE_INT);
	if($cantidad===FALSE || is_null($cantidad)) $error = true;
	
	$razatipo  = filter_input(INPUT_POST, 'razatipo', FILTER_SANITIZE_SPECIAL_CHARS);
	
	$pelaje  = filter_input(INPUT_POST, 'pelaje', FILTER_SANITIZE_SPECIAL_CHARS);
	
	$edad = filter_input(INPUT_POST, 'edad', FILTER_VALIDATE_INT);
	if($edad===FALSE || is_null($edad)) $edad = 0;

	$marcaliquida  = filter_input(INPUT_POST, 'marcaliquida', FILTER_VALIDATE_INT);
	if($marcaliquida===FALSE || is_null($marcaliquida)) $marcaliquida = 0;
	
	$trazados = filter_input(INPUT_POST, 'trazados', FILTER_VALIDATE_INT);
	if($trazados===FALSE || is_null($trazados)) $trazados = 0;

	$destetados = filter_input(INPUT_POST, 'destetados', FILTER_VALIDATE_INT);
	if($destetados===FALSE || is_null($destetados)) $destetados = 0;

	$alimentacion = filter_input(INPUT_POST, 'alimentacion', FILTER_VALIDATE_INT);
	if($alimentacion===FALSE || is_null($alimentacion)) $alimentacion = 0;

	$mochos = filter_input(INPUT_POST, 'mochos', FILTER_VALIDATE_INT);
	if($mochos===FALSE || is_null($mochos)) $mochos = 0;
	
	$descornados = filter_input(INPUT_POST, 'descornados', FILTER_VALIDATE_INT);
	if($descornados===FALSE || is_null($descornados)) $descornados = 0;

	$astados = filter_input(INPUT_POST, 'astados', FILTER_VALIDATE_INT);
	if($astados===FALSE || is_null($astados)) $astados = 0;
	
	$enteros = filter_input(INPUT_POST, 'enteros', FILTER_VALIDATE_INT);
	if($enteros===FALSE || is_null($enteros)) $enteros = 0;

	$querato = filter_input(INPUT_POST, 'querato', FILTER_VALIDATE_INT);
	if($querato===FALSE || is_null($querato)) $querato = 0;

	$observaciones = filter_input(INPUT_POST, 'observaciones', FILTER_SANITIZE_SPECIAL_CHARS);
	
	$establecimientoid = filter_input(INPUT_POST, 'establecimientoid', FILTER_VALIDATE_INT);
	if($establecimientoid===FALSE || is_null($establecimientoid)) $error = true;		
	
	if(!$error){
		//include('buscar_establecimiento.php');
		//$razatipo = strtoupper($razatipo);
		//$pelaje = strtoupper($pelaje);			
		if($accion=='a'){
			include('abmcondvta.php');
			$idcv = isset($_SESSION['sidcv']) ? $_SESSION['sidcv'] : 0;

			include('abmpesadainspeccion.php');
			$idpi = isset($_SESSION["sidpi"]) ? $_SESSION["sidpi"] : 0;
			
			if($idpi>0 && $idcv>0){
				include('abmpesadadefinitiva.php');
				$idpd = isset($_SESSION["sidpd"]) ? $_SESSION["sidpd"] : 0;

				include('abmvientre.php');
				$idvientre = isset($_SESSION["sidviente"]) ? $_SESSION["sidviente"] : 0;

				include('abmsanidad.php');
				$idsanidad = isset($_SESSION['sidsanidad']) ? $_SESSION['sidsanidad'] : 0;

				include('abmevaluacion.php');
				$idevaluacion = isset($_SESSION['sidevaluacion']) ? $_SESSION['sidevaluacion'] : 0;
				
				$sqlh = "INSERT INTO hacienda (trazados, cantidad, marcaliquida, razatipo, pelaje, destetados, alimentacion,  ";
				$sqlh .= " mochos, descornados, astados, enteros, querato, estado, idcategoria, observaciones, edad, idvendedor, resto, precioinicial, ";
				$sqlh .= "idvientre, idsanidad, idpi, idpd, idevaluacion, idcv, idevaluador, idlocalidad, codprov, nrocontrato, idfirma, idestablecimiento) ";
				$sqlh .= "VALUES ($trazados, $cantidad, $marcaliquida, '$razatipo', '$pelaje', $destetados, $alimentacion,  ";
				$sqlh .= "$mochos, $descornados, $astados, $enteros, $querato, 0, $categoria, '$observaciones', $edad, $idvendedor, $cantidad, $precioinicial, ";
				$sqlh .= "$idvientre, $idsanidad, $idpi, $idpd, $idevaluacion, $idcv, $idevaluador, 0, '', '$nrocontrato', $idfirma, $establecimientoid) ";
			}
		}

		if($accion=='mo'){						
			include('abmvientre.php');				
			include('abmpesadainspeccion.php');
			include('abmpesadadefinitiva.php');
			include('abmsanidad.php');
			include('abmevaluacion.php');
			include('abmcondvta.php');

			$sqlh = "UPDATE hacienda SET "; 
			$sqlh .= "trazados = $trazados, ";
			$sqlh .= "idcategoria = $categoria, "; 
			$sqlh .= "marcaliquida = $marcaliquida, ";
			$sqlh .= "razatipo = '$razatipo', ";
			$sqlh .= "pelaje = '$pelaje', ";
			$sqlh .= "edad = $edad, ";
			$sqlh .= "destetados = $destetados, ";
			$sqlh .= "alimentacion = $alimentacion, ";
			$sqlh .= "mochos = $mochos, ";
			$sqlh .= "descornados = $descornados, ";
			$sqlh .= "astados = $astados, ";
			$sqlh .= "enteros = $enteros, ";
			$sqlh .= "querato = $querato, ";
			$sqlh .= "observaciones = '$observaciones', ";
			$sqlh .= "precioinicial = '$precioinicial', ";
			$sqlh .= "idvendedor = $idvendedor, ";
			$sqlh .= "idvientre = $idvientre, ";
			$sqlh .= "idpi = $idpi, ";
			$sqlh .= "idpd = $idpd, ";
			$sqlh .= "idevaluacion = $idevaluacion, ";
			$sqlh .= "idcv = $idcv,";
			$sqlh .= "idestablecimiento = $establecimientoid, ";
			$sqlh .= "nrocontrato = '$nrocontrato' WHERE idhacienda = $idhacienda";
		}

		if($accion=='el'){
			include('abmvientre.php');				
			include('abmpesadainspeccion.php');
			include('abmpesadadefinitiva.php');
			include('abmsanidad.php');
			include('abmevaluacion.php');
			include('abmcondvta.php');
			$sql = "DELETE FROM hacienda WHERE idhacienda = $idhacienda AND estado = 0";
		}
		if($sqlh!=''){
			$rs = mysql_query($sqlh);
			if($rs){
				$arr = array('success'=>true,'de'=>'abmhacienda');
				if($accion=='a'){
					$_SESSION['sidhacienda'] = mysql_insert_id();
					if($vienelote){
						include('abmhacienda_lote.php');
					}
				}
				if($accion=='mo'){
					$accion = 'm';					
					if(isset($_POST['lotes'])){
						$idlote = $_POST['lotes'];
						include('abmlote.php');
					}
					$arr = array('success'=>true,'de'=>'abmhacienda');
				}				
			}else{
				$arr = array('success'=>false,'de'=>'abmhacienda','sql'=>$sql);
			}
		}		
		unset($_SESSION['saccion']);
	}
	echo json_encode(array('sqlh'=>$sqlh,'arr'=>$arr,'arrcv'=>$arrcv,'arreva'=>$arreva,'arrpd'=>$arrpd,'arrpi'=>$arrpi,'arrvientre'=>$arrvientre,'arrservicio'=>$arrservicio,'arrtoro'=>$arrtoro,'arrsanidad'=>$arrsanidad));
}
?>