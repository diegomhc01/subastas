<div id="oferta">
	<div id="ultimaoferta">
		<div class="clsuo">
			<label for="txtuo">Valor Actual</label>
			<input type="text" id="txtuo" name="txtuo" value="" disabled>
		</div>
	</div>
	<?php 
		if($_SESSION['sperfil']==1){
			echo
	'<div id="monto">
		<input type="submit" id="btnmontob" name="btnmontob" value="-">
		<input type="text" id="txtmonto" name="txtmonto" value="">
		<input type="submit" id="btnmontos" name="btnmontos" value="+">
	</div>
			<div id="ofertar">
			<input type="submit" id="btnofertar" name="btnofertar" value="OFERTAR">
			<p id="msgcliente"></p>
			</div>
			<div id="desarrollo_sub"></div>';
		}
	?>
</div>