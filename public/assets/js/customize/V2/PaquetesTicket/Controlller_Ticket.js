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
        let url = 'Seguimiento/Atender/';
        let factoryServicios = new factoryServicio();
        let datosFila = tablaServicios.datosFila(this);
        let validacionServicio = null;
        let data = {};
        let datoServicioTabla = {
            id: null,
            tipo: null,
            folio: null,
            idSucursal: null,
            nombreCliente: null
        };
        servicio = factoryServicios.getInstance(datosFila[3]);

        if (typeof servicio !== undefined) {
            url = 'Seguimiento/Servicio_Datos';
            data = {servicio: datosFila[0], operacion: "1"};
            validacionServicio = 'anterior';
        } else {
            datoServicioTabla.id = datosFila[0];
            datoServicioTabla.tipo = datosFila[3];

            if (datosFila[8] !== '' || datosFila[8] !== 0) {
                datoServicioTabla.folio = datosFila[8];
            }

            data = datoServicioTabla;
            validacionServicio = 'nuevo';
        }

        if (datosFila[6] === 'ABIERTO') {
            modal.mostrarModal();
            modal.eventoCancelar();
            modal.eventoIniciar(function () {

                servidor.enviar('modal-dialogo', url, data, datos => {
                    console.log(datos);

                    if (validacionServicio === 'anterior') {
//                        seguimientoOld.prueba();
                        let prueba = new seguimientoOld(evento);
                        let dataRespuesta = {servicio: datosFila[0], operacion: "2"};
//                        prueba.prueba();
//                        cargarFormularioSeguimiento();
                        prueba.cargarFormularioSeguimiento(
                                dataRespuesta,
                                datos,
                                "#panelSeguimientoPoliza"
                                );
                        prueba.recargandoTablaPoliza(datos.informacion);
//                    } else {

                    }
                    modal.cerrarModal();

//                    if (datos.error === undefined) {
//                        console.log('pumas1');
//                    } else {
////                        modal.mostrarModal('Error en el Servidor', '<div id="modal-dialogo" class="col-md-12">\n\
////                    <div class="col-md-3" style="text-align: right;">\n\
////                        <i class="fa fa-exclamation-triangle fa-4x text-danger"></i>\n\
////                    </div>\n\
////                    <div class="col-md-9">\n\
////                        <h4>`Surgio un problema de comunicación con el servidor : ${datos.mensage}`</h4>\n\
////                    </div>\n\
////                </div>');
//                        console.log(datos.mensage);
//                    }
                });
            });
        }


    });

});