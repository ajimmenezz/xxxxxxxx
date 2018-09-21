<div id="divFormularioSolicitud" class="content">    
    <h1 class="page-header">Solicitud de Gasto</h1>
    <div id="panelFormularioGasto" class="panel panel-inverse">        
        <div class="panel-heading">    
            <h4 class="panel-title">Solicitar Gasto</h4>
        </div>
        <div class="panel-body">
            <?php
//            echo "<pre>";
//            var_dump($datos['Sucursales']);
//            echo "</pre>";
            ?>
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <h4>Solicitar Gasto</h4>
                    <div class="underline m-b-10"></div>
                </div>
            </div>
            <form id="formGasto" data-parsley-validate="true">
                <div class="row">
                    <div class="col-md-4 col-sm-6 col-xs-12">
                        <div class="form-group">
                            <label class="f-w-600 f-s-13">Cliente:*</label>
                            <select id="listClientes" class="form-control" style="width: 100%" data-parsley-required="true">
                                <option value="">Selecciona . . .</option>
                                <?php
                                if (isset($datos['Clientes']) && count($datos['Clientes']) > 0) {
                                    foreach ($datos['Clientes'] as $key => $value) {
                                        echo '<option value="' . $value['ID'] . '">' . $value['Nombre'] . '</option>';
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>                 
                    <div class="col-md-4 col-sm-6 col-xs-12">
                        <div class="form-group">
                            <label class="f-w-600 f-s-13">Proyecto:*</label>
                            <select id="listProyectos" class="form-control" style="width: 100%" disabled="" data-parsley-required="true">
                                <option value="">Selecciona . . .</option>
                            </select>
                        </div>
                    </div>                 
                    <div class="col-md-4 col-sm-6 col-xs-12">
                        <div class="form-group">
                            <label class="f-w-600 f-s-13">Sucursal:*</label>
                            <select id="listSucursales" class="form-control" style="width: 100%" disabled="" data-parsley-required="true">
                                <option value="">Selecciona . . .</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 col-sm-6 col-xs-12">
                        <div class="form-group">
                            <label class="f-w-600 f-s-13">Tipo de Servicio:*</label>
                            <select id="listTiposServicio" class="form-control" style="width: 100%" data-parsley-required="true">
                                <option value="">Selecciona . . .</option>
                                <?php
                                if (isset($datos['TiposServicio']) && count($datos['TiposServicio']) > 0) {
                                    foreach ($datos['TiposServicio'] as $key => $value) {
                                        echo '<option value="' . $value['ID'] . '">' . $value['Nombre'] . '</option>';
                                    }
                                }
                                ?>                                
                            </select>
                        </div>
                    </div>                
                    <div class="col-md-4 col-sm-6 col-xs-12">
                        <div class="form-group">
                            <label class="f-w-600 f-s-13">Tipo de Beneficiario:*</label>
                            <select id="listTipoBeneficiario" class="form-control" style="width: 100%" data-parsley-required="true">
                                <option value="">Selecciona . . .</option>
                                <?php
                                if (isset($datos['TiposBeneficiario']) && count($datos['TiposBeneficiario']) > 0) {
                                    foreach ($datos['TiposBeneficiario'] as $key => $value) {
                                        echo '<option value="' . $value['ID'] . '">' . $value['Nombre'] . '</option>';
                                    }
                                }
                                ?>                                
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6 col-xs-12">
                        <div class="form-group">
                            <label class="f-w-600 f-s-13">Beneficiario:*</label>
                            <select id="listBeneficiarios" class="form-control" style="width: 100%" disabled="" data-parsley-required="true">
                                <option value="">Selecciona . . .</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 col-sm-6 col-xs-12">
                        <div class="form-group">
                            <label class="f-w-600 f-s-13">Tipo de Transferencia:*</label>
                            <select id="listTipoTrasnferencia" class="form-control" style="width: 100%">
                                <option value="">Selecciona . . .</option>
                                <?php
                                if (isset($datos['TiposTransferencia']) && count($datos['TiposTransferencia']) > 0) {
                                    foreach ($datos['TiposTransferencia'] as $key => $value) {
                                        echo '<option value="' . $value['ID'] . '">' . $value['Nombre'] . '</option>';
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>            
                <div class="row m-t-10">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <h4>Conceptos del Gasto</h4>
                        <div class="underline m-b-10"></div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div id="errorConceptoGasto"></div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <div class="form-group">
                            <label class="f-w-600 f-s-13">Categoría:*</label>
                            <select id="listCategoria" class="form-control" style="width: 100%" disabled="">
                                <option value="">Selecciona . . .</option>                            
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <div class="form-group">
                            <label class="f-w-600 f-s-13">Subcategoría:*</label>
                            <select id="listSubcategoria" class="form-control" style="width: 100%" disabled="">
                                <option value="">Selecciona . . .</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <div class="form-group">
                            <label class="f-w-600 f-s-13">Concepto:*</label>
                            <select id="listConceptos" class="form-control" style="width: 100%" disabled="">
                                <option value="">Selecciona . . .</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <div class="form-group">
                            <label class="f-w-600 f-s-13">Monto:*</label>    
                            <div class="input-group">
                                <span class="input-group-addon">$</span>
                                <input type="number" step="any" id="txtMonto" class="form-control"> 
                                <div class="input-group-btn m-l-10">
                                    <a id="btnAddConcepto" class="btn btn-success"><i class="fa fa-plus"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row m-t-10">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="table-responsive">
                            <table id="table-conceptos-gasto" class="table table-condensed table-stripped">
                                <thead>
                                    <tr>
                                        <th>Categoría</th>
                                        <th>Subcategoría</th>
                                        <th>Concepto</th>
                                        <th>Monto</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="3" style="text-align: right !important;" class="f-w-700 f-s-15">TOTAL</th>
                                        <th id="columna-total" class="f-w-700 f-s-15">$0.00</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="row m-t-10">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <h4>Información Adicional</h4>
                        <div class="underline m-b-10"></div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-8 col-sm-8 col-xs-12">
                        <div class="form-group">
                            <label class="f-w-600 f-s-13">Descripción:*</label>
                            <input type="text" class="form-control" id="txtDescripcion" placeholder="Breve Descripción del Gasto"  data-parsley-required="true" data-parsley-required="true"/>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-4 col-xs-12">
                        <div class="form-group">
                            <label class="f-w-600 f-s-13">Moneda:*</label>
                            <select class="form-control" id="listMonedas"  data-parsley-required="true">
                                <option value="" selected="">Selecciona . . .</option>
                                <option value="MN">MN (Peso Mexicano)</option>
                                <option value="USD">USD (Dolar Americano)</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label class="f-w-600 f-s-13">Archivos Adicionales</label>
                            <input id="fotosGasto" name="fotosGasto[]" type="file" multiple=""/>    
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 col-sm-9 col-xs-12">
                        <div class="form-group">
                            <label class="f-w-600 f-s-13">Observaciones:</label>
                            <textarea class="form-control" id="txtObservaciones" rows="5" placeholder="Observaciones adicionales de la solicitud de gasto."></textarea>
                        </div>
                    </div>
                </div>
            </form>
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div id="errorFormulario"></div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12 text-center">
                    <a id="btnSolicitarGasto" class="btn btn-info m-t-10 m-r-10 f-w-600 f-s-15">Solicitar Gasto</a>
                    <a id="btnLimpiarFormulario" class="btn btn-default m-t-10 m-l-10 f-w-600 f-s-15">Limpiar Formulario</a>
                </div>
            </div>
        </div>        
    </div>        
</div>    

<!--Empezando seccion para la captura del inventario por sala-->
<div id="capturePageInventario" class="content" style="display:none"></div>
<!--Finalizando seccion para la captura del inventario por sala-->