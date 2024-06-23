<?php
	include('validar.php');	
	include('coneccion.php');	
	if(isset($_SESSION['susuario'])){
		if(!isset($_SESSION['sidlote'])){
			$_SESSION['sidlote'] = $_POST['param'];
		}

		$idremate = $_SESSION['sidremate'];

		$idlote = $_SESSION['sidlote'];	
		$tipo = $_POST['param1'];
		$orderby = '';
		if($tipo == 's'){
			$orderby = 'DESC';
		}
		$sql = "SELECT nrolote, idlote FROM lotes ";
		$sql .= "WHERE idremate = $idremate ORDER BY nrolote ".$orderby;
		$rs0 = mysql_query($sql);
		$i = 0;
		$encontrado = false;
		$cantidad = mysql_num_rows($rs0);
		if(mysql_num_rows($rs0)>0){
			while($fnrolote = mysql_fetch_array($rs0)){
				if($encontrado===FALSE){						
					$nrolote = $fnrolote[0];						
					if($idlote==$fnrolote[1]){
						$encontrado = true;
					}
				}else{
					$nrolotesiguiente = $fnrolote[0];
					$idlotesiguiente = $fnrolote[1];
					break;
				}
			}			
		}
		mysql_query("SET AUTOCOMMIT=0");
		mysql_query("START TRANSACTION");			
		
		$sql = "UPDATE lotes SET nrolote = $nrolotesiguiente WHERE idlote = $idlote";
		$rs = mysql_query($sql);
		$sql1 = "UPDATE lotes SET nrolote = $nrolote WHERE idlote = $idlotesiguiente";
		$rs1 = mysql_query($sql1);
		
		if($rs && $rs1){
			mysql_query("COMMIT");
			unset($_SESSION['sidlote']);
			echo json_encode(array('success'=>true));
		}else{
			mysql_query("ROLLBACK");
			echo json_encode(array('success'=>false));
		}		
	}	
?>
