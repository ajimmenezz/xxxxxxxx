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
                    if (respuesta.resultado === true) {
                        campoFirma(datosTablaCartaResponsiva[1], respuesta.direccionSiccob, respuesta.nombreUsuario, respuesta.montoFijo);
                    } else if (respuesta.resultado === 'existePDF') {
                        mostrarPDFCartaResponsiva(respuesta.cartaResponsiva);
                    } else if (respuesta.resultado === 'faltaMonto') {
                        servicios.mensajeModal(
                                'No tienes asignado un Monto Fijo (Favor de comunicarse con su supervisor para se lo asigne).',
                                'Advertencia',
                                true);
                    }
                });
            }
        }
    });

    var campoFirma = function () {
        var nombreTecnico = arguments[0];
        var direccionSiccob = arguments[1];
        var nombreUsuario = arguments[2];
        var montoFijo = arguments[3];
        var fecha = new Date();
        var options = {year: 'numeric', month: 'long', day: 'numeric'};

        var html = ' <div id="campo_firma">\n\
                                    <div class="panel-body">\n\
                                        <div class="row">\n\
                                            <div class="col-md-12">\n\
                                                <div class="form-group">\n\
                                                  <h4>SICCOB SOLUTIONS S.A. DE C.V.</h4>\n\
                                                  <p>' + direccionSiccob + '</p>\n\
                                                </div>\n\
                                            </div>\n\
                                        </div>\n\
                                       <div class="row">\n\
                                            <div class="col-md-12 text-right">\n\
                                                <div class="form-group">\n\
                                                    <p>Ciudad de México a ' + fecha.toLocaleDateString("es-ES", options) + '</p>\n\
                                                </div>\n\
                                            </div>\n\
                                        </div>\n\
                                        <div class="row">\n\
                                            <div class="col-md-12">\n\
                                                <div class="form-group">\n\
                                                    <p>Recibo en este momento la cantidad, de:<br>\n\
                                                        $' + montoFijo + ' propiedad de SICCOB SOLUTIOS S.A. DE C.V.  Para la Creación de un Fondo Fijo “Revolvente”, para Gastos Menores, de placas y tenencia. Mismo que recibo en Custodia, para su buen Uso, siendo Responsable del correcto manejo de él, y me comprometo a Devolverlo, en el instante que me sea requerido.\n\
                                                    </p>\n\
                                                    <p>\n\
                                                        Hago constar, que he leído, y comprendido, el Procedimiento de Control Interno de la “Caja y fondo fijo” formulando por la Gerencia Administrativa Corporativa, el cual seguiré cabalmente.\n\
                                                    </p>\n\
                                                </div>\n\
                                            </div>\n\
                                        </div>\n\
                                        <div class="row">\n\
                                            <div class="col-md-12 text-center">\n\
                                                <div class="form-group">\n\
                                                  <h4>RECIBO DE CONFORMIDAD</h4>\n\
                                                </div>\n\
                                            </div>\n\
                                        </div>\n\
                                        <div class="row">\n\
                                                <div id="col-md-12 text-center">\n\
                                                    <div id="campoLapiz"></div>\n\
                                                </div>\n\
                                        </div>\n\
                                       <div class="row m-t-35"">\n\
                                            <div class="col-md-12 text-center">\n\
                                                <div class="form-group">\n\
                                                    <p>' + nombreUsuario + '</p>\n\
                                                </div>\n\
                                            </div>\n\
                                        </div>\n\
                                        <div class="row m-t-35">\n\
                                            <div class="col-md-12">\n\
                                                <div class="errorFirma"></div>\n\
                                            </div>\n\
                                        </div>\n\
                                    </div>\n\
                                </div>';

        evento.mostrarModal('CARTA RESPONSIVA', html, 'text-right');

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