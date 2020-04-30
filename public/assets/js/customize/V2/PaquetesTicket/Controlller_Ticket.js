$(function () {
    let evento = new Base();
    let tablaServicios = new TablaBasica('data-table-poliza');
    let modal = new ModalServicio('modal-dialogo');
    let datosFila = undefined;
    let datosServicio = undefined;
    let servicio = undefined;
    let domHtml = new Utileria();
    let informacion = new Informacion();
    let solucion = new Solucion();
    let bitacora = new Bitacora();
    let firma = new Firma();
    let servicios = new Servicio();
    let bug = new Bug();

    //Muestra la hora en el sistema
    evento.horaServidor($("#horaServidor").val());
    //Evento para cerra la session
    evento.cerrarSesion();

    //Inicializa funciones de la plantilla
    App.init();

    informacion.iniciarElementos();
//    bitacora.iniciarElementos();

    tablaServicios.evento(function () {
        datosFila = tablaServicios.datosFila(this);
        let factoryServicios = new factoryServicio();
        servicio = factoryServicios.getInstance(datosFila[3]);
        let url = 'Seguimiento/Servicio/Atender';

        let datos = {
            id: datosFila[0],
            tipo: datosFila[3],
            folio: datosFila[8],
            idSucursal: null,
            nombreCliente: null,
            servicio: datosFila[0],
            operacion: datosFila[7]
        };

        if (!servicio) {
            url = 'Seguimiento/Servicio_Datos';
        }

        if (datosFila[6] === 'ABIERTO') {
            modal.mostrarModal();
            modal.eventoCancelar();
            modal.eventoIniciar(function () {
                domHtml.enviar('modal-dialogo', url, datos, datosServidor => {
                    if (datosServidor) {
                        datosServicio = datosServidor;
                        modal.cerrarModal();
                        mostrarFormulario(datos);
                    } else {
                        modal.mostrarError('error', 'No Existe la información que solicita. Contacte con el administrador');
                    }
                });
            });
        } else {
            if (url === 'Seguimiento/Servicio/Atender') {
                domHtml.enviar('panelSeguimientoPoliza', 'Seguimiento/Servicio/Seguimiento', datos, datosServidor => {
                    if (bug.validar(datosServidor)) {
                        datosServicio = datosServidor;
                        mostrarFormulario(datos);
                    }
                });
            } else {
                datosServicio = datos;
                if (datos.operacion === '2' || datos.operacion === '3' || datos.operacion === '12' || datos.operacion === '10') {
                    datosServicio.operacion = '2';
                    seguimientoOld(evento, datosServicio, datosFila);
                }
            }
        }
    });

    function mostrarFormulario(datos) {
        if (servicio) {
            servicio.setDatos(datosServicio);
            domHtml.ocultarElemento('listaPoliza');
            domHtml.mostrarElemento('panelDetallesTicket');
            informacion.setDatos(datosServicio);
            informacion.listener(dato => servicio.setDatos(dato));
            solucion.setDatos(datosServicio);
            solucion.listener(dato => servicio.setDatos(dato));
            bitacora.setDatos(datosServicio);
            bitacora.listener(dato => servicio.setDatos(dato));
            firma.setDatos(datosServicio);
            firma.listener(dato => servicio.setDatos(dato));

            eventosBotonAcciones(datosServicio);
        } else {
            datosServicio = datos;
            seguimientoOld(evento, datosServicio, datosFila);
        }
    }

    function eventosBotonAcciones(datosServicio) {
        $("#btnNuevoServicioSeguimiento").off("click");
        $("#btnNuevoServicioSeguimiento").on("click", function () {
            var data = {servicio: datosServicio.servicio.servicio};
            servicios.nuevoServicio(
                    data,
                    datosServicio.servicio.ticket,
                    datosServicio.servicio.solicitud,
                    "Seguimiento/Servicio_Nuevo_Modal",
                    "#panel-ticket",
                    "Seguimiento/Servicio_Nuevo"
                    );
        });
        //Encargado de cancelar servicio
        $("#btnCancelarServicioSeguimiento").off("click");
        $("#btnCancelarServicioSeguimiento").on("click", function () {
            var data = {servicio: datosServicio.servicio.servicio, ticket: datosServicio.servicio.ticket};
            servicios.cancelarServicio(
                    data,
                    "Seguimiento/Servicio_Cancelar_Modal",
                    "#panel-ticket",
                    "Seguimiento/Servicio_Cancelar"
                    );
        });

        servicios.initBotonReasignarServicio(
                datosServicio.servicio.servicio,
                datosServicio.servicio.ticket,
                "#panel-ticket"
                );
        //evento para crear nueva solicitud
        servicios.initBotonNuevaSolicitud(
                datosServicio.servicio.servicio,
                "#panel-ticket"
                );

        servicios.subirInformacionSD(datosServicio.servicio.servicio, "#panel-ticket");
        servicios.botonAgregarVuelta({servicio: datosServicio.servicio.servicio},"#panel-ticket");
    }

});