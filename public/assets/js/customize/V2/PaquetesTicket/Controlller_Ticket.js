$(function () {
    let evento = new Base();
    let tablaServicios = new TablaBasica('data-table-poliza');
    let modal = new ModalServicio('modal-dialogo');
    let servicio = undefined;
    let servidor = new Utileria();
    
    //Muestra la hora en el sistema
    evento.horaServidor($("#horaServidor").val());
    //Evento para cerra la session
    evento.cerrarSesion();

    //Inicializa funciones de la plantilla
    App.init();

    tablaServicios.evento(function () {
        let datosFila = tablaServicios.datosFila(this);
        let factoryServicios = new factoryServicio();
        servicio = factoryServicios.getInstance(datosFila[3]);
        let url = 'Seguimiento/Atender/';
        let datosServicio;
        let datos = {
            id: datosFila[0],
            tipo: datosFila[3],
            folio: datosFila[8],
            idSucursal: null,
            nombreCliente: null,
            servicio: datosFila[0],
            operacion: datosFila[7]
        };

        if (servicio) {
            url = 'Seguimiento/Servicio_Datos';
        }

        if (datosFila[6] === 'ABIERTO') {
            modal.mostrarModal();
            modal.eventoCancelar();
            modal.eventoIniciar(function () {
                servidor.enviar('modal-dialogo', url, datos, datosServidor => {
                    if (datosServidor) {
                        datosServicio = datosServidor;
                        modal.cerrarModal();
                    } else {
                        modal.mostrarError('error','No Existe la información que solicita. Contacte con el administrador');
                    }
                });
            });
        }

        if (servicio) {
            servidor.enviar('panelSeguimientoPoliza', url, datos, datos => {
                datosServicio = datos;
            });
            servicio.setDatos(datosServicio);
        } else {
            datosServicio = datos;
            seguimientoOld(evento, datosServicio, datosFila);
        }
    });

});