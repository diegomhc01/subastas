<?php
	include('validar.php');
	include('coneccion.php');	
	if(isset($_SESSION['susuario'])){
		if(isset($_POST['param'])){
			$cliente = array();
			$arr = array();
			$establecimiento = array();
			$arrusuario = array();
			$usuario = $_POST['param'];			
			$_SESSION['grabarusuario'] = $_POST['param1'];
			//
			$sql = "SELECT idusuario, usuario, perfil, estado, operador, apellido, nombre FROM usuarios WHERE usuario = $usuario";
			$rs = mysql_query($sql,$coneccion);
			if(mysql_num_rows($rs)>0){
				while($fila=mysql_fetch_assoc($rs)){
					$arrusuario[] = $fila;
					$_SESSION['sidusuariog'] = $fila['idusuario'];
					$_SESSION['susuariog'] = $fila['usuario'];
					if($fila[2]==1){
						$sql = "SELECT c.idcliente, c.contacto, c.telefono, c.email, c.cuit, c.estado, c.credito ";
						$sql .= "FROM cliente c ";
						$sql .= "WHERE c.idusuario = ".$fila[0];
						$rs1 = mysql_query($sql);
						if(mysql_num_rows($rs1)>0){
							while($fila1=mysql_fetch_assoc($rs1)){								
								$cliente[] = $fila1;
								$idcliente = $fila1['idcliente'];
								$_SESSION['sidcliente'] = $idcliente;
							}
							$sql = "SELECT e.idestablecimiento, e.detalle, e.idlocalidad, e.codprov, e.lat, e.lon, ce.renspa ";
							$sql .= "FROM establecimiento e, cliente_establecimiento ce ";
							$sql .= "WHERE ce.idcliente = $idcliente and ce.idestablecimiento = e.idestablecimiento and e.estado = 0";
							$rs2 = mysql_query($sql);							
							if(mysql_num_rows($rs2)>0){
								while($fila2=mysql_fetch_assoc($rs2)){
									$_SESSION['sidestablecimiento'] = $fila['idestablecimiento'];
									$establecimiento[] = $fila2;
								}
							}
						}
					}
					$arr = array('success'=>true,
						'usuario'=>$arrusuario,
						'grabarusuario'=>$_SESSION['grabarusuario'],
						'cliente'=>$cliente,
						'establecimiento'=>$establecimiento);
				}
			}else{				
				$arr = array('success'=>false,
						'usuario'=>$arrusuario,
						'grabarusuario'=>-1,
						'cliente'=>$cliente,
						'establecimiento'=>$establecimiento);
			}
			echo json_encode($arr);
		}
	}
?>
