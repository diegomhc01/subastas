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
			$arrr = $fila;
		}

		$sql = "SELECT h.idhacienda, l.nrolote, l.cantcabezas, l.precioinicio, cv.plazo, ";
		$sql .= "CONCAT(u.apellido,',', u.nombre) AS evaluador, ";
		$sql .= "lo.nombre, pr.nombre, p.apeynom as vendedor, hl.cantidad, ";
		$sql .= "c.descripcion, h.razatipo, h.pelaje, pi.promedio, ";
		$sql .= "ev.calidad, ev.estadoeva, ev.sanidad, ev.uniformidad, h.trazados, cv.tipoprecio ";
		$sql .= "FROM hacienda h, hacienda_lote hl, lotes l, establecimiento e, localidad lo, ";
		$sql .= "provincias pr, evaluacion ev, usuarios u, cliente cl, persona p, ";
		$sql .= "condiciones_vta cv, categoria c, pesada_inspeccion pi ";
		$sql .= "WHERE l.idremate = $idremate and hl.idlote = l.idlote and hl.idhacienda = h.idhacienda ";
		$sql .= "and h.idestablecimiento = e.idestablecimiento and ";
		$sql .= "e.idlocalidad = lo.idlocalidad and e.codprov = pr.codprov and ";
		$sql .= "h.idcategoria = c.idcategoria and h.idpi = pi.idpi and h.idcv = cv.idcv and ";
		$sql .= "h.idevaluacion = ev.idevaluacion and h.idvendedor = cl.idcliente and ";
		$sql .= "cl.idpersona = p.idpersona and h.idevaluador = u.idusuario ";
		$sql .= "order by 2, 1";

		$rs = mysql_query($sql);
		$arrh = array();
		if(mysql_num_rows($rs)>0){
			while($fila=mysql_fetch_array($rs)){				
				$arrh[] = $fila;
			}
		}

		include('../reportes/catalogocr.php');

		$url = './reportes/'.$archivo;				
		$arr = array('success'=>true,'url'=>$url);
		echo json_encode($arr);

	}
?>