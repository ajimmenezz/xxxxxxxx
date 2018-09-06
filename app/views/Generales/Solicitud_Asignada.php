<!-- Empezando #contenido -->
<div id="content" class="content">
    <!-- Empezando titulo de la pagina -->
    <h1 class="page-header">Solicitudes</h1>
    <!-- Finalizando titulo de la pagina -->

    <!-- Empezando panel nuevo proyecto-->
    <div id="seccionSolicitudesAsignadas" class="panel panel-inverse">
        <!--Empezando cabecera del panel-->
        <div class="panel-heading">
            <div class="panel-heading-btn">
                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>                            
                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>                            
            </div>
            <h4 class="panel-title">Solicitudes Asignadas</h4>
        </div>
        <!--Finalizando cabecera del panel-->
        <!--Empezando cuerpo del panel-->
        <div class="panel-body">            
            <!--Empezando tabla de solicitudes asignadas-->
            <div class="table-responsive">
                <table id="data-table-solicitudes-asignadas" class="table table-hover table-striped table-bordered no-wrap " style="cursor:pointer" width="100%">
                    <thead>
                        <tr>
                            <th class="all">Numero de solicitud</th>
                            <th class="all">Asunto</th>
                            <th class="all">Tipo</th>
                            <th class="all">Ticket</th>
                            <th class="all">Solicita</th>
                            <th class="all">Asunto SD</th>
                            <th class="all">Fecha</th>
                            <th class="all">Estatus</th>                                
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($datos['solicitudesAsignadas']['solicitudes'] as $key => $value) {
                            echo '<tr>';
                            echo '<td>' . $value['Numero'] . '</td>';
                            echo '<td>' . $value['Asunto'] . '</td>';
                            echo '<td>' . $value['Tipo'] . '</td>';
                            echo '<td>' . $value['Ticket'] . '</td>';
                            if (!empty($datos['solicitudesAsignadas']['SolicitudesSD'])) {
                                foreach ($datos['solicitudesAsignadas']['SolicitudesSD'] as $folioSD) {
                                    if (((string) $folioSD['solicitud']) === $value['Numero']) {
                                        echo '<td>' . $folioSD['datos']['Solicitante'] . '</td>';
                                        echo '<td>' . $folioSD['datos']['Asunto'] . '</td>';
                                    } else {
                                        echo '<td>' . $value['Solicita'] . '</td>';
                                        echo '<td></td>';
                                    }
                                }
                            } else {
                                echo '<td>' . $value['Solicita'] . '</td>';
                                echo '<td></td>';
                            }
                            echo '<td>' . $value['Fecha'] . '</td>';
                            echo '<td>' . $value['Estatus'] . '</td>';
                            echo '</tr>';
                        }
                        ?>    
                    </tbody>
                </table>
            </div>
            <!--Finalizando tabla de solicitudes asignadas-->            
        </div>
        <!--Finalizando cuerpo del panel-->
    </div>
    <!-- Finalizando panel nuevo proyecto -->   
</div>
<!-- Finalizando #contenido -->