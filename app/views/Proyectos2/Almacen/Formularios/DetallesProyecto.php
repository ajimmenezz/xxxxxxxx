<div class="row">
    <div class="col-md-6 col-sm-6 col-xs-12">
        <h1 class="page-header">Material Para el Proyecto</h1>
    </div>
    <div class="col-md-6 col-xs-6 text-right">
        <div class="btn-group">
            <button type="button" class="btn btn-warning dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Acciones <span class="caret"></span>
            </button>
            <ul class="dropdown-menu">                                
                <li id="btnSolicitudMaterial"><a href="#"><i class="fa fa-file"></i> Imprimir Solicitud de Material</a></li>
                <li id="btnSolicitudMaterialFaltante"><a href="#"><i class="fa fa-file"></i> Imprimir Material Faltante</a></li>
        </div>
        <label id="btnRegresar" class="btn btn-success">
            <i class="fa fa fa-reply"></i> Regresar
        </label> 
    </div>
</div>    
<div id="panelFormDetallesProyecto" class="panel panel-inverse panel-with-tabs">        
    <div class="panel-heading p-0">
        <div class="btn-group pull-right" data-toggle="buttons">

        </div>
        <div class="panel-heading-btn m-r-10 m-t-10">                                                 
        </div>
        <div class="tab-overflow">
            <ul class="nav nav-tabs nav-tabs-inverse">
                <li class="prev-button"><a href="javascript:;" data-click="prev-tab" class="text-success"><i class="fa fa-arrow-left"></i></a></li>
                <li class="active"><a href="#Generales" data-toggle="tab">Generales</a></li>
                <li class=""><a href="#Material" data-toggle="tab">Material del Proyecto</a></li>                                
                <li class="next-button"><a href="javascript:;" data-click="next-tab" class="text-success"><i class="fa fa-arrow-right"></i></a></li>
            </ul>
        </div>
    </div>
    <!--Empezando error--> 
    <div class="row m-t-10">                       
        <div class="col-md-offset-4 col-md-4 col-sm-offset-3 col-sm-6 col-xs-offset-1 col-xs-10">
            <div id="errorMessage"></div>
        </div>
    </div>
    <!--Finalizando Error-->

    <div class="tab-content">

        <!--Empezando la seccion Generales-->
        <div class="tab-pane fade active in" id="Generales">
            <div class="panel-body">  
                <?php
