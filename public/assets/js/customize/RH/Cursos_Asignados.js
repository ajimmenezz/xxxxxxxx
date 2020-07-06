$(function () {

    var evento = new Base();
    var file = new Upload();
    //Muestra la hora en el sistema
    evento.horaServidor($('#horaServidor').val());

    //Evento para cerra la session
    evento.cerrarSesion();

    //Inicializa funciones de la plantilla
    App.init();
//    Gallery.init();


    let tablaCursosAsignados = new TablaBasica('tabla-cursosAsignados');
    let tablaTemario = new TablaBasica('tabla-temario');
    let tablaTemarioTerminar = new TablaBasica('tabla-temario-terminar');
    let tablaTemarioCompletado = new TablaBasica('tabla-temario-completado');
    let idUsuario = $('#valorIdUsuario').val();
    let idTema = null;
//    file.crearUploadBoton('#evidenciasAvanceCurso', 'Cursos_Asignados/Agregar-Avance', 'Agregar Archivos', 'btn btn-success');
    let evidenciaMaterial = new FileUpload_Basico('evidenciasAvanceCurso', {url: 'Cursos_Asignados/Agregar-Avance', extensiones: ['jpg', 'jpeg', 'png']});
    evidenciaMaterial.iniciarFileUpload();

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

    $("#btn-regresar-temario").off("click");
    $("#btn-regresar-temario").on('click', function (e) {
        $('#tablaAsigCursos').css('display', 'block');
        $('#asigCursoContinuar').css('display', 'none');
        $('#divMigajaTemario').addClass("hidden");
    });

    $("#btn-regresar-temario-completado").off("click");
    $("#btn-regresar-temario-completado").on('click', function (e) {

        regresarTemario();
    });

    $("#btn-cancel-avance").off("click");
    $("#btn-cancel-avance").on('click', function (e) {
        $('#asigCursoContinuar').css('display', 'block');
        $('#temarioTerminarCurso').css('display', 'none');
    });

    $("#btn-registrar-avance").off("click");
    $("#btn-registrar-avance").on('click', function (e) {
        let comentarios = $('#cometariosAvanceCurso').val();
        let evidencias = $('#evidenciasAvanceCurso').val();
        let idUsuario = $('#valorIdUsuario').val();
        let data = {comentarios: comentarios, idUsuario: idUsuario, idTema: idTema};
        
        if (evidencias !== '') {
            if (comentarios !== '') {
                evidenciaMaterial.enviarPeticionServidor('evidenciasAvanceCurso', data, function (respuesta) {
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
                });
            } else {
                evento.mostrarMensaje("#errorCometariosAvanceCurso", false, "Agrega la comentarios.", 3000);
            }
        } else {
            evento.mostrarMensaje("#errorCometariosAvanceCurso", false, "Agrega evidencia(s).", 3000);
        }
    });

    $("#btnCerrarCompletarAvanceCurso").on('click', function (e) {
        regresarTemario();
    });

    function regresarTemario() {
        $('#asigCursoContinuar').css('display', 'block');
        $('#temarioComenzarCurso').css('display', 'none');
        $('#divMigajaTemarioCompletado').addClass('hidden');
        $('#divMigajaTemario').removeClass('hidden');
    }

    function cargarTemarioUsuario(respuesta) {
        $('#tablaAsigCursos').css('display', 'none');
        $('#asigCursoContinuar').css('display', 'block');
        $('#divMigajaTemario').removeClass("hidden");

        tablaTemario.limpiartabla();
        tablaTemarioCompletado.limpiartabla();
        tablaTemarioTerminar.limpiartabla();

        $.each(respuesta.data.temario.temas, function (k, v) {
            let boton = '';

            if (v.idAvance === undefined) {
                boton = `<span class="temarioTablaTerminar" data-avance="${v.id}" style="cursor: pointer; margin: 5px; font-size: 13px;  color: #00acac; "><i class="fa fa-youtube-play" ></i> Terminar</span>`;
            } else {
                boton = `<span class="temarioTablaCompletado" data-avance="${v.idAvance}" style="cursor: pointer; margin: 5px; font-size: 13px;  color: #348fe2;"><i class="fa fa-check-square"></i> Completado</span>`;
            }

            tablaTemario.agregarDatosFila([v.nombre, v.porcentaje + '%', boton]);
            tablaTemarioCompletado.agregarDatosFila([v.nombre, v.porcentaje + '%', boton]);
            tablaTemarioTerminar.agregarDatosFila([v.nombre, v.porcentaje + '%', boton]);
        });

        $('.divFaltante').empty().html(respuesta.data.temario.faltante + '%');
        $('.divAvance').empty().html(respuesta.data.temario.avance + '%');

        $(".temarioTablaCompletado").on('click', function (e) {
            let idAvance = $(this).data('avance');
            let data = {idAvance: idAvance};

            evento.enviarEvento('Cursos_Asignados/Ver-Evidencias', data, '#asigCursoContinuar', function (respuesta) {
                $("#tablaAsigCursos").css('display', 'none');
                $("#asigCursoContinuar").css('display', 'none');
                $("#temarioComenzarCurso").css('display', 'block');
                $("#temarioTerminarCurso").css('display', 'none');
                $('#avanceComentario').empty().val(respuesta.data.avance[0].comentarios);

                crearGaleriaAvance(respuesta.data.avance);
                handleIsotopesGallery();
                $('#divMigajaTemario').addClass('hidden');
                $('#divMigajaTemarioCompletado').removeClass('hidden');
            });
        });

        $(".temarioTablaTerminar").on('click', function (e) {
            idTema = $(this).data('avance');
            $("#tablaAsigCursos").css('display', 'none');
            $("#asigCursoContinuar").css('display', 'none');
            $("#temarioComenzarCurso").css('display', 'none');
            $("#temarioTerminarCurso").css('display', 'block');
        });
    }

    function crearGaleriaAvance(avances) {
        let html = '';
        $.each(avances, function (k, v) {
            html += `<div class="image gallery-group-1">
                            <div class="image-inner">
                                <a href="${v.url}" data-lightbox="gallery-group-1">
                                    <img src="${v.url}" alt="" />
                                </a>
                            </div>
                            <div class="image-info">
                                <h5>Fecha ${v.fechaModificacion}</h5>
                            </div>
                            <h5 class="title">Comentarios</h5>
                            <div class="desc">
                                ${v.comentarios}
                            </div>
                        </div>`;
        });

        $('#gallery').append(html);
    }

    function calculateDivider() {
        var dividerValue = 4;
        if ($(this).width() <= 480) {
            dividerValue = 1;
        } else if ($(this).width() <= 767) {
            dividerValue = 2;
        } else if ($(this).width() <= 980) {
            dividerValue = 3;
        }
        return dividerValue;
    }
    var handleIsotopesGallery = function () {
        "use strict";
        var container = $('#gallery');
        var dividerValue = calculateDivider();
        var containerWidth = $(container).width() - 20;
        var columnWidth = containerWidth / dividerValue;
        $(container).isotope({
            resizable: true,
            masonry: {
                columnWidth: columnWidth
            }
        });

        $(window).smartresize(function () {
            var dividerValue = calculateDivider();
            var containerWidth = $(container).width() - 20;
            var columnWidth = containerWidth / dividerValue;
            $(container).isotope({
                masonry: {
                    columnWidth: columnWidth
                }
            });
        });

        var $optionSets = $('#options .gallery-option-set'),
                $optionLinks = $optionSets.find('a');

        $optionLinks.click(function () {
            var $this = $(this);
            if ($this.hasClass('active')) {
                return false;
            }
            var $optionSet = $this.parents('.gallery-option-set');
            $optionSet.find('.active').removeClass('active');
            $this.addClass('active');

            var options = {};
            var key = $optionSet.attr('data-option-key');
            var value = $this.attr('data-option-value');
            value = value === 'false' ? false : value;
            options[ key ] = value;
            $(container).isotope(options);
            return false;
        });
    };
});