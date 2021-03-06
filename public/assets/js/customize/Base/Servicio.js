function Servicio() {
//Constructor del la clase Tabla
    this.file = new Upload();
    this.tabla = new Tabla();
    this.select = new Select();
    this.nota = new Nota();
}
//Herencia del objeto Base
Servicio.prototype = new Base();
Servicio.prototype.constructor = Servicio;

//Evento para crear un nuevo servicio interno desde del mismo servicio
Servicio.prototype.nuevoServicio = function () {
    var _this = this;
    var data = arguments[0];
    var ticket = arguments[1];
    var idSolicitud = arguments[2];
    var eventoModal = arguments[3];
    var seccionPanel = arguments[4];
    var eventoServicioNuevo = arguments[5];
    var evento = new Base();
    var select = new Select();

    evento.enviarEvento(eventoModal, data, seccionPanel, function (respuesta) {
        evento.mostrarModal('Nuevo Servicio', respuesta.formulario);
        $('#btnModalConfirmar').addClass('hidden');
        $('#btnModalAbortar').addClass('hidden');
        select.crearSelect('#selectTipoServicio');
        select.crearSelect('#selectAtiendeServicio');

        //Evento para select para mostrar el encargado de Trafico
        $('#selectTipoServicio').on('change', function () {
            if ($(this).val() !== '' && $(this).val() === '5') {
                $('#selectAtiendeServicio').val('42').trigger('change');
            } else {
                $('#selectAtiendeServicio').val('').trigger('change');
            }
        });

        $('#btnAgregarServicio').on('click', function () {
            $('#btnCancelarServicio').attr('disabled', 'disabled');
            if (evento.validarFormulario('#formNuevoServicio')) {
                var tipoServicio = $('#selectTipoServicio').val();
                var atiende = $('#selectAtiendeServicio').val();
                var descripcion = $('#inputDescripcionServicio').val();
                var dataServicio = {Ticket: ticket, IdSolicitud: idSolicitud, IdTipoServicio: tipoServicio, Atiende: atiende, Descripcion: descripcion, servicio: data['servicio']};

                evento.enviarEvento(eventoServicioNuevo, dataServicio, '#modal-dialogo', function (respuesta) {
                    if (respuesta instanceof Array) {
                        eventoCalendario(dataServicio);
                        _this.mensajeModal('Se creo correctamente el nuevo servicio', 'Correcto');
                    } else {
                        var html = '<div class="row">\n\
                                                <div id="mensaje-modal" class="col-md-12 text-center">\n\
                                                    <h3>Vuelva a interntarlo</h3>\n\
                                                </div>\n\
                                            </div>';
                        $('#btnModalAbortar').removeClass('hidden');
                        evento.mostrarModal('Error', html);
                        $('#btnModalAbortar').empty().append('Cerrar');
                    }
                });
            }
        });

        var eventoCalendario = function () {
            var ticketCalendario = arguments[0];
            var nombreServicio = $('#selectTipoServicio option:selected').text();
            var atiende = $('#selectAtiendeServicio option:selected').text();
            var emailCorporativo = $('#selectAtiendeServicio option:selected').attr('data');
            var descripcion = $('#inputDescripcionServicio').val();
            var now = new Date();
            var fecha = moment(now).format();

            resource = {
                "summary": "Atención a ticket",
                "description": "Nuevo servicio.Se a agregado para su atención del ticket " + ticketCalendario.Ticket + " con la siguiente descripcion " + descripcion,
                "location": "Ciudad de México, CDMX",
                "start": {
                    "dateTime": now,
                    "timeZone": "America/Mexico_City"
                },
                "end": {
                    "dateTime": now,
                    "timeZone": "America/Mexico_City"
                },
                "attendees": [
                    {
                        "email": emailCorporativo,
                        "displayName": atiende,
                        "responseStatus": "accepted"

                    }]
            };

            handleClientLoad(resource, true);
        };

        $('#btnCancelarServicio').on('click', function () {
            evento.cerrarModal();
        });
    });
};

//Evento para cancelar un servicio interno desde del mismo servicio
Servicio.prototype.cancelarServicio = function () {
    var _this = this;
    var data = arguments[0];
    var eventoModal = arguments[1];
    var seccionPanel = arguments[2];
    var eventoServicioCancelar = arguments[3];
    var html = '<div class="row">\n\
                            <div id="mensaje-modal" class="col-md-12 text-center">\n\
                                <h3>¿Realmente quiere cancelar el Servicio?</h3>\n\
                            </div>\n\
                      </div>';
    html += '<div class="row m-t-20">\n\
                                <div class="col-md-12 text-center">\n\
                                    <button id="btnAceptarConcluirServicio" type="button" class="btn btn-sm btn-success"><i class="fa fa-check"></i> Aceptar</button>\n\
                                    <button id="btnCancelarConcluirServicio" type="button" class="btn btn-sm btn-danger"><i class="fa fa-times"></i> Cancelar</button>\n\
                                </div>\n\
                            </div>';
    $('#btnModalConfirmar').addClass('hidden');
    $('#btnModalAbortar').addClass('hidden');
    _this.mostrarModal('Advertencia', html);
    $('#btnModalConfirmar').empty().append('Eliminar');
    $('#btnModalConfirmar').off('click');

    $('#btnAceptarConcluirServicio').on('click', function () {
        $('#btnCancelarConcluirServicio').attr('disabled', 'disabled');
        $('#btnAceptarConcluirServicio').attr('disabled', 'disabled');
        _this.enviarEvento(eventoModal, data, '#modal-dialogo', function (respuesta) {
            _this.mostrarModal('Cancelar Servicio', respuesta.formulario);
            $('#btnServicioCancelar').on('click', function () {
                if (_this.validarFormulario('#formCancelarServicio')) {
                    var descripcion = $('#inputDescripcionServicioCancelar').val();
                    var dataServicio = {Descripcion: descripcion, servicio: data['servicio'], ticket: data['ticket']};
                    _this.enviarEvento(eventoServicioCancelar, dataServicio, '#modal-dialogo', function (respuesta) {
                        if (respuesta instanceof Array) {
                            _this.mensajeModal('Se cancelo correctamente el servicio', 'Correcto');
                        } else {
                            var html = '<div class="row">\n\
                                                <div id="mensaje-modal" class="col-md-12 text-center">\n\
                                                    <h3>' + respuesta + '</h3>\n\
                                                </div>\n\
                                            </div>';
                            $('#btnModalAbortar').removeClass('hidden');
                            _this.mostrarModal('Error', html);
                            $('#btnModalAbortar').empty().append('Cerrar');
                        }
                    });
                }
            });
            $('#btnCancelarServicioCancelar').on('click', function () {
                _this.cerrarModal();
            });
        });
    });

    //Envento para no concluir con la cancelacion
    $('#btnCancelarConcluirServicio').on('click', function () {
        _this.cerrarModal();
    });
};

//Evento para cancelar un servicio interno desde del mismo servicio
Servicio.prototype.nuevaSolicitud = function () {
    var _this = this;
    var evento = new Base();

    //variables
    var departamentos;
    var ticket = arguments[0];
    var data = [];

    //Obteniendo los datos de los departamentos    
    evento.enviarEvento('/Generales/Solicitud/Solicitud_CatDepartamentos', {}, '#panelNuevaSolicitud', function (respuesta) {
        departamentos = respuesta;
    });

    evento.enviarEvento('/Generales/Solicitud/Formulario_Nueva_Solicitud', {'apoyo': true, 'ticket': ticket}, null, function (respuesta) {
        _this.mostrarModal('Solicitar apoyo a otra área', respuesta.html);
        $("#modal-dialogo #content").prop('style', 'margin-left:0px !important;');
        var select = new Select();

        //Creando input de evidencias
        _this.file.crearUpload('#inputEvidenciasSolicitud', '/Generales/Solicitud/Nueva_solicitud');

        $('#inputProgramada').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss'
        });

        $('#inputLimiteAtencion').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss'
        });

        $('#botonesExtra').addClass('hidden');

        //Evento de select area que activa departamento
        $('#selectAreasSolicitud').on('change', function () {
            if ($(this).val() !== '') {
                $('#selectDepartamentoSolicitud').removeAttr('disabled');
                if ($(this).val() === 'sinArea') {
                    select.setOpcionesSelect('#selectDepartamentoSolicitud', departamentos, $('#selectAreasSolicitud').val(), 'IdArea', {id: 'sinDepartamento', text: 'Sin departamento'});
                    select.cambiarOpcion('#selectDepartamentoSolicitud', 'sinDepartamento');
                } else {
                    select.setOpcionesSelect('#selectDepartamentoSolicitud', departamentos, $('#selectAreasSolicitud').val(), 'IdArea');
                }
            } else {
                $('#selectDepartamentoSolicitud').attr('disabled', 'disabled');
                select.cambiarOpcion('#selectDepartamentoSolicitud', '');
            }
        });

        $("#selectClienteSolicitud").on("change", function () {
            $('#selectSucursalSolicitud').empty().append('<option value="">Seleccionar</option>');
            select.cambiarOpcion('#selectSucursalSolicitud', '');
            var cliente = $(this).val();

            if (cliente !== '') {
                $('#selectSucursalSolicitud').removeAttr('disabled');
                var data = {cliente: cliente};

                evento.enviarEvento('/Generales/Solicitud/MostrarSucursalesCliente', data, '#panelSeguimientoActividad', function (respuesta) {
                    $.each(respuesta, function (key, valor) {
                        $("#selectSucursalSolicitud").append('<option value=' + valor.Id + '>' + valor.Nombre + '</option>');
                    });
                });
            } else {
                $('#selectSucursalSolicitud').attr('disabled', 'disabled');
            }
        });

        $("#btnGenerarSolicitud").empty().append('Generar Solicitud').on("click", function () {
            var verificarFechas;

            if ($('#inputProgramada').val() !== '' && $('#inputLimiteAtencion').val() !== '') {
                verificarFechas = false;
            } else {
                verificarFechas = true;
            }

            if (evento.validarFormulario('#formNuevaSolicitud')) {
                if (verificarFechas) {
                    var data = {
                        ticket: ticket,
                        tipo: '3',
                        departamento: $('#selectDepartamentoSolicitud').val(),
                        prioridad: $('#selectPrioridadSolicitud').val(),
                        descripcion: $('#textareaDescripcionSolicitud').val(),
                        asunto: $('#inputAsuntoSolicitud').val(),
                        servicio: $("#hiddenServicio").val(),
                        personalSD: $("#selectPersonalSD").val(),
                        folio: $('#inputFolioSolicitud').val(),
                        sucursal: $('#selectSucursalSolicitud').val(),
                        fechaProgramada: $('#inputProgramada').val(),
                        fechaLimiteAtencion: $('#inputLimiteAtencion').val()
                    };
                    _this.file.enviarArchivos('#inputEvidenciasSolicitud', '/Generales/Solicitud/Nueva_solicitud', '#panelNuevaSolicitud', data, function (respuesta) {
                        if (respuesta) {
                            if (data.personalSD != "") {
                                data.solicitud = respuesta;
                                evento.enviarEvento('/Generales/Solicitud/ReasignarFolioSD', data, null, function (respuesta) {
                                });
                            }

                            $("#modal-dialogo .modal-body").empty().append('<div class="row">\n\
                                        <div class="col-md-12 text-center">\n\
                                            <h5>Se genero la solicitud <b>' + respuesta + '</b></h5>\n\
                                        </div>\n\
                                    </div>');
                            $('#btnModalConfirmar').addClass('hidden');
                            $('#btnModalAbortar').empty().append('Cerrar').removeClass('hidden');
                        } else {
                            evento.mostrarMensaje('.errorSolicitudNueva', false, 'No se pudo generar la solicitud vuelva a intentarlo', 3000);
                        }

                        $('#btnModalAbortar').on('click', function () {
                            $('#btnCancelarNuevaSolicitud').trigger('click');
                        });
                    });
                }
            } else {
                evento.mostrarMensaje('.errorSolicitudNueva', false, 'Debe seleccionar solo una fecha.', 3000);
            }
        });

        $("#btnCancelarNuevaSolicitud").empty().append('Cancelar Solicitud').on("click", function () {
            _this.file.limpiar('#inputEvidenciasSolicitud');
            evento.limpiarFormulario('#formNuevaSolicitud');
            _this.cerrarModal();
        });

        //Evento de select personal para seleccionar su area y departamento
        $('#selectParsonalSolicitud').on('change', function () {
            var perfil = $(this).val();
            var dataPerfil = {perfil: perfil};
            evento.enviarEvento('/Generales/Solicitud/BuscarAreaDepartamento', dataPerfil, '#panelNuevaSolicitud', function (respuesta) {
                if (respuesta) {
                    select.cambiarOpcion('#selectAreasSolicitud', respuesta[0].Area);
                    select.cambiarOpcion('#selectDepartamentoSolicitud', respuesta[0].Departamento);
                }
            });
        });

        $('#campoCorreo').empty();
    });

    $('#btnModalConfirmar').addClass('hidden');
    $('#btnModalAbortar').addClass('hidden');

};

