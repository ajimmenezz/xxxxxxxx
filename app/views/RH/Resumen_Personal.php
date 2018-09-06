<!-- Empezando #resumenPersonal -->
<div id="resumenPersonal" class="content">

    <!-- Empezando titulo de la pagina -->
    <h1 class="page-header">Perfil <small>de Personal</small></h1>
    <!-- Finalizando titulo de la pagina -->

    <!-- Empezando panel resumen personal -->
    <div id="seccionPersonal" class="panel panel-inverse">

        <!--Empezando cabecera del panel-->
        <div class="panel-heading">
            <div class="panel-heading-btn">
                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>                            
                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>                            
            </div>
            <h4 class="panel-title">Resumen de Personal</h4>
        </div>
        <!--Finalizando cabecera del panel-->

        <!--Empezando cuerpo del panel-->
        <div class="panel-body">
            <div class="row"> 
                <!--Empezando error--> 
                <div class="col-md-12">
                    <div class="errorResumenPersonal"></div>
                </div>
            </div>
            <!--Finalizando Error-->

            <div class="row">
                <div class="col-md-12">                        
                    <div class="form-group">
                        <div class="row">
                            <div id="nuevoPersonal" class="col-md-6 col-xs-6">
                                <h3 class="m-t-10">Lista de Personal</h3>
                            </div>
                            <div class="col-md-6 col-xs-6">
                                <div class="form-group text-right">
                                    <a href="javascript:;" class="btn btn-success btn-lg " id="btnAgregarPersonal"><i class="fa fa-plus"></i> Agregar</a>
                                </div>
                            </div>
                        </div>

                        <!--Empezando Separador-->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="underline m-b-15 m-t-15"></div>
                            </div>
                        </div>
                    </div>    
                </div> 
            </div>
            <div class="table-responsive">
                <table id="data-table-personal" class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%">
                    <thead>
                        <tr>
                            <th class="never">Id</th>
                            <th class="all">Apellido Paterno</th>
                            <th class="all">Apellido Materno</th>
                            <th class="all">Nombre(s)</th>
                            <th class="all">Área</th>
                            <th class="all">Depatamento</th>
                            <th class="all">Puesto</th>
                            <th class="never">Id Usuario</th>
                            <th class="never">Movil</th>
                            <th class="never">Fijo</th>
                            <th class="never">Email</th>
                            <th class="never">Fecha Nacimiento</th>
                            <th class="never">CURP</th>
                            <th class="never">RFC</th>
                            <th class="never">Fecha Ingreso</th>
                            <th class="never">No Seguro Social</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (!empty($datos['ListaPersonal'])) {
                            foreach ($datos['ListaPersonal'] as $key => $value) {
                                echo '<tr>';
                                echo '<td>' . $value['Id'] . '</td>';
                                echo '<td>' . $value['ApPaterno'] . '</td>';
                                echo '<td>' . $value['ApMaterno'] . '</td>';
                                echo '<td>' . $value['Nombres'] . '</td>';
                                echo '<td>' . $value['Area'] . '</td>';
                                echo '<td>' . $value['Departamento'] . '</td>';
                                echo '<td>' . $value['Perfil'] . '</td>';
                                echo '<td>' . $value['IdUsuario'] . '</td>';
                                echo '<td>' . $value['Tel1'] . '</td>';
                                echo '<td>' . $value['Tel2'] . '</td>';
                                echo '<td>' . $value['Email'] . '</td>';
                                echo '<td>' . $value['FechaNacimiento'] . '</td>';
                                echo '<td>' . $value['CURP'] . '</td>';
                                echo '<td>' . $value['RFC'] . '</td>';
                                echo '<td>' . $value['FechaAlta'] . '</td>';
                                echo '<td>' . $value['NSS'] . '</td>';
                                echo '</tr>';
                            }
                        }
                        ?>                                        
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- Finalizando panel resumen personal -->
</div>
<!-- Finalizando #resumenPersonal -->
<!-- Empezando #fromularioPersonal -->
<div id="formularioPersonal" class="content hidden">
</div>
<!-- Finalizando #formularioPersonal -->