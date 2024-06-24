<?php
	include('validar.php');	
	include('coneccion.php');	
	if(isset($_SESSION['susuario'])){	
		$valsel = 0;
		if(isset($_SESSION['sidlote'])){
			$valsel = $_SESSION['sidlote'];
		}

		$sql = "SELECT cr.idcredito, CONCAT(u.apellido, ', ', u.nombre) AS apeynom, ";
		$sql .= "MAX(o.montototal), (cr.monto - MAX(o.montototal)) as disponible ";
		$sql .= "FROM creditos cr, usuarios u, ofertas o ";
		$sql .= "WHERE u.usuario = cr.usuario and u.usuario = o.usuario and o.idlote = $valsel ";
		$sql .= "GROUP BY cr.idcredito, apeynom ";
		$sql .= "ORDER BY 3 DESC";
		
		$rs = mysql_query($sql);
		$cantidad = mysql_num_rows($rs);		
		$arr2 = array();
		if($cantidad>0){
			while($row=mysql_fetch_array($rs)){
				$arr = array();
				$arr['cliente'] = utf8_encode($row[1]);
				$arr['total'] = $row[2];
				$arr['disponible'] = $row[3];
				$arr['boton1'] = '<input type="image" name="btnclienteofr" src="images/restar.png" value="Restar" class="clsclienteof" id="r'.$row[0].'">';
				$arr['boton2'] = '<input type="image" name="btnclienteofs" src="images/sumar.png" value="Sumar" class="clsclienteof" id="s'.$row[0].'">';
				$arr2[] = $arr;
			}
		}
		
		echo json_encode(array('aaData'=>$arr2));
	}
?>