//Evento para mostrar mensaje de confirmacion en forma de modal
Servicio.prototype.mensajeModal = function () {
    var _this = this;
    var mensaje = arguments[0];
    var titulo = arguments[1];
    var recargarPagina = arguments[2] || false;
    var evento = new Base();
    var html = '<div class="row">\n\
                            <div id="mensaje-modal" class="col-md-12 text-center">\n\
                                <h3>' + mensaje + '</h3>\n\
                            </div>\n\
                      </div>';
    evento.mostrarModal(titulo, html);
    $('#btnModalConfirmar').addClass('hidden');
    $('#btnModalAbortar').removeClass('hidden');
    $('#btnModalAbortar').empty().append('Cerrar');
    $('#btnModalAbortar').on('click', function () {
        if (recargarPagina === false) {
            location.reload();
        } else {
            _this.cerrarModal();
        }
    });
};

//Evento mostrar formulario servicio sin especificar
Servicio.prototype.ServicioSinClasificar = function () {
    var _this = this;
    var formulario = arguments[0];
    var resumenSeguimiento = arguments[1];
    var seccion = arguments[2];
    var servicio = arguments[3];
    var datosDelServicio = arguments[4];
    var nombreControlador = arguments[5];
    var archivo = arguments[6];
    var panel = arguments[7];
    var ticket = arguments[8];
    var avanceServicio = arguments[9] || null;
    var idSucursal = arguments[10];
    var datosSD = arguments[11];
    var tipoServicio = arguments[12] || '';
    var idPerfil = arguments[13] || '';
    var informacionServicioGeneral = arguments[14] || null;

    var dataServicio = {servicio: servicio, ticket: ticket};

    $(resumenSeguimiento).addClass('hidden');
    $(seccion).removeClass('hidden').empty().append(formulario);

    _this.select.crearSelect('select');
    _this.select.cambiarOpcion('#selectSucursalesSinClasificar', idSucursal);
    _this.colocarBotonGuardarCambiosSinClasificar(datosDelServicio, archivo);


    //evento para mostrar los detalles de las descripciones
    $('#detallesServicioSinClasificar').on('click', function (e) {
        if ($('#masDetalles').hasClass('hidden')) {
            $('#masDetalles').removeClass('hidden');
            $('#detallesServicioSinClasificar').empty().html('<a>- Detalles</a>');
        } else {
            $('#masDetalles').addClass('hidden');
            $('#detallesServicioSinClasificar').empty().html('<a>+ Detalles</a>');
        }
    });

    //Evento que vuelve a mostrar la lista de los servicios
    $('#btnRegresarSeguimientoSinEspecificar').on('click', function () {
        location.reload();
    });

    //Encargado de crear un nuevo servicio
    $('#btnNuevoServicioSinEspecificar').on('click', function () {
        _this.nuevoServicio(
                dataServicio,
                datosDelServicio.Ticket,
                datosDelServicio.IdSolicitud,
                nombreControlador + '/Servicio_Nuevo_Modal',
                '#seccion-servicio-sin-clasificar',
                nombreControlador + '/Servicio_Nuevo'
                );
    });

    //Encargado de cancelar el servicio
    $('#btnCancelarServicioSinEspecificar').on('click', function () {
        _this.cancelarServicio(
                dataServicio,
                nombreControlador + '/Servicio_Cancelar_Modal',
                '#seccion-datos-logistica',
                nombreControlador + '/Servicio_Cancelar'
                );
    });

    //Evento para concluir el servicio
    $("#btnConcluirServicioSinClasificar").off("click");
    $('#btnConcluirServicioSinClasificar').on('click', function (e) {
        var sucursal = $('#selectSucursalesSinClasificar').val();
        var descripcion = $('#inputDescripcionSinClasificar').val();
        var evidencias = $('#evidenciaSinClasificar').val();
        var archivosPreview = _this.file.previews('.previewSinClasificar');
        if (descripcion !== '') {
            if (idPerfil !== '83') {
                var data = {ticket: ticket, servicio: servicio, descripcion: descripcion, previews: archivosPreview, evidencias: evidencias, sucursal: sucursal, datosConcluir: {servicio: servicio, descripcion: descripcion, sucursal: sucursal}, correo: '', operacion: '9'};
                _this.modalConfirmacionFirma(ticket, data);
                $('#btnNoFirma').on('click', function () {
                    $('#btnSiFirma').attr('disabled', 'disabled');
                    $('#btnNoFirma').attr('disabled', 'disabled');
                    _this.file.enviarArchivos('#evidenciaSinClasificar', '/Generales/Servicio/Concluir_SinClasificar', '#modal-dialogo', data, function (respuesta) {
                        if (respuesta === true) {
                            _this.mensajeModal('Se Concluyó correctamente el servicio', 'Correcto');
                        } else {
                            _this.mensajeModal('Ocurrió el error "' + respuesta + '" Por favor contacte al administrador del Sistema AdIST.', 'Error');
                        }
                    });
                });
            } else {
                var data = {servicio: servicio, descripcion: descripcion, previews: archivosPreview, evidencias: evidencias, sucursal: sucursal, datosConcluir: {servicio: servicio, descripcion: descripcion, sucursal: sucursal}};
//                _this.enviarEvento('/Generales/Servicio/VerificarFolioServicio', data, panel, function (respuesta) {
//                    if (respuesta === true) {
                _this.validarTecnicoPoliza();

                var html = '';

                $('#btnModalConfirmar').addClass('hidden');
                $('#btnModalConfirmar').off('click');
                _this.mostrarModal('Firma', _this.modalCampoFirmaExtra(html, 'Firma'));
                _this.validarCamposFirma(ticket, servicio, true, true, '5', data);
//                    } else {
//                        _this.mensajeModal('No cuenta con Folio este servicio.', 'Advertencia', true);
//                    }
//                });
            }
        } else {
            _this.mostrarMensaje('.errorGeneralServicioSinClasificar', false, 'Debes llenar el campo Descripción.', 3000);
        }
    });

    $('#selectSucursalesSinClasificar').on('change', function (event, data) {
        $('#selectAreaPuntoSinClasificar').empty().append('<option data-punto="" value="">Seleccionar</option>');
        _this.select.cambiarOpcion('#selectAreaPuntoSinClasificar', '');
        if ($('#selectSucursalesSinClasificar').val() !== '') {
            $('#selectAreaPuntoSinClasificar').removeAttr('disabled');
            var sucursal = $('#selectSucursalesSinClasificar').val();
            var dataSucursal = {sucursal: sucursal};
            _this.enviarEvento('/Poliza/Seguimiento/ConsultaAreaPuntoXSucursal', dataSucursal, '#seccion-servicio-sin-clasificar', function (respuesta) {
                if (respuesta !== false) {
                    $.each(respuesta, function (key, valor) {
                        $("#selectAreaPuntoSinClasificar").append('<option value="' + valor.IdArea + '-' + valor.Punto + '">' + valor.Area + ' ' + valor.Punto + '</option>');
                    });

                    if (informacionServicioGeneral !== null) {
                        if (informacionServicioGeneral[0].IdModelo !== '0') {
                            $('#selectAreaPuntoSinClasificar > option[value="' + informacionServicioGeneral[0].IdArea + '-' + informacionServicioGeneral[0].Punto + '"]').attr('selected', 'selected', 'selected', 'selected').trigger('change');
                        }
                    }
                } else {
                    if (informacionServicioGeneral !== null) {
                        if (informacionServicioGeneral[0].IdModelo !== '0') {
                            _this.mostrarMensaje('.errorGeneralServicioSinClasificar', false, 'No hay equipos reguistrados para el Area y Punto.', 5000);
                        }
                        $('#selectAreaPuntoSinClasificar').attr('disabled', 'disabled');
                    }
                }
            });
        } else {
            $('#selectAreaPuntoSinClasificar').attr('disabled', 'disabled');
            $('#selectEquipoSinClasificar').attr('disabled', 'disabled');
        }
    });

    _this.select.cambiarOpcion('#selectSucursalesSinClasificar', idSucursal);

    $('#selectAreaPuntoSinClasificar').on('change', function (event, data) {
        $('#selectEquipoSinClasificar').empty().append('<option data-serie="" data-terminal="" value="">Seleccionar</option>');
        _this.select.cambiarOpcion('#selectEquipoSinClasificar', '');

        if ($('#selectAreaPuntoSinClasificar').val() !== '') {
            $('#selectEquipoSinClasificar').removeAttr('disabled');
            var sucursal = $('#selectSucursalesSinClasificar').val();
            var areaPunto = $('#selectAreaPuntoSinClasificar').val();
            var aux = areaPunto.split("-");
            var punto = aux[1];
            var area = aux[0];

            if (area !== '') {
                var dataEquipo = {sucursal: sucursal, area: area, punto: punto};
                _this.enviarEvento('/Poliza/Seguimiento/ConsultaEquipoXAreaPuntoUltimoCenso', dataEquipo, '#seccion-servicio-sin-clasificar', function (respuesta) {
                    var nuevoArrayIdModelos = [];
                    $.each(respuesta, function (key, valor) {
                        $("#selectEquipoSinClasificar").append('<option data-serie="' + valor.Serie + '" data-terminal="' + valor.Extra + '" value=' + valor.IdModelo + '>' + valor.Equipo + ' (' + valor.Serie + ')</option>');
                        nuevoArrayIdModelos.push(valor.IdModelo);
                    });
                    if (informacionServicioGeneral[0].IdModelo !== '0') {
                        _this.select.cambiarOpcion('#selectEquipoSinClasificar', informacionServicioGeneral[0].IdModelo);
                    }
                });
            }
        } else {
            $('#selectEquipoSinClasificar').attr('disabled', 'disabled');
        }
    });

    $("#btnGuardarServicioSinClasificar").off("click");
    $('#btnGuardarServicioSinClasificar').on('click', function (e) {
        var sucursal = $('#selectSucursalesSinClasificar').val();
        var descripcion = $('#inputDescripcionSinClasificar').val();
        var evidencias = $('#evidenciaSinClasificar').val();
        var archivosPreview = _this.file.previews('.previewSinClasificar');
        var areaPunto = $('#selectAreaPuntoSinClasificar').val();
        var equipo = $('#selectEquipoSinClasificar').val();
        var numeroRenglon = areaPunto.search(/-/i);
        var punto = areaPunto.substr(2, 2);
        var area = areaPunto.substr(0, numeroRenglon);
        punto = Math.abs(punto);

        if (descripcion !== '') {
            var data = {ticket: ticket, servicio: servicio, descripcion: descripcion, previews: archivosPreview, evidencias: evidencias, sucursal: sucursal, datosConcluir: {servicio: servicio, descripcion: descripcion, sucursal: sucursal}, soloGuardar: true, equipo: equipo, area: area, punto: punto};
            _this.file.enviarArchivos('#evidenciaSinClasificar', '/Generales/Servicio/Concluir_SinClasificar', '#seccion-servicio-sin-clasificar', data, function (respuesta) {
                if (respuesta === true) {
                    _this.mostrarMensaje('.errorGeneralServicioSinClasificar', true, 'Datos guardados correctamente.', 3000);
                } else {
                    _this.mostrarMensaje('.errorGeneralServicioSinClasificar', false, 'Ocurrió el error "' + respuesta + '" Por favor contacte al administrador del Sistema AdIST.', 3000);
                }
            });
        } else {
            _this.mostrarMensaje('.errorGeneralServicioSinClasificar', false, 'Debes llenar el campo Descripción.', 3000);
        }
    });

    $("#btnGuardarCambiosServicioSinClasificar").off("click");
    $('#btnGuardarCambiosServicioSinClasificar').on('click', function (e) {
        var sucursal = $('#selectSucursalesSinClasificar').val();
        var descripcion = $('#inputDescripcionSinClasificar').val();
        var evidencias = $('#evidenciaSinClasificar').val();
        var archivosPreview = _this.file.previews('.previewSinClasificar');
        var data = {ticket: ticket, servicio: servicio, descripcion: descripcion, previews: archivosPreview, evidencias: evidencias, sucursal: sucursal, datosConcluir: {servicio: servicio, descripcion: descripcion, sucursal: sucursal}, correo: '', operacion: '9', seccion: '#seccion-servicio-sin-clasificar'};

        _this.servicioValidacion(data);
    });

    $("#btnGeneraPdfServicio").off("click");
    $("#btnGeneraPdfServicio").on("click", function () {
        _this.enviarEvento('/Servicio/Servicio_ToPdf', dataServicio, '#seccion-servicio-sin-clasificar', function (respuesta) {
            window.open(respuesta.link);
        });
    });

    $("#btnDocumentacionFirmaSinEspecificar").off("click");
    $("#btnDocumentacionFirmaSinEspecificar").on("click", function () {
        _this.modalCampoFirma(null, {servicio: servicio, operacion: '1', estatus: datosDelServicio.IdEstatus, sucursal: idSucursal}, '/Generales/Servicio/GuardarDocumentacionFirma');
    });

    //Encargado de agregar un problema
    $('#btnReasignarServicio').on('click', function () {
        _this.mostrarFormularioReasigarServicio(servicio, ticket);
    });

    _this.botonAgregarAvance(servicio, tipoServicio);
    _this.botonAgregarProblema(servicio, tipoServicio);
    _this.botonAgregarVuelta(dataServicio, '#seccion-servicio-sin-clasificar');
    _this.GuardarNotas(dataServicio, nombreControlador);
    _this.initBotonNuevaSolicitud(ticket);

    if (avanceServicio !== null) {
        $.each(avanceServicio, function (key, item) {
            _this.tabla.generaTablaPersonal('#data-table-avance-servicio-' + item.Id);
        });
    }

    _this.eventosFolio(datosDelServicio.IdSolicitud, '#seccion-servicio-sin-clasificar', servicio);
    _this.botonEliminarAvanceProblema(servicio);
    _this.botonEditarAvanceProblema(servicio);
};

