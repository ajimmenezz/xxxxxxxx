$(function () {
    //Objetos
    var evento = new Base();

    //Muestra la hora en el sistema
    evento.horaServidor($('#horaServidor').val());

    //Evento para cerra la session
    evento.cerrarSesion();
    evento.mostrarAyuda('Ayuda_Proyectos');
    App.init();

    let tablaPrincipal = new TablaBasica('data-table-unidad-negocios');
    let tablaAreas = new TablaBasica('data-table-area');
    let selectArea = new SelectBasico('selectArea');
    let selectEliminarArea = new SelectBasico('selectEliminarArea');
    let datosEnvioPrincipal = null;

    tablaPrincipal.evento(function () {
        var datos = tablaPrincipal.datosFila(this);
        datosEnvioPrincipal = {
            IdUnidadNegocio: datos[0]
        }
        evento.enviarEvento('EventoCatalogoUnidadNegocioArea/GetUnidadesArea', datosEnvioPrincipal, '#seccionUnidadesNegocio', function (respuesta) {
            if (respuesta.code == 200) {
                $('#btnEvent').removeClass('hidden');
                $('#nombreUnidad').text("Unidad: " + datos[2]);
                cargaTablaAreas(respuesta.data);
            } else {
                evento.mostrarMensaje('.errorUnidadesNegocio', false, 'No se pude cargar la información, intentalo mas tarde.', 3000);
            }
        });
    });

    function cargaTablaAreas(infoRespuesta) {
        $('#subtitulo').removeClass('hidden');
        $('#titulo').addClass('hidden');

        $('#tablaAreas').removeClass('hidden');
        $('#tablaUnidades').addClass('hidden');
        tablaAreas.limpiartabla();
        $.each(infoRespuesta.tabla, function (key, value) {
            tablaAreas.agregarDatosFila([
                value.IdArea,
                value.Area
            ]);
        });
        selectArea.cargaDatosEnSelect(infoRespuesta.areasAtencion);
    }

    $('#agregarArea').off();
    $('#agregarArea').on('click', function () {
        if (evento.validarFormulario('#formAgregarAreas')) {
            let idArea = selectArea.obtenerValor();
            let txtArea = selectArea.obtenerTexto();
            $("#selectArea").find(`option[value='${idArea}']`).remove();
            selectArea.definirValor();

            tablaAreas.agregarDatosFila([
                idArea,
                txtArea,
            ]);
        }
    });

    $('#guardarArea').off();
    $('#guardarArea').on('click', function () {
        var datosTablaArea = tablaAreas.datosTabla();
        let arrayAreas = [];

        $.each(datosTablaArea, function (key, value) {
            arrayAreas[key] = {
                Id: value[0],
                Nombre: value[2]
            }
        });

        let envioDatos = {
            IdUnidadNegocio: datosEnvioPrincipal.IdUnidadNegocio,
            areas: arrayAreas
        }

        evento.enviarEvento('EventoCatalogoUnidadNegocioArea/SetUnidadesArea', envioDatos, '#seccionUnidadesNegocio', function (respuesta) {
            if (respuesta.code == 200) {
                cargaTablaAreas(respuesta.data);
            } else {
                evento.mostrarMensaje('.errorUnidadesNegocio', false, 'No se pude guardar la información, intentalo mas tarde.', 3000);
            }
        });

    });

    $('#btnEliminarArea').on('click', function () {
        evento.enviarEvento('EventoCatalogoUnidadNegocioArea/GetUnidadesAreasSelectEliminar', datosEnvioPrincipal, '#seccionUnidadesNegocio', function (respuesta) {
            selectEliminarArea.cargaDatosEnSelect(respuesta.data.areasAtencion);
        });
    });

    $('#btnAceptarEliminarArea').on('click', function () {
        if (evento.validarFormulario('#formEliminarArea')) {
            let datosEnvio = {
                IdUnidadNegocio: datosEnvioPrincipal.IdUnidadNegocio,
                IdArea: selectEliminarArea.obtenerValor()
            }
            evento.enviarEvento('EventoCatalogoUnidadNegocioArea/FlagUnidadArea', datosEnvio, '#modalEliminarArea', function (respuesta) {
                console.log(respuesta);
                if (respuesta.code == 200) {
                    cargaTablaAreas(respuesta.data);
                    $('#modalEliminarArea').modal('hide');
                } else {
                    evento.mostrarMensaje('.errorUnidadesNegocio', false, 'No se pude borrar la información, intentalo mas tarde.', 3000);
                }
            });
        }
    });

    $('#btnRegresar').on('click', function () {
        $('#tablaUnidades').removeClass('hidden');
        $('#titulo').removeClass('hidden');
        $('#tablaAreas').addClass('hidden');
        $('#subtitulo').addClass('hidden');
        $('#btnEvent').addClass('hidden');
    });
});