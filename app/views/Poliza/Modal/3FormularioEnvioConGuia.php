<?php
if (!empty($datosSolicitudGuia)) {
    foreach ($datosSolicitudGuia as $value) {
        $paqueteria = $value['Paqueteria'];
        $guia = $value['Guia'];
        $fecha = $value['Fecha'];
        $mostrarSelect = "hidden";
        $mostrarInput = "";
        if (!empty($value['ArchivosEnvio'])) {
            $archivo = $value['ArchivosEnvio'];
            $mostrarInputFile = "";
            $mostrarSelectInput = "hidden";
        } else {
            $archivo = "";
            $mostrarInputFile = "hidden";
            $mostrarSelectInput = "hidden";
        }
    }
} else {
    $paqueteria = "";
    $guia = "";
    $fecha = "";
    $archivo = "";
    $mostrarSelect = "";
    $mostrarInput = "hidden";
    $mostrarInputFile = "hidden";
    $mostrarSelectInput = "";
}

if ($estatus['IdEstatus'] === '26') {
    $botonSolicitarGuia = 'hidden';
}else if ($estatus['IdEstatus'] === '4' && $estatus['Flag'] === '0') {
    $mostrarSelect = '';
    $botonSolicitarGuia = 'hidden';
    $mostrarInput = 'hidden';
    $mostrarSelectInput = '';
} else {
    $botonSolicitarGuia = '';
}
?>

<div id="panelEnvioConGuia" class="panel panel-inverse">
    <div class="panel-heading">
        <h4 class="panel-title">2) Envío a Almacén</h4>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-6 col-sm-6 col-xs-6">
                <h4>Documentación de envío a Almacén</h4>
            </div>
            <div class="col-md-6 col-sm-6 col-xs-6 <?php echo $mostrarSelect ?>">
                <div class="form-group text-right">
                    <a href="javascript:;" class="btn btn-sm btn-success f-s-13 <?php echo $botonSolicitarGuia ?>" id="solicitarGuia" >Solicitar Guía </a>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="underline m-b-10"></div>
        </div>
        <form id="formEnvioAlmacen" data-parsley-validate="true">
            <div class="row">
                <div class="col-md-4 col-sm-6 col-xs-12">
                    <div class="form-group <?php echo $mostrarSelect ?>">
                        <label class="f-w-600 f-s-13">Paquetería *</label>
                        <select id="listPaqueteria" class="form-control" style="width: 100%" data-parsley-required="true">
                            <option value="">Selecciona . . .</option>
                            <?php
                            foreach ($paqueterias as $item) {
                                echo '<option value="' . $item['Id'] . '">' . $item['Nombre'] . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group <?php echo $mostrarInput ?>">
                        <label class="f-w-600 f-s-13">Paquetería *</label>
                        <input type="text" class="form-control" placeholder="<?php echo $paqueteria ?>" disabled/>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6 col-xs-12">
                    <div class="form-group <?php echo $mostrarSelect ?>" >
                        <label class="f-w-600 f-s-13"># Guía *</label>
                        <input type="text" class="form-control" id="guia" placeholder=""  data-parsley-required="true"/>
                    </div>
                    <div class="form-group <?php echo $mostrarInput ?>">
                        <label class="f-w-600 f-s-13"># Guía *</label>
                        <input type="text" class="form-control" placeholder="<?php echo $guia ?>" disabled/>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6 col-xs-12">
                    <div class="form-group <?php echo $mostrarSelect ?>">
                        <label class="f-w-600 f-s-13">Fecha de Envío *</label>
                        <input type="datetime-local" id="fechaValidacion" value="<?php echo $date = date('Y-m-d\TH:i'); ?>" class="form-control" data-parsley-pattern="^\d\d\d\d-(0?[1-9]|1[0-2])-(0?[1-9]|[12][0-9]|3[01])T(|0[0-9]|1[0-9]|2[0-3]):([0-9]|[0-5][0-9])$" required/>
                    </div>
                    <div class="form-group <?php echo $mostrarInput ?>">
                        <label class="f-w-600 f-s-13">Fecha de Validación *</label>
                        <input type="text" class="form-control" placeholder="<?php echo $fecha ?>" disabled/>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="form-group <?php echo $mostrarSelectInput ?> ">
                        <label class="f-w-600 f-s-13">Evidencia del envío</label> 
                        <input id="evidenciaEnvioGuia"  name="evidenciaEnvioGuia[]" type="file" multiple />
                    </div>
                    <div class="form-group <?php echo $mostrarInputFile ?>">
                        <label class="f-w-600 f-s-13">Evidencia de envío</label>      
                        <div id="" class=" evidencia">
                            <?php
                            $archivosEnvia = explode(',', $archivo);
                            foreach ($archivosEnvia as $value) {
                                ?>
                                <a class="m-l-5 m-r-5" href="<?php echo $value ?>" data-lightbox="image-<?php echo $value ?>">
                                    <img src="<?php echo $value ?>" style="max-height:115px !important;" />
                                </a>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div id="errorFormularioEnvio"></div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12 text-center <?php echo $mostrarSelect ?>">
                <a id="btnGuardarEnvio" class="btn btn-success btn-sm m-t-10 m-r-10 f-w-600 f-s-13">Guardar Envío</a>
            </div>
        </div>
    </div>
</div> 