$(function () {

    var evento = new Base();
    var peticion = new Utileria();

    //Muestra la hora en el sistema
    evento.horaServidor($('#horaServidor').val());
    //Evento para cerra la session
    evento.cerrarSesion();

    App.init();

    let calendario = new Calendario('calendar');
    let selectCancelacion = new SelectBasico('selectCancelacion');
    let permisos = [];
    let infoPermiso = null;

    peticion.enviar('', 'CalendarioPermisos/datosPermiso', null, function (respuesta) {
        let listaTemporalPermisos = {}, titulo = '', puesto = '';

        $.each(respuesta.permisos, function (key, value) {
            titulo = value.Usuario.substring(0, 8) + "...";
            puesto = value.Perfil.substring(0, 9) + "...";
            listaTemporalPermisos = {
                id: value.Id,
                title: titulo + "\n" + puesto,
                nombreUsuario: value.Usuario,
                perfil: value.Perfil,
                tipoAusencia: value.Ausencia,
                MotivoAusencia: value.Motivo,
                start: value.FechaAusenciaDesde,
                end: value.FechaAusenciaHasta,
                fechaAusencia: value.FechaAusenciaDesde,
                horaEntrada: value.HoraEntrada,
                horaSalida: value.HoraSalida,
                estatus: value.Estatus,
                description: value.Descripcion,
                autorizacionJefe: value.AutorizacionJefe,
                idJefe: value.idUsuarioJefe,
                autorizacionRH: value.AutorizacionRH,
                autorizacionContador: value.AutorizacionContabilidad,
                archivo: value.Archivo,
                idPerfil: value.IdPerfil,
                idUsuario: value.IdUsuario,
                rechazo: value.Rechazo
            }
            permisos.push(listaTemporalPermisos)
        });
        calendario.cargarInformacionCalendario(permisos);

        calendario.setEventoClick(function (infoEvento) {
            infoPermiso = infoEvento;
            let hoy = moment().format('YYYY-MM-DD');
            $('#modalDatosPermiso').modal();
            $('#nombreUsuario').text(infoEvento.nombreUsuario);
            $('#perfilUsuario').text(infoEvento.perfil);
            $('#tipoAusencia').text(infoEvento.tipoAusencia);
            $('#motivoAusencia').text(infoEvento.MotivoAusencia);
            $('#fechaAusencia').text(infoEvento.fechaAusencia);
            if (infoEvento.tipoAusencia === "Llegada tarde") {
                $('#horaAusencia').text(infoEvento.horaEntrada);
            } else {
                $('#horaAusencia').text(infoEvento.horaSalida);
            }
            $('#estatusAusencia').text(infoEvento.estatus);
            $('#descripcionAusencia').text(infoEvento.description);
            if (infoEvento.autorizacionJefe !== null) {
                $('#autorizacionJefe').text(infoEvento.autorizacionJefe);
                $('#circleJefe').addClass("text-success");
            } else {
                $('#circleJefe').addClass("text-danger");
            }
            if (infoEvento.autorizacionRH !== null) {
                $('#autorizacionJefe').text(infoEvento.autorizacionRH);
                $('#circleRecursosHumanos').addClass("text-success");
            } else {
                $('#circleRecursosHumanos').addClass("text-danger");
            }
            if (infoEvento.autorizacionContador !== null) {
                $('#autorizacionJefe').text(infoEvento.autorizacionContador);
                $('#circleContabilidad').addClass("text-success");
            } else {
                $('#circleContabilidad').addClass("text-danger");
            }
            if (infoEvento.fechaAusencia < hoy) {
                $('#btnCancelarModalPermisos').addClass('hidden');
            }
        });
        datosSelect(respuesta.motivosCancelacion);
    });

    function datosSelect(motivosCancelacion) {
        selectCancelacion.iniciarSelect();
        selectCancelacion.cargaDatosEnSelect(motivosCancelacion);
        $('input[type="checkbox"]').click(function () {
            if ($(this).prop("checked") === true) {
                $('#otroMotivo').removeClass('hidden');
                $('#selectCancelacion').attr('data-parsley-required', 'false');
                $('#listaMotivos').addClass('hidden');
                $('#textareaMotivoSolicitarCancelacion').attr('data-parsley-required', 'true');
            } else {
                $('#otroMotivo').addClass('hidden');
                $('#textareaMotivoSolicitarCancelacion').attr('data-parsley-required', 'false');
                $('#listaMotivos').removeClass('hidden');
                $('#selectCancelacion').attr('data-parsley-required', 'true');
            }
        });
    }

    $('#btnCancelarModalPermisos').on('click', function () {
        $('.seccionInfoPermiso').addClass('hidden');
        $('.seccionSolicitarCancelarPermiso').removeClass('hidden');
    });
    $('#btnCerrarCancelacion').on('click', function () {
        $('.seccionInfoPermiso').removeClass('hidden');
        $('.seccionSolicitarCancelarPermiso').addClass('hidden');
    });
    $('#btnAceptarCancelacion').on('click', function () {
        if (evento.validarFormulario('#motivoSolicitudCancelacion')) {
            let datos = {
                motivoSelect: selectCancelacion.obtenerValor(),
                motivoTextArea: $('#textareaMotivoSolicitarCancelacion').val(),
                idUsuario: infoPermiso.idUsuario,
                nombreUsuario: infoPermiso.nombreUsuario,
                tipoAusencia: infoPermiso.tipoAusencia,
                MotivoAusencia: infoPermiso.MotivoAusencia,
                fechaAusencia: infoPermiso.fechaAusencia,
                idJefe: infoPermiso.idJefe
            }
            peticion.enviar('', 'CalendarioPermisos/peticionCancelar', datos, function (respuesta) {
                console.log(respuesta);
            });
        }
    });
    $('#btnCerrarModalPermisos').on('click', function () {
        $('.limpiarCampo').empty();
        $('.limpiarIcono').removeClass("text-success");
        $('.limpiarIcono').removeClass("text-danger");
        $('#btnCancelarModalPermisos').removeClass("hidden");
    });
});