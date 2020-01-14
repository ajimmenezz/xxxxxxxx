<!-- Empezando #contenido -->
<div id="content" class="content">
    <!-- Empezando titulo de la pagina -->
    <h1 class="page-header">Catálogo <small>de Áreas de Atención</small></h1>
    <!-- Finalizando titulo de la pagina -->
    <!-- Empezando panel catálogo de areas de atencion -->
    <div id="seccionAreasAtencion" class="panel panel-inverse">
        <!--Empezando cabecera del panel-->
        <div class="panel-heading">
            <div class="panel-heading-btn">
                <label id="btnRegresarAreasAtencion" class="btn btn-success btn-xs hidden">
                    <i class="fa fa fa-reply"></i> Regresar
                </label>   
                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>                            
                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>                            
            </div>
            <h4 class="panel-title">Catálogo de Áreas de Atención</h4>
        </div>
        <!--Finalizando cabecera del panel-->
        <!--Empezando cuerpo del panel-->
        <div class="panel-body">
            <div class="panel-body">
                <!--Empezando el formulario Areas de Atención -->
                <div id="formularioAreasAtencion">
                </div>
                <!--Finalizando formulario Areas de Atención-->
                <div class="row m-t-10">
                    <!--Empezando error--> 
                    <div class="col-md-12">
                        <div class="errorAreasAtencion"></div>
                    </div>
                    <!--Finalizando Error-->
                </div>   
                <div id='listaAreasAtencion'> 
                    <div class="row">
                        <div class="col-md-12">                        
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6 col-xs-6">
                                        <h3 class="m-t-10">Lista de Áreas de Atención</h3>
                                    </div>
                                    <div class="col-md-6 col-xs-6">
                                        <div class="form-group text-right">
                                            <a href="javascript:;" class="btn btn-success btn-lg " id="btnAgregarAreaAtencion"><i class="fa fa-plus"></i> Agregar</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <!--Empezando Separador-->
                                <div class="col-md-12">
                                    <div class="underline m-b-15 m-t-15"></div>
                                </div>
                                <!--Finalizando Separador-->
                            </div>    
                        </div> 
                    </div>
                    <div class="table-responsive">
                        <table id="data-table-areasAtencion" class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%">
                            <thead>
                                <tr>
                                    <th class="never">Id</th>
                                    <th class="all">Nombre</th>
                                    <th class="all">Cliente</th>
                                    <th class="all">Descripcion</th>
                                    <th class="all">Estatus</th>
                                    <th class="never">Clave</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (!empty($datos['ListaAreasAtencion'])) {
                                    foreach ($datos['ListaAreasAtencion'] as $key => $value) {
                                        echo '<tr>';
                                        echo '<td>' . $value['Id'] . '</td>';
                                        echo '<td>' . $value['Nombre'] . '</td>';
                                        echo '<td>' . $value['Cliente'] . '</td>';
                                        echo '<td>' . $value['Descripcion'] . '</td>';
                                        if ($value['Flag'] === '1') {
                                            echo '<td data-flag="' . $value['Flag'] . '">Activo</td>';
                                        } else {
                                            echo '<td data-flag="' . $value['Flag'] . '">Inactivo</td>';
                                        }
                                        echo '<td>' . $value['ClaveCorta'] . '</td>';
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
            <!-- Finalizando panel catálogo de areas de atencion -->
        </div>
    </div>
</div>
<!-- Finalizando #contenido -->