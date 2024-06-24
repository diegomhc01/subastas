<?php	
	session_start();
	include('coneccion.php');	
	if(isset($_SESSION['susuario'])){
		$idlote = $_POST['param'];
		$sql = "SELECT l.idlote, l.cantcabezas, l.estado, l.incremento, l.nrolote, c.descripcion, lo.nombre, pr.nombre, tp.inc1, tp.inc2, tp.inc3, h.idcv, ";
		$sql .= "CASE h.trazados WHEN 0 THEN 'NO' WHEN 1 THEN 'SI' END AS trazados, "; 
		$sql .= "CASE h.marcaliquida WHEN 0 THEN 'NO' WHEN 1 THEN 'SI' END AS marcaliquida, ";		
		$sql .= "CASE l.tipoentrega WHEN 0 THEN 'INMEDIATA' WHEN 1 THEN 'A TERMINO' END AS tipoentrega, h.idpi, ";
		$sql .= "CONCAT(u.nombre , ' ', u.apellido) AS evaluador, l.precioinicio, tp.descripcion AS tipoprecio, l.idtp ";
		$sql .= "FROM lotes l, hacienda h, hacienda_lote hl, localidad lo, provincias pr, categoria c, usuarios u, tipoprecio tp ";
		$sql .= "WHERE l.idlote = $idlote and hl.idlote = l.idlote and hl.idhacienda = h.idhacienda and h.idlocalidad = lo.idlocalidad and ";
		$sql .= "h.codprov = pr.codprov and h.idcategoria = c.idcategoria and h.idevaluador = u.idusuario and l.idtp = tp.idtp";
		$rs = mysql_query($sql);
		if(mysql_num_rows($rs)>0){
			while($row=mysql_fetch_array($rs)){
				$sql = "SELECT c.descripcion, hl.cantidad FROM hacienda_lote hl, hacienda h, categoria c ";
				$sql .= "WHERE h.idcategoria = c.idcategoria and h.idhacienda = hl.idhacienda and hl.idlote = ".$row[0];				
				$desc = '';
				$rshl = mysql_query($sql);
				$desc = '(';
				$cant = 0;
				while($fila=mysql_fetch_array($rshl)){
					if($cant==0){
						$desc .= $fila[1].' '.$fila[0];	
					}else{
						$desc .= ' - '.$fila[1].' '.$fila[0];	
					}
					$cant ++; 					
				}
				$desc .= ')';
				$plazo = '';
				$promedio = '';

				$sql = "SELECT promedio FROM pesada_inspeccion WHERE idpi = ".$row[15];
				$rspi = mysql_query($sql);
				if(mysql_num_rows($rspi)>0){
					$filapi = mysql_fetch_row($rspi);
					$promedio = $filapi[0];
				}

				$arr = array('idlote'=>$row[0],
					'cantcabezas'=>$row[1],
					'estado'=>$row[2],
					'success'=>true,
					'incremento'=>$row[3],
					'nrolote'=>$row[4],
					//'categoria'=>$row[5],
					'categoria'=>$desc,
					'localidad'=>$row[6],
					'provincia'=>$row[7],
					'inc1'=>$row[8],
					'inc2'=>$row[9],
					'inc3'=>$row[10],
					'trazados'=>$row[12],
					'marcaliquida'=>$row[13],
					'tipoentrega'=>$row[14],
					'plazo'=>$plazo,
					'tipoprecio'=>$row[18],
					'idtp'=>$row[19],
					'promedio'=>$promedio,
					'evaluador'=>$row[16],
					'estado'=>$row[2],
					'precioinicio'=>$row[17]);
			}
		}else{				
			$arr = array('success'=>false);
		}
		echo json_encode($arr);
	}
?>