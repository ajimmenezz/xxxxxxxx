<div class="row">
    <div class="col-md-9 col-sm-6 col-xs-12">
        <h1 class="page-header">Agregar elemento</h1>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12 text-right">
        <label id="btnRegresarToCapture" class="btn btn-success">
            <i class="fa fa fa-reply"></i> Regresar
        </label>  
    </div>
</div>    
<div id="panelAgregarElemento" class="panel panel-inverse">        
    <div class="panel-heading">
        <div class="panel-heading-btn">
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>                            
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>                            
        </div>
        <h4 class="panel-title">Agregar elemento</h4>        
    </div>
    <div class="panel-body">
        <form id="formAddElement" data-parsley-validate="true">
            <div class="row"> 
                <div class="col-md-12">
                    <div class="form-grup">
                        <h4 class="m-t-10">Agregar Elemento al Inventario</h4>                        
                        <div class="underline m-b-15 m-t-15"></div>                                               
                    </div>
                </div>
                <div class="col-md-12">
                    <div id="errorAddElementPage"></div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class="form-group">
                        <label class="f-w-700">Ubicación *</label>
                        <select id="listUbicaciones" class="form-control" data-parsley-required="true">
                            <option value="">Selecciona . . .</option>
                            <?php
                            foreach ($ubicaciones as $key => $value) {
                                echo '<option value="' . $value['Id'] . '">' . $value['Nombre'] . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class="form-group">
                        <label class="f-w-700">Sistema *</label>
                        <select id="listSistemas" class="form-control" data-parsley-required="true">
                            <option value="">Selecciona . . .</option>
                            <?php
                            foreach ($sistemas as $key => $value) {
                                echo '<option value="' . $value['Id'] . '">' . $value['Nombre'] . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class="form-group">
                        <label class="f-w-700">Elemento *</label>
                        <select id="listElementos" class="form-control" data-parsley-required="true">
                            <option value="">Selecciona . . .</option>
                            <?php
                            foreach ($elementos as $key => $value) {
                                echo '<option value="' . $value['Id'] . '">' . $value['Nombre'] . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="form-group">
                        <label class="f-w-700">Cantidad *</label>
                        <input class="form-control" id="txtCantidad" type="number" value="1" min="1"  />
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-9 col-sm-12 col-xs-12">                                    
                    <div class="form-group">
                        <label class="f-w-700">Fotografía(s) del elemento</label>
                        <input id="fotosElemento" name="fotosElemento[]" type="file" multiple/>
                    </div>
                </div>
            </div>
            <div class="row btns-bottom hidden">
                <div class="col-md-12 col-sm-12 col-xs-12 text-center">                         
                    <a class="btn btn-success btn-save-without-subelements m-15">Guardar elemento(s)</a>
                </div>
            </div>
            <div class="row m-t-15"> 
                <div class="col-md-12">
                    <div class="form-grup">
                        <h4 class="m-t-10">Información de Elementos</h4>                        
                        <div class="underline m-b-15 m-t-15"></div>                                               
                    </div>
                </div>
            </div>
            <div id="formSeriesCapture">
                <div class="row">
                    <div class="col-md-1 col-sm-2 col-xs-12">
                        <div class="form-grup">
                            <label class="f-w-600">Elemento</label>
                            <input type="text" value="#1" disabled="disabled" class="form-control f-s-16 text-center" />
                        </div>
                    </div>
                    <div class="col-md-5 col-sm-5 col-xs-12">
                        <div class="form-group">
                            <label class="f-w-600">Serie</label>
                            <input  class="form-control info-serie-1" type="text" placeholder="Introduce Serie" />
                        </div>
                    </div>
                    <div class="col-md-5 col-sm-5 col-xs-12">
                        <div class="form-group">
                            <label class="f-w-600">Clave Cinemex (Inventario)</label>
                            <input  class="form-control info-clave-1" type="text" placeholder="Introduce Clave" />
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12 text-center">                    
                    <a class="btn btn-success btn-save-without-subelements m-15">Guardar elemento(s)</a>
                </div>
            </div>
        </form>
    </div>        
</div>