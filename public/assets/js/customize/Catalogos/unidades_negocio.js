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

    //Creando tabla de areas
    tabla.generaTablaPersonal('#data-table-unidad-negocios', null, null, true);

    //Evento para mostrar la ayuda del sistema
    evento.mostrarAyuda('Ayuda_Proyectos');

    //Inicializa funciones de la plantilla
    App.init();

    //Evento que actualizar al personal
    $('#btnAgregarUnidadNegocio').on('click', function () {
        evento.enviarEvento('EventoCatalogoUnidadNegocio/MostrarFormularioUnidadNegocio', '', '#seccionUnidadesNegocio', function (respuesta) {
            $('#listaUnidadesNegocio').addClass('hidden');
            $('#formularioUnidadesNegocio').removeClass('hidden').empty().append(respuesta.formulario);
            //Evento que genera un nueva area
            $('#btnNuevaUnidadNegocio').on('click', function () {
                var nombre = $('#inputNombreUnidadNegocio').val();
                var activacion;
                var data = {nombre: nombre};
                if (nombre !== '') {
                    evento.enviarEvento('EventoCatalogoUnidadNegocio/Nueva_Unidad_Negocio', data, '#seccionUnidadesNegocio', function (respuesta) {
                        if (respuesta instanceof Array) {
                            tabla.limpiarTabla('#data-table-unidad-negocios');
                            $.each(respuesta, function (key, valor) {
                                if (valor.Flag === '1') {
                                    activacion = 'Activo';
                                } else {
                                    activacion = 'Inactivo';
                                }
                                tabla.agregarFila('#data-table-unidad-negocios', [valor.Id, valor.Nombre, activacion]);
                            });
                            $('#formularioUnidadesNegocio').addClass('hidden');
                            $('#listaUnidadesNegocio').removeClass('hidden');
                            evento.mostrarMensaje('.errorListaUnidadesNegocio', true, 'Datos insertados correctamente', 3000);
                        } else {
                            evento.mostrarMensaje('.errorListaUnidadesNegocio', false, 'Ya existe la unidad de negocio, por lo que ya no puedes repetirlo.', 3000);
                        }
                    });
                } else {
                    evento.mostrarMensaje('.errorUnidadNegocio', false, 'Falta el campo nombre.', 3000);
                }
            });
            $('#btnCancelar').on('click', function () {
                $('#formularioUnidadesNegocio').empty().addClass('hidden');
                $('#listaUnidadesNegocio').removeClass('hidden');
            });
        });
    });

    //Evento que permite actualizar el area
    $('#data-table-unidad-negocios tbody').on('click', 'tr', function () {
        var datos = $('#data-table-unidad-negocios').DataTable().row(this).data();
        var html = '<div class="row">';
        html += '<form class="margin-bottom-0" id="formActualizarNuevaUnidadNegocio" data-parsley-validate="true" >';
        html += '  <input type="hidden" id="inputId" value="' + datos[0] + '"/> ';
        html += '   <div class="col-md-12">';
        html += '      <div class="form-group">';
        html += '           <label for="nombreActualizarUnidadNegocio">Nombre *</label> ';
        html += '           <input type="text" class="form-control" id="inputActualizarNombreUnidadNegocio" placeholder="Ingresa el nombre de la unidad de negocio" value="' + datos[1] + '" data-parsley-required="true"/> ';
        html += '       </div>';
        html += '   </div>';
        html += '   <div class="col-md-12">';
        html += '           <div class="form-group">';
        html += '               <label for="selectEstatusUnidadNegocio">Estatus</label>';
        html += '               <select id="selectActualizarEstatusUnidadNegocio" class="form-control" style="width: 100%" required>';
        if (datos[2] === 'Activo') {
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
        html += '       <div class="errorActualizarUnidadNegocio"></div>';
        html += '   </div>';
        html += '</form>'
        html += '</div>';
        html += '';
        evento.mostrarModal('Actualizar Unidad de Negocio', html);
        select.crearSelect('select');
        $('#btnModalConfirmar').empty().append('Guardar');
        $('#btnModalConfirmar').off('click');
        $('#btnModalConfirmar').on('click', function () {
            var nombre = $('#inputActualizarNombreUnidadNegocio').val();
            var estatus = $('#selectActualizarEstatusUnidadNegocio').val();
            var activacion;
            if (evento.validarFormulario('#formActualizarNuevaUnidadNegocio')) {
                var data = {id: datos[0], nombre: nombre, estatus: estatus};
                evento.enviarEvento('EventoCatalogoUnidadNegocio/Actualizar_Unidad_Negocio', data, '#modal-dialogo', function (respuesta) {
                    if (respuesta instanceof Array) {
                        tabla.limpiarTabla('#data-table-unidad-negocios');
                        $.each(respuesta, function (key, value) {
                            if (value.Flag === '1') {
                                activacion = 'Activo';
                            } else {
                                activacion = 'Inactivo';
                            }
                            tabla.agregarFila('#data-table-unidad-negocios', [value.Id, value.Nombre, activacion]);
                        });
                        $('#modal-dialogo').modal('hide');
                        evento.mostrarMensaje('.errorListaUnidadesNegocio', true, 'Datos insertados correctamente', 3000);
                    } else {
                        evento.mostrarMensaje('.errorActualizarUnidadesNegocio', false, 'Ya existe la unidad de negocio, por lo que ya no puedes repetirlo.', 3000);
                    }
                });
            }
        });
    });
});


