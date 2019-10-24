<!-- Empezando #contenido -->
<div id="content" class="content">
    <!-- Empezando titulo de la pagina -->
    <h1 class="page-header">Movimientos en Almacenes</h1>
    <!-- Finalizando titulo de la pagina -->

    <div id="seccion-movimientos-almacenes-SAE" class="panel panel-inverse borde-sombra">
        <!--Empezando cabecera del panel-->
        <div class="panel-heading">
            <div class="panel-heading-btn">
                <div class="btn-group">
                    <button type="button" class="btn btn-warning btn-xs dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Acciones <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu">
                        <li id="btnExportarExcel"><a href="#"><i class="fa fa-file-excel-o"></i> Exportar Excel</a></li>
                    </ul>
                </div>
            </div>
            <h4 class="panel-title">Movimientos en Almacenes</h4>
        </div>
        <!--Finalizando cabecera del panel-->
        <!--Empezando cuerpo del panel-->
        <div class="panel-body">
            <div class="row">
                <div class="col-md-12">
                    <fieldset>
                        <legend class="pull-left width-full f-s-17">Definición de Fechas</legend>
                    </fieldset>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4 col-sm-5 col-xs-12">
                    <div class="form-group">
                        <label>Desde *</label>
                        <div class='input-group date' id='desdeMovimientos'>
                            <input type='text' id="txtDesdeMovimientos" class="form-control" value="" />
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-5 col-xs-12">
                    <div class="form-group">
                        <label>Hasta *</label>
                        <div class='input-group date' id='hastaMovimientos'>
                            <input type='text' id="txtHastaMovimientos" class="form-control" value="" />
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-5 col-xs-12">
                    <div class="form-group">
                        <label>Producto o clave *</label>
                        <input class="form-control" type="text" id="txtArticulo" placeholder="bobina" />
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-offset-4 col-md-4 col-sm-offset-3 col-sm-6 col-xs-offset-0 col-xs-12">
                    <div class="form-group">
                        <label style="color: transparent !important;">*</label>
                        <a id="btnFiltrarMovimientos" class="btn btn-info btn-block f-s-13 f-w-600">Filtrar Movimientos</a>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div id="errorMovimientosAlmacenes">

                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <table id="data-table-movimientos-sae" class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%">
                    <thead>
                        <tr>
                            <th class="all">Número Movimiento</th>
                            <th class="all">Folio</th>
                            <th class="all">Clave Artículo</th>
                            <th class="all">Articulo</th>
                            <th class="all">Alamacén</th>
                            <th class="all">Concepto</th>
                            <th class="all">Movimiento</th>
                            <th class="all">Referencia</th>
                            <th class="all">Cantidad</th>
                            <th class="all">Serie</th>
                            <th class="all">Costo</th>
                            <th class="all">Costo Promo Inicial</th>
                            <th class="all">Costo Promo Final</th>
                            <th class="all">Unidad Venta</th>
                            <th class="all">Existencia</th>
                            <th class="all">Fecha</th>
                            <th class="all">Movimiento Enlazado</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
            <!--Finalizando cuerpo del panel-->
        </div>
    </div>