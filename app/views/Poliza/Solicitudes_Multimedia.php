<!-- Empezando #contenido -->
<div id="content" class="content">
    <!-- Empezando titulo de la pagina -->
    <h1 class="page-header">Solicitudes <small>de Multimedia</small></h1>
    <!-- Finalizando titulo de la pagina -->
    <!-- Empezando panel solicitudes multimedia -->
    <div id="seccionSolicitudesMultimedia" class="panel panel-inverse">
        <!--Empezando cabecera del panel-->
        <div class="panel-heading">
            <div class="panel-heading-btn">
                <label id="btnRegresarSM" class="btn btn-success btn-xs hidden">
                    <i class="fa fa fa-reply"></i> Regresar
                </label> 
                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>                            
                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>                            
            </div>
            <h4 class="panel-title">Solicitudes de Multimedia</h4>
        </div>
        <!--Finalizando cabecera del panel-->
        <!--Empezando cuerpo del panel-->
        <div class="panel-body">
            <div id="tablaSolicitudesMultimedia" class="row"> 
                <div class="col-md-12">                        
                    <div class="form-group">
                        <div id="nuevoPersonal" class="col-md-12">
                            <h3 class="m-t-10">Lista de Solicitudes de Multimedia</h3>
                        </div>
                        <!--Empezando Separador-->
                        <div class="col-md-12">
                            <div class="underline m-b-15 m-t-15"></div>
                        </div>
                        <table id="data-table-solicitudes-multimedia" class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%">
                            <thead>
                                <tr>
                                    <th class="all">Ticket</th>
                                    <th class="all">Folio</th>
                                    <th class="all">Fecha</th>
                                    <th class="all">Sucursal</th>
                                    <th class="all">Estatus</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($datos['ListaSolicitudesMultimedia'] as $key => $value) {
                                    echo '<tr>';
                                    echo '<td>' . $value['Ticket'] . '</td>';
                                    echo '<td>' . $value['Folio'] . '</td>';
                                    echo '<td>' . $value['Fecha'] . '</td>';
                                    echo '<td>' . $value['Sucursal'] . '</td>';
                                    echo '<td>' . $value['Estatus'] . '</td>';
                                    echo '</tr>';
                                }
                                ?>                                        
                            </tbody>
                        </table>
                    </div>    
                </div> 
            </div>
            <div id="formularioSolicitudesMultimedia" class="row hidden">
            </div>
            <div class="row">
                <!--Empezando error--> 
                <div class="col-md-12">
                    <div class="errorSolicitudesMultimedia"></div>
                </div>
                <!--Finalizando Error-->
            </div>
        </div>
    </div>
    <!-- Finalizando panel solicitudes multimedia -->
</div>
<!-- Finalizando #contenido -->