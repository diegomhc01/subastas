<div id="subasat_rem">
	<div id="divlote_rem">
		<div id="lote_rem">
			<table id="dtlotes"  class="display" cellpadding="0"  width="100%">	
				<tbody></tbody>
			</table>
		</div>
		<div id="refrescarlotes">
			<input type="button" name="btnrefrescarlotes" id="btnrefrescarlotes" value="Refrescar">
		</div>
	</div>
	<div id="ctrlofertachat">
		<div id="control_rem">
			<div id="botones_rem">
				<input type="submit" name="btnbajarmartillo" id="btnbajarmartillo" value="Bajar Martillo" style="display:none;">
				<input type="submit" name="btncerrarsubasta" id="btncerrarsubasta" value="Cerrar Lote" style="display:none;">
				<input type="submit" name="btnpasarlote" id="btnpasarlote" value="Pasar Lote" style="display:none;">
			</div>
			<div id="precioinicial_rem" style="display:none;">
				<input type="text" name="txtprecioinicial" id="txtprecioinicial">
				<input type="button" name="btnprecioinicial" id="btnprecioinicial" value="Modificar Precio Inicial">
			</div>
			<div id="tipoprecio" style="display:none;">
				<select id="selecttipoprecio" name="selecttipoprecio">
					<?php include('selecttipoprecio.php'); ?>
				</select>
			</div>
			<div id="incremento_rem" style="display:none;">
				<input type="button" name="btninc1" id="btninc1">
				<input type="button" name="btninc2" id="btninc2">
				<input type="button" name="btninc3" id="btninc3">
				<input type="text" name="txtinc" id="txtinc">
				<input type="button" name="btninc" id="btninc" value="Inc">
			</div>			
			<div id="detalle_hacienda_rem">				
			</div>
		</div>
		<div id="listado_historico_rem">
			<table id="dthisotricorem" class="display" cellpadding="0"  width="100%">
				<tbody></tbody>
			</table>
		</div>
		<div id="divusuariosrem">
			<div id="usuarios_rem">
				<ul></ul>
			</div>
			<div id="totalconectados_rem"></div>
		</div>
		<div id="ofertas_rem">
			<table id="dtofertas" class="display" cellpadding="0"  width="100%">
				<tbody></tbody>
			</table>
		</div>
	</div>
</div>
<div id="msganularoferta" style="display:none"></div>