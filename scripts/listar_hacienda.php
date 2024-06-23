<?php	
	include('validar.php');
	include('coneccion.php');	
	if(isset($_SESSION['susuario'])){
		$idfirma = $_SESSION['sidfirma'];
		$asignar = false;
		$rematadas = false;
		$rematar = false;		
		$sql = '';
		$arr2 = array();
		if(isset($_SESSION['sasignar']) && $_SESSION['sasignar']) $asignar = true;
		if(isset($_SESSION['srematadas']) && $_SESSION['srematadas']) $rematadas = true;
		if(isset($_SESSION['srematar']) && $_SESSION['srematar']) $rematar = true;
		$asignar = true;
		if($asignar && $rematadas && $rematar){
			$sql = "SELECT h.idhacienda, ";
			$sql .= "CASE h.trazados WHEN 0 THEN 'NO TRAZADOS' WHEN 1 THEN 'TRAZADOS' END AS trazados, h.cantidad, "; 
			$sql .= "CASE h.marcaliquida WHEN 0 THEN 'SIN MARCA LIQUIDA' WHEN 1 THEN 'CON MARCA LIQUIDA' END AS marcaliquida, ";
			$sql .= "h.razatipo, h.pelaje, ";
			$sql .= "CASE h.destetados WHEN 0 THEN 'NO' WHEN 1 THEN 'SI' END AS destetados, ";
			$sql .= "CASE h.alimentacion WHEN 0 THEN 'RACIONADOS' WHEN 1 THEN 'SUPLEMENTADOS' WHEN 2 THEN 'A CAMPO' WHEN 3 THEN 'SIN DATOS' END AS alimentacion, ";
			$sql .= "h.mochos, h.descornados, h.astados, h.enteros, h.querato, h.estado, c.descripcion as categoria, h.edad, p.apeynom, cv.precioinicial, ";
			$sql .= "tp.descripcion as tipoprecio, nrocontrato ";
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
				$sql .= "h.mochos, h.descornados, h.astados, h.enteros, h.querato, h.estado, c.descripcion as categoria, h.edad, p.apeynom, cv.precioinicial, ";
				$sql .= "tp.descripcion as tipoprecio, nrocontrato ";
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
				$sql .= "h.mochos, h.descornados, h.astados, h.enteros, h.querato, h.estado, c.descripcion as categoria, h.edad, p.apeynom, cv.precioinicial, ";
				$sql .= "tp.descripcion as tipoprecio, nrocontrato ";
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
				$sql .= "h.mochos, h.descornados, h.astados, h.enteros, h.querato, h.estado, c.descripcion as categoria, ";
				$sql .= "h.edad, p.apeynom, cv.precioinicial, tp.descripcion as tipoprecio, nrocontrato ";
				$sql .= "FROM hacienda h, categoria c, cliente cl, persona p, condiciones_vta cv, tipoprecio tp ";
				$sql .= "WHERE c.idcategoria = h.idcategoria and cl.idcliente = h.idvendedor and ";
				$sql .= "cl.idpersona = p.idpersona and cv.idcv = h.idcv and cv.tipoprecio = tp.idtp and ";
				$sql .= "h.idfirma = $idfirma and h.idhacienda IN ";
				$sql .= "(SELECT hl.idhacienda FROM hacienda_lote hl, lotes l WHERE l.estado = 0) ";				
			}
		}
		if($sql!=''){
			$rs = mysql_query($sql);
			if(mysql_num_rows($rs) > 0){
				while($row=mysql_fetch_assoc($rs)){
					$sql = "SELECT count(*) as cantidadfotos FROM hacienda_img WHERE idhacienda = ".$row['idhacienda'];
					$rs1 = mysql_query($sql);
					$foto = mysql_fetch_assoc($rs1);
					$arr = array();
					$arr['nrocontrato'] = utf8_encode($row['nrocontrato']);
					$arr['apeynom'] = utf8_encode($row['apeynom']);
					$arr['categoria'] = utf8_encode($row['categoria']);
					$arr['edad'] = $row['edad'];
					$arr['razatipo'] = utf8_encode($row['razatipo']);
					$arr['pelaje'] = utf8_encode($row['pelaje']);
					$arr['cantidad'] = $row['cantidad'];
					$arr['destetados'] = $row['destetados'];
					$arr['alimentacion'] = $row['alimentacion'];
					$arr['precioinicial'] = $row['precioinicial'];
					$arr['tipoprecio'] = $row['tipoprecio'];
					$arr['modificar'] = '<input type="image" name="btnhacienda" class="clshacienda" src="images/modificar.png" id="mo'.$row['idhacienda'].'">';
					$arr['eliminar'] = '<input type="image" name="btnhacienda" class="clshacienda" src="images/eliminar.png" id="el'.$row['idhacienda'].'">';
					if($foto['cantidadfotos']==0){
						$arr['fotos'] = '<input type="image" name="btnhacienda" src="images/foto.png" value="Fotos" class="clshacienda" id="fo'.$row['idhacienda'].'">';					
					}else{
						$arr['fotos'] = '<input type="image" name="btnhacienda" src="images/fotoc.png" value="Fotos" class="clshacienda" id="fo'.$row['idhacienda'].'">';
					}
					$arr2[] = $arr;
				}
			}
		}
		echo json_encode(array('aaData'=>$arr2));
	}
?>