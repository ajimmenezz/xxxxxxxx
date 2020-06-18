$(function () {
    //Objetos
    var evento = new Base();
    var websocket = new Socket();
    var tabla = new Tabla();

    //Evento que maneja las peticiones del socket
    websocket.socketMensaje();

    //Muestra la hora en el sistema
    evento.horaServidor($('#horaServidor').val());

    //Evento para cerra la session
    evento.cerrarSesion();

    //Creando tabla de areas
    tabla.generaTablaPersonal('#data-table-permisos', null, null, true, true);

    //Evento para mostrar la ayuda del sistema
    evento.mostrarAyuda('Ayuda_Proyectos');

    //Inicializa funciones de la plantilla
    App.init();

    //Evento que actualizar al personal
    $('#btnAgregarPermiso').on('click', function () {
        evento.enviarEvento('EventoCatalogo/MostrarFormularioPermisos', '', '#seccionPermisos', function (respuesta) {
            $('#listaPermisos').addClass('hidden');
            $('#formularioPermiso').removeClass('hidden').empty().append(respuesta.formulario);
            //Evento que genera un nuevo de permiso
            $('#btnNuevoPermiso').on('click', function () {
                var nombre = $('#inputNombrePermiso').val();
                var permiso = $('#inputPermiso').val();
                var descripcion = $('#inputDescripcionPermiso').val();
                var data = {nombre: nombre, permiso: permiso, descripcion: descripcion};
                if (evento.validarFormulario('#formNuevoPermisos')) {
                    evento.enviarEvento('EventoCatalogo/Nuevo_Permiso', data, '#seccionPermisos', function (respuesta) {
                        if (respuesta) {
                            tabla.limpiarTabla('#data-table-permisos');
                            $.each(respuesta, function (key, valor) {
                                tabla.agregarFila('#data-table-permisos', [valor.Id, valor.Nombre, valor.Permiso, valor.Descripcion]);
                            });
                            evento.limpiarFormulario('#formNuevoPermisos');
                            $('#formularioPermiso').addClass('hidden');
                            $('#listaPermisos').removeClass('hidden');
                            evento.mostrarMensaje('.errorListaPermisos', true, 'Datos insertados correctamente', 3000);
                        } else {
                            evento.mostrarMensaje('.errorPermiso', false, 'Ya existe el Permiso, por lo que ya no puedes repetirlo.', 3000);
                        }
                    });
                }
            });
            $('#btnCancelarPermiso').on('click', function () {
                $('#formularioPermiso').empty().addClass('hidden');
                $('#listaPermisos').removeClass('hidden');
            });
        });
    });
    
    //Evento que permite actualizar el tipo de proyecto
    $('#data-table-permisos tbody').on('click', 'tr', function () {
        var datos = $('#data-table-permisos').DataTable().row(this).data();
        var html = '<div class="row">';
        html += '<form class="margin-bottom-0" id="formActualizarNuevoPermisos" data-parsley-validate="true" >';
        html += '  <input type="hidden" id="inputId" value="' + datos[0] + '"/> ';
        html += '   <div class="col-md-12">';
        html += '      <div class="form-group">';
        html += '           <label for="nombreActualizarPermiso">Nombre *</label> ';
        html += '           <input type="text" class="form-control" id="inputActualizarNombre" placeholder="Ingresa el nombre del permiso" value="' + datos[1] + '" data-parsley-required="true"/> ';
        html += '       </div>';
        html += '   </div>';
        html += '   <div class="col-md-12">';
        html += '      <div class="form-group">';
        html += '           <label for="nombreActualizarPermiso">Permiso *</label> ';
        html += '           <input type="text" class="form-control" id="inputActualizarPermiso" placeholder="Ingresa permiso" value="' + datos[2] + '" data-parsley-required="true"/> ';
        html += '       </div>';
        html += '   </div>';
        html += '   <div class="col-md-12">';
        html += '       <div class="form-group">';
        html += '           <label for="nombreActualizarPermiso">Descripción *</label> ';
        html += '           <input type="text" class="form-control" id="inputActulizarDescripcion" placeholder="Descripción breve de que trata el permiso" value="' + datos[3] + '" data-parsley-required="true"/> ';
        html += '       </div>';
        html += '   </div>';
        html += '   <div class="col-md-12">';
        html += '       <div class="errorActualizarPermiso"></div>';
        html += '   </div>';
        html += '</form>'
        html += '</div>';
        html += '';
        evento.mostrarModal('Actualizar Permiso', html);
        $('#btnModalConfirmar').empty().append('Guardar');
        $('#btnModalConfirmar').off('click');
        $('#btnModalConfirmar').on('click', function () {
            var nombre = $('#inputActualizarNombre').val();
            var permiso = $('#inputActualizarPermiso').val();
            var descripcion = $('#inputActulizarDescripcion').val();
            if (evento.validarFormulario('#formActualizarNuevoPermisos')) {
                var data = {id: datos[0], nombre: nombre, permiso: permiso, descripcion: descripcion};
                evento.enviarEvento('EventoCatalogo/Actualizar_Permiso', data, '#modal-dialogo', function (respuesta) {
                    if (respuesta instanceof Array) {
                        tabla.limpiarTabla('#data-table-permisos');
                        $.each(respuesta, function (key, value) {
                            tabla.agregarFila('#data-table-permisos', [value.Id, value.Nombre, value.Permiso, value.Descripcion]);
                        });
                        $('#modal-dialogo').modal('hide');
                        evento.mostrarMensaje('.errorListaPermisos', true, 'Datos insertados correctamente', 3000);
                    } else {
                        evento.mostrarMensaje('.errorActualizarPermiso', false, 'Ya existe el Permiso, por lo que ya no puedes repetirlo.', 3000);
                    }
                });
            }
        });
    });
});