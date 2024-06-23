<?php
	include('validar.php');
	include('coneccion.php');
	if(isset($_POST['btningresar'])){
		$mensaje='';
		$_SESSION['smensaje'] = '';
		if(isset($_POST['txtusuario']) && isset($_POST['txtclave']) && 	$_POST['txtusuario']!='' && $_POST['txtclave']!=''){
			$estado = 0;
			$vusuario = $_POST['txtusuario'];
			$clave = $_POST['txtclave'];
			$operador = 0;
			$sql = "SELECT u.usuario, u.perfil, u.operador, u.idusuario, u.apellido, u.nombre, p.descripcion, u.estado, u.idfirma ";
			$sql .= "FROM usuarios u, perfil p WHERE usuario = '$vusuario' AND clave = '$clave' and u.perfil = p.perfilid and u.estado <> 2"; 
			$rs0 = mysql_query($sql) or die(mysql_error());			
			if(mysql_num_rows($rs0)>0){
				while($row=mysql_fetch_array($rs0)){					
					$_SESSION['operador'] = 0;
					$_SESSION['operadorn'] = '';							
					$_SESSION['susuario'] 	= $row[0];
					$_SESSION['sperfil'] 	= $row[1];
					$_SESSION['apellido'] 	= $row[4];
					$_SESSION['nombre'] 	= $row[5];
					$_SESSION['descperfil'] = $row[6];
					$_SESSION['sestado'] = $row[7];
					$_SESSION['sidfirma'] = $row[8];
					if($estado!=9){
						if($row[1]==3 || $row[1]==4){
							$_SESSION['operadorn'] = $row[3];
							$voperador = $row[3];
						}else{
							$voperador = $row[2];
							$_SESSION['operadorn'] = $row[2];
						}
						$sql = "SELECT usuario FROM usuarios WHERE idusuario = $voperador";
						$rs3 = mysql_query($sql);
						if(mysql_num_rows($rs3)>0){

							$fila = mysql_fetch_row($rs3);
							$_SESSION['operador'] = $fila[0];														
							$rs4 = mysql_query($sql);
							if($_SESSION['sperfil']==1){
								$sql = "SELECT idcliente, estado FROM cliente WHERE idusuario = ".$row[3];
								$rs7 = mysql_query($sql);
								if(mysql_num_rows($rs7)>0){
									$fcliente = mysql_fetch_row($rs7);
									$_SESSION['estado_cliente'] = $fcliente[1];
									$_SESSION['sidcliente'] = $fcliente[0];
								}
							}
						}
						$sqlra = "SELECT idremate, tipo FROM remate ";
						$sqlra .= "WHERE estado = 1 and publicado = 1 and ";
						$sqlra .= "idfirma = 1 and fecha >= CURDATE()";

						$rsra = mysql_query($sqlra);
						if(mysql_num_rows($rsra) > 0){
							$fremate = mysql_fetch_row($rsra);
							$_SESSION['sidremate'] = $fremate[0];
							$_SESSION['stiporemate'] = $fremate[1];							
						}else{
							$_SESSION['sidremate']=0;
						}
						$mensaje = '';
						unset($_SESSION['sopcion']);
						
						//echo 'hola';						
					}
				}
					//foreach ($_SESSION as $key => $value) {						
	    				//$file = fopen($_SERVER['DOCUMENT_ROOT']."/intertv/remates/scripts/ses.txt", "a");
	    				//$info = $key.'='.$value."\r\n";
	    				//$info = json_encode($_SESSION);
	    				//fwrite($file, $info);
	    				//fclose($file);
					//}
				//BUSCAR EL OPERADOR CORRESPONDIENDO AL CLIENTE				
			}else{
				$mensaje = "Usuario o clave incorrectos";
				$_SESSION['smensaje'] = "Usuario o clave incorrectos";
			}
		}else{
			$mensaje = "Usuario o clave incorrectos";
			$_SESSION['smensaje'] = "Usuario o clave incorrectos";
		}
	}
	if(isset($_POST['btncambiar'])){
		if($_POST['btncambiar']=='Modificar Clave'){
			if(isset($_POST['txtclaveactual']) && isset($_POST['txtclavenueva1']) && isset($_POST['txtclavenueva2'])){
				if($_POST['txtclaveactual']!='' && $_POST['txtclavenueva1']!='' && $_POST['txtclavenueva2']!=''){
					$claveactual = $_POST['txtclaveactual'];
					$clavenueva1 = $_POST['txtclavenueva1'];
					$clavenueva2 = $_POST['txtclavenueva2'];
					if($clavenueva1 == $clavenueva2){
						if($claveactual != $clavenueva1){
							$sql = "SELECT clave FROM usuarios WHERE usuario = '".$_SESSION['usuario']."' and clave = '$claveactual'";
							$rs5 = mysql_query($sql);
							if(mysql_num_rows($rs5)>0){
								$sql = "UPDATE usuarios SET clave = '$clavenueva1' WHERE usuario = '".$_SESSION['usuario']."'";
								$rs6 = mysql_query($sql);								
								if($rs6){
									$mensaje = "LA CLAVE HA SIDO MODIFICADA";
								}else{
									$mensaje = "ERROR AL MODIFICAR LA CLAVE";
									$_SESSION['sestado']=0;
								}
							}else{
								$mensaje = "LA CLAVE ACTUAL NO ES VALIDA";
							}
						}else{
							$mensaje = "LA CLAVE NUEVA PUEDE SER IGUAL A LA CLAVE ACTUAL";
						}
					}else{
						$mensaje = "LA CLAVE NUEVA NO ES COINCIDENTE";
					}
				}else{
					$mensaje = "NO PUEDE HABER CAMPOS VACIOS";
				}
			}else{
				$mensaje = "ERROR";
			}
		}else{
			$mensaje = "ERROR";
		}
	}
	if(isset($mensaje) && $mensaje==''){
		header('Location: http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].'/intertv/remates/');
		//header('Location: http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].'/brandemann/');
	}
?>