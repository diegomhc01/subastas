<?php	
include('validar.php');
include('coneccion.php');	
if(isset($_SESSION['susuario'])){
	$idhacienda = 0;
	$idfirma = $_SESSION['sidfirma'];
	$idremate = $_SESSION['sidremate'];
	$arrl = array('success'=>false);
	$arrhl = array();

	if(isset($_POST['param']))
		$_SESSION['sidlote'] = $_POST['param'];
	if(isset($_POST['param1']))
		$_SESSION['saccion'] = $_POST['param1'];

	$idlote = $_SESSION['sidlote'];

	$sql = "SELECT idhacienda ";
	$sql .= "FROM hacienda_lote ";
	$sql .= "WHERE idlote = $idlote";
	$rshl = mysql_query($sql);		
	if(mysql_num_rows($rshl)>0){
		while($filahl=mysql_fetch_array($rshl)){
			$idhacienda = $filahl[0];
			$sql = "SELECT h.idhacienda, p.apeynom, c.descripcion as categoria, ";
			$sql .= "cv.precioinicial, hl.cantidad, h.resto ";
			$sql .= "FROM hacienda h, categoria c, cliente cl, persona p, condiciones_vta cv, hacienda_lote hl ";
			$sql .= "WHERE c.idcategoria = h.idcategoria and cl.idcliente = h.idvendedor and ";
			$sql .= "cl.idpersona = p.idpersona and cv.idcv = h.idcv and ";
			$sql .= "h.idfirma = $idfirma and h.idhacienda = $idhacienda and hl.idhacienda = h.idhacienda and ";
			$sql .= "hl.idlote = $idlote";
			
			$rsh = mysql_query($sql);
			if(mysql_num_rows($rsh)>0){
				$filah = mysql_fetch_row($rsh);
				$arrhl[] = array('id'=>$filah[0],
					'ven'=>utf8_encode($filah[1]),
					'cat'=>utf8_encode($filah[2]),
					'precioinicio'=>$filah[3],
					'can'=>$filah[4],
					'canl'=>$filah[4]);
					//'canh'=>$filah[5]+$filah[4]);
			}
		}
	}

	$sql = "SELECT idlote, cantcabezas, estado, nrolote, tipoentrega, idtp, precioinicio ";
	$sql .= "FROM lotes  ";
	$sql .= "WHERE idremate = $idremate and idlote = $idlote";	
	$rs = mysql_query($sql);
	if(mysql_num_rows($rs)>0){
		while($fila=mysql_fetch_array($rs)){
			$arrl = array('success'=>true,
				'cantcabezas'=>$fila[1],
				'estado'=>$fila[2],
				'nro'=>$fila[3],
				'tipoentrega'=>$fila[4],
				'tipoprecio'=>$fila[5],
				'precioinicio'=>$fila[6]);
		}
	}

	$arr = array('arrl'=>$arrl,'arrhl'=>$arrhl);
	echo json_encode($arr);
}
?>