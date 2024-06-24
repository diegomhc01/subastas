<div id="divformpesajei" class="tab-pane fade">
    <div class="col-xs-12">
        <div class="col-xs-6">
            <div class="form-group">
                <label class="control-label" for="forma">Forma</label>
                <select id="forma" name="forma" class="form-control selectpicker"> 
                    <option value="0" selected>Sin Dato</option>
                    <option value="1">Con Balanza</option>
                    <option value="2">Estimada</option>
                </select>
            </div>
            <div class="form-group">
                <label class="control-label" for="hora">Hora</label>
                <input type="text" id="hora" name="hora" class="form-control">
            </div>
            <div class="form-group">
                <label class="control-label" for="desbastei">Desbaste (%)</label>
                <input type="text" id="desbastei" name="desbastei" class="decimal-2-places form-control">
            </div>
        </div>        
        <div class="col-xs-6">
            <div class="form-group" id="fminimo">
                <label class="control-label" for="minimo">M&iacute;nimo (Kg)</label>
                <span style="color:red;font-size:24px;">*</span>
                <input type="text" id="minimo" name="minimo" class="positive-integer form-control">
            </div>
            <div class="form-group" id="fmaximo">
                <label class="control-label" for="maximo">M&aacute;ximo (Kg)</label>
                <span style="color:red;font-size:24px;">*</span>
                <input type="text" id="maximo" name="maximo" class="positive-integer form-control"> 
            </div>
            <div class="form-group" id="fpromedio">
                <label class="control-label" for="promedio">Promedio (Kg)</label>
                <span style="color:red;font-size:24px;">*</span>
                <input type="text" id="promedio" name="promedio" class="positive-integer form-control"> 
            </div>
        </div>
    </div>
</div>    