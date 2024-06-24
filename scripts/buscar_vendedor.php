<?php		
	include('validar.php');	
	include('coneccion.php');	
	if(isset($_SESSION['susuario'])){			
		$idcliente = $_POST['param'];
		
		$sql = "SELECT p.idpersona, c.idcliente, c.contacto, c.telefono, c.email, c.cuit ";
		$sql .= "FROM persona p, cliente c ";
		$sql .= "WHERE p.idpersona = c.idpersona and c.idcliente = $idcliente";
		$rs = mysql_query($sql);
		$arr = array();
		if($rs){
			while($row=mysql_fetch_assoc($rs)){
				$arr = $row;
				$_SESSION['sidpersona'] = $row['idpersona'];
				$_SESSION['sidcliente'] = $row['idcliente'];
			}
		}else{				
			$arr = array('success'=>false,'sql'=>$sql);
		}
		echo json_encode($arr);
	}
?>