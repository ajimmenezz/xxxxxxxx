<!-- Empezando #contenido -->
<div id="content" class="content">
    <!-- Empezando titulo de la pagina -->
    <h1 class="page-header">Resumen <small>de Minutas</small></h1>
    <!-- Finalizando titulo de la pagina -->
    <!-- Empezando panel resumen personal -->
    <div id="seccionResumenMinuta" class="panel panel-inverse">
        <!--Empezando cabecera del panel-->
        <div class="panel-heading">
            <div class="panel-heading-btn">
                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>                            
                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>                            
            </div>
            <h4 class="panel-title">Resumen de Minutas</h4>
        </div>
        <!--Finalizando cabecera del panel-->
        <!--Empezando cuerpo del panel-->
        <div class="panel-body">
            <div id="tablaResumenMinuta"> 
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <div class="form-inline muestraCarga"></div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!--Empezando error--> 
                    <div class="col-md-12">
                        <div class="errorResumenPersonal"></div>
                    </div>
                    <!--Finalizando Error-->
                </div>

                <div class="row">
                    <div class="col-md-12">                        
                        <div class="form-group">

                            <div class="row">
                                <div id="nuevoPersonal" class="col-md-6 col-xs-6">
                                    <h3 class="m-t-10">Lista de Minutas</h3>
                                </div>
                                <div class="col-md-6 col-xs-6">
                                    <div class="form-group text-right">
                                        <a href="javascript:;" class="btn btn-success btn-lg " id="btnAgregarMinuta"><i class="fa fa-plus"></i> Agregar</a>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <!--Empezando Separador-->
                                <div class="col-md-12">
                                    <div class="underline m-b-15 m-t-15"></div>
                                </div>
                            </div>

                        </div>    
                    </div> 
                </div>
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="table-responsive">
                            <table id="data-table-minuta" class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%">
                                <thead>
                                    <tr>
                                        <th class="never">Id</th>
                                        <th class="all">Nombre de Minuta</th>
                                        <th class="all">Fecha</th>
                                        <th class="all">Ubicación</th>
                                        <th class="never">Miembros</th>
                                        <th class="never">Descripcion</th>
                                        <th class="never">Archivo</th>
                                        <th class="never">IdUsuario</th>
                                        <th class="all">Creador de la minuta</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    foreach ($datos['ListaMinutas'] as $key => $value) {
                                        echo '<tr>';
                                        echo '<td>' . $value['Id'] . '</td>';
                                        echo '<td>' . $value['Nombre'] . '</td>';
                                        echo '<td>' . $value['Fecha'] . '</td>';
                                        echo '<td>' . $value['Ubicacion'] . '</td>';
                                        echo '<td>' . $value['Miembros'] . '</td>';
                                        echo '<td>' . $value['Descripcion'] . '</td>';
                                        echo '<td>' . $value['Archivo'] . '</td>';
                                        echo '<td>' . $value['IdUsuario'] . '</td>';
                                        echo '<td>' . $value['Usuario'] . '</td>';
                                        echo '</tr>';
                                    }
                                    ?>                                        
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div id="seccionActualizarMinuta" class="row hidden"></div>
        </div>
    </div>
    <!-- Finalizando panel resumen personal -->
</div>
<!-- Finalizando #contenido -->