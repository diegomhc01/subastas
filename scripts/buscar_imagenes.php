<?php	
	include('validar.php');
	include('coneccion.php');	
	if(isset($_SESSION['susuario'])){		
		unset($_SESSION['sidhacienda']);
		$_SESSION['sidhacienda'] = $_POST['param'];
		$idhacienda = $_SESSION['sidhacienda'];
		$sql = "SELECT h.idhi, h.imagen FROM hacienda_img h WHERE h.idhacienda = $idhacienda";
		$rs = mysql_query($sql);		
		if(mysql_num_rows($rs)>0){
			while($fila=mysql_fetch_array($rs)){
				//$info = getimagesize('http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].'/intertv/remates/fotos/'.$fila[1]);
				$arrpi[] = array(
					'idhi'=>$fila[0],
					'imagen'=>$fila[1]);
			}
			$sql = "SELECT idvideo, video, idhacienda, estado FROM hacienda_video WHERE idhacienda = $idhacienda";
			$rs0 = mysql_query($sql);
			if(mysql_num_rows($rs0)>0){
				while($video=mysql_fetch_assoc($rs0)){
					$arrpi[] = $video;
				}
			}
			$arrpi[] = array('success'=>true);
		}else{
			$arrpi = array('success'=>false);
		}		
	}
	echo json_encode($arrpi);
?>