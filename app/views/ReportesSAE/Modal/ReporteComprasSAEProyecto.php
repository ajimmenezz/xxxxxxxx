<!--Empezando Pestañas para definir la seccion-->
<div class="panel-heading p-0">
    <div class="panel-heading-btn m-r-10 m-t-10">
        <!-- Single button -->
        <div class="btn-group">
            <button type="button" class="btn btn-warning btn-xs dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Acciones <span class="caret"></span>
            </button>
            <ul class="dropdown-menu">
                <li id="btnGeneraExcelReportesComprasSAE"><a href="#"><i class="fa fa-file-pdf-o"></i> Generar Excel</a></li>
            </ul>
        </div>
        <label id="btnRegresar" class="btn btn-success btn-xs">
            <i class="fa fa fa-reply"></i> Regresar
        </label>
        <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-expand"><i class="fa fa-expand"></i></a>
    </div>
    <!-- begin nav-tabs -->
    <div class="tab-overflow">
        <ul class="nav nav-tabs nav-tabs-inverse">
            <li class="prev-button"><a href="javascript:;" data-click="prev-tab" class="text-success"><i class="fa fa-arrow-left"></i></a></li>
            <li class="active"><a href="#Compras" data-toggle="tab">Compras</a></li>
            <li class="next-button"><a href="javascript:;" data-click="next-tab" class="text-success"><i class="fa fa-arrow-right"></i></a></li>
        </ul>
    </div>
</div>
<!--Finalizando Pestañas para definir la seccion-->

<div class="tab-content">

    <!--Empezando la seccion reporte compras-->
    <div class="tab-pane fade active in" id="Compras">
        <div class="panel-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <div class="col-md-12">
                            <h3 class="m-t-10">Reporte de Compras</h3>
                        </div>
                        <div class="col-md-12">
                            <div class="underline m-b-15 m-t-15"></div>
                        </div>
                        <!--Finalizando Separador-->
                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <table id="data-table-compras-sae" class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%">
                    <thead>
                        <tr>
                            <th class="all">OC</th>
                            <th class="all">Proveedor</th>
                            <th class="all">Referencia</th>
                            <th class="all">Fecha Documento</th>
                            <th class="all">Fecha Cancelación</th>
                            <th class="all">Fecha Elaboración</th>
                            <th class="all">Total Compra</th>
                            <th class="all">Impuesto</th>
                            <th class="all">Descuento</th>
                            <th class="all">Importe</th>
                            <th class="all">Proyecto</th>
                            <th class="all">Campo Libre</th>
                            <th class="all"># Partida</th>
                            <th class="all">Clave Articulo</th>
                            <th class="all">Articulo</th>
                            <th class="all">Cantidad</th>
                            <th class="all">Precio Unitario</th>
                            <th class="all">Moneda</th>
                            <th class="all">Tipo Cambio</th>
                            <th class="all">Total Partida</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!--Finalizando-->
</div>
