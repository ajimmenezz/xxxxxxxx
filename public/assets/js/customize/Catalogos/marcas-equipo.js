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

    //Creando tabla de Marcas
    tabla.generaTablaPersonal('#data-table-marcas', null, null, true, true);

    //Evento para mostrar la ayuda del sistema
    evento.mostrarAyuda('Ayuda_Proyectos');

    //Inicializa funciones de la plantilla
    App.init();

    //Evento que agrega una sublínea
    $('#btnAgregarMarca').on('click', function () {
        evento.enviarEvento('Catalogo/MostrarFormularioMarca', '', '#seccionMarcas', function (respuesta) {
            $('#formularioMarca').removeClass('hidden').empty().append(respuesta.formulario);
            $('#listaMarcas').addClass('hidden');
            select.crearSelect('select');

            $("#selectLineaEquipo").on("change", function () {
                select.setOpcionesSelect('#selectSublineaEquipo', respuesta.sublineas, $(this).val(), 'IdLinea');
            });

            //Evento que genera un nueva linea
            $("#btnNuevaMarca").off("click");
            $('#btnNuevaMarca').on('click', function () {
                var data = {
                    linea: $("#selectLineaEquipo").val(), 
                    sublinea: $("#selectSublineaEquipo").val(), 
                    nombre: $('#inputNombreMarca').val()
                };               
                if (evento.validarFormulario('#formNuevaMarca')) {                    
                    evento.enviarEvento('Catalogo/NuevaMarca', data, '#seccionMarca', function (response) {
                        if (response instanceof Array) {
                            tabla.limpiarTabla('#data-table-marcas');
                            var columns = [
                                {data: 'IdLinea'},
                                {data: 'IdSub'},
                                {data: 'IdMar'},
                                {data: 'Marca'},
                                {data: 'Sublinea'},
                                {data: 'Linea'},
                                {data: 'Activacion'}
                            ];
                            tabla.generaTablaPersonal('#data-table-marcas', response, columns);                            
                            $('#formularioMarca').addClass('hidden');
                            $('#listaMarcas').removeClass('hidden');
                            evento.mostrarMensaje('.errorListaMarcas', true, 'Datos insertados correctamente', 3000);
                        } else {
                            evento.mostrarMensaje('.errorMarca', false, 'Ya existe la marca y no puede ser duplicada.', 3000);
                        }
                    });
                }
            });
            $('#btnCancelar').on('click', function () {
                $('#formularioMarca').empty().addClass('hidden');
                $('#listaMarcas').removeClass('hidden');
            });
        });
    });

    //Evento que permite actualizar la sublinea
    $('#data-table-marcas tbody').on('click', 'tr', function () {
        var fila = $('#data-table-marcas').DataTable().row($(this)).data();
        if (typeof fila.IdLinea !== 'undefined') {
            var datos = {idlinea: fila.IdLinea, idsub: fila.IdSub, idmar: fila.IdMar, marca: fila.Marca, flag: fila.Activacion}
        } else {
            var datos = {idlinea: fila[0], idsub: fila[1], idmar: fila[2], marca: fila[3], flag: fila[6]}
        }
        evento.enviarEvento('Catalogo/MostrarFormularioEditarMarca', datos, '#seccionSublineas', function (respuesta) {
            $("#formularioMarca").empty().append(respuesta.formulario).removeClass('hidden');
            $("#listaMarcas").addClass('hidden');
            select.crearSelect('select');

            $("#selectEditarLineaEquipo").on("change", function () {
                select.setOpcionesSelect('#selectEditarSublineaEquipo', respuesta.sublineas, $(this).val(), 'IdLinea');
            });

            //Evento del botón cancelar en la edición
            $("#btnEditarCancelar").off("click");
            $("#btnEditarCancelar").on("click", function () {
                $("#formEditarMarca").empty().addClass('hidden');
                $("#listaMarcas").removeClass('hidden');
            });


            //Evento del botón Guardar Cambios
            $("#btnEditarMarca").off("click");
            $("#btnEditarMarca").on("click", function () {
                var data = {
                    id: datos.idmar,
                    linea: $("#selectEditarLineaEquipo").val(),
                    sublinea: $("#selectEditarSublineaEquipo").val(),
                    nombre: $("#inputEditarNombreMarca").val(),
                    estatus: $("#selectEditarEstatus").val()
                };

                if (evento.validarFormulario('#formEditarMarca')) {
                    //Envia el evento AJAX para la actualización de la información.
                    evento.enviarEvento('Catalogo/ActualizarMarca', data, '#seccionMarcas', function (response) {
                        if (response instanceof Array) {
                            tabla.limpiarTabla('#data-table-marcas');
                            var columns = [
                                {data: 'IdLinea'},
                                {data: 'IdSub'},
                                {data: 'IdMar'},
                                {data: 'Marca'},
                                {data: 'Sublinea'},
                                {data: 'Linea'},
                                {data: 'Activacion'}
                            ];
                            tabla.generaTablaPersonal('#data-table-marcas', response, columns);
                            $("#formularioMarca").empty().addClass('hidden');
                            $("#listaMarcas").removeClass('hidden');
                            evento.mostrarMensaje('.errorEditarMarca', true, 'Datos actualizacos correctamente', 3000);
                        } else {
                            evento.mostrarMensaje('.errorEditarMarca', false, 'Ya existe la marca y no puede ser duplicada.', 3000);
                        }
                    });
                }
            });
        });
    });
});


