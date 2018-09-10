$(function () {

    //variables
    var departamentos;

    //Objetos
    var evento = new Base();
    var websocket = new Socket();
    var select = new Select();
    var file = new Upload();
    var servicios = new Servicio();

    //Evento que maneja las peticiones del socket
    websocket.socketMensaje();

    //Muestra la hora en el sistema
    evento.horaServidor($('#horaServidor').val());

    //Evento para cerra la session
    evento.cerrarSesion();

    //Inicializa funciones de la plantilla
    App.init();

    //Creando input de evidencias
    file.crearUpload('#inputEvidenciasSolicitud', 'Solicitud/Nueva_solicitud');

    //Obteniendo los datos de los departamentos    
    evento.enviarEvento('Solicitud/Solicitud_CatDepartamentos', {}, '', function (respuesta) {
        departamentos = respuesta;
        //Obteniendo el id del Usuario    
        evento.enviarEvento('Solicitud/Usuario', {}, '#panelNuevaSolicitud', function (respuesta) {
            if (respuesta === '92') {
                select.cambiarOpcion('#selectAreasSolicitud', '6');
                select.cambiarOpcion('#selectDepartamentoSolicitud', '7');
            }
        });
    });

    //Evento de select personal para seleccionar su area y departamento
    $('#selectParsonalSolicitud').on('change', function () {
        var perfil = $(this).val();

        if (perfil !== '') {
            var dataPerfil = {perfil: perfil};
            evento.enviarEvento('Solicitud/BuscarAreaDepartamento', dataPerfil, '#panelNuevaSolicitud', function (respuesta) {
                if (respuesta) {
                    select.cambiarOpcion('#selectAreasSolicitud', respuesta[0].Area);
                    select.cambiarOpcion('#selectDepartamentoSolicitud', respuesta[0].Departamento);
                }
            });
        }
    });

    //Evento de select area que activa departamento
    $('#selectAreasSolicitud').on('change', function () {
        if ($(this).val() !== '') {
            $('#selectDepartamentoSolicitud').removeAttr('disabled');
            if ($(this).val() === 'sinArea') {
                select.setOpcionesSelect('#selectDepartamentoSolicitud', departamentos, $('#selectAreasSolicitud').val(), 'IdArea', {id: 'sinDepartamento', text: 'Sin departamento'});
                select.cambiarOpcion('#selectDepartamentoSolicitud', 'sinDepartamento');
            } else {
                select.setOpcionesSelect('#selectDepartamentoSolicitud', departamentos, $('#selectAreasSolicitud').val(), 'IdArea');
            }
        } else {
            $('#selectDepartamentoSolicitud').attr('disabled', 'disabled');
            select.cambiarOpcion('#selectDepartamentoSolicitud', '');
        }
    });

    //Limpiar nueva solicitud
    $('#btnCancelarNuevaSolicitud').on('click', function () {
        file.limpiar('#inputEvidenciasSolicitud');
        evento.limpiarFormulario('#formNuevaSolicitud');
    });

    //Generar nueva solicitud
    $('#btnGenerarSolicitud').on('click', function () {
        var correo = $("#tagValor").tagit("assignedTags");
        var verificarCorreo;
        var verificarSucursal;

        if (correo.length > 0) {
            if (servicios.validarCorreoArray(correo)) {
                verificarCorreo = true;
            } else {
                verificarCorreo = false;
            }
        } else {
            verificarCorreo = true;
        }

        if ($('#inputFolioSolicitud').val() !== '' || $('#selectClienteSolicitud').val() === '1') {
            if ($('#selectSucursalSolicitud').val() !== '') {
                verificarSucursal = true;
            } else {
                verificarSucursal = false;
            }
        } else {
            verificarSucursal = true;
        }

        if (evento.validarFormulario('#formNuevaSolicitud')) {
            if (verificarCorreo) {
                if (verificarSucursal) {
                    var data = {
                        tipo: '3',
                        departamento: $('#selectDepartamentoSolicitud').val(),
                        prioridad: $('#selectPrioridadSolicitud').val(),
                        descripcion: $('#textareaDescripcionSolicitud').val(),
                        asunto: $('#inputAsuntoSolicitud').val(),
                        correo: correo,
                        folio: $('#inputFolioSolicitud').val(),
                        sucursal: $('#selectSucursalSolicitud').val()};
                    
                    file.enviarArchivos('#inputEvidenciasSolicitud', 'Solicitud/Nueva_solicitud', '#panelNuevaSolicitud', data, function (respuesta) {
                        if (respuesta !== 'otraImagen') {
                            evento.mostrarModal('Numero de solicitud',
                                    '<div class="row">\n\
                                        <div class="col-md-12 text-center">\n\
                                            <h5>Se genero la solicitud <b>' + respuesta + '</b></h5>\n\
                                        </div>\n\
                                    </div>');
                            $("#tagValor").tagit("removeAll");
                            select.cambiarOpcion('#selectParsonalSolicitud', '');
                            $('#btnModalConfirmar').addClass('hidden');
                            $('#btnModalAbortar').empty().append('Cerrar');
                        } else {
                            evento.mostrarMensaje('.errorSolicitudNueva', false, 'Hubo un problema con la imagen selecciona otra distinta.', 3000);
                        }

                        $('#btnModalAbortar').on('click', function () {
                            $('#btnCancelarNuevaSolicitud').trigger('click');
                        });
                    });
                } else {
                    evento.mostrarMensaje('.errorSolicitudNueva', false, 'Debe seleccionar una sucursal.', 3000);
                }
            } else {
                evento.mostrarMensaje('.errorSolicitudNueva', false, 'Algun Correo no es correcto.', 3000);
            }
        }
    });

    $("#selectClienteSolicitud").on("change", function () {
        $('#selectSucursalSolicitud').empty().append('<option value="">Seleccionar</option>');
        select.cambiarOpcion('#selectSucursalSolicitud', '');
        var cliente = $(this).val();

        if (cliente !== '') {
            $('#selectSucursalSolicitud').removeAttr('disabled');
            var data = {cliente: cliente};

            evento.enviarEvento('Solicitud/MostrarSucursalesCliente', data, '#panelSeguimientoActividad', function (respuesta) {
                $.each(respuesta, function (key, valor) {
                    $("#selectSucursalSolicitud").append('<option value=' + valor.Id + '>' + valor.Nombre + '</option>');
                });
            });
        } else {
            $('#selectSucursalSolicitud').attr('disabled', 'disabled');
        }
    });

    $("#tagValor").tagit({
        allowSpaces: false
    });

});