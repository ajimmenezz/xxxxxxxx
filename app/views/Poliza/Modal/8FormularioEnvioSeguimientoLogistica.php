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
        $mostrarInput = "hidden";
        $mostrarSelect = "hidden";
        $formularioSeguimientoEntrega = "";
        $mostrarSelectInput = "";
        $archivosEntrega = $value['ArchivosEntrega'];
        if (!empty($value['ArchivosEnvio'])) {
            $archivoEnvio = $value['ArchivosEnvio'];
            $mostrarInputFile = "";
        } else {
            $mostrarInputFile = "hidden";
        }

        if (!empty($value['IdTipoLugarRecepcion'])) {
            $camposSeguimientoEntrega = "hidden";
            $camposSeguimientoEntregaContrario = "";
        } else {
            $camposSeguimientoEntrega = "";
            $camposSeguimientoEntregaContrario = "hidden";
        }

        if ($value['CuentaSiccob'] === NULL) {
            $campoCuenta = "hidden";
        } else {
            $campoCuenta = "";
        }

        if ($value['IdUsuarioTransito'] === NULL) {
            $divPaqueteria = "";
            $divLogistica = "hidden";
            $chofer = "";
        } else {
            $divPaqueteria = "hidden";
            $divLogistica = "";
            $chofer = $value['Chofer'];
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
    $mostrarSelect = "";
    $mostrarInput = "";
    $mostrarInputFile = "hidden";
    $archivoEnvio = "";
    $archivosEntrega = "";
    $formularioSeguimientoEntrega = "hidden";
    $mostrarSelectInput = "hidden";
    $camposSeguimientoEntrega = "";
    $camposSeguimientoEntregaContrario = "hidden";
    $cuenta = "hidden";
    $campoCuenta = "hidden";
    $divPaqueteria = "hidden";
    $divLogistica = "hidden";
    $chofer = "";
}
?>
<div id="panelEnvioSeguimientoLog" class="panel panel-inverse">
    <div class="panel-heading">
        <h4 class="panel-title">7) Envío y Seguimiento Logística</h4>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <legend>Documentación de envío</legend>
            </div>
        </div>
        <?php
        if (!empty($informacionEnvioLog)) {
            foreach ($informacionEnvioLog as $value) {
                $paqueteria = $value['paqueteria'];
                $guia = $value['Guia'];
                $fechaEnvio = $value['FechaEnvio'];
            }
            $mostrarSelect = "hidden";
            $mostrarInput = "hidden";
        } else {
            $mostrarSelect = "";
            $mostrarInput = "";
        }
        ?>
        <form id="formDocumentacionEnvio" data-parsley-validate="true">
            <div class="row">
                <div class="col-md-3 col-sm-3">
                    <label>
                        <input class="tipoEnvio" type="radio" name="radioTipoEnvio" value="1" /> Paqueteria
                    </label>
                </div>
                <div class="col-md-3 col-sm-3">
                    <label>
                        <input class="tipoEnvio" type="radio" name="radioTipoEnvio" value="0" /> Logistica
                    </label>
                </div>
            </div>
            <div id="divPaqueteria" class="<?php echo $divPaqueteria; ?>">
                <div class="row">
                    <div class="col-md-6 col-sm-6 col-xs-12">
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
                        <div class="form-group <?php echo $formularioSeguimientoEntrega ?>">
                            <label class="f-w-600 f-s-13">Paquetería *</label>
                            <input type="text" class="form-control" placeholder="<?php echo $paqueteria ?>" disabled/>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <div class="form-group <?php echo $mostrarSelect ?>">
                            <label class="f-w-600 f-s-13"># Guía *</label>
                            <input type="text" class="form-control" id="guiaLogistica" placeholder=""  data-parsley-required="true"/>
                        </div>
                        <div class="form-group <?php echo $formularioSeguimientoEntrega ?>">
                            <label class="f-w-600 f-s-13"># Guía</label>
                            <input type="text" class="form-control" placeholder="<?php echo $guia ?>" disabled/>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div id="divCuentas" class="<?php echo $campoCuenta; ?>">
                        <div class="col-md-3 col-sm-3 m-t-30">
                            <label>
                                <input type="radio" name="radioCuenta" value="1" /> Cuenta Siccob
                            </label>
                        </div>
                        <div class="col-md-3 col-sm-3 m-t-30">
                            <label>
                                <input type="radio" name="radioCuenta" value="0" /> Cuenta Cliente
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <div id="divLogistica" class="<?php echo $divLogistica; ?>">
                <div class="row">
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <div class="form-group <?php echo $mostrarSelect ?>">
                            <label class="f-w-600 f-s-13">Chofer *</label>
                            <select id="listChofer" class="form-control" style="width: 100%" data-parsley-required="true">
                                <option value="">Selecciona . . .</option>
                                <?php
                                foreach ($choferes as $item) {
                                    echo '<option value="' . $item['Id'] . '">' . $item['Nombre'] . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group <?php echo $formularioSeguimientoEntrega ?>">
                            <label class="f-w-600 f-s-13">Chofer *</label>
                            <input type="text" class="form-control" placeholder="<?php echo $chofer ?>" disabled/>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class="form-group <?php echo $mostrarSelect ?>">
                        <label class="f-w-600 f-s-13">Fecha de Envío *</label>
                        <input type="datetime-local" id="fechaEnvio" value="<?php echo $date = date('Y-m-d\TH:i'); ?>" class="form-control" data-parsley-pattern="^\d\d\d\d-(0?[1-9]|1[0-2])-(0?[1-9]|[12][0-9]|3[01])T(|0[0-9]|1[0-9]|2[0-3]):([0-9]|[0-5][0-9])$" required/>
                    </div>
                    <div class="form-group <?php echo $formularioSeguimientoEntrega ?>">
                        <label class="f-w-600 f-s-13">Fecha de Envío *</label>
                        <input type="text" class="form-control" placeholder="<?php echo $FechaEnvio ?>" disabled/>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="form-group <?php echo $mostrarSelect ?>">
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
                    <div id="errorFormularioEnvioLogistica"></div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12 text-center <?php echo $mostrarSelect ?>">
                    <a id="btnGuardarEnvioLogistica" class="btn btn-primary btn-sm m-t-10 m-r-10 f-w-600 f-s-13"><i class="fa fa-save"></i> Guardar Envío</a>
                </div>
            </div>
        </form>
        <div id="divSeguimientoEntrega" class="<?php echo $formularioSeguimientoEntrega ?>">
            <div class="row m-t-20">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <legend>Seguimiento de Entrega</legend>
                </div>
            </div>
            <form id="formSeguimientoEntrega" data-parsley-validate="true">
                <div class="row">
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <div class="form-group <?php echo $camposSeguimientoEntrega ?>">
                            <label class="f-w-600 f-s-13">¿Donde se recibe? *</label>
                            <select id="listDondeRecibe" class="form-control" style="width: 100%" data-parsley-required="true">
                                <option value="">Selecciona . . .</option>
                                <?php
                                foreach ($dondeRecibe as $item) {
                                    echo '<option value="' . $item['Id'] . '">' . $item['Nombre'] . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group <?php echo $camposSeguimientoEntregaContrario ?>">
                            <label class="f-w-600 f-s-13">¿Donde se recibe? *</label>
                            <input type="text" class="form-control" placeholder="<?php echo $DondeRecibe ?>" disabled/>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <div id="selectSucursal" class="form-group hidden">
                            <label class="f-w-600 f-s-13">Complejo  *</label>
                            <select id="listSucursal" class="form-control" style="width: 100%" data-parsley-required="true">
                                <option value="">Selecciona . . .</option>
                                <?php
                                foreach ($sucursales as $item) {
                                    echo '<option value="' . $item['Id'] . '">' . $item['Nombre'] . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group <?php echo $camposSeguimientoEntregaContrario ?>">
                            <label class="f-w-600 f-s-13">Complejo (Solo en caso de ser complejo) *</label>
                            <input type="text" class="form-control" placeholder="<?php echo $Sucursal ?>" disabled/>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <div class="form-group <?php echo $camposSeguimientoEntrega ?>">
                            <label class="f-w-600 f-s-13">Fecha de Recepción *</label>
                            <input type="datetime-local" id="fechaEnvioSegLog" value="<?php echo $date = date('Y-m-d\TH:i'); ?>" class="form-control" data-parsley-pattern="^\d\d\d\d-(0?[1-9]|1[0-2])-(0?[1-9]|[12][0-9]|3[01])T(|0[0-9]|1[0-9]|2[0-3]):([0-9]|[0-5][0-9])$" required/>
                        </div>
                        <div class="form-group <?php echo $camposSeguimientoEntregaContrario ?>">
                            <label class="f-w-600 f-s-13">Fecha de Recepción *</label>
                            <input type="text" class="form-control" placeholder="<?php echo $FechaRecepcion ?>" disabled/>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <div class="form-group <?php echo $camposSeguimientoEntrega ?>">
                            <label class="f-w-600 f-s-13">Persona que recibe *</label>
                            <input type="text" class="form-control" id="personaRecibe" placeholder=""  data-parsley-required="true"/>
                        </div>
                        <div class="form-group <?php echo $camposSeguimientoEntregaContrario ?>">
                            <label class="f-w-600 f-s-13">Persona que recibe *</label>
                            <input type="text" class="form-control" placeholder="<?php echo $Recibe ?>" disabled/>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="form-group <?php echo $camposSeguimientoEntrega ?>">
                            <label class="f-w-600 f-s-13">Evidencia de Entrega *</label> 
                            <input id="evidenciaEntregaLog"  name="evidenciaEntregaLog[]" type="file" multiple />
                        </div>       
                        <div class="form-group <?php echo $camposSeguimientoEntregaContrario ?>">
                            <label class="f-w-600 f-s-13">Evidencia de Entrega *</label>      
                            <div id="" class=" evidencia">
                                <?php
                                $archivosEntrega = explode(',', $archivosEntrega);
                                foreach ($archivosEntrega as $value) {
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
                        <div id="errorGuardarEntrega"></div>
                    </div>
                </div>
                <div id="divBotonGuardarEntrega" class="row <?php echo $camposSeguimientoEntrega ?>">
                    <div class="col-md-12 col-sm-12 col-xs-12 text-center">
                        <a id="btnGuardarEntrega" class="btn btn-primary btn-sm m-t-10 m-r-10 f-w-600 f-s-13"><i class="fa fa-save"></i> Guardar Entrega</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>   
