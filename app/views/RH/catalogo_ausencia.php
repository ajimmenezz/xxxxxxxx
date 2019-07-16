<!--Comenzando contenido-->
<div id="contentCatalogoAusencia" class="content">
    <!-- Comenzando titulo de la pagina -->
    <h1 class="page-header">Catálogo de Ausencia</h1>
    <!-- Finalizando titulo de la pagina -->
    <!-- Empezando panel CatalogoAusencia-->
    <div id="panelCatalogoAusencia" class="panel panel-inverse">
        <!--Empezando cabecera del panel-->
        <div class="panel-heading">
            <h4 class="panel-title">Catálogo de Asistencia</h4>
        </div>
        <!--Finalizando cabecera del panel-->
        
        <!-- Empezando contenido-->
        <div class="tab-content">
            <!--Empezando cuerpo del panel-->
            <div class="tab-pane fade active in" id="CatalogoAusencia">
                <div class="panel-body">
                    
                    <!--Comienza apartado para generar un motivo-->
                    <div class="row">
                        <div class="col-md-12">
                            <h4 class="m-t-10">Generar Motivo</h4>
                        </div>
                        <div class="col-md-12">
                            <div class="col-md-4 col-sm-4 col-xs-4">
                                <div class="form-group">
                                    <label>Motivo</label>
                                        <input id="inputMotivo" type="text" class="form-control" style="width: 100%"/>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-4">
                                <label>Observaciones</label>
                                <input id="inputObservaciones" type="text" class="form-control" style="width: 100%"/>
                            </div>
                            <div class="col-md-1 col-sm-1 col-xs-1">
                                <br>
                                <label id="agregarMotivo" class="btn btn-white" data-toggle="tooltip" data-placement="top" data-title="Agregar" >
                                    <i class="fa fa-2x fa-plus text-success"></i>
                                </label>  
                            </div>
                            <div class="col-md-1 col-sm-1 col-xs-1">
                                <br>
                                <label id="limpiarCampos" class="btn btn-white" data-toggle="tooltip" data-placement="top" data-title="Limpiar Campos">
                                    <i class="fa fa-2x fa-times text-danger"></i>
                                </label>  
                            </div>
                        </div>
                    </div>
                    <!--Finaliza apartado para generar un motivo-->
                    <div class="col-md-12">
                        <div class="underline m-b-15 m-t-15"></div>
                    </div>
                    <!--Comienza apartado de motivos generados-->
                    <div class="row">
                        <div class="col-md-12">
                            <h4 class="m-t-10">Lista de Motivos Generados</h4>
                        </div>
                        <!--Comenzando tabla de catalogos generados-->
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table id="table-catalogo-ausencia" class="table table-hover table-striped table-bordered no-wrap " style="cursor:pointer" width="100%">
                                    <thead>
                                        <tr>
                                            <th class="never">Id</th>
                                            <th class="all">Motivo</th>
                                            <th class="all">Observaciones</th>
                                            <th class="all"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <th>1</th>
                                            <th>CONSULTA MEDICO IMSS</th>
                                            <th>JUSTIFICADO PRESENTANDO COMPROBANTES SEGÚN POLITICAS DE ASISTENCIA</th>
                                            <th style="text-align: center"><i id="editarMotivo" data-toggle="tooltip" data-placement="top" data-title="Editar" class="fa fa-2x fa-pencil"></i>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i id="eliminarMotivo" data-toggle="tooltip" data-placement="top" data-title="Eliminar" class="fa fa-2x fa-trash-o text-danger"></i></th>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!--Finalizando tabla de catalogos generados-->
                    </div>
                    <!--Finaliza apartado de motivos generados-->
                    
                </div>
            </div>
            <!--Finalizando cuerpo del panel-->
        </div>
        <!-- Finalizando contenido-->
        
    </div>
    <!-- Finalizando panel CatalogoAusencia-->
</div>
<!--Finalizando contenido-->
