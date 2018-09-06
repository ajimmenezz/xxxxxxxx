$(function () {
    //Objetos
    var evento = new Base();
    var file = new Upload();
    var websocket = new Socket();
    var tabla = new Tabla();

    //Creando tabla proyectos sin iniciar
    tabla.generaTablaPersonal('#data-table-solicitudes-multimedia', null, null, true, true);

    //Evento que maneja las peticiones del socket
    websocket.socketMensaje();

    //Muestra la hora en el sistema
    evento.horaServidor($('#horaServidor').val());

    //Evento para cerra la session
    evento.cerrarSesion();

    //Evento para mostrar la ayuda del sistema
    evento.mostrarAyuda('Ayuda_Proyectos');

    //Inicializa funciones de la plantilla
    App.init();


    //Evento que permite llevar el control
    $('#data-table-solicitudes-multimedia tbody').on('click', 'tr', function () {
        var datosTabla = $('#data-table-solicitudes-multimedia').DataTable().row(this).data();
        if (datosTabla != undefined) {
            var data = {ticket: datosTabla[0]};
            evento.enviarEvento('Evento/MostrarFormularioSolicitudMultimedia', data, '#seccionSolicitudesMultimedia', function (respuesta) {
                $('#formularioSolicitudesMultimedia').removeClass('hidden').empty().append(respuesta.formulario);
                $('#tablaSolicitudesMultimedia').addClass('hidden');
                $('#btnRegresarSM').removeClass('hidden');
                $('#inputFechaSolicitaron').datetimepicker({
                    format: 'YYYY-MM-DD HH:mm:ss'
                });
                $('#inputFechaRecibieron').datetimepicker({
                    format: 'YYYY-MM-DD HH:mm:ss'
                });
                //valida si la informacion de la silicitud a multimedia existe
                if (respuesta.datos.detallesSM[0] != undefined) {
                    $('#inputFechaSolicitaron').val(respuesta.datos.detallesSM[0].FechaSolicitud);
                    $('#inputFechaRecibieron').val(respuesta.datos.detallesSM[0].FechaApoyo);
                }
                //Creando las evidencias.
                var dataExtra = {ticket: datosTabla[0]};
                var array = [];
                file.crearUpload('#inputEvidenciasSolicitaron',
                        'Evento/NuevaEvidencia',
                        null,
                        null,
                        respuesta.datos.evidenciaSolicitud,
                        'Evento/EliminarEvidencia',
                        array = ['EvidenciaSolicitud', datosTabla[0]],
                        null,
                        null,
                        null,
                        true,
                        dataExtra
                        );
                file.crearUpload('#inputEvidenciasRecibieron',
                        'Evento/NuevaEvidencia',
                        null,
                        null,
                        respuesta.datos.evidenciaApoyo,
                        'Evento/EliminarEvidencia',
                        array = ['EvidenciaApoyo', datosTabla[0]],
                        null,
                        null,
                        null,
                        true,
                        dataExtra
                        );
                //Evento para insertar la informacion
                $('#btnGuardarSolicitudMultimedia').on('click', function () {
                    var fechaSolicitaron = $('#inputFechaSolicitaron').val();
                    var fechaRecibieron = $('#inputFechaRecibieron').val();
                    var datos = {ticket: datosTabla[0], fechaSolicitaron: fechaSolicitaron, fechaRecibieron: fechaRecibieron};
                    if (fechaSolicitaron != '' || fechaRecibieron !== '') {
                        evento.enviarEvento('Evento/insertarSolicitudMultimedia', datos, '#seccionSolicitudesMultimedia', function (respuesta) {
                            if (respuesta === true) {
                                evento.mostrarMensaje('.errorSolicitudesMultimedia', true, 'Datos Actualizados correctamente', 3000);
                            } else {
                                evento.mostrarMensaje('.errorSolicitudesMultimedia', false, 'No se pudo actualizar los datos vuelva a intentarlo.', 3000);
                            }
                        });
                    } else {
                        evento.mostrarMensaje('.errorSolicitudesMultimedia', false, 'Debes llenar por lo menos un campo de fecha', 3000);
                    }
                });
            });
        }
        else{
            console.log('No hay datos que mostrar');
        }
    });
    $('#btnRegresarSM').on('click', function () {
        $('#tablaSolicitudesMultimedia').removeClass('hidden');
        $('#formularioSolicitudesMultimedia').addClass('hidden');
        $('#btnRegresarSM').addClass('hidden');
    });
});