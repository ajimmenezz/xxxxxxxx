$(function () {
    var evento = new Base();
    let tablaServicios = new TablaBasica('data-table-poliza');
    let factoryServicios = new factoryServicio();
    let modal = new ModalServicio('modal-dialogo');
    let servicio = undefined;
    //Muestra la hora en el sistema
    evento.horaServidor($("#horaServidor").val());
    //Evento para cerra la session
    evento.cerrarSesion();

    //Inicializa funciones de la plantilla
    App.init();

    tablaServicios.evento(function () {
        let datos = tablaServicios.datosFila(this);
        
        try {
            servicio = factoryServicios.getInstance(datos[3]);
        } catch (error) {
            console.log(error, datos);
        } finally {
            if (datos[6] === 'ABIERTO') {
                modal.mostrarModal(); 
                modal.eventoCancelar();                
            } else {

            }
        }


    });

    seguimientoOld(evento);
});