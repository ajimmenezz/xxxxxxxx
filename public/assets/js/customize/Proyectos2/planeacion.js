$(function () {
    //Objetos
    var evento = new Base();
    var websocket = new Socket();
    var tabla = new Tabla();
    var select = new Select();
    var fecha = new Fecha();

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

    google.charts.load('current', {'packages': ['gantt']});

    function daysToMilliseconds(days) {
        return days * 24 * 60 * 60 * 1000;
    }

    function drawChart() {

        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Task ID');
        data.addColumn('string', 'Task Name');
        data.addColumn('date', 'Start Date');
        data.addColumn('date', 'End Date');
        data.addColumn('number', 'Duration');
        data.addColumn('number', 'Percent Complete');
        data.addColumn('string', 'Dependencies');

        data.addRows([
            ['Research', 'Find sources',
                new Date(2015, 0, 1), new Date(2015, 0, 5), null, 100, null],
            ['Write', 'Write paper',
                null, new Date(2015, 0, 9), daysToMilliseconds(3), 25, 'Research,Outline'],
            ['Cite', 'Create bibliography',
                null, new Date(2015, 0, 7), daysToMilliseconds(1), 20, 'Research'],
            ['Complete', 'Hand in paper',
                null, new Date(2015, 0, 10), daysToMilliseconds(1), 0, 'Cite,Write'],
            ['Outline', 'Outline paper',
                null, new Date(2015, 0, 6), daysToMilliseconds(1), 100, 'Research']
        ]);

        var options = {
            height: 275
        };

        var chart = new google.visualization.Gantt(document.getElementById('chart_div'));

        chart.draw(data, options);
    }

    tabla.generaTablaPersonal('#table-proyectos-sin-iniciar', null, null, true, true, [[0, 'asc']]);

    $("#btn-proyecto-nuevo").off("click");
    $("#btn-proyecto-nuevo").on("click", function () {
        evento.enviarEvento('Planeacion/FormularioNuevoProyecto', {}, '#panel-table-proyectos', function (respuesta) {
            $("#divNuevoProyecto").empty().append(respuesta.formulario);
            evento.cambiarDiv("#divListaProyectos", "#divNuevoProyecto", initFormNuevoProyecto());
            drawChart();
        });
    });

    function initFormNuevoProyecto() {
        select.crearSelect("#listClientes");
        select.crearSelect("#listSistemas");
        select.crearSelect("#listTipoProyecto");
        select.crearSelectMultiple("#listSucursales", 'Selecciona...');
        select.crearSelectMultiple("#listLideres", 'Selecciona...');
        fecha.dateRange("#fini", "#ffin", 'DD-MM-YYYY');

        $("#listClientes").on("change", function () {
            var _cliente = $(this).val();
            select.limpiarSelecccion("#listSucursales");
            if (_cliente !== '') {
                evento.enviarEvento('Planeacion/SucursalesByCliente', {'id': _cliente}, '#panel-table-proyectos', function (respuesta) {
                    $("#listSucursales").empty();
                    $.each(respuesta, function (k, v) {
                        $("#listSucursales").append('<option value="' + v.Id + '">' + v.Nombre + '</option>');
                    });
                    $("#listSucursales").removeAttr("disabled");
                });
            } else {
                $("#listSucursales").attr("disabled", "disabled");
            }
        });

        $("#listSistemas").on("change", function () {
            var _sistema = $(this).val();
            select.limpiarSelecccion("#listLideres");
            if (_sistema !== '') {
                evento.enviarEvento('Planeacion/LideresBySistema', {'id': _sistema}, '#panel-table-proyectos', function (respuesta) {
                    $("#listLideres").empty();
                    $.each(respuesta, function (k, v) {
                        $("#listLideres").append('<option value="' + v.Id + '">' + v.Nombre + '</option>');
                    });
                    $("#listLideres").removeAttr("disabled");
                });
            } else {
                $("#listLideres").attr("disabled", "disabled");
            }
        });

        $("#btnLimpiarFormulario").off("click");
        $("#btnLimpiarFormulario").on("click", function () {
            $("#txtNombre").val("");
            $("#txtObservaciones").val("").text("");
            select.cambiarOpcion("#listSistemas", '');
            select.cambiarOpcion("#listTipoProyecto", '');
            select.limpiarSelecccion("#listSucursales");
            select.limpiarSelecccion("#listLideres");
            $("#rangoFechas input").val('');
            $("#rangoFechas").datepicker('update', '');
        });

        $("#btnGeneraProyecto").off("click");
        $("#btnGeneraProyecto").on("click", function () {
            if (evento.validarFormulario("#formNuevoProyecto")) {
                var datos = {
                    'nombre': $.trim($("#txtNombre").val()),
                    'cliente': $("#listClientes").val(),
                    'sistema': $("#listSistemas").val(),
                    'tipo': $("#listTipoProyecto").val(),
                    'sucursal': $("#listSucursales").val(),
                    'lider': $("#listLideres").val(),
                    'observaciones': $("#txtObservaciones").val(),
                    'fini': $("#fini").val(),
                    'ffin': $("#ffin").val()
                };

                evento.enviarEvento('Planeacion/GenerarProyecto', datos, '#panel-table-proyectos', function (respuesta) {
                    if (respuesta.code == 200) {
                        if (respuesta.tickets.length > 1) {
                            location.reload();
                        } else {
                            //Aqui va la llamada del codígo que abre el detalle del proyecto.
                            location.reload();
                        }
                    } else {
                        evento.mostrarMensaje("#errorNuevoProyecto", false, "Ocurrió un error al crear el proyecto: " + respuesta.error, 5000);
                    }
                });
            } else {
                evento.mostrarMensaje("#errorNuevoProyecto", false, "Los marcados son obligatorios. Por favor revise la información.", 5000);
            }
        });
    }

    $('#table-proyectos-sin-iniciar tbody').on('click', 'tr', function () {
        let _this = this;
        var datosTabla = $("#table-proyectos-sin-iniciar").DataTable().row(_this).data();
        var datos = {
            'id': datosTabla[0]
        };

        evento.enviarEvento('Planeacion/FormularioDetallesProyecto', datos, '#panel-table-proyectos', function (respuesta) {
            if (respuesta.code == 200) {
                $("#divDetallesProyecto").empty().append(respuesta.formulario);
                evento.cambiarDiv("#divListaProyectos", "#divDetallesProyecto", initDetallesProyecto());
            } else {
                evento.mostrarMensaje("#errorTablePlaneacion", false, respuesta.error, 4000);
            }
        });
    });

    function initDetallesProyecto() {
        select.crearSelect("#listClientes");
        select.crearSelect("#listSistemas");
        select.crearSelect("#listTipoProyecto");
        select.crearSelect("#listSucursales");
        select.crearSelect("#listTecnicos");
        select.crearSelectMultiple("#listLideres", 'Selecciona...');
        fecha.dateRange("#fini", "#ffin", 'DD-MM-YYYY');

        tabla.generaTablaPersonal('#table-ubicaciones', null, null, true, true, [[0, 'asc']]);

        tabla.generaTablaPersonal('#table-material-diferencias', null, null, true, false, [[0, 'asc']], null, null, false);
        tabla.generaTablaPersonal('#table-tecnicos', null, null, true, false, [[2, 'asc']]);
        tabla.generaTablaPersonal('#table-tareas', null, null, true, false, [[1, 'asc']]);

        $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
            var target = $(e.target).attr("href");
            switch (target) {
                case "#Alcance":
                    cargaUbicacionesProyecto();
                    break;
                case "#Material":
                    cargaMaterialTotales();
                    break;
                case "#Tecnicos":
                    cargaDatosTecnicos();
                    break;
                case "#Tareas":
                    cargaTareasProyecto();
                    break;
                case "#nodosTarea":
                    cargaNodosTareaAgrupados();
                    break;
            }
        });


        $("#listClientes").on("change", function () {
            var _cliente = $(this).val();
            $("#listSucursales").empty().append("<option value=''>Selecciona . . .</option>");
            select.cambiarOpcion("#listSucursales", '');
            $("#listSucursales").attr("disabled", "disabled");
            if (_cliente !== '') {
                evento.enviarEvento('Planeacion/SucursalesByCliente', {'id': _cliente}, '#panel-table-proyectos', function (respuesta) {
                    $.each(respuesta, function (k, v) {
                        $("#listSucursales").append('<option value="' + v.Id + '">' + v.Nombre + '</option>');
                    });
                    $("#listSucursales").removeAttr("disabled");
                });
            }
        });

        $("#listSistemas").on("change", function () {
            var _sistema = $(this).val();
            select.limpiarSelecccion("#listLideres");
            if (_sistema !== '') {
                evento.enviarEvento('Planeacion/LideresBySistema', {'id': _sistema}, '#panel-table-proyectos', function (respuesta) {
                    $("#listLideres").empty();
                    $.each(respuesta, function (k, v) {
                        $("#listLideres").append('<option value="' + v.Id + '">' + v.Nombre + '</option>');
                    });
                    $("#listLideres").removeAttr("disabled");
                });
            } else {
                $("#listLideres").attr("disabled", "disabled");
            }
        });


        $("#btnGuardarCambios").off("click");
        $("#btnGuardarCambios").on("click", function () {
            if (evento.validarFormulario("#formGeneralesProyecto")) {
                var datos = {
                    'id': $.trim($("#IdProyecto").val()),
                    'nombre': $.trim($("#txtNombre").val()),
                    'cliente': $("#listClientes").val(),
                    'sucursal': $("#listSucursales").val(),
                    'sistema': $("#listSistemas").val(),
                    'tipo': $("#listTipoProyecto").val(),
                    'lider': $("#listLideres").val(),
                    'observaciones': $("#txtObservaciones").val(),
                    'fini': $("#fini").val(),
                    'ffin': $("#ffin").val()
                };

                evento.enviarEvento('Planeacion/GuardarGeneralesProyecto', datos, '#panelFormDetallesProyecto', function (respuesta) {
                    if (respuesta.code == 200) {
                        evento.enviarEvento('Planeacion/FormularioDetallesProyecto', datos, '#panelFormDetallesProyecto', function (respuesta) {
                            if (respuesta.code == 200) {
                                $("#divDetallesProyecto").empty().append(respuesta.formulario);
                                evento.cambiarDiv("#divListaProyectos", "#divDetallesProyecto", initDetallesProyecto());
                            } else {
                                evento.mostrarMensaje("#errorMessage", false, respuesta.error, 4000);
                            }
                        });
                    } else {
                        evento.mostrarMensaje("#errorMessage", false, "Ocurrió un error al guardar los cambios: " + respuesta.error, 5000);
                    }
                });
            } else {
                evento.mostrarMensaje("#errorMessage", false, "Los marcados son obligatorios. Por favor revise la información.", 5000);
            }
        });

        $("#btnAddUbicacion").off("click");
        $("#btnAddUbicacion").on("click", function () {
            var datos = {
                'id': $.trim($("#IdProyecto").val())
            };
            evento.enviarEvento('Planeacion/FormularioNuevaUbicacion', datos, '#panelFormDetallesProyecto', function (respuesta) {
                if (respuesta.code == 200) {
                    $("#divNuevaUbicacion").empty().append(respuesta.formulario);
                    evento.cambiarDiv("#divDetallesProyecto", "#divNuevaUbicacion", initNuevaUbicacion());
                } else {
                    evento.mostrarMensaje("#errorMessage", false, respuesta.error, 4000);
                }
            });
        });

        $("#btnNuevaTarea").off("click");
        $("#btnNuevaTarea").on("click", function () {
            var datos = {
                'id': $.trim($("#IdProyecto").val())
            };
            evento.enviarEvento('Planeacion/FormularioNuevaTarea', datos, '#panelFormDetallesProyecto', function (respuesta) {
                if (respuesta.code == 200) {
                    evento.iniciarModal("#modalEdit", "Agregar Nueva Tarea", respuesta.formulario);
                    initFormularioNuevaTarea();
                } else {
                    evento.mostrarMensaje("#errorMessage", false, respuesta.error, 4000);
                }
            });
        });

        $("#btnDocumentoInicial").off("click");
        $("#btnDocumentoInicial").on("click", function () {
            evento.enviarEvento('Planeacion/GeneraDocumentoInicial', {'id': $.trim($("#IdProyecto").val())}, '#panelFormDetallesProyecto', function (respuesta) {
                window.open(respuesta, '_blank');
            });
        });

        $("#btnSolicitudMaterial").off("click");
        $("#btnSolicitudMaterial").on("click", function () {
            var datos = {
                'id': $("#IdProyecto").val(),
                'almacen': $.trim($("#IdAlmacenSAE").val())
            }
            evento.enviarEvento('Planeacion/GeneraSolicitudMaterial', datos, '#panelFormDetallesProyecto', function (respuesta) {
                window.open(respuesta, '_blank');
            });
        });

        $("#btnSolicitudMaterialFaltante").off("click");
        $("#btnSolicitudMaterialFaltante").on("click", function () {
            var datos = {
                'id': $("#IdProyecto").val(),
                'almacen': $.trim($("#IdAlmacenSAE").val())
            }
            evento.enviarEvento('Planeacion/GeneraSolicitudMaterialFaltante', datos, '#panelFormDetallesProyecto', function (respuesta) {
                window.open(respuesta, '_blank');
            });
        });
        
        $("#btnMaterialNodosPdf").off("click");
        $("#btnMaterialNodosPdf").on("click", function () {
            var datos = {
                'id': $("#IdProyecto").val()                
            }
            evento.enviarEvento('Planeacion/GeneraDocumentoMaterialNodos', datos, '#panelFormDetallesProyecto', function (respuesta) {
                window.open(respuesta, '_blank');
            });
        });
        
        $("#btnNodosPdf").off("click");
        $("#btnNodosPdf").on("click", function () {
            var datos = {
                'id': $("#IdProyecto").val()                
            }
            evento.enviarEvento('Planeacion/GeneraDocumentoNodos', datos, '#panelFormDetallesProyecto', function (respuesta) {
                window.open(respuesta, '_blank');
            });
        });

    }

    function initNuevaUbicacion() {
        var _idProyecto = $.trim($("#IdProyecto").val());
        select.crearSelect("#listConceptos");
        select.crearSelect("#listAreas");
        select.crearSelect("#listUbicaciones");

        $("#listConceptos").on("change", function () {
            var _concepto = $(this).val();
            $("#listAreas").empty().append("<option value=''>Selecciona . . .</option>").attr("disabled", "disabled");
            select.cambiarOpcion("#listAreas", '');

            if (_concepto !== '') {
                evento.enviarEvento('Planeacion/AreasByConcepto', {'id': _concepto}, '#panelNuevaUbicacion', function (respuesta) {
                    $.each(respuesta, function (k, v) {
                        $("#listAreas").append('<option value="' + v.Id + '">' + v.Nombre + '</option>');
                    });
                    $("#listAreas").removeAttr("disabled");
                });
            }
        });

        $("#listAreas").on("change", function () {
            var _area = $(this).val();
            $("#listUbicaciones").empty().append("<option value=''>Selecciona . . .</option>").attr("disabled", "disabled");
            select.cambiarOpcion("#listUbicaciones", '');

            if (_area !== '') {
                evento.enviarEvento('Planeacion/UbicacionesByArea', {'id': _area}, '#panelNuevaUbicacion', function (respuesta) {
                    $.each(respuesta, function (k, v) {
                        $("#listUbicaciones").append('<option value="' + v.Id + '">' + v.Nombre + '</option>');
                    });
                    $("#listUbicaciones").removeAttr("disabled");
                });
            }
        });

        $("#listUbicaciones").on("change", function () {
            var _ubicacion = $(this).val();
            if (_ubicacion !== '') {
                var datos = {
                    'id': _idProyecto,
                    'concepto': $("#listConceptos").val(),
                    'area': $("#listAreas").val(),
                    'ubicacion': _ubicacion
                };
                evento.enviarEvento('Planeacion/FormularioNodosUbicacion', datos, '#panelNuevaUbicacion', function (respuesta) {
                    $("#divFormNodos").empty().append(respuesta.formulario);
                    evento.mostraDiv("#divFormNodos");
                    select.crearSelect("#listTiposNodo");
                    select.crearSelect("#listMateriales");
                    tabla.generaTablaPersonal('#table-nodos-ubicacion', null, null, true, false, [[4, 'asc']], null, null, false);

                    $('input[type=radio][name=radioMaterialKit]').change(function () {
                        if (this.value == '1') {
                            evento.ocultarDiv('div#agregarKit', evento.mostraDiv("div#agregarMaterial"));
//                            $('#formNodoUbicacion').parsley().destroy();
                            $("#listKits").removeAttr("data-parsley-required");
                            $("#listMateriales").attr("data-parsley-required", "true");
                            $("#txtCantidad").attr("data-parsley-required", "true");
                            $("#txtCantidad").val('');
                            $("#formNodoUbicacion").parsley().reset();
                        } else if (this.value == '2') {
//                            $('#formNodoUbicacion').parsley().destroy();
                            evento.ocultarDiv('div#agregarMaterial', evento.mostraDiv("div#agregarKit"));
                            $("#listKits").attr("data-parsley-required", 'true');
                            $("#listMateriales").removeAttr("data-parsley-required");
                            $("#txtCantidad").removeAttr("data-parsley-required");
                            $("#txtCantidad").val(1);
                            $("#formNodoUbicacion").parsley().reset();
                        }
                    });

                    $("#btnAddNodoUbicacion").off("click");
                    $("#btnAddNodoUbicacion").on("click", function () {
                        if (evento.validarFormulario("#formNodoUbicacion")) {
                            var datos = {
                                'idTipo': $("#listTiposNodo").val(),
                                'tipo': $("#listTiposNodo option:selected").text(),
                                'nombre': $.trim($("#txtNombreNodo").val()).toUpperCase(),
                                'idAccesorio': $("#listMateriales option:selected").attr("data-id-accesorio"),
                                'accesorio': $("#listMateriales option:selected").attr("data-accesorio"),
                                'idMaterial': $("#listMateriales option:selected").attr("data-id-material"),
                                'material': $("#listMateriales option:selected").attr("data-material"),
                                'cantidad': parseFloat($.trim($("#txtCantidad").val()))
                            };

                            var existe = false;
                            $('#table-nodos-ubicacion tbody tr').each(function () {
                                var _this = this;
                                var datosTabla = $("#table-nodos-ubicacion").DataTable().row(_this).data();
                                if (typeof datosTabla !== 'undefined') {
                                    if (datosTabla[1] == datos.idTipo && datosTabla[2] == datos.idAccesorio && datosTabla[3] == datos.idMaterial && datosTabla[5] == datos.nombre) {
                                        existe = true;
                                    }
                                }
                            });

                            if (existe) {
                                evento.mostrarMensaje("#errorNodosUbicacion", false, "Ya existe un registro similar al que intenta agregar. Si desea cambiar la cantidad o eliminar el registro de doble clic sobre el registro en la tabla.", 5000);
                            } else {
                                tabla.agregarFila('#table-nodos-ubicacion', [
                                    '', datos.idTipo, datos.idAccesorio, datos.idMaterial, datos.tipo, datos.nombre, datos.accesorio, datos.material, datos.cantidad
                                ]);
                                select.cambiarOpcion("#listMateriales", '');
                                $("#txtCantidad").val('');
                                $("#formNodoUbicacion").parsley().reset();
                                evento.mostrarMensaje("#errorNodosUbicacion", true, "Registro agregado. No olvide gardar todos los cambios antes de salir.", 5000);
                            }
                        } else {
                            evento.mostrarMensaje("#errorNodosUbicacion", false, "Los campos marcados son obligatorios. Por favor revise la información.", 5000);
                        }
                    });


                    $("#btnAgregarKit").off("click");
                    $("#btnAgregarKit").on("click", function () {
                        if (evento.validarFormulario("#formNodoUbicacion")) {
                            var _idKit = $("#listKits").val();
                            var _registrosExiten = [];
                            $(".divHiddenValues-" + _idKit).each(function () {
                                var _thisMaterialKit = this;
                                var datos = {
                                    'idTipo': $("#listTiposNodo").val(),
                                    'tipo': $("#listTiposNodo option:selected").text(),
                                    'nombre': $.trim($("#txtNombreNodo").val()).toUpperCase(),
                                    'idAccesorio': $(_thisMaterialKit).find(".materialKit-idAccesorio").val(),
                                    'accesorio': $(_thisMaterialKit).find(".materialKit-accesorio").val(),
                                    'idMaterial': $(_thisMaterialKit).find(".materialKit-idMaterial").val(),
                                    'material': $(_thisMaterialKit).find(".materialKit-material").val(),
                                    'cantidad': $(_thisMaterialKit).find(".materialKit-cantidad").val()
                                };

                                var existe = false;
                                $('#table-nodos-ubicacion tbody tr').each(function () {
                                    var _this = this;
                                    var datosTabla = $("#table-nodos-ubicacion").DataTable().row(_this).data();
                                    if (typeof datosTabla !== 'undefined') {
                                        if (datosTabla[1] == datos.idTipo && datosTabla[2] == datos.idAccesorio && datosTabla[3] == datos.idMaterial && datosTabla[5] == datos.nombre) {
                                            existe = true;
                                        }
                                    }
                                });

                                if (existe) {
                                    _registrosExiten.push('<br />Ya existe un registro similar para: ' + datos.material);
                                } else {
                                    tabla.agregarFila('#table-nodos-ubicacion', [
                                        '', datos.idTipo, datos.idAccesorio, datos.idMaterial, datos.tipo, datos.nombre, datos.accesorio, datos.material, datos.cantidad
                                    ]);
                                }
                            });

                            if (_registrosExiten.length <= 0) {
                                evento.mostrarMensaje("#errorNodosUbicacion", true, "Se ha agregado el kit correctamente.", 5000);
                            } else {
                                var htmlError = '';
                                $.each(_registrosExiten, function (k, v) {
                                    htmlError += v;
                                });
                                htmlError += '<br />Si desea cambiar la cantidad o eliminar el registro de doble clic sobre el registro en la tabla.';
                                evento.mostrarMensaje("#errorNodosUbicacion", false, htmlError, 10000);
                            }

                            select.cambiarOpcion("#listKits", '');
                            $("#formNodoUbicacion").parsley().reset();

                        } else {
                            evento.mostrarMensaje("#errorNodosUbicacion", false, "Los campos marcados son obligatorios. Por favor revise la información.", 5000);
                        }
                    });

                    $('#table-nodos-ubicacion tbody').on('click', 'tr', function () {
                        let _thisFila = this;
                        var datosTabla = $('#table-nodos-ubicacion').DataTable().row(_thisFila).data();
                        var datos = {
                            'id': _idProyecto,
                            'tipo': datosTabla[1],
                            'accesorio': datosTabla[2],
                            'material': datosTabla[3],
                            'nombre': datosTabla[5],
                            'cantidad': datosTabla[8]
                        };
                        evento.enviarEvento('Planeacion/FormularioEditarNodo', datos, '#panelNuevaUbicacion', function (respuesta) {
                            if (respuesta.code == 200) {
                                evento.iniciarModal('#modalEdit', 'Editar Nodo', respuesta.formulario);

                                $("#listTiposNodoEdit").combobox();
                                $("#listMaterialesEdit").combobox();

                                $("#btnEliminarNodo").off("click");
                                $("#btnEliminarNodo").on("click", function () {
                                    var datosTabla = $('#table-nodos-ubicacion').DataTable().row(_thisFila).data();
                                    if (datosTabla[0] !== "") {
                                        evento.enviarEvento('Planeacion/EliminarNodo', {'id': datosTabla[0]}, '#panelNuevaUbicacion', function (respuesta) {
                                            if (respuesta.code == 200) {
                                                tabla.eliminarFila('#table-nodos-ubicacion', _thisFila);
                                                evento.terminarModal('#modalEdit');
                                            } else {
                                                evento.mostrarMensaje("#error-in-modal", false, "No se ha podido eliminar el nodo: " + respuesta.error, 4000);
                                            }
                                        });
                                    } else {
                                        tabla.eliminarFila('#table-nodos-ubicacion', _thisFila);
                                        evento.terminarModal('#modalEdit');
                                    }


                                });

                                $("#btnGuardarCambiosModal").off("click");
                                $("#btnGuardarCambiosModal").on("click", function () {
                                    var datos = {
                                        'idTipo': $("#listTiposNodoEdit").val(),
                                        'tipo': $("#listTiposNodoEdit option:selected").text(),
                                        'nombre': $.trim($("#txtNombreNodoEdit").val()).toUpperCase(),
                                        'aux': $("#listMaterialesEdit").val(),
                                        'idAccesorio': $("#listMaterialesEdit option:selected").attr("data-id-accesorio"),
                                        'accesorio': $("#listMaterialesEdit option:selected").attr("data-accesorio"),
                                        'idMaterial': $("#listMaterialesEdit option:selected").attr("data-id-material"),
                                        'material': $("#listMaterialesEdit option:selected").attr("data-material"),
                                        'cantidad': parseFloat($.trim($("#txtCantidadEdit").val()))
                                    };
                                    if (datos.idTipo !== "" && datos.nombre !== '' && datos.aux !== '' && datos.cantidad > 0) {

                                        var existe = false;
                                        $('#table-nodos-ubicacion tbody tr').each(function () {
                                            var _this = this;
                                            if (_this == _thisFila) {
                                                return true;
                                            } else {
                                                var datosTabla = $("#table-nodos-ubicacion").DataTable().row(_this).data();
                                                if (typeof datosTabla !== 'undefined') {
                                                    if (datosTabla[1] == datos.idTipo && datosTabla[2] == datos.idAccesorio && datosTabla[3] == datos.idMaterial && datosTabla[5] == datos.nombre) {
                                                        existe = true;
                                                    }
                                                }
                                            }
                                        });

                                        if (existe) {
                                            evento.mostrarMensaje("#error-in-modal", false, "Ya existe un registro similar al que intenta agregar.", 4000);
                                        } else {
                                            evento.terminarModal('#modalEdit');
                                            $('#table-nodos-ubicacion').DataTable().row(_thisFila).data([
                                                datosTabla[0], datos.idTipo, datos.idAccesorio, datos.idMaterial, datos.tipo, datos.nombre, datos.accesorio, datos.material, datos.cantidad
                                            ]);
                                            tabla.reordenarTabla('#table-nodos-ubicacion', [4, 'asc']);
                                            select.cambiarOpcion("#listMateriales", '');
                                            $("#txtCantidad").val('');
                                            $("#formNodoUbicacion").parsley().reset();
                                            evento.mostrarMensaje("#errorNodosUbicacion", true, "Registro actualizado. No olvide gardar todos los cambios antes de salir.", 5000);
                                        }
                                    } else {
                                        evento.mostrarMensaje("#error-in-modal", false, "Todos los campos marcados son obligatorios. Revise su información", 4000);
                                    }

                                });

                            } else {
                                evento.mostrarMensaje("#errorNodosUbicacion", false, respuesta.error, 4000);
                            }
                        });
                    });

                    $("#btnGuardarNodos").off("click");
                    $("#btnGuardarNodos").on("click", function () {
                        var datos = {
                            'id': _idProyecto,
                            'concepto': $("#listConceptos").val(),
                            'area': $("#listAreas").val(),
                            'ubicacion': $("#listUbicaciones").val(),
                            'nodos': []
                        }

                        var table = $('#table-nodos-ubicacion').DataTable().search('').columns().search('').draw();

                        $('#table-nodos-ubicacion tbody tr').each(function () {
                            var _this = this;
                            var datosTabla = $("#table-nodos-ubicacion").DataTable().row(_this).data();
                            if (typeof datosTabla !== 'undefined') {
                                datos.nodos.push({
                                    'id': datosTabla[0],
                                    'tipo': datosTabla[1],
                                    'nombre': datosTabla[5],
                                    'accesorio': datosTabla[2],
                                    'material': datosTabla[3],
                                    'cantidad': datosTabla[8]
                                });
                            }
                        });

                        if (datos.nodos.length <= 0) {
                            evento.mostrarMensaje("#errorGuardarNodosUbicacion", false, "La tabla de nodos debe contener al menos un registro para ser guardada", 5000);
                        } else {
                            evento.enviarEvento('Planeacion/GuardarNodosUbicacion', datos, '#panelNuevaUbicacion', function (respuesta) {
                                if (respuesta.code == 200) {
                                    $('#divNuevaUbicacion').fadeOut(400, function () {
                                        $('#divDetallesProyecto').fadeIn(400, function () {
                                            $('#divNuevaUbicacion').empty();
                                            cargaUbicacionesProyecto();
                                        });
                                    });
                                } else {
                                    evento.mostrarMensaje("#errorGuardarNodosUbicacion", false, "Ocurrió un error: " + respuesta.error, 5000);
                                }
                            });
                        }

                    });

                    $("#listKits").on("change", function () {
                        var _thisKit = $(this).val();
                        evento.ocultarDiv(".divMaterialKit");
                        if (_thisKit !== '') {
                            evento.mostraDiv("#divMaterialKit-" + _thisKit);
                        }
                    });

                });
            } else {
                evento.ocultarDiv("#divFormNodos", function () {
                    $("#divFormNodos").empty();
                });
            }
        });
    }

    function cargaUbicacionesProyecto() {
        evento.enviarEvento('Planeacion/CargaUbicacionesProyecto', {'id': $.trim($("#IdProyecto").val())}, '#panelFormDetallesProyecto', function (respuesta) {
            tabla.limpiarTabla("#table-ubicaciones");
            $.each(respuesta, function (k, v) {
                tabla.agregarFila("#table-ubicaciones", [
                    v.Id, v.Concepto, v.Area, v.Ubicacion
                ]);
            });
            tabla.reordenarTabla("#table-ubicaciones", [[1, "asc"]]);

            $('#table-ubicaciones tbody').on('click', 'tr', function () {
                let _thisFila = this;
                var datosTabla = $('#table-ubicaciones').DataTable().row(_thisFila).data();
                var datos = {
                    'id': $.trim($("#IdProyecto").val()),
                    'alcance': datosTabla[0]
                };
                evento.enviarEvento('Planeacion/FormularioNuevaUbicacion', datos, '#panelFormDetallesProyecto', function (respuesta) {
                    if (respuesta.code == 200) {
                        $("#divNuevaUbicacion").empty().append(respuesta.formulario);
                        evento.cambiarDiv("#divDetallesProyecto", "#divNuevaUbicacion", initNuevaUbicacion());
                        select.cambiarOpcion("#listUbicaciones", $("#listUbicaciones").val());
                    } else {
                        evento.mostrarMensaje("#errorMessage", false, respuesta.error, 4000);
                    }
                });
            });
        });
    }

    function cargaMaterialTotales() {
        var datos = {
            'id': $.trim($("#IdProyecto").val()),
            'almacen': $.trim($("#IdAlmacenSAE").val())
        };
        evento.enviarEvento('Planeacion/CargaMaterialTotales', datos, '#panelFormDetallesProyecto', function (respuesta) {

            tabla.limpiarTabla("#table-material-proyectado");
            $.each(respuesta.proyectado, function (k, v) {
                tabla.agregarFila("#table-material-proyectado", [
                    v.Material, v.Clave, v.Total, v.Unidad
                ]);
            });
            tabla.reordenarTabla("#table-material-proyectado", [[0, "asc"]]);

            tabla.limpiarTabla("#table-material-sae");
            $.each(respuesta.sae, function (k, v) {
                tabla.agregarFila("#table-material-sae", [
                    v.Material, v.Clave, v.Total, v.Unidad
                ]);
            });
            tabla.reordenarTabla("#table-material-sae", [[0, "asc"]]);

            tabla.limpiarTabla("#table-material-diferencias");
            $.each(respuesta.diferencia, function (k, v) {
                tabla.agregarFila("#table-material-diferencias", [
                    v.Material, v.Clave, v.Unidad, v.Solicitado, v.Asignado, v.Diferencia
                ]);
            });
            tabla.reordenarTabla("#table-material-diferencias", [[0, "asc"]]);

            $("#table-material-diferencias > tbody > tr").each(function () {
                let _thisFila = this;
                var datosTabla = $('#table-material-diferencias').DataTable().row(_thisFila).data();
                if (datosTabla[5] < 0) {
                    $(_thisFila).find("td:last-child").addClass('bg-red f-w-700 text-white text-center');
                } else if (datosTabla[5] > 0) {
                    $(_thisFila).find("td:last-child").addClass('bg-green f-w-700 text-white text-center');
                } else {
                    $(_thisFila).find("td:last-child").addClass('f-w-700 text-center');
                }
            });

        });

    }

    function cargaDatosTecnicos() {
        evento.enviarEvento('Planeacion/CargaDatosTecnicos', {'id': $.trim($("#IdProyecto").val())}, '#panelFormDetallesProyecto', function (respuesta) {
            tabla.limpiarTabla("#table-tecnicos");
            $.each(respuesta.asignados, function (k, v) {
                tabla.agregarFila("#table-tecnicos", [
                    v.Id, v.IdUsuario, v.Nombre, v.Perfil, v.NSS
                ]);
            });
            tabla.reordenarTabla("#table-tecnicos", [[0, "asc"]]);

            $("#listTecnicos").empty().append('<option value="">Selecciona . . .</option>');
            $.each(respuesta.tecnicos, function (k, v) {
                $("#listTecnicos").append('<option value="' + v.Id + '">' + v.Nombre + '</option>');
            });

            select.cambiarOpcion("#listTecnicos", '');

            $('#table-tecnicos tbody').on('click', 'tr', function () {
                let _this = this;
                var datosTabla = $("#table-tecnicos").DataTable().row(_this).data();
                evento.enviarEvento('Planeacion/FormDetallesAsistente', {'id': $.trim($("#IdProyecto").val()), 'idRegistro': datosTabla[0]}, '#panelFormDetallesProyecto', function (respuesta) {
                    if (respuesta.code == 200) {
                        evento.iniciarModal('#modalEdit', 'Técnico del Proyecto', respuesta.formulario);
                        $("#btnGuardarCambiosModal").hide();


                        $("#btnEliminarAsistente").off("click");
                        $("#btnEliminarAsistente").on("click", function () {
                            evento.enviarEvento('Planeacion/EliminarAsistente', {'id': $.trim($("#idTecnico").val())}, '#modalEdit', function (respuesta) {
                                if (respuesta.code == 200) {
                                    tabla.eliminarFila('#table-tecnicos', _this);
                                    evento.terminarModal('#modalEdit');
                                } else {
                                    evento.mostrarMensaje("#error-in-modal", false, "No se ha podido eliminar al técnico: " + respuesta.error, 4000);
                                }
                            });
                        });



                    } else {
                        evento.mostrarMensaje("#errorAgregarAsistente", false, 'Error al intentar obtener la información del técnico.', 4000);
                    }
                });
            });

            $("#btnAddAsistente").off("click");
            $("#btnAddAsistente").on("click", function () {
                var _tecnico = $("#listTecnicos").val();
                if (_tecnico !== "") {
                    var existe = false;
                    $('#table-tecnicos tbody tr').each(function () {
                        var _this = this;
                        var datosTabla = $("#table-tecnicos").DataTable().row(_this).data();
                        if (typeof datosTabla !== 'undefined') {
                            if (datosTabla[1] == _tecnico) {
                                existe = true;
                            }
                        }
                    });

                    if (existe) {
                        evento.mostrarMensaje("#errorAgregarAsistente", false, 'El técnico ya está asignado al proyecto', 4000);
                    } else {
                        var datos = {
                            'id': $.trim($("#IdProyecto").val()),
                            'tecnico': _tecnico
                        }
                        evento.enviarEvento('Planeacion/GuardaAsistenteProyecto', datos, '#panelFormDetallesProyecto', function (respuesta) {
                            if (respuesta.code == 200) {
                                cargaDatosTecnicos();
                            } else {
                                evento.mostrarMensaje("#errorAgregarAsistente", false, 'Ocurrió en error al intentar guardar: ' + respuesta.error, 4000);
                            }
                        });
                    }
                } else {
                    evento.mostrarMensaje("#errorAgregarAsistente", false, 'Debe seleccionar un Técnico de la lista', 4000);
                }
            });


        });
    }

    function initFormularioNuevaTarea() {
        select.crearSelect("#listPredecesora");
        select.crearSelect("#listLiderTarea");
        select.crearSelectMultiple("#listTecnicosTarea", 'Selecciona...');

        $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
            var target = $(e.target).attr("href");
            switch (target) {
                case "#nodosTarea":
                    cargaNodosTareaAgrupados();
                    break;
                case "#materialTarea":
                    cargaMaterialTarea();
                    break;
            }
        });

        var minDate = $("#hiddenIni").val();
        var maxDate = $("#hiddenFin").val();
        minDate = (minDate != null && minDate != '00-00-0000') ? minDate.toString() : false;
        maxDate = (maxDate != null && maxDate != '00-00-0000') ? maxDate.toString() : false;

        fecha.dateRange("#finitarea", "#ffintarea", 'DD-MM-YYYY');
