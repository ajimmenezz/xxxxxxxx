<div id="seccionCatalogos" class="content">

    <!-- Empezando titulo de la pagina -->
    <h1 class="page-header">Catálogos <small> Comprobaciones</small></h1>
    <!-- Finalizando titulo de la pagina -->

    <div id="panel-catalogos" class="panel panel-inverse panel-with-tabs" data-sortable-id="ui-unlimited-tabs-1">

        <!--Empezando Pestañas para definir la seccion-->
        <div class="panel-heading p-0">
            <div class="btn-group pull-right" data-toggle="buttons">

            </div>
            <div class="panel-heading-btn m-r-10 m-t-10">                                                 
            </div>
            <div class="tab-overflow">
                <ul class="nav nav-tabs nav-tabs-inverse">
                    <li class="prev-button"><a href="javascript:;" data-click="prev-tab" class="text-success"><i class="fa fa-arrow-left"></i></a></li>
                    <li class="active"><a href="#ConceptosFF" data-toggle="tab">Conceptos Fondo Fijo</a></li>
                    <li class=""><a href="#FFxTecnico" data-toggle="tab">Fondo Fijo x Técnico</a></li>
                    <li class="next-button"><a href="javascript:;" data-click="next-tab" class="text-success"><i class="fa fa-arrow-right"></i></a></li>
                </ul>
            </div>
        </div>
        <!--Finalizando Pestañas para definir la seccion-->

        <!--Empezando error--> 
        <div class="row m-t-10">                       
            <div class="col-md-offset-4 col-md-4 col-sm-offset-3 col-sm-6 col-xs-offset-1 col-xs-10">
                <div id="errorMessage"></div>
            </div>
        </div>
        <!--Finalizando Error-->

        <!--Empezando contenido de catalogo de fallas poliza-->
        <div class="tab-content">

            <!--Empezando la seccion Conceptos Fondo Fijo-->
            <div class="tab-pane fade active in" id="ConceptosFF">
                <div class="panel-body">                                        
                    <div class="row">
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <h4>Lista de Conceptos</h4>                            
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12 pull-right">                            
                            <button class="btn btn-success pull-right" id="btnAddConcepto"><i class="fa fa-plus text-white"></i></button>                                        
                        </div>
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="underline m-b-10"></div>
                        </div>
                    </div>                    
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="table-responsive">
                                <table id="table-conceptos" class="table table-bordered table-striped table-condensed">
                                    <thead>
                                        <tr>
                                            <th class="none">Id</th>
                                            <th class="all">Concepto</th>
                                            <th class="all">Tipo de Comprobante</th>
                                            <th class="all">¿Extraordinario?</th>
                                            <th class="all">Monto</th>
                                            <th class="all">Alternativos</th>
                                            <th class="all">Estatus</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if (isset($datos['Sistemas']) && !empty($datos['Sistemas'])) {
                                            foreach ($datos['Sistemas'] as $key => $value) {
                                                echo ""
                                                . "<tr>"
                                                . " <td>" . $value['Id'] . "</td>"
                                                . " <td>" . $value['Flag'] . "</td>"
                                                . " <td>" . $value['Nombre'] . "</td>"
                                                . " <td>" . $value['Estatus'] . "</td>"
                                                . "</tr>";
                                            }
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>        
            <!--Empezando la seccion Conceptos Fondo Fijo-->

            <!--Empezando la seccion Fondo Fijo x Tecnico-->
            <div class="tab-pane fade" id="FFxTecnico">
                <div class="panel-body">                                        
                    <div class="row">
                        <div class="col-md-6 col-md-offset-6 col-sm-offset-6 col-sm-6 col-xs-offset-0 col-xs-12">
                            <div class="input-group">
                                <input type="text" id="txtNuevoTipo" class="form-control" placeholder="Nuevo Tipo de Proyecto">
                                <span role="button" id="btnAddTipo" class="input-group-addon bg-aqua"><i class="fa fa-plus text-white"></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <h4>Lista de Tipos de Proyecto</h4>
                            <div class="underline m-b-10"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="table-responsive">
                                <table id="table-tipos" class="table table-bordered table-striped table-condensed">
                                    <thead>
                                        <tr>
                                            <th class="none">Id</th>
                                            <th class="none">Flag</th>
                                            <th class="all">Tipo de Proyecto</th>
                                            <th class="all" style="width: 25%">Estatus</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if (isset($datos['Tipos']) && !empty($datos['Tipos'])) {
                                            foreach ($datos['Tipos'] as $key => $value) {
                                                echo ""
                                                . "<tr>"
                                                . " <td>" . $value['Id'] . "</td>"
                                                . " <td>" . $value['Flag'] . "</td>"
                                                . " <td>" . $value['Nombre'] . "</td>"
                                                . " <td>" . $value['Estatus'] . "</td>"
                                                . "</tr>";
                                            }
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>        
            <!--Empezando la seccion Fondo Fijo x Tecnico-->

        </div>
        <!--Finalizando contenido de catalogo de proyectos-->  

    </div>
</div>

<!--Empezando seccion para mostrar los fomularios de catalogos de fallas -->
<div id="divAgregarEditar" class="content" style="display: none"></div>
<!-- Finalizando seccion para mostrar los fomularios de catalogos de fallas --> 

<div class="modal fade" id="modalEdit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body"></div>
            <div class="modal-footer">
                <div id="error-in-modal"></div>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="button" id="btnGuardarCambios" class="btn btn-primary">Guardar</button>
            </div>
        </div>
    </div>
</div>