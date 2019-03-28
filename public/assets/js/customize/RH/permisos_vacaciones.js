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
    
    //evento para enviar la solicitud de permisos
    $("#btnGenerarSolicitudPermiso").on("click", function () {
        if (evento.validarFormulario('#formSolicitudPermiso')) {
            var data = {
                fechaDocumento: $('#inputFechaDocumento').val(),
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
                horaAusencia: $('#selectSolicitudHora').val()
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
                        $("#btnVerPDFAutorizar").on("click", function () {
                            window.open('/storage/Archivos/'+$('#archivoPDF').val(), '_blank');
                        });
//actualizar permiso
                        $("#btnActualizarPermiso").on("click", function () {
                            if (evento.validarFormulario('#formActualizarPermiso')) {
                                var dataActualizar = {
                                    fechaDocumento: $('#inputFechaDocumentoAct').val(),
                                    nombre: $('#inputNombreAct').val(),
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
                                    pdf: $('#archivoPDF').val()
                                }
                                if ( $('#inputCitaFolioAct').val() != '' ) {
                                    evento.enviarEvento('EventoPermisosVacaciones/ActualizarPermisoArchivo', dataActualizar, '', function (respuesta) {
                                        if (respuesta !== 'otraImagen') {
                                            window.open(respuesta, '_blank');
                                            location.reload();
                                        } else {
                                            evento.mostrarMensaje('.mensajeSolicitudPermisos', false, 'Hubo un problema con la imagen selecciona otra distinta.', 3000);
                                        }
                                    });
                                } else {
                                    evento.enviarEvento('EventoPermisosVacaciones/ActualizarPermiso', dataActualizar, '', function (respuesta) {
                                        if (respuesta) {
                                            window.open(respuesta, '_blank');
                                            location.reload();
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
