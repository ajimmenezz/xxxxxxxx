$(function () {
    //Objetos
    var evento = new Base();

    //Muestra la hora en el sistema
    evento.horaServidor($('#horaServidor').val());

    //Evento para cerra la session
    evento.cerrarSesion();

    //Creando tabla de areas
    let tablaPrincipal = new TablaBasica('data-table-unidad-negocios');
    let tablaModelos = new TablaBasica('data-table-modelos');
    let tablaInfoModelos = new TablaBasica('data-table-infoModelos', [], true);
    let selectArea = new SelectBasico('selectArea');
    let selectModelos = new SelectBasico('selectModelos');

    evento.mostrarAyuda('Ayuda_Proyectos');

    App.init();
    let vista = 0;
    let datosEnvioPrincipal = null;
    let datosEnvioModelos = null;

    tablaPrincipal.evento(function () {
        var datos = tablaPrincipal.datosFila(this);
        datosEnvioPrincipal = {
            IdUnidadNegocio: datos[0]
        }
        evento.enviarEvento('EventoCatalogoModelosArea/GetModelosArea', datosEnvioPrincipal, '#seccionUnidadesNegocio', function (respuesta) {
            if (respuesta.code == 200) {
                $('#btnEvent').removeClass('hidden');
                $('#nombreUnidad').text("Unidad: " + datos[2]);
                vista = 1;
                cargaTablaModelos(respuesta.data);
            } else {
                evento.mostrarMensaje('.errorUnidadesNegocio', false, 'No se pude cargar la información, intentalo mas tarde.', 3000);
            }
        });
    });

    function cargaTablaModelos(infoRespuesta) {
        $('#subtitulo').removeClass('hidden');
        $('#titulo').addClass('hidden');
        if (infoRespuesta.tabla.length > 0) {
            $('#tablaModelos').removeClass('hidden');
            $('#tablaUnidades').addClass('hidden');
            $('#addAreaAtencion').addClass('hidden');
            tablaModelos.limpiartabla();
//            $.each(infoRespuesta.tabla, function (key, value) {
//                tablaModelos.agregarDatosFila([
//                    value.
//                ]);
//            });
            nuevaArea(infoRespuesta.areasAtencion, infoRespuesta.modelos);
        } else {
            $('#tablaInfoModelos').removeClass('hidden');
            $('#tablaUnidades').addClass('hidden');
            $('#addAreaAtencion').removeClass('hidden');
            datosEnvioModelos = {
                IdArea: 0
            }
            cargaDatosSelect(infoRespuesta.areasAtencion, infoRespuesta.modelos);
            vista = 3;
        }
    }

    function cargaDatosSelect(areasAtencion, modelos) {
        selectArea.cargaDatosEnSelect(areasAtencion);
        selectModelos.cargaDatosEnSelect(modelos);
        cargaTablaInfoModelo();
    }

    tablaModelos.evento(function () {
        var datos = tablaModelos.datosFila(this);
        datosEnvioModelos = {
            IdUnidadNegocio: datosEnvioPrincipal.IdUnidadNegocio,
            IdArea: datos[0]
        }
        evento.enviarEvento('EventoCatalogoModelosArea/', datosEnvioModelos, '#seccionUnidadesNegocio', function (respuesta) {
            if (respuesta.code == 200) {
                vista = 2;
                $('#modeloArea').text(" - " + datos[1]);
                cargaSelectModelo(respuesta.data.modelos);
                cargaTablaInfoModelo(respuesta.data.modelosArea);
            } else {
                evento.mostrarMensaje('.errorUnidadesNegocio', false, 'No se pude cargar la información, intentalo mas tarde.', 3000);
            }
        });
    });

    function cargaSelectModelo(infoModelos) {
        selectModelos.cargaDatosEnSelect(infoModelos);
    }

    function nuevaArea(areasAtencion, modelos) {
        $('#agregarArea').on('click', function () {
            $('#tablaInfoModelos').removeClass('hidden');
            $('#tablaModelos').addClass('hidden');
            $('#addAreaAtencion').removeClass('hidden');
            datosEnvioModelos = {
                IdArea: 0
            }
            selectArea.cargaDatosEnSelect(areasAtencion);
            selectModelos.cargaDatosEnSelect(modelos);
            cargaTablaInfoModelo();
            vista = 2;
        });
    }

    function cargaTablaInfoModelo(infoModelo = null) {
        $('#tablaInfoModelos').removeClass('hidden');
        $('#tablaModelos').addClass('hidden');
        tablaInfoModelos.limpiartabla();
        if (infoModelo != null) {
            $.each(infoModelo, function (key, value) {
                tablaInfoModelos.agregarDatosFila([
                    value.Id,
                    value.IdModelo,
                    value.LineaModelo,
                ]);
            });
        }

        $('#agregarModelo').off();
        $('#agregarModelo').on('click', function () {
            if (evento.validarFormulario('#formAgregarModelos')) {
                let idModelo = selectModelos.obtenerValor();
                let txtModelo = selectModelos.obtenerTexto();
                $("#selectModelos").find(`option[value='${idModelo}']`).remove();
                selectModelos.definirValor();
                
                tablaInfoModelos.agregarDatosFila([
                    0,
                    idModelo,
                    txtModelo,
                ]);
            }
        });

        $('#guardarModelo').off();
        $('#guardarModelo').on('click', function () {
            var datosTablaModelo = tablaInfoModelos.datosTabla();
            let arrayModelos = [];
            let area = selectArea.obtenerValor();
            if (evento.validarFormulario('#formTable')) {
                $.each(datosTablaModelo, function (key, value) {
                    arrayModelos[key] = {
                        Id: value[0],
                        IdModelo: value[1],
                        Nombre: value[2]
                    }
                });
                if (datosEnvioModelos.IdArea != 0 || area != '') {
                    let envioDatos = {
                        IdUnidadNegocio: datosEnvioPrincipal.IdUnidadNegocio,
                        modelos: arrayModelos
                    }
                    if (datosEnvioModelos.IdArea != 0) {
                        envioDatos.IdArea = datosEnvioModelos.IdArea;
                    } else if (area != '') {
                        envioDatos.IdArea = area;
                    }
                    
                    evento.enviarEvento('EventoCatalogoModelosArea/', envioDatos, '#seccionUnidadesNegocio', function (respuesta) {
                        cargaTablaModelos(respuesta.data);
                        $('#tablaInfoModelos').addClass('hidden');
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
                $('#tablaModelos').addClass('hidden');
                $('#subtitulo').addClass('hidden');
                $('#btnEvent').addClass('hidden');
                vista = 0;
                break;

            case 2:
                $('#tablaModelos').removeClass('hidden');
                $('#tablaInfoModelos').addClass('hidden');
                $('#ModeloArea').text(" ");
                $('#addAreaAtencion').addClass('hidden');
                tablaInfoModelos.limpiartabla();
                vista = 1;
                break;
            case 3:
                $('#tablaUnidades').removeClass('hidden');
                $('#tablaInfoModelos').addClass('hidden');
                $('#ModeloArea').text(" ");
                tablaInfoModelos.limpiartabla();
                vista = 1;
                break;
        }
    });
}); 