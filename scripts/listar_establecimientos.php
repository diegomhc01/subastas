<?php
	include('validar.php');
	include('coneccion.php');
	if($_SESSION['susuario']){		
		$idcliente = $_SESSION['sidcliente'];

		mysql_set_charset('utf8');
		$sql = "SELECT e.idestablecimiento, ce.renspa, e.detalle as establecimiento, p.nombre as provincia, ";
		$sql .= "l.nombre as localidad, e.lat as latitud, e.lon as longitud ";
		$sql .= "FROM cliente_establecimiento ce, establecimiento e, cliente c, localidad l, provincias p ";
		$sql .= "WHERE c.idcliente = $idcliente and ce.idcliente = c.idcliente and ";
		$sql .= "e.idestablecimiento = ce.idestablecimiento and e.idlocalidad = l.idlocalidad and ";	
		$sql .= "e.codprov = p.codprov and e.estado = 0";
		$rs = mysql_query($sql);
		$cantidad = mysql_num_rows($rs);
		$i=0;
		$arr2 = array();
		while($fila = mysql_fetch_assoc($rs)){
			$arr = array();
			$arr['renspa'] = $fila['renspa'];
			$arr['establecimiento'] = $fila['establecimiento'];
			$arr['provincia'] = $fila['provincia'];
			$arr['localidad'] = $fila['localidad'];
			$arr['latitud'] = $fila['latitud'];
			$arr['longitud'] = $fila['longitud'];
			$arr['boton1'] = '<button type="button" name="btnce" value="Modificar" class="clsce" id="m'.$fila['idestablecimiento'].'" style="border:none;background:transparent;"><span><img src="images/modificar.png"></span></button>';
			$arr['boton2'] = '<button type="button" name="btnce" value="Eliminar" class="clsce" id="e'.$fila['idestablecimiento'].'" style="border:none;background:transparent;"><span><img src="images/eliminar.png"></span></button>';
			$arr2[] = $arr;
		}
		echo json_encode(array('aaData'=>$arr2));
	}
?>