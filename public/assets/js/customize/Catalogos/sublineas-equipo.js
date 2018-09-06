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

    //Creando tabla de sublineas
    tabla.generaTablaPersonal('#data-table-sublineas', null, null, true, true);

    //Evento para mostrar la ayuda del sistema
    evento.mostrarAyuda('Ayuda_Proyectos');

    //Inicializa funciones de la plantilla
    App.init();

    //Evento que agrega una sublínea
    $('#btnAgregarSublinea').on('click', function () {
        evento.enviarEvento('Catalogo/MostrarFormularioSublinea', '', '#seccionSublineas', function (respuesta) {
            $('#formularioSublinea').removeClass('hidden').empty().append(respuesta.formulario);
            $('#listaSublineas').addClass('hidden');
            select.crearSelect('select');
            //Evento que genera un nueva linea
            $('#btnNuevaSublinea').on('click', function () {
                var nombre = $('#inputNombreSublinea').val();
                var descripcion = $('#inputDescripcionSublinea').val();
                var linea = $("#selectLineaEquipo").val();
                var strLinea = $("#selectLineaEquipo option:selected").text();
                var activacion;
                var data = {linea: linea, nombre: nombre, descripcion: descripcion};
                if (evento.validarFormulario('#formNuevaSublinea')) {
                    evento.enviarEvento('Catalogo/NuevaSublinea', data, '#seccionSublinea', function (respuesta) {
                        if (respuesta instanceof Array) {
                            tabla.limpiarTabla('#data-table-sublineas');
                            $.each(respuesta, function (key, valor) {
                                if (valor.Flag === '1') {
                                    activacion = 'Activo';
                                } else {
                                    activacion = 'Inactivo';
                                }
                                tabla.agregarFila('#data-table-sublineas', [valor.IdLinea, valor.IdSub, valor.Sublinea, valor.Linea, valor.Descripcion, activacion]);
                            });
                            evento.limpiarFormulario('#formNuevaSublinea');
                            $('#formularioSublinea').addClass('hidden');
                            $('#listaSublineas').removeClass('hidden');
                            evento.mostrarMensaje('.errorListaSublinea', true, 'Datos insertados correctamente', 3000);
                        } else {
                            evento.mostrarMensaje('.errorSublinea', false, 'Ya existe la sublínea y no puede ser duplicada.', 3000);
                        }
                    });
                }
            });
            $('#btnCancelar').on('click', function () {
                $('#formularioSublinea').empty().addClass('hidden');
                $('#listaSublineas').removeClass('hidden');
            });
        });
    });

    //Evento que permite actualizar la sublinea
    $('#data-table-sublineas tbody').on('click', 'tr', function () {
        var fila = $('#data-table-sublineas').DataTable().row(this).data();
        var datos = {
            idlinea: fila[0],
            idsub: fila[1],
            sublinea: fila[2],
            desc: fila[4],
            flag: fila[5]
        };
        evento.enviarEvento('Catalogo/MostrarFormularioEditarSublinea', datos, '#seccionSublineas', function (respuesta) {
            $("#formularioSublinea").empty().append(respuesta.formulario).removeClass('hidden');
            $("#listaSublineas").addClass('hidden');
            select.crearSelect('select');

            //Evento del botón cancelar en la edición
            $("#btnEditarCancelar").off("click");
            $("#btnEditarCancelar").on("click", function () {
                $("#formularioSublinea").empty().addClass('hidden');
                $("#listaSublineas").removeClass('hidden');
            });


            //Evento del botón Guardar Cambios
            $("#btnEditarSublinea").off("click");
            $("#btnEditarSublinea").on("click", function () {
                var data = {
                    id: datos.idsub,
                    linea: $("#selectEditarLineaEquipo").val(),
                    nombre: $("#inputEditarNombreSublinea").val(),
                    descripcion: $("#inputEditarDescripcionSublinea").val(),
                    estatus: $("#selectEditarEstatus").val()
                };
                if (evento.validarFormulario('#formEditarSublinea')) {
                    //Envia el evento AJAX para la actualización de la información.
                    evento.enviarEvento('Catalogo/ActualizarSublinea', data, '#seccionSublineas', function (respuesta) {
                        if (respuesta instanceof Array) {
                            tabla.limpiarTabla('#data-table-sublineas');
                            $.each(respuesta, function (key, valor) {
                                console.log(valor);
                                if (valor.Flag === '1') {
                                    activacion = 'Activo';
                                } else {
                                    activacion = 'Inactivo';
                                }
                                tabla.agregarFila('#data-table-sublineas', [valor.IdLinea, valor.IdSub, valor.Sublinea, valor.Linea, valor.Descripcion, activacion]);
                            });
                            $("#formularioSublinea").empty().addClass('hidden');
                            $("#listaSublineas").removeClass('hidden');
                            evento.mostrarMensaje('.errorListaSubinea', true, 'Datos actualizacos correctamente', 3000);
                        } else {
                            evento.mostrarMensaje('.errorActualizarLinea', false, 'No se pudo actualizar la información. Error desconocido', 3000);
                        }
                    });
                }
            });
        });
    });
});


