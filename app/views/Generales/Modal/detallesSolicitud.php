<div class="row">
    <div class="col-md-12">                        
        <div class="form-group">
            <h3 class="m-t-10">Detalles de la solicitud <?php echo $detalles['Id']; ?></h3>
            <div class="underline m-b-15 m-t-15"></div>
        </div>    
    </div> 
</div>
<div class="row m-t-15">
    <div class="col-md-2 col-sm-4 col-xs-12">
        <div class="form-group">
            <label class="f-w-700 f-s-13"># Solicitud:</label>
            <input type="text" value="<?php echo $detalles['Id']; ?>" class="form-control f-w-600 text-center f-s-15" disabled="disabled" />
        </div>
    </div>
    <div class="col-md-2 col-sm-4 col-xs-12">
        <div class="form-group">
            <label class="f-w-700 f-s-13"># Ticket:</label>
            <input type="text" value="<?php echo $detalles['Ticket']; ?>" class="form-control f-w-600 text-center f-s-15" disabled="disabled" />
        </div>
    </div>
    <div class="col-md-2 col-sm-4 col-xs-12">
        <div class="form-group">
            <label class="f-w-700 f-s-13"># Folio SD:</label>
            <input type="text" value="<?php echo $detalles['Folio']; ?>" class="form-control f-w-600 text-center f-s-15" disabled="disabled" />
        </div>
    </div>    
    <div class="col-md-6 col-sm-6 col-xs-12">
        <div class="form-group">
            <label class="f-w-700 f-s-13">Departamento:</label>                    
            <input type="text" value="<?php echo $detalles['DepartamentoString']; ?>" class="form-control f-w-600 text-center f-s-15" disabled="disabled" />
        </div>
    </div>
    <div class="col-md-4 col-sm-6 col-xs-12">
        <div class="form-group">
            <label class="f-w-700 f-s-13">Solicita:</label>
            <input type="text" value="<?php echo $detalles['SolicitaString']; ?>" class="form-control f-w-600 text-center f-s-15" disabled="disabled" />
        </div>
    </div>
    <div class="col-md-4 col-sm-6 col-xs-12">
        <div class="form-group">
            <label class="f-w-700 f-s-13">Prioridad:</label>
            <input type="text" value="<?php echo $detalles['PrioridadString']; ?>" class="form-control f-w-600 text-center f-s-15" disabled="disabled" />
        </div>
    </div>
    <div class="col-md-4 col-sm-6 col-xs-12">
        <div class="form-group">
            <label class="f-w-700 f-s-13">Atiende:</label>
            <input type="text" value="<?php echo $detalles['AtiendeString']; ?>" class="form-control f-w-600 text-center f-s-15" disabled="disabled" />
        </div>
    </div>
    <div class="col-md-4 col-sm-6 col-xs-12">
        <div class="form-group">
            <label class="f-w-700 f-s-13">Estatus:</label>
            <input type="text" value="<?php echo $detalles['EstatusString']; ?>" class="form-control f-w-600 text-center f-s-15" disabled="disabled" />
        </div>
    </div>
    <div class="col-md-4 col-sm-6 col-xs-12">
        <div class="form-group">
            <label class="f-w-700 f-s-13">Fecha:</label>
            <div class='input-group' id='fechaSolicitud'>
                <?php
                $fecha = ($detalles['FechaCreacion'] != '') ? substr($detalles['FechaCreacion'], 0, 10) : '';
                ?>
                <input type='date' id="txtFechaSolicitud" class="form-control f-w-600 f-s-15" value="<?php echo $fecha; ?>" disabled="disabled" />
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
            <input type="text" id="txtAsuntoSolicitud" class="form-control f-w-500 f-s-15"  value="<?php echo $detalles['Asunto']; ?>" disabled="disabled"/>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-11 col-sm-11 col-xs-12">
        <div class="form-group">
            <label class="f-w-700 f-s-13">Descripción de solicitud:</label>                    
            <textarea id="txtDescripcionSolicitud" class="form-control f-s-15" placeholder="Descripción de la solicitud" rows="8" disabled="disabled"><?php echo $detalles['Descripcion']; ?></textarea>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-11 col-sm-11 col-xs-12">
        <div class="form-group">
            <label class="f-w-700 f-s-13">Archivos adjuntos:</label>                                            
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
                            echo '</div>';
                        }
                    }
                    ?>                                
                </div>
            </div>
        </div>
    </div>
</div>                               