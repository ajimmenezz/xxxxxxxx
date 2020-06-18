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
        <?php
        if ($inventarioInicial && !isset($alta[0])) {
            ?>
            <div class="row">
                <div class="col-md-offset-9 col-md-3 col-sm-offset-8 col-sm-4 col-xs-offset-0 col-xs-12">
                    <button id="btnNuevaAltaInicial" class="btn btn-warning f-w-600 pull-right">Nueva Alta Inicial</button>
                </div>
            </div>
            <?php
        } else if ($inventarioInicial && isset($alta[0]) && $alta[0]['IdAlmacen'] == $datos[0]) {
            ?>
            <div class="row">
                <div class="col-md-offset-9 col-md-3 col-sm-offset-8 col-sm-4 col-xs-offset-0 col-xs-12">
                    <button class="btn btn-danger f-w-600 pull-right" id="btnCerrarAltaInicial" data-id="<?php echo $alta[0]['Id']; ?>">Cerrar Alta <?php echo sprintf("%'.011d\n", $alta[0]['Id']); ?> </button>
                </div>
            </div>
            <?php
        } else if ($inventarioInicial && isset($alta[0]) && $alta[0]['IdAlmacen'] != $datos[0]) {
            ?>
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="note note-warning">                        
                        <p class="f-s-14">
                            Al parecer tiene un alta pendiente en el Almacén <strong>"<?php echo $alta[0]['Almacen']; ?>".</strong>
                            Para iniciar una nueva alta debe cerrar la anterior.
                        </p>
                    </div>
                </div>
            </div>
            <?php
        }
        ?>
        <ul class="nav nav-pills">
            <li class="active"><a href="#poliza-ti" data-toggle="tab" class="f-w-600 f-s-14">Póliza TI</a></li>
            <li><a href="#salas-4d" data-toggle="tab" class="f-w-600 f-s-14">Salas 4D</a></li>
            <li><a href="#otros" data-toggle="tab" class="f-w-600 f-s-14">Otros</a></li>            
            <li><a href="#movimientos" data-toggle="tab" class="f-w-600 f-s-14">Movimientos</a></li>            
        </ul>        
        <div class="row">
            <div class="col-md-12">
                <div class="underline m-b-15 m-t-15"></div>                                               
            </div>
            <div class="col-md-12">
                <div id="errorInventarioAlmacen"></div>
            </div>
        </div>    
        <div class="tab-content">
            <div class="tab-pane fade active in" id="poliza-ti">
                <div class="row"> 
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <div class="form-grup">
                            <h4 class="m-t-10">Inventario de Póliza (TI)</h4>
                        </div>
                    </div>                                    
                    <?php
                    if ($inventarioInicial && isset($alta[0]) && $alta[0]['IdAlmacen'] == $datos[0]) {
                        ?>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <div class="btn-group pull-right">
                                <button id="btnAddInicialPoliza" data-toggle="tooltip" data-placement="top" title="Agregar Producto" class="btn btn-success f-w-600"><i class="fa fa-plus text-white" aria-hidden="true"></i> Agregar Producto</button>                            
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                </div>
                <div class="row m-t-20">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="table-responsive">
                            <table id="data-table-poliza" class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%">
                                <thead>
                                    <tr>
                                        <th class="never">Id</th>
                                        <th class="all">Producto</th>
                                        <th class="all">Tipo</th>
                                        <th class="all">Cantidad</th>
                                        <th class="all">Serie</th>
                                        <th class="all">Estatus</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (isset($poliza) && !empty($poliza))
                                        foreach ($poliza as $key => $value) {
                                            echo '<tr>'
                                            . '<td>' . $value['Id'] . '</td>'
                                            . '<td>' . $value['Producto'] . '</td>'
                                            . '<td>' . $value['Tipo'] . '</td>'
                                            . '<td>' . $value['Cantidad'] . '</td>'
                                            . '<td>' . $value['Serie'] . '</td>'
                                            . '<td>' . $value['Estatus'] . '</td>'
                                            . '</tr>';
                                        }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="salas-4d">
                <div class="row"> 
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <div class="form-grup">
                            <h4 class="m-t-10">Inventario de Salas 4D</h4>
                        </div>
                    </div>                                    
                    <?php
                    if ($inventarioInicial && isset($alta[0]) && $alta[0]['IdAlmacen'] == $datos[0]) {
                        ?>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <div class="btn-group pull-right">
                                <button id="btnAddInicialSalas" data-toggle="tooltip" data-placement="top" title="Agregar Producto" class="btn btn-success f-w-600"><i class="fa fa-plus text-white" aria-hidden="true"></i> Agregar Producto</button>                            
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                </div>
                <div class="row m-t-20">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="table-responsive">
                            <table id="data-table-salas" class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%">
                                <thead>
                                    <tr>
                                        <th class="never">Id</th>
                                        <th class="all">Producto</th>
                                        <th class="all">Tipo</th>
                                        <th class="all">Cantidad</th>
                                        <th class="all">Serie</th>
                                        <th class="all">Estatus</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (isset($salas4D) && !empty($salas4D))
                                        foreach ($salas4D as $key => $value) {
                                            echo '<tr>'
                                            . '<td>' . $value['Id'] . '</td>'
                                            . '<td>' . $value['Producto'] . '</td>'
                                            . '<td>' . $value['Tipo'] . '</td>'
                                            . '<td>' . $value['Cantidad'] . '</td>'
                                            . '<td>' . $value['Serie'] . '</td>'
                                            . '<td>' . $value['Estatus'] . '</td>'
                                            . '</tr>';
                                        }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="otros">
                <div class="row"> 
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <div class="form-grup">
                            <h4 class="m-t-10">Inventario de Otros (SAE)</h4>
                        </div>
                    </div>                                    
                    <?php
                    if ($inventarioInicial && isset($alta[0]) && $alta[0]['IdAlmacen'] == $datos[0]) {
                        ?>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <div class="btn-group pull-right">
                                <button id="btnAddInicialOtros" data-toggle="tooltip" data-placement="top" title="Agregar Producto" class="btn btn-success f-w-600"><i class="fa fa-plus text-white" aria-hidden="true"></i> Agregar Producto</button>                            
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                </div>
                <div class="row m-t-20">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="table-responsive">
                            <table id="data-table-otros" class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%">
                                <thead>
                                    <tr>
                                        <th class="never">Id</th>
                                        <th class="all">Producto</th>
                                        <th class="all">Tipo</th>
                                        <th class="all">Cantidad</th>
                                        <th class="all">Serie</th>
                                        <th class="all">Estatus</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (isset($otros) && !empty($otros))
                                        foreach ($otros as $key => $value) {
                                            echo '<tr>'
                                            . '<td>' . $value['Id'] . '</td>'
                                            . '<td>' . $value['Producto'] . '</td>'
                                            . '<td>' . $value['Tipo'] . '</td>'
                                            . '<td>' . $value['Cantidad'] . '</td>'
                                            . '<td>' . $value['Serie'] . '</td>'
                                            . '<td>' . $value['Estatus'] . '</td>'
                                            . '</tr>';
                                        }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="movimientos">
                <div class="row"> 
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <div class="form-grup">
                            <h4 class="m-t-10">Movimientos al inventario</h4>
                        </div>
                    </div> 
                </div>
                <div class="row">
                    <div class="col-md-3 col-sm-6 col-xs-6">
                        <div class="form-grup">
                            <label class="f-w-600">Tipo de Movimiento</label>
                            <select id="listFilterTipoMovimiento" class="form-control" style="width: 100% !important;">
                                <option value="">Seleccionar . . .</option>
                                <?php
                                if (isset($tiposMovimientos) && !empty($tiposMovimientos)) {
                                    foreach ($tiposMovimientos as $key => $value) {
                                        echo '<option value="' . $value['Id'] . '">' . $value['Nombre'] . '</option>';
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-6">
                        <div class="form-grup">
                            <label class="f-w-600">Tipo de Producto</label>
                            <select id="listFilterTipoProducto" class="form-control" style="width: 100% !important;">
                                <option value="">Seleccionar . . .</option>
                                <?php
                                if (isset($tiposProductos) && !empty($tiposProductos)) {
                                    foreach ($tiposProductos as $key => $value) {
                                        echo '<option value="' . $value['Id'] . '">' . $value['Nombre'] . '</option>';
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6 col-xs-6">
                        <div class="form-group">
                            <label class="f-w-600">Rango de Fecha </label>    <br />                                                    
                            <div class="col-md-6 col-sm-6 col-xs-6 m-0 p-1">
                                <div class="form-group">
                                    <div class='input-group date' id='desde'>
                                        <input type='text' id="txtDesde" class="form-control" value="" disabled=""/>
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                    </div>
                                </div>                
                            </div>                                                        
                            <div class="col-md-6 col-sm-6 col-xs-6 m-0 p-1">
                                <div class="form-group">
                                    <div class='input-group date' id='hasta'>
                                        <input type='text' id="txtHasta" class="form-control" value="" disabled=""/>
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                    </div>
                                </div>                
                            </div>
                        </div>
                    </div>    
                    <div class="col-md-2 col-sm-6 col-xs-6">
                        <div class="form-group">
                            <label class="f-w-600">-</label>    <br />
                            <button class="btn btn-warning F-W-600" id="btnFiltrarMovimientos"> <i class="fa fa-filter" aria-hidden="true"></i> Filtrar</button>
                        </div>
                    </div>
                </div>
                <div class="row m-t-20">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="table-responsive">
                            <table id="data-table-movimientos" class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%">
                                <thead>
                                    <tr>
                                        <th class="all">Movimiento</th>
                                        <th class="all">Almacén Origen</th>
                                        <th class="all">Tipo Producto</th>
                                        <th class="all">Producto</th>
                                        <th class="all">Cantidad</th>
                                        <th class="all">Serie</th>
                                        <th class="all">Estatus</th>
                                        <th class="all">Usuario</th>
                                        <th class="all">Fecha</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (isset($movimientos) && !empty($movimientos))
                                        foreach ($movimientos as $key => $value) {
                                            echo '<tr>'
                                            . '<td>' . $value['Movimiento'] . '</td>'
                                            . '<td>' . $value['Origen'] . '</td>'
                                            . '<td>' . $value['TipoProducto'] . '</td>'
                                            . '<td>' . $value['Producto'] . '</td>'
                                            . '<td>' . $value['Cantidad'] . '</td>'
                                            . '<td>' . $value['Serie'] . '</td>'
                                            . '<td>' . $value['Estatus'] . '</td>'
                                            . '<td>' . $value['Usuario'] . '</td>'
                                            . '<td>' . $value['Fecha'] . '</td>'
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
    </div>        
</div>