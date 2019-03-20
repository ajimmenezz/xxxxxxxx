$(function () {

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

    file.crearUpload('#fotosGasto', 'Gasto/SolicitarGasto', ['jpg', 'bmp', 'jpeg', 'gif', 'png', 'pdf', 'doc', 'docx', 'xls', 'xlsx', 'xml', 'msg']);

    $("#listClientes").on("change", function () {
        $("#listProyectos").empty().append('<option value="">Selecciona . . .</option>');
        if ($(this).val() !== '') {
            var datos = {
                'id': $(this).val()
            }
            evento.enviarEvento('Gasto/ProyectosByCliente', datos, '#panelFormularioGasto', function (respuesta) {
                $.each(respuesta.proyectos, function (k, v) {
                    $("#listProyectos").append('<option data-tipo="' + v.Tipo + '" value="' + v.ID + '">' + v.Nombre + '</option>')
                });
                $("#listProyectos").removeAttr("disabled");
            });
            select.cambiarOpcion("#listProyectos", '');
        } else {
            $("#listProyectos").attr("disabled", "disabled");
            select.cambiarOpcion("#listProyectos", '');
        }
    });

    $("#listProyectos").on("change", function () {
        $("#listSucursales").empty().append('<option value="">Selecciona . . .</option>');
        if ($(this).val() !== '') {
            var datos = {
                'id': $(this).val()
            }
            evento.enviarEvento('Gasto/SucursalesByProyecto', datos, '#panelFormularioGasto', function (respuesta) {
                $.each(respuesta.sucursales, function (k, v) {
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

    $("#listTipoBeneficiario").on("change", function () {
        $("#listBeneficiarios").empty().append('<option value="">Selecciona . . .</option>');
        if ($(this).val() !== '') {
            var datos = {
                'id': $(this).val(),
                'proyecto': $("#listProyectos").val()
            }
            evento.enviarEvento('Gasto/BeneficiarioByTipo', datos, '#panelFormularioGasto', function (respuesta) {
                $.each(respuesta.beneficiarios, function (k, v) {
                    $("#listBeneficiarios").append('<option value="' + v.ID + '">' + v.Nombre + '</option>')
                });
                $("#listBeneficiarios").removeAttr("disabled");
            });
        } else {
            $("#listBeneficiarios").attr("disabled", "disabled");
        }
    });

    $("#listTipoTrasnferencia").on("change", function () {
        $("#listCategoria").empty().append('<option value="">Selecciona . . .</option>');
        select.cambiarOpcion("#listCategoria", '');
        if ($(this).val() !== '') {
            var datos = {
                'id': $(this).val()
            }
            evento.enviarEvento('Gasto/CategoriasByTipoTrans', datos, '#panelFormularioGasto', function (respuesta) {
                $.each(respuesta.categorias, function (k, v) {
                    $("#listCategoria").append('<option value="' + v.ID + '">' + v.Nombre + '</option>')
                });
                $("#listCategoria").removeAttr("disabled");
            });
        } else {
            $("#listCategoria").attr("disabled", "disabled");
            select.cambiarOpcion("#listCategoria", '');
        }
    });

    $("#checkCredito").on("click");
    $("#checkCredito").on("click", function () {
        if ($(this).is(":checked")) {
            $("#fechaCredito").removeAttr("disabled");
            $("#fechaCredito").attr("data-parsley-required", "true");
        } else {
            $("#fechaCredito").attr("disabled", "disabled");
            $("#fechaCredito").attr("data-parsley-required", "false");
            $("#fechaCredito").val("");
        }
    });


    $("#listCategoria").on("change", function () {
        $("#listSubcategoria").empty().append('<option value="">Selecciona . . .</option>');
        select.cambiarOpcion("#listSubcategoria", '');
        if ($(this).val() !== '') {
            var datos = {
                'id': $(this).val()
            }
            evento.enviarEvento('Gasto/SubcategoriasByCategoria', datos, '#panelFormularioGasto', function (respuesta) {
                $.each(respuesta.subcategorias, function (k, v) {
                    $("#listSubcategoria").append('<option value="' + v.ID + '">' + v.Nombre + '</option>')
                });
                $("#listSubcategoria").removeAttr("disabled");
            });
        } else {
            $("#listSubcategoria").attr("disabled", "disabled");
            select.cambiarOpcion("#listSubcategoria", '');
        }
    });

    $("#listSubcategoria").on("change", function () {
        $("#listConceptos").empty().append('<option value="">Selecciona . . .</option>');
        select.cambiarOpcion("#listConceptos", '');
        if ($(this).val() !== '') {
            var datos = {
                'id': $(this).val()
            }
            evento.enviarEvento('Gasto/ConceptosBySubcategoria', datos, '#panelFormularioGasto', function (respuesta) {
                $.each(respuesta.conceptos, function (k, v) {
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
    $("#btnAddConcepto").on("click", function () {
        addConcepto();
    });

    $("#txtMonto").on('keyup', function (e) {
        if (e.keyCode == 13) {
            addConcepto();
        }
    });

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
        $(".value-monto").each(function () {
            total = parseFloat(total) + parseFloat($(this).val());
        });
        total = parseFloat(Math.round(total * 100) / 100).toFixed(2);
        $("#columna-total").empty().append('$' + total);
    }

    function actionsRemove() {
        $(".btnRemoveConcepto").off("click");
        $(".btnRemoveConcepto").on("click", function () {
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

    $("#btnLimpiarFormulario").off("click");
    $("#btnLimpiarFormulario").on("click", function () {
        limpiarFormulario();
    });

    $("#btnSolicitarGasto").off("click");
    $("#btnSolicitarGasto").on("click", function () {
        if (evento.validarFormulario('#formGasto')) {
            $("#btnSolicitarGasto").off("click");
            $("#btnSolicitarGasto").attr("disabled", "disabled");
            var _conceptos = '[';
            var total = 0;
            var count = 0;
            $('#table-conceptos-gasto tbody tr').each(function () {
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
                var datos = {
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
                    'Conceptos': _conceptos
                };
                file.enviarArchivos('#fotosGasto', 'Gasto/SolicitarGasto', '#panelFormularioGasto', datos, function (respuesta) {
                    if (respuesta.code == 200) {
                        evento.mostrarMensaje("#errorFormulario", true, "Se ha solicitado la autorización del Gasto. Por favor espere a que sea autorizado", 6000);
                        limpiarFormulario();
                        $("#formGasto").parsley().reset();
                        file.limpiar('#fotosGasto');
                        setTimeout(function () {
                            location.reload();
                        }, 5000);
                    } else if (respuesta.code == 508) {
                        evento.mostrarMensaje("#errorFormulario", false, respuesta.message, 4000);
                        setTimeout(function () {
                            location.reload();
                        }, 10000);
                    } else {
                        evento.mostrarMensaje("#errorFormulario", false, "Ocurrió un error al solicitar el gasto. Por favor recargue su página y vuelva a intentarlo.", 4000);
                    }
                });
            }
        } else {
            evento.mostrarMensaje("#errorFormulario", false, "Todos los campos marcados son obligatorios. Por favor revise el formulario e intente de nuevo.", 4000);
        }
    }
    );

});