<div class="row">
    <div class="col-md-6 col-sm-6 col-xs-12">
        <h1 class="page-header">Seguimiento de Tarea</h1>
    </div>
    <div class="col-md-6 col-xs-6 text-right">
        <label id="btnRegresar" class="btn btn-success">
            <i class="fa fa fa-reply"></i> Regresar
        </label> 
    </div>
</div>    
<div id="panelFormularioSeguimientoTarea" class="panel panel-inverse panel-with-tabs">        
    <div class="panel-heading p-0">
        <div class="btn-group pull-right" data-toggle="buttons">

        </div>
        <div class="panel-heading-btn m-r-10 m-t-10">                                                 
        </div>
        <div class="tab-overflow">
            <ul class="nav nav-tabs nav-tabs-inverse">
                <li class="prev-button"><a href="javascript:;" data-click="prev-tab" class="text-success"><i class="fa fa-arrow-left"></i></a></li>
                <li class="active"><a href="#Generales" data-toggle="tab">Generales</a></li>
                <li class=""><a href="#MaterialNodo" data-toggle="tab">Material por Nodo</a></li>                                
                <li class=""><a href="#ConsumoMaterial" data-toggle="tab">Consumir Material</a></li>
                <li class=""><a href="#NotasAdjuntos" data-toggle="tab">Notas y Adjuntos</a></li>
                <li class="next-button"><a href="javascript:;" data-click="next-tab" class="text-success"><i class="fa fa-arrow-right"></i></a></li>
            </ul>
        </div>
    </div>
    <!--Empezando error--> 
    <div class="row m-t-10">                       
        <div class="col-md-offset-4 col-md-4 col-sm-offset-3 col-sm-6 col-xs-offset-1 col-xs-10">
            <div id="errorMessage">
                <?php
                $estatusCampos = '';
                $classButtons = '';
                if ($predecesora && $avancePredecesora <= 99) {
                    $estatusCampos = ' disabled="disabled" ';
                    $classButtons = ' hidden ';
                    ?>
                    <div class = "alert alert-warning fade in m-b-15 f-w-500 f-s-14 text-center">
                        <strong>Warning!</strong><br />
                        Al parecer la tarea predecesora no ha llegado al 100% de avance.<br />Para poder documentar el avance y el material en esta tarea debe documentar primero la predecesora.
                    </div>
                    <?php
                }
                ?>
            </div>
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
                        <h4><?php echo $generales['Tarea']; ?></h4>
                        <input type="hidden" id="IdTarea" value="<?php echo $generales['Id']; ?>" />                        
                        <div class="underline m-b-10"></div>
                    </div>
                </div>                
                <div class="row">
                    <div class="col-md-4 col-sm-6 col-xs-12">
                        <div class="form-group">
                            <label class="f-w-600 f-s-13">Proyecto:</label>
                            <pre class="form-control f-s-14"><?php echo $generales['Proyecto']; ?></pre>                                
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6 col-xs-12">
                        <div class="form-group">
                            <label class="f-w-600 f-s-13">Sucursal:</label>
                            <pre class="form-control f-s-14"><?php echo $generales['Sucursal']; ?></pre>                                
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6 col-xs-12">
                        <div class="form-group">
                            <label class="f-w-600 f-s-13">Avance:</label>
                            <div class="input-group">
                                <input type="number" min="0" max="100" id="txtAvanceProyecto" class="form-control" value="<?php echo $generales['Avance']; ?>" <?php echo $estatusCampos; ?> />
                                <span class="input-group-addon">%</span>
                                <span id="btnGuardarAvanceTarea" class="input-group-addon btn btn-success  <?php echo $classButtons; ?>" <?php echo $estatusCampos; ?>><i class="fa fa-save"></i></span>
                            </div>                            
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 col-sm-6 col-xs-12">
                        <div class="form-group">
                            <label class="f-w-600 f-s-13">Predecesora:</label>
                            <pre class="form-control f-s-14"><?php echo (($generales['Predecesora'] != '') ? $generales['Predecesora'] : 'N/A'); ?></pre>                                
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6 col-xs-12">
                        <div class="form-group">
                            <label class="f-w-600 f-s-13">Inicio:</label>
                            <pre class="form-control f-s-14"><?php echo $generales['Inicio']; ?></pre>                                
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6 col-xs-12">
                        <div class="form-group">
                            <label class="f-w-600 f-s-13">Fin:</label>
                            <pre class="form-control f-s-14"><?php echo $generales['Fin']; ?></pre>                                
                        </div>
                    </div>
                </div>  
                <div class="row">                    
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <div class="form-group">
                            <label class="f-w-600 f-s-13">Usuarios:</label>
                            <?php
                            $_usuarios = explode(",", $generales['Usuarios']);
                            foreach ($_usuarios as $key => $value) {
                                ?>
                                <div class="input-group m-b-2">
                                    <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                    <pre class="form-control f-s-14"><?php echo $value; ?></pre>
                                </div> 
                                <?php
                            }
                            ?>

                        </div>
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <div class="form-group">
                            <label class="f-w-600 f-s-13">Líder:</label>
                            <pre class="form-control f-s-14"><?php echo $generales['Lider']; ?></pre>                                
                        </div>
                    </div>
                </div>
            </div>
        </div>        
        <!--Empezando la seccion Generales-->

        <!--Empezando la seccion Material por Nodo-->
        <div class="tab-pane fade" id="MaterialNodo">
            <div class="panel-body" id="divNodosTarea"></div>
        </div>        
        <!--Empezando la seccion Material-->

        <!--Empezando la seccion Consumo de Material-->
        <div class="tab-pane fade" id="ConsumoMaterial">
            <div class="panel-body" id="divConsumoMaterial"></div>
        </div>        
        <!--Empezando la seccion Consumo de Material-->

        <!--Empezando la seccion Notas y Adjuntos-->
        <div class="tab-pane fade" id="NotasAdjuntos">
            <div class="panel-body">
                <div id="divFormularioNotasAdjuntos" class="<?php echo $classButtons; ?>">
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <h4 class="f-w-600">Agregar Notas y Adjuntos</h4>  
                            <div class="underline m-b-10"></div>
                        </div>
                    </div>  
                    <div class="row">
                        <div class="col-md-8 col-sm-9 col-xs-12">
                            <div class="form-group">
                                <label class="f-w-600 f-s-13">Nota:</label>
                                <textarea class="form-control" rows="5" id="txtNotaTarea" value=""></textarea>                            
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <label class="f-w-600 f-s-13">Adjuntos:</label>
                                <input id="adjuntosTarea" name="adjuntosTarea[]" type="file" multiple=""/>    
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12 text-center">
                            <a id="btnGuardarNotasAdjuntos" class="btn btn-success f-w-600 f-s-13"><i class="fa fa-save"> </i> Guardar Notas y Adjuntos</a>
                        </div>
                    </div>
                    <div class="row m-t-10">                       
                        <div class="col-md-offset-4 col-md-4 col-sm-offset-3 col-sm-6 col-xs-offset-1 col-xs-10">
                            <div id="errorGuardarNotasAdjunto"></div>
                        </div>
                    </div>
                </div>
                <div class="row m-t-15">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <h4 class="f-w-600">Notas y Adjuntos de la Tarea</h4>  
                        <div class="underline m-b-10"></div>
                    </div>
                </div> 
                <div class="timelineTareas" id="divNotasAdjuntos"></div>
            </div>            
        </div>        
        <!--Empezando la seccion Notas y Adjuntos-->

    </div>    
</div>

