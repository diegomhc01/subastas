<?php	
	include('validar.php');
	include('coneccion.php');	
	if(isset($_SESSION['susuario'])){
		if(!isset($_SESSION['sidvendedor']))
			if(isset($_POST['param']))
				$_SESSION['sidvendedor'] = $_POST['param'];
		$idcliente = $_SESSION['sidvendedor'];
		$sql = "SELECT c.idcliente, c.contacto, c.telefono, c.email, c.cuit, c.idpersona ";
		$sql .= "FROM cliente c ";
		$sql .= "WHERE c.idcliente = $idcliente";
		
		$rs = mysql_query($sql);
		if(mysql_num_rows($rs)>0){
			while($fila=mysql_fetch_array($rs)){							
				$arrve = array('success'=>true,
					'idcliente'=>$fila[0],
					'contacto'=>$fila[1],
					'telefono'=>$fila[2],
					'email'=>$fila[3],
					'cuit'=>$fila[4]);
				$_SESSION['sidpersona'] = $fila[5];
			}
		}else{
			$arrve = array('success'=>false);			
		}
	}else{
		$arrve = array('success'=>false);
	}	
?>