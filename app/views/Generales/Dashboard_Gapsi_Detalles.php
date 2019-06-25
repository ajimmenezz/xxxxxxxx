<!--Empieza Detalles de COncepto-->
<div id="dashboardDetallesConcepto">
    <div id="content" class="content">
        <!--Empieza encabezado detalles-->
        <div class="row">
            <div class="col-md-9 col-sm-6 col-xs-9">
                <h1 class="page-header">Dashboard Gapsi</h1>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-3 text-right">
                <label id="btnReturnDashboardGapsi" class="btn btn-warning" onclick="location.reload()">
                    <i class="fa fa-2x fa-home"></i>
                </label>  
            </div>
        </div>
        <!--Finaliza encabezado detalles-->
        <div id="panelDashboardGapsiFilters" class="panel panel-inverse">

            <!--Empieza titulo pagina-->
            <div class="panel-heading">                
                <h4 class="panel-title">Gastos</h4>
            </div>
            <!--Finaliza titulo pagina-->

            <!--Empieza Panel -->
            <div class="panel-body">
                <!--Empieza contenido Detalles-->                        
                <div id="proyecto" class="row"> 

                    <!--Empieza titulo y botones archivos-->
                    <div class="col-md-10">
                        <div class="form-group">
                            <h4 class="m-t-12">Detalles</h4>
                            <div class="underline m-b-15 m-t-15"></div>
                        </div> 
                    </div>
                    <div class="col-md-1 col-sm-1 col-xs-1">
                        <label id="descargaExcel" class="btn btn-white">
                            <i class="fa fa-2x fa-file-excel-o text-success"></i>
                        </label>  
                    </div>
                    <div class="col-md-1 col-sm-1 col-xs-1">
                        <label id="descargaExcel" class="btn btn-white">
                            <i class="fa fa-2x fa-file-pdf-o text-danger"></i>
                        </label>  
                    </div>
                    <!--Finaliza titulo y botones archivos-->

                    <!--Empieza tabla detalles-->                        
                    <div class="col-md-12 col-sm-12">
                        <div class="table-responsive">
                            <table id="data-table-detalles" class="table table-hover table-striped table-bordered no-wrap " style="cursor:pointer" width="100%">
                                <thead>
                                    <tr>
                                        <th class="all">Clave Gasto</th>
                                        <th class="all">Proyecto</th>
                                        <th class="all">Tipo Proyecto</th>
                                        <th class="all">Servicio</th>
                                        <th class="all">Beneficiario</th>
                                        <th class="all">Importe</th>
                                        <th class="all">Moneda</th>
                                        <th class="all">Fecha</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!--Finaliza tabla detalles-->

                </div>
                <!--Finaliza contenido Detalles-->   
            </div>
            <!--Finaliza Panel -->
        </div>
    </div>
</div>
<!--Finaliza Detalles de COncepto-->