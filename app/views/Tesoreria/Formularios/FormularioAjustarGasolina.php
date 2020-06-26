<div class="row">
    <div class="col-md-6 col-sm-6 col-xs-12">
        <h1 class="page-header">Auste de Gasolina</h1>
    </div>
    <div class="col-md-6 col-xs-6 text-right">
        <label id="btnRegresar" class="btn btn-success">
            <i class="fa fa fa-reply"></i> Regresar
        </label> 
    </div>
</div>    
<div id="panelRegistrarAjusteGasolina" class="panel panel-inverse">
    <!--Empezando cabecera del panel-->
    <div class="panel-heading">
        <h4 class="panel-title">Registrar Ajuste de Gasolina</h4>
    </div>
    <!--Finalizando cabecera del panel-->
    <!--Empezando cuerpo del panel-->
    <div class="panel-body">
        <div class="row">             
            <div class="col-md-12">  
                <div class="form-group">
                    <div class="col-md-12">
                        <h4>Ajustar gasolina a "<?php echo $usuario; ?>"</h4>
                    </div>
                    <div class="col-md-12">
                        <div class="underline m-b-15 m-t-5"></div>
                    </div>
                    <!--Finalizando Separador-->
                </div>    
            </div> 
        </div> 
        <form id="form-ajustar-gasolina" data-parsley-validate="true">
            <div class="row m-t-5">
                <div class="col-md-4 col-sm-6 col-xs-12">
                    <div class="form-group">
                        <label class="f-s-15 f-w-600">Fecha de Ajuste: *</label>
                        <input type="datetime-local" id="txtDate" value="<?php echo $date = date('Y-m-d\TH:i'); ?>" class="form-control" data-parsley-pattern="^\d\d\d\d-(0?[1-9]|1[0-2])-(0?[1-9]|[12][0-9]|3[01])T(0[0-9]|1[0-9]|2[0-3]):([0-9]|[0-5][0-9])$" required/>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6 col-xs-12">
                    <div class="form-group">
                        <label class="f-s-15 f-w-600">Monto Real de Gasolina: *</label>
                        <div class="input-group">
                            <span class="input-group-addon">$</span>
                            <input type="text" id="txtMonto" class="form-control" placeholder="<?php echo $saldoGasolina; ?>" value="<?php echo $saldoGasolina; ?>" data-parsley-type="number" required>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <!--Empezando error--> 
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div id="errorMessageAjusteGasolina"></div>
            </div>
        </div>
        <!--Finalizando Error-->
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12 text-center">
                <a id="btnGuardarAjusteGasolina" class="btn btn-success f-w-600 f-s-15">Ajustar Gasolinas</a>
            </div>
        </div>
    </div>  
</div>
<!--Finalizando cuerpo del panel-->
</div>
