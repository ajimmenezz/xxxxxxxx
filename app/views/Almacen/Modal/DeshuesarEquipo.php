<div id="divListaEquiposDeshuesar">
    <div class="row">
        <div class="col-md-9 col-sm-6 col-xs-12">
            <h1 class="page-header">Deshuesar Equipos</h1>
        </div>
        <div class="col-md-3 col-sm-6 col-xs-12 text-right">
            <label id="btnRegresar" class="btn btn-success">
                <i class="fa fa fa-reply"></i> Regresar
            </label>  
        </div>
    </div>    
    <div id="panelDeshuesarEquipo" class="panel panel-inverse">        
        <div class="panel-heading">
            <div class="panel-heading-btn">
                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>                            
                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>                            
            </div>
            <h4 class="panel-title">Deshuesar Equipo</h4>        
        </div>
        <div class="panel-body">        
            <?php
            if ($deshuesar) {
                ?>
                <div class="row"> 
                    <div class="col-md-9 col-sm-8 col-xs-12">
                        <div class="form-grup">
                            <h4 class="m-t-10">Lista de Equipos Disponibles para Deshuesar</h4>
                        </div>
                    </div>                               
                </div>
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12 underline"></div>
                    <div class="col-md-12">
                        <div id="errorDeshuesar"></div>
                    </div>
                </div>            
                <div class="row m-t-15">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="table-responsive">
                            <table id="data-table-equipos-deshueso" class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%">
                                <thead>
                                    <tr>
                                        <th class="never">Id</th>
                                        <th class="never">Id Equipo</th>
                                        <th class="never">Id Almacén</th>
                                        <th class="all">Equipo</th>                                    
                                        <th class="all">Estatus</th>
                                        <th class="all">Serie</th>
                                        <th class="all">Almacén</th>                                    
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (isset($productos)) {
                                        foreach ($productos as $key => $value) {
                                            echo '<tr>';
                                            echo '  <td>' . $value['IdRegistroInventario'] . '</td>';
                                            echo '  <td>' . $value['IdEquipo'] . '</td>';
                                            echo '  <td>' . $value['IdAlmacen'] . '</td>';
                                            echo '  <td>' . $value['Equipo'] . '</td>';
                                            echo '  <td>' . $value['Estatus'] . '</td>';
                                            echo '  <td>' . $value['Serie'] . '</td>';
                                            echo '  <td>' . $value['Almacen'] . '</td>';
                                            echo '</tr>';
                                        }
                                    }
                                    ?>                                        
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>        

                <?php
            } else {
                ?>
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="note note-danger">
                            <h4 class="f-w-700">Sin permisos para este sitio!</h4>
                            <p class="f-w-600 f-s-15">
                                Lo sentimos, pero al parecer no cuenta con los permisos para estar en esta sección. 
                                Por favor contácte al administrador del sistema si cree que se trata de un error.
                            </p>
                        </div>
                    </div>
                </div>
                <?php
            }
            ?>
        </div>        
    </div>
</div>
<div id="divListaComponentesDeshuesar"></div>
