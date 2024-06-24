<?php		
	include('validar.php');	
	include('coneccion.php');	
	if(isset($_SESSION['susuario'])){
		$idremate = filter_input(INPUT_POST, 'param', FILTER_SANITIZE_NUMBER_INT);

		$_SESSION['sidremate'] = $idremate;
		$_SESSION['saccion'] = filter_input(INPUT_POST, 'param1', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

		
		$sql = "SELECT idremate, DATE_FORMAT(fecha, '%d-%m-%Y'), hora, estado, cabezas, ";
		$sql .= "titulo, tipo, registrados, numero, comentarios ";
		$sql .= "FROM remate WHERE idremate = $idremate";
		
		$rs = mysql_query($sql);
		if(mysql_num_rows($rs)>0){
			$conceptos = array();
			$sql = "SELECT idconcepto FROM remate_concepto WHERE idremate = $idremate";
			$rs1 = mysql_query($sql);
			while($row1=mysql_fetch_assoc($rs1)){
				$conceptos[] = $row1;
			}
			while($row=mysql_fetch_array($rs)){
				$arr = array('idremate'=>$row[0],
					'fecha'=>$row[1],
					'hora'=>$row[2],
					'estado'=>$row[3],
					'cabezas'=>$row[4],
					'titulo'=>utf8_encode($row[5]),
					'tipo'=>$row[6],
					'numero'=>utf8_encode($row[8]),
					'comentarios'=>utf8_encode($row[9]),
					'conceptos'=>$conceptos,
					'success'=>true, 'sql'=>mysql_error());

			}
		}else{				
			$arr = array('success'=>false,'sql'=>$sql);
		}
		echo json_encode($arr);
	}
?>