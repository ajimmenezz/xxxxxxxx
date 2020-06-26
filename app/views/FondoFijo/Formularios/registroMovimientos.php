<div id="seccionDetalleMovimientos">
<h1 class="page-header">
    Saldos técnico
    <button class="pull-right btn btn-primary btn-sm float-right" onclick="location.reload();"><i class="fa fa-mail-reply "></i> Recargar</button>
</h1>


    <div id="panelCuentas" class="panel panel-inverse">
        <div class="panel-heading">
        </div>
        <div class="panel-body">
            <div id="seccionDetalleMovimientos">
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <h4>Registro de movimientos</h4>
                    </div>
                    <div class="col-md-12 col-sm-12 col-xs-12 m-t-15">
                        <div class="underline m-b-10"></div>
                    </div>
                    <div class="col-md-5 col-sm-12 col-xs-12">
                        <table class="table table-bordered table-striped table-condensed">
                            <thead>
                            </thead>
                            <tbody>
                                <tr>
                                    <th style=" background-color:#F0F3F5; text-align: right"id="usuarioNombreTable"></th>
                                    <th style=" background-color:white; text-align: left" id="usuarioNombre">Prueba</th>
                                </tr>
                                <tr>
                                    <th style=" background-color:#F0F3F5; text-align: right" id="saldo1Table"></th>
                                    <th style=" background-color:white; text-align: left" id="saldo1">Prueba3</th>
                                </tr>
                                <tr>
                                    <th style=" background-color:#F0F3F5; text-align: right" id="saldo2Table"></th>
                                    <th style=" background-color:white; text-align: left" id="saldo2">Prueba3</th>
                                </tr>
                                <tr>
                                    <th style=" background-color:#F0F3F5; text-align: right" id="saldo3Table"></th>
                                    <th style=" background-color:white; text-align: left" id="saldo3">Prueba3</th>
                                </tr>
                            </tbody>
                        </table>

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
                                        <th class="never">Id</th>
                                        <th class="all">Tipo Cuenta</th>
                                        <th class="all">Monto</th>
                                        <th class="all">Concepto</th>
                                        <th class="all">Estatus</th>
                                        <th class="all">Fecha Registro</th>
                                        <th class="all">Fecha Autorización</th>
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


