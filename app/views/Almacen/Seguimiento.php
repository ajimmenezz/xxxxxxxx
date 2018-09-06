<!-- Empezando #contenido -->
<div id="content" class="content">
    <!-- Empezando titulo de la pagina -->
    <h1 class="page-header">Seguimiento <small> de servicios de almacén</small></h1>
    <!-- Finalizando titulo de la pagina -->

    <!-- Empezando panel Servicios asignados y abiertos-->
    <div id="seccionServiciosAsignadosAlmacen" class="panel panel-inverse">
        <!--Empezando cabecera del panel-->
        <div class="panel-heading">
            <div class="panel-heading-btn">
                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>                            
                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>                            
            </div>
            <h4 class="panel-title">Servicios Abiertos</h4>
        </div>
        <!--Finalizando cabecera del panel-->
        <!--Empezando cuerpo del panel-->
        <div class="panel-body">
            <!--Empezando tabla de solicitudes asignadas-->
            <div class="row">
                <div class="col-md-12">          
                    <div class="form-group">
                        <table id="data-table-sevicios-asignados" class="table table-hover table-striped table-bordered no-wrap " style="cursor:pointer" width="100%">
                            <thead>
                                <tr>
                                    <th class="none">Id</th>
                                    <th class="all">Ticket</th>                                    
                                    <th class="all">Complejo</th>
                                    <th class="all">Servicio</th>
                                    <?php if ($datos['Servicios'] && isset($datos['Servicios'][0]['Atiende'])) { ?>
                                        <th class="all">Atiende</th>
                                    <?php } ?>
                                    <th class="all">Fecha Creación</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($datos['Servicios']) {
                                    foreach ($datos['Servicios'] as $value) {
                                        if ($value['IdEstatus'] === '1') {
                                            echo '<tr>';
                                            echo '<td>' . $value['Id'] . '</td>';
                                            echo '<td>' . $value['Ticket'] . '</td>';
                                            echo '<td>' . $value['Sucursal'] . '</td>';
                                            echo '<td>' . $value['Servicio'] . '</td>';
                                            if (isset($value['Atiende'])) {
                                                echo '<td>' . $value['Atiende'] . '</td>';
                                            }
                                            echo '<td>' . $value['FechaCreacion'] . '</td>';
                                            echo '</tr>';
                                        }
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>    
                </div> 
            </div>
            <!--Finalizando tabla de solicitudes asignadas-->            
        </div>
        <!--Finalizando cuerpo del panel-->
    </div>
    <!-- Finalizando panel Servicios asignados y abiertos -->   

    <!-- Empezando panel Servicios asignados y abiertos-->
    <div id="seccionServiciosProcesoAlmacen" class="panel panel-inverse">
        <!--Empezando cabecera del panel-->
        <div class="panel-heading">
            <div class="panel-heading-btn">
                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>                            
                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>                            
            </div>
            <h4 class="panel-title">Servicios en Proceso</h4>
        </div>
        <!--Finalizando cabecera del panel-->

        <!--Empezando cuerpo del panel-->
        <div class="panel-body">
            <!--Empezando tabla de solicitudes asignadas-->
            <div class="row">
                <div class="col-md-12">          
                    <div class="form-group">                        
                        <table id="data-table-sevicios-enproceso" class="table table-hover table-striped table-bordered no-wrap " style="cursor:pointer" width="100%">
                            <thead>
                                <tr>
                                    <th class="none">Id</th>
                                    <th class="all">Ticket</th>                                    
                                    <th class="all">Complejo</th>
                                    <th class="all">Servicio</th>
                                    <th class="all">Fecha Creación</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($datos['Servicios']) {
                                    foreach ($datos['Servicios'] as $value) {
                                        if ($value['IdEstatus'] === '2') {
                                            echo '<tr>';
                                            echo '<td>' . $value['Id'] . '</td>';
                                            echo '<td>' . $value['Ticket'] . '</td>';
                                            echo '<td>' . $value['Sucursal'] . '</td>';
                                            echo '<td>' . $value['Servicio'] . '</td>';
                                            echo '<td>' . $value['FechaCreacion'] . '</td>';
                                            echo '</tr>';
                                        }
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>    
                </div> 
            </div>
            <!--Finalizando tabla de solicitudes asignadas-->            
        </div>
        <!--Finalizando cuerpo del panel-->
    </div>
    <!-- Finalizando panel Servicios asignados y abiertos --> 

</div>
<!-- Finalizando #contenido -->
