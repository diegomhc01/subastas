<?php
	
	include('validar.php');
	require_once('macro.php');
	
	if(isset($_SESSION['susuario'])){
		$macro = new Macro();
		$retorno = '';
		$accion = 0;
		
		$usuario = $_SESSION['susuario'];

		if(isset($_POST['accion']))
			$accion = $_POST['accion'];
				$perfil = $_SESSION['sperfil'];
		switch($accion) {
			case 0:
				//$arrretorno = array();			
				if($perfil==1){ //CLIENTE
			        $arrretorno['remateabierto'] = $macro->buscarremateabierto();
			        $arrretorno['chatinicio'] = $macro->cargarchatinicio();		          
			        $arrretorno['operadoresconectados'] = $macro->buscaroperadoresconectados($usuario);
			        $arrretorno['usuarionuevo'] = $macro->buscarusuarionuevo();
			        $arrretorno['desarrolloremate'] = $macro->buscardesarrolloremateinicio();
			        $arrretorno['lote'] = $macro->buscarloteinicio();
			        $arrretorno['ofertapropia'] = $macro->buscarofertapropiainicio();
			        $arrretorno['mejoroferta'] = $macro->buscarmejorofertainicio();
		        }
		        if($perfil==2){ //REMATADOR
			        $arrretorno['remateabierto'] = $macro->buscarremateabierto();
			        $arrretorno['lotes'] = $macro->buscarlotesinicio();
			        $arrretorno['usuariosconectados'] = $macro->buscarusuariosconectados();
			        $arrretorno['chatinicio'] = $macro->cargarchatinicio();
			        $arrretorno['lote'] = $macro->buscarloteinicio();
			        $arrretorno['mejoroferta'] = $macro->buscarmejorofertainicio();
			        $arrretorno['desarrolloremateop'] = $macro->buscardesarrolloremateinicioop();
		        }
		        if($perfil==3){ //OPERADOR
		        	$arrretorno[] = $macro->buscarremateabierto();          
		        }
		        if($perfil==4){ //OPERADOR-ADMINISTRADOR
			        $arrretorno['remateabierto'] = $macro->buscarremateabierto();
			        $arrretorno['chatinicio'] = $macro->cargarchatinicio();
			        $arrretorno['ofertapropia'] = $macro->buscarofertapropiainicio();
			        $arrretorno['usuariosconectados'] = $macro->buscarusuariosconectados();
			        $arrretorno['desarrolloremateop'] = $macro->buscardesarrolloremateinicioop();
			        $arrretorno['lote'] = $macro->buscarloteinicio();
			        $arrretorno['mejoroferta'] = $macro->buscarmejorofertainicio();
		        }
		        if($perfil==7){ //PANTALLA
		        	$arrretorno['remateabierto'] = $macro->buscarremateabierto();
		        	$arrretorno['lote'] = $macro->buscarloteinicio();
		        	$arrretorno['mejoroferta'] = $macro->buscarmejorofertainicio();
		        }
		        break;
	        case 1000:
	              //BUSCAR PRECIO INICIAL
	              include('buscar_precio_inicial.php');
	              break;
	        case 1001:              
	              $retorno = $macro->enviarchat($_POST);  
	              break;
	        case 1010:
	              $retorno = $macro->enviarchatatodos($_POST);  
	              break;
	        case 2001:              
	              $retorno = $macro->realizaroferta($_POST);
	              break;
	        case 2002:              
	              $retorno = $macro->solicitarcredito();
	              break;              
	        case 4000:
	              $retorno = $macro->modificarcredito($_POST);
	              break;
	        case 4001:
	              	$retorno = $macro->realizarofertaop($_POST);
	              	break;
	        case 4002:
	            	$retorno = $macro->bloquearcliente($_POST);
	              	break;
	        case 4050:
	        		$retorno = $macro->modificarcreditotodos($_POST);
	        		break;
	        case 4100: 
	        		$retorno = $macro->modificarcredito($_POST);
	        		break;
	        case 5002:
	              //CHAT            
	              $retorno = $macro->buscardatoslote($_POST);	              	             
	              break;
	        case 5003:
	              //ABRIR LOTE
	              $retorno = $macro->abrirlote($_POST);
	              break;
	        case 5004:
	              //REABRIR LOTE
	              $retorno = $macro->reabrirlote($_POST);
	              break;
	        case 5005:
	              //PASAR LOTE
	              $retorno = $macro->pasarlote();
	              break;
	        case 5006:
	              //BAJAR MARTILLO
	              $retorno = $macro->bajarmartillo($_POST);
	              break;
	        case 5008:
	              //MOSTRAR DESARROLLO DEL REMATE
	              $retorno = $macro->cerrarlote();
	              break;
	        case 9006:
	              //ACEPTAR OFERTA
	              $retorno = $macro->aceptaroferta($_POST);
	              break;                  
	        case 9007:
	              //ACEPTAR OFERTA
	              $retorno = $macro->cerrarofertaop($_POST);
	              break;                  
	        case 9008:
	              $retorno = $macro->omitiroferta($_POST);
	              break;       
	        case 9009:
	              $retorno = $macro->modificartipoprecio($_POST);
	              break;              
	        case 9010:
	              $retorno = $macro->modificarprecioinicio($_POST);
	              break;               
	        case 9011:
	              $retorno = $macro->modificarincremento($_POST);
	              break;
	        case 9012:
	              $retorno = $macro->anularoferta();
	              break; 
	        case 10000:	        	
	              $retorno = $macro->habilitarremate($_POST);	              
	              break; 
	        }
	        if(isset($arrretorno)){
	        	echo json_encode($arrretorno);
	        }
	        if(isset($retorno)){
	        	echo $retorno;
	        }else{
	        	echo json_encode(array('success'=>false));
	        }
	}
	

?>