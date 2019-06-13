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
                        <!-- <li class=""><a href="#Conceptos" data-toggle="tab">Conceptos</a></li>
                        <li class=""><a href="#Montos" data-toggle="tab">Montos Por Usuario</a></li> -->
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
                        if ($generales['IdEstatus'] != 1) { } else {
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
            </div>
        </div>
    </div>
</div>