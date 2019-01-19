<!--<div id="divEnvioConGuia" class="content">-->
<div id="panelEnvioConGuia" class="panel panel-inverse">
    <div class="panel-heading">
        <h4 class="panel-title">2) Envío a Almacén</h4>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-6 col-sm-6 col-xs-6">
                <h4>Documentación de envío a Almacén</h4>
            </div>
            <div class="col-md-6 col-sm-6 col-xs-6">
                <div class="form-group text-right">
                    <a href="javascript:;" class="btn btn-sm btn-success f-s-13" id="solicitarGuia">Solicitar Guía</a>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="underline m-b-10"></div>
        </div>
        <form id="formEnvioAlmacen" data-parsley-validate="true">
            <div class="row">
                <div class="col-md-4 col-sm-6 col-xs-12">
                    <div class="form-group">
                        <label class="f-w-600 f-s-13">Paquetería *</label>
                        <select id="listPaqueteria" class="form-control" style="width: 100%" data-parsley-required="true">
                            <option value="">Selecciona . . .</option>
                            <?php
                            foreach ($paqueterias as $item) {
                                echo '<option value="' . $item['Id'] . '">' . $item['Nombre'] . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6 col-xs-12">
                    <div class="form-group">
                        <label class="f-w-600 f-s-13"># Guía *</label>
                        <input type="text" class="form-control" id="guia" placeholder="55666476146549"  data-parsley-required="true"/>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6 col-xs-12">
                    <div class="form-group">
                        <label class="f-w-600 f-s-13">Fecha de Envío *</label>
                        <input type="datetime-local" id="fechaValidacion" value="<?php echo $date = date('Y-m-d\TH:i'); ?>" class="form-control" data-parsley-pattern="^\d\d\d\d-(0?[1-9]|1[0-2])-(0?[1-9]|[12][0-9]|3[01])T(|0[0-9]|1[0-9]|2[0-3]):([0-9]|[0-5][0-9])$" required/>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-9 col-sm-12 col-xs-12">
                    <div class="form-group">
                        <label class="f-w-600 f-s-13">Evidencia del envío</label> 
                        <input id="evidenciaEnvioGuia"  name="evidenciaEnvioGuia[]" type="file" multiple />
                    </div>
                </div>
            </div>
        </form>
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div id="errorFormularioEnvio"></div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12 text-center">
                <a id="btnGuardarEnvio" class="btn btn-success btn-sm m-t-10 m-r-10 f-w-600 f-s-13">Guardar Envío</a>
            </div>
        </div>
    </div>
</div>    
<!--</div>-->