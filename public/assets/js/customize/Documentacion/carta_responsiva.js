$(function () {
    //Objetos
    var evento = new Base();
    var websocket = new Socket();
    var tabla = new Tabla();
    var servicios = new Servicio();

    //Evento que maneja las peticiones del socket
    websocket.socketMensaje();

    //Muestra la hora en el sistema
    evento.horaServidor($('#horaServidor').val());

    //Evento para cerra la session
    evento.cerrarSesion();

    //Creando tabla de resumen minuta
    tabla.generaTablaPersonal('#data-table-tecnicos-carta-responsiva', null, null, true, true);

    //Evento para mostrar la ayuda del sistema
    evento.mostrarAyuda('Ayuda_Proyectos');

    //Inicializa funciones de la plantilla
    App.init();


    $('#data-table-tecnicos-carta-responsiva tbody').on('click', 'tr', function () {
        var datosTablaCartaResponsiva = $('#data-table-tecnicos-carta-responsiva').DataTable().row(this).data();
        if (datosTablaCartaResponsiva !== undefined) {
            var data = {idUsuario: datosTablaCartaResponsiva[0]};
            if (datosTablaCartaResponsiva !== undefined) {
                evento.enviarEvento('Documentacion/ValidarCartaResponsiva', data, '#panelCartaResponsiva', function (respuesta) {
                    if (respuesta === true) {
                        campoFirma(datosTablaCartaResponsiva[1]);
                    } else if (respuesta !== false) {
                        mostrarPDFCartaResponsiva(respuesta);
                    }
                });
            }
        }
    });

    var campoFirma = function () {
        var nombreTecnico = arguments[0];
        var html = ' <div id="campo_firma">\n\
                                    <div class="panel-body">\n\
                                        <div class="row">\n\
                                            <div class="col-md-12 text-center">\n\
                                                <div class="form-group">\n\
                                                    <p>A través de la presente carta responsiva hago constar que el motivo de la carta es por la responsiva del FONDO FIJO Sirva éste como comprobante de entrega del fondo fijo, para uso exclusivo de atención de reportes asignados para el desempeño de mis actividades laborales. Conozco los montos pre-autorizados por cada concepto y debo entregar las comprobaciones correspondientes en las fechas establecidas.</p>\n\
                                                </div>\n\
                                            </div>\n\
                                        </div>\n\
                                        <div class="row">\n\
                                                <div id="col-md-12 text-center">\n\
                                                    <div id="campoLapiz"></div>\n\
                                                </div>\n\
                                        </div>\n\
                                        <div class="row m-t-35">\n\
                                            <div class="col-md-12">\n\
                                                <div class="errorFirma"></div>\n\
                                            </div>\n\
                                        </div>\n\
                                    </div>\n\
                                </div>';

        evento.mostrarModal('Carta Responsiva', html);

        var myBoard = servicios.campoLapiz('campoLapiz');

        $('#btnModalConfirmar').off('click');
        $('#btnModalConfirmar').on('click', function () {
            var imgFirma = myBoard.getImg();
            var imgInputFirma = (myBoard.blankCanvas == imgFirma) ? '' : imgFirma;
            if (imgInputFirma !== '') {
                var data = {img: imgInputFirma, nombreTecnico: nombreTecnico};
                evento.enviarEvento('Documentacion/GuardarFirmaCartaResponsiva', data, '#modal-dialogo', function (respuesta) {
                    if (respuesta) {
                        evento.cerrarModal();
                        location.reload();
                    }
                });
            } else {
                evento.mostrarMensaje('.errorFirma', false, 'Falta firma.', 4000);
            }
        });
    }

    var mostrarPDFCartaResponsiva = function () {
        var pdf = arguments[0];
        var html = '<div class="embed-responsive embed-responsive-16by9">\n\
                                        <iframe class="embed-responsive-item" src="' + pdf + '" allowfullscreen></iframe>\n\
                                    </div>';
        evento.mostrarModal('PDF', html);
        $('#btnModalConfirmar').addClass('hidden');
        $('#btnModalAbortar').empty().append('Cerrar');
    }

});