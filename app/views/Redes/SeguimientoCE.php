<div id="contentServiciosGeneralesRedes" class="content">
    <!-- Empezando titulo de cabecera -->
    <h1 class="page-header">Servicios Generales de Redes</h1>
    <!-- Finalizando titulo de cabecera -->
    <!--Empieza contenido general-->
    <div id="panelServicios" class="panel panel-inverse">
        <!--Empieza titulo pagina-->
        <div class="panel-heading">
            <div class="panel-heading-btn">
                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
            </div>
            <h4 class="panel-title">Seguimiento</h4>
        </div>
        <!--Finaliza titulo pagina-->
        <!--Empieza Panel -->
        <div class="panel-body row">
            <!--Empieza el encabezado del panel-->

            <!--Finaliza el encabezado del panel-->
            <!--Empieza tabla de servicios-->
            <div class="col-md-12">
                <div class="table-responsive">
                    <table id="table-ServiciosGeneralesRedes" class="table table-hover table-striped table-bordered" style="cursor:pointer" width="100%">
                        <thead>
                            <tr>
                                <th class="all">Ticket</th>
                                <th class="all">Servicio</th>
                                <th class="all"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th>Platino</th>
                                <th>Platino</th>
                                <th>Platino</th>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <!--Finaliza tabla de servicios-->
        </div>
        <!--Finaliza Panel -->
    </div>
    <!--Finaliza contenido general-->
</div>


