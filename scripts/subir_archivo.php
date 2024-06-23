<?php
	include('validar.php');
	if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
	    if(isset($_FILES['archivo'])){
	    	$i = 0;
			foreach ($_FILES["archivo"]["error"] as $key => $error) {
		    	if ($error == UPLOAD_ERR_OK) {
					$tipo = $_FILES["archivo"]["type"];
					$tamanio = $_FILES["archivo"]["size"];
					if (!((strpos($tipo, "gif") || strpos($tipo, "jpeg")) && ($tamanio < 10000000))) { 
		       			$tmp_name = $_FILES["archivo"]["tmp_name"][$key];		        	
		        		$name = $_FILES["archivo"]["name"] + $_SESSION['sidhacienda'] + '0' + $i;
		        		if(move_uploaded_file($tmp_name, "/home/intertvc/public_html/remates/fotos/$name")){
		        			sleep(3);
		        			$_SESSION['simagen'] = $name;
		        			include('abmimagen.php');
		        			$i++;
		        		}
		    		}
		    	}
			}
			if(isset($_POST['video'])){
				if($_POST['video']!=''){
					$_SESSION['svideo'] = $_POST['video'];
					include('abmvideo.php');
				}
			}
		}
	}	
?>