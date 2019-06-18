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
        <div id="panelSeguimiento45" class="panel panel-inverse panel-with-tabs" data-sortable-id="ui-unlimited-tabs-1">
            <div class="panel-heading p-0">
                <div class="tab-overflow">
                    <ul class="nav nav-tabs nav-tabs-inverse">
                        <li class="prev-button"><a href="javascript:;" data-click="prev-tab" class="text-success"><i class="fa fa-arrow-left"></i></a></li>
                        <li class="active"><a href="#Generales" data-toggle="tab" class="f-w-600 f-s-14">Información General</a></li>
                        <li class="f-w-600 f-s-14"><a href="#Instalados" data-toggle="tab">Equipos Instalados</a></li>
                        <li class="f-w-600 f-s-14"><a href="#Retirados" data-toggle="tab">Equipos Retirados</a></li>
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
                                            <select class="form-control" style="width:100%">
                                                <option value="">Selecciona . . .</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <div class="form-group">
                                        <label class="f-w-600 f-s-14">Sucursal:</label>
                                        <div class="input-group">
                                            <span class="input-group-addon">S</span>
                                            <select class="form-control" style="width:100%">
                                                <option value="">Selecciona . . .</option>
                                            </select>
                                            <div class="input-group-btn">
                                                <button type="button" class="btn btn-success"><i class="fa fa-save"></i></button>
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

                    </div>
                </div>
                <div class="tab-pane fade" id="Retirados">
                    <div class="panel-body">

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>