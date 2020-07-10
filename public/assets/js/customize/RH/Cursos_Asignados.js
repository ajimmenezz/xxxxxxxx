$(function () {

    var evento = new Base();
    var file = new Upload();
    var bug = new Bug();
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
    let idTema = null;
    let idCurso = null;
    let evidenciaMaterial = new FileUpload_Boton('evidencias', {
        url: 'Cursos_Asignados/Agregar-Avance',
        extensiones: ['jpg', 'jpeg', 'png'],
        tituloAceptar: 'Agregar Archivos',
        colorBotonAceptar: 'btn btn-success'});
    evidenciaMaterial.iniciarFileUpload();

    $("#cursoTablaContinuar").on('click', function (e) {
        $("#tablaAsigCursos").css('display', 'none')
        $("#temarioComenzarCurso").css('display', 'none')
        $("#temarioTerminarCurso").css('display', 'none')
        $("#asigCursoContinuar").css('display', 'block')
    });

    $(".btn-comenzar-curso").off("click");
    $(".btn-comenzar-curso").on('click', function (e) {
        idCurso = $(this).data('id');
        evento.iniciarModal("#modal-box", "<strong>Comenzar Curso</strong>", '<p class="text-center"><strong>¿Quieres Comenzar el curso?</strong></p>');
        $("#btnModalBoxConfirmar").off("click");
        $("#btnModalBoxConfirmar").on('click', function (e) {
            let data = {'idCurso': idCurso, 'idUsuario': idUsuario};
            evento.enviarEvento('Cursos_Asignados/Comenzar-Curso', data, '#modal-box', function (respuesta) {
                evento.terminarModal("#modal-box");
                respuesta.idCurso = idCurso;
                cargarTemarioUsuario(respuesta);
            });
        });
    });

    $(".btn-continuar-curso").off("click");
    $(".btn-continuar-curso").on('click', function (e) {
        let idCurso = $(this).data('id');
        let data = {'idCurso': idCurso, 'idUsuario': idUsuario}
        evento.enviarEvento('Cursos_Asignados/Continuar-Curso', data, '#tablaAsigCursos', function (respuesta) {
            respuesta.idCurso = idCurso;
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
        let evidencias = $('#evidencias').val();
        let idUsuario = $('#valorIdUsuario').val();
        let data = {comentarios: comentarios, idUsuario: idUsuario, idTema: idTema, idCurso: idCurso};

        if (evidencias !== '') {
            if (comentarios !== '') {
                evidenciaMaterial.enviarPeticionServidor('temarioTerminarCurso', data, function (respuesta) {
                    evento.iniciarModal(
                            "#modal-box",
                            "<strong>Avance Tema</strong>",
                            `<p class="text-center">Se registro el avance del curso con éxito.</p>                      
                                <p class="text-center"><span class="fa-stack fa-2x text-success">
                            <i class="fa fa-circle fa-stack-2x"></i>
                            <i class="fa fa-check fa-stack-1x fa-inverse"></i>
                        </span></i></p>`);

                    $('#btnModalBoxConfirmar').addClass('hidden');
                    $('#btnModalBoxAbortar').empty().html('Cerrar');
                    $("#btnModalBoxAbortar").off("click");
                    $("#btnModalBoxAbortar").on('click', function (e) {
                        $('#cometariosAvanceCurso').val('');
                        evidenciaMaterial.limpiarElemento();
                        $("#temarioTerminarCurso").css('display', 'none');
                        
                        if(respuesta.data.temario.temas.length === 1){
                            location.reload();
                        }else{
                            cargarTemarioUsuario(respuesta);
                        }
                    });
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

        $('.divFaltante').empty().html(respuesta.data.temario.faltante + '%');
        $('.divAvance').empty().html(respuesta.data.temario.avance + '%');

        $.each(respuesta.data.temario.temas, function (k, v) {
            let boton = '';

            if (v.idAvance === undefined) {
                boton = `<span class="temarioTablaTerminar" data-curso="${respuesta.idCurso}"  data-avance="${v.id}" style="cursor: pointer; margin: 5px; font-size: 13px;  color: #00acac; "><i class="fa fa-youtube-play" ></i> Terminar</span>`;
            } else {
                boton = `<span class="temarioTablaCompletado" data-avance="${v.idAvance}" style="cursor: pointer; margin: 5px; font-size: 13px;  color: #348fe2;"><i class="fa fa-check-square"></i> Completado</span>`;
            }

            tablaTemario.agregarDatosFila([v.nombre, v.porcentaje + '%', boton]);
            tablaTemarioCompletado.agregarDatosFila([v.nombre, v.porcentaje + '%', boton]);
            tablaTemarioTerminar.agregarDatosFila([v.nombre, v.porcentaje + '%', boton]);
        });

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
            idCurso = $(this).data('curso');
            $("#tablaAsigCursos").css('display', 'none');
            $("#asigCursoContinuar").css('display', 'none');
            $("#temarioComenzarCurso").css('display', 'none');
            $("#temarioTerminarCurso").css('display', 'block');
        });
    }

    function crearGaleriaAvance(avances) {
        let html = '';
        let arrayAvances = avances[0].url.split(',');

        $.each(arrayAvances, function (k, v) {
            html += `<div class="image gallery-group-1">
                            <div class="image-inner">
                                <a href="${v}" data-lightbox="gallery-group-1">
                                    <img src="${v}" alt="" />
                                </a>
                            </div>
                            <div class="image-info">
                                <h5>Fecha ${avances[0].fechaModificacion}</h5>
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