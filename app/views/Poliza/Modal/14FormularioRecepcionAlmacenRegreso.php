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
            $mostrarAdjuntos = "disabled";
        } else {
            $archivoRecepcion = "";
            $mostrarInputFile = "hidden";
            $mostrarSelectInput = "";
            $mostrarAdjuntos = "";
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
    $mostrarAdjuntos = "";
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
} else {
    $mostrarNota = "";
    $mostrarSelectInputProblema = "";
    $mostrarBtnProblema = "";
}
?>
<div id="panelRecepcionAlmacen" class="panel panel-inverse">
    <div class="panel-heading">
        <h4 class="panel-title">Recepción en Almacén</h4>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <legend>Documentación de recepción en Almacén para Entrega</legend>
            </div>
        </div>
        <div class="row"></div>
        <ul class="nav nav-pills">
            <li class="active"><a href="#RecepcionAlm" data-toggle="tab">Recepcion</a></li>
            <li><a href="#problemasRecepcionAlm" data-toggle="tab">Problemas de recepción</a></li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane fade active in" id="RecepcionAlm">
                <form id="formRecepcionAlmacen" data-parsley-validate="true">
                    <fieldset>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <div class="form-group <?php echo $mostrarSelectInput ?>">
                                <label class="f-w-600 f-s-13">Usuario que recibe *</label>
                                <input type="text" class="form-control" id="IdUsuarioRecibe" placeholder="<?php echo $usuario; ?>"  data-parsley-required="true" disabled/>
                            </div>
                            <div class="form-group <?php echo $mostrarInputFile ?>">
                                <label class="f-w-600 f-s-13">Usuario que recibe *</label>
                                <input type="text" class="form-control" placeholder="<?php echo $nombreRecibe ?>" disabled/>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <div class="form-group <?php echo $mostrarSelectInput ?>">
                                <label class="f-w-600 f-s-13">Fecha de Recepción *</label>
                                <input type="datetime-local" id="fechaRecepcionAlm" value="<?php echo $date = date('Y-m-d\TH:i'); ?>" class="form-control" data-parsley-pattern="^\d\d\d\d-(0?[1-9]|1[0-2])-(0?[1-9]|[12][0-9]|3[01])T(|0[0-9]|1[0-9]|2[0-3]):([0-9]|[0-5][0-9])$" required/>
                            </div>
                            <div class="form-group <?php echo $mostrarInputFile ?>">
                                <label class="f-w-600 f-s-13">Fecha de Recepción *</label>
                                <input type="text" class="form-control" placeholder="<?php echo $fecha ?>" disabled/>
                            </div>
                        </div>
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="form-group <?php echo $mostrarSelectInput ?>">
                                <label class="f-w-600 f-s-13">Evidencia de recepción *</label> 
                                <input id="evidenciaRecepcionAlmacen"  name="evidenciaRecepcionAlmacen[]" type="file" multiple />
                            </div>
                            <div class="form-group <?php echo $mostrarInputFile ?>">
                                <label class="f-w-600 f-s-13">Evidencia de recepción *</label>      
                                <div id="" class=" evidencia">
                                    <?php
                                    $archivosEnvia = explode(',', $archivoRecepcion);
                                    foreach ($archivosEnvia as $value) {
                                        $pathInfo = pathinfo($value);
                                        if (array_key_exists("extension", $pathInfo)) {
                                            switch (strtolower($pathInfo['extension'])) {
                                                case 'doc': case 'docx':
                                                    $scr = '/assets/img/Iconos/word_icon.png';
                                                    break;
                                                case 'xls': case 'xlsx':
                                                    $scr = '/assets/img/Iconos/excel_icon.png';
                                                    break;
                                                case 'pdf':
                                                    $scr = '/assets/img/Iconos/pdf_icon.png';
                                                    break;
                                                case 'jpg': case 'jpeg': case 'bmp': case 'gif': case 'png':
                                                    $scr = $value;
                                                    break;
                                                default :
                                                    $scr = '/assets/img/Iconos/file_icon.png';
                                                    break;
                                            }
                                        } else {
                                            $scr = '/assets/img/Iconos/file_icon.png';
                                        }

                                        if (strtolower($pathInfo['extension']) === 'jpg' || strtolower($pathInfo['extension']) === 'jpeg' || strtolower($pathInfo['extension']) === 'bmp' || strtolower($pathInfo['extension']) === 'gif' || strtolower($pathInfo['extension']) === 'png') {
                                            ?>
                                            <a class="m-l-5 m-r-5" href="<?php echo $scr ?>" data-lightbox="image-<?php echo$scr ?>">
                                                <img src="<?php echo $scr ?>" style="max-height:115px !important;" />
                                            </a>
                                            <?php
                                        } else {
                                            ?>
                                            <a href="<?php echo $value ?>" target="_blank"><img src="<?php echo $scr ?>" style="max-height:115px !important;" /></a>
                                            <?php
                                        }
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div id="errorFormularioAlmacen"></div>
                            </div>
                        </div>
                        <div class="col-md-12 col-sm-12 col-xs-12 text-center <?php echo $mostrarSelectInput ?>">
                            <a id="btnGuardarRecepcionAlmRegreso" class="btn btn-primary m-t-10 m-r-10 f-w-600 f-s-13"><i class="fa fa-save"></i> Guardar Recepción</a>
                        </div>
                    </fieldset>
                </form>
            </div>
            <div class="tab-pane fade" id="problemasRecepcionAlm">
                <form id="formProblemaRecepcionAlmacen" data-parsley-validate="true">
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <label class="f-w-600 f-s-13">Nota *</label>
                                <textarea class="form-control" rows="5" id="txtNotaAlmacen" value="" <?php echo $mostrarAdjuntos ?>></textarea>                            
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <label class="f-w-600 f-s-13">Adjuntos *</label>
                                <input id="adjuntosProblemaAlm" name="adjuntosProblemaAlm[]" type="file" multiple="" <?php echo $mostrarAdjuntos ?>/>    
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div id="errorAgregarProblemaAlm"></div>
                        </div>
                    </div>
                    <div>
                        <div class="col-md-12 col-sm-12 col-xs-12 text-center <?php echo $mostrarSelectInput ?>">
                            <a id="btnAgregarProblemaAlmRegreso" class="btn btn-success m-t-10 m-r-10 f-w-600 f-s-13"><i class="fa fa-plus"></i> Agregar Problema</a>
                        </div>
                    </div>
                </form>

                <div class="row m-t-25">
                    <legend>Notas y adjuntos</legend>
                </div>
                <div class="timelineTareas" id="divNotasAdjuntosAlmacen"></div>
            </div>                
        </div>
    </div>
</div>
