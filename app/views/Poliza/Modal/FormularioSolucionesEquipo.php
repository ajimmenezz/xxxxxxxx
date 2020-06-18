<!--
 * Description: Formulario para guardar los datos de Soluciones de Equipo
 *
 * @author: Alberto Barcenas
 *
-->
<form class="margin-bottom-0" id="formRegionCliente" data-parsley-validate="true">
    <div class="row m-t-10">
        <div class="col-md-12">                        
            <div class="form-group">
                <h3 id='tituloSolucionEquipo' class="m-t-10"></h3>
                <div class="underline m-b-15 m-t-15"></div>
            </div>
        </div>
    </div>
    <div class="row m-t-10">
        <div class="col-md-6">
            <div class="form-group">
                <label for="selectEquipoSolucionesEquipo">Equipo *</label>
                <select id="selectEquipoSolucionesEquipo" class="form-control" style="width: 100%" data-parsley-required="true">
                    <option value="">Seleccionar</option>
                    <?php
                    foreach ($equipos as $item) {
                        echo '<option value="' . $item['Id'] . '">' . $item['Nombre'] . '</option>';
                    }
                    ?>
                </select>
            </div>
        </div>
    </div>
    <div class="row m-t-10"> 
        <div class="col-md-6">
            <div class="form-group">
                <label for="inputNombreSolucionesEquipo">Nombre *</label>
                <input type="text" class="form-control" id="inputNombreSolucionesEquipo" placeholder="Ingresa Nombre de la solución de equipo" style="width: 100%" data-parsley-required="true"/>                            
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="inputDescripcionSolucionesEquipo">Descripcion</label>
                <input type="text" class="form-control" id="inputDescripcionSolucionesEquipo" placeholder="Ingresa la descripción de la solución de equipo" style="width: 100%" data-parsley-required="true"/>                            
            </div>
        </div>
    </div>
    <div id='estatusSolucionesEquipo' class="row m-t-10"> 
        <div class="col-md-6">
            <div class="form-group">
                <label for="selectEstatusSolucionesEquipo">Estatus</label>
                <select id="selectEstatusSolucionesEquipo" class="form-control" style="width: 100%">
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
            <div class="errorSolucionEquipo"></div>
        </div>
        <!--Finalizando Error-->
    </div>   
    <div class="row m-t-10">
        <div class="col-md-12">
            <div class="form-group text-center">
                <br>
                <a href="javascript:;" class="btn btn-primary m-r-5 " id="btnGuardarSolucionEquipo"><i class="fa fa-save"></i> Guardar</a>
            </div>
        </div>
    </div>
</form>