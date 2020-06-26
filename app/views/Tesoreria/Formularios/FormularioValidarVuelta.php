<form class="margin-bottom-0" id="formRegionCliente" data-parsley-validate="true">
    <div class="row">
        <div class="col-md-12">
            <h3 class="m-t-10">Validación</h3>
        </div>
        <!--Empezando Separador-->
        <div class="col-md-12">
            <div class="underline m-b-15 m-t-15"></div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-9">
            <div class="row">
                <div class="col-md-4 col-xs-4">
                    <div class="form-group">
                        <label>Servicio:</label>
                        <label><h5><strong><?php echo $datosTablaVueltas[1]; ?></strong></h5></label>
                    </div>
                </div>
                <div class="col-md-6 col-xs-6">
                    <div class="form-group">
                        <label>Ticket:</label>
                        <label><h5><strong><?php echo $datosTablaVueltas[2]; ?></strong></h5></label>
                    </div>
                </div>
                <div class="col-md-2 col-xs-2">
                    <div class="form-group">
                        <label>Folio:</label>
                        <label><h5><strong><?php echo $datosTablaVueltas[3]; ?></strong></h5></label>
                    </div>
                </div>
            </div>
            <div class="row m-t-20">
                <div class="col-md-4 col-xs-4">
                    <div class="form-group">
                        <label>Sucursal:</label>
                        <label><h5><strong><?php echo $datosTablaVueltas[5]; ?></strong></h5></label>
                    </div>
                </div>
                <div class="col-md-6 col-xs-6">
                    <div class="form-group">
                        <label>Técnico:</label>
                        <label><h5><strong><?php echo $datosTablaVueltas[6]; ?></strong></h5></label>
                    </div>
                </div>
                <div class="col-md-2 col-xs-2">
                    <div class="form-group">
                        <label>Vuelta:</label>
                        <label><h5><strong><?php echo $datosTablaVueltas[4]; ?></strong></h5></label>
                    </div>
                </div>
            </div>
            <div class="row m-t-20">
                <div class="col-md-12 col-xs-12">
                    <div class="form-group">
                        <label>Estatus del Servicio:</label>
                        <label><h5><strong><font color="red"><?php echo $datosTablaVueltas[9]; ?></font></strong></h5></label>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div id="minutaOriginal" class="row text-center"> 
                <div class="col-md-12">
                    <!--<div class="form-group">-->
                        <div class="evidenciasMinuta">
                            <div class = "evidencia">
                                <a id="modalArchivoValidarVuelta" href="javascript:;">
                                    <img src="\assets\img\Iconos\pdf_icon.png" alt="Lights" style = "width:100%">
                                </a>
                                <strong>PDF</strong>
                            </div>
                        </div>
                    <!--</div>-->
                </div>
            </div>
        </div>
    </div>
    <div class="row"> 
        <div class="col-md-6">
            <div class="form-group">
                <label for="inputMontoValidarVuelta">Monto (sin IVA) *</label>
                <input type="number" class="form-control" id="inputMontoValidarVuelta" style="width: 100%" value="<?php echo $monto; ?>" data-parsley-required="true" disabled/>                            
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="inputViaticosValidarVuelta">Viatico (sin IVA) *</label>
                <input type="number" class="form-control" id="inputViaticosValidarVuelta" style="width: 100%" value="<?php echo $viatico; ?>" data-parsley-required="true"/>                            
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">                                    
            <div class="form-group">
                <label for="inputObservacionesValidarVuelta">Observaciones</label>
                <textarea id="inputObservacionesValidarVuelta" class="form-control " placeholder="Observaciones." rows="3" ></textarea>
            </div>
        </div>
    </div>
    <!--Finalizando-->
    <div class="row m-t-10">
        <!--Empezando error--> 
        <div class="col-md-12">
            <div id="erroValidarVuelta"></div>
        </div>
        <!--Finalizando Error-->
    </div>   
    <div class="row">
        <div class="col-md-6">
            <div class="form-group text-center">
                <br>
                <a href="javascript:;" class="btn btn-danger m-r-5 " id="btnRechazarVuelta"><i class="fa fa-times"></i> Rechazar Vuelta</a>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group text-center">
                <br>
                <a href="javascript:;" class="btn btn-primary m-r-5 " id="btnValidarVuelta"><i class="fa fa-check"></i> Validar Vuelta</a>
            </div>
        </div>
    </div>
</form>