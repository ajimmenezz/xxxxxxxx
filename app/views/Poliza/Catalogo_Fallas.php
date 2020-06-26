<!--
 * Description: Listas de los catalogos de Fallas de Poliza
 *
 * @author: Alberto Barcenas
 *
-->
<div id="seccionCatalogoFallasPoliza" class="content">

    <!-- Empezando titulo de la pagina -->
    <h1 class="page-header">Catálogo <small>Fallas de Póliza</small></h1>
    <!-- Finalizando titulo de la pagina -->

    <div id="seccion-catalogo-fallas-poliza" class="panel panel-inverse panel-with-tabs" data-sortable-id="ui-unlimited-tabs-1">

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
                    <li class="active"><a href="#ClasificacionFallas" data-toggle="tab">Clasificación Fallas</a></li>
                    <li class=""><a href="#TiposFallas" data-toggle="tab">Tipos Fallas</a></li>
                    <li class=""><a href="#FallasEquipo" data-toggle="tab">Fallas por Equipo</a></li>
                    <li class=""><a href="#FallasRefaccion" data-toggle="tab">Fallas por Refacción</a></li>
                    <li class="next-button"><a href="javascript:;" data-click="next-tab" class="text-success"><i class="fa fa-arrow-right"></i></a></li>
                </ul>
            </div>
        </div>
        <!--Finalizando Pestañas para definir la seccion-->

        <!--Empezando contenido de catalogo de fallas poliza-->
        <div class="tab-content">

            <!--Empezando la seccion Clasificacion Fallas-->
            <div class="tab-pane fade active in" id="ClasificacionFallas">
                <div class="panel-body">

                    <!--Empezando error--> 
                    <div class="row m-t-10">                       
                        <div class="col-md-12">
                            <div class="errorClasificacionFallas"></div>
                        </div>
                    </div>   
                    <!--Finalizando Error-->

                    <!--Empezando tabla Clasificacion Fallas -->
                    <div id='listaClasificacionFallas'>

                        <!-- Empezando titulo de la tabla Clasificacion Fallas -->
                        <div class="row">
                            <div class="col-md-12">                        
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6 col-xs-6">
                                            <h3 class="m-t-10">Lista de Clasificación de Fallas</h3>
                                        </div>
                                        <div class="col-md-6 col-xs-6">
                                            <div class="form-group text-right">
                                                <a href="javascript:;" class="btn btn-success btn-lg " id="btnAgregarClasificacionFalla"><i class="fa fa-plus"></i> Agregar</a>
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
                        <!--Finalizando titulo de la tabla Clasificacion Fallas-->

                        <!--Empezando datos de la tabla Clasificacion Fallas -->
                        <div class="table-responsive">
                            <table id="data-table-clasificacion-fallas" class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%">
                                <thead>
                                    <tr>
                                        <th class="never">Id</th>
                                        <th class="all">Nombre</th>
                                        <th class="all">Decripción</th>
                                        <th class="all">Estatus</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (!empty($datos['ListaClasificacionFallas'])) {
                                        foreach ($datos['ListaClasificacionFallas'] as $key => $value) {
                                            echo '<tr>';
                                            echo '<td>' . $value['Id'] . '</td>';
                                            echo '<td>' . $value['Nombre'] . '</td>';
                                            echo '<td>' . $value['Descripcion'] . '</td>';
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
                        <!--Finalizando datos de la tabla Clasificacion Fallas -->

                    </div>
                    <!--Finalizando tabla Clasificacion Fallas -->

                </div>
            </div>
            <!--Empezando la seccion Clasificacion Fallas-->

            <!--Empezando la seccion Tipos Fallas-->
            <div class="tab-pane fade" id="TiposFallas">
                <div class="panel-body">

                    <!--Empezando error--> 
                    <div class="row m-t-10">                       
                        <div class="col-md-12">
                            <div class="errorTiposFallas"></div>
                        </div>
                    </div>   
                    <!--Finalizando Error-->

                    <!--Empezando tabla Tipos Fallas -->
                    <div id='listaTiposFallas'>

                        <!-- Empezando titulo de la tabla Tipos Fallas -->
                        <div class="row">
                            <div class="col-md-12">                        
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6 col-xs-6">
                                            <h3 class="m-t-10">Lista de Tipos de Fallas</h3>
                                        </div>
                                        <div class="col-md-6 col-xs-6">
                                            <div class="form-group text-right">
                                                <a href="javascript:;" class="btn btn-success btn-lg " id="btnAgregarTipoFalla"><i class="fa fa-plus"></i> Agregar</a>
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
                        <!--Finalizando titulo de la tabla Tipos Fallas-->

                        <!--Empezando datos de la tabla Tipos Fallas -->
                        <div class="table-responsive">
                            <table id="data-table-tipos-fallas" class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%">
                                <thead>
                                    <tr>
                                        <th class="never">Id</th>
                                        <th class="all">Tipo</th>
                                        <th class="all">Clasificación</th>
                                        <th class="all">Decripción</th>
                                        <th class="all">Estatus</th>
                                        <th class="never">IdClasificacion</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (!empty($datos['ListaTiposFallas'])) {
                                        foreach ($datos['ListaTiposFallas'] as $key => $value) {
                                            echo '<tr>';
                                            echo '<td>' . $value['Id'] . '</td>';
                                            echo '<td>' . $value['Nombre'] . '</td>';
                                            echo '<td>' . $value['Clasificacion'] . '</td>';
                                            echo '<td>' . $value['Descripcion'] . '</td>';
                                            if ($value['Flag'] === '1') {
                                                echo '<td data-flag="' . $value['Flag'] . '">Activo</td>';
                                            } else {
                                                echo '<td data-flag="' . $value['Flag'] . '">Inactivo</td>';
                                            }
                                            echo '<td>' . $value['IdClasificacion'] . '</td>';
                                            echo '</tr>';
                                        }
                                    }
                                    ?>                                        
                                </tbody>
                            </table>
                        </div>
                        <!--Finalizando datos de la tabla Tipos Fallas -->

                    </div>
                    <!--Finalizando tabla Tipos Fallas -->

                </div>
            </div>
            <!--Empezando la seccion Tipos Fallas-->

            <!--Empezando la seccion Fallas Equipo-->
            <div class="tab-pane fade" id="FallasEquipo">
                <div class="panel-body">

                    <!--Empezando el formulario Fallas por Equipo -->
                    <div id="formularioFallasEquipo">
                    </div>
                    <!--Finalizando formulario Fallas por Equipo-->

                    <!--Empezando error--> 
                    <div class="row m-t-10">                       
                        <div class="col-md-12">
                            <div class="errorFallasEquipo"></div>
                        </div>
                    </div>   
                    <!--Finalizando Error-->

                    <!--Empezando tabla Fallas Equipo -->
                    <div id='listaFallasEquipo'>

                        <!-- Empezando titulo de la tabla Fallas Equipo-->
                        <div class="row">
                            <div class="col-md-12">                        
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6 col-xs-6">
                                            <h3 class="m-t-10">Lista de Fallas por Equipo</h3>
                                        </div>
                                        <div class="col-md-6 col-xs-6">
                                            <div class="form-group text-right">
                                                <a href="javascript:;" class="btn btn-success btn-lg " id="btnAgregarFallaEquipo"><i class="fa fa-plus"></i> Agregar</a>
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
                        <!--Finalizando titulo de la tabla Fallas Equipo -->

                        <!--Empezando datos de la tabla Fallas Equipo -->
                        <div class="table-responsive">
                            <table id="data-table-fallas-equipo" class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%">
                                <thead>
                                    <tr>
                                        <th class="never">Id</th>
                                        <th class="all">Falla</th>
                                        <th class="all">Tipo de Falla</th>
                                        <th class="all">Equipo</th>
                                        <th class="all">Estatus</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (!empty($datos['ListaFallasEquipo'])) {
                                        foreach ($datos['ListaFallasEquipo'] as $key => $value) {
                                            echo '<tr>';
                                            echo '<td>' . $value['Id'] . '</td>';
                                            echo '<td>' . $value['Nombre'] . '</td>';
                                            echo '<td>' . $value['NombreTipoFalla'] . '</td>';
                                            echo '<td>' . $value['NombreEquipo'] . '</td>';
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
                        <!--Finalizando datos de la tabla Fallas Equipo -->

                    </div>
                    <!--Finalizando tabla Fallas Equipo -->

                </div>
            </div>
            <!--Empezando la seccion Fallas Equipo -->

            <!--Empezando la seccion Fallas Refaccion-->
            <div class="tab-pane fade" id="FallasRefaccion">
                <div class="panel-body">

                    <!--Empezando el formulario Fallas por Refaccion -->
                    <div id="formularioFallasRefaccion">
                    </div>
                    <!--Finalizando formulario Fallas por Refaccion-->

                    <!--Empezando error--> 
                    <div class="row m-t-10">                       
                        <div class="col-md-12">
                            <div class="errorFallasRefaccion"></div>
                        </div>
                    </div>   
                    <!--Finalizando Error-->

                    <!--Empezando tabla Fallas Refaccion -->
                    <div id='listaFallasRefaccion'>

                        <!-- Empezando titulo de la tabla Fallas Refaccion-->
                        <div class="row">
                            <div class="col-md-12">                        
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6 col-xs-6">
                                            <h3 class="m-t-10">Lista de Fallas por Refacción</h3>
                                        </div>
                                        <div class="col-md-6 col-xs-6">
                                            <div class="form-group text-right">
                                                <a href="javascript:;" class="btn btn-success btn-lg " id="btnAgregarFallaRefaccion"><i class="fa fa-plus"></i> Agregar</a>
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
                        <!--Finalizando titulo de la tabla Fallas Refaccion -->

                        <!--Empezando datos de la tabla Fallas Refaccion -->
                        <div class="table-responsive">
                            <table id="data-table-fallas-refaccion" class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%">
                                <thead>
                                    <tr>
                                        <th class="never">Id</th>
                                        <th class="all">Falla</th>
                                        <th class="all">Equipo</th>
                                        <th class="all">Refacción</th>
                                        <th class="all">Tipo de Falla</th>
                                        <th class="all">Estatus</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (!empty($datos['ListaFallasRefaccion'])) {
                                        foreach ($datos['ListaFallasRefaccion'] as $key => $value) {
                                            echo '<tr>';
                                            echo '<td>' . $value['Id'] . '</td>';
                                            echo '<td>' . $value['Nombre'] . '</td>';
                                            echo '<td>' . $value['NombreEquipo'] . '</td>';
                                            echo '<td>' . $value['NombreRefaccion'] . '</td>';
                                            echo '<td>' . $value['NombreTipoFalla'] . '</td>';
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
                        <!--Finalizando datos de la tabla Fallas Refaccion -->

                    </div>
                    <!--Finalizando tabla Fallas Refaccion -->

                </div>
            </div>
            <!--Empezando la seccion Fallas Refaccion -->            

        </div>
        <!--Finalizando contenido de catalogo de fallas poliza-->

    </div>

    <!--Empezando seccion para mostrar los fomularios de catalogos de fallas -->
    <div id="seccionFormulariosFallasPoliza" ></div>
    <!-- Finalizando seccion para mostrar los fomularios de catalogos de fallas --> 
    
</div>
