<!--
 * Description: Vista de los servicios en estatus de validacion
 *
 * @author: Alberto Barcenas
 *
-->
<!-- Empezando #contenido -->
<div id="content" class="content">
    <!-- Empezando titulo de la pagina -->
    <h1 class="page-header">Validación de Servicios</h1>
    <!-- Finalizando titulo de la pagina -->

    <!-- Empezando panel reporte-->
    <div id="seccion-reporte" class="panel panel-inverse borde-sombra">
        <!--Empezando cabecera del panel-->
        <div class="panel-heading">
            <h4 class="panel-title">Servicios por Validar</h4>
        </div>
        <!--Finalizando cabecera del panel-->
        <!--Empezando cuerpo del panel-->
        <div class="panel-body">
            <div class="row"> 
                <div class="col-md-12">  
                    <div class="form-group">
                        <div class="col-md-12">
                            <h3 class="m-t-10">Lista de Servicios por Validar</h3>
                        </div>
                        <div class="col-md-12">
                            <div class="underline m-b-15 m-t-15"></div>
                        </div>
                        <!--Finalizando Separador-->
                    </div>    
                </div> 
            </div>
            <div class="table-responsive">
                <table id="data-table-validaciones-servicios" class="table table-hover table-striped table-bordered no-wrap " style="cursor:pointer" width="100%">
                    <thead>
                        <tr>
                            <th class="never">IdSolicitud</th>
                            <th class="never">IdServicio</th>
                            <th class="all">Ticket</th>
                            <th class="all">Servicio</th>
                            <th class="all">Fecha</th>
                            <th class="all">Descripción</th>
                            <th class="all">Estatus</th>
                            <th class="all">Solicita</th>
                            <th class="all">Atiende</th>
                            <th class="never">IdAtiende</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (!empty($datos['Servicios'])) {
                            foreach ($datos['Servicios'] as $key => $value) {
                                echo '<tr>';
                                echo '<td>' . $value['IdSolicitud'] . '</td>';
                                echo '<td>' . $value['Id'] . '</td>';
                                echo '<td>' . $value['Ticket'] . '</td>';
                                echo '<td>' . $value['Servicio'] . '</td>';
                                echo '<td>' . $value['FechaCreacion'] . '</td>';
                                echo '<td>' . $value['Descripcion'] . '</td>';
                                echo '<td>' . $value['NombreEstatus'] . '</td>';
                                echo '<td>' . $value['Solicita'] . '</td>';
                                echo '<td>' . $value['Atiende'] . '</td>';
                                echo '<td>' . $value['IdAtiende'] . '</td>';
                                echo '</tr>';
                            }
                        }
                        ?>  
                    </tbody>
                </table>
            </div>
            <!--Finalizando cuerpo del panel-->
        </div>
        <!-- Finalizando panel nuevo proyecto -->   

    </div>
    <!-- Finalizando reporte -->


    <!-- Empezando panel detalles-->
    <div id="seccion-detalles" class="panel panel-inverse borde-sombra panel-with-tabs hidden">
        <!--Empezando cabecera del panel-->
        <div class="panel-heading">
            <div class="btn-group pull-right" data-toggle="buttons"></div>
            <div class="panel-heading-btn">
                <!-- Single button -->
                <div class="btn-group">
                    <button type="button" class="btn btn-warning btn-xs dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Acciones <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu">
                        <li id="btnValidarServicio"><a href="#" class='disabled'><i class="fa fa-check"></i> Validar Servicio</a></li>
                        <li id="btnRechazarServicio"><a href="#"><i class="fa fa-mail-reply-all"></i> Rechazar Servicio</a></li>
                        <li id="btnExportarPdf" data-id-servicio=""><a href="#"><i class="fa fa-file-pdf-o"></i> Exportar Pdf</a></li>
                    </ul>
                </div>
                <label id="btnRegresarDetalles" class="btn btn-success btn-xs">
                    <i class="fa fa fa-reply"></i> Regresar
                </label>   
            </div>
            <div class="tab-overflow overflow-right">
                <ul class="nav nav-tabs nav-tabs-inverse">                    
                    <li class="active"><a href="#Solicitud" data-toggle="tab">Solicitud</a></li>                    
                    <li><a href="#Servicio" data-toggle="tab">Servicio</a></li>       
                    <li><a href="#Historial" data-toggle="tab">Historial</a></li>                    
                    <li><a href="#Conversacion" data-toggle="tab">Conversación</a></li>                    
                </ul>
            </div>            
        </div>
        <!--Finalizando cabecera del panel-->
        <div class="tab-content">

            <!--Empezando la seccion de solicitud-->
            <div class="tab-pane fade active in" id="Solicitud">
                <div class="panel-body" id="panel-detalles-solicitud"></div>
            </div>
            <!--Finalizando la seccion de solicitud-->
            <!--Empezando la seccion de servicio-->
            <div class="tab-pane fade" id="Servicio">
                <div class="panel-body" id="panel-detalles-servicio"></div>
            </div>
            <!--Finalizando la seccion de servicio-->
            <!--Empezando la seccion de historial-->
            <div class="tab-pane fade" id="Historial">
                <div class="panel-body" id="panel-historial-servicio"></div>
            </div>
            <!--Finalizando la seccion de historial-->
            <!--Empezando la seccion de servicio-->
            <div class="tab-pane fade" id="Conversacion">
                <div class="panel-body" id="panel-conversacion-servicio"></div>
            </div>
            <!--Finalizando la seccion de servicio-->
        </div>

    </div>
    <!-- Finalizando detalles -->