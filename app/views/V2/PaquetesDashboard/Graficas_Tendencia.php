<!--Comienzo del panel-->
<div id="panel-grafica-VGT" class="panel panel-inverse" >
    <!--Comienzo titulo panel-->
    <div class="panel-heading">
        <div class="panel-heading-btn">                           
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>                            
        </div>
        <h3 class="panel-title">&nbsp;</h3>
    </div>
    <!--Fin titulo panel-->
    <!--Comienzo cuerpo del panel-->
    <div class="panel-body">
        <div class="row">
            <!--Comienzo seccion de selects-->
            <div class="col-md-12 col-md-offset-8">
                <!--Comienzo conjunto select Cliente-->
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Cliente</label>
                        <select id="select-cliente-VGT" style="width: 100%">
                            <option value="">Seleccionar</option>
                            <option value="1">Cinemex</option>
                            <option value="2">Siccob</option>
                            <option value="3">A&AT</option>
                        </select>
                    </div>
                </div>
                <!--Fin conjunto select Cliente-->
                <!--Comienzo conjunto select Semana-->
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Tiempo</label>
                        <select id="select-tiempo-VGT" style="width: 100%">
                            <option value="">Seleccionar</option>
                            <option value="WEEK">Semana</option>
                            <option value="MONTH">Mes</option>
                            <option value="YEAR">Año</option>
                        </select>
                    </div>
                </div>
                <!--Fin conjunto select Semana-->
            </div>
            <!--Fin seccion de selects-->
            <!--comienzo de encabezado-->
            <div class="col-md-12">
                <div class="col-md-11">
                    <h3>Grafica de Tendencia</h3>
                </div>
                <div class="col-md-1">
                    <br>
                    <div id="actualizar-VGT">
                        <button id="btn-actualizar-VGT" type="button" class="btn btn-sm btn-success m-r-5"><i class="fa fa-repeat"></i> Actualizar</button>
                    </div>
                </div>
                <div class="col-md-12" style="background: #FCAC31"><br></div>
            </div>
            <!--fin de encabezado-->
        </div>
        <div class="row">
            <!--Comienzo seccion de grafica-->
            <div class="col-md-12">
                <div id="grafica-VGT-1" class="height-md"></div>
            </div>
            <!--Fin seccion de grafica-->
        </div>
    </div>
    <!--Fin cuerpo del panel-->
</div>
<!--Fin del panel-->
