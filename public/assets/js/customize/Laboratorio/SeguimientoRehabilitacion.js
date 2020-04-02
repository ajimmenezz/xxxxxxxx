$(function () {
    let evento = new Base();
    evento.horaServidor($('#horaServidor').val());
    evento.cerrarSesion();
    evento.mostrarAyuda('Ayuda_Proyectos');
    App.init();
    
    let tablaPrincipal = new TablaBasica('data-tablaModelos');
    
    let tablaRefaccion = new TablaBasica('data-tablaRefaccion');
    let tablaDeshuesar = new TablaBasica('data-tablaDeshuesar');
    
    tablaPrincipal.evento(function () {
        
        $('.cambioVistas').removeClass('hidden');
        $('#panelRehabilitacionEquiposTabla').addClass('hidden');
    });
    
    $('#btnRegresar').on('click', function () {
        $('.cambioVistas').addClass('hidden');
        $('#panelRehabilitacionEquiposTabla').removeClass('hidden');
    });
    
    
});

