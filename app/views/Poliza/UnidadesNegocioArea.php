<!-- Empezando #contenido -->
<div id="content" class="content">
    <!-- Empezando titulo de la pagina -->
    <h1 class="page-header">Catálogo Area por unidad de negocio</h1>
    <!-- Finalizando titulo de la pagina -->
    <!-- Empezando panel catálogo de Unidades de negocio -->
    <div id="seccionUnidadesNegocio" class="panel panel-inverse">
        <!--Empezando cabecera del panel-->
        <div class="panel-heading">
        </div>
        <!--Finalizando cabecera del panel-->
        <!--Empezando cuerpo del panel-->
        <div class="panel-body">

            <div id='listaUnidadesNegocio'>               

                <!--Empezando error--> 
                <div class="row">
                    <div class="col-md-12">
                        <div class="errorUnidadesNegocio"></div>
                    </div>
                </div>
                <!--Finalizando Error-->

                <div class="row">
                    <div class="col-md-12">                        
                        <div class="form-group">
                            <div class="row">
                                <div id="titulo" class="col-md-6 col-xs-6">
                                    <h3 class="m-t-10">Unidades de Negocio</h3>
                                </div>
                                <div id="subtitulo" class="col-md-6 col-xs-6 hidden">
                                    <h3 id="nombreUnidad" class="m-t-10"></h3>
                                </div>
                                <div class="col-md-6 col-xs-6">
                                    <div id="btnEvent" class="form-group text-right hidden">
                                        <a href="javascript:;" class="btn btn-success btn-lg " id="btnRegresar"><i class="fa fa-reply"></i> Regresar</a>
                                    </div>
                                </div>
                            </div>

                            <!--Empezando Separador-->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="underline m-b-15 m-t-15"></div>
                                </div>
                            </div>
                            <!--Finalizando Separador-->
                        </div>    
                    </div> 
                </div>

                <!--Empezando tabla  -->
                <div id="tablaUnidades">
                    <div class="table-responsive">
                        <table id="data-table-unidad-negocios" class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%">
                            <thead>
                                <tr>
                                    <th class="never">Id</th>
                                    <th class="all">Cliente</th>
                                    <th class="all">Unidad de Negocio</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (!empty($datos['ListaUnidadeNegocio'])) {
                                    foreach ($datos['ListaUnidadeNegocio'] as $key => $value) {
                                        echo '<tr>';
                                        echo '<td>' . $value['Id'] . '</td>';
                                        echo '<td>' . $value['Cliente'] . '</td>';
                                        echo '<td>' . $value['Nombre'] . '</td>';
                                        echo '</tr>';
                                    }
                                }
                                ?>                                        
                            </tbody>
                        </table>
                    </div>
                </div>
                <!--Finalizando tabla-->

                <!--Empezando tabla  -->
                <div id="tablaAreas" class="hidden">
                    <div class="col-md-12">
                        <div class="col-md-8">
                            <form id="formAgregarAreas" data-parsley-validate="true" enctype="multipart/form-data">
                                <div id="addAreaAtencion" class="col-md-6">
                                    <div class="form-group">
                                        <label>Area de Atención</label>
                                        <select id="selectArea" class="form-control" style="width: 100%">
                                            <option value="">Seleccionar</option>
                                        </select>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="col-md-2">                    
                            <div class="form-group text-right">
                                <br>
                                <a href="javascript:;" class="btn btn-success" id="agregarArea"><i class="fa fa-plus"></i> Agregar</a>
                            </div>
                        </div>
                        <div class="col-md-2">                    
                            <div class="form-group text-right">
                                <br>
                                <label id="btnEliminarArea" href="#modalEliminarArea" class="btn btn-danger bloqueoConclusionBtn" data-toggle="modal"><i class="fa fa-exclamation-triangle"></i> Eliminar</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table id="data-table-area" class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%">
                                <thead>
                                    <tr>
                                        <th class="never">Id</th>
                                        <th class="all">Area de Atención</th>
                                    </tr>
                                </thead>
                                <tbody>                                     
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-4 col-md-offset-5 col-sm-offset-4">
                        <a href="javascript:;" class="btn btn-success btn-lg " id="guardarArea">Guardar</a>
                    </div>
                </div>
                <!--Finalizando tabla-->

            </div>
            <!--Finalizando cuerpo del panel-->
        </div>
        <!-- Finalizando panel catálogo de unidades de negocio -->
    </div>
</div>
<!-- Finalizando #contenido -->

<!--Empieza modal de reportar problemas-->
<div id="modalEliminarArea" class="modal modal-message fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <!--Empieza titulo del modal-->
            <div class="modal-header" style="text-align: center">
                <h4 class="modal-title">Eliminar Área</h4>
            </div>
            <!--Finaliza titulo del modal-->
            <!--Empieza cuerpo del modal-->
            <div class="modal-body">
                <div class="col-md-12">
                    <form id="formEliminarArea" data-parsley-validate="true" enctype="multipart/form-data">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label id="labelEliminar"></label>
                                <select id="selectEliminarArea" class="form-control" style="width: 100%" data-parsley-required="true">
                                    <option value="">Seleccionar</option>
                                </select>
                            </div>
                        </div>
                    </form>
                </div>   
            </div>
            <!--Finaliza cuerpo del modal-->
            <!--Empieza pie del modal-->
            <div class="modal-footer text-center">
                <a id="btnAceptarEliminarArea" class="btn btn-sm btn-success"><i class="fa fa-check"></i> Aceptar</a>
                <a id="btnCancelar" class="btn btn-sm btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> Cerrar</a>
            </div>
            <!--Finaliza pie del modal-->
        </div>
    </div>
</div>
<!--Finaliza modal de reportar problemas-->