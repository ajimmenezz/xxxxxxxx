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

    //Evento para mostrar la ayuda del sistema
    evento.mostrarAyuda('Ayuda_Proyectos');

    //Inicializa funciones de la plantilla
    App.init();

    tabla.generaTablaPersonal('#table-usuarios', null, null, true, true, [[1, 'asc']]);

    $('#table-usuarios tbody').on('click', 'tr', function () {
        let _this = this;
        var datosTabla = $('#table-usuarios').DataTable().row(_this).data();
        var datos = {
            'id': datosTabla[0],
            'nombre': datosTabla[1]
        };

        evento.enviarEvento('Depositar/FormularioDepositar', datos, '#panelDepositar', function (respuesta) {
            if (respuesta.code == 200) {
                $("#formularioDepositar").empty().append(respuesta.formulario);
                evento.cambiarDiv("#listaUsuariosFondoFijo", "#formularioDepositar");
                initDepositar(datos);
            } else {
                evento.mostrarMensaje("#errorMessage", false, respuesta.error, 4000);
            }
        });
    });

    function initDepositar(datos) {
        datos.tipoCuenta = '';
        $("#listTiposCuenta").on("change", function () {
            var tipoCuenta = $(this).val();
            datos.tipoCuenta = tipoCuenta;
            if (tipoCuenta == "") {
                $("#montoMaximoAutorizado, #saldoActual, #montoSugerido").empty().append("$");
                $("#txtMontoDepositar").val("").attr("disabled", "disabled");
            } else {
                evento.enviarEvento('Depositar/MontosDepositar', datos, '#panelDepositar', function (respuesta) {
                    if (respuesta.code == 200) {
                        $("#montoMaximoAutorizado").empty().append('$' + respuesta.montos.montoMaximo);
                        $("#saldoActual").empty().append('$' + respuesta.montos.saldo);
                        $("#montoSugerido").empty().append('$' + respuesta.montos.sugerido);
                        $("#txtMontoDepositar").val(respuesta.montos.sugerido.replace(",", "")).removeAttr("disabled");
                    } else {
                        evento.mostrarMensaje("#errorMessage", false, respuesta.error, 4000);
                    }
                });
            }
        });

        $("#btnGuardarDeposito").off("click");
        $("#btnGuardarDeposito").on("click", function () {

            datos.depositar = parseFloat($.trim($("#txtMontoDepositar").val()));

            if (datos.tipoCuenta == "") {
                evento.mostrarMensaje("#errorMessage", false, "Debe seleccionar un tipo de cuenta", 4000);
                return false;
            }

            if (isNaN(datos.depositar) || datos.depositar <= 0) {
                evento.mostrarMensaje("#errorMessage", false, "El monto a depositar debe ser mayor a 0 (cero)", 4000);
                return false;
            }

            console.log(datos);


            evento.enviarEvento('Depositar/RegistrarDeposito', datos, '#panelDepositar', function (respuesta) {
                if (respuesta.code == 200) {
                    $("#montoMaximoAutorizado").empty().append('$' + respuesta.montos.montoMaximo);
                    $("#saldoActual").empty().append('$' + respuesta.montos.saldo);
                    $("#montoSugerido").empty().append('$' + respuesta.montos.sugerido);
                    $("#txtMontoDepositar").val(respuesta.montos.sugerido.replace(",", "")).removeAttr("disabled");
                } else {
                    evento.mostrarMensaje("#errorMessage", false, respuesta.error, 4000);
                }
            });
        });
    }


    /************************************************************************ */

    $("#btnAddTipoCuenta").off("click");
    $("#btnAddTipoCuenta").on("click", function () {
        if ($.trim($("#txtNuevoTipoCuenta").val()) !== '') {
            var datos = {
                'tipo': $.trim($("#txtNuevoTipoCuenta").val())
            };
            evento.enviarEvento('Catalogos/AgregarTipoCuenta', datos, '#panel-catalogos', function (respuesta) {
                if (respuesta.code == 200) {
                    tabla.agregarFila('#table-tipos-cuenta', [respuesta.id, '1', respuesta.tipo, 'Activo']);
                    tabla.reordenarTabla('#table-tipos-cuenta', [2, 'asc']);
                } else {
                    evento.mostrarMensaje("#errorMessage", false, respuesta.error, 4000);
                }
            });
        } else {
            evento.mostrarMensaje("#errorMessage", false, "Al parecer el campo está vacío.", 4000);
        }
    });

    $('#table-tipos-cuenta tbody').on('click', 'tr', function () {
        let _this = this;
        var datosTabla = $('#table-tipos-cuenta').DataTable().row(_this).data();
        var datos = {
            'id': datosTabla[0]
        };
        evento.enviarEvento('Catalogos/FormularioEditarTipo', datos, '#panel-catalogos', function (respuesta) {
            if (respuesta.code == 200) {
                evento.iniciarModal('#modalEdit', 'Editar Tipo de Cuenta', respuesta.formulario);

                $("#btnGuardarCambios").off("click");
                $("#btnGuardarCambios").on("click", function () {
                    if ($.trim($("#txtTipoCuenta").val()) !== '') {
                        var datos = {
                            'id': $('#idTipoCuenta').val(),
                            'tipo': $.trim($("#txtTipoCuenta").val()),
                            'estatus': $("#listEstatus").val()
                        };
                        evento.enviarEvento('Catalogos/EditarTipoCuenta', datos, '#modalEdit', function (respuesta) {
                            if (typeof respuesta !== 'undefined' && respuesta.code == 200) {
                                evento.terminarModal('#modalEdit');
                                $('#table-tipos-cuenta').DataTable().row(_this).data([respuesta.Id, respuesta.Flag, respuesta.Nombre, respuesta.Estatus]);
                                tabla.reordenarTabla('#table-tipos-cuenta', [2, 'asc']);
                                $('#table-tipos-cuenta').DataTable().page.jumpToData(respuesta.Id, 0);
                            } else {
                                evento.mostrarMensaje("#error-in-modal", false, respuesta.error, 4000);
                            }
                        });
                    } else {
                        evento.mostrarMensaje("#error-in-modal", false, "Al parecer el campo está vacío.", 4000);
                    }
                });

            } else {
                evento.mostrarMensaje("#errorMessage", false, respuesta.error, 4000);
            }
        });
    });

    $("#btnAddTipo").off("click");
    $("#btnAddTipo").on("click", function () {
        if ($.trim($("#txtNuevoTipo").val()) !== '') {
            var datos = {
                'tipo': $.trim($("#txtNuevoTipo").val())
            };
            evento.enviarEvento('Catalogos/AgregarTipo', datos, '#panel-catalogos', function (respuesta) {
                if (respuesta.code == 200) {
                    tabla.agregarFila('#table-tipos', [respuesta.id, '1', respuesta.tipo, 'Activo']);
                    tabla.reordenarTabla('#table-tipos', [2, 'asc']);
                    $('#table-tipos').DataTable().page.jumpToData(respuesta.id, 0);
                } else {
                    evento.mostrarMensaje("#errorMessage", false, respuesta.error, 4000);
                }
            });
        } else {
            evento.mostrarMensaje("#errorMessage", false, "Al parecer el campo está vacío.", 4000);
        }
    });



    function initGuardarMontos() {
        $("#btnGuardarMontos").off("click");
        $("#btnGuardarMontos").on("click", function () {
            var _montos = '[';

            $(".txtMonto").each(function () {
                _montos += '{"tipoCuenta":"' + $(this).attr("data-id") + '"';
                _montos += ',"monto":"' + $(this).val() + '"},';
            });

            if (_montos != '[') {
                _montos = _montos.slice(0, -1);
            }
            _montos += ']';

            var datos = {
                'id': $("#hiddenUserId").val(),
                'montos': _montos
            };

            evento.enviarEvento('Catalogos/GuardarMontos', datos, '#panel-catalogos', function (respuesta) {
                if (respuesta.code == 200) {
                    evento.mostrarMensaje("#errorMessage", true, "Se han guardado los cambios de los montos.", 4000);
                } else {
                    evento.mostrarMensaje("#errorMessage", false, respuesta.error, 4000);
                }
            });

        });
    }

    $('#btnAddConcepto').off('click');
    $('#btnAddConcepto').on('click', function () {
        cargaFormularioAgregarConcepto(0);
    });

    $('#table-conceptos tbody').on('click', 'tr', function () {
        var datos = $('#table-conceptos').DataTable().row(this).data();
        cargaFormularioAgregarConcepto(datos[0], this);
    });

    function cargaFormularioAgregarConcepto() {
        var datos = {
            'id': arguments[0] || 0
        }
        var _fila = arguments[1] || '';
        evento.enviarEvento('Catalogos/FormularioAgregarConcepto', datos, '#panel-catalogos', function (respuesta) {
            $("#formularioConceptos").empty().append(respuesta.html);
            evento.cambiarDiv("#listaConceptos", "#formularioConceptos");
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
                    var tiposCuenta = [];
                    $(".checkTiposCuenta").each(function () {
                        if ($(this).is(":checked")) {
                            tiposCuenta.push($(this).val());
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
                        'tiposCuenta': tiposCuenta,
                        'concepto': $.trim($("#txtConcepto").val()),
                        'monto': $.trim($("#txtMonto").val()),
                        'extraordinario': $("input[name='radioExtraordinario']:checked").val(),
                        'comprobantes': comprobantes,
                        'alternativos': _datosAlternativos
                    }

                    evento.enviarEvento('Catalogos/AgregarConcepto', _datos, '#panel-catalogos', function (respuesta) {
                        if (respuesta.code == 200) {
                            if (_fila != '') {
                                tabla.eliminarFila("#table-conceptos", _fila);
                            }

                            var htmlFila = `
                                        <tr>
                                            <td>` + respuesta.fila.Id + `</td>
                                            <td>` + respuesta.fila.Nombre + `</td>
                                            <td>` + respuesta.fila.Cuentas + `</td>
                                            <td>` + respuesta.fila.Comprobante + `</td>
                                            <td>` + respuesta.fila.Extraordinario + `</td>
                                            <td>$` + respuesta.fila.Monto + `</td>
                                            <td>` + respuesta.fila.Alternativos + `</td>
                                            <td>` + respuesta.fila.Estatus + `</td>
                                        </tr>
                            `;
                            tabla.agregarFilaHtml("#table-conceptos", htmlFila);
                            tabla.reordenarTabla("#table-conceptos", [1, 'asc']);
                            evento.cambiarDiv("#formularioConceptos", "#listaConceptos");
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
                                            <td>` + respuesta.fila.Cuentas + `</td>
                                            <td>` + respuesta.fila.Comprobante + `</td>
                                            <td>` + respuesta.fila.Extraordinario + `</td>
                                            <td>$` + respuesta.fila.Monto + `</td>
                                            <td>` + respuesta.fila.Alternativos + `</td>
                                            <td>` + respuesta.fila.Estatus + `</td>
                                        </tr>
                            `;
                        tabla.agregarFilaHtml("#table-conceptos", htmlFila);
                        tabla.reordenarTabla("#table-conceptos", [1, 'asc']);
                        evento.cambiarDiv("#formularioConceptos", "#listaConceptos");
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
                                            <td>` + respuesta.fila.Cuentas + `</td>
                                            <td>` + respuesta.fila.Comprobante + `</td>
                                            <td>` + respuesta.fila.Extraordinario + `</td>
                                            <td>$` + respuesta.fila.Monto + `</td>
                                            <td>` + respuesta.fila.Alternativos + `</td>
                                            <td>` + respuesta.fila.Estatus + `</td>
                                        </tr>
                            `;
                        tabla.agregarFilaHtml("#table-conceptos", htmlFila);
                        tabla.reordenarTabla("#table-conceptos", [1, 'asc']);
                        evento.cambiarDiv("#formularioConceptos", "#listaConceptos");
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
});



