<div id="listaKits">
    <div class="row">
        <div class="col-md-9 col-sm-6 col-xs-12">
            <h1 class="page-header">Kits de Equipos</h1>
        </div>
        <div class="col-md-3 col-sm-6 col-xs-12 text-right">
            <label id="btnRegresar" class="btn btn-success">
                <i class="fa fa fa-reply"></i> Regresar
            </label>  
        </div>
    </div>    
    <div id="panelKitsEquipos" class="panel panel-inverse">        
        <div class="panel-heading">
            <div class="panel-heading-btn">
                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>                            
                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>                            
            </div>
            <h4 class="panel-title">Kits de Equipos</h4>        
        </div>
        <div class="panel-body">        
            <div class="row"> 
                <div class="col-md-9 col-sm-8 col-xs-12">
                    <div class="form-grup">
                        <h4 class="m-t-10">Lista de Kits de Equipos</h4>
                    </div>
                </div>                               
                <?php
                if ($agregarEditar) {
                    ?>
                    <div class="col-md-3 col-sm-4 col-xs-12">
                        <button class="btn btn-success f-w-600 pull-right" id="btnAgregarKitEquipo"><i class="fa fa fa-plus"></i> Agregar Kit</button>
                    </div>            
                    <?php
                }
                ?>
            </div>
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12 underline"></div>
            </div>            
            <div class="row m-t-15">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="table-responsive">
                        <table id="data-table-kits-equipos" class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%">
                            <thead>
                                <tr>
                                    <th class="never">Id</th>
                                    <th class="all">Equipo</th>                                    
                                    <th class="all">Componentes</th>
                                    <th class="all">Usuario</th>
                                    <th class="all">Fecha Modificación</th>
                                    <th class="all">Estatus</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (isset($kits)) {
                                    foreach ($kits as $key => $value) {
                                        echo '<tr>';
                                        echo '<td>' . $value['Id'] . '</td>';
                                        echo '<td>' . $value['Equipo'] . '</td>';
                                        echo '<td>';
                                        foreach ($value['Componentes'] as $k => $v) {
                                            echo $v['Cantidad'] . " - " . $v['Nombre'] . "<br />";
                                        }
                                        echo '</td>';
                                        echo '<td>' . $value['Usuario'] . '</td>';
                                        echo '<td>' . $value['Fecha'] . '</td>';
                                        if ($value['Flag'] == 1) {
                                            echo '<td>Activo</td>';
                                        } else {
                                            echo '<td>Inactivo</td>';
                                        }
                                        echo '</tr>';
                                    }
                                }
                                ?>                                        
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>        
        </div>        
    </div>
</div>
<?php
if ($agregarEditar) {
    ?>            
    <div id="agregarEditarKit" style="display: none">
        <div class="row">
            <div class="col-md-9 col-sm-6 col-xs-12">
                <h1 class="page-header" id="mainTitle"></h1>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12 text-right">
                <label id="btnRegresar" class="btn btn-success">
                    <i class="fa fa fa-reply"></i> Regresar
                </label>  
            </div>
        </div>         
        <div id="panelAgregarEditarKit" class="panel panel-inverse">        
            <div class="panel-heading">
                <div class="panel-heading-btn">
                    <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>                            
                    <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>                            
                </div>
                <h4 class="panel-title"></h4>        
            </div>
            <div class="panel-body">        
                <div class="row"> 
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <h4 class="m-t-10" id="mainTitleBody"></h4>
                        </div>
                    </div>                               
                </div>
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12 underline"></div>
                </div>            
                <div class="row m-t-15">
                    <div class="col-md-6 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label class="f-w-600">Equipo o Modelo</label>
                            <select class="form-control" id="listEquiposKit" style="width: 100% !important;">
                                <option value="">Selecciona . . .</option>
                                <?php
                                if (isset($equipos)) {
                                    foreach ($equipos as $key => $value) {
                                        echo '<option value="' . $value['Id'] . '">' . $value['Equipo'] . '</option>';
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>     
                <div id="div-table-componentes-kit"></div>                                
            </div>        
        </div>
    </div>
    <?php
}
?>