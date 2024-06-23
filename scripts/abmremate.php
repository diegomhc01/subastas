<?php	
	include('validar.php');	
	include('coneccion.php');	
	if(isset($_SESSION['susuario'])){
		$arr = array('success'=>false);
		$accion = 'a';
		$idfirma = $_SESSION['sidfirma'];

		if(isset($_SESSION['saccion']))
			$accion = $_SESSION['saccion'];
		
		if($accion=='m' || $accion=='e')
			$idremate = $_SESSION['sidremate'];
		if(isset($_POST['fecha']))
			$fecha = substr($_POST['fecha'],6,4).'-'.substr($_POST['fecha'], 3,2).'-'.substr($_POST['fecha'], 0,2);
		$hora = isset($_POST['hora']) ? $_POST['hora'] : '00:00';
		//$cabezas = isset($_POST['txtcabezas']) ? $_POST['txtcabezas'] : 0;
		//$estado  = $_POST['txtcabezas'];
		$concepto  = isset($_POST['chkconcepto']) ? $_POST['chkconcepto'] : array();
		$titulo  = isset($_POST['titulo']) ? $_POST['titulo'] : 'Remate';
		$titulo = strtoupper(utf8_encode($titulo));
		$tipo  = isset($_POST['tiporemate']) ? $_POST['tiporemate'] : 0;
		$numero  = isset($_POST['nro']) ? $_POST['nro'] : 0;
		$registrados  = isset($_POST['registrados']) ? $_POST['registrados'] : 0;
		$comentarios  = isset($_POST['comentarios']) ? $_POST['comentarios'] : '';
		$comentarios = strtoupper(utf8_encode($comentarios));
		if($accion=='a'){
			$sql = "INSERT INTO remate (fecha, hora, estado, cabezas, titulo, tipo, registrados, numero, comentarios, idfirma ) VALUES 
			(DATE_FORMAT('$fecha', '%Y-%m-%d'), '$hora', 1, 0, '$titulo', $tipo, $registrados, '$numero', '$comentarios', $idfirma)";		
		}
		if($accion=='m'){
			$sql = "UPDATE remate SET "; 
			$sql .= "fecha = DATE_FORMAT('$fecha', '%Y-%m-%d'), ";
			$sql .= "hora = '$hora', "; 
			$sql .= "titulo = '$titulo', "; 
			$sql .= "tipo = $tipo, "; 
			$sql .= "registrados = $registrados, "; 
			$sql .= "numero = '$numero', "; 
			$sql .= "comentarios = '$comentarios' WHERE idremate = $idremate and (estado = 0 or estado = 1)";
		}
		if($accion=='e'){
			$sql = "DELETE FROM remate WHERE idremate = $idremate and estado = 1";
		}

		$rs = mysql_query($sql);
		if($rs){
			$arr = array('success'=>true);
			if($accion=='a'){
				$_SESSION['sidremate'] = mysql_insert_id();			
			}
			include('abmremate_concepto.php');
		}else{
			$arr = array('success'=>false,'sql'=>$sql);
		}

		unset($_SESSION['saccion']);
		unset($_SESSION['sidremate']);
		echo json_encode($arr);
	}

?>