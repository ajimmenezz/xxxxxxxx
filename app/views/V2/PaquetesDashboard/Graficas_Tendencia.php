<!--Comienzo del panel-->
<div id="panel-grafica-VGT" class="panel panel-inverse" >
    <!--Comienzo titulo panel-->
    <div class="panel-heading">
        <div class="panel-heading-btn">                           
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>                            
        </div>
        <h3 class="panel-title">Grafica de Tendencias</h3>
    </div>
    <!--Fin titulo panel-->
    <!--Comienzo cuerpo del panel-->
    <div class="panel-body">
        <div class="row">
            <!--Comienzo seccion de selects-->
            <div class="col-lg-12 col-md-offset-4">
                <!--Comienzo conjunto select Cliente-->
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Cliente</label>
                        <select id="select-cliente-VGT" style="width: 100%">
                            <option value="">Seleccionar</option>
                            <option >Todos</option>
                            <option >Cinemex</option>
                            <option >Siccob</option>
                            <option >A&AT</option>
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
                        </select>
                    </div>
                </div>
                <!--Fin conjunto select Semana-->
                <!--Comienzo conjunto select Otro-->
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Otro</label>
                        <select id="select-otro-VGT" style="width: 100%">
                            <option value="">Seleccionar</option>
                        </select>
                    </div>
                </div>
                <!--Fin conjunto select Otro-->
                <!--Comienzo conjunto select Otro-->
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Otro</label>
                        <select id="select-otro-VGT" style="width: 100%">
                            <option value="">Seleccionar</option>
                        </select>
                    </div>
                </div>
                <!--Fin conjunto select Otro-->
            </div>
            <!--Fin seccion de selects-->
        </div>
        <div class="row">
            <!--Comienzo seccion de grafica-->
            <div class="col-md-12">
                <div id="grafica-VGT-1" class="height-sm"></div>
            </div>
            <!--Fin seccion de grafica-->
        </div>
    </div>
    <!--Fin cuerpo del panel-->
</div>
<!--Fin del panel-->