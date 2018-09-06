<div class="row m-t-15"> 
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <h4 class="m-t-10">Componentes de Equipo</h4>
        </div>
    </div>                               
</div>
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12 underline"></div>
</div>
<div class="row m-t-15">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="table-responsive">
            <table id="data-table-componentes-kit" class="table table-hover table-striped table-bordered no-wrap m-t-25" style="cursor:pointer" width="100%">
                <thead>
                    <tr>                
                        <th class="never">Id</th>                    
                        <th class="all">Componente</th>                    
                        <th class="all" style="max-width: 150px !important;">Cantidad</th>
                    </tr>
                </thead>
                <tbody>        
                    <?php
                    if (isset($componentes) && !empty($componentes)) {
                        foreach ($componentes as $key => $value) {

                            $cantidad = (array_key_exists($value['Id'], $cantidades)) ? $cantidades[$value['Id']] : 0;

                            echo '<tr>'
                            . '<td>' . $value['Id'] . '</td>'
                            . '<td>' . $value['Nombre'] . '</td>'
                            . '<td class="text-center">'
                            . ' <input type="number" class="form-control cantidad-componente-kit" max="999" value="' . $cantidad . '" data-id="' . $value['Id'] . '" min="0"/>'
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





<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12 text-center">
        <button class="btn btn-success f-w-600" id="btnGuardarKit">Guardar Kit</button>
    </div>
</div>