Servicio.prototype.colocarBotonGuardarCambiosSinClasificar = function () {
    var datosServicio = arguments[0];
    var archivo = arguments[1];
    var _this = this;

    if (datosServicio.Firma !== null && datosServicio.Firma !== '') {
        $('.divBotonesServicioSinClasificar').addClass('hidden');
        $('.divGuardarCambiosServicioSinClasificar').removeClass('hidden');
        _this.file.crearUpload('#evidenciaCambiosSinClasificar',
                '/Generales/Servicio/ServicioEnValidacion',
                null,
                null,
                archivo,
                '/Generales/Servicio/EliminarEvidenciaServicio',
                );
    } else {
        _this.file.crearUpload('#evidenciaSinClasificar',
                '/Generales/Servicio/Concluir_SinClasificar',
                null,
                null,
                archivo,
                '/Generales/Servicio/EliminarEvidenciaServicio',
                );
    }
};

Servicio.prototype.botonAgregarAvance = function () {
    var _this = this;
    var servicio = arguments[0];
    var tipoServicio = arguments[1];

    $('#btnAgregarAvance').on('click', function () {
        _this.mostrarFormularioAvanceServicio(servicio, '1', tipoServicio, 'Guardar');
    });
};

Servicio.prototype.botonAgregarProblema = function () {
    var _this = this;
    var servicio = arguments[0];
    var tipoServicio = arguments[1];

    $('#btnAgregarProblema').on('click', function () {
        _this.mostrarFormularioAvanceServicio(servicio, '2', tipoServicio, 'Guardar');
    });
};

Servicio.prototype.GuardarNotas = function () {
    var _this = this;
    var dataServicio = arguments[0];
    var nombreControlador = arguments[1];

    $("#divNotasServicio").slimScroll({height: '400px'});
    _this.nota.initButtons(dataServicio, nombreControlador);
};

Servicio.prototype.eventosFolio = function () {
    var _this = this;
    var solicitud = arguments[0];
    var seccion = arguments[1];
    var servicio = arguments[2];

    $('#detallesFolio').off('click');
    $('#detallesFolio').on('click', function (e) {
        $('#seccionSD').empty().html();
        $('#seccionSD').addClass('hidden');
        if ($('#masDetallesFolio').hasClass('hidden')) {
            $('#masDetallesFolio').removeClass('hidden');
            $('#detallesFolio').empty().html('<i class="fa fa-arrow-circle-up"></i> Folio</li>');
            $('#cargando').removeClass('hidden');
            _this.enviarEvento('/Generales/ServiceDesk/DatosSD', {solicitud: solicitud}, '', function (respuesta) {
                $('#cargando').addClass('hidden');
                var datosSD = respuesta;
                if (datosSD !== null) {
                    if (datosSD !== undefined) {
                        $('#seccionSD').removeClass('hidden');
                        var datosSDHTML = _this.camposSD(datosSD);
                        $('#seccionSD').empty().html(datosSDHTML);
                        _this.detallesDescripcionResolucion();
                    }
                } else {
                    var mensajeSinDatos = 'No hay información para mostrar con este folio en Service Desk.';
                    $('#seccionSD').empty().html(mensajeSinDatos);
                    $('#seccionSD').removeClass('hidden');
                }
            });
        } else {
            $('#masDetallesFolio').addClass('hidden');
            $('#detallesFolio').empty().html('<i class="fa fa-arrow-circle-down"></i> Folio</li>');
        }
    });

    $('#btnGuardarFolioServicioSinClasificar').off('click');
    $('#btnGuardarFolioServicioSinClasificar').on('click', function () {
        var folio = $('#inputFolioServicioSinClasificar').val();
        var dataFolio = {folio: folio, solicitud: solicitud};

        if (folio !== '') {
            if (folio >= 250000) {
                if (_this.validarFormulario('#formFolioSinClasificar')) {
                    _this.enviarEvento('/Generales/Solicitud/editarFolio', dataFolio, seccion, function (respuesta) {
                        $('#seccionSD').removeClass('hidden');
                        if (respuesta !== null) {
                            $('#folioSeguimiento').empty().html(' Folio <a  TITLE="Muestra la informacion de Service Desk">' + folio + '</a>');
                            $('#tituloFolio').empty().append('Cuenta con Folio');
                            $('#btnGuardarFolioServicioSinClasificar').addClass('hidden');
                            $('#btnActualizarFolioServicioSinClasificar').removeClass('hidden');
                            $('#btnEliminarFolioServicioSinClasificar').removeClass('hidden');
                            _this.mostrarMensaje('.errorFolioSolicitudSinClasificar', true, 'Folio guardado correctamente.', 3000);
                            var datosSDHTML = _this.camposSD(respuesta);
                            $('#seccionSD').empty().html(datosSDHTML);
                            _this.detallesDescripcionResolucion();
                        } else {
                            $('#btnGuardarFolioServicioSinClasificar').addClass('hidden');
                            $('#btnActualizarFolioServicioSinClasificar').removeClass('hidden');
                            $('#btnEliminarFolioServicioSinClasificar').removeClass('hidden');
                            _this.mostrarMensaje('.errorFolioSolicitudSinClasificar', true, 'Folio guardado correctamente.', 3000);
                            var mensajeSinDatos = _this.mensajeAlerta('No hay información para mostrar con este folio en Service Desk.')
                            $('#seccionSD').empty().html(mensajeSinDatos);
                        }
                    });
                }
            } else {
                _this.mostrarMensaje('.errorFolioSolicitudSinClasificar', false, 'El numero de folio debe ser mayor a 250,000.', 3000);
            }
        } else {
            _this.mostrarMensaje('.errorFolioSolicitudSinClasificar', false, 'Debe llenar el campo Folio.', 3000);
        }
    });

    $('#btnEliminarFolioServicioSinClasificar').off('click');
    $('#btnEliminarFolioServicioSinClasificar').on('click', function () {
        var dataFolio = {folio: '', solicitud: solicitud};
        var html = '<div class="row">\n\
                            <div id="mensaje-modal" class="col-md-12 text-center">\n\
                                <h3>¿Realmente quiere eliminar el Folio?</h3>\n\
                            </div>\n\
                      </div>';
        html += '<div class="row m-t-20">\n\
                                <div class="col-md-12 text-center">\n\
                                    <button id="btnAceptarEliminarFolio" type="button" class="btn btn-sm btn-success"><i class="fa fa-check"></i> Aceptar</button>\n\
                                    <button id="btnCancelarEliminarFolio" type="button" class="btn btn-sm btn-danger"><i class="fa fa-times"></i> Cancelar</button>\n\
                                </div>\n\
                            </div>';
        $('#btnModalConfirmar').addClass('hidden');
        $('#btnModalAbortar').addClass('hidden');
        _this.mostrarModal('Advertencia', html);
        $('#btnModalConfirmar').empty().append('Eliminar');
        $('#btnModalConfirmar').off('click');

        $('#btnAceptarEliminarFolio').off('click');
        $('#btnAceptarEliminarFolio').on('click', function () {
            _this.cerrarModal();
            _this.enviarEvento('/Generales/Solicitud/editarFolio', dataFolio, seccion, function (respuesta) {
                if (respuesta === null) {
                    $('#folioSeguimiento').empty().html('');
                    $('#tituloFolio').empty().append('Sin Folio');
                    $('#btnGuardarFolioServicioSinClasificar').removeClass('hidden');
                    $('#btnActualizarFolioServicioSinClasificar').addClass('hidden');
                    $('#btnEliminarFolioServicioSinClasificar').addClass('hidden');
                    $('#inputFolioServicioSinClasificar').attr('placeholder', '')
                    $('#inputFolioServicioSinClasificar').val('')
                    $('#seccionSD').empty();
                    $('#seccionSD').addClass('hidden');
                    _this.mostrarMensaje('.errorFolioSolicitudSinClasificar', true, 'Folio eliminado correctamente.', 3000);
                } else {
                    _this.mostrarMensaje('.errorFolioSolicitudSinClasificar', false, 'Vuelva a intentarlo.', 3000);
                }
            });
        });

        $('#btnCancelarEliminarFolio').on('click', function () {
            _this.cerrarModal();
        });
    });

    $('#btnActualizarFolioServicioSinClasificar').off('click');
    $('#btnActualizarFolioServicioSinClasificar').on('click', function () {
        var folio = $('#inputFolioServicioSinClasificar').val();
        var dataFolio = {folio: folio, solicitud: solicitud};

        if (folio !== '') {
            if (folio >= 250000) {
                if (_this.validarFormulario('#formFolioSinClasificar')) {
                    _this.enviarEvento('/Generales/Solicitud/editarFolio', dataFolio, seccion, function (respuesta) {
                        $('#seccionSD').removeClass('hidden');
                        if (respuesta !== null) {
                            $('#folioSeguimiento').empty().html(' Folio <a  TITLE="Muestra la informacion de Service Desk">' + folio + '</a>');
                            _this.mostrarMensaje('.errorFolioSolicitudSinClasificar', true, 'Datos actualizados correctamente.', 3000);
                            var datosSDHTML = _this.camposSD(respuesta);
                            $('#seccionSD').empty().html(datosSDHTML);
                            _this.detallesDescripcionResolucion();
                        } else {
                            _this.mostrarMensaje('.errorFolioSolicitudSinClasificar', true, 'Datos actualizados correctamente.', 3000);
                            var mensajeSinDatos = _this.mensajeAlerta('No hay información para mostrar con este folio en Service Desk.')
                            $('#seccionSD').empty().html(mensajeSinDatos);
                        }
                    });
                }
            } else {
                _this.mostrarMensaje('.errorFolioSolicitudSinClasificar', false, 'El numero de folio debe ser mayor a 250,000.', 3000);
            }
        } else {
            _this.mostrarMensaje('.errorFolioSolicitudSinClasificar', false, 'Debe llenar el campo Folio.', 3000);
        }
    });

    $('#btnReasignarFolioServicioSinClasificar').off('click');
    $('#btnReasignarFolioServicioSinClasificar').on('click', function () {
        _this.enviarEvento('/Generales/ServiceDesk/CatalogoUsuariosSD', {}, seccion, function (respuesta) {
            var html = '<form class="margin-bottom-0" id="formReasignarSD" data-parsley-validate="true">\n\
                            <div class="row" m-t-10">\n\
                                <div class="col-md-8 col-md-offset-2 col-xs-8 col-xs-offset-2">\n\
                                    <div class="form-group">\n\
                                        <label for="usuarioSD">Asignar en SD a *</label>\n\
                                        <select id="usuarioSD" class="form-control" style="width: 100%" data-parsley-required="true">\n\
                                           <option value="">Seleccionar</option>\n\
                                        </select>\n\
                                    </div>\n\
                                </div>\n\
                            </div>\n\
                        </form>';
            _this.mostrarModal('Reasignar SD', html);
            $.each(respuesta, function (key, valor) {
                $("#usuarioSD").append('<option value=' + valor.TECHNICIANID + '>' + valor.TECHNICIANNAME + '</option>');
            });

            _this.select.crearSelect('#usuarioSD');

            $('#btnModalConfirmar').off('click');
            $('#btnModalConfirmar').on('click', function () {
                if (_this.validarFormulario('#formReasignarSD')) {
                    var usuarioSD = $("#usuarioSD").val();
                    var data = {servicio: servicio, personalSD: usuarioSD, solicitud: solicitud};
                    _this.enviarEvento('/Generales/Solicitud/ReasignarFolioSD', data, '#modal-dialogo', function (respuesta) {
                        _this.cerrarModal();
                        if (respuesta.code === 200) {
                            _this.mostrarMensaje('.errorFolioSolicitudSinClasificar', true, 'Datos actualizados correctamente.', 3000);
                            var datosSDHTML = _this.camposSD(respuesta.message);
                            $('#seccionSD').empty().html(datosSDHTML);
                            _this.detallesDescripcionResolucion();
                        } else {
                            _this.mostrarMensaje('.errorFolioSolicitudSinClasificar', false, respuesta.message, 3000);
                            var mensajeSinDatos = _this.mensajeAlerta(respuesta.message)
                            $('#seccionSD').empty().html(mensajeSinDatos);
                        }
                    });
                }
            });
        });
    });
    $('#btnConcluirReasignarFolioServicioSinClasificar').off('click');
    $('#btnConcluirReasignarFolioServicioSinClasificar').on('click', function () {
        _this.enviarEvento('/Generales/ServiceDesk/CatalogoUsuariosSD', {}, seccion, function (respuesta) {
            var html = '<form class="margin-bottom-0" id="formReasignarSD" data-parsley-validate="true">\n\
                            <div class="row" m-t-10">\n\
                                <div class="col-md-8 col-md-offset-2 col-xs-8 col-xs-offset-2">\n\
                                    <div class="form-group">\n\
                                        <label for="usuarioSD">Asignar en SD a *</label>\n\
                                        <select id="usuarioSD" class="form-control" style="width: 100%" data-parsley-required="true">\n\
                                           <option value="">Seleccionar</option>\n\
                                        </select>\n\
                                    </div>\n\
                                </div>\n\
                            </div>\n\
                            <div class="row" m-t-10">\n\
                                <div class="col-md-8 col-md-offset-2 col-xs-8 col-xs-offset-2">\n\
                                    <div class="errorGuardarFactura"></div>\n\
                                </div>\n\
                            </div>\n\
                        </form>';
            _this.mostrarModal('Reasignar SD', html);
            $.each(respuesta, function (key, valor) {
                $("#usuarioSD").append('<option value=' + valor.TECHNICIANID + '>' + valor.TECHNICIANNAME + '</option>');
            });

            _this.select.crearSelect('#usuarioSD');

            $('#btnModalConfirmar').off('click');
            $('#btnModalConfirmar').on('click', function () {
                var sucursal = $('#selectSucursalesSinClasificar').val();
                var descripcion = $('#inputDescripcionSinClasificar').val();
                var evidencias = $('#evidenciaSinClasificar').val();
                var archivosPreview = _this.file.previews('.previewSinClasificar');
                var perfil = $('#nombreAtiende').attr('att-IdPerfil');
                if (_this.validarFormulario('#formReasignarSD') && descripcion !== '') {
                    var usuarioSD = $("#usuarioSD").val();
                    var data = {servicio: servicio, personalSD: usuarioSD, solicitud: solicitud, descripcion: descripcion, previews: archivosPreview, evidencias: evidencias, perfil: perfil, datosConcluir: {servicio: servicio, descripcion: descripcion, sucursal: sucursal}};
                    _this.enviarEvento('/Generales/Solicitud/ReasignarFolioSD', data, '#modal-dialogo', function (respuesta) {
                        _this.cerrarModal();
                        if (respuesta.code === 200) {
                            _this.mostrarMensaje('.errorFolioSolicitudSinClasificar', true, 'Datos actualizados correctamente.', 3000);
                            var datosSDHTML = _this.camposSD(respuesta.message);
                            $('#seccionSD').empty().html(datosSDHTML);
                            _this.detallesDescripcionResolucion();
                        } else {
                            _this.mostrarMensaje('.errorFolioSolicitudSinClasificar', false, respuesta.message, 3000);
                            var mensajeSinDatos = _this.mensajeAlerta(respuesta.message)
                            $('#seccionSD').empty().html(mensajeSinDatos);
                        }
                    });
                } else {
                    _this.mostrarMensaje('.errorGuardarFactura', false, 'Revisa los campos faltantes.', 3000);
                }
            });
        });
    });
};

