<?php		
	include('validar.php');
	include('classConeccion.php');
	
	class Macro{
		function __construct(){
		}
		private function horaactual(){
			setlocale(LC_TIME, 'es_RA');
			$hora = getdate();
			$horas = $hora['hours']-3;
			$minutos = $hora['minutes'];
			$segundos = $hora['seconds'];
			$horas = $horas>9 ? $horas : '0'.$horas;
			$minutos = $minutos>9 ? $minutos : '0'.$minutos;
			$segundos = $segundos>9 ? $segundos : '0'.$segundos;
			$hora = $horas.':'.$minutos.':'.$segundos;
			return $hora;
		}
		public function enviarchat($mes){			
			Coneccion::conectar();

			$mensaje = htmlentities(mysql_real_escape_string($mes['texto']));		
			$idusrr = $mes['usrr'];					
			$usre = $_SESSION['susuario'];	
			$hora = $this->horaactual();
			$perfil = $_SESSION['sperfil'];
			$idremate = $_SESSION['sidremate'];
			$apellido = $_SESSION['apellido'];			
			$nombre = $_SESSION['nombre'];			
			$apeynomr='';

			$sql = "SELECT perfil, usuario, CONCAT(apellido, ' ', nombre) as apeynomr FROM usuarios WHERE idusuario = $idusrr";
			$rs0 = mysql_query($sql);
			if(mysql_num_rows($rs0)>0){
				$fperfil = mysql_fetch_row($rs0);
				$perfilr = $fperfil[0];
				$usrr = $fperfil[1];
				$apeynomr = $fperfil[2];
				$sql = "INSERT INTO chat (usre, usrr, mensaje, idremate) VALUES ('$usre', '$usrr', '$mensaje', $idremate)";
				$rs = mysql_query($sql);
				$arr = array();
				if($rs){
					if($perfilr==1){
						$color = 'color:#993333;background-color:transparent;border:1px solid #993333';
					}else{
						$color = 'color:#336699;background-color:transparent;border:1px solid #336699';
					}
					$arr = array('accion'=>1100,
					'mensaje'=>utf8_encode(stripslashes($mensaje)),
					'usrr'=>$usrr,
					'idusrr'=>$idusrr,
					'apeynomr'=>$apeynomr,
					'usre'=>utf8_encode($apellido).' '.utf8_encode($nombre),
					'hora'=>$hora,
					'success'=>true,
					'color'=>$color);
				}else{
					$arr = array('accion'=>1100,'success'=>false,'sql'=>$sql);
				}
			}else{
				$arr = array('accion'=>1100,'success'=>false,'sql'=>$sql);
			}
			return json_encode($arr);
		}						
		public function enviarchatatodos($mes){			
			Coneccion::conectar();

			$mensaje = filter_input(INPUT_POST, 'texto', FILTER_SANITIZE_SPECIAL_CHARS);

			$usre = $_SESSION['susuario'];	
			$hora = $this->horaactual();
			$perfil = $_SESSION['sperfil'];
			
			$sql = "INSERT INTO chat (usre, usrr, mensaje) VALUES ('$usre', 'todos', '$mensaje')";
			$rs = mysql_query($sql);			
			if($rs){
				$arr = array('accion'=>1110,
					'mensaje'=>utf8_encode($mensaje),
					'usre'=>utf8_encode($_SESSION['apellido']).' '.utf8_encode($_SESSION['nombre']),
					'hora'=>$hora,
					'success'=>true,
					'color'=>'#993333');
			}else{
				$arr = array('success'=>false,'sql'=>$sql);
			}			
			return json_encode($arr);
		}		
		public function cargar($mes){
			Coneccion::conectar();
			$idchat = 0;
			$perfil = $_SESSION['sperfil'];
			if($perfil==3 || $perfil==1){
				$sql = "SELECT c.idchat, CONCAT(r.apellido, ', ', r.nombre) as usrr, CONCAT(u.apellido, ', ', u.nombre) as usre, c.mensaje, DATE_FORMAT(c.hora, '%H:%i:%s') AS hora, case r.perfil when 1 then '#336699' else '#993333' end as color ";
				$sql .= "FROM chat c, usuarios u, usuarios r ";
				$sql .= "WHERE c.usre = '".$perfil."' AND c.idchat > $idchat and c.usre = u.usuario and r.usuario = c.usrr and c.estado = 0 ";
				$sql .= "UNION ";
				$sql .= "SELECT c.idchat, CONCAT(u.apellido, ', ', u.nombre) as usrr, CONCAT(e.apellido, ', ', e.nombre) as usre, c.mensaje, DATE_FORMAT(c.hora, '%H:%i:%s') AS hora, case e.perfil when 1 then '#336699' else '#993333' end as color ";
				$sql .= "FROM chat c, usuarios u, usuarios e ";
				$sql .= "WHERE c.usrr = '".$perfil."' AND c.idchat > $idchat and c.usrr = u.usuario and e.usuario = c.usre and c.estado = 0 ";
				$sql .= "ORDER BY idchat DESC";
			}
			if($perfil==2){
				$sql = "SELECT DISTINCT idchat, usre, usrr, mensaje, hora, color ";
				$sql .= "FROM (";
				$sql .= "SELECT c.idchat, CONCAT(u.apellido, ', ', u.nombre, ' >> ', r.apellido, ', ', r.nombre) AS usre, '' as usrr, c.mensaje, DATE_FORMAT(c.hora, '%H:%i:%s') AS hora, case r.perfil when 1 then '#993333' else '#336699' end as color ";
				$sql .= "FROM chat c, usuarios u, usuarios r ";
				$sql .= "WHERE c.idchat > $idchat and c.usre = u.usuario and r.usuario = c.usrr and c.estado = 0 ";
				$sql .= "UNION ";
				$sql .= "SELECT c.idchat, CONCAT(e.apellido, ', ', e.nombre, ' >> ', u.apellido, ', ', u.nombre) AS usre, '' as usrr, c.mensaje, DATE_FORMAT(c.hora, '%H:%i:%s') AS hora, case e.perfil when 1 then '#336699' else '#993333' end as color ";
				$sql .= "FROM chat c, usuarios u, usuarios e ";
				$sql .= "WHERE c.idchat > $idchat and c.usrr = u.usuario and e.usuario = c.usre and c.estado = 0) C ";
				$sql .= "ORDER BY idchat DESC";
			}
			if($_SESSION['sperfil']==4){
				$sql = "SELECT c.idchat, CONCAT(u.apellido, ', ', u.nombre, ' >> ', r.apellido, ', ', r.nombre) AS usre, '' as usrr, c.mensaje, DATE_FORMAT(c.hora, '%H:%i:%s') AS hora, case r.perfil when 1 then '#993333' else '#336699' end as color ";
				$sql .= "FROM chat c, usuarios u, usuarios r ";
				$sql .= "WHERE c.idchat > $idchat and c.usre = u.usuario and r.usuario = c.usrr and c.estado = 0 ";
				$sql .= "ORDER BY idchat DESC";
			}


			$rs = mysql_query($sql);			
			if(mysql_num_rows($rs)>0){
				while($row=mysql_fetch_assoc($rs)){
					$arr = array('accion'=>1100,'success'=>true,'idchat'=>$row['idchat'], 'usre'=>$row['usre'], 'usrr'=>$row['usrr'], 'mensaje'=>$row['mensaje'], 'hora'=>$row['hora'],'color'=>$row['color']);
				}
			}else{
				$arr = array('success'=>false,'sql'=>$sql);
			}
			return json_encode($arr);
		}
		public function cargarchatinicio(){
			Coneccion::conectar();			
			$idchat = 0;
			$perfil = $_SESSION['sperfil'];
			$usuario = $_SESSION['susuario'];
			if($perfil==3 || $perfil==1){
				$sql = "SELECT c.idchat, CONCAT(r.apellido, ', ', r.nombre) as usrr, CONCAT(u.apellido, ', ', u.nombre) as usre, c.mensaje, DATE_FORMAT(c.hora, '%H:%i:%s') AS hora, case r.perfil when 1 then '#993333' else '#336699' end as color ";
				$sql .= "FROM chat c, usuarios u, usuarios r ";
				$sql .= "WHERE c.usre = '$usuario' and c.usre = u.usuario and r.usuario = c.usrr and c.estado = 0 ";
				$sql .= "UNION ";
				$sql .= "SELECT c.idchat, CONCAT(u.apellido, ', ', u.nombre) as usrr, CONCAT(e.apellido, ', ', e.nombre) as usre, c.mensaje, DATE_FORMAT(c.hora, '%H:%i:%s') AS hora, case e.perfil when 1 then '#336699' else '#993333' end as color ";
				$sql .= "FROM chat c, usuarios u, usuarios e ";
				$sql .= "WHERE c.usrr = '$usuario' and c.usrr = u.usuario and e.usuario = c.usre and c.estado = 0 ";
				$sql .= "UNION ";
				$sql .= "SELECT c.idchat, CONCAT(u.apellido, ', ', u.nombre) as usrr, CONCAT(e.apellido, ', ', e.nombre) as usre, c.mensaje, DATE_FORMAT(c.hora, '%H:%i:%s') AS hora, case e.perfil when 1 then '#336699' else '#993333' end as color ";
				$sql .= "FROM chat c, usuarios u, usuarios e ";
				$sql .= "WHERE c.usrr = 'todos' and e.usuario = c.usre and u.usuario = '$usuario' and c.estado = 0 ";
				$sql .= "ORDER BY idchat asc";
			}
			if($perfil==2){
				$sql = "SELECT DISTINCT idchat, usre, usrr, mensaje, hora, color ";
				$sql .= "FROM (";
				$sql .= "SELECT c.idchat, CONCAT(u.apellido, ', ', u.nombre) AS usre, CONCAT(r.apellido, ', ', r.nombre) as usrr, c.mensaje, DATE_FORMAT(c.hora, '%H:%i:%s') AS hora, case r.perfil when 1 then '#993333' else '#336699' end as color ";
				$sql .= "FROM chat c, usuarios u, usuarios r ";
				$sql .= "WHERE c.idchat > $idchat and c.usre = u.usuario and r.usuario = c.usrr and c.estado = 0 ";
				$sql .= "UNION ";
				$sql .= "SELECT c.idchat, CONCAT(e.apellido, ', ', e.nombre) AS usre, CONCAT(u.apellido, ', ', u.nombre) as usrr, c.mensaje, DATE_FORMAT(c.hora, '%H:%i:%s') AS hora, case e.perfil when 1 then '#336699' else '#993333' end as color ";
				$sql .= "FROM chat c, usuarios u, usuarios e ";
				$sql .= "WHERE c.usrr = u.usuario and e.usuario = c.usre and c.estado = 0) C ";
				$sql .= "ORDER BY idchat asc";
			}
			if($perfil==4){
				//$sql = "SELECT c.idchat, CONCAT(u.apellido, ', ', u.nombre) AS usre, '' as usrr, c.mensaje, DATE_FORMAT(c.hora, '%H:%i:%s') AS hora, case r.perfil when 1 then '#993333' else '#336699' end as color ";
				$sql = "SELECT c.idchat, CONCAT(u.apellido, ', ', u.nombre) AS usre, CONCAT(r.apellido, ', ', r.nombre) as usrr, c.mensaje, DATE_FORMAT(c.hora, '%H:%i:%s') AS hora, case r.perfil when 1 then '#993333' else '#336699' end as color ";
				$sql .= "FROM chat c, usuarios u, usuarios r ";
				$sql .= "WHERE c.usre = u.usuario and r.usuario = c.usrr and c.usre = '$usuario' and c.estado = 0 ";
				$sql .= "UNION ";
				$sql .= "SELECT c.idchat, CONCAT(u.apellido, ', ', u.nombre) AS usre, CONCAT(r.apellido, ', ', r.nombre) as usrr, c.mensaje, DATE_FORMAT(c.hora, '%H:%i:%s') AS hora, case r.perfil when 1 then '#993333' else '#336699' end as color ";
				$sql .= "FROM chat c, usuarios u, usuarios r ";
				$sql .= "WHERE c.usre = u.usuario and r.usuario = c.usrr and c.usrr = '$usuario' and c.estado = 0 ";
				$sql .= "ORDER BY idchat ASC";
			}			
			$rs = mysql_query($sql);					
			if(mysql_num_rows($rs)>0){
				while($row=mysql_fetch_assoc($rs)){
					$estilo = 'color:'.$row['color'].';background-color:transparent;border:1px solid '.$row['color'];
					$arr[] = array('accion'=>0,'success'=>true,'idchat'=>$row['idchat'], 'usre'=>utf8_encode($row['usre']), 'usrr'=>utf8_encode($row['usrr']), 'mensaje'=>utf8_encode($row['mensaje']), 'hora'=>$row['hora'],'color'=>$estilo);
				}
			}else{
				$arr[] = array('accion'=>0,'success'=>false);
			}			
			return array('aaData'=>$arr);
		}
		public function buscaroperadoresconectados(){
			Coneccion::conectar();
			$cantidad = 0;
			$usuario = $_SESSION['susuario'];			
			$sql = "SELECT u.idusuario, u.nombre, u.apellido, 0 as estado, uc.estado as ucestado, u.usuario ";
			$sql .= "FROM uconectados uc, usuarios u ";
			$sql .= "WHERE uc.usuario <> '$usuario' AND uc.usuario = u.usuario and (u.perfil = 3 or u.perfil = 4) and ";
			$sql .= " u.idusuario NOT IN (";
			$sql .= "SELECT u.idusuario ";
			$sql .= "FROM uconectados uc, usuarios u ";
			$sql .= "WHERE uc.usuario = '$usuario' and uc.operador = u.usuario) ";			
			$sql .= "UNION ";
			$sql .= "SELECT idusuario, u.nombre, u.apellido, 1 as estado, -1 as ucestado, u.usuario "; 
			$sql .= "FROM usuarios u ";
			$sql .= "WHERE (u.perfil = 3 or u.perfil = 4) and usuario NOT IN  (";
			$sql .= "SELECT usuario ";
			$sql .= "FROM uconectados) and ";
			$sql .= "idusuario NOT IN (SELECT u.idusuario FROM uconectados uc, usuarios u WHERE uc.usuario = '$usuario' and uc.operador = u.usuario) ";
			$sql .= "UNION ";
			$sql .= "SELECT u.idusuario, u.nombre, u.apellido, 2 AS estado, 0 as ucestado, u.usuario ";
			$sql .= "FROM uconectados uc, usuarios u ";
			$sql .= "WHERE uc.usuario = '$usuario' and uc.operador = u.usuario and uc.operador IN (SELECT usuario FROM uconectados) ";
			$sql .= "UNION ";
			$sql .= "SELECT u.idusuario, u.nombre, u.apellido, 2 AS estado, 1 as ucestado, u.usuario ";
			$sql .= "FROM uconectados uc, usuarios u ";
			$sql .= "WHERE uc.usuario = '$usuario' and uc.operador = u.usuario and uc.operador NOT IN (SELECT usuario FROM uconectados) "; 
			$sql .= "ORDER BY 3,2";

			$rs = mysql_query($sql);	
			$cantidad = mysql_num_rows($rs);
			
			if($cantidad==0){
				$html[]  = array('success'=>false,'cantidad'=>'0');
			}else{
				while($row=mysql_fetch_array($rs)){
					$id = $row[0];
					$estado = $row[3];
					$estilo = "width: 113px;font-size: 10px;border: 1px solid black;margin-bottom:2px;height:45px;";
					$valor = $row[1].'&#x00A;'.$row[2];
					$habilitado = '';
					if($estado==2){
						if($row[4]==0)
							$estilo .= "background-color:#669966;color:black;";
						if($row[4]==1)
							$estilo .= "background-color:#999999;color:black;";
					}
					if($estado==1){					
							$estilo .= "background-color:#DDDDDD;color:black;";							
							$habilitado = 'disabled';
					}
					if ($estado==0) {
						if($row[4]==0)
							$estilo .= "background-color:#0099CC;color:white;";	
						if($row[4]==1)
							$estilo .= "background-color:#CCFF66;color:#999999;";	
					}
					$html[] = array('accion'=>1200,
						'iduconectados'=>$row[0],
						'success'=>true,
						'usuarios'=>'<li id="'.$id.'"><input type="button" class="clsbtnchatusuario" style="'.$estilo.'" name="btnchatusuario" id="btnchatusuario'.$id.'" value="'.$valor.'"'.$habilitado.'></li>',
						'cantidad'=>(string)$cantidad);		
				}
			}
			
			return $html;
		}
		public function buscarusuariosconectados(){
			Coneccion::conectar();
			$cantidad = 0;
			$perfil = $_SESSION['sperfil'];
			$usuario = $_SESSION['susuario'];
			if($perfil==4 || $perfil==2){
				$sql = "SELECT iduconectadosoa, uc.usuario, CONCAT(u.nombre, ' ', u.apellido) AS apeynom, idsesion, uc.estado, u.idusuario ";
				$sql .= "FROM uconectadosoa uc, usuarios u ";
				$sql .= "WHERE uc.usuario <> '$usuario' AND uc.usuario = u.usuario";
			}
			//VER USER->USUARIO, NO EXISTE EN ESTE CONTEXTO
			if($perfil==3){
				$sql = "SELECT iduconectados, uc.usuario, CONCAT(u.nombre, ' ', u.apellido) AS apeynom, idsesion, uc.estado, u.idusuario ";
				$sql .= "FROM uconectados uc, usuarios u ";
				$sql .= "WHERE uc.usuario <> '$usuario' AND uc.usuario = u.usuario and uc.operador = (SELECT usuario FROM usuarios WHERE idusuario = ".$user->usuario.")";
			}

			$rs = mysql_query($sql);	
			$cantidad = mysql_num_rows($rs);
			if($cantidad==0){
				$html[]  = array('accion'=>1200,'success'=>false,'cantidad'=>'0');
			}else{
				while($row=mysql_fetch_array($rs)){
					$id = $row[5];
					if($perfil==2){
						$html[] = array('accion'=>1200,
							'iduconectados'=>$row[0],
							'success'=>true,
							'usuarios'=>'<li class="clsusuariosrem" id="'.$id.'" style="background-color:#DDDDDD,color:black;padding-left:10px;margin-bottom:10px;border-bottom:1px solid black;">'.utf8_encode($row[2]).'</li>',
							'cantidad'=>(string)$cantidad);
					}else{					
						if($row[4]==0){
							$html[] = array('accion'=>1200,
								'iduconectados'=>$row[0],
								'success'=>true,
								'usuarios'=>'<li class="clsusuariosop" id="'.$id.'"><input type="submit" class="clsbtnchatusuario" style="display: inline;padding: .2em .6em .3em;font-size: 75%;font-weight: bold;line-height: 1;text-align: center;white-space: nowrap;vertical-align: baseline;border-radius: .25em;background-color:#DDDDDD,color:black;" name="btnchatusuario" id="btnchatusuario'.$id.'" value="'.utf8_encode($row[2]).'"></li>',
								'cantidad'=>(string)$cantidad);
						}else{
							$html[] = array('accion'=>1200,
								'iduconectados'=>$row[0],
								'success'=>true,
								'usuarios'=>'<li class="clsusuariosop" id="'.$id.'"><input type="submit" class="clsbtnchatusuario" style="display: inline;padding: .2em .6em .3em;font-size: 75%;font-weight: bold;line-height: 1;text-align: center;white-space: nowrap;vertical-align: baseline;border-radius: .25em;background-color:#0099CC;color:white;" name="btnchatusuario" id="btnchatusuario'.$id.'" value="'.utf8_encode($row[2]).'"></li>',
								'cantidad'=>(string)$cantidad);
						}
					}
				}
			}
			return $html;
		}
		public function buscarusuarionuevo(){
			Coneccion::conectar();
			$vusuario = $_SESSION['susuario'];
			$arr = array("estado"=>1);
			$sql = "SELECT * FROM uconectados WHERE usuario = '$vusuario'";
			$rs = mysql_query($sql);
			if(mysql_num_rows($rs)==0){
				$arr = array("estado"=>0);
				$sql = "SELECT * FROM uconectadosoa WHERE usuario = '$vusuario'";
				$rs1 = mysql_query($sql);
				if(mysql_num_rows($rs1)>0){					
					$arr = array("estado"=>1);
				}
			}
			return $arr;
		}
		public function buscardesarrolloremateinicio(){
			Coneccion::conectar();
			mysql_set_charset('utf8');
			//$arr = array();
			$usuario = $_SESSION['susuario'];
			$sql = "SELECT idDS,  hora, detalle  FROM desarrollosub WHERE usuario = '$usuario' and estado = 0 ";
			$sql .= "UNION ";
			$sql .= "SELECT idDS,  hora, detalle  FROM desarrollosub WHERE usuario = 'todos' and estado = 0  ";
			$sql .= "ORDER BY 1 asc";

			$rs = mysql_query($sql);			
			if(mysql_num_rows($rs) > 0){
				while($row=mysql_fetch_assoc($rs)){										
					$arr[] = array('idDS'=>$row['idDS'],'hora'=>$row['hora'],'detalle'=>$row['detalle'],'success'=>true);
				}
			}else{				
				$arr[] = array('idDS'=>0,'hora'=>'','detalle'=>'','success'=>false);
			}
			
			return array('aaData'=>$arr,'accion'=>1203);
		}
		public function buscardesarrolloremateinicioop(){
			Coneccion::conectar();
			mysql_set_charset('utf8');
			$arr = array();
			$usuario = $_SESSION['susuario'];
			$idremate = $_SESSION['sidremate'];
			$sql = "SELECT d.idDS AS idds, concat('A ', p.apeynom, ' ', d.detalle) AS detalle, 'ds' AS tipo ";
			$sql .= "FROM desarrollosub d, usuarios u, cliente c, persona p ";
			$sql .= "WHERE d.usuario = u.usuario and u.idusuario = c.idusuario and ";
			$sql .= "c.idpersona = p.idpersona and d.idremate = $idremate and d.estado = 0 ";
			$sql .= "union ";
			$sql .= "SELECT d.idDS AS idds, ";
			$sql .= "concat('A TODOS', ' ', d.detalle) AS detalle, 'ds' AS tipo ";
			$sql .= "FROM desarrollosub d ";
			$sql .= "WHERE d.usuario = 'todos' and d.idremate = $idremate and d.estado = 0 ";
			$sql .= "UNION ";
			$sql .= "SELECT o.idoferta as idds, ";
			$sql .= "CASE o.estado ";
			$sql .= "WHEN 0 THEN ";
			$sql .= "CONCAT('EL CLIENTE ', p.apeynom, ' OFERTO POR $ ', ";
			$sql .= "case cv.tipoprecio ";
			$sql .= "when 1 then monto * pi.promedio * l.cantcabezas ";
			$sql .= "when 2 then monto * l.cantcabezas ";
			$sql .= "when 3 then monto * pi.promedio * l.cantcabezas ";
			$sql .= "when 4 then monto * l.cantcabezas end ";
			$sql .= ") ";
			$sql .= "WHEN 2 THEN ";
			$sql .= "CONCAT('LA OFERTA DE ', p.apeynom, ' FUE SUPERADA - MONTO (', case cv.tipoprecio ";
			$sql .= "when 1 then monto * pi.promedio * l.cantcabezas ";
			$sql .= "when 2 then monto * l.cantcabezas ";
			$sql .= "when 3 then monto * pi.promedio * l.cantcabezas ";
			$sql .= "when 4 then monto * l.cantcabezas end, ')') ";
			$sql .= "WHEN 3 THEN CONCAT('SE ACEPTO LA OFERTA DE ', p.apeynom, ' - MONTO (', case cv.tipoprecio ";
			$sql .= "when 1 then monto * pi.promedio * l.cantcabezas ";
			$sql .= "when 2 then monto * l.cantcabezas ";
			$sql .= "when 3 then monto * pi.promedio * l.cantcabezas ";
			$sql .= "when 4 then monto * l.cantcabezas end, ')') ";
			$sql .= "WHEN 4 THEN CONCAT('SE RECHAZO LA OFERTA DE ', p.apeynom, '- MONTO (', case cv.tipoprecio ";
			$sql .= "when 1 then monto * pi.promedio * l.cantcabezas ";
			$sql .= "when 2 then monto * l.cantcabezas ";
			$sql .= "when 3 then monto * pi.promedio * l.cantcabezas ";
			$sql .= "when 4 then monto * l.cantcabezas end, ')') ";
			$sql .= "WHEN 5 THEN CONCAT('SE CERRO EL LOTE - LA OFERTA GANADORA ES DE ', p.apeynom, ' POR UN MONTO DE $ ', case cv.tipoprecio ";
			$sql .= "when 1 then monto * pi.promedio * l.cantcabezas ";
			$sql .= "when 2 then monto * l.cantcabezas ";
			$sql .= "when 3 then monto * pi.promedio * l.cantcabezas ";
			$sql .= "when 4 then monto * l.cantcabezas end) END AS detalle, 'dh' as tipo ";
			$sql .= "FROM ofertas o, lotes l, hacienda_lote hl, hacienda h, condiciones_vta cv, usuarios u, cliente c, persona p, pesada_inspeccion pi ";
			$sql .= "WHERE o.idlote = l.idlote and l.idlote = hl.idlote and ";
			$sql .= "hl.idhacienda = h.idhacienda and h.idcv = cv.idcv and ";
			$sql .= "o.usuario = u.usuario and u.idusuario = c.idusuario and ";
			$sql .= "c.idpersona = p.idpersona and h.idpi = pi.idpi and l.idremate = $idremate ";
			$sql .= "union ";
			$sql .= "SELECT o.idoferta as idds, ";
			$sql .= "CASE o.estado ";
			$sql .= "WHEN 0 THEN ";
			$sql .= "CONCAT('EN PISTA SE OFERTO POR $ ', case cv.tipoprecio ";
			$sql .= "when 1 then monto * pi.promedio * l.cantcabezas ";
			$sql .= "when 2 then monto * l.cantcabezas ";
			$sql .= "when 3 then monto * pi.promedio * l.cantcabezas ";
			$sql .= "when 4 then monto * l.cantcabezas end) ";
			$sql .= "WHEN 2 THEN ";
			$sql .= "CONCAT('LA OFERTA EN PISTA FUE SUPERADA - MONTO (', case cv.tipoprecio ";
			$sql .= "when 1 then monto * pi.promedio * l.cantcabezas ";
			$sql .= "when 2 then monto * l.cantcabezas ";
			$sql .= "when 3 then monto * pi.promedio * l.cantcabezas ";
			$sql .= "when 4 then monto * l.cantcabezas end, ')') ";
			$sql .= "WHEN 3 THEN CONCAT('SE ACEPTO LA OFERTA DE LA PISTA - MONTO (', case cv.tipoprecio ";
			$sql .= "when 1 then monto * pi.promedio * l.cantcabezas ";
			$sql .= "when 2 then monto * l.cantcabezas ";
			$sql .= "when 3 then monto * pi.promedio * l.cantcabezas ";
			$sql .= "when 4 then monto * l.cantcabezas end, ')') ";
			$sql .= "WHEN 4 THEN CONCAT('SE RECHAZO LA OFERTA DE LA PISTA - MONTO (', case cv.tipoprecio ";
			$sql .= "when 1 then monto * pi.promedio * l.cantcabezas ";
			$sql .= "when 2 then monto * l.cantcabezas ";
			$sql .= "when 3 then monto * pi.promedio * l.cantcabezas ";
			$sql .= "when 4 then monto * l.cantcabezas end, ')') ";
			$sql .= "WHEN 5 THEN CONCAT('SE CERRO EL LOTE - LA OFERTA GANADORA ES EN PISTA POR UN MONTO DE $ ', case cv.tipoprecio ";
			$sql .= "when 1 then monto * pi.promedio * l.cantcabezas ";
			$sql .= "when 2 then monto * l.cantcabezas ";
			$sql .= "when 3 then monto * pi.promedio * l.cantcabezas ";
			$sql .= "when 4 then monto * l.cantcabezas end) END AS detalle, 'dh' as tipo ";
			$sql .= "FROM ofertas o, lotes l, hacienda_lote hl, hacienda h, condiciones_vta cv, pesada_inspeccion pi ";
			$sql .= "WHERE o.idlote = l.idlote and l.idlote = hl.idlote and ";
			$sql .= "hl.idhacienda = h.idhacienda and h.idcv = cv.idcv and ";
			$sql .= "o.usuario = 'PISTA' and h.idpi = pi.idpi and l.idremate = $idremate ";
			$sql .= "ORDER BY 1 ASC ";		
			$rs = mysql_query($sql); // or die (mysql_error());

			if(mysql_num_rows($rs) > 0){
				while($row=mysql_fetch_array($rs)){
					$arr[] = array('idDS'=>$row[0],'detalle'=>utf8_encode($row[1]),'tipo'=>$row[2],'success'=>true);
				}
			}else{
				$arr[] = array('success'=>false);
			}
			return array('aaData'=>$arr,'accion'=>1203);
		}
		public function buscarloteinicio(){
			Coneccion::conectar();
			mysql_set_charset('utf8');
			$sql = "SELECT l.idlote, l.cantcabezas, l.estado, l.incremento, l.nrolote, c.descripcion, lo.nombre, pr.nombre, tp.inc1, tp.inc2, tp.inc3, h.idcv, "; 
			$sql .= "CASE h.trazados WHEN 0 THEN 'NO' WHEN 1 THEN 'SI' END AS trazados, "; 
			$sql .= "CASE h.marcaliquida WHEN 0 THEN 'NO' WHEN 1 THEN 'SI' END AS marcaliquida, "; 
			$sql .= "CASE l.tipoentrega WHEN 0 THEN 'INMEDIATA' WHEN 1 THEN 'A TERMINO' END AS tipoentrega, h.idpi, "; 
			$sql .= "CONCAT(u.nombre , ' ', u.apellido) AS evaluador, l.precioinicio, tp.descripcion AS tipoprecio, l.idtp, cv.plazo ";
			$sql .= "FROM lotes l, hacienda h, hacienda_lote hl, localidad lo, provincias pr, categoria c, usuarios u, tipoprecio tp, establecimiento e, condiciones_vta cv "; 
			$sql .= "WHERE (l.estado = 1 or l.estado = 2) and hl.idlote = l.idlote and hl.idhacienda = h.idhacienda and h.idestablecimiento = e.idestablecimiento and "; 
			$sql .= "e.idlocalidad = lo.idlocalidad and e.codprov = pr.codprov and h.idcategoria = c.idcategoria and h.idevaluador = u.idusuario and l.idtp = tp.idtp and h.idcv = cv.idcv";
			$rs = mysql_query($sql);
			if(mysql_num_rows($rs)>0){
				while($row=mysql_fetch_array($rs)){
					$sql = "SELECT c.descripcion, hl.cantidad FROM hacienda_lote hl, hacienda h, categoria c ";
					$sql .= "WHERE h.idcategoria = c.idcategoria and h.idhacienda = hl.idhacienda and hl.idlote = ".$row[0];				
					$desc = '';
					$rshl = mysql_query($sql);
					$desc = '(';
					$cant = 0;
					while($fila=mysql_fetch_array($rshl)){
						if($cant==0){
							$desc .= $fila[1].' '.$fila[0];	
						}else{
							$desc .= ' - '.$fila[1].' '.$fila[0];	
						}
						$cant ++; 					
					}
					$desc .= ')';
					$plazo = $row[20];
					$promedio = '';

					$sql = "SELECT promedio FROM pesada_inspeccion WHERE idpi = ".$row[15];
					$rspi = mysql_query($sql);
					if(mysql_num_rows($rspi)>0){
						$filapi = mysql_fetch_row($rspi);
						$promedio = $filapi[0];
					}
					$sql = "SELECT hv.idvideo, hv.video, hl.idlote ";
					$sql .= "FROM hacienda_video hv, hacienda_lote hl ";
					$sql .= "WHERE hv.idhacienda = hl.idhacienda and hl.idlote = ".$row[0];
					$rsvideo = mysql_query($sql);
					if(mysql_num_rows($rsvideo)>0){
						$filavideo = mysql_fetch_row($rsvideo);
					}else{
						$filavideo = array('idvideo'=>0,'video'=>'','idlote'=>0);
					}
					$montooferta = 0;
					$sql = "SELECT idoferta, monto FROM ofertas WHERE idlote = ".$row[0].' ORDER BY 1 DESC';
					$rsofertas = mysql_query($sql);
					if(mysql_num_rows($rsofertas)>0){
						
						while($fofertas = mysql_fetch_array($rsofertas)){
							$montooferta = $fofertas[1];
							break;
						}
					}
					$arr = array('accion'=>1204,
						'idlote'=>$row[0],
						'cantcabezas'=>$row[1],
						'estado'=>$row[2],
						'success'=>true,
						'incremento'=>$row[3],
						'nrolote'=>$row[4],
						//'categoria'=>$row[5],
						'categoria'=>$desc,
						'localidad'=>$row[6],
						'provincia'=>$row[7],
						'inc1'=>$row[8],
						'inc2'=>$row[9],
						'inc3'=>$row[10],
						'trazados'=>$row[12],
						'marcaliquida'=>$row[13],
						'tipoentrega'=>$row[14],
						'plazo'=>$plazo,
						'tipoprecio'=>$row[18],
						'idtp'=>$row[19],
						'promedio'=>$promedio,
						'evaluador'=>$row[16],
						'estado'=>$row[2],
						'precioinicio'=>$row[17],
						'success'=>true,
						'detalle'=>'',
						'video'=>$filavideo,
						'montooferta'=>$montooferta);
					$_SESSION['sidlote'] = $row[0];
				}
			}else{				
				$arr = array('accion'=>1204,'success'=>false);
			}
			return $arr;
		}
		public function buscarmejorofertainicio(){
			Coneccion::conectar();
			$sql = "SELECT o.usuario, o.monto, o.idoferta, o.idlote, l.incremento, l.precioinicio ";
			$sql .= "FROM ofertas o, lotes l ";
			$sql .= "WHERE o.estado = 3 AND l.idlote = o.idlote and l.estado < 3";
			$rs = mysql_query($sql);
			$arr = array();
			if(mysql_num_rows($rs)>0){
				while($row=mysql_fetch_array($rs)){
					$apeynom = 'PISTA';
					$sql = "SELECT CONCAT(u.nombre,' ',u.apellido) AS apeynom ";
					$sql .= "FROM usuarios u ";
					$sql .= "WHERE u.usuario = '".$row[0]."' and u.perfil = 1 ";
					$sql .= "UNION ";
					$sql .= "SELECT 'PISTA' AS apeynom ";
					$sql .= "FROM usuarios u ";
					$sql .= "WHERE u.usuario = '".$row[0]."' and u.perfil = 4 ";
					$rs0 = mysql_query($sql);
					if(mysql_num_rows($rs0)>0){
						$fusuario = mysql_fetch_row($rs0);
						$apeynom = $fusuario[0];
					}
					$arr['accion'] = 1206;
					$arr['usuario'] = utf8_encode($row[0]);
					$arr['monto'] = $row[1];
					$arr['idoferta'] = $row[2];
					$arr['idlote'] = $row[3];
					$arr['incremento'] = $row[4];
					$arr['precioinicial'] = $row[5];
					$arr['apeynom'] = utf8_encode($apeynom);
					$arr['success'] = true;
				}
			}else{
					$sql = "SELECT idlote, incremento, precioinicio FROM lotes WHERE estado = 1";
					$rs1 = mysql_query($sql);
					if(mysql_num_rows($rs1)>0){
						while($row=mysql_fetch_array($rs1)){
							$arr['accion'] = 1206;
							$arr['usuario'] = '';
							$arr['monto'] = 0;
							$arr['idoferta'] = 0;
							$arr['idlote'] = $row[0];
							$arr['incremento'] = $row[1];
							$arr['precioinicial'] = $row[2];
							$arr['apeynom'] = '';
							$arr['success'] = true;
						}
					}else{
						$arr['accion'] = 1206;
						$arr['usuario'] = '';
						$arr['monto'] = 0;
						$arr['idoferta'] = 0;
						$arr['idlote'] = 0;
						$arr['incremento'] = 0;
						$arr['precioinicial'] = 0;
						$arr['apeynom'] = '';
						$arr['success'] = false;					
					}
			}
			return $arr;
		}
		public function buscarofertapropiainicio(){
			Coneccion::conectar();
			$usuario = $_SESSION['susuario'];
			$sql = "SELECT max(o.idoferta) as idoferta, o.estado, o.idlote, o.monto ";
			$sql .= "FROM ofertas o, lotes l ";
			$sql .= "WHERE l.idlote = o.idlote and l.estado < 3 and l.estado > 0 and o.usuario = '$usuario' ";
			$sql .= "GROUP BY estado, idlote, monto ";
			$sql .= "ORDER BY 1 DESC";
			$rs = mysql_query($sql);
			$arr = array();
			if(mysql_num_rows($rs)>0){
				while($row=mysql_fetch_array($rs)){
					$arr['accion'] = 1205;
					$arr['idoferta'] = $row[0];
					$arr['estado'] = $row[1];
					$arr['idlote'] = $row[2];
					$arr['monto'] = $row[3];
					$arr['success'] = true;
					break;
				}
			}else{
				$arr['accion'] = 1205;
				$arr['success'] = false;					
			}

			return $arr;
		}
		public function realizaroferta($mes){
			Coneccion::conectar();
			$idremate = $_SESSION['sidremate'];
			$idlote = $mes['idlote'];
			$oferente = $_SESSION['susuario'];
			$monto = $mes['monto'];
			$puedeofertar = true;
			$detalle = '';
			$estado = 0;
			$detalleoferta = '';
			$total = 0;
			mysql_query("SET AUTOCOMMIT=0");
			mysql_query("START TRANSACTION");

			$sql = "SELECT c.estado FROM cliente c, usuarios u WHERE c.idusuario = u.idusuario and u.usuario =  '$oferente'";
			
			$rsestado = mysql_query($sql);
			if(mysql_num_rows($rsestado)>0){
				$fila = mysql_fetch_row($rsestado);
				$_SESSION['sestado'] = $fila[0];
			}

			$hora = $this->horaactual();
			if($_SESSION['sestado']==0){	
				$sql = "SELECT monto, estado FROM creditos WHERE usuario = '$oferente'";
				$rs0 = mysql_query($sql);
				if(mysql_num_rows($rs0)>0){
					$fila = mysql_fetch_row($rs0);
					$credito = $fila[0];
					if($fila[1]==2){
						$puedeofertar = false;	
						$detalleoferta = 'CLIENTE BLOQUEADO';						
					}else{
						if($fila[0]==0){
							$puedeofertar = false;	
							$detalle = 'NO POSEE CREDIO O EL CREDITO ES IGUAL A CERO';
							$estado = 2;
						}else{
							$sql = "SELECT distinct estado FROM ofertas WHERE usuario = '$oferente'";
							$rsofertausu = mysql_query($sql);
							if(mysql_num_rows($rsofertausu)>0){
								while($fofertausuario = mysql_fetch_array($rsofertausu)){
									if($fofertausuario[0]==0){
										$puedeofertar = false;	
										$detalleoferta = 'TIENE UNA OFERTA PENDIENTE';
									} 
									if($fofertausuario[0]==3){
										$puedeofertar = false;	
										$detalleoferta = 'TIENE UNA OFERTA GANADORA';
									} 
									break;
								}							
							}
						}
					}
					
					if($puedeofertar){						
						$sql = "SELECT cv.tipoprecio, l.cantcabezas, h.idhacienda FROM condiciones_vta cv, hacienda h, hacienda_lote hl, lotes l ";
						$sql .= "WHERE cv.idcv = h.idcv and h.idhacienda = hl.idhacienda and hl.idlote = l.idlote and ";
						$sql .= "hl.idlote = $idlote and (l.estado = 1 or l.estado = 2)";
						$rs9 = mysql_query($sql);
						if(mysql_num_rows($rs9)>0){
							$filacv = mysql_fetch_row($rs9);
							$tipoprecio = $filacv[0];
							$cantcabezas = $filacv[1];
							$idhacienda = $filacv[2];
							if($tipoprecio==2 || $tipoprecio==3){
								$montototal = $monto * $cantcabezas;
							}
							if($tipoprecio==1){
								$sql = "SELECT promedio FROM pesada_inspeccion pi, hacienda h ";
								$sql .= "WHERE h.idpi = pi.idpi and h.idhacienda = $idhacienda";
								$rs8 = mysql_query($sql);
								if(mysql_num_rows($rs8)>0){
									$filapd = mysql_fetch_row($rs8);
									$total = $filapd[0];
								}
								if($total>0){
									$montototal = $monto * $total * $cantcabezas;
								}
							}
							if($montototal < $credito){					
								$sql = "INSERT INTO ofertas (usuario, monto, montototal, estado, idlote) VALUES ('$oferente',$monto, $montototal, 0,$idlote)";		
								$rs = mysql_query($sql);
								if($rs){
									//$sql = "UPDATE creditos SET monto = monto - $montototal WHERE usuario = '".$user->usuario."'";
									//$rs1 = mysql_query($sql);
									//if($rs1){																			
										$sql = "INSERT INTO desarrollosub (detalle, idremate, hora, estado, usuario) VALUES ('SU OFERTA FUE ENVIADA AL REMATADOR',$idremate, DATE_FORMAT(CURRENT_TIMESTAMP(), '%H:%i:%s') ,0, '$oferente')";
										$rs2 = mysql_query($sql);
										if($rs2){
											$detalle = 'SU OFERTA FUE ENVIADA AL REMATADOR';
											$estado = 0;
										}else{
											$detalle = 'ERROR INSERT DESARROLLOSUB';
											$estado = -1;
										}
									//}else{
									//	$detale = 'ERROR UPDATE CREDITO';
									//	$estado = -1;											
									//}
								}else{
									$detalle = 'ERROR INSERT OFERTA';
									$estado = -1;
								}
							}else{
								$detalle = 'CREDITO INSUFICIENTE';
								$estado = 1;
							}
						
						}
					}else{
						$detalle = $detalleoferta;
						$estado = 9;
					}
				}else{
					$detalle = 'NO POSEE CREDITO PARA OFERTAR';
					$estado = -3;					
				}
			}else{
				$detalle = 'CLIENTE INHABILITADO PARA OFERTAR';
				$estado = -2;
			}
			if($rs &&  $rs2){
				mysql_query("COMMIT");
				$arr = array('accion'=>2001,'success'=>true,'detalle'=>'SU OFERTA FUE ENVIADA AL REMATADOR','estado'=>0,'hora'=>$hora);
			}else{
				 mysql_query("ROLLBACK");
				 $arr = array('accion'=>2001,'success'=>false,'detalle'=>$detalle,'estado'=>$estado,'hora'=>$hora);
			}
			return json_encode($arr);
		}
		public function solicitarcredito(){
			Coneccion::conectar();
			$sql = "UPDATE creditos SET estado = 1 WHERE usuario = '".$_SESSION['susuario']."'";
			$rs = mysql_query($sql);
			if($rs){
				$sql = "INSERT INTO desarrollosub (detalle, idremate, hora, estado, usuario) VALUES ('SU SOLICITUD DE CREDITO FUE ENVIADA AL OPERADOR',".$_SESSION['sidremate'].", DATE_FORMAT(CURRENT_TIMESTAMP(), '%H:%i:%s') ,0, '".$_SESSION['susuario']."')";
				$rs1 = mysql_query($sql);
				if($rs1){					
					$hora = $this->horaactual();
					$arr = array('accion'=>2002,
						'success'=>true,
						'mensaje'=>'SU SOLICITUD DE CREDITO FUE ENVIADA AL OPERADOR',
						'hora'=>$hora,
						'mensajeop'=>'SOLICITUD DE CREDITO DE '.$_SESSION['apellido'].' '.$_SESSION['nombre'].' FUE ENVIADA AL OPERADOR',
						'tipo'=>'ds');
				}
			}else{	
				$arr = array('accion'=>2002,'success'=>false);
			}
			return json_encode($arr);
		}
		public function modificarcredito($mes){
			Coneccion::conectar();
			
			$idremate = $_SESSION['sidremate'];
			if(isset($mes['idcredito']))
				$idcredito = $mes['idcredito'];

			if(isset($mes['param']))
				$idcredito = $mes['param'];
			if(isset($mes['param1']))
				$accion = $mes['param1'];

			$monto = $mes['monto'];

			$hora = $this->horaactual();
			
			$sql = "SELECT usuario FROM creditos WHERE idcredito = $idcredito";
			$rs = mysql_query($sql);
			if(mysql_num_rows($rs)>0){
				$fusuario = mysql_fetch_row($rs);
				$usuario = $fusuario[0];
				$sql = "SELECT apellido, nombre FROM usuarios WHERE usuario = '$usuario'";
				$rs0 = mysql_query($sql);
				if(mysql_num_rows($rs0)>0){
					$fila = mysql_fetch_row($rs0);
					$apellido = $fila[0];
					$nombre = $fila[1];
				}
				if(!isset($accion)){
					$sql = "UPDATE creditos SET monto = $monto, estado = 0 WHERE idcredito = $idcredito";
					$detalle = 'SU CREDITO FUE AUMENTADO A '.$monto;
					$detalleop = 'SE AUMENTO EL CREDITO DE '.$apellido.', '.$nombre.' A '.$monto;
				}
				if(isset($accion)){
					if($accion=='r'){
						$sql = "UPDATE creditos SET estado = 1 WHERE idcredito = $idcredito";
						$detalle = '';
						$detalleop = 'SE BLOQUEO PARA OFERTAR AL CLIENTE '.$apellido.', '.$nombre;						
					}
					if($accion=='s'){
						$sql = "UPDATE creditos SET monto = monto + $monto, estado = 0 WHERE idcredito = $idcredito";
						$detalle = 'SU CREDITO FUE AUMENTADO EN '.$monto;
						$detalleop = 'SE AUMENTO EL CREDITO EN '.$monto.' AL CLIENTE '.$apellido.', '.$nombre;
					}
				}
				$rs1 = mysql_query($sql);
				if($rs1){
					$sql = "INSERT INTO desarrollosub (detalle, idremate, hora, estado, usuario) VALUES (CONCAT('SU CREDITO FUE AUMENTADO A ',$monto), $idremate, DATE_FORMAT(CURRENT_TIMESTAMP(), '%H:%i:%s') ,0, '$usuario')";

					$rs2 = mysql_query($sql);
					if($rs2){
						$arr = array('accion'=>4000,
							'usuario'=>$usuario,
							'success'=>true,
							'detalle'=>$detalle,
							'estado'=>0,
							'hora'=>$hora,
							'mensajeop'=>$detalleop);
					}else{
						$arr = array('accion'=>4000,'success'=>false);
					}
				}
			}else{
				$arr = array('accion'=>4000,'success'=>false);
			}
			return json_encode($arr);
		}
		public function modificarcreditotodos($mes){
			$arr = array('success'=>false);
			$error = false;

			Coneccion::conectar();

			$hora = $this->horaactual();

			$monto = filter_input(INPUT_POST, 'param', FILTER_VALIDATE_FLOAT);
			if($monto===FALSE || is_null($monto)) $error = true;
			
			$idlote = filter_input(INPUT_POST, 'param1', FILTER_VALIDATE_INT);
			if($idlote===FALSE || is_null($idlote)) $error = true;

			if(!$error){
				$sql = "SELECT o.usuario FROM lotes l, ofertas o ";
				$sql .= "WHERE l.idlote = $idlote and l.estado = 1 and ";
				$sql .= "o.idlote = l.idlote";
				$rs = mysql_query($sql);

				while($fusuario = mysql_fetch_array($rs)){
					$usuario = $fusuario[0];
					$sql = "UPDATE creditos SET monto = monto + $monto ";
					$sql .= "WHERE usuario = '$usuario'";
					$rs1 = mysql_query($sql);
					if($rs1){
						$arr = array('success'=>true);
					}
				}				
			}
			return json_encode($arr);
		}
		public function realizarofertaop($mes){
			Coneccion::conectar();
			$idremate = $_SESSION['sidremate'];
			$monto = $mes['monto'];
			$idlote = $_SESSION['sidlote'];
			$oferente = $_SESSION['susuario'];
			$detalle = '';

			mysql_query("SET AUTOCOMMIT=0");
			mysql_query("START TRANSACTION");

			$hora = $this->horaactual();

			$sql = "SELECT idlote, incremento, idtp, cantcabezas FROM lotes WHERE idlote = $idlote AND (estado = 1 OR estado = 2)";
			
			
			$rs0 = mysql_query($sql);
			if(mysql_num_rows($rs0)>0){
				$flote = mysql_fetch_row($rs0);	
				$idtp = $flote[2];
				$cantcabezas = $flote[3];
				$incremento = $flote[1];
				if($idtp==2 || $idtp==3){
					$montototal = $monto * $cantcabezas;
				}
				if($idtp==1){
					$promedio = 0;
					$sql = "SELECT promedio FROM pesada_inspeccion pi, hacienda_lote hl, hacienda h ";
					$sql .="WHERE hl.idlote = $idlote and h.idhacienda = hl.idhacienda and h.idpi = pi.idpi";
					$rs4 = mysql_query($sql);
					if(mysql_num_rows($rs4)>0){
						$fpromedio = mysql_fetch_row($rs4);
						$promedio = $fpromedio[0];
						$montototal = $monto * $cantcabezas * $promedio; 
					}
				}
				$sql = "UPDATE ofertas SET estado = 2 WHERE estado = 3 and idlote = $idlote";
				
				$rs = mysql_query($sql);
				if($rs){
					$sql = "INSERT INTO ofertas (usuario, monto, montototal, estado, idlote) VALUES ('$oferente', $monto, $montototal, 3, $idlote)";
					$rs1 = mysql_query($sql);
					if($rs1){
						$detalle = 'MEJOR OFERTA EN PISTA';				
						$sql = "INSERT INTO desarrollosub (detalle, idremate, estado, usuario) VALUES ('$detalle',$idremate, 0, 'todos')";
						$rs2 = mysql_query($sql);
						if($rs2){
							$sql = "SELECT MAX(o.idoferta) AS idoferta, o.usuario, o.monto, ";
							$sql .= "CONCAT(u.apellido, ', ', u.nombre) as apeynom ";
							$sql .= "FROM ofertas o, usuarios u ";
							$sql .= "WHERE o.idlote = $idlote and o.usuario = u.usuario ";
							$sql .= " GROUP BY usuario, monto ";
							$sql .= " ORDER BY 1 DESC";
							$rs3 = mysql_query($sql);
							if($rs3){
								while($row=mysql_fetch_array($rs3)){
									$arr = array('accion'=>4001,
										'idoferta'=>$row[0],
										'usuario'=>$row[1],
										'apeynom'=>$row[3],										
										'monto'=>$monto,
										'success'=>true,
										'incremento'=>$incremento,
										'detalle'=>$detalle,
										'hora'=>$hora,
										'estado'=>5);
										break;								
								}					
							}else{				
								$arr = array('accion'=>4001,'success'=>false,'estado'=>1);
							}
						}else{
							$arr = array('accion'=>4001,'success'=>false,'estado'=>2);
						}
					}else{
						$arr = array('accion'=>4001,'success'=>false,'estado'=>3);
					}
				}else{
					$arr = array('accion'=>4001,'success'=>false,'estado'=>4);
				}
			}else{
				$arr = array('accion'=>4001,'success'=>false,'estado'=>5);
			}

			if($rs && $rs1 && $rs2){
				mysql_query("COMMIT");
			}else{
				 mysql_query("ROLLBACK");
				 $arr = array('accion'=>4001,'success'=>false,'estado'=>6);
			}

			return json_encode($arr);
		}
		public function cerrarofertaop($mes){
			Coneccion::conectar();
			$idremate = $_SESSION['sidremate'];			
			$oferente = $_SESSION['susuario'];
			$arr = array('success'=>false,'mensaje'=>'Hubo un error');
			
			$idlote = filter_input(INPUT_POST, 'param', FILTER_VALIDATE_INT);
			if(!$idlote || is_null($idlote)) $error = true;

			$monto = filter_input(INPUT_POST, 'param1', FILTER_SANITIZE_NUMBER_FLOAT,FILTER_FLAG_ALLOW_FRACTION);
			if(!$monto || is_null($monto)) $error = true;
		
			if(!$error){
				$detalle = '';

				mysql_query("SET AUTOCOMMIT=0");
				mysql_query("START TRANSACTION");

				$hora = $this->horaactual();

				$sql = "SELECT idlote, incremento, idtp, cantcabezas, nrolote FROM lotes WHERE idlote = $idlote AND estado = 0";
				
				
				$rs0 = mysql_query($sql);
				if(mysql_num_rows($rs0)>0){
					$flote = mysql_fetch_row($rs0);	
					$idtp = $flote[2];
					$cantcabezas = $flote[3];
					$incremento = $flote[1];
					$nrolote = $flote[4];
					if($idtp==2 || $idtp==3){
						$montototal = $monto * $cantcabezas;
					}
					if($idtp==1){
						$promedio = 0;
						$sql = "SELECT promedio FROM pesada_inspeccion pi, hacienda_lote hl, hacienda h ";
						$sql .="WHERE hl.idlote = $idlote and h.idhacienda = hl.idhacienda and h.idpi = pi.idpi";
						$rs4 = mysql_query($sql);
						if(mysql_num_rows($rs4)>0){
							$fpromedio = mysql_fetch_row($rs4);
							$promedio = $fpromedio[0];
							$montototal = $monto * $cantcabezas * $promedio; 
						}
					}
					$sql = "UPDATE ofertas SET estado = 2 WHERE estado = 3 and idlote = $idlote";
					
					$rs = mysql_query($sql);

					$sql = "INSERT INTO ofertas (usuario, monto, montototal, estado, idlote) VALUES ('$oferente', $monto, $montototal, 5, $idlote)";
					$rs1 = mysql_query($sql);

					$sql = "UPDATE lotes SET estado = 3 WHERE idlote = $idlote";
					$rs3 = mysql_query($sql);

					$sql = "UPDATE hacienda_lote SET estado = 3 WHERE idlote = $idlote";
					$rs4 = mysql_query($sql);

					$detalle = 'MEJOR OFERTA EN PISTA - LOTE N '.$nrolote.' CERRADO';				
					$sql = "INSERT INTO desarrollosub (detalle, idremate, estado, usuario) VALUES ('$detalle',$idremate, 0, 'todos')";
					$rs2 = mysql_query($sql);
				}else{
					$arr = array('accion'=>9007,'success'=>false,'estado'=>5);
				}

				if($rs && $rs1 && $rs2 && $rs3 && $rs4){
					mysql_query("COMMIT");
					$arr = array('accion'=>9007,'success'=>true,'monto'=>$monto,
						'detalle'=>$detalle,
						'hora'=>$hora,
						'estado'=>0);
				}else{
					 mysql_query("ROLLBACK");
					 $arr = array('accion'=>9007,'success'=>false,'detalle'=>'HUBO UN ERROR AL OFERTAR Y CERRAR','estado'=>1);
				}
				return json_encode($arr);
				
			}

			return json_encode($arr);
		}
		public function bloquearcliente($mes){
			
			Coneccion::conectar();

			$usuario = $_SESSION['susuario'];
			$idremate = $_SESSION['sidremate'];
			$idcredito = $mes['param'];
			$accion = $mes['param1'];
			$hora = $this->horaactual();
			
			
			$sql = "SELECT idcredito, CONCAT(u.apellido, ', ', u.nombre) AS apeynom, ";
			$sql .= "cr.usuario,  u.idusuario ";
			$sql .= "FROM creditos cr, usuarios u ";
			$sql .= "WHERE idcredito = $idcredito and cr.usuario  = u.usuario";

			$rs0 = mysql_query($sql);
			
			if(mysql_num_rows($rs0)>0){
				$fcliente = mysql_fetch_row($rs0);
				$apeynom = $fcliente[1];
				if($accion=='uh'){
					$sql = "UPDATE creditos SET estado = 2 WHERE idcredito = $idcredito";
					$mensaje = 'FUE BLOQUEADO EL CREDITO A '.utf8_encode($apeynom);
				}
				if($accion=='ud'){
					$sql = "UPDATE creditos SET estado = 0 WHERE idcredito = $idcredito";
					$mensaje = 'FUE DESBLOQUEADO EL CREDITO A '.utf8_encode($apeynom);
				}

				$rs = mysql_query($sql);
				if($rs){
					$sql1 = "INSERT INTO desarrollosub (detalle, idremate, hora, estado, usuario) VALUES ('$mensaje', $idremate, DATE_FORMAT(CURRENT_TIMESTAMP(), '%H:%i:%s') ,0, '$usuario')";
					$rs2 = mysql_query($sql1);
					if($rs2){
						$arr = array('accion'=>4400,
							'usuario'=>$usuario,
							'success'=>true,
							'mensaje'=>$mensaje,
							'estado'=>0,
							'hora'=>$hora);
					}else{
						$arr = array('accion'=>4400,'success'=>false,'sql'=>$sql1);
					}
				}else{
					$arr = array('accion'=>4400,'success'=>false,'sql'=>$sql);
				}
			}			
			return json_encode($arr);
		}
		public function buscarlotesinicio(){
			Coneccion::conectar();
			$idfirma = $_SESSION['sidfirma'];
			$sql = "SELECT distinct l.idlote, l.idremate, l.estado, l.nrolote ";
			$sql .= "FROM lotes l, remate r, hacienda_lote hl ";
			$sql .= "WHERE r.idremate = l.idremate and ";
			$sql .= "r.estado = 1 and l.estado < 4 and ";
			$sql .= "l.idlote = hl.idlote and r.idfirma = $idfirma ORDER BY orden";
			$rs = mysql_query($sql);
			$cantidad = mysql_num_rows($rs);
			$i= 0;
			$html = '';
			if($rs){			
				while($row=mysql_fetch_assoc($rs)){
					$i = $i + 1;
					if($cantidad==$i){
						if($row['estado']==3){
							$html .= '{"accion":"5001","lote":"<input type=\"button\" name=\"rblotes\" id=\"lote'.$row['idlote'].'R'.$row['idremate'].'\" class=\"clslotes\" value=\"Lote '.$row['nrolote'].'\" style=\"width:82px;margin:0 0 0 -10px;background-color:#990000; color:#CCCCCC;\" >"}';
						}else{
							$html .= '{"accion":"5001","lote":"<input type=\"button\" name=\"rblotes\" id=\"lote'.$row['idlote'].'R'.$row['idremate'].'\" class=\"clslotes\" value=\"Lote '.$row['nrolote'].'\">"}';
						}
					}else{
						if($row['estado']==3){
							$html .= '{"accion":"5001","lote":"<input type=\"button\" name=\"rblotes\" id=\"lote'.$row['idlote'].'R'.$row['idremate'].'\" class=\"clslotes\" value=\"Lote '.$row['nrolote'].'\" style=\"width:82px;margin:0 0 0 -10px;background-color:#990000; color:#CCCCCC;\">"},';
						}else{
							$html .= '{"accion":"5001","lote":"<input type=\"button\" name=\"rblotes\" id=\"lote'.$row['idlote'].'R'.$row['idremate'].'\" class=\"clslotes\" value=\"Lote '.$row['nrolote'].'\">"},';
						}
					}
				}
			}else{				
				$html = array('success'=>false);
			}
			$html = '{"aaData":['.$html.']}';
			return $html;
		}
		public function buscarremateabierto(){
			Coneccion::conectar();
			$idfirma =$_SESSION['sidfirma'];		

			$arr = array('success'=>false,'idremate'=>-1);

			$sql = "SELECT idremate, tipo FROM remate ";
			$sql .= "WHERE estado = 1 and publicado = 1 and ";
			$sql .= "idfirma = $idfirma and fecha >= CURDATE()";

			$rs = mysql_query($sql);
			if(mysql_num_rows($rs) > 0){
				$fremate = mysql_fetch_row($rs);
				$_SESSION['sidremate'] = $fremate[0];
				$_SESSION['stiporemate'] = $fremate[1];
				$arr = array('success'=>true);
			}else{
				$arr = array('success'=>false);
				$_SESSION['sidremate']=0;
			}
			
			return json_encode($arr);
		}
		public function buscardatoslote($mes){
			Coneccion::conectar();			
			$idlote = $mes['param'];
			$monto=0;
			$sql = "SELECT l.idlote, l.cantcabezas, l.estado, l.incremento, l.nrolote, c.descripcion, lo.nombre as localidad, pr.nombre as provincia, tp.inc1, tp.inc2, tp.inc3, h.idcv, ";
			$sql .= "CASE h.trazados WHEN 0 THEN 'NO' WHEN 1 THEN 'SI' END AS trazados, "; 
			$sql .= "CASE h.marcaliquida WHEN 0 THEN 'NO' WHEN 1 THEN 'SI' END AS marcaliquida, ";		
			$sql .= "CASE l.tipoentrega WHEN 0 THEN 'INMEDIATA' WHEN 1 THEN 'A TERMINO' END AS tipoentrega, h.idpi, ";
			$sql .= "CONCAT(u.nombre , ' ', u.apellido) AS evaluador, l.precioinicio, tp.descripcion AS tipoprecio, l.idtp, cv.plazo, h.nrocontrato ";
			$sql .= "FROM lotes l, hacienda h, hacienda_lote hl, categoria c, usuarios u, tipoprecio tp, establecimiento e, localidad lo, provincias pr, condiciones_vta cv ";
			$sql .= "WHERE l.idlote = $idlote and hl.idlote = l.idlote and hl.idhacienda = h.idhacienda and ";
			$sql .= "h.idcategoria = c.idcategoria and h.idevaluador = u.idusuario and l.idtp = tp.idtp and ";
			$sql .= "h.idestablecimiento = e.idestablecimiento and e.idlocalidad = lo.idlocalidad and e.codprov = pr.codprov and h.idcv = cv.idcv";

			$rs = mysql_query($sql);
			if(mysql_num_rows($rs)>0){
				while($row=mysql_fetch_array($rs)){
					$sql = "SELECT c.descripcion, hl.cantidad FROM hacienda_lote hl, hacienda h, categoria c ";
					$sql .= "WHERE h.idcategoria = c.idcategoria and h.idhacienda = hl.idhacienda and hl.idlote = ".$row[0];				
					$desc = '';
					$rshl = mysql_query($sql);
					$desc = '(';
					$cant = 0;
					while($fila=mysql_fetch_array($rshl)){
						if($cant==0){
							$desc .= $fila[1].' '.$fila[0];
						}else{
							$desc .= ' - '.$fila[1].' '.$fila[0];
						}
						$cant ++; 					
					}
					$desc .= ')';
					$plazo = $row[20];
					$promedio = '';

					$sql = "SELECT promedio FROM pesada_inspeccion WHERE idpi = ".$row[15];
					$rspi = mysql_query($sql);
					if(mysql_num_rows($rspi)>0){
						$filapi = mysql_fetch_row($rspi);
						$promedio = $filapi[0];
					}
					if($row[2]==3){
						$sql = "SELECT monto FROM ofertas ";
						$sql .= "WHERE idlote = ".$row[0]." and estado = 5";
						$rsoferta = mysql_query($sql);
						if(mysql_num_rows($rsoferta)>0){
							$fofertadl = mysql_fetch_row($rsoferta);
							$monto = $fofertadl[0];
						}
					}
					$arr = array('accion'=>5002,
						'idlote'=>$row[0],
						'cantcabezas'=>$row[1],
						'estado'=>$row[2],
						'success'=>true,
						'incremento'=>$row[3],
						'nrolote'=>$row[4],
						'nrocontrato'=>$row[21],
						//'categoria'=>$row[5],
						'categoria'=>utf8_encode($desc),
						'localidad'=>utf8_encode($row[6]),
						'provincia'=>$row[7],
						'inc1'=>$row[8],
						'inc2'=>$row[9],
						'inc3'=>$row[10],
						'trazados'=>$row[12],
						'marcaliquida'=>$row[13],
						'tipoentrega'=>$row[14],
						'plazo'=>utf8_encode($plazo),
						'tipoprecio'=>utf8_encode($row[18]),
						'idtp'=>$row[19],
						'promedio'=>$promedio,
						'evaluador'=>utf8_encode($row[16]),
						'estado'=>$row[2],
						'precioinicio'=>$row[17],
						'monto'=>$monto);					
				}
			}else{				
				$arr = array('accion'=>5002,'success'=>false);
			}			
			return json_encode($arr);
		}
		public function abrirlote($mes){
			Coneccion::conectar();
			mysql_set_charset('utf8');
			$arrlotes = array();
			$idlote = $mes['param'];
			$idremate = $_SESSION['sidremate'];
			$arrhacienda = array();		
			$sql = "SELECT nrolote FROM lotes WHERE idlote = $idlote";
			$rs11 = mysql_query($sql);
			if(mysql_num_rows($rs11)>0){
				$filalote = mysql_fetch_row($rs11);
				
				$sql = "SELECT idlote FROM lotes WHERE estado = 1 AND idremate = $idremate";		
				$rs1 = mysql_query($sql);
				if(mysql_num_rows($rs1)==0){
					$sql = "SELECT idlote FROM lotes WHERE estado = 3 AND idremate = $idremate and idlote = $idlote";
					$rs0 = mysql_query($sql);
					if(mysql_num_rows($rs0)==0){				
						$sql = "UPDATE lotes SET estado = 1 WHERE idlote = $idlote";
						$rs2 = mysql_query($sql);
						mysql_query("COMMIT");
						if($rs2){
							$detalle = 'SE ABRE EL LOTE N '.$filalote[0];
							$_SESSION['sidlote'] = $idlote;
							$hora = $this->horaactual();
							$sql = "INSERT INTO desarrollosub (detalle, idremate, hora, estado, usuario) VALUES ('$detalle',$idremate, DATE_FORMAT(CURRENT_TIMESTAMP(), '%H:%i:%s') ,0, 'todos')";
							$rs = mysql_query($sql);
							if($rs){
								//$arr = array('success'=>true, 'mensaje'=>'EL LOTE '.$idlote.' SE ABRIO CON EXITO');					
								//$sql = "SELECT idlote, idremate FROM lotes WHERE idremate = $idremate ORDER BY orden";
								//$rs3 = mysql_query($sql);
								//$cantidad = mysql_num_rows($rs3);
								//$i= 0;					
								$sql = "SELECT l.idlote, l.cantcabezas, l.estado, l.incremento, l.nrolote, c.descripcion, lo.nombre, ";
								$sql .= "pr.nombre, tp.inc1, tp.inc2, tp.inc3, h.idcv, ";
								$sql .= "CASE h.trazados WHEN 0 THEN 'NO' WHEN 1 THEN 'SI' END AS trazados, "; 
								$sql .= "CASE h.marcaliquida WHEN 0 THEN 'NO' WHEN 1 THEN 'SI' END AS marcaliquida, ";		
								$sql .= "CASE l.tipoentrega WHEN 0 THEN 'INMEDIATA' WHEN 1 THEN 'A TERMINO' END AS tipoentrega, h.idpi, ";
								$sql .= "CONCAT(u.nombre , ' ', u.apellido) AS evaluador, l.precioinicio, tp.descripcion AS tipoprecio, ";
								$sql .= "l.idtp, cv.plazo, hl.idhacienda, ";
								$sql .= "CONCAT(h.razatipo,' - ',h.pelaje) as detallehl, hl.estado as estadohl ";
								$sql .= "FROM lotes l, hacienda h, hacienda_lote hl, localidad lo, provincias pr, categoria c, usuarios u, tipoprecio tp, condiciones_vta cv, establecimiento e ";
								$sql .= "WHERE l.estado = 1 and hl.idlote = l.idlote and hl.idhacienda = h.idhacienda and h.idestablecimiento = e.idestablecimiento and e.idlocalidad = lo.idlocalidad and ";
								$sql .= "e.codprov = pr.codprov and h.idcategoria = c.idcategoria and h.idevaluador = u.idusuario and l.idtp = tp.idtp and h.idcv = cv.idcv";
								$rs10 = mysql_query($sql);
								if(mysql_num_rows($rs10)>0){
									while($row=mysql_fetch_array($rs10)){
										$sql = "SELECT c.descripcion, hl.cantidad ";
										$sql .= "FROM hacienda_lote hl, hacienda h, categoria c ";
										$sql .= "WHERE h.idcategoria = c.idcategoria and h.idhacienda = hl.idhacienda and hl.idlote = ".$row[0];				
										$desc = '';
										$rshl = mysql_query($sql);
										$desc = '(';
										$cant = 0;
										while($fila=mysql_fetch_array($rshl)){
											if($cant==0){
												$desc .= $fila[1].' '.$fila[0];	
											}else{
												$desc .= ' - '.$fila[1].' '.$fila[0];	
											}
											$cant ++; 					
										}
										$arrlotes[] = array('idhacienda'=>$row[21],'detalle'=>$row[22],'estado'=>$row[23]);
										$desc .= ')';
										$plazo = '';
										$promedio = '';
										$sql = "SELECT promedio FROM pesada_inspeccion WHERE idpi = ".$row[15];
										$rspi = mysql_query($sql);
										if(mysql_num_rows($rspi)>0){
											$filapi = mysql_fetch_row($rspi);
											$promedio = $filapi[0];
										}
										$sql = "SELECT hv.idvideo, hv.video, hl.idlote ";
										$sql .= "FROM hacienda_video hv, hacienda_lote hl ";
										$sql .= "WHERE hv.idhacienda = hl.idhacienda and hl.idlote = ".$row[0];
										$rsvideo = mysql_query($sql);
										if(mysql_num_rows($rsvideo)>0){
											$filavideo = mysql_fetch_row($rsvideo);											
										}else{
											$filavideo = array('idvideo'=>0,'video'=>'','idlote'=>0);
										}
										$arr = array('accion'=>5003,
											'idlote'=>$row[0],
											'cantcabezas'=>$row[1],
											'estado'=>0,
											'success'=>true,
											'incremento'=>$row[3],
											'nrolote'=>$row[4],
											//'categoria'=>$row[5],
											'categoria'=>$desc,
											'localidad'=>$row[6],
											'provincia'=>$row[7],
											'inc1'=>$row[8],
											'inc2'=>$row[9],
											'inc3'=>$row[10],
											'trazados'=>$row[12],
											'marcaliquida'=>$row[13],
											'tipoentrega'=>$row[14],
											'plazo'=>$row[20],
											'tipoprecio'=>$row[18],
											'idtp'=>$row[19],
											'promedio'=>$promedio,
											'evaluador'=>$row[16],
											'estado'=>$row[2],
											'precioinicio'=>$row[17],
											'success'=>true,
											'detalle'=>$detalle,
											'mensajeop'=>'A TODOS '.$detalle,
											'hora'=>$hora,
											'video'=>$filavideo,
											'lotes'=>$arrlotes);
										$_SESSION['sidlote'] = $row[0];
									}
								}else{				
									$arr = array('accion'=>5003,'success'=>false);
								}
								return json_encode($arr);
							}
						}else{
							$arr = array("accion"=>5003,'success'=>false, 'mensaje'=>'ERROR AL ABRIR LOTE');
						}
					}else{
							$arr = array("accion"=>5003,'success'=>false, 'mensaje'=>'EL LOTE YA FUE REMATADO');
					}
				}else{
					$arr = array("accion"=>5003,'success'=>false, 'mensaje'=>'EXISTE UN LOTE ABIERTO');
				}		
			}else{
				$arr = array("accion"=>5003,'success'=>false, 'mensaje'=>'ERROR AL ABRIR LOTE');
			}
			return json_encode($arr);			
		}
		public function pasarlote(){
			Coneccion::conectar();
			$detalle = 'LOTE SIN VENDER';
			$idremate =$_SESSION['sidremate'];
			$idlote = $_SESSION['sidlote'];
			$hora = $this->horaactual();
			
			$sql = "SELECT estado FROM ofertas WHERE idlote = $idlote and estado = 3";
			$rs1 = mysql_query($sql);
			
			if(mysql_num_rows($rs1)==0){
				$sql = "UPDATE lotes SET estado = 0 WHERE idlote = $idlote";
				$rs0 = mysql_query($sql);

				if($rs0){		
					$sql = "INSERT INTO desarrollosub (detalle, idremate, hora, estado, usuario) VALUES ('$detalle',$idremate, DATE_FORMAT(CURRENT_TIMESTAMP(), '%H:%i:%s') ,0, 'todos')";
					$rs = mysql_query($sql);
					if($rs){
						$arr = array('accion'=>5005,'success'=>true,'detalle'=>'LOTE SIN VENDER','mensajeop'=>'A TODOS LOTE SIN VENDER','tipo'=>'ds','hora'=>$hora);
						unset($_SESSION['sidlote']);
					}else{
						$arr = array('accion'=>5005,'success'=>false,'detalle'=>'ERROR','mensajeop'=>'ERROR','tipo'=>'ds','hora'=>$hora);
					}
				}else{
					$arr = array('accion'=>5005,'success'=>false,'detalle'=>'ERROR','mensajeop'=>'ERROR','tipo'=>'ds','hora'=>$hora);
				}
			}else{			
				$arr = array('accion'=>5005,'success'=>false,'detalle'=>'NO PUEDE PASAR EL LOTE PORQUE EXISTE UNA OFERTA ACEPTADA','mensajeop'=>'','tipo'=>'','hora'=>$hora);
			}

			return json_encode($arr);
		}
		public function bajarmartillo($mes){
			Coneccion::conectar();

			$idremate = $_SESSION['sidremate'];
			$idlote = $_SESSION['sidlote'];

			$detalle = 'EL REMATADOR ESTA POR BAJAR EL MARTILLO';
			
			$arr = array('accion'=>5007,'success'=>false,'detalle'=>'','mensajeop'=>'','tipo'=>'');	
			
			$hora = $this->horaactual();

			$sql = "UPDATE lotes SET estado = 2 WHERE idlote = $idlote";
			$rs = mysql_query($sql);
			if($rs){				
				$sql = "INSERT INTO desarrollosub (detalle, idremate, hora, estado, usuario) VALUES ('$detalle',$idremate, DATE_FORMAT(CURRENT_TIMESTAMP(), '%H:%i:%s') ,0, 'todos')";
				$rs1 = mysql_query($sql);
				if($rs1){
					$arr = array('accion'=>5007,'success'=>true,'detalle'=>$detalle,'mensajeop'=>'A TODOS '.$detalle,'tipo'=>'ds','hora'=>$hora);
				}
			}
			return json_encode($arr);
		}
		public function cerrarlote(){
			Coneccion::conectar();
			$arr = array('success'=>false,'detalle'=>'');
			
			$hora = $this->horaactual();

			$idlote = $_SESSION['sidlote'];
			$idremate = $_SESSION['sidremate'];
			$nrolote = '';
			
			mysql_query("SET AUTOCOMMIT=0");
			mysql_query("START TRANSACTION");

			$idoferta = 0;
			$sql1 = "SELECT idoferta, montototal, usuario FROM ofertas WHERE idlote = $idlote and estado = 3";
			$rs12 = mysql_query($sql1);
			if(mysql_num_rows($rs12)>0){
				$foferta = mysql_fetch_row($rs12);
				$idoferta = $foferta[0];
				$montototal = $foferta[1];
				$usuario = $foferta[2];
			}
			if($idoferta > 0){
				$sql0 = "SELECT u.apellido, u.nombre, o.monto, ";
				$sql0 .= "case tp.calculo when 1 then l.cantcabezas * o.monto * pi.promedio when 2 then l.cantcabezas * o.monto end as montototal, ";
				$sql0 .= "l.nrolote, l.cantcabezas, c.idcliente, c.idusuario, h.nrocontrato ";
				$sql0 .= "FROM usuarios u, cliente c, ofertas o, lotes l, hacienda_lote hl, hacienda h, tipoprecio tp, pesada_inspeccion pi ";
				$sql0 .= "WHERE u.usuario =  o.usuario and u.idusuario = c.idusuario and o.idoferta = $idoferta and ";
				$sql0 .= "o.idlote = l.idlote and hl.idlote = l.idlote and hl.idhacienda = h.idhacienda and ";
				$sql0 .= "l.idtp = tp.idtp and h.idpi = pi.idpi ";			
				$sql0 .= "UNION ";
				$sql0 .= "SELECT 'PISTA' AS apellido, '' AS nombre, o.monto, ";
				$sql0 .= "case tp.calculo when 1 then l.cantcabezas * o.monto * pi.promedio when 2 then l.cantcabezas * o.monto end as montototal, ";
				$sql0 .= "l.nrolote, l.cantcabezas, 0, 0, h.nrocontrato ";
				$sql0 .= "FROM ofertas o, lotes l, hacienda_lote hl, hacienda h, tipoprecio tp, pesada_inspeccion pi ";
				$sql0 .= "WHERE o.usuario = '$usuario' and o.idoferta = $idoferta and o.idlote = l.idlote and  ";
				$sql0 .= "hl.idlote = l.idlote and hl.idhacienda = h.idhacienda and ";
				$sql0 .= "l.idtp = tp.idtp and h.idpi = pi.idpi ";
				$rs0 = mysql_query($sql0);			
				if(mysql_num_rows($rs0)>0){				
					
					$fdetalle = mysql_fetch_row($rs0);
					$nrolote = $fdetalle[4].' ['.$fdetalle[8].']';
					$detalle = "LOTE NRO ".$fdetalle[4]." CERRADO\n ";
					$detalle .= "GANADOR: ".utf8_encode($fdetalle[0]).", ".utf8_encode($fdetalle[1])."\n";
					$detalle .= "OFERTA: ".number_format($fdetalle[2], 2, ',', '.')."\n ";
					$detalle .= "TOTAL CABEZAS: ".$fdetalle[5]."\n ";
					$detalle .= "MONTO TOTAL: ".number_format($fdetalle[3], 2, ',', '.');
					//$detalle .= $sql;					

					$idcliente = $fdetalle[6];
					$idusuario = $fdetalle[7];
					

					$sql = "UPDATE lotes SET estado = 3 WHERE idlote = $idlote";
					
					$rs = mysql_query($sql);
					
					$sql = "UPDATE ofertas SET estado = 5 WHERE idoferta = $idoferta AND idlote = $idlote";
					$rs1 = mysql_query($sql);
				
				
					//$sql2 = "UPDATE ofertas SET estado = 2 WHERE idlote = $idlote and (estado < 2)";
					//$rs2 = mysql_query($sql2);	
					//if($rs2){
					if($usuario!='PISTA'){
						$sql2 = "UPDATE creditos SET monto = monto - $montototal WHERE usuario = '$usuario'";
						$rs2 = mysql_query($sql2);
					}							
					$sql = "INSERT INTO desarrollosub (detalle, idremate, hora, estado, usuario) VALUES ('$detalle',$idremate, DATE_FORMAT(CURRENT_TIMESTAMP(), '%H:%i:%s') ,0, 'todos')";						
					$rs3 = mysql_query($sql);

					$sql = "UPDATE desarrollosub SET estado = 1";
					$rs4 = mysql_query($sql);


						//}
									
				}
				//$rs  => pone todos los lotes de la oferta como aceptados estado = 3
				//$rs1 => indica cual es la oferta ganadora estado = 5
				//$rs2 => si no es pista, le resta el credito de la oferta aceptada
				//$rs3 => insert el detalle en el desarrollo de la subasta
				//$rs4 => pone el desarrollo de la subasta para que no se vea estado = 1
				if($usuario!='PISTA'){
					if($rs && $rs1 && $rs2 && $rs3 && $rs4){
						mysql_query("COMMIT");
						unset($_SESSION['sidlote']);
						$arr = array('accion'=>5008,'success'=>true,'detalle'=>$detalle,'mensajeop'=>'A TODOS '.$detalle,'tipo'=>'ds','idusuario'=>$idusuario,'hora'=>$hora,'nrolote'=>$nrolote);
					}else{
						mysql_query("ROLLBACK");
						$arr = array('accion'=>5008,'success'=>false,'estado'=>$sql0);
					}
				}
				if($usuario=='PISTA'){
					if($rs && $rs1 && $rs3 && $rs4){
						mysql_query("COMMIT");
						unset($_SESSION['sidlote']);
						$arr = array('accion'=>5008,'success'=>true,'detalle'=>$detalle,'mensajeop'=>'A TODOS '.$detalle,'tipo'=>'ds','idusuario'=>$idusuario,'hora'=>$hora);
					}else{
						mysql_query("ROLLBACK");
						$arr = array('accion'=>5008,'success'=>false,'estado'=>$idoferta);
					}
				}
			}else{
				$sql = "UPDATE lotes SET estado = 0 WHERE idlote = $idlote";			
				$rs = mysql_query($sql);
				if($rs){
					$detalle = "LOTE PASADO";
					$sql = "INSERT INTO desarrollosub (detalle, idremate, hora, estado, usuario) VALUES ('$detalle',$idremate, DATE_FORMAT(CURRENT_TIMESTAMP(), '%H:%i:%s') ,0, 'todos')";
					$rs2 = mysql_query($sql);
					if($rs2){					
						$arr = array('accion'=>5008,'success'=>true,'detalle'=>$detalle,'mensajeop'=>'A TODOS '.$detalle,'tipo'=>'ds','idusuario'=>0,'hora'=>$hora);
					}
				}
				if($rs && $rs2){
					mysql_query("COMMIT");
				}else{
					 mysql_query("ROLLBACK");
					 $arr = array('accion'=>5008,'success'=>false,'estado'=>$idoferta);
				}
			}

			return json_encode($arr);
		}
		public function reabrirlote($mes){
			Coneccion::conectar();
			$idlote = $mes['param'];
			$idremate = $_SESSION['sidremate'];
			$hora = $this->horaactual();
			$sql = "SELECT idlote FROM lotes WHERE estado = 1 AND idremate = $idremate";		
			$rs1 = mysql_query($sql);
			if(mysql_num_rows($rs1)==0){
				$sql = "UPDATE lotes SET estado = 1 WHERE idlote = $idlote";
				$rs2 = mysql_query($sql);
				if($rs2){
					//ELIMINAR EN TABLA factura, LA OFERTA QUE FUE GANADORA, O SEA
					$sql = "SELECT idoferta FROM ofertas WHERE idlote = $idlote and estado = 5";
					$rs5 = mysql_query($sql);
					if(mysql_num_rows($rs5)>0){
						$foferta = mysql_fetch_row($rs5);
						$idoferta = $foferta[0];
						//$sql = "DELETE FROM factura WHERE idoferta = $idoferta and estado = 0";
						//$rs6 = mysql_query($sql);
						//if($rs6){
							$sql = "UPDATE ofertas SET estado = 3 WHERE idoferta = $idoferta";
							$rs4 = mysql_query($sql);
							if($rs4){
								$sql = "SELECT nrolote FROM lotes WHERE idlote = $idlote";
								$rsnrolote = mysql_query($sql);
								$fnrolote = mysql_fetch_row($rsnrolote);

								$detalle = utf8_encode("SE REABRIO EL LOTE NRO ").$fnrolote[0];
								$sql = "INSERT INTO desarrollosub (detalle, idremate, hora, estado, usuario) VALUES ('$detalle',$idremate, DATE_FORMAT(CURRENT_TIMESTAMP(), '%H:%i:%s') ,0, 'todos')";
								$rs = mysql_query($sql);										
								if($rs){
									$sql = "SELECT idlote, idremate FROM lotes WHERE idremate = $idremate ORDER BY orden";
									$rs3 = mysql_query($sql);		
									$cantidad = mysql_num_rows($rs3);
									$i= 0;
									$html = '';
									if($rs3){
										$sql = "SELECT l.idlote, l.cantcabezas, l.estado, l.incremento, l.nrolote, c.descripcion, lo.nombre, pr.nombre, tp.inc1, tp.inc2, tp.inc3, h.idcv, ";
										$sql .= "CASE h.trazados WHEN 0 THEN 'NO' WHEN 1 THEN 'SI' END AS trazados, "; 
										$sql .= "CASE h.marcaliquida WHEN 0 THEN 'NO' WHEN 1 THEN 'SI' END AS marcaliquida, ";		
										$sql .= "CASE l.tipoentrega WHEN 0 THEN 'INMEDIATA' WHEN 1 THEN 'A TERMINO' END AS tipoentrega, h.idpi, ";
										$sql .= "CONCAT(u.nombre , ' ', u.apellido) AS evaluador, l.precioinicio, tp.descripcion AS tipoprecio, l.idtp ";
										$sql .= "FROM lotes l, hacienda h, hacienda_lote hl, localidad lo, provincias pr, categoria c, usuarios u, tipoprecio tp ";
										$sql .= "WHERE l.estado = 1 and hl.idlote = l.idlote and hl.idhacienda = h.idhacienda and h.idlocalidad = lo.idlocalidad and ";
										$sql .= "h.codprov = pr.codprov and h.idcategoria = c.idcategoria and h.idevaluador = u.idusuario and l.idtp = tp.idtp";
										$rs10 = mysql_query($sql);
										if(mysql_num_rows($rs10)>0){
											while($row=mysql_fetch_array($rs10)){
												$sql = "SELECT c.descripcion, hl.cantidad FROM hacienda_lote hl, hacienda h, categoria c ";
												$sql .= "WHERE h.idcategoria = c.idcategoria and h.idhacienda = hl.idhacienda and hl.idlote = ".$row[0];				
												$desc = '';
												$rshl = mysql_query($sql);
												$desc = '(';
												$cant = 0;
												while($fila=mysql_fetch_array($rshl)){
													if($cant==0){
														$desc .= $fila[1].' '.$fila[0];	
													}else{
														$desc .= ' - '.$fila[1].' '.$fila[0];	
													}
													$cant ++; 					
												}
												$desc .= ')';
												$plazo = '';
												$promedio = '';

												$sql = "SELECT promedio FROM pesada_inspeccion WHERE idpi = ".$row[15];
												$rspi = mysql_query($sql);
												if(mysql_num_rows($rspi)>0){
													$filapi = mysql_fetch_row($rspi);
													$promedio = $filapi[0];
												}
												$sql = "SELECT hv.idvideo, hv.video, hl.idlote ";
												$sql .= "FROM hacienda_video hv, hacienda_lote hl ";
												$sql .= "WHERE hv.idhacienda = hl.idhacienda and hl.idlote = ".$row[0];
												$rsvideo = mysql_query($sql);
												if(mysql_num_rows($rsvideo)>0){
													$filavideo = mysql_fetch_row($rsvideo);											
												}else{
													$filavideo = array('idvideo'=>0,'video'=>'','idlote'=>0);
												}
												$sql = "SELECT o.idoferta, o.monto, u.apellido, u.nombre ";
												$sql .= "FROM ofertas o, usuarios u ";
												$sql .= "WHERE o.idlote = $idlote and o.estado = 3 and u.usuario = o.usuario";
												$rs20 = mysql_query($sql);
												if(mysql_num_rows($rs20)>0){
													$fofertas = mysql_fetch_row($rs20);										
													$arr = array('accion'=>1204,
														'idlote'=>$row[0],
														'cantcabezas'=>$row[1],
														'estado'=>1,
														'success'=>true,
														'incremento'=>$row[3],
														'nrolote'=>$row[4],
														//'categoria'=>$row[5],
														'categoria'=>$desc,
														'localidad'=>$row[6],
														'provincia'=>$row[7],
														'inc1'=>$row[8],
														'inc2'=>$row[9],
														'inc3'=>$row[10],
														'trazados'=>$row[12],
														'marcaliquida'=>$row[13],
														'tipoentrega'=>$row[14],
														'plazo'=>$plazo,
														'tipoprecio'=>$row[18],
														'idtp'=>$row[19],
														'promedio'=>$promedio,
														'evaluador'=>$row[16],
														'estado'=>$row[2],
														'precioinicio'=>$row[17],
														'success'=>true,
														'detalle'=>$detalle,
														'mensajeop'=>'A TODOS '.$detalle,
														'hora'=>$hora,
														'ganador'=>$fofertas[2].', '.$fofertas[3],
														'monto'=>$fofertas[1],
														'idoferta'=>$fofertas[0],
														'video'=>$filavideo);
													$_SESSION['sidlote'] = $row[0];
												}
											}
										}else{				
											$arr = array('accion'=>1204,'success'=>false);
										}								
									}
								}
							}
					}						
					//$html = '{"aaData":['.$arr.']}';
					$html = $arr;
				}else{
					$html = array('accion'=>1204,'success'=>false, 'mensaje'=>'ERROR AL ABRIR LOTE');
				}
			}else{
				$html = array('accion'=>1204,'success'=>false, 'mensaje'=>'EXISTE UN LOTE ABIERTO');
			}		
			return json_encode($html);		
		}
		public function aceptaroferta($mes){
			Coneccion::conectar();
			$arr = array('accion'=>5009,'success'=>false,'detalle'=>'','mensajeop'=>'');
			$hora = $this->horaactual();
			$ofertaaceptada = 0;
			$ofertaaaceptar = 0;
			$usuariosuperado = '';
			$idofertasuperada = 0;
			$apeynomoferente = $mes['param1'];
			$idoferta = $mes['param'];
			$idusuariosuperado = -1;
			$idlote = $_SESSION['sidlote'];

			mysql_query("SET AUTOCOMMIT=0");
			mysql_query("START TRANSACTION");

			$sql1 = "SELECT o.idoferta, o.usuario, o.monto, o.idlote, l.nrolote ";
			$sql1 .= "FROM ofertas o, lotes l  ";
			$sql1 .= "WHERE o.idoferta = $idoferta and o.idlote = l.idlote";
			$rs0 = mysql_query($sql1);
			if(mysql_num_rows($rs0)>0){
				$fila = mysql_fetch_row($rs0);
				$ofertaaaceptar = $fila[2];
				$oferente = $fila[1];
				$monto = $fila[2];
				$idlote = $fila[3];
				$nrolote = $fila[4];

				$sql2 = "SELECT monto, usuario, idoferta FROM ofertas WHERE estado = 3 and idlote = $idlote";
				$rs1 = mysql_query($sql2);
				if(mysql_num_rows($rs1)>0){
					$fila1 = mysql_fetch_row($rs1);
					$ofertaaceptada = $fila1[0];
					$usuariosuperado = $fila1[1];
					$idofertasuperada = $fila1[2];
				}				
				if(($ofertaaceptada < $ofertaaaceptar) || $ofertaaceptada==0){
					if($idofertasuperada>0){
						$sql3 = "UPDATE ofertas SET estado = 2 WHERE idoferta = $idofertasuperada and usuario = '$usuariosuperado'";
						$rs2 = mysql_query($sql3);
					}
					$sql4 = "UPDATE ofertas SET estado = 3 WHERE idoferta = $idoferta and usuario = '$oferente'";
					$rs3 = mysql_query($sql4);
					
					$sql = "UPDATE lotes SET estado = 1 WHERE idlote = $idlote";
					$rs6 = mysql_query($sql);
											
					$idremate = $_SESSION['sidremate'];
					$sql6 = "INSERT INTO desarrollosub (detalle, idremate, hora, estado, usuario) ";
					$sql6 .= "VALUES ('SU OFERTA ES LA MEJOR',$idremate, DATE_FORMAT(CURRENT_TIMESTAMP(), '%H:%i:%s') ,0, '$oferente')";
					$rs5 = mysql_query($sql6);

					if($rs3 && $rs5 && $rs6){
						mysql_query("COMMIT");
						$arr = array('accion'=>5009,
							'idoferta'=>$idoferta,
							'usuario'=>$oferente,
							'monto'=>$monto,
							'success'=>true,
							'hora'=>$hora,
							'estado'=>3,
							'oferente'=>utf8_encode($apeynomoferente),
							'detalle'=>'SU OFERTA ES LA MEJOR',
							'mensajeop'=>'EL REMATADOR ACEPTO LA OFERTA DE '.utf8_encode($apeynomoferente).' DEL LOTE '.$nrolote.' POR UN MONTO DE $'.$ofertaaaceptar,
							'usuariosuperado'=>$usuariosuperado);
					}else{
						mysql_query("ROLLBACK");
						$arr = array('accion'=>5009,'success'=>false,'detalle'=>'LA OFERTA A ACEPTAR ES IGUAL O MENOR A LA OFERTA GANADORA');
					}
				}else{
					$arr = array('accion'=>5009,'success'=>false,'detalle'=>'LA OFERTA A ACEPTAR ES IGUAL O MENOR A LA OFERTA GANADORA');
				}
			}

			return json_encode($arr);
		}
		public function omitiroferta($mes){
			$arr = array('success'=>false);
			$hora = $this->horaactual();

			Coneccion::conectar();

			mysql_query("SET AUTOCOMMIT=0");
			mysql_query("START TRANSACTION");

			$idremate = $_SESSION['sidremate'];
			$idoferta = $mes['param'];

			$sql1 = "SELECT u.idusuario, o.usuario FROM ofertas o, usuarios u WHERE o.usuario = u.usuario and o.idoferta = $idoferta";
			$rs0 = mysql_query($sql1);
			if(mysql_num_rows($rs0)>0){				
				$fusuario = mysql_fetch_row($rs0);
				$idusuario = $fusuario[0];				
				$oferente = $fusuario[1];				
				$sql2 = "UPDATE ofertas SET estado = 1 WHERE idoferta = $idoferta";
				$rs = mysql_query($sql2);
				
				$detalle = 'SU OFERTA FUE SUPERADA';
				$sql3 = "INSERT INTO desarrollosub (detalle, idremate, hora, estado, usuario) VALUES ('$detalle',$idremate, DATE_FORMAT(CURRENT_TIMESTAMP(), '%H:%i:%s') ,0, '$oferente')";
				$rs1 = mysql_query($sql3);
			}
			if($rs && $rs1){
				mysql_query("COMMIT");
				$arr = array('accion'=>5010,'success'=>true,'detalle'=>$detalle,'idusuario'=>$idusuario,'hora'=>$hora,'estado'=>1);				
			}else{
				 mysql_query("ROLLBACK");
			}
			return json_encode($arr);
		}
		public function anularoferta(){
			Coneccion::conectar();
			$arr = array('success'=>false);

			$apeynommax = 'PISTA';
			$apeynom = 'PISTA';

			$hora = $this->horaactual();

			$idlote = $_SESSION['sidlote'];
			$idremate = $_SESSION['sidremate'];

			$sql = "SELECT idoferta FROM ofertas WHERE idlote = $idlote AND estado = 3";
			$rs7 = mysql_query($sql);
			if(mysql_num_rows($rs7)>0){
				$fofertas = mysql_fetch_row($rs7);
				$idoferta = $fofertas[0];

				$sql1 = "UPDATE ofertas SET estado = 4 WHERE idoferta = $idoferta";
				$rs1 = mysql_query($sql1);
				mysql_query("COMMIT");
				if($rs1){
					$sql = "SELECT usuario, idlote FROM ofertas WHERE idoferta = $idoferta";
					
					$rs0 = mysql_query($sql);

					if(mysql_num_rows($rs0)>0){			
						$foferta = mysql_fetch_row($rs0);			
						$idlote = $foferta[1];
						$usuariooferta = $foferta[0];

						$sql = "SELECT CONCAT(nombre, ' ', apellido) as apeynom FROM usuarios ";
						$sql .= "WHERE usuario = '$usuariooferta'";
						$rs4 = mysql_query($sql);
						if(mysql_num_rows($rs4)>0){
							$fusuario = mysql_fetch_row($rs4);
							$apeynom = utf8_encode($fusuario[0]);
						}
						$detalle = "LA OFERTA DE $apeynom FUE ANULADA";

						
						$sql = "SELECT idoferta, MAX(monto), usuario ";
						$sql .= "FROM ofertas o ";
						$sql .= "WHERE idlote = $idlote and estado = 2 ";
						$sql .= "GROUP BY idoferta, usuario ORDER BY idoferta DESC";
						$rs2 = mysql_query($sql);
						if(mysql_num_rows($rs2)>0){
							while($fofertamax=mysql_fetch_row($rs2)){							
								$idofertamax = $fofertamax[0];
								$montomax = $fofertamax[1];
								$usuariomax = $fofertamax[2];

								$sql = "UPDATE ofertas SET estado = 3 WHERE idoferta = $idofertamax";
								$rs3 = mysql_query($sql);
								if($rs3){
									$sql = "SELECT CONCAT(nombre, ' ', apellido) as apeynom FROM usuarios ";
									$sql .= "WHERE usuario = '$usuariomax'";
									$rs5 = mysql_query($sql);
									if(mysql_num_rows($rs5)>0){
										$fusuariomax = mysql_fetch_row($rs5);
										$apeynommax = $fusuariomax[0];									
									}									

									$sql = "INSERT INTO desarrollosub (detalle, idremate, hora, estado, usuario) VALUES ";
									$sql .= "('$detalle',$idremate, DATE_FORMAT(CURRENT_TIMESTAMP(), '%H:%i:%s') ,0, '$usuariomax')";
									$rs5 = mysql_query($sql);
									if($rs5){
										$arr = array('accion'=>5014,
											'success'=>true,
											'idofertamax'=>$idofertamax,
											'apeynommax'=>utf8_encode($apeynommax),
											'usuariomax'=>$usuariomax,
											'usuario'=>$usuariooferta,
											'apeynom'=>utf8_encode($apeynom),
											'monto'=>$montomax,											
											'mensajeop'=>$detalle,
											'mensajecli'=>'SU OFERTA FUE SUPERADA',
											'tipo'=>'dh',
											'idDS'=>$idofertamax,
											'hora'=>$hora);
									}
								}
								break;
							}
						}else{ //CUANDO ES LA PRIMERA OFERTA ANULADA O 
							   //TODAS LAS OFERTAS ESTAN ANULADAS
							$sql = "SELECT 0 AS idoferta, precioinicio as monto, '' as usuario ";
							$sql .= "FROM lotes l ";
							$sql .= "WHERE idlote = $idlote and (estado = 1 OR estado = 2)";
							$rs6 = mysql_query($sql);
							if(mysql_num_rows($rs6)>0){
								$fmonto = mysql_fetch_row($rs6);
								$montomax = $fmonto[1];
																
								$sql = "INSERT INTO desarrollosub (detalle, idremate, hora, estado, usuario) VALUES ";
								$sql .= "('SU OFERTA FUE SUPERADA',$idremate, DATE_FORMAT(CURRENT_TIMESTAMP(), '%H:%i:%s') ,0, '$usuariooferta')";
								$rs6 = mysql_query($sql);
								if($rs6){
									$sql = "UPDATE lotes SET estado = 1 WHERE idlote = $idlote";
									$rs8 = mysql_query($sql);
									if($rs8){
										$arr = array('accion'=>5014,
											'success'=>true,
											'idofertamax'=>0,
											'apeynommax'=>'',
											'usuariomax'=>'',
											'usuario'=>$usuariooferta,
											'apeynom'=>utf8_encode($apeynom),
											'monto'=>$montomax,
											'mensajeop'=>$detalle,
											'mensajecli'=>'SU OFERTA FUE SUPERADA',
											'tipo'=>'dh',
											'idDS'=>0,
											'hora'=>$hora);										
									}
								}
							}
							
						}				
					}
				}
			}
			return json_encode($arr);			
		}
		public function modificarprecioinicio($mes){
			Coneccion::conectar();
			$idlote = $_SESSION['sidlote'];
			$precioinicio = $mes['precioinicio'];
			
			
			$sql = "SELECT idlote FROM ofertas WHERE estado = 3 and idlote = $idlote";
			$rs0 = mysql_query($sql);
			if(mysql_num_rows($rs0)==0){
				$sql = "UPDATE lotes SET precioinicio = $precioinicio WHERE idlote = $idlote";
				$rs = mysql_query($sql);		
				if($rs){					
					$arr = array('accion'=>5011,'success'=>true,'precioinicio'=>$precioinicio,'incremento'=>0,'mensajeop'=>'SE MODIFICO EL PRECIO DE INICIO A '.$precioinicio);
				}
			}else{
				$sql = "SELECT precioinicio FROM lotes WHERE idlote = $idlote";
				$rs1 = mysql_query($sql);
				if(mysql_num_rows($rs1)>0){
					$fprecioinicio = mysql_fetch_row($rs1);
					$arr = array('accion'=>5011,'success'=>false,'precioinicio'=>$fprecioinicio[0],'incremento'=>0,'mensajeop'=>'EXISTE UNA OFERTA ACEPTADA. NO PUEDE MODIFICARSE EL PRECIO INICIAL');
				}else{
					$arr = array('accion'=>5011,'success'=>false,'precioinicio'=>0,'incremento'=>0,'mensajeop'=>'EXISTE UNA OFERTA ACEPTADA. NO PUEDE MODIFICARSE EL PRECIO INICIAL');
				}
			}
			return json_encode($arr);
		}
		public function modificarincremento($mes){
			Coneccion::conectar();
			$arr = array('accion'=>5012,'success'=>false,'incremento'=>0);
			$idlote = $_SESSION['sidlote'];
			$idremate = $_SESSION['sidremate'];
			$incremento = $mes['incremento'];

			$sql = "UPDATE lotes SET incremento = $incremento WHERE idlote = $idlote"; // and idremate = $idremate";
			$rs = mysql_query($sql);		
			if($rs){
				$arr = array('accion'=>5012,'success'=>true,'incremento'=>$incremento,'mensajeop'=>'SE MODIFICO EL INCREMENTO ['.$incremento.']');
			}
			return json_encode($arr);
		}
		public function modificartipoprecio($mes){
			Coneccion::conectar();
			$arr = array('accion'=>5013,'success'=>false,'tipoprecio'=>-1);
			$idlote = $_SESSION['sidlote'];
			$idremate = $_SESSION['sidremate'];
			$tipoprecio = $mes['param'];
			
			$sql = "SELECT idlote FROM ofertas WHERE estado = 3 and idlote = $idlote";
			$rs0 = mysql_query($sql);
			if(mysql_num_rows($rs0)==0){
				$sql = "SELECT descripcion, inc1, inc2, inc3 FROM tipoprecio WHERE idtp = $tipoprecio";
				$rs1 = mysql_query($sql);
				if(mysql_num_rows($rs1)>0){
					$ftp = mysql_fetch_row($rs1);
					$sql = "UPDATE lotes SET idtp = $tipoprecio, ";
					$sql .= "incremento = ".$ftp[3].", ";
					$sql .= "inc1 = ".$ftp[1].", ";
					$sql .= "inc2 = ".$ftp[2].", ";
					$sql .= "inc3 = ".$ftp[3]." ";
					$sql .= "WHERE idlote = $idlote"; // and idremate = $idremate";
					$rs = mysql_query($sql);		
					if($rs){
						$arr = array('accion'=>5013,
							'success'=>true,
							'descripcion'=>$ftp[0],
							'inc1'=>$ftp[1],
							'inc2'=>$ftp[2],
							'inc3'=>$ftp[3],
							'mensajeop'=>'SE MODIFICO EL TIPO DE PRECIO ['.$ftp[0].']');
					}
				}
			}else{
				$sql = "SELECT tp.idtp FROM lotes l, tipoprecio tp WHERE l.idtp = tp.idtp and l.idlote = $idlote";
				$rs2 = mysql_query($sql);
				if(mysql_num_rows($rs2)>0){
					$ftipoprecio = mysql_fetch_row($rs2);					
					$arr = array('accion'=>5013,
						'success'=>false,
						'idtp'=>$ftipoprecio[0],
						'mensajeop'=>'EXISTE UNA OFERTA ACEPTADA. NO PUEDE MODIFICARSE EL TIPO DE PRECIO');	
				}
			}
			return json_encode($arr);
		}
		public function desconectarusuario($user, $mes=''){
			Coneccion::conectar();
			$arr = array('accion'=>1202,'success'=>false,'usuario'=>'');
			$sql = "DELETE FROM uconectados WHERE usuario = '".$user->usuario."'";
			$rs1 = mysql_query($sql);
			$sql = "DELETE FROM uconectadosoa WHERE usuario = '".$user->usuario."'";
			$rs2 = mysql_query($sql);
			if($rs1 && $rs2){
				$arr = array('accion'=>1202,'success'=>true,'usuario'=>$user->usuario);
			}
			return json_encode($arr);
		}
		public function listaremates(){
			Coneccion::conectar();
			$idfirma = $_SESSION['sidfirma'];

			$sql = "SELECT r.idremate, DATE_FORMAT(r.fecha, '%d-%m-%Y'), r.hora, r.estado, SUM(cantcabezas) AS cabezas, ";
			$sql .= "r.concepto, r.titulo, r.tipo, r.numero ";
			$sql .= "FROM remate r, lotes l WHERE r.idremate = l.idremate and r.idfirma = $idfirma and r.estado < 2 ";	
			$sql .= "GROUP BY r.idremate, r.fecha, r.hora, r.estado, r.concepto, r.titulo, r.tipo, r.numero";
			$rs0 = mysql_query($sql);

			if(mysql_num_rows($rs0)>0){
				$fila = mysql_fetch_row($rs0);
			}
			if($fila[0]!=null){
				$sql = "SELECT r.idremate, DATE_FORMAT(r.fecha, '%d-%m-%Y'), r.hora, r.estado, SUM(cantcabezas) AS cabezas, r.concepto, r.titulo, ";
				$sql .= "case r.tipo when 1 then 'INTERNET' when 2 then 'FERIA' when 3 then 'CABAÑA' end AS tipo, r.numero, r.publicado ";
				$sql .= "FROM remate r, lotes l WHERE r.idremate = l.idremate and r.idfirma = $idfirma ";
				$sql .= "GROUP BY r.idremate, r.fecha, r.hora, r.estado, r.concepto, r.titulo, r.tipo, r.numero ";
				$sql .= "UNION ";
				$sql .= "SELECT r.idremate, DATE_FORMAT(r.fecha, '%d-%m-%Y'), r.hora, r.estado, 0 AS cabezas, r.concepto, r.titulo, ";
				$sql .= "case r.tipo when 1 then 'INTERNET' when 2 then 'FERIA' when 3 then 'CABAÑA' end AS tipo, r.numero, r.publicado ";
				$sql .= "FROM remate r WHERE r.idremate NOT IN (SELECT idremate FROM lotes) and r.idfirma = $idfirma ";
				$sql .= "GROUP BY r.idremate, r.fecha, r.hora, r.estado, r.concepto, r.titulo, r.tipo, r.numero ";			
				$sql .= "ORDER BY 1 DESC";
			}else{
				$sql = "SELECT r.idremate, DATE_FORMAT(r.fecha, '%d-%m-%Y'), r.hora, r.estado, 0 AS cabezas, r.concepto, r.titulo, ";
				$sql .= "case r.tipo when 1 then 'INTERNET' when 2 then 'FERIA' when 3 then 'CABAÑA' end AS tipo, r.numero, r.publicado ";
				$sql .= "FROM remate r WHERE r.idremate NOT IN (SELECT idremate FROM lotes) and r.idfirma = $idfirma ";
				$sql .= "ORDER BY 1 DESC";
			}

			$rs = mysql_query($sql);
			$arr2 = array();
			if(mysql_num_rows($rs)>0){
				while($row=mysql_fetch_array($rs)){
					$arr = array();
					$titulo = str_replace('"', '\"', utf8_encode($row[6]));
					$arr['id'] = $row[0];
					$arr['nro'] = utf8_encode($row[8]);
					$arr['titulo'] = $titulo;
					$arr['fecha'] = $row[1];
					$arr['hora'] = utf8_encode($row[2]);
					$arr['cabezas'] = $row[4];
					$arr['tipo'] = $row[7];
					$arr2[] = $arr;
				}
			}
			return $arr2;
		}
		public function habilitarremate($mes){
			Coneccion::conectar();
			$error = false;
			$arr = array('accion'=>10000,'success'=>false, 'mensaje'=>'ERROR');
			$idremate = filter_input(INPUT_POST, 'param', FILTER_VALIDATE_INT);
			if($idremate===FALSE || is_null($idremate)) $error = true;
			$tipo = filter_input(INPUT_POST, 'param1', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
			if($tipo===FALSE || is_null($tipo)) $error = true;

			if(!$error){
				$_SESSION['sidremate'] = $idremate;

				$sql = "SELECT idlote FROM lotes WHERE idremate = $idremate and estado = 1";
				$rs0 = mysql_query($sql);
				if(mysql_num_rows($rs0)==0){
					$sql = "UPDATE remate SET estado = 0 WHERE idremate = $idremate";
					$rs1 = mysql_query($sql);
					if($rs1){
						$tipo = $mes['param1'];
						if($tipo == 'd'){
							$sql = "UPDATE remate SET estado = 0 WHERE estado = 1";
							$rs2 = mysql_query($sql);
							if($rs2){								
								$sql = "UPDATE remate SET estado = 1 WHERE idremate = $idremate";
								$rs = mysql_query($sql);	
								if($rs){
									unset($_SESSION['sidremate']);
									$arr = array('accion'=>10000,'success'=>true, 'mensaje'=>'OK');
								}				
							}
						}else{
							unset($_SESSION['sidremate']);
							$arr = array('accion'=>10000,'success'=>true, 'mensaje'=>'OK');
						}
					}
					
				}else{
					$arr = array('accion'=>10000,'success'=>false, 'mensaje'=>'LOTE REMATANDO');
				}
			}
			return json_encode($arr);
		}
	}	
?>