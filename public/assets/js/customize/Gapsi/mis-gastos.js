$(function() {

    var evento = new Base();
    var websocket = new Socket();
    var tabla = new Tabla();
    var select = new Select();
    var file = new Upload();

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

    tabla.generaTablaPersonal("#data-table-gastos", null, null, true, true, [
        [0, 'asc']
    ]);

    $('#btnExportarMisGastos').off('click');
    $("#btnExportarMisGastos").on("click", function() {
        evento.enviarEvento('Gasto/ExportMisGastos', {}, '#panelListaGastos', function(respuesta) {
            window.open(respuesta.ruta, '_blank');
        });
    });

    $('#data-table-gastos tbody').on('click', 'tr', function() {
        var _fila = $(this);
        var datos = $('#data-table-gastos').DataTable().row(this).data();
        if (datos !== undefined) {
            var idGasto = datos[0];

            evento.enviarEvento('Gasto/CargaGasto', { id: idGasto }, '#panelListaGastos', function(respuesta) {
                $("#divFormularioGasto").empty().append(respuesta.html);
                evento.cambiarDiv("#divListaGastos", "#divFormularioGasto", initFormulario(_fila));
            });
        }
    });


    function initFormulario() {
        select.crearSelect("select");
        _fila = arguments[0];
        file.crearUpload('#fotosGasto', 'Gasto/GuardarCambiosGasto', ['jpg', 'bmp', 'jpeg', 'gif', 'png', 'pdf', 'doc', 'docx', 'xls', 'xlsx', 'xml', 'msg']);
        $("#listClientes").on("change", function() {
            $("#listProyectos").empty().append('<option value="">Selecciona . . .</option>');
            if ($(this).val() !== '') {
                var datos = {
                    'id': $(this).val()
                }
                evento.enviarEvento('Gasto/ProyectosByCliente', datos, '#panelFormularioGasto', function(respuesta) {
                    $.each(respuesta.proyectos, function(k, v) {
                        $("#listProyectos").append('<option data-tipo="' + v.Tipo + '" value="' + v.ID + '">' + v.Tipo + ' - ' + v.Nombre + '</option>')
                    });
                    $("#listProyectos").removeAttr("disabled");
                });
                select.cambiarOpcion("#listProyectos", '');
            } else {
                $("#listProyectos").attr("disabled", "disabled");
                select.cambiarOpcion("#listProyectos", '');
            }
        });

        $("#listProyectos").on("change", function() {
            $("#listSucursales").empty().append('<option value="">Selecciona . . .</option>');
            if ($(this).val() !== '') {
                var datos = {
                    'id': $(this).val()
                }
                evento.enviarEvento('Gasto/SucursalesByProyecto', datos, '#panelFormularioGasto', function(respuesta) {
                    $.each(respuesta.sucursales, function(k, v) {
                        $("#listSucursales").append('<option value="' + v.ID + '">' + v.Nombre + '</option>')
                    });
                    $("#listSucursales").removeAttr("disabled");
                });
                select.cambiarOpcion("#listSucursales", '');
                $("#listTipoBeneficiario").removeAttr("disabled");
                select.cambiarOpcion("#listTipoBeneficiario", '');
            } else {
                $("#listSucursales").attr("disabled", "disabled");
                select.cambiarOpcion("#listSucursales", '');
                $("#listTipoBeneficiario").attr("disabled", "disabled");
                select.cambiarOpcion("#listTipoBeneficiario", '');
            }
        });

        $("#listTipoBeneficiario").on("change", function() {
            $("#listBeneficiarios").empty().append('<option value="">Selecciona . . .</option>');
            if ($(this).val() !== '') {
                var datos = {
                    'id': $(this).val(),
                    'proyecto': $("#listProyectos").val()
                }
                evento.enviarEvento('Gasto/BeneficiarioByTipo', datos, '#panelFormularioGasto', function(respuesta) {
                    $.each(respuesta.beneficiarios, function(k, v) {
                        $("#listBeneficiarios").append('<option value="' + v.ID + '">' + v.Nombre + '</option>')
                    });
                    $("#listBeneficiarios").removeAttr("disabled");
                });
            } else {
                $("#listBeneficiarios").attr("disabled", "disabled");
            }
        });

        $("#listTipoTrasnferencia").on("change", function() {
            $("#listCategoria").empty().append('<option value="">Selecciona . . .</option>');
            select.cambiarOpcion("#listCategoria", '');
            if ($(this).val() !== '') {
                var datos = {
                    'id': $(this).val()
                }
                evento.enviarEvento('Gasto/CategoriasByTipoTrans', datos, '#panelFormularioGasto', function(respuesta) {
                    $.each(respuesta.categorias, function(k, v) {
                        $("#listCategoria").append('<option value="' + v.ID + '">' + v.Nombre + '</option>')
                    });
                    $("#listCategoria").removeAttr("disabled");
                });
            } else {
                $("#listCategoria").attr("disabled", "disabled");
                select.cambiarOpcion("#listCategoria", '');
            }
        });

        if ($("#checkCredito").is(":checked")) {
            $("#fechaCredito").removeAttr("disabled");
            $("#fechaCredito").attr("data-parsley-required", "true");
        } else {
            $("#fechaCredito").attr("disabled", "disabled");
            $("#fechaCredito").attr("data-parsley-required", "false");
            $("#fechaCredito").val("");
        }

        $("#checkCredito").on("click");
        $("#checkCredito").on("click", function() {
            if ($(this).is(":checked")) {
                $("#fechaCredito").removeAttr("disabled");
                $("#fechaCredito").attr("data-parsley-required", "true");
            } else {
                $("#fechaCredito").attr("disabled", "disabled");
                $("#fechaCredito").attr("data-parsley-required", "false");
                $("#fechaCredito").val("");
            }
        });

        $("#listCategoria").on("change", function() {
            $("#listSubcategoria").empty().append('<option value="">Selecciona . . .</option>');
            select.cambiarOpcion("#listSubcategoria", '');
            if ($(this).val() !== '') {
                var datos = {
                    'id': $(this).val()
                }
                evento.enviarEvento('Gasto/SubcategoriasByCategoria', datos, '#panelFormularioGasto', function(respuesta) {
                    $.each(respuesta.subcategorias, function(k, v) {
                        $("#listSubcategoria").append('<option value="' + v.ID + '">' + v.Nombre + '</option>')
                    });
                    $("#listSubcategoria").removeAttr("disabled");
                });
            } else {
                $("#listSubcategoria").attr("disabled", "disabled");
                select.cambiarOpcion("#listSubcategoria", '');
            }
        });

        $("#listSubcategoria").on("change", function() {
            $("#listConceptos").empty().append('<option value="">Selecciona . . .</option>');
            select.cambiarOpcion("#listConceptos", '');
            if ($(this).val() !== '') {
                var datos = {
                    'id': $(this).val()
                }
                evento.enviarEvento('Gasto/ConceptosBySubcategoria', datos, '#panelFormularioGasto', function(respuesta) {
                    $.each(respuesta.conceptos, function(k, v) {
                        $("#listConceptos").append('<option value="' + v.ID + '">' + v.Nombre + '</option>')
                    });
                    $("#listConceptos").removeAttr("disabled");
                });
            } else {
                $("#listConceptos").attr("disabled", "disabled");
                select.cambiarOpcion("#listConceptos", '');
            }
        });

        $("#btnAddConcepto").off("click");
        $("#btnAddConcepto").on("click", function() {
            addConcepto();
        });

        $("#txtMonto").on('keyup', function(e) {
            if (e.keyCode == 13) {
                addConcepto();
            }
        });

        $("#btnGuardarGasto").off("click");
        $("#btnGuardarGasto").on("click", function() {
            if (evento.validarFormulario('#formGasto')) {
                var _conceptos = '[';
                var total = 0;
                var count = 0;

                $('#table-conceptos-gasto tbody tr').each(function() {
                    _conceptos += '{"categoria":"' + $(this).find('.value-categoria').val() + '"';
                    _conceptos += ',"subcategoria":"' + $(this).find('.value-subcategoria').val() + '"';
                    _conceptos += ',"concepto":"' + $(this).find('.value-concepto').val() + '"';
                    _conceptos += ',"monto":"' + $(this).find('.value-monto').val() + '"},';
                    total = parseFloat(total) + parseFloat($(this).find('.value-monto').val());
                });

                _conceptos = _conceptos.slice(0, -1);
                _conceptos += ']';

                total = parseFloat(Math.round(total * 100) / 100).toFixed(2);

                if (total <= 0 && _conceptos.length <= 0) {
                    evento.mostrarMensaje("#errorFormulario", false, "Se debe introducir al menos un concepto del Gasto.", 4000);
                } else {

                    var _evidenciasAntes = '';
                    $('.imagenesSolicitud').each(function() {
                        _evidenciasAntes += ',' + $(this).attr("href");
                    });

                    var datos = {
                        'ID': $("#IDGasto").val(),
                        'Beneficiario': $("#listBeneficiarios option:selected").text(),
                        'IDBeneficiario': $("#listBeneficiarios").val(),
                        'Tipo': $("#listProyectos option:selected").attr("data-tipo"),
                        'TipoTrans': $("#listTipoTrasnferencia option:selected").text(),
                        'TipoServicio': $("#listTiposServicio option:selected").text(),
                        'OC': $.trim($("#txtOC").val()),
                        'Credito': ($("#checkCredito").is(":checked")) ? 1 : 0,
                        'FechaCredito': $("#fechaCredito").val(),
                        'Descripcion': $("#txtDescripcion").val(),
                        'Importe': total,
                        'Observaciones': $("#txtObservaciones").val(),
                        'Proyecto': $("#listProyectos").val(),
                        'ProyectoString': $("#listProyectos option:selected").text(),
                        'Cliente': $("#listClientes option:selected").text(),
                        'Sucursal': $("#listSucursales").val(),
                        'SucursalString': $("#listSucursales option:selected").text(),
                        'Moneda': $("#listMonedas").val(),
                        'EvidenciasAntes': _evidenciasAntes,
                        'Conceptos': _conceptos
                    };

                    file.enviarArchivos('#fotosGasto', 'Gasto/GuardarCambiosGasto', '#panelFormularioGasto', datos, function(respuesta) {
                        if (respuesta.code == 200) {
                            _fila.click();
                        } else {
                            evento.mostrarMensaje("#errorFormulario", false, "Ocurrió un error al guardar los cambios del gasto. Por favor recargue su página y vuelva a intentarlo.", 4000);
                        }
                    });

                }
            } else {
                evento.mostrarMensaje("#errorFormulario", false, "Todos los campos marcados son obligatorios. Por favor revise el formulario e intente de nuevo.", 4000);
            }
        });

        $(".deleteButton").off("click");
        $(".deleteButton").on("click", function() {
            var _thisButton = $(this);
            var _thisSource = $(this).attr("data-src");
            var filename = _thisSource.split('/').pop();

            var _htmlModal = `<p class="text-center f-s-20 text-danger f-w-600">¿Estás seguro de eliminar el Archivo ` + filename + `?</p>`;

            evento.mostrarModal("Warning", _htmlModal);

            $("#btnModalConfirmar").off("click");
            $("#btnModalConfirmar").on("click", function() {
                var datos = {
                    'Id': $("#IDGasto").val(),
                    'Source': _thisSource
                };

                evento.enviarEvento('Gasto/EliminarArchivo', datos, '#panelFormularioGasto', function(respuesta) {
                    if (respuesta.code == 200) {
                        evento.cerrarModal();
                        _thisButton.closest("div.thumbnail-pic").remove();
                        evento.mostrarMensaje("#errorDeleteImages", true, 'El archivo se eliminó correctamente', 4000);
                    } else {
                        evento.cerrarModal();
                        evento.mostrarMensaje("#errorDeleteImages", false, 'No se pudo eliminar el archivo. Intente de nuevo o contacte al administrador', 4000);
                    }
                });
            });

        });


        $("#btnMarcarLeido").off("click");
        $("#btnMarcarLeido").on("click", function() {
            var datos = {
                'Id': $("#IDGasto").val()
            };
            evento.enviarEvento('Gasto/MarcarLeido', datos, '#panelFormularioGasto', function(respuesta) {
                if (respuesta.code == 200) {
                    evento.empezarCargando('#panelFormularioGasto');
                    location.reload();
                } else {
                    evento.mostrarMensaje("#errorTop", false, 'No se pudo marcar como leído. Intente de nuevo o contacte al administrador', 4000);
                }
            });
        });

        select.cambiarOpcion("#listTipoTrasnferencia", $("#listTipoTrasnferencia").val());
        actualizaTotal();
        actionsRemove();
    }

    function addConcepto() {
        var datos = {
            'idCat': $("#listCategoria").val(),
            'cat': $("#listCategoria option:selected").text(),
            'idSubcat': $("#listSubcategoria").val(),
            'subcat': $("#listSubcategoria option:selected").text(),
            'idConc': $("#listConceptos").val(),
            'conc': $("#listConceptos option:selected").text(),
            'monto': $("#txtMonto").val()
        }

        if (datos.idCat == "" || datos.idSubcat == "" || datos.idConc == "") {
            evento.mostrarMensaje("#errorConceptoGasto", false, 'Todos los campos son obligatorios', 3000);
        } else if (datos.monto == "" || datos.monto <= 0) {
            evento.mostrarMensaje("#errorConceptoGasto", false, 'El monto debe ser mayor a 0.00', 3000);
        } else {
            var tr = `<tr>
                        <td>` + datos.cat + `<input type="hidden" class="value-categoria" value="` + datos.cat + `" /></td>
                        <td>` + datos.subcat + `<input type="hidden" class="value-subcategoria" value="` + datos.subcat + `" /></td>
                        <td>` + datos.conc + `<input type="hidden" class="value-concepto" value="` + datos.conc + `" /></td>
                        <td>$` + parseFloat(Math.round(datos.monto * 100) / 100).toFixed(2) + `<input type="hidden" class="value-monto" value="` + datos.monto + `" /></td>
                        <td><button class="btn btn-danger btnRemoveConcepto"><i class="fa fa-trash"></i></button></td>
                    </tr>`;
            $('#table-conceptos-gasto tbody').append(tr);
            select.cambiarOpcion("#listCategoria", '');
            $("#txtMonto").val('');

            actualizaTotal();
            actionsRemove();
        }
    }

    function actualizaTotal() {
        var total = 0;
        $(".value-monto").each(function() {
            total = parseFloat(total) + parseFloat($(this).val());
        });
        total = parseFloat(Math.round(total * 100) / 100).toFixed(2);
        $("#columna-total").empty().append('$' + total);
    }

    function actionsRemove() {
        $(".btnRemoveConcepto").off("click");
        $(".btnRemoveConcepto").on("click", function() {
            $(this).closest("tr").remove();
            actualizaTotal();
        });
    }

    function limpiarFormulario() {
        select.cambiarOpcion("#listClientes", "");
        select.cambiarOpcion("#listTiposServicio", "");
        select.cambiarOpcion("#listTipoBeneficiario", "");
        select.cambiarOpcion("#listBeneficiarios", "");
        select.cambiarOpcion("#listTipoTrasnferencia", "");
        select.cambiarOpcion("#listMonedas", "");
        $("#txtMonto").val('');
        $("#txtDescripcion").val('');
        $("#txtObservaciones").val('').text('');
        $('#table-conceptos-gasto tbody').empty();
        actualizaTotal();
    }

});