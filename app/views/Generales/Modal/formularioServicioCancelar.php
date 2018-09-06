<!--Empezando seccion para cancelar servicio-->
<div id="seccionCancelarServicio" >    
    <!--Empezando Separador-->
    <div class="row">
        <div class="col-md-12">
            <div class="underline m-t-15"></div>
        </div>
    </div>
    <!--Finalizando Separador-->
    <br>
    <!--Empezando secccion de servicios-->
    <!--Empezando formulario para los servicios-->
    <form id="formCancelarServicio" class="margin-bottom-0" data-parsley-validate="true" enctype="multipart/form-data">
        <div class="row">
            <div class="col-md-12">          
                <div class="form-group">
                    <label for="servicioNuevo"> Descripción por la cual cancela *</label>
                    <textarea class="form-control" id="inputDescripcionServicioCancelar" rows="5" placeholder="Describición breve ..." style="width: 100%" data-parsley-required="true"/> </textarea>                               
                </div>    
            </div>
        </div>
    </form>
    <!--Finalizando formulario para los servicios-->

    <!--Empezando boton para agregar servicio-->
    <div class="row">
        <div class="col-md-12 text-center">          
            <div class="form-group">
                <button id="btnServicioCancelar" type="button" class="btn btn-sm btn-primary m-r-5"><i class="fa fa-check"></i> Cancelar Servicio</button>
                <button id="btnCancelarServicioCancelar" type="button" class="btn btn-sm btn-default m-r-5"><i class="fa fa-times"></i> Cerrar</button>
            </div>    
        </div>
    </div>
    <!--Finalizando boton para agregar servicio-->  
</div>
<!--Finalizando seccion para cancelar servicio-->