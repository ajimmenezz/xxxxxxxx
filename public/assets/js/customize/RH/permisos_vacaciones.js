$(function () {
    
    var evento = new Base();
    var websocket = new Socket();
    var select = new Select();
    var file = new Upload();
    this.calendario = new Fecha();
    var tabla = new Tabla();

    //Evento que maneja las peticiones del socket
    websocket.socketMensaje();

    //Muestra la hora en el sistema
    evento.horaServidor($('#horaServidor').val());

    //Evento para cerra la session
    evento.cerrarSesion();

    //Evento para mostrar la ayuda del sistema
    evento.mostrarAyuda('Ayuda_Proyectos');

    file.crearUpload('#inputEvidenciaIncapacidad', 'EventoPermisosVacaciones/Permisos', ['pdf']);
    tabla.generaTablaPersonal('#data-table-permisos-ausencia', null, null, true, true);
    //Inicializa funciones de la plantilla
    App.init();

    $('#inputFechaDocumento').datepicker({
        format: 'yyyy-mm-dd'
    });
    $('#inputFechaDocumento').datepicker("setDate", new Date());
    $('#inputFechaPermisoDesde').datepicker({
        format: 'yyyy-mm-dd',
        startDate: new Date()
    });
    $('#selectSolicitudHora').timepicker();

    $('#inputFechaPermisoDesde').on('change', function () {
        diaDesde = $('#inputFechaPermisoDesde').val();
        $('#inputFechaPermisoHasta').val(diaDesde);
        $('#inputFechaPermisoHasta').datepicker({
            format: 'yyyy-mm-dd',
            startDate: diaDesde
        });
    });


    //evento que activa los campos inputCitaFolio, descripcionAusencia
    $('#selectMotivoAusencia').on('change', function () {
        if ($(this).val() == '3' || $(this).val() == '4') {
            $("#citaFolio").css("display","block");
            $('#inputCitaFolio').attr('data-parsley-required', 'true');
            $("#archivoCitaIncapacidad").css("display","block");
            $('#inputEvidenciaIncapacidad').attr('data-parsley-required', 'true');
            $("#descripcionAusencia").css("display","none");
            $('#textareaMotivoSolicitudPermiso').attr('data-parsley-required', 'false');
            $('#textareaMotivoSolicitudPermiso').val('');
        }else{
            $("#descripcionAusencia").css("display","block");
            $('#textareaMotivoSolicitudPermiso').attr('data-parsley-required', 'true');
            $("#citaFolio").css("display","none");
            $('#inputCitaFolio').attr('data-parsley-required', 'false');
            $('#inputCitaFolio').val('');
            $("#archivoCitaIncapacidad").css("display","none");
            $('#inputEvidenciaIncapacidad').attr('data-parsley-required', 'false');
            file.limpiar('#inputEvidenciaIncapacidad');
        }
        if ($(this).val() == '') {
            $("#descripcionAusencia").css("display","none");
            $("#citaFolio").css("display","none");
            $("#archivoCitaIncapacidad").css("display","none");
        }
    });

    $('#selectTipoAusencia').on('change', function () {
        switch($(this).val()){
            case '1':
                $("#bloqueHorario").css("display","block");
                $('#labelHora').text('Hora de Entrada');
                break;
            case '2':
                $("#bloqueHorario").css("display","block");
                $('#labelHora').text('Hora de Salida');
                break;
            default:
                $("#bloqueHorario").css("display","none");
        }
    });

    $('#formSolicitudPermiso').on('change', function () {
        if($('#selectTipoAusencia').val() == 3 && $('#selectMotivoAusencia').val() == 1){
            $('#inputDescuento').val('0 %');
            $('#Permisos').on('change', function () {
                const date1 = new Date($('#inputFechaPermisoDesde').val());
                const date2 = new Date($('#inputFechaPermisoHasta').val());
                const diffTime = Math.abs(date2.getTime() - date1.getTime());
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                var totalDescuentoDias;
                switch(diffDays){
                    case 0:
                        totalDescuentoDias = '1.17 %';
                        break;
                    case 1:
                        totalDescuentoDias = '2.34 %';
                        break;
                    case 2:
                        totalDescuentoDias = '3.51 %';
                        break;
                    case 3:
                        totalDescuentoDias = '4.68 %';
                        break;
                    case 4:
                        totalDescuentoDias = '5.85 %';
                        break;
                    case 5:
                        totalDescuentoDias = '7.02 %';
                        break;
                    case 6:
                        totalDescuentoDias = '8.19 %';
                        break;
                    case 7:
                        totalDescuentoDias = '9.36 %';
                        break;
                    case 8:
                        totalDescuentoDias = '10.53 %';
                        break;
                    case 9:
                        totalDescuentoDias = '11.70 %';
                        break;
                    case 10:
                        totalDescuentoDias = '12.87 %';
                        break;
                    default:
                        totalDescuentoDias = '12.87 %';
                        break;
                }
                $('#inputDescuento').val(totalDescuentoDias);
            });
        }
        if($('#selectTipoAusencia').val() == 1 && $('#selectMotivoAusencia').val() == 1){
            $('#Permisos').on('change', function () {
                var valuestart = '09:00 AM';
                var valuestop = $('#selectSolicitudHora').val();

                //create date format          
                var timeHourStart = new Date("01/01/2007 " + valuestart).getHours();
                var timeHourEnd = new Date("01/01/2007 " + valuestop).getHours();
                var timeMinutesStart = new Date("01/01/2007 " + valuestart).getMinutes();
                var timeMinutesEnd = new Date("01/01/2007 " + valuestop).getMinutes();

                var hourDiff = timeHourEnd - timeHourStart;
                var minutesDiff = timeMinutesEnd - timeMinutesStart;
                var totalDescuentoHrs = ((hourDiff+(minutesDiff/60))*1)/9;
                $('#inputDescuento').val(Number.parseFloat(totalDescuentoHrs).toFixed(4) + ' %');
            });
        }
        if($('#selectTipoAusencia').val() == 2 && $('#selectMotivoAusencia').val() == 1){
            $('#Permisos').on('change', function () {
                var valuestart = $('#selectSolicitudHora').val();
                var valuestop = '07:00 PM';

                //create date format          
                var timeHourStart = new Date("01/01/2007 " + valuestart).getHours();
                var timeHourEnd = new Date("01/01/2007 " + valuestop).getHours();
                var timeMinutesStart = new Date("01/01/2007 " + valuestart).getMinutes();
                var timeMinutesEnd = new Date("01/01/2007 " + valuestop).getMinutes();

                var hourDiff = timeHourEnd - timeHourStart;
                var minutesDiff = timeMinutesEnd - timeMinutesStart;
                var totalDescuentoHrs = ((hourDiff+(minutesDiff/60))*1)/9;
                $('#inputDescuento').val(Number.parseFloat(totalDescuentoHrs).toFixed(4) + ' %');
            });
        }
        if ($('#selectMotivoAusencia').val() != 1) {
            $('#Permisos').on('change', function () {
                $("#inputDescuento").val('0 %');
            });
        }
    });
    
    //evento para enviar la solicitud de permisos
    $("#btnGenerarSolicitudPermiso").on("click", function () {
        if (evento.validarFormulario('#formSolicitudPermiso')) {
            var data = {
                nombre: $('#inputNombre').val(),
                idUsuario: $('#idUsuario').val(),
                departamento: $('#inputDepartamento').val(),
                puesto: $('#inputPuesto').val(),
                tipoAusencia: $('#selectTipoAusencia').val(),
                motivoAusencia: $('#selectMotivoAusencia').val(),
                citaFolio: $('#inputCitaFolio').val(),
                descripcionAusencia: $('#textareaMotivoSolicitudPermiso').val(),
                evidenciaIncapacidad: $('#inputEvidenciaIncapacidad').val(),
                fechaPermisoDesde: $('#inputFechaPermisoDesde').val(),
                fechaPermisoHasta: $('#inputFechaPermisoHasta').val(),
                horaAusencia: $('#selectSolicitudHora').val(),
                descuentoPermiso: $('#inputDescuento').val()
            }
            if ( $('#inputEvidenciaIncapacidad').val() !== '' ) {
                file.enviarArchivos('#inputEvidenciaIncapacidad', 'EventoPermisosVacaciones/Permisos', '#panelPermisosVacaciones', data, function (respuesta) {
                    if (respuesta !== 'otraImagen') {
                        window.open(respuesta, '_blank');
                        location.reload();
                    } else {
                        evento.mostrarMensaje('.mensajeSolicitudPermisos', false, 'Hubo un problema con la imagen selecciona otra distinta.', 3000);
                    }
                });
            } else {
                evento.enviarEvento('EventoPermisosVacaciones/Permisos', data, '#panelPermisosVacaciones', function (respuesta) {
                    if (respuesta) {
                        window.open(respuesta, '_blank');
                        location.reload();
                    } else {
                        evento.mostrarMensaje('.mensajeSolicitudPermisos', false, 'Hubo un problema con la solicitud de permiso.', 3000);
                    }
                });
            }
        }
    });

    $('#data-table-permisos-ausencia tbody').on('click', 'tr', function () {

        var informacionPermisoAusencia = $('#data-table-permisos-ausencia').DataTable().row(this).data();
        
        if (informacionPermisoAusencia[7] == "Pendiente por Autorizar") {
            var data = {
                idPermiso: informacionPermisoAusencia[0]
            }
            evento.enviarEvento('EventoPermisosVacaciones/VerModalActualizar', data, '#panelPermisosVacaciones', function (respuesta) {
                if (respuesta) {
                    if (respuesta != "En revision") {
                        $('#contentActualizar').removeClass('hidden').empty().append(respuesta.formulario);
                        $('#contentPermisosVacaciones').addClass('hidden');
//********efectos modal Actualizar
                        file.crearUpload('#inputEvidenciaIncapacidadAct', 'EventoPermisosVacaciones/Permisos', ['pdf']);
                        $('#btnCancelarActualizarPermiso').on('click', function () {
                            $('#contentActualizar').empty().addClass('hidden');
                            $('#contentPermisosVacaciones').removeClass('hidden');
                        });
                        $('#inputFechaDocumentoAct').datepicker({
                            format: 'yyyy-mm-dd'
                        });
                        $('#inputFechaDocumentoAct').datepicker("setDate", new Date());
                        $('#inputFechaPermisoDesdeAct').datepicker({
                            format: 'yyyy-mm-dd'
                        });
                        $('#selectSolicitudHoraAct').timepicker();
                        $('#inputFechaPermisoHastaAct').on('click', function () {
                            diaDesde = $('#inputFechaPermisoDesdeAct').val();
                            $('#inputFechaPermisoHastaAct').val(diaDesde);
                            $('#inputFechaPermisoHastaAct').datepicker({
                                format: 'yyyy-mm-dd',
                                startDate: diaDesde
                            });
                        });
                        $('#selectMotivoAusenciaAct').on('change', function () {
                            if ($(this).val() == '3' || $(this).val() == '4') {
                                $("#citaFolioAct").css("display","block");
                                $('#inputCitaFolioAct').attr('data-parsley-required', 'true');
                                $("#archivoCitaIncapacidadAct").css("display","block");
                                $('#inputEvidenciaIncapacidadAct').attr('data-parsley-required', 'true');
                                $("#descripcionAusenciaAct").css("display","none");
                                $('#textareaMotivoSolicitudPermisoAct').attr('data-parsley-required', 'false');
                                $('#textareaMotivoSolicitudPermisoAct').val('');
                            }else{
                                $("#descripcionAusenciaAct").css("display","block");
                                $('#textareaMotivoSolicitudPermisoAct').attr('data-parsley-required', 'true');
                                $("#citaFolioAct").css("display","none");
                                $('#inputCitaFolioAct').attr('data-parsley-required', 'false');
                                $('#inputCitaFolioAct').val('');
                                $("#archivoCitaIncapacidadAct").css("display","none");
                                $('#inputEvidenciaIncapacidadAct').attr('data-parsley-required', 'false');
                                file.limpiar('#inputEvidenciaIncapacidadAct');
                            }
                            if ($(this).val() == '') {
                                $("#descripcionAusenciaAct").css("display","none");
                                $("#citaFolioAct").css("display","none");
                                $("#archivoCitaIncapacidadAct").css("display","none");
                            }
                        });
                        $('#selectTipoAusenciaAct').on('change', function () {
                            switch($(this).val()){
                                case '1':
                                    $("#bloqueHorarioAct").css("display","block");
                                    $('#labelHoraAct').text('Hora de Entrada');
                                    break;
                                case '2':
                                    $("#bloqueHorarioAct").css("display","block");
                                    $('#labelHoraAct').text('Hora de Salida');
                                    break;
                                default:
                                    $("#bloqueHorarioAct").css("display","none");
                            }
                        });

                        $('#formActualizarPermiso').on('change', function () {
                            if($('#selectTipoAusenciaAct').val() == 3 && $('#selectMotivoAusenciaAct').val() == 1){
                                $('#inputDescuentoAct').val('0 %');
                                $('#ActualizarPermiso').on('change', function () {
                                    const date1 = new Date($('#inputFechaPermisoDesdeAct').val());
                                    const date2 = new Date($('#inputFechaPermisoHastaAct').val());
                                    const diffTime = Math.abs(date2.getTime() - date1.getTime());
                                    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                                    var totalDescuentoDias;
                                    switch(diffDays){
                                        case 0:
                                            totalDescuentoDias = '1.17 %';
                                            break;
                                        case 1:
                                            totalDescuentoDias = '2.34 %';
                                            break;
                                        case 2:
                                            totalDescuentoDias = '3.51 %';
                                            break;
                                        case 3:
                                            totalDescuentoDias = '4.68 %';
                                            break;
                                        case 4:
                                            totalDescuentoDias = '5.85 %';
                                            break;
                                        case 5:
                                            totalDescuentoDias = '7.02 %';
                                            break;
                                        case 6:
                                            totalDescuentoDias = '8.19 %';
                                            break;
                                        case 7:
                                            totalDescuentoDias = '9.36 %';
                                            break;
                                        case 8:
                                            totalDescuentoDias = '10.53 %';
                                            break;
                                        case 9:
                                            totalDescuentoDias = '11.70 %';
                                            break;
                                        case 10:
                                            totalDescuentoDias = '12.87 %';
                                            break;
                                        default:
                                            totalDescuentoDias = '12.87 %';
                                            break;
                                    }
                                    $('#inputDescuentoAct').val(totalDescuentoDias);
                                });
                            }
                            if($('#selectTipoAusenciaAct').val() == 1 && $('#selectMotivoAusenciaAct').val() == 1){
                                $('#ActualizarPermiso').on('change', function () {
                                    var valuestart = '09:00 AM';
                                    var valuestop = $('#selectSolicitudHoraAct').val();
                    
                                    //create date format          
                                    var timeHourStart = new Date("01/01/2007 " + valuestart).getHours();
                                    var timeHourEnd = new Date("01/01/2007 " + valuestop).getHours();
                                    var timeMinutesStart = new Date("01/01/2007 " + valuestart).getMinutes();
                                    var timeMinutesEnd = new Date("01/01/2007 " + valuestop).getMinutes();
                    
                                    var hourDiff = timeHourEnd - timeHourStart;
                                    var minutesDiff = timeMinutesEnd - timeMinutesStart;
                                    var totalDescuentoHrs = ((hourDiff+(minutesDiff/60))*1)/9;
                                    $('#inputDescuentoAct').val(Number.parseFloat(totalDescuentoHrs).toFixed(4) + ' %');
                                });
                            }
                            if($('#selectTipoAusenciaAct').val() == 2 && $('#selectMotivoAusenciaAct').val() == 1){
                                $('#ActualizarPermiso').on('change', function () {
                                    var valuestart = $('#selectSolicitudHoraAct').val();
                                    var valuestop = '07:00 PM';
                    
                                    //create date format          
                                    var timeHourStart = new Date("01/01/2007 " + valuestart).getHours();
                                    var timeHourEnd = new Date("01/01/2007 " + valuestop).getHours();
                                    var timeMinutesStart = new Date("01/01/2007 " + valuestart).getMinutes();
                                    var timeMinutesEnd = new Date("01/01/2007 " + valuestop).getMinutes();
                    
                                    var hourDiff = timeHourEnd - timeHourStart;
                                    var minutesDiff = timeMinutesEnd - timeMinutesStart;
                                    var totalDescuentoHrs = ((hourDiff+(minutesDiff/60))*1)/9;
                                    $('#inputDescuentoAct').val(Number.parseFloat(totalDescuentoHrs).toFixed(4) + ' %');
                                });
                            }
                            if ($('#selectMotivoAusenciaAct').val() != 1) {
                                $('#ActualizarPermiso').on('change', function () {
                                    $("#inputDescuentoAct").val('0 %');
                                });
                            }
                        });


                        $("#btnVerPDFAutorizar").on("click", function () {
                            window.open('/storage/Archivos/'+$('#archivoPDF').val(), '_blank');
                        });
//actualizar permisos
                        $("#btnCancelarPermiso").on("click", function () {
                            var html = '<div class="row m-t-20">\n\
                                <form id="formCancelarPermiso" class="margin-bottom-0" enctype="multipart/form-data">\n\
                                    <div id="modal-dialogo" class="col-md-12 text-center">\n\
                                        <label>¿Estas seguro de Cancelar tu solicitud?</label><br>\n\
                                        <button id="btnCancelarRechazo" type="button" class="btn btn-sm btn-danger"><i class="fa fa-times"></i> Cerrar</button>\n\
                                        <button id="btnAceptarRechazo" type="button" class="btn btn-sm btn-success"><i class="fa fa-check"></i> Aceptar</button>\n\
                                    </div>\n\
                                </form>\n\
                                </div>';
                            $('#btnModalConfirmar').addClass('hidden');
                            $('#btnModalAbortar').addClass('hidden');
                            evento.mostrarModal('Rachazar Permiso', html);
                            $('#btnCancelarRechazo').on('click', function () {
                                evento.cerrarModal();
                            });
                            $('#btnAceptarRechazo').on('click', function () {
                                var dataActualizar = {
                                    idPermiso: $('#idPermisoAct').val()
                                }
                                evento.enviarEvento('EventoPermisosVacaciones/Cancelar', dataActualizar, '#formCancelarPermiso', function (respuesta) {
                                    if (respuesta) {
                                        location.reload();
                                    } else {
                                        evento.mostrarMensaje('.mensajeSolicitudPermisos', false, 'Hubo un problema con la solicitud de permiso.', 3000);
                                    }
                                });
                            });
                        });
                        $("#btnActualizarPermiso").on("click", function () {
                            if (evento.validarFormulario('#formActualizarPermiso')) {
                                var dataActualizar = {
                                    nombre: $('#inputNombreAct').val(),
                                    idUsuario: "",
                                    idPermiso: $('#idPermisoAct').val(),
                                    departamento: $('#inputDepartamentoAct').val(),
                                    puesto: $('#inputPuestoAct').val(),
                                    tipoAusencia: $('#selectTipoAusenciaAct').val(),
                                    motivoAusencia: $('#selectMotivoAusenciaAct').val(),
                                    citaFolio: $('#inputCitaFolioAct').val(),
                                    descripcionAusencia: $('#textareaMotivoSolicitudPermisoAct').val(),
                                    evidenciaIncapacidad: $('#inputEvidenciaIncapacidadAct').val(),
                                    fechaPermisoDesde: $('#inputFechaPermisoDesdeAct').val(),
                                    fechaPermisoHasta: $('#inputFechaPermisoHastaAct').val(),
                                    horaAusencia: $('#selectSolicitudHoraAct').val(),
                                    pdf: $('#archivoPDF').val(),
                                    descuentoPermiso: $('#inputDescuentoAct').val()
                                }
                                if ( $('#inputCitaFolioAct').val() != '' ) {
                                    evento.enviarEvento('EventoPermisosVacaciones/ActualizarPermisoArchivo', dataActualizar, '#panelPermisosVacaciones', function (respuesta) {
                                        if (respuesta !== 'otraImagen') {
                                            location.reload();
                                            window.open(respuesta, '_blank');
                                        } else {
                                            evento.mostrarMensaje('.mensajeSolicitudPermisos', false, 'Hubo un problema con la imagen selecciona otra distinta.', 3000);
                                        }
                                    });
                                } else {
                                    evento.enviarEvento('EventoPermisosVacaciones/ActualizarPermiso', dataActualizar, '#panelPermisosVacaciones', function (respuesta) {
                                        if (respuesta) {
                                            location.reload();
                                            window.open(respuesta, '_blank');
                                        } else {
                                            evento.mostrarMensaje('.mensajeSolicitudPermisos', false, 'Hubo un problema con la solicitud de permiso.', 3000);
                                        }
                                    });
                                };
                            }
                        });
                        //fin
                    } else {
                        evento.mostrarMensaje('.mensajeSolicitudPermisosV1', true, 'El permiso esta en revisión', 3000);
                    }
                } else {
                    evento.mostrarMensaje('.mensajeSolicitudPermisosV1', false, 'Hubo un problema con la solicitud de permiso.', 3000);
                }
            });
        } else {
            if (informacionPermisoAusencia[7] == "Autorizado") {
                evento.mostrarMensaje('.mensajeSolicitudPermisosV1', true, 'El permiso ya fue autorizado', 3000);
            } else {
                if (informacionPermisoAusencia[7] == "Rechazado") {
                    evento.mostrarMensaje('.mensajeSolicitudPermisosV1', false, 'El permiso fue rechazado', 3000);
                }
            }
        }
    });

});
