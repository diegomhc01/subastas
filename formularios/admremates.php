<div class="col-xs-12">	
	<div class="col-xs-11"></div>
	<div class="col-xs-1">
		<button type="button" name="btnaddremate" id="btnaddremate" class="btn btn-default" 
			data-toggle="modal" data-target="remateModal">Agregar</button>		
	</div>
</div>
<div class="col-xs-12">	
	<table id="dtremates" class="display" cellpadding="0"  width="100%">		
		<tbody></tbody>
	</table>
</div>
<div id="remateModal" class="modal fade  col-xs-12" role="dialog">
    <div class="">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h2 class="modal-title">Datos de Remate</h2>
            </div>
            <div class="modal-body">
				<form role="form" id="formremate">
					<div class="col-xs-6">
						<div class="form-group" id="fnro">
							<label class="control-label" for="nro">Nro Remate</label>
							<span style="color:red;font-size:24px;">*</span>
							<input type="text" class="form-control positive-integer" id="nro" name="nro" required>
						</div>
						<div class="form-group" id="ftiporemate">
					        <label class="control-label" for="tiporemate">Tipo de Remate</label>
					        <span style="color:red;font-size:24px;">*</span>
					        <select id="tiporemate" name="tiporemate" class="form-control selectpicker" required>
								<option value="1">Internet</option>
								<option value="2">Feria</option>
								<option value="3">Caba&ntilde;a</option>
					        </select>
				        </div>
						<div class="form-group" id="ftitulo">
							<label class="control-label" for="titulo">T&iacute;tulo</label>
							<span style="color:red;font-size:24px;">*</span>
							<input type="text" class="form-control" id="titulo" name="titulo" required>
						</div>
					</div>
					<div class="col-xs-6">
						<div class="form-group" id="ffecha">
							<label class="control-label" for="fecha">Fecha</label>
							<span style="color:red;font-size:24px;">*</span>
							<input type="text" class="form-control" id="fecha" name="fecha" required>
						</div>
						<div class="form-group" id="fhora">
							<label class="control-label" for="hora">Hora</label>
							<span style="color:red;font-size:24px;">*</span>
							<input type="text" class="form-control" id="hora" name="hora" required>
						</div>						
						<label class="control-label" for="chkconcepto">Concepto</label>
						<span style="color:red;font-size:24px;">*</span>
						<div class="form-group" id="fconcepto">
							<label class="control-label" for="chkconcepto2">Cr&iacute;a</label>
							<input type="checkbox" name="chkconcepto[]" id="chkconcepto2" value="2" class="regular-checkbox">
							<label class="control-label" for="chkconcepto1">Invernada</label>
							<input type="checkbox" name="chkconcepto[]" id="chkconcepto1" value="1" class="regular-checkbox">
							<label class="control-label" for="chkconcepto3">Gordo</label>
							<input type="checkbox" name="chkconcepto[]" id="chkconcepto3" value="3" class="regular-checkbox">
							<label class="control-label" for="chkconcepto4">Toros</label>
							<input type="checkbox" name="chkconcepto[]" id="chkconcepto4" value="4" class="regular-checkbox">
							<label class="control-label" for="chkconcepto5">Lanares</label>
							<input type="checkbox" name="chkconcepto[]" id="chkconcepto5" value="5" class="regular-checkbox">
							<label class="control-label" for="chkconcepto6">Equinos</label>
							<input type="checkbox" name="chkconcepto[]" id="chkconcepto6" value="6" class="regular-checkbox">
							<label class="control-label" for="chkconcepto7">Cerdos</label>
							<input type="checkbox" name="chkconcepto[]" id="chkconcepto7" value="7" class="regular-checkbox">
							<label class="control-label" for="chkconcepto8">Aves</label>
							<input type="checkbox" name="chkconcepto[]" id="chkconcepto8" value="8" class="regular-checkbox">
						</div>
					</div>
					<div class="col-xs-12">					
						<div class="form-group">						
							<label class="control-label" for="comentarios">Comentarios</label>
							<textarea id="comentarios" name="comentarios" class="form-control"></textarea>
						</div>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<div class="col-xs-12">
	                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
	                <button type="button" class="btn btn-default" id="btnremategrabar">Grabar</button>
	            </div>            
	         </div>
        </div>
    </div>
