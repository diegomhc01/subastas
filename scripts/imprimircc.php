<?php 
	include('validar.php');	
	include('coneccion.php');	
	if(isset($_SESSION['susuario'])){
		$idremate = $_SESSION['sidremate'];
		mysql_set_charset('utf8');

		$sql = "SELECT fecha, hora, titulo FROM remate WHERE idremate = $idremate";
		$rsr = mysql_query($sql);
		$arrr = array();
		while($fila=mysql_fetch_array($rsr)){
			$arrr[] = $fila;
		}

		$sql = "SELECT hl.idlote, l.nrolote, l.cantcabezas, c.descripcion, ";
		$sql .= "case h.trazados when 0 then 'NO' when 1 then 'SI' END trazados, "; 
		$sql .= "pi.promedio, l.precioinicio, lo.nombre, pr.nombre, cv.plazo ";
		$sql .= "FROM lotes l, hacienda h, hacienda_lote hl, localidad lo, ";
		$sql .= "provincias pr, categoria c, pesada_inspeccion pi, condiciones_vta cv, ";
		$sql .= "establecimiento e ";
		$sql .= "WHERE l.idremate = $idremate and l.idlote = hl.idlote and ";
		$sql .= "hl.idhacienda = h.idhacienda and e.idestablecimiento = h.idestablecimiento and ";
		$sql .= "e.idlocalidad = lo.idlocalidad and e.codprov = pr.codprov and ";
		$sql .= "h.idcategoria = c.idcategoria and h.idpi = pi.idpi and h.idcv = cv.idcv ";
		$sql .= "order by 2";

		$rs = mysql_query($sql);
		$arrh = array();
		if(mysql_num_rows($rs)>0){
			while($fila=mysql_fetch_array($rs)){				
				$arrh[] = $fila;
			}
		}
		include('../reportes/catalogocc.php');

		$url = './reportes/'.$archivo;				
		$arr = array('success'=>true,'url'=>$url);
		echo json_encode($arr);
	}
?>