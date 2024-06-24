<?php
	include('validar.php');
	include('coneccion.php');	
	if(isset($_SESSION['susuario'])){
		$idfirma = $_SESSION['sidfirma'];
		$sql = "SELECT u.idusuario, u.usuario, p.descripcion, u.estado, ";
		$sql .= "CONCAT(o.apellido, ', ', o.nombre) AS operador, ";
		$sql .= "CONCAT(u.apellido, ', ', u.nombre) AS apeynom, u.clave, u.perfil ";
		$sql .= "FROM usuarios u, perfil p, usuarios o ";
		$sql .= "WHERE p.perfilid = u.perfil and o.idusuario  = u.operador and u.operador > 0 and u.idfirma = $idfirma and u.estado < 99 ";
		$sql .= "UNION ";
		$sql .= "SELECT u.idusuario, u.usuario, p.descripcion, u.estado, '' AS operador, "; 
		$sql .= "CONCAT(u.apellido, ', ', u.nombre) AS apeynom, u.clave, u.perfil ";
		$sql .= "FROM usuarios u, perfil p ";
		$sql .= "WHERE p.perfilid = u.perfil and u.operador = 0 and u.idfirma = $idfirma and u.estado < 99 ";
		$sql .= "ORDER BY idusuario";

		$rs = mysql_query($sql);

		$arr2 = array();
		
		if(mysql_num_rows($rs) > 0){
			while($row=mysql_fetch_array($rs)){
				$arr = array();

				if($row[7]==1){
					$sql = "SELECT c.estado FROM cliente c WHERE c.idusuario = ".$row[0];
					$rs1 = mysql_query($sql);
					$estadocli = mysql_fetch_row($rs1);
					$estado = $estadocli[0];					
				}else{
					$estado = 9;
				}
				$arr['usuario'] = utf8_encode($row[1]);
				$arr['apeynom'] = utf8_encode($row[5]);				
				$arr['perfil'] = utf8_encode($row[2]);
				$arr['operador'] = utf8_encode($row[4]);
				$arr['clave'] = $row[6];
				$arr['boton1'] = '<input type="image" name="btnusuario" src="images/modificar.png" value="Modificar" class="clsusuarios" id="m'.$row[0].'">';
				$arr['boton2'] = '<input type="image" name="btnusuario" src="images/eliminar.png" value="Eliminar" class="clsusuarios" id="e'.$row[0].'">';

				if($row[3]!=2)
					$arr['boton3'] = '<input type="image" name="btnusuario" src="images/habilitado.png" value="Deshabilitar" class="clsusuarios" id="d'.$row[0].'">';
				if($row[3]==2)
					$arr['boton3'] = '<input type="image" name="btnusuario" src="images/deshabilitado.png" value="Habilitar" class="clsusuarios" id="h'.$row[0].'">';
				if($estado==0)
					$arr['boton4'] = '<input type="image" name="btnusuario" src="images/concredito.png" value="Deshabilitar Credito" class="clsusuarios" id="c'.$row[0].'">';
				if($estado==1)
					$arr['boton4'] = '<input type="image" name="btnusuario" src="images/sincredito.png" value="Habilitar Credito" class="clsusuarios" id="s'.$row[0].'">';
				if($estado==9)
					$arr['boton4'] = '';
				if($row[3]==9)
					$arr['boton5'] = '<input type="image" name="btnusuario" src="images/claver.png" value="Resetear Clave" class="clsusuarios" id="r'.$row[0].'">';
				else
					$arr['boton5'] = '<input type="image" name="btnusuario" src="images/clave.png" value="Resetear Clave" class="clsusuarios" id="r'.$row[0].'">';
				if($row[7]!=1)
					$arr['boton6'] = '';
				if($row[7]==1)
					$arr['boton6'] = '<input type="image" name="btnusuario" src="images/establecimiento.png" value="Establecimiento" class="clsusuarios" id="l'.$row[0].'">';

				$arr2[] = $arr;				
			}
		}
		echo json_encode(array('aaData'=>$arr2));
	}
?>