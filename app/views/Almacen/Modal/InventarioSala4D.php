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
                    <h4 class="m-t-10">Inventario de Sala 4D (Elementos)</h4>
                </div>
            </div>       
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="underline m-b-15 m-t-15"></div>
            </div>
        </div>        
        <div class="row m-t-20">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="table-responsive">
                    <table id="data-table-inventario-sala4d-elementos" class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%">
                        <thead>
                            <tr>
                                <th class="all">Elemento</th>
                                <th class="all">Serie</th>
                                <th class="all">Clave Cinemex</th>
                                <th class="all">Ubicación</th>
                                <th class="all">Sistema</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (isset($elementos) && !empty($elementos))
                                foreach ($elementos as $key => $value) {
                                    echo '<tr>'
                                    . '<td>' . $value['Elemento'] . '</td>'
                                    . '<td>' . $value['Serie'] . '</td>'
                                    . '<td>' . $value['ClaveCinemex'] . '</td>'
                                    . '<td>' . $value['Ubicacion'] . '</td>'
                                    . '<td>' . $value['Sistema'] . '</td>'
                                    . '</tr>';
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>     
        <br /><br /><br />
        <div class="row"> 
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="form-grup">
                    <h4 class="m-t-10">Inventario de Sala 4D (Sub-elementos)</h4>
                </div>
            </div>       
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="underline m-b-15 m-t-15"></div>
            </div>
        </div>                
        <div class="row m-t-20">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="table-responsive">
                    <table id="data-table-inventario-sala4d-subelementos" class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%">
                        <thead>
                            <tr>
                                <th class="all">Sub-elemento</th>
                                <th class="all">Elemento</th>
                                <th class="all">Serie</th>
                                <th class="all">Clave Cinemex</th>
                                <th class="all">Ubicación</th>
                                <th class="all">Sistema</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (isset($subelementos) && !empty($subelementos))
                                foreach ($subelementos as $key => $value) {
                                    echo '<tr>'
                                    . '<td>' . $value['Subelemento'] . '</td>'
                                    . '<td>' . $value['Elemento'] . '</td>'
                                    . '<td>' . $value['Serie'] . '</td>'
                                    . '<td>' . $value['ClaveCinemex'] . '</td>'
                                    . '<td>' . $value['Ubicacion'] . '</td>'
                                    . '<td>' . $value['Sistema'] . '</td>'
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