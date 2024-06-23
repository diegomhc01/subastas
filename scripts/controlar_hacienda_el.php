<?php	
include('validar.php');	
include('coneccion.php');	
if(isset($_SESSION['susuario'])){
	$arr = array('success'=>false,'mensaje'=>'');
	$idhacienda = $_POST['param'];
	$_SESSION['sidhacienda']= $idhacienda;
	$idhacienda = $_SESSION['sidhacienda'];
	$sql = "SELECT COUNT(*) FROM hacienda_lote hl, lotes l ";
	$sql .= "WHERE idhacienda = $idhacienda and hl.idlote = l.idlote";
	$rs = mysql_query($sql);
	$filahl = mysql_fetch_row($rs);	
	if($filahl[0]==1){
		$arr = array('success'=>true,'mensaje'=>'EXISTE HACIENDA ASIGNADA A UN LOTE');
	}
	if($filahl[0]>1){
		$arr = array('success'=>true,'mensaje'=>'EXISTE HACIENDA ASIGNADA A UNOS LOTES');
	}
	echo json_encode($arr);	
}
?>