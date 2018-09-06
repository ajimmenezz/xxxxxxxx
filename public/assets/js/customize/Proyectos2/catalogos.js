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

    tabla.generaTablaPersonal('#table-sistemas', null, null, true, true, [[2, 'asc']]);
    tabla.generaTablaPersonal('#table-tipos', null, null, true, true, [[2, 'asc']]);
    tabla.generaTablaPersonal('#table-conceptos', null, null, true, true, [[3, 'asc']]);
    tabla.generaTablaPersonal('#table-areas', null, null, true, true, [[3, 'asc']]);
    tabla.generaTablaPersonal('#table-ubicaciones', null, null, true, true, [[3, 'asc']]);
    tabla.generaTablaPersonal('#table-accesorios', null, null, true, true, [[3, 'asc']]);
    tabla.generaTablaPersonal('#table-material', null, null, true, true, [[3, 'asc']]);
    tabla.generaTablaPersonal('#table-kits', null, null, true, true, [[1, 'asc']]);

    $("#btnAddSistema").off("click");
    $("#btnAddSistema").on("click", function () {
        if ($.trim($("#txtNuevoSistema").val()) !== '') {
            var datos = {
                'sistema': $.trim($("#txtNuevoSistema").val())
            };
            evento.enviarEvento('Catalogos/AgregarSistema', datos, '#panel-catalogos', function (respuesta) {
                if (respuesta.code == 200) {
                    tabla.agregarFila('#table-sistemas', [respuesta.id, '1', respuesta.sistema, 'Activo']);
                    tabla.reordenarTabla('#table-sistemas', [2, 'asc']);
                } else {
                    evento.mostrarMensaje("#errorMessage", false, respuesta.error, 4000);
                }
            });
        } else {
            evento.mostrarMensaje("#errorMessage", false, "Al parecer el campo está vacío.", 4000);
        }
    });

    $('#table-sistemas tbody').on('dblclick', 'tr', function () {
        let _this = this;
        var datosTabla = $('#table-sistemas').DataTable().row(_this).data();
        var datos = {
            'id': datosTabla[0]
        };
        evento.enviarEvento('Catalogos/FormularioEditarSistema', datos, '#panel-catalogos', function (respuesta) {
            if (respuesta.code == 200) {
                evento.iniciarModal('#modalEdit', 'Editar Sistema', respuesta.formulario);

                $("#btnGuardarCambios").off("click");
                $("#btnGuardarCambios").on("click", function () {
                    if ($.trim($("#txtSistema").val()) !== '') {
                        var datos = {
                            'id': $('#idSistema').val(),
                            'sistema': $.trim($("#txtSistema").val()),
                            'estatus': $("#listEstatus").val()
                        };
                        evento.enviarEvento('Catalogos/EditarSistema', datos, '#modalEdit', function (respuesta) {
                            if (typeof respuesta !== 'undefined' && respuesta.code == 200) {
                                evento.terminarModal('#modalEdit');
                                $('#table-sistemas').DataTable().row(_this).data([respuesta.Id, respuesta.Flag, respuesta.Nombre, respuesta.Estatus]);
                                tabla.reordenarTabla('#table-sistemas', [2, 'asc']);
                                $('#table-sistemas').DataTable().page.jumpToData(respuesta.Id, 0);
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

    $('#table-tipos tbody').on('dblclick', 'tr', function () {
        let _this = this;
        var datosTabla = $('#table-tipos').DataTable().row(_this).data();
        var datos = {
            'id': datosTabla[0]
        };
        evento.enviarEvento('Catalogos/FormularioEditarTipo', datos, '#panel-catalogos', function (respuesta) {
            if (respuesta.code == 200) {
                evento.iniciarModal('#modalEdit', 'Editar Tipo de Proyecto', respuesta.formulario);

                $("#btnGuardarCambios").off("click");
                $("#btnGuardarCambios").on("click", function () {
                    if ($.trim($("#txtTipo").val()) !== '') {
                        var datos = {
                            'id': $('#idTipo').val(),
                            'tipo': $.trim($("#txtTipo").val()),
                            'estatus': $("#listEstatus").val()
                        };
                        evento.enviarEvento('Catalogos/EditarTipo', datos, '#modalEdit', function (respuesta) {
                            if (typeof respuesta !== 'undefined' && respuesta.code == 200) {
                                evento.terminarModal('#modalEdit');
                                $('#table-tipos').DataTable().row(_this).data([respuesta.Id, respuesta.Flag, respuesta.Nombre, respuesta.Estatus]);
                                tabla.reordenarTabla('#table-tipos', [2, 'asc']);
                                $('#table-tipos').DataTable().page.jumpToData(respuesta.Id, 0);
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

    $("#btnAddConcepto").off("click");
    $("#btnAddConcepto").on("click", function () {
        evento.enviarEvento('Catalogos/FormularioAgregarConcepto', {}, '#panel-catalogos', function (respuesta) {
            if (respuesta.code == 200) {
                evento.iniciarModal('#modalEdit', 'Agregar Concepto de Sistema', respuesta.formulario);

                $("#listSistemas").combobox();

                $("#btnGuardarCambios").off("click");
                $("#btnGuardarCambios").on("click", function () {
                    if ($.trim($("#txtNuevoConcepto").val()) !== '') {
                        var datos = {
                            'concepto': $.trim($("#txtNuevoConcepto").val()),
                            'sistema': $("#listSistemas").val()
                        };
                        evento.enviarEvento('Catalogos/AgregarConcepto', datos, '#modalEdit', function (respuesta) {
                            if (typeof respuesta !== 'undefined' && respuesta.code == 200) {
                                evento.terminarModal('#modalEdit');
                                tabla.agregarFila('#table-conceptos', [respuesta.Id, respuesta.IdSistema, respuesta.Flag, respuesta.Nombre, respuesta.Sistema, respuesta.Estatus]);
                                tabla.reordenarTabla('#table-conceptos', [2, 'asc']);
                                $('#table-conceptos').DataTable().page.jumpToData(respuesta.Id, 0);
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

    $('#table-conceptos tbody').on('dblclick', 'tr', function () {
        let _this = this;
        var datosTabla = $('#table-conceptos').DataTable().row(_this).data();
        var datos = {
            'id': datosTabla[0]
        };
        evento.enviarEvento('Catalogos/FormularioEditarConcepto', datos, '#panel-catalogos', function (respuesta) {
            if (respuesta.code == 200) {
                evento.iniciarModal('#modalEdit', 'Editar Concepto', respuesta.formulario);

                $("#btnGuardarCambios").off("click");
                $("#btnGuardarCambios").on("click", function () {
                    if ($.trim($("#txtConcepto").val()) !== '') {
                        var datos = {
                            'id': $('#idConcepto').val(),
                            'concepto': $.trim($("#txtConcepto").val()),
                            'sistema': $("#listSistemas").val(),
                            'estatus': $("#listEstatus").val()
                        };
                        evento.enviarEvento('Catalogos/EditarConcepto', datos, '#modalEdit', function (respuesta) {
                            if (typeof respuesta !== 'undefined' && respuesta.code == 200) {
                                evento.terminarModal('#modalEdit');
                                $('#table-conceptos').DataTable().row(_this).data([respuesta.Id, respuesta.IdSistema, respuesta.Flag, respuesta.Nombre, respuesta.Sistema, respuesta.Estatus]);
                                tabla.reordenarTabla('#table-conceptos', [3, 'asc']);
                                $('#table-conceptos').DataTable().page.jumpToData(respuesta.Id, 0);
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

    $("#btnAddArea").off("click");
    $("#btnAddArea").on("click", function () {
        evento.enviarEvento('Catalogos/FormularioAgregarArea', {}, '#panel-catalogos', function (respuesta) {
            if (respuesta.code == 200) {
                evento.iniciarModal('#modalEdit', 'Agregar Área por Concepto', respuesta.formulario);

                $("#listConceptos").combobox();

                $("#btnGuardarCambios").off("click");
                $("#btnGuardarCambios").on("click", function () {
                    if ($.trim($("#txtNuevaArea").val()) !== '') {
                        var datos = {
                            'area': $.trim($("#txtNuevaArea").val()),
                            'concepto': $("#listConceptos").val()
                        };
                        evento.enviarEvento('Catalogos/AgregarArea', datos, '#modalEdit', function (respuesta) {
                            if (typeof respuesta !== 'undefined' && respuesta.code == 200) {
                                evento.terminarModal('#modalEdit');
                                tabla.agregarFila('#table-areas', [respuesta.Id, respuesta.IdConcepto, respuesta.Flag, respuesta.Nombre, respuesta.Concepto, respuesta.Estatus]);
                                tabla.reordenarTabla('#table-areas', [3, 'asc']);
                                $('#table-areas').DataTable().page.jumpToData(respuesta.Id, 0);
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

    $('#table-areas tbody').on('dblclick', 'tr', function () {
        let _this = this;
        var datosTabla = $('#table-areas').DataTable().row(_this).data();
        var datos = {
            'id': datosTabla[0]
        };
        evento.enviarEvento('Catalogos/FormularioEditarArea', datos, '#panel-catalogos', function (respuesta) {
            if (respuesta.code == 200) {
                evento.iniciarModal('#modalEdit', 'Editar Área', respuesta.formulario);

                $("#btnGuardarCambios").off("click");
                $("#btnGuardarCambios").on("click", function () {
                    if ($.trim($("#txtArea").val()) !== '') {
                        var datos = {
                            'id': $('#idArea').val(),
                            'area': $.trim($("#txtArea").val()),
                            'concepto': $("#listConceptos").val(),
                            'estatus': $("#listEstatus").val()
                        };
                        evento.enviarEvento('Catalogos/EditarArea', datos, '#modalEdit', function (respuesta) {
                            if (typeof respuesta !== 'undefined' && respuesta.code == 200) {
                                evento.terminarModal('#modalEdit');
                                $('#table-areas').DataTable().row(_this).data([respuesta.Id, respuesta.IdConcepto, respuesta.Flag, respuesta.Nombre, respuesta.Concepto, respuesta.Estatus]);
                                tabla.reordenarTabla('#table-areas', [3, 'asc']);
                                $('#table-areas').DataTable().page.jumpToData(respuesta.Id, 0);
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

    $("#btnAddUbicacion").off("click");
    $("#btnAddUbicacion").on("click", function () {
        evento.enviarEvento('Catalogos/FormularioAgregarUbicacion', {}, '#panel-catalogos', function (respuesta) {
            if (respuesta.code == 200) {
                evento.iniciarModal('#modalEdit', 'Agregar Ubicación por Área', respuesta.formulario);

                $("#listAreas").combobox();

                $("#btnGuardarCambios").off("click");
                $("#btnGuardarCambios").on("click", function () {
                    if ($.trim($("#txtNuevaUbicacion").val()) !== '') {
                        var datos = {
                            'ubicacion': $.trim($("#txtNuevaUbicacion").val()),
                            'area': $("#listAreas").val()
                        };
                        evento.enviarEvento('Catalogos/AgregarUbicacion', datos, '#modalEdit', function (respuesta) {
                            if (typeof respuesta !== 'undefined' && respuesta.code == 200) {
                                evento.terminarModal('#modalEdit');
                                tabla.agregarFila('#table-ubicaciones', [respuesta.Id, respuesta.IdArea, respuesta.Flag, respuesta.Nombre, respuesta.Area, respuesta.Estatus]);
                                tabla.reordenarTabla('#table-ubicaciones', [3, 'asc']);
                                $('#table-ubicaciones').DataTable().page.jumpToData(respuesta.Id, 0);
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

    $('#table-ubicaciones tbody').on('dblclick', 'tr', function () {
        let _this = this;
        var datosTabla = $('#table-ubicaciones').DataTable().row(_this).data();
        var datos = {
            'id': datosTabla[0]
        };
        evento.enviarEvento('Catalogos/FormularioEditarUbicacion', datos, '#panel-catalogos', function (respuesta) {
            if (respuesta.code == 200) {
                evento.iniciarModal('#modalEdit', 'Editar Ubicación', respuesta.formulario);

                $("#listAreas").combobox();

                $("#btnGuardarCambios").off("click");
                $("#btnGuardarCambios").on("click", function () {
                    if ($.trim($("#txtUbicacion").val()) !== '') {
                        var datos = {
                            'id': $('#idUbicacion').val(),
                            'ubicacion': $.trim($("#txtUbicacion").val()),
                            'area': $("#listAreas").val(),
                            'estatus': $("#listEstatus").val()
                        };
                        evento.enviarEvento('Catalogos/EditarUbicacion', datos, '#modalEdit', function (respuesta) {
                            if (typeof respuesta !== 'undefined' && respuesta.code == 200) {
                                evento.terminarModal('#modalEdit');
                                $('#table-ubicaciones').DataTable().row(_this).data([respuesta.Id, respuesta.IdArea, respuesta.Flag, respuesta.Nombre, respuesta.Area, respuesta.Estatus]);
                                tabla.reordenarTabla('#table-ubicaciones', [3, 'asc']);
                                $('#table-ubicaciones').DataTable().page.jumpToData(respuesta.Id, 0);
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

    $("#btnAddAccesorio").off("click");
    $("#btnAddAccesorio").on("click", function () {
        evento.enviarEvento('Catalogos/FormularioAgregarAccesorio', {}, '#panel-catalogos', function (respuesta) {
            if (respuesta.code == 200) {
                evento.iniciarModal('#modalEdit', 'Agregar Accesorio de Sistema', respuesta.formulario);

                $("#listSistemas").combobox();

                $("#btnGuardarCambios").off("click");
                $("#btnGuardarCambios").on("click", function () {
                    if ($.trim($("#txtNuevoAccesorio").val()) !== '') {
                        var datos = {
                            'accesorio': $.trim($("#txtNuevoAccesorio").val()),
                            'sistema': $("#listSistemas").val()
                        };
                        evento.enviarEvento('Catalogos/AgregarAccesorio', datos, '#modalEdit', function (respuesta) {
                            if (typeof respuesta !== 'undefined' && respuesta.code == 200) {
                                evento.terminarModal('#modalEdit');
                                tabla.agregarFila('#table-accesorios', [respuesta.Id, respuesta.IdSistema, respuesta.Flag, respuesta.Nombre, respuesta.Sistema, respuesta.Estatus]);
                                tabla.reordenarTabla('#table-accesorios', [3, 'asc']);
                                $('#table-accesorios').DataTable().page.jumpToData(respuesta.Id, 0);
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

    $('#table-accesorios tbody').on('dblclick', 'tr', function () {
        let _this = this;
        var datosTabla = $('#table-accesorios').DataTable().row(_this).data();
        var datos = {
            'id': datosTabla[0]
        };
        evento.enviarEvento('Catalogos/FormularioEditarAccesorio', datos, '#panel-catalogos', function (respuesta) {
            if (respuesta.code == 200) {
                evento.iniciarModal('#modalEdit', 'Editar Accesorio', respuesta.formulario);

                $("#btnGuardarCambios").off("click");
                $("#btnGuardarCambios").on("click", function () {
                    if ($.trim($("#txtAccesorio").val()) !== '') {
                        var datos = {
                            'id': $('#idAccesorio').val(),
                            'accesorio': $.trim($("#txtAccesorio").val()),
                            'sistema': $("#listSistemas").val(),
                            'estatus': $("#listEstatus").val()
                        };
                        evento.enviarEvento('Catalogos/EditarAccesorio', datos, '#modalEdit', function (respuesta) {
                            if (typeof respuesta !== 'undefined' && respuesta.code == 200) {
                                evento.terminarModal('#modalEdit');
                                $('#table-accesorios').DataTable().row(_this).data([respuesta.Id, respuesta.IdSistema, respuesta.Flag, respuesta.Nombre, respuesta.Sistema, respuesta.Estatus]);
                                tabla.reordenarTabla('#table-accesorios', [3, 'asc']);
                                $('#table-accesorios').DataTable().page.jumpToData(respuesta.Id, 0);
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

    $("#btnAddMaterial").off("click");
    $("#btnAddMaterial").on("click", function () {
        evento.enviarEvento('Catalogos/FormularioAgregarMaterial', {}, '#panel-catalogos', function (respuesta) {
            if (respuesta.code == 200) {
                evento.iniciarModal('#modalEdit', 'Agregar Material por Accesorio', respuesta.formulario);

                $("#listAccesorios").combobox();
                $("#listMaterial").combobox();

                $("#btnGuardarCambios").off("click");
                $("#btnGuardarCambios").on("click", function () {
                    if ($("#listAccesorios").val() !== '' && $("listMaterial").val() !== '') {
                        var datos = {
                            'accesorio': $("#listAccesorios").val(),
                            'material': $("#listMaterial").val()
                        };
                        evento.enviarEvento('Catalogos/AgregarMaterial', datos, '#modalEdit', function (respuesta) {
                            if (typeof respuesta !== 'undefined' && respuesta.code == 200) {
                                evento.terminarModal('#modalEdit');
                                tabla.agregarFila('#table-material', [respuesta.Id, respuesta.IdMaterial, respuesta.IdAccesorio, respuesta.Material, respuesta.Accesorio]);
                                tabla.reordenarTabla('#table-material', [3, 'asc']);
                                $('#table-material').DataTable().page.jumpToData(respuesta.Id, 0);
                            } else {
                                evento.mostrarMensaje("#error-in-modal", false, respuesta.error, 4000);
                            }
                        });
                    } else {
                        evento.mostrarMensaje("#error-in-modal", false, "Al parecer no ha seleccionado el material o el accesorio.", 4000);
                    }
                });

            } else {
                evento.mostrarMensaje("#errorMessage", false, respuesta.error, 4000);
            }
        });
    });

    $('#table-material tbody').on('dblclick', 'tr', function () {
        let _this = this;
        var datosTabla = $('#table-material').DataTable().row(_this).data();
        var datos = {
            'id': datosTabla[0]
        };
        evento.enviarEvento('Catalogos/FormularioEditarMaterial', datos, '#panel-catalogos', function (respuesta) {
            if (respuesta.code == 200) {
                evento.iniciarModal('#modalEdit', 'Editar Material', respuesta.formulario);

                $("#listAccesorios").combobox();
                $("#listMaterial").combobox();

                $("#btnGuardarCambios").off("click");
                $("#btnGuardarCambios").on("click", function () {
                    if ($("#listAccesorios").val() !== '' && $("listMaterial").val() !== '') {
                        var datos = {
                            'id': $("#idMaterial").val(),
                            'accesorio': $("#listAccesorios").val(),
                            'material': $("#listMaterial").val()
                        };
                        evento.enviarEvento('Catalogos/EditarMaterial', datos, '#modalEdit', function (respuesta) {
                            if (typeof respuesta !== 'undefined' && respuesta.code == 200) {
                                evento.terminarModal('#modalEdit');
                                $('#table-material').DataTable().row(_this).data([respuesta.Id, respuesta.IdMaterial, respuesta.IdAccesorio, respuesta.Material, respuesta.Accesorio]);
                                tabla.reordenarTabla('#table-material', [3, 'asc']);
                                $('#table-material').DataTable().page.jumpToData(respuesta.Id, 0);
                            } else {
                                evento.mostrarMensaje("#error-in-modal", false, respuesta.error, 4000);
                            }
                        });
                    } else {
                        evento.mostrarMensaje("#error-in-modal", false, "Al parecer no ha seleccionado el material o el accesorio.", 4000);
                    }
                });

            } else {
                evento.mostrarMensaje("#errorMessage", false, respuesta.error, 4000);
            }
        });
    });

    $("#btnAddKit").off("click");
    $("#btnAddKit").on("click", function () {
        evento.enviarEvento('Catalogos/FormularioAgregarKit', {}, '#panel-catalogos', function (respuesta) {
            $("#divKitMaterial").empty().append(respuesta.formulario);

            $("#listMaterial").combobox();

            $("#seccionCatalogos").fadeOut(400, function () {
                $("#divKitMaterial").fadeIn(400);
            });

            $("#divKitMaterial #btnRegresar").off("click");
            $("#divKitMaterial #btnRegresar").on("click", function () {
                $("#divKitMaterial").fadeOut(400, function () {
                    $("#seccionCatalogos").fadeIn(400, function () {
                        $("#divKitMaterial").empty();
                    });
                });
            });
            initKitActions();
        });
    });

    $('#table-kits tbody').on('dblclick', 'tr', function () {
        let _this = this;
        var datosTabla = $('#table-kits').DataTable().row(_this).data();
        var datos = {
            'id': datosTabla[0]
        };
        evento.enviarEvento('Catalogos/FormularioEditarKit', datos, '#panel-catalogos', function (respuesta) {
            if (respuesta.code == 200) {
                $("#divKitMaterial").empty().append(respuesta.formulario);

                $("#listMaterial").combobox();

                $("#seccionCatalogos").fadeOut(400, function () {
                    $("#divKitMaterial").fadeIn(400);
                });

                $("#divKitMaterial #btnRegresar").off("click");
                $("#divKitMaterial #btnRegresar").on("click", function () {
                    $("#divKitMaterial").fadeOut(400, function () {
                        $("#seccionCatalogos").fadeIn(400, function () {
                            $("#divKitMaterial").empty();
                        });
                    });
                });
                initKitActions(_this);
            } else {
                evento.mostrarMensaje("#errorMessage", false, respuesta.error, 4000);
            }
        });
    });

    function initKitActions() {
        var _fila = arguments[0] || false;
        $("#btnAddMaterialKit").off("click");
        $("#btnAddMaterialKit").on("click", function () {
            var _idMaterial = $("#listMaterial").val();
            var _material = $("#listMaterial option:selected").text();
            if (_idMaterial !== '') {
                var existe = encuentraMaterialKit(_idMaterial);
                if (!existe) {
                    var _html = `<tr id="tr-` + _idMaterial + `">
                                <td>` + _material + `</td>
                                <td><input class="form-control txtCantidadesMaterial" min="1" type="number" data-id="` + _idMaterial + `" placeholder="0" /></td>
                                <td><button class="btn btn-danger btnDeleteMaterialKit" data-id="` + _idMaterial + `"><i class="fa fa-trash"></i></button></td>
                            </tr>`;
                    $("#table-material-kit tbody").prepend(_html);
                    $(".combobox-clear").click();
                    initActionsDeleteMaterialKit();
                } else {
                    evento.mostrarMensaje("#errorKitMateriales", false, 'Este material ya se encuentra en la lista del kit.', 4000);
                }
            } else {
                evento.mostrarMensaje("#errorKitMateriales", false, 'Debe seleccionar un material de la lista.', 4000);
            }
        });

        $("#btnGuardarKit").off("click");
        $("#btnGuardarKit").on("click", function () {
            guardarKit(_fila);
        });

        initActionsDeleteMaterialKit();
    }


    function encuentraMaterialKit() {
        var _idMaterial = arguments[0];
        var existe = false;
        $(".txtCantidadesMaterial").each(function () {
            if ($(this).attr("data-id") == _idMaterial) {
                existe = true;
            }
        });
        return existe;
    }

    function initActionsDeleteMaterialKit() {
        $(".btnDeleteMaterialKit").off("click");
        $(".btnDeleteMaterialKit").on("click", function () {
            $("#tr-" + $(this).attr("data-id")).remove();
        });
    }

    function guardarKit() {
        var _fila = arguments[0] || null;
        var _nombre = $.trim($("#txtKit").val());
        var _material = [];
        var _zero = false;
        $(".txtCantidadesMaterial").each(function () {
            var _value = $(this).attr("data-id") + ',' + $(this).val();
            _material.push(_value);
            if ($(this).val() <= 0) {
                _zero = true;
            }
        });

        if (_nombre == "") {
            evento.mostrarMensaje("#errorKitNombre", false, 'El nombre del Kit no puede ser una cadena vacía.', 4000);
        } else if (_material.length <= 0) {
            evento.mostrarMensaje("#errorKitNombre", false, 'El kit debe tener al menos un material con cantidad registrada.', 4000);
        } else if (_zero) {
            evento.mostrarMensaje("#errorKitNombre", false, 'Uno o mas materiales del kit tienen cantidades inferiores a 1', 4000);
        } else {
            var datos = {
                'idKit': $("#idKit").val(),
                'kit': _nombre,
                'material': _material
            };
            evento.enviarEvento('Catalogos/AgregarEditarKit', datos, '#panelKitMaterial', function (respuesta) {
                if (typeof respuesta !== 'undefined' && respuesta.code == 200) {
                    var material = '';
                    $.each(respuesta.Material, function (k, v) {
                        material += '<strong>' + v.Cantidad + '</strong> - ' + v.Nombre + '<br />';
                    });

                    var _row = '<tr><td>' + respuesta.Id + '</td><td>' + respuesta.Kit + '</td><td>' + material + '</td></tr>';

                    if (respuesta.move == 'edit') {
                        tabla.eliminarFila()
                        $('#table-kits').DataTable().row(_fila).remove().draw();
                    }

                    tabla.agregarFilaHtml('#table-kits', _row);
                    tabla.reordenarTabla('#table-kits', [1, 'asc']);
                    $('#table-kits').DataTable().page.jumpToData(respuesta.Id, 0);

                    $("#divKitMaterial").fadeOut(400, function () {
                        $("#seccionCatalogos").fadeIn(400, function () {
                            $("#divKitMaterial").empty();
                        });
                    });
                } else {
                    evento.mostrarMensaje("#errorKitNombre", false, respuesta.error, 4000);
                }
            });
        }
    }
});



