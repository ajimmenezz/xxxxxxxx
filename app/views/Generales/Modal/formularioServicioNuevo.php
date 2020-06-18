<!--Empezando seccion para nuevo servicio-->
<div id="seccionNuevoServicio" >    
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
    <form id="formNuevoServicio" class="margin-bottom-0" data-parsley-validate="true" enctype="multipart/form-data">
        <div class="row">
            <div class="col-md-12">          
                <div class="form-group">
                    <label for="servicioNuevo"> Tipo de servicio * </label>
                    <select id="selectTipoServicio" class="form-control" name="servicioNuevo" style="width: 100%" data-parsley-required="true" >
                        <option value="">Seleccionar</option>
                        <?php
                        foreach ($tipoServicio as $key => $value) {
                            echo '<option value="' . $value['Id'] . '">' . $value['Nombre'] . ' (' . $value['Departamento'] . ')</option>';
                        }
                        ?>
                    </select>                            
                </div>    
            </div>
        </div>
        <div class="row">
            <div id="content-selectAtiende" class="col-md-12">          
                <div class="form-group">
                    <label for="servicioNuevo"> Atiende * </label>
                    <select id="selectAtiendeServicio" class="form-control" name="atiendeServicio" style="width: 100%" data-parsley-required="true" >
                        <option value="">Seleccionar</option>
                        <?php
                        foreach ($atiende as $key => $value) {
                            echo '<option value="' . $value['IdUsuario'] . '" data="'. $value['EmailCorporativo'] .'">' . $value['Nombre'] . '</option>';
                        }
                        ?>
                    </select>                            
                </div>    
            </div>                        
        </div>
        <div class="row">
            <div class="col-md-12">          
                <div class="form-group">
                    <label for="servicioNuevo"> Descripción *</label>
                    <textarea class="form-control" id="inputDescripcionServicio" rows="5" placeholder="Describición breve ..." style="width: 100%" data-parsley-required="true"/> </textarea>                               
                </div>    
            </div>
        </div>
    </form>
    <!--Finalizando formulario para los servicios-->

    <!--Empezando boton para agregar servicio-->
    <div class="row">
        <div class="col-md-12 text-center">          
            <div class="form-group">
                <button id="btnAgregarServicio" type="button" class="btn btn-sm btn-primary m-r-5"><i class="fa fa-save"></i> Guardar Servicio</button>
                <button id="btnCancelarServicio" type="button" class="btn btn-sm btn-danger m-r-5"><i class="fa fa-times"></i> Cancelar</button>
            </div>    
        </div>
    </div>
    <!--Finalizando boton para agregar servicio-->  
</div>
<!--Finalizando seccion para nuevo servicio-->