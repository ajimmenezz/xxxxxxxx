<div class="row">
    <div class="col-md-6 col-sm-6 col-xs-12">
        <h1 class="page-header">Registrar Comprobante</h1>
    </div>
    <div class="col-md-6 col-xs-6 text-right">
        <label id="btnRegresar" class="btn btn-success">
            <i class="fa fa fa-reply"></i> Regresar
        </label> 
    </div>
</div>    
<div id="panelRegistrarComprobante" class="panel panel-inverse">
    <!--Empezando cabecera del panel-->
    <div class="panel-heading">
        <h4 class="panel-title">Registrar Comprobante</h4>
    </div>
    <!--Finalizando cabecera del panel-->
    <!--Empezando cuerpo del panel-->
    <div class="panel-body">
        <div class="row">             
            <div class="col-md-12">  
                <div class="form-group">
                    <div class="col-md-12">
                        <h4>Registrar Comprobante de gasto en Fondo Fijo</h4>
                    </div>
                    <div class="col-md-12">
                        <div class="underline m-b-15 m-t-5"></div>
                    </div>
                    <!--Finalizando Separador-->
                </div>    
            </div> 
        </div>  
        <form id="form-registrar-comprobante" data-parsley-validate="true">
            <div class="row m-t-5">
                <div class="col-md-4 col-sm-6 col-xs-12">
                    <div class="form-group">
                        <label class="f-s-15 f-w-600">Fecha del Gasto: *</label>
                        <input type="datetime-local" id="txtDate" value="<?php echo $date = date('Y-m-d\TH:i'); ?>" class="form-control" data-parsley-pattern="^\d\d\d\d-(0?[1-9]|1[0-2])-(0?[1-9]|[12][0-9]|3[01])T(00|[0-9]|1[0-9]|2[0-3]):([0-9]|[0-5][0-9])$" required/>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6 col-xs-12">
                    <div class="form-group">
                        <label class="f-s-15 f-w-600">Concepto: *</label>
                        <select id="listConceptos" class="form-control" style="width: 100% !important" data-parsley-required="true">
                            <option value="">Seleccionar . . .</option>
                            <?php
                            if (isset($conceptos) && count($conceptos) > 0) {
                                foreach ($conceptos as $key => $value) {
                                    if ($value['Flag'] == 1) {
                                        echo '<option data-comprobante="' . $value['TiposComprobante'] . '" value="' . $value['Id'] . '">' . $value['Nombre'] . '</option>';
                                    }
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6 col-xs-12">
                    <div class="form-group">
                        <label class="f-s-15 f-w-600">Monto Gastado: *</label>
                        <div class="input-group">
                            <span class="input-group-addon">$</span>
                            <input type="text" id="txtMonto" class="form-control" placeholder="59.90" value="" data-parsley-type="number" required disabled="">
                        </div>
                    </div>
                </div>
            </div>
            <div id="warningMontos" class="row hidden">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="alert alert-warning fade in m-b-15">
                        <strong>Alerta!</strong>
                        El monto que está ingresando es superior al presupuesto autorizado (<span id="warningMontoMaximo"></span>). Si continua con el registro, tendrá que ser autorizado por su supervisor para verse reflejado en su comprobación.                      
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3 col-sm-4 col-xs-12">
                    <div class="form-group">
                        <label class="f-s-15 f-w-600">Ticket:</label>
                        <select id="listTickets" class="form-control" style="width: 100% !important">
                            <option value="">Seleccionar . . .</option>
                            <?php
                            if (isset($tickets) && count($tickets) > 0) {
                                foreach ($tickets as $key => $value) {
                                    echo '<option value="' . $value['Ticket'] . '">' . $value['Ticket'] . '</option>';
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-9 col-sm-8 col-xs-12">
                    <div class="form-group">
                        <label class="f-s-15 f-w-600">Servicio:</label>
                        <select id="listServicios" class="form-control" style="width: 100% !important" disabled="">
                            <option value="">Seleccionar . . .</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class="form-group">
                        <label class="f-s-15 f-w-600">Origen:</label>
                        <select id="listOrigenes" class="form-control" style="width: 100% !important">
                            <option value="">Seleccionar . . .</option>
                            <?php
                            if (isset($sucursales) && count($sucursales) > 0) {
                                foreach ($sucursales as $key => $value) {
                                    echo '<option value="' . $value['Id'] . '">' . $value['Nombre'] . '</option>';
                                }
                            }
                            ?>
                            <option value="o">Otro Origen</option>
                        </select>
                    </div>
                </div>
                <div id="divOtroOrigen" class="col-md-6 col-sm-6 col-xs-12 hidden">
                    <div class="form-group">
                        <label class="f-s-15 f-w-600">Describe "Otro" del campo "Origen":</label>
                        <input type="text" id="txtOrigen" class="form-control" value="" placeholder="Insurgentes Sur 1647, 03900, CDMX" />
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class="form-group">
                        <label class="f-s-15 f-w-600">Destino:</label>
                        <select id="listDestinos" class="form-control" style="width: 100% !important">
                            <option value="">Seleccionar . . .</option>
                            <?php
                            if (isset($sucursales) && count($sucursales) > 0) {
                                foreach ($sucursales as $key => $value) {
                                    echo '<option value="' . $value['Id'] . '">' . $value['Nombre'] . '</option>';
                                }
                            }
                            ?>
                            <option value="o">Otro Destino</option>
                        </select>
                    </div>
                </div>
                <div id="divOtroDestino" class="col-md-6 col-sm-6 col-xs-12 hidden">
                    <div class="form-group">
                        <label class="f-s-15 f-w-600">Describe "Otro" del campo "destino":</label>
                        <input type="text" id="txtDestino" class="form-control" value="" placeholder="Insurgentes Sur 1647, 03900, CDMX" />
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-8 col-sm-9 col-xs-12">
                    <div class="form-group">
                        <label class="f-s-15 f-w-600">Observaciones:</label>
                        <textarea class="form-control" id="textObservaciones" rows="5" placeholder="Observaciones del depósito" style="width: 100%" /></textarea> 
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="form-group">
                        <label class="f-w-600 f-s-13">Comprobante: *</label>
                        <input id="fotosDeposito" name="fotosDeposito[]" type="file" multiple=""/>    
                    </div>
                </div>
            </div>
        </form>
        <!--Empezando error--> 
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div id="errorMessageComprobante"></div>
            </div>
        </div>
        <!--Finalizando Error-->
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12 text-center">
                <a id="btnGuardarComprobante" class="btn btn-success">Registrar</a>
            </div>
        </div>
    </div>
    <!--Finalizando cuerpo del panel-->
</div>
