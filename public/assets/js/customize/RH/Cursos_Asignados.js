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
    let tablaTemarioTerminar = new TablaBasica('tabla-temario-terminar');
    let tablaTemarioCompletado = new TablaBasica('tabla-temario-completado');
    let idUsuario = $('#valorIdUsuario').val();

    $("#cursoTablaContinuar").on('click', function (e) {
        console.log("continuar");
        $("#tablaAsigCursos").css('display', 'none')
        $("#temarioComenzarCurso").css('display', 'none')
        $("#temarioTerminarCurso").css('display', 'none')
        $("#asigCursoContinuar").css('display', 'block')
    });

    $(".btn-comenzar-curso").off("click");
    $(".btn-comenzar-curso").on('click', function (e) {
        let id = $(this).data('id');
        evento.iniciarModal("#modal-box", "<strong>Comenzar Curso</strong>", '<p class="text-center"><strong>¿Quieres Comenzar el curso?</strong></p>');
        $("#btnModalBoxConfirmar").off("click");
        $("#btnModalBoxConfirmar").on('click', function (e) {
            let data = {'idCurso': id, 'idUsuario': idUsuario}
            evento.enviarEvento('Cursos_Asignados/Comenzar-Curso', data, '#modalEdit', function (respuesta) {
                evento.terminarModal("#modal-box");
                cargarTemarioUsuario();
            });
        });
    });

    $(".btn-continuar-curso").off("click");
    $(".btn-continuar-curso").on('click', function (e) {
        let id = $(this).data('id');
        let data = {'idCurso': id, 'idUsuario': idUsuario}
        evento.enviarEvento('Cursos_Asignados/Continuar-Curso', data, '#tablaAsigCursos', function (respuesta) {
            cargarTemarioUsuario(respuesta);
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

    function cargarTemarioUsuario(respuesta) {
        $('#tablaAsigCursos').css('display', 'none');
        $('#asigCursoContinuar').css('display', 'block');

        $.each(respuesta.data.temario.temas, function (k, v) {
            let boton = '';

            if (v.idAvance === undefined) {
                boton = `<span class="temarioTablaTerminar" style="cursor: pointer; margin: 5px; font-size: 13px;  color: #00acac; "><i class="fa fa-youtube-play" ></i>Terminar</span>`;
            } else {
                boton = `<span class="temarioTablaCompletado"  style="cursor: pointer; margin: 5px; font-size: 13px;  color: #348fe2;"><i class="fa fa-edit"></i>Completado</span>`;
            }

            tablaTemario.agregarDatosFila([v.nombre, v.porcentaje + '%', boton]);
            tablaTemarioCompletado.agregarDatosFila([v.nombre, v.porcentaje + '%', boton]);
            tablaTemarioTerminar.agregarDatosFila([v.nombre, v.porcentaje + '%', boton]);
        });

        $(".temarioTablaCompletado").on('click', function (e) {
            $("#tablaAsigCursos").css('display', 'none')
            $("#asigCursoContinuar").css('display', 'none')
            $("#temarioComenzarCurso").css('display', 'block')
            $("#temarioTerminarCurso").css('display', 'none')

        });

        $(".temarioTablaTerminar").on('click', function (e) {
            $("#tablaAsigCursos").css('display', 'none')
            $("#asigCursoContinuar").css('display', 'none')
            $("#temarioComenzarCurso").css('display', 'none')
            $("#temarioTerminarCurso").css('display', 'block')

        });
    }

});