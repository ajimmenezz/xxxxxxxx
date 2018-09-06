<div id="mainPageInventario" class="content">    
    <h1 class="page-header">Inventario Salas 4D</h1>
    <div id="panelMainInventario" class="panel panel-inverse">        
        <div class="panel-heading">
            <div class="panel-heading-btn">
                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>                            
                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>                            
            </div>
            <h4 class="panel-title">Inventario Salas 4D</h4>
        </div>
        <div class="panel-body">
            <div class="row"> 
                <div class="col-md-12">
                    <div class="form-grup">
                        <h3 class="m-t-10">Sucursales con salas 4D</h3>                        
                        <div class="underline m-b-15 m-t-15"></div>                                               
                    </div>
                </div>
                <div class="col-md-12">
                    <div id="errorMainPageInventario"></div>
                </div>
            </div>
            <div class="row m-t-10">
                <div class="col-md-4 col-sm-6 col-xs-12">
                    <div class="form-grup">
                        <label class="f-w-700">Sucursal *</label>
                        <select id="listaSucursales" class="form-control">
                            <option value="">Selecciona Sucursal . . .</option>
                            <?php
                            if (isset($datos['Sucursales'])) {
                                foreach ($datos['Sucursales'] as $key => $value) {
                                    echo '<option value="' . $value['Id'] . '">' . $value['Nombre'] . '</option>';
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6 col-xs-12 p-t-20">                    
                    <a class="btn btn-success btn-block m-t-2 text-right f-w-700" id="btnGo">Ir al inventario <i class="fa fa-chevron-right" aria-hidden="true"></i></a>
                </div>
            </div>
        </div>        
    </div>        
</div>    

<!--Empezando seccion para la captura del inventario por sala-->
<div id="capturePageInventario" class="content" style="display:none"></div>
<!--Finalizando seccion para la captura del inventario por sala-->

<!--Empezando seccion para la captura de un nuevo elemento-->
<div id="addElementPageInventario" class="content" style="display:none"></div>
<!--Finalizando seccion para la captura de un nuevo elemento-->

<!--Empezando seccion para la captura de un nuevo elemento-->
<div id="infoElementPage" class="content" style="display:none"></div>
<!--Finalizando seccion para la captura de un nuevo elemento-->