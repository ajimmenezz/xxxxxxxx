<!--Empezando Pestañas para definir la seccion-->
<div class="panel-heading p-0">
    <div class="panel-heading-btn m-r-10 m-t-10">
        <!-- Single button -->
        <div class="btn-group">
            <button type="button" class="btn btn-warning btn-xs dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Acciones <span class="caret"></span>
            </button>
            <ul class="dropdown-menu">
                <li id="btnGeneraPdfReportesProblemasFaltantesMantenimientos"><a href="#"><i class="fa fa-file-pdf-o"></i> Generar Excel</a></li>
            </ul>
        </div>
        <label id="btnRegresarProblemasFaltantesMantenimientos" class="btn btn-success btn-xs">
            <i class="fa fa fa-reply"></i> Regresar
        </label>                                    
        <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-expand"><i class="fa fa-expand"></i></a>
    </div>
    <!-- begin nav-tabs -->
    <div class="tab-overflow">
        <ul class="nav nav-tabs nav-tabs-inverse">
            <li class="prev-button"><a href="javascript:;" data-click="prev-tab" class="text-success"><i class="fa fa-arrow-left"></i></a></li>
            <li class="active"><a href="#ProblemasMantenimientos" data-toggle="tab">Problemas</a></li>
            <li class=""><a href="#FaltantesMantenimientos" data-toggle="tab">Faltantes</a></li>
            <li class="next-button"><a href="javascript:;" data-click="next-tab" class="text-success"><i class="fa fa-arrow-right"></i></a></li>
        </ul>
    </div>
</div>
<!--Finalizando Pestañas para definir la seccion-->

