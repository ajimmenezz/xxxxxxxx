<!--Empezando seccion para mostrar los detalles del fondo fijo -->
<div id="seccionDetallesFondoFijo" class="content">
    <div class="row">
        <div class="col-md-6 col-sm-6 col-xs-12">
            <h1 class="page-header">Autorizar comprobantes de Fondo Fijo</h1>
        </div>     
    </div>    
    <div id="panelDetallesFondoFijo" class="panel panel-inverse">
        <!--Empezando cabecera del panel-->
        <div class="panel-heading">
            <h4 class="panel-title">Autorizar comprobantes de Fondo Fijo</h4>
        </div>
        <!--Finalizando cabecera del panel-->
        <!--Empezando cuerpo del panel-->
        <div class="panel-body">
            <div class="row"> 
                <!--Empezando error--> 
                <div class="col-md-12">
                    <div class="errorMessageFondoFijo"></div>
                </div>
                <!--Finalizando Error-->
                <div class="col-md-12">  
                    <div class="form-group">
                        <div class="col-md-12">
                            <h4>Comprobaciones por autorizar.</h4>
                        </div>
                        <div class="col-md-12">
                            <div class="underline m-b-15 m-t-5"></div>
                        </div>
                        <!--Finalizando Separador-->
                    </div>    
                </div> 
            </div>       
            <div class="row m-t-0">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="table-responsive m-t-0">    
                        <table id="table-comprobaciones-fondo-fijo" class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%">
                            <thead>
                                <tr>
                                    <th class="never">Id</th>
                                    <th class="all">Usuario</th>
                                    <th class="all">Fecha</th>
                                    <th class="all">Fecha Movimiento</th>
                                    <th class="all">Concepto</th>
                                    <th class="all">¿Extraordinario?</th>
                                    <th class="all">¿Dentro de presupuesto?</th>
                                    <th class="all">Monto</th>                                    
                                    <th class="all">Ticket</th>
                                    <th class="all">Tipo Comprobante</th>                                    
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (isset($datos['listaComprobaciones']) && count($datos['listaComprobaciones']) > 0) {
                                    foreach ($datos['listaComprobaciones'] as $key => $value) {
                                        echo ''
                                        . '<tr>'
                                        . '<td>' . $value['Id'] . '</td>'
                                        . '<td>' . $value['Usuario'] . '</td>'
                                        . '<td class="text-center">' . $value['Fecha'] . '</td>'
                                        . '<td class="text-center">' . $value['FechaMovimiento'] . '</td>'
                                        . '<td>' . $value['Nombre'] . '</td>'
                                        . '<td class="text-center">' . $value['Extraordinario'] . '</td>'
                                        . '<td class="text-center">' . $value['EnPresupuesto'] . '</td>'
                                        . '<td class="text-center f-w-700 f-s-14">$' . $value['Monto'] . '</td>'
                                        . '<td class="text-center">' . $value['Ticket'] . '</td>'
                                        . '<td>' . $value['TipoComprobante'] . '</td>'
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
        <!--Finalizando cuerpo del panel-->
    </div>
</div>
<!--Finalizando seccion para mostrar los detalles del fondo fijo -->

<!--Empezando seccion para mostrar el formulario de los depositos -->
<div id="seccionRegistrarDeposito" class="content" style="display: none"></div>
<!--Finalizando seccion para mostrar el formulario de los depositos -->

<div class="modal fade" id="modalEdit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body"></div>
            <div class="modal-footer">
                <div id="error-in-modal"></div>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="button" id="btnGuardarCambios" class="btn btn-primary">Guardar</button>
            </div>
        </div>
    </div>
</div>