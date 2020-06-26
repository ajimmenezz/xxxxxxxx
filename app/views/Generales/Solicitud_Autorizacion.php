<!-- Empezando #contenido -->
<div id="content" class="content">
    <!-- Empezando titulo de la pagina -->
    <h1 class="page-header">Solicitudes</h1>
    <!-- Finalizando titulo de la pagina -->

    <!-- Empezando panel nuevo proyecto-->
    <div id="seccionAutorizacion" class="panel panel-inverse">
        <!--Empezando cabecera del panel-->
        <div class="panel-heading">
            <div class="panel-heading-btn">
                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>                            
                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>                            
            </div>
            <h4 class="panel-title">Autorización de solicitud</h4>
        </div>
        <!--Finalizando cabecera del panel-->
        <!--Empezando cuerpo del panel-->
        <div class="panel-body">
            <?php if (!empty($datos['solicitudesAutorizacion'])) { ?>
                <!--Empezando tabla de solicitudes para autorizacion-->
                <div class="table-responsive">
                    <table id="data-table-solicitudes-autorizacion" class="table table-hover table-striped table-bordered no-wrap " style="cursor:pointer" width="100%">
                        <thead>
                            <tr>
                                <th class="all">Numero de solicitud</th>                                    
                                <th class="all">Asunto</th>                                    
                                <th class="all">Tipo</th>                                    
                                <th class="all">Solicita</th>
                                <th class="all">Fecha Creación</th>
                                <th class="all">Departamento</th>
                                <th class="all">Estatus</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (gettype($datos['solicitudesAutorizacion']) !== 'string') {
                                foreach ($datos['solicitudesAutorizacion'] as $key => $value) {
                                    echo '<tr>';
                                    echo '<td>' . $value['Numero'] . '</td>';
                                    echo '<td>' . $value['Asunto'] . '</td>';
                                    echo '<td>' . $value['Tipo'] . '</td>';
                                    echo '<td>' . $value['Solicita'] . '</td>';
                                    echo '<td>' . $value['Fecha'] . '</td>';
                                    echo '<td>' . $value['Departamento'] . '</td>';
                                    echo '<td>' . $value['Estatus'] . '</td>';
                                    echo '</tr>';
                                }
                            }
                            ?>    
                        </tbody>
                    </table>

                </div>
                <!--Finalizando tabla de solicitudes asignadas-->
            <?php } else { ?>
                <!--Empezando Mensaje sin permisos-->
                <div class="row">
                    <div class="col-md-12 text-center">
                        <h2>No cuentas con los permisos para autorizar solicitudes.</h2>
                    </div>
                </div>
                <!--Finalizando Mensaje sin permisos-->
            <?php } ?>
        </div>
        <!--Finalizando cuerpo del panel-->
    </div>
    <!-- Finalizando panel nuevo proyecto -->   

</div>
<!-- Finalizando #contenido -->