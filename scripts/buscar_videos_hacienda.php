<?php	
	include('validar.php');
	include('coneccion.php');	
	//if(isset($_SESSION['susuario'])){
		if(isset($_SESSION['sidfirma']))
			$idfirma = $_SESSION['sidfirma'];
		else
			$idfirma = 1;
		$sql = "SELECT distinct hv.idvideo, hv.video, l.idlote ";
		$sql .= "FROM lotes l, remate r, hacienda_lote hl, hacienda_video hv ";
		$sql .= "WHERE r.idremate = l.idremate and r.estado = 1 and r.idfirma = $idfirma and ";
		$sql .= "l.estado < 4 and l.idlote = hl.idlote and hl.idhacienda = hv.idhacienda ";
		$sql .= "ORDER BY orden";
		
		$rs = mysql_query($sql);
		
		$arr[] = array('idvideo'=>0,'video'=>'','idlote'=>0);
		if(mysql_num_rows($rs) > 0){
			while($fila=mysql_fetch_array($rs)){
				$arr[] = array('idvideo'=>$fila[0],
					'video'=>$fila[1],
					'idlote'=>$fila[2]);				
			}
		}

		echo json_encode(array('videos'=>$arr));
	//}
?>