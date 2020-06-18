<!-- Empezando #contenido -->
<div id="content" class="content">
    <!-- Empezando titulo de la pagina -->
    <h1 class="page-header">Poliza</h1>
    <!-- Finalizando titulo de la pagina -->

<!-- Empezando panel nuevo proyecto-->
    <div class="panel panel-inverse">
        <!--Empezando cabecera del panel-->
        <div class="panel-heading">
            <div class="panel-heading-btn">
                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>                            
                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>                            
            </div>
            <h4 class="panel-title">Generar Proyecto</h4>
        </div>
        <!--Finalizando cabecera del panel-->
        <!--Empezando cuerpo del panel-->
        <div class="panel-body">
            <!--Inicia formulario para nuevo proyecto-->
            <div id="seccionDatosProyecto" class="panel panel-default borde-sombra" data-sortable-id="ui-widget-10">
                <div class="panel-heading bg-cabecera-subpanel">
                    <div class="panel-heading-btn">                                                
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>                        
                    </div>
                    <h3 class="panel-title">DATOS DEL PROYECTO</h3>
                </div>
                <div class="panel-body ">                    
                    <form class="margin-bottom-0" id="formNuevoProyecto" data-parsley-validate="true" >
                        <!--Empezando campos Tipo Proyecto, Nombre Proyecto, Ticket-->
                        <div class="row ">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="nombreProyecto">Tipo Proyecto</label>
                                    <select id="selectTipoProyecto" class="form-control" style="width: 100%" data-parsley-required="true">
                                        <option value="">Seleccionar</option>
                                        <?php
                                        foreach ($datos['TiposProyectos'] as $item) {
                                            echo '<option value="' . $item['Id'] . '">' . $item['Nombre'] . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="nombreProyecto">Nombre del Proyecto</label>
                                    <input id="inputNomProyecto" type="text" class="form-control nuevoProyecto" placeholder="Nombre del proyecto" data-parsley-required="true" disabled/>
                                </div>
                            </div>                        
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="ticketProyecto">Ticket</label>
                                    <input id="inputTicketProyecto" type="text" data-id="" class="form-control" placeholder="Numero de ticket" disabled/>
                                </div>
                            </div>                        
                        </div>
                        <!--Finalizando campos Tipo Proyecto, Nombre Proyecto, Ticket-->

                        <!--Empezando campos Complejo y Lideres-->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="selectComplejo">Complejo</label>
                                    <select id="selectComplejo" class="form-control nuevoProyecto" style="width: 100%" multiple="multiple" data-parsley-required="true" disabled>
                                        <?php
                                        foreach ($datos['Sucursales'] as $item) {
                                            echo '<option value="' . $item['Id'] . '">' . $item['Nombre'] . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="lideresProyecto">Líderes</label>
                                    <select id="selectLideres" class="form-control nuevoProyecto" style="width: 100%" multiple="multiple" data-parsley-required="true" disabled>                                    
                                        <?php
                                        foreach ($datos['Lideres'] as $item) {
                                            echo '<option value="' . $item['Id'] . '">' . $item['Nombre'] . '</option>';
                                        }
                                        ?>
                                    </select>                                            
                                </div>
                            </div>
                        </div>
                        <!--Finalizando campos Complejo y Lideres-->

                        <!--Empezando campos observaciones -->
                        <div class="row">                            
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="observacionesProyecto">Observaciones</label>                                            
                                    <textarea id="textareaObservaciones" class="form-control nuevoProyecto" placeholder="Ingresa tus observaciones aqui ...." rows="3" disabled></textarea>
                                </div>
                            </div>   
                        </div>
                        <!--Finalizando campos observaciones -->

                        <!--Empezando campos fecha inicio y fecha fin -->
                        <div class="row">
                            <div class="col-md-offset-3 col-md-3 text-center">
                                <div class="form-group">
                                    <label for="control-label"> Fecha inicio </label>
                                    <div id="fecha-inicial" class="input-group date calendario"  >
                                        <input type="text" class="form-control nuevoProyecto" placeholder="Fecha Inicio" readonly disabled/>
                                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                    </div>
                                </div>
                            </div> 
                            <div class="col-md-3 text-center ">
                                <div class="form-group">
                                    <label for="control-label"> Fecha Termino </label>
                                    <div id="fecha-termino" class="input-group date calendario" >
                                        <input type="text" class="form-control nuevoProyecto" placeholder="Fecha Termino" readonly disabled/>
                                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--Finalizando campos fecha inicio y fecha fin-->

                        <!--Empezando mensaje de eror de fechas-->
                        <div class="row">
                            <div class="col-md-offset-3 col-md-6">
                                <div class="errorDiferenciaFecha"></div>
                            </div>
                        </div>
                        <!--Finalizando mensaje de eror de fechas-->

                        <!--Empezando Botones -->
                        <div class="row m-t-20">
                            <!--Separador-->
                            <div class="col-md-12">
                                <div class="underline m-b-15 m-t-15"></div>
                            </div>                            
                            <div id="btnSeccionNuevoProyecto" class="col-md-offset-4 col-md-4 text-center">
                                <button type="button" class="btn btn-sm btn-default m-r-5 nuevoProyecto" id="btnGenerarProyecto" disabled>Generar</button>
                                <button type="reset" class="btn btn-sm btn-danger nuevoProyecto" id="btnLimpiarGenerarProyecto" disabled>Limpiar</button>
                            </div>
                            <div class="col-md-12 m-t-15"></div>
                            <div id="btnSeccionActualizarProyecto" class="col-md-offset-2 col-md-8 text-center hidden">                          
                                <button type="button" class="btn btn-sm btn-primary m-r-5" id="btnGuardarActualizar">Guardar</button>
                                <button type="button" class="btn btn-sm btn-danger m-r-5" id="btnCancelarActualizar">Cancelar</button>                                
                            </div>
                            <div id="btnSeccionAccionesProyecto" class="col-md-offset-1 col-md-10 text-center hidden">                                        
                                <button type="button" class="btn btn-sm btn-primary m-r-5" id="btnActualizarProyecto">Actualizar Proyecto</button>
                                <button type="button" class="btn btn-sm btn-success m-r-5 hidden" id="btnIniciarProyecto">Iniciar Proyecto</button>
                                <button type="button" class="btn btn-sm btn-danger m-r-5" id="btnEliminarProyecto">Eliminar Proyecto</button>
                                <button type="button" class="btn btn-sm btn-default m-r-5" id="btnNuevoProyecto">Proyecto Nuevo</button>
                            </div>
                        </div>
                        <!--Finalizando Botones-->
                    </form>
                </div>
            </div>
            <!-- Finaliza formulario para nuevo proyecto-->
                    </div>
        <!--Finalizando cuerpo del panel-->
    </div>
    <!-- Finalizando panel nuevo proyecto -->
    <!-- Empezando panel proyectos sin iniciar-->
    <div id="proyecto-sin-iniciar" class="panel panel-inverse">
        <div class="panel-heading">
            <div class="panel-heading-btn">
                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>                            
                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>                            
            </div>
            <h4 class="panel-title">Proyectos Sin Iniciar</h4>
        </div>
        <div class="panel-body">
            <!--Empezando tabla-->
            <div class="row">                           
                <div class="col-md-12">
                    <table id="data-table-sinIniciar" class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%">
                        <thead>
                            <tr>
                                <th class="none">Id</th>
                                <th class="all">Ticket</th>
                                <th class="all">Proyecto</th>
                                <th class="all">Complejo</th>
                                <th class="all">Ciudad</th>
                                <th class="all">Fecha Inicio</th>
                                <th class="all">Fecha Fin</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($datos['ProyectosSinAtender'] as $key => $value) {
                                echo '<tr>';
                                echo '<td>' . $value['Id'] . '</td>';
                                echo '<td>' . $value['Ticket'] . '</td>';
                                echo '<td>' . $value['Nombre'] . '</td>';
                                echo '<td>' . $value['Sucursal'] . '</td>';
                                echo '<td>' . $value['Estado'] . '</td>';
                                echo '<td>' . $value['FechaInicio'] . '</td>';
                                echo '<td>' . $value['FechaTermino'] . '</td>';
                                echo '</tr>';
                            }
                            ?>
                        </tbody>

                    </table>
                </div> 
            </div>
            <!--Finalizando tabla-->
        </div>
    </div>
    <!-- Finalizando panel proyectos sin iniciar -->

</div>
<!-- Finalizando #contenido -->

