$(function () {

    var evento = new Base();
    //Muestra la hora en el sistema
    evento.horaServidor($('#horaServidor').val());

    //Evento para cerra la session
    evento.cerrarSesion();

    //Inicializa funciones de la plantilla
    App.init();

    let tablaCursosAsignados = new TablaBasica('tabla-cursosAsignados');
    let tablaTemario = new TablaBasica('tabla-temario');

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

    
    $(".btn-acciones").off("click");
    $(".btn-acciones").on('click', function (e) {
        evento.iniciarModal("#modalEdit", "<strong>Comenzar Curso</strong>", '<p class="text-center"><strong>¿Quieres Comenzar el curso?</strong></p>');

        $("#btnAceptar").off("click");
        $("#btnAceptar").on('click', function (e) {
            evento.terminarModal("#modalEdit");
            $('#tablaAsigCursos').addClass('hidden');
            $('#asigCursoContinuar').removeClass('hidden');
            
        });

    });




    // $("#btn-agregar-nuevo-temario").on('click',function(e){
    //     //modalSubirTemarios
    //     console.log("btn-agregar-nuevo-temario")
    //   $('#modalValidateTemario').modal('show')
    // });




});

