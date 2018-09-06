<!--
 * Description: Formulario para mostra el resumen de vueltas dependiento el permiso asigando al usuario
 *
 * @author: Alberto Barcenas
 *
-->
<!-- Empezando #contenido -->
<div id="listaResumenVueltasAsociados" class="content">
    <!-- Empezando titulo de la pagina -->
    <h1 class="page-header">Resumen Vueltas Asociados</h1>
    <!-- Finalizando titulo de la pagina -->
    <!-- Empezando panel Resumen Vueltas Asociados -->
    <div id="panelResumenVueltasAsociados" class="panel panel-inverse">
        <!--Empezando cabecera del panel-->
        <div class="panel-heading">
            <div class="panel-heading-btn">
                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>                            
                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>                            
            </div>
            <h4 class="panel-title">Resumen Vueltas Asociados</h4>
        </div>
        <!--Finalizando cabecera del panel-->
        <!--Empezando cuerpo del panel-->
        <div class="panel-body">
            <div class="row"> 
                <!--Empezando error--> 
                <div class="col-md-12">
                    <div class="errorListaResumenVueltasAsociados"></div>
                </div>
                <!--Finalizando Error-->
                <div class="col-md-12">  
                    <div class="form-group">
                        <div class="col-md-12">
                            <h3 class="m-t-10">Lista Vueltas</h3>
                        </div>
                        <div class="col-md-12">
                            <div class="underline m-b-15 m-t-15"></div>
                        </div>
                        <!--Finalizando Separador-->
                    </div>    
                </div> 
            </div>
            <div class="table-responsive">
                <table id="data-table-resumen-vueltas-asocidos" class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%">
                    <thead>
                        <tr>
                            <th class="never">Id</th>
                            <th class="all">Servicio</th>
                            <th class="all">Ticket </th>
                            <th class="all">Folio</th>
                            <th class="all">Número de Vuelta</th>
                            <th class="all">Sucursal</th>
                            <th class="all">Tecnico</th>
                            <th class="all">Fecha</th>
                            <th class="all">Estatus</th>
                            <th class="all">Archivo</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (!empty($datos['ListaVueltasAsociados'])) {
                            $numVueltas = 1;
                            $folioAnterior = $datos['ListaVueltasAsociados'][0]['Folio'];
                            foreach ($datos['ListaVueltasAsociados'] as $key => $value) {

                                if ($value['Folio'] !== $folioAnterior) {
                                    $numVueltas = 1;
                                }

                                echo '<tr>';
                                echo '<td>' . $value['Id'] . '</td>';
                                echo '<td>' . $value['IdServicio'] . '</td>';
                                echo '<td>' . $value['Ticket'] . '</td>';
                                echo '<td>' . $value['Folio'] . '</td>';
                                echo '<td>' . $numVueltas . '</td>';
                                echo '<td>' . $value['Sucursal'] . '</td>';
                                echo '<td>' . $value['NombreAtiende'] . '</td>';
                                echo '<td>' . $value['Fecha'] . '</td>';
                                echo '<td>' . $value['Estatus'] . '</td>';
                                echo '<td><a href="' . $value['Archivo'] . '" target="_blank" class="btn btn-danger btn-xs "><i class="fa fa-file-pdf-o"></i> PDF</a></td>';
                                echo '</tr>';

                                $numVueltas++;

                                $folioAnterior = $value['Folio'];
                            }
                        }
                        ?>                                        
                    </tbody>
                </table>
            </div>
        </div>
        <!--Finalizando cuerpo del panel-->
    </div>
    <!-- Finalizando panel Resumen Vueltas Asociados -->   
</div>
<!-- Finalizando #contenido -->