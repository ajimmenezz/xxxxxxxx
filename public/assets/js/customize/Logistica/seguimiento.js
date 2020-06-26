$(function () {

    var evento = new Base();
    var websocket = new Socket();
    var calendario = new Fecha();
    var file = new Upload();
    var tabla = new Tabla();
    var select = new Select();
    var materialFaltanteDistribucion = new Array();
    var materialFaltanteDistribucionListaVieja = new Array();
    var materialDeServicioTrafico;
    var numeroDestino = null;
    var servicios = new Servicio();
    var nota = new Nota();

    //Evento que maneja las peticiones del socket
    websocket.socketMensaje();

    //Muestra la hora en el sistema
    evento.horaServidor($('#horaServidor').val());

    //Evento para cerra la session
    evento.cerrarSesion();

    //Creando tabla de areas
    tabla.generaTablaPersonal('#data-table-logistica', null, null, true, true, [[0, 'desc']]);

    //Evento para mostrar la ayuda del sistema
    evento.mostrarAyuda('Ayuda_Proyectos');

    //Inicializa funciones de la plantilla
    App.init();

    //Evento que carga la seccion de seguimiento de un servicio de tipo Logistica
    $('#data-table-logistica tbody').on('click', 'tr', function () {
        var datos = $('#data-table-logistica').DataTable().row(this).data();

        if (datos !== undefined) {
            var servicio = datos[0];
            var operacion = datos[7];

            if (operacion === '1') {
                var html = '<div id="confirmacionServicioLogistica">\n\
                                <div class="row">\n\
                                    <div id="mensaje-modal" class="col-md-12 text-center">\n\
                                        <h3>¿Quieres atender el servicio?</h3>\n\
                                    </div>\n\
                                </div>\n\
                            </div>';
                html += '<div class="row m-t-20">\n\
                                <div class="col-md-12 text-center">\n\
                                    <button id="btnIniciarServicio" type="button" class="btn btn-sm btn-success"><i class="fa fa-check"></i> Aceptar</button>\n\
                                    <button id="btnCancelarIniciarServicio" type="button" class="btn btn-sm btn-danger"><i class="fa fa-times"></i> Cerrar</button>\n\
                                </div>\n\
                            </div>';
                $('#btnModalConfirmar').addClass('hidden');
                $('#btnModalAbortar').addClass('hidden');
                evento.mostrarModal('Iniciar Servicio', html);
                $('#btnModalConfirmar').empty().append('Eliminar');
                $('#btnModalConfirmar').off('click');

                $('#btnIniciarServicio').off('click');
                $('#btnIniciarServicio').on('click', function () {
                    $(this).addClass('disabled');
                    $('#btnCancelarIniciarServicio').addClass('disabled');
                    var data = {servicio: servicio, operacion: '1'};
                    evento.enviarEvento('Seguimiento/Servicio_Datos', data, '#modal-dialogo', function (respuesta) {
                        evento.cerrarModal();
                        data = {servicio: servicio, operacion: '2'};
                        cargarFormularioSeguimiento(data, datos, '#panelSeguimientoLogistica');
                        recargandoTablaLogistica(respuesta.serviciosAsignados);
                    });
                });

                //Envento para concluir con la cancelacion
                $('#btnCancelarIniciarServicio').on('click', function () {
                    evento.cerrarModal();
                });

            } else if (operacion === '2' || operacion === '12' || operacion === '10') {
                var data = {servicio: servicio, operacion: '2'};
                cargarFormularioSeguimiento(data, datos, '#panelSeguimientoLogistica');
            }


        }
    });

    var cargarFormularioSeguimiento = function () {
        var data = arguments[0];
        var datosTabla = arguments[1];
        var panel = arguments[2];

        evento.enviarEvento('Seguimiento/Servicio_Datos', data, panel, function (respuesta) {
            var datosDelServicio = respuesta.datosServicio;
            var informacionServicio = respuesta.informacion;
            var formulario = respuesta.formulario;
            var archivo = respuesta.archivo;
            var avanceServicio = respuesta.avanceServicio;
            var datosSD = respuesta.datosSD;

            if (datosDelServicio.tieneSeguimiento === '0') {
                var idSucursal = respuesta.idSucursal[0].IdSucursal;
                servicios.ServicioSinClasificar(
                        formulario,
                        '#listaLogistica',
                        '#seccionSeguimientoServicioSinClasificar',
                        datosTabla[0],
                        datosDelServicio,
                        'Seguimiento',
                        archivo,
                        '#panelSeguimientoLogistica',
                        datosTabla[1],
                        avanceServicio,
                        idSucursal,
                        datosSD
                        );
            } else {
                materialDeServicioTrafico = respuesta.informacion.datosTrafico.Material;
                mostrarSeccionSeguimientoLogistica(formulario);
                calcularEquiposQueFaltanDistribuir(materialDeServicioTrafico, respuesta.informacion.equiposFaltantesDistribuciones);
                cargarDatoEnInputTipoEnvioSeccionPuntoAPunto(respuesta);
                iniciarElementosPaginaSeguimientoLogistica(respuesta, datosTabla);
                mostrarInputsCompletosOrigenDestino(respuesta);
                desabilitarFormularioSeccionGenerales(datosDelServicio);
                mostrarPestañaPorElDeTipoTrafico(datosDelServicio, informacionServicio);
                eventosParaSeccionSeguimientoLogistica(respuesta, datosTabla);
                mostrarBotonEmpezarRuta(respuesta.informacion.datosTrafico.Ruta, respuesta.datosServicio.IdEstatus);

                if (panel === '#seccion-datos-logistica') {
                    recargandoTablaLogistica(respuesta.serviciosAsignados);
                    evento.mostrarMensaje('.errorGeneralesLogistica', true, 'Datos actualizados Correctamente', 3000);
                }
            }
        });
    };

    var recargandoTablaLogistica = function (informacionServicio) {
        tabla.limpiarTabla('#data-table-logistica');
        $.each(informacionServicio, function (key, item) {
            var folio = '';
            if (item.Folio !== null) {
                folio = item.Folio
            }
            tabla.agregarFila('#data-table-logistica', [item.Id, item.Ticket, item.Servicio, item.FechaCreacion, item.Descripcion, item.Solicita, item.NombreEstatus, item.IdEstatus, item.IdSolicitud, folio]);
        });
    };

    var mostrarSeccionSeguimientoLogistica = function (formulario) {
        $('#listaLogistica').addClass('hidden');
        $('#seccionSeguimientoServicioLogistica').removeClass('hidden').empty().append(formulario);
    };

    var iniciarElementosPaginaSeguimientoLogistica = function () {
        var respuesta = arguments[0];
        var datosTabla = arguments[1];
        var informacionServicio = respuesta.informacion;
        var numeroDeServicio = datosTabla[0];
        $('#listaLogistica').addClass('hidden');
        $('#formularioLogistica').removeClass('hidden').empty().append(respuesta.formulario);
        select.crearSelect('#selectTTLogistica');
        select.crearSelect('#selectTipoEnvio');
        select.crearSelect('#selectDestinoDistribucion');
        select.crearSelect('#selectTipoEnvioDistribucion');
        tabla.generaTablaPersonal('#data-table-servicio-materiales', null, null, true, true);
        tabla.generaTablaPersonal('#data-table-distribucion', null, null, true, true);
        tabla.generaTablaPersonal('#data-table-equipos-distribucion', null, null, true, true);

        $('#inputNumeroSerieTags').tagit({
            beforeTagAdded: function (event, ui) {
                var cantidad = $('#inputCantidadEquipo').val();
                var series = $('#inputNumeroSerieTags').tagit("assignedTags").length + 1;
                if (cantidad === '') {
                    evento.mostrarMensaje('.errorAgregar', false, 'Debes definir primero la cantidad de equipos para poder ingresar las series.', 3000);
                    $("#inputNumeroSerieTags").data("ui-tagit").tagInput.val('');
                    return false;
                } else if (series > cantidad) {
                    evento.mostrarMensaje('.errorAgregar', false, 'Ya no puedes agregar mas series ya que excedes la cantidad solicitada.', 3000);
                    $("#inputNumeroSerieTags").data("ui-tagit").tagInput.val('');
                    return false;
                }
            }
        });

        select.cambiarOpcion('#selectTipoOrigen', informacionServicio.datosTrafico.TipoOrigen);
        select.cambiarOpcion('#selectTipoDestino', informacionServicio.datosTrafico.TipoDestino);
        select.cambiarOpcion('#selectRutaLogistica', informacionServicio.datosTrafico.Ruta);
        select.cargaDatos('#selectMaterialDistribuir', materialFaltanteDistribucion);
        select.crearSelectMultiple('#selectSerieMaterialDistribucion', 'Seleccionar');
        select.cambiarOpcion('#selectListaTipoEnvio', informacionServicio.datosEnvio.Paqueteria);

        $('#fechaEnvio').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss'
        }).val(informacionServicio.datosEnvio.FechaEnvio);

        $('#entregaFechaEnvio').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss'
        }).val(informacionServicio.datosEnvio.FechaEntrega);

        $('#fechaRecoleccion').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss'
        });

        $('#fechaRecoleccionDistribucion').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss'
        });

        $('#envioFechaDistribucion').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss'
        });

        $('#entregaFechaEnvioDistribucion').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss'
        });

        $('#fechaEnvioDistribucion').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss'
        }).val(informacionServicio.datosEnvio.FechaEnvio);

        var dataExtra = {servicio: numeroDeServicio};

        file.crearUpload('#evidenciaEnvio',
                'Seguimiento/Guardar_Evidencia',
                null,
                null,
                informacionServicio.datosEnvio.EvidenciasEnvio,
                'Seguimiento/Eliminar_Evidencia',
                'UrlEnvio',
                null,
                null,
                null,
                true,
                dataExtra
                );

        file.crearUpload('#evidenciaEntregaEnvio',
                'Seguimiento/Guardar_Evidencia',
                null,
                null,
                informacionServicio.datosEnvio.EvidenciasEntrega,
                'Seguimiento/Eliminar_Evidencia',
                'UrlEntrega',
                null,
                null,
                null,
                true,
                dataExtra
                );

        file.crearUpload('#evidenciaRecoleccion',
                'Seguimiento/Guardar_Evidencia',
                null,
                null,
                obtenerUrlParaSeccionRecoleccionAlmacenamiento(informacionServicio),
                'Seguimiento/Eliminar_Evidencia',
                'UrlRecoleccion',
                null,
                null,
                null,
                true,
                dataExtra
                );

        file.crearUpload('#evidenciasNotasServicio',
                'Seguimiento/Guardar_Nota_Servicio',
                null,
                null,
                obtenerUrlParaSeccionRecoleccionAlmacenamiento(informacionServicio),
                'Seguimiento/Eliminar_Evidencia',
                null,
                null,
                null,
                null,
                null,
                dataExtra
                );
        file.crearUpload('#evidenciaRecoleccionDistribucion',
                'Seguimiento/Generar_Recoleccion_Distribucion',
                null,
                null,
                null,
                'Seguimiento/Eliminar_Evidencia',
                null,
                null,
                null,
                null,
                null,
                dataExtra
                );

        $("#divNotasServicio").slimScroll({height: '400px'});
        nota.initButtons({servicio: datosTabla[0]}, 'Seguimiento');
    };

    var obtenerUrlParaSeccionRecoleccionAlmacenamiento = function (informacionServicio) {
        if (informacionServicio.datosRecoleccion !== null) {
            $('#fechaRecoleccion').val(informacionServicio.datosRecoleccion.Fecha);
            $('#inputEntregaRecoleccion').val(informacionServicio.datosRecoleccion.Entrega);
            $('#textareaObservacionesRecoleccion').val(informacionServicio.datosRecoleccion.Comentarios);
            return informacionServicio.datosRecoleccion.EvidenciasRecoleccion;
        } else {
            return null;
        }
    };

    var eventosParaSeccionSeguimientoLogistica = function () {
        var informacionServicio = arguments[0];
        var datosTabla = arguments[1];
        var ticket = informacionServicio.datosServicio.Ticket;
        var idSolicitud = informacionServicio.datosServicio.IdSolicitud;
        var idDestino = null;
        var idRuta = informacionServicio.informacion.datosTrafico.Ruta;

        //Evento que  muestra diferentes campos del tipo origen
        $('#selectTipoOrigen').on('change', function () {
            var tipo = $(this).val();
            crearInputTipoOrigenDestino(tipo, 'seleccionTipoOrigen', informacionServicio, 'tipoOrigen', 'origen', 'Origen');
        });

        //Evento que muestra diferentes campos del tipo destino
        $('#selectTipoDestino').on('change', function () {
            var tipo = $(this).val();
            crearInputTipoOrigenDestino(tipo, 'seleccionTipoDestino', informacionServicio, 'tipoDestino', 'destino', 'Destino');
        });

        //Evento que vuelve a mostrar la lista de los servicios
        $('#btnRegresarSeguimientoLogistica').on('click', function () {
            $('#seccionSeguimientoServicioLogistica').empty().addClass('hidden');
            $('#listaLogistica').removeClass('hidden');
        });

        //Evento para ir actualizar el formulario generales
        $('#btnGuardarGenerales').on('click', function (e) {
            if (evento.validarFormulario('#formGeneralesLogistica')) {
                var tipoTrafico = $('#selectTTLogistica').val();
                var tipoOrigen = $('#selectTipoOrigen').val();
                var origen = $('#origen').val();
                var tipoDestino = $('#selectTipoDestino').val();
                var destino = (tipoDestino === '') ? '' : $('#destino').val();
                var ruta = $('#selectRutaLogistica').val();
                var dataGenerales = {operacion: '3', servicio: datosTabla[0], tipoTrafico: tipoTrafico, tipoOrigen: tipoOrigen, origen: origen, tipoDestino: tipoDestino, destino: destino, ruta: ruta, ticket: ticket};
                cargarFormularioSeguimiento(dataGenerales, datosTabla, '#seccion-datos-logistica');
            }
        });

        //Evento para crear una nueva ruta
        $('#btnAgregarRuta').on('click', function (e) {
            evento.enviarEvento('Seguimiento/MostrarFormularioRutas', '', '#seccionRutas', function (respuesta) {
                $('#seccionSeguimientoServicioLogistica').addClass('hidden');
                $('#formularioRuta').removeClass('hidden');
                $('#ruta').removeClass('hidden').empty().append(respuesta.formulario);
                calendario.crearFecha('.calendario');
                select.crearSelect('#selectChoferRutas');
                //Evento que guardar un nueva Ruta
                $('#btnNuevaRuta').on('click', function () {
                    var fecha = $('#inputFechaRutas').val();
                    var chofer = $('#selectChoferRutas').val();
                    var data = {fecha: fecha, chofer: chofer};
                    if (evento.validarFormulario('#formNuevaRuta')) {
                        if ($('#inputFechaRutas').val() !== '') {
                            evento.enviarEvento('Seguimiento/NuevaRuta', data, '#seccionRutas', function (respuesta) {
                                if (respuesta instanceof Array) {
                                    var rutas = [];
                                    $.each(respuesta, function (key, value) {
                                        var texto = value.Codigo + ' (' + value.Nombre + ' ' + value.ApPaterno + ')';
                                        rutas.push({id: value.Id, text: texto});
                                    });
                                    var ultimaRuta = (rutas[rutas.length - 1]);
                                    select.cargaDatos('#selectRutaLogistica', rutas);
                                    select.cambiarOpcion('#selectRutaLogistica', ultimaRuta['id'].toString());
                                    $('#formularioRuta').addClass('hidden');
                                    $('#seccionSeguimientoServicioLogistica').removeClass('hidden');
                                    evento.mostrarMensaje('.errorGeneralesLogistica', true, 'Ruta creada correctamente, favor de presionar “guardar” para agregar la ruta al seguimiento.', 3000);
                                } else {
                                    evento.mostrarMensaje('.errorRuta', false, 'No se pudo generar la ruta vuelva a intentarlo', 8000);
                                }
                            });
                        } else {
                            evento.mostrarMensaje('.errorRuta', false, 'Debes llenar el campo Fecha de Ingreso', 3000);
                        }
                    }
                });
                $('#btnCancelarRuta').on('click', function () {
                    $('#formularioRuta').addClass('hidden');
                    $('#ruta').empty().addClass('hidden');
                    $('#seccionSeguimientoServicioLogistica').removeClass('hidden');
                });
            });
        });

        //evento para mostrar los detalles de las descripciones
        $('#detallesLogistica').on('click', function (e) {
            if ($('#masDetalles').hasClass('hidden')) {
                $('#masDetalles').removeClass('hidden');
                $('#detallesLogistica').empty().html('<a>- Detalles</a>');
            } else {
                $('#masDetalles').addClass('hidden');
                $('#detallesLogistica').empty().html('<a>+ Detalles</a>');
            }
        });

        //evento para mostrar el modal que contiene la informacion de service desk
        $('#folioSeguimiento').on('click', function (e) {
            muestraInformacionFolioServiceDesk(informacionServicio.informacion.datosServiceDesk, informacionServicio.datosServicio.Folio);
        });

        //Evento para agregar Equipos a la tabla de material
        $('#btnAgregaEquipo').on('click', function () {

            if (validarFormulario('#selectEquipo', '#inputCantidadEquipo')) {
                agregandoEquipo();
                limpiarFormularioMaterial();
            }

        });

        //Evento que limpia el campo de series cuando no se tiene ningun cantidad definida
        $('#inputCantidadEquipo').on('blur', function () {
            $('#inputNumeroSerieTags').tagit('removeAll');
        });

        //Evento para agregar material a la tabla material
        $('#btnAgregaMaterial').on('click', function () {

            if (validarFormulario('#selectMaterial', '#inputCantidadMaterial')) {
                agregandoMaterialYOtros('#selectMaterial', '#inputCantidadMaterial');
                limpiarFormularioMaterial();
            }

        });

        //Evento para agregar Otros a la tabla material
        $('#btnAgregarOtros').on('click', function () {

            if (validarFormulario('#inputOtro', '#inputCantidadOtro')) {
                agregandoMaterialYOtros('#inputOtro', '#inputCantidadOtro');
                limpiarFormularioMaterial();
            }
        });

        //Evento encargado de eliminar un fila de la tabla de materiales
        $('#data-table-servicio-materiales tbody').on('click', 'tr', function () {
            var datosRecoleccion = informacionServicio.informacion.datosRecoleccion;
            if (!datosRecoleccion) {
                tabla.eliminarFila('#data-table-servicio-materiales', this);
            }
        });

        //Evento encargado de guardar los cambios del material del servicio de trafico
        $('#btnGuardarMaterialTrafico').on('click', function () {
            var filas = $('#data-table-servicio-materiales').DataTable().rows().data();

            if (filas.length > 0) {
                var mensaje = '<div class="row">\n\
                            <div class="col-md-12 text-center">\n\
                                <p>Al guardar los cambios se borrara la información anterior</p>\n\
                                <p>¿Estas seguro de querer realizar esta operación?</p>\n\
                            </div>\n\
                        </div>\n\
                        <div class="row">\n\
                            <div class="col-md-12 text-center">\n\
                                <button type="button" class="btn btn-sm btn-success" id="btnAceptarGuardarCambiosTrafico">Aceptar</button>\n\
                                <button type="button" class="btn btn-sm btn-danger" id="btnCancelarGuardarCambiosTrafico">Cancelar</button>\n\
                            </div>\n\
                        </div> ';

                evento.mostrarModal('Agregar Material', mensaje);
                $('#btnModalConfirmar').addClass('hidden');
                $('#btnModalAbortar').addClass('hidden');

                //Evento que guarda el material del trafico
                $('#btnAceptarGuardarCambiosTrafico').on('click', function () {
                    var datos = [];

                    for (var i = 0; i < filas.length; i++) {
                        datos.push(filas[i]);
                    }
                    var data = {servicio: datosTabla[0], operacion: '4', material: datos};

                    evento.enviarEvento('Seguimiento/Servicio_Datos', data, '#modal-dialogo', function (respuesta) {
                        if (respuesta.materialActualizado) {
                            $('.modal-body').empty().append('\
                            <div class="row">\n\
                                <div class="col-md-12 text-center">\n\
                                    <p>Se guardo el material con Exito</p>\n\
                                </div>\n\
                            </div>');
                        } else {
                            $('.modal-body').empty().append('\
                            <div class="row">\n\
                                <div class="col-md-12 text-center">\n\
                                    <p>No se pudo guardar el material por favor de volver a intentarlo</p>\n\
                                </div>\n\
                            </div>');
                        }
                        $('#btnModalAbortar').empty().append('Cerrar').removeClass('hidden');
                        actulizarSelectMaterialDistribucion(respuesta.materialActualizado.Material);
                    });

                });

                //Evento que guarda el material del trafico
                $('#btnCancelarGuardarCambiosTrafico').on('click', function () {
                    evento.cerrarModal();
                });
            } else {
                evento.mostrarMensaje('.errorGuardarMaterial', false, 'Para guardar el material debes al menos haber agregado un material.', 3000);
            }

        });

        //Evento que muestra la pestañas de envio o recoleccion segun lo que se seleccione
        $('#selectTTLogistica').on('change', function () {
            var valor = $(this).val();
            var envio = $('[href=#Envio]').parent('li');
            var recoleccion = $('[href=#Recoleccion]').parent('li');
            var pestañaDistribucion = $('[href=#Distribucion]').parent('li');

            if (valor === '3') {
                $('#selectTipoDestino').attr('data-parsley-required', 'false').val('').trigger('change').parents('div.row').addClass('hidden');
            } else {
                $('#selectTipoDestino').attr('data-parsley-required', 'true').parents('div.row').removeClass('hidden');
            }

            if (informacionServicio.datosServicio.IdEstatus === '12') {
                if (valor === '') {
                    if (!envio.hasClass('hidden')) {
                        envio.addClass('hidden');
                    }
                    if (!recoleccion.hasClass('hidden')) {
                        recoleccion.addClass('hidden');
                    }
                } else if (valor === '1') {
                    if (envio.hasClass('hidden')) {
                        envio.removeClass('hidden');
                    }
                    if (!recoleccion.hasClass('hidden')) {
                        recoleccion.addClass('hidden');
                    }
                } else if (valor === '2') {
                    if (recoleccion.hasClass('hidden')) {
                        recoleccion.removeClass('hidden');
                    }
                    if (!envio.hasClass('hidden')) {
                        envio.addClass('hidden');
                    }
                } else if (valor === '3') {
                    if (pestañaDistribucion.hasClass('hidden')) {
                        pestañaDistribucion.removeClass('hidden');
                    }
                }
            }
        });

        //Evento que muestra la pestaña de informacion de consolidado y paqueteria o informacion de entrega
        $('#selectTipoEnvio').on('change', function () {
            var opcionSeleccionada = $(this).val();
            muestraOpcionesDeEnvio('PuntoAPunto', informacionServicio, opcionSeleccionada);
        });

        //Evento que guarda la información del servicio de trafico en la seccion de envio
        $('#btnGuardarInformacionEnvio').on('click', function () {
            try {

                var seccion = '';
                var data = generarDatosEnvioParaGuardarCambios(datosTabla[0], seccion);

                guardarCambiosEnvioMaterial(data);
            } catch (exception) {
                evento.mostrarMensaje('#errorGeneralEnvio', false, exception.message, 3000);
            }
        });

        //Evento que concluir el servicio de trafico
        $('.btnConcluirServicio').on('click', function () {
            var idTipoTrafico = informacionServicio.informacion.datosTrafico.TipoTrafico;
            var servicio = datosTabla[0];
            var datos = null;

            try {
                if (idTipoTrafico === '1') {
                    datos = {servicio: servicio, operacion: '5', idTipoTrafico: idTipoTrafico};
                    datos.datosFormulario = generarDatosEnvioParaGuardarCambios(servicio);
                } else if (idTipoTrafico === '2') {
                    datos = {servicio: servicio, operacion: '5', idTipoTrafico: idTipoTrafico};
                    datos.datosFormulario = verificarDatosCambiosRecoleccion(servicio, 'concluir');
                } else if (idTipoTrafico === '3') {
                    datos = {servicio: servicio, operacion: '5', idTipoTrafico: idTipoTrafico};
                }

                evento.enviarEvento('Seguimiento/Actualizar_Servicio', datos, '#seccion-datos-logistica', function (respuesta) {
                    evento.mostrarModal(respuesta.informacion.tituloMensaje, respuesta.informacion.mensaje);
                    $('#btnModalConfirmar').addClass('hidden');
                    $('#btnModalAbortar').empty().append('Cerrar');
                    $('#btnModalAbortar').on('click', function () {
                        if (respuesta.informacion.servicioConcluido) {
                            recargandoTablaLogistica(respuesta.informacion.serviciosAsignados);
                            $('#seccionSeguimientoServicioLogistica').empty().addClass('hidden');
                            $('#listaLogistica').removeClass('hidden');
                            evento.cerrarModal();
                        } else {
                            evento.cerrarModal();
                        }
                    });
                });
            } catch (exception) {
                if (idTipoTrafico === '1') {
                    evento.mostrarMensaje('#errorGeneralEnvio', false, exception.message, 3000);
                } else if (idTipoTrafico === '2') {
                    evento.mostrarMensaje('#errorGeneralRecoleccion', false, exception.message, 3000);
                }
            }
        });

        //Evento que concluir el servicio de trafico en la seccion recoleccion
        $('#btnGuardarInformacionRecoleccion').on('click', function () {
            try {
                guardarCambiosRecoleccion(datosTabla[0]);
            } catch (exception) {
                evento.mostrarMensaje('#errorGeneralRecoleccion', false, exception.message, 3000);
            }
            var dataVerificacion = {operacion: '2', servicio: datosTabla[0], tipoTrafico: $('#selectTTLogistica').val()};
            evento.enviarEvento('Seguimiento/VerificarExistente', dataVerificacion, '#seccion-datos-logistica', function (respuesta) {
                if (respuesta === true) {
                    var fechaRecoleccion = $('#fechaRecoleccion').val();
                    var entregaRecoleccion = $('#inputEntregaRecoleccion').val();
                    var observacionesRecoleccion = $('#textareaObservacionesRecoleccion').val();
                    if (fechaRecoleccion !== '' || entregaRecoleccion !== '' || observacionesRecoleccion !== '') {
                        var datos = {servicio: datosTabla[0], fecha: fechaRecoleccion, entrega: entregaRecoleccion, observaciones: observacionesRecoleccion};
                        evento.enviarEvento('Seguimiento/Actualizar_Recoleccion', datos, '#seccion-datos-logistica', function (respuesta) {
                            if (respuesta === true) {
                                evento.mostrarMensaje('#errorGeneralRecoleccion', true, 'Datos actualizados correctamente.', 3000);
                                mostrarSeccionParaGenerarDestinoDistribucion();
                            } else {
                                evento.mostrarMensaje('#errorGeneralRecoleccion', false, 'No se pudo Actulizar los datos.', 3000);
                            }
                        });
                    } else {
                        evento.mostrarMensaje('#errorGeneralRecoleccion', false, 'Debes llenar almenos un campo.', 3000);
                    }
                } else {
                    evento.mostrarMensaje('#errorGeneralRecoleccion', false, 'Favor de guardar el tipo de campo', 3000);
                }
            });
        });

        //Evento para descargar formato equipo
        $('#btnDescargarFormato').on('click', function () {
            var dataTipoTrafico = {idTipoTrafico: informacionServicio.informacion.datosTrafico.TipoTrafico};
            evento.enviarEvento('Seguimiento/DescargarFormato', dataTipoTrafico, '#seccion-datos-logistica', function (respuesta) {
                location.href = respuesta;
            });
        });

        //Encargado subit el formato de excel
        $('#btnSubirFormato').on('click', function () {
            var html = '<div class="row">\n\
                            <div class="col-md-12">\n\
                                <div class="form-group">\n\
                                    <input id="formatoExcel"  name="formatoExcel[]" type="file" multiple/>\n\
                                </div>\n\
                            </div>\n\
                        </div>';
            html += '<div class="row m-t-20">\n\
                                <div class="col-md-12 text-center">\n\
                                    <button id="subirFormato" type="button" class="btn btn-sm btn-success"><i class="fa fa-cloud-download"></i> Subir Archivo</button>\n\
                                    <button id="cancelarFormato" type="button" class="btn btn-sm btn-danger"><i class="fa fa-times"></i> Cancelar</button>\n\
                                </div>\n\
                            </div><br>';
            html += '<div class="row">\n\
                                    <div class="col-md-12">\n\
                                        <div class="errorFormato"></div>\n\
                                    </div>\n\
                    </div>';
            $('#btnModalConfirmar').addClass('hidden');
            evento.mostrarModal('Subir Formato', html);
            file.crearUpload('#formatoExcel',
                    'Seguimiento/CargarFormato',
                    ['xlsx'],
                    null,
                    null,
                    null,
                    null,
                    true,
                    1,
                    true
                    );
            $('#btnModalConfirmar').empty().append('Eliminar');
            $('#btnModalConfirmar').off('click');
            $('#btnModalAbortar').addClass('hidden');
            $('#subirFormato').on('click', function () {
                $('#subirFormato').attr('disabled', 'disabled');
                $('#cancelarFormato').attr('disabled', 'disabled');
                var tipoTrafico = informacionServicio.informacion.datosTrafico.TipoTrafico;
                var dataExtra = {servicio: datosTabla[0], tipoTrafico: tipoTrafico};
                file.enviarArchivos('#formatoExcel', 'Seguimiento/CargarFormato', '#seccion-datos-logistica', dataExtra, function (respuesta) {
                    if (respuesta instanceof Array) {
                        tabla.limpiarTabla('#data-table-servicio-materiales');
                        $.each(respuesta, function (key, item) {
                            tabla.agregarFila('#data-table-servicio-materiales', [item.Nombre, item.Serie, item.Cantidad, item.IdTipoEquipo, item.IdModelo]);
                        });
                        evento.cerrarModal();
                        evento.mostrarMensaje('.confirmacionFormato', true, 'Se inserto con exito.', 3000);
                    } else if (respuesta === 'sinDatos') {
                        evento.mostrarMensaje('.errorFormato', false, 'El archivo que subio no tiene datos en la hoja correcta.', 3000);
                    } else if (respuesta === 'seleccionarTipo') {
                        evento.mostrarMensaje('.errorFormato', false, 'Debe de seleccionar y  guardar el "tipo de tráfico" en el formulario Generales.', 5000);
                    } else if (respuesta === 'formatoErroneo') {
                        evento.mostrarMensaje('.errorFormato', false, 'El archivo que subio no es el correcto.', 3000);
                    } else {
                        evento.mostrarMensaje('.errorFormato', false, 'Seleccione un formato.', 3000);
                    }
                });
            });
            //Envento para no concluir con la cancelacion
            $('#cancelarFormato').on('click', function () {
                evento.cerrarModal();
            });
        });

        //Encargado de crear un nuevo servicio
        $('#btnNuevoServicioSeguimiento').on('click', function () {
            var data = {servicio: datosTabla[0]};
            servicios.nuevoServicio(
                    data,
                    ticket,
                    idSolicitud,
                    'Seguimiento/Servicio_Nuevo_Modal',
                    '#seccion-datos-logistica',
                    'Seguimiento/Servicio_Nuevo'
                    );
        });

        //Encargado de generar el archivo Pdf
        $('#btnGeneraPdfServicio').off('click');
        $('#btnGeneraPdfServicio').on('click', function () {
            var data = {servicio: datosTabla[0]};
            evento.enviarEvento('Seguimiento/Servicio_ToPdf', data, '#seccion-datos-logistica', function (respuesta) {
                window.open(respuesta.link);
            });
        });

        //Evento para generar destinos a una distribucion
        $('#btnGenerarDestinoDistribucion').on('click', function () {

            if (informacionServicio.informacion.datosRecoleccion !== null) {
                mostrarSeccionParaGenerarDestinoDistribucion();
            } else {
                mostrarSeccionRecoleccionEquipos();
            }
        });

        //Evento de select para definir el tipo de destino en la seccion de distribucion
        $('#selectDestinoDistribucion').on('change', function () {
            var valorSeleccionado = $(this).val();
            crearInputTipoOrigenDestino(valorSeleccionado, 'seleccionTipoDestinoDistribucion', informacionServicio, 'destinoDistribucion', 'distribucion', 'Destino', true);
            if ($(this).val() !== '') {
                habilitaMaterialDistribucion();
                habilitaCantidadDistribucion();
            } else {
                desahabilitaMaterialDistribucion();
                desahabilitaCantidadDistribucion();
            }
        });

        //Evento que muestra la seccion tabla destino de la seccion recoleccion y distribucion
        $('#btnRegresarTablaDestinos').on('click', function () {

            cambiarTituloSeccionDistribucion('Destinos');
            mostrarOcultarBotonRegresarTablaDestinos('ocultar');
            mostrarOcultarBotonAgregarDestino('mostrar');
            restablecerFormularioRecoleccionDistribucion();
            restablecerFormularioDestinoDistribucion();
            restablecerSeccionEnvioDistribucion();
            mostrarOcultarTablaDestinosDistribucion('mostrar');

            if (materialFaltanteDistribucionListaVieja.length > 0) {
                restablecerMaterialFaltanteDistribucion();
                actualizarSelectMaterialDistribucion();
            }
        });

        //Evento para guardar recoleccion de una distribucion
        $('#btnGuardarRecoleccionDistribucion').on('click', function () {

            var fechaRecoleccion = $('#fechaRecoleccionDistribucion').val();
            var entregaRecoleccion = $('#inputEntregaRecoleccionDistricion').val();
            var observacionesRecoleccion = $('#textareaObservacionesRecoleccionDistribucion').val();
            var evidenciaRecoleccion = $('#evidenciaRecoleccionDistribucion').val();

            if (fechaRecoleccion !== '' && entregaRecoleccion !== '' && observacionesRecoleccion !== '' && evidenciaRecoleccion !== '') {
                var datos = {servicio: datosTabla[0], fecha: fechaRecoleccion, entrega: entregaRecoleccion, observaciones: observacionesRecoleccion};
                confirmacionParaGuardarRecoleccionDistribucion(informacionServicio, datos);
            } else {
                evento.mostrarMensaje('#errorGeneralRecoleccionDistribucion', false, 'Debes llenar los campos requeridos', 3000);
            }
        });

        //Evento que muestra el campo serie de un equipo y llena la cantidad en la seccion distribucion
        $('#selectMaterialDistribuir').on('change', function () {

            var materialSeleccionado = $(this).val();
            var datosMaterial = obtenerDatosDeMaterial(materialSeleccionado);

            if (materialSeleccionado !== '') {
                if (datosMaterial.TipoEquipo === '1') {
                    $('#contenedorSeriesDistribucion').removeClass('hidden');
                    $('#contenedorCantidadDistribucion').addClass('hidden');
                    $('#cantidadMaterial').removeAttr('data-parsley-required');
                    $('#selectSerieMaterialDistribucion').attr('data-parsley-required', 'true');
                    select.cargaDatos('#selectSerieMaterialDistribucion', datosMaterial.datos);
                } else {
                    $('#contenedorSeriesDistribucion').addClass('hidden');
                    $('#contenedorCantidadDistribucion').removeClass('hidden');
                    $('#cantidadMaterial').attr('data-parsley-required', 'true');
                    $('#selectSerieMaterialDistribucion').removeAttr('data-parsley-required');
                    $('#selectSerieMaterialDistribucion').empty();
                }
                $('#cantidadMaterial').val(datosMaterial.Cantidad);
            } else {
                $('#contenedorSeriesDistribucion').addClass('hidden');
                $('#inputSerieMaterialDistribucion').val('');
            }
        });

        //Evento que guarda el destino de una distribucion
        $('#btnGuardarDestinoDistribucion').on('click', function () {

            var servicio = datosTabla[0];

            if (evento.validarFormulario('#formDestinoDistribucion')) {
                crearNuevoDestino(servicio);
            }
        });

        //Evento para agregar material a un destino de distribución
        $('#btnAgregarMaterialDistribucion').on('click', function () {

            var materialSeleccionado = $('#selectMaterialDistribuir').val();
            var datosMaterial = obtenerDatosDeMaterial(materialSeleccionado);
            var tipoMaterial = null;

            if (evento.validarFormulario('#formMaterialDestinoDistribucion')) {
                try {
                    tipoMaterial = obtenerTipoMaterialParaAgregar(datosMaterial);
                    agregarMaterialATablaDistribucion(tipoMaterial, datosMaterial);
                    actualizarMaterialFaltanteDistribucion(datosMaterial, tipoMaterial, materialSeleccionado);
                    actualizarSelectMaterialDistribucion();
                    limpiarFormularioMaterialDestinoDistribucion();
                } catch (exception) {
                    evento.mostrarMensaje('#errorAgregarMaterialDistribucion', false, exception.message, 3000);
                }
            }
        });

        //Evento que elimina un material de la tabla de distribucion nueva
        $('#data-table-equipos-distribucion tbody').on('click', 'tr', function () {
            var datosFila = $('#data-table-equipos-distribucion').DataTable().row(this).data();
            regresarMaterialAVariableMaterialFaltante(datosFila);
            actualizarSelectMaterialDistribucion();
            limpiarFormulariosDestinoDistribucion();
            tabla.eliminarFila('#data-table-equipos-distribucion', this);
        });

        //Evento que guarda el material para el destino.
        $('#btnGuardarMaterialDestinoDistribucion').on('click', function () {

            var servicio = datosTabla[0];

            try {
                guardarMaterialParaDestinoDistribucion(servicio);
            } catch (exception) {
                evento.mostrarMensaje('#errorGuardarNuevoDestinoDistribucion', false, exception.message, 3000);
            }
        });

        //Evento que muestra el envio de un distribución
        $('#data-table-distribucion tbody').on('click', 'tr', function () {

            var datosFila = $('#data-table-distribucion').DataTable().row(this).data();
            var data = {servicio: datosTabla[0], destino: datosFila[0]};
            var estatus = datosFila[3];
            idDestino = datosFila[0];

            evento.enviarEvento('Seguimiento/Obtener_Material_Distribucion', data, '#seccion-datos-logistica', function (respuesta) {

                var informacionDestino = respuesta;

                cambiarTituloSeccionDistribucion('Envio');
                mostrarOcultarBotonRegresarTablaDestinos('mostrar');
                mostrarOcultarBotonAgregarDestino('ocultar');
                mostrarOcultarTablaDestinosDistribucion('ocultar');
                mostrarSeccionEnvioDistribucion();
                cargarInformacionDelDestino(informacionDestino[0]);
                iniciarFileUploadEnFormularioEnvioDistribucion({servicio: datosTabla[0], destino: idDestino}, informacionDestino[0]);

                if (estatus === 'CONCLUIDO') {
                    habilitarBloquearFormularioEnvioDistribucion('deshabilitar');
                } else {
                    habilitarBloquearFormularioEnvioDistribucion('habilitar');
                }
            });

        });

        //Evento que muestra la pestaña de informacion de consolidado y paqueteria o informacion de entrega
        $('#selectTipoEnvioDistribucion').on('change', function () {
            var opcionSeleccionada = $(this).val();
            muestraOpcionesDeEnvio('Distribucion', informacionServicio, opcionSeleccionada);
        });

        //Evento para guardar los cambios del destino para un envio de distribucion
        $('#btnGuardarInformacionEnvioDistribucion').on('click', function () {
            try {

                var seccion = 'Distribucion';
                var data = generarDatosEnvioParaGuardarCambios(datosTabla[0], seccion);
                data.destino = idDestino;

                guardarCambiosEnvioMaterial(data);
            } catch (exception) {
                evento.mostrarMensaje('#errorGeneralEnvioDistribucion', false, exception.message, 3000);
            }
        });

        //Evento que concluye un destino de distribucion
        $('#btnConcluirDestinoEnvioDistribucion').on('click', function () {
            try {
                var seccion = 'Distribucion';
                var data = generarDatosEnvioParaGuardarCambios(datosTabla[0], seccion);
                data.destino = idDestino;

                concluirDestino(data);
            } catch (exception) {
                evento.mostrarMensaje('#errorGeneralEnvioDistribucion', false, exception.message, 3000);
            }

        });

        //Evento que cancela un destino de distribucion
        $('#btnCancelarDestinoEnvioDistribucion').on('click', function () {
            cancelarDestinto({servicio: datosTabla[0], destino: idDestino});
        });

        //Encargado de filtrar los nombres de los equipos en el select Equipo en la pestaña Equipos
        $('#btnFiltrarEquipos').on('click', function () {
            var texto = $('#inputFiltraEquipos').val();
            filtrarListaDeSelect('#selectEquipo', texto);
        });

        //Encargado de filtrar los nombres de los equipos en el select Eqipo en la pestaña Material y Herramientas
        $('#btnFiltrarMaterialHerramientas').on('click', function () {
            var texto = $('#inputFiltraMaterialHerramientas').val();
            filtrarListaDeSelect('#selectMaterial', texto);
        });

        //Encargado de crear un nuevo servicio
        $('#btnCancelarServicioSeguimiento').on('click', function () {
            var data = {servicio: datosTabla[0], ticket: ticket};
            servicios.cancelarServicio(
                    data,
                    'Seguimiento/Servicio_Cancelar_Modal',
                    '#seccion-datos-logistica',
                    'Seguimiento/Servicio_Cancelar'
                    );
        });

        //Encargadod de empezar una ruta desde la seccion generales
        $('#btnEmpezarRutaSeguimiento').on('click', function () {
            empezarRuta(idRuta, datosTabla);
        });

        //evento para crear nueva solicitud
        servicios.initBotonNuevaSolicitud(datosTabla[1], '#seccion-datos-logistica');
        servicios.eventosFolio(datosTabla[8], '#seccion-datos-logistica', datosTabla[0]);

    };

    var mostrarInputsCompletosOrigenDestino = function (servicio) {
        var informacionServicio = servicio.informacion;
        if (informacionServicio.datosTrafico.TipoOrigen !== '' && informacionServicio.datosTrafico.TipoDestino !== '') {
            mostrarInputOrgienSeccionGenerales(informacionServicio, servicio);
            mostrarInputDestinoSeccionGenerales(informacionServicio, servicio);
        }
    };

    var mostrarInputOrgienSeccionGenerales = function (informacionServicio, servicio) {
        crearInputTipoOrigenDestino(informacionServicio.datosTrafico.TipoOrigen, 'seleccionTipoOrigen', servicio, 'tipoOrigen', 'origen', 'Origen');
        if (informacionServicio.datosTrafico.TipoOrigen !== '3') {
            select.cambiarOpcion('#origen', informacionServicio.datosTrafico.Origen);
        } else {
            $('#origen').val(informacionServicio.datosTrafico.OrigenDireccion);
        }
    };

    var mostrarInputDestinoSeccionGenerales = function (informacionServicio, servicio) {
        crearInputTipoOrigenDestino(informacionServicio.datosTrafico.TipoDestino, 'seleccionTipoDestino', servicio, 'tipoDestino', 'destino', 'Destino');
        if (informacionServicio.datosTrafico.TipoDestino !== '3') {
            select.cambiarOpcion('#destino', informacionServicio.datosTrafico.Destino);
        } else {
            $('#destino').val(informacionServicio.datosTrafico.DestinoDireccion);
        }
    };

    var crearInputTipoOrigenDestino = function (tipo, removerHidden, respuesta, idTipo, id, label, seccionDistribucion) {
        $('#' + removerHidden + '').removeClass('hidden');
        var estatus = respuesta.datosServicio.IdEstatus;
        var habilitado = '';
        if (estatus === '12' && typeof seccionDistribucion === 'undefined') {
            habilitado = 'disabled';
        }
        switch (tipo) {
            case('1'):
                var html = '<div class="form-group">';
                html += '       <label for="logistica">' + label + ' *</label>';
                html += '       <select id="' + id + '" class="form-control generales" style="width: 100%" data-parsley-required="true" ' + habilitado + '>';
                html += '           <option value="">Seleccionar</option>';
                $.each(respuesta.informacion.sucursales, function (key, valor) {
                    html += '       <option value="' + valor.Id + '">' + valor.Nombre + ' (' + valor.Cliente + ')</option>';
                });
                html += '       < /select>';
                html += '   </div>';
                html += '';
                $('#' + idTipo + '').html(html);
                select.crearSelect('#' + id + '');
                break;
            case('2'):
                var html = '<div class="form-group">';
                html += '       <label for="logistica">' + label + ' *</label>';
                html += '       <select id="' + id + '" class="form-control generales" style="width: 100%" data-parsley-required="true" ' + habilitado + '>';
                html += '           <option value="">Seleccionar</option>';
                $.each(respuesta.informacion.probedores, function (key, valor) {
                    html += '       <option value="' + valor.Id + '">' + valor.Nombre + '</option>';
                });
                html += '       < /select>';
                html += '   </div>';
                html += '';
                $('#' + idTipo + '').html(html);
                select.crearSelect('#' + id + '');
                break;
            case('3'):
                var html = '<div class="form-group">';
                html += '       <label for="logistica">' + label + ' *</label>';
                html += '       <input type="text" class="form-control generales" id="' + id + '" placeholder="Ingresa la Dirección" style="width: 100%" data-parsley-required="true" ' + habilitado + '/>';
                html += '   </div>';
                html += '';
                $('#' + idTipo + '').html(html);
                break;
            case(''):
                $('#' + removerHidden + '').addClass('hidden');
                $('#' + idTipo + '').html('');
                break;
        }
    };

    var cargarDatoEnInputTipoEnvioSeccionPuntoAPunto = function (servicio) {
        var informacionServicio = servicio.informacion;
        if (informacionServicio.datosEnvio.TipoEnvio !== '') {
            $('#selectTipoEnvio').val(informacionServicio.datosEnvio.TipoEnvio).trigger('change');
            muestraOpcionesDeEnvio('PuntoAPunto', servicio, informacionServicio.datosEnvio.TipoEnvio);
        }
    };

    var muestraOpcionesDeEnvio = function () {
        var seccion = (arguments[0] === 'PuntoAPunto') ? '' : arguments[0];
        var informacionServicio = arguments[1];
        var opcionSeleccionada = arguments[2];
        var pestañaConsolidadoPaqueteria = $('[href=#ConsolidadoPaqueteria' + seccion + ']').parent('li');
        var formularioConsolidadoPaqueteria = $('#ConsolidadoPaqueteria' + seccion);
        var pestañaEntrega = $('[href=#EntregaMaterial' + seccion + ']').parent('li');
        var formularioEntrega = $('#EntregaMaterial' + seccion);
        var tituloFormularios = $('#titulo' + seccion);
        var pestaña = $('.tipoEnvio' + seccion);

        if (opcionSeleccionada === '') {
            renombrarPestaña(pestaña, '');
            ocultarMostarContenedorPestañasEnvioDistribucion('ocultar');
            ocultarFormularios(seccion);

        } else if (opcionSeleccionada === '1') {
            renombrarPestaña(pestaña, 'Entrega');
            mostrarTitulo(tituloFormularios);
            ocultarMostarContenedorPestañasEnvioDistribucion('mostrar');
            ocultarMostrarPestaña(pestañaEntrega, formularioEntrega, 'mostrar');
            activarDesactivarPestaña(pestañaEntrega, formularioEntrega, 'activar');
            ocultarMostrarPestaña(pestañaConsolidadoPaqueteria, formularioConsolidadoPaqueteria, 'ocultar');
            activarDesactivarPestaña(pestañaConsolidadoPaqueteria, formularioConsolidadoPaqueteria, 'desactivar');

        } else if (opcionSeleccionada === '2') {
            renombrarPestaña(pestaña, 'Paqueteria');
            mostrarTitulo(tituloFormularios);
            cambiarNombreEtiqueta(seccion, 'Paqueteria *');
            ocultarMostarContenedorPestañasEnvioDistribucion('mostrar');
            ocultarMostrarPestaña(pestañaEntrega, formularioEntrega, 'mostrar');
            activarDesactivarPestaña(pestañaEntrega, formularioEntrega, 'desactivar');
            ocultarMostrarPestaña(pestañaConsolidadoPaqueteria, formularioConsolidadoPaqueteria, 'mostrar');
            activarDesactivarPestaña(pestañaConsolidadoPaqueteria, formularioConsolidadoPaqueteria, 'activar');
            cargarLista('#selectListaTipoEnvio' + seccion, informacionServicio, 'paqueteria');

        } else if (opcionSeleccionada === '3') {
            renombrarPestaña(pestaña, 'Consolidado');
            mostrarTitulo(tituloFormularios);
            cambiarNombreEtiqueta(seccion, 'Consolidado *');
            ocultarMostarContenedorPestañasEnvioDistribucion('mostrar');
            ocultarMostrarPestaña(pestañaEntrega, formularioEntrega, 'mostrar');
            activarDesactivarPestaña(pestañaEntrega, formularioEntrega, 'desactivar');
            ocultarMostrarPestaña(pestañaConsolidadoPaqueteria, formularioConsolidadoPaqueteria, 'mostrar');
            activarDesactivarPestaña(pestañaConsolidadoPaqueteria, formularioConsolidadoPaqueteria, 'activar');
            cargarLista('#selectListaTipoEnvio' + seccion, informacionServicio, 'consolidado');
        }

    };

    var renombrarPestaña = function () {
        var pestaña = arguments[0];
        var nombre = arguments[1];

        pestaña.empty().append(nombre);
    };

    var ocultarFormularios = function () {
        var seccion = arguments[0];
        var pestañaConsolidadoPaqueteria = $('[href=#ConsolidadoPaqueteria' + seccion + ']').parent('li');
        var pestañaEntrega = $('[href=#EntregaMaterial' + seccion + ']').parent('li');
        var formularioConsolidadoPaqueteria = $('#ConsolidadoPaqueteria' + seccion);
        var formularioEntrega = $('#EntregaMaterial' + seccion);
        var tituloFormularios = $('#titulo' + seccion);

        if (!tituloFormularios.hasClass('hidden')) {
            tituloFormularios.addClass('hidden');
        }

        if (!pestañaConsolidadoPaqueteria.hasClass('hidden')) {
            pestañaConsolidadoPaqueteria.addClass('hidden');
        }

        if (!pestañaEntrega.hasClass('hidden')) {
            pestañaEntrega.addClass('hidden');
        }

        if (!formularioConsolidadoPaqueteria.hasClass('hidden')) {
            formularioConsolidadoPaqueteria.addClass('hidden');
        }

        if (!formularioEntrega.hasClass('hidden')) {
            formularioEntrega.addClass('hidden');
        }
    };

    var mostrarTitulo = function () {
        var tituloFormularios = arguments[0];

        if (tituloFormularios.hasClass('hidden')) {
            tituloFormularios.removeClass('hidden');
        }
    };

    var cambiarNombreEtiqueta = function () {
        var seccion = arguments[0];
        var nombre = arguments[1];
        $('[for=tipoEnvio' + seccion + ']').empty().append(nombre);
    };

    var ocultarMostarContenedorPestañasEnvioDistribucion = function () {
        var accion = arguments[0];
        var contenedor = $('#contenedorPestañasEnvioDistribucion');

        if (accion === 'mostrar') {
            if (contenedor.hasClass('hidden')) {
                contenedor.removeClass('hidden');
            }
        } else if (accion === 'ocultar') {
            if (!contenedor.hasClass('hidden')) {
                contenedor.addClass('hidden');
            }
        }
    };

    var ocultarMostrarPestaña = function () {
        var pestaña = arguments[0];
        var formulario = arguments[1];
        var evento = arguments[2];

        if (evento === 'mostrar') {
            if (pestaña.hasClass('hidden')) {
                pestaña.removeClass('hidden').addClass('active');
            }

            if (formulario.hasClass('hidden')) {
                formulario.removeClass('hidden');
            }

        } else if (evento === 'ocultar') {
            if (!pestaña.hasClass('hidden')) {
                pestaña.addClass('hidden');
            }

            if (!formulario.hasClass('hidden')) {
                formulario.addClass('hidden');
            }

        }
    };

    var activarDesactivarPestaña = function () {
        var pestaña = arguments[0];
        var formulario = arguments[1];
        var evento = arguments[2];

        if (evento === 'activar') {

            if (!pestaña.hasClass('active')) {
                pestaña.addClass('active');
            }

            if (!formulario.hasClass('active')) {
                formulario.addClass('active in');
            }

        } else if (evento === 'desactivar') {

            if (pestaña.hasClass('active')) {
                pestaña.removeClass('active');
            }

            if (formulario.hasClass('active')) {
                formulario.removeClass('active in');
            }
        }
    };

    var cargarLista = function () {
        var lista = [];
        var elementoSelect = arguments[0];
        var informacionServicio = arguments[1];
        var opcionDeDatos = arguments[2];
        var datos = null;

        if (opcionDeDatos === 'paqueteria') {
            datos = informacionServicio.informacion.ListaPaqueteria;
        } else if (opcionDeDatos === 'consolidado') {
            datos = informacionServicio.informacion.ListaConsolidados;
        }

        $.each(datos, function (key, value) {
            lista.push({id: value.Id, text: value.Nombre});
        });

        select.cargaDatos(elementoSelect, lista);
    };

    var desabilitarFormularioSeccionGenerales = function (datosDelServicio) {
        if (datosDelServicio.IdEstatus === '12') {
            $('#selectCSLogistica').attr('disabled', 'disabled');
            $('#selectTTLogistica').attr('disabled', 'disabled');
            $('#selectTipoOrigen').attr('disabled', 'disabled');
            $('#selectTipoDestino').attr('disabled', 'disabled');
            $('#selectRutaLogistica').attr('disabled', 'disabled');
            $('#btnSeguimientoLogistica').attr('disabled', 'disabled');
            $('#btnAgregarRuta').attr('disabled', 'disabled');
            $('#btnGuardarGenerales').addClass('hidden');
        }
    };

    var mostrarPestañaPorElDeTipoTrafico = function (datosDelServicio, informacionServicio) {
        if (datosDelServicio.IdEstatus === '12') {
            if (informacionServicio.datosTrafico.TipoTrafico !== '0' || informacionServicio.datosTrafico.TipoTrafico !== 'null') {
                select.cambiarOpcion('#selectTTLogistica', informacionServicio.datosTrafico.TipoTrafico);
                if (informacionServicio.datosTrafico.TipoTrafico === '1') {
                    $('[href=#Envio]').parent('li').removeClass('hidden');
                } else if (informacionServicio.datosTrafico.TipoTrafico === '2') {
                    $('[href=#Recoleccion]').parent('li').removeClass('hidden');
                } else if (informacionServicio.datosTrafico.TipoTrafico === '3') {
                    $('#selectTipoDestino').attr('data-parsley-required', 'false').val('').trigger('change').parents('div.row').addClass('hidden');
                    $('[href=#Distribucion]').parent('li').removeClass('hidden');
                }
            }
        } else {
            if (informacionServicio.datosTrafico.TipoTrafico === '3') {
                $('#selectTipoDestino').attr('data-parsley-required', 'false').val('').trigger('change').parents('div.row').addClass('hidden');
            }
            select.cambiarOpcion('#selectTTLogistica', informacionServicio.datosTrafico.TipoTrafico);
        }
    };

    var muestraInformacionFolioServiceDesk = function (datosServiceDesk, folio) {
        var html = '<div class="row">';
        html += '    <div class="col-sm-4 col-md-4">';
        html += '        <div class="form-group">';
        html += '            <label for="seguimientoLogistica">Folio</label>';
        html += '            <br><strong>' + folio + '</strong>';
        html += '        </div> ';
        html += '    </div>';
        html += '    <div class="col-sm-4 col-md-4">';
        html += '        <div class="form-group">';
        html += '            <label for="seguimientoLogistica">Estatus</label>';
        html += '            <br><strong>' + datosServiceDesk.Estatus + '</strong>';
        html += '        </div> ';
        html += '    </div>';
        html += '    <div class="col-sm-4 col-md-4">';
        html += '        <div class="form-group">';
        html += '            <label for="seguimientoLogistica">Prioridad</label>';
        html += '            <br><strong>' + datosServiceDesk.Prioridad + '</strong>';
        html += '        </div> ';
        html += '    </div>';
        html += '</div>';
        html += '<div class="row">';
        html += '    <div class="col-sm-4 col-md-4">';
        html += '        <div class="form-group">';
        html += '            <label for="seguimientoLogistica">Creador</label>';
        html += '            <br><strong>' + datosServiceDesk.CreadoPor + '</strong>';
        html += '        </div> ';
        html += '    </div>';
        html += '    <div class="col-sm-4 col-md-4">';
        html += '        <div class="form-group">';
        html += '            <label for="seguimientoLogistica">Tecnico Asignado</label>';
        html += '            <br><strong>' + datosServiceDesk.TecnicoAsignado + '</strong>';
        html += '        </div> ';
        html += '    </div>';
        html += '    <div class="col-sm-4 col-md-4">';
        html += '        <div class="form-group">';
        html += '            <label for="seguimientoLogistica">Fecha Creación </label>';
        html += '            <br><strong>' + datosServiceDesk.FechaCreacion + '</strong>';
        html += '        </div> ';
        html += '    </div>';
        html += '</div>';
        html += '<div class="row">';
        html += '    <div class="col-md-12">';
        html += '        <div class="form-group">';
        html += '            <label for="seguimientoLogistica">Asunto</label>';
        html += '            <br><strong>' + datosServiceDesk.Asunto + '</strong>';
        html += '        </div> ';
        html += '    </div>';
        html += '    <div class="col-md-12">';
        html += '        <div class="form-group">';
        html += '            <label for="seguimientoLogistica">Solicitud</label>';
        html += '            <br><strong>' + datosServiceDesk.Solicitud + '</strong>';
        html += '        </div> ';
        html += '    </div>';
        html += '    <div class="col-md-12">';
        html += '        <div class="form-group">';
        html += '            <label for="seguimientoLogistica">Resolución</label>';
        html += '            <br><strong>' + datosServiceDesk.Resolucion + '</strong>';
        html += '        </div> ';
        html += '    </div>';
        html += '</div>';
        html += '';
        $('#btnModalConfirmar').addClass('hidden');
        evento.mostrarModal('Información Service Desk', html);
        $('#btnModalAbortar').empty().append('Regresar');
    };

    var mostrarSeccionParaGenerarDestinoDistribucion = function () {
        var seccionFormularioRecoleccion = $('#seccionFormularioRecoleccion');
        var seccionformularioDestinoDistribucion = $('#seccionFormularioGenerarDestino');

        if (seccionformularioDestinoDistribucion.hasClass('hidden')) {
            cambiarTituloSeccionDistribucion('Nuevo Destino');
            mostrarOcultarBotonRegresarTablaDestinos('mostrar');
            mostrarOcultarBotonAgregarDestino('ocultar');
            mostrarOcultarTablaDestinosDistribucion('ocultar');
            seccionformularioDestinoDistribucion.removeClass('hidden');
            if (!seccionFormularioRecoleccion.hasClass('hidden')) {
                seccionFormularioRecoleccion.empty().addClass('hidden');
            }
        }
    };

    var mostrarSeccionRecoleccionEquipos = function () {
        var formularioParaRecoleccion = $('#seccionFormularioRecoleccion');

        formularioParaRecoleccion.removeClass('hidden');
        cambiarTituloSeccionDistribucion('Recolección');
        mostrarOcultarBotonRegresarTablaDestinos('mostrar');
        mostrarOcultarBotonAgregarDestino('ocultar');
        mostrarOcultarTablaDestinosDistribucion('ocultar');
    };

    var cambiarTituloSeccionDistribucion = function (titulo) {
        var tituloSeccionDistribucion = $('#tituloTablaDistribucion');
        tituloSeccionDistribucion.find('strong').empty().append(titulo);
    };

    var mostrarOcultarBotonRegresarTablaDestinos = function () {
        var evento = arguments[0];
        var boton = $('#btnRegresarTablaDestinos');

        if (evento === 'mostrar') {
            boton.removeClass('hidden');
        } else if (evento === 'ocultar') {
            boton.addClass('hidden');
        }
    };

    var mostrarOcultarBotonAgregarDestino = function () {
        var evento = arguments[0];
        var boton = $('#btnGenerarDestinoDistribucion');

        if (evento === 'mostrar') {
            boton.removeClass('hidden');
        } else if (evento === 'ocultar') {
            boton.addClass('hidden');
        }
    };

    var mostrarOcultarTablaDestinosDistribucion = function () {
        var evento = arguments[0];
        var seccion = $('#seccionTablaDestinosDistribucion');

        if (evento === 'mostrar') {
            seccion.removeClass('hidden');
        } else if (evento === 'ocultar') {
            seccion.addClass('hidden');

        }
    };

    var limipiandoFormularioPaqueteriaConsolidado = function () {
        select.cambiarOpcion('#selectListaTipoEnvio', '');
        $('#inputDatoGuia').val('');
        $('#inputComentariosEnvio').val('');
        file.limpiar('#evidenciaEnvio');
    };

    var actulizarSelectMaterialDistribucion = function (material) {
        var elementosSelect = [];
        $.each(material, function (key, value) {
            elementosSelect.push({id: value.Nombre, text: value.Nombre});
        });
        select.cargaDatos('#selectMaterialDistribuir', elementosSelect);
    };

    var restablecerFormularioRecoleccionDistribucion = function () {
        limpiarFormularioRecoleccionDistribucion();
        ocultarSeccionRecoleccionEquipos();
    };

    var restablecerFormularioDestinoDistribucion = function () {
        limpiarFormulariosDestinoDistribucion();
        mostrarOcultarFormulariosDestinoDistribucion('ocultar');
    };

    var restablecerSeccionEnvioDistribucion = function () {
        ocultarMostarContenedorPestañasEnvioDistribucion('ocultar');
        ocultarSeccionEnvioDistribucion();
        limpiarFormularioEnvioDistribucion();
    };

    var mostrarTablaDestinosDistribucion = function () {
        var tablaEquiposDistribucion = $('#tablaDistribucion');
        tablaEquiposDistribucion.removeClass('hidden');
    };

    var ocultarSeccionRecoleccionEquipos = function () {
        var formularioParaRecoleccion = $('#seccionFormularioRecoleccion');
        if (!formularioParaRecoleccion.hasClass('hidden')) {
            formularioParaRecoleccion.addClass('hidden');
        }
    };

    var mostrarOcultarFormulariosDestinoDistribucion = function () {
        var evento = arguments[0];
        var seccionFormulariosNuevoDestino = $('#seccionFormularioGenerarDestino');
        var formularioMaterial = $('#seccionDefinirMaterial');
        var tablaMaterial = $('#secctionTablaMaterialDestino');
        var botonGuardar = $('#btnGuardarDestinoDistribucion');
        var botonAgregarMaterial = $('#btnAgregarMaterialDistribucion');
        var contenedorCantidadMaterial = $('#contenedorCantidadDistribucion');

        if (evento === 'mostrar') {
            seccionFormulariosNuevoDestino.removeClass('hidden');
        } else if (evento === 'ocultar') {

            seccionFormulariosNuevoDestino.addClass('hidden');
            botonGuardar.removeClass('hidden');
            botonAgregarMaterial.addClass('hidden');

            if (!formularioMaterial.hasClass('hidden')) {
                formularioMaterial.addClass('hidden');
            }

            if (!tablaMaterial.hasClass('hidden')) {
                tablaMaterial.addClass('hidden');
            }

            if (contenedorCantidadMaterial.hasClass('hidden')) {
                contenedorCantidadMaterial.removeClass('hidden');
            }
        }

    };

    var limpiarFormularioRecoleccionDistribucion = function () {
        $('#fechaRecoleccionDistribucion').val('');
        $('#inputEntregaRecoleccionDistricion').val('');
        $('#textareaObservacionesRecoleccionDistribucion').val('');
        file.limpiar('#evidenciaRecoleccionDistribucion');
    };

    var limpiarFormulariosDestinoDistribucion = function () {
        var selectTipoDestino = $('#selectDestinoDistribucion');
        var inputCantidadMaterial = $('#cantidadMaterial');

        evento.limpiarFormulario('#formDestinoDistribucion');
        limpiarFormularioMaterialDestinoDistribucion();
        tabla.limpiarTabla('#data-table-equipos-distribucion');

        if (selectTipoDestino.attr('disabled')) {
            selectTipoDestino.removeAttr('disabled');
        }

        if (!inputCantidadMaterial.attr('data-parsley-required')) {
            inputCantidadMaterial.attr('data-parsley-required', 'true');
        }
    };

    var limpiarFormularioMaterialDestinoDistribucion = function () {
        evento.limpiarFormulario('#formMaterialDestinoDistribucion');
    };

    var ocultarBotonesGuardarYDescargarEquipos = function () {
        $('#btnGuardarMaterialTrafico').addClass('hidden');
        $('#btnDescargarFormato').addClass('hidden');
        $('#btnSubirFormato').addClass('hidden');
        $('#tituloFormulariosMaterial').addClass('hidden');
        $('#formulariosMaterial').addClass('hidden');
    };

    var habilitaMaterialDistribucion = function () {
        $('#selectMaterialDistribuir').removeAttr('disabled');
    };

    var desahabilitaMaterialDistribucion = function () {
        $('#selectMaterialDistribuir').attr('disabled', 'disabled');
    };

    var habilitaCantidadDistribucion = function () {
        $('#cantidadMaterial').removeAttr('disabled');
    };

    var desahabilitaCantidadDistribucion = function () {
        $('#cantidadMaterial').attr('disabled', 'disabled');
    };

    var validarFormulario = function () {
        var objetoSelectMaterial = arguments[0];
        var inputCantidad = arguments[1];
        var modeloEquipo = $(objetoSelectMaterial).val();
        var cantidad = $(inputCantidad).val();

        if (modeloEquipo !== '' && cantidad !== '') {
            return validarMaterialEnTabla(objetoSelectMaterial);
        } else {
            evento.mostrarMensaje('.errorAgregar', false, 'Debes llenar todos los campos para poder agregar el material.', 3000);
        }
    };

    var validarMaterialEnTabla = function () {
        var filas = $('#data-table-servicio-materiales').DataTable().rows().data();
        var nombreMaterial = $.trim($(arguments[0].concat(' option:selected')).text());
        var repetido = false;

        if (filas.length > 0) {
            for (var i = 0; i < filas.length; i++) {
                if ($.trim(filas[i][0]) === nombreMaterial) {
                    repetido = true;
                }
            }
        }

        if (!repetido) {
            return true;
        } else {
            evento.mostrarMensaje('.errorAgregar', false, 'Ya se agrego este material favor de eliminar el que esta registrado si quiere actualizarlo', 3000);
        }
    };

    var agregandoEquipo = function () {
        var cantidad = $('#inputCantidadEquipo').val();
        var modeloEquipo = $('#selectEquipo').val();
        var nombreEquipo = $('#selectEquipo option:selected').text();
        var series = $('#inputNumeroSerieTags').tagit("assignedTags");
        var filas = [];
        var contador = 1;

        if (series.length > 0) {
            for (var i = 0; i < cantidad; i++) {
                if (typeof series[i] !== 'undefined') {
                    filas.push([nombreEquipo, series[i], '1', '1', modeloEquipo]);
                } else {
                    filas.push([nombreEquipo, 'sin serie ' + contador, '1', '1', modeloEquipo]);
                    contador++;
                }
            }
        } else {
            for (var i = 0; i < cantidad; i++) {
                filas.push([nombreEquipo, 'sin serie ' + contador, '1', '1', modeloEquipo]);
                contador++;
            }
        }

        $.each(filas, function (key, value) {
            tabla.agregarFila('#data-table-servicio-materiales', value);
        });

    };

    var agregandoMaterialYOtros = function () {
        var objetoTipoMaterial = arguments[0];
        var objetoCantidadMaterial = arguments[1];
        var modeloEquipo = $(objetoTipoMaterial).val();
        var nombreEquipo = $.trim($(objetoTipoMaterial.concat(' option:selected')).text());
        var cantidad = $(objetoCantidadMaterial).val();
        var tipoEquipoTrafico = '5';

        if (nombreEquipo === '') {
            tipoEquipoTrafico = '4';
            nombreEquipo = modeloEquipo.toUpperCase();
        }
        tabla.agregarFila('#data-table-servicio-materiales', [nombreEquipo, '', cantidad, tipoEquipoTrafico, modeloEquipo]);
    };

    var limpiarFormularioMaterial = function () {
        select.cambiarOpcion('#selectEquipo', '');
        select.cambiarOpcion('#selectMaterial', '');
        $('#inputOtro').val('');
        $('#inputCantidadEquipo').val('');
        $('#inputCantidadMaterial').val('');
        $('#inputCantidadOtro').val('');
        $('#inputNumeroSerieTags').tagit('removeAll');
        $('#inputFiltraEquipos').val('');
        $('#inputFiltraMaterialHerramientas').val('');

    };

    var confirmacionParaGuardarRecoleccionDistribucion = function () {
        var respuesta = arguments[0];
        var datos = arguments[1];
        var mensaje = '<div class="row">\n\
                            <div class="col-md-12 text-center">\n\
                                <p>Al guardar la recolección ya no podras agregar material.</p>\n\
                                <p>¿Estas seguro de querer guardarla?</p>\n\
                            </div>\n\
                        </div>\n\
                        <div class="row">\n\
                            <div class="col-md-12 text-center">\n\
                                <button type="button" class="btn btn-sm btn-success" id="btnAceptarGuardarRecoleccionDistribucion">Aceptar</button>\n\
                                <button type="button" class="btn btn-sm btn-danger" id="btnCancelarGuardarRecoleccionDistribucion">Cancelar</button>\n\
                            </div>\n\
                        </div> ';

        evento.mostrarModal('Guardar Recolección', mensaje);
        $('#btnModalConfirmar').addClass('hidden');
        $('#btnModalAbortar').addClass('hidden');

        $('#btnAceptarGuardarRecoleccionDistribucion').on('click', function () {
            guardarRecoleccionDistribucion(respuesta, datos);
        });

        $('#btnCancelarGuardarRecoleccionDistribucion').on('click', function () {
            evento.cerrarModal();
        });
    };

    var guardarRecoleccionDistribucion = function () {
        var respuesta = arguments[0];
        var datos = arguments[1];

        file.enviarArchivos('#evidenciaRecoleccionDistribucion', 'Seguimiento/Generar_Recoleccion_Distribucion', '#modal-dialogo', datos, function (data) {
            if (data) {
                limpiarFormularioRecoleccionDistribucion();
                ocultarSeccionRecoleccionEquipos();
                mostrarSeccionParaGenerarDestinoDistribucion();
                respuesta.informacion.datosRecoleccion = data;
                ocultarBotonesGuardarYDescargarEquipos();
                mensajeExito();
            } else {
                evento.mostrarMensaje('#errorGeneralRecoleccionDistribucion', false, 'No se pudo guardar la recolección, vuelva a intentarlo porfavor', 3000);
            }
        });
    };

    var mensajeExito = function () {
        var mensaje = '<div class="row">\n\
                            <div class="col-md-12 text-center">\n\
                                <p>Se guardo con exito la información.</p>\n\
                            </div>\n\
                        </div>';

        $('.modal-body').empty().append(mensaje);
        $('#btnModalAbortar').removeClass('hidden').empty().append('Cerrar');
    };

    var calcularEquiposQueFaltanDistribuir = function () {
        var material = arguments[0];
        var materialFaltante = arguments[1];
        var ultimoIndex = null;

        if (material !== null) {
            if (materialFaltante.length > 0) {
                ultimoIndex = agregandoMaterialAVaribleGlobal(materialFaltante);
                agregandoEquiposAVaribleGlobal(materialFaltante, ultimoIndex);
                guardarMaterialFaltanteListaVieja();
            } else {
                ultimoIndex = agregandoMaterialAVaribleGlobal(material);
                agregandoEquiposAVaribleGlobal(material, ultimoIndex);
                guardarMaterialFaltanteListaVieja();
            }
        }
    };

    var agregandoMaterialAVaribleGlobal = function () {
        var materiales = arguments[0];
        var contador = 0;

        $.each(materiales, function (key, value) {
            if (value.IdTipoEquipo !== '1') {
                materialFaltanteDistribucion.push(
                        {
                            id: contador.toString(),
                            text: value.Nombre,
                            Modelo: value.IdModelo,
                            TipoEquipo: value.IdTipoEquipo,
                            datos: null,
                            Cantidad: parseInt(value.Cantidad)
                        });
                contador++;
            }
        });

        return contador;
    };

    var agregandoEquiposAVaribleGlobal = function () {
        var materiales = arguments[0];
        var ultimoIndex = arguments[1];
        var nombreEquipos = [];
        var equipos = [];
        var contador = 0;

        $.each(materiales, function (key, material) {
            if (material.IdTipoEquipo === '1') {
                if ($.inArray(material.Nombre, nombreEquipos) === -1) {
                    contador = 0;
                    nombreEquipos.push(material.Nombre);
                    equipos.push(
                            {
                                id: ultimoIndex.toString(),
                                text: material.Nombre,
                                Modelo: material.IdModelo,
                                TipoEquipo: material.IdTipoEquipo,
                                datos: [
                                    {
                                        id: contador,
                                        Cantidad: material.Cantidad,
                                        text: material.Serie
                                    }
                                ],
                                Cantidad: null
                            });
                    ultimoIndex++;
                } else {
                    $.each(equipos, function (key, equipo) {
                        if (equipo.text === material.Nombre) {
                            equipo.datos.push({
                                id: ++contador,
                                Cantidad: material.Cantidad,
                                text: material.Serie
                            });
                        }
                    });
                }
            }
        });

        $.each(equipos, function (key, material) {
            materialFaltanteDistribucion.push(material);
        });

    };

    var obtenerDatosDeMaterial = function () {
        var materialSeleccionado = arguments[0];
        var indice = null;

        $.each(materialFaltanteDistribucion, function (key, value) {
            if (value.id === materialSeleccionado) {
                indice = key;
            }
        });

        return materialFaltanteDistribucion[indice];
    };

    var crearNuevoDestino = function () {
        var servicio = arguments[0];
        var datos = {servicio: servicio, tipoDestino: $('#selectDestinoDistribucion').val(), destino: $('#distribucion').val()};

        if (materialFaltanteDistribucion.length > 0) {
            evento.enviarEvento('Seguimiento/Generar_Destino_Distribucion', datos, '#seccion-datos-logistica', function (respuesta) {
                if (respuesta) {
                    numeroDestino = respuesta.identificadorDestino;
                    bloquearFormalarioDestino();
                    actualizarTablaDestinosDistribuciones(respuesta.listaDestinos);
                    mostrarFormularioMaterialDestino();
                } else {
                    evento.mostrarMensaje('#errorAgregarMaterialDistribucion', false, 'No se puede guardar el destino favor de volver a intentarlo.', 3000);
                }
            });
        } else {
            evento.mostrarMensaje('#errorAgregarMaterialDistribucion', false, 'No se puede crear un nuevo destino ya que todo el material ya fue asignado.', 3000);
        }
    };

    var bloquearFormalarioDestino = function () {
        $('#selectDestinoDistribucion').attr('disabled', 'disabled');
        $('#distribucion').attr('disabled', 'disabled');
        $('#btnGuardarDestinoDistribucion').addClass('hidden');
    };

    var actualizarTablaDestinosDistribuciones = function () {
        var listaDestinos = arguments[0];
        tabla.limpiarTabla('#data-table-distribucion');
        $.each(listaDestinos, function (key, value) {
            tabla.agregarFila('#data-table-distribucion', [value.Id, value.TipoDestino, value.NombreDestino, value.Estatus]);
        });

    };

    var mostrarFormularioMaterialDestino = function () {
        $('#btnAgregarMaterialDistribucion').removeClass('hidden');
        $('#seccionDefinirMaterial').removeClass('hidden');
        $('#secctionTablaMaterialDestino').removeClass('hidden');
    };

    var obtenerTipoMaterialParaAgregar = function () {
        var datosMaterial = arguments[0];
        var contenedorSelectSerieMaterial = $('#contenedorSeriesDistribucion');
        var cantidad = $('#cantidadMaterial').val();

        if (!contenedorSelectSerieMaterial.hasClass('hidden')) {
            return 'Equipo';
        } else {
            if (cantidad <= datosMaterial.Cantidad) {
                return 'Material';
            } else {
                throw new Error('La cantidad ingresada excede de la cantidad definida que es de <strong>' + datosMaterial.Cantidad + '</strong>. Favor de corregirla');
            }
        }
    };

    var agregarMaterialATablaDistribucion = function () {
        var tipoMaterial = arguments[0];
        var datosMaterial = arguments[1];
        var nombreMaterial = $('#selectMaterialDistribuir option:selected').text();
        var series = $('#selectSerieMaterialDistribucion').val();
        var cantidad = $('#cantidadMaterial').val();
        var nombreDestino = null;
        var tipoDestino = $('#selectDestinoDistribucion').val();
        var destino = $('#distribucion').val();

        if (tipoDestino !== '3') {
            nombreDestino = $('#distribucion option:selected').text();
        } else {
            nombreDestino = destino;
        }

        if (tipoMaterial === 'Equipo') {
            $.each(series, function (key, serie) {
                $.each(datosMaterial.datos, function (key, elemento) {
                    if (elemento.id === parseInt(serie)) {
                        tabla.agregarFila('#data-table-equipos-distribucion', [
                            nombreMaterial,
                            elemento.text,
                            '1',
                            datosMaterial.TipoEquipo,
                            datosMaterial.Modelo
                        ]);
                    }
                });
            });
        } else if (tipoMaterial === 'Material') {
            tabla.agregarFila('#data-table-equipos-distribucion', [
                nombreMaterial,
                ' ',
                cantidad,
                datosMaterial.TipoEquipo,
                datosMaterial.Modelo
            ]);
        }
    };

    var actualizarMaterialFaltanteDistribucion = function () {
        var material = arguments[0];
        var tipo = arguments[1];
        var materialSeleccionado = arguments[2];
        var cantidad = $('#cantidadMaterial').val();
        var indice = obtenerIndice(materialSeleccionado);

        if (tipo === 'Equipo') {
            actulizarListaEquipos(material.datos, indice);
            eliminarMaterialDeLista(material.datos.length, indice);
        } else if (tipo === 'Material') {
            material.Cantidad = material.Cantidad - cantidad;
            eliminarMaterialDeLista(material.Cantidad, indice);
        }
    };

    var obtenerIndice = function () {
        var materialSeleccionado = arguments[0];
        var indice = null;

        $.each(materialFaltanteDistribucion, function (key, elementoLista) {
            $.each(materialFaltanteDistribucion, function (key, value) {
                if (value.id === materialSeleccionado) {
                    indice = key;
                }
            });
        });

        return indice;
    };

    var actulizarListaEquipos = function () {
        var equipos = arguments[0];
        var indice = arguments[1];
        var seriesEquipos = $('#selectSerieMaterialDistribucion option:selected');
        var lista = [];
        var listaSeries = [];

        seriesEquipos.each(function (key, value) {
            lista.push($(value).text());
        });

        $.each(equipos, function (key, equipo) {
            if ($.inArray(equipo.text, lista) === -1) {
                listaSeries.push({id: equipo.id, text: equipo.text, Cantidad: equipo.Cantidad});
            }
        });

        materialFaltanteDistribucion[indice].datos = null;
        materialFaltanteDistribucion[indice].datos = listaSeries;
    };

    var eliminarMaterialDeLista = function () {
        var cantidadMaterialDisponible = arguments[0];
        var indice = arguments[1];

        if (cantidadMaterialDisponible === 0) {
            materialFaltanteDistribucion.splice(indice, 1);
        }
    };

    var actualizarSelectMaterialDistribucion = function () {
        $('#selectMaterialDistribuir').empty();
        select.cargaDatos('#selectMaterialDistribuir', materialFaltanteDistribucion);
        $('#selectMaterialDistribuir').val('').trigger('change');
    };

    var guardarMaterialFaltanteListaVieja = function () {
        $.each(materialFaltanteDistribucion, function (key, value) {
            if (typeof value.Cantidad !== 'undefined') {
                materialFaltanteDistribucionListaVieja.push({
                    id: value.id,
                    text: value.text,
                    Modelo: value.Modelo,
                    TipoEquipo: value.TipoEquipo,
                    Cantidad: value.Cantidad,
                    datos: value.datos
                });
            } else {
                materialFaltanteDistribucionListaVieja.push({
                    id: value.id,
                    text: value.text,
                    Modelo: value.Modelo,
                    TipoEquipo: value.TipoEquipo,
                    datos: value.datos
                });
            }
        });
    };

    var limpiarMaterialFaltanteListaVieja = function () {
        materialFaltanteDistribucionListaVieja = [];
    };

    var restablecerMaterialFaltanteDistribucion = function () {
        materialFaltanteDistribucion = [];

        $.each(materialFaltanteDistribucionListaVieja, function (key, value) {
            if (typeof value.Cantidad !== 'undefined') {
                materialFaltanteDistribucion.push({
                    id: value.id,
                    text: value.text,
                    Modelo: value.Modelo,
                    TipoEquipo: value.TipoEquipo,
                    Cantidad: value.Cantidad,
                    datos: value.datos
                });
            } else {
                materialFaltanteDistribucion.push({
                    id: value.id,
                    text: value.text,
                    Modelo: value.Modelo,
                    TipoEquipo: value.TipoEquipo,
                    datos: value.datos
                });
            }
        });
    };

    var guardarMaterialParaDestinoDistribucion = function () {
        var servicio = arguments[0];
        var filas = $('#data-table-equipos-distribucion').DataTable().rows().data();
        var datos = null;

        if (filas.length > 0) {
            datos = {servicio: servicio, identificadorDestino: numeroDestino};
            datos.material = generarListaMaterialParaDestino(filas);
            evento.enviarEvento('Seguimiento/Generar_Material_Destino_Distribucion', datos, '#seccion-datos-logistica', function (respuesta) {

                var html = '<div class="row">\n\
                            <div id="mensaje-modal" class="col-md-12 text-center">\n\
                                <p>Se guardo con exito el material para el destino</p>\n\
                            </div>\n\
                      </div>';
                evento.mostrarModal('Alta de Material para Destino', html);
                $('#btnModalConfirmar').addClass('hidden');
                $('#btnModalAbortar').empty().append('Cerrar');

                $('#btnModalConfirmar').off('click');
                $('#btnModalAbortar').off('click');

                $('#btnModalAbortar').on('click', function () {
                    cambiarTituloSeccionDistribucion('Destinos');
                    mostrarOcultarBotonRegresarTablaDestinos('ocultar');
                    mostrarOcultarBotonAgregarDestino('mostrar');
                    restablecerFormularioRecoleccionDistribucion();
                    restablecerFormularioDestinoDistribucion();
                    restablecerSeccionEnvioDistribucion();
                    mostrarOcultarTablaDestinosDistribucion('mostrar');
                    limpiarMaterialFaltanteListaVieja();
                });
            });
        } else {
            throw new Error('Debes definir al menos un material para poder guardar el destino.');
        }

    };

    var regresarMaterialAVariableMaterialFaltante = function () {
        var datosFilaSeleccionada = arguments[0];
        var datosParaOperacion = {};

        datosParaOperacion.serieMaterialSeleccionado = datosFilaSeleccionada[1];
        datosParaOperacion.cantidadMaterialSeleccionado = datosFilaSeleccionada[2];
        datosParaOperacion.tipoEquipoMaterialSeleccionado = datosFilaSeleccionada[4];
        datosParaOperacion.modeloMaterialSeleccionado = datosFilaSeleccionada[5];

        datosParaOperacion.materialListaVieja = obtenerMaterialDeLaListaVieja(datosParaOperacion);
        datosParaOperacion.materialListaFaltante = validarMaterialEnListaFaltante(datosParaOperacion);

        if (datosParaOperacion.materialListaFaltante.existe) {
            restablecerMaterialExistenteAListaFaltante(datosParaOperacion);
        } else {
            agregarMaterialAListaFaltante(datosParaOperacion);
        }
    };

    var obtenerMaterialDeLaListaVieja = function () {
        var datoMaterialSeleccionado = arguments[0];
        var material = {};

        $.each(materialFaltanteDistribucionListaVieja, function (key, value) {
            if (datoMaterialSeleccionado.tipoEquipoMaterialSeleccionado === value.TipoEquipo && datoMaterialSeleccionado.modeloMaterialSeleccionado === value.Modelo) {
                material.id = value.id;
                material.text = value.text;
                material.Modelo = value.Modelo;
                material.TipoEquipo = value.TipoEquipo;
                material.datos = value.datos;
                material.Cantidad = value.Cantidad;
            }
        });

        return material;
    };

    var validarMaterialEnListaFaltante = function () {
        var datoMaterialSeleccionado = arguments[0];
        var material = {};

        material.existe = false;

        $.each(materialFaltanteDistribucion, function (key, value) {
            if (datoMaterialSeleccionado.tipoEquipoMaterialSeleccionado === value.TipoEquipo && datoMaterialSeleccionado.modeloMaterialSeleccionado === value.Modelo) {
                material.indice = key;
                material.existe = true;
            }
        });

        return material;
    };

    var restablecerMaterialExistenteAListaFaltante = function () {
        var material = {};
        var datoMaterialSeleccionado = arguments[0];

        if (datoMaterialSeleccionado.tipoEquipoMaterialSeleccionado === '1') {
            $.each(datoMaterialSeleccionado.materialListaVieja.datos, function (key, value) {
                if (datoMaterialSeleccionado.serieMaterialSeleccionado === value.text) {
                    material.id = value.id;
                    material.text = value.text;
                    material.Cantidad = 1;
                }
            });
            materialFaltanteDistribucion[datoMaterialSeleccionado.materialListaFaltante.indice].datos.push(material);
        } else {
            materialFaltanteDistribucion[datoMaterialSeleccionado.materialListaFaltante.indice].Cantidad = materialFaltanteDistribucion[datoMaterialSeleccionado.materialListaFaltante.indice].Cantidad + parseInt(datoMaterialSeleccionado.cantidadMaterialSeleccionado);
        }
    };

    var agregarMaterialAListaFaltante = function () {
        var material = [];
        var datoMaterialSeleccionado = arguments[0];

        if (datoMaterialSeleccionado.tipoEquipoMaterialSeleccionado === '1') {
            material = [];
            $.each(datoMaterialSeleccionado.materialListaVieja.datos, function (key, value) {
                if (datoMaterialSeleccionado.serieMaterialSeleccionado === value.text) {
                    material.push({
                        id: value.id,
                        text: value.text,
                        Cantidad: 1
                    });
                }
            });
            datoMaterialSeleccionado.materialListaVieja.datos = material;
        } else {
            if (datoMaterialSeleccionado.materialListaVieja.Cantidad > parseInt(datoMaterialSeleccionado.cantidadMaterialSeleccionado)) {
                datoMaterialSeleccionado.materialListaVieja.Cantidad = parseInt(datoMaterialSeleccionado.cantidadMaterialSeleccionado);
            }
        }
        materialFaltanteDistribucion.push(datoMaterialSeleccionado.materialListaVieja);
    };

    var generarListaMaterialParaDestino = function () {
        var material = arguments[0];
        var lista = [];

        $.each(material, function (key, value) {
            lista.push({
                material: value[0],
                serie: value[1],
                cantidad: value[2],
                tipoEquipo: value[3],
                modelo: value[4]
            });
        });

        return lista;

    };

    var mostrarSeccionEnvioDistribucion = function () {
        $('#seccionEnvioDistribucion').removeClass('hidden');
    };

    var ocultarSeccionEnvioDistribucion = function () {
        $('#seccionEnvioDistribucion').addClass('hidden');
    };

    var limpiarFormularioEnvioDistribucion = function () {
        $('#fechaEnvioDistribucion').val();
        $('#selectTipoEnvioDistribucion').val('').trigger('change');
        $('#selectListaTipoEnvioDistribucion').val('').trigger('change');
        $('#inputDatoGuiaDistribucion').val('');
        $('#inputComentariosEnvioDistribucion').val('');
        file.destruir('#evidenciaEnvioDistribucion');
        $('#entregaFechaEnvioDistribucion').val('');
        $('#inputRecibeEnvioDistribucion').val('');
        $('#inputComentarioEntregaEnvioDistribucion').val('');
        file.destruir('#evidenciaEntregaEnvioDistribucion');

    };

    var guardarCambiosEnvioMaterial = function () {
        var data = arguments[0];
        evento.enviarEvento('Seguimiento/Actualizar_Envio', data, '#seccion-datos-logistica', function (respuesta) {
            var html;
            if (respuesta) {
                html = '<div class="row">\n\
                                <div class="col-md-12 text-center">\n\
                                    Se guardo la información con exito\n\
                                </div>\n\
                            </div>';
                evento.mostrarModal('Información de envio', html);
                $('#btnModalConfirmar').addClass('hidden');
                $('#btnModalAbortar').empty().append('Cerrar');

                if ($('#selectTipoEnvio').val() === '1') {
                    limipiandoFormularioPaqueteriaConsolidado();
                }

            } else {
                html = '<div class="row">\n\
                                <div class="col-md-12 text-center">\n\
                                    No se pudo guardar la información, por favor de volver a intentarlo.\n\
                                </div>\n\
                            </div>';
                evento.mostrarModal('Información de envio', html);
                $('#btnModalConfirmar').addClass('hidden');
                $('#btnModalAbortar').empty().append('Cerrar');
            }
        });
    };

    var filtrarListaDeSelect = function () {
        var hijosSelect = $(arguments[0]).children('option');
        var valorABuscar = arguments[1].toUpperCase();

        $.each(hijosSelect, function (key, hijoOption) {
            $(hijoOption).removeClass('hidden');
            if (valorABuscar !== '') {
                agregarPropiedadHidden($(hijoOption), {valorABuscar: valorABuscar, texto: $(hijoOption).text()});
            } else {
                removerPropiedadHidden($(hijoOption));
            }
        });
    };

    var agregarPropiedadHidden = function () {
        var hijoOption = arguments[0];
        var dato = arguments[1];

        if (dato.texto.indexOf(dato.valorABuscar) === -1) {
            if (!hijoOption.hasClass('hidden')) {
                hijoOption.addClass('hidden');
            }
        }
    };

    var removerPropiedadHidden = function () {
        var elemento = arguments[0];

        if (elemento.hasClass('hidden')) {
            elemento.removeClass('hidden');
        }
    };

    var generarDatosEnvioParaGuardarCambios = function () {
        var servicio = arguments[0];
        var seccion = (typeof arguments[1] !== 'undefined') ? arguments[1] : '';
        var tipoEnvio = $('#selectTipoEnvio' + seccion).val();

        if (tipoEnvio === '1') {
            var data = {
                servicio: servicio,
                tipoenvio: $('#selectTipoEnvio' + seccion).val(),
                idPaqueteria: '',
                fechaEnvio: $('#fechaEnvio' + seccion).val(),
                guia: '',
                comentariosEnvio: '',
                fechaEntrega: $('#entregaFechaEnvio' + seccion).val(),
                nombreRecibe: $('#inputRecibeEnvio' + seccion).val(),
                comentariosEntrega: $('#inputComentarioEntregaEnvio' + seccion).val(),
                seccion: seccion
            };

        } else if (tipoEnvio === '2' || tipoEnvio === '3') {
            var data = {
                servicio: servicio,
                tipoenvio: $('#selectTipoEnvio' + seccion).val(),
                idPaqueteria: $('#selectListaTipoEnvio' + seccion).val(),
                fechaEnvio: $('#fechaEnvio' + seccion).val(),
                guia: $('#inputDatoGuia' + seccion).val(),
                comentariosEnvio: $('#inputComentariosEnvio' + seccion).val(),
                fechaEntrega: $('#entregaFechaEnvio' + seccion).val(),
                nombreRecibe: $('#inputRecibeEnvio' + seccion).val(),
                comentariosEntrega: $('#inputComentarioEntregaEnvio' + seccion).val(),
                seccion: seccion
            };
        } else {
            throw new Error('Debes definir como se envia.');
        }

        return data;
    };

    var guardarCambiosRecoleccion = function () {
        var servicio = arguments[0];
        var datosRecoleccion = null;

        try {
            datosRecoleccion = verificarDatosCambiosRecoleccion(servicio, 'guardarCambios');
            evento.enviarEvento('Seguimiento/Actualizar_Recoleccion', datosRecoleccion, '#seccion-datos-logistica', function (respuesta) {
                if (respuesta) {
                    evento.mostrarMensaje('#errorGeneralRecoleccion', true, 'Datos actualizados correctamente.', 3000);
                } else {
                    evento.mostrarMensaje('#errorGeneralRecoleccion', false, 'No se pudo Actualizar los datos.', 3000);
                }
            });
        } catch (exception) {
            throw new Error(exception.message);
        }

    };

    var verificarDatosCambiosRecoleccion = function () {
        var servicio = arguments[0];
        var tipoValidacion = arguments[1];
        var fechaRecoleccion = $('#fechaRecoleccion').val();
        var entregaRecoleccion = $('#inputEntregaRecoleccion').val();
        var observacionesRecoleccion = $('#textareaObservacionesRecoleccion').val();
        var datosRecoleccion = {servicio: servicio, fecha: fechaRecoleccion, entrega: entregaRecoleccion, observaciones: observacionesRecoleccion};

        if (tipoValidacion === 'concluir') {
            if (fechaRecoleccion !== '' && entregaRecoleccion !== '' && observacionesRecoleccion !== '') {
                return datosRecoleccion;
            } else {
                throw new Error('Debes llenar todos los campos requeridos de la sección recoleccion.');
            }
        } else if (tipoValidacion === 'guardarCambios') {
            if (fechaRecoleccion !== '' || entregaRecoleccion !== '' || observacionesRecoleccion !== '') {
                return datosRecoleccion;
            } else {
                throw new Error('Debes al menos un campo requerido de la sección recoleccion.');
            }
        }
    };

    var cargarInformacionDelDestino = function () {
        var informacionDestino = arguments[0];

        $('#fechaEnvioDistribucion').val(informacionDestino.fechaEnvio);
        $('#selectTipoEnvioDistribucion').val(informacionDestino.tipoEnvio).trigger('change');
        $('#selectListaTipoEnvioDistribucion').val(informacionDestino.paqueteria).trigger('change');
        $('#inputDatoGuiaDistribucion').val(informacionDestino.guia);
        $('#inputComentariosEnvioDistribucion').val(informacionDestino.comentarioEnvio);
        $('#entregaFechaEnvioDistribucion').val(informacionDestino.fechaEntrega);
        $('#inputRecibeEnvioDistribucion').val(informacionDestino.nombreRecibe);
        $('#inputComentarioEntregaEnvioDistribucion').val(informacionDestino.comentarioEntrega);

    };

    var iniciarFileUploadEnFormularioEnvioDistribucion = function () {
        var dataExtra = arguments[0];
        var informacionDestino = arguments[1];

        file.crearUpload('#evidenciaEnvioDistribucion',
                'Seguimiento/Guardar_Evidencia',
                null,
                null,
                informacionDestino.evidenciaEnvio,
                'Seguimiento/Eliminar_Evidencia',
                'UrlEnvioDistribucion-' + dataExtra.destino,
                null,
                null,
                null,
                true,
                dataExtra
                );
        file.crearUpload('#evidenciaEntregaEnvioDistribucion',
                'Seguimiento/Guardar_Evidencia',
                null,
                null,
                informacionDestino.evidenciaEntrega,
                'Seguimiento/Eliminar_Evidencia',
                'UrlEntregaDistribucion-' + dataExtra.destino,
                null,
                null,
                null,
                true,
                dataExtra
                );
    };

    var concluirDestino = function () {
        var datos = arguments[0];
        var html = null;

        evento.enviarEvento('Seguimiento/Concluir_Destino_Distribucion', datos, '#seccion-datos-logistica', function (respuesta) {

            if (respuesta.faltaCampo) {
                evento.mostrarMensaje('#errorGeneralEnvioDistribucion', false, respuesta.mensaje, 5000);
            } else {
                html = '<div class="row">\n\
                                <div class="col-md-12 text-center">\n\
                                    Se concluyo el destino con exito\n\
                                </div>\n\
                            </div>';
                evento.mostrarModal('Concluir destino', html);
                $('#btnModalConfirmar').addClass('hidden');
                $('#btnModalAbortar').empty().append('Cerrar');

                $('#btnModalAbortar').off('click');
                $('#btnModalAbortar').on('click', function () {
                    actualizarTablaDestinosDistribuciones(respuesta.listaDestinos);
                    $('#btnRegresarTablaDestinos').trigger('click');
                    evento.cerrarModal();
                });
            }
        });
    };

    var habilitarBloquearFormularioEnvioDistribucion = function () {
        var evento = arguments[0];

        if (evento === 'habilitar') {
            $('#fechaEnvioDistribucion').removeAttr('disabled');
            $('#selectTipoEnvioDistribucion').removeAttr('disabled');
            $('#selectListaTipoEnvioDistribucion').removeAttr('disabled');
            $('#inputDatoGuiaDistribucion').removeAttr('disabled');
            $('#inputComentariosEnvioDistribucion').removeAttr('disabled');
            file.habilitar('#evidenciaEnvioDistribucion');
            $('#entregaFechaEnvioDistribucion').removeAttr('disabled');
            $('#inputRecibeEnvioDistribucion').removeAttr('disabled');
            $('#inputComentarioEntregaEnvioDistribucion').removeAttr('disabled');
            file.habilitar('#evidenciaEntregaEnvioDistribucion');

            if ($('#seccionBotonesDestinoDistribucion').hasClass('hidden')) {
                $('#seccionBotonesDestinoDistribucion').removeClass('hidden');
            }

        } else if (evento === 'deshabilitar') {
            $('#fechaEnvioDistribucion').attr('disabled', 'disabled');
            $('#selectTipoEnvioDistribucion').attr('disabled', 'disabled');
            $('#selectListaTipoEnvioDistribucion').attr('disabled', 'disabled');
            $('#inputDatoGuiaDistribucion').attr('disabled', 'disabled');
            $('#inputComentariosEnvioDistribucion').attr('disabled', 'disabled');
            file.deshabilitar('#evidenciaEnvioDistribucion');
            $('#entregaFechaEnvioDistribucion').attr('disabled', 'disabled');
            $('#inputRecibeEnvioDistribucion').attr('disabled', 'disabled');
            $('#inputComentarioEntregaEnvioDistribucion').attr('disabled', 'disabled');
            file.deshabilitar('#evidenciaEntregaEnvioDistribucion');

            if (!$('#seccionBotonesDestinoDistribucion').hasClass('hidden')) {
                $('#seccionBotonesDestinoDistribucion').addClass('hidden');
            }

        }

    };

    var cancelarDestinto = function () {
        var datos = arguments[0];
        var html = null;

        html = '<div class="row">\n\
                    <div class="col-md-12 text-center">\n\
                        ¿Estas seguro de querer cancelar el destino?\n\
                    </div>\n\
                </div>';

        evento.mostrarModal('Cancelar destino', html);

        $('#btnModalConfirmar').on('click', function () {
            actualizarDestino(datos);
        });

    };

    var actualizarDestino = function () {
        var datos = arguments[0];
        evento.enviarEvento('Seguimiento/Cancelar_Destino_Distribucion', datos, '#modal-dialogo', function (respuesta) {
            actualizarTablaDestinosDistribuciones(respuesta.listaDestinos);
            $('#btnRegresarTablaDestinos').trigger('click');
            $('.modal-body').empty().append('\
                <div class="row">\n\
                    <div class="col-md-12 text-center">\n\
                        <p>Se cancelo con exito el destino.</p>\n\
                    </div>\n\
                </div>');
            $('#btnModalConfirmar').addClass('hidden');
            $('#btnModalAbortar').empty().append('Cerrar');
        });
    };

    var mostrarBotonEmpezarRuta = function () {
        var ruta = arguments[0];
        var idEstatus = arguments[1];

        if (ruta !== null) {
            if (idEstatus !== '12') {
                $('#btnEmpezarRutaSeguimiento').removeClass('hidden');
            }
        }
    };

    var empezarRuta = function () {
        var idRuta = arguments[0];
        var datosTabla = arguments[1];
        var dataRuta = {Ruta: idRuta};
        var html = '<div id="seccionEmpezarRutaSeguimiento" > \
                        <div class="row">\n\
                            <div id="mensaje-modal" class="col-md-12 text-center">\n\
                                <h3>¿Realmente quieres empezar la Ruta?</h3>\n\
                            </div>\n\
                      </div>';
        html += '<div class="row m-t-20">\n\
                                <div class="col-md-12 text-center">\n\
                                    <button id="btnAceptarConcluir" type="button" class="btn btn-sm btn-success"><i class="fa fa-check"> Aceptar</i></button>\n\
                                    <button id="btnCancelarConcluir" type="button" class="btn btn-sm btn-danger"><i class="fa fa-times"> Cerrar</i></button>\n\
                                </div>\n\
                            </div>\n\
                </div>';
        $('#btnModalConfirmar').addClass('hidden');
        $('#btnModalAbortar').addClass('hidden');
        evento.mostrarModal('Advertencia', html);
        $('#btnModalConfirmar').empty().append('Eliminar');
        $('#btnModalConfirmar').off('click');
        $('#btnAceptarConcluir').on('click', function () {
            $('#btnCancelarConcluir').attr('disabled', 'disabled');
            evento.enviarEvento('Seguimiento/EmpezarRuta', dataRuta, '#modal-dialogo', function (respuesta) {
                if (respuesta instanceof Array) {
                    evento.cerrarModal();
                    var data = {servicio: datosTabla[0], operacion: '2'};
                    cargarFormularioSeguimiento(data, datosTabla, '#seccion-datos-logistica');
                } else if (respuesta === 'faltaServicio') {
                    evento.cerrarModal();
                    evento.mostrarMensaje('.errorGeneralesLogistica', false, 'No se pudo empezar el tránsito de la ruta "Debe tener al menos un servicio seleccionado "', 5000);
                } else {
                    evento.cerrarModal();
                    evento.mostrarMensaje('.errorGeneralesLogistica', false, 'No se pudo empezar el tránsito de la ruta', 3000);
                }
            });
        });
        //Envento para no concluir con la cancelacion
        $('#btnCancelarConcluir').on('click', function () {
            evento.cerrarModal();
        });
    };
});