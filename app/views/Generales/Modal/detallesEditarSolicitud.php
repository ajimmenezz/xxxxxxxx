<div class="row">
    <div class="col-md-9 col-sm-6 col-xs-12">
        <h1 class="page-header">Detalles de la solicitud</h1>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12 text-right">
        <label id="btnBackToInitial" class="btn btn-success">
            <i class="fa fa fa-reply"></i> Regresar
        </label>  
    </div>
</div>
<div id="panelDetallesSolicitud" class="panel panel-inverse">        
    <div class="panel-heading">
        <h4 class="panel-title">Panel de Detalles</h4>        
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-12">                        
                <div class="form-group">
                    <h3 class="m-t-10">Detalles de la solicitud <?php echo $detalles['Id']; ?></h3>
                    <div class="underline m-b-15 m-t-15"></div>
                </div>    
            </div> 
        </div>
        <div class="row m-t-15">
            <div class="col-md-2 col-sm-3 col-xs-6">
                <div class="form-group">
                    <label class="f-w-700 f-s-13"># Solicitud:</label>
                    <input type="text" value="<?php echo $detalles['Id']; ?>" class="form-control f-w-600 text-center f-s-15" disabled="disabled" />
                </div>
            </div>
            <div class="col-md-2 col-sm-3 col-xs-6">
                <div class="form-group">
                    <label class="f-w-700 f-s-13"># Ticket:</label>
                    <input type="text" value="<?php echo $detalles['Ticket']; ?>" class="form-control f-w-600 text-center f-s-15" disabled="disabled" />
                </div>
            </div>
            <div class="col-md-4 col-sm-6 col-xs-12">
                <div class="form-group">
                    <label class="f-w-700 f-s-13">Solicita:</label>
                    <select class="form-control" id="listaSolicitaSolicitudes">
                        <?php
                        $cont = 0;
                        foreach ($usuarios as $key => $value) {
                            $cont += ($value['Id'] == $detalles['IdDepartamento']) ? 1 : 0;
                            $selected = ($value['Id'] == $detalles['Solicita']) ? 'selected' : '';
                            echo '<option value="' . $value['Id'] . '" ' . $selected . '>' . $value['Nombre'] . '</option>';
                        }
                        if ($cont <= 0) {
                            echo '<option value="" selected="selected">Selecciona . . .</option>';
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="col-md-4 col-sm-6 col-xs-12">
                <div class="form-group">
                    <label class="f-w-700 f-s-13">Departamento:</label>                    
                    <select class="form-control" id="listaDepartamentosSolicitudes">
                        <?php
                        $cont = 0;
                        foreach ($departamentos as $key => $value) {
                            $cont += ($value['Id'] == $detalles['IdDepartamento']) ? 1 : 0;
                            $selected = ($value['Id'] == $detalles['IdDepartamento']) ? 'selected' : '';
                            echo '<option value="' . $value['Id'] . '" ' . $selected . '>' . $value['Nombre'] . '</option>';
                        }
                        if ($cont <= 0) {
                            echo '<option value="" selected="selected">Selecciona . . .</option>';
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="col-md-4 col-sm-6 col-xs-12">
                <div class="form-group">
                    <label class="f-w-700 f-s-13">Prioridad:</label>
                    <select class="form-control" id="listaPrioridadesSolicitudes">
                        <?php
                        $cont = 0;
                        foreach ($prioridades as $key => $value) {
                            $cont += ($value['Id'] == $detalles['IdPrioridad']) ? 1 : 0;
                            $selected = ($value['Id'] == $detalles['IdPrioridad']) ? 'selected' : '';
                            echo '<option value="' . $value['Id'] . '" ' . $selected . '>' . $value['Nombre'] . '</option>';
                        }
                        if ($cont <= 0) {
                            echo '<option value="" selected="selected">Selecciona . . .</option>';
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="col-md-4 col-sm-6 col-xs-12">
                <div class="form-group">
                    <label class="f-w-700 f-s-13">Atiende:</label>
                    <select class="form-control" id="listaAtiendeSolicitudes">
                        <?php
                        $cont = 0;
                        foreach ($usuarios as $key => $value) {
                            $cont += ($value['Id'] == $detalles['Atiende']) ? 1 : 0;
                            $selected = ($value['Id'] == $detalles['Atiende']) ? 'selected' : '';
                            echo '<option value="' . $value['Id'] . '" ' . $selected . '>' . $value['Nombre'] . '</option>';
                        }
                        if ($cont <= 0) {
                            echo '<option value="" selected="selected">Selecciona . . .</option>';
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="col-md-4 col-sm-6 col-xs-12">
                <div class="form-group">
                    <label class="f-w-700 f-s-13">Estatus:</label>
                    <?php
                    $estatusString = '';
                    foreach ($estatus as $key => $value) {
                        if ($value['Id'] == $detalles['IdEstatus']) {
                            $estatusString = $value['Nombre'];
                        }
                    }
                    ?>
                    <input type="text" value="<?php echo $estatusString; ?>" class="form-control f-w-600 text-center f-s-15" disabled="disabled" />
                </div>
            </div>
            <div class="col-md-4 col-sm-6 col-xs-12">
                <div class="form-group">
                    <label class="f-w-700 f-s-13">Fecha:</label>
                    <div class='input-group' id='fechaSolicitud'>
                        <?php
                        $fecha = ($detalles['FechaCreacion'] != '') ? substr($detalles['FechaCreacion'], 0, 10) : '';                        
                        ?>
                        <input type='date' id="txtFechaSolicitud" class="form-control" value="<?php echo $fecha; ?>" />
                        <span class="input-group-addon">
                            <span class="glyphicon glyphicon-calendar"></span>
                        </span>
                    </div>
                </div> 
            </div>
        </div>
        <div class="row">
            <div class="col-md-11 col-sm-11 col-xs-12">
                <div class="form-group">
                    <label class="f-w-700 f-s-13">Asunto:</label>                    
                    <input type="text" id="txtAsuntoSolicitud" class="form-control f-w-500 f-s-13"  value="<?php echo $detalles['Asunto']; ?>" />
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-11 col-sm-11 col-xs-12">
                <div class="form-group">
                    <label class="f-w-700 f-s-13">Descripción o solicitud:</label>                    
                    <textarea id="txtDescripcionSolicitud" class="form-control f-s-13" placeholder="Descripción de la solicitud" rows="8"><?php echo $detalles['Descripcion']; ?></textarea>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-11 col-sm-11 col-xs-12">
                <div class="form-group">
                    <label class="f-w-700 f-s-13">Archivos adjuntos:</label>
                    <input id="adjuntosSolicitud" name="adjuntosSolicitud[]" type="file" multiple/>
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12 m-t-20">                                                          
                            <?php
                            if (array_key_exists("Evidencias", $detalles) && $detalles['Evidencias'] != '') {
                                $evidencias = explode(",", $detalles['Evidencias']);
                                foreach ($evidencias as $key => $value) {
                                    echo '<div class="thumbnail-pic m-5 p-5">';
                                    $ext = strtolower(pathinfo($value, PATHINFO_EXTENSION));
                                    switch ($ext) {
                                        case 'png': case 'jpeg': case 'jpg': case 'gif':
                                            echo '<a class="imagenesSolicitud" target="_blank" href="' . $value . '"><img src="' . $value . '" class="img-responsive img-thumbnail" style="max-height:160px !important;" alt="Evidencia" /></a>';
                                            break;
                                        case 'xls': case 'xlsx':
                                            echo '<a class="imagenesSolicitud" target="_blank" href="' . $value . '"><img src="/assets/img/Iconos/excel_icon.png" class="img-responsive img-thumbnail" style="max-height:160px !important;" alt="Evidencia" /></a>';
                                            break;
                                        case 'doc': case 'docx':
                                            echo '<a class="imagenesSolicitud" target="_blank" href="' . $value . '"><img src="/assets/img/Iconos/word_icon.png" class="img-responsive img-thumbnail" style="max-height:160px !important;" alt="Evidencia" /></a>';
                                            break;
                                        case 'pdf':
                                            echo '<a class="imagenesSolicitud" target="_blank" href="' . $value . '"><img src="/assets/img/Iconos/pdf_icon.png" class="img-responsive img-thumbnail" style="max-height:160px !important;" alt="Evidencia" /></a>';
                                            break;
                                        default :
                                            echo '<a class="imagenesSolicitud" target="_blank" href="' . $value . '"><img src="/assets/img/Iconos/no-thumbnail.jpg" class="img-responsive img-thumbnail" style="max-height:160px !important;" alt="Evidencia" /></a>';
                                            break;
                                    }
                                    echo ''
                                    . ' <div class="edit btnRemoveFileSolicitudes"><a class="text-danger" role="button"><i class="fa fa-trash fa-lg"></i></a></div>'
                                    . '</div>';
                                }
                            }
                            ?>                                
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row m-t-10">
            <div class="col-md-11 col-sm-11 col-xs-12">
                <div class="alert alert-warning fade in m-b-15 f-s-15">
                    <strong>Alerta!</strong>
                    Los cambios que realice en la solicitud pueden afectar en la estadisticas previas. Por favor sea cuidadoso.
                    <span class="close" data-dismiss="alert">×</span>
                </div>
            </div>
        </div>
        <div class="row m-t-15">
            <div class="col-md-12 col-sm-12 col-xs-12 text-center">
                <a class="btn btn-warning" id="btnGuardarCambiosSolicitud"> Guardar cambios en la solicitud</a>
            </div>
        </div>
    </div>
</div>
