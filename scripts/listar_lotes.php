<?php	
	include('validar.php');
	include('coneccion.php');	
	if(isset($_SESSION['susuario'])){
	if(isset($_GET['param'])){
		if(!isset($_SESSION['sidremate'])){
			$_SESSION['sidremate'] = $_GET['param'];
		}else{
			if($_SESSION['sidremate'] != $_GET['param']){
				$_SESSION['sidremate'] = $_GET['param'];
			}
		}
	}
		
	 $idremate = $_SESSION['sidremate'];
		$sql = "SELECT  l.idlote, l.cantcabezas, ";
		$sql .= "CASE l.estado WHEN 0 THEN 'Publicado' WHEN 1 THEN 'Rematando' WHEN 4 THEN 'Sin Publicar' END as estado, ";
		$sql .= "CONCAT(tp.descripcion, ' (', l.inc1, ' - ', l.inc2, ' - ', l.inc3, ')') AS tipoprecio, l.orden, l.nrolote, ";
		$sql .= "case l.tipoentrega when 0 then 'INMEDIATO' when 1 then 'A TERMINO' END AS tipoentrega, l.precioinicio, l.estado ";
		$sql .= "FROM lotes l, tipoprecio tp WHERE l.idremate = $idremate and tp.idtp = l.idtp ORDER BY l.nrolote";
		$rs = mysql_query($sql);
		$arr = array();
		$arr2 = array();
		$cantidad = mysql_num_rows($rs);
		$i = 0;
		if($cantidad>0){
			while($row=mysql_fetch_array($rs)){
				$sql = "SELECT c.descripcion, hl.cantidad, hl.idhacienda, ";
				$sql .= "p.apeynom, h.nrocontrato, h.pelaje ";
				$sql .= "FROM hacienda_lote hl, hacienda h, categoria c, cliente cl, persona p ";
				$sql .= "WHERE h.idcategoria = c.idcategoria and ";
				$sql .= "cl.idcliente = h.idvendedor and cl.idpersona = p.idpersona and ";
				$sql .= "h.idhacienda = hl.idhacienda and hl.idlote = ".$row[0];

				$desc = '';
				$rs1 = mysql_query($sql);
				if(mysql_num_rows($rs1)>0){
					$desc = '(';
					$cant = 0;
					while($fila=mysql_fetch_array($rs1)){
						$idhacienda = $fila[2];
						if($cant==0){
							$desc .= $fila[1].' '.$fila[0];	
						}else{
							$desc .= ' - '.$fila[1].' '.$fila[0];	
						}
						$desc .= ' ['.$fila[3].' - '.$fila[4].' - '.$fila[5].']';
						$cant ++; 					
					}
					$desc .= ')';
				}else{
					$desc = '[Sin hacienda]';
				}
				
				if($row[8]==0) $estado = 'SIN VENDER';
				if($row[8]==1) $estado = 'REMATANDO';
				if($row[8]==2) $estado = 'MARTILLO BAJADO';
				if($row[8]==3) $estado = 'VENDIDO';
				if($row[8]==4) $estado = 'SIN PUBLICAR';
				
				$arr['nrolote'] = $row[5];
				$arr['cabezas'] = $row[1];
				$arr['detalle'] = utf8_encode($desc);
				$arr['tipoprecio'] = $row[3]; 
				$arr['entrega'] = $row[6];
				$arr['precio'] = $row[7];
				$arr['estado'] = $estado;
				$arr['boton1'] = '<input type="text" name="nrolote[]" size="1" class="clsinputlote" style="text-align:center;" value="'.$row[5].'" id="txt'.$row[0].'">';
				$arr['boton2'] = '<input type="image" name="btnlote" src="images/eliminar.png" value="Eliminar" class="clslote" id="e'.$row[0].'">';
				$arr['boton3'] = '<input type="image" name="btnlote" src="images/vaca.png" value="Hacienda" class="clslote" id="x'.$row[0].'">';
				if($row[8]==0 || $row[8]==4){ //GRIS SIN ABRIR
					$arr['boton4'] = '<input type="image" name="btnlote" src="images/abrir_gris.png" value="Abrir" class="clslote" id="c'.$row[0].'">';
				}else{
					if($row[8]==1){ // AMARILLO - REMATANDO
						$arr['boton4'] = '<input type="image" name="btnlote" src="images/abrir_amarillo.png" value="Abrir" class="clslote" id="c'.$row[0].'">';
					}else{
						if($row[8]==3){// ROJO - CERRADO
							$arr['boton4'] = '<input type="image" name="btnlote" src="images/abrir_rojo.png" value="Abrir" class="clslote" id="c'.$row[0].'">';
						}
					}
				}
				if($row[8]!=4){
					$arr['boton5'] = '<input type="image" name="btnlote" src="images/habilitado.png" value="Deshabilitar" class="clslote" id="d'.$row[0].'">';

				}
				if($row[8]==4){
					$arr['boton5'] = '<input type="image" name="btnlote" src="images/deshabilitado.png" value="Habilitar" class="clslote" id="h'.$row[0].'">';
				}
				if($i==0){
					$arr['boton6'] = '';
					$arr['boton7'] = '<input type="image" name="btnlote" src="images/bajar.png" value="Bajar" class="clslote" id="b'.$row[0].'">';					
				}else{
					if($i==$cantidad-1){
						$arr['boton6'] = '<input type="image" name="btnlote" src="images/subir.png" value="Subir" class="clslote" id="s'.$row[0].'">';
						$arr['boton7'] = '';
					}else{
						$arr['boton6'] = '<input type="image" name="btnlote" src="images/subir.png" value="Subir" class="clslote" id="s'.$row[0].'">';
						$arr['boton7'] = '<input type="image" name="btnlote" src="images/bajar.png" value="Bajar" class="clslote" id="b'.$row[0].'">';						
					}
				}
				$arr2[] = $arr;
				$i++;
			}
		}
		
		echo json_encode(array('aaData'=>$arr2));
	
	}
?>