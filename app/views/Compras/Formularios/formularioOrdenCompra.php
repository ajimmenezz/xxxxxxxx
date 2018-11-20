<div class="row">
    <div class="col-md-6 col-xs-6">
        <h1 class="page-header">Nueva Orden de Compra</h1>
    </div>
    <div class="col-md-6 col-xs-6 text-right">
        <label id="btnRegresarOrdenesCompra" class="btn btn-success">
            <i class="fa fa fa-reply"></i> Regresar
        </label>  
    </div>
</div>
<div id="panelFormularioOrdenesDeCompra" class="panel panel-inverse">
    <div class="panel-heading">
        <div class="panel-heading-btn">
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>                            
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>                            
        </div>
        <h4 class="panel-title">Nueva Orden de Compra</h4>
    </div>
    <div class="panel-body">
        <div class="row"> 
            <div class="col-md-6 col-xs-6">
                <h3>Información para O.C.</h3>
            </div>
            <div class="col-md-6 col-xs-6 text-right">
                <h2 id="claveNuevaDocumentacion" style="color:red;"><?php echo $claveDocumentacion ?></h2>
            </div>
        </div>

        <div class="underline m-b-5"></div>

        <form class="margin-bottom-0" id="formNuevoPermisos" data-parsley-validate="true" >
            <fieldset>  
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="selectOrdenOrdenCompra">Orden *</label>
                            <select id="selectOrdenOrdenCompra" class="form-control" style="width: 100%" data-parsley-required="true">
                                <option value="">Seleccionar...</option>
                                <option value="Directa">Directa</option>
                                <option value="Requisicion">Requisición</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="inputFechaOrdenCompra">Fecha *</label>
                            <div id="inputFecha" class="input-group date calendario" >
                                <input id="inputFechaOrdenCompra" type="text" class="form-control" />
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="inputFechaRecOrdenCompra">Fecha Rec *</label>
                            <div id="inputFechaRec" class="input-group date calendario" >
                                <input id="inputFechaRecOrdenCompra" type="text" class="form-control"  />
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-5 col-xs-11">
                        <div class="form-group">
                            <label for="selectProveedorOrdenCompra">Proveedor *</label>
                            <select id="selectProveedorOrdenCompra" class="form-control" style="width: 100%" data-parsley-required="true">
                                <option value="">Seleccionar...</option>
                                <?php
                                foreach ($proveedores as $item) {
                                    echo '<option data-direccion="' . $item['DIRECCION'] . '" value="' . $item['CLAVE'] . '">' . $item['NOMBRE'] . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-1 col-xs-1">
                        <a href="javascript:;" id="iconoInformacionProveedor" data-toggle="tooltip" data-container="body" data-title="Dirección del proveedor ..." class="btn btn-inverse btn-icon btn-circle btn-lg m-t-20"><i class="fa fa-info"></i></a>
                    </div>
                </div>
                <div id="divRequisicion" class="row hidden">
                    <div class="col-md-5 col-xs-11">
                        <div class="form-group">
                            <label for="selectRequisicionesOrdenCompra">Requisiciones *</label>
                            <select id="selectRequisicionesOrdenCompra" class="form-control" style="width: 100%" data-parsley-required="true">
                                <option value="">Seleccionar...</option>
                                <?php
                                foreach ($requisiciones as $item) {
                                    echo '<option data-fecha-requisicion="' . $item['FECHA_DOC'] . '" value="' . $item['CVE_DOC'] . '">' . $item['CVE_DOC'] . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-1 col-xs-1">
                        <a href="javascript:;" id="iconoInformacionRequisicion" data-toggle="tooltip" data-container="body" data-title="Fecha de la requisición ..." class="btn btn-inverse btn-icon btn-circle btn-lg m-t-20"><i class="fa fa-info"></i></a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="inputReferenciaOrdenCompra">Referencia Proveedor *</label>
                            <input type="text" class="form-control" id="inputReferenciaOrdenCompra" placeholder="Max 20 caracteres"  maxlength="20" style="width: 100%" data-parsley-required="true"/>                            
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="selectEsquemaOrdenCompra">Esquema *</label>
                            <select id="selectEsquemaOrdenCompra" class="form-control" style="width: 100%" data-parsley-required="true">
                                <option value="">Seleccionar...</option>
                                <option value="16">16% IVA</option>
                                <option value="11">11% IVA</option>
                                <option value="0">0% IVA</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="inputDescuentoOrdenCompra">Descuento *</label>
                            <input type="number" class="form-control" id="inputDescuentoOrdenCompra" value="0.00000" placeholder="0.00000" style="width: 100%" data-parsley-required="true"/>                                
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="inputDescuentoFinancieroOrdenCompra">Descuento Financiero *</label>
                            <input type="text" class="form-control" id="inputDescuentoFinancieroOrdenCompra" value="0.00000" placeholder="0.00000" style="width: 100%" data-parsley-required="true"/>                            
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="inputEntregaAOrdenCompra">Entrega a *</label>
                            <input type="text" class="form-control" id="inputEntregaAOrdenCompra" placeholder="Max 25 caracteres" maxlength="25" style="width: 100%" data-parsley-required="true"/>                            
                        </div>
                    </div>
                    <div class="col-md-5 col-xs-11">
                        <div class="form-group">
                            <label for="selectAlmacenOrdenCompra">Almacén *</label>
                            <select id="selectAlmacenOrdenCompra" class="form-control" style="width: 100%" data-parsley-required="true">
                                <option value="">Seleccionar...</option>
                                <?php
                                foreach ($almacenes as $item) {
                                    echo '<option data-direccion="' . $item['DIRECCION'] . '"value="' . $item['CVE_ALM'] . '">' . $item['DESCR'] . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-1 col-xs-1">
                        <a href="javascript:;" id="iconoInformacionAlmacen" data-toggle="tooltip" data-container="body" data-title="Dirección de almacen ..." class="btn btn-inverse btn-icon btn-circle btn-lg m-t-20"><i class="fa fa-info"></i></a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="inputDireccionEntregaOrdenCompra">Dirección de entrega *</label>
                            <input type="text" class="form-control" id="inputDireccionEntregaOrdenCompra" style="width: 100%" data-parsley-required="true"/>                            
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="selectMonedaOrdenCompra">Moneda *</label>
                            <select id="selectMonedaOrdenCompra" class="form-control" style="width: 100%" data-parsley-required="true">
                                <option value="">Seleccionar...</option>
                                <?php
                                foreach ($tiposMonedas as $item) {
                                    echo '<option data-tipo-cambio="' . $item['TCAMBIO'] . '" value="' . $item['NUM_MONED'] . '">' . $item['DESCR'] . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="inputTipoCambioOrdenCompra">Tipo de cambio *</label>
                            <input type="text" class="form-control" id="inputTipoCambioOrdenCompra" style="width: 100%" data-parsley-required="true" />                            
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">                                    
                        <div class="form-group">
                            <label for="textAreaObservacionesOrdenCompra">Observaciones del Documento *</label>
                            <textarea id="textAreaObservacionesOrdenCompra" class="form-control " placeholder="Ingrese las observaciones para el documento PDF" rows="3" ></textarea>
                        </div>
                    </div>
                </div>

                <div class="row"> 
                    <div class="form-group">
                        <div class="col-md-12">
                            <h3 class="m-t-10">Partidas de la O.C.</h3>
                        </div>
                    </div>
                </div>

                <div class="underline m-t-5"></div>

                <div class="row m-t-15">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="table-responsive">
                            <table id="data-table-partidas-oc" class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%">
                                <thead>
                                    <tr>                
                                        <th class="all">Clave</th>                    
                                        <th class="all">Producto</th>                    
                                        <th class="all" style="max-width: 150px !important;">Unidad</th>
                                        <th class="all" style="max-width: 150px !important;">Cantidad</th>
                                        <th class="all" style="max-width: 150px !important;">Descuento</th>
                                        <th class="all" style="max-width: 150px !important;">Costo por Unidad</th>
                                        <th class="all" style="max-width: 150px !important;">Subtotal por partida</th>
                                        <th class="never">NumeroPartida</th>
                                    </tr>
                                </thead>
                                <tbody>        
                                    <tr>
                                        <td>
                                            <div id="partidaClave0" data-partida-requisicion="0">
                                                <span id="botonAgregarObservaciones0" class="fa-stack text-success">
                                                    <i class="fa fa-circle fa-stack-2x"></i>
                                                    <i class="fa fa-plus fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </div>
                                        </td>
                                        <td>
                                            <select id="selectProductoPartida0" class="form-control" style="width: 100%" data-numero-fila="0" data-parsley-required="true">
                                                <option value="">Seleccionar...</option>
                                                <?php
                                                foreach ($productos as $item) {
                                                    echo '<option data-costo-unidad="' . $item['COSTO_UNIDAD'] . '" data-unidad1="' . $item['UNI_MED'] . '" data-unidad2="' . $item['UNI_ALT'] . '" value="' . $item['CVE_ART'] . '">' . $item['DESCR'] . ' (' . trim($item['CVE_ART']) .')</option>';
                                                }
                                                ?>
                                            </select>
                                        </td>
                                        <td class="text-center">
                                            <select id="selectUnidadPartida0" class="form-control" style="width: 100%" data-parsley-required="true">
                                                <option value="">Seleccionar...</option>
                                            </select>
                                        </td>
                                        <td class="text-center">
                                            <input id="cantidad0" type="number" class="form-control" value="0.0000" min="0"/>
                                        </td>
                                        <td class="text-center">
                                            <input id="descuento0" type="number" class="form-control" value="0.0000" min="0"/>
                                        </td>
                                        <td class="text-center">
                                            <input id="costoUnidad0" type="number" class="form-control" value="0.000000" min="0"/>
                                        </td>
                                        <td class="text-center">
                                            <input id="subtotalPartida0" type="number" class="form-control" value="0.00" min="0" disabled/>
                                        </td>
                                        <td>
                                            0
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div id="mensajeEliminarFila" class="row hidden">
                    <div class="col-md-12 m-t-5">
                        <div class="alert alert-warning fade in m-b-15">                            
                            Para eliminar una fila manten presionado el botón izquiedo del mause.                            
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group text-right">
                            <a href="javascript:;" class="btn btn-success btn-lg " id="btnAgregarPartidaFila"><i class="fa fa-plus"></i></a>
                        </div>
                    </div>
                </div>
                <div class="row m-t-15">
                    <div class="col-md-12">
                        <div class="errorTablaPartida"></div>
                    </div>
                </div>

                <div class="row"> 
                    <div class="col-md-12">                        
                        <div class="form-group">
                            <h3 class="m-t-10">Información para el Gasto</h3>
                            <div class="underline m-b-15 m-t-15"></div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="selectClienteOrdenCompra">Cliente *</label>
                            <select id="selectClienteOrdenCompra" class="form-control" style="width: 100%" data-parsley-required="true">
                                <option value="">Seleccionar...</option>
                                <?php
                                foreach ($clientes as $item) {
                                    echo '<option value="' . $item['ID'] . '">' . $item['Nombre'] . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="selectProyectoOrdenCompra">Proyecto *</label>
                            <select id="selectProyectoOrdenCompra" class="form-control" style="width: 100%" data-parsley-required="true" disabled>
                                <option value="">Seleccionar...</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="selectSucursalOrdenCompra">Sucursal *</label>
                            <select id="selectSucursalOrdenCompra" class="form-control" style="width: 100%" data-parsley-required="true" disabled>
                                <option value="">Seleccionar...</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="selectTipoServicioOrdenCompra">Tipo de Servicio *</label>
                            <select id="selectTipoServicioOrdenCompra" class="form-control" style="width: 100%" data-parsley-required="true">
                                <option value="">Seleccionar...</option>
                                <?php
                                foreach ($tiposServicio as $item) {
                                    echo '<option value="' . $item['ID'] . '">' . $item['Nombre'] . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="selectTipoBeneficiarioOrdenCompra">Tipo de Beneficiario *</label>
                            <select id="selectTipoBeneficiarioOrdenCompra" class="form-control" style="width: 100%" disabled="" data-parsley-required="true">
                                <option value="">Selecciona...</option>
                                <?php
                                foreach ($tiposBeneficiario as $key => $value) {
                                    echo '<option value="' . $value['ID'] . '">' . $value['Nombre'] . '</option>';
                                }
                                ?>                                
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="selectBeneficiarioOrdenCompra">Beneficiario *</label>
                            <select id="selectBeneficiarioOrdenCompra" class="form-control" style="width: 100%" data-parsley-required="true" disabled>
                                <option value="">Seleccionar...</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="f-w-600 f-s-13">Orden de Compra</label>
                            <input type="text" class="form-control" id="inputClaveOrdenCompra" data-ultimo-documento="<?php echo $ultimoDocumento ?>" value="<?php echo $claveGAPSI ?>"  data-parsley-required="false" disabled/>
                        </div>
                    </div>
                </div>

                <!--Empezando error--> 
                <div class="row">
                    <div class="col-md-12">
                        <div id="errorGuardarOC"></div>
                    </div>
                </div>

                <!--Finalizando Error-->
                <div class="row">
                    <div class="col-md-12 m-t-10 text-center">
                        <a href="javascript:;" class="btn btn-primary m-r-5 " id="btnGuardarOC"><i class="fa fa-save"></i> GUARDAR O.C</a>
                    </div>
                </div>

            </fieldset>
        </form>
    </div>
</div>