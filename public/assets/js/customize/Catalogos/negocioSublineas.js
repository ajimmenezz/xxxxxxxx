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
                $('#nombreUnidad').text("Unidad: "+datos[2]);
                vista = 1;
                cargaTablaSublineas(respuesta.data);
            } else {
                evento.mostrarMensaje('.errorUnidadesNegocio', false, 'No se pude cargar la información, intentalo mas tarde.', 3000);
            }
        });
    });

    function cargaTablaSublineas(sublienasArea) {
        $('#subtitulo').removeClass('hidden');
        $('#tablaSublineas').removeClass('hidden');
        $('#tablaUnidades').addClass('hidden');
        $('#titulo').addClass('hidden');
        tablaSublineas.limpiartabla();
        tablaSublineas.agregarDatosFila([
            sublienasArea.IdArea,
            sublienasArea.Area,
            sublienasArea.Sublineas,
            sublienasArea.Cantidad
        ]);
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
                $('#sublineaArea').text(" - "+datos[1]);
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

    function cargaTablaInfoSublinea(infoSublinea) {
        $('#tablaInfoSublineas').removeClass('hidden');
        $('#tablaSublineas').addClass('hidden');

        $('#data-table-infoSublineas tbody tr').each(function () {
            $(this).remove();
        });
        $.each(infoSublinea, function (key, value) {
            $('#data-table-infoSublineas').append(
                    '<tr id="fila' + value.Id + '">\n\
                        <td class="idNever">' + value.Id + '</td>\n\
                        <td>' + value.Sublinea + '</td>\n\
                        <td><input id="input' + value.Id + '" type="text" class="form-control" style="width: 100%" value="' + value.Cantidad + '"/></td>\n\
                    </tr>');
        });
        $(".idNever").hide();

        $('#agregarSublinea').on('click', function () {
            if (evento.validarFormulario('#formAgregarSublinea')) {
                let idSublinea = selectSublineas.obtenerValor();
                let txtSublinea = selectSublineas.obtenerTexto();
                $("#selectSublinea").find(`option[value='${idSublinea}']`).remove();
                selectSublineas.definirValor();

                $('#data-table-infoSublineas').append(
                        '<tr id="fila' + idSublinea + '">\n\
                        <td class="idNever">' + idSublinea + '</td>\n\
                        <td>' + txtSublinea + '</td>\n\
                        <td><input id="input' + idSublinea + '" type="text" class="form-control" style="width: 100%"/></td>\n\
                    </tr>');
                $(".idNever").hide();
            }
        });

        $('#guardarSublinea').on('click', function () {
            var datosTablaSublinea = $('#data-table-infoSublineas').DataTable().rows().data();
            let arraySublineas = [];
            $.each(datosTablaSublinea, function (key, value) {
                arraySublineas[key] = {
                    IdSublinea: value[0],
                    Nombre: value[1],
                    Cantidad: $(`#input${value[0]}`).val()
                }
            });
            let envioDatos = {
                IdUnidadNegocio: datosEnvioSublineas.IdUnidadNegocio,
                IdArea: datosEnvioSublineas.IdArea,
                sublineas: arraySublineas
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
                vista = 1;
                break;
        }
    });
});