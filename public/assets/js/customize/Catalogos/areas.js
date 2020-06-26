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

    //Creando tabla de areas
    tabla.generaTablaPersonal('#data-table-areas', null, null, true);

    //Evento para mostrar la ayuda del sistema
    evento.mostrarAyuda('Ayuda_Proyectos');

    //Inicializa funciones de la plantilla
    App.init();

    //Evento que actualizar al personal
    $('#btnAgregarArea').on('click', function () {
        evento.enviarEvento('EventoCatalogoArea/MostrarFormularioArea', '', '#seccionAreas', function (respuesta) {
            $('#listaAreas').addClass('hidden');
            $('#formularioArea').removeClass('hidden').empty().append(respuesta.formulario);
            //Evento que genera un nueva area
            $('#btnNuevaArea').on('click', function () {
                var nombre = $('#inputNombreArea').val();
                var descripcion = $('#inputDescripcionArea').val();
                var activacion;
                var data = {nombre: nombre, descripcion: descripcion};
                if (evento.validarFormulario('#formNuevaArea')) {
                    evento.enviarEvento('EventoCatalogoArea/Nueva_Area', data, '#seccionArea', function (respuesta) {
                        if (respuesta instanceof Array) {
                            tabla.limpiarTabla('#data-table-areas');
                            $.each(respuesta, function (key, valor) {
                                if (valor.Flag === '1') {
                                    activacion = 'Activo';
                                } else {
                                    activacion = 'Inactivo';
                                }
                                tabla.agregarFila('#data-table-areas', [valor.Id, valor.Nombre, valor.Descripcion, activacion]);
                            });
                            evento.limpiarFormulario('#formNuevaArea');
                            $('#formularioArea').addClass('hidden');
                            $('#listaAreas').removeClass('hidden');
                            evento.mostrarMensaje('.errorListaArea', true, 'Datos insertados correctamente', 3000);
                        } else {
                            evento.mostrarMensaje('.errorArea', false, 'Ya existe el Área, por lo que ya no puedes repetirlo.', 3000);
                        }
                    });
                }
            });
            $('#btnCancelar').on('click', function () {
                $('#formularioArea').empty().addClass('hidden');
                $('#listaAreas').removeClass('hidden');
            });
        });
    });

    //Evento que permite actualizar el area
    $('#data-table-areas tbody').on('click', 'tr', function () {
        var datos = $('#data-table-areas').DataTable().row(this).data();
        var html = '<div class="row">';
        html += '<form class="margin-bottom-0" id="formActualizarNuevaArea" data-parsley-validate="true" >';
        html += '  <input type="hidden" id="inputId" value="' + datos[0] + '"/> ';
        html += '   <div class="col-md-12">';
        html += '      <div class="form-group">';
        html += '           <label for="nombreActualizarArea">Nombre *</label> ';
        html += '           <input type="text" class="form-control" id="inputActualizarNombre" placeholder="Ingresa el nombre del área" value="' + datos[1] + '" data-parsley-required="true"/> ';
        html += '       </div>';
        html += '   </div>';
        html += '   <div class="col-md-12">';
        html += '       <div class="form-group">';
        html += '           <label for="nombreActualizarArea">Descripción *</label> ';
        html += '           <input type="text" class="form-control" id="inputActulizarDescripcion" placeholder="Descripción breve de que trata el área" value="' + datos[2] + '" data-parsley-required="true"/> ';
        html += '       </div>';
        html += '   </div>';
        html += '   <div class="col-md-4">';
        html += '           <div class="form-group">';
        html += '               <label for="selectEstatusArea">Estatus</label>';
        html += '               <select id="selectActualizarEstatus" class="form-control" style="width: 100%" required>';
        if (datos[3] === 'Activo') {
            html += '                       <option value="1" selected>Activo</option>';
            html += '                       <option value="0">Inactivo</option>';
        } else {
            html += '                       <option value="1">Activo</option>';
            html += '                       <option value="0" selected>Inactivo</option>';
        }
        html += '               </select>';
        html += '           </div>';
        html += '   </div>';
        html += '   <div class="col-md-12">';
        html += '       <div class="errorActualizarArea"></div>';
        html += '   </div>';
        html += '</form>'
        html += '</div>';
        html += '';
        evento.mostrarModal('Actualizar Area', html);
        select.crearSelect('select');
        $('#btnModalConfirmar').empty().append('Guardar');
        $('#btnModalConfirmar').off('click');
        $('#btnModalConfirmar').on('click', function () {
            var nombre = $('#inputActualizarNombre').val();
            var descripcion = $('#inputActulizarDescripcion').val();
            var estatus = $('#selectActualizarEstatus').val();
            var activacion;
            if (evento.validarFormulario('#formActualizarNuevaArea')) {
                var data = {id: datos[0], nombre: nombre, descripcion: descripcion, estatus: estatus};
                evento.enviarEvento('EventoCatalogoArea/Actualizar_Area', data, '#modal-dialogo', function (respuesta) {
                    if (respuesta instanceof Array) {
                        tabla.limpiarTabla('#data-table-areas');
                        $.each(respuesta, function (key, value) {
                            if (value.Flag === '1') {
                                activacion = 'Activo';
                            } else {
                                activacion = 'Inactivo';
                            }
                            tabla.agregarFila('#data-table-areas', [value.Id, value.Nombre, value.Descripcion, activacion]);
                        });
                        $('#modal-dialogo').modal('hide');
                        evento.mostrarMensaje('.errorListaArea', true, 'Datos insertados correctamente', 3000);
                    } else {
                        evento.mostrarMensaje('.errorActualizarArea', false, 'Ya existe el Área, por lo que ya no puedes repetirlo.', 3000);
                    }
                });
            }
        });
    });
});


