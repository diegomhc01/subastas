<div class="col-xs-12" id="btnagregarest"></div>
<div class="col-xs-12">
	<table id="testablecimientos" class="display" cellpadding="0"  width="100%">
		<tbody></tbody>
	</table>							
</div>
<div id="establecimientoModal" class="modal fade" role="dialog">
    <div class="">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <h2 class="modal-title">Datos del Establecimiento</h2>
            <div class="modal-body">
				<div class="col-xs-12">
					<div class="form-group col-xs-2">
				    	<label class="control-label" for="renspav">RENSPA</label>
				    	<span style="color:red;font-size:24px;">*</span>
				    	<input type="text" class="form-control" id="renspav" name="renspav">
					</div>
					<div class="form-group col-xs-2">
				    	<label class="control-label" for="establecimientov">Establecimiento</label>
				    	<span style="color:red;font-size:24px;">*</span>
				    	<input type="text" class="form-control" id="establecimientov" name="establecimientov">
					</div>
					<div class="form-group col-xs-2" id="fprovinciav">
				    	<label class="control-label" for="provinciav">Provincia</label>
				    	<span style="color:red;font-size:24px;">*</span>
				    	<select class="form-control selectpicker" id="provinciav" name="provinciav"></select>
					</div>
					<div class="form-group col-xs-2" id="flocalidadv">
				    	<label class="control-label" for="localidadv">Localidad</label>
				    	<span style="color:red;font-size:24px;">*</span>
				    	<select class="form-control selectpicker" id="localidadv" name="localidadv"></select>
					</div>							
					<div class="form-group col-xs-2">
				    	<label class="control-label" for="latv">Latitud</label>
				    	<input type="text" class="form-control" id="latv" name="latv">
					</div>
					<div class="form-group col-xs-2">
				    	<label class="control-label" for="lonv">Longitud</label>
				    	<input type="text" class="form-control" id="lonv" name="lonv">
					</div>
				</div>
			</div>
	        <div class="modal-footer">
	            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
	            <button type="button" class="btn btn-default" id="btngrabarusuario">Grabar</button>
	        </div> 
        </div>
    </div>
</div>	        