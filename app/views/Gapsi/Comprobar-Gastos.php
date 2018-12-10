<div id="divDetalleGastos">
    <div id="divComprobanteGasto"></div>
    <div id="divFileComprobarGasto" class="content" style="display:none">
        <div id="panelListaGastos" class="panel panel-inverse">
            <div class="panel-heading">    
                <h4 class="panel-title">Comprobante o Factura</h4>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div id="errorComprobarGasto"></div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-5 col-sm-6 col-xs-12">
                        <div class="form-group">
                            <label class="f-w-600 f-s-13">Adjuntar archivos:*</label> 
                            <input id="inputArchivoComprobante"  name="inputArchivoComprobante[]" type="file" multiple />
                        </div>
                    </div>
                    <div class="col-md-2 col-sm-6 col-xs-12">
                        <div class="form-group">
                            <label class="f-w-600 f-s-13">Monto:*</label>    
                            <div class="input-group">
                                <span class="input-group-addon">$</span>
                                <input type="number" step="any" id="txtMonto" class="form-control"> 
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2 col-xs-6 m-t-25 text-right">
                        <div class="form-group">
                            <label id="btnSubirArchivo" class="btn btn-success">Subir Archivo</label>
                        </div>
                    </div>
                    <div class="col-md-3 col-xs-6 m-t-25">
                        <div class="form-group">
                            <label id="btnTerminaComprobacion" class="btn btn-success">Termina Comprobación</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>