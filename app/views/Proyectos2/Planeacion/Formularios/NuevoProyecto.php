<div class="row">
    <div class="col-md-9 col-sm-6 col-xs-12">
        <h1 class="page-header">Nuevo Proyecto</h1>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12 text-right">
        <label id="btnRegresar" class="btn btn-success">
            <i class="fa fa fa-reply"></i> Regresar
        </label>  
    </div>
</div>    
<div id="panelFormNuevoProyecto" class="panel panel-inverse">        
    <div class="panel-heading">  
        <h4 class="panel-title">Generales del Proyecto</h4>        
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <h4>Datos del Nuevo Proyecto</h4>
                <div class="underline m-b-10"></div>
            </div>
        </div>
        <form id="formNuevoProyecto" data-parsley-validate="true">
            <div class="row">
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class="form-group">
                        <label class="f-w-600 f-s-13">Nombre*:</label>
                        <input type="text" id="txtNombre" class="form-control" placeholder="Nombre del proyecto" data-parsley-required="true" />
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 col-sm-6 col-xs-12">
                    <div class="form-group">
                        <label class="f-w-600 f-s-13">Cliente*:</label>
                        <select id="listClientes" class="form-control" style="width: 100%" data-parsley-required="true">
                            <option value="">Selecciona . . .</option>
                            <?php
                            if (isset($clientes) && !empty($clientes)) {
                                foreach ($clientes as $key => $value) {
                                    echo '<option value="' . $value['Id'] . '">' . $value['Nombre'] . '</option>';
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6 col-xs-12">
                    <div class="form-group">
                        <label class="f-w-600 f-s-13">Sistema*:</label>
                        <select id="listSistemas" class="form-control" style="width: 100%" data-parsley-required="true">
                            <option value="">Selecciona . . .</option>
                            <?php
                            if (isset($sistemas) && !empty($sistemas)) {
                                foreach ($sistemas as $key => $value) {
                                    echo '<option value="' . $value['Id'] . '">' . $value['Nombre'] . '</option>';
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6 col-xs-12">
                    <div class="form-group">
                        <label class="f-w-600 f-s-13">Tipo de Proyecto*:</label>
                        <select id="listTipoProyecto" class="form-control" style="width: 100% !important;" data-parsley-required="true">
                            <option value="">Selecciona . . .</option>
                            <?php
                            if (isset($tipos) && !empty($tipos)) {
                                foreach ($tipos as $key => $value) {
                                    echo '<option value="' . $value['Id'] . '">' . $value['Nombre'] . '</option>';
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="form-group">
                        <label class="f-w-600 f-s-13">Sucursal(es)*:</label>
                        <select id="listSucursales" class="form-control" style="width: 100% !important;" multiple="" data-parsley-required="true" disabled=""></select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="form-group">
                        <label class="f-w-600 f-s-13">Líder(es):</label>
                        <select id="listLideres" class="form-control" style="width: 100% !important;" multiple="">                        
                            <?php
                            if (isset($lideres) && !empty($lideres)) {
                                foreach ($lideres as $key => $value) {
                                    echo '<option value="' . $value['Id'] . '">' . $value['Nombre'] . '</option>';
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-9 col-sm-12 col-xs-12">
                    <div class="form-group">
                        <label class="f-w-600 f-s-13">Observaciones:</label>
                        <textarea class="form-control" rows="5" id="txtObservaciones"></textarea>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-9 col-sm-12 col-xs-12">
                    <div class="form-group">
                        <label class="f-w-600 f-s-13">Fechas:</label>
                        <div id="rangoFechas" class="input-group input-daterange">                        
                            <input id="fini" type="text" class="form-control" value="">
                            <div class="input-group-addon">hasta</div>
                            <input id="ffin" type="text" class="form-control" value="">
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div id="errorNuevoProyecto"></div>
            </div>
        </div>
        <div class="row m-t-15">
            <div class="col-md-12 col-sm-12 col-xs-12 text-center">
                <a id="btnGeneraProyecto" class="btn btn-info"><i class="fa fa-save"></i> Generar Proyecto</a>
                <a id="btnLimpiarFormulario" class="btn btn-warning"><i class="fa fa-paint-brush"></i> Limpiar Formulario</a>
            </div>
        </div>
    </div>        
</div>

