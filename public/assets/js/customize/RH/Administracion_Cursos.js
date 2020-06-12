$(function () {

    var evento = new Base();
    var eventoPagina = new Pagina();
    //Muestra la hora en el sistema
    evento.horaServidor($('#horaServidor').val());

    //Evento para cerra la session
    evento.cerrarSesion();

    //Inicializa funciones de la plantilla
    App.init();
    
    let tablaCursos = new TablaBasica('tabla-cursos');
    tablaCursos.iniciarTabla();
    
    $('#btn-nuevo-curso').on('click',function(e){
        console.log('clic en boton');
        let datos = { nombre : 'Noe'};
        eventoPagina.enviarPeticionServidor('administracion-cursos','Administracion_Cursos/Nuevo-Curso',datos,function(respuesta){
            console.log(respuesta);
        });
    });
    
});
