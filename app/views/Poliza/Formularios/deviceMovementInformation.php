<input type="hidden" id="serviceId" value="<?php echo $generalsService[0]['IdServicio']; ?>" />
<input type="hidden" id="movementId" value="<?php echo $deviceMovementData[0]['Id']; ?>" />
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <h4>Movimiento y validación</h4>
    </div>
    <div class="col-md-12 col-sm-12 col-xs-12 underline"></div>
</div>
<div class="row m-t-10">
    <div class="col-md-4 col-sm-6 col-xs-12 form-group">
        <label class="f-s-13 f-w-600">¿Quién valida el retiro o la solicitud? *</label>
        <label class="form-control f-s-12 f-w-600"><?php echo $deviceMovementData[0]['Valida']; ?><label>
    </div>
    <div class="col-md-4 col-sm-6 col-xs-12 form-group">
        <label class="f-s-13 f-w-600">Movimiento a realizar *</label>
        <label class="form-control f-s-12 f-w-600"><?php echo $deviceMovementData[0]['Movimiento']; ?><label>
    </div>
    <div class="col-md-4 col-sm-6 col-xs-12 form-group hidden">
        <label class="f-s-13 f-w-600">¿A quién solicita el equipo o refacción? *</label>
    </div>
</div>
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <h4>Equipo de respaldo</h4>
    </div>
    <div class="col-md-12 col-sm-12 col-xs-12 underline"></div>
</div>
<div class="row m-t-5">
    <div class="col-md-9 col-sm-12 col-xs-12 table-responsive">
        <?php
        if ($deviceMovementData[0]['IdInventarioRespaldo'] != '' && $deviceMovementData[0]['IdInventarioRespaldo'] > 0) {
        ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>Equipo</th>
                        <th>Serie</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><?php echo $deviceMovementData[0]['ModeloRespaldo']; ?></td>
                        <td><?php echo $deviceMovementData[0]['SerieRespaldo']; ?></td>
                    </tr>
                </tbody>
            </table>
        <?php
        } else {
        ?>
            <div class="note note-warning">
                <p class="f-s-14 f-w-600">No hay documentación del equipo de respaldo.</p>
            </div>
        <?php
        }
        ?>
    </div>
</div>
<?php
if ($deviceMovementData[0]['IdTipoMovimiento'] == 1) {
?>
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <h4>Información del envío</h4>
        </div>
        <div class="col-md-12 col-sm-12 col-xs-12 underline"></div>
    </div>
    <div class="row m-t-5">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <ul class="nav nav-pills">
                <li class="active"><a href="#ShinpingInfo" data-toggle="tab">Información del envío</a></li>
                <li><a href="#TrackNumberRequest" data-toggle="tab">Solicitud de guía</a></li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane fade active in" id="ShinpingInfo">
                    <div class="row">
                        <div class="col-md-4 col-sm-6 col-xs-12 form-group">
                            <label class="-s-13 f-w-600">Paquetería *: </label>
                            <select id="logisticCompaniesList" class="form-control" style="width:100%">
                                <option value="">Selecciona . . .</option>
                                <?php
                                if (isset($logisticCompanies) && !empty($logisticCompanies)) {
                                    foreach ($logisticCompanies as $k => $v) {
                                        echo '<option value="' . $v['Id'] . '">' . $v['Nombre'] . '</option>';
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-4 col-sm-6 col-xs-12 form-group">
                            <label class="-s-13 f-w-600"># Guía *: </label>
                            <input id="logisticTrackNumber" class="form-control" type="text" placeholder="0123456789" />
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="TrackNumberRequest">
                    <div class="row">
                        <div class="col-md-9 col-sm-12 col-xs-12 from-group">
                            <label class="f-s-13 f-w-600">Destino: *</label>
                            <input id="adressTo" class="form-control" type="text" value="(Almacén Siccob) Trigo 46, Granjas Esmeralda, Iztapalapa, Ciudad de México, CDMX CP 09810" />
                        </div>
                    </div>
                    <div class="row m-t-5">
                        <div class="col-md-4 col-sm-6 col-xs-12 form-group">
                            <label class="-s-13 f-w-600">Personal TI que valida *: </label>
                            <select id="customerValidatorsList" class="form-control" style="width:100%">
                                <option value="">Selecciona . . .</option>
                                <?php
                                if (isset($customerValidators) && !empty($customerValidators)) {
                                    foreach ($customerValidators as $k => $v) {
                                        echo '<option value="' . $v['userId'] . '">' . $v['userName'] . '</option>';
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                            <label class="-s-13 f-w-600">Información de cajas necesarias *: </label>
                        </div>
                        <div class="col-md-12 col-sm-12 col-xs-12 table-responsive">
                            <table class="table table-striped" id="boxForGuideRequestTable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Peso (Kg)</th>
                                        <th>Largo (cm)</th>
                                        <th>Ancho (cm)</th>
                                        <th>Alto (cm)</th>
                                        <th>
                                            <a id="addBoxButton" class="btn btn-success"><i class="fa fa-plus"></i></a>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td><input type="number" class="form-control weightBox" value="" /></td>
                                        <td><input type="number" class="form-control lengthBox" value="" /></td>
                                        <td><input type="number" class="form-control widthBox" value="" /></td>
                                        <td><input type="number" class="form-control heightBox" value="" /></td>
                                        <td><a class="btn btn-danger removeBoxButton"><i class="fa fa-trash"></i></a></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row m-t-5">
                        <div class="col-md-12 col-sm-12 col-xs-12 underline"></div>
                    </div>
                    <div class="row m-t-10">
                        <div class="<ol-md-12 col-sm-12 col-xs-12">
                            <a id="requestGuideButton" class="btn btn-success pull-right">Solicitar Guía</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php
}
?>
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12" id="errorMessage">
    </div>
</div>