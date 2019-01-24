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
                $comentario = $value['ComentarioDeGuia'];
                $archivos = $value['ArchivosEnvio'];
            }
        } else {
            $disabled = "";
            $hidden = "";
            $comentario = "";
            $archivos = "";
        }
        ?>
        <form id="formAsignacionGuia" data-parsley-validate="true">
            <fieldset>
                <div class="row">
                    <div class="col-md-9 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label class="f-w-600 f-s-13">Comentarios de la solicitud *</label>
                            <textarea class="form-control" id="txtComentarios" rows="5" placeholder="<?php echo $comentario ?>" <?php echo $disabled ?>></textarea>
                        </div>
                    </div>
                    <div class="col-md-9 col-sm-12 col-xs-12">
                        <div class = "evidencia" class="<?php echo $hidden ?>">
                            <label class="f-w-600 f-s-13">Evidencia *</label> 
                            <a href="" data-lightbox="" ><img src="${evidencia}" alt="" /></a>
                        </div>
                        <div class="form-group">
                            <label class="f-w-600 f-s-13">Archivos de problemas o guía *</label> 
                            <input id="archivosProblemaGuia"  name="archivosProblemaGuia[]" type="file" multiple <?php echo $disabled ?>/>
                        </div>
                    </div>
                </div>
                <div class="row hidden">
                    <div class="col-md-12 col-sm-12 col-xs-12 text-center">
                        <a id="btnGuardarProblema" class="btn btn-sm btn-danger m-t-10 m-r-10 f-w-600 f-s-13">Guardar Problema</a>
                        <a id="btnGuardarSolicitud" class="btn btn-sm btn-success m-t-10 m-l-10 f-w-600 f-s-13">Guardar Solicitud</a>
                    </div>
                </div>
            </fieldset>
        </form>
    </div>
</div>