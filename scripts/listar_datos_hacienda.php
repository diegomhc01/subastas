<?php	
	include('validar.php');
	include('coneccion.php');	
	if(isset($_SESSION['susuario'])){
		
		$sql = "SELECT h.idhacienda, p.apeynom, c.descripcion, h.razatipo, h.pelaje, h.cantidad, ";
		$sql .= "case h.trazados when 1 then 'Trazados' when 0 then '' end as trazados, ";
		$sql .= "case h.destetados when 1 then 'Destetados' when 0 then '' end as destetados, ";
		$sql .= "case h.alimentacion when 0 then 'Racionados' when 1 then 'Suplementados' when 2 then 'A campo' when 3 then 'Sin Datos' end AS alimentacion, ";
		$sql .= "case h.marcaliquida when 1 then 'Con ML' when 2 then '' end AS ML, ";
		$sql .= "cv.precioinicial, ";
		$sql .= "case cv.tipoprecio ";
		$sql .= "when 1 then '$/Kg vivo' ";
		$sql .= "when 2 then '$/Cab' ";
		$sql .= "when 3 then '$/Lo que pisa' ";
		$sql .= "when 4 then '$/Kg rendimiento' end AS tipoprecio, h.resto, ";
		$sql .= "pi.promedio, h.nrocontrato ";
		$sql .= "FROM hacienda h, categoria c, cliente cl, persona p, condiciones_vta cv, pesada_inspeccion pi ";
		$sql .= "WHERE c.idcategoria = h.idcategoria and resto > 0 and ";
		$sql .= "cl.idcliente = h.idvendedor and cl.idpersona = p.idpersona and cv.idcv = h.idcv and h.idpi = pi.idpi ";
		$sql .= "ORDER BY c.descripcion";
		//$sql .= "ORDER BY h.nrocontrato";
		$rs = mysql_query($sql);

		$arr = array();
		$arr2 = array();
		if(mysql_num_rows($rs) > 0){
			while($row=mysql_fetch_array($rs)){
				$arr['nrocontrato'] = $row[14];
				$arr['apeynom'] = utf8_encode($row[1]);
		        $arr['categoria'] = utf8_encode($row[2]);		        
		        $arr['razatipo'] = utf8_encode($row[3]);
		        $arr['pelaje'] = utf8_encode($row[4]);
		        $arr['cantidad'] = $row[5];
		        $arr['trazados'] = $row[6];
		        $arr['destetados'] = $row[7];
		        $arr['alimentacion'] = $row[8];
		        $arr['marcaliquida'] = $row[9];
		        $arr['precioinicial'] = $row[10];
		        $arr['tipoprecio'] = $row[11];
		        $arr['resto'] = $row[12];
		        $arr['peso'] = $row[13];
		        $arr['boton'] = '<input type="checkbox" name="btnhaciendalote[]" class="clshaciendalote" id="addh'.$row[0].'">';

		        $arr2[] = $arr;
			}
		}		
		echo json_encode(array('aaData'=>$arr2));
	}
?>