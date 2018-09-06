<?php
if (count($material) <= 0) {
    ?>
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="alert alert-warning fade in m-b-15 f-w-500 f-s-14 text-center">
                <strong>Warning!</strong><br />
                Al parecer no hay material asignado a la tarea. Revise con el usuario encargado de la planeación. <br />Si considera que esto es un error, por favor comuniquese con el administrador del sistema.
            </div>
        </div>
    </div>
    <?php
} else {
    $estatusCampos = '';
    $classButtons = '';
    if ($predecesora && $avancePredecesora <= 99) {
        $estatusCampos = ' disabled="disabled" ';
        $classButtons = ' hidden ';
    }
    ?>

    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <h4 class="f-w-600">Material Proyectado en Tarea</h4>  
            <div class="underline m-b-10"></div>
        </div>
    </div>   
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12 table-responsive">
            <table class="table table-striped table-bordered no-wrap table-material-nodo" style="cursor:pointer" width="100%">
                <thead>
                    <tr>                        
                        <th class="all">Accesorio</th>
                        <th class="all">Material</th>
                        <th class="all">Cantidad Proyectada</th>
                        <th class="all">Cantidad Utilizada</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($material as $key => $value) {
                        echo '<tr>';
                        echo '  <td>' . $value['Accesorio'] . '</td>';
                        echo '  <td>' . $value['Material'] . '</td>';
                        echo '  <td>' . $value['Cantidad'] . '</td>';
                        echo '  <td class="text-center"><input type="number" data-max="' . $value['Cantidad'] . '" data-id="' . $value['Id'] . '" class="form-control input-utilizado-material-tarea f-w-700" min="1" value="' . $value['Utilizado'] . '" ' . $estatusCampos . ' /></td>';
                        echo '</tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div> 
    <div class="row <?php echo $classButtons; ?>">
        <div class="col-md-12 col-sm-12 col-xs-12 text-center">
            <a class="btn btn-info btnGuardarMaterialTareaUtilizado f-w-600 f-s-13"><i class="fa fa-save"></i> Guardar Material</a>
        </div>
    </div>
    <?php
}

