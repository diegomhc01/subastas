<?php	
	include('validar.php');
	include('coneccion.php');	
	if(isset($_SESSION['susuario'])){
		if($_POST['param2']==0)
			$idcliente = $_POST['param1'];
		if($_POST['param2']==1)
			$idestablecimiento = $_POST['param1'];


		if($_POST['param2']==0){
			$sql = "SELECT l.nombre as localidad, p.nombre as provincia, e.lat, e.lon, ce.renspa, e.idestablecimiento ";
			$sql .= "FROM establecimiento e, cliente_establecimiento ce, localidad l, provincias p ";
			$sql .= "WHERE ce.idcliente = $idcliente and e.idestablecimiento = ce.idestablecimiento and ce.idestablecimiento = e.idestablecimiento and e.idlocalidad = l.idlocalidad and e.codprov = p.codprov";
		}
		if($_POST['param2']==1){
			$sql = "SELECT l.nombre as localidad, p.nombre as provincia, e.lat, e.lon, ce.renspa, e.idestablecimiento ";
			$sql .= "FROM establecimiento e, cliente_establecimiento ce, localidad l, provincias p ";
			$sql .= "WHERE e.idestablecimiento = $idestablecimiento and ce.idestablecimiento = e.idestablecimiento and e.idlocalidad = l.idlocalidad and e.codprov = p.codprov";
		}		
		$rs = mysql_query($sql);
		if(mysql_num_rows($rs)>0){
			while($fila=mysql_fetch_array($rs)){
				$arrestablecimiento = array('success'=>true,
					'localidad'=>$fila[0],
					'provincia'=>$fila[1],
					'lat'=>$fila[2],
					'lon'=>$fila[3],
					'renspa'=>$fila[4]);
					$_SESSION['sidestablecimiento'] =$fila[5];
			}
		}else{
			$arrestablecimiento = array('success'=>false);
		}
	}else{
		$arrestablecimiento = array('success'=>false);
	}
	echo json_encode($arrestablecimiento);
?>