<div id="contentServiciosRedes">
    <!--Empieza contenido general-->
    <div id="content" class="content">
        <div id="panelServiciosGeneralesRedes" class="panel panel-inverse">

            <!--Empieza titulo pagina-->
            <div class="panel-heading">
                <div class="panel-heading-btn">
                    <label id="btnRegresar" class="btn btn-success btn-xs">
                        <i class="fa fa-reply"></i> Regresar
                    </label>
                </div>
                <h4 class="panel-title">Seguimiento</h4>
            </div>
            <!--Finaliza titulo pagina-->

            <!--Empieza Panel -->
            <div class="panel-body row">
                <!--Empieza subtitulo y accion de botones-->
                <div class="col-md-12">
                    <div class="panel-heading-btn">
                        <label id="btnEditarServicio" class="btn btn-primary btn-sm">
                            <i class="fa fa-pencil"></i> Editar Servicio
                        </label>
                        <label id="btnAgregarFolio" class="btn btn-warning btn-sm">
                            <i class="fa fa-plus"></i> Agregar Folio
                        </label>
                    </div>
                    <h4>Información del Servicio</h4>
                    <div class="underline m-b-15 m-t-15"></div>
                </div>
                <!--Finaliza subtitulo y accion de botones-->
                <!--Empieza detalles de servicio-->
                <div class="col-md-12">
                    <div id="infoServicio" class="col-md-12">
                        <div class="col-md-12 row">
                            <div class="col-md-12">
                                <div class="col-md-8">
                                    <label>Fecha de Servicio: </label>
                                </div>
                                <div class="col-md-4">
                                    <label>Ticket: </label>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="col-md-8">
                                    <label>Atendido por: </label>
                                </div>
                                <div class="col-md-4">
                                    <label>Solicitud: </label>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Descripción del Servicio</label>
                                    <textarea id="textareaDescripcion" class="form-control" rows="2" disabled></textarea>
                                </div>
                            </div>
                        </div>
                        <!--Empieza seccion de mas detalles-->
                        <div class="col-md-12 row">
                            <div class="form-group">
                                <label id="masDetalles" style="cursor:pointer">+ Mas Detalles</label>
                                <label id="menosDetalles" class="hidden" style="cursor:pointer">- Menos Detalles</label>
                                <div id="detallesServicio" class="hidden">
                                    <div class="col-md-12">
                                        <div class="col-md-8">
                                            <label>Solicita: </label>
                                        </div>
                                        <div class="col-md-4">
                                            <label>Fecha Solicitud: </label>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Descripción Solicitud</label>
                                            <textarea id="textareaDescripcion" class="form-control" rows="2" disabled></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--Finaliza seccion de mas detalles-->
                    </div>
                    <!--Empieza seccion de agregar Folio-->
                    <div id="agregarFolio" class="col-md-6 hidden" style="background: #F9EAC6">
                        <div class="col-md-12">
                            <br>
                            <form id="folio" data-parsley-validate="true" enctype="multipart/form-data">
                                <div class="form-group">
                                    <label class="control-label col-md-2">Folio :</label>
                                    <div class="col-md-8">
                                        <input id="addFolio" class="form-control" data-parsley-required="true" />
                                    </div>
                                </div>
                            </form>
                            <div class="col-md-2">
                                <i id="guardarFolio" data-toggle="tooltip" data-placement="top" data-title="Guardar" class="fa fa-2x fa-save  text-success"></i>
                                <i id="editarFolio" data-toggle="tooltip" data-placement="top" data-title="Editar" class="fa fa-2x fa-pencil hidden"></i>
                                <i id="cancelarFolio" data-toggle="tooltip" data-placement="top" data-title="Cancelar" class="fa fa-2x fa-times text-danger"></i>
                                <i id="eliminarFolio" data-toggle="tooltip" data-placement="top" data-title="Eliminar" class="fa fa-2x fa-trash-o text-danger hidden"></i>
                            </div>
                            <br><br><br>
                        </div>
                        <!--Empieza sección de información basica del folio-->
                        <div id="infoFolio" class="hidden">
                            <div class="col-md-12">
                                <div class="col-md-7">
                                    <label>Creado por: </label>
                                </div>
                                <div class="col-md-5">
                                    <label>Fecha Creación: </label>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="col-md-7">
                                    <label>Solicita: </label>
                                </div>
                                <div class="col-md-5">
                                    <label>Prioridad: </label>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="col-md-7">
                                    <label>Asignado a: </label>
                                </div>
                                <div class="col-md-5">
                                    <label>Estatus: </label>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="col-md-12">
                                    <label>Asunto: </label>
                                </div>
                            </div>
                            <!--Empieza seccion de mas detalles del folio-->
                            <div class="col-md-12 row">
                                <div class="form-group">
                                    <label id="masDetallesFolio" style="cursor:pointer">+ Mas Detalles</label>
                                    <label id="menosDetallesFolio" class="hidden" style="cursor:pointer">- Menos Detalles</label>
                                    <div id="detallesFolio" class="hidden">
                                        <div class="col-md-12 row">
                                            <ul class="nav nav-pills">
                                                <li class="active"><a href="#nav-pills-tab-1" data-toggle="tab">Pills Tab 1</a></li>
                                                <li><a href="#nav-pills-tab-2" data-toggle="tab">Pills Tab 2</a></li>
                                            </ul>
                                            <div class="tab-content" style="background: #F9EAC6">
                                                <div id="nav-pills-tab-1" class="tab-pane fade active in">
                                                    <div id="accordion" class="panel-group">
                                                        <div class="panel panel-inverse overflow-hidden">
                                                            <div class="panel-heading">
                                                                <h3 class="panel-title">
                                                                    <a class="accordion-toggle accordion-toggle-styled collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseSeven">
                                                                        <i class="fa fa-plus-circle pull-right"></i> 
                                                                        Collapsible Group Item
                                                                    </a>
                                                                </h3>
                                                            </div>
                                                            <div id="collapseSeven" class="panel-collapse collapse">
                                                                <div class="panel-body">
                                                                    Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div id="nav-pills-tab-2" class="tab-pane">
                                                    <h3 class="m-t-10">Nav Pills Tab 2</h3>
                                                    <p>
                                                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. 
                                                        Integer ac dui eu felis hendrerit lobortis. Phasellus elementum, nibh eget adipiscing porttitor, 
                                                        est diam sagittis orci, a ornare nisi quam elementum tortor. 
                                                        Proin interdum ante porta est convallis dapibus dictum in nibh. 
                                                        Aenean quis massa congue metus mollis fermentum eget et tellus. 
                                                        Aenean tincidunt, mauris ut dignissim lacinia, nisi urna consectetur sapien, 
                                                        nec eleifend orci eros id lectus.
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--Finaliza seccion de mas detalles del folio-->
                        </div>
                        <!--Finaliza sección de información basica del folio-->
                    </div>
                    <!--Finaliza seccion de agregar Folio-->
                </div>
                <!--Finaliza detalles de servicio-->
                <!--Empieza seccion de Solucion y Problemas-->
                <div class="col-md-12">
                    <!--Empieza datos solucion-->
                    <div class="col-md-6">
                        <!--Empieza Titulo solucion-->
                        <div class="col-md-12 row">
                            <div class="panel-heading-btn">
                                <label id="btnSinMaterial" class="btn btn-default btn-xs">
                                    <i class="fa fa-toggle-off"></i> Sin Material
                                </label>
                                <label id="btnConMaterial" class="btn btn-primary btn-xs hidden">
                                    Con Material <i class="fa fa-toggle-off fa-rotate-180"></i>
                                </label>
                            </div>
                            <h4>Datos de solución</h4>
                            <div class="underline m-b-15 m-t-15"></div>
                        </div>
                        <!--Finaliza Titulo solucion-->
                        <!--Empieza contenido de solucion-->
                        <div class="col-md-12">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Sucursal</label>
                                    <select id="selectSucursal" class="form-control" style="width: 100%">
                                        <option value="">Seleccionar</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Observaciones</label>
                                    <textarea id="textareaObservaciones" class="form-control" rows="4"></textarea>
                                </div>
                            </div>
                        </div>
                        <!--Finaliza contenido de solucion-->
                    </div>
                    <!--Finaliza datos solucion-->
                    <!--Empieza problemas reportados-->
                    <div class="col-md-6">
                        <!--Empieza Titulo problemas-->
                        <div class="col-md-12 row">
                            <h4>Problemas Reportados</h4>
                            <div class="underline m-b-15 m-t-15"></div>
                        </div>
                        <!--Finaliza Titulo problemas-->
                        <!--Empieza contenido de problemas-->
                        <div class="col-md-12">
                            <div class="col-md-12">
                                <br>
                                <label id="btnReportar" class="btn btn-success">Reportar</label>
                                <br><br>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Historial de problemas</label>
                                    <textarea id="textareaObservaciones" class="form-control" rows="4"></textarea>
                                </div>
                            </div>
                        </div>
                        <!--Finaliza contenido de problemas-->
                    </div>
                    <!--Finaliza problemas reportados-->
                </div>
                <!--Finaliza seccion de Solucion y Problemas-->
                <!--Empieza seccion Nodos-->
                <div class="col-md-12">
                    <!--Empieza Titulo tabla nodos-->
                    <div class="col-md-12">
                        <div class="panel-heading-btn">
                            <label id="btnVerMaterial" style="cursor:pointer">Ver Material Utilizado</label>
                        </div>
                        <div class="panel-heading-btn">
                            <label id="btnVerNodos" class="hidden" style="cursor:pointer">Ver Nodos</label>
                        </div>
                        <h4>Nodos</h4>
                        <div class="underline m-b-15 m-t-15"></div>
                    </div>
                    <!--Finaliza Titulo tabla nodos-->
                    <div id="vistaNodos">
                        <!--Empieza seccion agregar nodo-->
                        <div class="col-md-12">
                            <form id="formAgregarNodo" class="margin-bottom-0" data-parsley-validate="true">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Area</label>
                                        <select id="selectArea" class="form-control" style="width: 100%" data-parsley-required="true">
                                            <option value="">Seleccionar</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Nodo</label>
                                        <input id="selectNodo" class="form-control" style="width: 100%" data-parsley-required="true"/>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Swith</label>
                                        <select id="selectSwith" class="form-control" style="width: 100%" data-parsley-required="true">
                                            <option value="">Seleccionar</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Número de Swith</label>
                                        <input id="selectNumSwith" class="form-control" style="width: 100%" data-parsley-required="true"/>
                                    </div>
                                </div>
                            </form>
                            <div class="col-md-1">
                                <br>
                                <label id="btnAgregarNodo" class="btn btn-success">
                                    <i class="fa fa-plus"></i>
                                </label>
                            </div>
                        </div>
                        <!--Finaliza seccion agregar nodo-->
                        <!--Empieza tabla de nodos-->
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table id="table-nodo" class="table table-hover table-striped table-bordered" style="cursor:pointer" width="100%">
                                    <thead>
                                        <tr>
                                            <th class="all">Sucursal</th>
                                            <th class="all">Nodo</th>
                                            <th class="all">Switch</th>
                                            <th class="all"># Switch</th>
                                            <th class="all">Evidencia</th>
                                            <th class="all">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <th>Platino</th>
                                            <th>Platino</th>
                                            <th>Platino</th>
                                            <th>5</th>
                                            <th style="text-align: center"><i id="evidenciaNodo" class="fa fa-2x fa-file"></i></th>
                                            <th style="text-align: center">
                                                <i id="editarNodo" data-toggle="tooltip" data-placement="top" data-title="Editar Nodo" class="fa fa-2x fa-pencil"></i>&nbsp;&nbsp;&nbsp;&nbsp;
                                                <i id="editarMaterial" data-toggle="tooltip" data-placement="top" data-title="Editar Material" class="fa fa-2x fa-file-photo-o text-warning"></i>&nbsp;&nbsp;&nbsp;&nbsp;
                                                <i id="eliminarNodo" data-toggle="tooltip" data-placement="top" data-title="Eliminar" class="fa fa-2x fa-trash-o text-danger"></i>
                                            </th>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!--Finaliza tabla de nodos-->
                    </div>
                    <div id="vistaMaterialUsado" class="hidden">
                        <!--Empieza tabla de material utilizado-->
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table id="table-material" class="table table-hover table-striped table-bordered" style="cursor:pointer" width="100%">
                                    <thead>
                                        <tr>
                                            <th class="all">Material</th>
                                            <th class="all">Cantidad</th>
                                            <th class="all">Almacén Disponible</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <th>Cable</th>
                                            <th>10 m</th>
                                            <th>60 m</th>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!--Finaliza tabla de material utilizado-->
                    </div>
                </div>
                <!--Finaliza seccion Nodos-->
                <div id="eliminarNodo" class="hidden">
                    <div class="form-group">
                        <label>Swith</label>
                        <select id="selectSwith" class="form-control" style="width: 100%" data-parsley-required="true">
                            <option value="">Seleccionar</option>
                        </select>
                    </div>
                </div>
            </div>
            <!--Finaliza Panel -->
        </div>
    </div>
    <!--Finaliza contenido general-->
