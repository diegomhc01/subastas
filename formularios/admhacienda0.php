<div id="divhacienda0" class="tab-pane fade">
    <div class="col-xs-12">
        <div class="col-xs-3">
            <div class="form-group" id="festablecimientoid">
                <label class="control-label" for="establecimientoid">Establecimiento</label>
                <span style="color:red;font-size:24px;">*</span>
                <select id="establecimientoid" name="establecimientoid" class="form-control selectpicker">
                    <option value="0" disabled selected>Seleccione un establecimiento</option>                  
                </select>
            </div>                                  
            <div class="form-group" id="fprovinciah">
                <label class="control-label" for="provinciah">Provincia</label>
                <input type="text" id="provinciah" name="provinciah" readonly class="form-control">
            </div>
            <div class="form-group" id="flocalidadh">
                <label class="control-label" for="localidadh">Localidad</label>
                <input type="text" id="localidadh" name="localidadh" readonly class="form-control">
            </div>
            <div class="form-group" id="frenspae">
                <label class="control-label" for="renspae">RENSPA</label>
                <input type="text" id="renspae" name="renspae" readonly class="form-control">
            </div>
        </div>
        <div class="col-xs-3">
            <div class="form-group" id="fcantidad">
                <label class="control-label" for="cantidad">Cabezas</label>
                <span style="color:red;font-size:24px;">*</span>
                <input type="text" name="cantidad" id="cantidad" class="positive-integer form-control">
            </div>
            <div class="form-group" id="fcategoria">
                <label class="control-label" for="categoria">Categor&iacute;a</label>
                <span style="color:red;font-size:24px;">*</span>
                <select id="categoria" name="categoria" class="form-control selectpicker" required>

                <?php
                    include('selectcategoria.php');
                ?>
                </select>
            </div>    
            <div class="form-group" id="frazatipo">
                <label class="control-label" for="razatipo">Raza/Tipo</label>
                <span style="color:red;font-size:24px;">*</span>
                <input type="text" name="razatipo" id="razatipo" class="form-control">
            </div>                        
            <div class="form-group" id="fpelaje">
                <label class="control-label" for="pelaje">Pelaje</label>
                <span style="color:red;font-size:24px;">*</span>
                <input type="text" name="pelaje" id="pelaje" class="form-control">
            </div>
            <div class="form-group">
                <label class="control-label" for="edad" class="positive-integer">Edad</label>
                <input type="text" name="edad" id="edad" class="positive-integer form-control">
            </div>
        </div>
        <div class="col-xs-3">
            <div class="form-group">
                <label class="control-label" for="marcaliquida">Marca L&iacute;quida</label>
                <select id="marcaliquida" name="marcaliquida" class="form-control selectpicker">
                    <option value="0" selected>Sin marca l&iacute;quida</option>
                    <option value="1">Con marca l&iacute;quida</option>
                </select>
            </div>
            <div class="form-group">
                <label class="control-label" for="trazados">Trazados</label>
                <select id="trazados" name="trazados" class="form-control selectpicker">
                    <option value="1" selected>Trazados</option>
                    <option value="0">No Trazados</option>
                </select>
            </div>        
            <div class="form-group">
                <label class="control-label" for="destetados">Destetados</label>
                <select id="destetados" name="destetados" class="form-control selectpicker">
                    <option value="0" selected>NO</option>
                    <option value="1">SI</option>
                </select>
            </div>
            <div class="form-group">
                <label class="control-label" for="alimentacion">Alimentaci&oacute;n</label>
                <select id="alimentacion" name="alimentacion" class="form-control selectpicker">
                    <option value="3" selected>Sin Datos</option>
                    <option value="0" selected>Racionados</option>
                    <option value="1">Suplementados</option>
                    <option value="2">A campo</option>
                </select>
            </div>      
        </div>
        <div class="col-xs-3">
            <div class="form-group">
                <label class="control-label" for="mochos">Mochos</label>         
                <input type="text" name="mochos" id="mochos" class="form-control positive-integer">
            </div>          
            <div class="form-group">
                <label class="control-label" for="descornados">Descornados</label>
                <input type="text" name="descornados" id="descornados" class="form-control positive-integer"> 
            </div>
            <div class="form-group">
                <label class="control-label" for="astados">Astados</label>
                <input type="text" name="astados" id="astados" class="form-control positive-integer">
            </div>
            <div class="form-group">
                <label class="control-label" for="enteros">Enteros</label>
                <input type="text" name="enteros" id="enteros" class="form-control positive-integer">
            </div>
            <div class="form-group">
                <label class="control-label" for="querato">Con querato</label>
                <input type="text" name="querato" id="querato" class="form-control positive-integer">
            </div>
        </div>
        <div class="col-xs-12">
            <div class="form-group">
                <label class="control-label" for="observaciones">Observaciones</label>
                <textarea name="observaciones" id="observaciones" class="form-control"></textarea>   
            </div>
        </div>
    </div>
</div>