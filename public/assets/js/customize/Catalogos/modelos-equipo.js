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

    //Creando tabla de Modelos
    tabla.generaTablaPersonal('#data-table-modelos', null, null, true, true);

    //Evento para mostrar la ayuda del sistema
    evento.mostrarAyuda('Ayuda_Proyectos');

    //Inicializa funciones de la plantilla
    App.init();

    //Evento que agrega una sublínea
    $('#btnAgregarModelo').on('click', function () {
        evento.enviarEvento('Catalogo/MostrarFormularioModelo', '', '#seccionModelos', function (respuesta) {
            $('#formularioModelo').removeClass('hidden').empty().append(respuesta.formulario);
            $('#listaModelos').addClass('hidden');
            select.crearSelect('select');

            $("#selectLineaEquipo").on("change", function () {
                select.setOpcionesSelect('#selectSublineaEquipo', respuesta.sublineas, $(this).val(), 'IdLinea');
            });

            $("#selectSublineaEquipo").on("change", function () {
                select.setOpcionesSelect('#selectMarcaEquipo', respuesta.marcas, $(this).val(), 'IdSub');
            });

            //Evento que genera un nueva linea
            $("#btnNuevoModelo").off("click");
            $('#btnNuevoModelo').on('click', function () {
                var data = {
                    linea: $("#selectLineaEquipo").val(),
                    sublinea: $("#selectSublineaEquipo").val(),
                    marca: $("#selectMarcaEquipo").val(),
                    nombre: $('#inputNombreModelo').val(),
                    parte: $('#inputParteModelo').val()
                };
                if (evento.validarFormulario('#formNuevoModelo')) {
                    evento.enviarEvento('Catalogo/NuevoModelo', data, '#seccionModelo', function (response) {
                        if (response instanceof Array) {
                            tabla.limpiarTabla('#data-table-modelos');
                            var columns = [
                                {data: 'IdLinea'},
                                {data: 'IdSub'},
                                {data: 'IdMar'},
                                {data: 'IdMod'},
                                {data: 'Modelo'},
                                {data: 'Parte'},
                                {data: 'Marca'},
                                {data: 'Sublinea'},
                                {data: 'Linea'},
                                {data: 'Activacion'}
                            ];
                            tabla.generaTablaPersonal('#data-table-modelos', response, columns);
                            evento.limpiarFormulario('#formNuevoModelo');
                            $('#formularioModelo').addClass('hidden');
                            $('#listaModelos').removeClass('hidden');
                            evento.mostrarMensaje('.errorListaModelos', true, 'Datos insertados correctamente', 3000);
                        } else {
                            evento.mostrarMensaje('.errorModelo', false, 'Ya existe la marca y no puede ser duplicada.', 3000);
                        }
                    });
                }
            });
            $('#btnCancelar').on('click', function () {
                $('#formularioModelo').empty().addClass('hidden');
                $('#listaModelos').removeClass('hidden');
            });
        });
    });

    //Evento que permite actualizar la sublinea
    $('#data-table-modelos tbody').on('click', 'tr', function () {
        var fila = $('#data-table-modelos').DataTable().row($(this)).data();
        if (typeof fila.IdLinea !== 'undefined') {
            var datos = {idlinea: fila.IdLinea, idsub: fila.IdSub, idmar: fila.IdMar, idmod: fila.IdMod, modelo: fila.Modelo, parte: fila.Parte, marca: fila.Marca, flag: fila.Activacion}
        } else {
            var datos = {idlinea: fila[0], idsub: fila[1], idmar: fila[2], idmod: fila[3], modelo: fila[4], parte: fila[5], flag: fila[9]}
        }        
        evento.enviarEvento('Catalogo/MostrarFormularioEditarModelo', datos, '#seccionSublineas', function (respuesta) {
            $("#formularioModelo").empty().append(respuesta.formulario).removeClass('hidden');
            $("#listaModelos").addClass('hidden');
            select.crearSelect('select');

            $("#selectEditarLineaEquipo").on("change", function () {
                select.setOpcionesSelect('#selectEditarSublineaEquipo', respuesta.sublineas, $(this).val(), 'IdLinea');
                select.cambiarOpcion('#selectEditarSublineaEquipo','');
            });
            
            $("#selectEditarSublineaEquipo").on("change", function () {
                select.setOpcionesSelect('#selectEditarMarcaEquipo', respuesta.marcas, $(this).val(), 'IdSub');
            });

            //Evento del botón cancelar en la edición
            $("#btnEditarCancelar").off("click");
            $("#btnEditarCancelar").on("click", function () {
                $("#formEditarModelo").empty().addClass('hidden');
                $("#listaModelos").removeClass('hidden');
            });


            //Evento del botón Guardar Cambios
            $("#btnEditarModelo").off("click");
            $("#btnEditarModelo").on("click", function () {
                var data = {
                    id: datos.idmod,
                    linea: $("#selectEditarLineaEquipo").val(),
                    sublinea: $("#selectEditarSublineaEquipo").val(),
                    marca: $("#selectEditarMarcaEquipo").val(),
                    nombre: $('#inputEditarNombreModelo').val(),
                    parte: $('#inputEditarParteModelo').val(),
                    estatus: $("#selectEditarEstatus").val()
                };                

                if (evento.validarFormulario('#formEditarModelo')) {
                    //Envia el evento AJAX para la actualización de la información.
                    evento.enviarEvento('Catalogo/ActualizarModelo', data, '#seccionModelos', function (response) {
                        if (response instanceof Array) {
                            tabla.limpiarTabla('#data-table-modelos');
                            var columns = [
                                {data: 'IdLinea'},
                                {data: 'IdSub'},
                                {data: 'IdMar'},
                                {data: 'IdMod'},
                                {data: 'Modelo'},
                                {data: 'Parte'},
                                {data: 'Marca'},
                                {data: 'Sublinea'},
                                {data: 'Linea'},
                                {data: 'Activacion'}
                            ];
                            tabla.generaTablaPersonal('#data-table-modelos', response, columns);
                            $("#formularioModelo").empty().addClass('hidden');
                            $("#listaModelos").removeClass('hidden');
                            evento.mostrarMensaje('.errorEditarModelo', true, 'Datos actualizacos correctamente', 3000);
                        } else {
                            evento.mostrarMensaje('.errorEditarModelo', false, 'Ya existe el modelo y no puede ser duplicado.', 3000);
                        }
                    });
                }
            });
        });
    });
});


