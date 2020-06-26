<form class="margin-bottom-0" id="formNuevaArea" data-parsley-validate="true" >
    <div class="col-md-12">                        
        <div class="form-group">
            <h3 class="m-t-10">Nueva Área</h3>
            <div class="underline m-b-15 m-t-15"></div>
        </div>
    </div>
    <div class="col-md-5 m-t-10">
        <div class="form-group">
            <label for="nombreArea">Nombre *</label>
            <input type="text" class="form-control" id="inputNombreArea" placeholder="Ingresa nombre de la área" style="width: 100%" data-parsley-required="true"/>                            
        </div>
    </div>
    <div class="col-md-7 m-t-10">
        <div class="form-group">
            <label for="nombreArea">Descripción *</label>
            <div class="form-inline">
                <input type="text" class="form-control" id="inputDescripcionArea" placeholder="Descripción breve de que trata el área" style="width: 100%" data-parsley-required="true"/>                                
            </div>
        </div>
    </div>
    <div class="col-md-12 text-center">
        <div class="form-group">
            <div class="form-inline">
                <a href="javascript:;" class="btn btn-success m-r-5 " id="btnNuevaArea"><i class="fa fa-plus"></i> Agregar</a>
                <a href="javascript:;" class="btn btn-danger m-r-5 " id="btnCancelar"><i class="fa fa-times"></i> Cancelar</a>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="form-group">
            <div class="form-inline muestraCarga"></div>
        </div>
    </div>
    <!--Empezando error--> 
    <div class="col-md-12">
        <div class="errorArea"></div>
    </div>
    <!--Finalizando Error-->
</form>


