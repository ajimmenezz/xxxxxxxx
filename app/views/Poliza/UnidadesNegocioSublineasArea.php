<!-- Empezando #contenido -->
<div id="content" class="content">
    <!-- Empezando titulo de la pagina -->
    <h1 class="page-header">Catálogo Sublineas por Área</h1>
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
                                    <h3 id="nombreUnidad" class="m-t-10"><h5 id="sublineaArea" class="m-t-10"></h5></h3>
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
                <div id="tablaSublineas" class="hidden">
                    <div class="table-responsive">
                        <table id="data-table-sublineas" class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%">
                            <thead>
                                <tr>
                                    <th class="never">Id</th>
                                    <th class="all">Area de Atención</th>
                                    <th class="all">Sublinea</th>
                                    <th class="all">Cantidad</th>
                                </tr>
                            </thead>
                            <tbody>                                     
                            </tbody>
                        </table>
                    </div>
                </div>
                <!--Finalizando tabla-->

                <!--Empezando tabla  -->
                <div id="tablaInfoSublineas"  class="hidden">
                    <div class="col-md-12">                    
                        <div class="col-md-4">
                            <form id="formAgregarSublinea" data-parsley-validate="true" enctype="multipart/form-data">
                                <div class="form-group">
                                    <label>Sublinea</label>
                                    <select id="selectSublinea" class="form-control" data-parsley-required="true" style="width: 100%" data-parsley-required="true">
                                        <option value="">Seleccionar</option>
                                    </select>
                                </div>
                            </form>
                        </div>
                        <div class="col-md-2">                    
                            <div class="form-group text-right">
                                <br>
                                <a href="javascript:;" class="btn btn-success btn-lg " id="agregarSublinea"><i class="fa fa-plus"></i> Agregar</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table id="data-table-infoSublineas" class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%">
                                <thead>
                                    <tr>
                                        <th class="idNever">Id</th>
                                        <th class="idNever">IdSublinea</th>
                                        <th class="all">Sublinea</th>
                                        <th class="all">Cantidad</th>
                                    </tr>
                                </thead>
                                <tbody>                                      
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-4 col-md-offset-5 col-sm-offset-4">
                        <a href="javascript:;" class="btn btn-success btn-lg " id="guardarSublinea">Guardar</a>
                    </div>
                </div>
                <!--Finalizando tabla-->

            </div>
            <!--Finalizando cuerpo del panel-->
        </div>
        <!-- Finalizando panel catálogo de unidades de negocio -->
    </div>
    <!-- Finalizando #contenido -->
