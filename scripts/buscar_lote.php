<?php	
	include('validar.php');
	include('coneccion.php');	
	if(isset($_SESSION['susuario'])){		
		$idremate = $_SESSION['sidremate'];
		
		if(isset($_POST['param1']))
			$_SESSION['saccion'] = $_POST['param1'];
		else
			$accion = 'a';
		
		if(isset($_POST['param']))
			$_SESSION['sidlote'] = $_POST['param'];
		
		if(isset($_SESSION['sidlote']))	
			$idlote = $_SESSION['sidlote'];
		
		if(isset($_POST['paramh']))
			$_SESSION['sidhacienda'] = $_POST['paramh'];

		if($accion!='a'){
			$sql = "SELECT l.idlote, l.cantcabezas, l.estado, l.incremento, l.orden, l.nrolote, l.tipoentrega, ";
			$sql .= "case l.tipoentrega when 1 then 'A termino' when 0 then 'Inmediata' end as stipoentrega, precioinicio, l.inc1, l.inc2, l.inc3, l.idtp ";
			$sql .= "FROM lotes l WHERE l.idremate = $idremate and l.idlote = $idlote";			
			$rs = mysql_query($sql);
			if(mysql_num_rows($rs)>0){
				while($fila=mysql_fetch_array($rs)){
					$arr = array('success'=>true,
						'idlote'=>$fila[0],
						'cantcabezas'=>$fila[1],
						'estado'=>$fila[2],
						'incremento'=>$fila[3],
						'orden'=>$fila[4],
						'nro'=>$fila[5],
						'tipoentrega'=>$fila[6],
						'stipoentrega'=>$fila[7],
						'precioinicio'=>$fila[8],
						'inc1'=>$fila[9],
						'inc2'=>$fila[10],
						'inc3'=>$fila[11],
						'idtp'=>$fila[12]);
					$_SESSION['sidlote'] = $fila[0];
				}
			}else{				
				$arr = array('success'=>false,'sql'=>$sql,'idlote'=>$_SESSION['sidlote']);
			}
		}
		if($accion=='a'){
			$sql = "SELECT MAX(nrolote) + 1 FROM lotes WHERE idremate = $idremate";
			$rs = mysql_query($sql);
			if(mysql_num_rows($rs)>0){
				while($fila=mysql_fetch_array($rs)){
					$arr = array('success'=>true,'idlote'=>$fila[0]);
					$_SESSION['snrolote'] = $fila[0];
				}
			}else{
				$arr = array('success'=>false,'sql'=>$sql,'idlote'=>-1);
			}
		}
	}else{
		$arr = array('success'=>false,'idlote'=>$_SESSION['sidlote']);
	}
	echo json_encode($arr);
?>