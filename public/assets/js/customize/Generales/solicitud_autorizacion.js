$(function () {
    //Objetos
    var evento = new Base();
    var websocket = new Socket();
    var tabla = new Tabla();
    var select = new Select();

    //Variables globales
    var listaIds;

    //Evento que maneja las peticiones del socket
    websocket.socketMensaje();

    //Muestra la hora en el sistema
    evento.horaServidor($('#horaServidor').val());

    //Evento para cerra la session
    evento.cerrarSesion();

    //Inicializa funciones de la plantilla
    App.init();

    //Creando tabla de solicitudes generadas
    tabla.generaTablaPersonal('#data-table-solicitudes-autorizacion', null, null, true);

    //Evento que carga el formulario segun se tipo de solicitud
    $('#data-table-solicitudes-autorizacion tbody').on('click', 'tr', function () {
        var datos = $('#data-table-solicitudes-autorizacion').DataTable().row(this).data();
        var data = {solicitud: datos[0], operacion: '2'};
        var datosTablaMaterial = [];
        var datosAntiguos;

        evento.enviarEvento('Solicitud/Solicitud_Datos', data, '#seccionAutorizacion', function (respuesta) {            
            evento.mostrarModal('Solicitud ' + datos[0], respuesta.formularioSolicitud);
            $('#btnModalConfirmar').addClass('hidden');
            $('#btnModalAbortar').addClass('hidden');

            /*
             * Empezando botones generales
             */

            //Habilita el formulario
            $('#btnEditarSolicitud').on('click', function () {
                listaIds = evento.desbloquearFormulario('#formSolicitudPoyecto');
                datosAntiguos = evento.datosFormulario(listaIds);
                $('#seccionBtnEditarSolicitudPersonal').removeClass('hidden');
                $('#seccionBtnSolicitudPersonal').addClass('hidden');
            });

            //Boton para autorizar la solicitud
            $('#btnAutorizarSolicitud').on('click', function () {
                var data = {solicitud: datos[0], operacion: '2'};
                evento.enviarEvento('Solicitud/Solicitud_Actualizar', data, '#modal-dialogo', function (respuesta) {
                    tabla.limpiarTabla('#data-table-solicitudes-autorizacion');
                    if (typeof respuesta.solicitudes !== 'string') {
                        $.each(respuesta.solicitudes, function (key, value) {
                            tabla.agregarFila('#data-table-solicitudes-autorizacion', [value.Numero, value.Asunto, value.Tipo, value.Solicita, value.Fecha, value.Departamento, value.Estatus]);
                        });
                    }
                    $('#seccionFormulario').addClass('hidden');
                    $('#seccionExitoAutorizacion').removeClass('hidden');
                    $('#btnModalAbortar').removeClass('hidden').empty().append('Cerrar');
                });

            });

            //Boton el cual no autoriza la solicitud
            $('#btnNoAutorizar').on('click', function () {
                $('#seccionFormulario').addClass('hidden');
                $('#seccionNoAutorizado').removeClass('hidden');
            });

            //Boton el cual no cancela la opcion de no autorizar
            $('#btnCancelarNoAutorizar').on('click', function () {
                $('#seccionFormulario').removeClass('hidden');
                $('#seccionNoAutorizado').addClass('hidden');
                evento.limpiarFormulario('#formNoAutorizar');
            });

            //Boton el cual confirma no autorizar la solicitud
            $('#btnAceptarNoAutorizar').on('click', function () {
                if (evento.validarFormulario('#formNoAutorizar')) {
                    var data = {solicitud: datos[0], operacion: '3', descripcion: $('#textareaDescripcionNoAutorizado').val()};
                    evento.enviarEvento('Solicitud/Solicitud_Actualizar', data, '#modal-dialogo', function (respuesta) {
                        tabla.limpiarTabla('#data-table-solicitudes-autorizacion');
                        if (typeof respuesta.solicitudes !== 'string') {
                            $.each(respuesta.solicitudes, function (key, value) {
                                tabla.agregarFila('#data-table-solicitudes-autorizacion', [value.Numero, value.Asunto, value.Tipo, value.Solicita, value.Fecha, value.Departamento, value.Estatus]);
                            });
                        }
                        $('#seccionNoAutorizado').addClass('hidden');
                        $('#seccionExitoNoAutorizacion').removeClass('hidden');
                        $('#btnModalAbortar').removeClass('hidden').empty().append('Cerrar');
                    });
                }
            });

            //Cierra el modal
            $('#btnCerrarVentana').on('click', function () {
                evento.cerrarModal();
            });

            //Guarda los cambios despues de actualizar la informacion
            $('#btnGuardarCambiosSolicitud').on('click', function () {
                var data = {solicitud: datos[0], operacion: '1', ticket: respuesta.datos.Ticket, proyecto: respuesta.datos.detalles[0].IdProyecto, version: respuesta.datos.detalles[0].Version, datos: evento.datosFormulario(listaIds)};
                evento.enviarEvento('Solicitud/Solicitud_Actualizar', data, '#modal-dialogo', function (respuesta) {
                    evento.limpiarFormulario('#formSolicitudPoyecto')
                    evento.bloquearFormulario(listaIds);
                    $('#seccionBtnEditarSolicitudPersonal').addClass('hidden');
                    $('#seccionBtnSolicitudPersonal').removeClass('hidden');
                });
            });

            //Boton para cancelar cambios
            $('#btnCancelarCambiosSolicitud').on('click', function () {
                evento.cargarDatosAntiguosFormulario(listaIds, datosAntiguos, tabla);
                evento.bloquearFormulario(listaIds);
                $('#seccionBtnEditarSolicitudPersonal').addClass('hidden');
                $('#seccionBtnSolicitudPersonal').removeClass('hidden');
            });


            /*
             * Empezando botones para formulario Material
             */

            select.crearSelect('#selectLinea');
            select.crearSelect('#selectMaterial');
            $.each(respuesta.datos.detalles, function (key, value) {
                datosTablaMaterial.push([value.IdMaterial, value.Nombre, value.NumeroParte, value.Cantidad, value.Estatus]);
            });
            tabla.generaTablaPersonal('#data-table-materiales', datosTablaMaterial, null, true, null, null, false);


            //Evento del select linea para cargar el select material
            $('#selectLinea').on('change', function () {
                var datosMaterial = [], contador = 0, seleccion = $('#selectLinea').val();
                $.each(respuesta.Material, function (key, item) {
                    if (item.linea === seleccion) {
                        datosMaterial[contador] = {id: item.Id, text: item.Nombre, numparte: item.NumeroParte};
                        contador++;
                    }
                });
                select.setOpcionesSelect('#selectMaterial', respuesta.Material, $('#selectLinea').val(), 'linea', null, datosMaterial);
            });

            //Evento que borra una fila en la tabla material
            $('#data-table-materiales tbody').on('click', 'tr', function () {
                if (tabla.validarClickRenglon('#data-table-materiales')) {
                    tabla.eliminarFila('#data-table-materiales', this);
                }
            });

            //Evento que agrega una fila a la tabla material
            $('#btnAgregaMaterial').on('click', function (e) {
                var exprecion = new RegExp('^[0-9]+$');
                var material = $('#selectMaterial').val();
                var numparte = select.obtenerDatosSelect('#selectMaterial', 'numparte');
                var textomaterial = $('#selectMaterial option:selected').text();
                var cantidad = $('#inputCantidadMaterial').val();
                var filas = $('#data-table-materiales').DataTable().rows().data();
                var repetidoMaterial = false;
                if (material !== '' && exprecion.exec(cantidad) !== null) {
                    if (filas.length > 0) {
                        for (var i = 0; i < filas.length; i++) {
                            if (filas[i][1] === textomaterial) {
                                repetidoMaterial = true;
                            }
                        }
                    }
                    if (!repetidoMaterial) {
                        tabla.agregarFila('#data-table-materiales', [material, textomaterial, numparte, cantidad, 'SIN AUTORIZACIÓN']);
                        select.cambiarOpcion('#selectLinea', '');
                        $('#inputCantidadMaterial').val(null);
                    } else {
                        evento.mostrarMensaje('.errorAgregarMaterial', false, 'Ya se agrego este material favor de eliminar el que esta registrado si quiere actualizarlo', 3000);
                    }
                } else {
                    evento.mostrarMensaje('.errorAgregarMaterial', false, 'No se puede agregar el material ya que falta un campo por llenar', 3000);
                }
            });
        });
    });
});