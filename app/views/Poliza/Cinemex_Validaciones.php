<!--
 * Description: Catalogo de Cinemex Validaciones Poliza
 *
 * @author: Alberto Barcenas
 *
-->
<!-- Empezando #contenido -->
<div id="content" class="content">

    <!-- Empezando titulo de la pagina -->
    <h1 class="page-header">Catálogo <small>Personal de Cinemex para Validaciones</small></h1>
    <!-- Finalizando titulo de la pagina -->

    <!-- Empezando panel catálogo Cinemex Validaciones -->
    <div id="seccionCinemexValidaciones" class="panel panel-inverse">

        <!-- Empezando cabecera del panel -->
        <div class="panel-heading">
            <div class="panel-heading-btn">
                <label id="btnRegresarCinemexValidaciones" class="btn btn-success btn-xs hidden">
                    <i class="fa fa fa-reply"></i> Regresar
                </label>   
                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>                            
                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>                            
            </div>
            <h4 class="panel-title">Catálogo Personal de Cinemex para Validaciones</h4>
        </div>
        <!-- Finalizando cabecera del panel -->

        <div class="panel-body">

            <!-- Empezando el formulario Cinemec Validaciones -->
            <div id="formularioCinemexValidacion">
            </div>
            <!-- Finalizando formulario Cinemex Validaciones -->

            <div class="row m-t-10">
                <!--Empezando error--> 
                <div class="col-md-12">
                    <div class="errorCinemexValidaciones"></div>
                </div>
                <!--Finalizando Error-->
            </div>   

            <!--Empezando tabla -->
            <div id='listaCinemexValidaciones'>
                <div class="row">
                    <div class="col-md-12">                        
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6 col-xs-6">
                                    <h3 class="m-t-10">Lista de Personal de Cinemex para Validaciones</h3>
                                </div>
                                <div class="col-md-6 col-xs-6">
                                    <div class="form-group text-right">
                                        <a href="javascript:;" class="btn btn-success btn-lg " id="btnAgregarCinemexValidacion"><i class="fa fa-plus"></i> Agregar</a>
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
                    <table id="data-table-cinemex-validaciones" class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%">
                        <thead>
                            <tr>
                                <th class="never">Id</th>
                                <th class="all">Nombre</th>
                                <th class="all">Correo</th>
                                <th class="all">Estatus</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (!empty($datos['ListaCinemexValidaciones'])) {
                                foreach ($datos['ListaCinemexValidaciones'] as $key => $value) {
                                    echo '<tr>';
                                    echo '<td>' . $value['Id'] . '</td>';
                                    echo '<td>' . $value['Nombre'] . '</td>';
                                    echo '<td>' . $value['Correo'] . '</td>';
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
            <!--Finalizando tabla-->

        </div>        
    </div>
    <!-- Finalizando panel catálogo Cinemex Validaciones -->

</div>
<!-- Finalizando #contenido -->