<?php	
	include('validar.php');	
	include('coneccion.php');	
	if(isset($_SESSION['susuario'])){
		$sql = "SELECT monto FROM creditos WHERE usuario = '".$_SESSION['susuario']."'";
		$rs = mysql_query($sql);
		if(mysql_num_rows($rs)>0){
			$monto = mysql_fetch_row($rs);
			$arr = array('success'=>true,'monto'=>$monto[0]);
		}else{	
			$arr = array('success'=>false,'monto'=>-1);
		}
		echo json_encode($arr);
	}
?>