<?php
	include('scripts/validar.php');
	include('scripts/coneccion.php');
	if($_SESSION['susuario']){
		$idremate = $_SESSION['sidremate'];
		echo $idremate;
		$sql = "SELECT idlote, nrolote FROM lotes WHERE estado = 0 and idremate = $idremate ORDER BY 2";
		
		$rs = mysql_query($sql);
		
		echo '<option value="" disabled selected>Seleccionar Lote</option>';
		if($rs){
			while($fila = mysql_fetch_array($rs)){
				echo '<option value="'.$fila[0].'">'.$fila[1].'</option>';
			}
		}
	}
?>

	
	