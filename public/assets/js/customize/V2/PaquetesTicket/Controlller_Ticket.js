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
        servicio = factoryServicios.getInstance(datosFila[3]);
        
        if(typeof servicio !== undefined){
            url = 'Seguimiento/Servicio_Datos';
        }
        
//        console.log(servicio);
        if (datosFila[6] === 'ABIERTO') {
            modal.mostrarModal(); 
            modal.eventoCancelar();
            modal.eventoIniciar(function(){
                servidor.enviar('modal-dialogo','',{},datos => {
                    console.log(datos);
                });                
            });
        }
        


    });

    seguimientoOld(evento);
});