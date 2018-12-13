$(function () {

    var evento = new Base();
    var tabla = new Tabla();
    var select = new Select();
    var file = new Upload();

    //Muestra la hora en el sistema
    evento.horaServidor($('#horaServidor').val());

    //Evento para cerra la session
    evento.cerrarSesion();

    //Evento para mostrar la ayuda del sistema
    evento.mostrarAyuda('Ayuda_Proyectos');

    //Inicializa funciones de la plantilla
    App.init();

    file.crearUpload('#inputArchivoComprobante', 'Gasto/ComprobacionRegistro', ['jpg', 'jpeg', 'png', 'pdf', 'xml']);

    evento.enviarEvento('Gasto/MostrarTablaMisGatos', {}, '#divDetalleGastos', function (respuesta) {
//        console.log(respuesta.datos);
        $("#divComprobanteGasto").empty().append(respuesta.vistaTabla);

        tabla.generaTablaPersonal("#data-table-gastos", null, null, true, true, [[0, 'asc']]);

        $('#data-table-gastos tbody').on('click', 'tr', function () {
            var _fila = $(this);
            var datos = $('#data-table-gastos').DataTable().row(this).data();
            if (datos !== undefined) {
                var idGasto = datos[0];

                evento.enviarEvento('Gasto/CargaGasto', {id: idGasto}, '#panelListaGastos', function (respuesta) {
                    $("#divFormularioGasto").empty().append(respuesta.html);
                    evento.cambiarDiv("#divListaGastos", "#divFormularioGasto");
                    evento.cambiarDiv("#divListaGastos", "#divFileComprobarGasto");

                    $("#btnRegresar").off("click");
                    $("#btnRegresar").on("click", function () {
                        $("#divFileComprobarGasto").fadeOut(400, function () {
                            $("#divListaGastos").fadeIn(400, function () {
                                $("#divFileComprobarGasto").empty();
                            });
                        });
                        $("#divFormularioGasto").fadeOut(400, function () {
                            $("#divListaGastos").fadeIn(400, function () {
                                $("#divFormularioGasto").empty();
                            });
                        });
                    });
                });
            }
        });
    });

    $('#btnSubirArchivo').off('click');
    $('#btnSubirArchivo').on('click', function () {
        var idGasto = $('#IDGasto').val();
        var inputArchivo = $('#inputArchivoComprobante').val();
        var datosGastos = {'idGasto': idGasto,
            'monto': parseFloat(Math.round($("#txtMonto").val() * 100) / 100).toFixed(2)};

        if (inputArchivo === "") {
            evento.mostrarMensaje("#errorComprobarGasto", false, 'Todos los campos son obligatorios', 4000);
        } else if (datosGastos.monto === "" || datosGastos.monto <= 0) {
            evento.mostrarMensaje("#errorComprobarGasto", false, 'El monto debe ser mayor a 0.00', 4000);
        } else {
            file.enviarArchivos('#inputArchivoComprobante', 'Gasto/ComprobacionRegistro', '#divDetalleGastos', datosGastos, function (respuesta) {
                file.limpiar('#inputArchivoComprobante');
                $('#txtMonto').val('');
                console.log(respuesta);
            });
        }
    });

    $('#btnTerminaComprobacion').off('click');
    $('#btnTerminaComprobacion').on('click', function () {
        var idGasto = parseInt($('#IDGasto').val());
//        console.log(typeof(idGasto));
        evento.enviarEvento('Gasto/ActualizarCampoComprobado', {id: idGasto}, '#panelListaGastos', function (respuesta) {
            console.log(respuesta);
        });
    });

});