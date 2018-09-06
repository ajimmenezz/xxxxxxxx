$(function () {
//Objetos
    var evento = new Base();
    var websocket = new Socket();
    var select = new Select();
    var tabla = new Tabla();
    //Evento que maneja las peticiones del socket
    websocket.socketMensaje();
    //Muestra la hora en el sistema
    evento.horaServidor($('#horaServidor').val());
    //Evento para cerra la session
    evento.cerrarSesion();
    //Creando tablas de Fallas Poliza
    tabla.generaTablaPersonal('#data-table-viaticos', null, null, true, true);

    //Evento para mostrar la ayuda del sistema
    evento.mostrarAyuda('Ayuda_Proyectos');
    //Inicializa funciones de la plantilla
    App.init();

    $('#btnAgregarViatico').off('click');
    $('#btnAgregarViatico').on('click', function () {
        mostrarFormularioViaticos();
    });

    $('#btnGuardarMontos').off('click');
    $('#btnGuardarMontos').on('click', function () {
        guardarMontos();
    });

    var mostrarFormularioViaticos = function () {
        var datos = arguments[0] || '';

        evento.enviarEvento('EventoCatalogos/MostrarFormularioViaticos', datos, '#seccion-catalogo-outsorcing', function (respuesta) {
            iniciarFormularioViaticos(respuesta, datos);
            cargarEventosFormularioViaticos(respuesta, datos);
        });
    };

    var iniciarFormularioViaticos = function () {
        var respuesta = arguments[0] || null;

        $('#seccionCatalogoOutsorcing').addClass('hidden');
        $('#seccionFormulariosOutsorcing').removeClass('hidden').empty().append(respuesta.formulario);

        select.crearSelect('select');

    };

    var cargarEventosFormularioViaticos = function () {

        $("#selectTecnicosOutsorcing").on("change", function () {
            var _tecnico = $(this).val();

            if (_tecnico == "") {
                $("#div-table-viaticos-outsorcing").empty();
            } else {
                var data = {
                    'tecnico': _tecnico
                }
                evento.enviarEvento('EventoCatalogos/MostrarTablaSucursalesAsociado', data, '#panelViaticosOutsorcing', function (respuesta) {
                    $("#div-table-viaticos-outsorcing").empty().append(respuesta.formulario);
                    tabla.generaTablaPersonal('#data-table-viaticos-outsorcing', null, null, true, false, [], null, null, false);

                    $("#btnGuardarViaticoOutsorcing").off("click");
                    $("#btnGuardarViaticoOutsorcing").on("click", function () {

                        var arrayComponentes = [];
                        $(".cantidad-viaticos-outsourcing").each(function (k, v) {
                            var _cantidadViaticosOutsourcing = $(this).val();

                            if (!isNaN(_cantidadViaticosOutsourcing) && _cantidadViaticosOutsourcing > 0) {
                                arrayComponentes.push($(this).attr("data-id") + "_" + _cantidadViaticosOutsourcing);
                            }
                        });

                        if (arrayComponentes.length > 0) {
                            var data = {
                                'tecnico': _tecnico,
                                'viaticos': arrayComponentes
                            };
                            evento.enviarEvento('EventoCatalogos/GuardarViaticosOutsourcing', data, '#panelViaticosOutsorcing', function (respuesta) {
                                if (respuesta === true) {
                                    evento.mensajeConfirmacion('Datos guardados con exito.', 'Correcto');
                                } else {
                                    evento.mostrarMensaje('#errorFormularioViaticoOutsourcing', false, 'No se pudo registrar el viático(s). Intente de nuevo o recargue su página.', 4000);
                                }
                            });
                        }
                    });
                });
            }
        });

        $('#btnRegresarViaticosOutsourcing').on('click', function () {
            ocultarFormulario();
        });
    };

    var ocultarFormulario = function () {
        $('#seccionCatalogoOutsorcing').removeClass('hidden');
        $('#seccionFormulariosOutsorcing').addClass('hidden');
    }

    var guardarMontos = function () {
        var primerMonto = $('#inputPrimerVueltaMonto').val();
        var adicionalMonto = $('#inputAdicionalesMonto').val();

        if (primerMonto !== '') {
            if (adicionalMonto !== '') {
                if (primerMonto > 0) {
                    if (adicionalMonto > 0) {
                        var data = {primerMonto: primerMonto, adicionalMonto: adicionalMonto};
                        evento.enviarEvento('EventoCatalogos/GuardarMontosOutsourcing', data, '#seccion-catalogo-outsorcing', function (respuesta) {
                            if (respuesta === true) {
                                evento.mostrarMensaje('#errorMontos', true, 'Datos guardados correctamente', 3000);
                            } else {
                                evento.mostrarMensaje('#errorMontos', false, 'No se pudo guardar los datos. Intente de nuevo o recargue su página.', 3000);
                            }
                        });
                    } else {
                        evento.mostrarMensaje('#errorMontos', false, 'El campo Monto Vueltas Adicionales debe ser mayor a cero.', 3000);
                    }
                } else {
                    evento.mostrarMensaje('#errorMontos', false, 'El campo Monto Primer Vuelta debe ser mayor a cero.', 3000);
                }
            } else {
                evento.mostrarMensaje('#errorMontos', false, 'El campo Monto Vueltas Adicionales esta vacio.', 3000);
            }
        } else {
            evento.mostrarMensaje('#errorMontos', false, 'El campo Monto Primer Vuelta esta vacio.', 3000);
        }
    };
});