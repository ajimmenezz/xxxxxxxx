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
        <div class="row"> 
            <div class="col-md-9 col-sm-8 col-xs-12">
                <div class="form-grup">
                    <h4 class="m-t-10">Lista y Detalle de Componentes</h4>
                </div>
            </div>                               
        </div>
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12 underline"></div>
        </div>            
        <div class="row m-t-15">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="table-responsive">
                    <form id="formularioSeriesCaptureComponentes" data-parsley-validate="true">
                        <table id="data-table-componentes-deshueso" class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%">
                            <thead>
                                <tr>                                
                                    <th class="all">Componente / Refacción</th>                                    
                                    <th class="all">Estatus *</th>
                                    <th class="all">Serie</th>                                
                                </tr>
                            </thead>
                            <tbody>

                                <?php
                                if (isset($componentes)) {
                                    foreach ($componentes as $key => $value) {
                                        if (array_key_exists($value['Id'], $kit)) {
                                            for ($i = 1; $i <= $kit[$value['Id']]; $i++) {
                                                echo '<tr>';
                                                echo '  <td>' . $value['Nombre'] . '</td>';
                                                echo '  <td>';
                                                echo '      <select class="form-control list-estus-componentes-deshueso" data-id="' . $value['Id'] . '" data-parsley-required="true">';
                                                echo '          <option value="">Seleccionar . . .</option>';
                                                if (isset($estatus)) {
                                                    foreach ($estatus as $k => $v) {
                                                        echo '<option value="' . $v['Id'] . '">' . $v['Nombre'] . '</option>';
                                                    }
                                                }
                                                echo '      </select>';
                                                echo '  </td>';
                                                echo '  <td>';
                                                echo '      <input type="text" class="serie-componente-deshueso form-control" id="serie-componente-deshueso-' . $value['Id'] . '" data-id="' . $value['Id'] . '" />';
                                                echo '  </td>';
                                                echo '</tr>';
                                            }
                                        }
                                    }
                                }
                                ?>                              
                            </tbody>
                        </table>
                    </form>
                </div>
            </div>
        </div>   
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12 text-center">
                <button class="btn btn-success f-w-600 p-l-20 p-r-20 f-s-15" id="btnGuargarDeshuesoEquipo">Deshuesar Equipo</button>
            </div>
        </div>
    </div>        
</div>
