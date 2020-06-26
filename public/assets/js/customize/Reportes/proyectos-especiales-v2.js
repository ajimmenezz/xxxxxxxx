$(function () {

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

    tabla.generaTablaPersonal('#table-proyectos-especiales', null, null, true, true, [[0, 'desc']]);

    $('#table-proyectos-especiales tbody').on('dblclick', 'tr', function () {
        let _this = this;
        var datosTabla = $('#table-proyectos-especiales').DataTable().row(_this).data();
        var datos = {
            'datos': datosTabla
        };
        evento.enviarEvento('PEV2/GeneraPDF', datos, '#panelTablaProyectos', function (respuesta) {
            window.open(respuesta, '_blank');
        });
    });




    $("#listTipoBeneficiario").on("change", function () {
        $("#listBeneficiarios").empty().append('<option value="">Selecciona . . .</option>');
        if ($(this).val() !== '') {
            var datos = {
                'id': $(this).val()
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
            var tr = `<tr >
                        <td>` + datos.cat + `<input type="hidden" class="value-categoria" value="` + datos.idCat + `" /></td>
                        <td>` + datos.subcat + `<input type="hidden" class="value-subcategoria" value="` + datos.idSubcat + `" /></td>
                        <td>` + datos.conc + `<input type="hidden" class="value-concepto" value="` + datos.idConc + `" /></td>
                        <td>$` + parseFloat(Math.round(datos.monto * 100) / 100).toFixed(2) + `<input type="hidden" class="value-monto" value="` + datos.monto + `" /></td>
                        <td><button class="btn btn-danger btnRemoveConcepto"><i class="fa fa-trash"></i></button></td>
                    </tr>`;
            $('#table-conceptos-gasto tbody').append(tr);
            select.cambiarOpcion("#listCategoria", '');
            $("#txtMonto").val('');

            actualizaTotal();
            actionsRemove();
        }
    });

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

    $("#btnLimpiarFormulario").off("click");
    $("#btnLimpiarFormulario").on("click", function () {
        select.cambiarOpcion("#listSucursales", "");
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
    });

});