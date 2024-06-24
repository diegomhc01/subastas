<?php
	include('validar.php');
	include('coneccion.php');	
	mysql_set_charset('utf8');
	$arr = array();
	$arridlote = array();	
	if($_POST['param']=='P'){
		$sql = "SELECT l.idlote, l.cantcabezas, l.nrolote, DATE_FORMAT(r.fecha, '%d-%m-%Y') AS fecha, ";
		$sql .= "r.hora, r.tipoentrega FROM remate r, lotes l WHERE l.idremate = r.idremate WHERE r.estado = 1 and l.idlote = 1";

		$rs = mysql_query($sql);
		if(mysql_num_rows($rs)>0){
			while($fila=mysql_fetch_assoc($rs)){
				$fecharemate = $fila['fecha'];
				$horaremate = $fila['hora'];
				$nrolote = $fila['nrolote'];
				$cantcabezas = $fila['cantcabezas'];
				$tipoentrega = $fila['tipoentrega'];
				$arridlote[] = $fila['idlote'];
			}
		}	
	}
	if($_POST['param']=='F'){
	}
	if($_POST['param']=='A'){
	}
	if($_POST['param']=='T'){
	}
	
	for($i=0;$i<count($arridlote);$i++){
		//BUSCAR DATOS DE HACIENDA POR IDLOTE
		$sql = "SELECT h.idhacienda, ";
		$sql .= "CASE h.trazados WHEN 0 THEN 'NO TRAZADOS' WHEN 1 THEN 'TRAZADOS' END AS trazados, h.cantidad, "; 
		$sql .= "CASE h.marcaliquida WHEN 0 THEN 'SIN MARCA LIQUIDA' WHEN 1 THEN 'CON MARCA LIQUIDA' END AS marcaliquida, ";
		$sql .= "h.razatipo, h.pelaje, h.idevaluacion, h.idalimentacion, h.idcv ";
		$sql .= "CASE h.destetados WHEN 0 THEN 'NO' WHEN 1 THEN 'SI' END AS destetados, ";
		$sql .= "CASE h.alimentacion WHEN 0 THEN 'RACIONADOS' WHEN 1 THEN 'SUPLEMENTADOS' WHEN 2 THEN 'A CAMPO' END AS alimentacion ";
		$sql .= "FROM hacienda h, hacienda_lote hl ";
		$sql .= "WHERE h.idhacienda = hl.idhacienda = hl.idlote = $idlote";
		$rs1 = mysql_query($sql);
		if(mysql_num_rows($rs1)){ //CONTROLAR SI HAY MAS UN REGISTRO
			while($dhfila=mysql_fetch_assoc($rs1)){
				$trazados = $dhfila['trazados'];
				$marcaliquida = $dhfila['marcaliquida'];
				$alimentacion = $dhfila['alimentacion'];
				$razatipo = $dhfila['razatipo'];
				$pelaje = $dhfila['pelaje'];
				$destetados = $dhfila['destetados'];
				if($dhfila['idevaluacion'] > 0){
					//BUSCAR DATOS DE HACIENDA POR IDLOTE
					$sql = "SELECT e.idevaluacion, e.calidad, e.estadoeva, e.sanidad, e.uniformidad ";
					$sql .= "FROM evaluacion e WHERE e.idevaluacion = ".$dhfila['idevaluacion']
					$rs2 = mysql_query($sql);
					if(mysql_num_rows($rs2)){
						while($defila=mysql_fetch_assoc($rs2)){
							$idevluacion = $defila['idevaluacion'];
							$calidad =  $defila['calidad'];
							$estadoeva =  $defila['estadoeva'];
							$sanidad =  $defila['sanidad'];
							$uniformidad =  $defila['uniformidad'];
						}
					}
				}
				if($dhfila['idcv'] > 0){
					$sql = "SELECT c.idcv, c.plazo, c.precioinicial, c.tipoprecio, c.estado ";
					$sql = "FROM condiciones_vta c WHERE c.idcv = ".$dhfila['idcv'];
					$rs3 = mysql_query($sql);
					if(mysql_num_rows($rs3)>0){
						while($cvfila=mysql_fetch_assoc($rs3)){
							$plazo = $cvfila['plazo'];
							$precioinicial = $cvfila['precioinicial'];
							$tipoprecio = $cvfila['tipoprecio'];
						}
					}
				}
				$arr[] = '<div class="clsitemdetallelote"> 
						<div id="dl1" class="dl">
							<h1>Remate</h1>
							<div id="dl11">
								<p>Fecha: <strong>'.$fecharemate.'</strong></p>
								<p>Hora : <strong>'.$horaremate.'</strong></p>
							</div>
							<div id="dl12">
								<p>Nro Lote: <strong>'.$nrolote.'</strong></p>
								<p>Cabezas: <strong>'.$cantcabezas.'</strong></p>
							</div>
						</div>
						<div id="dl2" class="dl">
							<h1>Tipificaci&oacute;n</h1>
							<div id="dl21">
								<p>Trazados <strong>'.$trazados.'</strong></p>
								<p>Marca L&iacute;quida <strong>'.$marcaliquida.'</strong></p>
							</div>
							<div id="dl22">
								<p>Alimentaci&oacute;n <strong>'.$alimentacion.'</strong></p>
								<p>Destetados <strong>'.$destetados.'</strong></p>
							</div>
						</div>
						<div id="dl3" class="dl">
							<h1>Evaluaci&oacute;n</h1>
								<div id="dl31">
									<p>Calidad: <strong>'.$calidad.'</strong></p>
									<p>Estado: <strong>'.$estadoeva.'</strong></p>
								</div>
								<div id="dl32">
									<p>Pesada: <strong>'..'</strong></p>
									<p>Pes&oacute;: <strong>'..'</strong></p>
								</div>
						</div>
						<div id="dl4" class="dl">
							<h1>Condiciones</h1>
							<div id="dl41">
								<p>Precio: <strong>'.$precioinicial.'</strong></p>
								<p>Plazo: <strong>'.$plazo.'</strong></p>
							</div>
							<div id="dl42">
								<p>Entrega: <strong>'.$tipoentrega.'</strong></p>
								<p>Pesada: <strong>'..'</strong></p>
							</div>
						</div>
						<div id="btndl">
							<input type="image" src="images/infob.png" name="btndl1" class="btndlinfo" id="btndl'.$idlote.'" value="M&aacute;s Info">
							<input type="image" src="images/starbn.png" name="btndl2" class="btndlalerta" id="btndl'.$idlote.'" value="M&aacute;s Info">
						</div>
						</div>';
			}
		}
	}
	echo json_encode($arr);
?>