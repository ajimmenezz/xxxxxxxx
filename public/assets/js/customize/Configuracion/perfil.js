$(function () {
    //Objetos
    var evento = new Base();
    var websocket = new Socket();
    var file = new Upload();
    var select = new Select();
    var calendario = new Fecha();
    var tabla = new Tabla();

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

    calendario.crearFecha('.calendario');

    tabla.generaTablaPersonal('#data-table-datos-academicos', null, null, true, true, [[0, 'desc']]);
    tabla.generaTablaPersonal('#data-table-datos-idiomas', null, null, true, true, [[0, 'desc']]);
    tabla.generaTablaPersonal('#data-table-datos-computacionales', null, null, true, true, [[0, 'desc']]);
    tabla.generaTablaPersonal('#data-table-datos-sistemas-especiales', null, null, true, true, [[0, 'desc']]);
    tabla.generaTablaPersonal('#data-table-datos-dependientes-economicos', null, null, true, true, [[0, 'desc']]);

    $("#selectActualizarPaisUsuario").on("change", function () {
        $("#selectActualizarEstadoUsuario").empty().append('<option value="">Seleccionar...</option>');
        select.cambiarOpcion("#selectActualizarEstadoUsuario", '');
        var pais = $(this).val();
        if (pais !== '') {
            var data = {IdPais: pais};
            evento.enviarEvento('PerfilUsuario/MostrarDatosEstados', data, '#seccion-informacion-usuario', function (respuesta) {
                $.each(respuesta, function (k, v) {
                    $("#selectActualizarEstadoUsuario").append('<option value="' + v.Id + '">' + v.Nombre + '</option>')
                });
                $("#selectActualizarEstadoUsuario").removeAttr("disabled");
            });
        } else {
            $("#selectActualizarEstadoUsuario").attr("disabled", "disabled");
        }
    });

    $("#selectActualizarEstadoUsuario").on("change", function () {
        $("#selectActualizarMunicipioUsuario").empty().append('<option value="">Seleccionar...</option>');
        select.cambiarOpcion("#selectActualizarMunicipioUsuario", '');
        var pais = $(this).val();
        if (pais !== '') {
            var data = {IdEstado: pais};
            evento.enviarEvento('PerfilUsuario/MostrarDatosMunicipio', data, '#seccion-informacion-usuario', function (respuesta) {
                $.each(respuesta, function (k, v) {
                    $("#selectActualizarMunicipioUsuario").append('<option value="' + v.Id + '">' + v.Nombre + '</option>')
                });
                $("#selectActualizarMunicipioUsuario").removeAttr("disabled");
            });
        } else {
            $("#selectActualizarMunicipioUsuario").attr("disabled", "disabled");
        }
    });

    $('.editarPerfil').off("click");
    $('.editarPerfil').on('click', function () {
        mostrarCargaPagina();
        var campo = $(this).attr('data-campo');
        var input = $(this).attr('data-input');
        var nombreInput = $(this).attr('data-nombreInput');
        var tabla = $(this).attr('data-tabla');
        var data = {'campo': campo, 'input': input, 'nombreInput': nombreInput};
        var validarInput = '';
        var validarExpresion = true;
        var mensajeError = '';

        evento.enviarEvento('PerfilUsuario/MostrarFormularioPerfilUsuario', data, '', function (respuesta) {
            evento.iniciarModal('#modalEdit', 'Editar Perfil Usuario', respuesta.modal);
            if (campo === 'IdSexo') {
                if (input === 'Femenino') {
                    $('#selectPerfilGenero').val('1').trigger('change');
                } else if (input === 'Masculino') {
                    $('#selectPerfilGenero').val('2').trigger('change');
                }
            }

            $('#btnGuardarCambios').off('click');
            $('#btnGuardarCambios').on('click', function () {
                if (campo === 'IdSexo') {
                    validarInput = $('#selectPerfilGenero').val();
                } else {
                    validarInput = $('#select' + campo).val();
                }

                switch (campo) {
                    case 'Email':
                        var expresion = /^[a-zA-Z0-9.!#$%&'*+\/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/;
                        validarExpresion = expresion.test(validarInput);
                        mensajeError = 'Incluye un "@" en tu dirección de correo electrónico.';
                        break;
                    case 'Tel1':
                        var expresion = /^([0-9]{13})$/;
                        validarExpresion = expresion.test(validarInput);
                        mensajeError = 'El formato del número que escribio es incorrecto.';
                        break;
                    case 'Tel2':
                        var expresion = /^([0-9]{12})$/;
                        validarExpresion = expresion.test(validarInput);
                        mensajeError = 'El formato del número que escribio es incorrecto.';
                        break;
                    case 'CP':
                        var expresion = /^([1-9]{2}|[0-9][1-9]|[1-9][0-9])[0-9]{3}$/;
                        validarExpresion = expresion.test(validarInput);
                        mensajeError = 'Deben ser solo números.';
                        break;
                    default:
                        expresion = /^$/;
                }

                if (validarInput !== '') {
                    if (validarExpresion) {
                        var datos = {'inputNuevo': validarInput, 'campo': campo, 'tabla': tabla};
                        evento.enviarEvento('PerfilUsuario/ActualizarPerfilUsuario', datos, '#modalEdit', function (resultado) {
                            if (resultado) {
                                recargarPagina();
                            } else {
                                evento.mostrarMensaje(".errorPerfilUsuario", false, "El campo " + nombreInput + " es el mismo que el anterior.", 4000);
                            }
                        });
                    } else {
                        evento.mostrarMensaje(".errorPerfilUsuario", false, mensajeError, 4000);
                    }
                } else {
                    evento.mostrarMensaje(".errorPerfilUsuario", false, "El campo " + nombreInput + " esta vacío.", 4000);
                }
            });

            cerrarModalCambios();
        });
    });

    $('#btnSubirFotoUsuario').off("click");
    $('#btnSubirFotoUsuario').on('click', function () {
        mostrarCargaPagina();
        evento.enviarEvento('PerfilUsuario/MostrarFormularioCambiarFoto', {}, '', function (respuesta) {
            evento.iniciarModal('#modalEdit', 'Editar Perfil Usuario', respuesta.modal);
            file.crearUpload('#fotoUsuario',
                    'PerfilUsuario/ActualizarFotoUsuario',
                    false,
                    false,
                    false,
                    false,
                    false,
                    false,
                    1);

            $('#btnGuardarCambios').off('click');
            $('#btnGuardarCambios').on('click', function () {
                var foto = $('#fotoUsuario').val();
                if (foto !== '') {
                    var datos = {};
                    file.enviarArchivos('#fotoUsuario', 'PerfilUsuario/ActualizarFotoUsuario', '#modalEdit', datos, function (resultado) {
                        recargarPagina();
                    });
                } else {
                    evento.mostrarMensaje("#errorFotoUsuario", false, "Favor de seleccionar una foto.", 4000);
                }
            });

            cerrarModalCambios();
        });
    });

    $('#btnActualizarContraseñaUsuario').off("click");
    $('#btnActualizarContraseñaUsuario').on('click', function () {
        mostrarCargaPagina();
        evento.enviarEvento('PerfilUsuario/MostrarFormularioActualizarPasswordUsuario', {}, '', function (respuesta) {
            evento.iniciarModal('#modalEdit', 'Editar Perfil Usuario', respuesta.modal);
            $('#btnGuardarCambios').off('click');
            $('#btnGuardarCambios').on('click', function () {
                var nuevo = $('#inputNuevoPsw').val();
                var confirmacion = $('#inputConfirmaNuevoPsw').val();
                if (nuevo !== '') {
                    if (confirmacion !== '') {
                        if (nuevo === confirmacion) {
                            var mensaje = validarPassword(nuevo);
                            if (mensaje === null) {
                                var data = {nuevo: nuevo, usuario: $('#usuario').val()};
                                evento.enviarEvento('/Acceso/Modificar_Password', data, '#modalEdit', function (respuesta) {
                                    if (respuesta) {
                                        recargarPagina();
                                    } else {
                                        evento.mostrarMensaje("#errorPasswordUsuario", false, 'La nueva contraseña es igual que la actual.', 5000);
                                    }
                                });
                            } else {
                                $('#inputNuevoPsw').val('');
                                $('#inputConfirmaNuevoPsw').val('');
                                evento.mostrarMensaje("#errorPasswordUsuario", false, mensaje, 5000);
                            }
                        } else {
                            $('#inputNuevoPsw').val('');
                            $('#inputConfirmaNuevoPsw').val('');
                            evento.mostrarMensaje("#errorPasswordUsuario", false, "Los contraseñas no coinciden.", 4000);
                        }
                    } else {
                        evento.mostrarMensaje("#errorPasswordUsuario", false, "El campo Confirmar Password esta vacío.", 4000);
                    }
                } else {
                    evento.mostrarMensaje("#errorPasswordUsuario", false, "El campo Nuevo Password esta vacío.", 4000);
                }
            });

            cerrarModalCambios();
        });
    });

    $('#inputToken').off("click");
    $('#inputToken').on('click', function () {
        mostrarCargaPagina();
        var data = {};
        evento.enviarEvento('PerfilUsuario/ActualizarTokenUsuario', data, '', function (respuesta) {
            if (respuesta) {
                recargarPagina();
            }
        });
    });

    $('#btnGuardarPersonalesUsuario').off("click");
    $('#btnGuardarPersonalesUsuario').on('click', function () {
        var fechaNacimiento = $('#inputFechaNacimiento').val();
        var pais = $('#selectActualizarPaisUsuario').val();
        var estado = $('#selectActualizarEstadoUsuario').val();
        var municipio = $('#selectActualizarMunicipioUsuario').val();
        var estadoCivil = $('#selectActualizarEstadoCivilUsuario').val();
        var nacionalidad = $('#inputActualizarNacionalidadUsuario').val();
        var telefonoParticular = $('#inputActualizarTelefonoParticularUsuario').val();
        var estatura = $('#inputActualizarEstaturaUsuario').val();
        var sexo = $('#selectActualizarSexoUsuario').val();
        var peso = $('#inputActualizarPesoUsuario').val();
        var tipoSangre = $('#inputActualizarTipoSangreUsuario').val();
        var tallaPantalon = $('#inputActualizarTallaPantalonUsuario').val();
        var tallaCamisa = $('#inputActualizarTallaCamisaUsuario').val();
        var tallaZapatos = $('#inputActualizarTallaZapatosUsuario').val();
        var curp = $('#inputActualizarCurpUsuario').val();
        var rfc = $('#inputActualizarRfcUsuario').val();
        var institutoAfore = $('#inputActualizarInstitutoAforeUsuario').val();
        var numeroAfore = $('#inputActualizarNumeroAforeUsuario').val();
        var nss = $('#inputActualizarNssUsuario').val();

        if (fechaNacimiento !== '') {
            var data = {
                fechaNacimiento: fechaNacimiento,
                pais: pais,
                estado: estado,
                municipio: municipio,
                estadoCivil: estadoCivil,
                nacionalidad: nacionalidad,
                telefonoParticular: telefonoParticular,
                estatura: estatura,
                sexo: sexo,
                peso: peso,
                tipoSangre: tipoSangre,
                tallaPantalon: tallaPantalon,
                tallaCamisa: tallaCamisa,
                tallaZapatos: tallaZapatos,
                curp: curp,
                rfc: rfc,
                institutoAfore: institutoAfore,
                numeroAfore: numeroAfore,
                nss: nss
            };
            evento.enviarEvento('PerfilUsuario/GuardarDatosPersonalesUsuario', data, '', function (respuesta) {
                console.log(respuesta);
            });
        } else {
            evento.mostrarMensaje("#errorGuardarPersonalesUsuario", false, "El campo Fecha de nacimiento esta vacío.", 4000);
        }

    });

    $('#btnGuardarAcademicosUsuario').off("click");
    $('#btnGuardarAcademicosUsuario').on('click', function () {
        console.log('pumas');
    });

    $('#btnGuardarIdiomasUsuario').off("click");
    $('#btnGuardarIdiomasUsuario').on('click', function () {
        console.log('pumas');
    });

    $('#btnGuardarComputacionalesUsuario').off("click");
    $('#btnGuardarComputacionalesUsuario').on('click', function () {
        console.log('pumas');
    });

    $('#btnGuardarEspecialesUsuario').off("click");
    $('#btnGuardarEspecialesUsuario').on('click', function () {
        console.log('pumas');
    });

    $('#btnGuardarAutomovilUsuario').off("click");
    $('#btnGuardarAutomovilUsuario').on('click', function () {
        console.log('pumas');
    });

    $('#btnGuardarDependientesUsuario').off("click");
    $('#btnGuardarDependientesUsuario').on('click', function () {
        console.log('pumas');
    });

    var cerrarModalCambios = function () {
        $('#btnCerrarCambios').off('click');
        $('#btnCerrarCambios').on('click', function () {
            evento.terminarModal('#modalEdit');
            $('#cargando').addClass('hidden');
            $('#configuracionPerfilUsuario').removeClass('hidden');
        });
    }

    var mostrarCargaPagina = function () {
        $('#cargando').removeClass('hidden');
        $('#configuracionPerfilUsuario').addClass('hidden');
    }

    var recargarPagina = function () {
        evento.terminarModal('#modalEdit');
        location.reload();
    }

    var validarPassword = function (password) {
        var expresiones = {
            '^(?=.*[A-Z])': 'Te falta agregar una mayuscula',
            '(?=.*[0-9])': 'Te falta agregar al menos un numero',
            '(?=.*[a-z])': 'Te falta agregar al menos una minuscula',
            '(.{8,15})$': 'La longitud minima es 8 y maxima es 15'
        }
        var mensaje = null;
        $.each(expresiones, function (key, value) {
            var expre = new RegExp(key);
            if (expre.test(password)) {
                expre = undefined;
            } else {
                mensaje = value;
                return false;
            }
        });
        return mensaje;
    };
});