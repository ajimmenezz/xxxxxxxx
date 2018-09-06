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

    tabla.generaTablaPersonal('#table-tareas', null, null, true, true, [[0, 'asc']]);

    $('#table-tareas tbody').on('click', 'tr', function () {
        let _this = this;
        var datosTabla = $("#table-tareas").DataTable().row(_this).data();
        var datos = {
            'id': datosTabla[0]
        };

        evento.enviarEvento('Tareas/FormularioSeguimientoTarea', datos, '#panel-table-tareas', function (respuesta) {
            if (respuesta.code == 200) {
                $("#divFormularioSeguimientoTarea").empty().append(respuesta.formulario);
                evento.cambiarDiv("#divListaTareas", "#divFormularioSeguimientoTarea", initFormularioSeguimientoTarea(_this));
            } else {
                evento.mostrarMensaje("#errorTableTareas", false, respuesta.error, 4000);
            }
        });
    });

    function initFormularioSeguimientoTarea() {
        var _fila = arguments[0];

        $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
            var target = $(e.target).attr("href");
            switch (target) {
                case "#MaterialNodo":
                    cargaNodosTarea();
                    break;
                case "#ConsumoMaterial":
                    cargaConsumirMaterial();
                    break;
                case "#NotasAdjuntos":
                    cargaNotasAdjuntos();
                    break;
            }
        });


        $("#btnGuardarAvanceTarea").off("click");
        $("#btnGuardarAvanceTarea").on("click", function () {
            var datos = {
                'id': $("#IdTarea").val(),
                'avance': $.trim($("#txtAvanceProyecto").val())
            }

            if (datos.avance >= 0 && datos.avance <= 100) {
                evento.enviarEvento('Tareas/GuardarAvanceTarea', datos, '#panelFormularioSeguimientoTarea', function (respuesta) {
                    if (respuesta.code == 200) {
                        evento.mostrarMensaje("#errorMessage", true, respuesta.error, 4000);
                        $(_fila).find("td:nth-child(4)").empty().append(datos.avance + "%");
                    } else {
                        evento.mostrarMensaje("#errorMessage", false, respuesta.error, 4000);
                    }
                });
            } else {
                evento.mostrarMensaje("#errorMessage", false, "El valor del avance debe ser entre 0% y 100%", 4000);
            }
        });

        file.crearUpload('#adjuntosTarea', 'Tareas/GuardarNotasAdjuntos', ['jpg', 'bmp', 'jpeg', 'gif', 'png', 'xls', 'xlsx', 'pdf']);

        $("#btnGuardarNotasAdjuntos").off("click");
        $("#btnGuardarNotasAdjuntos").on("click", function () {
            var _nota = $.trim($("#txtNotaTarea").val());
            var _adjunto = $("#adjuntosTarea").val();

            if (_nota !== '' || _adjunto !== '') {
                var datos = {
                    'id': $.trim($("#IdTarea").val()),
                    'nota': _nota
                };

                file.enviarArchivos('#adjuntosTarea', 'Tareas/GuardarNotasAdjuntos', '#panelFormularioSeguimientoTarea', datos, function (respuesta) {
                    if (respuesta.code == 200) {
                        evento.mostrarMensaje("#errorGuardarNotasAdjunto", true, "Se ha guardado la nota correctamente", 6000);
                        $("#txtNotaTarea").val('').text('');
                        file.limpiar('#adjuntosTarea');
                        cargaNotasAdjuntos();
                    } else {
                        evento.mostrarMensaje("#errorGuardarNotasAdjunto", false, "Ocurrió un error al guardar la nota. Por favor recargue su página y vuelva a intentarlo.", 4000);
                    }
                });
            } else {
                evento.mostrarMensaje("#errorGuardarNotasAdjunto", false, "Al menos debe agregar una nota o un adjunto para poder guardar la información", 4000);
            }
        });
    }

    function cargaNodosTarea() {
        var datos = {
            'id': $.trim($("#IdTarea").val())
        };

        evento.enviarEvento('Tareas/CargaMaterialNodosTarea', datos, '#panelFormularioSeguimientoTarea', function (respuesta) {
            if (respuesta.code == 200) {
                $("#divNodosTarea").empty().append(respuesta.formulario);
                initFormularioMaterialNodo();
            } else {
                evento.mostrarMensaje("#errorMessage", false, respuesta.error, 4000);
            }
        });
    }

    function initFormularioMaterialNodo() {
        $(".input-utilizado").on("change", function () {
            var _max = parseFloat($(this).attr("data-max"));
            var _utilizado = $(this).val();
            if (isNaN(_utilizado)) {
                $(this).val(0.00).trigger("change");
            } else {
                _utilizado = parseFloat(_utilizado);
                if (_utilizado > _max) {
                    $(this).removeClass("bg-green").addClass("text-white bg-red");
                } else if (_utilizado == 0) {
                    $(this).removeClass("text-white bg-green bg-red");
                } else {
                    $(this).removeClass("bg-red").addClass("text-white bg-green");
                }
            }
        });

        $(".btnGuardarMaterialNodoUtilizado").off("click");
        $(".btnGuardarMaterialNodoUtilizado").on("click", function () {
            var _datos = [];
            $(".input-utilizado").each(function () {
                _datos.push({
                    'id': $(this).attr("data-id"),
                    'utilizado': parseFloat($.trim($(this).val()))
                });
            });

            var datos = {
                'id': $.trim($("#IdTarea").val()),
                'data': _datos
            }

            evento.enviarEvento('Tareas/GuardaMaterialUtilizadoNodosTarea', datos, '#panelFormularioSeguimientoTarea', function (respuesta) {
                if (respuesta.code == 200) {
                    $("#divNodosTarea").empty().append(respuesta.formulario);
                    initFormularioMaterialNodo();
                } else {
                    evento.mostrarMensaje("#errorMessage", false, respuesta.error, 4000);
                }
            });

        });

        $(".input-utilizado").trigger("change");
    }

    function cargaConsumirMaterial() {
        var datos = {
            'id': $.trim($("#IdTarea").val())
        };

        evento.enviarEvento('Tareas/CargaConsumirMaterial', datos, '#panelFormularioSeguimientoTarea', function (respuesta) {
            if (respuesta.code == 200) {
                $("#divConsumoMaterial").empty().append(respuesta.formulario);
                initFormularioConsumirMaterial();
            } else {
                evento.mostrarMensaje("#errorMessage", false, respuesta.error, 4000);
            }
        });
    }

    function initFormularioConsumirMaterial() {
        $(".input-utilizado-material-tarea").on("change", function () {
            var _max = parseFloat($(this).attr("data-max"));
            var _utilizado = $(this).val();
            if (isNaN(_utilizado)) {
                $(this).val(0.00).trigger("change");
            } else {
                _utilizado = parseFloat(_utilizado);
                if (_utilizado > _max) {
                    $(this).removeClass("bg-green").addClass("text-white bg-red");
                } else if (_utilizado == 0) {
                    $(this).removeClass("text-white bg-green bg-red");
                } else {
                    $(this).removeClass("bg-red").addClass("text-white bg-green");
                }
            }
        });

        $(".btnGuardarMaterialTareaUtilizado").off("click");
        $(".btnGuardarMaterialTareaUtilizado").on("click", function () {
            var _datos = [];
            $(".input-utilizado-material-tarea").each(function () {
                _datos.push({
                    'id': $(this).attr("data-id"),
                    'utilizado': parseFloat($.trim($(this).val()))
                });
            });

            var datos = {
                'id': $.trim($("#IdTarea").val()),
                'data': _datos
            }

            evento.enviarEvento('Tareas/GuardaMaterialUtilizadoTarea', datos, '#panelFormularioSeguimientoTarea', function (respuesta) {
                if (respuesta.code == 200) {
                    $("#divConsumoMaterial").empty().append(respuesta.formulario);
                    initFormularioConsumirMaterial();
                } else {
                    evento.mostrarMensaje("#errorMessage", false, respuesta.error, 4000);
                }
            });

        });

        $(".input-utilizado-material-tarea").trigger("change");
    }

    function cargaNotasAdjuntos() {
        var datos = {
            'id': $.trim($("#IdTarea").val())
        };

        evento.enviarEvento('Tareas/CargaNotasAdjuntos', datos, '#panelFormularioSeguimientoTarea', function (respuesta) {
            if (respuesta.code == 200) {
                $("#divNotasAdjuntos").empty().append(respuesta.formulario);
            } else {
                evento.mostrarMensaje("#errorMessage", false, respuesta.error, 4000);
            }
        });
    }

});