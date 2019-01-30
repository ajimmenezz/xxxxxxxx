<?php
if (!empty($informacionEnvioLog)) {
    foreach ($informacionEnvioLog as $value) {
        $paqueteria = $value['paqueteria'];
        $guia = $value['Guia'];
        $FechaEnvio = $value['FechaEnvio'];
        $DondeRecibe = $value['DondeRecibe'];
        $Sucursal = $value['Sucursal'];
        $FechaRecepcion = $value['FechaRecepcion'];
        $Recibe = $value['Recibe'];
        $mostrarInput = "";
        $mostrarSelect = "hidden";
        if (!empty($value['ArchivosEnvio']) || $value['ArchivosEntrega']) {
            $archivoEnvio = $value['ArchivosEnvio'];
            $archivosEntrega = $value['ArchivosEntrega'];
            $mostrarInputFile = "";
            $mostrarSelectInput = "hidden";
        } else {
            $mostrarInputFile = "hidden";
            $mostrarSelectInput = "hidden";
        }
    }
} else {
    $paqueteria = "";
    $guia = "";
    $FechaEnvio = "";
    $DondeRecibe = "";
    $Sucursal = "";
    $FechaRecepcion = "";
    $Recibe = "";
    $mostrarInput = "hidden";
    $mostrarSelect = "";
    $mostrarInputFile = "hidden";
    $mostrarSelectInput = "";
    $archivoEnvio = "";
    $archivosEntrega = "";
}
?>
<div id="panelEnvioSeguimientoLog" class="panel panel-inverse">
    <div class="panel-heading">
        <h4 class="panel-title">7) Envío y Seguimiento Logística</h4>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-6 col-sm-6 col-xs-6">
                <legend>Documentación de envío</legend>
            </div>
        </div>
        <?php