Servicio.prototype.camposSD = function () {
    var datosSD = arguments[0];

    if (datosSD === 'El sistema de ServiceDesk no se encuentra disponible por el momento.') {
        var html = '<div class="row">\n\
                        <div class="row">\n\
                            <div class="col-sm-12 col-md-12">'
                + datosSD +
                '           </div>\n\
                        </div>\n\
                    </div>';
    } else {
        if (datosSD.code === 400) {
            var html = '<div class="row">\n\
                        <div class="row">\n\
                            <div class="col-sm-12 col-md-12">'
                    + datosSD.message +
                    '           </div>\n\
                        </div>\n\
                    </div>';
        } else {
            var html = '<div class="row">\n\
                        <div class="col-sm-4 col-md-4">\n\
                            <div class="form-group">\n\
                                <label> Creado por: <strong>' + datosSD.creadoSD + '</strong></label>\n\
                            </div>\n\
                        </div>\n\
                        <div class="col-sm-4 col-md-4">\n\
                            <div class="form-group">\n\
                                <label> Fecha de Creación: <strong>' + datosSD.fechaSolicitudSD + '</strong></label>\n\
                            </div>\n\
                        </div>\n\
                        <div class="col-sm-4 col-md-4">\n\
                            <div class="form-group text-right">\n\
                                <label> Prioridad: <strong>' + datosSD.prioridadSD + '</strong></label>\n\
                            </div>\n\
                        </div>\n\
                    </div>\n\
                    <div class="row">\n\
                        <div class="col-sm-4 col-md-4">\n\
                            <div class="form-group">\n\
                                <label> Solicita: <strong>' + datosSD.solicitaSD + '</strong></label>\n\
                            </div>\n\
                        </div>\n\
                        <div class="col-sm-4 col-md-4">\n\
                            <div class="form-group">\n\
                                <label> Asignado a: <strong>' + datosSD.asignadoSD + '</strong></label>\n\
                            </div>\n\
                        </div>\n\
                        <div class="col-sm-4 col-md-4">\n\
                            <div class="form-group text-right">\n\
                                <label> Estatus: <strong>' + datosSD.estatusSD + '</strong></label>\n\
                            </div>\n\
                        </div>\n\
                    </div>\n\
                    <div class="row">\n\
                        <div class="col-md-12">\n\
                            <div class="underline m-b-15 m-t-15"></div>\n\
                        </div>\n\
                    </div>\n\
                    <div class="row">\n\
                        <div class="col-sm-12 col-md-12">\n\
                            <div class="form-group">\n\
                                <label> Asunto: <strong>' + datosSD.asuntoSD + '</strong></label>\n\
                            </div>\n\
                        </div>\n\
                    </div>\n\
                    <div class="row">\n\
                        <div class="col-sm-12 col-md-12">\n\
                            <div class="form-group">\n\
                                <label> Descripción:</label>\n\
                                <br>\n\
                                <strong>' + datosSD.descripcionSD + '</strong>\n\
                            </div>\n\
                        </div>\n\
                    </div>';

            if (datosSD.notasSD !== 'Sin notas') {
                html += '<div class="row">\n\
                        <div class="col-md-12">\n\
                            <div class="underline m-b-15 m-t-15"></div>\n\
                        </div>\n\
                    </div>\n\
                    <div class="row">\n\
                        <div class="col-md-offset-9 col-md-3">\n\
                            <div class="form-group text-right">\n\
                                <h5><a><strong id="detallesResolucion"><i class="fa fa-minus-square"></i> Notas</strong></a></h5>\n\
                            </div>\n\
                        </div>\n\
                    </div>\n\
                    <div id="masDetallesResolucion" class="">\n\
                        <div class="row">\n\
                            <div class="col-md-12">';
                $.each(datosSD.notasSD, function (key, value) {
                    var collapseTitulo = '';
                    var collapseTexto = '';
                    if (key === 0) {
                        collapseTitulo = '';
                        collapseTexto = 'collapse in';
                    } else {
                        collapseTitulo = 'collapse';
                        collapseTexto = 'collapse';
                    }

                    html += '       <div class="panel panel-inverse overflow-hidden">\n\
                                    <div class="panel-heading">\n\
                                        <h3 class="panel-title">\n\
                                            <a class="accordion-toggle accordion-toggle-styled ' + collapseTitulo + '" data-toggle="collapse" data-parent="#accordion" href="#collapse' + key + '">\n\
                                                <i class="fa fa-plus-circle pull-right"></i>\n\
                                                ' + value.nombreUsuario + '  ' + value.fecha + '\n\
                                            </a>\n\
                                        </h3>\n\
                                    </div>\n\
                                    <div id="collapse' + key + '" class="panel-collapse ' + collapseTexto + ' ">\n\
                                        <div class="panel-body">\n\
                                            ' + value.texto + '\n\
                                        </div>\n\
                                    </div>\n\
                                </div>';
                });
                html += '       </div>';
            }
            html += '       </div>\n\
                    </div>';
        }
    }

    return html;
};

Servicio.prototype.detallesDescripcionResolucion = function () {
    $('#detallesResolucion').on('click', function (e) {
        if ($('#masDetallesResolucion').hasClass('hidden') === false) {
            $('#masDetallesResolucion').addClass('hidden');
            $('#detallesResolucion').empty().html('<i class="fa fa-plus-square"></i> Notas</li>');
        } else {
            $('#masDetallesResolucion').removeClass('hidden');
            $('#detallesResolucion').empty().html('<i class="fa fa-minus-square"></i> Notas</li>');
        }
    });
};

Servicio.prototype.mensajeAlerta = function () {
    var mensaje = arguments[0];
    var html = '<div class="col-md-12 m-t-20">\n\
                    <div class="alert alert-warning fade in m-b-15">\n\
                        ' + mensaje + '\n\
                    </div>\n\
                </div>';
    return html;
};

Servicio.prototype.initBotonNuevaSolicitud = function () {
    var _this = this;
    var ticket = arguments[0];

    //Encargado de mostrar el modal para generar una nueva solicitud
    $("#btnNuevaSolicitud").off("click");
    $("#btnNuevaSolicitud").on("click", function () {
        _this.nuevaSolicitud(ticket);
    });
};

Servicio.prototype.initBotonReasignarServicio = function () {
    var _this = this;
    var servicio = arguments[0];
    var ticket = arguments[1];
    var seccionCarga = arguments[2];

    $("#btnReasignarServicio").off("click");
    $("#btnReasignarServicio").on("click", function () {
        _this.mostrarFormularioReasigarServicio(servicio, ticket, seccionCarga);
    });
};

Servicio.prototype.initDocumentacionFirma = function () {
    var _this = this;
    var servicio = arguments[0];
    var estatus = arguments[1];
    var sucursal = arguments[2];

    //Encargado de mostrar el modal para generar una nueva solicitud
    $("#btnDocumentacionFirma").off("click");
    $("#btnDocumentacionFirma").on("click", function () {
        _this.modalCampoFirma(null, {servicio: servicio, operacion: '1', estatus: estatus, sucursal: sucursal}, '/Generales/Servicio/GuardarDocumentacionFirma')
    });
};

Servicio.prototype.modalConfirmacionFirma = function () {
    var _this = this;
    var ticket = arguments[0];
    var data = arguments[1];

    var html = '<div id="canfirmacionFirma">\n\
                    <div class="panel-body">\n\
                        <div class="row">\n\
                            <div class="col-md-12 col-xs-12 text-center">\n\
                                <h2>¿Quieres colocar Firma?</h2>\n\
                            </div>\n\
                        </div>';
    html += '<div class="row m-t-20">\n\
                                <div class="col-md-12 text-center">\n\
                                    <button id="btnSiFirma" type="button" class="btn btn-sm btn-success"><i class="fa fa-check"></i> Si</button>\n\
                                    <button id="btnNoFirma" type="button" class="btn btn-sm btn-danger"><i class="fa fa-times"></i> No</button>\n\
                                </div>\n\
                        </div>\n\
                    </div>\n\
                </div>';

    $('#btnModalConfirmar').addClass('hidden');
    $('#btnModalAbortar').addClass('hidden');
    _this.mostrarModal('Firma', html);

    $("#btnSiFirma").off("click");
    $('#btnSiFirma').on('click', function () {
        _this.validarTecnicoPoliza();
        _this.modalCampoFirma(ticket, data);
    });
};

