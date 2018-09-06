$(function () {
    //Objetos
    var evento = new Base();
    var calendario = new Fecha();
    var websocket = new Socket();
    var select = new Select();
    var tabla = new Tabla();
    var file = new Upload();

    //Evento que maneja las peticiones del socket
    websocket.socketMensaje();

    //Muestra la hora en el sistema
    evento.horaServidor($('#horaServidor').val());

    //Evento para cerra la session
    evento.cerrarSesion();

    //Creando tabla de resumen minuta
    tabla.generaTablaPersonal('#data-table-minuta', null, null, true, true, [[0, 'desc']]);

    //Evento para mostrar la ayuda del sistema
    evento.mostrarAyuda('Ayuda_Proyectos');

    //Inicializa funciones de la plantilla
    App.init();

    //Evento que permite actualizar el personal
    $('#data-table-minuta tbody').on('click', 'tr', function () {
        var datos = $('#data-table-minuta').DataTable().row(this).data();
        var idMinuta = datos[0];
        var idUsuario = datos[7];
        var archivoViejo = '';
        var data = {id: idMinuta};
        evento.enviarEvento('Minuta/MostrarActualizarMinuta', data, '#seccionResumenMinuta', function (respuesta) {
            $('#seccionActualizarMinuta').removeClass('hidden').empty().append(respuesta.formulario);
            $('#tablaResumenMinuta').addClass('hidden');
            $('#formNuevaMinuta').addClass('hidden');
            $('#mensajeEliminar').removeClass('hidden');
            //Creando tabla de actualizar minuta
            if (respuesta.datos.archivosMinutas.length > 0) {
                var columnas = datosNuevosTabla();
                tabla.generaTablaPersonal('#data-table-actualizarMinuta', respuesta.datos.archivosMinutas, columnas, null, null, [[0, 'desc']]);
            } else {
                tabla.generaTablaPersonal('#data-table-actualizarMinuta', null, null, true, true, [[0, 'desc']]);
            }
            //Evento para eliminar el renglon de la tabla
            $('#data-table-actualizarMinuta tbody').on('click', 'tr > td', function () {
                var datos = $('#data-table-actualizarMinuta').DataTable().row($(this).parent('tr')).data();
                if (!$(this).hasClass('descargar')) {
                    var dato = {id: datos['Id'], idMinuta: idMinuta};
                    var html = '<div class="row">\n\
                            <div id="mensaje-modal" class="col-md-12 text-center">\n\
                                <h4>"¿Realmente desea eliminar el Archivo?"</h4>\n\
                            </div>\n\
                      </div>';
                    html += ''
                    evento.mostrarModal('"Advertencia"', html);
                    $('#btnModalConfirmar').empty().append('Eliminar');
                    $('#btnModalConfirmar').off('click');
                    $('#btnModalConfirmar').on('click', function () {
                        evento.enviarEvento('Minuta/CambiarEstatusMinuta', dato, '#seccionResumenMinuta', function (respuesta) {
                            if (respuesta instanceof Array) {
                                $('#modal-dialogo').modal('hide');
                                tabla.limpiarTabla('#data-table-actualizarMinuta');
                                var columnas = datosNuevosTabla();
                                tabla.generaTablaPersonal('#data-table-actualizarMinuta', respuesta, columnas, null, null, [[0, 'desc']]);
                                evento.mostrarMensaje('.errorMinutasAdicionales', true, 'Se elimino la Minuta correctamente', 3000);
                            } else if (respuesta === 'sinPermiso') {
                                $('#modal-dialogo').modal('hide');
                                evento.mostrarMensaje('.errorMinutasAdicionales', false, 'No tienes los permisos para eliminar la minuta.', 3000);
                            } else if (respuesta === 'error') {
                                $('#modal-dialogo').modal('hide');
                                evento.mostrarMensaje('.errorMinutasAdicionales', false, 'No se pudo eliminar la minuta', 3000);
                            }
                        });
                    });
                }
            });

            select.crearSelect('select');
            //Creando input de evidencias
            file.crearUpload('#inputActualizarEvidenciasMinuta', 'Minuta/Nuevo_Archivo', ['doc', 'docx', 'pps', 'ppt', 'pptx', 'pdf', 'xls', 'xlsx', 'jpg', 'jpeg', 'bmp', 'png'], null, null, null, null, true, 1);
            var miembros = JSON.parse("[" + datos[4] + "]");
            //Evento para actualizar los miembros de la minuta 
            if (idUsuario === respuesta.datos.idUsuario) {
                $('#btnActualizar').removeClass('hidden');
                $('#btnActualizar').on('click', function (e) {
                    $('#selectActualizarPermisos').removeAttr('disabled');
                    $('#btnGuardar').removeClass('hidden');
                    $('#btnEliminarEvidenciaMinuta').removeClass('hidden');
                    $('#btnCancelarActualizacion').removeClass('hidden');
                    $('#btnActualizar').addClass('hidden');
                });
                $('#btnCancelarActualizacion').on('click', function (e) {
                    $('#selectActualizarPermisos').attr('disabled', 'disabled');
                    $('#btnGuardar').addClass('hidden');
                    $('#btnEliminarEvidenciaMinuta').addClass('hidden');
                    $('#btnCancelarActualizacion').addClass('hidden');
                    $('#btnActualizar').removeClass('hidden');
                    $('#minutaOriginal').removeClass('hidden');
                    $('#inputEvidencias').addClass('hidden');
                });
                $('#btnGuardar').on('click', function () {
                    if (evento.validarFormulario('#formActualizarMinuta')) {
                        var evidencia = $('#inputActualizarMinuta').val();
                        if (archivoViejo != '' && evidencia != '') {
                            actualizarMinuta(idMinuta, archivoViejo, datos[1]);
                        } else if (archivoViejo === '') {
                            actualizarMinuta(idMinuta, archivoViejo, datos[1]);
                        } else {
                            evento.mostrarMensaje('.errorActualizacionMinuta', false, 'Debes subir un archivo.', 3000);
                        }
                    }
                });
                file.crearUpload(
                        '#inputActualizarMinuta',
                        'Minuta/ActualizarMinuta',
                        ['doc', 'docx', 'pps', 'ppt', 'pptx', 'pdf', 'xls', 'xlsx', 'jpg', 'jpeg', 'bmp', 'png'], null, null, null, null, true, 1
                        );
                $('#btnEliminarEvidenciaMinuta').on('click', function () {
                    archivoViejo = $('#btnEliminarEvidenciaMinuta').attr('data-nombrearchivo');
                    $('#minutaOriginal').addClass('hidden');
                    $('#inputEvidencias').removeClass('hidden');
                });
            }
            $('#ActualizarNombreMinuta').html(datos[1]);
            $('#ActualizarFechaMinuta').html(datos[2]);
            $('#ActualizarUbicacionMinuta').html(datos[3]);
            $('#selectActualizarPermisos').val(miembros).trigger('change');
            $('#ActualizarDescripcionMinuta').html(datos[5]);
            //Generar actualiza un nuevo archivo
            $('#btnActualizarMinuta').on('click', function () {
                var id = datos[0];
                var usuarios = datos[4];
                var nombre = datos[1];
                var actualizarEvidenciasMinuta = $('#inputActualizarEvidenciasMinuta').val();
                var nombreArchivoAdicional = $('#inputNombreAA').val();
                var data = {id: id, usuarios: usuarios, nombre: nombre, tipo: '17', nombreArchivoAdicional: nombreArchivoAdicional};
                if (actualizarEvidenciasMinuta != '') {
                    if (nombreArchivoAdicional != '') {
                        file.enviarArchivos('#inputActualizarEvidenciasMinuta', 'Minuta/Nuevo_Archivo', '#seccionResumenMinuta', data, function (respuesta) {
                            if (respuesta instanceof Array) {
                                var columnas = datosNuevosTabla();
                                tabla.limpiarTabla('#data-table-actualizarMinuta');
                                tabla.generaTablaPersonal('#data-table-actualizarMinuta', respuesta, columnas, null, null, [[0, 'desc']]);
                                file.limpiar('#inputActualizarEvidenciasMinuta');
                                evento.limpiarFormulario('#formActualizarAA');
                                evento.mostrarMensaje('.errorMinutasAdicionales', true, 'Datos actualizados correctamente', 3000);
                            } else if (respuesta === 'repetido') {
                                file.limpiar('#inputActualizarEvidenciasMinuta');
                                evento.mostrarMensaje('.errorMinutasAdicionales', false, 'El nombre del Archivo Adicional ya se encuentra.', 3000);
                            }
                        });
                    } else {
                        evento.mostrarMensaje('.errorMinutasAdicionales', false, 'Falta el campo de Nombre del Archivo Adicional', 3000);
                    }
                } else {
                    evento.mostrarMensaje('.errorMinutasAdicionales', false, 'Agrege un Archivo', 3000);
                }
            });
            //Evento para regresar a resumen de minutas
            $('#btnRegresarMinuta').on('click', function () {
                location.reload();
            });
        });
    });

    //Evento que agregar la minuta
    $('#btnAgregarMinuta').on('click', function () {
        evento.enviarEvento('Minuta/MostrarActualizarMinuta', '', '#seccionResumenMinuta', function (respuesta) {
            $('#seccionActualizarMinuta').removeClass('hidden').empty().append(respuesta.formulario);
            $('#tablaResumenMinuta').addClass('hidden');
            $('#datosActualizar').addClass('hidden');
            $('#evidenciasAdicionales').addClass('hidden');
            calendario.crearFecha('.calendario');
            //Crea select multiple permiso
            select.crearSelectMultiple('#selectMiembrosMinuta', 'Define los Miembros');
            //Creando input de evidencias
            file.crearUpload('#inputEvidenciasMinuta', 'Minuta/Nueva_minuta', ['doc', 'docx', 'pps', 'ppt', 'pptx', 'pdf', 'xls', 'xlsx', 'jpg', 'jpeg', 'bmp', 'png']);
            //Generar nueva minuta
            $('#btnNuevaMinuta').on('click', function () {
                if (evento.validarFormulario('#formNuevaMinuta')) {
                    var nombre = $('#inputNombreMinuta').val();
                    var fecha = $('#inputFechaMinuta').val();
                    var ubicacion = $('#inputUbicacionMinuta').val();
                    var miembros = $('#selectMiembrosMinuta').val();
                    var descripcion = $('#textareaDescripcionMinuta').val();
                    var evidenciasMinuta = $('#inputEvidenciasMinuta').val();
                    var data = {tipo: '10', nombre: nombre, fecha: fecha, ubicacion: ubicacion, miembros: miembros.join(), descripcion: descripcion};
                    if (fecha != '') {
                        if (evidenciasMinuta != '') {
                            file.enviarArchivos('#inputEvidenciasMinuta', 'Minuta/Nueva_minuta', '#seccionResumenMinuta', data, function (respuesta) {
                                if (respuesta) {
                                    $('#btnModalConfirmar').addClass('hidden');
                                    $('#btnModalAbortar').empty().append('Cerrar');
                                    evento.limpiarFormulario('#formNuevaMinuta');
                                    $('#seccionActualizarMinuta').addClass('hidden');
                                    tabla.limpiarTabla('#data-table-minuta');
                                    $('#tablaResumenMinuta').removeClass('hidden');
                                    $.each(respuesta, function (key, valor) {
                                        tabla.agregarFila('#data-table-minuta', [valor.Id, valor.Nombre, valor.Fecha, valor.Ubicacion, valor.Miembros, valor.Descripcion, valor.Archivo, valor.IdUsuario, valor.Usuario], true);
                                    });
                                    evento.mostrarModal('Nombre de Minuta',
                                            '<div class="row">\n\
                                        <div class="col-md-12 text-center">\n\
                                            <h5>Se genero la Minuta correctamente</h5>\n\
                                        </div>\n\
                                    </div>');
                                    $('#btnModalConfirmar').addClass('hidden');
                                    $('#btnModalAbortar').empty().append('Cerrar');
                                } else {
                                    evento.mostrarMensaje('.errorNuevaMinuta', false, 'No se pudo generar la minuta vuelva a intentarlo', 3000);
                                }
                            });
                        } else {
                            evento.mostrarMensaje('.errorNuevaMinuta', false, 'Falta campo Archivos Minuta', 3000);
                        }
                    } else {
                        evento.mostrarMensaje('.errorNuevaMinuta', false, 'Falta campo Fecha', 3000);
                    }
                }
            });
            //Evento para regresar 
            $('#btnRegresarMinutaNueva').on('click', function (e) {
                $('#seccionActualizarMinuta').addClass('hidden');
                $('#tablaResumenMinuta').removeClass('hidden');
            });
        });
    });

    //Evento para actualizar minuta
    var actualizarMinuta = function () {
        var miembros = $('#selectActualizarPermisos').val();
        var data = {id: arguments[0], miembros: miembros, tipo: '17', minutaAnterior: arguments[1], nombre: arguments[2]};
        file.enviarArchivos('#inputActualizarMinuta', 'Minuta/ActualizarMinuta', '#seccionResumenMinuta', data, function (respuesta) {
            if (respuesta.actualizacion) {
                $('#selectActualizarPermisos').attr('disabled', 'disabled');
                $('#btnGuardar').addClass('hidden');
                $('#btnEliminarEvidenciaMinuta').addClass('hidden');
                $('#btnCancelarActualizacion').addClass('hidden');
                $('#btnActualizar').removeClass('hidden');
                $('#minutaOriginal').removeClass('hidden');
                $('#inputEvidencias').addClass('hidden');
                file.limpiar('#inputActualizarMinuta');
                if (respuesta.urlArchivo !== '') {
                    $('.evidencia a').removeAttr('href').attr('href', respuesta.urlArchivo);
                    $('.nombreArchivo').empty().append(respuesta.urlArchivo.substring(respuesta.urlArchivo.lastIndexOf('/') + 1));
                    $('#btnEliminarEvidenciaMinuta').removeAttr('data-nombrearchivo').attr('data-nombrearchivo', respuesta.urlArchivo);
                }
                evento.mostrarModal('Actualizacion de Minuta', '<div class="row"><div class="col-md-12 text-center">Se actualizo correctamente la minuta</div></div>');
                $('#btnModalConfirmar').addClass('hidden');
                $('#btnModalAbortar').empty().append('Cerrar');
            } else {
                evento.mostrarMensaje('.errorActualizacionMinuta', false, 'No se pudo Actualizar, favor de volver a intentarlo', 3000);
            }
        });
    };

    var datosNuevosTabla = function () {
        var columnas = [
            {data: 'Id'},
            {data: 'Miembro'},
            {data: 'Nombre'},
            {data: 'Fecha'},
            {data: null,
                sClass: 'descargar',
                render: function (data, type, row, meta) {
                    return '<a href = "' + data.Archivo + '">Descargar</a>';
                }
            }
        ];
        return columnas;
    }
});

