<!--Empezando scritp para ejecutar pdf de diagrama de gantt-->
<script src="https://export.dhtmlx.com/gantt/api.js"></script>
<!--FInalizando scritp para ejecutar pdf de diagrama de gantt-->

<!-- Empezando #contenido -->
<div id="content" class="content">
    <!-- Empezando titulo de la pagina -->
    <h1 class="page-header">Seguimiento <small> de proyecto</small></h1>
    <!-- Finalizando titulo de la pagina -->

    <!-- Empezando panel de proyectos asignados-->
    <div id="panel-table-proyectos" class="panel panel-inverse">

        <!--Empezando cabecera del panel-->
        <div class="panel-heading">
            <div class="panel-heading-btn">
                <button id="" type="button" class="btn btn-info btn-xs"><i class="fa fa-refresh"></i> Proyectos</button>
                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>                            
                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>                            
            </div>
            <h4 class="panel-title">Complejos</h4>
        </div>
        <!--Finalizando cabecera del panel-->

        <!--Empezando cuerpo del panel-->
        <div class="panel-body">
            <!--Empezando tabla de proyectos asignados-->
            <div id="seccionListaProyectos" class="row">
                <div class="col-md-12">          
                    <div class="form-group">
                        <table id="data-table-proyectos-iniciados" class="table table-hover table-striped table-bordered no-wrap " style="cursor:pointer" width="100%">
                            <thead>
                                <tr>
                                    <th class="never">Id</th>
                                    <th class="all">Ticket</th>
                                    <th class="all">Proyecto</th>
                                    <th class="not-mobile-l">Complejo</th>                                    
                                    <th class="not-mobile-l">Fecha Inicio</th>
                                    <th class="not-mobile-l">Fecha Fin</th>
                                    <th class="not-mobile-l">Avance</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($datos['ProyectosIniciados'] as $key => $value) {
                                    echo '<tr>';
                                    echo '<td>' . $value['Id'] . '</td>';
                                    echo '<td>' . (($value['Ticket'] !== '0' ) ? $value['Ticket'] : '') . '</td>';
                                    echo '<td>' . $value['Nombre'] . '</td>';
                                    echo '<td>' . $value['Complejo'] . '</td>';
                                    echo '<td>' . (($value['FechaInicio'] !== '0000-00-00' ) ? $value['FechaInicio'] : '') . '</td>';
                                    echo '<td>' . (($value['FechaTermino'] !== '0000-00-00' ) ? $value['FechaTermino'] : '') . '</td>';
                                    echo '<td>' . $value['Avance'] . '</td>';
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

    <!-- Empezando panel para formularios del los proyectos -->
    <div id="panel-seccion-proyecto" class="panel panel-inverse panel-with-tabs hidden" data-sortable-id="ui-unlimited-tabs-1">

        <!--Empezando cabecera del panel-->
        <div class="panel-heading p-0">

            <!--Empezando botones-->
            <div class="panel-heading-btn m-r-10 m-t-10">
                <button id="btn-regresar-lista-proyectos" type="button" class="btn btn-info btn-xs"><i class="fa fa-reply"></i> Regresar</button>
                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-expand"><i class="fa fa-expand"></i></a>
            </div>
            <!--Finalizando botones-->

            <!-- Empezando nav-tabs en la cabecera del panel -->
            <div class="tab-overflow">
                <ul class="nav nav-tabs nav-tabs-inverse">
                    <li class="prev-button"><a href="javascript:;" data-click="prev-tab" class="text-success"><i class="fa fa-arrow-left"></i></a></li>
                    <li id="pestana-generales" class="active"><a href="#generales" data-toggle="tab">Generales</a></li>
                    <li id="pestana-material" class=""><a href="#material" data-toggle="tab">Material</a></li>
                    <li id="pestana-personal" class=""><a href="#personal" data-toggle="tab">Personal</a></li>
                    <li id="pestana-tareas" class=""><a href="#tareas" data-toggle="tab">Tareas</a></li>
                    <li class="next-button"><a href="javascript:;" data-click="next-tab" class="text-success"><i class="fa fa-arrow-right"></i></a></li>
                </ul>
            </div>
            <!-- Finalizando nav-tabs en la cabecera del panel -->
        </div>
        <!--Finalizando cabecera del panel-->

        <!-- Empezando contenido del panel  -->
        <div id="contenido" class="tab-content panel panel-body">

            <!--Empezando Seccion Generales-->
            <div id="generales" class="tab-pane fade active in" >

                <!--Empezando cabecera -->
                <div id="cabecera-generales" >

                    <!--Empezando titulo-->
                    <div class="row">
                        <div class="col-xs-7 col-sm-5 col-md-5">
                            <h5 class="titulo-pestaña"><span class="nombre-proyecto"></span> : <span class="nombre-complejo"></span> - <span class="ticket-proyecto text-info"></span></h5>
                        </div>
                        <div class="col-xs-5 col-sm-7 col-md-7 text-right">
                            <h5>
                                <button id="" type="button" class="btn btn-warning btn-xs btn-concluir-proyecto-complejo hidden" title="Concluir Proyecto"><i class="fa fa-check"></i> <span class="texto-btn-cabecera-formulario-proyecto">Concluir Proyecto</span></button>
                                <button id="" type="button" class="btn btn-success btn-xs btn-nuevo-anexo-proyecto" title="Nuevo Anexo"><i class="fa fa-plus"></i> <span class="texto-btn-cabecera-formulario-proyecto">Anexo</span></button>
                                <div class="btn-group pull-right m-l-5">
                                    <button type="button" class="btn btn-info btn-xs" title="Nueva Solicitud"><i class="fa fa-file-o"></i> <span class="texto-btn-cabecera-formulario-proyecto">Solicitud</span></button>
                                    <button type="button" class="btn btn-info btn-xs dropdown-toggle" data-toggle="dropdown">
                                        <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu" role="menu">
                                        <li><a href="javascript:;" class="btn-solicitud-personal">Personal</a></li>
                                        <li><a href="javascript:;" class="btn-solicitud-material">Material</a></li>
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
                <!--Finalizando cabecera -->

                <!--Empezando Cuerpo-->
                <div id="cuerpo-generales"> 

                    <!--Empezando formulario-->
                    <form id="form-proyecto-iniciado" data-parsley-validate="true">

                        <!--Empezando Fila 1-->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <p>Sistema : <span id="tipo-sistema"></span></p>
                                </div>
                            </div>
                            <div class="col-md-6 text-right">
                                <div class="form-group">
                                    <p>Tipo Proyecto : <span id="tipo-proyecto"></span></p>
                                </div>
                            </div>
                        </div>
                        <!--Finalizando Fila 1-->

                        <!--Empezando Fila 2-->
                        <div class="row">
                            <div class="col-md-12">
                                <label>Observaciones</label>
                                <div class="form-group">
                                    <p id="observaciones-proyecto"></p>
                                </div>
                            </div>
                        </div>
                        <!--Finalizando Fila 2-->

                        <!--Empezando Fila 3-->
                        <div class="row">
                            <div class="col-md-offset-3 col-md-3 text-center" disable>
                                <div class="form-group">
                                    <label >Fecha Inicio</label>
                                    <div id="fecha-inicio-proyecto" class="input-group date" >
                                        <input type="text" class="form-control" placeholder="Inicio" readonly />
                                        <span class="input-group-addon" ><i class="fa fa-calendar"></i></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 text-center">
                                <div class="form-group">
                                    <label >Fecha Final</label>
                                    <div id="fecha-final-proyecto" class="input-group date ">
                                        <input type="text" class="form-control" placeholder="Final" readonly />
                                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--Finalizando Fila 3-->

                    </form>
                    <!--Finalizando formulario-->

                    <!--Empezando botones de acciones-->
                    <div class="row">
                        <div class="col-md-12 text-center m-t-10">                            
                            <button id="btn-guardar-actualizacion" type="button" class="btn btn-success hidden"><i class="fa fa-save"></i> Guardar</button>                            
                            <button id="btn-cancelar-actualizar" type="button" class="btn btn-danger hidden"><i class="fa fa-trash-o"></i> Cancelar</button>
                            <button id="btn-actualizar-proyecto" type="button" class="btn btn-info"><i class="fa fa-refresh"></i> Actualizar</button>
                        </div>
                    </div>
                    <!--Finalizando botones de acciones-->
                </div>
                <!--Finalizando Cuerpo-->

            </div>
            <!--Finalizando Seccion Generales-->

            <!--Empezando Seccion Material-->
            <div id="material" class="tab-pane fade">

                <!--Empezando cabecera -->
                <div id="cabecera-material">

                    <!--Empezando titulo-->
                    <div class="row">
                        <div class="col-xs-7 col-sm-5 col-md-5">
                            <h5 class="titulo-pestaña"><span class="nombre-proyecto"></span> : <span class="nombre-complejo"></span> - <span class="ticket-proyecto text-info"></span></h5>
                        </div>
                        <div class="col-xs-5 col-sm-7 col-md-7 text-right">
                            <h5>
                                <button id="" type="button" class="btn btn-warning btn-xs btn-concluir-proyecto-complejo hidden" title="Concluir Proyecto"><i class="fa fa-check"></i> <span class="texto-btn-cabecera-formulario-proyecto">Concluir Proyecto</span></button>
                                <button id="" type="button" class="btn btn-success btn-xs btn-nuevo-anexo-proyecto" title="Nuevo Anexo"><i class="fa fa-plus"></i> <span class="texto-btn-cabecera-formulario-proyecto">Anexo</span></button>
                                <div class="btn-group pull-right m-l-5">
                                    <button type="button" class="btn btn-info btn-xs" title="Nueva Solicitud"><i class="fa fa-file-o"></i> <span class="texto-btn-cabecera-formulario-proyecto">Solicitud</span></button>
                                    <button type="button" class="btn btn-info btn-xs dropdown-toggle" data-toggle="dropdown">
                                        <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu" role="menu">
                                        <li><a href="javascript:;" class="btn-solicitud-personal">Personal</a></li>
                                        <li><a href="javascript:;" class="btn-solicitud-material">Material</a></li>
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
                <!--Finalizando cabecera -->

                <!--Empezando Cuerpo-->
                <div id="cuerpo-material">                                                            
                </div>
                <!--Finalizando Cuerpo-->
            </div>
            <!--Finalizando Seccion Material-->                

            <!--Empezando Seccion Personal-->
            <div id="personal" class="tab-pane fade">

                <!--Empezando cabecera -->
                <div id="cabecera-personal">

                    <!--Empezando titulo-->
                    <div class="row">
                        <div class="col-xs-7 col-sm-5 col-md-5">
                            <h5 class="titulo-pestaña"><span class="nombre-proyecto"></span> : <span class="nombre-complejo"></span> - <span class="ticket-proyecto text-info"></span></h5>
                        </div>
                        <div class="col-xs-5 col-sm-7 col-md-7 text-right">
                            <h5>
                                <button id="" type="button" class="btn btn-warning btn-xs btn-concluir-proyecto-complejo hidden" title="Concluir Proyecto"><i class="fa fa-check"></i> <span class="texto-btn-cabecera-formulario-proyecto">Concluir Proyecto</span></button>
                                <button id="" type="button" class="btn btn-success btn-xs btn-nuevo-anexo-proyecto" title="Nuevo Anexo"><i class="fa fa-plus"></i> <span class="texto-btn-cabecera-formulario-proyecto">Anexo</span></button>
                                <div class="btn-group pull-right m-l-5">
                                    <button type="button" class="btn btn-info btn-xs" title="Nueva Solicitud"><i class="fa fa-file-o"></i> <span class="texto-btn-cabecera-formulario-proyecto">Solicitud</span></button>
                                    <button type="button" class="btn btn-info btn-xs dropdown-toggle" data-toggle="dropdown">
                                        <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu" role="menu">
                                        <li><a href="javascript:;" class="btn-solicitud-personal">Personal</a></li>
                                        <li><a href="javascript:;" class="btn-solicitud-material">Material</a></li>
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
                <!--Finalizando cabecera -->

                <!--Empezando Cuerpo-->
                <div id="cuerpo-personal">
                </div>
                <!--Finalizando Cuerpo-->

            </div>
            <!--Finalizando Seccion Personal-->

            <!--Empezando Seccion Tareas-->
            <div id="tareas" class="tab-pane fade">

                <!--Empezando cabecera tareas-->
                <div id="cabecera-tareas" >

                    <!--Empezando titulo-->
                    <div class="row">
                        <div class="col-xs-7 col-sm-5 col-md-5">
                            <h5 class="titulo-pestaña"><span class="nombre-proyecto"></span> : <span class="nombre-complejo"></span> - <span class="ticket-proyecto text-info"></span></h5>
                        </div>
                        <div class="col-xs-5 col-sm-7 col-md-7 text-right">
                            <h5>
                                <button id="" type="button" class="btn btn-warning btn-xs btn-concluir-proyecto-complejo hidden" title="Concluir Proyecto"><i class="fa fa-check"></i> <span class="texto-btn-cabecera-formulario-proyecto">Concluir Proyecto</span></button>
                                <button id="" type="button" class="btn btn-success btn-xs btn-nuevo-anexo-proyecto" title="Nuevo Anexo"><i class="fa fa-plus"></i> <span class="texto-btn-cabecera-formulario-proyecto">Anexo</span></button>
                                <div class="btn-group pull-right m-l-5">
                                    <button type="button" class="btn btn-info btn-xs" title="Nueva Solicitud"><i class="fa fa-file-o"></i> <span class="texto-btn-cabecera-formulario-proyecto">Solicitud</span></button>
                                    <button type="button" class="btn btn-info btn-xs dropdown-toggle" data-toggle="dropdown">
                                        <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu" role="menu">
                                        <li><a href="javascript:;" class="btn-solicitud-personal">Personal</a></li>
                                        <li><a href="javascript:;" class="btn-solicitud-material">Material</a></li>
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
                <!--Finalizando cabecera tareas-->

                <!--Empezando cabecera seguimiento tarea-->
                <div id="cabecera-tarea" class="hidden" >

                    <!--Empezando titulo-->
                    <div class="row">
                        <div class="col-xs-7 col-sm-5 col-md-5">
                            <h5 class="titulo-pestaña"><span class="tarea-nombre"></span> : <span class="tarea-porcentaje text-danger"></span> - <span class="tarea-area"></span></h5>
                        </div>
                        <div class="col-xs-5 col-sm-7 col-md-7 text-right">
                            <h5>
                                <button id="btn-regresar-tareas-lista" type="button" class="btn btn-info btn-xs btns-informacion-tarea" title="Regresar Tareas"><i class="fa fa-reply"></i> <span class="ocultar-elemento">Regresar Tareas</span></button>
                                <button id="btn-actualizar-tarea" type="button" class="btn btn-success btn-xs btns-informacion-tarea" title="Actualizar"><i class="fa fa-refresh"></i> <span class="ocultar-elemento">Actualizar </span></button>
                                <button id="btn-confirmar-eliminar-tarea" type="button" class="btn btn-danger btn-xs btns-informacion-tarea" title="Eliminar"><i class="fa fa-trash-o"></i> <span class="ocultar-elemento">Eliminar </span></button>
                                <button id="btn-confirmar-actualizar-tarea" type="button" class="btn btn-success btn-xs btns-editar-tarea hidden" title="Guardar Cambios"><i class="fa fa-floppy-o"></i> <span class="">Guardar </span></button>
                                <button id="btn-cancelar-actualizar-tarea" type="button" class="btn btn-danger btn-xs btns-editar-tarea hidden" title="Cancelar">Cancelar</button>
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
                <!--Finalizando cabecera seguimiento tarea-->

                <!--Empezando cabecera seguimiento actividad-->
                <div id="cabecera-actividad" class="hidden" >

                    <!--Empezando titulo-->
                    <div class="row">
                        <div class="col-xs-8 col-sm-8 col-md-8">
                            <h6 class="titulo-pestaña">Capturo: <span class="actividad-capturo f-w-700"></span> -- Fecha de Captura : <span class="actividad-fecha-captura f-w-700"></span></h6>
                        </div>
                        <div class="col-xs-4 col-sm-4 col-md-4 text-right">
                            <h6>
                                <button id="btn-regresar-tarea" type="button" class="btn btn-info btn-xs " title="Regresar a tarea"><i class="fa fa-reply"></i> <span class="ocultar-elemento">Regresar A Tarea</span></button>
                            </h6>
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
                <!--Finalizando cabecera seguimiento actividad-->

                <!--Empezando cabecera seguimiento material utilizado en la actividad-->
                <div id="cabecera-material-actividad" class="hidden" >

                    <!--Empezando titulo-->
                    <div class="row">
                        <div class="col-xs-8 col-sm-8 col-md-8">
                            <h5 class="f-w-700">Nodo Capturado : <span id="nombre-nodo"></span></h5>
                        </div>
                        <div class="col-xs-4 col-sm-4 col-md-4 text-right">
                            <h5>
                                <button id="btn-regresar-dia-actividad" type="button" class="btn btn-info btn-xs " title="Regresar a actividad"><i class="fa fa-reply"></i> <span class="ocultar-elemento">Regresar a Actividad</span></button>
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
                <!--Finalizando cabecera seguimiento material utilizado en la actividad-->

                <!--Empezando Cuerpo-->
                <div id="cuerpo-tareas">

                    <!--Empezando seccion tabla tareas-->
                    <div class="seccion-tabla-tareas">
                        <!--Empezando fila-1 -->
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
                        <!--Finalizando fila-1 -->

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
                                                <th class="not-mobile-l">Lider</th>
                                                <th class="all">Fecha Inicio</th>
                                                <th class="not-mobile-l">Fecha Fin</th>
                                                <th class="all">Avance</th>
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
                    </div>
                    <!--Finalizando seccion tabla tareas-->

                    <!--Empezando contenedor diagrama de gantt-->
                    <div id="contenedor-gantt" class="hidden">
                        <div class="row">
                            <div class="col-md-12">
                                <div id="gantt_here" style='width:100%; height:300px;'></div>                                
                            </div>
                        </div>
                    </div>
                    <!--Finalizando contenedor diagrama de gantt-->

                    <!--Empezando seccion datos tarea-->
                    <div id="seccion-tarea" class="hidden">

                        <!--Empezando formulario tarea-->
                        <div class="row">
                            <div id="formulario-tarea" class="col-md-12"></div>
                        </div>
                        <!--Empezando formulario tarea -->

                        <!--Empezando contenedor tabla actividades-->
                        <div id="contenedor-tabla-actividades">

                            <!--Empezando titulo tabla actividad-->
                            <div class="row">
                                <div class="col-md-12">
                                    <h5 class="f-w-700">Días de actividad</h5>
                                </div>                            
                            </div>
                            <!--Finalizando titulo tabla actividad-->

                            <!--Empezando Separador-->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="underline m-b-15"></div>
                                </div>
                            </div>
                            <!--Finalizando Separador-->

                            <!--Empezando tabla actividades-->
                            <div class="row">
                                <div class="col-md-12">
                                    <table id="data-table-actividades" class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%">
                                        <thead>
                                            <tr>                                    
                                                <th class="never">Id</th>                                                                            
                                                <th class="all">Fecha</th>
                                                <th class="not-mobile-l">Descripción</th>
                                                <th class="all">Capturo</th>
                                            </tr>
                                        </thead>
                                        <tbody>                                    
                                        </tbody>
                                    </table> 
                                </div>
                            </div>
                            <!--Finalizando tabla actividades-->

                            <!--Empezando alerta mensaje--> 
                            <div class="row">
                                <div class="col-md-12 m-t-15">                                
                                    <div class="alert alert-info fade in ">
                                        <strong>Informacion : </strong>
                                        Para var la información de la actividad solo tiene que dar click sobre fila.
                                    </div>
                                </div>
                            </div>
                            <!--Finalizando alerta mensaje--> 
                        </div>
                        <!--Finalizando contenedor tabla actividades-->
                    </div>
                    <!--Finalizando seccion datos tarea-->

                    <!--Empezando seccion datos dia de actividad-->
                    <div id="seccion-actividad" class="hidden">

                        <!--Empezando formulario actividad-->
                        <div class="row">
                            <div id="formulario-actividad" class="col-md-12"></div>
                        </div>
                        <!--Empezando formulario actividad -->

                        <!--Empezando fila 4-->
                        <div class="row archivos-subidos hidden">
                            <div class="col-md-12">
                                <label>Evidencia</label>
                                <div class="evidenciasSubidas">                                
                                </div>
                            </div>
                        </div>
                        <!--Finalizando fila 4-->

                        <!--Empezando titulo tabla material utilizado-->
                        <div class="row nodos-actividad">
                            <div class="col-md-12">
                                <h4 class="f-w-700">Nodo Realizados</h4>
                            </div>
                        </div>
                        <!--Finalizando titulo material utilizado-->

                        <!--Empezando Separador-->
                        <div class="row nodos-actividad">
                            <div class="col-md-12">
                                <div class="underline m-b-15"></div>
                            </div>
                        </div>
                        <!--Finalizando Separador-->

                        <!--Empezando contenedor tabla material utilizado-->
                        <div id="contenedor-tabla-material-utilizado" class="nodos-actividad">

                            <!--Empezando tabla nodos de actividad-->
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
                            <!--Finalizando tabla nodos de actividad-->

                            <!--Empezando alerta mensaje--> 
                            <div class="row">
                                <div class="col-md-12 m-t-15">                                
                                    <div class="alert alert-info fade in ">
                                        <strong>Informacion : </strong>
                                        Para var la información del material solo tiene que dar click sobre fila.
                                    </div>
                                </div>
                            </div>
                            <!--Finalizando alerta mensaje--> 
                        </div>
                        <!--Finalizando contenedor material utilizado-->

                    </div>
                    <!--Finalizando seccion datos dia de actividad-->

                    <!--Empezando seccion datos de material utilizado en la actividad-->
                    <div id="seccion-material-actividad" class="hidden">
                        <!--Empezando formulario actividad-->
                        <!--                        <div class="row">
                                                    <div id="formulario-material-actividad" class="col-md-12"></div>
                                                </div>-->
                        <!--Empezando formulario actividad -->

                        <!--Empezando fila 1-->
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
                        <!--Finalizando fila 1-->

                        <!--Empezando fila 2-->
                        <div id="file-evidencia-subida" class="row m-t-15 info-nodo">
                            <div class="col-md-12">
                                <label>Evidencia</label>
                                <div class="evidenciasMaterialUtilizado">                                
                                </div>
                            </div>
                        </div>
                        <!--Finalizando fila 2-->

                    </div>
                    <!--Finalizando seccion datos de material utilizado en la actividad-->
                </div>
                <!--Finalizando Cuerpo-->

            </div>
            <!--Finalizando Seccion Tareas-->

        </div>
        <!-- Finalizando contenido del panel  -->

    </div>
    <!-- Finalizando panel para formularios del los proyectos -->

</div>
<!-- Finalizando #contenido -->