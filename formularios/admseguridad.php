<div class="col-xs-12">	
	<div class="col-xs-11"></div>
	<div class="col-xs-1">		
		<button type="button" name="btnaddusuario" id="btnaddusuario" class="btn btn-default" 
		data-toggle="modal" data-target="seguridadModal">Agregar</button>		
	</div>
</div>
<div class="col-xs-12">	
	<table id="tusuarios" class="display" cellpadding="0"  width="100%">
		<tbody></tbody>
	</table>
</div>
<div id="seguridadModal" class="modal fade" role="dialog">
    <div class="">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <h2 class="modal-title">Datos de Usuario</h2>
            <div class="modal-body">
				<form role="form" id="formusuario">					
					<div class="form-group" id="fusuario">
			            <label class="control-label" for="usuario">Usuario</label>
			            <span style="color:red;font-size:24px;">*</span>
			            <input type="text" class="form-control" id="usuario" name="usuario" required>
			        </div>
					<div class="form-group" id="fapellido">
			            <label class="control-label" for="apellido">Apellido o Razón Social</label>
			            <span style="color:red;font-size:24px;">*</span>
			            <input type="text" class="form-control" id="apellido" name="apellido" required>
			        </div>
					<div class="form-group" id="fnombre">
			            <label class="control-label" for="nombre">Nombre</label>
			            <input type="text" class="form-control" id="nombre" name="nombre" required>
			        </div>
					<div class="form-group" id="fperfil">
			            <label class="control-label" for="perfil">Perfil</label>
			            <span style="color:red;font-size:24px;">*</span>
			            <select id="perfil" name="perfil" class="form-control selectpicker" required>
			            <?php
							include('selectperfil.php');
						?>
			            </select>
			        </div>
					<div id="datoscliente" style="display:none;">	
						<h2>Datos del Cliente</h2>
						<div class="col-xs-6">
							<div class="form-group" id="fcuitv">
				            	<label class="control-label" for="cuitv">CUIT</label>
				            	<span style="color:red;font-size:24px;">*</span>
				            	<input type="text" class="form-control" id="cuitv" name="cuitv">
				        	</div>
							<div class="form-group" id="fcontactov">
				            	<label class="control-label" for="contactov">Contacto</label>
				            	<input type="text" class="form-control" id="contactov" name="contactov">
				        	</div>
				        </div>
				        <div class="col-xs-6">
							<div class="form-group">
				            	<label class="control-label" for="telefonov">Tel&eacute;fono</label>
				            	<input type="text" class="form-control" id="telefonov" name="telefonov">
				        	</div>
							<div class="form-group">
				            	<label class="control-label" for="emailv">Correo Elec</label>
				            	<span style="color:red;font-size:24px;">*</span>
				            	<input type="text" class="form-control" id="emailv" name="emailv">
				        	</div>
						</div>
				    </div>
				</form>
			</div>
	        <div class="modal-footer">
	            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
	            <button type="button" class="btn btn-default" id="btngrabarusuario">Grabar</button>
	        </div>            
        </div>
    </div>
</div>
<div id="establecimientoModal" class="modal fade" role="dialog">
    <div class="">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-title"><h2>Datos del Establecimiento</h2></div>
            <div class="modal-body">            	
				<form role="form" id="formest">	
					<div class="col-xs-12">				
						<div class="form-group col-xs-2" id="frenspav">
					    	<label class="control-label" for="renspav">RENSPA</label>
					    	<span style="color:red;font-size:24px;">*</span>
					    	<input type="text" class="form-control" id="renspav" name="renspav">
						</div>
						<div class="form-group col-xs-2" id="festablecimiento">
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
				</form>
				<div class="col-xs-12">
					<table id="testablecimientos" class="display" cellpadding="0"  width="100%">
						<tbody></tbody>
					</table>							
				</div>   				
			</div>
	        <div class="modal-footer">
	        	<div class="col-xs-12" style="margin-top: 10px;">
	        	</div>
				<div class="col-xs-12">
					<div class="col-xs-4"></div>
					<div class="col-xs-6">
			            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
			            <button type="button" class="btn btn-default" id="btngrabarest">Grabar</button>
					</div>		            
				</div>	        
	        </div> 
        </div>
    </div>
</div>	
<div id="resetModal" class="modal fade" role="dialog">
    <div class="">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <h2 class="modal-title">Datos de Usuario</h2>
            <div class="modal-body">
				<form role="form" id="formreset">					
					<div class="form-group">
			            <label class="control-label" for="usuarioreset">Usuario</label>
			            <input type="text" class="form-control" id="usuarioreset" name="usuarioreset" disabled>
			        </div>
					<div class="form-group">
			            <label class="control-label" for="apeynomreset">Apellido y Nombre</label>
			            <input type="text" class="form-control" id="apeynomreset" name="apeynomreset" disabled>
			        </div>
					<div class="form-group" id="fclave">
			            <label class="control-label" for="clave">Clave</label>
			            <span style="color:red;font-size:24px;">*</span>
			            <input type="text" class="form-control" id="clave" name="clave" required>
			        </div>
				</form>
			</div>
	        <div class="modal-footer">
	            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
	            <button type="button" class="btn btn-default" id="btngrabarclave">Grabar</button>
	        </div>            
        </div>
    </div>
</div>

