<!--
 * Description: Vista de la lista de facturas oc
 *
 * @author: Alberto Barcenas
 *
-->
<!-- Empezando #contenido -->
<div id="listaCompras" class="content">
    <!-- Empezando titulo de la pagina -->
    <h1 class="page-header">Facturación por O.C.</h1>
    <!-- Finalizando titulo de la pagina -->
    <!-- Empezando panel facturacion por OC -->
    <div id="panelSeguimientoCompras" class="panel panel-inverse">
        <!--Empezando cabecera del panel-->
        <div class="panel-heading">
            <div class="panel-heading-btn">
                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>                            
                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>                            
            </div>
            <h4 class="panel-title">Facturación por O.C.</h4>
        </div>
        <!--Finalizando cabecera del panel-->
        <!--Empezando cuerpo del panel-->
        <div class="panel-body">
            <div class="row"> 
                <!--Empezando error--> 
                <div class="col-md-12">
                    <div class="errorListaFacturasOC"></div>
                </div>
                <!--Finalizando Error-->
                <div class="col-md-12">  
                    <div class="form-group">
                        <div class="col-md-6">
                            <h3 class="m-t-10">Lista de Ordenes de Compra</h3>
                        </div>
                        <div class="col-md-12">
                            <div class="underline m-b-15 m-t-15"></div>
                        </div>
                        <!--Finalizando Separador-->
                    </div>    
                </div> 
            </div>
            <div class="table-responsive">
                <table id="data-table-facturas-oc" class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%">
                    <thead>
                        <tr>
                            <th class="all">Orden de Compra</th>
                            <th class="all">Proveedor</th>
                            <th class="all">Factura PDF</th>
                            <th class="all">Factura XML</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (!empty($datos['Servicios'])) {
                            foreach ($datos['Servicios'] as $key => $value) {
                                echo '<tr>';
                                echo '<td>' . $value['Id'] . '</td>';
                                echo '<td>' . $value['Ticket'] . '</td>';
                                echo '<td>' . $value['Servicio'] . '</td>';
                                echo '<td>' . $value['FechaCreacion'] . '</td>';
                                echo '<td>' . $value['Descripcion'] . '</td>';
                                echo '<td>' . $value['NombreEstatus'] . '</td>';
                                echo '<td>' . $value['IdEstatus'] . '</td>';
                                ($value['Folio'] === '0') ? $folio = '' : $folio = $value['Folio'];
                                echo '<td>' . $folio . '</td>';
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
    <!-- Finalizando panel facturacion por OC -->   
</div>
<!-- Finalizando #contenido -->

<!--Empezando seccion para el seguimiento de un servicio sin clasificar->-->
<!--<div id="seccionSeguimientoServicio" class="content hidden"></div>-->
<!-- Finalizando seccion para el seguimiento de un servicio sin clasificar 