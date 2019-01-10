$(function () {
//Objetos
    var evento = new Base();
    var websocket = new Socket();
    var calendario = new Fecha();
    var select = new Select();
    var tabla = new Tabla();
    var file = new Upload();
    var usuario_perfil = new Usuario_perfil();

    //Evento que maneja las peticiones del socket
    websocket.socketMensaje();
    //Muestra la hora en el sistema
    evento.horaServidor($('#horaServidor').val());
    //Evento para cerra la session
    evento.cerrarSesion();
    //Creando tabla de personal
    tabla.generaTablaPersonal('#data-table-personal', null, null, true, true, [[0, 'desc']]);
    //Evento para mostrar la ayuda del sistema
    evento.mostrarAyuda('Ayuda_Proyectos');
    //Inicializa funciones de la plantilla
    App.init();
    //Evento que permite actualizar el personal
    $('#data-table-personal tbody').on('click', 'tr', function () {
        var datos = $('#data-table-personal').DataTable().row(this).data();
        var data = {id: datos[0]};
        var url = [];
        evento.enviarEvento('EventoAltaPersonal/MostrarPersonalActualizar', data, '#seccionPersonal', function (respuesta) {
            $.each(respuesta.datos.urlFoto, function (key, valor) {
                url[0] = valor.UrlFoto;
            });
            if (url[0] === null) {
                url[0] = '';
            }

            $('#formularioPersonal').removeClass('hidden').empty().append(respuesta.formulario);
            $('#resumenPersonal').addClass('hidden');
            $('#nuevo').addClass('hidden');
            select.crearSelect('#selectArea');
            select.setOpcionesSelect('#selectDepartamento', respuesta.datos.departamentos, $('#selectArea').val(), 'IdArea');
            $('#selectDepartamento').removeAttr('disabled');
            select.crearSelect('#selectPerfil');
            select.crearSelect('#selectJefe');
            select.crearSelect('#selectActualizarPaisUsuario');
            select.crearSelect('#selectActualizarEstadoUsuario');
            select.crearSelect('#selectActualizarMunicipioUsuario');
            select.crearSelect('#selectActualizarEstadoCivilUsuario');
            select.crearSelect('#selectActualizarSexoUsuario');
            select.crearSelect('#selectActualizarNivelEstudioUsuario');
            select.crearSelect('#selectActualizarDocumentoRecibidoUsuario');
            select.crearSelect('#selectActualizarIdiomaUsuario');
            select.crearSelect('#selectActualizarComprensionUsuario');
            select.crearSelect('#selectActualizarLecturaUsuario');
            select.crearSelect('#selectActualizarEscrituraUsuario');
            select.crearSelect('#selectActualizarSoftwareUsuario');
            select.crearSelect('#selectActualizarNivelComputacionalesUsuario');
            select.crearSelect('#selectActualizarSistemasUsuario');
            select.crearSelect('#selectActualizarNivelSistemasUsuario');
            select.crearSelect('#selectActualizarDominaUsuario');
            $('#inputAPPersonal').val(datos[1]);
            $('#inputAMPersonal').val(datos[2]);
            $('#inputNombrePersonal').val(datos[3]);
            $('#inputTMPersonal').val(datos[8]);
            $('#inputTFPersonal').val(datos[9]);
            $('#inputEmailPersonal').val(datos[10]);
            $('#inputFechaNacimiento').val(datos[11]);
            $('#inputCurpPersonal').val(datos[12]);
            $('#inputRFCPersonal').val(datos[13]);
            $('#inputFechaIngreso').val(datos[14]);
            $('#inputNoSeguroSocial').val(datos[15]);
            $('#selectPerfil').removeAttr('disabled');
            $('#actualizarPersonal').removeClass('hidden');
            $('#nuevaFoto').addClass('hidden');
            //mascara para telefono movil
            $('#inputTMPersonal').mask("999-9999999999");
            //mascara para telefono fijo
            $('#inputTFPersonal').mask("99-999-99999999");
            //mascara para no. seguro social
            $('#inputNoSeguroSocial').mask("99999999999");
            calendario.crearFecha('.calendario');
            //Creando input de foto
            $('#actualizarFoto').empty().append('\
                            <div class="form-group">\n\
                                <label for="personal">Foto</label>\n\
                                <input id="inputActualizarFoto" name="fotoActualizarPersonal[]" type="file" multiple>\n\
                        </div>');
            file.crearUpload(
                    '#inputActualizarFoto',
                    'EventoAltaPersonal/Actualizar_Personal',
                    ['jpg', 'png'],
                    null,
                    url,
                    'EventoAltaPersonal/EliminarFoto',
                    datos[0],
                    true,
                    1,
                    true);
            $('#selectArea').on('change', function () {
                select.setOpcionesSelect('#selectDepartamento', respuesta.datos.departamentos, $('#selectArea').val(), 'IdArea');
                select.cambiarOpcion('#selectPerfil', '');
                $('#selectPerfil').attr('disabled', 'disabled');
                if ($(this).val() !== '') {
                    $('#selectDepartamento').removeAttr('disabled');
                } else {
                    $('#selectDepartamento').attr('disabled', 'disabled');
                    $('#selectPerfil').attr('disabled', 'disabled');
                    select.cambiarOpcion('#selectPerfil', '');
                }
            });
            select.setOpcionesSelect('#selectPerfil', respuesta.datos.perfiles, $('#selectDepartamento').val(), 'IdDepartamento');
            $('#selectDepartamento').on('change', function () {
                select.setOpcionesSelect('#selectPerfil', respuesta.datos.perfiles, $('#selectDepartamento').val(), 'IdDepartamento');
                if ($(this).val() !== '') {
                    $('#selectPerfil').removeAttr('disabled');
                } else {
                    $('#selectPerfil').attr('disabled', 'disabled');
                }
            });
            select.cambiarOpcion('#selectArea', respuesta.datos.idArea[0].Area);
            select.cambiarOpcion('#selectDepartamento', respuesta.datos.idDepartamento[0].Id);
            select.cambiarOpcion('#selectPerfil', respuesta.datos.idPerfil[0].IdPerfil);
            select.cambiarOpcion('#selectJefe', respuesta.datos.consultaV3Usuarios[0].IdJefe);

            //Evento que actualizar al personal
            $('#btnactualizarPersonal').on('click', function () {
                if (evento.validarFormulario('#formNuevoPersonal')) {
                    var paterno = $('#inputAPPersonal').val();
                    var materno = $('#inputAMPersonal').val();
                    var nombre = $('#inputNombrePersonal').val();
                    var movil = $('#inputTMPersonal').val();
                    var fijo = $('#inputTFPersonal').val();
                    var email = $('#inputEmailPersonal').val();
                    var fechaNacimiento = $('#inputFechaNacimiento').val();
                    var curp = $('#inputCurpPersonal').val();
                    var rfc = $('#inputRFCPersonal').val();
                    var fechaIngreso = $('#inputFechaIngreso').val();
                    var noSeguroSocial = $('#inputNoSeguroSocial').val();
                    var idPerfil = $('#selectPerfil').val();
                    var idJefe = $('#selectJefe').val();

                    var data = {id: datos[0], paterno: paterno, materno: materno, nombre: nombre, movil: movil, fijo: fijo, email: email, fechaNacimiento: fechaNacimiento, curp: curp, rfc: rfc, fechaIngreso: fechaIngreso, noSeguroSocial: noSeguroSocial, idPerfil: idPerfil, usuario: datos[7], idJefe: idJefe};
                    if (fechaNacimiento != '') {
                        if (fechaIngreso != '') {
                            file.enviarArchivos('#inputActualizarFoto', 'EventoAltaPersonal/Actualizar_Personal', '#seccion-datos-personal', data, function (respuesta) {
                                if (respuesta instanceof Array) {
                                    tabla.limpiarTabla('#data-table-personal');
                                    $.each(respuesta, function (key, valor) {
                                        tabla.agregarFila('#data-table-personal', [valor.Id, valor.ApPaterno, valor.ApMaterno, valor.Nombres, valor.Area, valor.Departamento, valor.Perfil, valor.IdUsuario, valor.Tel1, valor.Tel2, valor.Email, valor.FechaNacimiento, valor.CURP, valor.RFC, valor.FechaAlta, valor.NSS], true);
                                    });
                                    $('#formularioPersonal').empty().addClass('hidden');
                                    $('#resumenPersonal').removeClass('hidden');
                                    evento.mostrarMensaje('.errorResumenPersonal', true, 'Datos actualizados correctamente', 3000);

                                } else {
                                    evento.mostrarMensaje('.errorAltaPersonal', false, 'Ya se dio de alta ese correo, por lo que ya no puedes repetirlo.', 3000);
                                }
                            });

                        } else {
                            evento.mostrarMensaje('.errorAltaPersonal', false, 'Campo Fecha Ingreso vacío', 3000);
                        }
                    } else {
                        evento.mostrarMensaje('.errorAltaPersonal', false, 'Campo Fecha Nacimiento vacío', 3000);
                    }
                }
            });
            $('#btnCancelarActualizarPersonal').on('click', function () {
                $('#formularioPersonal').empty().addClass('hidden');
                $('#resumenPersonal').removeClass('hidden');
            });

            $("#selectActualizarPaisUsuario").on("change", function () {

            usuario_perfil.SelectNacimiento();
            });

        });
    });
    //Evento que actualizar al personal
    $('#btnAgregarPersonal').on('click', function () {
        evento.enviarEvento('EventoAltaPersonal/MostrarPersonalActualizar', '', '#seccionPersonal', function (respuesta) {
            $('#formularioPersonal').removeClass('hidden').empty().append(respuesta.formulario);
            //mascara para telefono movil
            $('#inputTMPersonal').mask("999-9999999999");
            //mascara para telefono fijo
            $('#inputTFPersonal').mask("99-999-99999999");
            //mascara para no. seguro social
            $('#inputNoSeguroSocial').mask("99999999999");
            calendario.crearFecha('.calendario');
            select.crearSelect('select');
            //Creando input de foto
            file.crearUpload('#inputFoto', 'EventoAltaPersonal/Nuevo_Personal', ['jpg', 'png'], null, null, null, null, true, 1);
            $('#selectDepartamento').attr('disabled', 'disabled');
            $('#selectPerfil').attr('disabled', 'disabled');
            $('#resumenPersonal').addClass('hidden');
            select.setOpcionesSelect('#selectDepartamento', respuesta.datos.departamentos, $('#selectArea').val(), 'IdArea');
            $('#selectArea').on('change', function () {
                select.setOpcionesSelect('#selectDepartamento', respuesta.datos.departamentos, $('#selectArea').val(), 'IdArea');
                select.cambiarOpcion('#selectPerfil', '');
                $('#selectPerfil').attr('disabled', 'disabled');
                if ($(this).val() !== '') {
                    $('#selectDepartamento').removeAttr('disabled');
                } else {
                    $('#selectDepartamento').attr('disabled', 'disabled');
                    $('#selectPerfil').attr('disabled', 'disabled');
                    select.cambiarOpcion('#selectPerfil', '');
                }
            });
            $('#selectDepartamento').on('change', function () {
                select.setOpcionesSelect('#selectPerfil', respuesta.datos.perfiles, $('#selectDepartamento').val(), 'IdDepartamento');
                if ($(this).val() !== '') {
                    $('#selectPerfil').removeAttr('disabled');
                } else {
                    $('#selectPerfil').attr('disabled', 'disabled');
                }
            });
            $('#btnNuevoPersonal').on('click', function () {
                if (evento.validarFormulario('#formNuevoPersonal')) {
                    var paterno = $('#inputAPPersonal').val();
                    var materno = $('#inputAMPersonal').val();
                    var nombre = $('#inputNombrePersonal').val();
                    var movil = $('#inputTMPersonal').val();
                    var fijo = $('#inputTFPersonal').val();
                    var email = $('#inputEmailPersonal').val();
                    var fechaNacimiento = $('#inputFechaNacimiento').val();
                    var curp = $('#inputCurpPersonal').val();
                    var rfc = $('#inputRFCPersonal').val();
                    var area = $('#selectArea').val();
                    var perfil = $('#selectPerfil').val();
                    var fechaIngreso = $('#inputFechaIngreso').val();
                    var noSeguroSocial = $('#inputNoSeguroSocial').val();
                    var idJefe = $('#selectJefe').val();

                    var data = {paterno: paterno, materno: materno, nombre: nombre, movil: movil, fijo: fijo, email: email, fechaNacimiento: fechaNacimiento, curp: curp, rfc: rfc, fechaIngreso: fechaIngreso, noSeguroSocial: noSeguroSocial, idJefe: idJefe, area: area, perfil: perfil};
                    if (fechaNacimiento != '') {
                        if (fechaIngreso != '') {
                            file.enviarArchivos('#inputFoto', 'EventoAltaPersonal/Nuevo_Personal', '#seccion-datos-personal', data, function (respuesta) {
                                if (respuesta !== 'correo') {
                                    if (respuesta !== 'correoCorporativo') {
                                        tabla.limpiarTabla('#data-table-personal');
                                        $.each(respuesta, function (key, valor) {
                                            tabla.agregarFila('#data-table-personal', [valor.Id, valor.ApPaterno, valor.ApMaterno, valor.Nombres, valor.Area, valor.Departamento, valor.Perfil, valor.IdUsuario, valor.Tel1, valor.Tel2, valor.Email, valor.FechaNacimiento, valor.CURP, valor.RFC, valor.FechaAlta, valor.NSS], true);
                                        });
                                        $('#formularioPersonal').addClass('hidden');
                                        $('#resumenPersonal').removeClass('hidden');
                                        evento.mostrarMensaje('.errorResumenPersonal', true, 'Se inserto correctamente el personal', 3000);
                                    } else {
                                        evento.mostrarMensaje('.errorAltaPersonal', false, 'Ya se dio de alta ese correo, por lo que ya no puedes repetirlo.', 3000);
                                    }
                                } else {
                                    evento.mostrarMensaje('.errorAltaPersonal', false, 'Ya se dio de alta ese correo, por lo que ya no puedes repetirlo.', 3000);
                                }
                            });
                        } else {
                            evento.mostrarMensaje('.errorAltaPersonal', false, 'Campo Fecha Ingreso vacío', 3000);
                        }
                    } else {
                        evento.mostrarMensaje('.errorAltaPersonal', false, 'Campo Fecha Nacimiento vacío', 3000);
                    }
                }
            });
            $('#btnCancelarNuevoPersonal').on('click', function () {
                $('#formularioPersonal').empty().addClass('hidden');
                $('#resumenPersonal').removeClass('hidden');
            });
        });
    });
});


