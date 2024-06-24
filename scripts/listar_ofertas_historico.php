<?php
	include('validar.php');	
	include('coneccion.php');	
	if(isset($_SESSION['susuario'])){	
		$valsel = $_GET['param'];			

		$sql = "SELECT o.idoferta AS id, CONCAT( u.nombre,  ' ', u.apellido ) AS cliente, o.monto AS oferta, ";
		$sql .= "CASE o.estado WHEN 1 THEN 'OFERTA OMITIDA' WHEN 2 THEN 'OFERTA SUPERADA' WHEN 3 THEN 'OFERTA ACEPTADA' ";
		$sql .= "WHEN 4 THEN 'OFERTA ANULADA' WHEN 5 THEN 'OFERTA GANADORA' END AS desestado, o.estado ";
		$sql .= "FROM ofertas o, usuarios u ";
		$sql .= "WHERE o.estado > 0 AND o.idlote = $valsel and ";
		$sql .= "o.usuario = u.usuario and u.perfil = 1 ";
		$sql .= "UNION ";
		$sql .= "SELECT o.idoferta AS id, 'PISTA' AS cliente, o.monto AS oferta, ";
		$sql .= "CASE o.estado WHEN 1 THEN 'OFERTA OMITIDA' WHEN 2 THEN 'OFERTA SUPERADA' WHEN 3 THEN 'OFERTA ACEPTADA' ";
		$sql .= "WHEN 4 THEN 'OFERTA ANULADA' WHEN 5 THEN 'OFERTA GANADORA' END AS desestado, o.estado ";
		$sql .= "FROM ofertas o, usuarios u ";
		$sql .= "WHERE o.estado > 0 AND o.idlote = $valsel and ";
		$sql .= "o.usuario = u.usuario and u.perfil = 4 ";		
		$sql .= "ORDER BY id DESC";
		$rs = mysql_query($sql);
		$cantidad = mysql_num_rows($rs);		
		$arr = array();
		$arr2 = array();
		if($cantidad>0){
			while($row=mysql_fetch_array($rs)){
				$arr['cliente'] = utf8_encode($row[1]);
				$arr['oferta'] = $row[1];
				$arr['estado'] = $row[2];
				if($row[4]=='3') //ACEPTADA
					$arr['boton'] = '<input type="image" name="btnofertalote" src="images/seleccionar.png" value="Oferta" class="clsofertalote" id="'.$row[0].'">';
				else
					$arr['boton'] = '';					
				$arr2[] = $arr;
			}
		}		
		echo json_encode(array('aaData'=>$arr2));
	}
?>