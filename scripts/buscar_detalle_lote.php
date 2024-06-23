<?php
	include('validar.php');
	include('coneccion.php');	
	//if(isset($_SESSION['susuario'])){
		$idlote = $_POST['param'];
		$sql = "SELECT l.idlote, l.nrolote, l.cantcabezas, CASE l.tipoentrega WHEN 0 THEN 'INMEDIATA' WHEN 1 THEN 'A TERMINO' END AS tipoentrega ";
		$sql .= "FROM lotes l ";
		$sql .= "WHERE l.idlote = $idlote ";
		$rs = mysql_query($sql,$coneccion);
		if(mysql_num_rows($rs)>0){
			while($fila=mysql_fetch_assoc($rs)){
				
					$sql = "SELECT h.idhacienda, ";
					$sql .= "CASE h.trazados WHEN 0 THEN 'NO TRAZADOS' WHEN 1 THEN 'TRAZADOS' END AS trazados, "; 
					$sql .= "CASE h.marcaliquida WHEN 0 THEN '' WHEN 1 THEN 'MARCA LIQUIDA' END AS marcaliquida, ";
					$sql .= "h.razatipo, h.pelaje, ";
					$sql .= "CASE h.destetados WHEN 0 THEN '' WHEN 1 THEN 'DESTETADOS' END AS destetados, ";
					$sql .= "CASE h.alimentacion WHEN 0 THEN 'RACIONADOS' WHEN 1 THEN 'SUPLEMENTADOS' WHEN 2 THEN 'A CAMPO' WHEN 3 THEN 'SIN DATOS' END AS alimentacion, ";
					$sql .= "h.observaciones, h.edad, c.descripcion, ";
					$sql .= "h.idcategoria, h.idvientre, h.idpi, h.idpd, h.idevaluacion, h.idcv, h.idevaluador, h.idestablecimiento ";
					$sql .= "FROM hacienda h, hacienda_lote hl, categoria c ";
					$sql .= "WHERE hl.idhacienda = h.idhacienda and hl.idlote = $idlote and c.idcategoria = h.idcategoria";
					$rshacienda = mysql_query($sql);
					if(mysql_num_rows($rshacienda)>0){
						$hacienda= array(mysql_fetch_row($rshacienda));						
						if($hacienda[0][11]>0){
							$sql = "SELECT v.idvientre, v.preniados, v.conservicio, v.vacios, v.idservicio, v.estado, s.desde, s.hasta, ";
							$sql .= "case s.garantia when 0 then 'SIN GARANTIA' when 1 then 'CON GARANTIA' end as garantia, ";
							$sql .= "s.estado, s.idtoro, s.ia, s.naturals, CONVERT(t.sangre USING utf8) as sangre, CONVERT(t.razatipo USING utf8) as razatipo, t.estado ";
							$sql .= "FROM vientre v, servicio s, toro t ";
							$sql .= "WHERE t.idtoro = s.idtoro and s.idservicio = v.idservicio and v.idvientre = ".$hacienda[0][11];
							$rsvientre = mysql_query($sql);
							if(mysql_num_rows($rsvientre)>0)
								while($filavientre=mysql_fetch_assoc($rsvientre))
									$vientre= array($filavientre);
							else
								$vientre = array('idvientre'=>0, 'preniados'=>'', 'conservicio'=>'', 'vacios'=>'', 'idservicio'=>'', 'estado'=>'', 'desde'=>'', 'hasta'=>'', 'garantia'=>'', 'sestado'=>'', 'idtoro'=>'', 'ia'=>'', 'naturals'=>'', 'sangre'=>'', 'razatipo'=>'', 'testado'=>'');
							
						}else{							
								$vientre = array('idvientre'=>0, 'preniados'=>'', 'conservicio'=>'', 'vacios'=>'', 'idservicio'=>'', 'estado'=>'', 'desde'=>'', 'hasta'=>'', 'garantia'=>'', 'sestado'=>'', 'idtoro'=>'', 'ia'=>'', 'naturals'=>'', 'sangre'=>'', 'razatipo'=>'', 'testado'=>'');
						}
						if($hacienda[0][12]>0){
							$sqlpi = "SELECT p.idpi, ";
							$sqlpi .= "case p.forma when 0 then 'SIN DATOS' when 1 then 'CON BALANZA' when 2 then 'ESTIMADA' end as forma, ";
							$sqlpi .= "p.hora, p.desbaste, p.promedio, p.maximo, p.minimo, p.estado ";
							$sqlpi .= "FROM pesada_inspeccion p WHERE p.idpi = ".$hacienda[0][12];
							$rspi = mysql_query($sqlpi);
							if(mysql_num_rows($rspi)>0)
								while($filapi=mysql_fetch_assoc($rspi))
									$pi=array($filapi);
							else
								$pi = array('idpi'=>'', 'forma'=>'', 'hora'=>'', 'desbaste'=>'', 'promedio'=>'', 'maximo'=>'', 'minimo'=>'', 'estado'=>'');
						}else{
							$pi = array('idpi'=>'', 'forma'=>'', 'hora'=>'', 'desbaste'=>'', 'promedio'=>'', 'maximo'=>'', 'minimo'=>'', 'estado'=>'');
						}						
						if($hacienda[0][13]>0){
							$sql = "SELECT p.idpd, CONVERT(p.ubicacion USING utf8) as ubicacion, p.arreo, p.camion, p.total, p.promedio, p.maximo, p.minimo, ";
							$sql .= "case p.balanza when 0 then 'SIN BALANZA' when 1 then 'PUBLICA' when 2 then 'SOCIEDAD RURAL' end as balanza, ";
							$sql .= "case p.lugarcamion when 0 then 'SIN DATOS' when 1 then 'ARRIBA' when 2 then 'ABAJO' end as lugarcamion, ";
							$sql .= "p.desbaste, p.observaciones, p.estado FROM pesada_definitiva p WHERE p.idpd = ".$hacienda[0][13];							
							
							$rspd = mysql_query($sql);
							if(mysql_num_rows($rspd)>0){
								while($filapd=mysql_fetch_assoc($rspd)){
									$pd=array($filapd);									
								}
							}
							else{
								$auxpd = array('idpd'=>'', 'ubicacion'=>'', 'arreo'=>'', 'camion'=>'', 'total'=>'', 'balanza'=>'', 'lugarcamion'=>'', 'desbaste'=>'', 'observaciones'=>'', 'estado'=>'');
								$pd = array($auxpd);
							}
						}else{
							$auxpd = array('idpd'=>'', 'ubicacion'=>'', 'arreo'=>'', 'camion'=>'', 'total'=>'', 'balanza'=>'', 'lugarcamion'=>'', 'desbaste'=>'', 'observaciones'=>'', 'estado'=>'');
							$pd = array($auxpd);
						}
						if($hacienda[0][14]>0){
							$sql = "SELECT e.idevaluacion, ";
							$sql .= "case e.calidad when 0 then 'EXCELENTE' when 1 then 'MUY BUENO' when 2 then 'BUENO' when 3 then 'REGULAR' when 4 then 'MALO' end as calidad, ";
							$sql .= "case e.estadoeva when 0 then 'EXCELENTE' when 1 then 'MUY BUENO' when 2 then 'BUENO' when 3 then 'REGULAR' when 4 then 'MALO' end as estadoeva, ";
							$sql .= "case e.sanidad when 0 then 'EXCELENTE' when 1 then 'MUY BUENO' when 2 then 'BUENO' when 3 then 'REGULAR' when 4 then 'MALO' end as sanidad, ";
							$sql .= "case e.uniformidad when 0 then 'UNIFORME' when 1 then 'POCO UNIFORME' when 2 then 'DIVERSO' end as uniformidad, ";
							$sql .= "e.observaciones, e.estado ";
							$sql .= "FROM evaluacion e WHERE e.idevaluacion = ".$hacienda[0][14];
							$rsevaluacion = mysql_query($sql);
							if(mysql_num_rows($rsevaluacion)>0){
								while ($filaevaluacion=mysql_fetch_assoc($rsevaluacion)){
									$evaluacion=array($filaevaluacion);
									break;
								}			
							}else{
								$auxevaluacion = array('idevaluacion'=>'', 'calidad'=>'', 'estadoeva'=>'', 'sanidad'=>'', 'uniformidad'=>'', 'observaciones'=>'', 'estado'=>'');
								$evaluacion = array($auxevaluacion);
							}
						}else{
							$auxevaluacion = array('idevaluacion'=>'', 'calidad'=>'', 'estadoeva'=>'', 'sanidad'=>'', 'uniformidad'=>'', 'observaciones'=>'', 'estado'=>'');
							$evaluacion = array($auxevaluacion);
						}
						if($hacienda[0][15]>0){
							$sql = "SELECT c.idcv, CONVERT(c.plazo USING utf8) as plazo, c.precioinicial, ";
							$sql .= "CASE c.tipoprecio WHEN 0 THEN 'SIN DATOS' WHEN 1 THEN '$/Kg vivo' WHEN 2 THEN '$/Cab' ";
							$sql .= "WHEN 3 THEN '$/Lo que pisa' WHEN 4 THEN '$/Kg rendimiento' END AS tipoprecio, c.estado ";
							$sql .= "FROM condiciones_vta c WHERE c.idcv = ".$hacienda[0][15];
							$rscv = mysql_query($sql);
							if(mysql_num_rows($rscv)>0){
								while($filacv=mysql_fetch_assoc($rscv)){
									$cv=array($filacv);
									break;
								}
							}else{
								$auxcv = array('idcv'=>'', 'plazo'=>'', 'precioinicial'=>'','tipoprecio'=>'', 'estado'=>'');
								$cv = array($auxcv);
							}
						}else{
							$auxcv = array('idcv'=>'', 'plazo'=>'', 'precioinicial'=>'','tipoprecio'=>'', 'estado'=>'');
							$cv = array($auxcv);
						}
						if($hacienda[0][16]>0){
							$sql = "SELECT idusuario as idevaluador, CONVERT(apellido USING utf8) as apellido, CONVERT(nombre USING utf8) as nombre ";
							$sql .= "FROM usuarios u ";
							$sql .= "WHERE idusuario = ".$hacienda[0][16];
							$rsevaluador = mysql_query($sql);
							if(mysql_num_rows($rsevaluador)>0){
								while($filaevaluador=mysql_fetch_assoc($rsevaluador)){
									$evaluador=array($filaevaluador);
								}
							}							
							else{
								$auxaevaluador = array('idevaluador'=>'', 'apellido'=>'', 'nombre'=>'');
								$evaluador = array($auxaevaluador);
							}
						}else{
							$auxaevaluador = array('idevaluador'=>'', 'apellido'=>'', 'nombre'=>'');
							$evaluador = array($auxaevaluador);
						}
						if($hacienda[0][17]>0){
							$sql = "SELECT idlocalidad, codprov FROM establecimiento WHERE idestablecimiento = ".$hacienda[0][17];
							$rsestablecimiento = mysql_query($sql);
							if(mysql_num_rows($rsestablecimiento)>0){
								$festablecimiento = mysql_fetch_row($rsestablecimiento);
								$sql = "SELECT l.idlocalidad, l.nombre FROM localidad l WHERE l.idlocalidad = ".$festablecimiento[0];
								$rslocalidad = mysql_query($sql);
								if(mysql_num_rows($rslocalidad)>0){
									while($filalocalidad=mysql_fetch_assoc($rslocalidad)){
										$localidad=array($filalocalidad);
									}
								}else{
									$auxlocalidad = array('idlocalidad'=>'','nombre'=>'','codprov'=>'');
									$localidad = array($auxlocalidad);
								}
								$sql = "SELECT p.codprov, p.nombre FROM provincias p WHERE p.codprov = '".$festablecimiento[1]."'";							
								$rsprovincia = mysql_query($sql);		
								if(mysql_num_rows($rsprovincia)>0){
									while($filaprovincia=mysql_fetch_assoc($rsprovincia)){
										$provincia=array($filaprovincia);
									}
								}else{
									$auxprovincia = array('codprov'=>'', 'nombre'=>'');
									$provincia = array($auxprovincia);
								}
							}
						}else{
							$auxlocalidad = array('idlocalidad'=>'','nombre'=>'', 'codprov'=>'');
							$localidad = array($auxlocalidad);
							$auxprovincia = array('codprov'=>'', 'nombre'=>'');
							$provincia = array($auxprovincia);
						}
						if($hacienda[0][0]>0){
							$sql = "SELECT idhi, concat('fotos/', imagen) AS imagen, estado FROM hacienda_img WHERE idhacienda = ".$hacienda[0][0];
							$rsimagen = mysql_query($sql);
							if(mysql_num_rows($rsimagen)>0){
								$imagen = array();
								while($img=mysql_fetch_assoc($rsimagen)){
									$imagen[] = $img;
								}
							}else{
								$imagen = array('idhi'=>'', 'imagen'=>'', 'estado'=>'');
							}
						}else{
							$imagen = array('idhi'=>'', 'imagen'=>'', 'estado'=>'');
						}
						if($hacienda[0][0]>0){
							$sql = "SELECT idvideo, video FROM hacienda_video WHERE idhacienda = ".$hacienda[0][0];
							
							$rsvideo = mysql_query($sql);
							if(mysql_num_rows($rsvideo)>0){
								while($vid=mysql_fetch_assoc($rsvideo)){
									$video[] = array($vid);
								}
							}else{
								$video[] = array('idvideo'=>0, 'video'=>'');
							}
						}else{
							$video[] = array('idvideo'=>0, 'video'=>'');
						}
						$arr = array(
							'idlote'=>$fila['idlote'],
							'nrolote'=>$fila['nrolote'],
							'cantcabezas'=>$fila['cantcabezas'],
							'tipoentrega'=>$fila['tipoentrega'],
							'trazados'=>$hacienda[0][1],
							'marcaliquida'=>$hacienda[0][2],
							'razatipo'=>utf8_encode($hacienda[0][3]),
							'pelaje'=>utf8_encode($hacienda[0][4]),
							'destetados'=>$hacienda[0][5],
							'alimentacion'=>$hacienda[0][6],
							'observaciones'=>utf8_encode($hacienda[0][7]),
							'edad'=>$hacienda[0][8],
							'categoria'=>utf8_encode($hacienda[0][9]),
							'vientre'=>$vientre,
							'evaluador'=>$evaluador,
							'pi'=>$pi,
							'pd'=>$pd,
							'evaluacion'=>$evaluacion,
							'cv'=>$cv,
							'localidad'=>$localidad,
							'provincia'=>$provincia,
							'imagen'=>$imagen,
							'video'=>$video,
							'success'=>true);
					}else{
						array('success'=>false);
					}				
			}
		}else{				
			$arr = array('success'=>false);
		}		
		echo json_encode($arr);
	//}
?>