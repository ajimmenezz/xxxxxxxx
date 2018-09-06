<!-- Empezando #contenido -->
<div id="content" class="content">

    <!-- Empezando titulo de la pagina -->
    <h1 class="page-header">Usuarios</h1>
    <!-- Finalizando titulo de la pagina -->

    <!-- Empezando panel usuarios-->
    <div id="seccionUsuario" class="panel panel-inverse">

        <!--Empezando cabecera del panel-->
        <div class="panel-heading">
            <div class="panel-heading-btn">
                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>                            
                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>                            
            </div>
            <h4 class="panel-title">Usuarios</h4>
        </div>
        <!--Finalizando cabecera del panel-->

        <!--Empezando cuerpo del panel-->
        <div class="panel-body">

            <!--Inicia formulario para Usuarios-->
            <fieldset>  

                <div class="row"> 
                    <div class="col-md-12">
                        <div class="form-group">
                            <div class="form-inline muestraCarga"></div>
                        </div>
                    </div>
                </div>

                <!--Empezando error--> 
                <div class="row"> 
                    <div class="col-md-12">
                        <div class="errorResumenUsuario"></div>
                    </div>
                </div>
                <!--Finalizando Error-->

                <div class="row">
                    <div class="col-md-12">                        
                        <div class="form-group">
                            <h3 class="m-t-10">Lista de Usuarios</h3>
                            <!--Empezando Separador-->
                            <div class="col-md-12">
                                <div class="underline m-b-15 m-t-15"></div>
                            </div>
                        </div>    
                    </div> 
                </div>

                <!--Empezando tabla fila -->
                <div class="table-responsive">
                    <table id="data-table-usuarios" class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%">
                        <thead>
                            <tr>
                                <th class="never">Id</th>
                                <th class="all">Usuario</th>
                                <th class="all">Perfil</th>
                                <th class="never">PermisosAdicionales</th>
                                <th class="all">Nombre</th>
                                <th class="all">Email</th>
                                <th class="all">Email Corporativo</th>                          
                                <th class="all">Estatus</th>
                                <th class="never">SDKey</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($datos['ListaUsuarios'] as $key => $value) {
                                echo '<tr>';
                                echo '<td>' . $value['Id'] . '</td>';
                                echo '<td>' . $value['Usuario'] . '</td>';
                                echo '<td>' . $value['Perfil'] . '</td>';
                                echo '<td>' . $value['PermisosAdicionales'] . '</td>';
                                echo '<td>' . $value['Nombre'] . '</td>';
                                echo '<td>' . $value['Email'] . '</td>';
                                echo '<td>' . $value['EmailCorporativo'] . '</td>';
                                if ($value['Flag'] === '1') {
                                    echo '<td data-flag="' . $value['Flag'] . '">Activo</td>';
                                } else {
                                    echo '<td data-flag="' . $value['Flag'] . '">Inactivo</td>';
                                }
                                echo '<td>' . $value['SDKey'] . '</td>';
                                echo '</tr>';
                            }
                            ?>                                        
                        </tbody>
                    </table>

                </div>
                <!--Finalizando tabla fila -->
            </fieldset>
            <!-- Finaliza formulario para usuarios-->
        </div>
        <!--Finalizando cuerpo del panel-->
    </div>
    <!-- Finalizando panel usuarios -->   
</div>
<!-- Finalizando #contenido -->