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

    //Creando tabla de lineas
    tabla.generaTablaPersonal('#data-table-lineas', null, null, true);

    //Evento para mostrar la ayuda del sistema
    evento.mostrarAyuda('Ayuda_Proyectos');

    //Inicializa funciones de la plantilla
    App.init();

    //Evento que actualizar al personal
    $('#btnAgregarLinea').on('click', function () {
        evento.enviarEvento('Catalogo/MostrarFormularioLinea', '', '#seccionLineas', function (respuesta) {            
            $('#listaLineas').addClass('hidden');
            $('#formularioLinea').removeClass('hidden').empty().append(respuesta.formulario);
            //Evento que genera un nueva linea
            $('#btnNuevaLinea').on('click', function () {
                var nombre = $('#inputNombreLinea').val();
                var descripcion = $('#inputDescripcionLinea').val();
                var activacion;
                var data = {nombre: nombre, descripcion: descripcion};
                console.log(data);
                if (evento.validarFormulario('#formNuevaLinea')) {
                    evento.enviarEvento('Catalogo/NuevaLinea', data, '#seccionLinea', function (respuesta) {
                        if (respuesta instanceof Array) {
                            tabla.limpiarTabla('#data-table-lineas');
                            $.each(respuesta, function (key, valor) {
                                if (valor.Flag === '1') {
                                    activacion = 'Activo';
                                } else {
                                    activacion = 'Inactivo';
                                }
                                tabla.agregarFila('#data-table-lineas', [valor.Id, valor.Nombre, valor.Descripcion, activacion]);
                            });
                            evento.limpiarFormulario('#formNuevaLinea');
                            $('#formularioLinea').addClass('hidden');
                            $('#listaLineas').removeClass('hidden');
                            evento.mostrarMensaje('.errorListaLinea', true, 'Datos insertados correctamente', 3000);
                        } else {
                            evento.mostrarMensaje('.errorLinea', false, 'Ya existe la línea, por lo que ya no puedes repetirlo.', 3000);
                        }
                    });
                }
            });
            $('#btnCancelar').on('click', function () {
                $('#formularioLinea').empty().addClass('hidden');
                $('#listaLineas').removeClass('hidden');
            });
        });
    });

    //Evento que permite actualizar el linea
    $('#data-table-lineas tbody').on('click', 'tr', function () {
        var datos = $('#data-table-lineas').DataTable().row(this).data();
        var html = '<div class="row">';
        html += '<form class="margin-bottom-0" id="formActualizarNuevaLinea" data-parsley-validate="true" >';
        html += '  <input type="hidden" id="inputId" value="' + datos[0] + '"/> ';
        html += '   <div class="col-md-12">';
        html += '      <div class="form-group">';
        html += '           <label for="nombreActualizarLinea">Nombre *</label> ';
        html += '           <input type="text" class="form-control" id="inputActualizarNombre" placeholder="Ingresa el nombre de la línea" value="' + datos[1] + '" data-parsley-required="true"/> ';
        html += '       </div>';
        html += '   </div>';
        html += '   <div class="col-md-12">';
        html += '       <div class="form-group">';
        html += '           <label for="nombreActualizarLinea">Descripción *</label> ';
        html += '           <input type="text" class="form-control" id="inputActulizarDescripcion" placeholder="Descripción breve de la línea" value="' + datos[2] + '" data-parsley-required="true"/> ';
        html += '       </div>';
        html += '   </div>';
        html += '   <div class="col-md-4">';
        html += '           <div class="form-group">';
        html += '               <label for="selectEstatusLinea">Estatus</label>';
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
        html += '       <div class="errorActualizarLinea"></div>';
        html += '   </div>';
        html += '</form>'
        html += '</div>';
        html += '';
        evento.mostrarModal('Actualizar Linea', html);
        select.crearSelect('select');
        $('#btnModalConfirmar').empty().append('Guardar');
        $('#btnModalConfirmar').off('click');
        $('#btnModalConfirmar').on('click', function () {
            var nombre = $('#inputActualizarNombre').val();
            var descripcion = $('#inputActulizarDescripcion').val();
            var estatus = $('#selectActualizarEstatus').val();
            var activacion;
            if (evento.validarFormulario('#formActualizarNuevaLinea')) {
                var data = {id: datos[0], nombre: nombre, descripcion: descripcion, estatus: estatus};
                evento.enviarEvento('Catalogo/ActualizarLinea', data, '#modal-dialogo', function (respuesta) {
                    if (respuesta instanceof Array) {
                        tabla.limpiarTabla('#data-table-lineas');
                        $.each(respuesta, function (key, value) {
                            if (value.Flag === '1') {
                                activacion = 'Activo';
                            } else {
                                activacion = 'Inactivo';
                            }
                            tabla.agregarFila('#data-table-lineas', [value.Id, value.Nombre, value.Descripcion, activacion]);
                        });
                        $('#modal-dialogo').modal('hide');
                        evento.mostrarMensaje('.errorListaLinea', true, 'Datos insertados correctamente', 3000);
                    } else {
                        evento.mostrarMensaje('.errorActualizarLinea', false, 'Ya existe la línea, por lo que ya no puedes repetirlo.', 3000);
                    }
                });
            }
        });
    });
});


