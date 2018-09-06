<!-- Empezando #contenido -->
<div id="content" class="content">
    <!-- Empezando titulo de la pagina -->
    <h1 class="page-header">Catálogo <small>de Líneas de Equipo</small></h1>
    <!-- Finalizando titulo de la pagina -->
    <!-- Empezando panel catálogo de lineas -->
    <div id="seccionLineas" class="panel panel-inverse">
        <!--Empezando cabecera del panel-->
        <div class="panel-heading">
            <div class="panel-heading-btn">
                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>                            
                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>                            
            </div>
            <h4 class="panel-title">Catálogo de Líneas de Equipo</h4>
        </div>
        <!--Finalizando cabecera del panel-->
        <!--Empezando cuerpo del panel-->
        <div class="panel-body">
            <!--Empezando fila 1 -->
            <div id="formularioLinea" class="row m-t-10" >
            </div>
            <!--Finalizando fila 1-->
            <!--Empezando tabla fila 2 -->
            <div id='listaLineas' class="row"> 
                <!--Empezando error--> 
                <div class="col-md-12">
                    <div class="errorListaLineas"></div>
                </div>
                <!--Finalizando Error-->
                <div class="col-md-12">                        
                    <div class="form-group">
                        <div class="col-md-6">
                            <h3 class="m-t-10">Lista de Líneas</h3>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group text-right">
                                <a href="javascript:;" class="btn btn-success btn-lg " id="btnAgregarLinea"><i class="fa fa-plus"></i> Agregar</a>
                            </div>
                        </div>
                        <!--Empezando Separador-->
                        <div class="col-md-12">
                            <div class="underline m-b-15 m-t-15"></div>
                        </div>
                        <table id="data-table-lineas" class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%">
                            <thead>
                                <tr>
                                    <th class="never">Id</th>
                                    <th class="all">Nombre</th>
                                    <th class="all">Descripción</th>
                                    <th class="all">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($datos['ListaLineas'] as $key => $value) {
                                    echo '<tr>';
                                    echo '<td>' . $value['Id'] . '</td>';
                                    echo '<td>' . $value['Nombre'] . '</td>';
                                    echo '<td>' . $value['Descripcion'] . '</td>';
                                    if ($value['Flag'] === '1') {
                                        echo '<td data-flag="' . $value['Flag'] . '">Activo</td>';
                                    } else {
                                        echo '<td data-flag="' . $value['Flag'] . '">Inactivo</td>';
                                    }
                                    echo '</tr>';
                                }
                                ?>                                        
                            </tbody>
                        </table>
                    </div>    
                </div> 
            </div>
            <!--Finalizando tabla fila 2-->
        </div>
        <!--Finalizando cuerpo del panel-->
    </div>
    <!-- Finalizando panel catálogo de lineas -->
</div>
<!-- Finalizando #contenido -->