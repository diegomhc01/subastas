<?php 
    include('../scripts/validar.php');
    unset($_SESSION['sopcion']);
?>
<form id="formregistrar" method="post">
    <div class="col-xs-12">
    	<div class="col-xs-1"></div>
        <h2>Datos de Usuario</h2> 	           								
    	<div class="col-xs-5">
    		<div class="form-group" id="fapellidoc">
                <label class="control-label" for="apellidoc"c>Apellido</label><span style="color:red;font-size:24px;">*</span>
                <input type="text" class="form-control" id="apellidoc" name="apellidoc">
            </div>
    		<div class="form-group" id="fnombrec">
                <label class="control-label" for="nombrec">Nombre</label><span style="color:red;font-size:24px;">*</span>
                <input type="text" class="form-control" id="nombrec" name="nombrec">
            </div>    		
            <div class="form-group"  id="femailc">
                <label class="control-label" for="emailc">Correo Eléctronico</label><span style="color:red;font-size:24px;">*</span>
                <input type="text" class="form-control" id="emailc" name="emailc">
                <div id="erroemail"></div>
            </div>
    	</div>
    	<div class="col-xs-5">
    		<div class="form-group"  id="ftelefonoc">
            	<label class="control-label" for="telefonoc">Tel&eacute;fono</label><span style="color:red;font-size:24px;">*</span>
            	<input type="text" class="form-control" id="telefonoc" name="telefonoc">
        	</div>
            <div class="form-group" id="fclavec">
                <label class="control-label" for="clavec">Clave</label><span style="color:red;font-size:24px;">*</span>
                <input type="password" class="form-control" id="clavec" name="clavec">
            </div>
    		<div class="form-group" id="fclave1c">
            	<label class="control-label" for="clave1c">Repita la clave</label><span style="color:red;font-size:24px;">*</span>
            	<input type="password" class="form-control" id="clave1c" name="clave1c">
        	</div>
    	</div>
    	<div class="col-xs-1"></div>	
    </div>	
    <div class="col-xs-12">
        <button type="button" class="btn btn-primary btn-block" id="btngrabarusuario" name="btngrabarusuario">Registrarse</button>
    </div>
</form>
<div id="errorModal"></div>
<?php
	if(!isset($_SESSION['sperfil'])){
		echo '<script src="js/registrar.js"></script>';
	}
?>