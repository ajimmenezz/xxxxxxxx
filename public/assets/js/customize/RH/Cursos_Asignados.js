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

    let botonesFilaCursos = [
        {
            targets: 6,
            data: null,
            render: function (data, type, row, meta) {
                let accion = '<strong><i class="fa fa-ban"></i> Suspendido</strong>';
                if (row[4] === 'Disponible' && row[2] === '100%') {
                    accion = '<span style="color: #348fe2;"> <i class="fa fa-check-square"></i> Completado</span>';
                } else if (row[4] === 'Disponible' && row[5] !== '') {
                    accion = `<a href="javascript:;" class="btn btn-link btn-xs btn-continuar-curso"><strong style="color: #f0ad4e;"> <i class="fa fa-fast-forward"></i> Continuar</strong></a>`;
                } else if (row[4] === 'Disponible' && row[2] === '0%') {
                    accion = `<a href="javascript:;" class="btn btn-link btn-xs btn-comenzar-curso"><strong style="color: #00acac;"> <i class="fa fa-youtube-play"></i> Comenzar</strong></a>`;
                }
                return accion;
            }
        }
    ];

    let botonesFilaTemario = [
        {
            targets: 5,
            data: null,
            render: function (data, type, row, meta) {
            }
        }
    ];

    let configTablaCursos = {columnas: botonesFilaCursos};
    let tablaCursosAsignados = new TablaRender('tabla-cursosAsignados', [], configTablaCursos);
    let tablaTemario = new TablaRender('tabla-temario', [], botonesFilaTemario);
    let idUsuario = $('#valorIdUsuario').val();
    let idTema = null;
    let idCurso = null;

    $("#cursoTablaContinuar").on('click', function (e) {
        $("#tablaAsigCursos").css('display', 'none');
        $("#temarioComenzarCurso").css('display', 'none');
        $("#temarioTerminarCurso").css('display', 'none');
        $("#asigCursoContinuar").css('display', 'block');
    });

    tablaCursosAsignados.addListenerOnclik('.btn-comenzar-curso', function (dataRow, fila) {
        let idCurso = dataRow[0];
        evento.iniciarModal("#modal-box", "<strong>Comenzar Curso</strong>", '<p class="text-center"><strong>¿Quieres Comenzar el curso?</strong></p>');
        $("#btnModalBoxConfirmar").off("click");
        $("#btnModalBoxConfirmar").on('click', function (e) {
            let data = {'idCurso': idCurso, 'idUsuario': idUsuario};
            evento.enviarEvento('Cursos_Asignados/Comenzar-Curso', data, '#modal-box', function (respuesta) {
                evento.terminarModal("#modal-box");
                respuesta.idCurso = idCurso;
                recargarCursos(respuesta.data.temario.cursos)
                cargarTemarioUsuario(respuesta);
            });
        });
    });

    tablaCursosAsignados.addListenerOnclik('.btn-continuar-curso', function (dataRow, fila) {
        let idCurso = dataRow[0];
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

    function recargarCursos(cursos) {
        tablaCursosAsignados.limpiartabla();

        $.each(cursos, function (k, v) {
            tablaCursosAsignados.agregarDatosFila([v.id, v.Nombre, Math.ceil(v.Porcentaje) + '%', v.fechaAsignacion, v.EstatusNombre, v.FechaInicio]);
        });
    }

    function cargarTemarioUsuario(respuesta) {
        $('#tablaAsigCursos').css('display', 'none');
        $('#asigCursoContinuar').css('display', 'block');
        $('#divMigajaTemario').removeClass("hidden");
        $('#divAvances').empty().html($('#divInstrucciones').html());

        tablaTemario.limpiartabla();

        $('.divFaltante').empty().html(respuesta.data.temario.faltante + '%');
        $('.divAvance').empty().html(respuesta.data.temario.avance + '%');

        $.each(respuesta.data.temario.temas, function (k, v) {
            let boton = '';
            let idAvance = '';

            if (v.idAvance === undefined) {
                idAvance = v.id;
                boton = `<span class="temarioTablaTerminar" data-curso="${respuesta.idCurso}"  data-avance="${v.id}" style="cursor: pointer; margin: 5px; font-size: 13px;  color: #00acac; "><i class="fa fa-youtube-play" ></i> Terminar</span>`;
            } else {
                idAvance = v.idAvance;
                boton = `<span class="temarioTablaCompletado" data-avance="${v.idAvance}" style="cursor: pointer; margin: 5px; font-size: 13px;  color: #348fe2;"><i class="fa fa-check-square"></i> Completado</span>`;
            }

            tablaTemario.agregarDatosFila([respuesta.idCurso, idAvance, v.nombre, v.porcentaje + '%', boton]);
        });

        tablaTemario.addListenerOnclik('.temarioTablaCompletado', function (dataRow, fila) {
            eventoCompletadoTemario(dataRow);
        });

        tablaTemario.addListenerOnclik('.temarioTablaTerminar', function (dataRow, fila) {
            eventoTerminarTemario(dataRow);

            let evidenciaMaterial = new FileUpload_Boton('evidencias', {
                url: 'Cursos_Asignados/Agregar-Avance',
                extensiones: ['jpg', 'jpeg', 'png'],
                tituloAceptar: 'Agregar Archivos',
                colorBotonAceptar: 'btn btn-success'});
            evidenciaMaterial.iniciarFileUpload();

            $("#btn-cancel-avance").off("click");
            $("#btn-cancel-avance").on('click', function (e) {
                $('#divAvances').empty().html($('#divInstrucciones').html());
            });

            $("#btn-registrar-avance").off("click");
            $("#btn-registrar-avance").on('click', function (e) {
                let comentarios = $('#cometariosAvanceCurso').val();
                let evidencias = $('#evidencias').val();
                let idUsuario = $('#valorIdUsuario').val();
                let data = {comentarios: comentarios, idUsuario: idUsuario, idTema: idTema, idCurso: idCurso};

                if (evidencias !== '') {
                    if (comentarios !== '') {
                        evidenciaMaterial.enviarPeticionServidor('asigCursoContinuar', data, function (respuesta) {
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

                                if (respuesta.data.temario.temas.length === 1) {
                                    location.reload();
                                } else {
                                    respuesta.idCurso = idCurso;
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
        });

    }

    function eventoCompletadoTemario(dataRow) {
        let idAvance = dataRow[1];
        let data = {idAvance: idAvance};

        evento.enviarEvento('Cursos_Asignados/Ver-Evidencias', data, '#asigCursoContinuar', function (respuesta) {
            $('#divAvances').empty().html($('#formularioMostrarAvance').html());
            $('#avanceComentario').empty().val(respuesta.data.avance[0].comentarios);
            $('#gallery').empty('');

            crearGaleriaAvance(respuesta.data.avance);
            handleIsotopesGallery();

            $('#divMigajaTemario').addClass('hidden');
            $('#divMigajaTemarioCompletado').removeClass('hidden');

            $("#btnCerrarCompletarAvanceCurso").on('click', function (e) {
                regresarTemario();
            });
        });
    }

    function regresarTemario() {
        $('#divAvances').empty().html($('#divInstrucciones').html());
        $('#divMigajaTemarioCompletado').addClass('hidden');
        $('#divMigajaTemario').removeClass('hidden');
    }

    function eventoTerminarTemario(dataRow) {
        idCurso = dataRow[0];
        idTema = dataRow[1];
        $('#divAvances').empty().html($('#formularioAgregarAvance').html());
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