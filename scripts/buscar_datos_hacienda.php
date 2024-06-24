<?php	
	include('validar.php');
	include('coneccion.php');	
	if(isset($_SESSION['susuario'])){
		if(isset($_SESSION['saccion']))
			unset($_SESSION['saccion']);
		if(isset($_SESSION['sidhacienda']))
			unset($_SESSION['sidhacienda']);			
		if(isset($_SESSION['sidvendedor']))
			unset($_SESSION['sidvendedor']);
		if(isset($_SESSION['sidvientre']))
			unset($_SESSION['sidvientre']);
		if(isset($_SESSION['sidsanidad']))
			unset($_SESSION['sidsanidad']);
		if(isset($_SESSION['sidpi']))
			unset($_SESSION['sidpi']);
		if(isset($_SESSION['sidpd']))
			unset($_SESSION['sidpd']);
		if(isset($_SESSION['sidevaluacion']))
			unset($_SESSION['sidevaluacion']);
		if(isset($_SESSION['sidcv']))
			unset($_SESSION['sidcv']);
		if(isset($_SESSION['sidpersona']))
			unset($_SESSION['sidpersona']);

		if(isset($_POST['param'])){
			if($_POST['param']==0){
				unset($_SESSION['sidhacienda']);
			}else{
				$_SESSION['sidhacienda'] = $_POST['param'];
				$idhacienda = $_SESSION['sidhacienda'];
			}
		}

		$_SESSION['saccion'] = $_POST['param1'];		
		$sql = "idhacienda ".$idhacienda;
		if(isset($idhacienda)){
			$sql = "SELECT idhacienda, trazados, cantidad, marcaliquida, razatipo, pelaje, ";
			$sql .= "destetados, alimentacion, mochos, descornados, astados, enteros, ";
			$sql .= "querato, estado, idcategoria, edad, observaciones, idvendedor, idvientre, ";
			$sql .= "idpi, idpd, idevaluacion, idcv, idevaluador, idlocalidad, codprov, nrocontrato, ";
			$sql .= "idsanidad, idestablecimiento ";
			$sql .= "FROM hacienda h WHERE h.idhacienda = $idhacienda";		
			$rs = mysql_query($sql);
			if(mysql_num_rows($rs)>0){
				while($fila=mysql_fetch_array($rs)){
					$arrha = array('success'=>true,
						'nrocontrato'=>$fila[26],
						'trazados'=>$fila[1],
						'cantidad'=>$fila[2],
						'marcaliquida'=>$fila[3],
						'razatipo'=>$fila[4],
						'pelaje'=>$fila[5],
						'destetados'=>$fila[6],
						'alimentacion'=>$fila[7],						
						'mochos'=>$fila[8],
						'descornados'=>$fila[9],
						'astados'=>$fila[10],
						'enteros'=>$fila[11],
						'querato'=>$fila[12],
						'estado'=>$fila[13],
						'categoria'=>$fila[14],
						'edad'=>$fila[15],
						'observaciones'=>$fila[16],
						'idevaluador'=>$fila[23],
						'idlocalidad'=>$fila[24],
						'codprov'=>$fila[25],
						'idestablecimiento'=>$fila[28]);

					$_SESSION['sidhacienda'] = $fila[0];			
					$_SESSION['sidvendedor'] = $fila[17];
					$_SESSION['sidcliente'] = $fila[17];
					$_SESSION['sidvientre'] = $fila[18];
					$_SESSION['sidpi'] = $fila[19];
					$_SESSION['sidpd'] = $fila[20];
					$_SESSION['sidevaluacion'] = $fila[21];
					$_SESSION['sidcv'] = $fila[22];
					$_SESSION['sidsanidad'] = $fila[27];
					$_SESSION['sidestablecimiento'] = $fila[28];			
					$arrve = array();
					$arrvi = array();
					$arrsa = array();
					$arrse = array();
					$arrto = array();
					$arrpi = array();
					$arrpd = array();
					$arrev = array();
					$arrcv = array();
					$arrce = array();
					include('buscar_datos_vendedor.php');
					include('buscar_datos_vientre.php');				
					include('buscar_datos_sanidad.php');				
					include('buscar_datos_pi.php');
					include('buscar_datos_pd.php');
					include('buscar_datos_evaluacion.php');
					include('buscar_datos_condvta.php');
					include('buscar_datos_establecimiento_h.php');
					
					$arr = array('success'=>true,
						'hacienda'=>$arrha,
						'vendedor'=>$arrve,
						'vientre'=>$arrvi,
						'sanidad'=>$arrsa,
						'servicio'=>$arrse,
						'toro'=>$arrto,
						'pi'=>$arrpi,
						'pd'=>$arrpd,
						'evaluacion'=>$arrev,
						'cv'=>$arrcv,
						'ce'=>$arrce);
				}
			}else{
				$arr = array('success'=>false);
			}
		}else{
			$arr = array('success'=>false);
		}	
		echo json_encode($arr);
	}
?>