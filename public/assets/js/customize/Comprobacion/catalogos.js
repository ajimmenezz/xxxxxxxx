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
    //Evento para mostrar la ayuda del sistema
    evento.mostrarAyuda('Ayuda_Proyectos');
    //Inicializa funciones de la plantilla
    App.init();

    //Creando tabla de sistemas especiales
    tabla.generaTablaPersonal('#table-conceptos', null, null, true);
    tabla.generaTablaPersonal('#table-usuarios-ff', null, null, true);

    //Evento que genera un nuevo concepto de fondo fijo
    $('#btnAddConcepto').off('click');
    $('#btnAddConcepto').on('click', function () {
        cargaFormularioAgregarConcepto(0);
    });

    $('#table-conceptos tbody').on('click', 'tr', function () {
        var datos = $('#table-conceptos').DataTable().row(this).data();
        cargaFormularioAgregarConcepto(datos[0], this);
    });

    $('#table-usuarios-ff tbody').on('click', 'tr', function () {
        var datos = $('#table-usuarios-ff').DataTable().row(this).data();
        cargaFormularioAgregarFondoFijo(datos[0], this);
    });

    $("#btnAddFFxUsuario").off("click");
    $("#btnAddFFxUsuario").on("click", function () {
        cargaFormularioAgregarFondoFijo();
    });

    function cargaFormularioAgregarFondoFijo() {
        var datos = {
            'id': arguments[0] || 0
        }

        var _fila = arguments[1];

        var title = (datos.id != 0) ? 'Editar Fondo Fijo' : 'Agregar Fondo Fijo';
        evento.enviarEvento('Catalogos/FormularioAgregarFondoFijo', datos, '#panel-catalogos', function (respuesta) {
            evento.iniciarModal('#modalEdit', title, respuesta.formulario);
            $("#listUsuariosFF").combobox();

            $("#btnGuardarCambios").off("click");
            $("#btnGuardarCambios").on("click", function () {
                var _datos = {
                    'usuario': $("#listUsuariosFF").val(),
                    'monto': $.trim($("#txtMontoFF").val())
                }


                if (_datos.usuario != '' && parseFloat(_datos.monto) > 0) {
                    evento.enviarEvento('Catalogos/AgregarFondoFijo', _datos, '#modalEdit', function (respuesta) {
                        if (respuesta.code == 200) {

                            if (respuesta.existe == 1) {
                                $('#table-usuarios-ff tbody tr').each(function () {
                                    var _fila = $('#table-usuarios-ff').DataTable().row(this).data();
                                    if ($.trim(_fila[0]) == $.trim(respuesta.id)) {
                                        tabla.eliminarFila("#table-usuarios-ff", this);
                                    }
                                });
                                evento.mostrarMensaje("#errorMessage", true, "El monto se actualizó para el usuario seleccionado.", 4000);
                            } else {
                                evento.mostrarMensaje("#errorMessage", true, "Se ha agregado el registro cprrectamente.", 4000);
                            }

                            evento.terminarModal("#modalEdit");
                            var htmlFila = `
                                        <tr>
                                            <td>` + respuesta.fila.Id + `</td>
                                            <td>` + respuesta.fila.Usuario + `</td>¿
                                            <td>$` + respuesta.fila.Monto + `</td>                                            
                                            <td>` + respuesta.fila.Estatus + `</td>
                                        </tr>
                            `;
                            tabla.agregarFilaHtml("#table-usuarios-ff", htmlFila);
                            tabla.reordenarTabla("#table-usuarios-ff", [1, 'asc']);
                        } else {
                            evento.mostrarMensaje("#error-in-modal", false, "Ocurrió un error al guardar el Fondo Fijo. Intente de nuevo o contacte al Administrador.", 4000);
                        }
                    });
                } else {
                    evento.mostrarMensaje("#error-in-modal", false, "Debe definir el usuario y el monto", 4000);
                }
            });

            $("#btnInhabilitarFF").off("click");
            $("#btnInhabilitarFF").on("click", function () {
                var _datos = {
                    'id': $(this).attr("data-id")
                }
                evento.enviarEvento('Catalogos/InhabilitarFF', _datos, '#modalEdit', function (respuesta) {
                    if (respuesta.code == 200) {
                        tabla.eliminarFila("#table-usuarios-ff", _fila);
                        var htmlFila = `
                                        <tr>
                                            <td>` + respuesta.fila.Id + `</td>
                                            <td>` + respuesta.fila.Usuario + `</td>¿
                                            <td>$` + respuesta.fila.Monto + `</td>                                            
                                            <td>` + respuesta.fila.Estatus + `</td>
                                        </tr>
                            `;
                        tabla.agregarFilaHtml("#table-usuarios-ff", htmlFila);
                        tabla.reordenarTabla("#table-usuarios-ff", [1, 'asc']);
                        evento.terminarModal('#modalEdit');
                        evento.mostrarMensaje("#errorMessage", true, "El fondo fijo para el usuario se ha inhabilitado.", 4000);
                    } else {
                        evento.mostrarMensaje("#error-in-modal", false, "Ocurrió un error al inhabilitar el Fondo Fijo. Intente de nuevo o contacte al Administrador.", 4000);
                    }
                });
            });

            $("#btnHabilitarFF").off("click");
            $("#btnHabilitarFF").on("click", function () {
                var _datos = {
                    'id': $(this).attr("data-id")
                }
                evento.enviarEvento('Catalogos/HabilitarFF', _datos, '#modalEdit', function (respuesta) {
                    if (respuesta.code == 200) {
                        tabla.eliminarFila("#table-usuarios-ff", _fila);
                        var htmlFila = `
                                        <tr>
                                            <td>` + respuesta.fila.Id + `</td>
                                            <td>` + respuesta.fila.Usuario + `</td>¿
                                            <td>$` + respuesta.fila.Monto + `</td>                                            
                                            <td>` + respuesta.fila.Estatus + `</td>
                                        </tr>
                            `;
                        tabla.agregarFilaHtml("#table-usuarios-ff", htmlFila);
                        tabla.reordenarTabla("#table-usuarios-ff", [1, 'asc']);
                        evento.terminarModal('#modalEdit');
                        evento.mostrarMensaje("#errorMessage", true, "El fondo fijo para el usuario se habilitó.", 4000);
                    } else {
                        evento.mostrarMensaje("#error-in-modal", false, "Ocurrió un error al habilitar el Fondo Fijo. Intente de nuevo o contacte al Administrador.", 4000);
                    }
                });
            });
        });
    }

    function cargaFormularioAgregarConcepto() {
        var datos = {
            'id': arguments[0] || 0
        }
        var _fila = arguments[1] || '';
        evento.enviarEvento('Catalogos/FormularioAgregarConcepto', datos, '#panel-catalogos', function (respuesta) {
            $("#divAgregarEditar").empty().append(respuesta.html);
            evento.cambiarDiv("#seccionCatalogos", "#divAgregarEditar");
            select.crearSelect("select");
            tabla.generaTablaPersonal('#table-alternativas', null, null, true, false, [], null, null, false);

            initBtnDeleteAlternativas();

            $("#btnAgregarAlternativa").off("click");
            $("#btnAgregarAlternativa").on("click", function () {
                var _datos = {
                    'usuario': $("#listUsuariosAlternativas").val(),
                    'usuarioString': $("#listUsuariosAlternativas option:selected").text(),
                    'sucursal': $("#listSucursalesAlternativas").val(),
                    'sucursalString': $("#listSucursalesAlternativas option:selected").text(),
                    'monto': $.trim($("#txtMontoAlternativo").val())
                }

                if (_datos.monto <= 0) {
                    evento.mostrarMensaje('#errorMessageAlternativas', false, 'El monto NO puede ser menor que 0 (cero).', 3000);
                } else if (_datos.usuario == "" && _datos.sucursal == "") {
                    evento.mostrarMensaje('#errorMessageAlternativas', false, 'Sebe seleccionar el usuario o la sucursal para agregar la alternativa.', 3000);
                } else {
                    var insertar = true;
                    $("#table-alternativas tbody tr").each(function () {
                        var _datosFila = $('#table-alternativas').DataTable().row(this).data();
                        if (typeof _datosFila !== 'undefined') {

                            if (_datos.usuario != "" && _datos.sucursal == "") {
                                if (_datos.usuario == _datosFila[1] && (_datosFila[2] == "" || _datosFila[2] == "0")) {
                                    insertar = false;
                                    return true;
                                }
                            }

                            if (_datos.usuario == "" && _datos.sucursal != "") {
                                if (_datos.sucursal == _datosFila[2] && (_datosFila[1] == "" || _datosFila[1] == "0")) {
                                    insertar = false;                                    
                                    return true;
                                }
                            }

                            if (_datos.usuario != "" && _datos.sucursal != "") {
                                if (_datos.usuario == _datosFila[1] && _datos.sucursal == _datosFila[2]) {
                                    insertar = false;                                   
                                    return true;
                                }
                            }

                        }
                    });
                    if (!insertar) {
                        evento.mostrarMensaje('#errorMessageAlternativas', false, 'Ya existe una alternativa similar a la que desea agregar. Por favor revise la información.', 3000);
                        console.log(_where);
                    } else {
                        _datos.usuarioString = (_datos.usuario != "") ? _datos.usuarioString : '';
                        _datos.sucursalString = (_datos.sucursal != "") ? _datos.sucursalString : '';
                        var htmlFila = `
                                        <tr>
                                            <td></td>
                                            <td>` + _datos.usuario + `</td>
                                            <td>` + _datos.sucursal + `</td>
                                            <td>` + _datos.monto + `</td>
                                            <td>` + _datos.usuarioString + `</td>
                                            <td>` + _datos.sucursalString + `</td>
                                            <td>$` + _datos.monto + `</td>
                                            <td class="text-center"><span role="button" class="label label-danger text-white btnDeleteAlternativas"><i class="fa fa-trash"></i></span></td>
                                        </tr>`;
                        tabla.agregarFilaHtml("#table-alternativas", htmlFila);
                        select.cambiarOpcion("#listUsuariosAlternativas", "");
                        select.cambiarOpcion("#listSucursalesAlternativas", "");
                        $("#txtMontoAlternativo").val("");
                    }
                }

                initBtnDeleteAlternativas();
            });

            $("#btnGuardarConcepto").off("click");
            $("#btnGuardarConcepto").on("click", function () {
                if (evento.validarFormulario('#form-agregar-editar-concepto')) {
                    var comprobantes = [];
                    $(".checkTiposComprobante").each(function () {
                        if ($(this).is(":checked")) {
                            comprobantes.push($(this).val());
                        }
                    });
                    var _datosAlternativos = [];
                    var dataTable = tabla.getTableData("#table-alternativas");
                    $.each(dataTable, function (k, v) {
                        if (!isNaN(k)) {
                            _datosAlternativos.push({
                                'id': v[0],
                                'usuario': v[1],
                                'sucursal': v[2],
                                'monto': v[3]
                            });
                        }
                    });
                    var _datos = {
                        'id': datos.id,
                        'concepto': $.trim($("#txtConcepto").val()),
                        'monto': $.trim($("#txtMonto").val()),
                        'extraordinario': $("input[name='radioExtraordinario']:checked").val(),
                        'comprobantes': comprobantes,
                        'alternativos': _datosAlternativos
                    }

                    evento.enviarEvento('Catalogos/AgregarConcepto', _datos, '#panelAgregarConcepto', function (respuesta) {
                        if (respuesta.code == 200) {
                            if (_fila != '') {
                                tabla.eliminarFila("#table-conceptos", _fila);
                            }

                            var htmlFila = `
                                        <tr>
                                            <td>` + respuesta.fila.Id + `</td>
                                            <td>` + respuesta.fila.Nombre + `</td>
                                            <td>` + respuesta.fila.Comprobante + `</td>
                                            <td>` + respuesta.fila.Extraordinario + `</td>
                                            <td>$` + respuesta.fila.Monto + `</td>
                                            <td>` + respuesta.fila.Alternativos + `</td>
                                            <td>` + respuesta.fila.Estatus + `</td>
                                        </tr>
                            `;
                            tabla.agregarFilaHtml("#table-conceptos", htmlFila);
                            tabla.reordenarTabla("#table-conceptos", [1, 'asc']);
                            evento.cambiarDiv("#divAgregarEditar", "#seccionCatalogos");
                            evento.mostrarMensaje("#errorMessage", true, 'El concepto se agregó correctamente', 3000);
                            initBtnDeleteAlternativas();
                        } else {
                            evento.mostrarMensaje("#errorMessageConcepto", false, 'Error al agregar el concepto. Intente de nuevo o contacte al administrador.', 3000);
                        }
                    });
                }
            });

            $("#btnInhabilitarConcepto").off("click");
            $("#btnInhabilitarConcepto").on("click", function () {
                $("#page-loader").removeClass('hide');
                var _datos = {
                    'id': $(this).attr("data-id")
                }
                evento.enviarEvento('Catalogos/InhabilitarConcepto', _datos, '#', function (respuesta) {
                    if (respuesta.code == 200) {
                        tabla.eliminarFila("#table-conceptos", _fila);
                        var htmlFila = `
                                        <tr>
                                            <td>` + respuesta.fila.Id + `</td>
                                            <td>` + respuesta.fila.Nombre + `</td>
                                            <td>` + respuesta.fila.Comprobante + `</td>
                                            <td>` + respuesta.fila.Extraordinario + `</td>
                                            <td>$` + respuesta.fila.Monto + `</td>
                                            <td>` + respuesta.fila.Alternativos + `</td>
                                            <td>` + respuesta.fila.Estatus + `</td>
                                        </tr>
                            `;
                        tabla.agregarFilaHtml("#table-conceptos", htmlFila);
                        tabla.reordenarTabla("#table-conceptos", [1, 'asc']);
                        evento.cambiarDiv("#divAgregarEditar", "#seccionCatalogos");
                        evento.mostrarMensaje("#errorMessage", true, "El concepto '" + respuesta.fila.Nombre + "' se ha inhabilitado.", 4000);
                        $("#page-loader").addClass('hide');
                    } else {
                        evento.mostrarMensaje(".errorMessageConcepto", false, "Ocurrió un error al inhabilitar el Concepto. Intente de nuevo o contacte al Administrador.", 4000);
                        $("#page-loader").addClass('hide');
                    }
                });
            });

            $("#btnHabilitarConcepto").off("click");
            $("#btnHabilitarConcepto").on("click", function () {
                $("#page-loader").removeClass('hide');
                var _datos = {
                    'id': $(this).attr("data-id")
                }
                evento.enviarEvento('Catalogos/HabilitarConcepto', _datos, '#', function (respuesta) {
                    if (respuesta.code == 200) {
                        tabla.eliminarFila("#table-conceptos", _fila);
                        var htmlFila = `
                                        <tr>
                                            <td>` + respuesta.fila.Id + `</td>
                                            <td>` + respuesta.fila.Nombre + `</td>
                                            <td>` + respuesta.fila.Comprobante + `</td>
                                            <td>` + respuesta.fila.Extraordinario + `</td>
                                            <td>$` + respuesta.fila.Monto + `</td>
                                            <td>` + respuesta.fila.Alternativos + `</td>
                                            <td>` + respuesta.fila.Estatus + `</td>
                                        </tr>
                            `;
                        tabla.agregarFilaHtml("#table-conceptos", htmlFila);
                        tabla.reordenarTabla("#table-conceptos", [1, 'asc']);
                        evento.cambiarDiv("#divAgregarEditar", "#seccionCatalogos");
                        evento.mostrarMensaje("#errorMessage", true, "El concepto '" + respuesta.fila.Nombre + "' se habilitó.", 4000);
                        $("#page-loader").addClass('hide');
                    } else {
                        evento.mostrarMensaje(".errorMessageConcepto", false, "Ocurrió un error al habilitar el Concepto. Intente de nuevo o contacte al Administrador.", 4000);
                        $("#page-loader").addClass('hide');
                    }
                });
            });
        });

    }

    function initBtnDeleteAlternativas() {
        $(".btnDeleteAlternativas").off("click");
        $(".btnDeleteAlternativas").on("click", function () {
            tabla.eliminarFila("#table-alternativas", $(this).closest("tr"));
        });
    }



    /* AQUI COMIENZA EL CÖDIGO COPIADO */
    /* AQUI COMIENZA EL CÖDIGO COPIADO */
    /* AQUI COMIENZA EL CÖDIGO COPIADO */
    /* AQUI COMIENZA EL CÖDIGO COPIADO */
    /* AQUI COMIENZA EL CÖDIGO COPIADO */

    $('#table-conceptos-99 tbody').on('click', 'tr', function () {
        var datos = $('#table-conceptos.99').DataTable().row(this).data();
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


