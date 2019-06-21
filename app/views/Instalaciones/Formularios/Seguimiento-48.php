<div class="row">
    <div class="col-md-6 col-sm-6 col-xs-12">
        <h1 class="page-header">Seguimiento Instalación</h1>
    </div>
    <div class="col-md-6 col-xs-6 text-right">
        <label id="btnPdf" class="btn btn-danger f-w-600">
            <i class="fa fa-file-pdf-o"></i> Exportar
        </label>
        <label id="btnRegresar" class="btn btn-success f-w-600">
            <i class="fa fa-reply"></i> Regresar
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
                            <li class="f-w-600 f-s-14"><a href="#EvidenciasInstalacion" data-toggle="tab">Evidencias Instalación</a></li>
                            <li class="f-w-600 f-s-14"><a href="#Materiales" data-toggle="tab">Materiales</a></li>
                            <li class="f-w-600 f-s-14"><a href="#Firmas" data-toggle="tab">Firmas y Cierre</a></li>
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
                                            <label class="f-s-14 form-control"><?php echo $generales['IdSolicitud']; ?></label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-3 col-xs-12">
                                    <div class="form-group">
                                        <label class="f-w-600 f-s-14">Incidente SD:</label>
                                        <div class="input-group">
                                            <span class="input-group-addon">SD</span>
                                            <input type="text" class="form-control bg-white text-inverse f-w-600 f-s-14" value="<?php echo $generales['SD']; ?>" disabled="disabled" />
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
                                            <label class="f-s-14 form-control"><?php echo $generales['Ticket']; ?></label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-3 col-xs-12">
                                    <div class="form-group">
                                        <label class="f-w-600 f-s-14">Servicio:</label>
                                        <div class="input-group">
                                            <span class="input-group-addon">#</span>
                                            <label class="f-s-14 form-control"><?php echo $generales['Id']; ?></label>
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
                                            <label class="f-s-14 form-control"><?php echo $generales['Solicita']; ?></label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <div class="form-group">
                                        <label class="f-w-600 f-s-14">Atiende:</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-male"></i></span>
                                            <label class="f-s-14 form-control"><?php echo $generales['Atiende']; ?></label>
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
                                            <label class="f-s-14 form-control"><?php echo $generales['FechaSolicitud']; ?></label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-4 col-xs-12">
                                    <div class="form-group">
                                        <label class="f-w-600 f-s-14">Fecha Creación Servicio:</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-calendar-o"></i></span>
                                            <label class="f-s-14 form-control"><?php echo $generales['FechaCreacion']; ?></label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-4 col-xs-12">
                                    <div class="form-group">
                                        <label class="f-w-600 f-s-14">Fecha Inicio Servicio:</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                            <label class="f-s-14 form-control"><?php echo $generales['FechaInicio']; ?></label>
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
                                <h5>Información de la antena 1</h5>
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
                                            <label class="f-w-600 f-s-14">Modelo de la antena:</label>
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-wifi"></i></span>
                                                <select id="listModelosAntenas1" class="form-control" style="width:100%">
                                                    <option value="">Selecciona . . .</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-4 col-xs-12">
                                        <div class="form-group">
                                            <label class="f-w-600 f-s-14">Serie de la antena*:</label>
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-barcode"></i></span>
                                                <input id="txtSerieAntena1" type="text" class="form-control f-w-600 f-s-14" placeholder="Serial Number" value="" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-4 col-xs-12">
                                        <div class="form-group">
                                            <label class="f-w-600 f-s-14">Ubicación de la antena*:</label>
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-map-marker"></i></span>
                                                <select id="listUbicacionesAntena1" class="form-control" style="width:100%">
                                                    <option value="">Selecciona . . .</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4 col-sm-4 col-xs-12">
                                        <div class="form-group">
                                            <label class="f-w-600 f-s-14">MAC Address*:</label>
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-sitemap"></i></span>
                                                <input id="txtMACImpresora1" type="text" class="form-control f-w-600 f-s-14" placeholder="00:11:22:33:44:55" value="" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-4 col-xs-12">
                                        <div class="form-group">
                                            <label class="f-w-600 f-s-14">¿Ocupa POE?*:</label>
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-flash"></i></span>
                                                <div class="form-control">
                                                    <label class="radio-inline">
                                                        <input type="radio" name="poe1" value="0" checked="">
                                                        No
                                                    </label>
                                                    <label class="radio-inline">
                                                        <input type="radio" name="poe1" value="1">
                                                        Si
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="divSeriePoe1" class="col-md-4 col-sm-4 col-xs-12 hidden">
                                        <div class="form-group">
                                            <label class="f-w-600 f-s-14">Serie POE*:</label>
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-barcode"></i></span>
                                                <input id="txtSeriePOE1" type="text" class="form-control f-w-600 f-s-14" placeholder="C1811659300" value="" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4 col-sm-4 col-xs-12">
                                        <div class="form-group">
                                            <label class="f-w-600 f-s-14">Modelo del Switch*:</label>
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-download"></i></span>
                                                <select id="listModelosSwitch1" class="form-control" style="width:100%">
                                                    <option value="">Selecciona . . .</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-4 col-xs-12">
                                        <div class="form-group">
                                            <label class="f-w-600 f-s-14">Número del Switch*:</label>
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-sort-numeric-asc"></i></span>
                                                <input id="txtContadorImpresora" type="number" class="form-control f-w-600 f-s-14" placeholder="0" value="" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-4 col-xs-12">
                                        <div class="form-group">
                                            <label class="f-w-600 f-s-14">Número del Puerto en Switch*:</label>
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-sort-numeric-asc"></i></span>
                                                <input id="txtContadorImpresora" type="number" class="form-control f-w-600 f-s-14" placeholder="0" value="" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row m-b-15">
                                    <div class="col-md-12 text-center">
                                        <a id="btnGuardarInstalados" class="btn btn-success btn-xs f-s-15 f-w-600 p-l-20 p-r-20">
                                            <i class="fa fa-save"></i> Guardar Antena 1
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="EvidenciasInstalacion">
                    <div class="panel-body">
                        <div id="divFormularioEvidenciasInstalacion">
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
                <div class="tab-pane fade" id="Materiales">
                    <div class="panel-body">
                        <div id="divMateriales">
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="Firmas">
                    <div class="panel-body">
                        <div id="divFirmas">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>