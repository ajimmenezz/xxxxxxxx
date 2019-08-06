<div class="row">
    <div class="col-md-9 col-sm-6 col-xs-12">
        <h1 class="page-header">Inventario del Almacén</h1>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12 text-right">
        <label id="btnRegresar" class="btn btn-success">
            <i class="fa fa fa-reply"></i> Regresar
        </label>
    </div>
</div>
<div id="panelAgregarProducto" class="panel panel-inverse">
    <div class="panel-heading">
        <div class="panel-heading-btn">
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
        </div>
        <h4 class="panel-title">Agregar producto al inventario</h4>
    </div>
    <div class="panel-body">
        <?php
        //        echo "<pre>", var_dump($modelos), "</pre>";
        ?>
        <form id="formAddProducto" data-parsley-validate="true">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-grup">
                        <h4 class="m-t-10">Agregar Producto al Inventario</h4>
                        <div class="underline m-b-15 m-t-15"></div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div id="errorAgregarProducto"></div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 col-sm-6 col-xs-12">
                    <div class="form-group">
                        <label class="f-w-700">Elemento *</label>
                        <select id="listModelos" class="form-control" style="width: 100%" data-parsley-required="true">
                            <option value="">Selecciona . . .</option>
                            <?php
                            if (isset($elementos) && count($elementos) > 0) {
                                foreach ($elementos as $key => $value) {
                                    echo '<option value="' . $value['Id'] . '">' . $value['Elemento'] . '</option>';
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6 col-xs-12">
                    <div class="form-group">
                        <label class="f-w-700">Subelemento </label>
                        <select id="listRefacciones" class="form-control" style="width: 100%">
                            <option value="">Selecciona . . .</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6 col-xs-12">
                    <div class="form-group">
                        <label class="f-w-700">Cantidad *</label>
                        <input class="form-control" id="txtCantidad" type="number" value="1" min="1" />
                    </div>
                </div>
            </div>
        </form>
        <div class="row m-t-15">
            <div class="col-md-12">
                <div class="form-grup">
                    <h4 class="m-t-10">Series de Productos</h4>
                    <div class="underline m-b-15 m-t-15"></div>
                </div>
            </div>
        </div>
        <?php
        if (isset($estatus) && count($estatus) > 0) {
            ?>
            <div class="row">
                <div class="col-md-12">
                    <label class="f-w-600">Marcar todo como:</label>
                    <p>
                        <?php
                        foreach ($estatus as $key => $value) {
                            echo '<a role="button" data-id="' . $value['Id'] . '" class="btnMarcarEstatusAll m-r-10 f-w-600">' . $value['Nombre'] . '</a>';
                        }
                        ?>
                    </p>
                </div>
            </div>
        <?php
        }
        ?>
        <div id="formSeriesCapture">
            <form id="formularioSeriesCapture" data-parsley-validate="true">
                <div class="row">
                    <div class="col-md-1 col-sm-2 col-xs-12">
                        <div class="form-grup">
                            <label class="f-w-600">Producto</label>
                            <input type="text" value="#1" disabled="disabled" class="form-control f-s-16 text-center" />
                        </div>
                    </div>
                    <div class="col-md-5 col-sm-5 col-xs-12">
                        <div class="form-group">
                            <label class="f-w-600">Serie</label>
                            <input class="form-control info-serie-1" type="text" placeholder="Introduce Serie" />
                        </div>
                    </div>
                    <div class="col-md-5 col-sm-5 col-xs-12">
                        <div class="form-group">
                            <label class="f-w-600">Estatus *</label>
                            <select id="list-info-estatus-1" class="form-control listEstatusProductos" data-parsley-required="true">
                                <option value="">Selecciona . . .</option>
                                <?php
                                if (isset($estatus) && count($estatus) > 0) {
                                    foreach ($estatus as $key => $value) {
                                        echo '<option value="' . $value['Id'] . '">' . $value['Nombre'] . '</option>';
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12 text-center">
                <a id="btnGuardarProductoSalas" class="btn btn-success m-15">Guardar producto(s)</a>
            </div>
        </div>
    </div>
</div>