<div class="tab-content">

    <!--Empezando la seccion reporte Problemas mantenimientos-->
    <div class="tab-pane fade active in" id="ProblemasMantenimientos">
        <div class="panel-body"> 
            <ul class="nav nav-pills">
                <li class="active"><a href="#ProblemasXSucursal" data-toggle="tab">Problemas por Sucursal</a></li>
                <li class=""><a href="#ProblemasXZona" data-toggle="tab">Problemas por Zona</a></li>
                <li class=""><a href="#ProblemasXAreaAtencion" data-toggle="tab">Problemas por Área de Atención</a></li>
                <li class=""><a href="#ProblemasXEquipo" data-toggle="tab">Problemas por Equipo</a></li>
                <li class=""><a href="#ProblemasXSucursalEquipo" data-toggle="tab">Problemas por Sucursal y Equipo</a></li>
            </ul>
            <div class="tab-content">
                
                <!--Empezando la seccion reporte problemas por sucursal-->
                <div class="tab-pane fade active in" id="ProblemasXSucursal">
                    <div class="row"> 
                        <div class="col-md-12">  
                            <div class="form-group">
                                <div class="col-md-12">
                                    <h3 class="m-t-10">Problemas por Sucursal</h3>
                                </div>
                                <div class="col-md-12">
                                    <div class="underline m-b-15 m-t-15"></div>
                                </div>
                                <!--Finalizando Separador-->
                            </div>    
                        </div> 
                    </div>
                    <div class="table-responsive">
                        <table id="data-table-problemas-sucursal" class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%">
                            <thead>
                                <tr>
                                    <th class="all">Sucursal</th>
                                    <th class="all">Problemas</th>
                                </tr>
                            </thead>
                            <tbody>                                       
                            </tbody>
                        </table>
                    </div>
                </div>
                <!--Finalizando-->

                <!--Empezando la seccion reporte problemas por zona-->
                <div class="tab-pane fade " id="ProblemasXZona">
                    <div class="row"> 
                        <div class="col-md-12">  
                            <div class="form-group">
                                <div class="col-md-12">
                                    <h3 class="m-t-10">Problemas por Zona</h3>
                                </div>
                                <div class="col-md-12">
                                    <div class="underline m-b-15 m-t-15"></div>
                                </div>
                                <!--Finalizando Separador-->
                            </div>    
                        </div> 
                    </div>
                    <div class="table-responsive">
                        <table id="data-table-problemas-zona" class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%">
                            <thead>
                                <tr>
                                    <th class="all">Zona</th>
                                    <th class="all">Problemas</th>
                                    <th class="all">Tickets</th>
                                </tr>
                            </thead>
                            <tbody>                                       
                            </tbody>
                        </table>
                    </div>
                </div>
                <!--Finalizando-->
                
                <!--Empezando la seccion reporte problemas por area de atencion-->
                <div class="tab-pane fade " id="ProblemasXAreaAtencion">
                    <div class="row"> 
                        <div class="col-md-12">  
                            <div class="form-group">
                                <div class="col-md-12">
                                    <h3 class="m-t-10">Problemas por Área de Atención</h3>
                                </div>
                                <div class="col-md-12">
                                    <div class="underline m-b-15 m-t-15"></div>
                                </div>
                                <!--Finalizando Separador-->
                            </div>    
                        </div> 
                    </div>
                    <div class="table-responsive">
                        <table id="data-table-problemas-area-atencion" class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%">
                            <thead>
                                <tr>
                                    <th class="all">Área de Atención</th>
                                    <th class="all">Problemas por Área</th>
                                </tr>
                            </thead>
                            <tbody>                                       
                            </tbody>
                        </table>
                    </div>
                </div>
                <!--Finalizando-->

                <!--Empezando la seccion reporte Problemas por equipo-->
                <div class="tab-pane fade" id="ProblemasXEquipo">
                    <div class="row"> 
                        <div class="col-md-12">  
                            <div class="form-group">
                                <div class="col-md-12">
                                    <h3 class="m-t-10">Problemas por Equipo</h3>
                                </div>
                                <div class="col-md-12">
                                    <div class="underline m-b-15 m-t-15"></div>
                                </div>
                                <!--Finalizando Separador-->
                            </div>    
                        </div> 
                    </div>
                    <div class="table-responsive">
                        <table id="data-table-problemas-equipo" class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%">
                            <thead>
                                <tr>
                                    <th class="all">Equipo</th>
                                    <th class="all">Problemas por Equipo</th>
                                    <th class="all">Zona 1</th>
                                    <th class="all">Zona 2</th>
                                    <th class="all">Zona 3</th>
                                    <th class="all">Zona 4</th>
                                </tr>
                            </thead>
                            <tbody>                                       
                            </tbody>
                        </table>
                    </div>
                </div>
                <!--Finalizando-->

                <!--Empezando la seccion reporte Problemas por sucursal y equipo-->
                <div class="tab-pane fade" id="ProblemasXSucursalEquipo">
                    <div class="row"> 
                        <div class="col-md-12">  
                            <div class="form-group">
                                <div class="col-md-12">
                                    <h3 class="m-t-10">Problemas por Sucursal y Equipo</h3>
                                </div>
                                <div class="col-md-12">
                                    <div class="underline m-b-15 m-t-15"></div>
                                </div>
                                <!--Finalizando Separador-->
                            </div>    
                        </div> 
                    </div>
                    <div class="table-responsive">
                        <table id="data-table-problemas-sucursal-equipo" class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%">
                            <thead>
                                <tr>
                                    <th class="all">Sucursal</th>
                                    <th id="thProblemaModelo1" class="all"></th>
                                    <th id="thProblemaModelo2" class="all"></th>
                                    <th id="thProblemaModelo3" class="all"></th>
                                </tr>
                            </thead>
                            <tbody>                                       
                            </tbody>
                        </table>
                    </div>
                </div>
                <!--Finalizando-->

            </div>
            <!--Finalizando-->

        </div>
    </div>
    <!--Finalizando-->
    
    <!--Empezando la seccion reporte Faltantes mantenimientos-->
    <div class="tab-pane fade" id="FaltantesMantenimientos">
        <div class="panel-body"> 
            <ul class="nav nav-pills">
                <li class="active"><a href="#FaltantesXSucursal" data-toggle="tab">Faltantes por Sucursal</a></li>
                <li class=""><a href="#FaltantesXZona" data-toggle="tab">Faltantes por Zona</a></li>
                <li class=""><a href="#FaltantesXAreaAtencion" data-toggle="tab">Faltantes por Área de Atención</a></li>
                <li class=""><a href="#FaltantesXEquipo" data-toggle="tab">Faltantes por Equipo</a></li>
                <li class=""><a href="#FaltantesXSucursalEquipo" data-toggle="tab">Faltantes por Sucursal y Equipo</a></li>
            </ul>
            <div class="tab-content">

                <!--Empezando la seccion reporte Faltantes por sucursal-->
                <div class="tab-pane fade active in" id="FaltantesXSucursal">
                    <div class="row"> 
                        <div class="col-md-12">  
                            <div class="form-group">
                                <div class="col-md-12">
                                    <h3 class="m-t-10">Faltantes por Sucursal</h3>
                                </div>
                                <div class="col-md-12">
                                    <div class="underline m-b-15 m-t-15"></div>
                                </div>
                                <!--Finalizando Separador-->
                            </div>    
                        </div> 
                    </div>
                    <div class="table-responsive">
                        <table id="data-table-faltantes-sucursal" class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%">
                            <thead>
                                <tr>
                                    <th class="all">Sucursal</th>
                                    <th class="all">Equipos Faltantes</th>
                                </tr>
                            </thead>
                            <tbody>                                       
                            </tbody>
                        </table>
                    </div>
                </div>
                <!--Finalizando-->

                <!--Empezando la seccion reporte Faltantes por Zona-->
                <div class="tab-pane fade" id="FaltantesXZona">
                    <div class="row"> 
                        <div class="col-md-12">  
                            <div class="form-group">
                                <div class="col-md-12">
                                    <h3 class="m-t-10">Faltantes por Zona</h3>
                                </div>
                                <div class="col-md-12">
                                    <div class="underline m-b-15 m-t-15"></div>
                                </div>
                                <!--Finalizando Separador-->
                            </div>    
                        </div> 
                    </div>
                    <div class="table-responsive">
                        <table id="data-table-faltantes-zona" class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%">
                            <thead>
                                <tr>
                                    <th class="all">Zona</th>
                                    <th class="all">Equipos Faltantes</th>
                                    <th class="all">Tickets</th>
                                </tr>
                            </thead>
                            <tbody>                                       
                            </tbody>
                        </table>
                    </div>
                </div>
                <!--Finalizando-->

                <!--Empezando la seccion reporte Faltantes por area de atencion-->
                <div class="tab-pane fade" id="FaltantesXAreaAtencion">
                    <div class="row"> 
                        <div class="col-md-12">  
                            <div class="form-group">
                                <div class="col-md-12">
                                    <h3 class="m-t-10">Faltantes por Área de Atención</h3>
                                </div>
                                <div class="col-md-12">
                                    <div class="underline m-b-15 m-t-15"></div>
                                </div>
                                <!--Finalizando Separador-->
                            </div>    
                        </div> 
                    </div>
                    <div class="table-responsive">
                        <table id="data-table-faltantes-area-atencion" class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%">
                            <thead>
                                <tr>
                                    <th class="all">Área de Atención</th>
                                    <th class="all">Equipos Faltantes</th>
                                </tr>
                            </thead>
                            <tbody>                                       
                            </tbody>
                        </table>
                    </div>
                </div>
                <!--Finalizando-->

                <!--Empezando la seccion reporte Faltantes por Equipo-->
                <div class="tab-pane fade" id="FaltantesXEquipo">
                    <div class="row"> 
                        <div class="col-md-12">  
                            <div class="form-group">
                                <div class="col-md-12">
                                    <h3 class="m-t-10">Faltantes por Equipo</h3>
                                </div>
                                <div class="col-md-12">
                                    <div class="underline m-b-15 m-t-15"></div>
                                </div>
                                <!--Finalizando Separador-->
                            </div>    
                        </div> 
                    </div>
                    <div class="table-responsive">
                        <table id="data-table-faltantes-equipo" class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%">
                            <thead>
                                <tr>
                                    <th class="all">Equipo Faltante</th>
                                    <th class="all">Faltates por Equipo</th>
                                    <th class="all">Zona 1</th>
                                    <th class="all">Zona 2</th>
                                    <th class="all">Zona 3</th>
                                    <th class="all">Zona 4</th>
                                </tr>
                            </thead>
                            <tbody>                                       
                            </tbody>
                        </table>
                    </div>
                </div>
                <!--Finalizando-->

                <!--Empezando la seccion reporte Faltantes por Sucursal y Equipo-->
                <div class="tab-pane fade" id="FaltantesXSucursalEquipo">
                    <div class="row"> 
                        <div class="col-md-12">  
                            <div class="form-group">
                                <div class="col-md-12">
                                    <h3 class="m-t-10">Faltantes por Sucursal y Equipo</h3>
                                </div>
                                <div class="col-md-12">
                                    <div class="underline m-b-15 m-t-15"></div>
                                </div>
                                <!--Finalizando Separador-->
                            </div>    
                        </div> 
                    </div>
                    <div class="table-responsive">
                        <table id="data-table-faltantes-sucursal-equipo" class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%">
                            <thead>
                                <tr>
                                    <th class="all">Sucursal</th>
                                    <th id="thFaltanteModelo1" class="all"></th>
                                    <th id="thFaltanteModelo2" class="all"></th>
                                    <th id="thFaltanteModelo3" class="all"></th>
                                </tr>
                            </thead>
                            <tbody>                                       
                            </tbody>
                        </table>
                    </div>
                </div>
                <!--Finalizando-->

            </div>
        </div>
    </div>
    <!--Finalizando-->
    
</div>
