<!-- Empezando #contenido -->
<div id="content" class="content">
    <!-- Empezando titulo de la pagina -->
    <h1 class="page-header">Notificaciones</h1>
    <!-- Finalizando titulo de la pagina -->

    <!-- Empezando panel nuevo proyecto-->
    <div id="seccion-notificaciones" class="panel panel-inverse borde-sombra">
        <!--Empezando cabecera del panel-->
        <div class="panel-heading">
            <div class="panel-heading-btn">
                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>                            
                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>                            
            </div>
            <h4 class="panel-title">Alertas</h4>
        </div>
        <!--Finalizando cabecera del panel-->
        <!--Empezando cuerpo del panel-->
        <div class="panel-body">
            <div class="row m-t-20">
                <div class="col-md-12">                            
                    <div class="form-group">
                        <table id="data-table-notificaciones" class="table table-hover table-striped table-bordered no-wrap " style="cursor:pointer" width="100%">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th class="never">Id</th>
                                    <th>Remitente</th>
                                    <th>Departamento</th>
                                    <th>Tipo</th>
                                    <th>Fecha</th>
                                    <th class="never">Descripcion</th>
                                    <th class="never">Url</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $tipoCarpeta = null;
                                foreach ($datos['Notificaciones'] as $key => $value) {
                                    if ($value['Flag'] === '1') {
                                        $tipoCarpeta = '<a href="#"><i class="fa fa-folder-o"></i></a>';
                                    } else {
                                        $tipoCarpeta = '<a href="#"><i class="fa fa-folder-open"></i></a>';
                                    }
                                    echo '<tr>
                                            <td>'.$tipoCarpeta.'</td>
                                            <td>'.$value['Id'].'</td>
                                            <td>' . $value['Remitente'] . '</td>
                                            <td>' . $value['Departamento'] . '</td>
                                            <td>' . $value['Tipo'] . '</td>
                                            <td>' . $value['Fecha'] . '</td>                                            
                                            <td>' . $value['Descripcion'] . '</td>                                            
                                            <td>' . $value['Url'] . '</td>                                            
                                        </tr>';
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>    
                </div>
            </div>
            <!--Finalizando cuerpo del panel-->
        </div>
        <!-- Finalizando panel nuevo proyecto -->   

    </div>
    <!-- Finalizando #contenido -->