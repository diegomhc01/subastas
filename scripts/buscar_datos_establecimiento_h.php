<?php
	include('validar.php');
	include('coneccion.php');	

	if(isset($_SESSION['susuario'])){
		$idestablecimiento = $_SESSION['sidestablecimiento'];
		$sql = "SELECT ce.renspa, l.nombre as localidad, p.nombre as provincia ";
		$sql .= "FROM cliente_establecimiento ce, establecimiento e, localidad l, provincias p ";
		$sql .= "WHERE ce.idestablecimiento = $idestablecimiento and ";
		$sql .= "e.idestablecimiento = ce.idestablecimiento and ";
		$sql .=	"e.idlocalidad = l.idlocalidad and ";
		$sql .=	"e.codprov = p.codprov";

		$rs = mysql_query($sql);
		if(mysql_num_rows($rs)>0){
			$festablecimiento = mysql_fetch_row($rs);
			$arrce = array('success'=>true,
				'renspa'=>$festablecimiento[0],
				'localidad'=>$festablecimiento[1],
				'provincia'=>$festablecimiento[2]
				);
		}
	}

?>