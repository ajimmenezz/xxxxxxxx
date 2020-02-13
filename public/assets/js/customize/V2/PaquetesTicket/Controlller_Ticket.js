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
            domHtml.enviar('panelSeguimientoPoliza', url, datos, datosServidor => {
                datosServicio = datosServidor;
                mostrarFormulario(datos);
            });
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
            solucion.iniciarElementos();
            solucion.listener(dato => servicio.setDatos(dato));
            bitacora.setDatos(datosServicio);
            bitacora.listener(dato => servicio.setDatos(dato));
        } else {
            datosServicio = datos;
            seguimientoOld(evento, datosServicio, datosFila);
        }
    }

});