<!--
 * Description: Formulario para guardar la ubicacion de salas X4D
 *
 * @author: Alberto Barcenas
 *
-->
<form class="margin-bottom-0" id="formUbicacion" data-parsley-validate="true">
    <div class="row m-t-10">
        <div class="col-md-12">                        
            <div class="form-group">
                <h3 id='tituloUbicacion' class="m-t-10"></h3>
                <div class="underline m-b-15 m-t-15"></div>
            </div>
        </div>
    </div>
    <div class="row m-t-10"> 
        <div class="col-md-6">
            <div class="form-group">
                <label for="inputNombreUbicacion">Nombre *</label>
                <input type="text" class="form-control" id="inputNombreUbicacion" placeholder="Ingresa Nombre de la Ubicación" style="width: 100%" data-parsley-required="true"/>                            
            </div>
        </div>
    </div>
    <div id='estatusUbicacion' class="row m-t-10"> 
        <div class="col-md-6">
            <div class="form-group">
                <label for="selectEstatusUbicacion">Estatus</label>
                <select id="selectEstatusUbicacion" class="form-control" style="width: 100%">
                    <?php
                    foreach ($flag as $item) {
                        if ($item['Flag'] === '1') {
                            ?>
                            <option value="1" selected>Activo</option>
                            <option value="0">Inactivo</option>
                            <?php
                        } else {
                            ?>
                            <option value="1">Activo</option>
                            <option value="0" selected>Inactivo</option>
                            <?php
                        }
                    }
                    ?>
                </select>
            </div>
        </div>
    </div>
    <div class="row m-t-10">
        <!--Empezando error--> 
        <div class="col-md-12">
            <div class="errorUbicacion"></div>
        </div>
        <!--Finalizando Error-->
    </div>   
    <div class="row m-t-10">
        <div class="col-md-12">
            <div class="form-group text-center">
                <br>
                <a href="javascript:;" class="btn btn-primary m-r-5 " id="btnGuardarUbicacion"><i class="fa fa-save"></i> Guardar</a>
            </div>
        </div>
    </div>
</form>