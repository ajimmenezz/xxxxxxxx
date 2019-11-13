<!--Comienzo del panel-->
<div id="panel-grafica-VGHI" class="panel panel-inverse">
    <!--Comienzo titulo panel-->
    <div class="panel-heading">
        <div class="panel-heading-btn">                          
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>                            
        </div>
        <h3 class="panel-title">Grafica de Incidentes Pendientes Completados</h3>
    </div>
    <!--Fin titulo panel-->
    <!--Comienzo cuerpo del panel-->
    <div class="panel-body">
        <div class="row">
            <!--empieza encabezado del cuerpo-->
            <div class="col-md-12">
                <!--comienza titulo-->
                <div class="col-md-6">
                    <h3>Grafica de Incidentes Pendientes Completados</h3>
                </div>
                <!--termina titulo-->
                <!--Comienzo conjunto select Año-->
                <div class="col-md-1">
                    <div class="form-group">
                        <label>Año</label>
                        <select id="select-year-VGHI" style="width: 100%">
                            <option value="">Seleccionar</option>
                            <option value="2019">2019</option>
                            <option value="2018">2018</option>
                        </select>
                    </div>
                </div>
                <!--Fin conjunto select Año-->
                <!--Comienzo conjunto select tiempo-->
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Tiempo</label>
                        <select id="select-tiempo-VGHI" style="width: 100%">
                            <option value="">Seleccionar</option>
                            <option value="WEEK">Semana</option>
                            <option value="MONTH">Mes</option>
                            <!--<option value="YEAR">Año</option>-->
                        </select>
                    </div>
                </div>
                <!--Fin conjunto select tiempo-->
                <!--Comienzo conjunto select complemento tiempo-->
                <div class="col-md-1">
                    <div class="form-group">
                        <label>&nbsp;</label>
                        <select id="select-numero-VGHI" style="width: 100%" disabled="true">
                            <option value="">Seleccionar</option>
                        </select>
                    </div>
                </div>
                <!--Fin conjunto select complemento tiempo-->
                <!--Comienzo conjunto select Zonas-->
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Zonas</label>
                        <select id="select-zona-VGHI" style="width: 100%">
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
            </div>
            <!--fin encabezado del cuerpo-->
        </div>
        <div class="col-md-12"><br>
            <!--Empieza tabla de incidentes pendientes completados-->
            <div class="table-responsive">
                <table id="tabla-VGHI" class="table table-hover table-striped table-bordered" style="cursor:pointer" width="100%">
                    <thead>
                        <tr>
                            <th class="all">Año</th>
                            <th class="all">Tiempo</th>
                            <th class="all">Total Concluidos</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
            <!--Finaliza tabla de incidentes pendientes completados-->
        </div>
    </div>
    <!--Fin cuerpo del panel-->
</div>
<!--Fin del panel-->