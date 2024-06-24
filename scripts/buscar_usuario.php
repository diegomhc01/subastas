<?php
	include('validar.php');
	include('coneccion.php');	
	if(isset($_SESSION['susuario'])){
		if(isset($_POST['param'])){
			$idusuario = $_POST['param'];			
			$_SESSION['grabarusuario'] = $_POST['param1'];
			$_SESSION['idusuariog'] = $_POST['param'];
			$sql = "SELECT idusuario, usuario, perfil, estado, operador, apellido, nombre FROM usuarios WHERE idusuario = $idusuario";
			$rs = mysql_query($sql,$coneccion);
			$cliente = array();
			$arr = array();
			$establecimiento = array();
			$arrusuario = array();
			if($rs){
				while($fila=mysql_fetch_assoc($rs)){
					$arrusuario = $fila;
					$_SESSION['sidusuariog'] = $fila['idusuario'];
					if($fila['perfil']==1){
						$sql = "SELECT c.idcliente, c.contacto, c.telefono, c.email, c.cuit, c.estado, c.credito, c.idpersona ";
						$sql .= "FROM cliente c ";
						$sql .= "WHERE c.idusuario = ".$fila['idusuario'];
						$rs1 = mysql_query($sql);
						if(mysql_num_rows($rs1)>0){
							while($fila1=mysql_fetch_assoc($rs1)){								
								$cliente[] = $fila1 ;
								$idcliente = $fila1['idcliente'];
								$_SESSION['sidcliente'] = $idcliente;
								$_SESSION['sidpersona'] = $fila1['idpersona'];
							}
							$sql = "SELECT e.idestablecimiento, e.detalle, e.idlocalidad, e.codprov, e.lat, e.lon, ce.renspa ";
							$sql .= "FROM establecimiento e, cliente_establecimiento ce ";
							$sql .= "WHERE ce.idcliente = $idcliente and ce.idestablecimiento = e.idestablecimiento and e.estado = 0";
							$rs2 = mysql_query($sql);							
							if(mysql_num_rows($rs2)>0){
								while($fila2=mysql_fetch_assoc($rs2)){									
									$establecimiento[] = $fila2;
									$_SESSION['sidestablecimiento'] = $fila2['idestablecimiento'];
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