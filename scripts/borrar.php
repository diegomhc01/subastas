<?php

	$producto['id'] = $id; 
	$producto['nombre'] = $nombre; 
	$producto['precio'] = $precio; 
	$producto['cantidad'] = $cantidad;
	$_SESSION['scarrito'][] = $producto;

	$cant = count($_SESSION['scarrito']);
	for($i=0;$i<$cant;$i++){
		$producto = $_SESSION['scarrito'][$i];
	}
	$ususario = $_SESSION['susuario'];
	$sql = 	"INSERT INTO tmppedido (id, nombre, precio, cantidad, usuario) VALUES 
	($id, '$nombre',$precio,$cantidad,'$usuario')";
	$rs = mysql_query($sql)

?>