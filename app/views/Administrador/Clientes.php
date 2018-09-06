<!-- Empezando #contenido -->
<div id="content" class="content">
    <!-- Empezando titulo de la pagina -->
    <h1 class="page-header">Catálogo <small>de Clientes</small></h1>
    <!-- Finalizando titulo de la pagina -->
    <!-- Empezando panel catálogo de clientes -->
    <div id="seccionClientes" class="panel panel-inverse">
        <!--Empezando cabecera del panel-->
        <div class="panel-heading">
            <div class="panel-heading-btn">
                <label id="btnRegresarClientes" class="btn btn-success btn-xs hidden">
                    <i class="fa fa fa-reply"></i> Regresar
                </label>   
                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>                            
                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>                            
            </div>
            <h4 class="panel-title">Catálogo de Clientes</h4>
        </div>
        <!--Finalizando cabecera del panel-->
        <!--Empezando cuerpo del panel-->
        <div class="panel-body">
            <div class="panel-body">
                <!--Empezando el formulario Cliente -->
                <div id="formularioClientes">
                </div>
                <!--Finalizando formulario Cliente-->
                <div class="row m-t-10">
                    <!--Empezando error--> 
                    <div class="col-md-12">
                        <div class="errorClientes"></div>
                    </div>
                    <!--Finalizando Error-->
                </div> 
                <!--Empezando tabla fila 2 -->
                <div id='listaClientes' class="row"> 
                    <div class="col-md-12">                        
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <h3 class="m-t-10">Lista de Clientes</h3>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group text-right">
                                        <a href="javascript:;" class="btn btn-success btn-lg " id="btnAgregarCliente"><i class="fa fa-plus"></i> Agregar</a>
                                    </div>
                                </div>
                                <!--Empezando Separador-->
                                <div class="col-md-12">
                                    <div class="underline m-b-15 m-t-15"></div>
                                </div>
                            </div>
                            <!--Finalizando Separador-->
                            <table id="data-table-clientes" class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%">
                                <thead>
                                    <tr>
                                        <th class="never">Id</th>
                                        <th class="all">Cliente</th>
                                        <th class="desktop">Razón Social</th>
                                        <th class="all">País</th>
                                        <th class="desktop">Estado</th>
                                        <th class="desktop">Municipio</th>
                                        <th class="desktop">CP</th>
                                        <th class="never">Representante</th>
                                        <th class="never">Calle</th>
                                        <th class="never">NoInt</th>
                                        <th class="never">NoExt</th>
                                        <th class="never">Telefono1</th>
                                        <th class="never">Telefono2</th>
                                        <th class="never">Email</th>
                                        <th class="never">Web</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    foreach ($datos['ListaClientes'] as $key => $value) {
                                        echo '<tr>';
                                        echo '<td>' . $value['Id'] . '</td>';
                                        echo '<td>' . $value['Nombre'] . '</td>';
                                        echo '<td>' . $value['RazonSocial'] . '</td>';
                                        echo '<td>' . $value['Pais'] . '</td>';
                                        echo '<td>' . $value['Estado'] . '</td>';
                                        echo '<td>' . $value['Municipio'] . '</td>';
                                        echo '<td>' . $value['CP'] . '</td>';
                                        echo '<td>' . $value['Representante'] . '</td>';
                                        echo '<td>' . $value['Calle'] . '</td>';
                                        echo '<td>' . $value['NoInt'] . '</td>';
                                        echo '<td>' . $value['NoExt'] . '</td>';
                                        echo '<td>' . $value['Telefono1'] . '</td>';
                                        echo '<td>' . $value['Telefono2'] . '</td>';
                                        echo '<td>' . $value['Email'] . '</td>';
                                        echo '<td>' . $value['Web'] . '</td>';
                                        echo '</tr>';
                                    }
                                    ?>                                        
                                </tbody>
                            </table>
                        </div>    
                    </div> 
                </div>
                <!--Finalizando tabla fila 2-->
                <!-- Finaliza formulario para catálogo de clientes -->
            </div>
            <!--Finalizando cuerpo del panel-->
        </div>
    </div>
    <!-- Finalizando panel catálogo de clientes -->
</div>
<!-- Finalizando #contenido -->