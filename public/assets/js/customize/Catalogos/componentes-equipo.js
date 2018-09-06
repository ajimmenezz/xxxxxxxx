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

    //Creando tabla de Componentes
    tabla.generaTablaPersonal('#data-table-componentes', null, null, true);

    //Evento para mostrar la ayuda del sistema
    evento.mostrarAyuda('Ayuda_Proyectos');

    //Inicializa funciones de la plantilla
    App.init();

    //Evento que agrega una sublínea
    $('#btnAgregarComponente').on('click', function () {
        evento.enviarEvento('Catalogo/MostrarFormularioComponente', '', '#seccionComponentes', function (respuesta) {
            $('#formularioComponente').removeClass('hidden').empty().append(respuesta.formulario);
            $('#listaComponentes').addClass('hidden');
            select.crearSelect('select');

            //Evento que genera un nueva linea
            $("#btnNuevoComponente").off("click");
            $('#btnNuevoComponente').on('click', function () {
                var data = {
                    equipo: $("#selectEquipo").val(),
                    nombre: $('#inputNombreComponente').val(),
                    parte: $('#inputParteComponente').val()
                };
                if (evento.validarFormulario('#formNuevoComponente')) {
                    evento.enviarEvento('Catalogo/NuevoComponente', data, '#seccionComponente', function (response) {
                        if (response instanceof Array) {
                            tabla.limpiarTabla('#data-table-componentes');
                            var columns = [
                                {data: 'IdMod'},
                                {data: 'IdCom'},
                                {data: 'Componente'},
                                {data: 'Parte'},
                                {data: 'Equipo'},
                                {data: 'Activacion'}
                            ];
                            tabla.generaTablaPersonal('#data-table-componentes', response, columns);
                            evento.limpiarFormulario('#formNuevoComponente');
                            $('#formularioComponente').addClass('hidden');
                            $('#listaComponentes').removeClass('hidden');
                            evento.mostrarMensaje('.errorListaComponentes', true, 'Datos insertados correctamente', 3000);
                        } else {
                            evento.mostrarMensaje('.errorComponente', false, 'Ya existe la marca y no puede ser duplicada.', 3000);
                        }
                    });
                }
            });
            $('#btnCancelar').on('click', function () {
                $('#formularioComponente').empty().addClass('hidden');
                $('#listaComponentes').removeClass('hidden');
            });
        });
    });

    //Evento que permite actualizar la sublinea
    $('#data-table-componentes tbody').on('click', 'tr', function () {
        var fila = $('#data-table-componentes').DataTable().row($(this)).data();
        if (typeof fila.IdMod !== 'undefined') {
            var datos = {idmod: fila.IdMod, idcom: fila.IdCom, componente: fila.Componente, parte: fila.Parte, flag: fila.Activacion}
        } else {
            var datos = {idmod: fila[0], idcom: fila[1], componente: fila[2], parte: fila[3], flag: fila[5]}
        }        

        evento.enviarEvento('Catalogo/MostrarFormularioEditarComponente', datos, '#seccionSublineas', function (respuesta) {
            $("#formularioComponente").empty().append(respuesta.formulario).removeClass('hidden');
            $("#listaComponentes").addClass('hidden');
            select.crearSelect('select');

            //Evento del botón cancelar en la edición
            $("#btnEditarCancelar").off("click");
            $("#btnEditarCancelar").on("click", function () {
                $("#formEditarComponente").empty().addClass('hidden');
                $("#listaComponentes").removeClass('hidden');
            });


            //Evento del botón Guardar Cambios
            $("#btnEditarComponente").off("click");
            $("#btnEditarComponente").on("click", function () {
                var data = {
                    id: datos.idcom,
                    equipo: $("#selectEditarEquipo").val(),
                    nombre: $('#inputEditarNombreComponente').val(),
                    parte: $('#inputEditarParteComponente').val(),
                    estatus: $("#selectEditarEstatus").val()
                };                

                if (evento.validarFormulario('#formEditarComponente')) {
                    //Envia el evento AJAX para la actualización de la información.
                    evento.enviarEvento('Catalogo/ActualizarComponente', data, '#seccionComponentes', function (response) {
                        if (response instanceof Array) {
                            tabla.limpiarTabla('#data-table-componentes');
                            var columns = [
                                {data: 'IdMod'},
                                {data: 'IdCom'},
                                {data: 'Componente'},
                                {data: 'Parte'},
                                {data: 'Equipo'},
                                {data: 'Activacion'}
                            ];
                            tabla.generaTablaPersonal('#data-table-componentes', response, columns);
                            $("#formularioComponente").empty().addClass('hidden');
                            $("#listaComponentes").removeClass('hidden');
                            evento.mostrarMensaje('.errorEditarComponente', true, 'Datos actualizacos correctamente', 3000);
                        } else {
                            evento.mostrarMensaje('.errorEditarComponente', false, 'Ya existe el modelo y no puede ser duplicado.', 3000);
                        }
                    });
                }
            });
        });
    });
});


