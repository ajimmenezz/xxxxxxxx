<div class="content" id="agendaContent">
    <h1 class="page-header">Agenda</h1>
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="panel panel-inverse panel-with-tabs" data-sortable-id="ui-unlimited-tabs-1">
                <div class="panel-heading p-0">
                    <div class="panel-heading-btn m-r-10 m-t-10">
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                    </div>
                    <!-- begin nav-tabs -->
                    <div class="tab-overflow overflow-right">
                        <ul class="nav nav-tabs nav-tabs-inverse">
                            <li class="prev-button" style=""><a href="javascript:;" data-click="prev-tab" class="text-success"><i class="fa fa-arrow-left"></i></a></li>
                            <li class="active"><a href="#nav-tab-calendar" data-toggle="tab">Calendario</a></li>
                            <li class=""><a href="#nav-tab-program" data-toggle="tab">Programar Mis Servicios</a></li>
                            <li class=""><a href="#nav-tab-99" data-toggle="tab">Tester</a></li>
                            <li class="next-button" style=""><a href="javascript:;" data-click="next-tab" class="text-success"><i class="fa fa-arrow-right"></i></a></li>
                        </ul>
                    </div>
                </div>
                <div class="tab-content">
                    <div class="tab-pane fade active in" id="nav-tab-calendar">
                        <div class="row">
                            <div id="calendar" class="col-md-12 col-sm-12 col-xs-12">

                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="nav-tab-program">
                        <div class="note note-warning">
                            <h4>Programar mis servicios.</h4>
                            <p class="f-s-13 f-w-600">
                                En esta sección puedes asignar la fecha de atención para tus servicios
                                pendientes. Al hacerlo, se creará un evento en el calendario de Google
                                y será posible ver tus compromisos de atención.
                            </p>
                        </div>
                        <div id="pendingServices">

                        </div>

                    </div>
                    <div class="tab-pane fade" id="nav-tab-99">
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-xs-12">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="basicModal" tabindex="-1" role="dialog" aria-labelledby="basicModalTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="basicModalTitle"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button id="saveEventChanges" type="button" class="btn btn-primary">Guardar cambios</button>
            </div>
        </div>
    </div>
</div>