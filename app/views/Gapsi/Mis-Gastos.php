<div id="divListaGastos" class="content">    
    <h1 class="page-header">Mis Gastos</h1>
    <div id="panelListaGastos" class="panel panel-inverse">        
        <div class="panel-heading">    
            <h4 class="panel-title">Lista de Solicitudes de Gasto</h4>
        </div>
        <div class="panel-body">
            <?php
//            echo "<pre>";
//            var_dump($datos['Gastos']);
//            echo "</pre>";
            $clase = 'never';
            if ($datos['Gastos']['permiso']) {
                $clase = 'all';
            }
            ?>
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <h4>Lista de Solicitudes de Gasto</h4>
                    <div class="underline m-b-10"></div>
                </div>
            </div>            
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div id="errorFormulario"></div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="table-responsive">
                        <table id="data-table-gastos" class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%">
                            <thead>
                                <tr>
                                    <th class="all">Id</th>
                                    <th class="<?php echo $clase; ?>">Usuario</th>
                                    <th class="all">OC</th>
                                    <th class="all">Fecha</th>
                                    <th class="all">Beneficiario</th>
                                    <th class="all">Proyecto</th>
                                    <th class="all">Tipo</th>
                                    <th class="all">Descripción</th>
                                    <th class="all">Importe</th>
                                    <th class="all">Moneda</th>                                    
                                    <th class="all">Estatus</th>                                    
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (isset($datos['Gastos']['gastos']) && count($datos['Gastos']['gastos']) > 0) {
                                    foreach ($datos['Gastos']['gastos'] as $key => $value) {
                                        $usuario = isset($datos['Gastos']['usuarios'][$value['ID']]) ? $datos['Gastos']['usuarios'][$value['ID']]['usuario'] : '';
                                        echo ''
                                        . '<tr>'
                                        . '  <td class="f-s-10">' . $value['ID'] . '</td>'
                                        . '  <td>' . $usuario . '</td>'
                                        . '  <td>' . $value['OrdenCompra'] . '</td>'
                                        . '  <td>' . substr($value['FechaSolicitud'], 0, 16) . '</td>'
                                        . '  <td>' . $value['Beneficiario'] . '</td>'
                                        . '  <td>' . $value['NameProyecto'] . '</td>'
                                        . '  <td>' . $value['TipoTrans'] . '</td>'
                                        . '  <td>' . $value['Descripcion'] . '</td>'
                                        . '  <td>$' . $value['Importe'] . '</td>'
                                        . '  <td>' . $value['Moneda'] . '</td>'
                                        . '  <td>' . $value['Status'] . '</td>'
                                        . '</tr>';
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>        
    </div>        
</div>    

<!--Empezando seccion para la captura del inventario por sala-->
<div id="divFormularioGasto" class="content" style="display:none"></div>
<!--Finalizando seccion para la captura del inventario por sala-->