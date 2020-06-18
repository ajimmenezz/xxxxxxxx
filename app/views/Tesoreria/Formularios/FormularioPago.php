<form class="margin-bottom-0" id="formRegionCliente" data-parsley-validate="true">
    <div class="row">
        <div class="col-md-6">
            <h3 class="m-t-10">Pago de Factura <strong><?php echo $folioSerie; ?></strong></h3>
        </div>
        <div class="col-md-6">
            <div class="form-group text-right">
                <a href="javascript:;" class="btn btn-info btn-lg " id="btnDetallesFactura"><i class="fa fa-reorder"></i> Detalles</a>
            </div>
        </div>
        <!--Empezando Separador-->
        <div class="col-md-12">
            <div class="underline m-b-15 m-t-15"></div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label>Técnico:</label>
                <label><h5><strong><?php echo $datosFactura[0]['Tecnico']; ?></strong></h5></label>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label>Total a pagar:</label>
                <label><h5><strong>$<?php echo (float) $totalPago; ?></strong></h5></label>
            </div>
        </div>
        <div class="col-md-5">
            <div class="form-group">
                <label>Nombre a quien se paga:</label>
                <label><h5><strong><?php echo $emisor; ?></strong></h5></label>
            </div>
        </div>
    </div>
    <div id="minutaOriginal" class="row m-t-10"> 
        <div class="col-md-6 text-center">
            <div class="form-group">
                <div class="evidenciasMinuta">
                    <div class = "evidencia">
                        <a id="modalArchivoFacturaPDF" href="javascript:;">
                            <img src="\assets\img\Iconos\pdf_icon.png" alt="Lights" style = "width:100%">
                        </a>
                        <strong>PDF</strong>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 text-center">
            <div class="form-group">
                <div class="evidenciasMinuta">
                    <div class = "evidencia">
                        <a id="modalArchivoFacturaXML" href="javascript:;">
                            <img src="\assets\img\Iconos\xml_icon.png" alt="Lights" style = "width:100%">
                        </a>
                        <strong>XML</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Seccion de Evidencia de pago -->
    <div class="row">
        <div class="col-md-12">                                    
            <div class="form-group">
                <label for="evidenciasPago">Evidencía de pago *</label>
                <input id="evidenciasPago"  name="evidenciasPago[]" type="file" multiple/>
            </div>
        </div>
    </div>
    <!-- Finalizando -->

    <div class="row m-t-10">
        <!--Empezando error--> 
        <div class="col-md-12">
            <div id="errorPago"></div>
        </div>
        <!--Finalizando Error-->
    </div>   
    <div class="row m-t-10">
        <div class="col-md-12">
            <div class="form-group text-center">
                <br>
                <a href="javascript:;" class="btn btn-primary m-r-5 " id="btnSubirPago"><i class="fa fa-cloud-upload"></i> Subir Pago</a>
            </div>
        </div>
    </div>
</form>