<?php
include('validar.php');
include('coneccion.php');
if(isset($_SESSION['susuario'])){
	$arr = array('success'=>false);
	
	if(isset($_SESSION['sidlote'])){
		$idlote = $_SESSION['sidlote'];
		
		$sql = "SELECT estado, nrolote FROM lotes WHERE idlote = $idlote";
		$rsblote = mysql_query($sql);

		if(mysql_num_rows($rsblote)>0){
			$fblote = mysql_fetch_row($rsblote);
			$estadolote = $fblote[0];
			$nrolote = $fblote[1];
			if($estadolote==0) $strestadolote = 'PUBLICADO';
			if($estadolote==1) $strestadolote = 'ABIERTO';
			if($estadolote==2) $strestadolote = '2';
			if($estadolote==3) $strestadolote = 'VENDIDO';
			$arr = array('success'=>false,'mensaje'=>'NO PUEDE ELIMINAR EL LOTE SI ESTA '.$strestadolote);
		}
		if($estadolote==4){			
			$sql = "SELECT idhacienda, cantidad FROM hacienda_lote WHERE idlote = $idlote";
			$rs = mysql_query($sql);
			if(mysql_num_rows($rs)>0){
				while($filahl=mysql_fetch_array($rs)){
					$idhaciendalote = $filahl[0];
					$canthaciendalote = $filahl[1];

					$sql = "DELETE FROM hacienda_lote WHERE idhacienda = $idhaciendalote and idlote = $idlote";
					$rsdelhl = mysql_query($sql);

					if($rsdelhl){
						$sql = "UPDATE hacienda SET resto = resto + $canthaciendalote WHERE idhacienda = $idhaciendalote";
						$rshacienda = mysql_query($sql);
					}
				}
			}
			$sql = "DELETE FROM lotes WHERE idlote = $idlote and estado = 4";
			$rsdellote = mysql_query($sql);
			if($rsdellote){
				if(mysql_affected_rows()>0){
					$sql = "UPDATE lotes SET nrolote = nrolote - 1 ";
					$sql .= "WHERE nrolote > $nrolote and idremate = $idremate";
					$rsordenlote = mysql_query($sql);
					if($rsordenlote){
						$arr = array('success'=>true,'mensaje'=>'');
					}else{
						$arr = array('success'=>false,'mensaje'=>$sql);
					}
				}else{
					$arr = array('success'=>false,'mensaje'=>'NO PUEDE ELIMINAR EL LOTE SI ESTA PUBLICADO, ABIERTO o VENDIDO');
				}
			}else{
				$arr = array('success'=>false,'mensaje'=>'ERROR DELETE');
			}
		}

	}
}
?>
