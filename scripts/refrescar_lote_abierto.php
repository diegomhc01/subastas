<?php
	include('validar.php');
	if(isset($_POST['param']))
		$_SESSION['sidlote'] = $_POST['param'];
?>