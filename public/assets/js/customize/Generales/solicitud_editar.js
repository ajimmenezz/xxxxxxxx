$(function () {
    //Objetos
    var evento = new Base();
    var websocket = new Socket();
    var tabla = new Tabla();
    var select = new Select();

    //Variables globales
    var listaIds;

    //Evento que maneja las peticiones del socket
    websocket.socketMensaje();

    //Muestra la hora en el sistema
    evento.horaServidor($('#horaServidor').val());

    //Evento para cerra la session
    evento.cerrarSesion();

    //Inicializa funciones de la plantilla
    App.init();

    initPage();


    function initPage() {

        tabla.generaTablaPersonal('#tableSolicitudes', null, null, true, true, [[0, 'asc']]);

        $('input[type=radio][name=radioSolicitud]').change(function () {
            if (this.value == 'dpto') {
                $("#listDepartamentosSolicitud").removeAttr('disabled');
            } else if (this.value == 'id') {
                $("#listDepartamentosSolicitud").attr('disabled', 'disabled');
                select.limpiarSelecccion("#listDepartamentosSolicitud");
            }
        });

        select.crearSelectMultiple("#listDepartamentosSolicitud", "Seleccionar . . .");

        $("#tagsIdsSolicitudes").tagit({
            allowSpaces: false
        });

        $("#btnBuscarSolicitudes").off("click");
        $("#btnBuscarSolicitudes").on("click", function () {
            var departamentos = $("#listDepartamentosSolicitud").val();
            var ids = $("#tagsIdsSolicitudes").tagit("assignedTags");
            var data = {
                departamentos: departamentos,
                ids: ids
            };

            if ((typeof departamentos == 'undefined' || departamentos == null) && ids.length <= 0) {
                evento.mostrarMensaje('.errorSolicitudes1', false, 'Debe seleccionar al menos un departamento o capturar un Id de Solicitud.', 4000);
            } else {
                cargaSolicitudes();
            }
        });
    }

    function cargaSolicitudes() {
        var departamentos = $("#listDepartamentosSolicitud").val();
        var ids = $("#tagsIdsSolicitudes").tagit("assignedTags");
        var data = {
            departamentos: departamentos,
            ids: ids
        };
        evento.enviarEvento('Solicitud_Editar/Carga_Solicitudes', data, '#PanelGeneral', function (respuesta) {
            var columnas = [
                {data: 'Id'},
                {data: 'Ticket'},
                {data: 'Solicita'},
                {data: 'Departamento'},
                {data: 'Prioridad'},
                {data: 'Atiende'},
                {data: 'Estatus'},
                {data: 'Fecha'},
                {data: 'Asunto'}
            ];
            tabla.limpiarTabla('#tableSolicitudes');
            tabla.generaTablaPersonal('#tableSolicitudes', respuesta.data, columnas, true, true, [[0, 'asc']]);

            initClickTableSolicitudes();
        });               
    }

    function initClickTableSolicitudes() {
        $('#tableSolicitudes').on('click', 'tr', function () {
            var datos = $('#tableSolicitudes').DataTable().row(this).data();
            var data = {id: datos.Id};
            cargaDetallesSolicitud(data);
        });
    }

    function cargaDetallesSolicitud() {
        var data = arguments[0];
        evento.enviarEvento('Solicitud_Editar/Carga_DetallesSolicitud', data, '#PanelGeneral', function (respuesta) {
            if (respuesta.code == 200) {
                var id = data.id;
                $("#secondPage").empty().append(respuesta.html);
                var file = new Upload();
                file.crearUpload('#adjuntosSolicitud', 'Solicitud_Editar/GuardaImagenesSolicitud');
                $(".btnRemoveFileSolicitudes").off("click");
                $(".btnRemoveFileSolicitudes").on("click", function () {
                    $(this).closest("div.thumbnail-pic").remove();
                });

                $("#btnGuardarCambiosSolicitud").off("click");
                $("#btnGuardarCambiosSolicitud").on("click", function () {
                    var solicita = $("#listaSolicitaSolicitudes").val();
                    var departamento = $("#listaDepartamentosSolicitudes").val();
                    var prioridad = $("#listaPrioridadesSolicitudes").val();
                    var atiende = $("#listaAtiendeSolicitudes").val();
                    var fecha = $("#txtFechaSolicitud").val();
                    var asunto = $("#txtAsuntoSolicitud").val();
                    var descripcion = $("#txtDescripcionSolicitud").val();
                    var nuevasImagenes = $("#adjuntosSolicitud").val();
                    var imagenesSolicitud = [];
                    $(".imagenesSolicitud").each(function () {
                        imagenesSolicitud.push($(this).prop("href"));
                    });

                    var data = {
                        id: id,
                        solicita: solicita,
                        departamento: departamento,
                        prioridad: prioridad,
                        atiende: atiende,
                        fecha: fecha,
                        asunto: asunto,
                        descripcion: descripcion,
                        imagenes: imagenesSolicitud
                    }

                    if (nuevasImagenes == '') {
                        saveWithoutImageSolicitud(data);
                    } else {
                        var datos = {
                            id: data.id
                        }
                        file.enviarArchivos('#adjuntosSolicitud', 'Solicitud_Editar/GuardaImagenesSolicitud', '#panelDetallesSolicitud', datos, function (respuesta) {
                            if (respuesta.code == 200) {
                                saveWithoutImageSolicitud(data, respuesta.files);
                            }
                        });
                    }
                });
                showSecondPage();
            }
        });
    }

    function saveWithoutImageSolicitud() {
        var data = arguments[0];
        var id = data.id;
        var nuevasImagenes = arguments[1] || '';
        var data = {
            detalles: data,
            images: nuevasImagenes
        }
        evento.enviarEvento('Solicitud_Editar/Guarda_DetallesSolicitud', data, '#panelDetallesSolicitud', function (respuesta) {
            if (respuesta.code == 200) {
                cargaSolicitudes();
                cargaDetallesSolicitud({id: id});
            }
        });
    }

    function showSecondPage() {
        $("#firstPage").fadeOut(400, function () {
            $("#secondPage").fadeIn(400, function () {
                initButtonToInitial();
            });
        });
    }

    function initButtonToInitial() {
        $("#btnBackToInitial").off("click");
        $("#btnBackToInitial").on("click", function () {
            $("#secondPage").fadeOut(400, function () {
                $("#firstPage").fadeIn(400);
            });
        });

    }

});