<?php // echo "<pre>", var_dump($usuarios), "</pre>"; ?>

<form class="margin-bottom-0" id="formNuevoAlmacen" data-parsley-validate="true" >
    <div class="col-md-12">                        
        <div class="form-group">
            <h3 class="m-t-10">Nuevo Almacén</h3>
            <div class="underline m-b-15 m-t-15"></div>
        </div>
    </div>
    <div class="col-md-5 m-t-10">
        <div class="form-group">
            <label>Nombre *</label>
            <input type="text" class="form-control" id="inputNombreAlmacen" placeholder="Ingresa nombre del almacén" style="width: 100%" data-parsley-required="true"/>                            
        </div>
    </div>
    <div class="col-md-7 m-t-10">
        <div class="form-group">
            <label>Responsable *</label>
            <select id="listResponsableAlmacen" class="form-control">
                <option value="">Seleccionar . . .</option>
                <?php
                foreach ($usuarios as $key => $value) {
                    echo '<option value="' . $value['Id'] . '">' . $value['Nombre'] . '</option>';
                }
                ?>
            </select>
        </div>
    </div>
    <div class="col-md-12 text-center">
        <div class="form-group">
            <div class="form-inline">
                <a href="javascript:;" class="btn btn-success m-r-5 " id="btnNuevoAlmacen"><i class="fa fa-plus"></i> Agregar</a>
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
        <div class="errorAlmacen"></div>
    </div>
    <!--Finalizando Error-->
</form>


