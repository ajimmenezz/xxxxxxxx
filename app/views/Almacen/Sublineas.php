<!-- Empezando #contenido -->
<div id="content" class="content">
    <!-- Empezando titulo de la pagina -->
    <h1 class="page-header">Catálogo <small>de Sublíneas de Equipo</small></h1>
    <!-- Finalizando titulo de la pagina -->
    <!-- Empezando panel catálogo de Sublíneas -->
    <div id="seccionSublineas" class="panel panel-inverse">
        <!--Empezando cabecera del panel-->
        <div class="panel-heading">
            <div class="panel-heading-btn">
                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>                            
                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>                            
            </div>
            <h4 class="panel-title">Catálogo de Sublíneas de Equipo</h4>
        </div>
        <!--Finalizando cabecera del panel-->
        <!--Empezando cuerpo del panel-->
        <div class="panel-body">
            <!--Empezando fila 1 -->
            <div class="row" >
                <div id="formularioSublinea" class="col-md-12 col-xs-12 hidden" ></div>
            </div>
            <!--Finalizando fila 1-->
            <!--Empezando tabla fila 2 -->
            <div id='listaSublineas' class="row"> 
                <div class="col-md-12 col-xs-12">
                    <div class="row">
                        <!--Empezando error--> 
                        <div class="col-md-12">
                            <div class="errorListaSublineas"></div>
                        </div>
                        <!--Finalizando Error-->
                    </div>

                    <div class="row">
                        <!--Empezando tabla de sublíneas--> 
                        <div class="col-md-12">                        
                            <div class="form-group">
                                <div class="col-md-6">
                                    <h3 class="m-t-10">Lista de Sublíneas</h3>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group text-right">
                                        <a href="javascript:;" class="btn btn-success btn-lg " id="btnAgregarSublinea"><i class="fa fa-plus"></i> Agregar</a>
                                    </div>
                                </div>
                                <!--Empezando Separador-->
                                <div class="col-md-12">
                                    <div class="underline m-b-15 m-t-15"></div>
                                </div>
                                <table id="data-table-sublineas" class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%">
                                    <thead>
                                        <tr>
                                            <th class="never">IdLinea</th>
                                            <th class="never">IdSublinea</th>
                                            <th class="all">Sublínea</th>
                                            <th class="all">Línea</th>
                                            <th class="desktop tablet-l">Descripción</th>
                                            <th class="desktop tablet-l">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        foreach ($datos['ListaSublineas'] as $key => $value) {
                                            echo '<tr>';
                                            echo '<td>' . $value['IdLinea'] . '</td>';
                                            echo '<td>' . $value['IdSub'] . '</td>';
                                            echo '<td>' . $value['Sublinea'] . '</td>';
                                            echo '<td>' . $value['Linea'] . '</td>';
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
                        <!--Empezando tabla de sublíneas-->
                    </div>
                </div>
                <!--Finalizando tabla fila 2-->
            </div>
            <!--Finalizando cuerpo del panel-->
        </div>
        <!-- Finalizando panel catálogo de Sublineas -->
    </div>
    <!-- Finalizando #contenido -->
</div>