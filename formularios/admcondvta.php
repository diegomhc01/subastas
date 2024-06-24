<div id="divcondvta"  class="tab-pane fade">
    <div class="col-xs-12">
        <div class="form-group" id="fplazo">
            <label class="control-label" for="plazo">Plazo</label>
            <span style="color:red;font-size:24px;">*</span>
            <input type="text" id="plazo" name="plazo" class="form-control">
        </div>
        <div class="form-group" id="fprecioinicial">
            <label class="control-label" for="precioinicial">Precio Sugerido</label>
            <span style="color:red;font-size:24px;">*</span>
            <input type="text" class="decimal-2-places form-control" id="precioinicial" name="precioinicial">
        </div>
        <div class="form-group" id="ftipoprecio">
            <label class="control-label" for="tipoprecio">Tipo Precio</label>
            <span style="color:red;font-size:24px;">*</span>
            <select id="tipoprecio" name="tipoprecio" class="form-control selectpicker">
                <option value="0" selected>Sin datos</option>
                <option value="1">$/Kg vivo</option>
                <option value="2">$/Cabezas</option>
                <option value="3">$/Lo que pisa</option>
            </select>
        </div>
    </div>
</div>