<div id="oferta_op">
	<div id="ultimaoferta">
		<div class="clsuo_op">
			<label for="txtuo_op">Valor Actual</label>
			<div style="z-index:1;">
				<input type="text" id="txtuo_op" name="txtuo_op">
			</div>
		</div>
	</div>
	<div id="ofertar_op">
		<input type="text" id="txtmonto_op" name="txtmonto_op" value="">
		<input type="submit" id="btnofertar_op" name="btnofertar_op" value="OFERTAR">
		<p id="msgcliente"></p>
		<div id="ofertar_cerrar_op">
			<input type="button" id="btnofertar_cerrar_op" name="btnofertar_cerrar_op" value="OFERTAR Y CERRAR">
		</div>
	</div>
</div>
<div class="col-xs-4"></div>
<div id="cerrarofertarModal" class="modal fade col-xs-4" role="dialog">
    <div class="">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Ofertar y Cerrar Lote</h4>
            </div>
            <div class="modal-body">
            	<label>Nro de Lote</label>
            	<select name="lslotesco" id="lslotesco">
            		<?php 
            			include('formularios/selectlotes.php');
            		 ?>
            	</select><br>
            	<label>Monto de la oferta</label>
            	<input type="text" name="montoofertaco" id="montoofertaco">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-default" id="btngrabarco">Ofertar y Cerrar</button>
            </div>            
        </div>
    </div>
</div>
<div class="col-xs-4"></div>