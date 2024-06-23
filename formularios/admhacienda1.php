<?php
	include('scripts/validar.php');
	$mensaje = '';	
	if(isset($_POST['multimediagrabar'])){
		if($_POST['multimediagrabar']=='Grabar'){
		    if(isset($_FILES['archivo'])){
		    	$i = 0;
				foreach ($_FILES["archivo"]["error"] as $key => $error) {					
		    		if($error == UPLOAD_ERR_OK){		    			
		    			$tipo = $_FILES["archivo"]["type"][$key];
						$tamanio = $_FILES["archivo"]["size"][$key];
						if(substr($tipo, strpos($tipo, "jpeg"))=='jpeg') {
							if($tamanio < 10000000){
				       			$tmp_name = $_FILES["archivo"]["tmp_name"][$key];
				        		$name = $_FILES["archivo"]["name"][$key];
				        		$ext = pathinfo($name, PATHINFO_EXTENSION);
				        		$name =  md5((string)getdate().$name).'.'.$ext;
				        		//if(move_uploaded_file($tmp_name, '/home/intertvc/public_html/remates/fotos/'.$name)){	
				        		if(move_uploaded_file($tmp_name, $_SERVER['DOCUMENT_ROOT'].'/intertv/remates/fotos/'.$name)){	
				        			sleep(3);
				        			$_SESSION['simagen'] = $name;
				        			include('scripts/abmimagen.php');
				        			$i++;
				        		}
				        	}else{
				        		$mensaje .= "EL ARCHIVO ES DEMASIADO GRANDE";
				        	}
				        }else{
				        	$mensaje .= 'NO ES DEL TIPO DE ARCHIVO CORRECTO';
				        }
		    		}else{
		    			//$mensaje .= $error;
		    		}
				}
				if(isset($_POST['video'])){
					if($_POST['video']!=''){
						$_SESSION['svideo'] = $_POST['video'];
						include('scripts/abmvideo.php');
					}
				}
			}else{
				$mensaje = 'NO HAY VALORES DE LAS IMAGENES';			}
		}
	}

?>
<div id="divthacienda">
	<div id="chkhacienda">
		<form id="formcheck">
			<label for="chkasignar">Para asignar</label>
			<input type="checkbox" name="chkasignar" id="chkasignar" class="chkhacienda" value="1" checked="checked">
			<label for="chkrematar">Para rematar</label>
			<input type="checkbox" name="chkrematar" id="chkrematar" class="chkhacienda" value="1">
			<label for="chkrematadas">Rematadas</label>
			<input type="checkbox" name="chkrematadas" id="chkrematadas" class="chkhacienda" value="1">
		</form>
	</div>
	<div id="addhacienda">
		<input type="button" name="btnaddhacienda" id="btnaddhacienda" value="Agregar">
	</div>
	<div id="mensaje"><?php echo $mensaje; ?></div>
	<table id="thacienda">
		<thead></thead>
		<tbody></tbody>
	</table>
