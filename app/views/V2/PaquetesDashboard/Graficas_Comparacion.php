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
        <div class="col-md-12">
            <div class="col-md-12">
                <!--Comienzo seccion de grafica-->
                <div class="col-md-12">
                    <div id="grafica-VGC-1" class="height-sm"></div>
                </div>
                <!--Fin seccion de grafica-->
            </div>
            <div class="col-md-6">
                <div class="col-lg-12">
                    <!--Comienzo conjunto select tipo de servicio-->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Tipo Servicio</label>
                            <select id="select-servicio-VGC" style="width: 100%">
                                <option value="">Seleccionar</option>
                            </select>
                        </div>
                    </div>
                    <!--Fin conjunto select tipo de servicio-->
                    <!--Comienzo conjunto select Semana-->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Tiempo</label>
                            <select id="select-tiempo-VGC" style="width: 100%">
                                <option value="">Seleccionar</option>
                                <option value="WEEK">Semana</option>
                                <option value="MONTH">Mes</option>
                                <option value="YEAR">Año</option>
                            </select>
                        </div>
                    </div>
                    <!--Fin conjunto select Semana-->
                    <!--Comienzo conjunto select actual-->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Lapso</label>
                            <select id="select-lapso-VGC" style="width: 100%" disabled="true">
                                <option value="">Seleccionar</option>
                            </select>
                        </div>
                    </div>
                    <!--Fin conjunto select actual-->
                </div>
            </div>
        </div>
    </div>
    <!--Fin cuerpo del panel-->
</div>
<!--Fin del panel-->