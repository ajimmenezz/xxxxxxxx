<div class="row">
    <div class="col-md-12">   
        <div class="form-group">
            <h3 class="m-t-10">Detalles del servicio <?php echo $datos['Id']; ?></h3>
            <div class="underline m-b-15 m-t-15"></div>
        </div> 
    </div>
</div>
<div class="row">
    <div class="col-md-3 col-sm-3 col-xs-6">
        <div class="form-group">
            <label class="f-w-700 f-s-13"># Solicitud:</label>
            <input type="text" value="<?php echo $datos['Solicitud']; ?>" class="form-control f-w-600 text-center f-s-15" disabled="disabled" />
        </div>
    </div>
    <div class="col-md-3 col-sm-3 col-xs-6">
        <div class="form-group">
            <label class="f-w-700 f-s-13"># Ticket:</label>
            <input type="text" value="<?php echo $datos['Ticket']; ?>" class="form-control f-w-600 text-center f-s-15" disabled="disabled" />
        </div>
    </div>
    <div class="col-md-3 col-sm-3 col-xs-6">
        <div class="form-group">
            <label class="f-w-700 f-s-13">Folio SD:</label>
            <input type="text" value="<?php echo $datos['Folio']; ?>" class="form-control f-w-600 text-center f-s-15" disabled="disabled" />
        </div>
    </div>
    <div class="col-md-3 col-sm-3 col-xs-6">
        <div class="form-group">
            <label class="f-w-700 f-s-13"># Servicio:</label>
            <input type="text" value="<?php echo $datos['Id']; ?>" class="form-control f-w-600 text-center f-s-20" disabled="disabled" />
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-4 col-sm-4 col-xs-6">
        <div class="form-group">
            <label class="f-w-700 f-s-13">Tipo de Servicio:</label>
            <input type="text" value="<?php echo $datos['Tipo']; ?>" class="form-control f-w-600 text-center f-s-15" disabled="disabled" />
        </div>
    </div>
    <div class="col-md-4 col-sm-4 col-xs-6">
        <div class="form-group">
            <label class="f-w-700 f-s-13">Sucursal:</label>
            <input type="text" value="<?php echo $datos['Sucursal']; ?>" class="form-control f-w-600 text-center f-s-15" disabled="disabled" />
        </div>
    </div>
    <div class="col-md-4 col-sm-4 col-xs-6">
        <div class="form-group">
            <label class="f-w-700 f-s-13">Personal que Atiende:</label>
            <input type="text" value="<?php echo $datos['Atiende']; ?>" class="form-control f-w-600 text-center f-s-15" disabled="disabled" />
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-3 col-sm-3 col-xs-6">
        <div class="form-group">
            <label class="f-w-700 f-s-13">Estatus:</label>
            <input type="text" value="<?php echo $datos['Estatus']; ?>" class="form-control f-w-600 text-center f-s-15" disabled="disabled" />
        </div>
    </div>
    <div class="col-md-3 col-sm-3 col-xs-6">
        <div class="form-group">
            <label class="f-w-700 f-s-13">Fecha Creación:</label>
            <input type="text" value="<?php echo $datos['FechaCreacion']; ?>" class="form-control f-w-600 text-center f-s-15" disabled="disabled" />
        </div>
    </div>
    <div class="col-md-3 col-sm-3 col-xs-6">
        <div class="form-group">
            <label class="f-w-700 f-s-13">Fecha Inicio:</label>
            <input type="text" value="<?php echo $datos['FechaInicio']; ?>" class="form-control f-w-600 text-center f-s-15" disabled="disabled" />
        </div>
    </div>
    <div class="col-md-3 col-sm-3 col-xs-6">
        <div class="form-group">
            <label class="f-w-700 f-s-13">Fecha Conclusión:</label>
            <input type="text" value="<?php echo $datos['FechaConclusion']; ?>" class="form-control f-w-600 text-center f-s-15" disabled="disabled" />
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label class="f-w-700 f-s-13">Servicio:</label>
            <input type="text" value="<?php echo $datos['Servicio']; ?>" class="form-control f-w-600 f-s-15" disabled="disabled" />
        </div>
    </div>
</div>
<?php
if (!in_array($datos['FechaResolucion'], ['', NULL])) {
    ?>
    <div class="row m-t-20">
        <div class="col-md-4 col-sm-6 col-xs-12">
            <div class="form-group">
                <label class="f-w-700 f-s-13">Fecha de Resolución:</label>
                <input type="text" value="<?php echo $datos['FechaResolucion']; ?>" class="form-control f-w-600 text-center f-s-15" disabled="disabled" />
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="form-group">
                <label class="f-w-700 f-s-13">Resolución del servicio:</label>                    
                <textarea id="txtDescripcionSolicitud" class="form-control f-s-15" placeholder="Resolución del servicio" rows="8" disabled="disabled"><?php echo $datos['Resolucion']; ?></textarea>
            </div>
        </div>
    </div>
    <?php
}

if (!in_array($datos['Firma'], ['', NULL])) {
    ?>
    <div class="row m-t-20">
        <div class="col-md-offset-4 col-md-4 col-sm-offset-3 col-sm-6 col-xs-offset-12 col-xs-12">
            <h5 class="f-w-700 text-center">Firma de Cierre</h5>
            <img style="max-height: 120px;" src="<?php echo $datos['Firma']; ?>" alt="Firma de Cierre" />
            <h6 class="f-w-700 text-center"><?php echo $datos['NombreFirma']; ?></h6>            
            <h6 class="f-w-700 text-center"><?php echo $datos['FechaFirma']; ?></h6>            
        </div>
    </div>
    <?php
}

if (!in_array($datos['Evidencias'], ['', NULL])) {
    ?>
    <div class="row m-t-20">
        <div class="col-md-11 col-sm-11 col-xs-12">
            <div class="form-group">
                <label class="f-w-700 f-s-13">Archivos adjuntos:</label>                                            
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12 m-t-20">  

                        <?php
                        $archivos = explode(",", $datos['Evidencias']);
                        $htmlArchivos = '';
                        foreach ($archivos as $key => $value) {
                            echo '<div class="thumbnail-pic m-5 p-5">';
                            $extencion = pathinfo($value, PATHINFO_EXTENSION);                            
                            switch ($extencion) {
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
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
}
?>