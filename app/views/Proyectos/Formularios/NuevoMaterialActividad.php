<!--Empezando formulario--> 
<form id="form-definiendo-material-utilizado" data-parsley-validate="true">
    <!--Empezando titulo-->
    <div class="row">
        <div class="col-xs-7 ">
            <h5 class="f-w-700">Definir Material Utilizado</h5>
        </div>                    
        <div class="col-xs-5 text-right">
            <h5>
                <button id="btn-regresar-tabla-material" type="button" class="btn btn-success btn-xs editar-material hidden"><i class="fa fa-reply"></i> <span class="ocultar-xs">Regresar Actividad</span></button>                
            </h5>
        </div>                    
    </div>
    <!--Finalizando titulo-->

    <!--Empezando Separador-->
    <div class="row">
        <div class="col-md-12">
            <div class="underline m-b-15"></div>
        </div>
    </div>
    <!--Finalizando Separador-->   

    <!--Empezando fila 1-->
    <div class="row">                    
        <div class="col-xs-12 col-md-4">
            <label>Material</label>
            <div class="form-group">
                <select id="select-material" class="form-control" style="width: 100%" data-parsley-required="true">
                    <option value="">Seleccionar</option>
                </select>
            </div>
        </div>
        <div class="col-xs-12 col-md-4">
            <label>Ubicación</label>
            <div class="form-group">
                <select id="select-ubicacion" class="form-control" style="width: 100%" data-parsley-required="true">
                    <option value="">Seleccionar</option>
                </select>
            </div>
        </div>
        <div class="col-xs-12 col-md-4">
            <label>Nodo</label>
            <div class="form-group">
                <select id="select-nodo" class="form-control" style="width: 100%" data-parsley-required="true">
                    <option value="">Seleccionar</option>                   
                </select>
            </div>
        </div>
    </div>
    <!--Finalizando fila 1-->

    <!--Empezando fila 2-->
    <div class="row">        
        <div class="col-md-4">
            <label>Utilizado</label>
            <div class="form-group">
                <input id="input-utilizado-material-actividad" type="number" class="form-control" placeholder="Cantidad" data-parsley-required="true"/>
            </div>
        </div>
        <div class="col-md-4">
            <label>Solicitado</label>
            <div class="form-group">
                <input id="input-solicitado-nodo" type="text" class="form-control" disabled/>
            </div>
        </div>
    </div>
    <!--Finalizando fila 2-->
   
    <!--Empezando fila 4-->
    <div id="file-evidencia-subida" class="row editar-material">
        <div class="col-md-12">
            <label>Evidencia</label>
            <div class="evidenciasMaterialUtilizado">                                
            </div>
        </div>
    </div>
    <!--Finalizando fila 4-->
</form>
<!--Finalizando formulario--> 
