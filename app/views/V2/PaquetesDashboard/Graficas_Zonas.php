<!--Comienzo del panel-->
<div id="panel-grafica-VGZ" class="panel panel-inverse">
    <!--Comienzo titulo panel-->
    <div class="panel-heading">
        <div class="panel-heading-btn">                         
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>                            
        </div>
        <h3 class="panel-title">Grafica de Comparación por Zonas</h3>
    </div>
    <!--Fin titulo panel-->
    <!--Comienzo cuerpo del panel-->
    <div class="panel-body">
        <div class="row">
            <!--empieza encabezado del cuerpo-->
            <div class="col-md-12">
                <!--comienza titulo-->
                <div class="col-md-6">
                    <h3>Grafica de Comparación por Zonas</h3>
                </div>
                <!--termina titulo-->
                <!--Comienzo conjunto select tiempo-->
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Tiempo</label>
                        <select id="select-tiempo-VGZ" style="width: 100%">
                            <option value="">Seleccionar</option>
                            <option value="WEEK">Semana</option>
                            <option value="MONTH">Mes</option>
                            <!--<option value="YEAR">Año</option>-->
                        </select>
                    </div>
                </div>
                <!--Fin conjunto select tiempo-->
                <!--Comienzo conjunto select complemento tiempo-->
                <div class="col-md-2">
                    <div class="form-group">
                        <label>&nbsp;</label>
                        <select id="select-numero-VGZ" style="width: 100%" disabled="true">
                            <option value="">Seleccionar</option>
                        </select>
                    </div>
                </div>
                <!--Fin conjunto select complemento tiempo-->
                <!--Comienzo conjunto select Zonas-->
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Zonas</label>
                        <select id="select-zona-VGZ" style="width: 100%">
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
        <div class="row">
            <!--Comienzo seccion de grafica-->
            <div class="col-md-12">
                <div id="grafica-VGZ-1" class="height-md"></div>
            </div>
            <!--Fin seccion de grafica-->
        </div>
    </div>
    <!--Fin cuerpo del panel-->
</div>
<!--Fin del panel-->