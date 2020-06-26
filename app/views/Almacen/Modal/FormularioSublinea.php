<form class="margin-bottom-0" id="formNuevaSublinea" data-parsley-validate="true" >
    <div class="col-md-12">                        
        <div class="form-group">
            <h3 class="m-t-10">Nueva Sublínea</h3>
            <div class="underline m-b-15 m-t-15"></div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label>Línea de Equipo *</label>
            <select id="selectLineaEquipo" class="form-control" style="width: 100%" data-parsley-required="true">
                <option value="">Seleccionar</option>
                <?php
                foreach ($lineas as $item) {
                    if ($item['Flag'] > 0) {
                        echo '<option value="' . $item['Id'] . '">' . $item['Nombre'] . '</option>';
                    }
                }
                ?>
            </select>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label>Nombre *</label>
            <input type="text" class="form-control" id="inputNombreSublinea" placeholder="Ingresa nombre de la línea" style="width: 100%" data-parsley-required="true"/>                            
        </div>
    </div>
    <div class="col-md-12">
        <div class="form-group">
            <label>Descripción *</label>
            <div class="form-inline">
                <input type="text" class="form-control" id="inputDescripcionSublinea" placeholder="Descripción breve de la sublínea" style="width: 100%" data-parsley-required="true"/>                                
            </div>
        </div>
    </div>
    <div class="col-md-12 text-center">
        <div class="form-group">
            <div class="form-inline">
                <a href="javascript:;" class="btn btn-success m-r-5 " id="btnNuevaSublinea"><i class="fa fa-plus"></i> Agregar</a>
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
        <div class="errorSublinea"></div>
    </div>
    <!--Finalizando Error-->
</form>


