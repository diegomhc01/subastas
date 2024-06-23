<?php
	include('validar.php');
	include('coneccion.php');	
if($_SESSION['susuario']){		
	$sql = "SELECT DISTINCT idcredito, CONCAT(u.apellido, ', ', u.nombre) AS apeynom, ";
	$sql .= "cr.usuario, monto, C.estado AS estado, cl.estado as uestado, u.idusuario, cr.estado as crestado ";
	$sql .= "FROM creditos cr, usuarios u, cliente cl, uconectadosoa uc, ";
	$sql .= "(SELECT 0 AS idestado, 'CREDITO OTORGADO' AS estado ";
	$sql .= "UNION ";
	$sql .= "SELECT 1 AS idestado, 'CREDITO SOLICITADO' AS estado ";
	$sql .= "UNION ";
	$sql .= "SELECT 2 AS idestado, 'CREDITO BLOQUEADO' AS estado) C ";
	$sql .= "WHERE cr.estado = C.idestado and u.usuario = cr.usuario and ";
	$sql .= "u.idusuario = cl.idusuario and uc.usuario = u.usuario";
	$rs = mysql_query($sql);

	$arr2 = array();
	if(mysql_num_rows($rs)>0){
		while($row=mysql_fetch_assoc($rs)){
			$arr = array();
			$arr['usuario'] = utf8_encode($row['apeynom']);
			$arr['monto'] = $row['monto'];
			$arr['estado'] = $row['estado'];
			if($row['crestado']==0){
				$arr['otorgar'] = '<input type="image" name="rbcredito" src="images/aranceles.png" style="background-color:green;" value="Aceptar" class="clscredito" id="co'.$row['idcredito'].'">';			
				$arr['bloquear'] = '<input type="image" name="rbcredito" src="images/habilitado.png" value="Aceptar" class="clscredito" id="uh'.$row['idcredito'].'">';
			}
			if($row['crestado']==1){
				$arr['otorgar'] = '<input type="image" name="rbcredito" src="images/aranceles.png" style="background-color:red;" value="Aceptar" class="clscredito" id="cs'.$row['idcredito'].'">';
				$arr['bloquear'] = '<input type="image" name="rbcredito" src="images/habilitado.png" value="Aceptar" class="clscredito" id="uh'.$row['idcredito'].'">';
			}			
			if($row['crestado']==2){
				$arr['otorgar'] = '';
				$arr['bloquear'] = '<input type="image" name="rbcredito" src="images/deshabilitado.png" value="Aceptar" class="clscredito" id="ud'.$row['idcredito'].'">';
			}
			$arr2[] = $arr;
		}
	}
	
	echo json_encode(array('aaData'=>$arr2));
}
?>