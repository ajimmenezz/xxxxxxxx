<?php

if ($datosValidacion[0] !== "sinDatos") {
    if (!empty($datosValidacion)) {
        foreach ($datosValidacion as $item) {
            $idServicio = $item['IdServicio'];
            $ticket = $item['Ticket'];
            $servicio = $item['Servicio'];
            $nombrePersonal = $item['NombrePersonal'];
            $fecha = $item['FechaValidacion'];
            $tipoMovimiento = $item['TipoMovimiento'];
            $movimiento = $item['Movimiento'];
            $equipo = $item['Equipo'];
            $tipoDiagnostico = $item['TipoDiagnostico'];
            $tipoFalla = $item['TipoFalla'];
            $falla = $item['Falla'];
            $tecnicoSolicita = $item['TecnicoSolicita'];
            $sucursal = $item['Sucursal'];
            if ($item['Lectura'] === 'Lectura') {
                $mostrarSelect = "hidden";
                $mostrarInput = "";
            }
            ?>
            <?php
        }
    } else {
        $mostrarSelect = "";
        $mostrarInput = "hidden";
        $ticket = "";
        $servicio = "";
        $nombrePersonal = "";
        $fecha = "";
        $movimiento = "";
        $equipo = "";
        $tipoDiagnostico = "";
        $tipoFalla = "";
        $falla = "";
        $tipoMovimiento = "";
        $idServicio = "";
        $tecnicoSolicita = "";
        $sucursal = "";
    }
    ?>
    <div id="envioAlmacenSinGuia" class="hidden"></div>
    <div id="envioAlmacenConGuia" class="hidden"></div>
    <div id="panelValidacion" class="panel panel-inverse">
        <div class="panel-heading">
            <h4 class="panel-title">Validación</h4>
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
                        <div class="form-group <?php echo $mostrarSelect; ?>">
                            <label class="f-w-600 f-s-13">Ticket AdIST - Ticket SD *</label>
                            <select id="listaTicket" class="form-control" style="width: 100%" data-parsley-required="true">
                                <option value="">Selecciona . . .</option>
                                <?php
                                foreach ($ticketTecnico as $item) {
                                    echo '<option value="' . $item['Ticket'] . '">' . $item['Ticket'] . ' - ' . $item['Folio'] . '</option>';
                                }
                                ?>
                            </select>    
                        </div>
                        <div class="form-group <?php echo $mostrarInput ?>">
                            <label class="f-w-600 f-s-13">Ticket *</label>
                            <input type="text" class="form-control" placeholder="<?php echo $ticket ?>" disabled/>
                        </div>
                    </div>
                    <div class="col-md-8 col-sm-6 col-xs-12 ">
                        <div class="form-group <?php echo $mostrarSelect; ?>">
                            <label class="f-w-600 f-s-13">Servicio *</label>
                            <select id="listaServicio" class="form-control" style="width: 100%" data-parsley-required="true" disabled>
                                <option value="" data-idModelo = "" data-serie="">Selecciona . . .</option>
                            </select>
                        </div>
                        <div class="form-group <?php echo $mostrarInput; ?>">
                            <label class="f-w-600 f-s-13">Servicio *</label>
                            <input id="inputServicio" type="text" class="form-control" placeholder="<?php echo $servicio ?>" data-idServicio="<?php echo $idServicio ?>" disabled/>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 col-sm-6 col-xs-12">
                        <div class="form-group <?php echo $mostrarSelect; ?>">
                            <label class="f-w-600 f-s-13">Tipo de personal que valida *</label>
                            <select id="listaTipoPersonal" class="form-control" style="width: 100%" data-parsley-required="true" disabled>
                                <option value="">Selecciona . . .</option>
                                <?php
                                foreach ($tipoPerfiles as $item) {
                                    echo '<option value="' . $item['Id'] . '">' . $item['Nombre'] . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group <?php echo $mostrarInput; ?>">
                            <label class="f-w-600 f-s-13">Tipo de personal que valida *</label>
                            <input type="text" class="form-control" placeholder="<?php echo $tipoMovimiento; ?>" disabled/>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6 col-xs-12">
                        <div class="form-group <?php echo $mostrarSelect; ?>">
                            <label class="f-w-600 f-s-13">Personal que valida *</label>
                            <select id="listaNombrePersonal" class="form-control" style="width: 100%" data-parsley-required="true" disabled>
                                <option value="">Selecciona . . .</option>
                            </select>
                        </div>
                        <div class="form-group <?php echo $mostrarInput; ?>">
                            <label class="f-w-600 f-s-13">Personal que valida *</label>
                            <input type="text" class="form-control" placeholder="<?php echo $nombrePersonal; ?>" disabled/>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6 col-xs-12">
                        <div class="form-group <?php echo $mostrarSelect; ?>">
                            <label class="f-w-600 f-s-13">Fecha de Validación *</label>
                            <input type="datetime-local" id="fechaValidacion" value="<?php echo $date = date('Y-m-d\TH:i'); ?>" class="form-control" data-parsley-pattern="^\d\d\d\d-(0?[1-9]|1[0-2])-(0?[1-9]|[12][0-9]|3[01])T(|0[0-9]|1[0-9]|2[0-3]):([0-9]|[0-5][0-9])$" required disabled/>
                        </div>
                        <div class="form-group <?php echo $mostrarInput; ?>">
                            <label class="f-w-600 f-s-13">Fecha de Validación *</label>
                            <input type="text" class="form-control" placeholder="<?php echo $fecha; ?>" disabled/>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <input type="hidden" value="" id="inputMovimiento" />
                    <div class="col-md-4 col-sm-6 col-xs-12">
                        <div class="form-group <?php echo $mostrarSelect; ?>">
                            <label class="f-w-600 f-s-13">¿Que movimiento va a realizar? *</label>
                            <div class="radio">
                                <label>
                                    <input type="radio" name="movimiento" value="1" disabled/>
                                    Envío de Equipo a Laboratorio (Foraneos)
                                </label>
                            </div>
                            <div class="radio">
                                <label>
                                    <input type="radio" name="movimiento" value="2" disabled/>
                                    Entrega de Equipo en Laboratorio (Locales)
                                </label>
                            </div>
                            <div class="radio">
                                <label>
                                    <input type="radio" name="movimiento" value="3" disabled/>
                                    Solicitud de Equipo o Refacción
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8 col-sm-6 col-xs-12 hidden" id="divEquipoEnvio">
                        <div class="form-group" >
                            <label class="f-w-600 f-s-13">Equipo que se envía *</label>
                            <input value="" readonly="readonly" type="text" class="form-control" id="equipoEnviado" data-IdEquipo="" data-parsley-required="true"/>
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
                <div class="row">
                    <div class="col-md-4 col-sm-4 col-xs-4">
                        <div class="form-group <?php echo $mostrarInput; ?>">
                            <label class="f-w-600 f-s-13">Tipo de Movimiento *</label>
                            <input type="text" class="form-control" placeholder="<?php echo $movimiento; ?>" disabled/>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-4 col-xs-4">
                        <div class="form-group <?php echo $mostrarInput; ?>">
                            <label class="f-w-600 f-s-13">Equipo *</label>
                            <input type="text" class="form-control" placeholder="<?php echo $equipo; ?>" disabled/>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-4 col-xs-4">
                        <div class="form-group <?php echo $mostrarInput; ?>">
                            <label class="f-w-600 f-s-13">Tipo Diagnostico *</label>
                            <input type="text" class="form-control" placeholder="<?php echo $tipoDiagnostico; ?>" disabled/>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 col-sm-4 col-xs-4">
                        <div class="form-group <?php echo $mostrarInput; ?>">
                            <label class="f-w-600 f-s-13">Tipo Falla *</label>
                            <input type="text" class="form-control" placeholder="<?php echo $tipoFalla; ?>" disabled/>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-4 col-xs-4">
                        <div class="form-group <?php echo $mostrarInput; ?>">
                            <label class="f-w-600 f-s-13">Falla *</label>
                            <input type="text" class="form-control" placeholder="<?php echo $falla; ?>" disabled/>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-4 col-xs-4">
                        <div class="form-group <?php echo $mostrarInput; ?>">
                            <label class="f-w-600 f-s-13">Técnico que solicita</label>
                            <input type="text" class="form-control" placeholder="<?php echo $tecnicoSolicita; ?>" disabled/>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 col-sm-4 col-xs-4">
                        <div class="form-group <?php echo $mostrarInput; ?>">
                            <label class="f-w-600 f-s-13">Sucursal</label>
                            <input type="text" class="form-control" placeholder="<?php echo $sucursal; ?>" disabled/>
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
                <div class="col-md-12 col-sm-12 col-xs-12 text-center <?php echo $mostrarSelect; ?>">
                    <a id="btnGuardarValidacion" href="javascript:;" class="btn btn-primary m-t-10 m-r-10 f-w-600 f-s-15"><i class="fa fa-save"></i> Guardar Validación</a>
                </div>
            </div>
        </div>
    </div>    
<?php } ?>