</div>
<div id="divhacienda" style="display:none;">
	<form id="formhacienda" method="post" name="formhacienda">		
		<div id="divevaluador">
			<div class="divclshacienda">
				<label for="evaluador">Evaluador</label>
				<select id="evaluador" name="evaluador"></select>
			</div><div class="haciendaobliga"><span>*</span></div>
			<div class="divclshacienda">
				<label for="nrocontrato">Nro Contrato</label>
				<input type="text" class="positive-integer" id="nrocontrato" name="nrocontrato">
			</div><div class="haciendaobliga"><span>*</span></div>
		</div>
		<div id="divvendedor">
			<h1>Vendedor</h1>
			<div class="divclshacienda">
				<label for="apeynomv">Apellido y Nombre</label>
				<select id="apeynomv" name="apeynomv"></select>
			</div>
			<div class="haciendaobliga"><span>*</span></div>
			<div class="divclshacienda">
				<label for="txtcuitv">CUIT</label>
				<input type="text" class="positive-integer" id="cuitv" name="txtcuitv">
			</div>
			<div class="divclshacienda">
				<label for="txtcontactov">Contacto</label>
				<input type="text" id="contactov" name="txtcontactov">
			</div>
			<div class="divclshacienda">
				<label for="txttelefonov">Tel&eacute;fono</label>
				<input type="text" id="telefonov" name="txttelefonov">
			</div>
			<div class="divclshacienda">
				<label for="txtemailv">Correo Elec</label>
				<input type="text" id="emailv" name="txtemailv">
			</div>			
		</div>
		<div id="hacienda0">
			<h1>Datos Hacienda</h1>
			<div class="divclshacienda">
				<label for="cantidad">Cabezas</label>
				<input type="text" class="positive-integer" name="cantidad" id="cantidad">
			</div>
			<div class="haciendaobliga"><span>*</span></div>
			<div class="divclshacienda">
			<?php
				include('selectcategoria.php');
			?>
			</div>
			<div class="haciendaobliga"><span>*</span></div>
			<div class="divclshacienda">
				<label for="establecimientoid">Establecimiento</label>
				<select id="establecimientoid" name="establecimientoid">
					<option value="0" disabled selected>Seleccione un establecimiento</option>					
				</select>
			</div>			
			<div class="haciendaobliga"><span>*</span></div>
			<div class="divclshacienda">
				<label for="provinciah">Provincia</label>
				<input type="text" id="provinciah" name="provinciah" readonly>
			</div>
			<div class="divclshacienda">
				<label for="localidadh">Localidad</label>
				<input type="text" id="localidadh" name="localidadh" readonly>
			</div>
			<div class="divclshacienda">
				<label for="txtrenspae">RENSPA</label>
				<input type="text" id="renspae" name="txtrenspae" readonly>
			</div>
			<div class="divclshacienda">
				<label for="latitud">Latitud</label>
				<input type="text" id="latitud" name="latitud" readonly>
			</div>			
			<div class="divclshacienda">
				<label for="longitud">Longitud</label>
				<input type="text" id="longitud" name="longitud" readonly>
			</div>			
			<div class="divclshacienda">
				<label for="marcaliquida">Marca L&iacute;quida</label>
				<select id="marcaliquida" name="marcaliquida">
					<option value="1" selected>Con marca l&iacute;quida</option>
					<option value="0">Sin marca l&iacute;quida</option>
				</select>
			</div>
			<div class="haciendaobliga"><span>*</span></div>
			<div class="divclshacienda">
				<label for="trazados">Trazados</label>
				<select id="trazados" name="trazados">
					<option value="1" selected>Trazados</option>
					<option value="0">No Trazados</option>
				</select>
			</div>
			<div class="haciendaobliga"><span>*</span></div>
			<div class="divclshacienda">
				<label for="razatipo">Raza/Tipo</label>
				<input type="text" name="razatipo" id="razatipo">
			</div>
			<div class="haciendaobliga"><span>*</span></div>
			<div class="divclshacienda">
				<label for="pelaje">Pelaje</label>
				<input type="text" name="pelaje" id="pelaje">
			</div>
			<div class="haciendaobliga"><span>*</span></div>
			<div class="divclshacienda">
				<label for="edad" class="positive-integer">Edad</label>
				<input type="text" name="edad" id="edad">
			</div>
			<div class="divclshacienda">
				<label for="destetados">Destetados</label>
				<select id="destetados" name="destetados">
					<option value="1" selected>SI</option>
					<option value="0">NO</option>
				</select>
			</div>
			<div class="haciendaobliga"><span>*</span></div>
			<div class="divclshacienda">
				<label for="alimentacion">Alimentaci&oacute;n</label>
				<select id="alimentacion" name="alimentacion">
					<option value="0" selected>Racionados</option>
					<option value="1">Suplementados</option>
					<option value="2">A campo</option>
				</select>
			</div>		
			<div class="haciendaobliga"><span>*</span></div>		
			<div class="divclshacienda">
				<label for="mochos" class="positive-integer">Mochos</label>			
				<input type="text" name="mochos" id="mochos">
			</div>			
			<div class="divclshacienda">	
				<label for="descornados" class="positive-integer">Descornados</label>
				<input type="text" name="descornados" id="descornados">	
			</div>
			<div class="divclshacienda">
				<label for="astados" class="positive-integer">Astados</label>
				<input type="text" name="astados" id="astados">
			</div>
			<div class="divclshacienda">
				<label for="enteros" class="positive-integer">Enteros</label>
				<input type="text" name="enteros" id="enteros">
			</div>
			<div class="divclshacienda">
				<label for="querato" class="positive-integer">Con querato</label>
				<input type="text" name="querato" id="querato">
			</div>
			<div class="divclshacienda">
				<label for="observaciones">Observaciones</label>
				<textarea name="observaciones" id="observaciones"></textarea>	
			</div>
		</div>
		<div id="divcondvta">
			<h1>Condiciones de Venta</h1>
			<div class="divclshacienda">
				<label for="plazo">Plazo</label>
				<input type="text" id="plazo" name="plazo">
			</div>
			<div class="haciendaobliga"><span>*</span></div>
			<div class="divclshacienda">
				<label for="precioinicial">Precio Sugerido</label>
				<input type="text" class="decimal-2-places" id="precioinicial" name="precioinicial">
			</div>
			<div class="haciendaobliga"><span>*</span></div>
			<div class="divclshacienda">
				<label for="tipoprecio">Tipo Precio</label>
				<select id="tipoprecio" name="tipoprecio">
					<option value="0" selected>Sin datos</option>
					<option value="1">$/Kg vivo</option>
					<option value="2">$/Cabezas</option>
				</select>
			</div>
			<div class="haciendaobliga"><span>*</span></div>
		</div>
		<div id="divformpesajei">
			<h1>Pesada Inspecci&oacute;n</h1>
			<div class="divclshacienda">
				<label for="forma">Forma</label>
				<select id="forma" name="forma"> 
					<option value="0" selected>Sin Dato</option>
					<option value="1">Con Balanza</option>
					<option value="2">Estimada</option>
				</select>
			</div>
			<div class="divclshacienda">
				<label for="hora">Hora</label>
				<input type="text" id="hora" name="hora">
			</div>
			<div class="divclshacienda">
				<label for="desbastei">Desbaste (%)</label>
				<input type="text" id="desbastei" name="desbastei">
			</div>
			<h3>Peso</h3>
			<div class="divclshacienda">
				<label for="minimo">M&iacute;nimo (Kg)</label>
				<input type="text" id="minimo" name="minimo">
			</div>
			<div class="divclshacienda">
				<label for="maximo">M&aacute;ximo (Kg)</label>
				<input type="text" id="maximo" name="maximo"> 
			</div>
			<div class="divclshacienda">
				<label for="promedio">Promedio (Kg)</label>
				<input type="text" id="promedio" name="promedio"> 
			</div>
		</div>
		<div id="divformpesajed">
			<h1>Pesada Definitiva</h1>
			<div class="divclshacienda">
				<label for="ubicacion">Ubicaci&oacute;n</label>
				<input type="text" id="ubicacion" name="ubicacion">
			</div>
			<h3>Distancia</h3>
			<div class="divclshacienda">
				<label for="arreo">Arreo</label>
				<input type="text" id="arreo" name="arreo">
			</div>
			<div class="divclshacienda">
				<label for="camion">Cami&oacute;n</label>
				<input type="text" id="camion" name="camion">
			</div>
			<div class="haciendaobliga"><span>*</span></div>
			<div class="divclshacienda">
				<label for="total">Total</label>
				<input type="text" id="total" name="total">
			</div>
			<div class="divclshacienda">
				<label for="balanza">Balanza</label>
				<select id="balanza" name="balanza">
					<option value="0" selected>Sin Dato</option>
					<option value="1">P&uacute;blica</option>
					<option value="2">Sociedad Rural</option>
				</select>
			</div>
			<div class="divclshacienda">
				<label for="camions">Cami&oacute;n</label>
				<select id="camions" name="camions">
					<option value="0" selected>Sin Dato</option>
					<option value="1">Arriba</option>
					<option value="2">Abajo</option>
				</select>
			</div>
			<div class="divclshacienda">
				<label for="observacionesp">Observaciones</label>
				<textarea id="observacionesp" name="observacionesp"></textarea>
			</div>			
		</div>		
		<div id="divsanidad">
			<h1>Sanidad</h1>
			<div class="divclshacienda">
				<label for="tuberculosis">Tuberculosis</label>
				<select id="tuberculosis" name="tuberculosis">
					<option value="1">Con garant&iacute;a</option>
					<option value="0">Sin garantía</option>
				</select>
			</div>			
			<div class="divclshacienda">
				<label for="brucelosis">Brucelosis</label>
				<select id="brucelosis" name="brucelosis">
					<option value="1">Con garant&iacute;a</option>
					<option value="0">Sin garantía</option>
				</select>
			</div>			
		</div>	
		<div id="divmadres">
			<h1>Vientres</h1>
			<div class="divclshacienda">
				<label for="preniados">Pre&ntilde;ados</label>
				<input type="text" id="preniados" name="preniados">
			</div>
			<div class="divclshacienda">
				<label for="conservicio">Con Servicio</label>
				<input type="text" id="conservicio" name="conservicio">
			</div>
			<div class="divclshacienda">
				<label for="vacios">Vac&iacute;os</label>
				<input type="text" id="vacios" name="vacios">
			</div>
			<h1>Servicio</h1>
			<div class="divclshacienda">
				<label for="ia">IA</label>
				<input type="text" id="ia" name="ia">
			</div>
			<div class="divclshacienda">
				<label for="natural">Natural</label>
				<input type="text" id="natural" name="natural">
			</div>
			<div class="divclshacienda">
				<label for="serviciodesde">Desde</label>
				<input type="text" id="serviciodesde" name="serviciodesde">
			</div>
			<div class="divclshacienda">
				<label for="serviciohasta">Hasta</label>
				<input type="text" id="serviciohasta" name="serviciohasta">
			</div>
			<div class="divclshacienda">
				<label for="garantia">Garant&iacute;a</label>
				<select id="garantia" name="garantia">
					<option value="0" selected>Sin garant&iacute;a</option>
					<option value="1">Con garant&iacute;a</option>
				</select>
			</div>
			<h3>Toro</h3>
			<div class="divclshacienda">
				<label for="sangretoro">Sangre</label>
				<input type="text" id="sangretoro" name="sangretoro">
			</div>
			<div class="divclshacienda">
				<label for="razatipotoro">Raza/Tipo</label>
				<input type="text" id="razatipotoro" name="razatipotoro">
			</div>
		</div>
		<div id="divevaluacion">
			<h1>Evaluaci&oacute;n</h1>
			<div class="divclshacienda">
				<label for="evacalidad">Calidad</label>
				<select id="evacalidad" name="evacalidad">
					<option value="0" selected>Excelente</option>
					<option value="1">Muy Bueno</option>
					<option value="2">Bueno</option>
					<option value="3">Regular</option>
					<option value="4">Malo</option>
				</select>
			</div>
			<div class="divclshacienda">
				<label for="evaestado">Estado</label>
				<select id="evaestado" name="evaestado">
					<option value="0" selected>Excelente</option>
					<option value="1">Muy Bueno</option>
					<option value="2">Bueno</option>
					<option value="3">Regular</option>
					<option value="4">Malo</option>
				</select>
			</div>
			<div class="divclshacienda">
				<label for="evasanidad">Sanidad</label>
				<select id="evasanidad" name="evasanidad">
					<option value="0" selected>Excelente</option>
					<option value="1">Muy Buena</option>
					<option value="2">Buena</option>
					<option value="3">Regular</option>
					<option value="4">Mala</option>
				</select>
			</div>
			<div class="divclshacienda">
				<label for="evauniformidad">Uniformidad</label>
				<select id="evauniformidad" name="evauniformidad">
					<option value="0" selected>Uniforme</option>
					<option value="1">Poco Uniforme</option>
					<option value="2">Diverso</option>
				</select>
			</div>
			<div class="divclshacienda">
				<label for="evaobservaciones">Observaciones</label>
				<textarea id="evaobservaciones" name="evaobservaciones"></textarea>
			</div>
		</div>
		<div id="btnhacienda">
			<input type="button" id="btngrabarhacienda" name="btngrabarhacienda" value="Grabar" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only">
			<input type="button" id="btncancelarhacienda" name="btncancelarhacienda" value="Cancelar" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only">
		</div>
	</form>
