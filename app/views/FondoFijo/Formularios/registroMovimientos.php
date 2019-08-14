<div id="seccionDetalleMovimientos">
<h1 class="page-header">Saldos técnico</h1>

    <div id="panelCuentas" class="panel panel-inverse">
        <div class="panel-heading">
            <button class="btn btn-primary btn-sm float-right" onclick="location.reload();"><i class="fa fa-mail-reply "></i> Recargar</button>
        </div>
        <div class="panel-body">
            <div id="seccionDetalleMovimientos">
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <h4>Registro de movimientos</h4>
                        <h5 id="usuarioNombre"></h5>
                        <h5 id="saldoNombre"></h5>
                    </div>
                    <div class="col-md-12 col-sm-12 col-xs-12 m-t-15">
                        <div class="underline m-b-10"></div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="table-responsive-movimientos">
                            <table id="tabla-movimientos" class="table table-bordered table-striped table-condensed">
                                <thead>
                                    <tr>
                                        <th class="all">Id</th>
                                        <th class="all">Movimiento</th>
                                        <th class="all">Fecha</th>
                                        <th class="all">Concepto</th>
                                        <th class="all">Monto</th>
                                        <th class="all">Tipo Movimiento</th>
                                    </tr>
                                </thead>
                                <tbody id="table_datos">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div> 
        </div>
    </div>
</div>


