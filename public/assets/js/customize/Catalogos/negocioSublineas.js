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
    let tablaInfoSublineas = new TablaBasica('data-table-infoSublineas', [], true);
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
        if (typeof sublienasArea.tabla !== 'undefined') {
            $('#tablaSublineas').removeClass('hidden');
            $('#tablaUnidades').addClass('hidden');
            $('#addAreaAtencion').addClass('hidden');
            tablaSublineas.limpiartabla();
            $.each(sublienasArea.tabla, function (key, value) {
                tablaSublineas.agregarDatosFila([
                    value.IdArea,
                    value.Area,
                    value.Sublineas,
                    value.Cantidad
                ]);
            });
            nuevaArea(sublienasArea.areasAtencion, sublienasArea.sublineas);
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

    function nuevaArea(areasAtencion, sublineas) {
        $('#agregarArea').on('click', function () {
            $('#tablaInfoSublineas').removeClass('hidden');
            $('#tablaSublineas').addClass('hidden');
            $('#addAreaAtencion').removeClass('hidden');
            datosEnvioSublineas = {
                IdArea: 0
            }
            selectArea.cargaDatosEnSelect(areasAtencion);
            selectSublineas.cargaDatosEnSelect(sublineas);
            cargaTablaInfoSublinea();
            vista = 2;
        });
    }

    function cargaTablaInfoSublinea(infoSublinea = null) {
        $('#tablaInfoSublineas').removeClass('hidden');
        $('#tablaSublineas').addClass('hidden');
        tablaInfoSublineas.limpiartabla();
        if (infoSublinea != null) {
            $.each(infoSublinea, function (key, value) {
                tablaInfoSublineas.agregarDatosFila([
                    value.Id,
                    value.IdSublinea,
                    value.LineaSublinea,
                    '<td><input id="input' + value.IdSublinea + '" type="text" class="form-control" style="width: 100%" value="' + value.Cantidad + '" data-parsley-required="true"/></td>'
                ]);
            });
        }

        $('#agregarSublinea').off();
        $('#agregarSublinea').on('click', function () {
            if (evento.validarFormulario('#formAgregarSublinea')) {
                let idSublinea = selectSublineas.obtenerValor();
                let txtSublinea = selectSublineas.obtenerTexto();
                $("#selectSublinea").find(`option[value='${idSublinea}']`).remove();
                selectSublineas.definirValor();
                
                tablaInfoSublineas.agregarDatosFila([
                    0,
                    idSublinea,
                    txtSublinea,
                    '<td><input id="input' + idSublinea + '" type="text" class="form-control" style="width: 100%" data-parsley-required="true"/></td>'
                ]);
            }
        });

        $('#guardarSublinea').off();
        $('#guardarSublinea').on('click', function () {
            var datosTablaSublinea = tablaInfoSublineas.datosTabla();
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
                        console.log(envioDatos);
//                        if (respuesta.code == 200) {
//                            evento.mostrarMensaje('.errorUnidadesNegocio', true, 'Información guardada exitosamente.', 3000);
//                            setTimeout(function () {
//                                location.reload();
//                            }, 2000);
//                        } else {
//                            evento.mostrarMensaje('.errorUnidadesNegocio', false, 'No se pude cargar la información, intentalo mas tarde.', 3000);
//                        }
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
                $('#addAreaAtencion').addClass('hidden');
                tablaInfoSublineas.limpiartabla();
                vista = 1;
                break;
            case 3:
                $('#tablaUnidades').removeClass('hidden');
                $('#tablaInfoSublineas').addClass('hidden');
                $('#sublineaArea').text(" ");
                tablaInfoSublineas.limpiartabla();
                vista = 1;
                break;
        }
    });
});
