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

    $("#cursoTablaContinuar").on('click', function (e) {
        console.log("continuar");
        $("#tablaAsigCursos").css('display', 'none')
        $("#temarioComenzarCurso").css('display', 'none')
        $("#temarioTerminarCurso").css('display', 'none')
        $("#asigCursoContinuar").css('display', 'block')
    });

    $(".temarioTablaCompletado").on('click', function (e) {
        console.log("temarioTablaCompletado");
        $("#tablaAsigCursos").css('display', 'none')
        $("#asigCursoContinuar").css('display', 'none')
        $("#temarioComenzarCurso").css('display', 'block')
        $("#temarioTerminarCurso").css('display', 'none')

    });

    $(".temarioTablaTerminar").on('click', function (e) {
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
            $('#tablaAsigCursos').css('display', 'none');
            $('#asigCursoContinuar').css('display', 'block');
        });

    });

    $("#btn-cancel-avance").off("click");
    $("#btn-cancel-avance").on('click', function (e) {
        $('#asigCursoContinuar').css('display', 'block');
        $('#temarioTerminarCurso').css('display', 'none');
    });

    $("#btn-registrar-avance").off("click");
    $("#btn-registrar-avance").on('click', function (e) {
        let comentarios = $('#cometariosAvanceCurso').val();

        if (comentarios !== '') {
            evento.iniciarModal(
                    "#modalEdit",
                    "<strong>Avance Tema</strong>",
                    `<p class="text-center">Se registro el avance del curso con éxito.</p>                      
                    <p class="text-center"><span class="fa-stack fa-2x text-success">
                            <i class="fa fa-circle fa-stack-2x"></i>
                            <i class="fa fa-check fa-stack-1x fa-inverse"></i>
                        </span></i></p>`);

            $('#btnAceptar').addClass('hidden');
            $('#btnCancelar').empty().html('Cerrar');
        } else {
            evento.mostrarMensaje("#errorCometariosAvanceCurso", false, "Agrega la comentarios.", 3000);
        }
    });

    $("#btnCerrarCompletarAvanceCurso").on('click', function (e) {
        $('#asigCursoContinuar').css('display', 'block');
        $('#temarioComenzarCurso').css('display', 'none');
    });



    // $("#btn-agregar-nuevo-temario").on('click',function(e){
    //     //modalSubirTemarios
    //     console.log("btn-agregar-nuevo-temario")
    //   $('#modalValidateTemario').modal('show')
    // });




});

