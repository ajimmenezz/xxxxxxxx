$(function () {
    //Objetos
    var evento = new Base();
    var websocket = new Socket();
    var tabla = new Tabla();
    var select = new Select();

    //Evento que maneja las peticiones del socket
    websocket.socketMensaje();

    //Muestra la hora en el sistema
    evento.horaServidor($('#horaServidor').val());

    //Evento para cerra la session
    evento.cerrarSesion();

    //Inicializa funciones de la plantilla
    App.init();

    //Geneando la tabla de tareas asignadas
    tabla.generaTablaPersonal('#data-table-proyectos-asignados', null, null, true);

    //Evento sobre el renglon de la tabla para mostrar el detalle del proyecto
    $('#data-table-proyectos-asignados tbody').on('click', 'tr', function () {
        var datos = $('#data-table-proyectos-asignados').DataTable().row(this).data();
        var data = {proyecto: datos[0]};

        evento.enviarEvento('Seguimiento/Datos_Proyecto', data, '#seccionSeguimientoProyecto', function (respuesta) {
            var nombreProyecto = respuesta.informacion.datosProyecto[0].Nombre;
            var Ticket = respuesta.informacion.datosProyecto[0].Ticket;
            console.log(respuesta);
            $('#seccionDetallesProyecto').removeClass('hidden').empty().append(respuesta.formulario);
            $('#seccionListaProyectos').addClass('hidden');

            select.crearSelect('#selectTipoProyecto');
            select.cambiarOpcion('#selectTipoProyecto', respuesta.informacion.datosProyecto[0].Tipo);
            select.crearSelect('#selectComplejo');
            select.cambiarOpcion('#selectComplejo', respuesta.informacion.datosProyecto[0].Sucursal);
            select.crearSelect('#selectLideres');
            select.cambiarOpcion('#selectLideres', respuesta.informacion.datosProyecto.lideres);
            $('#fecha-inicial input').val(respuesta.informacion.datosProyecto[0].FechaInicio);
            $('#fecha-termino input').val(respuesta.informacion.datosProyecto[0].FechaTermino);

            //Inciando tablas
            tabla.generaTablaPersonal('#data-table-proyectos-alcance', null, null, true);
            tabla.generaTablaPersonal('#data-table-proyectos-material', null, null, true);
            tabla.generaTablaPersonal('#data-table-proyectos-personal', null, null, true);
            tabla.generaTablaPersonal('#data-table-proyectos-tareas', null, null, true);

            //Evento para regresar a la lista de proyectos
            $('#btnRegresarProyectos').on('click', function () {
                $('#seccionListaProyectos').removeClass('hidden');
                $('#seccionDetallesProyecto').addClass('hidden').empty();
            });

            //Evento para el seguiemnto de una tarea
            $('#data-table-proyectos-tareas tbody').on('click', 'tr', function () {
                var datos = $('#data-table-proyectos-tareas').DataTable().row(this).data();
                var data = {tarea: datos[0]};
                var html, asistentes = [], mostrarAsistentes = '';
                evento.enviarEvento('Seguimiento/Seguimiento_Tarea', data, '#seccionSeguimientoProyecto', function (respuesta) {
                    console.log(respuesta);
                    if (respuesta.datosTarea.Estatus === '1') {
                        $.each(respuesta.datosTarea.asistentes, function (key, value) {
                            if (value.asistente !== null) {
                                asistentes.push(value.asistente);
                            }
                        });

                        if (asistentes.length > 0) {
                            mostrarAsistentes = asistentes.toString();
                        } else {
                            mostrarAsistentes = 'No se han definido asistentes para esta tarea.';
                        }

                        html = '<div class="row">\n\
                                    <div class="col-md-4">\n\
                                         <div class="form-group">\n\
                                            <label for="tarea">Tarea</label>\n\
                                            <p>' + datos[3] + '</p>\n\
                                         </div>\n\
                                    </div>\n\
                                    <div class="col-md-4">\n\
                                         <div class="form-group">\n\
                                            <label for="Estatus">Estatus</label>\n\
                                            <p>Abierto</p>\n\
                                         </div>\n\
                                    </div>\n\
                                    <div class="col-md-4">\n\
                                         <div class="form-group">\n\
                                            <label for="Duracion">Fecha Inicio - Termino</label>\n\
                                            <p>' + datos[5] + ' - ' + datos[6] + '</p>\n\
                                         </div>\n\
                                    </div>\n\
                                </div>\n\
                                <div class="row">\n\
                                    <div class="col-md-12">\n\
                                         <div class="form-group">\n\
                                            <label for="Asistentes">Asistentes</label>\n\
                                            <p>' + mostrarAsistentes + '</p>\n\
                                            <p></p>\n\
                                         </div>\n\
                                    </div>\n\
                                </div>\n\
                                ';
                        evento.mostrarModal('Seguimiento Tarea', html);
                        $('#btnModalConfirmar').empty().append('Iniciar Tarea');
                        $('#btnModalAbortar').empty().append('No Iniciar');


                        $('#btnModalConfirmar').off('click');
                        $('#btnModalConfirmar').on('click', function () {
                            var data = {tarea: datos[0], operacion: '1'};
                            evento.enviarEvento('Seguimiento/Actualizar_Tarea', data, '#modal-dialogo', function (respuesta) {
                                console.log(respuesta);
                                if (respuesta) {
                                    html = '<div class="row">\n\
                                    <div class="col-md-4  text-center">\n\
                                         <div class="form-group">\n\
                                            <p>Se inicio con exito la tarea.</p>\n\
                                         </div>\n\
                                    </div>';
                                } else {
                                    html = '<div class="row">\n\
                                    <div class="col-md-4">\n\
                                         <div class="form-group text-center">\n\
                                            <p>No se pudo inciar la tarea por favor de volver a intentarlo.</p>\n\
                                         </div>\n\
                                    </div>';
                                }
                                $('.modal-body').empty().append(html);
                                $('#btnModalConfirmar').empty().addClass('hidden');
                                $('#btnModalAbortar').empty().append('Cerrar');
                                $('#seccionDetallesProyecto').addClass('hidden');
                                $('#seccionSeguimientoTarea').empty().removeClass('hidden').append(respuesta.formulario);
                            });
                        });
                    } else if (respuesta.datosTarea.Estatus === '2') {
                        $('#seccionDetallesProyecto').addClass('hidden');
                        $('#seccionSeguimientoTarea').empty().removeClass('hidden').append(respuesta.formulario);
                    }
                    
                    datosSeguimientotarea(nombreProyecto);
                    
                    
                    //Inicia tabla para agregar dias de actividad
                    tabla.generaTablaPersonal('#data-table-tarea-diasActividad', null, null, true);

                    //Evento para regresar a la informacion del proyecto
                    $('#btnRegresarProyecto').on('click', function () {
                        $('#seccionDetallesProyecto').removeClass('hidden');
                        $('#seccionSeguimientoTarea').empty();
                    });


                });
            });
        });
    });

    //Cargar datos en el formulario
    var datosSeguimientotarea = function () {
        var nombreProyecto = arguments[0];
        var Proyecto = arguments[1];

        $('#nombreProyecto').append(nombreProyecto);
    };
});


