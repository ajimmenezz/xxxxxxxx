<!-- Empezando #contenido -->
<div id="content" class="content">
    <!-- Empezando titulo de la pagina -->
    <h1 class="page-header">Dispositivos Móviles</h1>
    <!-- Finalizando titulo de la pagina -->

    <!-- Empezando panel nuevo proyecto-->
    <div id="panelMapa" class="panel panel-inverse borde-sombra">
        <!--Empezando cabecera del panel-->
        <div class="panel-heading">
            <div class="panel-heading-btn">
                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>                            
                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>                            
            </div>
            <h4 class="panel-title">Ubicaciones de Dispositivos</h4>
        </div>
        <!--Finalizando cabecera del panel-->
        <!--Empezando cuerpo del panel-->
        <div class="panel-body">
            <div class="row">
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <select class="form-control" id="listUsuarios">                        
                    </select>
                </div>
            </div>
            <div class="row m-t-20">
                <div class="col-md-12">                      
                    <div id="map" style="min-height: 500px !important; min-width: 100% !important;"></div>
                    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBhxRFDVIhlAA3QayN6D6R5V9BEyXmDuEM"></script>
                </div>
            </div>
            <!--Finalizando cuerpo del panel-->
        </div>
        <!-- Finalizando panel nuevo proyecto -->   

    </div>
    <!-- Finalizando #contenido -->