Servicio.prototype.modalCampoFirma = function () {
    var _this = this;
    var ticket = arguments[0] || null;
    var data = arguments[1];
    var controladorEventoExtra = arguments[2] || null;
    var idCorrectivoDiagnostico = arguments[3] || null;
    var estatus = arguments[4] || null;
    var textoBoton = ' Guardar y Concluir';
    $('#btnModalAbortar').removeClass('hidden');

    if (data.operacion === '1') {
        textoBoton = ' Enviar';
    }

    var html = ' <div id="campo_firma">\n\
                        <form class="margin-bottom-0" id="formFirmaSinClasificar" data-parsley-validate="true">\n\
                            <div class="row m-t-10">\n\
                                <div class="col-md-8 col-md-offset-2 col-xs-8 col-xs-offset-2">\n\
                                    <div class="form-group">\n\
                                        <label for="catalogoActualizarSucursales">Recibe *</label>\n\
                                        <input type="text" class="form-control" id="inputRecibeFirma" placeholder="Nombre de la persona que Recibe." style="width: 100%" data-parsley-required="true"/>\n\
                                    </div>\n\
                                </div>\n\
                            </div>\n\
                        </form>\n\
                        <div id="divCampoCorreo" class="row">\n\
                            <div class="col-md-8 col-md-offset-2 col-xs-8 col-xs-offset-2">\n\
                                <div class="form-group">\n\
                                    <label>Correo(s) *</label>\n\
                                    <ul id="tagValor" class="inverse"></ul>\n\
                                </div>\n\
                            </div>\n\
                        </div>\n\
                        <div class="row">\n\
                                <div id="col-md-12 text-center">\n\
                                    <div id="campoLapiz"></div>\n\
                                </div>\n\
                        </div>';
    html += '           <div class="row m-t-20">\n\
                            <div class="col-md-12 text-center">\n\
                                <br>\n\
                                <label><input type="checkbox" id="terminos" value="first_checkbox"> He leído y acepto los <a href="">Términos de Uso</a> y <a href="">Declaración de Privacidad</a> para SICCOB</label><br>\n\
                            </div>\n\
                        </div>';
    html += '           <div class="row m-t-10">\n\
                            <div class="col-md-12">\n\
                                <div class="errorFirma"></div>\n\
                            </div>\n\
                        </div>\n\
                        <div class="row m-t-20">\n\
                            <div class="col-md-12 text-center">\n\
                                <button id="btnGuardarConcluir" type="button" class="btn btn-sm btn-primary"><i class="fa fa-save"></i>' + textoBoton + '</button>\n\
                            </div>\n\
                        </div>\n\
                </div>';

    _this.mostrarModal('Firma', html);

    var myBoard = _this.campoLapiz('campoLapiz');

    $('#btnModalConfirmar').addClass('hidden');
    $('#btnModalConfirmar').off('click');

    $('#btnGuardarConcluir').off('click');
    $('#btnGuardarConcluir').on('click', function () {
        var img = myBoard.getImg();
        var imgInput = (myBoard.blankCanvas == img) ? '' : img;

        if (_this.validarFormulario('#formFirmaSinClasificar')) {
            var recibe = $('#inputRecibeFirma').val();
            var correo = $("#tagValor").tagit("assignedTags");

            if (correo.length > 0) {
                if (_this.validarCorreoArray(correo)) {
                    if (imgInput !== '') {
                        if ($('#terminos').attr('checked')) {
                            if (data.operacion === '3') {
                                $('#btnGuardarConcluir').attr('disabled', 'disabled');
                                $('#btnModalAbortar').attr('disabled', 'disabled');
                                var dataMandar = {ticket: ticket, img: img, datosConcluir: data, recibe: recibe, correo: correo, servicio: data.servicio, estatus: estatus, sucursal: data.sucursal};
                                _this.enviarEvento('/Generales/Servicio/Concluir_SinClasificar', dataMandar, '#modal-dialogo', function (respuesta) {
                                    if (respuesta === true) {
                                        $('#modal-dialogo').modal('hide');
                                        location.reload();
//                                        _this.mensajeModal('Se Concluyó correctamente el servicio', 'Correcto');
                                    } else {
                                        $('#modal-dialogo').modal('hide');
                                        _this.mensajeModal('Ocurrió el error "' + respuesta + '" Por favor contacte al administrador del Sistema AdIST.', 'Error');
                                    }
                                });
                            } else if (data.operacion === '1') {
                                $('#btnGuardarConcluir').attr('disabled', 'disabled');
                                $('#btnModalAbortar').attr('disabled', 'disabled');
                                var dataMandar = {ticket: ticket, img: img, datosConcluir: data, recibe: recibe, correo: correo, servicio: data.servicio, idCorrectivoDiagnostico: idCorrectivoDiagnostico, sucursal: data.sucursal};
                                _this.enviarEvento(controladorEventoExtra, dataMandar, '#modal-dialogo', function (respuesta) {
                                    if (respuesta === true) {
                                        $('#modal-dialogo').modal('hide');
                                        location.reload();
//                                        _this.mensajeModal('Se envio el reporte correctamente', 'Correcto', true);
                                    } else if (respuesta instanceof Array || respuesta instanceof Object) {
                                        _this.tabla.limpiarTabla('#data-table-documetacion-firmada');
                                        var columnas = _this.datosTablaDocumentacionFirmada();
                                        _this.tabla.generaTablaPersonal('#data-table-documetacion-firmada', respuesta, columnas, null, null, [[0, 'desc']]);
                                        $('#modal-dialogo').modal('hide');
                                        location.reload();
//                                        _this.mensajeModal('Se envio el reporte correctamente', 'Correcto', true);
                                    } else {
                                        $('#modal-dialogo').modal('hide');
                                        _this.mensajeModal('Ocurrió el error "' + respuesta + '" Por favor contacte al administrador del Sistema AdIST.', 'Error', true);
                                    }
                                });
                            } else {
                                $('#btnGuardarConcluir').attr('disabled', 'disabled');
                                $('#btnModalAbortar').attr('disabled', 'disabled');
                                var dataMandar = {ticket: ticket, img: img, datosConcluir: data.descripcion, recibe: recibe, correo: correo, servicio: data.servicio, operacion: '9', sucursal: data.sucursal};
                                _this.file.enviarArchivos('#evidenciaSinClasificar', '/Generales/Servicio/Concluir_SinClasificar', '#modal-dialogo', dataMandar, function (respuesta) {
                                    if (respuesta.result === true) {
                                        $('#modal-dialogo').modal('hide');
                                        location.reload();
//                                        _this.mensajeModal('Se Concluyó correctamente el servicio', 'Correcto');
                                    } else if (respuesta === true) {
                                        $('#modal-dialogo').modal('hide');
                                        location.reload();
//                                        _this.mensajeModal('Se Concluyó correctamente el servicio', 'Correcto');
                                    } else {
                                        $('#modal-dialogo').modal('hide');
                                        _this.mensajeModal('Ocurrió el error "' + respuesta + '" Por favor contacte al administrador del Sistema AdIST.', 'Error');
                                    }
                                });
                            }
                            myBoard.clearWebStorage();
                        } else {
                            _this.mostrarMensaje('.errorFirma', false, 'Debes aceptar los Terminos y Declaración de Privacidad.', 4000);
                        }
                    } else {
                        _this.mostrarMensaje('.errorFirma', false, 'Debes llenar el campo Firma.', 3000);
                    }
                } else {
                    _this.mostrarMensaje('.errorFirma', false, 'Algun Correo no es correcto.', 3000);
                }
            } else {
                if (imgInput !== '') {
                    if ($('#terminos').attr('checked')) {
                        if (data.operacion === '3') {
                            $('#btnGuardarConcluir').attr('disabled', 'disabled');
                            $('#btnModalAbortar').attr('disabled', 'disabled');
                            var dataMandar = {ticket: ticket, img: img, datosConcluir: data, recibe: recibe, correo: correo, servicio: data.servicio, estatus: estatus, sucursal: data.sucursal};
                            _this.enviarEvento('/Generales/Servicio/Concluir_SinClasificar', dataMandar, '#modal-dialogo', function (respuesta) {
                                if (respuesta === true) {
                                    $('#modal-dialogo').modal('hide');
                                    location.reload();
//                                    _this.mensajeModal('Se Concluyó correctamente el servicio', 'Correcto');
                                } else {
                                    $('#modal-dialogo').modal('hide');
                                    _this.mensajeModal('Ocurrió el error "' + respuesta + '" Por favor contacte al administrador del Sistema AdIST.', 'Error');
                                }
                            });
                        } else if (data.operacion === '1') {
                            $('#btnGuardarConcluir').attr('disabled', 'disabled');
                            $('#btnModalAbortar').attr('disabled', 'disabled');
                            var dataMandar = {ticket: ticket, img: img, datosConcluir: data, recibe: recibe, correo: correo, servicio: data.servicio, idCorrectivoDiagnostico: idCorrectivoDiagnostico, sucursal: data.sucursal};
                            _this.enviarEvento(controladorEventoExtra, dataMandar, '#modal-dialogo', function (respuesta) {
                                if (respuesta === true) {
                                    $('#modal-dialogo').modal('hide');
                                    _this.mensajeModal('Se envio el reporte correctamente', 'Correcto', true);
                                    location.reload();
                                } else if (respuesta instanceof Array || respuesta instanceof Object) {
                                    _this.tabla.limpiarTabla('#data-table-documetacion-firmada');
                                    var columnas = _this.datosTablaDocumentacionFirmada();
                                    _this.tabla.generaTablaPersonal('#data-table-documetacion-firmada', respuesta, columnas, null, null, [[0, 'desc']]);
                                    $('#modal-dialogo').modal('hide');
                                    location.reload();
//                                    _this.mensajeModal('Se envio el reporte correctamente', 'Correcto', true);
                                } else {
                                    $('#modal-dialogo').modal('hide');
                                    _this.mensajeModal('Ocurrió el error "' + respuesta + '" Por favor contacte al administrador del Sistema AdIST.', 'Error', true);
                                }
                            });
                        } else {
                            $('#btnGuardarConcluir').attr('disabled', 'disabled');
                            $('#btnModalAbortar').attr('disabled', 'disabled');
                            var dataMandar = {ticket: ticket, img: img, datosConcluir: data.descripcion, recibe: recibe, correo: correo, servicio: data.servicio, operacion: '9', sucursal: data.sucursal};
                            _this.file.enviarArchivos('#evidenciaSinClasificar', '/Generales/Servicio/Concluir_SinClasificar', '#modal-dialogo', dataMandar, function (respuesta) {
                                if (respuesta.result === true) {
                                    $('#modal-dialogo').modal('hide');
                                    location.reload();
//                                    _this.mensajeModal('Se Concluyó correctamente el servicio', 'Correcto');
                                } else if (respuesta === true) {
                                    $('#modal-dialogo').modal('hide');
                                    location.reload();
//                                    _this.mensajeModal('Se Concluyó correctamente el servicio', 'Correcto');
                                } else {
                                    $('#modal-dialogo').modal('hide');
                                    _this.mensajeModal('Ocurrió el error "' + respuesta + '" Por favor contacte al administrador del Sistema AdIST.', 'Error');
                                }
                            });
                        }
                        myBoard.clearWebStorage();
                    } else {
                        _this.mostrarMensaje('.errorFirma', false, 'Debes aceptar los Terminos y Declaración de Privacidad.', 4000);
                    }
                } else {
                    _this.mostrarMensaje('.errorFirma', false, 'Debes llenar el campo Firma.', 3000);
                }
            }
        }
    });
};

Servicio.prototype.modalCampoFirmaExtra = function () {
    var textoExtra = arguments[0];

    $('#btnModalAbortar').removeClass('hidden');

    var html = ' <div id="campo_firma_extra">\n\
                    <div class="panel-body">';
    html += '           <form class="margin-bottom-0" id="formFirmaExtra" data-parsley-validate="true">\n\
                            <div class="row m-t-10">\n\
                                <div class="col-md-8 col-md-offset-2 col-xs-8 col-xs-offset-2">\n\
                                    <div class="form-group">\n\
                                        <label for="inputRecibeFirma">Gerente en turno o TI*</label>\n\
                                        <input type="text" class="form-control" id="inputRecibeFirma" placeholder="Nombre de la persona que Recibe." style="width: 100%" data-parsley-required="true"/>\n\
                                    </div>\n\
                                </div>\n\
                            </div>\n\
                            <div class="row m-t-10"">\n\
                                <div id="col-md-12 text-center">\n\
                                    <div id="campoLapiz"></div>\n\
                                </div>\n\
                            </div>\n\
                            <div class="row m-t-20">\n\
                                <div class="col-md-12 text-center">\n\
                                    <br>\n\
                                    <label>Firma de conformidad del Gerente o TI</label><br>\n\
                                </div>\n\
                            </div>';
    html += textoExtra;
    html += '               <div id="divCampoCorreo" class="row m-t-10">\n\
                                <div class="col-md-8 col-md-offset-2 col-xs-8 col-xs-offset-2">\n\
                                    <div class="form-group">\n\
                                        <label id="campoCorreo">Correo(s) *</label>\n\
                                        <ul id="tagValor" class="inverse"></ul>\n\
                                    </div>\n\
                                </div>\n\
                            </div>';
    html += '               <div class="row m-t-20">\n\
                                <div class="col-md-12 text-center">\n\
                                    <br>\n\
                                    <label><input type="checkbox" id="terminos" value="first_checkbox"> He leído y acepto los <a href="">Términos de Uso</a> y <a href="">Declaración de Privacidad</a> para SICCOB</label><br>\n\
                                </div>\n\
                            </div>';
    html += '               <div class="row m-t-10">\n\
                                <div class="col-md-12">\n\
                                    <div class="errorFirma"></div>\n\
                                </div>\n\
                            </div>\n\
                            <div class="row m-t-20">\n\
                                <div class="col-md-12 text-center">\n\
                                    <button id="btnGuardarFirma" type="button" class="btn btn-sm btn-primary"><i class="fa fa-save"></i> Guardar</button>\n\
                                </div>\n\
                            </div>\n\
                        </form>\n\
                    </div>\n\
                </div>';
    return html;

};

