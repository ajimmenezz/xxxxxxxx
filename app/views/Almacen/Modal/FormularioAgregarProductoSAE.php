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
                <div class="col-md-2 col-sm-4 col-xs-12">
                    <div class="form-group">
                        <label class="f-w-700">Filtrar:</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="txtFiltrarSAE">
                            <span class="input-group-addon" role="button" id="btnFilterSAEProducts"><i class="fa fa-search"></i></span>
                        </div>                        
                    </div>
                </div>
                <div class="col-md-8 col-sm-8 col-xs-12">
                    <div class="form-group">
                        <label class="f-w-700">Producto *</label>
                        <select id="listProductos" class="form-control" data-parsley-required="true">
                            <option value="">Selecciona . . .</option>
                            <?php
                            if (isset($productos) && count($productos) > 0) {
                                foreach ($productos as $key => $value) {
                                    echo '<option value="' . $value['Id'] . '">' . $value['Nombre'] . '</option>';
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-2 col-sm-12 col-xs-12">
                    <div class="form-group">
                        <label class="f-w-700">Cantidad *</label>
                        <input class="form-control" id="txtCantidad" type="number" value="1" min="1"  />
                    </div>
                </div>
            </div>
        </form>
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12 text-center">                    
                <a id="btnGuardarProductoSAE" class="btn btn-success m-15">Guardar producto(s)</a>
            </div>
        </div>        
    </div>        
</div>