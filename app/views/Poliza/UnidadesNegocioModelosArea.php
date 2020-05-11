<!-- Empezando #contenido -->
<div id="content" class="content">
    <!-- Empezando titulo de la pagina -->
    <h1 class="page-header">Catálogo Modelos por Área</h1>
    <!-- Finalizando titulo de la pagina -->
    <!-- Empezando panel catálogo de Unidades de negocio -->
    <div id="seccionUnidadesNegocio" class="panel panel-inverse">

        <!--Empezando cuerpo del panel-->
        <div class="panel-body">

            <div id='listaUnidadesNegocio'>               

                <!--Empezando error--> 
                <div class="row">
                    <div class="col-md-12">
                        <div class="errorUnidadesModelo"></div>
                    </div>
                </div>
                <!--Finalizando Error-->
                
                <!--empieza cabecera del cuerpo-->
                <div class="row">
                    <div class="col-md-12">                        
                        <div class="form-group">
                            <div class="row">
                                <div id="titulo" class="col-md-6 col-xs-6">
                                    <h3 class="m-t-10">Unidades de Negocio</h3>
                                </div>
                                <div id="subtitulo" class="col-md-6 col-xs-6 hidden">
                                    <h3 id="nombreSubtitulo" class="m-t-10"></h3>
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
                <!--termina cabecera del cuerpo-->

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
                <div id="tablaModelos" class="hidden">
                    <div class="col-md-12">
                        <div class="col-md-10"></div>
                        <div class="col-md-2">                    
                            <div class="form-group text-right">
                                <br>
                                <a href="javascript:;" class="btn btn-success" id="agregarArea"><i class="fa fa-plus"></i> Agregar</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table id="data-table-modelo" class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%">
                                <thead>
                                    <tr>
                                        <th class="never">Id</th>
                                        <th class="all">Area de Atención</th>
                                        <th class="all">Modelos</th>
                                    </tr>
                                </thead>
                                <tbody>                                     
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!--Finalizando tabla-->

            </div>
            <!--Finalizando cuerpo del panel-->
        </div>
        <!-- Finalizando panel catálogo de unidades de negocio -->
    </div>
    <!-- Finalizando #contenido -->
