$(function () {

    var evento = new Base();
    //Muestra la hora en el sistema
    evento.horaServidor($('#horaServidor').val());

    //Evento para cerra la session
    evento.cerrarSesion();


    //Inicializa funciones de la plantilla
    App.init();

    
    let tablaCursosAsignados = new TablaBasica('tabla-cursosAsignados');
    tablaCursosAsignados.iniciarTabla();

    $("#cursoTablaContinuar").on('click',function(e){
        console.log("continuar");
        $("#tablaAsigCursos").css('display', 'none')
        $("#temarioComenzarCurso").css('display', 'none')
        $("#temarioTerminarCurso").css('display', 'none')
        $("#asigCursoContinuar").css('display', 'block')
    });

    $(".temarioTablaCompletado").on('click',function(e){
        console.log("temarioTablaCompletado");
        $("#tablaAsigCursos").css('display', 'none')
        $("#asigCursoContinuar").css('display', 'none')
        $("#temarioComenzarCurso").css('display', 'block')
        $("#temarioTerminarCurso").css('display', 'none')
      
    });

    $(".temarioTablaTerminar").on('click',function(e){
        console.log("temarioTablaTerminar");
        $("#tablaAsigCursos").css('display', 'none')
        $("#asigCursoContinuar").css('display', 'none')
        $("#temarioComenzarCurso").css('display', 'none')
        $("#temarioTerminarCurso").css('display', 'block')
      
    });

    

    
    



    // $("#btn-agregar-nuevo-temario").on('click',function(e){
    //     //modalSubirTemarios
    //     console.log("btn-agregar-nuevo-temario")
    //   $('#modalValidateTemario').modal('show')
    // });

    


});

