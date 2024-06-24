<?php	
include('validar.php');	
include('coneccion.php');	
if(isset($_SESSION['susuario'])){
	$arr = array('success'=>false);
	if(isset($_SESSION['sidvendedor']))
		unset($_SESSION['sidvendedor']);
	if(isset($_SESSION['sidvientre']))
		unset($_SESSION['sidvientre']);
	if(isset($_SESSION['sidsanidad']))
		unset($_SESSION['sidsanidad']);
	if(isset($_SESSION['sidpi']))
		unset($_SESSION['sidpi']);
	if(isset($_SESSION['sidpd']))
		unset($_SESSION['sidpd']);
	if(isset($_SESSION['sidevaluacion']))
		unset($_SESSION['sidevaluacion']);
	if(isset($_SESSION['sidcv']))
		unset($_SESSION['sidcv']);
	if(isset($_SESSION['sidpersona']))
		unset($_SESSION['sidpersona']);

	if(isset($_SESSION['sidhacienda'])){
		$idhacienda = $_SESSION['sidhacienda'];
	}
	$arr = array('success'=>false,'sql'=>$_SESSION['sidhacienda']);		
	if(isset($idhacienda)){
		$sql = "SELECT idhacienda,  ";
		$sql .= " idvientre, idpi, idpd, idevaluacion, idcv, idevaluador ";
		$sql .= "idsanidad, idvientre ";
		$sql .= "FROM hacienda h WHERE h.idhacienda = $idhacienda";

		$arr = array('success'=>false,'sql'=>$sql);		
		$rs = mysql_query($sql);
		if(mysql_num_rows($rs)>0){
			while($fila=mysql_fetch_array($rs)){
				$idhacienda = $fila[0];			
				$idpi = $fila[1];
				$idpd = $fila[2];
				$idevaluacion = $fila[3];
				$idcv = $fila[4];
				$idsanidad = $fila[5];
				$idvientre = $fila[6];
				
				mysql_query("SET AUTOCOMMIT=0");
				mysql_query("START TRANSACTION");
				
				$sql = "SELECT s.idservicio, s.idtoro FROM vientre v, servicio s, toro t ";
				$sql .= "WHERE v.idvientre = $idvientre and ";
				$sql .= "s.idservicio = v.idservicio and s.idtoro = t.idtoro";
				$arr = array('success'=>false,'sql'=>$sql);
				$rs = mysql_query($sql);
				$idservicio = 0;
				$idtoro = 0;
				if(mysql_num_rows($rs)>0){
					$filavientre = mysql_fetch_row($rs);
					$idservicio = $filavientre[0];
					$idtoro = $filavientre[1];
				}
				if($idtoro>0){
					$sql = "DELETE FROM toro WHERE idtoro = $idtoro";					
					$rstoro = mysql_query($sql);
				}else{
					$rstoro = true;
				}
				if($idservicio>0){				
					$sql = "DELETE FROM servicio WHERE idservicio = $idservicio";
					$rsservicio = mysql_query($sql);
				}else{
					$rsservicio = true;
				}
				$sql = "DELETE FROM vientre WHERE idvientre = $idvientre";
				$rsvientre = mysql_query($sql);
				$sql = "DELETE FROM pesada_definitiva WHERE idpd = $idpd";
				$rspd = mysql_query($sql);
				$sql = "DELETE FROM sanidad WHERE idsanidad = $idsanidad";
				$rssanidad = mysql_query($sql);
				$sql = "DELETE FROM evaluacion WHERE idevaluacion = $idevaluacion";
				$rsevaluacion = mysql_query($sql);
				$sql = "DELETE FROM pesada_inspeccion WHERE idpi = $idpi";
				$rspi = mysql_query($sql);
				$sql = "DELETE FROM condiciones_vta WHERE idcv = $idcv";
				$rscondvta = mysql_query($sql);
				$sql = "DELETE FROM hacienda WHERE idhacienda = $idhacienda AND estado = 0";
				$rsh = mysql_query($sql);

				if($rstoro && $rsservicio && $rsvientre && $rspd && $rssanidad && 
					$rsevaluacion && $rspi && $rscondvta && $rsh){
					mysql_query("COMMIT");
					$arr = array('success'=>true);
				}else{
					mysql_query("ROLLBACK");
				}
				
			}
		}
	}
	echo json_encode($arr);	
}
?>