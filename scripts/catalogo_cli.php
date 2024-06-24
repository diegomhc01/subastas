<?php	
	include('validar.php');
	include('coneccion.php');	
	if(isset($_SESSION['sidrematec']) || isset($_SESSION['sidremate'])){		
		if(isset($_SESSION['sidremate'])){
			$idrematec = $_SESSION['sidremate'];
		}else{
			if(isset($_SESSION['sidrematec'])){
				$idrematec = $_SESSION['sidrematec'];
			}			
		}
	
		$nrolote = 0;
		$categoriastr='';
		$texto = '';
		$sql = "SELECT l.idlote, l.nrolote, l.cantcabezas, c.descripcion, h.razatipo, hl.cantidad, ";
		$sql .= "CONCAT(lo.nombre,' - ',p.nombre) as ubicacion, pi.promedio ";
		$sql .= "FROM lotes l, hacienda_lote hl, remate r, hacienda h, categoria c, establecimiento e, localidad lo, provincias p, pesada_inspeccion pi ";
		$sql .= "WHERE r.idremate = $idrematec and l.idlote = hl.idlote and l.idremate = r.idremate and ";
		$sql .= "l.estado < 4 and ";
		$sql .= "h.idhacienda = hl.idhacienda and h.idcategoria = c.idcategoria and ";
		$sql .= "h.idestablecimiento = e.idestablecimiento and e.idlocalidad = lo.idlocalidad and  ";
		$sql .= "e.codprov = p.codprov and h.idpi = pi.idpi ";
		$sql .= "ORDER BY 2";
		
		$rs = mysql_query($sql);
		
		$arr2 = array();
		$cantidad = mysql_num_rows($rs);
		if($cantidad>0){
			while($row=mysql_fetch_array($rs)){
				if($nrolote!=$row[1]){
					if($texto!=''){
						$arr = array();
						$categoriastr .= ']';
						$texto .= $categoriastr;
						$arr['lote'] = $texto;						
						$arr['boton'] = '<input type="image" name="rblotes" src="images/ver.png" id="lote'.$idlote.'" class="clslotescat" value="Detalle">';
						$arr2[] = $arr;
						$categoriastr = '';						
					}
					$idlote = $row[0];
					$nrolote=$row[1];
					$cantidadlote = $row[2];
					$cantidadhl =  $row[5];
					$categoria = utf8_encode($row[3]);
					$razatipo = utf8_encode($row[4]);
					$ubicacion = utf8_encode($row[6]);
					$promedio = $row[7];
					$texto = 'Lote N° <strong>'.$nrolote. '</strong> - Cantidad de cabezas <strong>'.$cantidadlote.'</strong> - ';
					$categoriastr = '[<strong>'.$cantidadhl.'</strong> '.'<strong>'.$categoria.' - '.$razatipo.' ('.$ubicacion.') - '.$promedio.' Kg</strong>';
				}else{
					$cantidadhl =  $row[5];
					$categoria = utf8_encode($row[3]);
					$razatipo = utf8_encode($row[4]);					
					$ubicacion = utf8_encode($row[6]);
					$promedio = $row[7];
					$categoriastr .= ' - <strong>'.$cantidadhl.'</strong> '.'<strong>'.$categoria.' - '.$razatipo.' ('.$ubicacion.') - '.$promedio.' Kg</strong>';
				}
			}
			$arr = array();
			$categoriastr .= ']';
			$texto .= $categoriastr;
			$arr['lote'] = $texto;						
			$arr['boton'] = '<input type="image" name="rblotes" src="images/ver.png" id="lote'.$idlote.'" class="clslotescat" value="Detalle">';
			$arr2[] = $arr;				
		}
		echo json_encode(array('data'=>$arr2));		
	}else{
		$arr2 = array();
		echo json_encode(array('data'=>$arr2));		
	}
?>