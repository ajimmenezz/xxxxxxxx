<!-- Empezando #contenido -->
<div id="content" class="content">
    <!-- Empezando titulo de la pagina -->
    <h1 class="page-header">Catálogo <small>de Proveedores</small></h1>
    <!-- Finalizando titulo de la pagina -->
    <!-- Empezando panel catálogo de proveedores -->
    <div id="seccionProveedores" class="panel panel-inverse">
        <!--Empezando cabecera del panel-->
        <div class="panel-heading">
            <div class="panel-heading-btn">
                <label id="btnRegresarProveedores" class="btn btn-success btn-xs hidden">
                    <i class="fa fa fa-reply"></i> Regresar
                </label>  
                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>                            
                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>                            
            </div>
            <h4 class="panel-title">Catálogo de Proveedores</h4>
        </div>
        <!--Finalizando cabecera del panel-->
        <!--Empezando cuerpo del panel-->
        <div class="panel-body">
            <div class="panel-body">
                <!--Empezando el formulario Proverdor -->
                <div id="formularioProveedor">
                </div>
                <!--Finalizando formulario Proveerdor -->
                <div class="row m-t-10">
                    <!--Empezando error--> 
                    <div class="col-md-12">
                        <div class="errorProveedores"></div>
                    </div>
                    <!--Finalizando Error-->
                </div> 
                <!--Empezando tabla fila 2 -->
                <div id='listaProveedores' class="row"> 
                    <div class="col-md-12">                        
                        <div class="form-group">
                            <div class="row">
                                <!--Empezando Titulo-->
                                <div class="col-md-6">
                                    <h3 class="m-t-10">Lista de Proveedores</h3>
                                </div>
                                <!--Finalizando Titulo-->
                                <div class="col-md-6">
                                    <div class="form-group text-right">
                                        <a href="javascript:;" class="btn btn-success btn-lg " id="btnAgregarProveedor"><i class="fa fa-plus"></i> Agregar</a>
                                    </div>
                                </div>
                                <!--Empezando Separador-->
                                <div class="col-md-12">
                                    <div class="underline m-b-15 m-t-15"></div>
                                </div>
                                <!--Finalizando Separador-->
                            </div>

                            <table id="data-table-proveedores" class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%">
                                <thead>
                                    <tr>
                                        <th class="never">Id</th>
                                        <th class="all">Proveedor</th>
                                        <th class="never">Razon Social</th>
                                        <th class="all">País</th>
                                        <th class="desktop">Estado</th>
                                        <th class="desktop">Municipio</th>
                                        <th class="desktop">CP</th>
                                        <th class="never">Calle</th>                                    
                                        <th class="never">NoExt</th>
                                        <th class="never">NoInt</th>
                                        <th class="never">Telefono1</th>
                                        <th class="never">Telefono2</th>
                                        <th class="all">Estatus</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (!empty($datos['ListaProveedores'])) {
                                        foreach ($datos['ListaProveedores'] as $key => $value) {
                                            echo '<tr>';
                                            echo '<td>' . $value['Id'] . '</td>';
                                            echo '<td>' . $value['Nombre'] . '</td>';
                                            echo '<td>' . $value['RazonSocial'] . '</td>';
                                            echo '<td>' . $value['Pais'] . '</td>';
                                            echo '<td>' . $value['Estado'] . '</td>';
                                            echo '<td>' . $value['Municipio'] . '</td>';
                                            echo '<td>' . $value['CP'] . '</td>';
                                            echo '<td>' . $value['Calle'] . '</td>';
                                            echo '<td>' . $value['NoExt'] . '</td>';
                                            echo '<td>' . $value['NoInt'] . '</td>';
                                            echo '<td>' . $value['Telefono1'] . '</td>';
                                            echo '<td>' . $value['Telefono2'] . '</td>';
                                            if ($value['Flag'] === '1') {
                                                echo '<td data-flag="' . $value['Flag'] . '">Activo</td>';
                                            } else {
                                                echo '<td data-flag="' . $value['Flag'] . '">Inactivo</td>';
                                            }
                                            echo '</tr>';
                                        }
                                    }
                                    ?>                                        
                                </tbody>
                            </table>
                        </div>    
                    </div> 
                </div>
                <!--Finalizando tabla fila 2-->
                <!-- Finaliza formulario para catálogo de proveedores -->
            </div>
        </div>
        <!--Finalizando cuerpo del panel-->
    </div>
    <!-- Finalizando panel catálogo de proveedores -->
</div>
<!-- Finalizando #contenido -->