<form id="formNuevaTarea" data-parsley-validate="true">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="form-group">
                <label class="f-w-600 f-s-13">Tarea*:</label>
                <input type="hidden" id="IdTarea" value="0" />
                <input type="text" class="form-control" id="txtNombreTarea" data-parsley-required="true" />
            </div> 
        </div>
    </div>
</form>
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label class="f-w-600 f-s-13">Predecesora:</label>
            <select id="listPredecesora" class="form-control" style="width: 100%">
                <option value="">Selecciona . . .</option>
                <?php
                if (isset($predecesoras) && !empty($predecesoras)) {
                    foreach ($predecesoras as $key => $value) {
                        echo '<option data-fin="' . $value['FinG'] . '" value="' . $value['Id'] . '">' . $value['Nombre'] . ' Fin: ' . $value['Fin'] . '</option>';
                    }
                }
                ?>
            </select>            
        </div> 
    </div>
</div>
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">            
            <label class="f-w-600 f-s-13">Fechas:</label>
            <input type="hidden" id="hiddenIni" value="<?php echo $generales['IniG']; ?>" />
            <input type="hidden" id="hiddenFin" value="<?php echo $generales['FinG']; ?>" />
            <div id="rangoFechasTarea" class="input-group input-daterange">                        
                <input id="finitarea" type="text" class="form-control" value="">
                <div class="input-group-addon">hasta</div>
                <input id="ffintarea" type="text" class="form-control" value="">
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label class="f-w-600 f-s-13">Líder:</label>
            <select id="listLiderTarea" class="form-control" style="width: 100%">
                <option value="">Selecciona . . .</option>
                <?php
                if (isset($lideres) && !empty($lideres)) {
                    $lideresP = explode(",", $lideresProyecto['Lideres']);
                    foreach ($lideres as $key => $value) {
                        if (in_array($value['Id'], $lideresP)) {
                            $selected = (count($lideresP) == 1) ? 'selected' : '';
                            echo '<option value="' . $value['Id'] . '" ' . $selected . '>' . $value['Nombre'] . '</option>';
                        }
                    }
                }
                ?>
            </select>            
        </div> 
    </div>
</div>
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label class="f-w-600 f-s-13">Técnicos:</label>
            <select id="listTecnicosTarea" class="form-control" style="width: 100%" multiple="">   
                <?php
                if (isset($tecnicos) && !empty($tecnicos)) {
                    foreach ($tecnicos as $key => $value) {
                        echo '<option value="' . $value['IdUsuario'] . '">' . $value['Nombre'] . '</option>';
                    }
                }
                ?>
            </select>            
        </div> 
    </div>
</div>