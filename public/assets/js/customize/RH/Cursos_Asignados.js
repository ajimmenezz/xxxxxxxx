$(function () {

    var evento = new Base();
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

//    $("#btn-registrar-avance").off("click");
//    $("#btn-registrar-avance").on('click', function (e) {
//        let comentarios = $('#cometariosAvanceCurso').val();
//
//        if (comentarios !== '') {
//            evento.iniciarModal(
//                    "#modalEdit",
//                    "<strong>Avance Tema</strong>",
//                    `<p class="text-center">Se registro el avance del curso con éxito.</p>                      
//                    <p class="text-center"><span class="fa-stack fa-2x text-success">
//                            <i class="fa fa-circle fa-stack-2x"></i>
//                            <i class="fa fa-check fa-stack-1x fa-inverse"></i>
//                        </span></i></p>`);
//
//            $('#btnAceptar').addClass('hidden');
//            $('#btnCancelar').empty().html('Cerrar');
//        } else {
//            evento.mostrarMensaje("#errorCometariosAvanceCurso", false, "Agrega la comentarios.", 3000);
//        }
//    });

    $("#btnCerrarCompletarAvanceCurso").on('click', function (e) {
        $('#asigCursoContinuar').css('display', 'block');
        $('#temarioComenzarCurso').css('display', 'none');
    });


//    function uploadajax(ttl, cl) {
//
//        var fileList = $('#multiupload').prop("files");
//        $('#prog' + cl).removeClass('loading-prep').addClass('upload-image');
//
//        var form_data = "";
//
//        form_data = new FormData();
//        form_data.append("upload_image", fileList[cl]);
//
//
//        var request = $.ajax({
//            url: "Cursos_Asignados/Agregar-Avance",
//            cache: false,
//            contentType: false,
//            processData: false,
//            async: true,
//            data: form_data,
//            type: 'POST',
//            xhr: function () {
//                var xhr = $.ajaxSettings.xhr();
//                if (xhr.upload) {
//                    xhr.upload.addEventListener('progress', function (event) {
//                        var percent = 0;
//                        if (event.lengthComputable) {
//                            percent = Math.ceil(event.loaded / event.total * 100);
//                        }
//                        $('#prog' + cl).text(percent + '%')
//                    }, false);
//                }
//                return xhr;
//            },
//            success: function (res, status) {
//                if (status == 'success') {
//                    percent = 0;
//                    $('#prog' + cl).text('');
//                    $('#prog' + cl).text('--Success: ');
//                    if (cl < ttl) {
//                        uploadajax(ttl, cl + 1);
//                    } else {
//                        alert('Done');
//                    }
//                }
//            },
//            fail: function (res) {
//                alert('Failed');
//            }
//        })
//    }

//    $('#btn-registrar-avance').click(function () {
//        var fileList = $('#multiupload').prop("files");
//        $('#uploadsts').html('');
//        var i;
//        for (i = 0; i < fileList.length; i++) {
//            $('#uploadsts').append('<p class="upload-page">' + fileList[i].name + '<span class="loading-prep" id="prog' + i + '"></span></p>');
//            if (i == fileList.length - 1) {
//                uploadajax(fileList.length - 1, 0);
//            }
//        }
//    });


    $('#btn-registrar-avance').on('click', function (e) {
        var form_data = new FormData();
        var ins = document.getElementById('multiFiles').files.length;
        console.log(ins);
        for (var x = 0; x < ins; x++) {
            form_data.append("files[]", document.getElementById('multiFiles').files[x]);
        }
        $.ajax({
            url: 'Cursos_Asignados/Agregar-Avance', // point to server-side PHP script 
            dataType: 'text', // what to expect back from the PHP script
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            type: 'post',
            success: function (response) {
                $('#msg').html(response); // display success response from the PHP script
            },
            error: function (response) {
                $('#msg').html(response); // display error response from the PHP script
            }
        });
    });

//    $("#btn-registrar-avance").on("click", function () {
//        var file_data = $("#sortpicture").prop("files")[0];
////        console.log(file_data);
//        var form_data = new FormData();
//        form_data.append("file", file_data);
//        alert(form_data);
//        $.ajax({
//            url: "Cursos_Asignados/Agregar-Avance",
//            dataType: 'script',
//            cache: false,
//            contentType: false,
//            processData: false,
//            data: form_data,
//            type: 'post'
//        });
//    });
//});

//$('#btn-registrar-avance').on('click', function () {
//    var file_data = $('#sortpicture').prop('files')[0];
//    var form_data = new FormData();
//    form_data.append('file', file_data);
//    alert(form_data);
//    $.ajax({
//        url: 'Cursos_Asignados/Agregar-Avance', // point to server-side PHP script 
//        dataType: 'text', // what to expect back from the PHP script, if anything
//        cache: false,
//        contentType: false,
//        processData: false,
//        data: form_data,
//        type: 'post',
//        success: function (php_script_response) {
//            alert(php_script_response); // display response from the PHP script, if any
//        }
//    });
//  $("#btn-registrar-avance").click(function(){
//
//        var fd = new FormData();
//        var files = $('#sortpicture')[0].files[0];
//        fd.append('file',files);
//
//console.log(fd);
//        $.ajax({
//            url: 'Cursos_Asignados/Agregar-Avance',
//            type: 'post',
//            data: fd,
//            contentType: false,
//            processData: false,
//            success: function(response){
//                if(response != 0){
//                    $("#img").attr("src",response); 
//                    $(".preview img").show(); // Display image element
//                }else{
//                    alert('file not uploaded');
//                }
//            },
//        });
//    });
//});



    // $("#btn-agregar-nuevo-temario").on('click',function(e){
    //     //modalSubirTemarios
    //     console.log("btn-agregar-nuevo-temario")
    //   $('#modalValidateTemario').modal('show')
    // });

    function cargarTemarioUsuario(respuesta) {
        console.log(respuesta.data.temario.faltante);
        $('#tablaAsigCursos').css('display', 'none');
        $('#asigCursoContinuar').css('display', 'block');

        $.each(respuesta.data.temario.temas, function (k, v) {
            let boton = '';

            if (v.idAvance === undefined) {
                boton = `<span class="temarioTablaTerminar" data-avance="${v.idAvance}" style="cursor: pointer; margin: 5px; font-size: 13px;  color: #00acac; "><i class="fa fa-youtube-play" ></i>Terminar</span>`;
            } else {
                boton = `<span class="temarioTablaCompletado" data-avance="${v.idAvance}" style="cursor: pointer; margin: 5px; font-size: 13px;  color: #348fe2;"><i class="fa fa-edit"></i>Completado</span>`;
            }

            tablaTemario.agregarDatosFila([v.nombre, v.porcentaje + '%', boton]);
            tablaTemarioCompletado.agregarDatosFila([v.nombre, v.porcentaje + '%', boton]);
            tablaTemarioTerminar.agregarDatosFila([v.nombre, v.porcentaje + '%', boton]);
        });

        $('.divFaltante').empty().html(respuesta.data.temario.faltante + '%');
        $('.divAvance').empty().html(respuesta.data.temario.avance + '%');

        $(".temarioTablaCompletado").on('click', function (e) {
            let idAvance = $(this).data('avance');
            let data = {idAvance: idAvance}

            evento.enviarEvento('Cursos_Asignados/Ver-Evidencias', data, '#asigCursoContinuar', function (respuesta) {
                $("#tablaAsigCursos").css('display', 'none');
                $("#asigCursoContinuar").css('display', 'none');
                $("#temarioComenzarCurso").css('display', 'block');
                $("#temarioTerminarCurso").css('display', 'none');
                $('#avanceComentario').empty().val(respuesta.data.avance[0].comentarios);

                crearGaleriaAvance(respuesta.data.avance);
                handleIsotopesGallery();
            });


        });

        $(".temarioTablaTerminar").on('click', function (e) {
            $("#tablaAsigCursos").css('display', 'none')
            $("#asigCursoContinuar").css('display', 'none')
            $("#temarioComenzarCurso").css('display', 'none')
            $("#temarioTerminarCurso").css('display', 'block')

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