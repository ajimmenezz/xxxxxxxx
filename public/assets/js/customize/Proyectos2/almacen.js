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

    tabla.generaTablaPersonal('#table-proyectos', null, null, true, true, [[1, 'asc']]);

    $('#table-proyectos tbody').on('click', 'tr', function () {
        let _this = this;
        var datosTabla = $("#table-proyectos").DataTable().row(_this).data();
        var datos = {
            'id': datosTabla[0],
            'almacen': datosTabla[1]
        };

        evento.enviarEvento('Almacen/FormularioDetallesProyectoAlmacen', datos, '#panel-table-proyectos', function (respuesta) {
            if (respuesta.code == 200) {
                $("#divDetallesProyectoAlmacen").empty().append(respuesta.formulario);
                evento.cambiarDiv("#divListaProyectos", "#divDetallesProyectoAlmacen", initFormularioDetallesProyectoAlmacen());
            } else {
                evento.mostrarMensaje("#errorTableProyectos", false, respuesta.error, 4000);
            }
        });
    });

    function initFormularioDetallesProyectoAlmacen() {
        select.crearSelect("#listAlmacenesSAE");
        select.crearSelectMultiple("#listLideres", 'Selecciona...');

        tabla.generaTablaPersonal('#table-material-diferencias', null, null, true, false, [[0, 'asc']], null, null, false);

        $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
            var target = $(e.target).attr("href");
            switch (target) {
                case "#Material":
                    cargaMaterialTotales();
                    break;
            }
        });

        $("#listAlmacenesSAE").on("change", function () {
            var datos = {
                'proyecto': $("#IdProyecto").val(),
                'almacen': $("#listAlmacenesSAE").val()
            }
            evento.enviarEvento('Almacen/AsignarAlmacenVirtual', datos, '#panelFormDetallesProyecto', function (respuesta) {
                if (respuesta.code == 200) {
                    evento.mostrarMensaje("#errorMessage", true, respuesta.error, 4000);
                    $("#IdAlmacenSAE").val(datos.almacen);
                } else {
                    evento.mostrarMensaje("#errorMessage", false, respuesta.error, 4000);
                }
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

});