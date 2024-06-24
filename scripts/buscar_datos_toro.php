<?php	
	include('validar.php');
	include('coneccion.php');	
	if(isset($_SESSION['susuario'])){
		$idtoro = $_SESSION['sidtoro'];
		$sql = "SELECT idtoro, sangre, razatipo FROM toro WHERE idtoro = $idtoro";
		$rs = mysql_query($sql);
		if(mysql_num_rows($rs)>0){
			while($fila=mysql_fetch_array($rs)){
				$arrto = array('success'=>true,
					'sangre'=>$fila[1],
					'razatipo'=>$fila[2]);				
			}
		}else{
			$arrto = array('success'=>false);
		}
	}else{
		$arrto = array('success'=>false);
	}
	//echo json_encode($arr);
?>