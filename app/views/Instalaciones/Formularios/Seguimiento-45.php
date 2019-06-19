<div class="row">
    <div class="col-md-6 col-sm-6 col-xs-12">
        <h1 class="page-header">Seguimiento Instalación</h1>
    </div>
    <div class="col-md-6 col-xs-6 text-right">
        <label id="btnRegresar" class="btn btn-success">
            <i class="fa fa fa-reply"></i> Regresar
        </label>
    </div>
</div>
<div class="row">
    <div class="col-ms-12">
        <div id="panelSeguimiento" class="panel panel-inverse panel-with-tabs" data-sortable-id="ui-unlimited-tabs-1">
            <div class="panel-heading p-0">
                <div class="tab-overflow">
                    <ul class="nav nav-tabs nav-tabs-inverse">
                        <li class="prev-button"><a href="javascript:;" data-click="prev-tab" class="text-success"><i class="fa fa-arrow-left"></i></a></li>
                        <li class="active"><a href="#Generales" data-toggle="tab" class="f-w-600 f-s-14">Información General</a></li>
                        <?php
                        if ($generales['IdEstatus'] != 1) {
                            ?>
                            <li class="f-w-600 f-s-14"><a href="#Instalados" data-toggle="tab">Equipos Instalados</a></li>
                            <li class="f-w-600 f-s-14"><a href="#Retirados" data-toggle="tab">Equipos Retirados</a></li>
                            <li class="f-w-600 f-s-14"><a href="#EvidenciasInstalacion" data-toggle="tab">Evidencias Instalación</a></li>
                            <li class="f-w-600 f-s-14"><a href="#EvidenciasRetiro" data-toggle="tab">Evidencias Retiro</a></li>
                        <?php
                    }
                    ?>
                        <li class="next-button"><a href="javascript:;" data-click="next-tab" class="text-success"><i class="fa fa-arrow-right"></i></a></li>
                    </ul>
                </div>
            </div>
            <div class="row m-t-10">
                <div class="col-md-offset-4 col-md-4 col-sm-offset-3 col-sm-6 col-xs-offset-1 col-xs-10">
                    <div id="errorMessageSeguimiento"></div>
                </div>
            </div>
            <div class="tab-content">
                <div class="tab-pane fade active in" id="Generales">
                    <div class="panel-body">
                        <?php
                        if ($generales['IdEstatus'] != 1) {
                            ?>
                            <div class="row">
                                <div class="col-md-12">
                                    <h5>Información general de solicitud y servicio</h5>
                                </div>
                                <div class="col-md-12">
                                    <div class="underline"></div>
                                </div>
                            </div>
                            <div class="row m-t-10">
                                <div class="col-md-3 col-sm-3 col-xs-12">
                                    <div class="form-group">
                                        <label class="f-w-600 f-s-14">Solicitud:</label>
                                        <div class="input-group">
                                            <span class="input-group-addon">#</span>
                                            <label class="f-s-14 form-control">234242</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-3 col-xs-12">
                                    <div class="form-group">
                                        <label class="f-w-600 f-s-14">Incidente SD:</label>
                                        <div class="input-group">
                                            <span class="input-group-addon">SD</span>
                                            <input type="text" class="form-control bg-white text-inverse f-w-600 f-s-14" value="462342" disabled="disabled" />
                                            <div class="input-group-btn">
                                                <button type="button" class="btn btn-info"><i class="fa fa-edit"></i></button>
                                                <button type="button" class="btn btn-warning"><i class="fa fa-info-circle"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-3 col-xs-12">
                                    <div class="form-group">
                                        <label class="f-w-600 f-s-14">Ticket:</label>
                                        <div class="input-group">
                                            <span class="input-group-addon">#</span>
                                            <label class="f-s-14 form-control">3424242</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-3 col-xs-12">
                                    <div class="form-group">
                                        <label class="f-w-600 f-s-14">Servicio:</label>
                                        <div class="input-group">
                                            <span class="input-group-addon">#</span>
                                            <label class="f-s-14 form-control">234242</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <div class="form-group">
                                        <label class="f-w-600 f-s-14">Solicita:</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-child"></i></span>
                                            <label class="f-s-14 form-control">Alonso Jiménez</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <div class="form-group">
                                        <label class="f-w-600 f-s-14">Atiende:</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-male"></i></span>
                                            <label class="f-s-14 form-control">Alonso Jiménez</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 col-sm-4 col-xs-12">
                                    <div class="form-group">
                                        <label class="f-w-600 f-s-14">Fecha Solicitud:</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-calendar-o"></i></span>
                                            <label class="f-s-14 form-control">2019/06/17 11:00</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-4 col-xs-12">
                                    <div class="form-group">
                                        <label class="f-w-600 f-s-14">Fecha Creación Servicio:</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-calendar-o"></i></span>
                                            <label class="f-s-14 form-control">2019/06/17 11:00</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-4 col-xs-12">
                                    <div class="form-group">
                                        <label class="f-w-600 f-s-14">Fecha Inicio Servicio:</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                            <label class="f-s-14 form-control">2019/06/17 11:00</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <div class="form-group">
                                        <label class="f-w-600 f-s-14">Cliente:</label>
                                        <div class="input-group">
                                            <span class="input-group-addon">C</span>
                                            <select class="form-control" style="width:100%" id="listClientes">
                                                <option value="">Selecciona . . .</option>
                                                <?php
                                                if (isset($clientes) && count($clientes) > 0) {
                                                    foreach ($clientes as $key => $value) {
                                                        $selected = '';
                                                        if ($value['Id'] == $generales['IdCliente']) {
                                                            $selected = ' selected="selected" ';
                                                        }
                                                        echo '<option value="' . $value['Id'] . '" ' . $selected . '>' . $value['Nombre'] . '</option>';
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <div class="form-group">
                                        <label class="f-w-600 f-s-14">Sucursal:</label>
                                        <div class="input-group">
                                            <span class="input-group-addon">S</span>
                                            <select class="form-control" style="width:100%" id="listSucursales">
                                                <option value="">Selecciona . . .</option>
                                                <?php
                                                if (isset($sucursales) && count($sucursales) > 0) {
                                                    foreach ($sucursales as $key => $value) {
                                                        $selected = '';
                                                        if ($value['Id'] == $generales['IdSucursal']) {
                                                            $selected = ' selected="selected" ';
                                                        }
                                                        echo '<option value="' . $value['Id'] . '" ' . $selected . '>' . $value['Nombre'] . '</option>';
                                                    }
                                                }
                                                ?>
                                            </select>
                                            <div class="input-group-btn">
                                                <button id="btnGuardarSucursal" type="button" class="btn btn-success"><i class="fa fa-save"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php
                    } else {
                        ?>
                            <div class="row">
                                <div class="col-md-12 text-center">
                                    <div class="alert alert-warning fade in m-b-15">
                                        <strong>Al parecer no se ha iniciado este servicio</strong>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 text-center">
                                    <a class="btn btn-info f-w-600" id="btnIniciarServicio">Iniciar Servicio</a>
                                    <a class="btn btn-danger f-w-600" id="btnRegresar">Regresar</a>
                                </div>
                            </div>
                        <?php
                    }
                    ?>
                    </div>
                </div>
                <div class="tab-pane fade" id="Instalados">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-12">
                                <h5>Información de la Impresora</h5>
                            </div>
                            <div class="col-md-12">
                                <div class="underline"></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 bg-silver-lighter">
                                <div class="row m-t-10">
                                    <div class="col-md-4 col-sm-4 col-xs-12">
                                        <div class="form-group">
                                            <label class="f-w-600 f-s-14">Modelo:</label>
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-print"></i></span>
                                                <label class="f-s-14 form-control">Impresora Lexmark MX521</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-4 col-xs-12">
                                        <div class="form-group">
                                            <label class="f-w-600 f-s-14">Serie*:</label>
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-barcode"></i></span>
                                                <input id="txtSerieImpresora" type="text" class="form-control f-w-600 f-s-14" placeholder="Serial Number" value="" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-4 col-xs-12">
                                        <div class="form-group">
                                            <label class="f-w-600 f-s-14">Ubicación*:</label>
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-map-marker"></i></span>
                                                <select id="listUbicacionesImpresora" class="form-control" style="width:100%">
                                                    <option value="">Selecciona . . .</option>
                                                    <?php
                                                    if (isset($areas) && count($areas) > 0) {
                                                        foreach ($areas as $key => $value) {
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
                                    <div class="col-md-4 col-sm-4 col-xs-12">
                                        <div class="form-group">
                                            <label class="f-w-600 f-s-14">IP Address*:</label>
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-sitemap"></i></span>
                                                <input id="txtIPImpresora" type="text" class="form-control f-w-600 f-s-14" placeholder="192.168.0.10" value="" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-4 col-xs-12">
                                        <div class="form-group">
                                            <label class="f-w-600 f-s-14">MAC Address*:</label>
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-sitemap"></i></span>
                                                <input id="txtMACImpresora" type="text" class="form-control f-w-600 f-s-14" placeholder="00:11:22:33:44:55" value="" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row m-t-25">
                            <div class="col-md-12">
                                <h5>Información de supresor de picos</h5>
                            </div>
                            <div class="col-md-12">
                                <div class="underline"></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 bg-silver-lighter">
                                <div class="row m-t-10">
                                    <div class="col-md-4 col-sm-4 col-xs-12">
                                        <div class="form-group">
                                            <label class="f-w-600 f-s-14">Modelo:</label>
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-plug"></i></span>
                                                <label class="f-s-14 form-control">Supresor de Picos Tripp Lite ISOBAR6 </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-4 col-xs-12">
                                        <div class="form-group">
                                            <label class="f-w-600 f-s-14">Serie*:</label>
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-barcode"></i></span>
                                                <input id="txtSerieSupresor" type="text" class="form-control f-w-600 f-s-14" placeholder="Serial Number" value="" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-4 col-xs-12">
                                        <div class="form-group">
                                            <label class="f-w-600 f-s-14">Ubicación*:</label>
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-map-marker"></i></span>
                                                <select id="listUbicacionesSupresor" class="form-control" style="width:100%">
                                                    <option value="">Selecciona . . .</option>
                                                    <?php
                                                    if (isset($areas) && count($areas) > 0) {
                                                        foreach ($areas as $key => $value) {
                                                            echo '<option value="' . $value['Id'] . '">' . $value['Nombre'] . '</option>';
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row m-t-25">
                            <div class="col-md-12 text-center">
                                <a id="btnGuardarInstalados" class="btn btn-success f-s-15 f-w-600 p-t-10 p-b-10 p-l-15 p-r-15">
                                    <i class="fa fa-save"></i> Guardar
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="Retirados">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-12">
                                <h5>Impresora Retirada</h5>
                            </div>
                            <div class="col-md-12">
                                <div class="underline"></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 bg-silver-lighter">
                                <div class="row m-t-10">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="f-w-600 f-s-14">Impresoras en complejo:</label>
                                            <select id="listImpresorasEnComplejo" class="form-control" style="width:100%">
                                                <option value="">Selecciona . . .</option>
                                                <?php
                                                if (isset($impresorasCensadas) && count($impresorasCensadas) > 0) {
                                                    foreach ($impresorasCensadas as $key => $value) {
                                                        echo '<option value="' . $value['Id'] . '">' . $value['Nombre'] . '</option>';
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row m-t-10">
                                    <div class="col-md-4 col-sm-4 col-xs-12">
                                        <div class="form-group">
                                            <label class="f-w-600 f-s-14">Modelo:</label>
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-print"></i></span>
                                                <select id="listImpresorasRetiro" class="form-control" style="width:100%">
                                                    <option value="">Selecciona . . .</option>
                                                    <?php
                                                    if (isset($kyocera) && count($kyocera) > 0) {
                                                        foreach ($kyocera as $key => $value) {
                                                            echo '<option value="' . $value['Id'] . '">' . $value['Nombre'] . '</option>';
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-4 col-xs-12">
                                        <div class="form-group">
                                            <label class="f-w-600 f-s-14">Serie*:</label>
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-barcode"></i></span>
                                                <input id="txtSerieImpresoraRetirada" type="text" class="form-control f-w-600 f-s-14" placeholder="Serial Number" value="" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-4 col-xs-12">
                                        <div class="form-group">
                                            <label class="f-w-600 f-s-14">Estatus*:</label>
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-map-marker"></i></span>
                                                <select id="listEstatusImpresoraRetirada" class="form-control" style="width:100%">
                                                    <option value="">Selecciona . . .</option>
                                                    <?php
                                                    if (isset($estatusRetiro) && count($estatusRetiro) > 0) {
                                                        foreach ($estatusRetiro as $key => $value) {
                                                            echo '<option value="' . $value['Id'] . '">' . $value['Nombre'] . '</option>';
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row m-t-25">
                            <div class="col-md-12 text-center">
                                <a id="btnGuardarRetirados" class="btn btn-success f-s-15 f-w-600 p-t-10 p-b-10 p-l-15 p-r-15">
                                    <i class="fa fa-save"></i> Guardar
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="EvidenciasInstalacion">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-12">
                                <h4>Subir Evidencias de Instalación</h4>
                            </div>
                            <div class="col-md-12">
                                <div class="underline"></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 bg-silver-lighter">
                                <div class="row m-t-10">
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label class="f-w-600 f-s-14">Tipo de Evidencia*:</label>
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-file"></i></span>
                                                <select id="listTiposEvidenciaInstalacion" class="form-control" style="width:100%">
                                                    <option value="">Selecciona . . .</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label class="f-w-600 f-s-14">Adjuntar Archivo*:</label>
                                            <input id="archivosInstalacion" name="archivosInstalacion[]" type="file" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row m-t-25">
                            <div class="col-md-12 text-center">
                                <a id="btnSubirEvidenciaInstalacion" class="btn btn-success f-s-15 f-w-600 p-t-10 p-b-10 p-l-15 p-r-15">
                                    <i class="fa fa-cloud-upload"></i> Subir Archivo
                                </a>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <h4>Evidencias de Instalación</h4>
                            </div>
                            <div class="col-md-12">
                                <div class="underline"></div>
                            </div>
                        </div>
                        <div class="row" id="divEvidenciasInstalacion"></div>
                    </div>
                </div>
                <div class="tab-pane fade" id="EvidenciasRetiro">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-12">
                                <h4>Subir Evidencias de Retiro</h4>
                            </div>
                            <div class="col-md-12">
                                <div class="underline"></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 bg-silver-lighter">
                                <div class="row m-t-10">
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label class="f-w-600 f-s-14">Tipo de Evidencia*:</label>
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-file"></i></span>
                                                <select id="listTiposEvidenciaRetiro" class="form-control" style="width:100%">
                                                    <option value="">Selecciona . . .</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label class="f-w-600 f-s-14">Adjuntar Archivo*:</label>
                                            <input id="archivosRetiro" name="archivosRetiro[]" type="file" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row m-t-25">
                            <div class="col-md-12 text-center">
                                <a id="btnSubirEvidenciaRetiro" class="btn btn-success f-s-15 f-w-600 p-t-10 p-b-10 p-l-15 p-r-15">
                                    <i class="fa fa-cloud-upload"></i> Subir Archivo
                                </a>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <h4>Evidencias de Retiro</h4>
                            </div>
                            <div class="col-md-12">
                                <div class="underline"></div>
                            </div>
                        </div>
                        <div class="row" id="divEvidenciasRetiro"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>