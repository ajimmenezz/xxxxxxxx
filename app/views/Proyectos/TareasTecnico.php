<!-- Empezando #contenido -->
<div id="content" class="content">
    <!-- Empezando titulo de la pagina -->
    <h1 class="page-header">Seguimiento <small> de proyecto</small></h1>
    <!-- Finalizando titulo de la pagina -->

    <!-- Empezando panel de proyectos asignados-->
    <div id="panel-table-tareas-asignadas" class="panel panel-inverse">

        <!--Empezando cabecera del panel-->
        <div class="panel-heading">
            <div class="panel-heading-btn">                
                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>                            
                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>                            
            </div>
            <h4 class="panel-title">Tareas Asignadas</h4>
        </div>
        <!--Finalizando cabecera del panel-->

        <!--Empezando cuerpo del panel-->
        <div class="panel-body">            
            <!--Empezando tabla de proyectos asignados-->
            <div id="seccionListaProyectos" class="row">                
                <div class="col-md-12">          
                    <div class="form-group">
                        <table id="data-table-proyecto-tareas-asignadas" class="table table-hover table-striped table-bordered no-wrap " style="cursor:pointer" width="100%">
                            <thead>
                                <tr>
                                    <th class="never">IdProyecto</th>
                                    <th class="never">IdTarea</th>                                    
                                    <th class="all">Tarea</th>
                                    <th class="all">Proyecto</th>
                                    <th class="not-mobile-l">Complejo</th>
                                    <th class="not-mobile-l">Fecha Inicio</th>
                                    <th class="not-mobile-l">Fecha Fin</th>
                                    <th class="all">Avance</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($datos['TareasTecnico'] as $key => $value) {
                                    echo '<tr>';
                                    echo '<td>' . $value['IdProyecto'] . '</td>';
                                    echo '<td>' . $value['IdTarea'] . '</td>';
                                    echo '<td>' . $value['Tarea'] . '</td>';
                                    echo '<td>' . $value['Proyecto'] . '</td>';
                                    echo '<td>' . $value['Complejo'] . '</td>';
                                    echo '<td>' . (($value['FechaInicio'] !== '0000-00-00' ) ? $value['FechaInicio'] : '') . '</td>';
                                    echo '<td>' . (($value['FechaTermino'] !== '0000-00-00' ) ? $value['FechaTermino'] : '') . '</td>';
                                    echo '<td>' . (($value['Avance'] !== '0%') ? $value['Avance'] : $value['AvanceNodos'] ) . '</td>';
                                    echo '</tr>';
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>    
                </div> 
            </div>
            <!--Finalizando tabla de proyectos asignados-->           
        </div>
        <!--Finalizando cuerpo del panel-->
    </div>
    <!-- Finalizando panel de proyectos asignados -->   

    <!-- Empezando panel de seguimietno de tarea-->
    <div id="panel-seccion-detalles-tarea" class="panel panel-inverse hidden">

        <!--Empezando cabecera del panel-->
        <div class="panel-heading">
            <div class="panel-heading-btn">
                <button id="btn-regresar-lista-tareas" type="button" class="btn btn-success btn-xs"><i class="fa fa-reply"></i> Regresar</button>
                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>                            
                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>                            
            </div>
            <h4 class="panel-title">TAREA : <span id="nombre-tarea" class="f-w-700">  </span> <span id="porcentaje-tarea" class="text-danger"></span> <span class="ocultar-xs">- ÁREA : <span id="area-tarea" class="f-w-700"></span> --- </span>DURACIÓN : <span id="duración-tarea" class="text-warning"></span></h4>
        </div>
        <!--Finalizando cabecera del panel-->

        <!--Empezando cuerpo del panel-->
        <div class="panel-body">

            <!--Empezando apartado información de la tarea-->
            <div id="seccion-tabla-dias-actividad">

                <!--Empezando titulo Fila 1-->
                <div class="row">
                    <div class=" col-xs-6 col-sm-8 col-md-8">
                        <h6>Proyecto : <span id="titulo-proyecto" class="f-w-700"></span> : <span id="titulo-complejo" class="f-w-700"></span> - <span id="titulo-ticket" class="text-info f-w-700"></span></h6>
                    </div>
                    <div class="col-xs-6 col-sm-4 col-md-4 text-right ">
                        <h6>Lider : <span id="titulo-lider"></span></h6>
                    </div>
                </div>
                <!--Finalizando titulo Fila 1-->

                <!--Empezando Separador Fila 2-->
                <div class="row">
                    <div class="col-md-12">
                        <div class="underline m-b-15"></div>
                    </div>
                </div>
                <!--Finalizando Separador Fila 2--> 

                <!--Empezando tabla-->
                <div id="seccion-tabla-nodos-tarea" class="hidden">
                    <!--Empezando Fila 3-->
                    <div class="row">
                        <div class="col-xs-8 col-md-8">
                            <h4 class="f-w-700">Nodos</h4>
                        </div>                    
                    </div>
                    <!--Finalizando Fila 3-->

                    <!--Empezando Separador Fila 4-->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="underline m-b-15"></div>
                        </div>
                    </div>
                    <!--Finalizando Separador Fila 4-->

                    <!--Empezando Fila 5-->
                    <div class="row m-b-20">
                        <div class="col-md-12">
                            <div class="form-group">
                                <table id="data-table-nodos-tarea" class="table table-hover table-striped table-bordered no-wrap " style="cursor:pointer" width="100%">
                                    <thead>
                                        <tr>                                                                                                                                                       
                                            <th class="all">Ubicación</th>
                                            <th class="not-mobile-l">Tipo</th>
                                            <th class="not-mobile-l">Nodo</th>
                                            <th class="not-mobile-l">Avance</th>
                                        </tr>
                                    </thead>
                                    <tbody>                                    
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!--Finalizando Fila 5-->
                </div>
                <!--Empezando tabla-->

                <!--Empezando Fila 6-->
                <div class="row">
                    <div class="col-xs-8 col-md-8">
                        <h4 class="f-w-700">Días de actividad</h4>
                    </div>
                    <div class="col-xs-4 col-md-4 text-right">
                        <h6><button id="btn-nueva-actividad" type="button" class="btn btn-success btn-xs"><i class="fa fa-plus"></i> <span class="texto-btn-agregar-actividad"> Actividad</span></button></h6>
                    </div> 
                </div>
                <!--Finalizando Fila 6-->

                <!--Empezando Separador Fila 7-->
                <div class="row">
                    <div class="col-md-12">
                        <div class="underline m-b-15"></div>
                    </div>
                </div>
                <!--Finalizando Separador Fila 7-->

                <!--Empezando Fila 8-->
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <table id="data-table-dias-actividad-tarea" class="table table-hover table-striped table-bordered no-wrap " style="cursor:pointer" width="100%">
                                <thead>
                                    <tr>                                    
                                        <th class="never">Id</th>                                                                            
                                        <th class="all">Fecha</th>
                                        <th class="not-mobile-l">Descripción</th>
                                        <th class="not-mobile-l">Capturo</th>
                                    </tr>
                                </thead>
                                <tbody>                                    
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!--Finalizando Fila 8 -->
            </div>
            <!--Finalizando apartado información de la tarea-->

            <!--Empezando apartado Actividad-->
            <div id="seccion-formulario-actividad" class="hidden">

                <!--Empezando titulo-->
                <div class="row">
                    <div class="col-xs-6 ">
                        <h5 class="f-w-700"><span class="nueva-actividad">Nueva Actividad</span><span class="modificar-actividad hidden">Documentar Actividad</span></h5>
                    </div>                    
                    <div class="col-xs-6 text-right">
                        <h5>
                            <button id="btn-generar-nueva-actividad" type="button" class="btn btn-success btn-xs nueva-actividad"><i class="fa fa-floppy-o"></i> <span class="ocultar-xs">Generar Nueva Actividad</span></button>
                            <button id="btn-cancelar-nueva-actividad" type="button" class="btn btn-danger btn-xs nueva-actividad"> Cancelar</button>
                            <button id="btn-regresar-tabla-actividades" type="button" class="btn btn-info btn-xs modificar-actividad hidden"><i class="fa fa-reply"></i> <span class="ocultar-xs">Regresar Tarea</span></button>
                            <button id="btn-actualizar-nueva-actividad" type="button" class="btn btn-success btn-xs hidden "><i class="fa fa-floppy-o"></i> <span class="ocultar-xs">Guardar</span></button>
                            <button id="btn-eliminar-actividad" type="button" class="btn btn-danger btn-xs modificar-actividad hidden"><i class="fa fa-trash"></i> <span class="ocultar-xs">Eliminar</span></button>
                        </h5>
                    </div>                    
                </div>
                <!--Finalizando titulo-->

                <!--Empezando Separador-->
                <div class="row">
                    <div class="col-md-12">
                        <div class="underline m-b-15"></div>
                    </div>
                </div>
                <!--Finalizando Separador-->     

                <!--Empezando formulario datos principales-->
                <form id="form-nueva-actividad-tarea" data-parsley-validate="true">

                    <!--Empezando fila 1-->
                    <div class="row">
                        <div class="col-md-12">
                            <label>Describa lo que realizo en el día.</label>
                            <div class="form-group">
                                <textarea id="textArea-descripcion-actividad" class="form-control" placeholder="Ingresa aqui tus descripcion..." rows="5" data-parsley-required="true"></textarea>
                            </div>
                        </div>
                    </div>
                    <!--Finalizando fila 1-->

                    <!--Empezando fila 2-->
                    <div class="row">
                        <div class="col-md-offset-3 col-md-3 text-center" disable>
                            <div class="form-group">
                                <label >Fecha Proyectada</label>
                                <div id="fecha-proyectada-actividad" class="input-group date" >
                                    <input type="text" class="form-control" placeholder="Inicio" readonly />
                                    <span class="input-group-addon" ><i class="fa fa-calendar"></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 text-center">
                            <div class="form-group">
                                <label >Fecha Real</label>
                                <div id="fecha-real-actividad" class="input-group date " >
                                    <input type="text" class="form-control" placeholder="Final" readonly />
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--Finalizando fila 2-->

                    <!--Empezando fila 3-->
                    <div id="elemento-evidencia-actividad" class="row nueva-evidencia hidden">
                        <div class="col-md-12">
                            <label>Evidencia</label>
                            <div class="form-group">  
                                <input id="file-evidencia-actividad" name="evidenciaActividadSinNodos[]" type="file" multiple>
                            </div>
                        </div>
                    </div>
                    <!--Finalizando fila 3-->

                    <!--Empezando fila 4-->
                    <div class="row archivos-subidos hidden">
                        <div class="col-md-12">
                            <label>Evidencia</label>
                            <div class="evidenciasSubidas">                                
                            </div>
                        </div>
                    </div>
                    <!--Finalizando fila 4-->
                </form>                
                <!--Finalizando formulario datos principales-->

                <!--Empezando subseccion material utilizado-->
                <div id="subseccion-nodos-utilizados" class="hidden">
                    <!--Empezando titulo material actividad-->
                    <div class="row">
                        <div class="col-xs-7 col-md-10 f-w-700">
                            <h5 class="f-w-700">Nodos Realizados</h5>
                        </div>
                        <div class="col-xs-5 col-md-2 text-right">
                            <h5>
                                <button id="btn-agregar-nodo-actividad" type="button" class="btn btn-success btn-xs"><i class="fa fa-plus"></i> Agregar Nodo</button>
                            </h5>
                        </div>
                    </div>
                    <!--Finalizando titulo material actividad-->

                    <!--Empezando Separador-->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="underline m-b-15"></div>
                        </div>
                    </div>
                    <!--Finalizando Separador--> 

                    <!--Empezando fila 3-->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <table id="datatable-nodos-capturados-actividad" class="table table-hover table-striped table-bordered no-wrap " style="cursor:pointer" width="100%">
                                    <thead>
                                        <tr>                                        
                                            <th class="never">Id</th>                                    
                                            <th class="all">Ubicación</th>
                                            <th class="all">Nodo</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!--Finalizando fila 3-->
                </div>
                <!--Finalizando subseccion material utilizado-->
            </div>
            <!--Finalizando apartado Actividad-->

            <!--Empezando apartado Nodo-->
            <div id="seccion-agregar-nodo" class="hidden">

                <!--Empezando titulo-->
                <div class="row">
                    <div class="col-xs-7 ">
                        <h5 class="f-w-700"><span class="nuevo-nodo">Definir Nodo</span><span class="info-nodo hidden">Nodo Capturado : <span id="nombre-nodo"></span></h5>
                    </div>                    
                    <div class="col-xs-5 text-right">
                        <h5>
                            <button id="btn-regresar-actividad" type="button" class="btn btn-success btn-xs info-nodo hidden"><i class="fa fa-reply"></i> <span class="ocultar-xs">Regresar Actividad</span></button>
                            <button id="btn-guardar-material-nodo" type="button" class="btn btn-success btn-xs nuevo-nodo"><i class="fa fa-floppy-o"></i> <span class="ocultar-xs">Guardar </span></button>
                            <button id="btn-cancelar-material-nodo" type="button" class="btn btn-danger btn-xs nuevo-nodo">Cancelar</button>
                            <button id="btn-eliminar-nodo" type="button" class="btn btn-danger btn-xs info-nodo hidden"><i class="fa fa-trash"></i> <span class="ocultar-xs">Eliminar</span></button>
                        </h5>
                    </div>                    
                </div>
                <!--Finalizando titulo-->

                <!--Empezando Separador-->
                <div class="row">
                    <div class="col-md-12">
                        <div class="underline m-b-15"></div>
                    </div>
                </div>
                <!--Finalizando Separador--> 

                <!--Empezando formulario para agregar nodo--> 
                <form id="form-definiendo-nodo" data-parsley-validate="true">
                    <!--Empezando fila 1-->
                    <div class="row nuevo-nodo">
                        <div class="col-xs-12 col-md-4">
                            <label>Nodo</label>
                            <div class="form-group">
                                <select id="select-nodo" class="form-control" style="width: 100%" data-parsley-required="true">
                                    <option value="">Seleccionar</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-4">
                            <label>&nbsp;</label>
                            <div class="form-group">
                                <button id="btn-mostrar-formulario-material-nodo" type="button" class="btn btn-success btn-sm "><i class="fa fa-plus"></i> <span class="ocultar-xs">Agregar Nodo</span></button>
                            </div>
                        </div>
                    </div>
                    <!--Finalizando fila 1-->
                </form>
                <!--Finalizando formulario para agregar nodo--> 

                <!--Empezando subseccion para agregar material y evidencia-->
                <div id="subseccion-material-nodo" class="hidden">

                    <!--Empezando formulario para material del nodo--> 
                    <form id="form-definiendo-material-nodo" data-parsley-validate="true">
                        <!--Empezando fila 1-->
                        <div class="row">
                            <div class="col-xs-12 col-md-3">
                                <label>Material</label>
                                <div class="form-group">
                                    <select id="select-material" class="form-control" style="width: 100%" data-parsley-required="true">
                                        <option value="">Seleccionar</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-xs-12 col-md-3">
                                <label>Solicitado</label>
                                <div class="form-group">
                                    <input id="input-solicitado-nodo" type="text" class="form-control" disabled/>
                                </div>
                            </div>
                            <div class="col-xs-12 col-md-3">
                                <label>Utilizado</label>
                                <div class="form-group">
                                    <input id="input-utilizado-material-nodo" type="number" class="form-control" placeholder="Cantidad" data-parsley-required="true"/>
                                </div>
                            </div>
                            <div class="col-xs-12 col-md-3">
                                <label>&nbsp;</label>
                                <div class="form-group">
                                    <button id="btn-agregar-material-nodo" type="button" class="btn btn-success btn-sm "><i class="fa fa-plus"></i> <span class="ocultar-xs">Agregar Material</span></button>
                                </div>
                            </div>
                        </div>
                        <!--Finalizando fila 1-->
                    </form>
                    <!--Finalizando formulario para material del nodo--> 

                    <!--Empezando fila 3-->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <table id="datatable-material-nodo" class="table table-hover table-striped table-bordered no-wrap " style="cursor:pointer" width="100%">
                                    <thead>
                                        <tr>
                                            <th class="never">Id</th>                                    
                                            <th class="all">Material</th>
                                            <th class="all">Solicitado</th>
                                            <th class="all">Utilizado</th>
                                            <th class="all">Justificación</th>
                                        </tr>
                                    </thead>
                                    <tbody>                                        
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!--Finalizando fila 3-->

                    <!--Empezando alerta mensaje--> 
                    <div class="row nuevo-nodo">
                        <div class="col-md-12 m-t-15">                                
                            <div class="alert alert-info fade in ">
                                <strong>Informacion : </strong>
                                Para eliminar el material de la tabla solo tiene que dar click sobre fila para eliminarlo.
                            </div>
                        </div>
                    </div>
                    <!--Finalizando alerta mensaje--> 

                    <!--Empezando fila 3-->
                    <div class="row nuevo-nodo">
                        <div class="col-md-12">
                            <label>Evidencia</label>
                            <div class="form-group">  
                                <input id="file-evidencia-material-utilizado" name="evidenciaMaterial[]" type="file" multiple>
                            </div>
                        </div>
                    </div>
                    <!--Finalizando fila 3-->

                    <!--Empezando fila 4-->
                    <div id="file-evidencia-subida" class="row m-t-15 info-nodo hidden">
                        <div class="col-md-12">
                            <label>Evidencia</label>
                            <div class="evidenciasMaterialUtilizado">                                
                            </div>
                        </div>
                    </div>
                    <!--Finalizando fila 4-->

                </div>
                <!--Finalizando subseccion para agregar material y evidencia-->

            </div>
            <!--Empezando apartado Nodo-->

        </div>
        <!--Finalizando cuerpo del panel-->
    </div>
    <!-- Finalizando panel de proyectos asignados -->   

</div>
<!-- Finalizando #contenido -->