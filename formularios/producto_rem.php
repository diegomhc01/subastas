<div id="producto">
	<table id="dtlotecatalogo" class="display" cellpadding="0"  width="100%">		
		<tbody></tbody>
	</table>	
</div>
<div id="detallelote" style="display:none;">
	<div id="tabs">
		<ul>
			<li><a href="#cajahacienda">Hacienda</a></li>
		    <li><a href="#cajavientre">Vientre</a></li>
		    <li><a href="#cajapi">Pesada Inpecci&oacute;n</a></li>
		    <li><a href="#cajapd">Pesada Definitiva</a></li>
		    <li><a href="#cajaevaluacion">Evaluaci&oacute;n</a></li>
		    <li><a href="#cajacv">Condiciones de Vta</a></li>
		    <li><a href="#cajalocalidad">Ubicaci&oacute;n</a></li>
		    <li><a href="#fotosdetallelote">Fotos</a></li>
		    <li><a href="#videosdetallelote">V&iacute;deos</a></li>
		</ul>	
		<div id="cajadetallelote">		
			<div id="cajahacienda">
	    		<div class="clsdetallelote"><p>N&deg; de Lote</p></div>
				<div class="clsdetallelote"><p>Cabezas</p></div>
				<div class="clsdetallelote"><p>Trazados</p></div>
				<div class="clsdetallelote"><p>Categor&iacute;a</p></div>
				<div class="clsdetallelote"><p>Entrega</p></div>
				<div class="clsdetallelote"><p>Marca L&iacute;quida</p></div>
				<div class="clsdetallelote"><p>Edad</p></div>
				<div class="clsdetallelote"><p>Raza/Tipo</p></div>
				<div class="clsdetallelote"><p>Pelaje</p></div>
				<div class="clsdetallelote"><p>Destetados</p></div>
				<div class="clsdetallelote"><p>Alimentaci&oacute;n</p></div>
				<div class="clsdetallelote"><p>Observaciones</p></div>
				<div class="clsdetallelote"><p>Evaluador</p></div>				
			</div>
			<div id="cajavientre">
				<div class="clsdetallelote"><p>Pre&ntilde;ados</p></div>
				<div class="clsdetallelote"><p>Vac&iacute;os</p></div>
				<div class="clsdetallelote"><p>Con Servicio</p></div><br>
				<h3>Servicio</h3>
				<div class="clsdetallelote"><p>Garant&iacute;a</p></div>
				<div class="clsdetallelote"><p>IA</p></div>
				<div class="clsdetallelote"><p>Natural</p></div>
				<div class="clsdetallelote"><p>Desde</p></div>
				<div class="clsdetallelote"><p>Hasta</p></div><br>
				<h3>Toro</h3>
				<div class="clsdetallelote"><p>Tipo Sangre Toro</p></div>
				<div class="clsdetallelote"><p>Raza/Tipo Toro</p></div>
			</div>
			<div id="cajapi">
				<div class="clsdetallelote"><p>Forma</p></div>
				<div class="clsdetallelote"><p>Hora</p></div>
				<div class="clsdetallelote"><p>Desbaste</p></div>
				<div class="clsdetallelote"><p>Promedio</p></div>
				<div class="clsdetallelote"><p>M&aacute;ximo</p></div>
				<div class="clsdetallelote"><p>M&iacute;nimo</p></div>
			</div>
			<div id="cajapd">
				<div class="clsdetallelote"><p>Ubicaci&oacute;n</p></div>
				<div class="clsdetallelote"><p>Arreo <strong></p></div>
				<div class="clsdetallelote"><p>Cami&oacute;n</p></div>
				<div class="clsdetallelote"><p>Balanza</p></div>
				<div class="clsdetallelote"><p>Lugar Cami&oacute;n</p></div>
				<div class="clsdetallelote"><p>Desbaste</p></div>
				<div class="clsdetallelote"><p>Observaciones</p></div>
			</div>
			<div id="cajaevaluacion">
				<div class="clsdetallelote"><p>Calidad</strong></p></div>
				<div class="clsdetallelote"><p>Estado</p></div>
				<div class="clsdetallelote"><p>Sanidad</p></div>
				<div class="clsdetallelote"><p>Uniformidad</p></div>
				<div class="clsdetallelote"><p>Observaciones</p></div>
			</div>
			<div id="cajacv">
				<div class="clsdetallelote"><p>Plazo</p></div>
				<div class="clsdetallelote"><p>Precio Inicial</p></div>
				<div class="clsdetallelote"><p>Tipo de Precio</p></div>
			</div>
			<div id="cajalocalidad">
				<div class="clsdetallelote"><p>Localidad</p></div>
				<div class="clsdetallelote"><p>Provincia</p></div>
			</div>
		</div>
		<div id="fotosdetallelote">			
			<a id="afoto0" class="fancybox" rel="group" href="">
				<img src="" id="foto0" width="190" height="190" style="margin-top:30px;">
			</a>
			<a id="afoto1" class="fancybox" rel="group" href="">
				<img src="" id="foto1" width="190" height="190" style="margin-top:30px;">
			</a>
			<a id="afoto2" class="fancybox" rel="group" href="">
				<img src="" id="foto2" width="190" height="190" style="margin-top:30px;">
			</a>
			<a id="afoto3" class="fancybox" rel="group" href="">
				<img src="" id="foto3" width="190" height="190" style="margin-top:30px;">
			</a>
		</div>
		<div id="videosdetallelote">			
		<div id="player"></div>
		</div>
	</div>
	<!--
	<input type="image" src="images/2.jpg" id="foto1">
	<input type="image" src="images/3.jpg" id="foto2">
	<input type="image" src="images/4.jpg" id="foto3">
	<input type="image" src="images/5.jpg" id="foto3">
	-->
</div>
<div id="mostrarfoto" style="display:none;">
	<img src="" id="idmostrarfoto">
</div>
<?php
	if(!isset($_SESSION['sperfil'])){
		echo '<script src="js/producto.js"></script>';
	}
?>