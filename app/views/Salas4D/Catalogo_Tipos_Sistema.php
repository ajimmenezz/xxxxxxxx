<!--
 * Description: Listas de los catalogos de Tipos de Sistema
 *
 * @author: Alberto Barcenas
 *
-->
<div id="seccionCatalogoTiposSistema" class="content">

    <!-- Empezando titulo de la pagina -->
    <h1 class="page-header">Catálogos</h1>
    <!-- Finalizando titulo de la pagina -->

    <div id="seccion-catalogo-tipos-sistema-salas4xd" class="panel panel-inverse panel-with-tabs" data-sortable-id="ui-unlimited-tabs-1">

        <!--Empezando Pestañas para definir la seccion-->
        <div class="panel-heading p-0">
            <div class="btn-group pull-right" data-toggle="buttons">

            </div>
            <div class="panel-heading-btn m-r-10 m-t-10">                                 
                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-expand"><i class="fa fa-expand"></i></a>
            </div>
            <div class="tab-overflow">
                <ul class="nav nav-tabs nav-tabs-inverse">
                    <li class="prev-button"><a href="javascript:;" data-click="prev-tab" class="text-success"><i class="fa fa-arrow-left"></i></a></li>
                    <li class="active"><a href="#TiposSistema" data-toggle="tab">Tipos de Sistema</a></li>
                    <li class=""><a href="#TiposSistemaEquipos" data-toggle="tab">Líneas</a></li>
                    <li class=""><a href="#TiposSistemaMarcas" data-toggle="tab">Marcas</a></li>
                    <li class=""><a href="#TiposSistemaModelos" data-toggle="tab">Elementos</a></li>
                    <li class=""><a href="#TiposSistemaComponentes" data-toggle="tab">Sub-elementos</a></li>
                    <li class=""><a href="#Actividades_de_Mantenimiento" data-toggle="tab">Actividades de Mantenimiento</a></li>
                    <li class="next-button"><a href="javascript:;" data-click="next-tab" class="text-success"><i class="fa fa-arrow-right"></i></a></li>
                </ul>
            </div>
        </div>
        <!-- Finalizando Pestañas para definir la seccion -->

        <!-- Empezando contenido de catalogo de tipos de sistema salas X4D -->
        <div class="tab-content">  
            <!-- Empezando la seccion Tipos Sistema -->
            <div class="tab-pane fade active in" id="TiposSistema">
                <div class="panel-body">
                    <!--Empezando error--> 
                    <div class="row m-t-10">                       
                        <div class="col-md-12">
                            <div class="errorTiposSistema"></div>
                        </div>
                    </div>   
                    <!--Finalizando Error-->
                    
                    <!--Empezando tabla Tipos Sistema -->
                    <div id='listaTiposSistema'>

                        <!-- Empezando titulo de la tabla Tipos Sistema -->
                        <div class="row">
                            <div class="col-md-12">                        
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6 col-xs-6">
                                            <h3 class="m-t-10">Lista de Tipos de Sistema</h3>
                                        </div>
                                        <div class="col-md-6 col-xs-6">
                                            <div class="form-group text-right">
                                                <a href="javascript:;" class="btn btn-success btn-lg " id="btnAgregarTipoSistema"><i class="fa fa-plus"></i> Agregar</a>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Empezando Separador -->
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="underline m-b-15 m-t-15"></div>
                                        </div>
                                    </div>
                                    <!-- Finalizando Separador -->

                                </div>    
                            </div> 
                        </div>
                        <!-- Finalizando titulo de la tabla Tipos Sistema -->

                        <!--Empezando datos de la tabla Tipos Sistema -->
                        <div class="table-responsive">
                            <table id="data-table-tipos-sistema" class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%">
                                <thead>
                                    <tr>
                                        <th class="never">Id</th>
                                        <th class="all">Nombre</th>
                                        <th class="all">Estatus</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (!empty($datos['ListaTiposSistema'])) {
                                        foreach ($datos['ListaTiposSistema'] as $key => $value) {
                                            echo '<tr>';
                                            echo '<td>' . $value['Id'] . '</td>';
                                            echo '<td>' . $value['Nombre'] . '</td>';
                                            if ($value['Flag'] === '1') {
                                                echo '<td data-flag="' . $value['Flag'] . '">Activo</td>';
                                            } else {
                                                echo '<td data-flag="' . $value['Flag'] . '">Inactivo</td>';
                                            }
                                            echo '</tr>';
                                        }
                                    }
                                    ?>                                        
                                </tbody>
                            </table>
                        </div>
                        <!--Finalizando datos de la tabla Tipos Sistema -->

                    </div>
                    <!--Finalizando tabla Tipos Sistema -->

                </div>
            </div>
            <!-- Empezando la seccion Tipos Sistema -->

            <!-- Empezando la seccion Tipos Sistema Equipos -->
            <div class="tab-pane fade" id="TiposSistemaEquipos">
                <div class="panel-body">
                    <!--Empezando error--> 
                    <div class="row m-t-10">                       
                        <div class="col-md-12">
                            <div class="errorTiposSistemaEquipos"></div>
                        </div>
                    </div>   
                    <!--Finalizando Error-->

                    <!--Empezando lista de Lineas de Equipo -->
                    <div id='listaTiposSistemaEquipos'>

                        <!-- Empezando titulo de la tabla Líneas de equipo -->
                        <div class="row">
                            <div class="col-md-12">                        
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6 col-xs-6">
                                            <h3 class="m-t-10">Lista de Líneas</h3>
                                        </div>
                                        <div class="col-md-6 col-xs-6">
                                            <div class="form-group text-right">
                                                <a href="javascript:;" class="btn btn-success btn-lg " id="btnAgregarTiposSistemaEquipo"><i class="fa fa-plus"></i> Agregar</a>
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
                        <!--Finalizando titulo de la tabla Tipos Sistema Equipos-->

                        <!--Empezando datos de la tabla Tipos Sistema Equipos -->
                        <div class="table-responsive">
                            <table id="data-table-tipos-sistema-equipos" class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%">
                                <thead>
                                    <tr>
                                        <th class="never">Id</th>
                                        <th class="all">Línea</th>                                        
                                        <th class="all">Estatus</th>                                        
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (!empty($datos['ListaEquipos'])) {
                                        foreach ($datos['ListaEquipos'] as $key => $value) {
                                            echo '<tr>';
                                            echo '<td>' . $value['Id'] . '</td>';
                                            echo '<td>' . $value['Nombre'] . '</td>';
                                            if ($value['Flag'] === '1') {
                                                echo '<td data-flag="' . $value['Flag'] . '">Activo</td>';
                                            } else {
                                                echo '<td data-flag="' . $value['Flag'] . '">Inactivo</td>';
                                            }
                                            echo '</tr>';
                                        }
                                    }
                                    ?>                                        
                                </tbody>
                            </table>
                        </div>
                        <!--Finalizando datos de la tabla Tipos Sistema Equipos -->

                    </div>
                    <!--Finalizando tabla Tipos Sistema Equipos -->

                </div>
            </div>
            <!-- Empezando la seccion Tipos Sistemas Equipos -->

            <!-- Empezando la seccion Tipos Sistema Marcas -->
            <div class="tab-pane fade" id="TiposSistemaMarcas">
                <div class="panel-body">
                    <!-- Empezando error --> 
                    <div class="row m-t-10">                       
                        <div class="col-md-12">
                            <div class="errorTiposSistemaMarcas"></div>
                        </div>
                    </div>   
                    <!-- Finalizando Error -->

                    <!-- Empezando tabla Tipos Sistema Marcas -->
                    <div id='listaTiposSistemaMarcas'>

                        <!-- Empezando titulo de la tabla Tipos Sistema Marcas -->
                        <div class="row">
                            <div class="col-md-12">                        
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6 col-xs-6">
                                            <h3 class="m-t-10">Lista de Marcas</h3>
                                        </div>
                                        <div class="col-md-6 col-xs-6">
                                            <div class="form-group text-right">
                                                <a href="javascript:;" class="btn btn-success btn-lg " id="btnAgregarMarca"><i class="fa fa-plus"></i> Agregar</a>
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
                        <!--Finalizando titulo de la tabla Tipoas Sistema Marcas -->

                        <!--Empezando datos de la tabla Tipoas Sistema Marcas -->
                        <div class="table-responsive">
                            <table id="data-table-tipos-sistema-marcas" class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%">
                                <thead>
                                    <tr>
                                        <th class="never">Id</th>
                                        <th class="all">Marca</th>
                                        <th class="all">Estatus</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (!empty($datos['ListaMarcas'])) {
                                        foreach ($datos['ListaMarcas'] as $key => $value) {
                                            echo '<tr>';
                                            echo '<td>' . $value['Id'] . '</td>';
                                            echo '<td>' . $value['Nombre'] . '</td>';
                                            if ($value['Flag'] === '1') {
                                                echo '<td data-flag="' . $value['Flag'] . '">Activo</td>';
                                            } else {
                                                echo '<td data-flag="' . $value['Flag'] . '">Inactivo</td>';
                                            }
                                            echo '</tr>';
                                        }
                                    }
                                    ?>                                        
                                </tbody>
                            </table>
                        </div>
                        <!--Finalizando datos de la tabla Tipos Sistema Marcas -->

                    </div>
                    <!--Finalizando tabla Tipos Sistema Marcas -->

                </div>
            </div>
            <!-- Empezando la seccion Tipos Sistema Marcas -->

            <!-- Empezando la seccion Tipos Sistema Modelos -->
            <div class="tab-pane fade" id="TiposSistemaModelos">
                <div class="panel-body">

                    <!--Empezando error--> 
                    <div class="row m-t-10">                       
                        <div class="col-md-12">
                            <div class="errorTiposSistemaModelos"></div>
                        </div>
                    </div>   
                    <!--Finalizando Error-->

                    <!--Empezando tabla Tipos Sitema Modelos -->
                    <div id='listaTiposSistemaModelos'>

                        <!-- Empezando titulo de la tabla Tipos Sistema Modelos-->
                        <div class="row">
                            <div class="col-md-12">                        
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6 col-xs-6">
                                            <h3 class="m-t-10">Lista de Elementos</h3>
                                        </div>
                                        <div class="col-md-6 col-xs-6">
                                            <div class="form-group text-right">
                                                <a href="javascript:;" class="btn btn-success btn-lg " id="btnAgregarModelo"><i class="fa fa-plus"></i> Agregar</a>
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
                        <!--Finalizando titulo de la tabla Tipos Sistema Modelos -->

                        <!--Empezando datos de la tabla Tipos Sistema Modelos -->
                        <div class="table-responsive">
                            <table id="data-table-tipos-sistema-modelos" class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%">
                                <thead>
                                    <tr>
                                        <th class="never">Id</th>
                                        <th class="all">Elemento</th>
                                        <th class="never">IdLinea</th>
                                        <th class="all">Línea</th>
                                        <th class="never">IdMarca</th>
                                        <th class="all">Marca</th>                                        
                                        <th class="all">Clave SAE</th>                                        
                                        <th class="all">Estatus</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (!empty($datos['ListaModelos'])) {
                                        foreach ($datos['ListaModelos'] as $key => $value) {
                                            echo '<tr>';
                                            echo '<td>' . $value['Id'] . '</td>';
                                            echo '<td>' . $value['Nombre'] . '</td>';
                                            echo '<td>' . $value['IdLinea'] . '</td>';
                                            echo '<td>' . $value['Linea'] . '</td>';
                                            echo '<td>' . $value['IdMarca'] . '</td>';
                                            echo '<td>' . $value['Marca'] . '</td>';
                                            echo '<td>' . $value['ClaveSAE'] . '</td>';
                                            if ($value['Flag'] === '1') {
                                                echo '<td data-flag="' . $value['Flag'] . '">Activo</td>';
                                            } else {
                                                echo '<td data-flag="' . $value['Flag'] . '">Inactivo</td>';
                                            }
                                            echo '</tr>';
                                        }
                                    }
                                    ?>                                        
                                </tbody>
                            </table>
                        </div>
                        <!-- Finalizando datos de la tabla Tipos Sistema Modelos -->

                    </div>
                    <!-- Finalizando tabla Tipos Sistema Modelos -->

                </div>
            </div>
            <!-- Empezando la seccion Tipos Sistemas Modelos -->

            <!-- Empezando la seccion Tipos Sistemas Componentes -->
            <div class="tab-pane fade" id="TiposSistemaComponentes">
                <div class="panel-body">

                    <!-- Empezando error --> 
                    <div class="row m-t-10">                       
                        <div class="col-md-12">
                            <div class="errorTiposSistemaComponentes"></div>
                        </div>
                    </div>   
                    <!-- Finalizando Error -->

                    <!-- Empezando tabla Tipos Sistema Componentes -->
                    <div id='listaFallasRefaccion'>

                        <!-- Empezando titulo de la tabla Tipos Sistema Componentes -->
                        <div class="row">
                            <div class="col-md-12">                        
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6 col-xs-6">
                                            <h3 class="m-t-10">Lista Sub-elementos</h3>
                                        </div>
                                        <div class="col-md-6 col-xs-6">
                                            <div class="form-group text-right">
                                                <a href="javascript:;" class="btn btn-success btn-lg " id="btnAgregarComponente"><i class="fa fa-plus"></i> Agregar</a>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Empezando Separador -->
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="underline m-b-15 m-t-15"></div>
                                        </div>
                                    </div>
                                    <!-- Finalizando Separador -->

                                </div>    
                            </div> 
                        </div>
                        <!--Finalizando titulo de la tabla Tipos Sistema Componentes -->

                        <!--Empezando datos de la tabla Tipos Sistema Componentes -->
                        <div class="table-responsive">
                            <table id="data-table-tipos-sistema-componentes" class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%">
                                <thead>
                                    <tr>
                                        <th class="never">Id</th>
                                        <th class="all">Sub-elemento</th>
                                        <th class="all">Marca Sub-elemento</th>
                                        <th class="all">Elemento</th>                                        
                                        <th class="all">Clave SAE</th>                                        
                                        <th class="all">Estatus</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (!empty($datos['ListaComponentes'])) {
                                        foreach ($datos['ListaComponentes'] as $key => $value) {
                                            echo '<tr>';
                                            echo '<td>' . $value['Id'] . '</td>';
                                            echo '<td>' . $value['Nombre'] . '</td>';
                                            echo '<td>' . $value['Marca'] . '</td>';
                                            echo '<td>' . $value['Elemento'] . '</td>';
                                            echo '<td>' . $value['ClaveSAE'] . '</td>';
                                            if ($value['Flag'] === '1') {
                                                echo '<td data-flag="' . $value['Flag'] . '">Activo</td>';
                                            } else {
                                                echo '<td data-flag="' . $value['Flag'] . '">Inactivo</td>';
                                            }
                                            echo '</tr>';
                                        }
                                    }
                                    ?>                                        
                                </tbody>
                            </table>
                        </div>
                        <!--Finalizando datos de la tabla Tipos Sistema Componentes -->

                    </div>
                    <!--Finalizando tabla Tipos Sistema Componentes -->

                </div>
            </div>

            <!-- Empezando la seccion Tipos Sistemas Componentes -->
            <div class="tab-pane fade" id="Actividades_de_Mantenimiento">
                <div class="panel-body">                    
                    <div id='Actividades_de_Mantenimiento'>                        
                        <div class="row">
                            <div class="col-md-12">                        
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6 col-xs-6">
                                            <h3 class="m-t-10">Actividades de Mantenimiento</h3>
                                        </div>
                                    </div>                                    
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="underline m-b-15 m-t-15"></div>
                                        </div>
                                    </div>
                                </div>    
                            </div> 
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div id="errorActividades"></div>
                            </div>
                        </div>
                        <div class="row">                            
                            <div class="col-md-12">
                                <div class="panel panel-inverse" data-sortable-id="tree-view-3">
                                    <div class="panel-heading">
                                        <div class="panel-heading-btn"></div>
                                        <h4 class="panel-title">Actividades de Mantenimiento </h4>
                                    </div>                                    
                                    <div class="panel-body">                                        
                                        <div id="jstree-default"></div>

                                    </div>
                                </div>
                            </div>                                                       
                        </div>                        
                    </div>
                </div>                
            </div>            
        </div>

    </div>
     <!-- Empezando seccion para mostrar los fomularios de catalogos de tipos de salas -->
    <div id="seccionFormulariosTiposSistema"></div>
    <!-- Finalizando seccion para mostrar los fomularios de catalogos de tipos de salas --> 
    
</div>
