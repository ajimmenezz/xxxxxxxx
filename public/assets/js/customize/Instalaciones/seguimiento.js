$(function () {
    //Objetos
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

    tabla.generaTablaPersonal('#table-servicios', null, null, true, true, [[1, 'asc']]);

    $('#table-servicios tbody').on('click', 'tr', function () {
        let _this = this;
        var datosTabla = $('#table-servicios').DataTable().row(_this).data();
        var datos = {
            'id': datosTabla[0]
        };

        evento.enviarEvento('Seguimiento/SeguimientoInstalacion', datos, '#panelListaInstalaciones', function (respuesta) {
            if (respuesta.code == 200) {
                $("#seccionFormulario").empty().append(respuesta.formulario);
                evento.cambiarDiv("#seccionPendientes", "#seccionFormulario");
                initSeguimiento(datos, respuesta.tipoInstalacion);
            } else {
                evento.mostrarMensaje("#errorMessage", false, respuesta.error, 4000);
            }
        });
    });

    function initSeguimiento(datos, tipoInstalacion) {
        $("#btnIniciarServicio").off("click");
        $("#btnIniciarServicio").on("click", function () {
            evento.enviarEvento('Seguimiento/IniciarInstalacion', datos, '#panelSeguimiento', function (respuesta) {
                if (respuesta.code == 200) {
                    $("#seccionFormulario").empty().append(respuesta.formulario);
                    evento.cambiarDiv("#seccionPendientes", "#seccionFormulario");
                    initSeguimiento(datos);
                } else {
                    evento.mostrarMensaje("#errorMessageSeguimiento", false, respuesta.error, 4000);
                }
            });
        });

        select.crearSelect("select");
        $("#listClientes").on("change", function () {
            evento.enviarEvento('Seguimiento/SucursalesXCliente', { 'id': $(this).val() }, '#panelSeguimiento', function (respuesta) {
                if (respuesta.code == 200) {
                    select.destruirSelect("#listSucursales");
                    $("#listSucursales").empty().append('<option value="">Selecciona . . .</option>');
                    $.each(respuesta.result, function (k, v) {
                        $("#listSucursales").append('<option value="' + v.Id + '">' + v.Nombre + '</option>');
                    });
                    select.crearSelect("#listSucursales");

                } else {
                    evento.mostrarMensaje("#errorMessageSeguimiento", false, respuesta.message, 4000);
                }
            });
        });

        $("#btnGuardarSucursal").off("click");
        $("#btnGuardarSucursal").on("click", function () {
            var data = datos;
            data.sucursal = $("#listSucursales").val();

            if (data.sucursal == '') {
                evento.mostrarMensaje("#errorMessageSeguimiento", false, "Debe seleccionar una sucursal para guardar los cambios.", 4000);
                return false;
            } else {
                evento.enviarEvento('Seguimiento/GuardarSucursalServicio', data, '#panelSeguimiento', function (respuesta) {
                    if (respuesta.code == 200) {
                        evento.mostrarMensaje("#errorMessageSeguimiento", true, respuesta.message, 4000);
                    } else {
                        evento.mostrarMensaje("#errorMessageSeguimiento", false, respuesta.message, 4000);
                    }
                });
            }
        });

        $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
            var target = $(e.target).attr("href");
            switch (target) {
                case "#EvidenciasInstalacion":
                    cargaEvidenciasInstalacion(datos);
                    break;
                case "#EvidenciasRetiro":
                    cargaEvidenciasRetiro(datos);
                    break;
            }
        });

        switch (tipoInstalacion) {
            case 45: case '45':
                initInstalacionLexmark(datos);
                break;
        }

    }

    function cargaEvidenciasInstalacion(datos) {
        file.crearUpload('#archivosInstalacion', 'Seguimiento/SubirArchivoInstalacion');
        file.habilitar("#archivosInstalacion");

        evento.enviarEvento('Seguimiento/EvidenciasInstalacion', datos, '#panelSeguimiento', function (respuesta) {
            if (respuesta.code == 200) {
                select.destruirSelect("#listTiposEvidenciaInstalacion");
                $("#listTiposEvidenciaInstalacion").empty().append('<option value="">Selecciona...</option>');

                $.each(respuesta.evidenciasInstalacion, function (k, v) {
                    $("#listTiposEvidenciaInstalacion").append('<option value="' + v.Id + '">' + v.Nombre + '</option>')
                });

                select.crearSelect("#listTiposEvidenciaInstalacion");

                $("#divEvidenciasInstalacion").empty();

                $.each(respuesta.infoEvidenciasInstalacion, function (k, v) {
                    $("#divEvidenciasInstalacion").append(`
                    <div class="col-md-3 text-center p-10">
                        <div class="image-inner">
                            <a href="`+ v.Archivo + `" data-lightbox="gallery-group-instalacion">
                                <img style="height:150px !important; max-height:150px !important;" class="img-thumbnail" src="`+ v.Archivo + `" alt="` + v.Evidencia + `">
                            </a>
                            <a data-id="`+ v.Id + `" class="btn btn-block btn-danger btn-xs f-w-600 btnEliminarEvidenciaInstalacion">Eliminar Archivo</a>
                            <p class="image-caption f-w-600 f-s-14">`+ v.Evidencia + `</p>
                        </div>
                    </div>
                    `);
                });

                $(".btnEliminarEvidenciaInstalacion").off("click");
                $(".btnEliminarEvidenciaInstalacion").on("click", function () {
                    var id = $(this).attr("data-id");
                    evento.enviarEvento('Seguimiento/EliminarEvidenciaInstalacion', { 'id': id }, '#panelSeguimiento', function (respuesta) {
                        if (respuesta.code == 200) {
                            cargaEvidenciasInstalacion(datos);
                        } else {
                            evento.mostrarMensaje("#errorMessageSeguimiento", false, respuesta.message, 4000);
                        }
                    });
                });

                $("#btnSubirEvidenciaInstalacion").off("click");
                $("#btnSubirEvidenciaInstalacion").on("click", function () {
                    subirEvidenciasInstalacion(datos);
                });

            } else {
                evento.mostrarMensaje("#errorMessageSeguimiento", false, respuesta.message, 4000);
            }
        });
    }

    function cargaEvidenciasRetiro(datos) {
        file.crearUpload('#archivosRetiro', 'Seguimiento/SubirArchivoRetiro');
        file.habilitar("#archivosRetiro");

        evento.enviarEvento('Seguimiento/EvidenciasRetiro', datos, '#panelSeguimiento', function (respuesta) {
            if (respuesta.code == 200) {
                select.destruirSelect("#listTiposEvidenciaRetiro");
                $("#listTiposEvidenciaRetiro").empty().append('<option value="">Selecciona...</option>');

                $.each(respuesta.evidenciasRetiro, function (k, v) {
                    $("#listTiposEvidenciaRetiro").append('<option value="' + v.Id + '">' + v.Nombre + '</option>')
                });

                select.crearSelect("#listTiposEvidenciaRetiro");

                $("#divEvidenciasRetiro").empty();

                $.each(respuesta.infoEvidenciasRetiro, function (k, v) {
                    $("#divEvidenciasRetiro").append(`
                    <div class="col-md-3 text-center p-10">
                        <div class="image-inner">
                            <a href="`+ v.Archivo + `" data-lightbox="gallery-group-retiro">
                                <img style="height:150px !important; max-height:150px !important;" class="img-thumbnail" src="`+ v.Archivo + `" alt="` + v.Evidencia + `">
                            </a>
                            <a data-id="`+ v.Id + `" class="btn btn-block btn-danger btn-xs f-w-600 btnEliminarEvidenciaRetiro">Eliminar Archivo</a>
                            <p class="image-caption f-w-600 f-s-14">`+ v.Evidencia + `</p>
                        </div>
                    </div>
                    `);
                });

                $(".btnEliminarEvidenciaRetiro").off("click");
                $(".btnEliminarEvidenciaRetiro").on("click", function () {
                    var id = $(this).attr("data-id");
                    evento.enviarEvento('Seguimiento/EliminarEvidenciaRetiro', { 'id': id }, '#panelSeguimiento', function (respuesta) {
                        if (respuesta.code == 200) {
                            cargaEvidenciasRetiro(datos);
                        } else {
                            evento.mostrarMensaje("#errorMessageSeguimiento", false, respuesta.message, 4000);
                        }
                    });
                });

                $("#btnSubirEvidenciaRetiro").off("click");
                $("#btnSubirEvidenciaRetiro").on("click", function () {
                    subirEvidenciasRetiro(datos);
                });

            } else {
                evento.mostrarMensaje("#errorMessageSeguimiento", false, respuesta.message, 4000);
            }
        });
    }

    function subirEvidenciasInstalacion(datos) {
        var data = datos;
        data.evidencia = $("#listTiposEvidenciaInstalacion").val();
        data.archivos = $("#archivosInstalacion").val();

        var error = '';
        if (data.evidencia == "") {
            error = "El Tipo de Evidencia es un campo obligatorio";
        } else if (data.archivos == "") {
            error = "Los archivos adjuntos son un campo obligatorio";
        }

        if (error != '') {
            evento.mostrarMensaje("#errorMessageSeguimiento", false, error, 4000);
        } else {
            file.enviarArchivos('#archivosInstalacion', 'Seguimiento/SubirArchivoInstalacion', '#panelSeguimiento', data, function (respuesta) {
                if (respuesta.code == 200) {
                    evento.mostrarMensaje("#errorMessageSeguimiento", true, respuesta.message, 4000);
                    file.limpiar("#archivosInstalacion");
                    file.destruir("#archivosInstalacion");
                    $("#archivosInstalacion").val('');
                    cargaEvidenciasInstalacion(datos);
                } else {
                    evento.mostrarMensaje("#errorMessageSeguimiento", false, "Ocurrió el siguiente error al guardar el archivo. " + respuesta.message, 4000);
                }
            });
        }
    }

    function subirEvidenciasRetiro(datos) {
        var data = datos;
        data.evidencia = $("#listTiposEvidenciaRetiro").val();
        data.archivos = $("#archivosRetiro").val();

        var error = '';
        if (data.evidencia == "") {
            error = "El Tipo de Evidencia es un campo obligatorio";
        } else if (data.archivos == "") {
            error = "Los archivos adjuntos son un campo obligatorio";
        }

        if (error != '') {
            evento.mostrarMensaje("#errorMessageSeguimiento", false, error, 4000);
        } else {
            file.enviarArchivos('#archivosRetiro', 'Seguimiento/SubirArchivoRetiro', '#panelSeguimiento', data, function (respuesta) {
                if (respuesta.code == 200) {
                    evento.mostrarMensaje("#errorMessageSeguimiento", true, respuesta.message, 4000);
                    file.limpiar("#archivosRetiro");
                    file.destruir("#archivosRetiro");
                    $("#archivosRetiro").val('');
                    cargaEvidenciasRetiro(datos);
                } else {
                    evento.mostrarMensaje("#errorMessageSeguimiento", false, "Ocurrió el siguiente error al guardar el archivo. " + respuesta.message, 4000);
                }
            });
        }
    }

    function initInstalacionLexmark(datos) {
        $.mask.definitions['h'] = "[A-Fa-f0-9]";
        $("#txtMACImpresora").mask("hh:hh:hh:hh:hh:hh");
        $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
            var target = $(e.target).attr("href");
            switch (target) {
                case "#Instalados":
                    cargaInstaladosLexmark(datos);
                    break;
                case "#Retirados":
                    cargaRetiradosLexmark(datos);
                    break;
            }
        });
    }

    function cargaInstaladosLexmark(datos) {
        evento.enviarEvento('Seguimiento/InstaladosLexmark', datos, '#panelSeguimiento', function (respuesta) {
            if (respuesta.code == 200) {
                select.destruirSelect("#listUbicacionesImpresora");
                select.destruirSelect("#listUbicacionesSupresor");
                $("#listUbicacionesImpresora, #listUbicacionesSupresor").empty().append('<option data-area="" data-punto="">Selecciona...</option>');
                var selectedUbicacionImp = '';
                var selectedUbicacionSup = '';
                $.each(respuesta.ubicaciones.result, function (k, v) {
                    for (var i = 1; i <= v.Puntos; i++) {
                        selectedUbicacionImp = '';
                        selectedUbicacionSup = '';
                        if (v.IdArea == respuesta.instalados.impresora.IdArea && i == respuesta.instalados.impresora.Punto) {
                            selectedUbicacionImp = ' selected = "selected" ';
                        }

                        $("#listUbicacionesImpresora").append('<option data-area="' + v.IdArea + '" data-punto="' + i + '" ' + selectedUbicacionImp + '>' + v.Area + ' ' + i + '</option>')

                        if (v.IdArea == respuesta.instalados.supresor.IdArea && i == respuesta.instalados.supresor.Punto) {
                            selectedUbicacionSup = ' selected = "selected" ';
                        }

                        $("#listUbicacionesSupresor").append('<option data-area="' + v.IdArea + '" data-punto="' + i + '" ' + selectedUbicacionSup + '>' + v.Area + ' ' + i + '</option>')
                    }
                });
                select.crearSelect("#listUbicacionesImpresora");
                select.crearSelect("#listUbicacionesSupresor");

                $("#txtSerieImpresora").val(respuesta.instalados.impresora.Serie);
                $("#txtIPImpresora").val(respuesta.instalados.impresora.IP);
                $("#txtMACImpresora").val(respuesta.instalados.impresora.MAC);
                $("#txtSerieSupresor").val(respuesta.instalados.supresor.Serie);

            } else {
                evento.mostrarMensaje("#errorMessageSeguimiento", false, respuesta.message, 4000);
            }
        });

        $("#btnGuardarInstalados").off("click");
        $("#btnGuardarInstalados").on("click", function () {
            var instalados = {};
            instalados.impresora = {
                'serie': $.trim($("#txtSerieImpresora").val()),
                'area': $("#listUbicacionesImpresora option:selected").attr("data-area"),
                'punto': $("#listUbicacionesImpresora option:selected").attr("data-punto"),
                'ip': $.trim($("#txtIPImpresora").val()),
                'mac': $.trim($("#txtMACImpresora").val())
            };

            instalados.supresor = {
                'serie': $.trim($("#txtSerieSupresor").val()),
                'area': $("#listUbicacionesSupresor option:selected").attr("data-area"),
                'punto': $("#listUbicacionesSupresor option:selected").attr("data-punto")
            };

            var ipFormat = new RegExp(/^(([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])\.){3}([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])$/);
            var macFormat = new RegExp(/^([0-9a-fA-F][0-9a-fA-F]:){5}([0-9a-fA-F][0-9a-fA-F])$/);

            var error = '';
            if (instalados.impresora.serie == "") {
                error = 'El número de serie de la impresora es un campo obligatorio'
            } else if (instalados.impresora.area == "") {
                error = 'La ubicación de la impresora es un campo obligatorio'
            } else if (instalados.impresora.ip == "" || !ipFormat.test(instalados.impresora.ip)) {
                error = 'La IP de la impresora no tiene el formato necesario'
            } else if (instalados.impresora.mac == "" || !macFormat.test(instalados.impresora.mac)) {
                error = 'La MAC de la impresora no tiene el formato necesario'
            } else if (instalados.supresor.serie == "") {
                error = 'El número de serie del supresor es un campo obligatorio'
            } else if (instalados.supresor.area == "") {
                error = 'La ubicación del supresor es un campo obligatorio'
            }

            if (error != '') {
                evento.mostrarMensaje("#errorMessageSeguimiento", false, error, 4000);
                return false;
            } else {
                var data = {
                    servicio: datos.id,
                    instalados: instalados
                }
                evento.enviarEvento('Seguimiento/GuardarInstaladosLexmark', data, '#panelSeguimiento', function (respuesta) {
                    if (respuesta.code == 200) {
                        evento.mostrarMensaje("#errorMessageSeguimiento", true, respuesta.message, 4000);
                    } else {
                        evento.mostrarMensaje("#errorMessageSeguimiento", false, respuesta.message, 4000);
                    }
                });
            }
        });
    }

    function cargaRetiradosLexmark(datos) {
        evento.enviarEvento('Seguimiento/RetiradosLexmark', datos, '#panelSeguimiento', function (respuesta) {
            if (respuesta.code == 200) {
                select.destruirSelect("#listImpresorasEnComplejo");
                select.destruirSelect("#listImpresorasRetiro");
                select.destruirSelect("#listEstatusImpresoraRetirada");

                $("#listImpresorasEnComplejo, #listImpresorasRetiro, #listEstatusImpresoraRetirada").empty().append('<option data-area="" data-punto="">Selecciona...</option>');

                $.each(respuesta.censadas, function (k, v) {
                    $("#listImpresorasEnComplejo").append('<option data-serie="' + v.Serie + '" data-area="' + v.IdArea + '" data-punto="' + v.Punto + '">' + v.Modelo + '</option>')
                });

                var selectedImpresoraRetirada = '';
                $.each(respuesta.modelos, function (k, v) {
                    selectedImpresoraRetirada = '';
                    if (v.Id == respuesta.retirada.impresora.IdModelo) {
                        selectedImpresoraRetirada = ' selected="selected" ';
                    }
                    $("#listImpresorasRetiro").append('<option value="' + v.Id + '" ' + selectedImpresoraRetirada + '>' + v.Sublinea + ' ' + v.Nombre + '</option>');
                });

                var selectedEstatusImpresoraRetirada = '';
                $.each(respuesta.estatus, function (k, v) {
                    selectedEstatusImpresoraRetirada = '';
                    if (v.Id == respuesta.retirada.impresora.IdEstatus) {
                        selectedEstatusImpresoraRetirada = ' selected="selected" ';
                    }
                    $("#listEstatusImpresoraRetirada").append('<option value="' + v.Id + '" ' + selectedEstatusImpresoraRetirada + '>' + v.Nombre + '</option>');
                });

                $("#txtSerieImpresoraRetirada").val(respuesta.retirada.impresora.Serie);

                select.crearSelect("#listImpresorasEnComplejo");
                select.crearSelect("#listImpresorasRetiro");
                select.crearSelect("#listEstatusImpresoraRetirada");

            } else {
                evento.mostrarMensaje("#errorMessageSeguimiento", false, respuesta.message, 4000);
            }
        });

        $("#btnGuardarRetirados").off("click");
        $("#btnGuardarRetirados").on("click", function () {
            var retirados = {};
            retirados.impresora = {
                'modelo': $("#listImpresorasRetiro").val(),
                'serie': $.trim($("#txtSerieImpresoraRetirada").val()),
                'estatus': $("#listEstatusImpresoraRetirada").val(),
            };

            var error = '';
            if (retirados.impresora.serie == "") {
                error = 'El número de serie de la impresora es un campo obligatorio'
            } else if (retirados.impresora.modelo == "") {
                error = 'El modelo de la impresora que se retira es un campo obligatorio'
            } else if (retirados.impresora.estatus == "") {
                error = 'El Estatus de la impresora es un campo obligatorio'
            }

            if (error != '') {
                evento.mostrarMensaje("#errorMessageSeguimiento", false, error, 4000);
                return false;
            } else {
                var data = {
                    servicio: datos.id,
                    retirados: retirados
                }
                evento.enviarEvento('Seguimiento/GuardarRetiradosLexmark', data, '#panelSeguimiento', function (respuesta) {
                    if (respuesta.code == 200) {
                        evento.mostrarMensaje("#errorMessageSeguimiento", true, respuesta.message, 4000);
                    } else {
                        evento.mostrarMensaje("#errorMessageSeguimiento", false, respuesta.message, 4000);
                    }
                });
            }
        });

    }

});



