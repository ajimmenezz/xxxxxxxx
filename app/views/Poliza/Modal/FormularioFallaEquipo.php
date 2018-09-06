<!--
 * Description: Formulario Falla de Equipo de Poliza
 *
 * @author: Alberto Barcenas
 *
-->
<!-- Empezando panel Falla Equipo -->
<div id="panelFallaEquipo" class="panel panel-inverse">

    <!--Empezando cabecera del panel-->
    <div class="panel-heading">
        <div class="panel-heading-btn">
            <label id="btnRegresarListaCatalogoFallas" class="btn btn-success btn-xs">
                <i class="fa fa fa-reply"></i> Regresar
            </label>    
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>                            
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>                            
        </div>
        <h4 class="panel-title">Fallas por Equipo</h4>
    </div>
    <!--Finalizando cabecera del panel-->

    <!--Empezando cuerpo del panel-->
    <div class="panel-body">

        <form class="margin-bottom-0" id="formFallaEquipo" data-parsley-validate="true">
            <div class="row m-t-10">
                <div class="col-md-12">                        
                    <div class="form-group">
                        <h3 id='tituloFallaEquipo' class="m-t-10"></h3>
                        <div class="underline m-b-15 m-t-15"></div>
                    </div>
                </div>
            </div>
            <div class="row m-t-10">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Tipo de Falla *</label>
                        <select id="selectTiposFallas" class="form-control" style="width: 100%" data-parsley-required="true">
                            <option value="">Seleccionar</option>
                            <?php
                            foreach ($tiposFallas as $item) {
                                echo '<option value="' . $item['Id'] . '">' . $item['Clasificacion'] . ' - ' . $item['Nombre'] . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Equipo *</label>
                        <select id="selectEquipo" class="form-control" style="width: 100%" data-parsley-required="true">
                            <option value="">Seleccionar</option>
                            <?php
                            foreach ($equipos as $item) {
                                echo '<option value="' . $item['Id'] . '">' . $item['Equipo'] . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row m-t-10">
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Falla *</label>
                        <input type="text" class="form-control" id="inputNombreFallaEquipo" placeholder="Ingresa la Falla" style="width: 100%" data-parsley-required="true"/>                            
                    </div>
                </div>
            </div>
            <div class="row m-t-10">
                <div id='estatusFallaEquipo' class="col-md-6">
                    <div class="form-group">
                        <label>Estatus</label>
                        <select id="selectEstatusFallaEquipo" class="form-control" style="width: 100%">
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
                    <div class="errorFormularioFallaEquipo"></div>
                </div>
                <!--Finalizando Error-->
            </div>   
            <div class="row m-t-10">
                <div class="col-md-12">
                    <div class="form-group text-center">
                        <br>
                        <a href="javascript:;" class="btn btn-primary m-r-5 " id="btnGuardarFallaEquipo"><i class="fa fa-save"></i> Guardar</a>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <!--Finalizando cuerpo del panel-->
</div>
<!-- Finalizando panel Falla Equipo -->  
