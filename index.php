<?php
	session_start();
	ini_set('post_max_size', '10M');
	include('scripts/validar.php');
	include('scripts/usuario.php');
	include('scripts/subir_archivo.php');
	$titulo = '';
	header("Expires: Tue, 03 Jul 2001 06:00:00 GMT");
	header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
	header("Cache-Control: no-store, no-cache, must-revalidate");
	header("Cache-Control: post-check=0, pre-check=0", false);
	header("Pragma: no-cache");
?>
<html>
<head>
	<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
	<link rel="stylesheet" href="css/jquery-ui.css">  
	<link rel="stylesheet" href="css/bootstrap.css">
	<link rel="stylesheet" href="css/bootstrap-select.css">
	<link rel="stylesheet" href="css/tablesaw.css">
	<link rel="stylesheet" href="css/datatables.css">
	<link rel="stylesheet" href="css/dataTables.bootstrap.css">	
	<link rel="stylesheet" href="css/responsive.dataTables.min.css">
	<link rel="stylesheet" href="css/jquery.fancybox.css">
	<link rel="stylesheet" href="js/helpers/jquery.fancybox-buttons.css?v=1.0.5" media="screen">
	<link rel="stylesheet" href="js/helpers/jquery.fancybox-thumbs.css?v=1.0.7" media="screen">
	<link type="text/css" rel="stylesheet" href="http://p.jwpcdn.com/player/v/7.0.1/skins/stormtrooper.css">
	<link rel="stylesheet" href="css/login.css">
	<link rel="stylesheet" href="css/remates.css">

	<script src="js/jquery.min.js"></script>
	<script src="js/jquery.form.js"></script>
	<script src="js/jquery-ui.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/bootstrap-select.js"></script> 
	<script src="js/tablesaw.js"></script>
	<!--<script src="js/datatables.js"></script>-->
	<script src="js/dataTables.min.js"></script>
	<script src="js/dataTables.bootstrap.min.js"></script>
	<script src="js/jquery.fancybox.pack.js"></script>
	<script src="js/helpers/jquery.fancybox-buttons.js?v=1.0.5"></script>
	<script src="js/helpers/jquery.fancybox-media.js?v=1.0.6"></script>
	<script src="js/helpers/jquery.fancybox-thumbs.js?v=1.0.7"></script>
	<script src="js/jquery.numeric.js"></script>
	<script src="js/jquery.serializeObject.js"></script>
	<script src="js/inputmask.js"></script>
	<script src="js/jquery.inputmask.js"></script>
	<script src="js/inputmask.extensions.js"></script>
	<script src="js/inputmask.date.extensions.js"></script>
	<script src="js/inputmask.numeric.extensions.js"></script>
	<!--<script src="js/jwplayer6.js"></script>-->
	<!--<script src="js/jwplayer.js"></script>-->
	<script type="text/javascript" src="http://videostreaminghd.hdstreamhost.com/players/jwp7/jwplayer.js"></script>
	<!--<script> jwplayer.key="m1A4pGxTafrSAwFcLVL7cYvPcTDl5brDM3sDxIYs7KFn05JG9Gv7eGjBP8c=";</script>	-->