//        $("#finitarea").data("DateTimePicker").minDate(minDate);
//        $("#finitarea").data("DateTimePicker").maxDate(maxDate);
//        $("#ffintarea").data("DateTimePicker").minDate(minDate);
//        $("#ffintarea").data("DateTimePicker").maxDate(maxDate);

        $("#listPredecesora").on("change", function () {
            var pred = $(this).val();
            if (pred !== '') {
                var _minDate = $("#listPredecesora option:selected").attr("data-fin");
                var _finicio;
                if ($("#finitarea").val() != "") {
                    _finicio = $("#finitarea").val();
                } else {
                    _finicio = _minDate;
                }

                $("#finitarea").data("DateTimePicker").minDate(_minDate);
                $("#finitarea").data("DateTimePicker").date(_finicio);
            } else {
                $("#finitarea").data("DateTimePicker").minDate(minDate);
                if ($.trim($("#finitarea").val()) === "") {
                    $("#finitarea").data("DateTimePicker").date('');
                }
            }
        });

        $("#listPredecesora").change();

        var button = ($("#IdTarea").val() > 0) ? 'btnGuardarTarea' : 'btnGuardarCambiosModal';

        $("#" + button).off("click");
        $("#" + button).on("click", function () {
            if (evento.validarFormulario("#formNuevaTarea")) {
                var datos = {
                    'id': $.trim($("#IdProyecto").val()),
                    'idTarea': $("#IdTarea").val(),
                    'nombre': $.trim($("#txtNombreTarea").val()),
                    'predecesora': $("#listPredecesora").val(),
                    'predecesoraS': $("#listPredecesora option:selected").text(),
                    'fini': $.trim($("#finitarea").val()),
                    'ffin': $.trim($("#ffintarea").val()),
                    'lider': $("#listLiderTarea").val(),
                    'liderS': $("#listLiderTarea option:selected").text(),
                    'tecnicos': $("#listTecnicosTarea").val()
                };

                if (datos.idTarea > 0) {
                    evento.enviarEvento('Planeacion/NuevaTarea', datos, '#panelFormEditarTarea', function (respuesta) {
                        if (respuesta.code == 200) {
                            evento.mostrarMensaje("#divErrorTarea", true, "Cambios guardados.", 5000);
                            cargaTareasProyecto();
                        } else {
                            evento.mostrarMensaje("#divErrorTarea", false, "Ocurrió un error al guardar la tarea: " + respuesta.error, 5000);
                        }
                    });
                } else {
                    evento.enviarEvento('Planeacion/NuevaTarea', datos, '#modalEdit', function (respuesta) {
                        if (respuesta.code == 200) {
                            evento.terminarModal("#modalEdit");
                            cargaTareasProyecto();
                        } else {
                            evento.mostrarMensaje("#error-in-modal", false, "Ocurrió un error al guardar la tarea: " + respuesta.error, 5000);
                        }
                    });
                }
            } else {
                evento.mostrarMensaje("#error-in-modal", false, "Los campos marcados son obligatorios. Por favor revise la información.", 5000);
            }
        });

        $("#btnEliminarTarea").off("click");
        $("#btnEliminarTarea").on("click", function () {
            var datos = {
                'id': $(this).attr("data-id")
            };

            evento.enviarEvento('Planeacion/EliminarTarea', datos, '#panelFormEditarTarea', function (respuesta) {
                if (respuesta.code == 200) {
                    $("#divDetallesTarea #btnRegresar").click();
                    cargaTareasProyecto();
                } else {
                    evento.mostrarMensaje("#divErrorTarea", false, "Ocurrió un error al eliminar la tarea: " + respuesta.error, 5000);
                }
            });
        });
    }

    function drawGanttChart() {
        var _data = tabla.getTableData("#table-tareas");
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Task ID');
        data.addColumn('string', 'Task Name');
        data.addColumn('date', 'Start Date');
        data.addColumn('date', 'End Date');
        data.addColumn('number', 'Duration');
        data.addColumn('number', 'Percent Complete');
        data.addColumn('string', 'Dependencies');

        $.each(_data, function (k, v) {
            if ($.isNumeric(k)) {
                var _inicio = null;
                var _fin = null;
                if (v[4] !== '') {
                    _inicio = new Date(v[4].substring(6) + "-" + v[4].substring(3, 5) + "-" + v[4].substring(0, 2));
                    _inicio.setDate(_inicio.getDate() + 1);
                }
                if (v[5] !== '') {
                    var _fin = new Date(v[5].substring(6) + "-" + v[5].substring(3, 5) + "-" + v[5].substring(0, 2));
                    _fin.setDate(_fin.getDate() + 1);
                }

                data.addRows([
                    [
                        v[0],
                        v[2],
                        _inicio,
                        _fin,
                        null,
                        20,
                        (v[1] > 0) ? v[1] : null
                    ]
                ]);
            }
        });

        var options = {
            height: 300
        };

        var chart = new google.visualization.Gantt(document.getElementById('chart_div'));

        chart.draw(data, options);
    }

    function cargaTareasProyecto() {
        evento.enviarEvento('Planeacion/CargaTareasProyecto', {'id': $.trim($("#IdProyecto").val())}, '#panelFormDetallesProyecto', function (respuesta) {
            tabla.limpiarTabla("#table-tareas");
            $.each(respuesta, function (k, v) {
                var datos = {
                    'idPredecesora': v.IdPredecesora || '',
                    'predecesora': v.Predecesora || '',
                    'inicio': (v.Inicio !== null && v.Inicio != '0000-00-00 00:00:00') ? v.Inicio : '',
                    'fin': (v.Fin !== null && v.Fin != '0000-00-00 00:00:00') ? v.Fin : '',
                    'lider': v.Lider || '',
                    'tecnicos': (v.Tecnicos !== null) ? v.Tecnicos.replace(",", "<br />") : '',
                    'rm': (v.RM <= 0) ? 'NO' : 'SI'
                };

                var html = `
                            <tr>
                                <td>` + v.Id + `</td>
                                <td>` + datos.idPredecesora + `</td>
                                <td>` + v.Nombre + `</td>
                                <td>` + datos.predecesora + `</td>
                                <td>` + datos.inicio + `</td>
                                <td>` + datos.fin + `</td>
                                <td>` + datos.lider + `</td>
                                <td>` + datos.tecnicos + `</td>
                                <td>` + v.Nodos + `</td>
                                <td>` + datos.rm + `</td>
                            </tr>`;
                tabla.agregarFilaHtml("#table-tareas", html);
            });
            tabla.reordenarTabla("#table-tareas", [[3, "asc"]]);

            drawGanttChart();

            $('#table-tareas tbody').on('click', 'tr', function () {
                let _thisFila = this;
                var datosTabla = $('#table-tareas').DataTable().row(_thisFila).data();
                var datos = {
                    'id': $.trim($("#IdProyecto").val()),
                    'tarea': datosTabla[0]
                };
                evento.enviarEvento('Planeacion/DetallesTarea', datos, '#panelFormDetallesProyecto', function (respuesta) {
                    if (respuesta.code == 200) {
                        $("#divDetallesTarea").empty().append(respuesta.formulario);
                        tabla.generaTablaPersonal('#table-nodos-tarea-agrupados', null, null, true, false, [[1, 'asc']], null, null, false);
                        tabla.generaTablaPersonal('#table-nodos-tarea', null, null, true, false, [[1, 'asc']], null, null, false);
                        evento.cambiarDiv("#divDetallesProyecto", "#divDetallesTarea", initFormularioNuevaTarea());
                    } else {
                        evento.mostrarMensaje("#errorMessage", false, respuesta.error, 4000);
                    }
                });
            });
        });
    }

    function cargaNodosTareaAgrupados() {
        evento.enviarEvento('Planeacion/CargaNodosTareaAgrupados', {'id': $.trim($("#IdProyecto").val()), 'tarea': $.trim($("#IdTarea").val())}, '#panelFormEditarTarea', function (respuesta) {
            tabla.limpiarTabla("#table-nodos-tarea-agrupados");
            $.each(respuesta, function (k, v) {
                var html = `
                            <tr>
                                <td>` + v.IdConcepto + `</td>
                                <td>` + v.IdArea + `</td>
                                <td>` + v.IdUbicacion + `</td>
                                <td>` + v.IdTipoNodo + `</td>
                                <td>` + v.TipoNodo + `</td>
                                <td>` + v.Nombre + `</td>
                                <td>` + v.Concepto + `</td>
                                <td>` + v.Area + `</td>
                                <td>` + v.Ubicacion + `</td>                                
                                <td>` + v.MaterialesSeleccionados + ` / ` + v.Total + `</td>                                
                            </tr>`;
                tabla.agregarFilaHtml("#table-nodos-tarea-agrupados", html);
            });

            tabla.reordenarTabla("#table-nodos-tarea-agrupados", [[5, "asc"]]);

            $('#table-nodos-tarea-agrupados tbody').off('click', 'tr');
            $('#table-nodos-tarea-agrupados tbody').on('click', 'tr', function () {
                var datosTabla = $('#table-nodos-tarea-agrupados').DataTable().row(this).data();
                var datos = {
                    'IdConcepto': datosTabla[0],
                    'IdArea': datosTabla[1],
                    'IdUbicacion': datosTabla[2],
                    'IdTipoNodo': datosTabla[3],
                    'Nodo': datosTabla[5]
                };
                cargaNodosTarea(datos);
            });
        });
    }

    function cargaNodosTarea() {
        var nodo = arguments[0];
        evento.enviarEvento('Planeacion/CargaNodosTarea', {'id': $.trim($("#IdProyecto").val()), 'tarea': $.trim($("#IdTarea").val()), 'nodo': nodo}, '#panelFormEditarTarea', function (respuesta) {
            evento.ocultarDiv("#divNodosProyectoParaTareas", function () {
                $("#divMaterialesPorNodoParaTareas").fadeIn(400);
            });
            tabla.limpiarTabla("#table-nodos-tarea");
            $.each(respuesta, function (k, v) {
                var checked = (v.Existe > 0) ? 'checked' : '';
                var clase = (v.Existe > 0) ? 'class="text-white f-w-700 bg-green-lighter"' : '';
                var claseCheck = (v.Existe > 0) ? 'text-white bg-green-lighter ' : '';

                var html = `
                            <tr>
                                <td ` + clase + `>` + v.Id + `</td>
                                <td ` + clase + `>` + v.TipoNodo + `</td>
                                <td ` + clase + `>` + v.Nombre + `</td>
                                <td ` + clase + `>` + v.Concepto + `</td>
                                <td ` + clase + `>` + v.Area + `</td>
                                <td ` + clase + `>` + v.Ubicacion + `</td>
                                <td ` + clase + `>` + v.Material + `</td>
                                <td class="` + claseCheck + ` text-center"><input type="checkbox" class="checkNodosTarea" data-id="` + v.Id + `" ` + checked + ` /></td>               
                            </tr>`;
                tabla.agregarFilaHtml("#table-nodos-tarea", html);
            });

            tabla.reordenarTabla("#table-nodos-tarea", [[1, "asc"]]);

            $('#table-nodos-tarea tbody').off('click', 'tr');
            $('#table-nodos-tarea tbody').on('click', 'tr', function () {
                var checkBox = $(this).find(".checkNodosTarea:checkbox");
                checkBox.attr("checked", !checkBox.attr("checked"));
            });

            $("#btnRegresarANodosTarea").off("click");
            $("#btnRegresarANodosTarea").on("click", function () {
                evento.ocultarDiv("#divMaterialesPorNodoParaTareas", function () {
                    $("#divNodosProyectoParaTareas").fadeIn(400);
                });
                tabla.limpiarTabla("#table-nodos-tarea");
                cargaNodosTareaAgrupados();
            });

            $(".btnGuardarNodosTarea").off("click");
            $(".btnGuardarNodosTarea").on("click", function () {
                var nodos = [];
                $(".checkNodosTarea").each(function () {
                    if ($(this).is(":checked")) {
                        nodos.push($(this).attr("data-id"));
                    }
                });

                var datos = {
                    'tarea': $.trim($("#IdTarea").val()),
                    'nodo': nodo,
                    'nodos': nodos
                };

                evento.enviarEvento('Planeacion/GuardarNodosTarea', datos, '#panelFormEditarTarea', function (respuesta) {
                    if (respuesta.code == 200) {
                        cargaNodosTarea(nodo);
                    } else {
                        evento.mostrarMensaje("#divErrorTarea", false, respuesta.error, 4000);
                    }
                });
            });
        });
    }

    function cargaMaterialTarea() {
        var _idProyecto = $.trim($("#IdProyecto").val());
        var _idTarea = $("#IdTarea").val();
        evento.enviarEvento('Planeacion/FormularioMaterialesTarea', {'id': _idProyecto, 'idTarea': _idTarea}, '#panelFormEditarTarea', function (respuesta) {
            $("#materialTarea").empty().append(respuesta.formulario);
            select.crearSelect("#listMateriales");
            tabla.generaTablaPersonal('#table-material-tarea', null, null, true, false, [[1, 'asc']], null, null, false);

            $('input[type=radio][name=radioMaterialKit]').change(function () {
                if (this.value == '1') {
                    evento.ocultarDiv('div#agregarKit', evento.mostraDiv("div#agregarMaterial"));
//                            $('#formNodoUbicacion').parsley().destroy();
                    $("#listKits").removeAttr("data-parsley-required");
                    $("#listMateriales").attr("data-parsley-required", "true");
                    $("#txtCantidad").attr("data-parsley-required", "true");
                    $("#txtCantidad").val('');
                    $("#formNodoUbicacion").parsley().reset();
                } else if (this.value == '2') {
//                            $('#formNodoUbicacion').parsley().destroy();
                    evento.ocultarDiv('div#agregarMaterial', evento.mostraDiv("div#agregarKit"));
                    $("#listKits").attr("data-parsley-required", 'true');
                    $("#listMateriales").removeAttr("data-parsley-required");
                    $("#txtCantidad").removeAttr("data-parsley-required");
                    $("#txtCantidad").val(1);
                    $("#formNodoUbicacion").parsley().reset();
                }
            });

            $("#btnAddMaterialTarea").off("click");
            $("#btnAddMaterialTarea").on("click", function () {
                if (evento.validarFormulario("#formNodoUbicacion")) {
                    var datos = {
                        'idTarea': _idTarea,
                        'idAccesorio': $("#listMateriales option:selected").attr("data-id-accesorio"),
                        'accesorio': $("#listMateriales option:selected").attr("data-accesorio"),
                        'idMaterial': $("#listMateriales option:selected").attr("data-id-material"),
                        'material': $("#listMateriales option:selected").attr("data-material"),
                        'cantidad': parseFloat($.trim($("#txtCantidad").val()))
                    };

                    var existe = false;
                    $('#table-material-tarea tbody tr').each(function () {
                        var _this = this;
                        var datosTabla = $("#table-material-tarea").DataTable().row(_this).data();
                        if (typeof datosTabla !== 'undefined') {
                            if (datosTabla[1] == datos.idAccesorio && datosTabla[2] == datos.idMaterial) {
                                existe = true;
                            }
                        }
                    });

                    if (existe) {
                        evento.mostrarMensaje("#errorMaterialTarea", false, "Ya existe un registro similar al que intenta agregar. Si desea cambiar la cantidad o eliminar el registro de doble clic sobre el registro en la tabla.", 5000);
                    } else {
                        tabla.agregarFila('#table-material-tarea', [
                            '', datos.idAccesorio, datos.idMaterial, datos.accesorio, datos.material, datos.cantidad
                        ]);
                        select.cambiarOpcion("#listMateriales", '');
                        $("#txtCantidad").val('');
                        $("#formNodoUbicacion").parsley().reset();
                        evento.mostrarMensaje("#errorMaterialTarea", true, "Registro agregado. No olvide gardar todos los cambios antes de salir.", 5000);
                    }
                } else {
                    evento.mostrarMensaje("#errorMaterialTarea", false, "Los campos marcados son obligatorios. Por favor revise la información.", 5000);
                }
            });


            $("#btnAgregarKit").off("click");
            $("#btnAgregarKit").on("click", function () {
                if (evento.validarFormulario("#formNodoUbicacion")) {
                    var _idKit = $("#listKits").val();
                    var _registrosExiten = [];
                    $(".divHiddenValues-" + _idKit).each(function () {
                        var _thisMaterialKit = this;
                        var datos = {
                            'idAccesorio': $(_thisMaterialKit).find(".materialKit-idAccesorio").val(),
                            'accesorio': $(_thisMaterialKit).find(".materialKit-accesorio").val(),
                            'idMaterial': $(_thisMaterialKit).find(".materialKit-idMaterial").val(),
                            'material': $(_thisMaterialKit).find(".materialKit-material").val(),
                            'cantidad': $(_thisMaterialKit).find(".materialKit-cantidad").val()
                        };

                        var existe = false;
                        $('#table-material-tarea tbody tr').each(function () {
                            var _this = this;
                            var datosTabla = $("#table-material-tarea").DataTable().row(_this).data();
                            if (typeof datosTabla !== 'undefined') {
                                if (datosTabla[1] == datos.idAccesorio && datosTabla[2] == datos.idMaterial) {
                                    existe = true;
                                }
                            }
                        });

                        if (existe) {
                            _registrosExiten.push('<br />Ya existe un registro similar para: ' + datos.material);
                        } else {
                            tabla.agregarFila('#table-material-tarea', [
                                '', datos.idAccesorio, datos.idMaterial, datos.accesorio, datos.material, datos.cantidad
                            ]);
                        }
                    });

                    if (_registrosExiten.length <= 0) {
                        evento.mostrarMensaje("#errorMaterialTarea", true, "Se ha agregado el kit correctamente.", 5000);
                    } else {
                        var htmlError = '';
                        $.each(_registrosExiten, function (k, v) {
                            htmlError += v;
                        });
                        htmlError += '<br />Si desea cambiar la cantidad o eliminar el registro de doble clic sobre el registro en la tabla.';
                        evento.mostrarMensaje("#errorMaterialTarea", false, htmlError, 10000);
                    }

                    select.cambiarOpcion("#listKits", '');
                    $("#formNodoUbicacion").parsley().reset();

                } else {
                    evento.mostrarMensaje("#errorMaterialTarea", false, "Los campos marcados son obligatorios. Por favor revise la información.", 5000);
                }
            });




            $('#table-material-tarea tbody').on('click', 'tr', function () {
                let _thisFila = this;
                var datosTabla = $('#table-material-tarea').DataTable().row(_thisFila).data();
                var datos = {
                    'id': _idProyecto,
                    'idTarea': _idTarea,
                    'accesorio': datosTabla[1],
                    'material': datosTabla[2],
                    'cantidad': datosTabla[5]
                };
                evento.enviarEvento('Planeacion/FormularioEditarMaterialTarea', datos, '#panelFormEditarTarea', function (respuesta) {
                    if (respuesta.code == 200) {
                        evento.iniciarModal('#modalEdit', 'Editar Material de Tarea', respuesta.formulario);

                        $("#listMaterialesEdit").combobox();

                        $("#btnEliminarMaterialTarea").off("click");
                        $("#btnEliminarMaterialTarea").on("click", function () {
                            var datosTabla = $('#table-material-tarea').DataTable().row(_thisFila).data();
                            if (datosTabla[0] !== "") {
                                evento.enviarEvento('Planeacion/EliminarMaterialTarea', {'id': datosTabla[0]}, '#panelFormEditarTarea', function (respuesta) {
                                    if (respuesta.code == 200) {
                                        tabla.eliminarFila('#table-material-tarea', _thisFila);
                                        evento.terminarModal('#modalEdit');
                                    } else {
                                        evento.mostrarMensaje("#error-in-modal", false, "No se ha podido eliminar el material: " + respuesta.error, 4000);
                                    }
                                });
                            } else {
                                tabla.eliminarFila('#table-material-tarea', _thisFila);
                                evento.terminarModal('#modalEdit');
                            }
                        });

                        $("#btnGuardarCambiosModal").off("click");
                        $("#btnGuardarCambiosModal").on("click", function () {
                            var datos = {
                                'aux': $("#listMaterialesEdit").val(),
                                'idAccesorio': $("#listMaterialesEdit option:selected").attr("data-id-accesorio"),
                                'accesorio': $("#listMaterialesEdit option:selected").attr("data-accesorio"),
                                'idMaterial': $("#listMaterialesEdit option:selected").attr("data-id-material"),
                                'material': $("#listMaterialesEdit option:selected").attr("data-material"),
                                'cantidad': parseFloat($.trim($("#txtCantidadEdit").val()))
                            };
                            if (datos.aux !== '' && datos.cantidad > 0) {
                                var existe = false;
                                $('#table-material-tarea tbody tr').each(function () {
                                    var _this = this;
                                    if (_this == _thisFila) {
                                        return true;
                                    } else {
                                        var datosTabla = $("#table-material-tarea").DataTable().row(_this).data();
                                        if (typeof datosTabla !== 'undefined') {
                                            if (datosTabla[1] == datos.idAccesorio && datosTabla[2] == datos.idMaterial) {
                                                existe = true;
                                            }
                                        }
                                    }
                                });

                                if (existe) {
                                    evento.mostrarMensaje("#error-in-modal", false, "Ya existe un registro similar al que intenta agregar.", 4000);
                                } else {
                                    evento.terminarModal('#modalEdit');
                                    $('#table-material-tarea').DataTable().row(_thisFila).data([
                                        datosTabla[0], datos.idAccesorio, datos.idMaterial, datos.accesorio, datos.material, datos.cantidad
                                    ]);
                                    tabla.reordenarTabla('#table-material-tarea', [3, 'asc']);
                                    evento.mostrarMensaje("#errorMaterialTarea", true, "Registro actualizado. No olvide gardar todos los cambios antes de salir.", 5000);
                                }
                            } else {
                                evento.mostrarMensaje("#error-in-modal", false, "Todos los campos marcados son obligatorios. Revise su información", 4000);
                            }

                        });

                    } else {
                        evento.mostrarMensaje("#errorMaterialTarea", false, respuesta.error, 4000);
                    }
                });
            });

            $("#btnGuardarMaterialTarea").off("click");
            $("#btnGuardarMaterialTarea").on("click", function () {
                var datos = {
                    'idTarea': _idTarea,
                    'material': []
                }

                $('#table-material-tarea tbody tr').each(function () {
                    var _this = this;
                    var datosTabla = $("#table-material-tarea").DataTable().row(_this).data();
                    if (typeof datosTabla !== 'undefined') {
                        datos.material.push({
                            'id': datosTabla[0],
                            'accesorio': datosTabla[1],
                            'material': datosTabla[2],
                            'cantidad': datosTabla[5]
                        });
                    }
                });

                if (datos.material.length <= 0) {
                    evento.mostrarMensaje("#errorMaterialTarea", false, "La tabla de nodos debe contener al menos un registro para ser guardada", 5000);
                } else {
                    evento.enviarEvento('Planeacion/GuardarMaterialTarea', datos, '#panelFormEditarTarea', function (respuesta) {
                        if (respuesta.code == 200) {
                            cargaMaterialTarea();
                        } else {
                            evento.mostrarMensaje("#errorMaterialTarea", false, "Ocurrió un error: " + respuesta.error, 5000);
                        }
                    });
                }

            });

            $("#listKits").on("change", function () {
                var _thisKit = $(this).val();
                evento.ocultarDiv(".divMaterialKit");
                if (_thisKit !== '') {
                    evento.mostraDiv("#divMaterialKit-" + _thisKit);
                }
            });

        });
    }

});



