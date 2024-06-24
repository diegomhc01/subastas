<?php	
	include('validar.php');
	include('coneccion.php');	
	if(isset($_SESSION['susuario'])){
		$idfirma = $_SESSION['sidfirma'];
		$asignar = false;
		$rematadas = false;
		$rematar = false;		
		$sql = '';
		$arr = '';
		if(isset($_SESSION['sasignar']) && $_SESSION['sasignar']) $asignar = true;
		if(isset($_SESSION['srematadas']) && $_SESSION['srematadas']) $rematadas = true;
		if(isset($_SESSION['srematar']) && $_SESSION['srematar']) $rematar = true;
		if($asignar && $rematadas && $rematar){
			$sql = "SELECT h.idhacienda, ";
			$sql .= "CASE h.trazados WHEN 0 THEN 'NO TRAZADOS' WHEN 1 THEN 'TRAZADOS' END AS trazados, h.cantidad, "; 
			$sql .= "CASE h.marcaliquida WHEN 0 THEN 'SIN MARCA LIQUIDA' WHEN 1 THEN 'CON MARCA LIQUIDA' END AS marcaliquida, ";
			$sql .= "h.razatipo, h.pelaje, ";
			$sql .= "CASE h.destetados WHEN 0 THEN 'NO' WHEN 1 THEN 'SI' END AS destetados, ";
			$sql .= "CASE h.alimentacion WHEN 0 THEN 'RACIONADOS' WHEN 1 THEN 'SUPLEMENTADOS' WHEN 2 THEN 'A CAMPO' END AS alimentacion, ";
			$sql .= "h.mochos, h.descornados, h.astados, h.enteros, h.querato, h.estado, c.descripcion, h.edad, p.apeynom, cv.precioinicial, ";
			$sql .= "tp.descripcion, nrocontrato ";
			$sql .= "FROM hacienda h, categoria c, cliente cl, persona p, condiciones_vta cv, tipoprecio tp ";
			$sql .= "WHERE c.idcategoria = h.idcategoria and cl.idcliente = h.idvendedor and ";
			$sql .= "cl.idpersona = p.idpersona and cv.idcv = h.idcv and cv.tipoprecio = tp.idtp and ";
			$sql .= "h.idfirma = $idfirma and h.idhacienda ";
		}else{		
			if($asignar){			
				$sql .= "SELECT h.idhacienda, ";
				$sql .= "CASE h.trazados WHEN 0 THEN 'NO TRAZADOS' WHEN 1 THEN 'TRAZADOS' END AS trazados, h.cantidad, "; 
				$sql .= "CASE h.marcaliquida WHEN 0 THEN 'SIN MARCA LIQUIDA' WHEN 1 THEN 'CON MARCA LIQUIDA' END AS marcaliquida, ";
				$sql .= "h.razatipo, h.pelaje, ";
				$sql .= "CASE h.destetados WHEN 0 THEN 'NO' WHEN 1 THEN 'SI' END AS destetados, ";
				$sql .= "CASE h.alimentacion WHEN 0 THEN 'RACIONADOS' WHEN 1 THEN 'SUPLEMENTADOS' WHEN 2 THEN 'A CAMPO' END AS alimentacion, ";
				$sql .= "h.mochos, h.descornados, h.astados, h.enteros, h.querato, h.estado, c.descripcion, h.edad, p.apeynom, cv.precioinicial, ";
				$sql .= "tp.descripcion, nrocontrato ";
				$sql .= "FROM hacienda h, categoria c, cliente cl, persona p, condiciones_vta cv, tipoprecio tp ";
				$sql .= "WHERE c.idcategoria = h.idcategoria and cl.idcliente = h.idvendedor and ";
				$sql .= "cl.idpersona = p.idpersona and cv.idcv = h.idcv and cv.tipoprecio = tp.idtp and ";
				$sql .= "h.idfirma = $idfirma and h.idhacienda NOT IN ";
				$sql .= "(SELECT hl.idhacienda FROM hacienda_lote hl, lotes l) ";
			}
			if($rematadas){
				if($asignar) $sql .=" UNION ";
				$sql .= "SELECT h.idhacienda, ";
				$sql .= "CASE h.trazados WHEN 0 THEN 'NO TRAZADOS' WHEN 1 THEN 'TRAZADOS' END AS trazados, h.cantidad, "; 
				$sql .= "CASE h.marcaliquida WHEN 0 THEN 'SIN MARCA LIQUIDA' WHEN 1 THEN 'CON MARCA LIQUIDA' END AS marcaliquida, ";
				$sql .= "h.razatipo, h.pelaje, ";
				$sql .= "CASE h.destetados WHEN 0 THEN 'NO' WHEN 1 THEN 'SI' END AS destetados, ";
				$sql .= "CASE h.alimentacion WHEN 0 THEN 'RACIONADOS' WHEN 1 THEN 'SUPLEMENTADOS' WHEN 2 THEN 'A CAMPO' END AS alimentacion, ";
				$sql .= "h.mochos, h.descornados, h.astados, h.enteros, h.querato, h.estado, c.descripcion, h.edad, p.apeynom, cv.precioinicial, ";
				$sql .= "tp.descripcion, nrocontrato ";
				$sql .= "FROM hacienda h, categoria c, cliente cl, persona p, condiciones_vta cv, tipoprecio tp ";
				$sql .= "WHERE c.idcategoria = h.idcategoria and cl.idcliente = h.idvendedor and ";
				$sql .= "cl.idpersona = p.idpersona and cv.idcv = h.idcv and cv.tipoprecio = tp.idtp and ";
				$sql .= "h.idfirma = $idfirma and h.idhacienda IN ";
				$sql .= "(SELECT hl.idhacienda FROM hacienda_lote hl, lotes l WHERE l.estado = 3) ";
			}
			if($rematar){
				if($asignar || $rematadas) $sql .=" UNION ";
				$sql .= "SELECT h.idhacienda, ";
				$sql .= "CASE h.trazados WHEN 0 THEN 'NO TRAZADOS' WHEN 1 THEN 'TRAZADOS' END AS trazados, h.cantidad, "; 
				$sql .= "CASE h.marcaliquida WHEN 0 THEN 'SIN MARCA LIQUIDA' WHEN 1 THEN 'CON MARCA LIQUIDA' END AS marcaliquida, ";
				$sql .= "h.razatipo, h.pelaje, ";
				$sql .= "CASE h.destetados WHEN 0 THEN 'NO' WHEN 1 THEN 'SI' END AS destetados, ";
				$sql .= "CASE h.alimentacion WHEN 0 THEN 'RACIONADOS' WHEN 1 THEN 'SUPLEMENTADOS' WHEN 2 THEN 'A CAMPO' END AS alimentacion, ";
				$sql .= "h.mochos, h.descornados, h.astados, h.enteros, h.querato, h.estado, c.descripcion, h.edad, p.apeynom, cv.precioinicial, ";
				$sql .= "tp.descripcion, nrocontrato ";
				$sql .= "FROM hacienda h, categoria c, cliente cl, persona p, condiciones_vta cv, tipoprecio tp ";
				$sql .= "WHERE c.idcategoria = h.idcategoria and cl.idcliente = h.idvendedor and ";
				$sql .= "cl.idpersona = p.idpersona and cv.idcv = h.idcv and cv.tipoprecio = tp.idtp and ";
				$sql .= "h.idfirma = $idfirma and h.idhacienda IN ";
				$sql .= "(SELECT hl.idhacienda FROM hacienda_lote hl, lotes l WHERE l.estado = 0) ";				
			}
		}
		if($sql!=''){
			$rs = mysql_query($sql);
			$i=0;
			if(mysql_num_rows($rs) > 0){
				while($row=mysql_fetch_array($rs)){
					$sql = "SELECT count(*) FROM hacienda_img WHERE idhacienda = ".$row[0];
					$rs1 = mysql_query($sql);
					$foto = mysql_fetch_row($rs1);
					$arr .= '["'.$row[19].'","'.$row[16].'","'.$row[14].'","'.$row[13].'","'.$row[4].'","'.$row[5].'","'.$row[2].'","'.$row[6].'","'.$row[7].'","'.$row[17].'","'.$row[18].'",';
					$arr .= '"<input type=\"image\" name=\"btnhacienda\" src=\"images/modificar.png\" value=\"Modificar\" class=\"clshacienda\" id=\"mo'.$row[0].'\">",';
					$arr .= '"<input type=\"image\" name=\"btnhacienda\" src=\"images/eliminar.png\" value=\"Eliminar\" class=\"clshacienda\" id=\"el'.$row[0].'\">",';
					if($foto[0]==0){
						$arr .= '"<input type=\"image\" name=\"btnhacienda\" src=\"images/foto.png\" value=\"Fotos\" class=\"clshacienda\" id=\"fo'.$row[0].'\">"],';
					}else{
						$arr .= '"<input type=\"image\" name=\"btnhacienda\" src=\"images/fotoc.png\" value=\"Fotos\" class=\"clshacienda\" id=\"fo'.$row[0].'\">"],';
					}					
				}
			}
		}
		$arr = '{"aaData":['.trim($arr,',').']}';
		echo $arr;		
	}
?>