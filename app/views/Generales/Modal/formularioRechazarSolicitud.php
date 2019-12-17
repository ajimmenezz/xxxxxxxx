<div id="seccionRechazar" >    
    <!--Empezando Separador-->
    <div class="row">
        <div class="col-md-12">
            <div class="underline m-t-15"></div>
        </div>
    </div>

    <form id="formRechazar" class="margin-bottom-0 m-t-15" data-parsley-validate="true" enctype="multipart/form-data">
        <div class="row">
            <div class="col-md-12">          
                <div class="form-group">
                    <label> Descripción por la cual rechaza *</label>
                    <textarea class="form-control" id="inputDescripcionRechazo" rows="5" placeholder="Describción breve ..." style="width: 100%" data-parsley-required="true"/> </textarea>                               
                </div>    
            </div>
        </div>
    </form>

    <div class="row">
        <div class="col-md-12 text-center">          
            <div class="form-group">
                <button id="btnRechazarSD" type="button" class="btn btn-sm btn-warning m-r-5"><i class="fa fa-check"></i> Confirmar Rechazo</button>
                <button id="btnCancelarRechazar" type="button" class="btn btn-sm btn-default m-r-5"><i class="fa fa-times"></i> Cerrar</button>
            </div>    
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 m-t-20">
            <div id="errorRechazar"></div>
        </div>
    </div>   
</div>