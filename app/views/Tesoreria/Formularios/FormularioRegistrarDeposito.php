<div class="row">
    <div class="col-md-6 col-sm-6 col-xs-12">
        <h1 class="page-header">Registrar Depósito</h1>
    </div>
    <div class="col-md-6 col-xs-6 text-right">
        <label id="btnRegresar" class="btn btn-success">
            <i class="fa fa fa-reply"></i> Regresar
        </label> 
    </div>
</div>    
<div id="panelRegistrarDeposito" class="panel panel-inverse">
    <!--Empezando cabecera del panel-->
    <div class="panel-heading">
        <h4 class="panel-title">Registrar Depósito</h4>
    </div>
    <!--Finalizando cabecera del panel-->
    <!--Empezando cuerpo del panel-->
    <div class="panel-body">
        <div class="row">             
            <div class="col-md-12">  
                <div class="form-group">
                    <div class="col-md-12">
                        <h4>Registrar depósito para "<?php echo $usuario; ?>"</h4>
                    </div>
                    <div class="col-md-12">
                        <div class="underline m-b-15 m-t-5"></div>
                    </div>
                    <!--Finalizando Separador-->
                </div>    
            </div> 
        </div>  

        <?php
        if ($estatus != 1) {
            ?>
            <div class="row">
                <div class="col-md-8 col-sm-8 col-xs-12">
                    <div class="note note-warning">                    
                        <p class="f-w-600 f-s-13">El monto fijo para este usuario YA NO se encuentra activo. Por favor omita los depósitos a su cuenta.</p>
                    </div>
                </div>
            </div>
            <?php
        } else {
            ?>

            <div class="row">
                <div class="col-md-8 col-sm-8 col-xs-12">
                    <div class="note note-warning">                    
                        <p class="f-w-600 f-s-13">El monto calculado y sugerido para cubrir el fondo fijo es de $<?php echo number_format(((float) $monto - (float) $saldo), 2, '.', ','); ?></p>
                    </div>
                </div>
            </div>
            <form id="form-registrar-deposito" data-parsley-validate="true">
                <div class="row m-t-5">
                    <div class="col-md-4 col-sm-6 col-xs-12">
                        <div class="form-group">
                            <label class="f-s-15 f-w-600">Fecha de Depósito: *</label>
                            <input type="datetime-local" id="txtDate" value="<?php echo $date = date('Y-m-d\TH:i'); ?>" class="form-control" data-parsley-pattern="^\d\d\d\d-(0?[1-9]|1[0-2])-(0?[1-9]|[12][0-9]|3[01])T(00|[0-9]|1[0-9]|2[0-3]):([0-9]|[0-5][0-9])$" required/>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6 col-xs-12">
                        <div class="form-group">
                            <label class="f-s-15 f-w-600">Monto Depositado: *</label>
                            <div class="input-group">
                                <span class="input-group-addon">$</span>
                                <input type="text" id="txtMonto" class="form-control" placeholder="59.90" value="" data-parsley-type="number" required>
                            </div>
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
                            <label class="f-w-600 f-s-13">Evidencias de Depósito: *</label>
                            <input id="fotosDeposito" name="fotosDeposito[]" type="file" multiple=""/>    
                        </div>
                    </div>
                </div>
            </form>
            <!--Empezando error--> 
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div id="errorMessageDeposito"></div>
                </div>
            </div>
            <!--Finalizando Error-->
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12 text-center">
                    <a id="btnGuardarDeposito" class="btn btn-success">Registrar</a>
                </div>
            </div>

            <?php
        }
        ?>
    </div>
    <!--Finalizando cuerpo del panel-->
</div>