</div>

<!--Empezando seccion para material nodo-->
<div id="materialNodo" class="content hidden" >
    <!--Empieza seccion agregar Material-->
    <div class="col-md-12">
        <div class="col-md-5">
            <div class="form-group">
                <label>Material</label>
                <select id="selectMaterial" class="form-control" style="width: 100%" data-parsley-required="true">
                    <option value="">Seleccionar</option>
                </select>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label>Cantidad Disponible</label>
                <input id="materialDisponible" class="form-control" style="width: 100%" data-parsley-required="true" disabled/>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label>Cantidad Utilizar</label>
                <input id="materialUtilizar" class="form-control" style="width: 100%" data-parsley-required="true"/>
            </div>
        </div>
        <div class="col-md-1">
            <br>
            <label id="btnAgregarMaterialNodo" class="btn btn-success">
                <i class="fa fa-plus"></i>
            </label>
        </div>
    </div>
    <!--Finaliza seccion agregar Material-->
    <!--Empieza tabla de material nodos-->
    <div class="col-md-12">
        <div class="table-responsive">
            <table id="table-materalNodo" class="table table-hover table-striped table-bordered" style="cursor:pointer" width="100%">
                <thead>
                    <tr>
                        <th class="all">Material</th>
                        <th class="all">Cantidad</th>
                        <th class="all">Accion</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th>Cable UTP</th>
                        <th>300 m</th>
                        <th style="text-align: center">
                            <i id="eliminarMateralNodo" class="fa fa-2x fa-trash-o text-danger"></i>
                        </th>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <!--Finaliza tabla de material nodos-->
    <!--Empieza seccion de evidencia-->
    <div class="col-md-12">
        <div class="col-md-12">
            <label id="btnAgregarEvidenciaNodo" class="btn btn-success">
                <i class="fa fa-plus"> Evidencia</i>
            </label>
        </div>
        <div class="col-md-12">
            <br>
            <textarea id="textareaDescripcion" class="form-control" rows="3" disabled></textarea>
            <br>
        </div>
    </div>
    <!--Finaliza seccion de evidencia-->
</div>
<!--Finalizando seccion para material nodo-->
