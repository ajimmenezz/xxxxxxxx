<?php
if (!empty($datosValidacion)) {
    foreach ($datosValidacion as $value) {
        if ($value['IdEstatus'] === '39' || $value['IdEstatus'] === '34' || $value['IdEstatus'] === '30' || $value['IdEstatus'] === '35' || $value['IdEstatus'] === '12' || $value['IdEstatus'] === '36' || $value['IdEstatus'] === '48' || $value['IdEstatus'] === '49') {
            $datosCloncluirRevision = "hidden";
            $tablaRefaccionUtilizada = "";
        } else {
            $datosCloncluirRevision = "";
            $tablaRefaccionUtilizada = "hidden";
        }
    }
} else {
    $datosCloncluirRevision = "";
    $tablaRefaccionUtilizada = "hidden";
}
?>
<div id="panelLaboratorioHistorial" class="panel panel-inverse">
    <div class="panel-heading">
        <h4 class="panel-title">Revisión en Laboratorio</h4>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div id="errorConcluirRevision"></div>
            </div>
        </div>
        <ul class="nav nav-pills col-md-6 col-sm-6 col-xs-12">
            <li class="active"><a href="#revisionHistorial" data-toggle="tab">Historial</a></li>
            <li><a href="#refaccionUtilizada" data-toggle="tab">Refacciones Utilizadas</a></li>
        </ul>
        <div class="col-md-6 col-sm-6 col-xs-12 <?php echo $datosCloncluirRevision ?>">
            <div class="form-group text-right">
                <a href="javascript:;" class="btn btn-sm btn-danger f-s-13" id="consluirRevisionLab">Concluir Revisión</a>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="underline m-b-10"></div>
            </div>
        </div>
        <div class="tab-content">
            <div class="tab-pane fade active in" id="revisionHistorial">
                <div class="row <?php echo $datosCloncluirRevision ?>">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label class="f-w-600 f-s-13">Comentarios y Observaciones *</label>
                            <textarea class="form-control" id="comentariosObservaciones" rows="5" placeholder=""></textarea>
                        </div>
                    </div>
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label class="f-w-600 f-s-13">Adjuntos</label> 
                            <input id="archivosLabHistorial"  name="archivosLabHistorial[]" type="file" multiple />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div id="errorAgregarComentario"></div>
                        </div>
                    </div>
                    <div class="col-md-12 col-sm-12 col-xs-12 text-center">
                        <a id="agregarComentarioHistorial" class="btn btn-sm btn-success m-t-10 m-r-10 f-w-600 f-s-13"><i class="fa fa-plus"></i> Agregar Comentario</a>
                    </div>
                </div>
                <div class="row m-t-25">
                    <legend>Comentarios y adjuntos de la revisión</legend>
                </div>
                <div class="timelineTareas" id="divComentariosAdjuntos"></div>
            </div>
            <div class="tab-pane fade" id="refaccionUtilizada">
                <div class="row <?php echo $datosCloncluirRevision ?>">
                    <div class="col-md-8 col-sm-8 col-xs-12">
                        <div class="form-group">
                            <label class="f-w-600 f-s-13">Refacción Utilizada *</label>
                            <select id="listRefaccionUtil" class="form-control" style="width: 100%" data-parsley-required="true">
                                <option value="">Selecciona . . .</option>
                                <?php
                                foreach ($componentesEquipo as $item) {
                                    echo '<option value="' . $item['Id'] . '">' . $item['Nombre'] . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-4 col-xs-12 m-t-25">
                        <?php
                        if (empty($componentesEquipo)) {
                            if ($cotizacionAnterior[0]['Total'] <= 0) {
                                ?>
                                <a id="btnSolicitarCotizacionRevisionLaboratorio" class="btn btn-primary f-s-13"><i class="fa fa-usd"></i> Solicitar Cotización</a>
                                <?php
                            }
                        } else {
                            ?>
                            <a id = "btnAgregarRefaccion" class = "btn btn-success f-s-13"><i class = "fa fa-plus"></i> Agregar Refacción</a>
                            <?php
                        }
                        ?>

                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div id="errorAgregarRefaccion"></div>
                    </div>
                </div>
                <div class="row <?php echo $datosCloncluirRevision ?>">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="table-responsive">
                            <table id="listaRefaccionUtilizada" class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%">
                                <thead>
                                    <tr>
                                        <th class="never">Id</th>
                                        <th class="all">Refacción</th>
                                        <th class="all">Cantidad</th>
                                        <th class="never">IdInventario</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (!empty($listRefaccionesUtilizadasServicio)) {
                                        foreach ($listRefaccionesUtilizadasServicio as $key => $value) {
                                            echo '<tr>';
                                            echo '<td>' . $value['Id'] . '</td>';
                                            echo '<td>' . $value['Nombre'] . '</td>';
                                            echo '<td>' . $value['Cantidad'] . '</td>';
                                            echo '<td>' . $value['IdInventario'] . '</td>';
                                            echo '</tr>';
                                        }
                                    }
                                    ?>  
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-12 m-t-20">
                        <div class="alert alert-warning fade in m-b-15">                            
                            Para eliminar el registro de la tabla solo tiene que dar click sobre fila para eliminarlo.                            
                        </div>                        
                    </div>
                </div>
                <div class="row <?php echo $tablaRefaccionUtilizada ?>">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="table-responsive">
                            <table id="listaRefaccionUtilizadaLaboratorio" class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%">
                                <thead>
                                    <tr>
                                        <th class="never">Id</th>
                                        <th class="all">Refacción</th>
                                        <th class="all">Cantidad</th>
                                        <th class="never">IdInventario</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (!empty($listRefaccionesUtilizadasServicio)) {
                                        foreach ($listRefaccionesUtilizadasServicio as $key => $value) {
                                            echo '<tr>';
                                            echo '<td>' . $value['Id'] . '</td>';
                                            echo '<td>' . $value['Nombre'] . '</td>';
                                            echo '<td>' . $value['Cantidad'] . '</td>';
                                            echo '<td>' . $value['IdInventario'] . '</td>';
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
</div>