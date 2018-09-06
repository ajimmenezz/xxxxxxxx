<div class="row">
    <div class="col-md-12 col-xs-12">
        <ul class="nav nav-pills">
            <li class="active"><a href="#nav-pills-resumen" data-toggle="tab" aria-expanded="false">Resumen</a></li>
            <li class=""><a href="#nav-pills-notas" data-toggle="tab" aria-expanded="true">Conversación del Servicio</a></li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane fade active in" id="nav-pills-resumen">
                <div class="row">
                    <div class="col-md-12 col-xs-12">
                        <h3>Información General del Servicio Sin Clasificar</h3>
                        <div class="underline"></div>
                    </div>
                </div>
                <div class="row m-t-10">
                    <div class="col-md-3 col-xs-12">
                        <h5 class="f-w-700">Número de Solicitud</h5>
                        <h4><?php echo $solicitud['solicitud']; ?></h4>
                    </div>                    
                    <div class="col-md-3 col-xs-12">
                        <h5 class="f-w-700">Solicitante</h5>
                        <h4><?php echo $solicitud['solicitante']; ?></h4>
                    </div>                    
                    <div class="col-md-3 col-xs-12">
                        <h5 class="f-w-700">Fecha de Solicitud</h5>
                        <h4><?php echo $solicitud['fechaSolicitud']; ?></h4>
                    </div>                    
                    <div class="col-md-3 col-xs-12">
                        <h5 class="f-w-700">Estatus de Solicitud</h5>
                        <h4><?php echo $solicitud['estatusSolicitud']; ?></h4>
                    </div>
                </div>
                <div class="row m-t-10">
                    <div class="col-md-12 col-xs-12">                        
                        <div class="underline"></div>                    
                    </div>
                </div>
                <div class="row m-t-10">
                    <div class="col-md-12 col-xs-12">
                        <h5 class="f-w-700">Descripción de la Solicitud</h5>
                        <h4><?php echo $solicitud['descripcionSolicitud']; ?></h4>
                        <div class="underline"></div>
                    </div>       
                </div>
                <div class="row m-t-10">
                    <div class="col-md-3 col-xs-12">
                        <h5 class="f-w-700">Número de Ticket</h5>
                        <h4><?php echo $solicitud['ticket']; ?></h4>
                    </div>                    
                    <div class="col-md-3 col-xs-12">
                        <h5 class="f-w-700">Tipo de Servicio</h5>
                        <h4><?php echo $solicitud['tipoServicio']; ?></h4>
                    </div>                    
                    <div class="col-md-3 col-xs-12">
                        <h5 class="f-w-700">Fecha de Servicio</h5>
                        <h4><?php echo $solicitud['fechaServicio']; ?></h4>
                    </div>                    
                    <div class="col-md-3 col-xs-12">
                        <h5 class="f-w-700">Estatus de Servicio</h5>
                        <h4><?php echo $solicitud['estatusServicio']; ?></h4>
                    </div>                          
                </div>
                <div class="row m-t-10">
                    <div class="col-md-12 col-xs-12">                        
                        <div class="underline"></div>                    
                    </div>
                </div>
                <div class="row m-t-10">
                    <div class="col-md-12 col-xs-12">
                        <h5 class="f-w-700">Descripción del Servicio</h5>
                        <h4><?php echo $solicitud['descripcionServicio']; ?></h4>
                        <div class="underline"></div>
                    </div>                   
                </div>
                <div class="row m-t-10">
                    <div class="col-md-6 col-xs-12">
                        <h5 class="f-w-700">Tiempo de la Solicitud</h5>
                        <h4><?php echo $solicitud['tiempoSolicitud']; ?> hrs</h4>
                    </div>                   
                    <div class="col-md-6 col-xs-12">
                        <h5 class="f-w-700">Tiempo del Servicio</h5>
                        <h4><?php echo $solicitud['tiempoServicio']; ?> hrs</h4>
                    </div>                   
                </div>
                <div class="row m-t-20">
                    <div class="col-md-12 col-xs-12">
                        <h3>Documentación del servicio.</h3>
                        <div class="underline"></div>
                    </div>
                </div>                   
                <div class="row m-t-10">
                    <div class="col-md-6 col-xs-12">
                        <h5 class="f-w-700">Fecha de Captura</h5>
                        <h4><?php echo $generales['fecha']; ?></h4>
                    </div>  
                </div>
                <div class="row m-t-10">
                    <div class="col-md-6 col-xs-12">
                        <h5 class="f-w-700">Observaciones del Servicio</h5>
                        <h4><?php echo $generales['descripcion']; ?></h4>
                    </div>  
                </div>
                <div class="row m-t-10">
                    <div class="col-md-6 col-xs-12">
                        <h5 class="f-w-700">Archivos de Evidencia</h5>
                        <h4><?php echo $generales['archivos']; ?></h4>
                    </div>  
                </div>
            </div>
            <div class="tab-pane fade" id="nav-pills-notas">
                <?php echo $notas; ?>;
            </div>
        </div>
    </div>
</div>
