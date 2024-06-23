<?php
	include('validar.php');		
	include('coneccion.php');		
if(isset($_SESSION['susuario'])){

	$idcredito = $_POST['idcredito'];
	$sql = "SELECT monto, usuario FROM creditos WHERE idcredito = $idcredito";
	$rs = mysql_query($sql);
	if($rs){
		while($row=mysql_fetch_array($rs)){
			$arr = array('monto'=>$row[0],'success'=>true); 
		}
	}else{				
		$arr = array('success'=>false);
	}
	echo json_encode($arr);	
}
?>