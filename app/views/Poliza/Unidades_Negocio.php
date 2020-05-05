<!-- Empezando #contenido -->
<div id="content" class="content">
    <!-- Empezando titulo de la pagina -->
    <h1 class="page-header">Catálogo Sublineas por Área</h1>
    <!-- Finalizando titulo de la pagina -->
    <!-- Empezando panel catálogo de Unidades de negocio -->
    <div id="seccionUnidadesNegocio" class="panel panel-inverse">
        <!--Empezando cabecera del panel-->
        <div class="panel-heading">
            <div class="panel-heading-btn">
                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>                            
                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>                            
            </div>
            <h4 class="panel-title">Catálogo de Unidades de Negocio</h4>
        </div>
        <!--Finalizando cabecera del panel-->
        <!--Empezando cuerpo del panel-->
        <div class="panel-body">

            <div id='listaUnidadesNegocio'>               

                <!--Empezando error--> 
                <div class="row">
                    <div class="col-md-12">
                        <div class="errorListaUnidadesNegocio"></div>
                    </div>
                </div>
                <!--Finalizando Error-->

                <div class="row">
                    <div class="col-md-12">                        
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6 col-xs-6">
                                    <h3 class="m-t-10">Unidades de Negocio</h3>
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
                <!--Finalizando tabla-->

            </div>
            <!--Finalizando cuerpo del panel-->
        </div>
        <!-- Finalizando panel catálogo de unidades de negocio -->
    </div>
    <!-- Finalizando #contenido -->