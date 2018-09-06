<div class="row">
    <div class="col-md-12">                            
        <fieldset>
            <legend class="pull-left width-full f-s-17">Conversación del Servicio.</legend>
        </fieldset>  
    </div>
</div>

<?php
if ($datos !== '') {
    foreach ($datos as $key => $value) {
        $fecha = strftime('%A %e de %B, %G ', strtotime($value['Fecha'])) . date("h:ma", strtotime($value['Fecha']));
        ?>
        <div class="row m-t-25">
            <div class="col-md-6 col-sm-6 col-xs-12">
                <p class="f-w-600 pull-left"><?php echo $value['Nombre']; ?></p>            
            </div>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <p class="f-w-600 pull-right"><?php echo $fecha; ?></p>            
            </div>
        </div>
        <?php
        if ($value['Nota'] != '') {
            ?>
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="form-group">                        
                        <div class="p-5" style="background: #e5e9ed; opacity: .9; border: 1px solid #ccd0d4; border-radius: 3px;">
                            <p class="f-w-500 f-s-15 m-8"><?php echo $value['Nota']; ?></p>
                        </div>                        
                    </div>
                </div>
            </div>
            <?php
        }
        if ($value['Archivos'] != '') {
            ?>               
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">                                                          
                    <?php
                    $evidencias = explode(",", $value['Archivos']);
                    foreach ($evidencias as $key => $value) {
                        if ($value != '') {
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
            <?php
        }
    }
} else {
    ?>
    <div class="row"><div class="col-md-12 col-sm-12 col-xs-12"><pre>Al parecer esta solicitud no tiene conversaciones registradas.</pre></div></div>
    <?php
}
?>