<?php	
	include('validar.php');	
	include('coneccion.php');	
	if(isset($_SESSION['susuario'])){
		$accion = 'a';
		$arr = array('success'=>false);
		if(isset($_SESSION['saccion']))
			$accion = $_SESSION['saccion'];
		
		$idremate = $_SESSION['sidremate'];

		if($accion=='a'){
			for($i=0;$i<count($concepto);$i++){
				$sql = "INSERT INTO remate_concepto (idremate, idconcepto) VALUES ($idremate, ".$concepto[$i].")";
				$rs = mysql_query($sql);
				$arr = array('success'=>true);
			}
		}
		if($accion=='m'){
			$sql = "DELETE FROM remate_concepto WHERE idremate = $idremate";
			$rs1 = mysql_query($sql);
			if($rs1){
				for($i=0;$i<count($concepto);$i++){
					$sql = "INSERT INTO remate_concepto (idremate, idconcepto) VALUES ($idremate, ".$concepto[$i].")";
					$rs = mysql_query($sql);
					$arr = array('success'=>true);
				}
			}
		}
		if($accion=='e'){
			$sql = "DELETE FROM remate_concepto WHERE idremate = $idremate";
			$rs1 = mysql_query($sql);
			if($rs1){
				$arr = array('success'=>true);
			}
		}
		unset($_SESSION['saccion']);
	}

?>