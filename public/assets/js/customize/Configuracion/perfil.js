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

    var globals = {viewGlobals: true},
            newGlobals = [];

    calendario.crearFecha('.calendario');

    tabla.generaTablaPersonal('#data-table-datos-academicos', null, null, true, true, [[0, 'desc']]);
    tabla.generaTablaPersonal('#data-table-datos-idiomas', null, null, true, true, [[0, 'desc']]);
    tabla.generaTablaPersonal('#data-table-datos-computacionales', null, null, true, true, [[0, 'desc']]);
    tabla.generaTablaPersonal('#data-table-datos-sistemas-especiales', null, null, true, true, [[0, 'desc']]);
    tabla.generaTablaPersonal('#data-table-datos-dependientes-economicos', null, null, true, true, [[0, 'desc']]);

    $('#btn-show-alert').click(function () {
        evento.enviarEvento('PerfilUsuario/datosGuardadosPerfilUsuario', {}, '#seccion-informacion-usuario', function (respuesta) {
            console.log(respuesta);
            var estado = respuesta.datosUsuario.EstadoNac;
            var municipio = respuesta.datosUsuario.MunicipioNac;
            var nivelEstudio = respuesta.nivelEstudio;
            var documentosEstudio = respuesta.documentosEstudio;

            newGlobals.push(estado);
            newGlobals.push(municipio);
            newGlobals.push(nivelEstudio);
            newGlobals.push(documentosEstudio);

            select.cambiarOpcion('#selectActualizarPaisUsuario', respuesta.datosUsuario.PaisNac);
            select.cambiarOpcion('#selectActualizarEstadoCivilUsuario', respuesta.datosUsuario.IdEstadoCivil);
            select.cambiarOpcion('#selectActualizarSexoUsuario', respuesta.datosUsuario.IdSexo);
            select.cambiarOpcion('#selectActualizarDominaUsuario', respuesta.datosConduccion.Dominio);

            recargandoTablaAcademicos(respuesta.datosAcademicos);
            recargandoTablaIdiomas(respuesta.datosIdiomas);
            recargandoTablaSoftware(respuesta.datosSoftware);
            recargandoTablaSistemas(respuesta.datosSistemas);
            recargandoTablaDependientes(respuesta.datosDependientes);

            botonActualizarAcademico();
            botonActualizarIdioma();
        });
    });

    $('#btn-show-alert').trigger('click');

    $("#selectActualizarPaisUsuario").on("change", function () {
        $("#selectActualizarEstadoUsuario").empty().append('<option value="">Seleccionar...</option>');
        var pais = $(this).val();
        if (pais !== '') {
            var data = {IdPais: pais};
            evento.enviarEvento('PerfilUsuario/MostrarDatosEstados', data, '#seccion-informacion-usuario', function (respuesta) {
                $.each(respuesta, function (k, v) {
                    $("#selectActualizarEstadoUsuario").append('<option value="' + v.Id + '">' + v.Nombre + '</option>')
                });
                $("#selectActualizarEstadoUsuario").removeAttr("disabled");
                var variablesGlobales = viewGlobals();
                select.cambiarOpcion('#selectActualizarEstadoUsuario', variablesGlobales[0]);
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
                var variablesGlobales = viewGlobals();
                select.cambiarOpcion('#selectActualizarMunicipioUsuario', variablesGlobales[1]);
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
        var nivelEstudio = $('#selectActualizarNivelEstudioUsuario').val();
        var nombreInstituto = $('#selectActualizarNombreInstitutoUsuario').val();
        var documentoRecibido = $('#selectActualizarDocumentoRecibidoUsuario').val();
        var desde = $('#inputActualizarDesdeUsuario').val();
        var hasta = $('#inputActualizarHastaUsuario').val();

        var data = {
            nivelEstudio: nivelEstudio,
            nombreInstituto: nombreInstituto,
            documentoRecibido: documentoRecibido,
            desde: desde,
            hasta: hasta
        };

        var arrayCampos = [
            {'objeto': '#selectActualizarNivelEstudioUsuario', 'mensajeError': 'Falta seleccionar el campo Nivel de Estudio.'},
            {'objeto': '#selectActualizarNombreInstitutoUsuario', 'mensajeError': 'Falta seleccionarel campo Nombre de la Institución.'},
            {'objeto': '#selectActualizarDocumentoRecibidoUsuario', 'mensajeError': 'Falta seleccionar el campo DocumentoRecibido.'},
            {'objeto': '#inputActualizarDesdeUsuario', 'mensajeError': 'Falta seleccionar el campo Desde.'},
            {'objeto': '#inputActualizarHastaUsuario', 'mensajeError': 'Falta escribir el campo Hasta.'}
        ];

        var camposFormularioValidados = evento.validarCamposObjetos(arrayCampos, '#errorGuardarAcademicosUsuario');

        mostrarCargaPaginaInformacionUsuario('#academicos');

        if (camposFormularioValidados) {
            evento.enviarEvento('PerfilUsuario/GuardarDatosAcademicosUsuario', data, '#seccion-informacion-usuario', function (respuesta) {
                ocultarCargaPaginaInformacionUsuario('#academicos');
                if (respuesta instanceof Array || respuesta instanceof Object) {
                    select.cambiarOpcion('#selectActualizarNivelEstudioUsuario', '');
                    select.cambiarOpcion('#selectActualizarDocumentoRecibidoUsuario', '');
                    $('#selectActualizarNombreInstitutoUsuario').val('');
                    $('#inputActualizarDesdeUsuario').val('');
                    $('#inputActualizarHastaUsuario').val('');
                    recargandoTablaAcademicos(respuesta);
                    botonActualizarAcademico();
                } else {
                    evento.mostrarMensaje("#errorGuardarAcademicosUsuario", false, "Hubo un error contacte al administrador de AdIST", 4000);
                }
            });
        }
    });

    $('#btnGuardarIdiomasUsuario').off("click");
    $('#btnGuardarIdiomasUsuario').on('click', function () {
        var idioma = $('#selectActualizarIdiomaUsuario').val();
        var comprension = $('#selectActualizarComprensionUsuario').val();
        var lectura = $('#selectActualizarLecturaUsuario').val();
        var escritura = $('#selectActualizarEscrituraUsuario').val();
        var comentarios = $('#inputActualizarComantariosIdiomasUsuario').val();

        var data = {
            idioma: idioma,
            comprension: comprension,
            lectura: lectura,
            escritura: escritura,
            comentarios: comentarios
        };

        var arrayCampos = [
            {'objeto': '#selectActualizarIdiomaUsuario', 'mensajeError': 'Falta seleccionar el campo Idioma.'},
            {'objeto': '#selectActualizarComprensionUsuario', 'mensajeError': 'Falta seleccionarel campo Comprensión.'},
            {'objeto': '#selectActualizarLecturaUsuario', 'mensajeError': 'Falta seleccionar el campo Lectura.'},
            {'objeto': '#selectActualizarEscrituraUsuario', 'mensajeError': 'Falta seleccionar el campo Escritura.'}
        ];

        var camposFormularioValidados = evento.validarCamposObjetos(arrayCampos, '#errorGuardarIdiomasUsuario');

        mostrarCargaPaginaInformacionUsuario('#idiomas');

        if (camposFormularioValidados) {
            evento.enviarEvento('PerfilUsuario/GuardarDatosIdiomasUsuario', data, '', function (respuesta) {
                ocultarCargaPaginaInformacionUsuario('#idiomas');
                if (respuesta instanceof Array || respuesta instanceof Object) {
                    select.cambiarOpcion('#selectActualizarIdiomaUsuario', '');
                    select.cambiarOpcion('#selectActualizarComprensionUsuario', '');
                    select.cambiarOpcion('#selectActualizarLecturaUsuario', '');
                    select.cambiarOpcion('#selectActualizarEscrituraUsuario', '');
                    $('#inputActualizarComantariosIdiomasUsuario').val('');
                    recargandoTablaIdiomas(respuesta);
                } else {
                    evento.mostrarMensaje("#errorGuardarIdiomasUsuario", false, "Hubo un error contacte al administrador de AdIST", 4000);
                }
            });
        }
    });

    $('#btnGuardarComputacionalesUsuario').off("click");
    $('#btnGuardarComputacionalesUsuario').on('click', function () {
        var software = $('#selectActualizarSoftwareUsuario').val();
        var nivel = $('#selectActualizarNivelComputacionalesUsuario').val();
        var comentarios = $('#inputActualizarComentariosComputacionalesUsuario').val();

        var data = {
            software: software,
            nivel: nivel,
            comentarios: comentarios
        };

        var arrayCampos = [
            {'objeto': '#selectActualizarSoftwareUsuario', 'mensajeError': 'Falta seleccionar el campo Software.'},
            {'objeto': '#selectActualizarNivelComputacionalesUsuario', 'mensajeError': 'Falta seleccionarel campo Nivel.'}
        ];

        var camposFormularioValidados = evento.validarCamposObjetos(arrayCampos, '#errorGuardarComputacionalesUsuario');

        mostrarCargaPaginaInformacionUsuario('#computacionales');

        if (camposFormularioValidados) {
            evento.enviarEvento('PerfilUsuario/GuardarDatosComputacionalesUsuario', data, '', function (respuesta) {
                ocultarCargaPaginaInformacionUsuario('#computacionales');
                if (respuesta instanceof Array || respuesta instanceof Object) {
                    select.cambiarOpcion('#selectActualizarSoftwareUsuario', '');
                    select.cambiarOpcion('#selectActualizarNivelComputacionalesUsuario', '');
                    $('#inputActualizarComentariosComputacionalesUsuario').val('');
                    recargandoTablaSoftware(respuesta);
                } else {
                    evento.mostrarMensaje("#errorGuardarComputacionalesUsuario", false, "Hubo un error contacte al administrador de AdIST", 4000);
                }
            });
        }
    });

    $('#btnGuardarEspecialesUsuario').off("click");
    $('#btnGuardarEspecialesUsuario').on('click', function () {
        var sistema = $('#selectActualizarSistemasUsuario').val();
        var nivel = $('#selectActualizarNivelSistemasUsuario').val();
        var comentarios = $('#inputActualizarComnetariosSistemasUsuario').val();

        var data = {
            sistema: sistema,
            nivel: nivel,
            comentarios: comentarios
        };

        var arrayCampos = [
            {'objeto': '#selectActualizarSistemasUsuario', 'mensajeError': 'Falta seleccionar el campo Sistema.'},
            {'objeto': '#selectActualizarNivelSistemasUsuario', 'mensajeError': 'Falta seleccionarel campo Nivel.'}
        ];

        var camposFormularioValidados = evento.validarCamposObjetos(arrayCampos, '#errorGuardarEspecialesUsuario');

        mostrarCargaPaginaInformacionUsuario('#sistemasEspeciales');

        if (camposFormularioValidados) {
            evento.enviarEvento('PerfilUsuario/GuardarDatosSistemasEspecialesUsuario', data, '', function (respuesta) {
                ocultarCargaPaginaInformacionUsuario('#sistemasEspeciales');
                if (respuesta instanceof Array || respuesta instanceof Object) {
                    select.cambiarOpcion('#selectActualizarSistemasUsuario', '');
                    select.cambiarOpcion('#selectActualizarNivelSistemasUsuario', '');
                    $('#inputActualizarComnetariosSistemasUsuario').val('');
                    recargandoTablaSistemas(respuesta);
                } else {
                    evento.mostrarMensaje("#errorGuardarEspecialesUsuario", false, "Hubo un error contacte al administrador de AdIST", 4000);
                }
            });
        }
    });

    $('#btnGuardarAutomovilUsuario').off("click");
    $('#btnGuardarAutomovilUsuario').on('click', function () {
        var dominio = $('#selectActualizarDominaUsuario').val();
        var antiguedad = $('#selectActualizarAntiguedadUsuario').val();
        var tipoLicencia = $('#inputActualizarTipoLicenciaUsuario').val();
        var vigenciaTipoLicencia = $('#selectActualizarTipoVigenciaUsuario').val();
        var numeroLicencia = $('#inputActualizarNumeroLicenciaUsuario').val();
        var vigenciaNumeroLicencia = $('#selectActualizarNumeroVigenciaUsuario').val();

        var validacion = false;
        var arrayValidacion = [];
        var mensajeErrorArray = 'Falta llenar algún campo.';

        arrayValidacion[0] = [dominio, antiguedad, 'Los campos ¿Sabe conducir? y Antiguedad no pueden estar vacios.'];
        arrayValidacion[1] = [tipoLicencia, vigenciaTipoLicencia, 'Los campos Tipo de Licencia y Vigencia no pueden estar vacios.'];
        arrayValidacion[2] = [numeroLicencia, vigenciaNumeroLicencia, 'Los campos Número de Licencia y Vigencia no pueden estar vacios.'];

        $.each(arrayValidacion, function (k, v) {
            if (v[0] !== '' || v[1] !== '') {
                validacion = validarCamposDatosAutomovil(v[0], v[1]);
                if (validacion === false) {
                    mensajeErrorArray = v[2];
                }
            }
        });

        if (validacion) {
            var data = {
                dominio: dominio,
                antiguedad: antiguedad,
                tipoLicencia: tipoLicencia,
                vigenciaTipoLicencia: vigenciaTipoLicencia,
                numeroLicencia: numeroLicencia,
                vigenciaNumeroLicencia: vigenciaNumeroLicencia,
            };
            evento.enviarEvento('PerfilUsuario/GuardarDatosAutomovilUsuario', data, '', function (respuesta) {
                console.log(respuesta);
            });
        } else {
            evento.mostrarMensaje("#errorGuardarAutomovilUsuario", false, mensajeErrorArray, 4000);
        }

    });

    $('#btnGuardarDependientesUsuario').off("click");
    $('#btnGuardarDependientesUsuario').on('click', function () {
        var nombre = $('#inputActualizarNombreDependienteUsuario').val();
        var parentesco = $('#inputActualizarParentescoUsuario').val();
        var vigencia = $('#inputActualizarParentescoVigenciaUsuario').val();

        var data = {
            nombre: nombre,
            parentesco: parentesco,
            vigencia: vigencia
        };

        var arrayCampos = [
            {'objeto': '#inputActualizarNombreDependienteUsuario', 'mensajeError': 'Falta seleccionar el campo Nombre.'},
            {'objeto': '#inputActualizarParentescoUsuario', 'mensajeError': 'Falta seleccionarel campo Parentesco.'},
            {'objeto': '#inputActualizarParentescoVigenciaUsuario', 'mensajeError': 'Falta seleccionarel campo Vigencia.'}
        ];

        var camposFormularioValidados = evento.validarCamposObjetos(arrayCampos, '#errorGuardarDependientesUsuario');

        mostrarCargaPaginaInformacionUsuario('#dependientesEconomicos');

        if (camposFormularioValidados) {
            evento.enviarEvento('PerfilUsuario/GuardarDatosDependientesEconomicosUsuario', data, '', function (respuesta) {
                ocultarCargaPaginaInformacionUsuario('#dependientesEconomicos');
                if (respuesta instanceof Array || respuesta instanceof Object) {
                    $('#inputActualizarNombreDependienteUsuario').val('');
                    $('#inputActualizarParentescoUsuario').val('');
                    $('#inputActualizarParentescoVigenciaUsuario').val('');
                    recargandoTablaDependientes(respuesta);
                } else {
                    evento.mostrarMensaje("#errorGuardarDependientesUsuario", false, "Hubo un error contacte al administrador de AdIST", 4000);
                }
            });
        }
    });

    var botonActualizarAcademico = function () {
        $('.btn-actualizar-academico').off("click");
        $('.btn-actualizar-academico').on('click', function () {
            var idAcademico = $(this).data('id-academico');
            var variablesGlobales = viewGlobals();
            actualizarDatosAcademicos(idAcademico, variablesGlobales[2], variablesGlobales[3]);
        });
    }
    
    var botonActualizarIdioma = function () {
        $('.btn-actualizar-idioma').off("click");
        $('.btn-actualizar-idioma').on('click', function () {
            var idIdioma = $(this).data('id-idioma');
            var variablesGlobales = viewGlobals();
            console.log(idIdioma);
            actualizarDatosIdiomas(idIdioma, variablesGlobales[2], variablesGlobales[3]);
        });
    }

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

    var mostrarCargaPaginaInformacionUsuario = function (objeto) {
        $('#cargandoInformacionUsuario').removeClass('hidden');
        $(objeto).addClass('hidden');
    }

    var ocultarCargaPaginaInformacionUsuario = function (objeto) {
        $('#cargandoInformacionUsuario').addClass('hidden');
        $(objeto).removeClass('hidden');
    }

    var recargandoTablaAcademicos = function (datosAcademicos) {
        tabla.limpiarTabla('#data-table-datos-academicos');
        $.each(datosAcademicos, function (key, item) {
            var botones = '<a href="javascript:;" class="btn btn-success btn-xs btn-actualizar-academico" data-id-academico="' + item.Id + '"><i class="fa fa-pencil"></i> Actualizar</a> <a onclick="eventoEliminarProblemaEquipo();" class="btn btn-danger btn-xs "><i class="fa fa-trash-o"></i> Eliminar</a>';
            tabla.agregarFila('#data-table-datos-academicos', [item.Id, item.NivelEstudio, item.Institucion, item.Desde, item.Hasta, item.Documento, botones, item.IdNivelEstudio, item.IdDocumento]);
        });
    };

    var recargandoTablaIdiomas = function (datosIdiomas) {
        tabla.limpiarTabla('#data-table-datos-idiomas');
        $.each(datosIdiomas, function (key, item) {
            var botones = '<a href="javascript:;" class="btn btn-success btn-xs btn-actualizar-idioma" data-id-idioma="' + item.Id + '"><i class="fa fa-pencil"></i> Actualizar</a> <a onclick="eventoEliminarProblemaEquipo();" class="btn btn-danger btn-xs "><i class="fa fa-trash-o"></i> Eliminar</a>';
            tabla.agregarFila('#data-table-datos-idiomas', [item.Id, item.NombreIdioma, item.NivelComprension, item.NivelLectura, item.NivelEscritura, item.Comentarios, botones, item.Idioma, item.Comprension, item.Lectura, item.Escritura]);
        });
    };

    var recargandoTablaSoftware = function (datosIdiomas) {
        tabla.limpiarTabla('#data-table-datos-computacionales');
        $.each(datosIdiomas, function (key, item) {
            tabla.agregarFila('#data-table-datos-computacionales', [item.Id, item.Software, item.Nivel, item.Comentarios]);
        });
    };

    var recargandoTablaSistemas = function (datosIdiomas) {
        tabla.limpiarTabla('#data-table-datos-sistemas-especiales');
        $.each(datosIdiomas, function (key, item) {
            tabla.agregarFila('#data-table-datos-sistemas-especiales', [item.Id, item.Sistema, item.Nivel, item.Comentarios]);
        });
    };

    var recargandoTablaDependientes = function (datosIdiomas) {
        tabla.limpiarTabla('#data-table-datos-dependientes-economicos');
        $.each(datosIdiomas, function (key, item) {
            tabla.agregarFila('#data-table-datos-dependientes-economicos', [item.Id, item.Nombre, item.Parentesco, item.FechaNacimiento]);
        });
    };

    var actualizarDatosAcademicos = function () {
        var idAcademico = arguments[0];
        var catalogoNivelEstudio = arguments[1];
        var catalogoDocumentoEstudio = arguments[2];

        var anteriorNivelEstudio = '';
        var anteriorInstitucion = '';
        var anteriorDocumentoRecibido = '';
        var anteriorDesde = '';
        var anteriorHasta = '';
        var tablaDatosAcademicos = $('#data-table-datos-academicos').DataTable().data();

        $.each(tablaDatosAcademicos, function (key, valor) {
            if (idAcademico == valor[0]) {
                anteriorNivelEstudio = valor[7];
                anteriorInstitucion = valor[2];
                anteriorDocumentoRecibido = valor[8];
                anteriorDesde = valor[3];
                anteriorHasta = valor[4];
            }
        });

        var html = '<div class="row">\n\
                        <div class="col-md-4">\n\
                            <label for="actualizarNivelEstudioUsuario">Nivel de estudio *</label>\n\
                            <select id="actualizarNivelEstudioUsuario" class="form-control" style="width: 100%" data-parsley-required="true">\n\
                                <option value="">Seleccionar...</option>\n\
                            </select>\n\
                        </div>\n\
                        <div class="col-md-4">\n\
                            <label for="actualizarNombreInstitutoUsuario">Nombre de la institución *</label>\n\
                            <input type="tel" class="form-control" id="actualizarNombreInstitutoUsuario" style="width: 100%"/>\n\
                        </div>\n\
                        <div class="col-md-4">\n\
                            <label for="actualizarDocumentoRecibidoUsuario">Documento recibido *</label>\n\
                            <select id="actualizarDocumentoRecibidoUsuario" class="form-control" style="width: 100%" data-parsley-required="true">\n\
                                <option value="">Seleccionar...</option>\n\
                            </select>\n\
                        </div>\n\
                    </div>\n\
                    <div class="row m-t-10">\n\
                        <div class="col-md-6">\n\
                            <label for="actualizarDesdeUsuario">Desde *</label>\n\
                            <div id="inputFecha" class="input-group date calendario" >\n\
                                <input id="actualizarDesdeUsuario" type="text" class="form-control"/>\n\
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>\n\
                            </div>\n\
                        </div>\n\
                        <div class="col-md-6">\n\
                            <label for="actualizarHastaUsuario">Hasta *</label>\n\
                            <div id="inputFecha" class="input-group date calendario" >\n\
                                <input id="actualizarHastaUsuario" type="text" class="form-control"/>\n\
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>\n\
                            </div>\n\
                        </div>\n\
                    </div>\n\
                    <div class="row m-t-10">\n\
                        <div class="col-md-12">\n\
                            <div id="errorAcademicosUsuario"></div>\n\
                        </div>\n\
                    </div>';
        evento.iniciarModal('#modalEdit', 'Editar Nivel Estudio', html);

        $.each(catalogoNivelEstudio, function (key, valor) {
            $("#actualizarNivelEstudioUsuario").append('<option value=' + valor.Id + '>' + valor.Nombre + '</option>');
        });

        $.each(catalogoDocumentoEstudio, function (key, valor) {
            $("#actualizarDocumentoRecibidoUsuario").append('<option value=' + valor.Id + '>' + valor.Nombre + '</option>');
        });

        select.crearSelect('#actualizarNivelEstudioUsuario');
        select.crearSelect('#actualizarDocumentoRecibidoUsuario');

        select.cambiarOpcion('#actualizarNivelEstudioUsuario', anteriorNivelEstudio);
        select.cambiarOpcion('#actualizarDocumentoRecibidoUsuario', anteriorDocumentoRecibido);
        $('#actualizarNombreInstitutoUsuario').val(anteriorInstitucion);
        $('#actualizarDesdeUsuario').val(anteriorDesde);
        $('#actualizarHastaUsuario').val(anteriorHasta);

        $('#btnGuardarCambios').off('click');
        $('#btnGuardarCambios').on('click', function () {
            var nivelEstudio = $('#actualizarNivelEstudioUsuario').val();
            var nombreInstituto = $('#actualizarNombreInstitutoUsuario').val();
            var documentoRecibido = $('#actualizarDocumentoRecibidoUsuario').val();
            var desde = $('#actualizarDesdeUsuario').val();
            var hasta = $('#actualizarHastaUsuario').val();

            var data = {
                id: idAcademico,
                nivelEstudio: nivelEstudio,
                institucion: nombreInstituto,
                documento: documentoRecibido,
                desde: desde,
                hasta: hasta
            };

            var arrayCampos = [
                {'objeto': '#actualizarNivelEstudioUsuario', 'mensajeError': 'Falta seleccionar el campo Nivel de Estudio.'},
                {'objeto': '#actualizarNombreInstitutoUsuario', 'mensajeError': 'Falta seleccionarel campo Nombre de la Institución.'},
                {'objeto': '#actualizarDocumentoRecibidoUsuario', 'mensajeError': 'Falta seleccionar el campo DocumentoRecibido.'},
                {'objeto': '#actualizarDesdeUsuario', 'mensajeError': 'Falta seleccionar el campo Desde.'},
                {'objeto': '#actualizarHastaUsuario', 'mensajeError': 'Falta escribir el campo Hasta.'}
            ];

            var camposFormularioValidados = evento.validarCamposObjetos(arrayCampos, '#errorAcademicosUsuario');

            mostrarCargaPaginaInformacionUsuario('#academicos');

            if (camposFormularioValidados) {
                evento.enviarEvento('PerfilUsuario/ActualizarDatosAcademicosUsuario', data, '#modalEdit', function (respuesta) {
                    ocultarCargaPaginaInformacionUsuario('#academicos');
                    if (respuesta instanceof Array || respuesta instanceof Object) {
                        recargandoTablaAcademicos(respuesta);
                        evento.terminarModal('#modalEdit');
                        botonActualizarAcademico();
                    } else {
                        evento.mostrarMensaje("#errorAcademicosUsuario", false, "Hubo un error contacte al administrador de AdIST.", 4000);
                    }
                });
            }
        });

        cerrarModalCambios();
    }
    
    var actualizarDatosIdiomas = function () {
        var idAcademico = arguments[0];
        var catalogoNivelEstudio = arguments[1];
        var catalogoDocumentoEstudio = arguments[2];

        var anteriorNivelEstudio = '';
        var anteriorInstitucion = '';
        var anteriorDocumentoRecibido = '';
        var anteriorDesde = '';
        var anteriorHasta = '';
        var tablaDatosAcademicos = $('#data-table-datos-academicos').DataTable().data();

        $.each(tablaDatosAcademicos, function (key, valor) {
            if (idAcademico == valor[0]) {
                anteriorNivelEstudio = valor[7];
                anteriorInstitucion = valor[2];
                anteriorDocumentoRecibido = valor[8];
                anteriorDesde = valor[3];
                anteriorHasta = valor[4];
            }
        });

        var html = '<div class="row">\n\
                        <div class="col-md-6">\n\
                            <label for="selectActualizarIdiomaUsuario">Idioma *</label>\n\
                            <select id="selectActualizarIdiomaUsuario" class="form-control" style="width: 100%" data-parsley-required="true">\n\
                                <option value="">Seleccionar...</option>\n\
                            </select>\n\
                        </div>\n\
                        <div class="col-md-6">\n\
                            <label for="selectActualizarComprensionUsuario">Comprensión *</label>\n\
                            <select id="selectActualizarComprensionUsuario" class="form-control" style="width: 100%" data-parsley-required="true">\n\
                                <option value="">Seleccionar...</option>\n\
                            </select>\n\
                        </div>\n\
                    </div>\n\
                    <div class="row m-t-10">\n\
                        <div class="col-md-6">\n\
                            <label for="selectActualizarLecturaUsuario">Lectura *</label>\n\
                            <select id="selectActualizarLecturaUsuario" class="form-control" style="width: 100%" data-parsley-required="true">\n\
                                <option value="">Seleccionar...</option>\n\
                            </select>\n\
                        </div>\n\
                        <div class="col-md-6">\n\
                            <label for="selectActualizarEscrituraUsuario">Escritura *</label>\n\
                            <select id="selectActualizarEscrituraUsuario" class="form-control" style="width: 100%" data-parsley-required="true">\n\
                                <option value="">Seleccionar...</option>\n\
                            </select>\n\
                        </div>\n\
                    </div>\n\
                    <div class="row m-t-10">\n\
                        <div class="col-md-12">\n\
                            <label for="inputActualizarComantariosIdiomasUsuario">Comentarios</label>\n\
                            <textarea id="inputActualizarComantariosIdiomasUsuario" class="form-control entregaGarantia" placeholder="Ingrese los comentarios" rows="3" ></textarea>\n\
                        </div>\n\
                    </div>';
        evento.iniciarModal('#modalEdit', 'Editar Idioma', html);

//        $.each(catalogoNivelEstudio, function (key, valor) {
//            $("#actualizarNivelEstudioUsuario").append('<option value=' + valor.Id + '>' + valor.Nombre + '</option>');
//        });
//
//        $.each(catalogoDocumentoEstudio, function (key, valor) {
//            $("#actualizarDocumentoRecibidoUsuario").append('<option value=' + valor.Id + '>' + valor.Nombre + '</option>');
//        });
//
//        select.crearSelect('#actualizarNivelEstudioUsuario');
//        select.crearSelect('#actualizarDocumentoRecibidoUsuario');
//
//        select.cambiarOpcion('#actualizarNivelEstudioUsuario', anteriorNivelEstudio);
//        select.cambiarOpcion('#actualizarDocumentoRecibidoUsuario', anteriorDocumentoRecibido);
//        $('#actualizarNombreInstitutoUsuario').val(anteriorInstitucion);
//        $('#actualizarDesdeUsuario').val(anteriorDesde);
//        $('#actualizarHastaUsuario').val(anteriorHasta);

//        $('#btnGuardarCambios').off('click');
//        $('#btnGuardarCambios').on('click', function () {
//            var nivelEstudio = $('#actualizarNivelEstudioUsuario').val();
//            var nombreInstituto = $('#actualizarNombreInstitutoUsuario').val();
//            var documentoRecibido = $('#actualizarDocumentoRecibidoUsuario').val();
//            var desde = $('#actualizarDesdeUsuario').val();
//            var hasta = $('#actualizarHastaUsuario').val();
//
//            var data = {
//                id: idAcademico,
//                nivelEstudio: nivelEstudio,
//                institucion: nombreInstituto,
//                documento: documentoRecibido,
//                desde: desde,
//                hasta: hasta
//            };
//
//            var arrayCampos = [
//                {'objeto': '#actualizarNivelEstudioUsuario', 'mensajeError': 'Falta seleccionar el campo Nivel de Estudio.'},
//                {'objeto': '#actualizarNombreInstitutoUsuario', 'mensajeError': 'Falta seleccionarel campo Nombre de la Institución.'},
//                {'objeto': '#actualizarDocumentoRecibidoUsuario', 'mensajeError': 'Falta seleccionar el campo DocumentoRecibido.'},
//                {'objeto': '#actualizarDesdeUsuario', 'mensajeError': 'Falta seleccionar el campo Desde.'},
//                {'objeto': '#actualizarHastaUsuario', 'mensajeError': 'Falta escribir el campo Hasta.'}
//            ];
//
//            var camposFormularioValidados = evento.validarCamposObjetos(arrayCampos, '#errorAcademicosUsuario');
//
//            mostrarCargaPaginaInformacionUsuario('#academicos');
//
//            if (camposFormularioValidados) {
//                evento.enviarEvento('PerfilUsuario/ActualizarDatosAcademicosUsuario', data, '#modalEdit', function (respuesta) {
//                    ocultarCargaPaginaInformacionUsuario('#academicos');
//                    if (respuesta instanceof Array || respuesta instanceof Object) {
//                        recargandoTablaAcademicos(respuesta);
//                        evento.terminarModal('#modalEdit');
//                        botonActualizarAcademico();
//                    } else {
//                        evento.mostrarMensaje("#errorAcademicosUsuario", false, "Hubo un error contacte al administrador de AdIST.", 4000);
//                    }
//                });
//            }
//        });

        cerrarModalCambios();
    }

    var recargarPagina = function () {
        evento.terminarModal('#modalEdit');
        location.reload();
    }

    var validarCamposDatosAutomovil = function () {
        var primerCampo = arguments[0];
        var segundoCampo = arguments[1];
        var validacion = false;

        if (primerCampo !== '' && segundoCampo !== '') {
            validacion = true
        }

        return validacion;

    }

    var viewGlobals = function () {
        return newGlobals;
    };

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
