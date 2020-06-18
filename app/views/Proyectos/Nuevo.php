<!--Empezando scritp para ejecutar pdf de diagrama de gantt-->
<script src="https://export.dhtmlx.com/gantt/api.js"></script>
<!--FInalizando scritp para ejecutar pdf de diagrama de gantt-->

<!-- Empezando #contenido -->
<div id="content" class="content">
    <div id="divListaProyectos">
        <div class="row">
            <div class="col-md-9 col-sm-6 col-xs-12">
                <h1 class="page-header">Proyectos <small>Especiales</small></h1>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12 text-right">
                <label id="btn-proyecto-nuevo" class="btn btn-success">
                    <i class="fa fa-file"></i> Nuevo Proyecto
                </label>  
            </div>
        </div>                 
        <div id="panel-table-proyectos" class="panel panel-inverse">            
            <div class="panel-heading">
                <div class="panel-heading-btn">                
                    <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                    <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
                </div>
                <h4 class="panel-title">Proyectos Sin Iniciar</h4>
            </div>            
            <div class="panel-body">                
                <div class="row">                           
                    <div class="col-md-12">
                        <table id="data-table-sinIniciar" class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%">
                            <thead>
                                <tr>                                
                                    <th class="never">Id</th>
                                    <th class="all">Ticket</th>
                                    <th class="all">Proyecto</th>
                                    <th class="not-mobile-l">Complejo</th>
                                    <th class="not-mobile-l">Ciudad</th>
                                    <th class="not-mobile-l">Fecha Inicio</th>
                                    <th class="not-mobile-l">Fecha Fin</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($datos['ProyectosSinAtender'] as $key => $value) {
                                    echo '<tr>';
                                    echo '<td>' . $value['Id'] . '</td>';
                                    echo '<td>' . (($value['Ticket'] !== '0' ) ? $value['Ticket'] : '') . '</td>';
                                    echo '<td>' . $value['Nombre'] . '</td>';
                                    echo '<td>' . $value['Complejo'] . '</td>';
                                    echo '<td>' . $value['Estado'] . '</td>';
                                    echo '<td>' . (($value['FechaInicio'] !== '0000-00-00' ) ? $value['FechaInicio'] : '') . '</td>';
                                    echo '<td>' . (($value['FechaTermino'] !== '0000-00-00' ) ? $value['FechaTermino'] : '') . '</td>';
                                    echo '</tr>';
                                }
                                ?>
                            </tbody>
                        </table>
                    </div> 
                </div>                
            </div>            
        </div>        
    </div>

    <div id="divDetallesProyecto" style="display:none !important;">
        <div class="row">
            <div class="col-md-9 col-sm-6 col-xs-12">
                <h1 class="page-header">Proyectos <small>Especiales</small></h1>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12 text-right">
                <label id="btnRegresar" class="btn btn-success">
                    <i class="fa fa-reply"></i> Regresar
                </label>  
            </div>
        </div>

        <div id="panel-seccion-proyecto" class="panel panel-inverse panel-with-tabs" data-sortable-id="ui-unlimited-tabs-1">            
            <div class="panel-heading p-0">
                <div class="panel-heading-btn m-r-10 m-t-10">                    
                    <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                </div>
                <!--Finalizando botones-->

                <!-- Empezando nav-tabs en la cabecera del panel -->
                <div class="tab-overflow">
                    <ul class="nav nav-tabs nav-tabs-inverse">
                        <li class="prev-button"><a href="javascript:;" data-click="prev-tab" class="text-success"><i class="fa fa-arrow-left"></i></a></li>
                        <li id="pestana-generales" class="active"><a href="#generales" data-toggle="tab">Generales</a></li>
                        <li id="pestana-material" class="hidden"><a href="#material" data-toggle="tab">Material</a></li>
                        <li id="pestana-alcance" class="hidden"><a href="#alcance" data-toggle="tab">Alcance</a></li>
                        <li id="pestana-personal" class="hidden"><a href="#personal" data-toggle="tab">Técnicos</a></li>
                        <li id="pestana-tareas" class="hidden"><a href="#tareas" data-toggle="tab">Tareas</a></li>
                        <li class="next-button"><a href="javascript:;" data-click="next-tab" class="text-success"><i class="fa fa-arrow-right"></i></a></li>
                    </ul>
                </div>
                <!-- Finalizando nav-tabs en la cabecera del panel -->
            </div>
            <!--Finalizando cabecera del panel-->

            <!-- Empezando contenido del panel  -->
            <div id="contenido" class="tab-content panel panel-body">                
                <!--Empezando formulario generales-->
                <div id="generales" class="tab-pane fade active in" >
                    <!--Empezando cabecera -->
                    <div id="cabecera-alcance">
                        <!--Empezando titulo-->
                        <div class="row">
                            <div class="col-xs-7 col-sm-5 col-md-5">
                                <h5 class="titulo-pestaña"><span class="nombre-proyecto"></span> : <span class="nombre-complejo"></span> - <span class="ticket-proyecto text-info"></span></h5>
                            </div>
                            <div class="col-xs-5 col-sm-7 col-md-7 text-right">
                                <h5>
                                    <button id="" type="button" class="btn btn-info btn-xs btn-iniciar-proyecto-complejo hidden"><i class="fa fa-check"></i> <span class="texto-btn-cabecera-formulario-proyecto">Iniciar Proyecto</span></button>
                                    <button id="" type="button" class="btn btn-success btn-xs btn-nuevo-complejo-proyecto"><i class="fa fa-plus"></i> <span class="texto-btn-cabecera-formulario-proyecto">Complejo</span></button>
                                    <div class="btn-group pull-right m-l-5">
                                        <button type="button" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i> <span class="texto-btn-cabecera-formulario-proyecto">Eliminar</span></button>
                                        <button type="button" class="btn btn-danger btn-xs dropdown-toggle" data-toggle="dropdown">
                                            <span class="caret"></span>
                                        </button>
                                        <ul class="dropdown-menu" role="menu">
                                            <li><a href="javascript:;" class="btn-eliminar-proyecto">Proyecto</a></li>
                                            <li><a href="javascript:;" class="btn-eliminar-complejo">Complejo</a></li>
                                        </ul>
                                    </div>                               
                                    <div class="btn-group pull-right m-l-5">
                                        <button type="button" class="btn btn-white btn-xs"><i class="fa fa-file-pdf-o"></i> <span class="texto-btn-cabecera-formulario-proyecto"> Exportar</span></button>
                                        <button type="button" class="btn btn-white btn-xs dropdown-toggle" data-toggle="dropdown">
                                            <span class="caret"></span>
                                        </button>
                                        <ul class="dropdown-menu" role="menu">
                                            <li><a href="javascript:;" class="btn-reporte-inicio-proyecto">Inicio Proyecto</a></li>
                                            <li><a href="javascript:;" class="btn-reporte-material">Material</a></li>
                                        </ul>
                                    </div>  
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
                    </div>                   
                    <div id="cuerpo-generales" class=" m-t-20">
                        <!--Empezando formulario-->
                        <form id="form-nuevo-proyecto" data-parsley-validate="true">

                            <!--Empezando informacion sin complejo-->
                            <div class="row">
                                <div id="info-sin-complejo" class="col-md-12">
                                    <div class="alert alert-info fade in m-b-15">
                                        <strong>Para poder capturar el alcance, personal y tareas deberas definir al menos un complejo.</strong>                                                                    
                                    </div>
                                </div>
                            </div>
                            <!--Finalizando informacion sin complejo-->

                            <!--Empezando fila-1-->
                            <div class="row">
                                <div id="nombre-proyecto" class="col-xs-12 col-md-4">
                                    <div class="form-group">
                                        <label>Nombre</label>
                                        <input id="input-nombre-proyecto" type="text" class="form-control" placeholder="Nombre del proyecto" data-parsley-required="true"/>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-md-4">
                                    <div class="form-group">
                                        <label >Sistema</label>
                                        <select id="select-sistemas" class="form-control" style="width: 100%" data-parsley-required="true">
                                            <option value="">Seleccionar</option>
                                            <?php
                                            foreach ($datos['Sistemas'] as $item) {
                                                echo '<option value="' . $item['Id'] . '">' . $item['Nombre'] . '</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-md-4">
                                    <div class="form-group">
                                        <label >Tipo Proyecto</label>
                                        <select id="select-tipo-proyecto" class="form-control" style="width: 100%" data-parsley-required="true">
                                            <option value="">Seleccionar</option>
                                            <?php
                                            foreach ($datos['Tipo'] as $item) {
                                                echo '<option value="' . $item['Id'] . '">' . $item['Nombre'] . '</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <!--Finalizando fila-1-->

                            <!--Empezando fila-2-->
                            <div class="row">
                                <div id="contenedor-select-complejo" class="col-md-12">
                                    <div class="form-group">
                                        <label >Complejo(s)</label>
                                        <div class="form-inline ">
                                            <select id="select-complejo" class="form-control " style="width: 95%;"  multiple="multiple" >
                                                <?php
                                                foreach ($datos['Complejos'] as $item) {
                                                    echo '<option value="' . $item['Id'] . '">' . $item['Nombre'] . '</option>';
                                                }
                                                ?>
                                            </select>
                                            <a id="ayuda-complejo-md" class="btn btn-inverse btn-icon btn-circle" data-toggle="tooltip" data-placement="bottom" title="Si el complejo es nuevo debes generar una soliciutd a  la mesa de ayuda."><i class="fa fa-question"></i></a>
                                            <span id="ayuda-complejo-xs" class="label label-default">Si el complejo es nuevo debes generar una soliciutd a  la mesa de ayuda.</span>
                                        </div>
                                    </div>
                                </div>                                
                            </div>
                            <!--Finalizando fila-2-->

                            <!--Empezando fila-3-->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label >Lideres </label>
                                        <select id="select-lideres" class="form-control" style="width: 100%"  multiple="multiple">                                        
                                            <?php
                                            foreach ($datos['Lideres'] as $item) {
                                                echo '<option value="' . $item['Id'] . '"  >' . $item['Nombre'] . '</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <!--Finalizando fila-3-->

                            <!--Empezando fila-3-->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label >Observaciones</label>
                                        <textarea id="textArea-observaciones" class="form-control" placeholder="Ingresa aqui tus observaciones" rows="5" data-parsley-required="true"></textarea>
                                    </div>
                                </div>
                            </div>
                            <!--Finalizando fila-3-->

                            <!--Empezando fila-4-->
                            <div class="row">
                                <div class="col-md-offset-3 col-md-3 text-center" disable>
                                    <div class="form-group">
                                        <label >Fecha Inicio</label>
                                        <div id="fecha-inicio-proyecto" class="input-group date" data-date-format="dd-mm-yyyy" data-date-start-date="Date.default" >
                                            <input type="text" class="form-control" placeholder="Inicio" readonly />
                                            <span class="input-group-addon" ><i class="fa fa-calendar"></i></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 text-center">
                                    <div class="form-group">
                                        <label >Fecha Final</label>
                                        <div id="fecha-final-proyecto" class="input-group date " data-date-format="dd-mm-yyyy" data-date-start-date="Date.default">
                                            <input type="text" class="form-control" placeholder="Final" readonly />
                                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--Finalizando fila-4-->

                        </form>
                        <!--Empezando formulario-->

                        <!--Empezando botones de acciones-->
                        <div class="row">
                            <div class="col-md-12 text-center m-t-10">
                                <button id="btn-generar-proyecto" type="button" class="btn btn-success"><i class="fa fa-save"></i> Generar Proyecto</button>
                                <button id="btn-guardar-cambios" type="button" class="btn btn-success hidden"><i class="fa fa-save"></i> Guardar</button>
                                <button id="btn-limpiar-formulario" type="button" class="btn btn-danger"><i class="fa fa-trash-o"></i> Limpiar</button>
                                <button id="btn-cancelar" type="button" class="btn btn-danger hidden"><i class="fa fa-trash-o"></i> Cancelar</button>
                                <button id="btn-actualizar-proyecto" type="button" class="btn btn-info hidden"><i class="fa fa-refresh"></i> Actualizar</button>
                            </div>
                        </div>
                        <!--Finalizando botones de acciones-->
                    </div>
                    <!--Finalizando Cuerpo-->
                </div>
                <!--Finalizando formulario generales-->

                <!--Empezando formulario Material-->
                <div id="material" class="tab-pane fade">   
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="form-grup">
                                <h4 class="m-t-10">Material General del Proyecto</h4>                        
                                <div class="underline m-b-15 m-t-15"></div>                                               
                            </div>
                        </div>
                    </div>
                    <div id="cuerpo-alcance">
                        <!--Empezando tabla-->
                        <div class="row">                           
                            <div class="col-md-12">
                                <table id="data-table-material-alcance" class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%">
                                    <thead>
                                        <tr>
                                            <th class="never">Id</th>
                                            <th class="not-mobile-l">Material</th>
                                            <th class="all">Numero Parte</th>
                                            <th class="all">Total</th>
                                            <th class="all">Unidad</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>

                                </table>
                            </div> 
                        </div>
                        <!--Finalizando tabla-->

                        <!--Empezando botones alcance-->
                        <div class="row">
                            <div class="col-md-12 text-center m-t-20">
                                <button id="btn-generar-solicitud-material" type="button" class="btn btn-sm btn-info"><i class="fa fa-check"></i> Generar Solicitud Material</button>                                
                                <button id="btn-lista-nodos" type="button" class="btn btn-sm btn-default"><i class="fa fa-list"></i> Lista de Ubicaciones</button>
                            </div>
                        </div>                        
                        <div id="alerta-solicitud-material-generada" class="row hidden">
                            <div class="col-md-12">                                
                                <div class="alert alert-info fade in ">
                                    <strong>Informacion : </strong>
                                    Ya se generó la solicitud <span id="num-solicitud-material"></span> al área de almancén. Por tal motivo ya no es posbible agregar mas nodos al proyecto.
                                </div>
                            </div>
                        </div>                        
                    </div>
                </div>
                <!--Finalizando formulario Material-->

                <!--Empezando formulario Alcance-->
                <div id="alcance" class="tab-pane fade">                                           
                    <div class="row">
                        <div class="col-md-6 col-xs-6">
                            <h4 class="m-t-10">Lista de Ubicaciones</h4>
                        </div>
                        <div class="col-md-6 col-xs-6">
                            <div class="form-group text-right">
                                <button id="btn-nuevo-nodo" type="button" class="btn btn-success"><i class="fa fa-plus"></i> Agregar Ubicación</button>                                    
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="underline m-b-15 m-t-15"></div>
                        </div>
                    </div>
                    <div id="divUbicaciones">

                    </div>
                </div>

                <!--Empezando formulario Personal temporal-->
                <div id="personal" class="tab-pane fade">
                    <!--Empezando Cuerpo-->
                    <div id="cuerpo-personal">
                        <!--Empezando Formulario para agregar asistente-->
                        <form id="form-agregar-asistente">
                            <!--Empezando fila 1-->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label >Técnico</label>                                    
                                        <div class="form-inline">
                                            <select id="select-asistente" class="form-control" style="width: 75%" data-parsley-required="true">
                                                <option value="">Seleccionar</option>
                                                <?php
                                                foreach ($datos['Asistentes'] as $item) {
                                                    echo '<option value="' . $item['Id'] . '" data-NSS = "' . $item['NSS'] . '">' . $item['Nombre'] . '</option>';
                                                }
                                                ?>
                                            </select>
                                            <a id="btn-agregar-asistente" href="javascript:;" class="btn btn-success m-r-5"><i class="fa fa-plus"></i> Agregar</a>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--Finalizando fila 1-->

                        </form>
                        <!--Finalizando Formulario para agregar asistente-->

                        <!--Empezando tabla asistentes-->
                        <div class="row">
                            <div class="col-md-12">
                                <table id="data-table-asistentes" class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%">
                                    <thead>
                                        <tr>                                
                                            <th class="never">Id</th>
                                            <th class="never">IdUsuario</th>
                                            <th class="all">Nombre</th>
                                            <th class="all">NSS</th>                                        
                                        </tr>
                                    </thead>
                                    <tbody>                                  
                                    </tbody>
                                </table> 
                            </div>
                        </div>
                        <!--Finalizando tabla asistentes-->

                        <!--Empezando alerta mensaje--> 
                        <div class="row">
                            <div class="col-md-12 m-t-15">                                
                                <div class="alert alert-info fade in ">
                                    <strong>Informacion : </strong>
                                    Para eliminar el registro de la tabla solo tiene que dar click sobre fila para eliminarlo.
                                </div>
                            </div>
                        </div>
                        <!--Finalizando alerta mensaje--> 

                        <!--Empezando botones de acciones-->
                        <div class="row">
                            <div class="col-md-12 text-center m-t-10">                            
                                <button id="btn-solicitud-personal" type="button" class="btn btn-sm btn-info"><i class="fa fa-file"></i> Generar Solicitud Personal</button>
                            </div>
                        </div>
                        <!--Finalizando botones de acciones-->
                    </div>
                    <!--Finalizando Cuerpo-->

                </div>
                <!--Finalizando formulario Personal temporal-->

                <!--Empezando formulario Tareas-->
                <div id="tareas" class="tab-pane fade">
                    <!--Empezando Cuerpo-->
                    <div id="cuerpo-tareas">

                        <!--Empezando fila 1-->
                        <div class="row">
                            <div class="col-md-12 m-b-15">
                                <button id="btn-nueva-tarea" type="button" class="btn btn-sm btn-success"><i class="fa fa-plus"></i> Nueva Tarea</button>
                                <div class="pull-right">
                                    <button id="btn-gantt" type="button" class="btn btn-sm btn-info"><i class="fa fa-tasks"></i> Diagrama Gantt</button>
                                    <button id="btn-pdf-gantt" type="button" class="btn btn-sm btn-white hidden" onclick='gantt.exportToPDF()'><i class="fa fa-file-pdf-o"></i> Generar PDF</button>
                                    <button id="btn-tabla-tareas" type="button" class="btn btn-sm btn-info hidden"><i class="fa fa-list-alt"></i> Lista Tareas</button>
                                </div>
                            </div>                        
                        </div>
                        <!--Finalizando fila 1-->

                        <!--Empezando contenedor tabla tareas-->
                        <div id="contenedor-tabla-tareas">
                            <!--Empezando tabla tareas-->
                            <div class="row">
                                <div class="col-md-12">
                                    <table id="data-table-tareas" class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%">
                                        <thead>
                                            <tr>                                
                                                <th class="never">Id</th>
                                                <th class="all">Nombre</th>
                                                <th class="all">Lider</th>                                        
                                                <th class="all">Fecha Inicio</th>                                        
                                                <th class="all">Fecha Fin</th>                                        
                                            </tr>
                                        </thead>
                                        <tbody>                                  
                                        </tbody>
                                    </table> 
                                </div>
                            </div>
                            <!--Finalizando tabla tareas-->

                            <!--Empezando alerta mensaje--> 
                            <div class="row">
                                <div class="col-md-12 m-t-15">                                
                                    <div class="alert alert-info fade in ">
                                        <strong>Informacion : </strong>
                                        Para eliminar o editar una tarea solo tiene que dar click sobre fila.
                                    </div>
                                </div>
                            </div>
                            <!--Finalizando alerta mensaje--> 
                        </div>
                        <!--Finalizando contenedor tabla tareas-->

                        <!--Empezando contenedor diagrama de gantt-->
                        <div id="contenedor-gantt" class="hidden">
                            <div class="row">
                                <div class="col-md-12">
                                    <div id="gantt_here" style='width:100%; height:300px;'></div>                                
                                </div>
                            </div>
                        </div>
                        <!--Finalizando contenedor diagrama de gantt-->
                    </div>
                    <!--Finalizando Cuerpo-->

                </div>
                <!--Finalizando formulario Tareas-->

            </div>
            <!-- Finalizando contenido del panel  -->

        </div>
        <!-- Finalizando panel para formularios del los proyectos -->    
    </div>


    <div id="divAgregarUbicacion" style="display:none !important;">

    </div>

</div>
<!-- Finalizando #contenido -->





