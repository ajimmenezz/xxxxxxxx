$(function () {

    peticion = new Utileria();

    evento = new Base();
    evento.horaServidor($('#horaServidor').val());
    evento.cerrarSesion();
    evento.mostrarAyuda('Ayuda_Proyectos');
    App.init();
    
    $('#agregarMotivo').on('click', function(){
        console.log('agregarMotivo');
    });
    $('#limpiarCampos').on('click', function(){
        $('#inputMotivo').val('');
        $('#inputObservaciones').val('');
    });
    $('#editarMotivo').on('click', function(){
        console.log('editarMotivo');
    });
    $('#eliminarMotivo').on('click', function(){
        console.log('eliminarMotivo');
    });
    
});