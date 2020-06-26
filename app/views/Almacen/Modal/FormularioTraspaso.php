<div class="row">
    <div class="col-md-9 col-sm-6 col-xs-12">
        <h1 class="page-header">Traspaso entre almacenes</h1>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12 text-right">
        <label id="btnRegresar" class="btn btn-success">
            <i class="fa fa fa-reply"></i> Regresar
        </label>  
    </div>
</div>    
<div id="panelTraspaso" class="panel panel-inverse">        
    <div class="panel-heading">
        <div class="panel-heading-btn">
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>                            
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>                            
        </div>
        <h4 class="panel-title">Traspasar entre almacenes</h4>        
    </div>
    <div class="panel-body">
        <div id="traspaso-almacenes-f1">
            <div class="row"> 
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="form-grup">
                        <h4 class="m-t-10">Almacenes Origen y Destino</h4>
                    </div>
                </div>                                    
            </div>
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12 underline"></div>
            </div>
            <form id="formOrigenDestino" data-parsley-validate="true">
                <div class="row m-t-20">
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <div class="form-group">
                            <label class="f-w-700">Almacén de Origen *</label>
                            <select id="listAlmacenOrigen" class="form-control" data-parsley-required="true">
                                <option value="">Selecciona . . .</option>
                                <?php
                                if (isset($almacenesOrigen) && count($almacenesOrigen) > 0) {
                                    foreach ($almacenesOrigen as $key => $value) {
                                        echo '<option value="' . $value['Id'] . '">' . $value['Nombre'] . '</option>';
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <div class="form-group">
                            <label class="f-w-700">Almacén Destino *</label>
                            <select id="listAlmacenDestino" class="form-control" data-parsley-required="true">
                                <option value="">Selecciona . . .</option>
                                <?php
                                if (isset($almacenesDestino) && count($almacenesDestino) > 0) {
                                    foreach ($almacenesDestino as $key => $value) {
                                        echo '<option value="' . $value['Id'] . '">' . $value['Nombre'] . '</option>';
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
            </form>
            <div class="row hidden m-t-30" id="divBtnTraspaso">                
                <div class="col-md-12 col-sm-12 col-xs-12 text-center">
                    <button class="btn btn-success p-l-25 p-r-25 f-w-600 m-20" id="btnTraspasar">Traspasar Productos <i class=" m-l-15 fa fa-chevron-circle-right" aria-hidden="true"></i></button>
                </div>
                <div class="col-md-12">
                    <div id="errorTraspasar"></div>
                </div>
            </div>
            <div class="row hidden" id="divProductosTraspaso"></div>
        </div>
    </div>        
</div>