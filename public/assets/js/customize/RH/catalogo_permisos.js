$(function () {

    peticion = new Utileria();

    evento = new Base();
    evento.horaServidor($('#horaServidor').val());
    evento.cerrarSesion();
    evento.mostrarAyuda('Ayuda_Proyectos');
    App.init();
    
    /**Empieza sección de eventos para el catalogo de Asistencia**/
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
    /**Finaliza sección de eventos para el catalogo de Asistencia**/
    
    
    /**Empieza sección de eventos para el catalogo de Motivos de Rechazo**/
    $('#agregarRechazo').on('click', function(){
        console.log('agregarRechazo');
    });
    $('#editarRechazo').on('click', function(){
        console.log('editarRechazo');
    });
    $('#eliminarRechazo').on('click', function(){
        console.log('eliminarRechazo');
    });
    
    /**Finaliza sección de eventos para el catalogo de Motivos de Rechazo**/
    
});