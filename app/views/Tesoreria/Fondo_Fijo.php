<!-- Empezando #contenido -->
<div id="listaFondosFijos" class="content">
    <!-- Empezando titulo de la pagina -->
    <h1 class="page-header">Seguimiento Fondos Fijos</h1>
    <!-- Finalizando titulo de la pagina -->
    <!-- Empezando panel seguimiento poliza -->
    <div id="panelFondosFijos" class="panel panel-inverse">
        <!--Empezando cabecera del panel-->
        <div class="panel-heading">
            <div class="panel-heading-btn">
                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>                            
                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>                            
            </div>
            <h4 class="panel-title">Seguimiento Fondos Fijos</h4>
        </div>
        <!--Finalizando cabecera del panel-->
        <!--Empezando cuerpo del panel-->
        <div class="panel-body">
            <div class="row"> 
                <!--Empezando error--> 
                <div class="col-md-12">
                    <div class="errorListaFondosFijos"></div>
                </div>
                <!--Finalizando Error-->
                <div class="col-md-12">  
                    <div class="form-group">
                        <div class="col-md-12">
                            <h3 class="m-t-10">Lista de Personal con Fondos Fijos</h3>
                        </div>
                        <div class="col-md-12">
                            <div class="underline m-b-15 m-t-15"></div>
                        </div>
                        <!--Finalizando Separador-->
                    </div>    
                </div> 
            </div>
            <div class="table-responsive">
                <table id="table-fondos-fijos" class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%">
                    <thead>
                        <tr>
                            <th class="never">IdUsuario</th>
                            <th class="all">Usuario</th>
                            <th class="all">Monto Autorizado de Fondo Fijo</th>
                            <th class="all">Fecha de Último Depósito</th>
                            <th class="all">Monto de Último Depósito</th>                            
                            <th class="all">Último Saldo Conocido</th>
                            <th class="all">Fecha de último saldo</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (isset($datos['FondosFijos']) && count($datos['FondosFijos']) > 0) {
                            foreach ($datos['FondosFijos'] as $key => $value) {
                                $fecha = ($value['Fecha'] != '') ? $value['Fecha'] : 'N.E.';
                                $monto = ($value['Monto'] != '') ? '$' . $value['Monto'] : 'N.E.';
                                $saldo = ($value['Saldo'] != '') ? '$' . $value['Saldo'] : 'N.E.';
                                $fechaSaldo = ($value['FechaSaldo'] != '') ? $value['FechaSaldo'] : 'N.E.';

                                echo '<tr>';
                                echo '<td>' . $value['IdUsuario'] . '</td>';
                                echo '<td>' . $value['Usuario'] . '</td>';
                                echo '<td class="text-center">$' . $value['MontoUsuario'] . '</td>';
                                echo '<td class="text-center">' . $fecha . '</td>';
                                echo '<td class="text-center">' . $monto . '</td>';
                                echo '<td class="text-center">' . $saldo . '</td>';
                                echo '<td class="text-center">' . $fechaSaldo . '</td>';
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
    <!-- Finalizando panel seguimiento poliza -->   
</div>
<!-- Finalizando #contenido -->


<!--Empezando seccion para mostrar los detalles del fondo fijo -->
<div id="seccionDetallesFondoFijo" class="content" style="display: none"></div>
<!--Finalizando seccion para mostrar los detalles del fondo fijo -->

<!--Empezando seccion para mostrar el formulario de los depositos -->
<div id="seccionRegistrarDeposito" class="content" style="display: none"></div>
<!--Finalizando seccion para mostrar el formulario de los depositos -->