<div class="row">
    <div class="col-md-9 col-sm-6 col-xs-12">
        <h1 class="page-header">Inventario del Almacén</h1>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12 text-right">
        <label id="btnRegresar" class="btn btn-success">
            <i class="fa fa fa-reply"></i> Regresar
        </label>  
    </div>
</div>    
<div id="panelInventarioAlmacen" class="panel panel-inverse">        
    <div class="panel-heading">
        <div class="panel-heading-btn">
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>                            
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>                            
        </div>
        <h4 class="panel-title"><?php echo $datos[1]; ?></h4>        
    </div>
    <div class="panel-body">
        <div class="row"> 
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="form-grup">
                    <h4 class="m-t-10">Censo de Sucursal de Póliza (TI)</h4>
                </div>
            </div> 
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="underline m-b-15 m-t-15"></div>
            </div>
        </div>
        <div class="row m-t-20">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="table-responsive">
                    <table id="data-table-censo-poliza" class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%">
                        <thead>
                            <tr>
                                <th class="all">Área de Atención</th>
                                <th class="all">Punto</th>
                                <th class="all">Equipo</th>
                                <th class="all">Serie</th>
                                <th class="all">Terminal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (isset($censo) && !empty($censo))
                                foreach ($censo as $key => $value) {
                                    echo '<tr>'
                                    . '<td>' . $value['Area'] . '</td>'
                                    . '<td>' . $value['Punto'] . '</td>'
                                    . '<td>' . $value['Equipo'] . '</td>'
                                    . '<td>' . $value['Serie'] . '</td>'
                                    . '<td>' . $value['Terminal'] . '</td>'
                                    . '</tr>';
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>     
    </div>        
</div>