Servicio.prototype.formConcluirServicio = function () {
    var html = ' <div id="modal-concluir-servicio">\n\
                    <div class="panel-body">\n\
                        <form class="margin-bottom-0" id="formConcluirServicioFirma" data-parsley-validate="true">';

    html += '<div class="row m-t-10">\n\
                                    <div class="col-md-8 col-md-offset-2 col-xs-8 col-xs-offset-2">\n\
                                        <div class="form-group">\n\
                                            <label for="nombre-personal">Nombre personal que Recibe *</label>\n\
                                            <input type="text" class="form-control" id="inputPersonaRecibe" placeholder="Nombre de la persona que Recibe." style="width: 100%" data-parsley-required="true"/>\n\
                                        </div>\n\
                                    </div>\n\
                                </div>';
    html += '<div class="row m-t-10">\n\
                                    <div class="col-md-8 col-md-offset-2 col-xs-8 col-xs-offset-2">\n\
                                        <div class="form-group">\n\
                                            <label id="inputCorreo">Correo(s) *</label>\n\
                                            <ul id="tagValor" class="inverse"></ul>\n\
                                        </div>\n\
                                    </div>\n\
                                </div>';
    html += '<div class="row m-t-10">\n\
                                    <div class="col-md-12 text-center">\n\
                                        <div id="campoFirma"></div>\n\
                                    </div>\n\
                                </div>\n\
                                <div class="row m-t-20">\n\
                                    <div class="col-md-12 text-center">\n\
                                        <br>\n\
                                        <label>Firma de conformidad del Gerente o TI *</label><br>\n\
                                    </div>\n\
                                </div>';
    html += '               <div class="row m-t-20">\n\
                                        <div class="col-md-12 text-center">\n\
                                            <br>\n\
                                            <label><input type="checkbox" id="terminos" value="first_checkbox"> He leído y acepto los <a href="">Términos de Uso</a> y <a href="">Declaración de Privacidad</a> para SICCOB</label><br>\n\
                                        </div>\n\
                                    </div>';
    html += '           <div class="row m-t-10">\n\
                                    <div class="col-md-12">\n\
                                        <div class="errorConcluirServicio"></div>\n\
                                    </div>\n\
                                </div>\n\
                                <div class="row m-t-20">\n\
                                    <div class="col-md-12 text-center">\n\
                                        <button id="btnConcluirServicio" type="button" class="btn btn-sm btn-primary"><i class="fa fa-save"></i> Concluir Servicio</button>\n\
                                    </div>\n\
                                </div>';

    html += '       </form>\n\
                            </div>\n\
                         </div>';
    return html;
};

Servicio.prototype.validarCamposFirma = function () {
    var _this = this;
    var evento = new Base();
    var ticket = arguments[0];
    var servicio = arguments[1];
    var campoFirmaTecnico = arguments[2] || false;
    var concluirServicio = arguments[3] || false;
    var estatus = arguments[4] || false;
    var evidencias = arguments[5] || false;

    var myBoard = _this.campoLapiz('campoLapiz');

    //si es verdadero se creara el campo de la firma del tecnico
//    if (campoFirmaTecnico) {
//        var myBoardTecnico = _this.campoLapiz('campoLapizTecnico');
//
//    }

    $('#btnGuardarFirma').off('click');
    $('#btnGuardarFirma').on('click', function () {
        var encargadoTI = $('#selectTI').val();
        if (evento.validarFormulario('#formFirmaExtra')) {
            var imgFirma = myBoard.getImg();
            var imgInputFirma = (myBoard.blankCanvas == imgFirma) ? '' : imgFirma;
            var correo = $("#tagValor").tagit("assignedTags");
            var recibe = $('#inputRecibeFirma').val();

            if ($('#terminos').attr('checked')) {
                if (correo.length > 0) {
                    if (_this.validarCorreoArray(correo)) {
                        if (imgInputFirma !== '') {
//                            if (campoFirmaTecnico) {
////                                var imgFirmaTecnico = myBoardTecnico.getImg();
////                                var imgInputFirmaTecnico = (myBoardTecnico.blankCanvas == imgFirmaTecnico) ? '' : imgFirmaTecnico;
//
                            if (encargadoTI === undefined) {
                                encargadoTI = null
                            }

//                                var dataNuevo = {ticket: ticket, servicio: servicio, img: imgFirma, imgFirmaTecnico: imgFirmaTecnico, correo: correo, recibe: recibe, encargadoTI: encargadoTI, concluirServicio: concluirServicio, estatus: estatus};
//                                if (imgInputFirmaTecnico !== '') {
//                                    _this.enviarEvento('/Generales/Servicio/Enviar_Reporte_PDF', dataNuevo, '#modal-dialogo', function (respuesta) {
//                                        if (respuesta === true) {
//                                            if (evidencias != false) {
//                                                _this.enviarEvidenciaAsociado(evidencias);
//                                            } else {
//                                                _this.mensajeModal('Se envió el reporte correctamente', 'Correcto');
//                                            }
//                                        } else {
//                                            _this.mensajeModal('Ocurrió el error "' + respuesta + '" Por favor contacte al administrador del Sistema AdIST.', 'Error');
//                                        }
//                                        myBoard.clearWebStorage();
////                                        myBoardTecnico.clearWebStorage();
//                                    });
//                                } else {
//                                    evento.mostrarMensaje('.errorFirma', false, 'Debes llenar el campo Firma del Tecnico.', 3000);
//                                }
//                            } else {
                            var imgFirmaTecnico = null;
//                                encargadoTI = null;
                            var dataNuevo = {ticket: ticket, servicio: servicio, img: imgFirma, imgFirmaTecnico: imgFirmaTecnico, correo: correo, recibe: recibe, encargadoTI: encargadoTI, concluirServicio: concluirServicio, estatus: estatus};
                            _this.enviarEvento('/Generales/Servicio/Enviar_Reporte_PDF', dataNuevo, '#modal-dialogo', function (respuesta) {
                                if (respuesta === true) {
                                    if (evidencias != false) {
                                        _this.enviarEvidenciaAsociado(evidencias);
                                    } else {
                                        _this.mensajeModal('Se envió el reporte correctamente', 'Correcto');
                                    }
                                } else {
                                    _this.mensajeModal('Ocurrió el error "' + respuesta + '" Por favor contacte al administrador del Sistema AdIST.', 'Error');
                                }
                                myBoard.clearWebStorage();
//                                    myBoardTecnico.clearWebStorage();
                            });
//                            }
                        } else {
                            evento.mostrarMensaje('.errorFirma', false, 'Debes llenar el campo Firma de quien Recibe.', 3000);
                        }
                    } else {
                        evento.mostrarMensaje('.errorFirma', false, 'Algun Correo no es correcto.', 3000);
                    }
                } else {
                    if (imgInputFirma !== '') {
//                        if (campoFirmaTecnico) {
//                            var imgFirmaTecnico = myBoardTecnico.getImg();
//                            var imgInputFirmaTecnico = (myBoardTecnico.blankCanvas == imgFirmaTecnico) ? '' : imgFirmaTecnico;

                        if (encargadoTI === undefined) {
                            encargadoTI = null
                        }

//                            var dataNuevo = {ticket: ticket, servicio: servicio, img: imgFirma, imgFirmaTecnico: imgFirmaTecnico, correo: correo, recibe: recibe, encargadoTI: encargadoTI, concluirServicio: concluirServicio, estatus: estatus};
//                            if (imgInputFirmaTecnico !== '') {
//                                _this.enviarEvento('/Generales/Servicio/Enviar_Reporte_PDF', dataNuevo, '#modal-dialogo', function (respuesta) {
//                                    if (respuesta === true) {
//                                        if (evidencias != false) {
//                                            _this.enviarEvidenciaAsociado(evidencias);
//                                        } else {
//                                            _this.mensajeModal('Se envió el reporte correctamente', 'Correcto');
//                                        }
//                                    } else {
//                                        _this.mensajeModal('Ocurrió el error "' + respuesta + '" Por favor contacte al administrador del Sistema AdIST.', 'Error');
//                                    }
//                                    myBoard.clearWebStorage();
////                                    myBoardTecnico.clearWebStorage();
//                                });
//                            } else {
//                                evento.mostrarMensaje('.errorFirma', false, 'Debes llenar el campo Firma del Tecnico.', 3000);
//                            }
//                        } else {
                        var imgFirmaTecnico = null;
//                            encargadoTI = null;
                        var dataNuevo = {ticket: ticket, servicio: servicio, img: imgFirma, imgFirmaTecnico: imgFirmaTecnico, correo: correo, recibe: recibe, encargadoTI: encargadoTI, concluirServicio: concluirServicio, estatus: estatus};
                        _this.enviarEvento('/Generales/Servicio/Enviar_Reporte_PDF', dataNuevo, '#modal-dialogo', function (respuesta) {
                            if (respuesta === true) {
                                if (evidencias != false) {
                                    _this.enviarEvidenciaAsociado(evidencias);
                                } else {
                                    _this.mensajeModal('Se envió el reporte correctamente', 'Correcto');
                                }
                            } else {
                                _this.mensajeModal('Ocurrió el error "' + respuesta + '" Por favor contacte al administrador del Sistema AdIST.', 'Error');
                            }
                            myBoard.clearWebStorage();
//                                myBoardTecnico.clearWebStorage();
                        });
//                        }
                    } else {
                        evento.mostrarMensaje('.errorFirma', false, 'Debes llenar el campo Firma de quien Recibe.', 3000);
                    }
                }
            } else {
                evento.mostrarMensaje('.errorFirma', false, 'Debes aceptar los Terminos y Declaración de Privacidad.', 4000);
            }
        }
    });
};

Servicio.prototype.enviarEvidenciaAsociado = function (evidencias) {
    var _this = this;
    _this.file.enviarArchivos('#evidenciaSinClasificar', '/Generales/Servicio/Concluir_SinClasificar', '#modal-dialogo', evidencias, function (respuesta) {
        if (respuesta === true) {
            _this.mensajeModal('Se envió la información correctamente', 'Correcto');
        } else {
            _this.mensajeModal('Ocurrió el error "' + respuesta + '" Por favor contacte al administrador del Sistema AdIST.', 'Error');
        }
    });
}

Servicio.prototype.validarCorreoArray = function (correo) {
    var resultado;
    var expre = new RegExp(/^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/);
    if (correo.length !== 0) {
        $.each(correo, function (key, value) {
            if (expre.test(value)) {
                resultado = true;
            } else {
                resultado = false;
            }
        });
    } else {
        resultado = true;
    }
    return resultado;
};

