<?php	
	session_start();
	include('coneccion.php');	
	if(isset($_SESSION['susuario'])){		
		$sql = "SELECT idlote, cantcabezas FROM lotes WHERE idremate =".$_GET['idremate'];
		$rs = mysql_query($sql,$coneccion);		
		$cantidad = mysql_num_rows($rs);
		$i= 0;
		$html = '';
		if($rs){			
			while($row=mysql_fetch_array($rs)){
				$i = $i + 1;
				$html .= '["Lote N° '.$row['idlote']. ' - Cantidad de cabezas '.$row['cantcabezas'].'",';
				if($cantidad==$i){
					$html .= '"<input type=\"button\" name=\"rblotes\" id=\"lote'.$row['idlote'].'\" class=\"clslotescat\" value=\"Detalle\">"]';
				}else{
					$html .= '"<input type=\"button\" name=\"rblotes\" id=\"lote'.$row['idlote'].'\" class=\"clslotescat\" value=\"Detalle\">"],';
				}

			}
		}else{				
			$html = array('success'=>false);
		}
		$html = '{"aaData":['.$html.']}';
		echo $html;
		//echo json_encode($arr);
	}
?>