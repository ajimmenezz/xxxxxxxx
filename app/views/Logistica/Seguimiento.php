<!-- Empezando #contenido -->
<div id="listaLogistica" class="content">
    <!-- Empezando titulo de la pagina -->
    <h1 class="page-header">Seguimiento Logística</h1>
    <!-- Finalizando titulo de la pagina -->
    <!-- Empezando panel seguimiento-->
    <div id="panelSeguimientoLogistica" class="panel panel-inverse">
        <!--Empezando cabecera del panel-->
        <div class="panel-heading">
            <div class="panel-heading-btn">
                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>                            
                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>                            
            </div>
            <h4 class="panel-title">Seguimiento Logística</h4>
        </div>
        <!--Finalizando cabecera del panel-->
        <!--Empezando cuerpo del panel-->
        <div class="panel-body">
            <div class="row"> 
                <!--Empezando error--> 
                <div class="col-md-12">
                    <div class="errorListaLogistica"></div>
                </div>
                <!--Finalizando Error-->
                <div class="col-md-12">  
                    <div class="form-group">
                        <div class="col-md-6">
                            <h3 class="m-t-10">Lista General de Tickets y Tareas Pendientes</h3>
                        </div>
                        <div class="col-md-12">
                            <div class="underline m-b-15 m-t-15"></div>
                        </div>
                        <!--Finalizando Separador-->
                    </div>    
                </div> 
            </div>
            <div class="table-responsive">
                <table id="data-table-logistica" class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%">
                    <thead>
                        <tr>
                            <th class="never">Id</th>
                            <th class="all">Ticket</th>
                            <th class="all">Servicio</th>
                            <th class="all">Fecha de Creación</th>
                            <th class="all">Descripcion</th>
                            <th class="all">Solicita</th>
                            <th class="all">Estatus</th>
                            <th class="never">IdEstatus</th>
                            <th class="all">Solicitud</th>
                            <th class="all">Folio</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (!empty($datos['Servicios'])) {
                            foreach ($datos['Servicios'] as $key => $value) {
                                echo '<tr>';
                                echo '<td>' . $value['Id'] . '</td>';
                                echo '<td>' . $value['Ticket'] . '</td>';
                                echo '<td>' . $value['Servicio'] . '</td>';
                                echo '<td>' . $value['FechaCreacion'] . '</td>';
                                echo '<td>' . $value['Descripcion'] . '</td>';
                                echo '<td>' . $value['Solicita'] . '</td>';
                                echo '<td>' . $value['NombreEstatus'] . '</td>';
                                echo '<td>' . $value['IdEstatus'] . '</td>';
                                echo '<td>' . $value['IdSolicitud'] . '</td>';
                                ($value['Folio'] === '0') ? $folio = '' : $folio = $value['Folio'];
                                echo '<td>' . $folio . '</td>';
                                echo '</tr>';
                            }
                        }
                        ?>                                        
                    </tbody>
                </table>
            </div>
        </div>
        <!--Finalizando cuerpo del panel-->
    </div>
    <!-- Finalizando panel nuevo proyecto -->   
</div>
<!-- Finalizando #contenido -->

<!-- Empezando seccion para el seguimiento de un servicio de tipo logistica-->
<div id="seccionSeguimientoServicioLogistica" class="content hidden"></div>
<!-- Finalizando seccion para el seguimiento de un servicio de tipo logistica -->


<!-- Empezando #fromularioRutas -->
<div id="formularioRuta" class="content hidden">
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
            <div id="ruta" class="row m-t-10" >
            </div>
        </div>
        <!--Finalizando cuerpo del panel-->
    </div>
    <!-- Finalizando panel Rutas -->
</div>
<!-- Finalizando #formularioRutas -->

<!--Empezando seccion para el seguimiento de un servicio sin clasificar->-->
<div id="seccionSeguimientoServicioSinClasificar" class="content hidden"></div>
<!-- Finalizando seccion para el seguimiento de un servicio sin clasificar 