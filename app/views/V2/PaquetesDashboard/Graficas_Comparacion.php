<!--Comienzo del panel-->
<div id="panel-grafica-VGC" class="panel panel-inverse">
    <!--Comienzo titulo panel-->
    <div class="panel-heading">
        <div class="panel-heading-btn">
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
        </div>
        <h3 class="panel-title">Grafica de Comparación de Incidencias</h3>
    </div>
    <!--Fin titulo panel-->
    <!--Comienzo cuerpo del panel-->
    <div class="panel-body">
        <div class="row">
            <!--Empieza encabezado con selects-->
            <div class="col-md-12">
                <!--comienza titulo-->
                <div class="col-md-5">
                    <h3>Grafica de Comparación de Incidencias</h3>
                </div>
                <!--termina titulo-->
                <!--Comienzo conjunto select tipo de servicio-->
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Tipo Servicio</label>
                        <select id="select-servicio-VGC" style="width: 100%">
                            <option value="">Seleccionar</option>
                        </select>
                    </div>
                </div>
                <!--Fin conjunto select tipo de servicio-->
                <!--Comienzo conjunto select Semana-->
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Tiempo</label>
                        <select id="select-tiempo-VGC" style="width: 100%">
                            <option value="">Seleccionar</option>
                            <option value="WEEK">Semana</option>
                            <option value="MONTH">Mes</option>
                            <!--<option value="YEAR">Año</option>-->
                        </select>
                    </div>
                </div>
                <!--Fin conjunto select Semana-->
                <!--Comienzo conjunto select complemento tiempo-->
                <div class="col-md-1">
                    <div class="form-group">
                        <label>&nbsp;</label>
                        <select id="select-numero-VGC" style="width: 100%" disabled="true">
                            <option value="">Seleccionar</option>
                        </select>
                    </div>
                </div>
                <!--Fin conjunto select complemento tiempo-->
                <!--Comienzo conjunto select Zonas-->
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Zonas</label>
                        <select id="select-zona-VGC" style="width: 100%">
                            <option value="">Seleccionar</option>
                            <option value="ZONA 1">Zona 1</option>
                            <option value="ZONA 2">Zona 2</option>
                            <option value="ZONA 3">Zona 3</option>
                            <option value="ZONA 4">Zona 4</option>
                        </select>
                    </div>
                </div>
                <!--Fin conjunto select Zonas-->
                <div class="col-md-12" style="background: #FCAC31"><br></div>
                <!--Fin conjunto select actual-->
            </div>
            <!--Finaliza encabezado con selects-->
            <!--Comienzo navegador vista-->
            <div class="col-md-12 hidden">
                <ul class="nav nav-pills">
                    <li class="active"><a href="#vista-grafica-VGC" data-toggle="tab" class="f-w-600 f-s-14">Grafica</a></li>
                    <li><a href="#vista-tabla-VGC" data-toggle="tab" class="f-w-600 f-s-14">Tabla</a></li>          
                </ul>
            </div>
            <!--Finaliza navegador vista-->
        </div>
        <!--Empieza contenedor de las pestañas-->
        <div class="tab-content">
            <div class="tab-pane fade active in" id="vista-grafica-VGC">
                <!--Empezando cuerpo del panel de Grafica VGC-->
                <div class="panel-body">
                    <div class="row">
                        <!--Empieza grafica VGC-->
                        <div class="col-md-12">
                            <div id="grafica-VGC-1" class="height-md"></div>
                        </div>
                        <!--Finaliza grafica VGC-->
                    </div>
                </div>
                <!--Finaliza cuerpo del panel de Grafica VGC-->
            </div>
            <div class="tab-pane fade" id="vista-tabla-VGC">
                <!--Empezando cuerpo del panel de Tabla VGC-->
                <div class="panel-body">
                    <div class="row">
                        <div class="table-responsive">
                            <table id="tabla-VGC" class="table table-hover table-striped table-bordered" style="cursor:pointer" width="100%">
                                <thead>
                                    <tr>
                                        <th class="all"></th>
                                        <th class="all">Abierto</th>
                                        <th class="all">Atención</th>
                                        <th class="all">Problema</th>
                                        <th class="all">Cerrado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>  
                    </div>
                </div>
                <!--Finaliza cuerpo del panel de Tabla VGC-->
            </div>
        </div>
        <!--Finaliza contenedor de las pestañas-->
    </div>
    <!--Fin cuerpo del panel-->
</div>
<!--Fin del panel-->