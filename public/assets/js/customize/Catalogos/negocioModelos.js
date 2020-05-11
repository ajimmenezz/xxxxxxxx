$(function () {
    //Objetos
    var evento = new Base();

    //Muestra la hora en el sistema
    evento.horaServidor($('#horaServidor').val());

    //Evento para cerra la session
    evento.cerrarSesion();
    
    evento.mostrarAyuda('Ayuda_Proyectos');

    App.init();
    
    let tablaPrincipal = new TablaBasica('data-table-unidad-negocios');
    
}); 