<!--Empezando Personal del proyecto -->
<div id="seccionFormulario">
    <form id="formSolicitudPoyecto" class="margin-bottom-0" data-parsley-validate="true" >    
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label for="personal">Perfiles de Personal</label>
                    <?php foreach ($datos['detalles'] as $value) { ?>
                        <textarea id="textareaPerfilPersonal" class="form-control" name="perfilPersonal" placeholder="Ingresa los perfiles del personal aqui ...." data-parsley-required="true" rows="3" disabled><?php echo $value['DescripcionPerfil'] ?></textarea>
                    <?php } ?>
                    <dl>
                        <dt>Debes definir cual es el perfil del personal que se va ha contratar para el proyecto.</dt>                                    
                    </dl>
                </div>
            </div>
        </div>
        <!--Empezando mensaje de error-->
        <div class="row">
            <div class="col-md-12 ">
                <div class="errorGuardarPersonal"></div>
            </div>
        </div>
        <!--Finalizando mensaje de error-->

        <!--Empezando botones para solicitud de personal-->
        <div class="row">       
            <div id="seccionBtnSolicitudPersonal"class="col-md-12 text-right">            
                <button id="btnEditarSolicitud" type="button" class="btn btn-sm btn-primary m-r-5" >Editar Solicitud</button>
                <button id="btnAutorizarSolicitud" type="button" class="btn btn-sm btn-success m-r-5" >Autorizar </button>
                <button id="btnNoAutorizar" type="button" class="btn btn-sm btn-danger m-r-5" >No Autorizar </button>
                <button id="btnCerrarVentana" type="button" class="btn btn-sm btn-default m-r-5" >Cerrar ventana</button>
            </div>
            <div id="seccionBtnEditarSolicitudPersonal" class="col-md-12 text-center hidden">
                <button id="btnGuardarCambiosSolicitud" type="button" class="btn btn-sm btn-success m-r-5">Guardar cambios </button>
                <button id="btnCancelarCambiosSolicitud" type="button" class="btn btn-sm btn-danger m-r-5">Cancelar cambios</button>
            </div>
        </div>
        <!--Finalizando botones para solicitud de personal-->
    </form>
</div>
<!--Finalizando Personal del proyecto-->

<!--Empezando seccion de la autorizacion de la solicitud con exito-->
<div id="seccionExitoAutorizacion" class="hidden">
    <div  class="row">
        <div class="col-md-12 text-center">
            <p>Se ha autorizado la solicitud con exito. Ya se notifico al departamento para que le brinde el seguimiento.</p>
        </div>
    </div>
</div>
<!--Finalizando seccion de la autorizacion de la solicitud con exito-->

<!--Empezando seccion de no autorizacion de la solicitud-->
<div id="seccionNoAutorizado" class="hidden">
    <form id="formNoAutorizar" class="margin-bottom-0" data-parsley-validate="true" >
        <div  class="row">
            <div class="col-md-12">
                <label>Indique la causa por la que no se autoriza la solicitud.</label>
                <textarea id="textareaDescripcionNoAutorizado" class="form-control" name="solicitudNoAutorizada" placeholder="Ingresa aqui la causa ...." data-parsley-required="true" rows="3" ></textarea>
            </div>
        </div>
        <div  class="row">
            <div class="col-md-12 m-t-10 text-center">
                <button id="btnAceptarNoAutorizar" type="button" class="btn btn-sm btn-success m-r-5">Aceptar No autorizar </button>
                <button id="btnCancelarNoAutorizar" type="button" class="btn btn-sm btn-danger m-r-5">Cancelar No autorizar</button>            
            </div>
        </div>
    </form>
</div>
<!--Finalizando seccion de no autorizacion de la solicitud-->

<!--Empezando seccion mensaje de no autorizada la solicitud con exito-->
<div id="seccionExitoNoAutorizacion" class="hidden">
    <div  class="row">
        <div class="col-md-12 text-center">
            <p>La solicitud fue actualizada con exito. Ya se notifico al solicitante para que le brinde el seguimiento.</p>
        </div>
    </div>
</div>
<!--Finalizando seccion mensaje de no autorizada la solicitud con exito-->