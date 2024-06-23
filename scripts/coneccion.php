<?php	
	$coneccion = mysql_connect('127.0.0.1','root','');
//	$bd = mysql_select_db('develhar_intertv',$coneccion);
//	$coneccion = mysql_connect('127.0.0.1','develhar_admin','@mb+60P)ggn]') or die("Error " . mysql_error());
	$bd = mysql_select_db('develhar_intertv_desa',$coneccion) or die("Error " . mysql_error());
//	$bd = mysql_select_db('develhar_intertv',$coneccion) or die("Error " . mysql_error());
?>