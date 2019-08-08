<!-- Empezando #contenido -->
<div id="content" class="content">
    <!-- Empezando titulo de la pagina -->
    <h1 class="page-header">Búsqueda</h1>
    <!-- Finalizando titulo de la pagina -->

    <!-- Empezando panel filtros de búsqueda-->
    <div id="seccion-buscar" class="panel panel-inverse borde-sombra">
        <!--Empezando cabecera del panel-->
        <div class="panel-heading">
            <div class="panel-heading-btn">
                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>                            
                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>                            
            </div>
            <h4 class="panel-title">Búsqueda de Tickets, Solicitudes y Servicios</h4>
        </div>
        <!--Finalizando cabecera del panel-->
        <!--Empezando cuerpo del panel-->
        <div class="panel-body">
            <div class="row m-t-20">
                <div class="col-md-12">                            
                    <fieldset>
                        <legend class="pull-left width-full f-s-17">Asistente de Búsqueda.</legend>
                    </fieldset>  
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 col-xs-12">                    
                    <div class="panel panel-success overflow-visible">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                <a class="accordion-toggle accordion-toggle-styled collapsed" data-toggle="collapse" data-parent="#accordion" href="#paso1" aria-expanded="false">
                                    <i class="fa fa-plus-circle pull-right"></i> 
                                    #1. Seleccionar las columnas a mostrar
                                </a>
                            </h3>
                        </div>
                        <div id="paso1" class="panel-collapse collapse in" aria-expanded="true">
                            <div class="panel-body">
                                <div class="form-group">
                                    <label>Columnas disponibles</label>
                                    <select id="listaColumnas" class="form-control" style="width: 100%" multiple="multiple">
                                        <option value="ts.Id">Solicitud</option>
                                        <option value="ts.Ticket">Ticket</option>
                                        <option value="ts.Folio">Folio</option>
                                        <option value="cs.IdRegionCliente">Zona / Región</option>
                                        <option value="tst.IdSucursal">Sucursal</option>
                                        <option value="ts.IdEstatus">Estatus de Solicitud</option>
                                        <option value="ts.IdDepartamento">Departamento de Solicitud</option>
                                        <option value="ts.IdPrioridad">Prioridad de Solicitud</option>
                                        <option value="ts.FechaCreacion">Fecha de Solicitud</option>
                                        <option value="ts.FechaRevision">Fecha de Revisión de Solicitud</option>
                                        <option value="ts.FechaConclusion">Fecha de Cierre de Solicitud</option>
                                        <option value="ts.Solicita">Solicita</option>
                                        <option value="tsi.Asunto">Asunto de Solicitud</option>
                                        <option value="tsi.Descripcion">Descripcion de Solicitud</option>
                                        <option value="tst.IdTipoServicio">Tipo de Servicio</option>
                                        <option value="tst.IdEstatus">Estatus del Servicio</option>
                                        <option value="tst.Solicita">Personal que Genera el Servicio</option>
                                        <option value="tst.Atiende">Atiende el Servicio</option>
                                        <option value="tst.FechaCreacion">Fecha del Servicio</option>
                                        <option value="tst.FechaInicio">Fecha de Inicio del Servicio</option>
                                        <option value="tst.FechaConclusion">Fecha de Cierre del Servicio</option>
                                        <option value="tst.Descripcion">Descripción del Servicio</option>
                                    </select>                                                    
                                    <input type="checkbox" id="checkboxColumnasDisponibles"/>Todas las columnas
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-success overflow-visible">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                <a class="accordion-toggle accordion-toggle-styled collapsed" data-toggle="collapse" data-parent="#accordion" href="#paso2" aria-expanded="false">
                                    <i class="fa fa-plus-circle pull-right"></i> 
                                    #2. Opciones de filtro
                                </a>
                            </h3>
                        </div>
                        <div id="paso2" class="panel-collapse collapse in" aria-expanded="true">
                            <div class="panel-body">
                                <form id="formFiltrosFechas">
                                    <fieldset>
                                        <legend class="f-s-15">Filtro de Fechas</legend>                                        
                                        <div class="row">
                                            <div class="col-md-4 col-xs-12">      
                                                <div class="form-group">
                                                    <label>Filtros disponibles</label>
                                                    <select id="selectFiltroFechas" class="form-control" style="width: 100%">                                                                                                       
                                                        <option value="">Seleccionar</option>
                                                        <option value="ts.FechaCreacion">Fecha de Solicitud</option>
                                                        <option value="ts.FechaRevision">Fecha de Revisión de Solicitud</option>
                                                        <option value="ts.FechaConclusion">Fecha de Cierre de Solicitud</option>                                                   
                                                        <option value="tst.FechaCreacion">Fecha del Servicio</option>
                                                        <option value="tst.FechaInicio">Fecha de Inicio del Servicio</option>
                                                        <option value="tst.FechaConclusion">Fecha de Cierre del Servicio</option>                                                    
                                                    </select>
                                                </div>
                                            </div>                                            
                                            <div class="col-md-4 col-xs-12">      
                                                <div class="form-group">
                                                    <label><input type="radio" name="radioFiltroFecha" value="rango"> Rango</label>    <br />                                                    
                                                    <div class="col-md-6 col-xs-6">
                                                        <div class="form-group">
                                                            <div class='input-group date' id='desde'>
                                                                <input type='text' id="txtDesde" class="form-control" value="" disabled=""/>
                                                                <span class="input-group-addon">
                                                                    <span class="glyphicon glyphicon-calendar"></span>
                                                                </span>
                                                            </div>
                                                        </div>                
                                                    </div>                                                        
                                                    <div class="col-md-6 col-xs-6">
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
                                            <div class="col-md-4 col-xs-12">      
                                                <div class="form-group">
                                                    <label><input type="radio" name="radioFiltroFecha" value="durante"> Durante</label>
                                                    <select id="selectFiltroDurante" class="form-control" style="width: 100%" disabled="">                                                                                                       
                                                        <option value="">Seleccionar</option>
                                                        <option value="btn-anio-pasado">Año pasado</option>
                                                        <option value="btn-trimestre-pasado">Trimestre pasado</option>
                                                        <option value="btn-mes-pasado">Mes pasado</option>
                                                        <option value="btn-semana-pasado">Semana pasada</option>
                                                        <option value="btn-anio-presente">Este año</option>
                                                        <option value="btn-mes-presente">Este mes</option>
                                                        <option value="btn-anio-anterior">Último año</option>                                                            
                                                        <option value="btn-trimestre-anterior">Último trimestre</option>                                                            
                                                        <option value="btn-mes-anterior">Último mes</option>                                                            
                                                        <option value="btn-semana-anterior">Última semana</option>                                                            
                                                    </select>
                                                </div>
                                            </div>
                                        </div>                                        
                                    </fieldset>
                                </form>
                                <form id="formFiltrosVarios">
                                    <fieldset>
                                        <legend class="f-s-15">Filtro Avanzado</legend>                                        
                                        <div class="row">
                                            <div class="col-md-4 col-xs-10">
                                                <div class="form-group">
                                                    <label>Campos de filtro</label>
                                                    <select id="selectCamposFiltros" class="form-control" style="width: 100%">                                                                                                       
                                                        <option value="">Seleccionar</option>                                                                                                 
                                                        <option value="ts.Id" data-tipo="tag">Solicitud</option>
                                                        <option value="ts.Ticket" data-tipo="tag">Ticket</option>
                                                        <option value="ts.Folio" data-tipo="tag">Folio</option>
                                                        <option value="cs.IdRegionCliente" data-tipo="cat">Zona / Región</option>
                                                        <option value="tst.IdSucursal" data-tipo="cat">Sucursal</option>
                                                        <option value="ts.IdEstatus" data-tipo="cat">Estatus de Solicitud</option>
                                                        <option value="ts.IdDepartamento" data-tipo="cat">Departamento de Solicitud</option>
                                                        <option value="ts.IdPrioridad" data-tipo="cat">Prioridad de Solicitud</option>                                                        
                                                        <option value="ts.Solicita" data-tipo="cat">Solicita</option>
                                                        <option value="tsi.Asunto" data-tipo="text">Asunto de Solicitud</option>
                                                        <option value="tsi.Descripcion" data-tipo="text">Descripcion de Solicitud</option>
                                                        <option value="tst.IdTipoServicio" data-tipo="cat">Tipo de Servicio</option>
                                                        <option value="tst.IdEstatus" data-tipo="cat">Estatus del Servicio</option>
                                                        <option value="tst.Solicita" data-tipo="cat">Personal que Genera el Servicio</option>
                                                        <option value="tst.Atiende" data-tipo="cat">Atiende el Servicio</option>                                                        
                                                        <option value="tst.Descripcion" data-tipo="text">Descripción del Servicio</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-2 col-xs-2">
                                                <div class="form-group">
                                                    <label>Criterio</label>
                                                    <div id="valorCriterio"></div>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-xs-12">
                                                <div class="form-group">
                                                    <label>Valor</label>
                                                    <div id="valorFiltro"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12 col-xs-12 errorFiltroAvanzado"></div>
                                            <div class="col-md-12 col-xs-12 text-center">
                                                <a href="javascript:;" id="btnAgregarFiltro" class="btn btn-sm btn-success">Agregar filtro avanzado</a>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12 col-xs-12">
                                                <table id="tableFiltrosAvanzados" class="table table-hover m-t-20 hidden">
                                                    <thead>
                                                        <tr>
                                                            <th style="width: 15%">Eliminar</th>
                                                            <th style="width: 85%">Filtro</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody></tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </fieldset>
                                </form>
                            </div>
                        </div>
                    </div>        
                    <div class="panel panel-success overflow-visible">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                <a class="accordion-toggle accordion-toggle-styled collapsed" data-toggle="collapse" data-parent="#accordion" href="#paso3" aria-expanded="false">
                                    <i class="fa fa-plus-circle pull-right"></i> 
                                    #3. Ejecutar búsqueda
                                </a>
                            </h3>
                        </div>
                        <div id="paso3" class="panel-collapse collapse in" aria-expanded="true">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-md-12 col-xs-12 text-center">
                                        <a href="javascript:;" id="btnBuscar" class="btn btn-success"><i class="fa fa-search"></i> Buscar</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>          
            <!--Finalizando cuerpo del panel-->
        </div>
        <!-- Finalizando panel nuevo proyecto -->   

    </div>
    <!-- Finalizando filtros de búsqueda -->


    <!-- Empezando panel reporte-->
    <div id="seccion-reporte" class="panel panel-inverse borde-sombra hidden">
        <!--Empezando cabecera del panel-->
        <div class="panel-heading">
            <div class="panel-heading-btn">
                <!-- Single button -->
                <div class="btn-group">
                    <button type="button" class="btn btn-warning btn-xs dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Acciones <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu">
                        <li id="btnExportarExcel"><a href="#"><i class="fa fa-file-excel-o"></i> Exportar Excel</a></li>
                    </ul>
                </div>
                <label id="btnRegresarReporte" class="btn btn-success btn-xs">
                    <i class="fa fa fa-reply"></i> Regresar
                </label>   
            </div>
            <h4 class="panel-title">Resultado de búsqueda</h4>
        </div>
        <!--Finalizando cabecera del panel-->
        <!--Empezando cuerpo del panel-->
        <div class="panel-body">
            <div class="row m-t-20">
                <div class="col-md-12">                            
                    <fieldset>
                        <legend class="pull-left width-full f-s-17">Resultado de Búsqueda.</legend>
                    </fieldset>  
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 col-xs-12">
                    <div class="table-responsive" id="divResultadoBusqueda"></div>
                </div>
            </div>          
            <!--Finalizando cuerpo del panel-->
        </div>
        <!-- Finalizando panel nuevo proyecto -->   

    </div>
    <!-- Finalizando reporte -->


    <!-- Empezando panel detalles-->
    <div id="seccion-detalles" class="panel panel-inverse borde-sombra panel-with-tabs hidden">
        <!--Empezando cabecera del panel-->
        <div class="panel-heading">
            <div class="btn-group pull-right" data-toggle="buttons"></div>
            <div class="panel-heading-btn">
                <!-- Single button -->
                <div class="btn-group">
                    <button type="button" class="btn btn-warning btn-xs dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Acciones <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu">
                        <li id="btnCancelarServicioSeguimiento"><a href="#"><i class="fa fa-times"></i> Cancelar Servicio</a></li>
                        <li id="btnExportarPdf" data-id-servicio=""><a href="#"><i class="fa fa-file-pdf-o"></i> Exportar Pdf</a></li>
                        <li id="btnReasignarServicio"><a href="#"><i class="fa fa-mail-reply-all"></i> Reasignar Servicio</a></li>
                        <?php
                        if (in_array('177', $usuario['PermisosAdicionales']) || in_array('177', $usuario['Permisos']) ||
                                in_array('182', $usuario['PermisosAdicionales']) || in_array('182', $usuario['Permisos'])) {
                            ?>
                            <li id="btnRechazarServicioConcluido" class="hidden" data-id-servicio=""><a href="#"><i class="fa fa-times-circle"></i> Rechazar Servicio</a></li>                        
                            <?php
                        }
                        if (in_array('217', $usuario['Permisos'])) {
                            echo '<li id="btnSubirInfoSD" data-id-servicio=""><a href="#"><i class="fa fa-cloud-upload"></i> Subir Información SD</a></li>';
                        }
                        ?>
                    </ul>
                </div>
                <label id="btnRegresarDetalles" class="btn btn-success btn-xs">
                    <i class="fa fa fa-reply"></i> Regresar
                </label>   
            </div>
            <div class="tab-overflow overflow-right">
                <ul class="nav nav-tabs nav-tabs-inverse">                    
                    <li class="active"><a href="#Solicitud" data-toggle="tab">Solicitud</a></li>                    
                    <li><a href="#Servicio" data-toggle="tab">Servicio</a></li>                    
                    <li><a href="#Historial" data-toggle="tab">Historial</a></li>                    
                    <li><a href="#Conversacion" data-toggle="tab">Conversación</a></li>                    
                </ul>
            </div>            
        </div>
        <!--Finalizando cabecera del panel-->
        <div class="tab-content">

            <!--Empezando la seccion de solicitud-->
            <div class="tab-pane fade active in" id="Solicitud">                
                <div class="panel-body" id="panel-detalles-solicitud"></div>
            </div>
            <!--Finalizando la seccion de solicitud-->
            <!--Empezando la seccion de servicio-->
            <div class="tab-pane fade" id="Servicio">
                <div class="panel-body" id="panel-detalles-servicio"></div>
            </div>
            <!--Finalizando la seccion de servicio-->
            <!--Empezando la seccion de historial-->
            <div class="tab-pane fade" id="Historial">
                <div class="panel-body" id="panel-historial-servicio"></div>
            </div>
            <!--Finalizando la seccion de historial-->
            <!--Empezando la seccion de servicio-->
            <div class="tab-pane fade" id="Conversacion">
                <div class="panel-body" id="panel-conversacion-servicio"></div>
            </div>
            <!--Finalizando la seccion de servicio-->
        </div>
    </div>
    <!-- Finalizando detalles -->
