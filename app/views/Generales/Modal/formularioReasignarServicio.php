<!--
 * Description: Formulario para agregar ReasignarServicio
 *
 * @author: Alberto Barcenas
 *
-->
<div id="seccion-reasignar-servicio" class="panel panel-inverse panel-with-tabs" data-sortable-id="ui-unlimited-tabs-1">

    <div class="tab-content">

        <div class="panel-body">

            <!--Empezando formulario Reasignar Servicio -->
            <form class="margin-bottom-0" id="formReasignarServicioSinClasificar" data-parsley-validate="true">
                
               <!--Empezando Nuevo Atiende--> 
                <div class="row">
                    <div id="content-selectAtiende" class="col-md-12">          
                        <div class="form-group">
                            <label for="atiendeServicio"> Atiende * </label>
                            <select id="selectAtiendeReasignarServicio" class="form-control" name="atiendeReasignarServicio" style="width: 100%" data-parsley-required="true" >
                                <option value="">Seleccionar</option>
                                <?php
                                foreach ($atiende as $key => $value) {
                                    echo '<option value="' . $value['IdUsuario'] . '">' . $value['Nombre'] . '</option>';
                                }
                                ?>
                            </select>                            
                        </div>    
                    </div>                        
                </div>
               <!--Finalizando-->

                <!--Empezando Decripcion-->
                <div class="row">
                    <div class="col-md-12">                                    
                        <div class="form-group">
                            <label>Descripción *</label>
                            <textarea id="inputDescripcionReasignarServicio" class="form-control " placeholder="Indique la causa por la que se reasigna el Servicio" rows="3"></textarea>
                        </div>
                    </div>
                </div>
                <!--Finalizando-->

                <!--Empezando boton de agregar y mensaje de error-->
                <div class="row m-t-10">
                    <!--Empezando error--> 
                    <div class="col-md-12">
                        <div class="errorReasignarServicio"></div>
                    </div>
                    <!--Finalizando Error-->
                </div>
                <!--Finalizando-->

            </form>
            <!--Finalizando formulario Reasigar Servicio -->
        </div>

    </div>
</div>
