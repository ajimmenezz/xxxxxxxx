<?php
if (!empty($faltantes)) {
    ?>
    <div class="row">
        <div class="col-md-12">
            <h4>Firmas para Cierre de Instalación</h4>
        </div>
        <div class="col-md-12">
            <div class="underline"></div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="alert alert-warning fade in m-b-15">
                <strong>Alerta!</strong>
                Aún no es posible firmar el servicio. Revise los siguientes puntos y capture la información necesaria.
                <ul>
                    <?php
                    foreach ($faltantes as $key => $value) {
                        echo '<li>' . $value . '</li>';
                    }
                    ?>
                </ul>
            </div>
        </div>
    </div>
<?php
} else {


    $datosFirma = [
        'gerente' => '',
        'firmaGerente' => '',
        'tecnico' => '',
        'firmaTecnico' => ''
    ];

    if (isset($firmas) && count($firmas) > 0 && isset($firmas[0])) {
        $datosFirma['gerente'] = $firmas[0]['Gerente'];
        $datosFirma['firmaGerente'] = $firmas[0]['Firma'];
        $datosFirma['tecnico'] = $firmas[0]['Tecnico'];
        $datosFirma['firmaTecnico'] = $firmas[0]['FirmaTecnico'];
    }

    ?>

    <div class="row">
        <div class="col-md-12">
            <h4>Firmas para Cierre de Instalación</h4>
        </div>
        <div class="col-md-12">
            <div class="underline"></div>
        </div>
    </div>
    <div class="row m-t-10">
        <div class="col-md-offset-3 col-md-6 col-sm-offset-2 col-sm-8 col-xs-offset-0 col-xs-12 bg-silver text-center">
            <div class="form-group">
                <label class="f-s-14 f-w-600 m-t-10">Firma del Gerente Cinemex * :</label>
                <div class="input-group m-b-10">
                    <span class="input-group-addon"><i class="fa fa-child"></i></span>
                    <?php
                    if (!is_null($datosFirma['gerente']) && $datosFirma['gerente'] != '') {
                        echo ' 
                    <label class="form-control">' . $datosFirma['gerente'] . '</label>
                    </div>
                    <div class="image-inner">
                        <a class="text-center" href="' . $datosFirma['firmaGerente'] . '" data-lightbox="gallery-group-firmas">
                            <img style="height:150px !important; max-height:150px !important;" class="img-thumbnail" src="' . $datosFirma['firmaGerente'] . '" alt="Firma Gerente">
                        </a>                                                
                    </div>';
                    } else {
                        echo '
                        <input class="form-control" type="text" id="txtNombreGerente" value="" placeholder="Nombre Gerente" />
                    </div>
                    <a id="btnGuardarFirmaGerente" class="btn btn-block btn-success m-b-10 f-s-13 f-w-600">Guardar Firma Gerente</a>
                    <div style="width:100%; height: 200px;" id="firmaGerente"></div>';
                    }
                    ?>

                </div>
            </div>
        </div>

        <div class="row m-t-10">
            <div class="col-md-offset-3 col-md-6 col-sm-offset-2 col-sm-8 col-xs-offset-0 col-xs-12 bg-silver text-center">
                <div class="form-group">
                    <label class="f-s-14 f-w-600 m-t-10">Firma del Técnico (Siccob o Lexmark) * :</label>
                    <?php
                    if (!is_null($datosFirma['firmaTecnico']) && $datosFirma['firmaTecnico'] != '') {
                        echo '
                    <div class="input-group m-b-10">
                        <span class="input-group-addon"><i class="fa fa-child"></i></span>
                        <label class="form-control">' . $datosFirma['tecnico'] . '</label>
                    </div>
                    <div class="image-inner">
                        <a class="text-center" href="' . $datosFirma['firmaTecnico'] . '" data-lightbox="gallery-group-firmas">
                            <img style="height:150px !important; max-height:150px !important;" class="img-thumbnail" src="' . $datosFirma['firmaTecnico'] . '" alt="Firma Técnico">
                        </a>                                                
                    </div>';
                    } else {
                        ?>
                        <a id="btnGuardarFirmaTecnico" class="btn btn-block btn-success m-b-10 f-s-13 f-w-600">Guardar Firma Técnico</a>
                        <div style="width:100%; height: 200px;" id="firmaTecnico"></div>
                    <?php
                }
                ?>
                </div>
            </div>
        </div>

    <?php
}
?>