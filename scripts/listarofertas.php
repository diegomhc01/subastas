<?php
	include('validar.php');	
	include('coneccion.php');	

//	header("Cache-Control: no-cache");
//	header("Expires: -1");
	if(isset($_SESSION['susuario'])){
		if(isset($_GET['param'])){			
			$valsel = $_GET['param'];			
		}else{
			$valsel = 0;
		}
		
		$sql = "SELECT o.idoferta AS id, CONCAT(u.apellido,', ',u.nombre) AS cliente, monto AS oferta ";
		$sql .= "FROM ofertas o, usuarios u ";
		$sql .= "WHERE o.estado = 0 and o.idlote = $valsel and o.usuario = u.usuario ";
		$sql .= "ORDER BY idoferta DESC";
		$rs = mysql_query($sql);
		$cantidad = mysql_num_rows($rs);
		$i=0;
		$arr = array();
		$arr2 = array();
		if($rs){
			while($row=mysql_fetch_assoc($rs)){
				$arr['cliente'] = utf8_encode($row['cliente']);
				$arr['oferta'] = $row['oferta'];
				$arr['aceptar'] = '<input type="button" name="rboferta" value="Aceptar" class="clsofertas" id="a'.$row['id'].'">';
				$arr['rechazar'] = '<input type="button" name="rboferta" value="Omitir" class="clsofertas" id="o'.$row['id'].'">';
				$arr2[] = $arr;
			}
		}
		echo json_encode(array('aaData'=>$arr2));
	}
?>