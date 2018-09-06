<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">        
        <div class="form-group">
            <label class="f-w-600">Accesorio:</label>
            <input class="form-control" type="text" id="txtNuevoAccesorio" value="" placeholder="Accesorio de Sistema" />
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label class="f-w-600">Sistema:</label>
            <select id="listSistemas" class="form-control" style="width: 100% !important;">
                <option value="">Seleccionar. . .</option>
                <?php
                if (isset($sistemas) && !empty($sistemas)) {
                    foreach ($sistemas as $key => $value) {
                        echo '<option value="' . $value['Id'] . '">' . $value['Nombre'] . '</option>';
                    }
                }
                ?>
            </select>        
        </div>
    </div>
</div>
