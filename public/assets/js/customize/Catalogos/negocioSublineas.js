$(function () {
    //Objetos
    var evento = new Base();

    //Muestra la hora en el sistema
    evento.horaServidor($('#horaServidor').val());

    //Evento para cerra la session
    evento.cerrarSesion();

    //Creando tabla de areas
    let tablaPrincipal = new TablaBasica('data-table-unidad-negocios');
    let tablaSublineas = new TablaBasica('data-table-sublineas');
    let selectArea = new SelectBasico('selectArea');
    let selectSublineas = new SelectBasico('selectSublinea');

    evento.mostrarAyuda('Ayuda_Proyectos');

    App.init();
    let vista = 0;
    let datosEnvioPrincipal = null;
    let datosEnvioSublineas = null;

    tablaPrincipal.evento(function () {
        var datos = tablaPrincipal.datosFila(this);
        datosEnvioPrincipal = {
            IdUnidadNegocio: datos[0]
        }
        evento.enviarEvento('EventoCatalogoSublineasArea/GetSublienasArea', datosEnvioPrincipal, '#seccionUnidadesNegocio', function (respuesta) {
            if (respuesta.code == 200) {
                $('#btnEvent').removeClass('hidden');
                $('#nombreUnidad').text("Unidad: " + datos[2]);
                vista = 1;
                cargaTablaSublineas(respuesta.data);
            } else {
                evento.mostrarMensaje('.errorUnidadesNegocio', false, 'No se pude cargar la información, intentalo mas tarde.', 3000);
            }
        });
    });

    function cargaTablaSublineas(sublienasArea) {
        $('#subtitulo').removeClass('hidden');
        $('#titulo').addClass('hidden');
        if (typeof sublienasArea.IdArea !== 'undefined') {
            $('#tablaSublineas').removeClass('hidden');
            $('#tablaUnidades').addClass('hidden');
            $('#addAreaAtencion').addClass('hidden');
            tablaSublineas.limpiartabla();
            tablaSublineas.agregarDatosFila([
                sublienasArea.IdArea,
                sublienasArea.Area,
                sublienasArea.Sublineas,
                sublienasArea.Cantidad
            ]);
        } else {
            $('#tablaInfoSublineas').removeClass('hidden');
            $('#tablaUnidades').addClass('hidden');
            $('#addAreaAtencion').removeClass('hidden');
            datosEnvioSublineas = {
                IdArea: 0
            }
            cargaDatosSelect(sublienasArea.areasAtencion, sublienasArea.sublineas);
            vista = 3;
        }
    }

    function cargaDatosSelect(areasAtencion, sublineas) {
        selectArea.cargaDatosEnSelect(areasAtencion);
        selectSublineas.cargaDatosEnSelect(sublineas);
        cargaTablaInfoSublinea();
    }

    tablaSublineas.evento(function () {
        var datos = tablaSublineas.datosFila(this);
        datosEnvioSublineas = {
            IdUnidadNegocio: datosEnvioPrincipal.IdUnidadNegocio,
            IdArea: datos[0]
        }
        evento.enviarEvento('EventoCatalogoSublineasArea/GetSublineas', datosEnvioSublineas, '#seccionUnidadesNegocio', function (respuesta) {
            if (respuesta.code == 200) {
                vista = 2;
                $('#sublineaArea').text(" - " + datos[1]);
                cargaSelectSublinea(respuesta.data.sublineas);
                cargaTablaInfoSublinea(respuesta.data.sublineasArea);
            } else {
                evento.mostrarMensaje('.errorUnidadesNegocio', false, 'No se pude cargar la información, intentalo mas tarde.', 3000);
            }
        });
    });

    function cargaSelectSublinea(infoSublineas) {
        selectSublineas.cargaDatosEnSelect(infoSublineas);
    }

    function cargaTablaInfoSublinea(infoSublinea = null) {
        $('#tablaInfoSublineas').removeClass('hidden');
        $('#tablaSublineas').addClass('hidden');

        $('#data-table-infoSublineas tbody tr').each(function () {
            $(this).remove();
        });
        if (infoSublinea != null) {
            $.each(infoSublinea, function (key, value) {
                $('#data-table-infoSublineas').append(
                        '<tr id="fila' + value.Id + '">\n\
                        <td class="idNever">' + value.Id + '</td>\n\
                        <td class="idNever">' + value.IdSublinea + '</td>\n\
                        <td>' + value.LineaSublinea + '</td>\n\
                        <td><input id="input' + value.IdSublinea + '" type="text" class="form-control" style="width: 100%" value="' + value.Cantidad + '" data-parsley-required="true"/></td>\n\
                    </tr>');
            });
        }
        $(".idNever").hide();

        $('#agregarSublinea').off();
        $('#agregarSublinea').on('click', function () {
            if (evento.validarFormulario('#formAgregarSublinea')) {
                let idSublinea = selectSublineas.obtenerValor();
                let txtSublinea = selectSublineas.obtenerTexto();
                $("#selectSublinea").find(`option[value='${idSublinea}']`).remove();
                selectSublineas.definirValor();

                $('#data-table-infoSublineas').append(
                        '<tr id="fila' + idSublinea + '">\n\
                        <td class="idNever">0</td>\n\
                        <td class="idNever">' + idSublinea + '</td>\n\
                        <td>' + txtSublinea + '</td>\n\
                        <td><input id="input' + idSublinea + '" type="text" class="form-control" style="width: 100%" data-parsley-required="true"/></td>\n\
                    </tr>');
                $(".idNever").hide();
            }
        });

        $('#guardarSublinea').on('click', function () {
            var datosTablaSublinea = $('#data-table-infoSublineas').DataTable().rows().data();
            let arraySublineas = [];
            let area = selectArea.obtenerValor();
            if (evento.validarFormulario('#formTable')) {
                $.each(datosTablaSublinea, function (key, value) {
                    arraySublineas[key] = {
                        Id: value[0],
                        IdSublinea: value[1],
                        Nombre: value[2],
                        Cantidad: $(`#input${value[1]}`).val()
                    }
                });
                if (datosEnvioSublineas.IdArea != 0 || area != '') {
                    let envioDatos = {
                        IdUnidadNegocio: datosEnvioPrincipal.IdUnidadNegocio,
                        sublineas: arraySublineas
                    }
                    if (datosEnvioSublineas.IdArea != 0) {
                        envioDatos.IdArea = datosEnvioSublineas.IdArea;
                    } else if (area != '') {
                        envioDatos.IdArea = area;
                    }

                    evento.enviarEvento('EventoCatalogoSublineasArea/SetSublineas', envioDatos, '#seccionUnidadesNegocio', function (respuesta) {
                        if (respuesta.code == 200) {
                            evento.mostrarMensaje('.errorUnidadesNegocio', true, 'Información guardada exitosamente.', 3000);
                            setTimeout(function () {
                                location.reload();
                            }, 2000);
                        } else {
                            evento.mostrarMensaje('.errorUnidadesNegocio', false, 'No se pude cargar la información, intentalo mas tarde.', 3000);
                        }
                    });
                } else {
                    evento.mostrarMensaje('.errorUnidadesNegocio', false, 'No hay Area.', 3000);
                }
            }
        });
    }

    $('#btnRegresar').on('click', function () {
        switch (vista) {
            case 1:
                $('#tablaUnidades').removeClass('hidden');
                $('#titulo').removeClass('hidden');
                $('#tablaSublineas').addClass('hidden');
                $('#subtitulo').addClass('hidden');
                $('#btnEvent').addClass('hidden');
                vista = 0;
                break;

            case 2:
                $('#tablaSublineas').removeClass('hidden');
                $('#tablaInfoSublineas').addClass('hidden');
                $('#sublineaArea').text(" ");
                $('#data-table-infoSublineas tbody tr').each(function () {
                    $(this).remove();
                });
                vista = 1;
                break;
            case 3:
                $('#tablaUnidades').removeClass('hidden');
                $('#tablaInfoSublineas').addClass('hidden');
                $('#sublineaArea').text(" ");
                $('#data-table-infoSublineas tbody tr').each(function () {
                    $(this).remove();
                });
                vista = 1;
                break;
        }
    });
});
