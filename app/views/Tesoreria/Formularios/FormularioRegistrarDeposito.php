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

            <div class="row m-b-25">
                <div class="col-md-3 col-sm-3 col-xs-6">
                    <a href="javascript:;" data-id-concepto-registro="3" class="btn btn-lg btn-primary f-w-600 btnRegistrarDeposito">                    
                        <i class="fa fa-money fa-2x pull-left"></i>
                        Registrar depósito<br>
                        <small>SSO0101179Z7</small><br>
                        <small><?php echo '$' . number_format(abs($montoSiccob), 2, ".", ",") ?></small>
                        <input type="hidden" value="<?php echo number_format(abs($montoSiccob), 2, ".", "") ?>" id="hiddenMontoSiccob" />
                    </a>
                </div>
                <div class="col-md-3 col-sm-3 col-xs-6">
                    <a href="javascript:;" data-id-concepto-registro="4" class="btn btn-lg btn-info f-w-600 btnRegistrarDeposito">
                        <i class="fa fa-money fa-2x pull-left"></i>
                        Registrar depósito<br>
                        <small>RSD130305DI7</small><br>
                        <small><?php echo '$' . number_format(abs($montoResidig), 2, ".", ",") ?></small>
                        <input type="hidden" value="<?php echo number_format(abs($montoResidig), 2, ".", "") ?>" id="hiddenMontoResidig" />
                    </a>
                </div>
                <div class="col-md-3 col-sm-3 col-xs-6">                    
                    <a href="javascript:;" data-id-concepto-registro="2" class="btn btn-lg btn-inverse f-w-600 btnRegistrarDeposito">                    
                        <i class="fa fa-money fa-2x pull-left"></i>
                        Registrar depósito<br>
                        <small>GASOLINA</small><br>
                        <small><?php echo '$' . number_format(abs($montoGasolina), 2, ".", ",") ?></small>                        
                        <input type="hidden" value="<?php echo number_format(abs($montoGasolina), 2, ".", "") ?>" id="hiddenMontoGasolina" />
                    </a>
                </div>
                <div class="col-md-3 col-sm-3 col-xs-6">
                    <a href="javascript:;" data-id-concepto-registro="1" class="btn btn-lg btn-warning f-w-600 btnRegistrarDeposito">                    
                        <i class="fa fa-money fa-2x pull-left"></i>
                        Registrar depósito<br>
                        <small>OTROS</small><br>
                        <small>
                            <?php
                            $totalGasolina = abs($montoGasolina) + (($saldoGasolina > 0) ? $saldoGasolina : 0);

                            $totalMontoOtros = $monto - $saldo - $totalGasolina - abs($montoSiccob) - abs($montoResidig);

                            if ($totalMontoOtros <= 0) {
                                $totalMontoOtros = 0;
                            }
                            echo '$' . number_format($totalMontoOtros, 2, ".", ",");
                            ?>
                        </small>       
                        <input type="hidden" value="<?php echo number_format($totalMontoOtros, 2, ".", "") ?>" id="hiddenMontoOtros" />
                    </a>
                </div>
            </div>
            <form id="form-registrar-deposito" data-parsley-validate="true">
                <div class="row m-t-5">
                    <div class="col-md-4 col-sm-6 col-xs-12">
                        <div class="form-group">
                            <label class="f-s-15 f-w-600">Fecha de Depósito: *</label>
                            <input type="datetime-local" id="txtDate" value="<?php echo $date = date('Y-m-d\TH:i'); ?>" class="form-control" data-parsley-pattern="^\d\d\d\d-(0?[1-9]|1[0-2])-(0?[1-9]|[12][0-9]|3[01])T(0[0-9]|1[0-9]|2[0-3]):([0-9]|[0-5][0-9])$" required/>
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
                    <div class="col-md-4 col-sm-6 col-xs-12">
                        <div class="form-group">
                            <label class="f-s-15 f-w-600">Concepto de Registro: *</label>
                            <input type="hidden" id="hiddenConceptoRegistro" value="" />
                            <input type="text" id="txtConceptoRegistro" class="form-control f-w-600 f-s-15" value="" disabled="" />
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
                    <a id="btnGuardarDeposito" class="btn btn-success f-w-600 f-s-15">Registrar Deposito</a>
                </div>
            </div>

            <div class="row m-t-25">             
                <div class="col-md-12 col-sm-12 col-xs-12">                      
                    <h4>Registros comprobados sin pagar</h4>                        
                    <div class="underline m-b-15 m-t-5"></div>                        
                    <!--Finalizando Separador-->
                </div>    
            </div> 

            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="table-responsive">
                        <table id="table-comprobacion-sin-pago" class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%">
                            <thead>
                                <tr>
                                    <th class="never">Id</th>
                                    <th class="never">TipoRegistro</th>                            
                                    <th class="all">Concepto</th>
                                    <th class="all">Monto</th>
                                    <th class="all">Autoriza</th>
                                    <th class="all">Fecha de Autorización</th>
                                    <th class="all">Receptor (RFC)</th>                            
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (isset($listaSinPago) && count($listaSinPago) > 0) {
                                    foreach ($listaSinPago as $key => $value) {
                                        $monto = number_format(abs($value['Monto']), 2, ".", ",");

                                        echo '<tr>';
                                        echo '<td>' . $value['Id'] . '</td>';
                                        echo '<td>' . $value['TipoRegistro'] . '</td>';
                                        echo '<td>' . $value['Concepto'] . '</td>';
                                        echo '<td>$' . $monto . '</td>';
                                        echo '<td>' . $value['Autoriza'] . '</td>';
                                        echo '<td class="text-center">' . $value['FechaAutorizacion'] . '</td>';
                                        echo '<td class="text-center">' . $value['Receptor'] . '</td>';
                                        echo '</tr>';
                                    }
                                }
                                ?>                                        
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>  
        <?php
    }
    ?>
</div>
<!--Finalizando cuerpo del panel-->
</div>
