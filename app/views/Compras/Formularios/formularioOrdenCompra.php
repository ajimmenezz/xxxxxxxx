<h1 class="page-header">Nueva Orden de Compra</h1>
<div id="panelOrdenesDeCompra" class="panel panel-inverse">
    <div class="panel-heading">
        <div class="panel-heading-btn">
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>                            
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>                            
        </div>
        <h4 class="panel-title">Nueva Orden de Compra</h4>
    </div>
    <div class="panel-body">
        <div class="row"> 
            <div class="col-md-12">                        
                <div class="form-group">
                    <h3 class="m-t-10">Información para O.C.</h3>
                    <div class="underline m-b-15 m-t-15"></div>
                </div>
            </div>
        </div>
        <form class="margin-bottom-0" id="formNuevoPermisos" data-parsley-validate="true" >
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
                <div class="col-md-5">
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
                <div class="col-md-1">
                    <a href="javascript:;" id="iconoInformacionProveedor" data-toggle="tooltip" data-container="body" data-title="Dirección del proveedor ..." class="btn btn-inverse btn-icon btn-circle btn-lg m-t-20"><i class="fa fa-info"></i></a>
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
                <div class="col-md-5">
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
                <div class="col-md-1">
                    <a href="javascript:;" id="iconoInformacionAlmacen" data-toggle="tooltip" data-container="body" data-title="Dirección de almacen ..." class="btn btn-inverse btn-icon btn-circle btn-lg m-t-20"><i class="fa fa-info"></i></a>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="selectMonedaOrdenCompra">Moneda *</label>
                        <select id="selectMonedaOrdenCompra" class="form-control" style="width: 100%" data-parsley-required="true">
                            <option value="">Seleccionar...</option>
                            <option value="Dolares">Dolares</option>
                            <option value="Pesos">Pesos</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="inputTipoCambioOrdenCompra">Tipo de cambio *</label>
                        <input type="number" class="form-control" id="inputTipoCambioOrdenCompra" style="width: 100%" data-parsley-required="true"/>                            
                    </div>
                </div>
            </div>
            <div class="row"> 
                <div class="col-md-12">                        
                    <div class="form-group">
                        <h3 class="m-t-10">Partidas de la O.C.</h3>
                        <div class="underline m-b-15 m-t-15"></div>
                    </div>
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
                                echo '<option value="' . $item['Id'] . '">' . $item['Nombre'] . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="selectProyectoOrdenCompra">Proyecto *</label>
                        <select id="selectProyectoOrdenCompra" class="form-control" style="width: 100%" data-parsley-required="true">
                            <option value="">Seleccionar...</option>
                            <?php
                            foreach ($clientes as $item) {
                                echo '<option value="' . $item['Id'] . '">' . $item['Nombre'] . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="selectSucursalOrdenCompra">Sucursal *</label>
                        <select id="selectSucursalOrdenCompra" class="form-control" style="width: 100%" data-parsley-required="true">
                            <option value="">Seleccionar...</option>
                            <?php
                            foreach ($clientes as $item) {
                                echo '<option value="' . $item['Id'] . '">' . $item['Nombre'] . '</option>';
                            }
                            ?>
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
                            foreach ($clientes as $item) {
                                echo '<option value="' . $item['Id'] . '">' . $item['Nombre'] . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="selectBeneficiarioOrdenCompra">Beneficiario *</label>
                        <select id="selectBeneficiarioOrdenCompra" class="form-control" style="width: 100%" data-parsley-required="true">
                            <option value="">Seleccionar...</option>
                            <?php
                            foreach ($clientes as $item) {
                                echo '<option value="' . $item['Id'] . '">' . $item['Nombre'] . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-md-12 m-t-10 text-center">
                <a href="javascript:;" class="btn btn-success m-r-5 " id="btnNuevoPermiso"><i class="fa fa-plus"></i> Agregar</a>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <div class="form-inline muestraCarga"></div>
                </div>
            </div>
            <!--Empezando error--> 
            <div class="col-md-12">
                <div class="errorPermiso"></div>
            </div>
            <!--Finalizando Error-->
        </form>
    </div>
</div>