//                echo "<pre>";
//                var_dump($generales);
//                echo "</pre>";
                ?>

                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <h4>Generales del Proyecto</h4>
                        <input type="hidden" id="IdProyecto" value="<?php echo $generales['Id']; ?>" />
                        <input type="hidden" id="IdAlmacenSAE" value="<?php echo $generales['cve_almacen']; ?>" />
                        <div class="underline m-b-10"></div>
                    </div>
                </div>                
                <div class="row">
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <div class="form-group">
                            <label class="f-w-600 f-s-13">Nombre:</label>
                            <pre class="form-control f-s-14"><?php echo $generales['Nombre']; ?></pre>                                
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <div class="form-group">
                            <label class="f-w-600 f-s-13">Almacen SAE*:</label>
                            <select id="listAlmacenesSAE" class="form-control" style="width: 100% !important;">
                                <option value="">Seleccionar . . .</option>
                                <?php
                                if (isset($almacenes) && !empty($almacenes)) {
                                    foreach ($almacenes as $key => $value) {
                                        $selected = ($value['CVE_ALM'] == $generales['cve_almacen']) ? 'selected' : '';
                                        echo '<option value="' . $value['CVE_ALM'] . '" ' . $selected . '>' . $value['DESCR'] . '</option>';
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <div class="form-group">
                            <label class="f-w-600 f-s-13">Cliente:</label>
                            <?php
                            $cliente = '';
                            if (isset($clientes) && !empty($clientes)) {
                                foreach ($clientes as $key => $value) {
                                    if ($value['Id'] == $generales['IdCliente']) {
                                        $cliente = $value['Nombre'];
                                    }
                                }
                            }
                            ?>
                            <pre class="form-control f-s-14"><?php echo $cliente; ?></pre>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label class="f-w-600 f-s-13">Sucursal:</label>

                                <?php
                                $sucursal = '';
                                if (isset($sucursales) && !empty($sucursales)) {
                                    foreach ($sucursales as $key => $value) {
                                        if ($value['Id'] == $generales['IdSucursal']) {
                                            $sucursal = $value['Nombre'];
                                        }
                                    }
                                }
                                ?>   
                                <pre class="form-control f-s-14"><?php echo $sucursal; ?></pre>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <div class="form-group">
                            <label class="f-w-600 f-s-13">Sistema:</label>
                            <?php
                            $sistema = '';
                            if (isset($sistemas) && !empty($sistemas)) {
                                foreach ($sistemas as $key => $value) {
                                    if ($value['Id'] == $generales['IdSistema']) {
                                        $sistema = $value['Nombre'];
                                    }
                                }
                            }
                            ?>
                            <pre class="form-control f-s-14"><?php echo $sistema; ?></pre>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <div class="form-group">
                            <label class="f-w-600 f-s-13">Tipo de Proyecto:</label>
                            <?php
                            $tipo = '';
                            if (isset($tipos) && !empty($tipos)) {
                                foreach ($tipos as $key => $value) {
                                    if ($value['Id'] == $generales['IdTipo']) {
                                        $tipo = $value['Nombre'];
                                    }
                                }
                            }
                            ?>   
                            <pre class="form-control f-s-14"><?php echo $tipo; ?></pre>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label class="f-w-600 f-s-13">Líder(es):</label>
                            <select id="listLideres" class="form-control" style="width: 100% !important;" multiple="" disabled="">                        
                                <?php
                                if (isset($lideres) && !empty($lideres)) {
                                    foreach ($lideres as $key => $value) {
                                        $selected = (in_array($value['Id'], $generales['Lideres'])) ? 'selected' : '';
                                        echo '<option value="' . $value['Id'] . '" ' . $selected . '>' . $value['Nombre'] . '</option>';
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-9 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <?php
                            $fini = (isset($generales['FechaInicio']) && $generales['FechaInicio'] !== '') ? date('d-m-Y', strtotime($generales['FechaInicio'])) : '';
                            $ffin = (isset($generales['FechaTermino']) && $generales['FechaTermino'] !== '') ? date('d-m-Y', strtotime($generales['FechaTermino'])) : '';
                            ?>
                            <label class="f-w-600 f-s-13">Fechas:</label>
                            <div id="rangoFechas" class="input-group input-daterange">                        
                                <input id="fini" type="text" class="form-control" value="<?php echo $fini; ?>" disabled="">
                                <div class="input-group-addon">hasta</div>
                                <input id="ffin" type="text" class="form-control" value="<?php echo $ffin; ?>" disabled="">
                            </div>
                        </div>
                    </div>
                </div>     
            </div>
        </div>        
        <!--Empezando la seccion Generales-->

        <!--Empezando la seccion Material-->
        <div class="tab-pane fade" id="Material">
            <div class="panel-body">                                        
                <ul class="nav nav-pills">
                    <li class="active"><a href="#materialProyectado" data-toggle="tab" aria-expanded="true">Material Proyectado</a></li>
                    <li class=""><a href="#materialAsignado" data-toggle="tab" aria-expanded="false">Material Asignado</a></li>
                    <li class=""><a href="#materialDiferencias" data-toggle="tab" aria-expanded="false">Diferencias</a></li>                    
                </ul>
                <div class="tab-content">
                    <div class="tab-pane fade active in" id="materialProyectado">
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <h4>Material Proyectado</h4>                                                
                            </div>
                        </div>
                        <div class="row">
                            <div class="underline m-b-10"></div>
                        </div>
                        <div class="row m-t-10">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="table-responsive">
                                    <table id="table-material-proyectado" class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer">
                                        <thead>
                                            <tr>                                                
                                                <th class="all">Material</th>
                                                <th class="all">No. Parte</th>
                                                <th class="all">Total</th>
                                                <th class="all">Unidad</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="materialAsignado">
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <h4>Material Asignado en SAE</h4>                                                
                            </div>
                        </div>
                        <div class="row">
                            <div class="underline m-b-10"></div>
                        </div>
                        <div class="row m-t-10">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="table-responsive">
                                    <table id="table-material-sae" class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer">
                                        <thead>
                                            <tr>                                                
                                                <th class="all">Material</th>
                                                <th class="all">No. Parte</th>
                                                <th class="all">Total</th>
                                                <th class="all">Unidad</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="materialDiferencias">
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <h4>Diferencias de Material</h4>                                                
                            </div>
                        </div>
                        <div class="row">
                            <div class="underline m-b-10"></div>
                        </div>
                        <div class="row m-t-10">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="table-responsive">
                                    <table id="table-material-diferencias" class="table table-striped table-bordered no-wrap" style="cursor:pointer">
                                        <thead>
                                            <tr>                                                
                                                <th class="all">Material</th>
                                                <th class="all">No. Parte</th>
                                                <th class="all">Unidad</th>
                                                <th class="all">Total Solicitado</th>
                                                <th class="all">Total Asignado</th>
                                                <th class="all">Diferencia</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>        
        <!--Empezando la seccion Material-->

    </div>    
</div>

