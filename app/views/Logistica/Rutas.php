<!-- Empezando #contenido -->
<div id="content" class="content">
    <!-- Empezando titulo de la pagina -->
    <h1 class="page-header">Rutas</h1>
    <!-- Finalizando titulo de la pagina -->
    <!-- Empezando panel Rutas -->
    <div id="seccionRutas" class="panel panel-inverse">
        <!--Empezando cabecera del panel-->
        <div class="panel-heading">
            <div class="panel-heading-btn">
                <label id="btnRegresarActualizarRuta" class="btn btn-success btn-xs hidden">
                    <i class="fa fa fa-reply"></i> Regresar
                </label> 
                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>                            
                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>                            
            </div>
            <h4 class="panel-title">Rutas</h4>
        </div>
        <!--Finalizando cabecera del panel-->
        <!--Empezando cuerpo del panel-->
        <div class="panel-body">
            <!--Empezando fila 1 -->
            <div id="formularioRuta" class="row m-t-10" >
            </div>
            <!--Finalizando fila 1-->
            <!--Empezando tabla fila 2 -->
            <div id="listaRutas" class="row"> 
                <!--Empezando error--> 
                <div class="col-md-12">
                    <div class="errorListaRutas"></div>
                </div>
                <!--Finalizando Error-->
                <div class="col-md-12">                        
                    <div class="form-group">

                        <div class="row">
                            <div class="col-md-6 col-xs-6">
                                <h3 class="m-t-10">Lista de Rutas</h3>
                            </div>
                            <div class="col-md-6 col-xs-6">
                                <div class="form-group text-right">
                                    <a href="javascript:;" class="btn btn-success btn-lg " id="btnAgregarRuta"><i class="fa fa-plus"></i> Agregar</a>
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

                        <div class="row">
                             <form class="margin-bottom-0" id="formBuscarRutas" data-parsley-validate="true" >
                                <div id="fechaNueva" class="col-xs-6 col-md-3 m-t-10">
                                    <div class="form-group">
                                        <label for="rutasLogistica">Desde</label>
                                        <div id="inputDesde" class="input-group date calendario" >
                                            <input id="inputDesdeRutas" type="text" class="form-control" placeholder="Fecha de Ruta" />
                                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                        </div>
                                    </div>
                                </div> 
                                <div id="fechaNueva" class="col-xs-6 col-md-3 m-t-10">
                                    <div class="form-group">
                                        <label for="rutasLogistica">Hasta</label>
                                        <div id="inputHasta" class="input-group date calendario" >
                                            <input id="inputHastaRutas" type="text" class="form-control" placeholder="Fecha de Ruta" />
                                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                        </div>
                                    </div>
                                </div> 
                                <div class="col-xs-6 col-md-3 m-t-30 text-center">
                                    <a href="javascript:;" class="btn btn-warning m-r-5 " id="btnBuscarRuta"><i class="fa fa-search"></i> Filtrar Rutas</a><br>
                                </div>
                                <div class="col-xs-6 col-md-3 m-t-30 text-center">
                                    <a href="javascript:;" class="btn btn-info m-r-5 " id="btnMostrarRutas"><i class="fa fa-bars"></i> Mostrar Todas las Rutas</a>
                                </div>
                            </form>
                        </div>
                        <!--Empezando Separador-->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="underline m-b-15 m-t-15"></div>
                            </div>
                        </div>
                        <!--Finalizando Separador-->

                        <table id="data-table-rutas" class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%">
                            <thead>
                                <tr>
                                    <th class="never">Id</th>
                                    <th class="all">Codigo</th>
                                    <th class="all">Fecha</th>
                                    <th class="all">Responsable</th>
                                    <th class="all">Estatus</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($datos['ListaRutas'] as $key => $value) {
                                    echo '<tr>';
                                    echo '<td>' . $value['Id'] . '</td>';
                                    echo '<td>' . $value['Codigo'] . '</td>';
                                    echo '<td>' . $value['FechaRuta'] . '</td>';
                                    echo '<td>' . $value['Nombre'] . ' ' . $value['ApPaterno'] . '</td>';
                                    if ($value['IdEstatus'] === '1') {
                                        echo '<td data-flag="' . $value['IdEstatus'] . '">Abierto</td>';
                                    } elseif ($value['IdEstatus'] === '2') {
                                        echo '<td data-flag="' . $value['IdEstatus'] . '">En Atención</td>';
                                    } elseif ($value['IdEstatus'] === '3') {
                                        echo '<td data-flag="' . $value['IdEstatus'] . '">Problema</td>';
                                    } elseif ($value['IdEstatus'] === '4') {
                                        echo '<td data-flag="' . $value['IdEstatus'] . '">Concluido</td>';
                                    } elseif ($value['IdEstatus'] === '5') {
                                        echo '<td data-flag="' . $value['IdEstatus'] . '">En validación</td>';
                                    } elseif ($value['IdEstatus'] === '7') {
                                        echo '<td data-flag="' . $value['IdEstatus'] . '">Autorizado/td>';
                                    } elseif ($value['IdEstatus'] === '8') {
                                        echo '<td data-flag="' . $value['IdEstatus'] . '">Sin Autorización</td>';
                                    } elseif ($value['IdEstatus'] === '9') {
                                        echo '<td data-flag="' . $value['IdEstatus'] . '">Pendiente por Autorizar</td>';
                                    } elseif ($value['IdEstatus'] === '10') {
                                        echo '<td data-flag="' . $value['IdEstatus'] . '">Rechazado</td>';
                                    } elseif ($value['IdEstatus'] === '11') {
                                        echo '<td data-flag="' . $value['IdEstatus'] . '">Personal Activo</td>';
                                    } elseif ($value['IdEstatus'] === '12') {
                                        echo '<td data-flag="' . $value['IdEstatus'] . '">En tránsito</td>';
                                    }
                                }
                                ?>                                        
                            </tbody>
                        </table>
                    </div>    
                </div> 
            </div>
            <!--Finalizando tabla fila 2-->
        </div>
        <!--Finalizando cuerpo del panel-->
    </div>
    <!-- Finalizando panel Rutas -->
</div>
<!-- Finalizando #contenido -->