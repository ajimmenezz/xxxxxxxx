<div id="divListaEquiposEnviados" class="content">
    <input type="hidden" value="<?php // echo $datos['vistaUsuario']  ?>" id="IdPerfil" />
    <h1 class="page-header">Seguimiento Equipos Almecén o Solicitados</h1>
    <div id="panelTablaEquiposEnviados" class="panel panel-inverse">
        <div class="panel-heading">    
            <h4 class="panel-title">Seguimiento Equipos Almecén o Solicitados</h4>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-6 col-sm-6 col-xs-6">
                    <h4>Lista de Equipos Enviados o Solicitados</h4>
                </div>
                <div class="col-md-6 col-sm-6 col-xs-6">
                    <div class="form-group text-right hidden" id="botonNuevoValidacion">
                        <a href="javascript:;" class="btn btn-success" id="agregarEquipo">Nuevo</a>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="underline m-b-10"></div>
            </div>
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div id="errorTabla"></div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div id="errorFormulario"></div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="table-responsive">
                        <table id="lista-equipos-enviados-solicitados" class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%">
                            <thead>
                                <tr>
                                    <th class="all">Id</th>
                                    <th class="all">IdServicio</th>
                                    <th class="all">Ticket</th>
                                    <th class="all">Sucursal</th>
                                    <th class="all">Equipo o Refacción</th>
                                    <th class="all">Fecha</th>
                                    <th class="all">IdEstatus</th>
                                    <th class="all">Estatus</th>
                                    <th class="all">Refaccion</th>
                                </tr>
                            </thead>
                            <tbody>
                                    <?php
                                    if (!empty($datos['tablaEquipos'])) {
                                        foreach ($datos['tablaEquipos'] as $key => $value) {
                                            echo '<tr>';
                                            echo '<td>' . $value['Id'] . '</td>';
                                            echo '<td>' . $value['IdServicio'] . '</td>';
                                            echo '<td>' . $value['Ticket'] . '</td>';
                                            echo '<td>' . $value['NombreSucursal'] . '</td>';
                                            echo '<td>' . $value['Equipo'] . '</td>';
                                            echo '<td>' . $value['FechaValidacion'] . '</td>';
                                            echo '<td>' . $value['IdEstatus'] . '</td>';
                                            echo '<td>' . $value['NombreEstatus'] . '</td>';
                                            echo '<td>' . $value['IdRefaccion'] . '</td>';
                                            echo '</tr>';
                                        }
                                    }
                                    ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="row">
                <!--<pre>-->
                    <?php
//                    print_r($datos['vistaUsuario']['IdPerfil'] . " " . $datos['vistaUsuario']['nombrePerfil']);
                    ?>
                <!--</pre>-->
            </div>
        </div>
    </div>
    <div id="seccionPanelEspera" class="hidden"></div>
    <div id="seccionFormulariosRecepcionTecnico" class="hidden"></div>
    <div id="seccionFormulariosEnvSegLog" class="hidden"></div>
    <div id="seccionFormulariosRecepcionLogistica" class="hidden"></div>
    <div id="seccionFormulariosRevisionHistorial" class="hidden"></div>
    <div id="seccionFormulariosRecepcionLaboratorio" class="hidden"></div>
    <div id="seccionFormulariosRecepcionAlmacen" class="hidden"></div>
    <div id="seccionFormulariosAsignacionGuiaLogistica" class="hidden"></div>
    <div id="seccionFormulariosAsignacionGuia" class="hidden"></div>
    <div id="seccionFormulariosGuia" class="hidden"></div>
    <div id="seccionFormulariosValidacion" class="hidden"></div>
</div>