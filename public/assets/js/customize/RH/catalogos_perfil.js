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

    tabla.generaTablaPersonal('#table-documentos-recibido', null, null, true, true, [[2, 'asc']]);
    tabla.generaTablaPersonal('#table-estado-civil', null, null, true, true, [[2, 'asc']]);
    tabla.generaTablaPersonal('#table-idiomas', null, null, true, true, [[2, 'asc']]);
    tabla.generaTablaPersonal('#table-niveles-estudio', null, null, true, true, [[2, 'asc']]);
    tabla.generaTablaPersonal('#table-niveles-habilidad', null, null, true, true, [[2, 'asc']]);
    tabla.generaTablaPersonal('#table-sexos', null, null, true, true, [[2, 'asc']]);
    tabla.generaTablaPersonal('#table-sistemas', null, null, true, true, [[2, 'asc']]);
    tabla.generaTablaPersonal('#table-software', null, null, true, true, [[2, 'asc']]);

    $("#btnGuardarDocumentoRecibido").off("click");
    $("#btnGuardarDocumentoRecibido").on("click", function () {
        if ($.trim($("#txtNuevoDocumentoRecibido").val()) !== '') {
            var datos = {
                'operacion': 'documentoRecibido',
                'documentoRecibido': $.trim($("#txtNuevoDocumentoRecibido").val())
            };

            evento.enviarEvento('EventoCatalogosPerfil/GuardarCatalogosPerfil', datos, '#panel-catalogos-perfil', function (respuesta) {
                if (respuesta instanceof Array || respuesta instanceof Object) {
                    $('#txtNuevoDocumentoRecibido').val('');
                    recargandoTabla(respuesta, '#table-documentos-recibido');
                } else {
                    evento.mostrarMensaje("#errorMessage", false, 'Ya existe nombre del documento recibido', 4000);
                }
            });
        } else {
            evento.mostrarMensaje("#errorMessage", false, "Al parecer el campo está vacío.", 4000);
        }
    });

    $('#table-documentos-recibido tbody').on('click', 'tr', function () {
        let _this = this;
        var datosTabla = $('#table-documentos-recibido').DataTable().row(_this).data();
        var htmlFormaularioEdicion = formularioEdicion('Documento Recibido', datosTabla[2]);

        evento.iniciarModal('#modalEdit', 'Editar Documento Recibido', htmlFormaularioEdicion);
        select.crearSelect('#listEstatus');
        select.cambiarOpcion('#selectActualizarNivelSistemasUsuario', datosTabla[1]);

        $("#btnGuardarCambios").off("click");
        $("#btnGuardarCambios").on("click", function () {
            if ($.trim($("#txtCampoCatalogoPerfil").val()) !== '') {
                var datos = {
                    'operacion': 'documentoRecibido',
                    'id': datosTabla[0],
                    'nombre': $.trim($("#txtCampoCatalogoPerfil").val()),
                    'estatus': $("#listEstatus").val()
                };

                evento.enviarEvento('EventoCatalogosPerfil/ActualizarCatalogoPerfil', datos, '#modalEdit', function (respuesta) {
                    if (respuesta instanceof Array || respuesta instanceof Object) {
                        recargandoTabla(respuesta, '#table-documentos-recibido');
                         evento.terminarModal('#modalEdit');
                    } else {
                        evento.mostrarMensaje("#errorMessage", false, 'Ya existe nombre del documento recibido', 4000);
                    }
                });
            } else {
                evento.mostrarMensaje("#error-in-modal", false, "Al parecer el campo está vacío.", 4000);
            }
        });
    });

    $("#btnGuardarEstadoCivil").off("click");
    $("#btnGuardarEstadoCivil").on("click", function () {
        if ($.trim($("#txtNuevoEstadoCivil").val()) !== '') {
            var datos = {
                'operacion': 'estadoCivil',
                'estadoCivil': $.trim($("#txtNuevoEstadoCivil").val())
            };

            evento.enviarEvento('EventoCatalogosPerfil/GuardarCatalogosPerfil', datos, '#panel-catalogos-perfil', function (respuesta) {
                if (respuesta instanceof Array || respuesta instanceof Object) {
                    $('#txtNuevoEstadoCivil').val('');
                    recargandoTabla(respuesta, '#table-estado-civil');
                } else {
                    evento.mostrarMensaje("#errorMessage", false, 'Ya existe nombre del Estado civil', 4000);
                }
            });
        } else {
            evento.mostrarMensaje("#errorMessage", false, "Al parecer el campo está vacío.", 4000);
        }
    });

    $('#table-estado-civil tbody').on('click', 'tr', function () {
        let _this = this;
        var datosTabla = $('#table-estado-civil').DataTable().row(_this).data();
        var datos = {
            'id': datosTabla[0]
        };
        evento.enviarEvento('Catalogos/FormularioEditarEstadoCivil', datos, '#panel-catalogos', function (respuesta) {
            if (respuesta.code == 200) {
                evento.iniciarModal('#modalEdit', 'Editar Estado Civl', respuesta.formulario);

                $("#btnGuardarCambios").off("click");
                $("#btnGuardarCambios").on("click", function () {
//                    if ($.trim($("#txtTipo").val()) !== '') {
//                        var datos = {
//                            'id': $('#idTipo').val(),
//                            'tipo': $.trim($("#txtTipo").val()),
//                            'estatus': $("#listEstatus").val()
//                        };
//                        evento.enviarEvento('Catalogos/EditarTipo', datos, '#modalEdit', function (respuesta) {
//                            if (typeof respuesta !== 'undefined' && respuesta.code == 200) {
//                                evento.terminarModal('#modalEdit');
//                                $('#table-tipos').DataTable().row(_this).data([respuesta.Id, respuesta.Flag, respuesta.Nombre, respuesta.Estatus]);
//                                tabla.reordenarTabla('#table-tipos', [2, 'asc']);
//                                $('#table-tipos').DataTable().page.jumpToData(respuesta.Id, 0);
//                            } else {
//                                evento.mostrarMensaje("#error-in-modal", false, respuesta.error, 4000);
//                            }
//                        });
//                    } else {
//                        evento.mostrarMensaje("#error-in-modal", false, "Al parecer el campo está vacío.", 4000);
//                    }
                });

            } else {
                evento.mostrarMensaje("#errorMessage", false, respuesta.error, 4000);
            }
        });
    });

    $("#btnGuardarIdioma").off("click");
    $("#btnGuardarIdioma").on("click", function () {
        if ($.trim($("#txtNuevoIdioma").val()) !== '') {
            var datos = {
                'operacion': 'idioma',
                'idioma': $.trim($("#txtNuevoIdioma").val())
            };

            evento.enviarEvento('EventoCatalogosPerfil/GuardarCatalogosPerfil', datos, '#panel-catalogos-perfil', function (respuesta) {
                if (respuesta instanceof Array || respuesta instanceof Object) {
                    $('#txtNuevoIdioma').val('');
                    recargandoTabla(respuesta, '#table-idiomas');
                } else {
                    evento.mostrarMensaje("#errorMessage", false, 'Ya existe nombre del Idioma', 4000);
                }
            });
        } else {
            evento.mostrarMensaje("#errorMessage", false, "Al parecer el campo está vacío.", 4000);
        }
    });

    $('#table-idiomas tbody').on('click', 'tr', function () {
        let _this = this;
        var datosTabla = $('#table-idiomas').DataTable().row(_this).data();
        var datos = {
            'id': datosTabla[0]
        };
        evento.enviarEvento('CatalogosPerfil/FormularioEditarIdioma', datos, '#panel-catalogos-perfil', function (respuesta) {
            if (respuesta.code == 200) {
                evento.iniciarModal('#modalEdit', 'Editar Idioma', respuesta.formulario);

                $("#btnGuardarCambios").off("click");
                $("#btnGuardarCambios").on("click", function () {
//                    if ($.trim($("#txtConcepto").val()) !== '') {
//                        var datos = {
//                            'id': $('#idConcepto').val(),
//                            'concepto': $.trim($("#txtConcepto").val()),
//                            'sistema': $("#listSistemas").val(),
//                            'estatus': $("#listEstatus").val()
//                        };
//                        evento.enviarEvento('Catalogos/EditarConcepto', datos, '#modalEdit', function (respuesta) {
//                            if (typeof respuesta !== 'undefined' && respuesta.code == 200) {
//                                evento.terminarModal('#modalEdit');
//                                $('#table-conceptos').DataTable().row(_this).data([respuesta.Id, respuesta.IdSistema, respuesta.Flag, respuesta.Nombre, respuesta.Sistema, respuesta.Estatus]);
//                                tabla.reordenarTabla('#table-conceptos', [3, 'asc']);
//                                $('#table-conceptos').DataTable().page.jumpToData(respuesta.Id, 0);
//                            } else {
//                                evento.mostrarMensaje("#error-in-modal", false, respuesta.error, 4000);
//                            }
//                        });
//                    } else {
//                        evento.mostrarMensaje("#error-in-modal", false, "Al parecer el campo está vacío.", 4000);
//                    }
                });

            } else {
                evento.mostrarMensaje("#errorMessage", false, respuesta.error, 4000);
            }
        });
    });

    $("#btnGuardarNivelEstudio").off("click");
    $("#btnGuardarNivelEstudio").on("click", function () {
        if ($.trim($("#txtNuevoNivelEstudio").val()) !== '') {
            var datos = {
                'operacion': 'nivelEstudio',
                'nivelEstudio': $.trim($("#txtNuevoNivelEstudio").val())
            };

            evento.enviarEvento('EventoCatalogosPerfil/GuardarCatalogosPerfil', datos, '#panel-catalogos-perfil', function (respuesta) {
                if (respuesta instanceof Array || respuesta instanceof Object) {
                    $('#txtNuevoNivelEstudio').val('');
                    recargandoTabla(respuesta, '#table-niveles-estudio');
                } else {
                    evento.mostrarMensaje("#errorMessage", false, 'Ya existe nombre del Nivel de estudio', 4000);
                }
            });
        } else {
            evento.mostrarMensaje("#errorMessage", false, "Al parecer el campo está vacío.", 4000);
        }
    });

    $('#table-niveles-estudio tbody').on('click', 'tr', function () {
        let _this = this;
        var datosTabla = $('#table-niveles-estudio').DataTable().row(_this).data();
        var datos = {
            'id': datosTabla[0]
        };
        evento.enviarEvento('CatalogosPerfil/FormularioEditarNivelEstudio', datos, '#panel-catalogos-perfil', function (respuesta) {
            if (respuesta.code == 200) {
                evento.iniciarModal('#modalEdit', 'Editar Nivel de Estudio', respuesta.formulario);

                $("#btnGuardarCambios").off("click");
                $("#btnGuardarCambios").on("click", function () {
//                    if ($.trim($("#txtArea").val()) !== '') {
//                        var datos = {
//                            'id': $('#idArea').val(),
//                            'area': $.trim($("#txtArea").val()),
//                            'concepto': $("#listConceptos").val(),
//                            'estatus': $("#listEstatus").val()
//                        };
//                        evento.enviarEvento('Catalogos/EditarArea', datos, '#modalEdit', function (respuesta) {
//                            if (typeof respuesta !== 'undefined' && respuesta.code == 200) {
//                                evento.terminarModal('#modalEdit');
//                                $('#table-areas').DataTable().row(_this).data([respuesta.Id, respuesta.IdConcepto, respuesta.Flag, respuesta.Nombre, respuesta.Concepto, respuesta.Estatus]);
//                                tabla.reordenarTabla('#table-areas', [3, 'asc']);
//                                $('#table-areas').DataTable().page.jumpToData(respuesta.Id, 0);
//                            } else {
//                                evento.mostrarMensaje("#error-in-modal", false, respuesta.error, 4000);
//                            }
//                        });
//                    } else {
//                        evento.mostrarMensaje("#error-in-modal", false, "Al parecer el campo está vacío.", 4000);
//                    }
                });

            } else {
                evento.mostrarMensaje("#errorMessage", false, respuesta.error, 4000);
            }
        });
    });

    $("#btnGuardarNivelHabilidad").off("click");
    $("#btnGuardarNivelHabilidad").on("click", function () {
        if ($.trim($("#txtNuevoNivelHabilidad").val()) !== '') {
            var datos = {
                'operacion': 'nivelHabilidad',
                'nivelHabilidad': $.trim($("#txtNuevoNivelHabilidad").val())
            };

            evento.enviarEvento('EventoCatalogosPerfil/GuardarCatalogosPerfil', datos, '#panel-catalogos-perfil', function (respuesta) {
                if (respuesta instanceof Array || respuesta instanceof Object) {
                    $('#txtNuevoNivelHabilidad').val('');
                    recargandoTabla(respuesta, '#table-niveles-habilidad');
                } else {
                    evento.mostrarMensaje("#errorMessage", false, 'Ya existe nombre del Nivel de habilidad', 4000);
                }
            });
        } else {
            evento.mostrarMensaje("#errorMessage", false, "Al parecer el campo está vacío.", 4000);
        }
    });

    $('#table-niveles-habilidad tbody').on('dblclick', 'tr', function () {
        let _this = this;
        var datosTabla = $('#table-niveles-habilidad').DataTable().row(_this).data();
        var datos = {
            'id': datosTabla[0]
        };
        evento.enviarEvento('Catalogos/FormularioEditarNivelEstudio', datos, '#panel-catalogos-perfil', function (respuesta) {
            if (respuesta.code == 200) {
                evento.iniciarModal('#modalEdit', 'Editar Nivel de Habilidad', respuesta.formulario);

                $("#listAreas").combobox();

                $("#btnGuardarCambios").off("click");
                $("#btnGuardarCambios").on("click", function () {
//                    if ($.trim($("#txtUbicacion").val()) !== '') {
//                        var datos = {
//                            'id': $('#idUbicacion').val(),
//                            'ubicacion': $.trim($("#txtUbicacion").val()),
//                            'area': $("#listAreas").val(),
//                            'estatus': $("#listEstatus").val()
//                        };
//                        evento.enviarEvento('Catalogos/EditarUbicacion', datos, '#modalEdit', function (respuesta) {
//                            if (typeof respuesta !== 'undefined' && respuesta.code == 200) {
//                                evento.terminarModal('#modalEdit');
//                                $('#table-ubicaciones').DataTable().row(_this).data([respuesta.Id, respuesta.IdArea, respuesta.Flag, respuesta.Nombre, respuesta.Area, respuesta.Estatus]);
//                                tabla.reordenarTabla('#table-ubicaciones', [3, 'asc']);
//                                $('#table-ubicaciones').DataTable().page.jumpToData(respuesta.Id, 0);
//                            } else {
//                                evento.mostrarMensaje("#error-in-modal", false, respuesta.error, 4000);
//                            }
//                        });
//                    } else {
//                        evento.mostrarMensaje("#error-in-modal", false, "Al parecer el campo está vacío.", 4000);
//                    }
                });

            } else {
                evento.mostrarMensaje("#errorMessage", false, respuesta.error, 4000);
            }
        });
    });

    $("#btnGuardarSexo").off("click");
    $("#btnGuardarSexo").on("click", function () {
        if ($.trim($("#txtNuevoSexo").val()) !== '') {
            var datos = {
                'operacion': 'sexo',
                'sexo': $.trim($("#txtNuevoSexo").val())
            };

            evento.enviarEvento('EventoCatalogosPerfil/GuardarCatalogosPerfil', datos, '#panel-catalogos-perfil', function (respuesta) {
                if (respuesta instanceof Array || respuesta instanceof Object) {
                    $('#txtNuevoSexo').val('');
                    recargandoTabla(respuesta, '#table-sexos');
                } else {
                    evento.mostrarMensaje("#errorMessage", false, 'Ya existe nombre del Sexo', 4000);
                }
            });
        } else {
            evento.mostrarMensaje("#errorMessage", false, "Al parecer el campo está vacío.", 4000);
        }
    });

    $('#table-sexos tbody').on('click', 'tr', function () {
        let _this = this;
        var datosTabla = $('#table-sexos').DataTable().row(_this).data();
        var datos = {
            'id': datosTabla[0]
        };
        evento.enviarEvento('Catalogos/FormularioEditarSexo', datos, '#panel-catalogos-perfil', function (respuesta) {
            if (respuesta.code == 200) {
                evento.iniciarModal('#modalEdit', 'Editar Sexo', respuesta.formulario);

                $("#btnGuardarCambios").off("click");
                $("#btnGuardarCambios").on("click", function () {
//                    if ($.trim($("#txtAccesorio").val()) !== '') {
//                        var datos = {
//                            'id': $('#idAccesorio').val(),
//                            'accesorio': $.trim($("#txtAccesorio").val()),
//                            'sistema': $("#listSistemas").val(),
//                            'estatus': $("#listEstatus").val()
//                        };
//                        evento.enviarEvento('Catalogos/EditarAccesorio', datos, '#modalEdit', function (respuesta) {
//                            if (typeof respuesta !== 'undefined' && respuesta.code == 200) {
//                                evento.terminarModal('#modalEdit');
//                                $('#table-accesorios').DataTable().row(_this).data([respuesta.Id, respuesta.IdSistema, respuesta.Flag, respuesta.Nombre, respuesta.Sistema, respuesta.Estatus]);
//                                tabla.reordenarTabla('#table-accesorios', [3, 'asc']);
//                                $('#table-accesorios').DataTable().page.jumpToData(respuesta.Id, 0);
//                            } else {
//                                evento.mostrarMensaje("#error-in-modal", false, respuesta.error, 4000);
//                            }
//                        });
//                    } else {
//                        evento.mostrarMensaje("#error-in-modal", false, "Al parecer el campo está vacío.", 4000);
//                    }
                });

            } else {
                evento.mostrarMensaje("#errorMessage", false, respuesta.error, 4000);
            }
        });
    });

    $("#btnGuardarSistema").off("click");
    $("#btnGuardarSistema").on("click", function () {
        if ($.trim($("#txtNuevoSistema").val()) !== '') {
            var datos = {
                'operacion': 'sistema',
                'sistema': $.trim($("#txtNuevoSistema").val())
            };

            evento.enviarEvento('EventoCatalogosPerfil/GuardarCatalogosPerfil', datos, '#panel-catalogos-perfil', function (respuesta) {
                if (respuesta instanceof Array || respuesta instanceof Object) {
                    $('#txtNuevoSistema').val('');
                    recargandoTabla(respuesta, '#table-sistemas');
                } else {
                    evento.mostrarMensaje("#errorMessage", false, 'Ya existe nombre del Sistema', 4000);
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
        evento.enviarEvento('Catalogos/FormularioEditarSistema', datos, '#panel-catalogos-perfil', function (respuesta) {
            if (respuesta.code == 200) {
                evento.iniciarModal('#modalEdit', 'Editar Sistema', respuesta.formulario);

                $("#listAccesorios").combobox();
                $("#listMaterial").combobox();

                $("#btnGuardarCambios").off("click");
                $("#btnGuardarCambios").on("click", function () {
//                    if ($("#listAccesorios").val() !== '' && $("listMaterial").val() !== '') {
//                        var datos = {
//                            'id': $("#idMaterial").val(),
//                            'accesorio': $("#listAccesorios").val(),
//                            'material': $("#listMaterial").val()
//                        };
//                        evento.enviarEvento('Catalogos/EditarMaterial', datos, '#modalEdit', function (respuesta) {
//                            if (typeof respuesta !== 'undefined' && respuesta.code == 200) {
//                                evento.terminarModal('#modalEdit');
//                                $('#table-material').DataTable().row(_this).data([respuesta.Id, respuesta.IdMaterial, respuesta.IdAccesorio, respuesta.Material, respuesta.Accesorio]);
//                                tabla.reordenarTabla('#table-material', [3, 'asc']);
//                                $('#table-material').DataTable().page.jumpToData(respuesta.Id, 0);
//                            } else {
//                                evento.mostrarMensaje("#error-in-modal", false, respuesta.error, 4000);
//                            }
//                        });
//                    } else {
//                        evento.mostrarMensaje("#error-in-modal", false, "Al parecer no ha seleccionado el material o el accesorio.", 4000);
//                    }
                });

            } else {
                evento.mostrarMensaje("#errorMessage", false, respuesta.error, 4000);
            }
        });
    });

    $("#btnGuardarSoftware").off("click");
    $("#btnGuardarSoftware").on("click", function () {
        if ($.trim($("#txtNuevoSoftware").val()) !== '') {
            var datos = {
                'operacion': 'software',
                'software': $.trim($("#txtNuevoSoftware").val())
            };

            evento.enviarEvento('EventoCatalogosPerfil/GuardarCatalogosPerfil', datos, '#panel-catalogos-perfil', function (respuesta) {
                if (respuesta instanceof Array || respuesta instanceof Object) {
                    $('#txtNuevoSoftware').val('');
                    recargandoTabla(respuesta, '#table-software');
                } else {
                    evento.mostrarMensaje("#errorMessage", false, 'Ya existe nombre del Software', 4000);
                }
            });
        } else {
            evento.mostrarMensaje("#errorMessage", false, "Al parecer el campo está vacío.", 4000);
        }
    });

    $('#table-software tbody').on('click', 'tr', function () {
        let _this = this;
        var datosTabla = $('#table-software').DataTable().row(_this).data();
        var datos = {
            'id': datosTabla[0]
        };

        evento.enviarEvento('Catalogos/FormularioEditarKit', datos, '#panel-catalogos-perfil', function (respuesta) {
//            if (respuesta.code == 200) {
//                $("#divKitMaterial").empty().append(respuesta.formulario);
//
//                $("#listMaterial").combobox();
//
//                $("#seccionCatalogos").fadeOut(400, function () {
//                    $("#divKitMaterial").fadeIn(400);
//                });
//
//                $("#divKitMaterial #btnRegresar").off("click");
//                $("#divKitMaterial #btnRegresar").on("click", function () {
//                    $("#divKitMaterial").fadeOut(400, function () {
//                        $("#seccionCatalogos").fadeIn(400, function () {
//                            $("#divKitMaterial").empty();
//                        });
//                    });
//                });
//                initKitActions(_this);
//            } else {
//                evento.mostrarMensaje("#errorMessage", false, respuesta.error, 4000);
//            }
        });
    });

    var recargandoTabla = function () {
        var datosTabla = arguments[0];
        var nombreTabla = arguments[1];

        tabla.limpiarTabla(nombreTabla);
        $.each(datosTabla, function (key, item) {
            tabla.agregarFila(nombreTabla, [item.Id, item.Flag, item.Nombre, item.Estatus]);
        });
    };

    var formularioEdicion = function () {
        var nombreCampo = arguments[0];
        var textoCampo = arguments[1];

        var html = '<div class="row">\n\
                        <div class="col-md-12 col-sm-12 col-xs-12">\n\
                            <div class="form-group">\n\
                                <label class="f-w-600">' + nombreCampo + '</label>\n\
                                <input class="form-control" type="text" id="txtCampoCatalogoPerfil" value="' + textoCampo + '" />\n\
                            </div>\n\
                        </div>\n\
                    </div>\n\
                    <div class="row">\n\
                        <div class="col-md-12 col-sm-12 col-xs-12">\n\
                            <div class="form-group">\n\
                                <label class="f-w-600">Estatus:</label>\n\
                                <select id="listEstatus" class="form-control" style="width: 100% !important;">\n\
                                    <option value="1">Activo</option>\n\
                                    <option value="0">Inactivo</option>\n\
                                </select>\n\
                            </div>\n\
                        </div>\n\
                    </div>';
        return html;
    }

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
}
);



