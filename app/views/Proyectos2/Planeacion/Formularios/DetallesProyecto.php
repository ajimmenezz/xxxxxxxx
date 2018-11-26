<div class="row">
    <div class="col-md-6 col-sm-6 col-xs-12">
        <h1 class="page-header">Planeación del Proyecto</h1>
    </div>
    <div class="col-md-6 col-xs-6 text-right">
        <div class="btn-group">
            <button type="button" class="btn btn-warning dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Acciones <span class="caret"></span>
            </button>
            <ul class="dropdown-menu">                
                <li id="btnDocumentoInicial"><a href="#"><i class="fa fa-file"></i> Imprimir Inicio de Proyecto</a></li>
                <li id="btnSolicitudMaterial"><a href="#"><i class="fa fa-file"></i> Imprimir Solicitud de Material</a></li>
                <li id="btnSolicitudMaterialFaltante"><a href="#"><i class="fa fa-file"></i> Imprimir Material Faltante</a></li>
                <li id="btnMaterialNodosPdf"><a href="#"><i class="fa fa-file"></i> Imprimir Material y Nodos</a></li>
                <li id="btnNodosPdf"><a href="#"><i class="fa fa-file"></i> Imprimir Nodos del Proyecto</a></li>
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
                <?php
                if (isset($generales['IdSistema']) && $generales['IdSistema'] == 1) {
                    ?>
                    <li class=""><a href="#Alcance" data-toggle="tab">Alcance del Proyecto</a></li>
                    <?php
                }
                ?>
                <li class=""><a href="#Tecnicos" data-toggle="tab">Técnicos</a></li>
                <li class=""><a href="#Tareas" data-toggle="tab">Tareas</a></li>
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
                <form id="formGeneralesProyecto" data-parsley-validate="true">
                    <div class="row">
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label class="f-w-600 f-s-13">Nombre*:</label>
                                <input type="text" id="txtNombre" class="form-control" value="<?php echo $generales['Nombre']; ?>" placeholder="Nombre del proyecto" data-parsley-required="true" />
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label class="f-w-600 f-s-13">Cliente*:</label>
                                <select id="listClientes" class="form-control" style="width: 100%" data-parsley-required="true">
                                    <option value="">Selecciona . . .</option>
                                    <?php
                                    if (isset($clientes) && !empty($clientes)) {
                                        foreach ($clientes as $key => $value) {
                                            $selected = ($value['Id'] == $generales['IdCliente']) ? 'selected' : '';
                                            echo '<option value="' . $value['Id'] . '" ' . $selected . '>' . $value['Nombre'] . '</option>';
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <label class="f-w-600 f-s-13">Sucursal(es)*:</label>
                                    <select id="listSucursales" class="form-control" style="width: 100%" data-parsley-required="true">
                                        <option value="">Selecciona . . .</option>
                                        <?php
                                        if (isset($sucursales) && !empty($sucursales)) {
                                            foreach ($sucursales as $key => $value) {
                                                $selected = ($value['Id'] == $generales['IdSucursal']) ? 'selected' : '';
                                                echo '<option value="' . $value['Id'] . '" ' . $selected . '>' . $value['Nombre'] . '</option>';
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label class="f-w-600 f-s-13">Sistema*:</label>
                                <select id="listSistemas" class="form-control" style="width: 100%" data-parsley-required="true">
                                    <option value="">Selecciona . . .</option>
                                    <?php
                                    if (isset($sistemas) && !empty($sistemas)) {
                                        foreach ($sistemas as $key => $value) {
                                            $selected = ($value['Id'] == $generales['IdSistema']) ? 'selected' : '';
                                            echo '<option value="' . $value['Id'] . '" ' . $selected . '>' . $value['Nombre'] . '</option>';
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label class="f-w-600 f-s-13">Tipo de Proyecto*:</label>
                                <select id="listTipoProyecto" class="form-control" style="width: 100% !important;" data-parsley-required="true">
                                    <option value="">Selecciona . . .</option>
                                    <?php
                                    if (isset($tipos) && !empty($tipos)) {
                                        foreach ($tipos as $key => $value) {
                                            $selected = ($value['Id'] == $generales['IdTipo']) ? 'selected' : '';
                                            echo '<option value="' . $value['Id'] . '" ' . $selected . '>' . $value['Nombre'] . '</option>';
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <label class="f-w-600 f-s-13">Líder(es):</label>
                                <select id="listLideres" class="form-control" style="width: 100% !important;" multiple="">                        
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
                                <label class="f-w-600 f-s-13">Observaciones:</label>
                                <textarea class="form-control" rows="5" id="txtObservaciones" value=""><?php echo $generales['Observaciones']; ?></textarea>
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
                                    <input id="fini" type="text" class="form-control" value="<?php echo $fini; ?>">
                                    <div class="input-group-addon">hasta</div>
                                    <input id="ffin" type="text" class="form-control" value="<?php echo $ffin; ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="row m-t-15">
                    <div class="col-md-12 col-sm-12 col-xs-12 text-center">
                        <a id="btnGuardarCambios" class="btn btn-info"><i class="fa fa-save"></i> Guardar Cambios</a>
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

        <?php if (isset($generales['IdSistema']) && $generales['IdSistema'] == 1) { ?>
            <!--Empezando la seccion Alcance-->
            <div class="tab-pane fade" id="Alcance">
                <div class="panel-body">                                        
                    <div class="row">
                        <div class="col-md-6 col-sm-9 col-xs-12">
                            <h4>Ubicaciones del Proyecto</h4>                                                
                        </div>
                        <div class="col-md-6 col-sm-3 col-xs-12 text-right">
                            <a id="btnAddUbicacion" class="btn btn-success">
                                <i class="fa fa fa-plus"></i> Nueva Ubicación
                            </a>
                        </div>
                    </div>
                    <div class="row">
                        <div class="underline m-b-10"></div>
                    </div>
                    <div class="row m-t-10">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="table-responsive">
                                <table id="table-ubicaciones" class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer">
                                    <thead>
                                        <tr>
                                            <th class="never">Id</th>
                                            <th class="all">Concepto</th>
                                            <th class="all">Área</th>
                                            <th class="all">Ubicación</th>
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
            <!--Terminando la seccion Alcance-->
        <?php } ?>

        <!--Empezando la seccion Tecnicos-->
        <div class="tab-pane fade" id="Tecnicos">
            <div class="panel-body">   
                <div class="row">
                    <div class="col-md-6 col-md-offset-6 col-sm-offset-6 col-sm-6 col-xs-offset-0 col-xs-12">
                        <div class="input-group">
                            <select id="listTecnicos" class="form-control" style="width: 100%">
                                <option value="">Selecciona . . .</option>
                            </select>
                            <span role="button" id="btnAddAsistente" class="input-group-addon bg-aqua"><i class="fa fa-plus text-white"></i></span>
                        </div>
                    </div>
                </div>
                <div class="row">                       
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div id="errorAgregarAsistente"></div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <h4>Técnicos asignados al proyecto</h4>                                                
                        <div class="underline m-b-10"></div>
                    </div>
                </div>
                <div class="row m-t-10">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="table-responsive">
                            <table id="table-tecnicos" class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer">
                                <thead>
                                    <tr>
                                        <th class="never">Id</th>
                                        <th class="never">IdUsuario</th>
                                        <th class="all">Usuario</th>                                        
                                        <th class="all">Perfil</th>                                        
                                        <th class="all">NSS</th>                                        
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
        <!--Empezando la seccion Tecnicos-->

        <!--Empezando la seccion Tareas-->
        <div class="tab-pane fade" id="Tareas">
            <div class="panel-body">                                        
                <div id="divListaTareas">
                    <div class="row">
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <h4 class="pull-left">Lista de Tareas</h4>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <a id="btnNuevaTarea" class="btn btn-sm btn-success pull-right m-l-10"><i class="fa fa-plus"> </i> Nueva Tarea</a>
                            <!--<a id="btnVerDiagrama" class="btn btn-sm btn-info pull-right m-l-10"><i class="fa fa-bars"> </i> Diagrama de Gantt</a>-->
                        </div>
                    </div>                
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="underline m-b-10"></div>
                        </div>
                    </div>                    
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="table-responsive">
                                <table id="table-tareas" class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer">
                                    <thead>
                                        <tr>
                                            <th class="never">Id</th>
                                            <th class="never">IdPredecesora</th>
                                            <th class="all">Tarea</th>
                                            <th class="all">Predecesora</th>
                                            <th class="all">Comienzo</th>
                                            <th class="all">Fin</th>
                                            <th class="all">Líder</th>
                                            <th class="all">Técnicos</th>                                            
                                            <th class="all">Nodos</th>                                            
                                            <th class="all">RM</th>                                            
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>                                        
                </div>
                <div id="divDiagramaGantt m-t-20">
                    <div class="row">
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <h4 class="pull-left">Diagrama de Gantt</h4>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12">                            
                            <!--<a id="btnVerListaTareas" class="btn btn-sm btn-info pull-right m-l-10"><i class="fa fa-bars"> </i> Lista de Tareas</a>-->
                        </div>
                    </div>                
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="underline m-b-10"></div>
                        </div>
                    </div>     
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="table-responsive">
                                <div id="chart_div"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>        
        <!--Empezando la seccion Tareas-->

    </div>    
</div>

