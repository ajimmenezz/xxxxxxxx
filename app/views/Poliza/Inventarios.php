<div class="content" id="filtersContent">
    <h1 class="page-header">Reporte de Inventarios</h1>
    <div id="filtersPanel" class="panel panel-inverse">
        <div class="panel-heading">
            <h4 class="panel-title">Filtros de Reporte</h4>
        </div>
        <div class="panel-body">
            <form id="filtersForm">
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <h3>Filtros de Reporte</h3>
                    </div>
                    <div class="underline col-md-12 col-sm-12 col-xs-12"></div>
                </div>
                <div class="row">
                    <div class="col-md-4 col-sm-6 col-xs-12 form-group">
                        <label class="form-label">Fechas:</label>
                        <div class="input-group input-daterange">
                            <input id="iniDate" type="text" class="form-control" name="start" placeholder="Fecha Inicio">
                            <span class="input-group-addon">a</span>
                            <input id="endDate" type="text" class="form-control" name="end" placeholder="Fecha Final">
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6 col-xs-12 form-group">
                        <label class="form-label">Estatus del Censo:</label>
                        <select id="statusList" class="form-control" style="width: 100%">
                            <option value="">Cualquier Estatus</option>
                            <?php
                            if (isset($datos['data']['status']) && count($datos['data']['status']) > 0) {
                                foreach ($datos['data']['status'] as $k => $v) {
                                    echo '<option value="' . $v['Id'] . '">' . ucwords($v['Nombre']) . '</option>';
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-4 col-sm-6 col-xs-12 form-group">
                        <label class="form-label">Técnico:</label>
                        <select id="technicianList" class="form-control" style="width: 100%">
                            <option value="">Cualquier Técnico</option>
                            <?php
                            if (isset($datos['data']['technician']) && count($datos['data']['technician']) > 0) {
                                foreach ($datos['data']['technician'] as $k => $v) {
                                    echo '<option value="' . $v['Id'] . '">' . ucwords($v['Nombre']) . '</option>';
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 col-sm-6 col-xs-12 form-group">
                        <label class="form-label">Región(es):</label>
                        <select id="regionList" class="form-control" multiple="multiple">
                            <?php
                            if (isset($datos['data']['region']) && count($datos['data']['region']) > 0) {
                                foreach ($datos['data']['region'] as $k => $v) {
                                    echo '<option value="' . $v['Id'] . '">' . ucwords($v['Nombre']) . '</option>';
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-12 form-group">
                        <label class="form-label">Sucursal(es):</label>
                        <select id="branchList" class="form-control" multiple="multiple">
                            <?php
                            if (isset($datos['data']['branches']) && count($datos['data']['branches']) > 0) {
                                foreach ($datos['data']['branches'] as $k => $v) {
                                    echo '<option value="' . $v['Id'] . '">' . ucwords($v['Nombre']) . '</option>';
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 col-sm-6 col-xs-12 form-group">
                        <label class="form-label">Área(s) de Atención:</label>
                        <select id="areasList" class="form-control" multiple="multiple">
                            <?php
                            if (isset($datos['data']['areas']) && count($datos['data']['areas']) > 0) {
                                foreach ($datos['data']['areas'] as $k => $v) {
                                    echo '<option value="' . $v['Id'] . '">' . ucwords($v['Nombre']) . '</option>';
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-12 form-group">
                        <label class="form-label">Equipo(s):</label>
                        <select id="deviceList" class="form-control" multiple="multiple">
                            <?php
                            if (isset($datos['data']['devices']) && count($datos['data']['devices']) > 0) {
                                foreach ($datos['data']['devices'] as $k => $v) {
                                    echo '<option value="' . $v['Id'] . '">' . ucwords($v['Nombre']) . '</option>';
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12 text-center">
                        <a id="submitButton" class="btn btn-success form-label">Mostrar Resultado</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="content" id="firstViewContent" style="display: none"></div>
<div class="content" id="secondViewContent" style="display: none"></div>