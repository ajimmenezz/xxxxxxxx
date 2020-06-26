<!--
 * Description: Formulario Modelos para salas X4D
 *
 * @author: Alberto Barcenas
 *
-->
<!-- Empezando panel Modelo -->
<div id="panelModelo" class="panel panel-inverse">

    <!--Empezando cabecera del panel-->
    <div class="panel-heading">
        <div class="panel-heading-btn">
            <label id="btnRegresarListaCatalogoTiposSistema" class="btn btn-success btn-xs">
                <i class="fa fa fa-reply"></i> Regresar
            </label>    
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>                            
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>                            
        </div>
        <h4 class="panel-title">Elemento</h4>
    </div>
    <!--Finalizando cabecera del panel-->

    <!--Empezando cuerpo del panel-->
    <div class="panel-body">

        <form class="margin-bottom-0" id="formModelo" data-parsley-validate="true">
            <div class="row m-t-10">
                <div class="col-md-12">                        
                    <div class="form-group">
                        <h3 id='tituloModelo' class="m-t-10"></h3>
                        <div class="underline m-b-15 m-t-15"></div>
                    </div>
                </div>
            </div>
            <div class="row m-t-10">                
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Línea *</label>
                        <select id="selectEquipo" class="form-control" style="width: 100%" data-parsley-required="true">
                            <option value="">Seleccionar</option>
                            <?php
                            foreach ($lineas as $key => $value) {
                                $selected = "";
                                if ($ids[0]['IdEquipo'] == $value['Id']) {
                                    $selected = "selected";
                                }
                                echo '<option value="' . $value['Id'] . '" ' . $selected . '>' . $value['Nombre'] . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Marca *</label>
                        <select id="selectMarca" class="form-control" style="width: 100%" data-parsley-required="true">
                            <option value="">Seleccionar</option>
                            <?php
                            foreach ($marcas as $key => $value) {
                                $selected = "";
                                if ($ids[0]['IdMarca'] == $value['Id']) {
                                    $selected = "selected";
                                }
                                echo '<option value="' . $value['Id'] . '" ' . $selected . '>' . $value['Nombre'] . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row m-t-10">                
                <div class="col-md-8 col-sm-12 col-xs-12">
                    <div class="form-group">
                        <label>Nombre *</label>
                        <input type="text" class="form-control" id="inputNombreModelo" placeholder="Ingresa el nombre del modelo" style="width: 100%" data-parsley-required="true"/>                            
                    </div>
                </div>
                <div class="col-md-4 col-sm-12 col-xs-12">
                    <div class="form-group">
                        <label>Clave SAE</label>
                        <input type="text" value="<?php echo $ids[0]['ClaveSAE']; ?>" class="form-control" id="inputClaveSAE" placeholder="Ingresa la Clave SAE" style="width: 100%"/>                            
                    </div>
                </div>
            </div>
            <div class="row m-t-10">
                <div id='estatusModelo' class="col-md-6">
                    <div class="form-group">
                        <label>Estatus</label>
                        <select id="selectEstatusModelo" class="form-control" style="width: 100%">
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
                    <div class="errorFormularioModelo"></div>
                </div>
                <!--Finalizando Error-->
            </div>   
            <div class="row m-t-10">
                <div class="col-md-12">
                    <div class="form-group text-center">
                        <br>
                        <a href="javascript:;" class="btn btn-primary m-r-5 " id="btnGuardarModelo"><i class="fa fa-save"></i> Guardar</a>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <!--Finalizando cuerpo del panel-->
</div>
<!-- Finalizando panel Modelo -->  
