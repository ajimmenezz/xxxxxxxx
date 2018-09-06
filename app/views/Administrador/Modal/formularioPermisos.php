<form class="margin-bottom-0" id="formNuevoPermisos" data-parsley-validate="true" >
    <div class="col-md-12">                        
        <div class="form-group">
            <h3 class="m-t-10">Nuevo Permiso</h3>
            <div class="underline m-b-15 m-t-15"></div>
        </div>
    </div>
    <div class="col-md-3 m-t-10">
        <div class="form-group">
            <label for="nombreSistema">Nombre *</label>
            <input type="text" class="form-control" id="inputNombrePermiso" placeholder="Ingresa nombre de permiso" style="width: 100%" data-parsley-required="true"/>                            
        </div>
    </div>
    <div class="col-md-3 m-t-10">
        <div class="form-group">
            <label for="permiso">Permiso *</label>
            <input type="text" class="form-control" id="inputPermiso" placeholder="Ingresa permiso" style="width: 100%" data-parsley-required="true"/>                            
        </div>
    </div>
    <div class="col-md-6 m-t-10">
        <div class="form-group">
            <label for="nombrePermiso">Descripción *</label>
            <input type="text" class="form-control" id="inputDescripcionPermiso" placeholder="Descripción breve de que trata el permiso" style="width: 100%" data-parsley-required="true"/>                                
        </div>
    </div>
    <div class="col-md-12 m-t-10 text-center">
        <a href="javascript:;" class="btn btn-success m-r-5 " id="btnNuevoPermiso"><i class="fa fa-plus"></i> Agregar</a>
        <a href="javascript:;" class="btn btn-danger m-r-5 " id="btnCancelarPermiso"><i class="fa fa-times"></i> Cancelar</a>
    </div>
    <div class="col-md-12">
        <div class="form-group">
            <div class="form-inline muestraCarga"></div>
        </div>
    </div>
    <!--Empezando error--> 
    <div class="col-md-12">
        <div class="errorPermiso"></div>
    </div>
    <!--Finalizando Error-->
</form>
