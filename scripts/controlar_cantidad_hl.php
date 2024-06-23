<?php
	include('validar.php');	
	include('coneccion.php');	
	if(isset($_SESSION['susuario'])){
		//BUSCO LA CANTIDAD DE CABEZAS ACTUAL EN HACIENDA
		$cantidad = $_POST['cantidad'];
		$idhacienda = $_SESSION['sidhacienda'];
		$cantidadh = 0; 
		$cantidadenlote = 0;
		$cantidadl = array(); 
		//BUSCO LA CANTIDAD ORIGINAL DE CABEZAS EN HACIENDA
		$sql = "SELECT cantidad, resto FROM hacienda WHERE idhacienda = $idhacienda";
		$rs90 = mysql_query($sql);
		$filah = mysql_fetch_row($rs90);
		$cantidadh = $filah[0];
		$resto = $filah[1];
		$arr = array('success'=>false);
		if($cantidad!=$cantidadh){
			//BUSCO LOS LOTES QUE TENGAN ESA MISMA HACIENDA Y LA CANTIDAD EN CADA LOTE
			$sql = "SELECT hl.idlote, hl.cantidad, l.nrolote FROM hacienda_lote hl, lotes l WHERE idhacienda = $idhacienda and hl.idlote = l.idlote";
			$rs91 = mysql_query($sql);
			while($filal = mysql_fetch_array($rs91)){
				$cantidadl[] = $filal;
				$cantidadenlote += $filal[1];
			}
			
			//'modifica'=>0 - 
			//				  QUE NO TENGA LOTE ASOCIADO, SUMO O RESTO HACIENDA
			//				  QUE TENGA UN SOLO LOTE ASOCIADO Y LA CANTIDAD SEA DE CABEZAS SEA MENOR A LA CANTIDAD ACTUAL EN LOTE Y
			//				  HACIENDA	
			//				  		REALIZO LA ACCION, ES DECIR, MODIFICO LA CANTIDAD EN HACIENDA Y, EN TAL CASO, EN LOTE TAMBIEN
			//
			//'modifica'=>1 - TIENE UN SOLO LOTE ASOCIADO Y LA CANTIDAD DE HACIENDA ES MAYOR A LA CANTIDAD DE HACIENDA EN EL LOTE
			//				  DEBO PREGUNTAR SI
			//				  MODIFICO LA CANTIDAD EN EL LOTE O LE SUMO EN HACIENDA EL RESTO DE LA CANTIDAD DE CABEZAS Y DEJO LA CANTIDAD
			//				  DE CABEZAS EN EL LOTE IGUAL
			//				  
			//'modifica'=>2 - CUANDO EXISTE UN DATO DE HACIENDA EN VARIOS LOTES
			//				  PREGUNTO QUE HAGO. TENGO QUE MOSTRAR LA INFORMACION PARA VER QUE ES LO QUE QUIERE HACER EL OPERADOR
			//				  EN EL CASO DE QUE SEA MENOR CANTIDAD DE ANIMALES, DEBO DEFINIR A QUE LOTE LE VOY A RESTAR CANTIDAD DE CABEZAS
			//				  EN EL CASO DE QUE SEA MAYOR LA CANTIDAD DE ANIMALES, DEBO DEFINIR, SI SE LO ASIGNO A UN LOTE, A VARIOS LOTES O
			//				  SE LO SUMO AL RESTO DE HACIENDA	

			//SI NO EXISTE ASOCIACION ENTRE LOTE Y HACIENDA
			if($cantidadenlote==0){
				$arr = array('success'=>true,'modifica'=>0,'cantidad'=>$cantidad,'resto'=>0,'lotes'=>0);
			}
			if(isset($cantidadl)){				
				//SI LA EXISTE ASOCIACION ENTRE LOTE Y HACIENDA ES DE UNO A UNO
				if(count($cantidadl)==1){
					if($cantidadl[0][1]<$cantidad){
						$arr = array('success'=>true,'modifica'=>1,'cantidad'=>$cantidadh,'resto'=>0,'lotes'=>$cantidadl[0][0]);
					}
					if($cantidadl[0][1]>=$cantidad){
						//PREGUNTAR SI QUIERE SUMAR LA DIFERENCIA ENTRE LA CANTIDAD EN EL LOTE Y LA DIFERENCIA EN LA HACIENDA
						$arr = array('success'=>true,'modifica'=>1,'cantidad'=>$cantidadh,'resto'=>($cantidad - $cantidadenlote),'lotes'=>$cantidadl[0][0]);
					}
				}
				//SI LA ASOCIACION DE HACIENDA Y LOTE ES UNA HACIENDA VARIOS LOTES
				if(count($cantidadl)>1){
					if($cantidadenlote<=$cantidad){
						$arr = array('success'=>true,'modifica'=>2,'cantidad'=>$cantidadh,'restonuevo'=>($cantidad - $cantidadenlote),'lotes'=>$cantidadl,'cantidadnueva'=>$cantidad,'resto'=>$resto);
					}
					if($cantidadenlote>$cantidad){
						$arr = array('success'=>true,'modifica'=>3,'cantidad'=>$cantidadh,'restonuevo'=>($cantidadenlote-$cantidad),'lotes'=>$cantidadl,'cantidadnueva'=>$cantidad,'resto'=>$resto);
					}
				}
			}			
		}
		echo json_encode($arr);
	}
?>