//        echo $informacionEnvioLog;
        if (!empty($informacionEnvioLog)) {
            foreach ($informacionEnvioLog as $value) {
                $paqueteria = $value['paqueteria'];
                $guia = $value['Guia'];
                $fechaEnvio = $value['FechaEnvio'];
            }
            $mostrarSelect = "hidden";
            $mostrarInput = "";
        }else{
            $mostrarSelect = "";
            $mostrarInput = "hidden";
        }
        ?>
        <form id="formDocumentacionEnvio" data-parsley-validate="true">
            <div class="row">
                <div class="col-md-4 col-sm-6 col-xs-12">
                    <div class="form-group <?php echo $mostrarSelect ?>">
                        <label class="f-w-600 f-s-13">Paquetería *</label>
                        <select id="listPaqueteria" class="form-control" style="width: 100%" data-parsley-required="true">
                            <option value="">Selecciona . . .</option>
                        </select>
                    </div>
                    <div class="form-group <?php echo $mostrarInput ?>">
                        <label class="f-w-600 f-s-13">Paquetería *</label>
                        <input type="text" class="form-control" placeholder="<?php echo $paqueteria ?>" disabled/>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6 col-xs-12">
                    <div class="form-group <?php echo $mostrarSelect ?>">
                        <label class="f-w-600 f-s-13"># Guía</label>
                        <input type="text" class="form-control" id="guia" placeholder="55666476146549"  data-parsley-required="true"/>
                    </div>
                    <div class="form-group <?php echo $mostrarInput ?>">
                        <label class="f-w-600 f-s-13"># Guía</label>
                        <input type="text" class="form-control" placeholder="<?php echo $guia ?>" disabled/>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6 col-xs-12">
                    <div class="form-group <?php echo $mostrarSelect ?>">
                        <label class="f-w-600 f-s-13">Fecha de Envío *</label>
                        <input type="datetime-local" id="fechaEnvio" value="<?php echo $date = date('Y-m-d\TH:i'); ?>" class="form-control" data-parsley-pattern="^\d\d\d\d-(0?[1-9]|1[0-2])-(0?[1-9]|[12][0-9]|3[01])T(|0[0-9]|1[0-9]|2[0-3]):([0-9]|[0-5][0-9])$" required/>
                    </div>
                    <div class="form-group <?php echo $mostrarInput ?>">
                        <label class="f-w-600 f-s-13">Fecha de Envío *</label>
                        <input type="text" class="form-control" placeholder="<?php echo $FechaEnvio ?>" disabled/>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-9 col-sm-12 col-xs-12">
                    <div class="form-group <?php echo $mostrarSelectInput ?>">
                        <label class="f-w-600 f-s-13">Evidencia del envío</label> 
                        <input id="evidenciaEnvio"  name="evidenciaEnvio[]" type="file" multiple />
                    </div>
                    <div class="form-group <?php echo $mostrarInputFile ?>">
                        <label class="f-w-600 f-s-13">Evidencia de envío</label>  
                        <div id="" class="<?php $mostrarInputFile ?> evidencia">
                            <?php
                            $archivosEnvia = explode(',', $archivoEnvio);
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
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div id="errorFormulario"></div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12 text-center <?php echo $mostrarSelect ?>">
                    <a id="btnGuardarEnvio" class="btn btn-success btn-sm m-t-10 m-r-10 f-w-600 f-s-13">Guardar Envío</a>
                </div>
            </div>
        </form>
        <div id="divSeguimientoEntrega" class="">
            <div class="row m-t-20">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <legend>Seguimiento de Entrega</legend>
                </div>
            </div>
            <form id="formSeguimientoEntrega" data-parsley-validate="true">
                <div class="row">
                    <div class="col-md-4 col-sm-6 col-xs-12">
                        <div class="form-group <?php echo $mostrarSelect ?>">
                            <label class="f-w-600 f-s-13">¿Donde se recibe? *</label>
                            <select id="listDondeRecibe" class="form-control" style="width: 100%" data-parsley-required="true">
                                <option value="">Selecciona . . .</option>
                            </select>
                        </div>
                        <div class="form-group <?php echo $mostrarInput ?>">
                            <label class="f-w-600 f-s-13">¿Donde se recibe? *</label>
                            <input type="text" class="form-control" placeholder="<?php echo $DondeRecibe ?>" disabled/>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6 col-xs-12">
                        <div class="form-group <?php echo $mostrarSelect ?>">
                            <label class="f-w-600 f-s-13">Sucursal (Solo en caso de ser sucursal) *</label>
                            <select id="listSucursal" class="form-control" style="width: 100%" data-parsley-required="true">
                                <option value="">Selecciona . . .</option>
                            </select>
                        </div>
                        <div class="form-group <?php echo $mostrarInput ?>">
                            <label class="f-w-600 f-s-13">Sucursal (Solo en caso de ser sucursal) *</label>
                            <input type="text" class="form-control" placeholder="<?php echo $Sucursal ?>" disabled/>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6 col-xs-12">
                        <div class="form-group <?php echo $mostrarSelect ?>">
                            <label class="f-w-600 f-s-13">Fecha de Recepción *</label>
                            <input type="datetime-local" id="fechaEnvioSegLog" value="<?php echo $date = date('Y-m-d\TH:i'); ?>" class="form-control" data-parsley-pattern="^\d\d\d\d-(0?[1-9]|1[0-2])-(0?[1-9]|[12][0-9]|3[01])T(|0[0-9]|1[0-9]|2[0-3]):([0-9]|[0-5][0-9])$" required/>
                        </div>
                        <div class="form-group <?php echo $mostrarInput ?>">
                            <label class="f-w-600 f-s-13">Fecha de Recepción *</label>
                            <input type="text" class="form-control" placeholder="<?php echo $FechaRecepcion ?>" disabled/>
                        </div>
                    </div>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                        <div class="form-group <?php echo $mostrarSelect ?>">
                            <label class="f-w-600 f-s-13">Persona que recibe *</label>
                            <input type="text" class="form-control" id="personaRecibe" placeholder="Nombre del gerente"  data-parsley-required="true"/>
                        </div>
                        <div class="form-group <?php echo $mostrarInput ?>">
                            <label class="f-w-600 f-s-13">Persona que recibe *</label>
                            <input type="text" class="form-control" placeholder="<?php echo $Recibe ?>" disabled/>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-9 col-sm-12 col-xs-12">
                        <div class="form-group <?php echo $mostrarSelectInput ?>">
                            <label class="f-w-600 f-s-13">Evidencia de Entrega *</label> 
                            <input id="evidenciaEntregaLog"  name="evidenciaEntregaLog[]" type="file" multiple />
                        </div>       
                        <div class="form-group <?php echo $mostrarInputFile ?>">
                            <label class="f-w-600 f-s-13">Evidencia de Entrega *</label>      
                            <div id="" class=" evidencia">
                                <?php
                                $archivosEntrega = explode(',', $archivoEnvio);
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
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12 text-center <?php echo $mostrarSelect ?>">
                        <a id="btnGuardarEntrega" class="btn btn-success btn-sm m-t-10 m-r-10 f-w-600 f-s-13">Guardar Entrega</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>   
