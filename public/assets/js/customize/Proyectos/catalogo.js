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

    //Creando tabla de sistemas especiales
    tabla.generaTablaPersonal('#data-table-sistemasEspeciales', null, null, true);

    //Creando tabla de sistemas especiales
    tabla.generaTablaPersonal('#data-table-tarea', null, null, true);

    //Evento para mostrar la ayuda del sistema
    evento.mostrarAyuda('Ayuda_Proyectos');

    //Inicializa funciones de la plantilla
    App.init();

    //Evento que genera un nuevo de tipo de sistema especial
    $('#btnNuevoTipo').on('click', function () {
        var nuevoTipo = $('#inputNombreSistemaEspecial').val();
        var descripcion = $('#inputDescripcionSistemaEspecial').val();
        var activacion;

        if (nuevoTipo !== '' && descripcion !== '') {
            var data = {tipo: nuevoTipo, descripcion: descripcion};
            evento.enviarEvento('EventoCatalogo/Nuevo_Tipo', data, '#seccionTipoServicio', function (respuesta) {
                if (respuesta instanceof Array) {
                    tabla.limpiarTabla('#data-table-sistemasEspeciales');
                    var datos = [];
                    $.each(respuesta, function (key, valor) {
                        datos[key] = {id: valor.Id, text: valor.Nombre};
                        if (valor.Flag === '1') {
                            activacion = 'Activo';
                        } else {
                            activacion = 'Desactivo';
                        }
                        tabla.agregarFila('#data-table-sistemasEspeciales', [valor.Id, valor.Nombre.toUpperCase(), valor.Descripcion, activacion]);
                    });
                    select.cargaDatos('#selectSistemaEspecial', datos);
                    $('#inputNombreSistemaEspecial').val(null);
                    $('#inputDescripcionSistemaEspecial').val(null);
                } else {
                    evento.mostrarMensaje('.errorTipoProyecto', false, 'Ya existe el tipo de proyecto por lo que ya no puedes repetirlo.', 3000);
                }

            });
        } else {
            evento.mostrarMensaje('.errorTipoProyecto', false, 'Debes llenar todos los campos', 3000);
        }
    });

    //Evento que permite actualizar el tipo de proyecto
    $('#data-table-sistemasEspeciales tbody').on('click', 'tr', function () {
        var datos = $('#data-table-sistemasEspeciales').DataTable().row(this).data();
        var html = '<div class="row">';
        html += '   <div class="col-md-12">';
        html += '      <div class="form-group">';
        html += '           <label for="nombreActualizarSistema">Nombre</label> ';
        html += '           <input type="text" class="form-control" id="inputActualizarNombre" placeholder="Ingresa el nuevo sistema" value="' + datos[1] + '"/> ';
        html += '       </div>';
        html += '   </div>';
        html += '   <div class="col-md-12">';
        html += '       <div class="form-group">';
        html += '           <label for="nombreActualizarSistema">Descripción</label> ';
        html += '           <input type="text" class="form-control" id="inputActulizarDescripcion" placeholder="Descripción breve de que trata el sistema" value="' + datos[2] + '"/> ';
        html += '       </div>';
        html += '   </div>';
        html += '   <div class="col-md-12">';
        html += '           <div class="form-group">';
        html += '               <label for="selectEstatusSistemaEspecial">Estatus</label>';
        html += '               <select id="selectActualizarEstatus" class="form-control" style="width: 100%" required>';
        if (datos[3] === 'Activo') {
            html += '<option value="1" selected>Activo</option>';
            html += '<option value="0">Desactivo</option>';
        } else {
            html += '<option value="1">Activo</option>';
            html += '<option value="0" selected>Desactivo</option>';
        }
        html += '               </select>';
        html += '           </div>';
        html += '   </div>';
        html += '   <div class="col-md-12">';
        html += '       <div class="errorActualizarTipoProyecto"></div>';
        html += '   </div>';
        html += '</div>';
        html += '';
        evento.mostrarModal('Actualizar Sistema Especial', html);
        $('#btnModalConfirmar').empty().append('Guardar');
        select.crearSelect('#selectActualizarEstatus');

        $('#btnModalConfirmar').off('click');

        $('#btnModalConfirmar').on('click', function () {
            var nombre = $('#inputActualizarNombre').val();
            var descripcion = $('#inputActulizarDescripcion').val();
            var estatus = $('#selectActualizarEstatus').val();
            var activacion;
            if (nombre !== '' && descripcion !== '') {
                var data = {id: datos[0], nombre: nombre, descripcion: descripcion, estatus: estatus};
                evento.enviarEvento('EventoCatalogo/Actualizar_Tipo', data, '#modal-dialogo', function (respuesta) {
                    if (respuesta instanceof Array) {
                        tabla.limpiarTabla('#data-table-sistemasEspeciales');
                        $.each(respuesta, function (key, value) {
                            if (value.Flag === '1') {
                                activacion = 'Activo';
                            } else {
                                activacion = 'Desactivo';
                            }
                            tabla.agregarFila('#data-table-sistemasEspeciales', [value.Id, value.Nombre.toUpperCase(), value.Descripcion, activacion]);
                        });
                        $('#modal-dialogo').modal('hide');
                    } else {
                        evento.mostrarMensaje('.errorActualizarTipoProyecto', false, 'Ya existe este tipo de sistema especial.', 3000);
                    }

                });
            } else {
                evento.mostrarMensaje('.errorActualizarTipoProyecto', false, 'Debes llenar todos los campos', 3000);
            }
        });
    });

    //Evento que genera una nueva tarea
    $('#btnNuevaTarea').on('click', function () {
        var tipoProyecto = $('#selectSistemaEspecial').val();
        var nuevaTarea = $('#inputNombreTareaProyecto').val();
        var activacion;

        if (tipoProyecto !== '' && nuevaTarea !== '') {
            var data = {tipo: tipoProyecto, tarea: nuevaTarea};
            evento.enviarEvento('EventoCatalogo/Nueva_Tarea', data, '#seccionTareas', function (respuesta) {
                if (respuesta instanceof Array) {
                    tabla.limpiarTabla('#data-table-tarea');
                    $.each(respuesta, function (key, value) {
                        if (value.Flag === '1') {
                            activacion = 'Activo';
                        } else {
                            activacion = 'Desactivo';
                        }
                        tabla.agregarFila('#data-table-tarea', [value.Id, value.Nombre.toUpperCase(), value.Tipo.toUpperCase(), activacion]);
                    });
                    select.cambiarOpcion('#selectSistemaEspecial', '');
                    $('#inputNombreTareaProyecto').val(null);
                } else {
                    evento.mostrarMensaje('.errorTarea', false, 'Ya esta registrada la tarea que quiere agregar en el tipo de sistema', 3000);
                }

            });
        } else {
            evento.mostrarMensaje('.errorTarea', false, 'Debes llenar todos los campos', 3000);
        }
    });

    //Evento que permite actualizar el tipo de tarea
    $('#data-table-tarea tbody').on('click', 'tr', function () {
        var datos = $('#data-table-tarea').DataTable().row(this).data();
        evento.enviarEvento('EventoCatalogo/Obtener_Tipo', {}, '#seccionTareas', function (respuesta) {
            var html = '<div class="row">';
            html += '   <div class="col-md-6">';
            html += '       <div class="form-group">';
            html += '           <label for="selectSistemaEspecial">Sistema Especial</label> ';
            html += '           <select id="selectActualizarSistema" class="form-control" style="width: 100%" required>';
            html += '               <option value="">Seleccionar</option>';
            $.each(respuesta, function (key, item) {
                if (item.Nombre === datos[2]) {
                    html += '<option value="' + item.Id + '" selected>' + item.Nombre + '</option>';
                } else {
                    html += '<option value="' + item.Id + '">' + item.Nombre + '</option>';
                }
            });
            html += '           </select>';
            html += '       </div> ';
            html += '   </div>';
            html += '   <div class="col-md-6">';
            html += '       <div class="form-group">';
            html += '           <label for="nombreTareaSistema">Nombre</label> ';
            html += '           <input type="text" class="form-control" id="inputActualizarTarea" placeholder="Ingresa la nueva tarea" value="' + datos[1] + '"/> ';
            html += '       </div> ';
            html += '   </div>';
            html += '</div>';
            html += '<div class="row">';
            html += '   <div class="col-md-6">';
            html += '       <div class="form-group">';
            html += '               <label for="selectActEstatusTareaSistema">Estatus</label>';
            html += '               <select id="selectActualizarEstatus" class="form-control" style="width: 100%" required>';
            if (datos[3] === 'Activo') {
                html += '<option value="1" selected>Activo</option>';
                html += '<option value="0">Desactivo</option>';
            } else {
                html += '<option value="1">Activo</option>';
                html += '<option value="0" selected>Desactivo</option>';
            }
            html += '               </select>';
            html += '       </div> ';
            html += '   </div>';
            html += '   <div class="col-md-12">';
            html += '       <div class="errorActualizarTipoProyecto"></div>';
            html += '   </div>';
            html += '</div>';
            evento.mostrarModal('Actualizar Tarea', html);
            $('#btnModalConfirmar').empty().append('Guardar');
            select.crearSelect('#selectActualizarSistema');
            select.crearSelect('#selectActualizarEstatus');

            $('#btnModalConfirmar').off('click');

            $('#btnModalConfirmar').on('click', function () {
                var tipoProyecto = $('#selectActualizarSistema').val();
                var nuevoTarea = $('#inputActualizarTarea').val();
                var estatus = $('#selectActualizarEstatus').val();
                var activacion;

                if (tipoProyecto !== '' && nuevoTarea !== '') {
                    var data = {};
                    data = {id: datos[0], tipo: tipoProyecto, nombre: nuevoTarea, estatus: estatus};
                    evento.enviarEvento('EventoCatalogo/Actualizar_Tarea', data, '#modal-dialogo', function (respuesta) {
                        if (respuesta instanceof Array) {
                            tabla.limpiarTabla('#data-table-tarea');
                            $.each(respuesta, function (key, value) {
                                if (value.Flag === '1') {
                                    activacion = 'Activo';
                                } else {
                                    activacion = 'Desactivo';
                                }
                                tabla.agregarFila('#data-table-tarea', [value.Id, value.Nombre.toUpperCase(), value.Tipo.toUpperCase(), activacion]);
                            });
                            $('#modal-dialogo').modal('hide');
                        } else {
                            evento.mostrarMensaje('.errorActualizarTipoProyecto', false, 'Ya esta registrada la tarea en el tipo de sistema especial.', 3000);
                        }
                    });
                } else {
                    evento.mostrarMensaje('.errorActualizarTipoProyecto', false, 'Debes llenar todos los campos', 3000);
                }
            });
        });


    });

});


