<?php	
	include('validar.php');
	include('coneccion.php');	
	if(isset($_SESSION['susuario'])){		
		$sql = "SELECT distinct l.idlote, l.idremate, l.estado, l.nrolote ";
		$sql .= "FROM lotes l, remate r, hacienda_lote hl ";
		$sql .= "WHERE r.idremate = l.idremate and r.estado = 1 and ";
		$sql .= "l.estado < 4 and l.idlote = hl.idlote and ";
		$sql .= "r.fecha >= CURDATE() ";
		$sql .= "ORDER BY nrolote";
		$rs = mysql_query($sql);

		$arr = array();
		$arr2 = array();
		if(mysql_num_rows($rs)>0){			
			while($row=mysql_fetch_assoc($rs)){
				$_SESSION['sidremate'] = $row['idremate'];				

				if($row['estado']==3){
					$arr['boton'] = '<input type="button" name="rblotes" id="lote'.$row['idlote'].'R'.$row['idremate'].'" class="clslotes" value="Lote '.$row['nrolote'].'" style="width:82px;margin:0 0 0 -10px;background-color:#990000; color:#CCCCCC;" >';
				}else{
					$arr['boton'] = '<input type="button" name="rblotes" id="lote'.$row['idlote'].'R'.$row['idremate'].'" class="clslotes" value="Lote '.$row['nrolote'].'">';
				}
				$arr2[] = $arr;
			}
		}
		echo json_encode(array('aaData'=>$arr2));
	}
?>