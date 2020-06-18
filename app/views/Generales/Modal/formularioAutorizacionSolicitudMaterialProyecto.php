<!--Empezando Modola de Materiales -->
<div id="seccionFormulario">
    <form id="formSolicitudPoyecto" class="margin-bottom-0" data-parsley-validate="true" >
        <!--Empezando fila de los select de material y Linea-->
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="material">Línea</label>
                    <select id="selectLinea" class="form-control" style="width: 100%" disabled>
                        <option value="">Seleccionar</option>
                        <?php
                        foreach ($Linea as $item) {
                            echo '<option value="' . $item['Id'] . '">' . $item['Nombre'] . '</option>';
                        }
                        ?>
                    </select>                                            
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="material">Material</label>
                    <select id="selectMaterial" class="form-control materialProyecto" style="width: 100%" disabled>
                        <option value="">Seleccionar</option>
                    </select>                                            
                </div>
            </div>
        </div>
        <!--Finalizando fila de los select de material y Linea-->
        <!--Empezando fila de cantidad-->
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="cantidadMaterial">Cantidad</label>
                    <input id="inputCantidadMaterial" type="number" class="form-control"  placeholder="Cantidad" disabled/>
                </div>
            </div>
            <div class="col-md-2">
                <label for="cantidadMaterial">&nbsp;</label>
                <div class="form-group">
                    <a id="btnAgregaMaterial" href="javascript:;" class="btn btn-success m-r-5 disabled"><i class="fa fa-plus"></i> Agregar</a>
                </div>
            </div>
        </div>
        <!--Finalizando fila de cantidad-->
        <!--Empezando Tabla de material-->
        <div class="row">
            <div class="col-md-12 ">
                <div class="errorAgregarMaterial"></div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <table id="data-table-materiales" class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%" data-editar="true">
                        <thead>
                            <tr>
                                <th class="none">Id</th>
                                <th class="all">Material</th>
                                <th class="all">Numero de parte</th>
                                <th class="all">Cantidad</th>                                          
                                <th class="all">Estatus</th>                                          
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>    
            </div>                       
        </div>                       
        <!--Finalizando Tabla de material-->

        <!--Empezando mensaje de tabla-->
        <div class="row">
            <div class="col-md-12 m-t-20">
                <div class="alert alert-warning fade in m-b-15">                            
                    Para eliminar el registro de la tabla solo tiene que dar click sobre fila para eliminarlo.                            
                </div>                        
            </div>
        </div>
        <!--Finalizando mensaje de tabla-->

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
<!--Finalizando Modal de Materiales -->

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