<?php

	if (strlen(session_id()) < 1)
		session_start();
	
	/*
	if(isset($_SESSION['sestado'])){
		if($_SESSION['sestado']==2){
			session_destroy();
			header('Location: http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].'/intertv/remates/bloqueado.php');
		}
		if($_SESSION['sestado']==-1){
			session_destroy();
			header('Location: http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].'/intertv/remates/noautorizado.php');
		}
	}else{
		$_SESSION['sestado'] = -1;
	}
	*/
?>