<!-- Empezando #contenido -->
<div id="content" class="content">
    <!-- Empezando titulo de la pagina -->
    <h1 class="page-header">Catálogo <small>de Componentes de Equipo</small></h1>
    <!-- Finalizando titulo de la pagina -->
    <!-- Empezando panel catálogo de Componentes -->
    <div id="seccionComponentes" class="panel panel-inverse">
        <!--Empezando cabecera del panel-->
        <div class="panel-heading">
            <div class="panel-heading-btn">
                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>                            
                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>                            
            </div>
            <h4 class="panel-title">Catálogo de Componentes de Equipo</h4>
        </div>
        <!--Finalizando cabecera del panel-->
        <!--Empezando cuerpo del panel-->
        <div class="panel-body">
            <!--Empezando fila 1 -->
            <div class="row" >
                <div id="formularioComponente" class="col-md-12 col-xs-12 hidden" ></div>
            </div>
            <!--Finalizando fila 1-->
            <!--Empezando tabla fila 2 -->
            <div id='listaComponentes' class="row"> 
                <div class="col-md-12 col-xs-12">
                    <div class="row">
                        <!--Empezando error--> 
                        <div class="col-md-12">
                            <div class="errorListaComponentes"></div>
                        </div>
                        <!--Finalizando Error-->
                    </div>

                    <div class="row">
                        <!--Empezando tabla de componentes--> 
                        <div class="col-md-12">                        
                            <div class="form-group">
                                <div class="col-md-6">
                                    <h3 class="m-t-10">Lista de Componentes</h3>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group text-right">
                                        <a href="javascript:;" class="btn btn-success btn-lg " id="btnAgregarComponente"><i class="fa fa-plus"></i> Agregar</a>
                                    </div>
                                </div>
                                <!--Empezando Separador-->
                                <div class="col-md-12">
                                    <div class="underline m-b-15 m-t-15"></div>
                                </div>
                                <table id="data-table-componentes" class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%">
                                    <thead>
                                        <tr>
                                            <th class="never">IdMod</th>                                            
                                            <th class="never">IdCom</th>                                            
                                            <th class="all">Componente</th>
                                            <th class="all">No. Parte</th>
                                            <th class="all">Equipo</th>                                            
                                            <th class="all">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if (!empty($datos['ListaComponentes'])) {
                                            foreach ($datos['ListaComponentes'] as $key => $value) {
                                                echo '<tr>';
                                                echo '<td>' . $value['IdMod'] . '</td>';
                                                echo '<td>' . $value['IdCom'] . '</td>';
                                                echo '<td>' . $value['Componente'] . '</td>';
                                                echo '<td>' . $value['Parte'] . '</td>';
                                                echo '<td>' . $value['Equipo'] . '</td>';
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
                        <!--Empezando tabla de componentes-->
                    </div>
                </div>
                <!--Finalizando tabla fila 2-->
            </div>
            <!--Finalizando cuerpo del panel-->
        </div>
        <!-- Finalizando panel catálogo de Componentes -->
    </div>
    <!-- Finalizando #contenido -->
</div>