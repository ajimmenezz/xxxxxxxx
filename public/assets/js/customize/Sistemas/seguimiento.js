$(function () {

    var evento = new Base();
    var websocket = new Socket();
    var tabla = new Tabla();
    var servicios = new Servicio();
    var botones = new Botones();

    //Evento que maneja las peticiones del socket
    websocket.socketMensaje();

    //Muestra la hora en el sistema
    evento.horaServidor($('#horaServidor').val());

    //Evento para cerra la session
    evento.cerrarSesion();

    //Creando tabla de areas
    tabla.generaTablaPersonal('#data-table-sistemas', null, null, true, true, [[0, 'desc']]);

    //Evento para mostrar la ayuda del sistema
    evento.mostrarAyuda('Ayuda_Proyectos');

    //Inicializa funciones de la plantilla
    App.init();

    //Evento que carga la seccion de seguimiento de un servicio de tipo Logistica
    $('#data-table-sistemas tbody').on('click', 'tr', function () {
        var datos = $('#data-table-sistemas').DataTable().row(this).data();
        if (datos !== undefined) {
            var servicio = datos[0];
            var operacion = datos[6];            
            if (operacion === '1') {
                var html = '<div class="row">\n\
                            <div id="mensaje-modal" class="col-md-12 text-center">\n\
                                <h3>¿Quieres atender el servicio?</h3>\n\
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

                $('#btnIniciarServicio').on('click', function () {
                    var data = {servicio: servicio, operacion: '1'};
                    evento.enviarEvento('Seguimiento/Servicio_Datos', data, '#modal-dialogo', function (respuesta) {
                        evento.cerrarModal();
                        data = {servicio: servicio, operacion: '2'};
                        cargarFormularioSeguimiento(data, datos);
                        recargandoTablaGeneral(respuesta.informacion);
                    });
                });

                //Envento para concluir con la cancelacion
                $('#btnCancelarIniciarServicio').on('click', function () {
                    evento.cerrarModal();
                });

            } else if (operacion === '2' || operacion === '12' || operacion === '10') {
                var data = {servicio: servicio, operacion: '2'};
                cargarFormularioSeguimiento(data, datos);
            }
        }
    });

    var cargarFormularioSeguimiento = function () {
        var data = arguments[0];
        var datosTabla = arguments[1];
        var panel = arguments[2];
        evento.enviarEvento('Seguimiento/Servicio_Datos', data, panel, function (respuesta) {
            var datosDelServicio = respuesta.datosServicio;
            var formulario = respuesta.formulario;
            var archivo = respuesta.archivo;
            var avanceServicio = respuesta.avanceServicio;
            var idSucursal = respuesta.idSucursal[0].IdSucursal;
            var datosSD = respuesta.datosSD;

            if (datosDelServicio.tieneSeguimiento === '0') {
                servicios.ServicioSinClasificar(
                        formulario,
                        '#listaServicio',
                        '#seccionSeguimientoServicio',
                        datosTabla[0],
                        datosDelServicio,
                        'Seguimiento',
                        archivo,
                        '#panelSeguimientoSistemas',
                        datosTabla[1],
                        avanceServicio,
                        idSucursal,
                        datosSD
                        );
            } else {
                $('#listaServicio').addClass('hidden');
                $('#seccionSeguimientoServicio').removeClass('hidden').empty().append(formulario);
                botones.iniciarBotonesGenerales(datosTabla[0], datosDelServicio);                
                switch (datosDelServicio.IdTipoServicio) {
                    case '10':
                        seguimientoUber(datosTabla[0]);
                        break;
                }
            }
        });
    };

    var recargandoTablaGeneral = function (informacionServicio) {
        tabla.limpiarTabla('#data-table-sistemas');
        $.each(informacionServicio.serviciosAsignados, function (key, item) {
            tabla.agregarFila('#data-table-sistemas', [item.Id, item.Ticket, item.Servicio, item.FechaCreacion, item.Descripcion, item.NombreEstatus, item.IdEstatus, item.Folio]);
        });
    };
});
