<div class="row">
    <div class="col-md-9 col-sm-6 col-xs-12">
        <h1 class="page-header">Lista de servicios</h1>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12 text-right">
        <label id="btnBackToLast" class="btn btn-success">
            <i class="fa fa fa-reply"></i> Regresar
        </label>  
    </div>
</div>    
<div id="lastPanel" class="panel panel-inverse">        
    <div class="panel-heading">
        <?php
        $titulo = '';
        if (isset($tipo) && $tipo != '') {
            $titulo .= ' - ' . $tipo;
        }
        if (isset($estatus) && $estatus != '') {
            $titulo .= ' - ' . $estatus;
        }
        if (isset($sucursal) && $sucursal != '') {
            $titulo .= ' - ' . $sucursal;
        }
        if (isset($atiende) && $atiende != '') {
            $titulo .= ' - ' . $atiende;
        }
        ?>
        <h4 class="panel-title"><?php echo $titulo; ?></h4>        
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-12">                        
                <div class="form-group">
                    <h4 class="m-t-10"><?php echo $titulo; ?></h4>
                    <div class="underline m-b-15 m-t-15"></div>
                </div>    
            </div> 
        </div>        
        <div class="row m-t-10">
            <div class="col-md-12">                        
                <div class="form-group">
                    <h3 class="m-t-10">Lista de Servicios</h3>
                    <div class="underline m-b-15 m-t-15"></div>
                </div>    
            </div> 
        </div> 
        <div class="row m-t-15">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="table-responsive">                    
                    <table id="table-servicios-secondary-last" class="table table-hover table-striped table-bordered no-wrap">
                        <thead>
                            <tr>
                                <th class="all"># Servicio</th>
                                <th class="all"># Ticket</th>
                                <th class="all">Sucursal</th>
                                <th class="all">Estatus</th>
                                <th class="all">Tipo Servicio</th>
                                <th class="all">Fecha</th>
                                <th class="all">Atiende</th>
                                <th class="all">Descripción</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (isset($lista)) {
                                foreach ($lista as $key => $value) {
                                    echo ''
                                    . '<tr>'
                                    . '<td>' . $value['Id'] . '</td>'
                                    . '<td>' . $value['Ticket'] . '</td>'
                                    . '<td>' . $value['Sucursal'] . '</td>'
                                    . '<td>' . $value['Estatus'] . '</td>'
                                    . '<td>' . $value['Tipo'] . '</td>'
                                    . '<td>' . $value['Fecha'] . '</td>'
                                    . '<td>' . $value['Atiende'] . '</td>'
                                    . '<td>' . $value['Descripcion'] . '</td>'
                                    . '</tr>';
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