</div>
<div id="divmultimedia" style="display:none;">
	<form enctype="multipart/form-data" action="" method="POST" name="formularioi" id="formularioi" class="formularioimg">
		<div class="multimedia">
			<input type="file" id="archivo1" name="archivo[]">
		</div>
		<div class="multimedia">
			<input type="file" id="archivo1" name="archivo[]">
		</div>
		<div class="multimedia">
			<input type="file" id="archivo3" name="archivo[]">
		</div>
		<div class="multimedia">
			<input type="file" id="archivo4" name="archivo[]">
		</div>
		<div class="multimedia">
			<label for="video">Video</label>
			<input type="text" id="video" name="video">
		</div>
		<div id="fotosvideo">
			<div id="imagen1"><img width="190" height="190" class="fotosvideoimg"></div>
			<div id="imagen2"><img width="190" height="190" class="fotosvideoimg"></div>
			<div id="imagen3"><img width="190" height="190" class="fotosvideoimg"></div>
			<div id="imagen4"><img width="190" height="190" class="fotosvideoimg"></div>
		</div>
		<div id="mensaje_img">
			<p>Las im&aacute;genes deben ser con extensi&oacute;n jpg</p>
			<p>El tama&ntilde;o m&aacute;ximo de los archivos no pueden superar los 10 MB</p>
			<p>De la direcci&oacute;n de yotube, debe ingresar solo el c&oacute;digo como el que est&aacute; resaltado</p>
			<p>https://www.youtube.com/watch?v=<span>4J7gIlffArM</span></p>
		</div>
		<div id="videoh"></div>
		<div class="messages"></div><br>
	 	<div class="showImage"></div>

		<div id="divmultimediasalir">
			<input type="submit" id="multimediagrabar" name="multimediagrabar" value="Grabar">
			<input type="submit" id="multimediasalir" name="multimediasalir" value="Cancelar">
		</div>
	</form>
</div>
<div id="formmsg" class="divmsg" style="display:none"> 
    <form name="frmmsg" method="get" class="clsmsg">
        <label id="msg"></label>
    </form>
</div>
<div id="loteshacienda" style="display:none;">
	<form id="formloteshacienda" name="formloteshacienda"></form>
</div>
<div id="formmsg1" class="divmsg" style="display:none"> 
    <form name="frmmsg1" method="get" class="clsmsg">
        <label id="msg1"></label>
    </form>
</div>