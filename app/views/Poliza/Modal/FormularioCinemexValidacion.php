<!--
 * Description: Formulario para guardar el personal de validaciones de cinemex para poliza
 *
 * @author: Alberto Barcenas
 *
-->
<form class="margin-bottom-0" id="formCinemexValidacion" data-parsley-validate="true">
    <div class="row m-t-10">
        <div class="col-md-12">                        
            <div class="form-group">
                <h3 id='tituloCinemexValidacion' class="m-t-10"></h3>
                <div class="underline m-b-15 m-t-15"></div>
            </div>
        </div>
    </div>
    <div class="row m-t-10"> 
        <div class="col-md-6">
            <div class="form-group">
                <label for="inputNombreCinemexValidacion">Nombre *</label>
                <input type="text" class="form-control" id="inputNombreCinemexValidacion" placeholder="Ingresa Nombre del personal" style="width: 100%" data-parsley-required="true"/>                            
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="inputCorreoCinemexValidacion">Correo *</label>
                <input type="text" class="form-control" id="inputCorreoCinemexValidacion" placeholder="Ingresa el correo" style="width: 100%" data-parsley-type="email" data-parsley-required="true"/>                            
            </div>
        </div>
    </div>
    <div id='estatusCinemexValidacion' class="row m-t-10"> 
        <div class="col-md-6">
            <div class="form-group">
                <label for="selectEstatusCinemexValidacion">Estatus</label>
                <select id="selectEstatusCinemexValidacion" class="form-control" style="width: 100%">
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
            <div class="errorCinemexValidacion"></div>
        </div>
        <!--Finalizando Error-->
    </div>   
    <div class="row m-t-10">
        <div class="col-md-12">
            <div class="form-group text-center">
                <br>
                <a href="javascript:;" class="btn btn-primary m-r-5 " id="btnGuardarCinemexValidacion"><i class="fa fa-save"></i> Guardar</a>
            </div>
        </div>
    </div>
</form>