Servicio.prototype.mostrarFormularioAvanceServicio = function () {
    var _this = this;
    var servicio = arguments[0];
    var tipoAvanceProblema = arguments[1];
    var tipoServicio = arguments[2] || '';
    var tipoFormulario = arguments[3] || 'Guardar';
    var informacionAvanceProblema = arguments[4] || null;
    var data = {servicio: servicio, tipoAvanceProblema: tipoAvanceProblema};
    var titulo = '';
    var tituloEquipoMaterial = '';
    var nombreCampo = '';
    var verficarCampoArchivo = false;
    var idAvanceProblema = '';
    var archivosAvanceProblema = '';

    if (tipoAvanceProblema === '1') {
        titulo = 'Agregar Avance';
        tituloEquipoMaterial = 'Equipo o Material Utilizado';
        $('#TituloEquipoMaterial').empty().html('Equipo o Material Utilizado');
    } else if (tipoAvanceProblema === '2') {
        titulo = 'Agregar Problema'
        tituloEquipoMaterial = 'Equipo y Material Necesario';
    }

    _this.enviarEvento('/Generales/Servicio/MostrarFormularioAvanceServicio', data, '#seccion-servicio-sin-clasificar', function (respuesta) {
        _this.mostrarModal(titulo, respuesta.formulario);

        $("#modal-dialogo #content").prop('style', 'margin-left:0px !important;');
        $('#tituloEquipoMaterial').empty().html(tituloEquipoMaterial);

        _this.select.crearSelect('#selectAvanceRefaccionEquipo');
        _this.select.crearSelect('#selectTipoFalla');
        _this.select.crearSelect('select');
        _this.tabla.generaTablaPersonal('#data-table-avances');

        if (tipoFormulario !== 'Guardar') {
            _this.colocarInformacionFormularioAvanceProblema(informacionAvanceProblema);
        }
        if (informacionAvanceProblema !== null) {
            idAvanceProblema = informacionAvanceProblema.avanceProblema[0].Id
            archivosAvanceProblema = informacionAvanceProblema.archivo
        }

        _this.file.crearUpload('#archivosAvanceServicio',
                '/Generales/Servicio/GuardarAvenceServicio',
                null,
                null,
                archivosAvanceProblema,
                '/Generales/Servicio/EliminarEvidenciaAvanceProblema',
                idAvanceProblema,
                false,
                false,
                false,
                false,
                false,
                false,
                false,
                null,
                false);

        if (tipoAvanceProblema === '2') {
            $('#divArchivos').empty().html('Archivos *');
            $('#divTipoFalla').removeClass('hidden');
        }

        $('#selectUtilizado').on('change', function () {
            var utilizar = $('#selectUtilizado option:selected').attr('value');
            switch (utilizar) {
                case '1':
                    $('#seleccionEquipo').removeClass('hidden');
                    $('#seleccionMaterial').addClass('hidden');
                    $('#seleccionRefaccion').addClass('hidden');
                    $('#seleccionSalas4XD').addClass('hidden');
                    if (tipoAvanceProblema === '2') {
                        $('#inputSerieEquipo').addClass('hidden');
                        $('#inputCantidadEquipo').removeClass('hidden');
                        if (tipoServicio === 'Checklist') {
                            $('#inputAvanceCantidadEquipo').attr('disabled', 'disabled');
                            $('#inputAvanceCantidadEquipo').val(1);
                        }
                    } else {
                        $('#inputCantidadEquipo').addClass('hidden');
                        $('#inputSerieEquipo').removeClass('hidden');
                    }
                    nombreCampo = 'equipo';
                    break;
                case '2':
                    $('#seleccionEquipo').addClass('hidden');
                    $('#seleccionMaterial').removeClass('hidden');
                    $('#seleccionRefaccion').addClass('hidden');
                    $('#seleccionSalas4XD').addClass('hidden');
                    nombreCampo = 'material';
                    break;
                case '3':
                    $('#seleccionEquipo').addClass('hidden');
                    $('#seleccionMaterial').addClass('hidden');
                    $('#seleccionRefaccion').removeClass('hidden');
                    $('#seleccionSalas4XD').addClass('hidden');
                    nombreCampo = 'refaccion';
                    break;
                case '4':
                    $('#seleccionEquipo').addClass('hidden');
                    $('#seleccionMaterial').addClass('hidden');
                    $('#seleccionRefaccion').addClass('hidden');
                    $('#seleccionSalas4XD').removeClass('hidden');
                    nombreCampo = 'salas4XD';
                    break;
                default:
                    $('#seleccionEquipo').addClass('hidden');
                    $('#seleccionMaterial').addClass('hidden');
                    $('#seleccionRefaccion').addClass('hidden');
                    $('#seleccionSalas4XD').addClass('hidden');
            }
        });

        $('#selectAvanceRefaccionEquipo').on('change', function () {
            var objetoNuevoComponentesEquipo = {};
            $.each(respuesta.datos.componentesEquipo, function (key, valor) {
                objetoNuevoComponentesEquipo[key] = {Id: valor.IdCom, Nombre: valor.Componente, IdMod: valor.IdMod};
            });
            _this.select.setOpcionesSelect('#selectAvanceRefaccion', objetoNuevoComponentesEquipo, $('#selectAvanceRefaccionEquipo').val(), 'IdMod');
            if ($('#selectAvanceRefaccionEquipo').val() !== '') {
                $('#selectAvanceRefaccion').removeAttr('disabled');
            } else {
                $('#selectAvanceRefaccion').attr('disabled', 'disabled');
            }
        });

        $('#selectAvanceElemento').on('change', function () {
            var objetoNuevoSubelementos = {};
            $.each(respuesta.datos.subelementos, function (key, valor) {
                objetoNuevoSubelementos[key] = {Id: valor.Id, Nombre: valor.Nombre + ' - ' + valor.Marca, IdElemento: valor.IdElemento};
            });

            _this.select.setOpcionesSelect('#selectAvanceSubelemento', objetoNuevoSubelementos, $('#selectAvanceElemento').val(), 'IdElemento');
            if ($('#selectAvanceElemento').val() !== '') {
                $('#selectAvanceSubelemento').removeAttr('disabled');
            } else {
                $('#selectAvanceSubelemento').attr('disabled', 'disabled');
            }
        });

        $('#data-table-avances tbody').on('click', 'tr', function () {
            var datosTablaAvances = $('#data-table-avances').DataTable().rows(this).data();
            if (datosTablaAvances.length > 0) {
                _this.tabla.eliminarFila('#data-table-avances', this);
            }
        });

        $('#btnModalConfirmar').off('click');
        $('#btnModalConfirmar').on('click', function () {
            var descripcion = $('#inputDescripcionAvanceServicio').val();
            var datosTablaAvanceServicios = $('#data-table-avances').DataTable().rows().data();
            var verificarArchivos = false;
            var archivos = $('#archivosAvanceServicio').val();

            if (archivos !== '') {
                verificarArchivos = true;
            }
            if (tipoAvanceProblema === '2') {
                if (archivos !== '') {
                    verficarCampoArchivo = false;
                } else if (archivosAvanceProblema !== '') {
                    verficarCampoArchivo = false;
                } else {
                    verficarCampoArchivo = true;
                }
            }

            if (verficarCampoArchivo === false) {
                if (descripcion !== '') {
                    var datosTabla = [];

                    for (var i = 0; i < datosTablaAvanceServicios.length; i++) {
                        datosTablaAvanceServicios[i][1] = datosTablaAvanceServicios[i][1].replace(",", " ");
                        datosTabla.push(datosTablaAvanceServicios[i]);
                    }
                    if (datosTabla.length <= 0) {
                        datosTabla.push('sinDatos');
                    }

                    var data = {datosTabla: datosTabla, servicio: servicio, tipoAvanceProblema: tipoAvanceProblema, descripcion: descripcion, verificarArchivos: verificarArchivos, tipoOperacion: tipoFormulario, idAvanceProblema: idAvanceProblema};
                    _this.file.enviarArchivos('#archivosAvanceServicio', '/Generales/Servicio/GuardarAvenceServicio', '#seccion-avance-servicio', data, function (respuesta) {
                        if (respuesta.code === 200) {
                            $('#Historial').empty().append(respuesta.message);
                            _this.botonEliminarAvanceProblema(servicio);
                            _this.botonEditarAvanceProblema(servicio);
                            _this.mensajeModal('Se agrego a la sección de Historial', 'Correcto', true);
                        } else {
                            _this.mensajeModal(respuesta.message, 'Error', false);
                        }
                    });
                } else {
                    _this.mostrarMensaje('.errorAvenceServicio', false, 'Debes llenar el campo de Descripción.', 3000);
                }
            } else {
                _this.mostrarMensaje('.errorAvenceServicio', false, 'Debes llenar el campo de Archivos.', 3000);
            }
        });

        $('#btnAgregarAvenceServicio').off('click');
        $('#btnAgregarAvenceServicio').on('click', function () {
            _this.verificarCamposGeneralesAvanceServicio(nombreCampo, tipoAvanceProblema, tipoServicio);
        });

        $('#btnSalirAvanceServicio').off('click');
        $('#btnSalirAvanceServicio').on('click', function () {
            _this.cerrarModal();
        });
    });
};

Servicio.prototype.verificarCamposGeneralesAvanceServicio = function () {
    var _this = this;
    var nombreCampo = arguments[0];
    var tipoAvanceProblema = arguments[1];
    var tipoServicio = arguments[2] || '';
    var selectUtilizado = $('#selectUtilizado').val();
    var tipoFalla = $('#selectTipoFalla').val();

    if (selectUtilizado !== '') {
        switch (nombreCampo) {
            case 'equipo':
                var serieEquipo = '';
                var cantidad = '1';
                var selectEquipo = $('#selectAvanceEquipo').val();
                var nombreEquipo = $('#selectAvanceEquipo option:selected').text();
                if (selectEquipo !== '') {
                    if (tipoAvanceProblema === '1') {
                        serieEquipo = $('#inputAvanceSerieEquipo').val();
                    } else {
                        if (tipoServicio === 'Checklist') {
                            serieEquipo = $('#selectAvanceEquipo option:selected').attr('data-serie')
                        }
                        cantidad = $('#inputAvanceCantidadEquipo').val();
                    }
                    if (cantidad === '') {
                        cantidad = '1';
                    }
                    var data = {tipoItem: '1', descripcion: nombreEquipo, item: selectEquipo, serie: serieEquipo, cantidad: cantidad, tipoFalla: tipoFalla};
                    _this.agregandoTablaAvanceServicio(data);
                } else {
                    _this.mostrarMensaje('.errorAvenceServicio', false, 'Debes Selecionar un Equipo.', 3000)
                }
                break;
            case 'material':
                var selectMaterial = $('#selectAvanceMaterial').val();
                var cantidadMaterial = $('#inputAvanceCantidadMaterial').val();
                var nombreMaterial = $('#selectAvanceMaterial option:selected').text();
                if (selectMaterial !== '') {
                    if (cantidadMaterial > 0) {
                        var data = {tipoItem: '2', descripcion: nombreMaterial, item: selectMaterial, serie: '', cantidad: cantidadMaterial, tipoFalla: tipoFalla};
                        _this.agregandoTablaAvanceServicio(data);
                    } else {
                        _this.mostrarMensaje('.errorAvenceServicio', false, 'Debes llenar el campo de Cantidad con un número positivo.', 3000)
                    }
                } else {
                    _this.mostrarMensaje('.errorAvenceServicio', false, 'Debes Selecionar un Material.', 3000)
                }
                break;
            case 'refaccion':
                var selectEquipo = $('#selectAvanceRefaccionEquipo').val();
                var selectRefaccion = $('#selectAvanceRefaccion').val();
                var cantidadRefaccion = $('#inputAvanceCantidadRefaccion').val();
                var nombreRefaccion = $('#selectAvanceRefaccion option:selected').text();
                if (selectEquipo !== '') {
                    if (selectRefaccion !== '') {
                        if (cantidadRefaccion > 0) {
                            var data = {tipoItem: '3', descripcion: nombreRefaccion, item: selectRefaccion, serie: '', cantidad: cantidadRefaccion, tipoFalla: tipoFalla};
                            _this.agregandoTablaAvanceServicio(data);
                        } else {
                            _this.mostrarMensaje('.errorAvenceServicio', false, 'Debes llenar el campo de Cantidad con un número positivo.', 3000)
                        }
                    } else {
                        _this.mostrarMensaje('.errorAvenceServicio', false, 'Debes Selecionar una Refacción.', 3000)
                    }
                } else {
                    _this.mostrarMensaje('.errorAvenceServicio', false, 'Debes Selecionar un Equipo.', 3000)
                }
                break;
            case 'salas4XD':
                var selectElemento = $('#selectAvanceElemento').val();
                var selectSubelemento = $('#selectAvanceSubelemento').val();
                var cantidadElementoSubelemento = $('#inputAvanceCantidadElementoSubelemento').val();
                var nombreElemento = $('#selectAvanceElemento option:selected').text();
                var nombreSubelemento = $('#selectAvanceSubelemento option:selected').text();
                if (selectElemento !== '') {
                    if (cantidadElementoSubelemento > 0) {
                        if (selectSubelemento !== '') {
                            var data = {tipoItem: '5', descripcion: nombreSubelemento, item: selectSubelemento, serie: '', cantidad: cantidadElementoSubelemento, tipoFalla: tipoFalla};
                        } else {
                            var data = {tipoItem: '4', descripcion: nombreElemento, item: selectElemento, serie: '', cantidad: cantidadElementoSubelemento, tipoFalla: tipoFalla};
                        }
                        _this.agregandoTablaAvanceServicio(data);
                    } else {
                        _this.mostrarMensaje('.errorAvenceServicio', false, 'Debes Selecionar una Cantidad.', 3000)
                    }
                } else {
                    _this.mostrarMensaje('.errorAvenceServicio', false, 'Debes Selecionar un Elemento.', 3000)
                }
                break;
        }
    } else {
        _this.mostrarMensaje('.errorAvenceServicio', false, 'Debes Selecionar un Equipo o Material.', 3000)
    }
};

Servicio.prototype.agregandoTablaAvanceServicio = function () {
    var _this = this;
    var datos = arguments[0];
    var tipoItem = '';
    var tipoFalla = '';

    switch (datos.tipoItem) {
        case '1':
            tipoItem = 'Equipo';
            break;
        case '2':
            tipoItem = 'Material';
            break;
        case '3':
            tipoItem = 'Refacción';
            break;
        case '4':
            tipoItem = 'Elemento';
            break;
        case '5':
            tipoItem = 'Sub-Elemento';
            break;
        default:
    }

    switch (datos.tipoFalla) {
        case '2':
            tipoFalla = 'Impericia';
            break;
        case '3':
            tipoFalla = 'Falla de Equipo';
            break;
        case '4':
            tipoFalla = 'Falla de Componente';
            break;
        case '5':
            tipoFalla = 'Servicio a Multimedia';
            break;
        case '7':
            tipoFalla = 'Falta de Equipo';
            break;
        case '12':
            tipoFalla = 'Desgaste';
            break;
        default:
    }
    var filas = [];

    filas.push([tipoItem, datos.descripcion, datos.serie, datos.cantidad, datos.item, datos.tipoItem, tipoFalla, datos.tipoFalla]);

    $.each(filas, function (key, value) {
        _this.tabla.agregarFila('#data-table-avances', value);
        _this.select.cambiarOpcion('#selectUtilizado', '');
        _this.select.cambiarOpcion('#selectAvanceEquipo', '');
        _this.select.cambiarOpcion('#selectAvanceMaterial', '');
        _this.select.cambiarOpcion('#selectAvanceRefaccionEquipo', '');
        _this.select.cambiarOpcion('#selectAvanceRefaccion', '');
        _this.select.cambiarOpcion('#selectTipoFalla', '');
        $('#inputAvanceSerieEquipo').val('');
        $('#inputAvanceCantidadEquipo').val('');
        $('#inputAvanceCantidadMaterial').val('');
        $('#inputAvanceCantidadRefaccion').val('');
    });
};

