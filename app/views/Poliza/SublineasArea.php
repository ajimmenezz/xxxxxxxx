<!-- Empezando #contenido -->
<div id="content" class="content">
    <!-- Empezando titulo de la pagina -->
    <h1 class="page-header">Sublinea por</h1>
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

            <!--Empezando formulario -->
            <div id="formularioUnidadesNegocio" class="row m-t-10" >
            </div>
            <!--Finalizando formulario-->

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
                                    <h3 class="m-t-10">Lista de Unidades de Negocio</h3>
                                </div>
                                <div class="col-md-6 col-xs-6">
                                    <div class="form-group text-right">
                                        <a href="javascript:;" class="btn btn-success btn-lg " id="btnAgregarUnidadNegocio"><i class="fa fa-plus"></i> Agregar</a>
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
                <div class="table-responsive">
                    <table id="data-table-unidad-negocios" class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%">
                        <thead>
                            <tr>
                                <th class="never">Id</th>
                                <th class="all">Nombre</th>
                                <th class="all">Estatus</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
//                            if (!empty($datos['ListaUnidadeNegocio'])) {
//                                foreach ($datos['ListaUnidadeNegocio'] as $key => $value) {
//                                    echo '<tr>';
//                                    echo '<td>' . $value['Id'] . '</td>';
//                                    echo '<td>' . $value['Nombre'] . '</td>';
//                                    if ($value['Flag'] === '1') {
//                                        echo '<td data-flag="' . $value['Flag'] . '">Activo</td>';
//                                    } else {
//                                        echo '<td data-flag="' . $value['Flag'] . '">Inactivo</td>';
//                                    }
//                                    echo '</tr>';
//                                }
//                            }
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