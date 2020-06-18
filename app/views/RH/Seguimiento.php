<!-- Empezando #contenido -->
<!--<div id="listaRH" class="content">
     Empezando titulo de la pagina 
    <h1 class="page-header">Seguimiento <small> de servicios de RH</small></h1>
     Finalizando titulo de la pagina 

     Empezando panel Servicios asignados y abiertos
    <div id="seccionServiciosAsignadosRH" class="panel panel-inverse">
        Empezando cabecera del panel
        <div class="panel-heading">
            <div class="panel-heading-btn">
                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>                            
                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>                            
            </div>
            <h4 class="panel-title">Servicios Abiertos</h4>
        </div>
        Finalizando cabecera del panel
        Empezando cuerpo del panel
        <div class="panel-body">
            Empezando tabla de solicitudes asignadas
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
//                                if ($datos['Servicios']) {
//                                    foreach ($datos['Servicios'] as $value) {
//                                        if ($value['IdEstatus'] === '1') {
//                                            echo '<tr>';
//                                            echo '<td>' . $value['Id'] . '</td>';
//                                            echo '<td>' . $value['Ticket'] . '</td>';
////                                            echo '<td>' . $value['Sucursal'] . '</td>';
//                                            echo '<td>' . $value['Servicio'] . '</td>';
//                                            if (isset($value['Atiende'])) {
//                                                echo '<td>' . $value['Atiende'] . '</td>';
//                                            }
//                                            echo '<td>' . $value['FechaCreacion'] . '</td>';
//                                            echo '</tr>';
//                                        }
//                                    }
//                                }
?>
                            </tbody>
                        </table>
                    </div>    
                </div> 
            </div>
            Finalizando tabla de solicitudes asignadas            
        </div>
        Finalizando cuerpo del panel
    </div>-->
<!-- Finalizando panel Servicios asignados y abiertos -->   

<!-- Empezando panel Servicios asignados y abiertos-->
<!--    <div id="seccionServiciosProcesoRH" class="panel panel-inverse">
        Empezando cabecera del panel
        <div class="panel-heading">
            <div class="panel-heading-btn">
                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>                            
                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>                            
            </div>
            <h4 class="panel-title">Servicios en Proceso</h4>
        </div>
        Finalizando cabecera del panel

        Empezando cuerpo del panel
        <div class="panel-body">
            Empezando tabla de solicitudes asignadas
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
//                                if ($datos['Servicios']) {
//                                    foreach ($datos['Servicios'] as $value) {
//                                        if ($value['IdEstatus'] === '2') {
//                                            echo '<tr>';
//                                            echo '<td>' . $value['Id'] . '</td>';
//                                            echo '<td>' . $value['Ticket'] . '</td>';
////                                            echo '<td>' . $value['Sucursal'] . '</td>';
//                                            echo '<td>' . $value['Servicio'] . '</td>';
//                                            echo '<td>' . $value['FechaCreacion'] . '</td>';
//                                            echo '</tr>';
//                                        }
//                                    }
//                                }
?>
                            </tbody>
                        </table>
                    </div>    
                </div> 
            </div>
            Finalizando tabla de solicitudes asignadas            
        </div>
        Finalizando cuerpo del panel
    </div>-->
<!-- Finalizando panel Servicios asignados y abiertos --> 

<!--Empezando panel para el seguimiento del servicio-->
<!--    <div id="seccionSeguimientoServicio" class="panel panel-inverse hidden">
        Empezando cabecera del panel
        <div class="panel-heading">
            <div class="panel-heading-btn">
                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>                            
                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>                            
            </div>
            <h4 class="panel-title">Seguimiento Servicio</h4>
        </div>
        Finalizando cabecera del panel
        Empezando cuerpo del panel
        <div class="panel-body">
            
        </div>
        Finalizando cuerpo del panel
    </div>-->
<!-- Finalizando panel para el seguimiento del servicio -->

<!--</div>-->
<!-- Finalizando #contenido -->


<!-- Empezando #contenido -->
<div id="listaServicio" class="content">
    <!-- Empezando titulo de la pagina -->
    <h1 class="page-header">Seguimiento RH</h1>
    <!-- Finalizando titulo de la pagina -->
    <!-- Empezando panel seguimiento RH-->
    <div id="panelSeguimientoRH" class="panel panel-inverse">
        <!--Empezando cabecera del panel-->
        <div class="panel-heading">
            <div class="panel-heading-btn">
                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>                            
                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>                            
            </div>
            <h4 class="panel-title">Seguimiento RH</h4>
        </div>
        <!--Finalizando cabecera del panel-->
        <!--Empezando cuerpo del panel-->
        <div class="panel-body">
            <div class="row"> 
                <!--Empezando error--> 
                <div class="col-md-12">
                    <div class="errorListaRH"></div>
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
                <table id="data-table-rh" class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%">
                    <thead>
                        <tr>
                            <th class="never">Id</th>
                            <th class="all">Ticket</th>
                            <th class="all">Servicio</th>
                            <th class="all">Fecha de Creación</th>
                            <th class="all">Descripcion</th>
                            <th class="all">Estatus</th>
                            <th class="never">IdEstatus</th>
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
                                echo '<td>' . $value['NombreEstatus'] . '</td>';
                                echo '<td>' . $value['IdEstatus'] . '</td>';
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
    <!-- Finalizando panel seguimiento RH -->   
</div>
<!-- Finalizando #contenido -->

<!--Empezando seccion para el seguimiento de un servicio sin clasificar->-->
<div id="seccionSeguimientoServicio" class="content hidden"></div>
<!-- Finalizando seccion para el seguimiento de un servicio sin clasificar 