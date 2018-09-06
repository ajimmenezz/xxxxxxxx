<div class="row">
    <div class="col-md-9 col-sm-6 col-xs-12">
        <h1 class="page-header">Detalles de Tarea</h1>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12 text-right">
        <label id="btnRegresar" class="btn btn-success">
            <i class="fa fa fa-reply"></i> Regresar
        </label>  
    </div>
</div>    
<div id="panelFormEditarTarea" class="panel panel-inverse">        
    <div class="panel-heading">  
        <h4 class="panel-title">Detalles de la Tarea</h4>        
    </div>
    <div class="panel-body">   
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div id="divErrorTarea"></div>
            </div>
        </div>
        <ul class="nav nav-pills">
            <li class="active"><a href="#generalesTarea" data-toggle="tab" aria-expanded="true">Generales</a></li>
            <?php
            if (isset($generales['IdSistema']) && $generales['IdSistema'] == 1) {
                ?>
                <li class=""><a href="#nodosTarea" data-toggle="tab" aria-expanded="false">Nodos del Proyecto</a></li>
                <?php
            }
            ?>
            <li class=""><a href="#materialTarea" data-toggle="tab" aria-expanded="false">Material</a></li>                    
        </ul>
        <div class="tab-content">
            <div class="tab-pane fade active in" id="generalesTarea">
                <div class="row">
                    <div class="col-md-6 col-sm-8 col-xs-12">
                        <h4>Datos Generales de la Tarea</h4>                                                
                    </div>
                    <div class="col-md-6 col-sm-4 col-xs-12">
                        <a id="btnEliminarTarea" data-id="<?php echo $tarea[0]['Id']; ?>" class="btn btn-sm btn-danger pull-right f-w-600 f-s-13"><i class="fa fa-trash-o"> </i> Eliminar Tarea</a>
                    </div>
                </div>
                <div class="row">
                    <div class="underline m-b-10"></div>
                </div>
                <form id="formNuevaTarea" data-parsley-validate="true">
                    <div class="row m-t-10">
                        <div class="col-md-6 col-sm-6 col-xs-12">                        
                            <div class="form-group">
                                <label class="f-w-600 f-s-13">Tarea*:</label>
                                <input type="hidden" id="IdTarea" value="<?php echo $tarea[0]['Id']; ?>" />
                                <input type="text" class="form-control" value="<?php echo $tarea[0]['Nombre']; ?>" id="txtNombreTarea" data-parsley-required="true" />
                            </div>                         
                        </div>                                                   
                        <div class="col-md-6 col-sm-6 col-xs-12">                        
                            <label class="f-w-600 f-s-13">Predecesora:</label>
                            <select id="listPredecesora" class="form-control" style="width: 100%">
                                <option value="">Selecciona . . .</option>
                                <?php
                                if (isset($predecesoras) && !empty($predecesoras)) {
                                    foreach ($predecesoras as $key => $value) {
                                        $selected = ($value['Id'] == $tarea[0]['IdPredecesora']) ? 'selected' : '';
                                        echo '<option data-fin="' . $value['FinG'] . '" value="' . $value['Id'] . '" ' . $selected . '>' . $value['Nombre'] . ' Fin: ' . $value['Fin'] . '</option>';
                                    }
                                }
                                ?>
                            </select>                        
                        </div>
                    </div>
                </form> 
                <div class="row m-t-10">
                    <div class="col-md-6 col-sm-6 col-xs-12">                        
                        <div class="form-group">            
                            <label class="f-w-600 f-s-13">Fechas:</label>
                            <input type="hidden" id="hiddenIni" value="<?php echo $generales['IniG']; ?>" />
                            <input type="hidden" id="hiddenFin" value="<?php echo $generales['FinG']; ?>" />
                            <input type="hidden" id="hiddenIniFFFFF" value="<?php echo $tarea[0]['InicioG']; ?>" />
                            <div id="rangoFechasTarea" class="input-group input-daterange">                        
                                <input id="finitarea" type="text" class="form-control" value="<?php echo $tarea[0]['InicioG']; ?>">
                                <div class="input-group-addon">hasta</div>
                                <input id="ffintarea" type="text" class="form-control" value="<?php echo $tarea[0]['FinG']; ?>">
                            </div>
                        </div>                      
                    </div>                                                   
                    <div class="col-md-6 col-sm-6 col-xs-12">                        
                        <div class="form-group">
                            <label class="f-w-600 f-s-13">Líder:</label>
                            <select id="listLiderTarea" class="form-control" style="width: 100%">
                                <option value="">Selecciona . . .</option>
                                <?php
                                if (isset($lideres) && !empty($lideres)) {
                                    $lideresP = explode(",", $lideresProyecto['Lideres']);
                                    foreach ($lideres as $key => $value) {
                                        if (in_array($value['Id'], $lideresP)) {
                                            $selected = ($value['Id'] == $tarea[0]['IdLider']) ? 'selected' : '';
                                            echo '<option value="' . $value['Id'] . '" ' . $selected . '>' . $value['Nombre'] . '</option>';
                                        }
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
                            <label class="f-w-600 f-s-13">Técnicos:</label>
                            <select id="listTecnicosTarea" class="form-control" style="width: 100%" multiple="">   
                                <?php
                                if (isset($tecnicos) && !empty($tecnicos)) {
                                    $tecnicosTarea = explode(",", $tarea[0]['IdTecnicos']);
                                    foreach ($tecnicos as $key => $value) {
                                        $selected = (in_array($value['IdUsuario'], $tecnicosTarea)) ? 'selected' : '';
                                        echo '<option value="' . $value['IdUsuario'] . '" ' . $selected . '>' . $value['Nombre'] . '</option>';
                                    }
                                }
                                ?>
                            </select>            
                        </div> 
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12 text-center">
                        <a id="btnGuardarTarea" class="btn btn-success"><i class="fa fa-save"> </i> Guardar Cambios</a>
                    </div>
                </div>
            </div>
            <?php if (isset($generales['IdSistema']) && $generales['IdSistema'] == 1) { ?>
                <div class="tab-pane fade" id="nodosTarea">
                    <div class="row">
                        <div class="col-md-6 col-sm-8 col-xs-12">
                            <h4>Asignar nodos a la Tarea</h4>                                                
                        </div>
                        <div class="col-md-6 col-sm-8 col-xs-12">
                            <a class="btn btn-success pull-right btnGuardarNodosTarea"><i class="fa fa-save"> </i>Guardar Nodos</a>
                        </div>                    
                    </div>
                    <div class="row">
                        <div class="underline m-b-10"></div>
                    </div>
                    <div class="row m-t-10">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="table-responsive">
                                <table id="table-nodos-tarea" class="table table-striped table-bordered no-wrap" style="cursor:pointer">
                                    <thead>
                                        <tr>                                                
                                            <th class="never">Id</th>
                                            <th class="all">Tipo de Nodo</th>
                                            <th class="all">Nodo</th>
                                            <th class="all">Concepto</th>
                                            <th class="all">Área</th>
                                            <th class="all">Ubicación</th>
                                            <th class="all">Material</th>
                                            <th class="all"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="row m-t-10">
                        <div class="col-md-12 col-sm-12 col-xs-12 text-center">
                            <a class="btn btn-success btnGuardarNodosTarea"><i class="fa fa-save"> </i>Guardar Nodos</a>
                        </div>
                    </div>
                </div>
            <?php } ?>
            <div class="tab-pane fade" id="materialTarea">                               
            </div>
        </div>
    </div>        
</div>

