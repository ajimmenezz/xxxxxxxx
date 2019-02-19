<div id="divNuevaSolicitud" class="content">
    <h1 class="page-header">Nueva Solicitud o Envio de Equipo</h1>
    <div id="panelValidacion" class="panel panel-inverse">
        <div class="panel-heading">
            <h4 class="panel-title">1) Validación</h4>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <h4>Llamada de Validación</h4>
                    <div class="underline m-b-10"></div>
                </div>
            </div>
            <form id="formValidacion" data-parsley-validate="true">
                <div class="row">
                    <div class="col-md-4 col-sm-6 col-xs-12">
                        <div class="form-group">
                            <label class="f-w-600 f-s-13">Ticket *</label>
                            <select id="listTicket" class="form-control" style="width: 100%" data-parsley-required="true">
                                <option value="">Selecciona . . .</option>
                                <option value="12458">12458</option>
                                <option value="12459">12459</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-8 col-sm-6 col-xs-12">
                        <div class="form-group">
                            <label class="f-w-600 f-s-13">Servicio *</label>
                            <select id="listServicio" class="form-control" style="width: 100%" data-parsley-required="true">
                                <option value="">Selecciona . . .</option>
                                <option value="496673">496673 -- POS DE TAQUILLA NO ENCIENDE</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 col-sm-6 col-xs-12">
                        <div class="form-group">
                            <label class="f-w-600 f-s-13">Tipo de personal que valida *</label>
                            <select id="listTipoPersonal" class="form-control" style="width: 100%" data-parsley-required="true">
                                <option value="">Selecciona . . .</option>
                                <option value="1">Laboratorio</option>
                                <option value="2">Supervisor</option>
                                <option value="3">Coordinador de Póliza</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6 col-xs-12">
                        <div class="form-group">
                            <label class="f-w-600 f-s-13">Personal que valida *</label>
                            <select id="listNombrePersonal" class="form-control" style="width: 100%" data-parsley-required="true">
                                <option value="">Selecciona . . .</option>
                                <option value="1">Rigoberto Sánchez</option>
                                <option value="2">Jonathan Ledezma</option>
                                <option value="3">Omar Martínez</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6 col-xs-12">
                        <div class="form-group">
                            <label class="f-w-600 f-s-13">Fecha de Validación *</label>
                            <div class="input-group date" id="fechaValidacion">
                                <input type="text" class="form-control" />
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 col-sm-6 col-xs-12">
                        <div class="form-group">
                            <label class="f-w-600 f-s-13">¿Que movimiento va a realizar? *</label>
                            <div class="radio">
                                <label>
                                    <input type="radio" name="optionsRadios" value="foraneos" checked />
                                    Envío de Equipo a Laboratorio (Foraneos)
                                </label>
                            </div>
                            <div class="radio">
                                <label>
                                    <input type="radio" name="optionsRadios" value="locales"/>
                                    Entrega de Equipo en Laboratorio (Locales)
                                </label>
                            </div>
                            <div class="radio">
                                <label>
                                    <input type="radio" name="optionsRadios" value="EquipoRefaccion"/>
                                    Solicitud de Equipo o Refacción
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6 col-xs-12">
                        <div class="form-group">
                            <label class="f-w-600 f-s-13">Equipo *</label>
                            <select id="listTipoPersonal" class="form-control" style="width: 100%" data-parsley-required="true">
                                <option value="">Selecciona . . .</option>
                                <option value="1">HP 7800</option>
                                <option value="2">RP9000</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6 col-xs-12">
                        <div class="form-group">
                            <label class="f-w-600 f-s-13">Refacción</label>
                            <select id="listNombrePersonal" class="form-control" style="width: 100%" data-parsley-required="true">
                                <option value="">Selecciona . . .</option>
                                <option value="1">Disco Duro</option>
                                <option value="2">Fuente</option>
                                <option value="3">Cabezal de Impresión</option>
                            </select>
                        </div>
                    </div>
                </div>
            </form>
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div id="errorFormulario"></div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12 text-center">
                    <a id="btnGuardarValidacion" class="btn btn-success m-t-10 m-r-10 f-w-600 f-s-15">Guardar Validación</a>
                </div>
            </div>
        </div>
    </div>    
</div>