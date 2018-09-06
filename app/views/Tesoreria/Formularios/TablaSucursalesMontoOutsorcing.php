<div class="row m-t-15"> 
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <h4 class="m-t-10">Sucursales del Técnico</h4>
        </div>
    </div>                               
</div>
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12 underline"></div>
</div>
<div class="row m-t-15">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="table-responsive">
            <table id="data-table-viaticos-outsorcing" class="table table-hover table-striped table-bordered no-wrap m-t-25" style="cursor:pointer" width="100%">
                <thead>
                    <tr>                
                        <th class="never">Id</th>                    
                        <th class="all">Sucursal</th>                    
                        <th class="all" style="max-width: 150px !important;">Monto</th>
                    </tr>
                </thead>
                <tbody>        
                    <?php
                    if (!empty($sucursales)) {
                        foreach ($sucursales as $key => $value) {
                            $monto = (!empty($value['Monto'])) ? $value['Monto'] : 0;
                            echo '<tr>'
                            . '<td>' . $value['Id'] . '</td>'
                            . '<td>' . $value['Nombre'] . '</td>'
                            . '<td class="text-center">'
                            . ' <input type="number" class="form-control cantidad-viaticos-outsourcing" max="999" value="' . $monto . '" data-id="' . $value['Id'] . '" min="0"/>'
                            . '</td>'
                            . '</tr>';
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!--Empezando error--> 
<div class="row m-t-10">
    <div class="col-md-12">
        <div class="errorFormularioViaticoOutsourcing"></div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="form-group text-center">
            <br>
            <a id="btnGuardarViaticoOutsorcing" href="javascript:;" class="btn btn-primary m-r-5 "><i class="fa fa-floppy-o"></i> Guardar Viáticos</a>                            
        </div>
    </div>
</div>