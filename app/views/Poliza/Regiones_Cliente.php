<!-- Empezando #contenido -->
<div id="content" class="content">

    <!-- Empezando titulo de la pagina -->
    <h1 class="page-header">Catálogo <small>Regiones de Cliente</small></h1>
    <!-- Finalizando titulo de la pagina -->

    <!-- Empezando panel catálogo regiones de cliente -->
    <div id="seccionRegionesClente" class="panel panel-inverse">

        <!--Empezando cabecera del panel-->
        <div class="panel-heading">
            <div class="panel-heading-btn">
                <label id="btnRegresarRegionesCliente" class="btn btn-success btn-xs hidden">
                    <i class="fa fa fa-reply"></i> Regresar
                </label>   
                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>                            
                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>                            
            </div>
            <h4 class="panel-title">Catálogo Regiones de Cliente</h4>
        </div>
        <!--Finalizando cabecera del panel-->

        <!--Empezando cuerpo del panel-->
        <div class="panel-body">
            <div class="panel-body">

                <!--Empezando el formulario Regiones de Cliente -->
                <div id="formularioRegionesCliente">
                </div>
                <!--Finalizando formulario Regiones de Cliente-->

                <!--Empezando error--> 
                <div class="row m-t-10">
                    <div class="col-md-12">
                        <div class="errorRegionesCliente"></div>
                    </div>
                </div>   
                <!--Finalizando Error-->

                <!--Empezando tabla -->
                <div id='listaRegionesCliente'> 
                    <div class="row">
                        <div class="col-md-12">                        
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6 col-xs-6">
                                        <h3 class="m-t-10">Lista de Regiones Cliente</h3>
                                    </div>
                                    <div class="col-md-6 col-xs-6">
                                        <div class="form-group text-right">
                                            <a href="javascript:;" class="btn btn-success btn-lg " id="btnAgregarRegionCliente"><i class="fa fa-plus"></i> Agregar</a>
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
                    <div class="table-responsive">
                        <table id="data-table-regiones-cliente" class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%">
                            <thead>
                                <tr>
                                    <th class="never">Id</th>
                                    <th class="all">Cliente</th>
                                    <th class="all">Región</th>
                                    <th class="all">Responsable Cliente</th>
                                    <th class="all">Email</th>
                                    <th class="all">Responsable Siccob</th>
                                    <th class="all">Estatus</th>
                                    <th class="never">IdCliente</th>
                                    <th class="never">IdResposableInterno</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (!empty($datos['ListaRegionesCliente'])) {
                                    foreach ($datos['ListaRegionesCliente'] as $key => $value) {
                                        echo '<tr>';
                                        echo '<td>' . $value['Id'] . '</td>';
                                        echo '<td>' . $value['Cliente'] . '</td>';
                                        echo '<td>' . $value['Nombre'] . '</td>';
                                        echo '<td>' . $value['ResponsableCliente'] . '</td>';
                                        echo '<td>' . $value['Email'] . '</td>';
                                        echo '<td>' . $value['ResponsableInterno'] . '</td>';
                                        if ($value['Flag'] === '1') {
                                            echo '<td data-flag="' . $value['Flag'] . '">Activo</td>';
                                        } else {
                                            echo '<td data-flag="' . $value['Flag'] . '">Inactivo</td>';
                                        }
                                        echo '<td>' . $value['IdCliente'] . '</td>';
                                        echo '<td>' . $value['IdResponsableInterno'] . '</td>';
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
            <!-- Finalizando panel catálogo de regiones de Cliente -->
        </div>
        <!-- Finalizando #contenido -->
    </div>
</div>