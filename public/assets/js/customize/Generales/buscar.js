$(function () {

    //Objetos
    var evento = new Base();
    var websocket = new Socket();
    var tabla = new Tabla();
    var select = new Select();
    var fecha = new Fecha();
    var catalogos;
    var filtrosAvanzados = [];

    //Evento que maneja las peticiones del socket
    websocket.socketMensaje();

    //Muestra la hora en el sistema
    evento.horaServidor($('#horaServidor').val());

    //Evento para cerra la session
    evento.cerrarSesion();

    //Inicializa funciones de la plantilla
    App.init();

    select.crearSelectMultiple("#listaColumnas", "Seleccionar");

    $("#checkboxColumnasDisponibles").click(function () {
        select.seleccionarTodos(this, $('#listaColumnas'));
    });

    $('input[name=radioFiltroFecha]').change(function () {
        var tipoFiltroFecha = $(this).val();
        if (tipoFiltroFecha == 'rango') {
            $('#desde').data("DateTimePicker").enable().clear();
            $('#hasta').data("DateTimePicker").enable().clear();
            $("#selectFiltroDurante").prop("disabled", true).val("").trigger("change");
        } else {
            $('#desde').data("DateTimePicker").disable().clear();
            $('#hasta').data("DateTimePicker").disable().clear();
            $("#selectFiltroDurante").prop("disabled", false).val("").trigger("change");
        }
    });

    $("#btnAgregarFiltro").on("click", function () {
        var campo = $("#selectCamposFiltros").val();
        var campoTexto = $("#selectCamposFiltros option:selected").text();
        if (campo == "") {
            evento.mostrarMensaje('.errorFiltroAvanzado', false, 'Debe seleccionar un campo de filtro', 3000);
        } else {
            var tipoCampo = $("#selectCamposFiltros option:selected").attr("data-tipo");
            var criterio = $("#operadorComparacion").val();
            var textoCriterio = $("#operadorComparacion option:selected").text();
            var valor;
            var valorMostrar = '';
            switch (tipoCampo) {
                case 'cat' :
                    valor = $("#selectValor").val();
                    if (valor !== null) {
                        $("#selectValor option:selected").each(function () {
                            var $this = $(this);
                            if ($this.length) {
                                var selText = $this.text();
                                valorMostrar += '"' + selText + '", ';
                            }
                        });
                    } else {
                        evento.mostrarMensaje('.errorFiltroAvanzado', false, 'Selecciona al menos un valor de la lista.', 3000);
                        return false;
                    }
                    break;
                case 'tag' :
                    valor = $("#tagValor").tagit("assignedTags");
                    if (valor.length > 0) {
                        valorMostrar = valor.toString();
                    } else {
                        evento.mostrarMensaje('.errorFiltroAvanzado', false, 'Debes definir al menos un valor.', 3000);
                        return false;
                    }
                    break;
                case 'text':
                    valor = $("#inputValor").val();
                    if (valor !== '') {
                        valorMostrar = '"' + valor + '"';
                    } else {
                        evento.mostrarMensaje('.errorFiltroAvanzado', false, 'Escribe el valor que se va a comparar.', 3000);
                        return false;
                    }
                    break;
                default :
                    break;
            }

            filtrosAvanzados.push({
                'tipoCampo': tipoCampo,
                'campo': campo,
                'textoCampo': campoTexto,
                'criterio': criterio,
                'textoCriterio': textoCriterio,
                'valor': valor,
                'valorMostrar': valorMostrar
            });

            $("#selectCamposFiltros").val("").trigger("change");

        }
    });

    $("#btnBuscar").on("click", function () {
        var data = {
            'columnas': $("#listaColumnas").val(),
            'filtroFecha': $("#selectFiltroFechas").val(),
            'tipoFiltroFecha': $('input[name=radioFiltroFecha]:checked').val(),
            'desde': $("#txtDesde").val(),
            'hasta': $("#txtHasta").val(),
            'durante': $("#selectFiltroDurante").val(),
            'avanzados': filtrosAvanzados
        }

        $("#seccion-buscar").addClass("hidden");
        $("#seccion-reporte").removeClass("hidden");
        $("#divResultadoBusqueda").empty();
        evento.enviarEvento('Buscar/Reporte', data, '#seccion-reporte', function (respuesta) {
            $("#divResultadoBusqueda").append(respuesta.tabla);
            tabla.generaTablaPersonal('#data-table-busqueda-reporte', null, null, true, true, [[0, 'desc']]);
            $('#data-table-busqueda-reporte tbody').on('click', 'tr', function () {
                var datos = $('#data-table-busqueda-reporte').DataTable().row(this).data();
                getDetalles(datos);
            });
        });
    });

    $("#btnRegresarReporte").on("click", function () {
        $("#seccion-reporte").addClass("hidden");
        $("#seccion-buscar").removeClass("hidden");
    });

    $("#btnRegresarDetalles").on("click", function () {
        $("#seccion-detalles").addClass("hidden");
        $("#seccion-reporte").removeClass("hidden");
    });

    $("#btnExportarExcel").on("click", function () {
        var info = $('#data-table-busqueda-reporte').DataTable().rows({search: 'applied'}).data();
        var realInfo = new Array();
        $.each(info, function (k, v) {
            if (!isNaN(k)) {
                realInfo.push(v);
            }
        });
        var data = {
            'columnas': $("#listaColumnas").val(),
            'info': realInfo
        };

        evento.enviarEvento('Buscar/Excel', data, '#seccion-reporte', function (respuesta) {
            window.open(respuesta.ruta, '_blank');
        });
    });

    var initFiltroAvanzado = function () {
        $("#selectCamposFiltros").on("change", function () {
            var tipoCampo = $("#selectCamposFiltros option:selected").attr("data-tipo");
            var campo = $("#selectCamposFiltros").val();
            var htmlCriterio = '<select id="operadorComparacion" class="form-control" style="width: 100%">';
            $("#valorFiltro").empty();
            $("#operadorComparacion > option").prop('disabled="disabled"');
            switch (tipoCampo) {
                case 'cat' :
                    htmlCriterio += '<option value="es">es</option><option value="noes">no es</option>';
                    var htmlSelect = '<select class="form-control" style="width: 100%" id="selectValor" multiple="multiple">';
                    switch (campo) {
                        case 'ts.IdEstatus':
                        case 'tst.IdEstatus':
                            $.each(catalogos.status, function (k, v) {
                                htmlSelect += '<option value="' + v.Id + '">' + v.Nombre + '</option>';
                            });
                            break;
                        case 'ts.IdDepartamento':
                            $.each(catalogos.departamentos, function (k, v) {
                                htmlSelect += '<option value="' + v.Id + '">' + v.Departamento + '</option>';
                            });
                            break;
                        case 'ts.IdPrioridad':
                            $.each(catalogos.prioridades, function (k, v) {
                                htmlSelect += '<option value="' + v.Id + '">' + v.Nombre + '</option>';
                            });
                            break;
                        case 'ts.Solicita':
                        case 'tst.Solicita':
                        case 'tst.Atiende':
                            $.each(catalogos.personal, function (k, v) {
                                htmlSelect += '<option value="' + v.Id + '">' + v.Nombre + '</option>';
                            });
                            break;
                        case 'tst.IdTipoServicio':
                            $.each(catalogos.tiposServicio, function (k, v) {
                                htmlSelect += '<option value="' + v.Id + '">' + v.Nombre + '</option>';
                            });
                            break;
                        case 'tst.IdSucursal':
                            $.each(catalogos.sucursales, function (k, v) {
                                htmlSelect += '<option value="' + v.Id + '">' + v.Nombre + '</option>';
                            });
                            break;
                        case 'cs.IdRegionCliente':
                            $.each(catalogos.regiones, function (k, v) {
                                htmlSelect += '<option value="' + v.Id + '">' + v.Nombre + '</option>';
                            });
                            break;
                    }
                    $("#valorFiltro").empty().append(htmlSelect);
                    select.crearSelectMultiple("#selectValor", "Seleccionar");
                    break;
                case 'tag' :
                    htmlCriterio += '<option value="es">es</option><option value="noes">no es</option>';
                    var htmlTag = '<ul id="tagValor"></ul>';
                    $("#valorFiltro").empty().append(htmlTag);
                    $("#tagValor").tagit({
                        allowSpaces: false
                    });
                    break;
                case 'text':
                    htmlCriterio += '<option value="contiene">contiene</option>';
                    var htmlInput = '<input type="text" id="inputValor" class="form-control" placeholder="Ingresa texto"/>';
                    $("#valorFiltro").empty().append(htmlInput);
                    break;
                default :
                    break;
            }
            htmlCriterio += '</select>';
            $("#valorCriterio").empty().append(htmlCriterio);
            pintaTablaFiltros();
        });
    };

    var getCatalogos = function () {
        evento.enviarEvento('Buscar/Catalogos', [], '#seccion-buscar', function (respuesta) {
            catalogos = respuesta;
        });
    };

    var pintaTablaFiltros = function () {
        var htmlFiltros = '';
        $.each(filtrosAvanzados, function (k, v) {
            htmlFiltros += '\
            <tr>\
                <td class="text-center"><a href="javascript:;" class="text-danger borra-filtro-avanzado" data-position="' + k + '"><i class="fa fa-2x fa-trash text-danger"></i></a></td>\
                <td><strong>' + v.textoCampo + '</strong> ' + v.textoCriterio + ' <strong>' + v.valorMostrar + '</strong></td>\
            </tr>';
        });
        $("#tableFiltrosAvanzados tbody").empty().append(htmlFiltros);

        if (htmlFiltros == '') {
            $("#tableFiltrosAvanzados").addClass("hidden");
        } else {
            $("#tableFiltrosAvanzados").removeClass("hidden");
        }

        $(".borra-filtro-avanzado").off("click");
        $(".borra-filtro-avanzado").on("click", function () {
            var position = $(this).attr("data-position");
            filtrosAvanzados.splice(position, 1);
            pintaTablaFiltros();
        });
    };

    var getDetalles = function (datos) {
        $("#seccion-reporte").addClass("hidden");
        $("#seccion-detalles").removeClass("hidden");
        evento.enviarEvento('Buscar/Detalles', {datos: datos}, '#seccion-detalles', function (respuesta) {

            $("#panel-detalles-solicitud").empty().append(respuesta.solicitud);
            $("#panel-detalles-servicio").empty().append(respuesta.servicio);
            $("#panel-historial-servicio").empty().append(respuesta.historial);
            $("#panel-conversacion-servicio").empty().append(respuesta.conversacion);
            $("#btnExportarPdf").attr("data-id-servicio", datos[1]);
            $("#btnRechazarServicioConcluido").attr("data-id-servicio", datos[1]);
            $("#btnSubirInfoSD").attr("data-id-servicio", datos[1]);

            $("#btnSubirInfoSD").off("click");
            $("#btnSubirInfoSD").on("click", function () {
                var data = {
                    'servicio': $(this).attr("data-id-servicio")
                }
                evento.enviarEvento('/Generales/ServiceDesk/ValidarServicio', data, '#seccion-detalles', function (respuesta) {
                    if (respuesta === true) {
                        var html = `<p class="f-s-20 text-center">Su información fué agregada a ServiceDesk.</p>`;
                        evento.mostrarModal("Informcación SD", html);
                        $('#btnModalConfirmar').addClass('hidden');
                        $('#btnModalAbortar').empty().append('Cerrar');
                    } else {
                        var html = `<p class="f-s-20">Ocurrió un error al subir la información. Intente de nuevo o contacte al administrador.</p>
                                    <p class="f-s-20">(` + respuesta + `)</p>`;
                        evento.mostrarModal("ERROR SD", html);
                        $('#btnModalConfirmar').addClass('hidden');
                        $('#btnModalAbortar').empty().append('Cerrar');

                    }
                });
            });


            $("#btnExportarPdf").off("click");
            $("#btnExportarPdf").on("click", function () {
                var data = {
                    'servicio': $(this).attr("data-id-servicio")
                }
                evento.enviarEvento('Servicio/Servicio_ToPdf', data, '#seccion-detalles', function (respuesta) {
                    window.open('/' + respuesta.link);
                });
            });

            tabla.generaTablaPersonal('.table-datatable', null, null, true, true, [[0, 'desc']]);

            $("#btnGenerarPDFProblemasEquipo").on("click", function () {
                var data = {
                    'servicio': datos[1]
                }
                evento.enviarEvento('Servicio/Servicio_ToPdf_ProblemasEquipo', data, '#seccion-detalles', function (respuesta) {
                    window.open('/' + respuesta.link);
                });
            });

            $("#btnGenerarPDFEquipoFaltante").on("click", function () {
                var data = {
                    'servicio': datos[1]
                }
                evento.enviarEvento('Servicio/Servicio_ToPdf_EquipoFaltante', data, '#seccion-detalles', function (respuesta) {
                    window.open('/' + respuesta.link);
                });
            });

            $("#btnGenerarPDFOtrosProblemas").on("click", function () {
                var data = {
                    'servicio': datos[1]
                }
                evento.enviarEvento('Servicio/Servicio_ToPdf_OtrosProblemas', data, '#seccion-detalles', function (respuesta) {
                    window.open('/' + respuesta.link);
                });
            });

            if (datos[16] === "CONCLUIDO") {
                $("#btnRechazarServicioConcluido").removeClass("hidden");

                $("#btnRechazarServicioConcluido").off("click");
                $("#btnRechazarServicioConcluido").on("click", function () {
                    var modalMensaje = evento.mensajeValidar("¿Realmente quiere Reabrir el Servicio?");
                    evento.mostrarModal('"Advertencia"', modalMensaje);

                    $('#btnAceptarConfirmacion').on('click', function () {
                        evento.mostrarModal('"Rechazar Servicio"', modalRechazarServicio());
                        $('#btnModalConfirmar').off('click');
                        $('#btnGuardarDescripionServicio').on('click', function () {
                            if (evento.validarFormulario('#formRechazarFormulario')) {
                                var descripcion = $('#inputDescripcionRecharzarServicio').val();
                                var data = {'servicio': datos[1], idSolicitud: datos[0], descripcion: descripcion, ticket: datos[2]};
                                $('#btnGuardarDescripionServicio').attr('disabled', 'disabled');
                                $('#btnCancelarRechazarServicio').attr('disabled', 'disabled');
                                evento.enviarEvento('Servicio/Reabrir_Servicio', data, '#seccionRechazarServicio', function (respuesta) {
                                    if (respuesta) {
                                        evento.mensajeConfirmacion('Servicio Reabierto con Exito', 'Correcto', true);
                                    } else {
                                        $('#btnModalAbortar').removeClass('hidden');
                                        evento.mensajeConfirmacion('No tiene el permiso para reabrir el servicio.', 'Advertencia');
                                    }
                                });
                            }
                        });

                        $('#btnCancelarRechazarServicio').on('click', function () {
                            evento.cerrarModal();
                        });
                    });
                    $('#btnCancelarConfirmacion').on('click', function () {
                        evento.cerrarModal();
                    });
                });
            }
        });
    };

    var modalRechazarServicio = function () {
        var html = '<div id="seccionRechazarServicio" > ';
        html += '       <div class="row">';
        html += '           <form class="margin-bottom-0" id="formRechazarFormulario" data-parsley-validate="true" >';
        html += '               <div class="col-md-12">';
        html += '                   <div class="form-group">';
        html += '                       <label for="rechazarServicio">Descripción del Rechazo *</label> ';
        html += '                       <input type="text" class="form-control" id="inputDescripcionRecharzarServicio" placeholder="Descripción del por que esta rechazando el servicio" data-parsley-required="true"/> ';
        html += '                   </div>';
        html += '               </div>';
        html += '               <div class="col-md-12">';
        html += '                   <div class="errorRechazarServicio"></div>';
        html += '               </div>';
        html += '               <div class="row m-t-20">';
        html += '                   <div class="col-md-12 text-center">';
        html += '                       <button id="btnGuardarDescripionServicio" type="button" class="btn btn-sm btn-primary"><i class="fa fa-save"></i> Aceptar</button>';
        html += '                       <button id="btnCancelarRechazarServicio" type="button" class="btn btn-sm btn-danger"><i class="fa fa-times"></i> Cancelar</button>';
        html += '                   </div>';
        html += '               </div>';
        html += '           </form>'
        html += '       </div>';
        html += '</div>';
        html += '';

        return html;
    };

    /*Iniciadores*/
    fecha.rangoFechas('#desde', '#hasta');
    initFiltroAvanzado();
    getCatalogos();
});