Servicio.prototype.mostrarFormularioReasigarServicio = function () {
    var _this = this;
    var servicio = arguments[0];
    var ticket = arguments[1];
    var seccionCarga = arguments[2] || null;

    _this.enviarEvento('/Generales/Servicio/MostrarFormularioReasignarServicio', '', seccionCarga, function (respuesta) {

        _this.mostrarModal('Reasigar Servicio', respuesta.formulario);
        _this.select.crearSelect('#selectAtiendeReasignarServicio');

        $('#btnModalConfirmar').off('click');
        $('#btnModalConfirmar').on('click', function () {
            var atiende = $('#selectAtiendeReasignarServicio').val();
            var descripcion = $('#inputDescripcionReasignarServicio').val();
            if (atiende !== '') {
                if (descripcion !== '') {
                    var data = {servicio: servicio, atiende: atiende, ticket: ticket, descripcion: descripcion};
                    _this.enviarEvento('/Generales/Servicio/cambiarAtiendeServicio', data, '#seccion-reasignar-servicio', function (respuesta) {
                        if (respuesta !== false) {
                            _this.cerrarModal();
                            location.reload();
                        } else {
                            _this.mostrarMensaje('.errorReasignarServicio', false, 'El usuario ya esta asignado a este servicio.', 3000);
                        }
                    });
                } else {
                    _this.mostrarMensaje('.errorReasignarServicio', false, 'Debes llenar el campo de Descripción.', 3000);
                }
            } else {
                _this.mostrarMensaje('.errorReasignarServicio', false, 'Debes seleccionar el campo Atiende.', 3000);
            }
        });

    });
};

Servicio.prototype.datosTablaDocumentacionFirmada = function () {
    var columnas = [
        {data: 'Fecha'},
        {data: 'Recibe'},
        {data: null,
            sClass: 'Correos',
            render: function (data, type, row, meta) {
                if (data.Correos !== null) {
                    var correos = data.Correos.replace(',', '<br/>');
                    return correos;
                } else {
                    return '';
                }
            }
        },
        {data: 'Estatus'},
        {data: null,
            sClass: 'PDF',
            render: function (data, type, row, meta) {
                return '<a href="/' + data.UrlArchivo + '" target="_blank" class="btn btn-danger btn-xs "><i class="fa fa-file-pdf-o"></i> PDF</a>';
            }
        }
    ];
    return columnas;
}

Servicio.prototype.definirImagenExtencion = function (archivo) {
    var imagen = '';
    var ext = archivo.substring(archivo.lastIndexOf("."));

    switch (ext) {
        case '.png':
        case '.jpeg':
        case '.jpg':
        case '.gif':
            imagen = archivo;
            break;
        case '.xls':
        case '.xlsx':
            imagen = '/assets/img/Iconos/excel_icon.png';
            break;
        case '.doc':
        case '.docx':
            imagen = '/assets/img/Iconos/word_icon.png';
            break;
        case '.pdf':
            imagen = '/assets/img/Iconos/pdf_icon.png';
            break;
        default :
            imagen = '/assets/img/Iconos/no-thumbnail.jpg';
            break;
    }
    return imagen;
}

Servicio.prototype.subirInformacionSD = function (servicio) {
    var _this = this;
    var servicio = arguments[0];
    var seccionCarga = arguments[1];

    $('#btnSubirInformacionSD').off('click');
    $('#btnSubirInformacionSD').on('click', function () {
        var data = {servicio: servicio, servicioConcluir: false};
        _this.enviarEvento('/Generales/ServiceDesk/GuardarInformacionSD', data, seccionCarga, function (respuesta) {
            if (respuesta.code === 200) {
                _this.mensajeModal('Se subio la información', 'Correcto', true);
            } else {
                _this.mensajeModal(respuesta.message, 'Advertencia', true);
            }
        });
    });
}

Servicio.prototype.botonAgregarVuelta = function () {
    var _this = this;
    var dataServicio = arguments[0];
    var panel = arguments[1];

    var data = {servicio: dataServicio.servicio};
    $('#btnAgregarVuelta').off('click');
    $('#btnAgregarVuelta').on('click', function () {
        _this.enviarEvento('/Generales/Servicio/VerificarVueltaAsociado', data, panel, function (respuesta) {
            if (respuesta === true) {
                var html = '<div class="row" m-t-10">\n\
                                        <div id="col-md-12 text-center">\n\
                                            <div id="campoLapizTecnico"></div>\n\
                                        </div>\n\
                                    </div>\n\
                                    <div class="row m-t-20">\n\
                                        <div class="col-md-12 text-center">\n\
                                            <br>\n\
                                            <label>Firma del técnico</label><br>\n\
                                        </div>\n\
                                    </div>\n\
                                    <br>';
                _this.mostrarModal('Firma', _this.modalCampoFirmaExtra(html, 'Firma'));

                $('#btnModalConfirmar').addClass('hidden');
                $('#btnModalConfirmar').off('click');
                $('#campoCorreo').empty().html('Correo(s)');

                var myBoard = _this.campoLapiz('campoLapiz');
                var myBoardTecnico = _this.campoLapiz('campoLapizTecnico');

                _this.validarCamposFirmaAgregarVuelta(myBoard, myBoardTecnico, dataServicio);
            } else {
                if (respuesta === 'sinSucural') {
                    _this.mensajeModal('No cuenta con sucursal guardada.', 'Advertencia', true);
                } else if (respuesta === 'noEstaProblema') {
                    _this.mensajeModal('El servicio debe estar en Problema para agregar una vuelta.', 'Advertencia', true);
                } else if (respuesta === 'yaTieneVueltas') {
                    _this.mensajeModal('No puede agregar otra vuelta a esta Folio hasta dentro de 14 horas.', 'Advertencia', true);
                } else if (respuesta === 'noHaySolucion') {
                    _this.mensajeModal('No puede agregar otra vuelta si no tiene solución el servicio.', 'Advertencia', true);
                } else if (respuesta === 'noTieneFolio') {
                    _this.mensajeModal('No cuenta con Folio este servicio.', 'Advertencia', true);
                }
            }
        });
    });
};

Servicio.prototype.botonEliminarAvanceProblema = function (servicio) {
    var _this = this;

    $('.btnEliminarAvanceSeguimientoSinEspecificar').off('click');
    $('.btnEliminarAvanceSeguimientoSinEspecificar').on('click', function () {
        var idAvanceProblema = $(this).data('id');
        var modalMensaje = _this.mensajeValidar("¿Realmente quiere eliminar la información?");
        _this.mostrarModal('Advertencia', modalMensaje);

        $('#btnAceptarConfirmacion').on('click', function () {
            var data = {
                idAvanceProblema: idAvanceProblema,
                idServicio: servicio
            };

            _this.enviarEvento('/Generales/Servicio/EliminarAvanceProblema', data, '#modal-dialogo', function (respuesta) {
                if (respuesta.code === 200) {
                    $('#Historial').empty().append(respuesta.message);
                    _this.botonEliminarAvanceProblema(servicio);
                    _this.botonEditarAvanceProblema(servicio);
                    _this.cerrarModal();
                } else {
                    _this.mensajeModal(respuesta.message, 'Error', true);
                }
            });
        });

        $('#btnCancelarConfirmacion').on('click', function () {
            _this.cerrarModal();
        });
    });
}

Servicio.prototype.botonEditarAvanceProblema = function (servicio) {
    var _this = this;
    $(".btnEditarAvanceSeguimientoSinEspecificar").off("click");
    $(".btnEditarAvanceSeguimientoSinEspecificar").on("click", function () {
        var idAvanceProblema = $(this).data('id');
        var data = {id: idAvanceProblema};
        _this.enviarEvento('/Generales/Servicio/ConsultaAvanceProblema', data, '#modal-dialogo', function (respuesta) {
            _this.mostrarFormularioAvanceServicio(servicio, respuesta.message.avanceProblema[0].IdTipo, '', 'Actualizar', respuesta.message);
        });
    });
}

Servicio.prototype.colocarInformacionFormularioAvanceProblema = function () {
    var _this = this;
    var informacionAvanceProblema = arguments[0];
    $('#inputDescripcionAvanceServicio').val(informacionAvanceProblema.avanceProblema[0].Descripcion);

    $.each(informacionAvanceProblema.serviciosAvanceEquipo, function (index, value) {
        var data = {
            tipoItem: value.IdItem,
            descripcion: value.EquipoMaterial,
            item: value.TipoItem,
            serie: value.Serie,
            cantidad: value.Cantidad,
            tipoFalla: value.TipoDiagnostico};
        _this.agregandoTablaAvanceServicio(data);
    });
}

Servicio.prototype.validarCamposFirmaAgregarVuelta = function () {
    var _this = this;
    var evento = new Base();
    var myBoard = arguments[0];
    var myBoardTecnico = arguments[1];
    var dataServicio = arguments[2];
    var servicio = dataServicio.servicio;

    $('#btnGuardarFirma').off('click');
    $('#btnGuardarFirma').on('click', function () {
        if (evento.validarFormulario('#formFirmaExtra')) {
            var imgFirma = myBoard.getImg();
            var imgInputFirma = (myBoard.blankCanvas == imgFirma) ? '' : imgFirma;
            var correo = $("#tagValor").tagit("assignedTags");
            var recibe = $('#inputRecibeFirma').val();
            if ($('#terminos').attr('checked')) {
                if (imgInputFirma !== '') {
                    var imgFirmaTecnico = myBoardTecnico.getImg();
                    var imgInputFirmaTecnico = (myBoardTecnico.blankCanvas == imgFirmaTecnico) ? '' : imgFirmaTecnico;
                    if (imgInputFirmaTecnico !== '') {
                        var data = {servicio: servicio, img: imgFirma, imgFirmaTecnico: imgFirmaTecnico, correo: correo, recibe: recibe};
                        if (correo.length > 0) {
                            if (_this.validarCorreoArray(correo)) {
                                _this.guardarVueltaAsociado(data, myBoard, myBoardTecnico);
                            } else {
                                evento.mostrarMensaje('.errorFirma', false, 'Debes llenar el campo Firma del Tecnico.', 3000);
                            }
                        } else {
                            _this.guardarVueltaAsociado(data, myBoard, myBoardTecnico);
                        }
                    } else {
                        evento.mostrarMensaje('.errorFirma', false, 'Algun Correo no es correcto.', 3000);
                    }
                } else {
                    evento.mostrarMensaje('.errorFirma', false, 'Debes llenar el campo Firma de quien Recibe.', 3000);
                }
            } else {
                evento.mostrarMensaje('.errorFirma', false, 'Debes aceptar los Terminos y Declaración de Privacidad.', 4000);
            }
        }
    });
};

Servicio.prototype.guardarVueltaAsociado = function () {
    var data = arguments[0];
    var myBoard = arguments[1];
    var myBoardTecnico = arguments[2];
    var _this = this;

    _this.enviarEvento('/Generales/Servicio/GuardarVueltaAsociado', data, '#modal-dialogo', function (respuesta) {
        if (respuesta === true) {
            _this.mensajeModal('Documento enviado.', 'Correcto', true);
        } else {
            _this.mensajeModal('Se agregó la vuelta, pero surgió el siguiente detalle: ' + respuesta.message, 'Advertencia', true);
        }
        myBoard.clearWebStorage();
        myBoardTecnico.clearWebStorage();
    });
}

Servicio.prototype.campoLapiz = function () {
    var campoLapiz = arguments[0];
    var myBoard = new DrawingBoard.Board(campoLapiz, {
        background: "#fff",
        color: "#000",
        size: 1,
        controlsPosition: "right",
        controls: [
            {Navigation: {
                    back: false,
                    forward: false
                }
            }
        ],
        webStorage: false
    });
    $("#tagValor").tagit({
        allowSpaces: false
    });
    myBoard.ev.trigger('board:reset', 'what', 'up');
    return myBoard;
}

Servicio.prototype.validarTecnicoPoliza = function () {
    var _this = this;
    _this.enviarEvento('/Generales/Servicio/VerificarTecnicoPoliza', {}, '#modal-dialogo', function (respuesta) {
        if (respuesta === false) {
            $('#divCampoCorreo').addClass('hidden');
        }
    });
}

Servicio.prototype.servicioValidacion = function () {
    var _this = this;
    var ticket = arguments[0].ticket || null;
    var servicio = arguments[0].servicio;
    var estatus = '5';
    var sucursal = arguments[0].sucursal;
    var seccion = arguments[0].seccion;
    var descripcion = arguments[0].descripcion;
    var datosConcluir = arguments[0].datosConcluir || null;
    var dataMandar = {ticket: ticket, servicio: servicio, estatus: estatus, sucursal: sucursal, descripcion: descripcion, datosConcluir: datosConcluir};

    if ($('#evidenciaCambiosSinClasificar').val() !== undefined) {
        _this.file.enviarArchivos('#evidenciaCambiosSinClasificar', '/Generales/Servicio/ServicioEnValidacion', seccion, dataMandar, function (respuesta) {
            if (respuesta.code === 200) {
                _this.mensajeModal('Se Concluyó correctamente el servicio', 'Correcto');
            } else {
                _this.mensajeModal('Ocurrió el error "' + respuesta.message + '" Por favor contacte al administrador del Sistema AdIST.', 'Error');
            }
        });
    } else {
        _this.enviarEvento('/Generales/Servicio/ServicioEnValidacion', dataMandar, seccion, function (respuesta) {
            if (respuesta.code === 200) {
                _this.mensajeModal('Se Concluyó correctamente el servicio', 'Correcto');
            } else {
                _this.mensajeModal('Ocurrió el error "' + respuesta.message + '" Por favor contacte al administrador del Sistema AdIST.', 'Error');
            }
        });
    }
}
