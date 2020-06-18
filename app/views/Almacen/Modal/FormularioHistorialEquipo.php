<div class="row">
    <div class="col-md-9 col-sm-6 col-xs-12">
        <h1 class="page-header">Historial de Equipo</h1>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12 text-right">
        <label id="btnRegresar" class="btn btn-success">
            <i class="fa fa fa-reply"></i> Regresar
        </label>  
    </div>
</div>    
<div id="panelHistorialEquipo" class="panel panel-inverse">        
    <div class="panel-heading">
        <div class="panel-heading-btn">
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>                            
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>                            
        </div>
        <h4 class="panel-title">Historial de Equipo</h4>        
    </div>
    <div class="panel-body">        
        <div class="row"> 
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="form-grup">
                    <h4 class="m-t-10">Historial de Equipo</h4>
                </div>
            </div>                                    
        </div>
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12 underline"></div>
        </div>            
        <div class="row m-t-20">
            <div class="col-md-3 col-sm-4 col-xs-12 form-group">
                <label class="f-s-14 f-w-600">Serie de Equipo: *</label>
                <div class="input-group">     
                    <div class="input-group-btn">                                       
                        <input type="text" id="txtSerie" class="form-control" />
                        <button type="button" id="btnBuscarHistorialEquipo" class="btn btn-success f-s-13 f-w-600"><i class="fa fa-history"></i> General Historial</button>
                    </div>
                </div>  
                <div class="divError"></div>
            </div>
        </div>     
        <div id="divResult" style="display: none">
            <div class="row">
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <h4 class="m-t-10">Historial de Equipo</h4>                        
                    <div class="underline m-b-15"></div>                        
                </div>
            </div>            
            <div class="row m-t-20">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="table-responsive">
                        <table id="data-table-movimientos" class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%">
                            <thead>
                                <tr>
                                    <th class="all">Movimiento</th>
                                    <th class="all">Almacén</th>
                                    <th class="all">Tipo Producto</th>
                                    <th class="all">Producto</th>                                    
                                    <th class="all">Serie</th>
                                    <th class="all">Estatus</th>
                                    <th class="all">Usuario</th>
                                    <th class="all">Fecha</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>           
        </div>
    </div>        
</div>