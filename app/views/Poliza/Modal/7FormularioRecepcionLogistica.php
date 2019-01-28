<?php
if (!empty($datosRecepcion['recepcion'])) {
    foreach ($datosRecepcion['recepcion'] as $value) {
        $nombreRecibe = $value['UsuarioRecibe'];
        $fecha = $value['Fecha'];
        $mostrarSelect = "hidden";
        $mostrarInput = "";
        if (!empty($value['Archivos'])) {
            $archivoRecepcion = $value['Archivos'];
            $mostrarInputFile = "";
            $mostrarSelectInput = "hidden";
        } else {
            $archivoRecepcion = "";
            $mostrarInputFile = "hidden";
            $mostrarSelectInput = "hidden";
        }
    }
} else {
    $nombreRecibe = "";
    $fecha = "";
    $archivcos = "";
    $mostrarSelect = "";
    $mostrarInput = "hidden";
    $mostrarInputFile = "hidden";
    $mostrarSelectInput = "";
}

if (!empty($datosRecepcion['recepcionProblema'])) {
    foreach ($datosRecepcion['recepcionProblema'] as $problema) {
        $mostrarNota = "disabled";
        $mostrarSelectInputProblema = "disabled";
        $mostrarBtnProblema = "hidden";
    }
} else if (!empty($datosRecepcion['recepcion']) && empty($datosRecepcion['recepcionProblema'])) {
    $mostrarNota = "disabled";
    $mostrarSelectInputProblema = "disabled";
    $mostrarBtnProblema = "hidden";
}else{
    $mostrarNota = "";
    $mostrarSelectInputProblema = "";
    $mostrarBtnProblema = "";
}
?>
<div id="panelRecepcionLogistica" class="panel panel-inverse">
    <div class="panel-heading">
        <h4 class="panel-title">3) Recepción en Logistica</h4>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <legend>Documentación de recepción en Logistica</legend>
            </div>
        </div>
        <div class="row"></div>
        <ul class="nav nav-pills">
            <li class="active"><a href="#RecepcionLog" data-toggle="tab">Recepcion</a></li>
            <li><a href="#problemasRecepcionLog" data-toggle="tab">Problemas de recepción</a></li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane fade active in" id="RecepcionLog">
                <form id="formRecepcionAlmacen" data-parsley-validate="true">
                    <fieldset>
                        <div class="col-md-4 col-sm-6 col-xs-12">
                            <div class="form-group <?php echo $mostrarSelect ?>">
                                <label class="f-w-600 f-s-13">Usuario que recibe *</label>
                                <input type="text" class="form-control" id="IdUsuarioRecibe" placeholder="<?php echo $usuario; ?>"  data-parsley-required="true" disabled/>
                            </div>
                            <div class="form-group <?php echo $mostrarInput ?>">
                                <label class="f-w-600 f-s-13">Usuario que recibe *</label>
                                <input type="text" class="form-control" placeholder="<?php echo $nombreRecibe ?>" disabled/>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6 col-xs-12">
                            <div class="form-group <?php echo $mostrarSelect ?>">
                                <label class="f-w-600 f-s-13">Fecha de Recepción *</label>
                                <input type="datetime-local" id="fechaRecepcionAlm" value="<?php echo $date = date('Y-m-d\TH:i'); ?>" class="form-control" data-parsley-pattern="^\d\d\d\d-(0?[1-9]|1[0-2])-(0?[1-9]|[12][0-9]|3[01])T(|0[0-9]|1[0-9]|2[0-3]):([0-9]|[0-5][0-9])$" required/>
                            </div>
                            <div class="form-group <?php echo $mostrarInput ?>">
                                <label class="f-w-600 f-s-13">Fecha de Recepción *</label>
                                <input type="text" class="form-control" placeholder="<?php echo $fecha ?>" disabled/>
                            </div>
                        </div>
                        <div class="col-md-9 col-sm-12 col-xs-12">
                            <div class="form-group <?php echo $mostrarSelectInput ?>">
                                <label class="f-w-600 f-s-13">Evidencia de recepción *</label> 
                                <input id="evidenciaRecepcionLab"  name="evidenciaRecepcionAlmacen[]" type="file" multiple />
                            </div>
                            <div class="form-group <?php echo $mostrarInputFile ?>">
                                <label class="f-w-600 f-s-13">Evidencia de recepción *</label>      
                                <div id="" class=" evidencia">
                                    <?php
                                    $archivosEnvia = explode(',', $archivoRecepcion);
                                    foreach ($archivosEnvia as $value) {
                                        ?>
                                        <a class="m-l-5 m-r-5" href="<?php echo $value ?>" data-lightbox="image-<?php echo $value ?>">
                                            <img src="<?php echo $value ?>" style="max-height:115px !important;" />
                                        </a>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div id="errorFormulario"></div>
                            </div>
                        </div>
                        <div class="col-md-12 col-sm-12 col-xs-12 text-center <?php echo $mostrarSelect ?>">
                            <a id="btnGuardarRecepcionLog" class="btn btn-success m-t-10 m-r-10 f-w-600 f-s-13">Guardar Recepción</a>
                        </div>
                    </fieldset>
                </form>
            </div>
            <div class="tab-pane fade" id="problemasRecepcionLog">
                <form id="formProblemaRecepcionAlmacen" data-parsley-validate="true">
                    <div class="row">
                        <div class="col-md-8 col-sm-9 col-xs-12">
                            <div class="form-group">
                                <label class="f-w-600 f-s-13">Nota:</label>
                                <textarea class="form-control" rows="5" id="txtNota" value="" <?php echo $mostrarNota ?>></textarea>                            
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-9 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <label class="f-w-600 f-s-13">Adjuntos:</label>
                                <input id="adjuntosProblemaLog" name="adjuntosTarea[]" type="file" multiple="" <?php echo $mostrarSelectInputProblema ?>/>    
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="col-md-12 col-sm-12 col-xs-12 text-center <?php echo $mostrarBtnProblema ?>">
                            <a id="btnAgregarProblemaAlm" class="btn btn-success m-t-10 m-r-10 f-w-600 f-s-13">Agregar Problema</a>
                        </div>
                    </div>
                </form>
            </div>                
        </div>
    </div>
</div>