<div id="panelAsignacionGuia" class="panel panel-inverse">
    <div class="panel-heading">
        <h4 class="panel-title">2) Asignación de Guía</h4>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <legend>Documentación de Guía</legend>
            </div>
        </div>
        <?php
        if (!empty($datosSolicitudGuia)) { // estatus 26
            foreach ($datosSolicitudGuia as $value) {
                $disabled = 'disabled';
                $hidden = 'hidden';
                $comentario = $value['ComentariosSolicitud'];
                $archivos = $value['ArchivosEnvio'];
                $archivosSolicitud = $value['ArchivosSolicitud'];
                
                if (empty($archivosSolicitud)) {
                    $hiddenContrario = 'hidden';
                } else {
                    $hiddenContrario = '';
                }
                $informacionSolicitudGuia = $value['InformacionSolicitudGuia'];
            }
        } else {
            $disabled = "";
            $hidden = "";
            $comentario = "";
            $archivos = "";
            $archivosSolicitud = "";
            $hiddenContrario = "hidden";
            $informacionSolicitudGuia = "";
        }

        if ($formularioEditable) {
            $camposEditables = '';
            $botonesEditables = '';
        } else {
            $camposEditables = 'disabled';
            $botonesEditables = 'hidden';
        }

        if ($estatus['IdEstatus'] === '37' && $estatus['Flag'] === '1') {
            $botonesEditables = 'hidden';
            $camposEditables = 'disabled';
        }
        ?>
        <form id="formAsignacionGuia" data-parsley-validate="true">
            <fieldset>
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label class="f-w-600 f-s-13">Información para generar guía *</label>
                            <textarea class="form-control" id="txtInformacionGuia" rows="10" <?php echo $camposEditables ?> disabled><?php echo $informacionSolicitudGuia ?></textarea>
                        </div>
                    </div>
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label class="f-w-600 f-s-13">Comentarios de la solicitud *</label>
                            <textarea class="form-control" id="txtComentariosGuia" rows="5" placeholder="<?php echo $comentario ?>" <?php echo $camposEditables ?>></textarea>
                        </div>
                    </div>
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="form-group <?php echo $hiddenContrario ?>">
                            <label class="f-w-600 f-s-13">Evidencia de envío</label>      
                            <div id="" class=" evidencia">
                                <?php
                                $archivosSolicitud = explode(',', $archivosSolicitud);
                                foreach ($archivosSolicitud as $value) {
                                    ?>
                                    <a class="m-l-5 m-r-5" href="<?php echo $value ?>" data-lightbox="image-<?php echo $value ?>">
                                        <img src="<?php echo $value ?>" style="max-height:115px !important;" />
                                    </a>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="form-group <?php echo $botonesEditables ?>">
                            <label class="f-w-600 f-s-13">Archivos de problemas o guía *</label> 
                            <input id="archivosProblemaGuia"  name="archivosProblemaGuia[]" type="file" multiple <?php echo $camposEditables ?>/>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div id="errorGuardarGuiaLogistica"></div>
                    </div>
                </div>
                <div class="row <?php echo $botonesEditables ?>">
                    <div class="col-md-12 col-sm-12 col-xs-12 text-center">
                        <a id="btnGuardarProblema" class="btn btn-sm btn-danger m-t-10 m-r-10 f-w-600 f-s-13"><i class="fa fa-save"></i> Guardar Problema</a>
                        <a id="btnGuardarSolicitud" class="btn btn-sm btn-primary m-t-10 m-l-10 f-w-600 f-s-13"><i class="fa fa-save"></i> Guardar Solicitud de Guía</a>
                    </div>
                </div>
            </fieldset>
        </form>
    </div>
</div>