<?php
$disabledSelects = (!isset($alcance)) ? '' : 'disabled';
?>
<div class="row">
    <div class="col-md-9 col-sm-6 col-xs-12">
        <h1 class="page-header">Agregar Ubicación</h1>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12 text-right">
        <label id="btnRegresar" class="btn btn-success">
            <i class="fa fa fa-reply"></i> Regresar
        </label>  
    </div>
</div>    
<div id="panelNuevaUbicacion" class="panel panel-inverse">            
    <div class="panel-heading"> 
        <h4 class="panel-title">Agregar ubicación y nodos</h4>
    </div>            
    <div class="panel-body">      
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <h4>Generales del Proyecto</h4>                
                <div class="underline m-b-10"></div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div id="errorNuevaUbicacion"></div>
            </div>
        </div>
        <div class="row">                           
            <div class="col-md-4 col-sm-6 col-xs-12">
                <div class="form-group">
                    <label class="f-w-600 f-s-13">Concepto*:</label>
                    <select id="listConceptos" class="form-control" style="width: 100%" data-parsley-required="true" <?php echo $disabledSelects; ?>>
                        <option value="">Selecciona . . .</option>
                        <?php
                        if (!isset($alcance)) {
                            if (isset($conceptos) && !empty($conceptos)) {
                                foreach ($conceptos as $key => $value) {
                                    echo '<option value="' . $value['Id'] . '">' . $value['Nombre'] . '</option>';
                                }
                            }
                        } else {
                            if (isset($conceptos) && !empty($conceptos)) {
                                foreach ($conceptos as $key => $value) {
                                    if ($value['Id'] == $alcance['IdConcepto']) {
                                        echo '<option value="' . $value['Id'] . '" selected>' . $value['Nombre'] . '</option>';
                                    } else {
                                        continue;
                                    }
                                }
                            }
                        }
                        ?>
                    </select>
                </div>
            </div> 
            <div class="col-md-4 col-sm-6 col-xs-12">
                <div class="form-group">
                    <label class="f-w-600 f-s-13">Área*:</label>
                    <select id="listAreas" class="form-control" style="width: 100%" data-parsley-required="true" disabled="">
                        <option value="">Selecciona . . .</option>
                        <?php
                        if (isset($areas) && !empty($areas)) {
                            foreach ($areas as $key => $value) {
                                if ($value['Id'] == $alcance['IdArea']) {
                                    echo '<option value="' . $value['Id'] . '" selected>' . $value['Nombre'] . '</option>';
                                } else {
                                    continue;
                                }
                            }
                        }
                        ?>
                    </select>
                </div>
            </div> 
            <div class="col-md-4 col-sm-6 col-xs-12">
                <div class="form-group">
                    <label class="f-w-600 f-s-13">Ubicación*:</label>
                    <select id="listUbicaciones" class="form-control" style="width: 100%" data-parsley-required="true" disabled="">
                        <option value="">Selecciona . . .</option>
                         <?php
                        if (isset($ubicaciones) && !empty($ubicaciones)) {
                            foreach ($ubicaciones as $key => $value) {
                                if ($value['Id'] == $alcance['IdUbicacion']) {
                                    echo '<option value="' . $value['Id'] . '" selected>' . $value['Nombre'] . '</option>';
                                } else {
                                    continue;
                                }
                            }
                        }
                        ?>
                    </select>
                </div>
            </div> 
        </div>     
        <div id="divFormNodos" style="display: none;"></div>
    </div>            
</div>     
