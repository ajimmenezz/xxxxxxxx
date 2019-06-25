<?php if (!$firmas) {
    ?>
    <div class="row">
        <div class="col-md-12 bg-silver-lighter">

            <div class="row m-t-10">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="form-group">
                        <label class="f-w-600 f-s-14">Tipo de Evidencia*:</label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-file"></i></span>
                            <select id="listTiposEvidenciaEquipo<?php echo $instalacion; ?>" class="form-control" style="width:100%">
                                <option value="">Selecciona . . .</option>
                                <?php
                                if (isset($evidencias['evidenciasRequeridas']) && count($evidencias['evidenciasRequeridas']) > 0) {
                                    foreach ($evidencias['evidenciasRequeridas'] as $key => $value) {
                                        echo '<option value="' . $value['Id'] . '">' . $value['Nombre'] . '</option>';
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="form-group">
                        <label class="f-w-600 f-s-14">Adjuntar Archivo*:</label>
                        <input id="evidenciaEquipo<?php echo $instalacion; ?>" name="evidenciaEquipo<?php echo $instalacion; ?>[]" type="file" />
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div id="divErrorEvidenciaEquipo<?php echo $instalacion; ?>"></div>
        </div>
    </div>
    <div class="row m-t-25">
        <div class="col-md-12 text-center">
            <a id="btnSubirEvidenciaEquipo<?php echo $instalacion; ?>" class="btn btn-success btn-xs f-s-15 f-w-600 p-l-15 p-r-15">
                <i class="fa fa-cloud-upload"></i> Subir Archivo
            </a>
        </div>
    </div>
<?php
}
?>
<div class="row">
    <div class="col-md-12">
        <h4>Evidencias de Instalación Antena 1</h4>
    </div>
    <div class="col-md-12">
        <div class="underline"></div>
    </div>
</div>
<div class="row" id="divEvidenciasInstalacion">
    <?php
    foreach ($evidencias['evidenciasXEquipoInstalado'] as $key => $value) {
        ?>
        <div class="col-md-3 text-center p-10">
            <div class="image-inner">
                <a href="<?php echo $value['Archivo']; ?>" data-lightbox="gallery-group-instalacion">
                    <img style="height:150px !important; max-height:150px !important;" class="img-thumbnail" src="<?php echo $value['Archivo']; ?>" alt="<?php echo $value['Evidencia']; ?>">
                </a>
                <a data-id="<?php echo $value['Id']; ?>" class="btn btn-block btn-danger btn-xs f-w-600 btnEliminarEvidenciaInstalacionEquipo">Eliminar Archivo</a>
                <p class="image-caption f-w-600 f-s-14"><?php echo $value['Evidencia']; ?></p>
            </div>
        </div>
    <?php
}
?>
</div>