</head>
<body>
			<?php
				if(isset($_SESSION['sperfil'])){
					if($_SESSION['sperfil']==7){ // PANTALLA								
						echo '<div id="encabezado_p">';
						echo '<div id="titulovideo">VIDEO EN VIVO</div>';
						include('formularios/videoflash.php');
						echo '<div id="tituloic1">INFORMACION DEL LOTE</div>';
						echo '<div id="infolote_clic1"></div>';
						echo '<div id="titulooferta">OFERTAS</div>';
						include('formularios/oferta.php');						
						echo '<div id="tituloic2">CONDICIONES</div>';
						echo '<div id="infolote_clic2"></div>';
						include('formularios/producto_rem.php');
						echo '</div>';
					}
					if($_SESSION['sestado']==0){
						if(isset($_POST['btnmenu'])  || isset($_SESSION['sbtnmenu'])){
							if(!isset($_SESSION['sbtnmenu']))
								$_SESSION['sbtnmenu'] = $_POST['btnmenu'];
							if(isset($_POST['btnmenu']))
								$_SESSION['sbtnmenu'] = $_POST['btnmenu'];
						
							if($_SESSION['sbtnmenu']=='Subasta'){
								$titulo = 'SUBASTA';
								include('scripts/abmusuarioconectado.php');
								if($_SESSION['sperfil']==1){ // CLIENTE COMPRADOR								
									echo '<div id="titulochat">CHAT</div>';
									echo '<div id="titulooperadores">OPERADORES</div>';
									echo '<div id="tituloofertas">OFERTAS</div>';
									echo '<div id="tituloinfolote">INFORMACION LOTE</div>';
									echo '<div id="encabezado">';
									include('formularios/chat.php');
									include('formularios/oferta.php');
									//include('formularios/desarrollo_sub.php');
									include('formularios/info_lote_rem.php');									
									echo '</div>';									
								}
								if($_SESSION['sperfil']==2){ //REMATADOR
									echo '<div id="encabezado">';
									include('formularios/chat.php');
									include('formularios/desarrollo_sub_rem.php');
									include('formularios/info_lote_rem.php');								
									echo '</div>';
								}
								if($_SESSION['sperfil']==3 || $_SESSION['sperfil']==4){ //OPERADOR									
									echo '<div id="encabezado">';
									include('formularios/oferta_op.php');
									include('formularios/listado_historico_op.php');
									include('formularios/oferta_cliente_op.php');
									include('formularios/info_lote_rem.php');
									echo '</div>';
								}								
							}elseif ($_SESSION['sbtnmenu']=='Salir') {
								include('scripts/desconectar_usuario.php');
								unset($_SESSION['susuario']);
								unset($_SESSION['sperfil']);
								unset($_SESSION['sestado']);
								if (strlen(session_id()) > 1)									
									session_destroy();
							}
							else{
								//echo '<img src="img/logob.jpg">';
							}
						}
					}
				}
			?>	
		
			<?php
				if(!isset($_SESSION['susuario'])){
					include('formularios/portada.php');
				}else{
					if($_SESSION['sestado']==9){
						include('formularios/portada.php');
						include('formularios/cambio_clave.php');
					}else{
						if(isset($_POST['btnmenu'])  || isset($_SESSION['sbtnmenu'])){
							if(!isset($_SESSION['sbtnmenu']))
								$_SESSION['sbtnmenu'] = $_POST['btnmenu'];
							if(isset($_POST['btnmenu']))
								$_SESSION['sbtnmenu'] = $_POST['btnmenu'];
							if($_SESSION['sbtnmenu'] == 'Subasta'){
								$titulo = 'SUBASTA';
								//include('scripts/abmusuarioconectado.php');
								if($_SESSION['sperfil']==1){ //CLIENTE COMPRADOR
									echo '<div id="contenido"  style="border:none;">';
									include('formularios/subasta.php');
									echo '</div>';
								}
								if($_SESSION['sperfil']==2){ //REMATADOR
									echo '<div id="contenido"  style="border:none;">';
									include('formularios/subasta_rem.php');
									echo '</div>';
								}
								if($_SESSION['sperfil']==3 || $_SESSION['sperfil']==4){ //OPERADOR
									echo '<div id="contenido"  style="border:none;">';
									include('formularios/subasta_op.php');
									echo '</div>';
								}								
							}
							if($_SESSION['sbtnmenu'] == 'Remates'){
								include('scripts/desconectar_usuario.php');
								$titulo = 'REMATES';
								echo '<div id="contenido">';
								if($_SESSION['sperfil']==4 || $_SESSION['sperfil']==2){ //OPERADOR-ADMINISTRADOR								
									include('formularios/admremates.php');
								}
								echo '</div>';
							}
							if($_SESSION['sbtnmenu'] == 'Seguridad'){
								include('scripts/desconectar_usuario.php');
								$titulo = 'SEGURIDAD';
								echo '<div id="contenido">';
								if($_SESSION['sperfil']==5 || $_SESSION['sperfil']==4){ //OPERADOR							
									include('formularios/admseguridad.php');
								}
								echo '</div>';
							}
							if($_SESSION['sbtnmenu'] == 'Hacienda'){
								include('scripts/desconectar_usuario.php');
								$titulo = 'HACIENDA';
								echo '<div id="contenido">';
								if($_SESSION['sperfil']==5 || $_SESSION['sperfil']==4 || $_SESSION['sperfil']==6){ //OPERADOR
									include('formularios/admhacienda.php');
								}
								echo '</div>';
							}
							if($_SESSION['sbtnmenu'] == 'Registrados'){
								include('scripts/desconectar_usuario.php');
								$titulo = 'REGISTRADOS';
								echo '<div id="contenido">';
								if($_SESSION['sperfil']==5 || $_SESSION['sperfil']==4 || $_SESSION['sperfil']==6){ //OPERADOR								
									include('formularios/admhacienda_r.php');
								}
								echo '</div>';
							}
							if($_SESSION['sbtnmenu'] == 'Mis Lotes'){
								include('scripts/desconectar_usuario.php');
								$titulo = 'MIS LOTES';
								echo '<div id="contenido">';
								if($_SESSION['sperfil']==1){ //CLIENTE COMPRADOR
									include('formularios/mislotes.php');
								}
								echo '</div>';
							}
						}else{
							if($_SESSION['sperfil']<7){
								echo '<div id="contenido"></div>';
							}

						}
					}
				}
			?>
		</div>
		<?php		
		if(isset($_SESSION['sperfil'])){
			if($_SESSION['sperfil']<7){
		?>
			<div id="pie">
				<div id="usuariopie">
					<?php
						if(isset($_SESSION['sperfil']) && isset($_SESSION['sestado'])){						
							if($_SESSION['sestado']==0){
								echo 'Apellido y Nombre: '.$_SESSION['apellido'].', '.$_SESSION['nombre'].'<br>';
								echo 'USUARIO: '.$_SESSION['susuario'].'<br>';
								if($_SESSION['sperfil'] > 1)							
									echo 'PERFIL: '.$_SESSION['descperfil'];
							}
						}
					?>
				</div>
					<?php
			if(isset($_SESSION['sperfil'])){
				if($_SESSION['sestado']==0){
					if($_SESSION['sperfil']==1){
						include('menu/menu_cli.php');
					}
					if($_SESSION['sperfil']==2){
						include('menu/menu.php');
					}
					if($_SESSION['sperfil']==3){
						include('menu/menu_op.php');
					}
					if($_SESSION['sperfil']==4){
						include('menu/menu_op_admin.php');
					}					
					if($_SESSION['sperfil']==5){
						include('menu/menu_admin.php');
					}
					if($_SESSION['sperfil']==6){
						include('menu/menu_eval.php');	
					}
				}
			}else{
				include('formularios/login.php');
			}
		?>
				<div id="pantallapie">
					<?php 
						echo $titulo; 
					?>
				</div>

			</div>
		<?php
			}
		}else{
			echo '<div id="pie">';
			include('formularios/login.php');
			echo '</div>';
		}
		?>
		
	<script src="js/subasta.js"></script>

	<?php
		if(isset($_SESSION['susuario']) && isset($_SESSION['sbtnmenu'])){
			if($_SESSION['sbtnmenu']=='Subasta'){
				$ancho = 0;
				$alto = 0;				
				if($_SESSION['sperfil']==2){ //REMATADOR
					echo '<title>BRANDEMANN - REMATE EN VIVO - REMATADOR</title>';
					echo	'<script src="js/rematador.js"></script>';					
				}
				if($_SESSION['sperfil']==1){ //CLIENTE
					echo '<title>BRANDEMANN - REMATE EN VIVO - CLIENTE '.$_SESSION['susuario'].'</title>';
					echo '<script src="js/cliente.js"></script>';
				}
				if($_SESSION['sperfil']==3){ // OPERADOR
					echo '<title>BRANDEMANN - REMATE EN VIVO - OPERADOR</title>';
					echo	'<script src="js/operador.js"></script>';				
				}
				if($_SESSION['sperfil']==4){ // OP-ADMIN
					echo '<title>BRANDEMANN - REMATE EN VIVO - OPERADOR-ADMINISTRADOR</title>';
					echo	'<script src="js/operador.js"></script>';				
				}
			}
			if($_SESSION['sbtnmenu']=='Seguridad'){
				if($_SESSION['sperfil']==4){ // OP-ADMIN
					echo '<title>BRANDEMANN -SEGURIDAD - OPERADOR-ADMINISTRADOR</title>';				
					echo	'<script src="js/seguridad.js"></script>';				
				}				
				if($_SESSION['sperfil']==5){ // ADMIN
					echo '<title>BRANDEMANN - SEGURIDAD - ADMINISTRADOR</title>';
					echo	'<script src="js/seguridad.js"></script>';				
				}
			}
			if($_SESSION['sbtnmenu']=='Remates'){
				if($_SESSION['sperfil']==4){ // OP-ADMIN
					echo '<title>BRANDEMANN - REMATES -OPERADOR-ADMINISTRADOR</title>';				
				}				
				if($_SESSION['sperfil']==5){ // ADMIN
					echo '<title>BRANDEMANN - REMATES - ADMINISTRADOR</title>';
				}
				if($_SESSION['sperfil']==2){ // REMATADOR
					echo '<title>BRANDEMANN - REMATES - REMATADOR</title>';				
				}				
				echo	'<script src="js/remates.js"></script>';				

			}
			if($_SESSION['sbtnmenu']=='Hacienda'){
				if($_SESSION['sperfil']==4){ // OP-ADMIN
					echo '<title>BRANDEMANN - HACIENDA - OPERADOR-ADMINISTRADOR</title>';				
				}				
				if($_SESSION['sperfil']==5){ // ADMIN
					echo '<title>bBRANDEMANN - HACIENDA - ADMINISTRADOR</title>';
				}
				if($_SESSION['sperfil']==6){ // EVALUADOR
					echo '<title>BRANDEMANN - HACIENDA - EVALUADOR</title>';
				}
				//echo '<script src="https://maps.googleapis.com/maps/api/js"></script>';
				echo '<script src="js/hacienda.js"></script>';	
			}
			if($_SESSION['sbtnmenu']=='Registrados'){
				if($_SESSION['sperfil']==4){ // OP-ADMIN
					echo '<title>BRANDEMANN - REGISTRADOS - OPERADOR-ADMINISTRADOR</title>';				
				}				
				if($_SESSION['sperfil']==5){ // ADMIN
					echo '<title>BRANDEMANN - REGISTRADOS - ADMINISTRADOR</title>';
				}
				echo '<script src="js/hacienda_r.js"></script>';	
			}			
			if($_SESSION['sbtnmenu']=='Mis Lotes'){
				echo '<script src="js/mislotes.js"></script>';
			}
		}
		if(isset($_SESSION['sperfil']) && $_SESSION['sperfil']==7){ // OP-ADMIN
			echo '<title>BRANDEMANN - REMATE EN VIVO - PANTALLA</title>';
			echo	'<script src="js/pantalla.js"></script>';				
		}
		?>
	
	
</body>
</html>