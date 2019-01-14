<!--<div id="divNuevaSolicitud" class="content">
    <h1 class="page-header">Nueva Solicitud o Envio de Equipo</h1>-->
<div id="panelValidacion" class="panel panel-inverse">
    <div class="panel-heading">
        <div class="panel-heading-btn">
            <label id="btnRegresarTabla" class="btn btn-success btn-xs">
                <i class="fa fa fa-reply"></i> Regresar
            </label>
        </div>
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
                        <select id="listaTicket" class="form-control" style="width: 100%" data-parsley-required="true">
                            <option value="">Selecciona . . .</option>
                            <?php
                            foreach ($ticketsEnProblemas as $item) {
                                echo '<option value="' . $item['Id'] . '">' . $item['Ticket'] . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-8 col-sm-6 col-xs-12">
                    <div class="form-group">
                        <label class="f-w-600 f-s-13">Servicio *</label>
                        <select id="listaServicio" class="form-control" style="width: 100%" data-parsley-required="true" disabled>
                            <option value="">Selecciona . . .</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 col-sm-6 col-xs-12">
                    <div class="form-group">
                        <label class="f-w-600 f-s-13">Tipo de personal que valida *</label>
                        <select id="listaTipoPersonal" class="form-control" style="width: 100%" data-parsley-required="true" disabled>
                            <option value="">Selecciona . . .</option>
                            <?php
                            foreach ($tipoPersonaValida as $item) {
                                echo '<option value="' . $item['Id'] . '">' . $item['Nombre'] . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6 col-xs-12">
                    <div class="form-group">
                        <label class="f-w-600 f-s-13">Personal que valida *</label>
                        <select id="listaNombrePersonal" class="form-control" style="width: 100%" data-parsley-required="true" disabled>
                            <option value="">Selecciona . . .</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6 col-xs-12">
                    <div class="form-group">
                        <label class="f-w-600 f-s-13">Fecha de Validación *</label>
                        <!--                        <div class="input-group date" id="fechaValidacion">
                                                    <input type="text" class="form-control" />
                                                    <span class="input-group-addon">
                                                        <span class="glyphicon glyphicon-calendar"></span>
                                                    </span>
                                                </div>-->
                        <input type="datetime-local" id="fechaValidacion" value="<?php echo $date = date('Y-m-d\TH:i'); ?>" class="form-control" data-parsley-pattern="^\d\d\d\d-(0?[1-9]|1[0-2])-(0?[1-9]|[12][0-9]|3[01])T(|0[0-9]|1[0-9]|2[0-3]):([0-9]|[0-5][0-9])$" required/>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 col-sm-6 col-xs-12">
                    <div class="form-group">
                        <label class="f-w-600 f-s-13">¿Que movimiento va a realizar? *</label>
                        <div class="radio">
                            <label>
                                <input type="radio" name="movimiento" value="foraneos" disabled/>
                                Envío de Equipo a Laboratorio (Foraneos)
                            </label>
                        </div>
                        <div class="radio">
                            <label>
                                <input type="radio" name="movimiento" value="locales" disabled/>
                                Entrega de Equipo en Laboratorio (Locales)
                            </label>
                        </div>
                        <div class="radio">
                            <label>
                                <input type="radio" name="movimiento" value="EquipoRefaccion" disabled/>
                                Solicitud de Equipo o Refacción
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-md-8 col-sm-6 col-xs-12 hidden" id="divEquipoEnvio">
                    <div class="form-group" >
                        <label class="f-w-600 f-s-13">Equipo que se envía *</label>
                        <input value="" readonly="readonly" type="text" class="form-control" id="equipoEnviado" placeholder=""  data-parsley-required="true"/>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6 col-xs-12 hidden divRefaccionEquipo">
                    <div class="form-group">
                        <label class="f-w-600 f-s-13">Equipo *</label>
                        <select id="listaSolicitarEquipo" class="form-control" style="width: 100%" disabled>
                            <option value="">Selecciona . . .</option>
                            <?php
                            foreach ($listaEquipo as $item) {
                                echo '<option value="' . $item['Id'] . '">' . $item['Equipo'] . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6 col-xs-12 hidden divRefaccionEquipo">
                    <div class="form-group">
                        <label class="f-w-600 f-s-13">Refacción</label>
                        <select id="listaSolicitarRefaccion" class="form-control" style="width: 100%" disabled>
                            <option value="">Selecciona . . .</option>
                        </select>
                    </div>
                </div>
            </div>
        </form>
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div id="errorFormularioValidacion"></div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12 text-center">
                <a id="btnGuardarValidacion" href="javascript:;" class="btn btn-success m-t-10 m-r-10 f-w-600 f-s-15">Guardar Validación</a>
                <!--<a id="btnConcluirServicioCorrectivo" href="javascript:;" class="btn btn-danger m-r-5 "><i class="fa fa-unlock-alt"></i> Concluir Servicio</a>-->
            </div>
        </div>
    </div>
</div>    
<!--</div>-->