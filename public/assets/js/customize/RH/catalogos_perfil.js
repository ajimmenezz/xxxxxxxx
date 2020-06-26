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
        select.cambiarOpcion('#listEstatus', datosTabla[1]);

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
        var htmlFormaularioEdicion = formularioEdicion('Estado Civil', datosTabla[2]);

        evento.iniciarModal('#modalEdit', 'Editar Estado Civil', htmlFormaularioEdicion);
        select.crearSelect('#listEstatus');
        select.cambiarOpcion('#listEstatus', datosTabla[1]);

        $("#btnGuardarCambios").off("click");
        $("#btnGuardarCambios").on("click", function () {
            if ($.trim($("#txtCampoCatalogoPerfil").val()) !== '') {
                var datos = {
                    'operacion': 'estadoCivil',
                    'id': datosTabla[0],
                    'nombre': $.trim($("#txtCampoCatalogoPerfil").val()),
                    'estatus': $("#listEstatus").val()
                };

                evento.enviarEvento('EventoCatalogosPerfil/ActualizarCatalogoPerfil', datos, '#modalEdit', function (respuesta) {
                    if (respuesta instanceof Array || respuesta instanceof Object) {
                        recargandoTabla(respuesta, '#table-estado-civil');
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
        var htmlFormaularioEdicion = formularioEdicion('Idioma', datosTabla[2]);

        evento.iniciarModal('#modalEdit', 'Editar Idioma', htmlFormaularioEdicion);
        select.crearSelect('#listEstatus');
        select.cambiarOpcion('#listEstatus', datosTabla[1]);

        $("#btnGuardarCambios").off("click");
        $("#btnGuardarCambios").on("click", function () {
            if ($.trim($("#txtCampoCatalogoPerfil").val()) !== '') {
                var datos = {
                    'operacion': 'idioma',
                    'id': datosTabla[0],
                    'nombre': $.trim($("#txtCampoCatalogoPerfil").val()),
                    'estatus': $("#listEstatus").val()
                };

                evento.enviarEvento('EventoCatalogosPerfil/ActualizarCatalogoPerfil', datos, '#modalEdit', function (respuesta) {
                    if (respuesta instanceof Array || respuesta instanceof Object) {
                        recargandoTabla(respuesta, '#table-idiomas ');
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
        var htmlFormaularioEdicion = formularioEdicion('Nivel de estudio', datosTabla[2]);

        evento.iniciarModal('#modalEdit', 'Editar Nivel de Estudio', htmlFormaularioEdicion);
        select.crearSelect('#listEstatus');
        select.cambiarOpcion('#listEstatus', datosTabla[1]);

        $("#btnGuardarCambios").off("click");
        $("#btnGuardarCambios").on("click", function () {
            if ($.trim($("#txtCampoCatalogoPerfil").val()) !== '') {
                var datos = {
                    'operacion': 'nivelEstudio',
                    'id': datosTabla[0],
                    'nombre': $.trim($("#txtCampoCatalogoPerfil").val()),
                    'estatus': $("#listEstatus").val()
                };

                evento.enviarEvento('EventoCatalogosPerfil/ActualizarCatalogoPerfil', datos, '#modalEdit', function (respuesta) {
                    if (respuesta instanceof Array || respuesta instanceof Object) {
                        recargandoTabla(respuesta, '#table-niveles-estudio');
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

    $('#table-niveles-habilidad tbody').on('click', 'tr', function () {
        let _this = this;
        var datosTabla = $('#table-niveles-habilidad').DataTable().row(_this).data();
        var htmlFormaularioEdicion = formularioEdicion('Nivel de habilidad', datosTabla[2]);

        evento.iniciarModal('#modalEdit', 'Editar Nivel de Habilidad', htmlFormaularioEdicion);
        select.crearSelect('#listEstatus');
        select.cambiarOpcion('#listEstatus', datosTabla[1]);

        $("#btnGuardarCambios").off("click");
        $("#btnGuardarCambios").on("click", function () {
            if ($.trim($("#txtCampoCatalogoPerfil").val()) !== '') {
                var datos = {
                    'operacion': 'nivelHabilidad',
                    'id': datosTabla[0],
                    'nombre': $.trim($("#txtCampoCatalogoPerfil").val()),
                    'estatus': $("#listEstatus").val()
                };

                evento.enviarEvento('EventoCatalogosPerfil/ActualizarCatalogoPerfil', datos, '#modalEdit', function (respuesta) {
                    if (respuesta instanceof Array || respuesta instanceof Object) {
                        recargandoTabla(respuesta, '#table-niveles-habilidad');
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
        var htmlFormaularioEdicion = formularioEdicion('Sexo', datosTabla[2]);

        evento.iniciarModal('#modalEdit', 'Editar Sexo', htmlFormaularioEdicion);
        select.crearSelect('#listEstatus');
        select.cambiarOpcion('#listEstatus', datosTabla[1]);

        $("#btnGuardarCambios").off("click");
        $("#btnGuardarCambios").on("click", function () {
            if ($.trim($("#txtCampoCatalogoPerfil").val()) !== '') {
                var datos = {
                    'operacion': 'sexo',
                    'id': datosTabla[0],
                    'nombre': $.trim($("#txtCampoCatalogoPerfil").val()),
                    'estatus': $("#listEstatus").val()
                };

                evento.enviarEvento('EventoCatalogosPerfil/ActualizarCatalogoPerfil', datos, '#modalEdit', function (respuesta) {
                    if (respuesta instanceof Array || respuesta instanceof Object) {
                        recargandoTabla(respuesta, '#table-sexos');
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

    $('#table-sistemas tbody').on('click', 'tr', function () {
        let _this = this;
        var datosTabla = $('#table-sistemas').DataTable().row(_this).data();
        var htmlFormaularioEdicion = formularioEdicion('Sistema', datosTabla[2]);

        evento.iniciarModal('#modalEdit', 'Editar Sistema', htmlFormaularioEdicion);
        select.crearSelect('#listEstatus');
        select.cambiarOpcion('#listEstatus', datosTabla[1]);

        $("#btnGuardarCambios").off("click");
        $("#btnGuardarCambios").on("click", function () {
            if ($.trim($("#txtCampoCatalogoPerfil").val()) !== '') {
                var datos = {
                    'operacion': 'sistema',
                    'id': datosTabla[0],
                    'nombre': $.trim($("#txtCampoCatalogoPerfil").val()),
                    'estatus': $("#listEstatus").val()
                };

                evento.enviarEvento('EventoCatalogosPerfil/ActualizarCatalogoPerfil', datos, '#modalEdit', function (respuesta) {
                    if (respuesta instanceof Array || respuesta instanceof Object) {
                        recargandoTabla(respuesta, '#table-sistemas');
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
        var datosTabla = $('#table-software ').DataTable().row(_this).data();
        var htmlFormaularioEdicion = formularioEdicion('Software', datosTabla[2]);

        evento.iniciarModal('#modalEdit', 'Editar Software', htmlFormaularioEdicion);
        select.crearSelect('#listEstatus');
        select.cambiarOpcion('#listEstatus', datosTabla[1]);

        $("#btnGuardarCambios").off("click");
        $("#btnGuardarCambios").on("click", function () {
            if ($.trim($("#txtCampoCatalogoPerfil").val()) !== '') {
                var datos = {
                    'operacion': 'software',
                    'id': datosTabla[0],
                    'nombre': $.trim($("#txtCampoCatalogoPerfil").val()),
                    'estatus': $("#listEstatus").val()
                };

                evento.enviarEvento('EventoCatalogosPerfil/ActualizarCatalogoPerfil', datos, '#modalEdit', function (respuesta) {
                    if (respuesta instanceof Array || respuesta instanceof Object) {
                        recargandoTabla(respuesta, '#table-software ');
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



