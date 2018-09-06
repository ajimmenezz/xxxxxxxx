$(function () {
//Objetos
    var evento = new Base();
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
    //Creando tabla de resumen archivos
    tabla.generaTablaPersonal('#data-table-archivos', null, null, true, true, [[0, 'desc']]);
    //Evento para mostrar la ayuda del sistema
    evento.mostrarAyuda('Ayuda_Proyectos');
    //Inicializa funciones de la plantilla
    App.init();
    //Generar consulta dependiendo el tipo de archivo
    $('#btnBuscarTipoArchivo').on('click', function () {
        var tipo = $('#selectTiposArchivos').val();
        var data = {tipo: tipo};
        if (evento.validarFormulario('#formSeleccionarArchivo')) {
            evento.enviarEvento('Archivos/MostrarTabla', data, '#seccionResumenArchivos', function (respuesta) {
                tabla.limpiarTabla('#data-table-archivos');
                var columnas = datosNuevosTabla();
                tabla.generaTablaPersonal('#data-table-archivos', respuesta, columnas, true, true, [[0, 'desc']])
            });
        }
    });
    //Evento que permite actualizar el archivo
    $('#data-table-archivos tbody').on('click', 'tr', function () {
        var datos = $('#data-table-archivos').DataTable().row(this).data();
        var data = {id: datos['IdUsuario']};
        evento.enviarEvento('Archivos/VerficarUsuario', data, '#seccionResumenArchivos', function (respuesta) {
            if (respuesta == true) {
                var data = {id: datos['Id']};
                evento.enviarEvento('Archivos/MostrarActualizarArchivo', data, '#seccionResumenArchivos', function (respuesta) {
                    var tipo = respuesta.datos.tipo[0].IdTipoArchivo;
                    var descripcion = respuesta.datos.descripcion[0].Descripcion;
                    var formulario = respuesta.formulario;
                    $('#seccionActualizarArchivo').removeClass('hidden').empty().append(formulario);
                    $('#tablaResumenArchivos').addClass('hidden');
                    //Creando tabla de actualizar minuta
                    tabla.generaTablaPersonal('#data-table-actualizarArchivo', null, null, true, true, [[0, 'desc']]);
                    //Creando tabla de actualizar minuta
                    select.crearSelect('select');
                    //Creando input de evidencias
                    file.crearUpload('#inputActualizarEvidenciasArchivo', 'Archivos/ActualizarArchivo', ['doc', 'docx', 'xls', 'xlsx', 'pdf'], null, null, null, null, true, 1);
                    $('#inputActualizarNombreArchivo').val(datos['Nombre']);
                    $('#ActualizarFechaArchivo').html(datos['Fecha']);
                    $('#inputActualizarDescripcionArchivo').val(descripcion);
                    $('#selectActualizarTiposArchivos').val(tipo).trigger('change');
                    //Generar actualiza un nuevo archivo
                    $('#btnActualizarArchivo').on('click', function () {
                        var id = datos['Id'];
                        var tipo = $('#selectActualizarTiposArchivos').val();
                        var nombre = $('#inputActualizarNombreArchivo').val();
                        var descripcion = $('#inputActualizarDescripcionArchivo').val();
                        var data = {id: id, nombre: nombre, descripcion: descripcion, tipo: tipo};
                        if (evento.validarFormulario('#formActualizarArchivo')) {
                            file.enviarArchivos('#inputActualizarEvidenciasArchivo', 'Archivos/ActualizarArchivo', '#seccionResumenArchivos', data, function (respuesta) {
                                if (respuesta instanceof Array) {
                                    $('.evidencia a').attr('href', respuesta[0].NombreArchivo);
                                    $('#idP').empty().append(respuesta[0].NombreArchivo.substring(30, 50));
                                    tabla.limpiarTabla('#data-table-actualizarArchivo');
                                    var columnas = archivosAdicionalesTabla();
                                    tabla.generaTablaPersonal('#data-table-actualizarArchivo', respuesta, columnas, true, true, [[0, 'desc']]);
                                    file.limpiar('#inputActualizarEvidenciasArchivo');
                                    evento.mostrarMensaje('.errorActualizarArchivo', true, 'Datos actualizados correctamente', 3000);
                                } else {
                                    evento.mostrarMensaje('.errorActualizarArchivo', false, 'Falta el campo Agregar otro Archivo', 3000);
                                }
                            });
                        }
                    });
                    //Evento para regresar
                    $('#btnRegresarArchivo').on('click', function (e) {
                        location.reload();
                    });
                });
            } else {
                console.log('no tiene permisos para editar');
            }
        });
    });
    var datosNuevosTabla = function () {
        var columnas = [
            {data: 'Id'},
            {data: 'Nombre'},
            {data: 'Fecha'},
            {data: null,
                sClass: 'Archivo',
                render: function (data, type, row, meta) {
                    return '<a href = "' + data.Url + '">Descargar</a>';
                }
            }
        ];
        return columnas;
    }
    var archivosAdicionalesTabla = function () {
        var columnas = [
            {data: 'Id'},
            {data: 'Nombre'},
            {data: 'Fecha'},
            {data: null,
                sClass: 'Archivo',
                render: function (data, type, row, meta) {
                    return '<a href = "' + data.Url + '">' + data.Url.substring(30, 50) + '</a>';
                }
            }
        ];
        return columnas;
    }
});