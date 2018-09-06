$(function () {

    //Objetos
    var evento = new Base();
    var websocket = new Socket();
    var tabla = new Tabla();

    //Evento que maneja las peticiones del socket
    websocket.socketMensaje();

    //Muestra la hora en el sistema
    evento.horaServidor($('#horaServidor').val());

    //Evento para cerra la session
    evento.cerrarSesion();

    //Inicializa funciones de la plantilla
    App.init();

    tabla.generaTablaPersonal('#data-table-notificaciones', null, null, true, true, [[1, 'desc']]);

    $('#data-table-notificaciones tbody').on('click', 'tr', function () {
        var datos = $('#data-table-notificaciones').DataTable().row(this).data();
        var data = {Id: datos[1]};
        var mensaje = '<div class="row">\n\
                            <div class="col-xs-12 col-sm-8 col-md-9">\n\
                                Área: ' + datos[3] + '\n\
                            </div>\n\
                            <div class="col-xs-12 col-sm-4 col-md-3">\n\
                                Fecha: ' + datos[5] + ' \n\
                            </div>\n\
                       </div>\n\
                        <div class="row m-t-10">\n\
                            <div class="col-xs-12 col-sm-8 col-md-9">\n\
                                Tipo: ' + datos[4] + '\n\
                            </div>\n\
                            <div class="col-xs-12 col-sm-4 col-md-3">\n\
                                Solicita : ' + datos[2] + ' \n\
                            </div>\n\
                       </div>\n\
                        <div class="row m-t-10">\n\
                            <div class="col-md-12">\n\
                                Descripción:\n\
                            </div>\n\
                            <div class="col-md-12">\n\
                                <strong>' + datos[6] + '</strong>\n\
                            </div>\n\
                       </div>';
        evento.mostrarModal('Notificacion', mensaje);

        if (datos[7] !== '' && datos[7] !== null) {
            $('#btnModalConfirmar').empty().append('Seguimiento');
        } else {
            $('#btnModalConfirmar').addClass('hidden');
        }
        $('#btnModalAbortar').empty().append('Cerrar');

        $('#btnModalConfirmar').off('click');

        $('#btnModalConfirmar').on('click', function () {
            evento.enviarEvento('Notificacion/Abierta', data, '#modal-dialogo', function (respuesta) {
                evento.enviarPagina(datos[7]);
            });
        });

        $('#btnModalAbortar').off('click');

        $('#btnModalAbortar').on('click', function () {
            var cantidad, notificaciones = '', cabecera;
            evento.enviarEvento('Notificacion/Abierta', data, '#modal-dialogo', function (respuesta) {
                tabla.limpiarTabla('#data-table-notificaciones');
                $.each(respuesta.Notificaciones, function (key, value) {
                    if (value.Flag === '1') {
                        tabla.agregarFila(
                                '#data-table-notificaciones',
                                [
                                    '<a href="#"><i class="fa fa-folder-o"></i></a>',
                                    value.Id,
                                    value.Remitente,
                                    value.Departamento,
                                    value.Tipo,
                                    value.Fecha,
                                    value.Descripcion,
                                    value.Url
                                ]);
                    } else {
                        tabla.agregarFila(
                                '#data-table-notificaciones',
                                [
                                    '<a href="#"><i class="fa fa-folder-open"></i></a>',
                                    value.Id,
                                    value.Remitente,
                                    value.Departamento,
                                    value.Tipo,
                                    value.Fecha,
                                    value.Descripcion,
                                    value.Url
                                ]);
                    }

                });

                $.each(respuesta.MenuCabecera, function (key, value) {
                    if (typeof value.cantidad === 'undefined') {
                        notificaciones += '<li class="media">\n\
                                                    <a href="/Generales/Notificaciones">\n\
                                                        <div class="media-left"><i class="fa fa-envelope media-object bg-blue-lighter"></i></div>\n\
                                                        <div class="media-body">\n\
                                                            <h6 class="media-heading">' + value.Tipo + '</h6>\n\
                                                            <div class="text-muted f-s-11">\n\
                                                                <p>' + value.Departamento + '</p>\n\
                                                                <p>' + value.Fecha + '</p>\n\
                                                            </div>\n\
                                                        </div>\n\
                                                    </a>\n\
                                                </li>';
                    } else {
                        cantidad = value.cantidad;
                    }
                });
                cabecera = '<a href="javascript:;" data-toggle="dropdown" class="dropdown-toggle f-s-14">\n\
                                <i class="fa fa-bell-o"></i>';
                if (cantidad > 0) {
                    $('#notificaciones-menu').empty().append(cantidad);
                    cabecera += '<span class="label">' + cantidad + '</span>';
                } else if (cantidad === 0) {
                    $('#notificaciones-menu').empty();
                }
                cabecera += '</a><ul class="dropdown-menu media-list pull-right animated fadeInDown">';
                if (cantidad > 0) {
                    cabecera += '<li class="dropdown-header">Notificaciones (' + cantidad + ')</li>';
                } else {
                    cabecera += '<li class="dropdown-header">Sin Notificaciones </li>';
                }

                if (typeof notificaciones !== '') {
                    cabecera += notificaciones;
                }
                cabecera += '<li class="dropdown-footer text-center">\n\
                                    <a href="/Generales/Notificaciones">Ver mas</a>\n\
                                </li>\n\
                            </ul>';
                $('#notificaciones-cabecera').empty().append(cabecera);
                $('#modal-dialogo').modal('hide');
            });
        });


    });

});