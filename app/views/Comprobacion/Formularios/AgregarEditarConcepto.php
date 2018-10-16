<div class="row">
    <div class="col-md-6 col-sm-6 col-xs-12">
        <h1 class="page-header">Agregar Concepto</h1>
    </div>
    <div class="col-md-6 col-xs-6 text-right">
        <label id="btnRegresar" class="btn btn-success">
            <i class="fa fa fa-reply"></i> Regresar
        </label> 
    </div>
</div>    
<div id="panelAgregarConcepto" class="panel panel-inverse">
    <!--Empezando cabecera del panel-->
    <div class="panel-heading">
        <h4 class="panel-title">Agregar concepto</h4>
    </div>
    <!--Finalizando cabecera del panel-->
    <!--Empezando cuerpo del panel-->
    <div class="panel-body">
        <div class="row"> 
            <!--Empezando error--> 
            <div class="col-md-12">
                <div class="errorMessageConcepto"></div>
            </div>
            <!--Finalizando Error-->
            <div class="col-md-12">  
                <div class="form-group">
                    <div class="col-md-12">
                        <h4 class="m-t-10">Agregar nuevo concepto</h4>
                    </div>
                    <div class="col-md-12">
                        <div class="underline m-b-15 m-t-15"></div>
                    </div>
                    <!--Finalizando Separador-->
                </div>    
            </div> 
        </div>
        <form id="form-agregar-editar-concepto" data-parsley-validate="true">
            <div class="row">
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class="form-group">
                        <label class="f-w-600 f-s-13">Concepto: *</label>
                        <input type="text" id="txtConcepto" class="form-control" placeholder="Nuevo Concepto" data-parsley-required="true" />
                    </div>
                </div>
                <div class="col-md-3 col-sm-3 col-xs-6">
                    <div class="form-group">
                        <label class="f-w-600 f-s-13">Monto Máximo: *</label>
                        <div class="input-group">
                            <span class="input-group-addon">$</span>
                            <input type="number" class="form-control" placeholder="600.00" data-parsley-required="true">
                        </div>                        
                    </div>
                </div>
                <div class="col-md-3 col-sm-3 col-xs-6">
                    <div class="form-group">
                        <label class="f-w-600 f-s-13">¿Se considera extraordinario?: *</label>
                        <div>
                            <label class="radio-inline">
                                <input type="radio" name="radioExtraordinario" value="1">
                                Si
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="radioExtraordinario" value="0" checked="">
                                No
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <label class="checkbox-inline">
                        <input type="checkbox" value="">
                        Checkbox Label 1
                    </label>
                </div>
            </div>
        </form>
    </div>
    <!--Finalizando cuerpo del panel-->
</div>
