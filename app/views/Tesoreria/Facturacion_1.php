<!-- Empezando #contenido -->
<div id="listaFacturas" class="content">

    <!-- Empezando titulo de la pagina -->
    <h1 class="page-header">Facturación</h1>
    <!-- Finalizando titulo de la pagina -->

    <!-- Empezando panel facturacin tesoreria-->
    <div id="panelFacturacionTesoreria" class="panel panel-inverse">

        <!--Empezando cabecera del panel-->
        <div class="panel-heading">
            <div class="panel-heading-btn">
                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>                            
                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>                            
            </div>
            <h4 class="panel-title">Facturación</h4>
        </div>
        <!--Finalizando cabecera del panel-->

        <!--Empezando cuerpo del panel-->
        <div class="panel-body">
            <div class="row"> 

                <!--Empezando error--> 
                <div class="col-md-12">
                    <div class="errorListaFacturacionTesoreria"></div>
                </div>
                <!--Finalizando Error-->

                <div class="col-md-12">  
                    <div class="form-group">
                        <div class="col-md-6">
                            <h3 class="m-t-10">Lista Facturas</h3>
                        </div>
                        <div class="col-md-12">
                            <div class="underline m-b-15 m-t-15"></div>
                        </div>
                        <!--Finalizando Separador-->
                    </div>    
                </div> 
            </div>

            <div class="table-responsive">
                <table id="data-table-facturas-tesoreria" class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%">
                    <thead>
                        <tr>
                            <th class="all">Ticket</th>
                            <th class="all">Ingeniero</th>
                            <th class="all">Fecha Documenación</th>
                            <th class="all">Fecha Validación Supervisor</th>
                            <th class="all">Fecha Validación Cordinador</th>
                            <th class="all">Fecha Pago</th>
                            <th class="all">Estatus</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (!empty($datos['ServiciosFacturacion'])) {
                            foreach ($datos['ServiciosFacturacion'] as $key => $value) {
                                echo '<tr>';
                                echo '<td>' . $value['Ticket'] . '</td>';
                                echo '<td>' . $value['Ingeniero'] . '</td>';
                                echo '<td>' . $value['FechaDocumentacion'] . '</td>';
                                echo '<td>' . $value['FechaValidacionSup'] . '</td>';
                                echo '<td>' . $value['FechaValidacionCoord'] . '</td>';
                                echo '<td>' . $value['FechaPago'] . '</td>';
                                echo '<td>' . $value['Estatus'] . '</td>';
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
    <!-- Finalizando panel facturacion tesoreria -->

</div>
<!-- Finalizando #contenido -->

<!-- Empezando seccion para la Documentacion -->
<div id="seccionProcesoFacturacion" class="content hidden"></div>