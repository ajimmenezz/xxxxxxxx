<!-- Empezando boton scroll para regresar a la parete superior de la pagina -->
<a href="javascript:;" class="btn btn-icon btn-circle btn-success btn-scroll-to-top fade" data-click="scroll-top"><i class="fa fa-angle-up"></i></a>
<!-- Fin de  boton scroll -->
</div>
<!-- Finalizando pagina-contenedor -->

<!-- Empezando seccion de ayuda -->
<div class="" id="seccion-ayuda">    
    <div class="text-center ayuda-titulo">
        <h4 class="m-t-0">Ayuda del sistema <i class="fa fa-question-circle"></i></h4>
    </div>
    <div class="divider"></div> 
    <div id="ayuda-contenido" data-scrollbar="true" data-height="75%"></div>
    <div class="row m-t-10 btn-cerrar-proyecto ayuda-boton">
        <div class="col-md-12 text-center m-t-1">  
            <a id="btnCerrarSeccionAyuda" href="javascript:;" class="btn btn-primary btn-sm btn-sm" >Cerrar Ayuda</a>
        </div>
    </div>
</div>
<!-- Finalizando seccion de ayuda -->


<!--cuadro de dialogo-->
<div class="modal modal-message fade" id="modal-dialogo">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close hidden" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">
            </div>
            <div class="modal-footer">
                <a id="btnModalAbortar" href="javascript:;" class="btn btn-sm btn-white" data-dismiss="modal">Cancelar</a>
                <a id="btnModalConfirmar" href="javascript:;" class="btn btn-sm btn-primary">Aceptar</a>
            </div>
        </div>
    </div>
</div>
<!--fin de cuadro de dialogo-->

<!-- Empieza alerta de modal -->
<div id="modal-alerta-error" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger m-b-0 text-center">
                    <h4><i class="fa fa-info-circle"></i> Alert Header</h4>
                    <p>Cras sit amet nibh libero, in gravida nulla. Nulla vel metus scelerisque ante sollicitudin commodo. Cras purus odio, vestibulum in vulputate at, tempus viverra turpis. Fusce condimentum nunc ac nisi vulputate fringilla. Donec lacinia congue felis in faucibus.</p>
                </div>
            </div>
            <div class="modal-footer">
                <!--<a id="btnAlertaModalAbortar" href="javascript:;" class="btn btn-sm btn-white" data-dismiss="modal">Cerrar</a>-->
                <!--<a id="btnAlertaModalConfirmar" href="javascript:;" class="btn btn-sm btn-danger hidden" data-dismiss="modal">Aceptar</a>-->
            </div>
        </div>
    </div>
</div>

<div id="modal-box" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">
            </div>
            <div class="modal-footer">
                <a id="btnModalBoxAbortar" href="javascript:;" class="btn btn-sm btn-white" data-dismiss="modal">Cancelar</a>
                <a id="btnModalBoxConfirmar" href="javascript:;" class="btn btn-sm btn-primary">Aceptar</a>
            </div>
        </div>
    </div>
</div>
<!-- Finaliza alerta de modal -->
</body>
</html>