</div>
<div id="tblloteModal" class="modal fade" role="dialog">
	<div class="">	
		<div class="modal-content">
	        <div class="modal-body">
	        	<div class="col-xs-12">
	        		<div class="col-xs-4"></div>
	        		<div class="col-xs-4"><h3>Datos de Hacienda</h3></div>
	        		<div class="col-xs-4"></div>
	        	</div>
				<table id="dtdatoshacienda"  class="display" cellpadding="0"  width="100%">		
					<tbody></tbody>
				</table>
				<div style="margin-bottom:10px;"></div>
				<div class="col-xs-12">
					<div class="col-xs-3">
						<button type="button" name="btnaddlote" id="btnaddlote" class="btn btn-success btn-xs btn-block">Crear Lote</button>						
					</div>
					<div class="col-xs-6">
					</div>
					<div class="col-xs-3">
						<button type="button" name="btnaddhaciendalote" id="btnaddhaciendalote" class="btn btn-success btn-xs btn-block disabled" disabled="disabled">Agregar Hacienda</button>						
					</div>
				</div>
				<div class="col-xs-12">
					<div class="col-xs-4"></div>
					<div class="col-xs-4"><h3>Datos de Lotes</h3></div>
					<div class="col-xs-4"></div>
				</div>				
				<table id="dtlotes"  class="display" cellpadding="0"  width="100%">		
					<tbody></tbody>
				</table>
			<div class="modal-footer">
				<div class="col-xs-12">
					<div class="col-xs-3">
			        	<button type="button" class="btn btn-default" id="btnreordenar">Reordenar Lotes</button>
			        	<button type="button" class="btn btn-default" id="btnpublicar">Publicar</button>
			        </div>
					<div class="col-xs-3">
			        	<button type="button" class="btn btn-default" id="btnimprimircc">Imprimir Catalogo Cliente</button>
			        </div>
					<div class="col-xs-3">
			        	<button type="button" class="btn btn-default" id="btnimprimircr">Imprimir Catalogo Rematador</button>
			        </div>
			        <div class="col-xs-3">
			        	<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
			        </div>
		        </div>
		    </div>            
		</div>
    </div>		
</div>
<div id="loteModal" class="modal fade" role="dialog" >
    <div class="">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h2 class="modal-title">Datos de Lotes</h2>
            </div>
            <div class="modal-body">
            	<div class="col-xs-12">
					<form role="form" id="formlotes">
						<div class="col-xs-4">
							<div class="form-group" id="fnrolote">
								<label class="control-label" for="nrolote">Nro Lote</label>
								<input type="text" class="form-control positive-integer" id="nrolote" name="nrolote" required>
							</div>
							<div class="form-group" id="ftipoentrega">
								<label class="control-label" for="tipoentrega">Entrega</label>
								<select id="tipoentrega" name="tipoentrega" class="form-control selectpicker">
									<option value="">Seleccionar</option>
									<option value="0">Inmediata</option>
									<option value="1">A t&eacute;rmino</option>
								</select>
							</div>
							<div class="form-group" id="ftipoprecio">
								<label class="control-label" for="tipoprecio">Tipo de Precio</label>
								<select id="tipoprecio" name="tipoprecio" class="form-control selectpicker">';
								<?php 
									include('selecttipoprecio.php');
								?>
								</select>
							</div>
							<div class="form-group">
								<label class="control-label" for="precioinicio">Precio Inicio</label>
								<input type="text" class="form-control decimal-2-places" id="precioinicio" name="precioinicio">
							</div>							
						</div>
						<div id="divtablahl" class="col-xs-8">
							<table id="dtdatoshl" class="display dataTable no-footer" cellpadding="0"  width="100%">
								<thead>
									<tr>
										<th class="sorting_disabled">Vendedor</th>
										<th class="sorting_disabled">Categor&iacute;a</th>
										<th class="sorting_disabled">Cantidad</th>
										<th class="sorting_disabled">Precio</th>
										<th class="sorting_disabled"></th>
										<th class="sorting_disabled"></th>
									</tr>
								</thead>
								<tbody></tbody>
								<tfoot>
									<th></th>
									<th></th>
									<th>Total Lote</th>
									<th><input type="text" class="form-control input-sm" disabled id="cantidad" name="cantidad" style="width:50px;margin-left:-8px;"></th>
									<th style="width:70px;"><button type="button" id="calculartotal" name="calculartotal" class="btn btn-danger btn-sm">Calcular</button> </th>
								</tfoot>
							</table>							
						</div>
						<div class="clsbotonah">
							<button type="button" class="btn btn-default" id="btnagregarhacienda">Agregar Hacienda</button>
						</div>
					</form>
            	</div>
			</div>
			<div class="modal-footer">
                <button type="button" class="btn btn-default" id="btnlotecerrar">Cerrar</button>
                <button type="button" class="btn btn-default" id="btnlotegrabar">Grabar</button>
            </div>            
        </div>
    </div>
</div>								
</div>
<div id="msgmodal" class="modal modal-dialog modal-sm" role="dialog">
	<div class="modal-content">
		<div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h2 class="modal-title" id="titulo">Agregar Lote</h2>
        </div>	
		<div class="modal-body">
			<div class="form-group">
				<p id="mensaje" class="lead"></p>
			</div>			
		</div>
		<div class="modal-footer">
	        <button type="button" class="btn btn-default" id="btnmsgsi">Si</button>
	        <button type="button" class="btn btn-default" data-dismiss="modal" id="btnmsgno">No</button>
	    </div>		
	</div>
</div>
<div id="mailmodal" class="modal modal-dialog" role="dialog">
	<div class="modal-content">
		<div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h2 class="modal-title" id="titulo">Enviar Correos</h2>
        </div>	
		<div class="modal-body">
			<div class="form-group">
				<p id="mensajemail" class="lead"></p>
			</div>			
		</div>
		<div class="modal-footer">
	        <button type="button" class="btn btn-default" id="btnmailsi">Si</button>
	        <button type="button" class="btn btn-default" data-dismiss="modal" id="btnmailno">No</button>
	    </div>		
	</div>
</div>
<div id="mensajeenviomail"></div>
