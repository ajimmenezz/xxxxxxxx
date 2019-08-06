<!-- Empezando #contenido -->
<div id="content" class="content">    
    <!-- Empezando titulo de la pagina -->
    <h1 class="page-header">Catálogo <small>de Sucursales</small></h1>
    <!-- Finalizando titulo de la pagina -->
    <!-- Empezando panel catálogo de sucursales -->
    <div id="seccionSucursales" class="panel panel-inverse">
        <!--Empezando cabecera del panel-->
        <div class="panel-heading">
            <div class="panel-heading-btn">
                <label id="btnRegresarSucursales" class="btn btn-success btn-xs hidden">
                    <i class="fa fa fa-reply"></i> Regresar
                </label>   
                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>                            
                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>                            
            </div>
            <h4 class="panel-title">Catálogo de Sucursales</h4>
        </div>
        <!--Finalizando cabecera del panel-->
        <!--Empezando cuerpo del panel-->
        <div class="panel-body">
            <!--Empezando el formulario Sucursal -->
            <div id="formularioSucursal">
            </div>
            <!--Finalizando formulario Sucursal-->
            <div class="row m-t-10">
                <!--Empezando error--> 
                <div class="col-md-12">
                    <div class="errorSucursales"></div>
                </div>
                <!--Finalizando Error-->
            </div>   
            <!--Empezando tabla -->
            <div id='listaSucursales'>
                <div class="row">
                    <div class="col-md-12">                        
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6 col-xs-6">
                                    <h3 class="m-t-10">Lista de Sucursales</h3>
                                </div>
                                <div class="col-md-6 col-xs-6">
                                    <div class="form-group text-right">
                                        <?php
                                        $permisos = array('46','42','2','3','4');
                                        if (in_array($datos['Perfil'], $permisos)) {
                                            echo '<a href="javascript:;" class="btn btn-success btn-lg " id="btnAgregarSucursal"><i class="fa fa-plus"></i> Agregar</a>';
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>

                            <!--Empezando Separador-->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="underline m-b-15 m-t-15"></div>
                                </div>
                            </div>
                            <!--Finalizando Separador-->
                        </div>    
                    </div> 
                </div>
                <div class="table-responsive">
                    <table id="data-table-sucursales" class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%">
                        <thead>
                            <tr>
                                <th class="never">Id</th>
                                <th class="all">Sucursal</th>
                                <th class="all">Cliente</th>
                                <th class="all">Región</th>
                                <th class="all">Responsable</th>
                                <th class="all">Unidad Negocio</th>
                                <th class="all">País</th>
                                <th class="all">Estado</th>
                                <th class="all">Municipio</th>
                                <th class="all">CP</th>
                                <th class="never">Calle</th>
                                <th class="never">NoInt</th>
                                <th class="never">NoExt</th>
                                <th class="never">Telefono1</th>
                                <th class="never">Telefono2</th>
                                <th class="all">Estatus</th>
                                <th class="never">NombreCinemex</th>
                            </tr>
                        </thead>
                        <tbody>
<?php
foreach ($datos['ListaSucursales'] as $key => $value) {
    echo '<tr>';
    echo '<td>' . $value['Id'] . '</td>';
    echo '<td>' . $value['Nombre'] . '</td>';
    echo '<td>' . $value['Cliente'] . '</td>';
    if ($value['IdRegionCliente'] !== '0') {
        echo '<td>' . $value['Region'] . '</td>';
    } else {
        echo '<td>  </td>';
    }
    echo '<td>' . $value['Responsable'] . '</td>';
    echo '<td>' . $value['UnidadNegocio'] . '</td>';
    echo '<td>' . $value['Pais'] . '</td>';
    echo '<td>' . $value['Estado'] . '</td>';
    echo '<td>' . $value['Municipio'] . '</td>';
    echo '<td>' . $value['CP'] . '</td>';
    echo '<td>' . $value['Calle'] . '</td>';
    echo '<td>' . $value['NoInt'] . '</td>';
    echo '<td>' . $value['NoExt'] . '</td>';
    echo '<td>' . $value['Telefono1'] . '</td>';
    echo '<td>' . $value['Telefono2'] . '</td>';
    if ($value['Flag'] === '1') {
        echo '<td data-flag="' . $value['Flag'] . '">Activo</td>';
    } else {
        echo '<td data-flag="' . $value['Flag'] . '">Inactivo</td>';
    }
    echo '<td>' . $value['NombreCinemex'] . '</td>';
    echo '</tr>';
}
?>                                        
                        </tbody>
                    </table>
                </div>
                <!--Finalizando tabla-->
            </div>
            <!-- Finalizando panel catálogo de sucursales -->
        </div>
        <!-- Finalizando #contenido -->
    </div>
</div>