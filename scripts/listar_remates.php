<?php
	include('validar.php');
	include('coneccion.php');	
	if(isset($_SESSION['susuario'])){
		$idfirma = $_SESSION['sidfirma'];

		$sql = "SELECT r.idremate, DATE_FORMAT(r.fecha, '%d-%m-%Y'), r.hora, r.estado, SUM(cantcabezas) AS cabezas, ";
		$sql .= "r.concepto, r.titulo, r.tipo, r.numero ";
		$sql .= "FROM remate r, lotes l WHERE r.idremate = l.idremate and r.idfirma = $idfirma ";	
		$sql .= "GROUP BY r.idremate, r.fecha, r.hora, r.estado, r.concepto, r.titulo, r.tipo, r.numero";
		$rs0 = mysql_query($sql);

		if(mysql_num_rows($rs0)>0){
			$fila = mysql_fetch_row($rs0);
		}
		if($fila[0]!=null){
			$sql = "SELECT r.idremate, DATE_FORMAT(r.fecha, '%d-%m-%Y'), r.hora, r.estado, SUM(cantcabezas) AS cabezas, r.concepto, r.titulo, ";
			$sql .= "case r.tipo when 1 then 'INTERNET' when 2 then 'FERIA' when 3 then 'CABAÑA' end AS tipo, r.numero, r.publicado ";
			$sql .= "FROM remate r, lotes l WHERE r.idremate = l.idremate and r.idfirma = $idfirma ";
			$sql .= "GROUP BY r.idremate, r.fecha, r.hora, r.estado, r.concepto, r.titulo, r.tipo, r.numero ";
			$sql .= "UNION ";
			$sql .= "SELECT r.idremate, DATE_FORMAT(r.fecha, '%d-%m-%Y'), r.hora, r.estado, 0 AS cabezas, r.concepto, r.titulo, ";
			$sql .= "case r.tipo when 1 then 'INTERNET' when 2 then 'FERIA' when 3 then 'CABAÑA' end AS tipo, r.numero, r.publicado ";
			$sql .= "FROM remate r WHERE r.idremate NOT IN (SELECT idremate FROM lotes) and r.idfirma = $idfirma ";
			$sql .= "GROUP BY r.idremate, r.fecha, r.hora, r.estado, r.concepto, r.titulo, r.tipo, r.numero ";			
			$sql .= "ORDER BY 1 DESC";
		}else{
			$sql = "SELECT r.idremate, DATE_FORMAT(r.fecha, '%d-%m-%Y'), r.hora, r.estado, 0 AS cabezas, r.concepto, r.titulo, ";
			$sql .= "case r.tipo when 1 then 'INTERNET' when 2 then 'FERIA' when 3 then 'CABAÑA' end AS tipo, r.numero, r.publicado ";
			$sql .= "FROM remate r WHERE r.idremate NOT IN (SELECT idremate FROM lotes) and r.idfirma = $idfirma ";
			$sql .= "ORDER BY 1 DESC";
		}

		$rs = mysql_query($sql);
		$arr2 = array();
		if(mysql_num_rows($rs)>0){
			while($row=mysql_fetch_array($rs)){
				$arr = array();
				$titulo = str_replace('"', '\"', utf8_encode($row[6]));
				$arr['nro'] = utf8_encode($row[8]);
				$arr['titulo'] = $titulo;
				$arr['fecha'] = $row[1];
				$arr['hora'] = utf8_encode($row[2]);
				$arr['cabezas'] = $row[4];
				$arr['tipo'] = $row[7];
				$arr['boton1'] = '<input type="image" name="btnremate" src="images/modificar.png" value="Modificar" class="clsremate" id="m'.$row[0].'">';
				$arr['boton2'] = '<input type="image" name="btnremate" src="images/eliminar.png" value="Eliminar" class="clsremate" id="e'.$row[0].'">';
				if($row[3]==0)
					$arr['boton3'] = '<input type="image" name="btnremate" src="images/deshabilitado.png" value="Deshabilitar" class="clsremate" id="d'.$row[0].'">';
				if($row[3]==1)
					$arr['boton3'] = '<input type="image" name="btnremate" src="images/habilitado.png" value="Habilitar" class="clsremate" id="h'.$row[0].'">';
				if($row[9]==0)
					$arr['boton4'] = '<input type="image" name="btnremate" src="images/nopublicado.png" value="Publicar" class="clsremate" id="p'.$row[0].'">';
				if($row[9]==1)
					$arr['boton4'] = '<input type="image" name="btnremate" src="images/publicado.png" value="No Publicar" class="clsremate" id="n'.$row[0].'">';

				$arr['boton5'] = '<input type="image" name="btnremate" src="images/vaca.png" value="Lotes" class="clsremate" id="l'.$row[0].'">';
				$arr['boton6'] = '<input type="image" name="btnremate" src="images/email.png" value="Correo" class="clsremate" id="c'.$row[0].'">';

				$arr2[] = $arr;
			}
		}
		echo json_encode(array('aaData'=>$arr2));

	}
?>