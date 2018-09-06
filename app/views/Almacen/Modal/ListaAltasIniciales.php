<div class="row">
    <div class="col-md-9 col-sm-6 col-xs-12">
        <h1 class="page-header">Altas Iniciales en almacenes</h1>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12 text-right">
        <label id="btnRegresar" class="btn btn-success">
            <i class="fa fa fa-reply"></i> Regresar
        </label>  
    </div>
</div>    
<div id="panelAltasIniciales" class="panel panel-inverse">        
    <div class="panel-heading">
        <div class="panel-heading-btn">
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>                            
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>                            
        </div>
        <h4 class="panel-title">Altas iniciales en Almacenes</h4>        
    </div>
    <div class="panel-body">        
        <div class="row"> 
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="form-grup">
                    <h4 class="m-t-10">Lista de Altas iniciales</h4>
                </div>
            </div>                                    
        </div>
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12 underline"></div>
        </div>            
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="table-responsive">
                    <table id="data-table-altas-iniciales" class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%">
                        <thead>
                            <tr>
                                <th class="never">Id</th>
                                <th class="all">No. Alta</th>                                    
                                <th class="all">Almacen</th>
                                <th class="all">Usuario</th>
                                <th class="all">Fecha Inicio</th>
                                <th class="all">Fecha Termino</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (isset($altas)) {
                                foreach ($altas as $key => $value) {
                                    echo '<tr>';
                                    echo '<td>' . $value['Id'] . '</td>';
                                    echo '<td>' . sprintf("%'.011d\n", $value['Id']) . '</td>';
                                    echo '<td>' . $value['Almacen'] . '</td>';                                    
                                    echo '<td>' . $value['Usuario'] . '</td>';
                                    echo '<td>' . $value['FechaInicio'] . '</td>';
                                    echo '<td>' . $value['FechaTermino'] . '</td>';
                                    echo '</tr>';
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