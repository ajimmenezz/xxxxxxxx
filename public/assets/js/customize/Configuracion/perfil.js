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
    var dataFirma = null;

    calendario.crearFecha('.calendario');

    tabla.generaTablaPersonal('#data-table-datos-academicos', null, null, true, true, [[0, 'desc']]);
    tabla.generaTablaPersonal('#data-table-datos-idiomas', null, null, true, true, [[0, 'desc']]);
    tabla.generaTablaPersonal('#data-table-datos-computacionales', null, null, true, true, [[0, 'desc']]);
    tabla.generaTablaPersonal('#data-table-datos-sistemas-especiales', null, null, true, true, [[0, 'desc']]);
    tabla.generaTablaPersonal('#data-table-datos-dependientes-economicos', null, null, true, true, [[0, 'desc']]);

    $('#btn-show-alert').click(function () {
        evento.enviarEvento('PerfilUsuario/datosGuardadosPerfilUsuario', {}, '#seccion-informacion-usuario', function (respuesta) {
            var estado = respuesta.datosUsuario.EstadoNac;
            var municipio = respuesta.datosUsuario.MunicipioNac;
            var nivelEstudio = respuesta.nivelEstudio;
            var documentosEstudio = respuesta.documentosEstudio;
            var habilidadesIdioma = respuesta.habilidadesIdioma;
            var nivelHabilidades = respuesta.nivelHabilidades;
            var habilidadesSoftware = respuesta.habilidadesSoftware;
            var habilidadesSistema = respuesta.habilidadesSistema;

            newGlobals.push(estado);
            newGlobals.push(municipio);
            newGlobals.push(nivelEstudio);
            newGlobals.push(documentosEstudio);
            newGlobals.push(habilidadesIdioma);
            newGlobals.push(nivelHabilidades);
            newGlobals.push(habilidadesSoftware);
            newGlobals.push(habilidadesSistema);

            select.cambiarOpcion('#selectActualizarPaisUsuario', respuesta.datosUsuario.PaisNac);
            select.cambiarOpcion('#selectActualizarEstadoCivilUsuario', respuesta.datosUsuario.IdEstadoCivil);
            select.cambiarOpcion('#selectActualizarSexoUsuario', respuesta.datosUsuario.IdSexo);
            select.cambiarOpcion('#selectActualizarDominaUsuario', respuesta.datosConduccion.Dominio);
            select.cambiarOpcion('#inputActualizarTallaCamisaUsuario', respuesta.datosUsuario.TallaCamisa);
            select.cambiarOpcion('#inputActualizarTallaZapatosUsuario', respuesta.datosUsuario.TallaZapatos);
            select.cambiarOpcion('#inputActualizarTallaPantalonUsuario', respuesta.datosUsuario.TallaPantalon);
            $('input[type=radio][name=radioPersonas][value="' + respuesta.datosCovid[0].ViveConMayores + '"]').attr("checked", "checked");
            $('input[type=radio][name=radioCancer][value="' + respuesta.datosCovid[0].TratamientoCancer + '"]').attr("checked", "checked");
            $('input[type=radio][name=radioFumador][value="' + respuesta.datosCovid[0].Fumador + '"]').attr("checked", "checked");
            $('input[type=radio][name=radioTransplante][value="' + respuesta.datosCovid[0].Transplantes + '"]').attr("checked", "checked");

            recargandoTablaAcademicos(respuesta.datosAcademicos);
            recargandoTablaIdiomas(respuesta.datosIdiomas);
            recargandoTablaSoftware(respuesta.datosSoftware);
            recargandoTablaSistemas(respuesta.datosSistemas);
            recargandoTablaDependientes(respuesta.datosDependientes);

            botonActualizarAcademico();
            botonActualizarIdioma();
            botonActualizarSoftware();
            botonActualizarSistema();
            botonActualizarDependiente();
            botonEliminarAcademico();
            botonEliminarIdioma();
            botonEliminarSoftware();
            botonEliminarSistema();
            botonEliminarDependiente();

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
                    case 'SDKey':
                        var expresion = /^([a-z]+[0-9]+)|([0-9]+[a-z]+)/i;
                        validarExpresion = expresion.test(validarInput);
                        mensajeError = 'Deben ser números y letras.';
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

    let firmaUsuario = new DrawingBoard.Board("firmaUsuario", {
        background: "#fff",
        color: "#000",
        size: 1,
        controlsPosition: "right",
        controls: [
            {
                Navigation: {
                    back: false,
                    forward: false
                }
            }
        ],
        webStorage: false
    });
    $('.editarFirma').on('click', function () {
        dataFirma = {
            'campo': $(this).attr('data-campo'),
            'input': $(this).attr('data-input')
        };

        if (dataFirma.input != '') {
            $('#firmaExistente').removeClass('hidden');
            $('#checkOtro').removeClass('hidden');
            $('#imagenFirmaUsuario').append('<img src ="' + dataFirma.input + '" />');
            $('#btnAceptarModal').addClass('hidden');
        } else {
            $('#firmaExistente').addClass('hidden');
            $('#checkOtro').addClass('hidden');
            $('#contentfirmaUsuario').removeClass('hidden');
            $('#btnAceptarModal').removeClass('hidden');
        }
        $('#modalDefinirFirma').modal('toggle');
    });
    $('input[type="checkbox"]').click(function () {
        if ($(this).prop("checked") === true) {
            $('#firmaExistente').addClass('hidden');
            $('#contentfirmaUsuario').removeClass('hidden');
            $('#btnAceptarModal').removeClass('hidden');
        } else {
            $('#firmaExistente').removeClass('hidden');
            $('#contentfirmaUsuario').addClass('hidden');
            $('#btnAceptarModal').addClass('hidden');
        }
    });
    $('#btnAceptarModal').on("click", function () {
        let imgFirmaUsuario = firmaUsuario.getImg();
        let inputFirmaUsuario = (firmaUsuario.blankCanvas == imgFirmaUsuario) ? '' : imgFirmaUsuario;

        if (inputFirmaUsuario == '') {
            evento.mostrarMensaje("#errorMessageFirma", false, 'Falta la firma', 2000);
        } else {
            dataFirma.firmaUsuario = firmaUsuario.getImg();
            evento.enviarEvento('PerfilUsuario/ActualizarFirmaUsuario', dataFirma, '#modalDefinirFirma', function (respuesta) {
                if (respuesta) {
                    evento.mostrarMensaje("#errorMessageFirma", true, 'Cambio realizado', 2000);
                    location.reload();
                }
            });
        }
    });
    $('#btnCancelarModal').on("click", function () {
        $('#imagenFirmaUsuario').html('');
        $('#contentfirmaUsuario').addClass('hidden');
        $('#btnAceptarModal').addClass('hidden');
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

    $('#inputSD').off("click");
    $('#inputSD').on('click', function () {
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

        mostrarCargaPaginaInformacionUsuario('#personales');

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
                if (respuesta) {
                    mensajeModal('Se guardo correctamente.', 'Correcto', '#personales');
                } else {
                    mensajeModal('No hay ningún campo modificado.', 'Advertencia', '#personales');
                }
            });
        } else {
            mensajeModal('El campo Fecha de nacimiento esta vacío.', 'Advertencia', '#personales');
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
                    botonEliminarAcademico();
                } else {
                    mensajeModal('Hubo un error contacte al administrador de AdIST.', 'Error', '#academicos');
                }
            });
        } else {
            ocultarCargaPaginaInformacionUsuario('#academicos');
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
                    botonActualizarIdioma();
                    botonEliminarIdioma();
                } else {
                    evento.mostrarMensaje("#errorGuardarIdiomasUsuario", false, "Hubo un error contacte al administrador de AdIST", 4000);
                }
            });
        } else {
            ocultarCargaPaginaInformacionUsuario('#idiomas');
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
                    botonActualizarSoftare();
                    botonEliminarSoftware();
                } else {
                    evento.mostrarMensaje("#errorGuardarComputacionalesUsuario", false, "Hubo un error contacte al administrador de AdIST", 4000);
                }
            });
        } else {
            ocultarCargaPaginaInformacionUsuario('#computacionales');
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
                    botonActualizarSistema();
                    botonEliminarSistema();
                } else {
                    evento.mostrarMensaje("#errorGuardarEspecialesUsuario", false, "Hubo un error contacte al administrador de AdIST", 4000);
                }
            });
        } else {
            ocultarCargaPaginaInformacionUsuario('#sistemasEspeciales');
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

        mostrarCargaPaginaInformacionUsuario('#automovil');

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
                ocultarCargaPaginaInformacionUsuario('#automovil');
                if (respuesta) {
                    evento.mostrarMensaje("#errorGuardarAutomovilUsuario", true, 'Se guardo correctamente.', 4000);
                } else {
                    evento.mostrarMensaje("#errorGuardarAutomovilUsuario", false, 'No hay ningún campo modificado.', 4000);
                }
            });
        } else {
            ocultarCargaPaginaInformacionUsuario('#automovil');
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
                    botonActualizarDependiente();
                    botonEliminarDependiente();
                } else {
                    evento.mostrarMensaje("#errorGuardarDependientesUsuario", false, "Hubo un error contacte al administrador de AdIST", 4000);
                }
            });
        } else {
            ocultarCargaPaginaInformacionUsuario('#dependientesEconomicos');
        }
    });
    
    $('#btnGuardarInfoSalud').on('click', function () {
        if(evento.validarFormulario('#formSalud')){
            let envioData = {
                ViveConMayores: $('input[name="radioPersonas"]:checked').val(),
                TratamientoCancer: $('input[name="radioCancer"]:checked').val(),
                Fumador: $('input[name="radioFumador"]:checked').val(),
                Transplantes: $('input[name="radioTransplante"]:checked').val(),
                Diagnostico: $('#selectDiagnostico').val()
            }
            evento.enviarEvento('PerfilUsuario/GuardarDatosCovid', envioData, '#cargandoInformacionUsuario', function (respuesta) {
                if (respuesta.code == 200) {
                    evento.mostrarMensaje('.errorGuardarInfoSalud', true, respuesta.message, 3000);
                } else {
                    evento.mostrarMensaje('.errorGuardarInfoSalud', false, respuesta.message, 3000);
                }
            });
        }
    });

    var botonActualizarAcademico = function () {
        $('#data-table-datos-academicos tbody').off('click', '.btn-actualizar-academico');
        $('#data-table-datos-academicos tbody').on('click', '.btn-actualizar-academico', function (e) {
            var idAcademico = $(this).data('id-academico');
            var variablesGlobales = viewGlobals();
            actualizarDatosAcademicos(idAcademico, variablesGlobales[2], variablesGlobales[3]);
        });
    }

    var botonActualizarIdioma = function () {
        $('#data-table-datos-idiomas tbody').off('click', '.btn-actualizar-idioma');
        $('#data-table-datos-idiomas tbody').on('click', '.btn-actualizar-idioma', function (e) {
            var idIdioma = $(this).data('id-idioma');
            var variablesGlobales = viewGlobals();
            actualizarDatosIdiomas(idIdioma, variablesGlobales[4], variablesGlobales[5]);
        });
    }

    var botonActualizarSoftware = function () {
        $('#data-table-datos-computacionales tbody').off('click', '.btn-actualizar-software');
        $('#data-table-datos-computacionales tbody').on('click', '.btn-actualizar-software', function (e) {
            var idSoftware = $(this).data('id-software');
            var variablesGlobales = viewGlobals();
            actualizarDatosSoftware(idSoftware, variablesGlobales[6], variablesGlobales[5]);
        });
    }

    var botonActualizarSistema = function () {
        $('#data-table-datos-sistemas-especiales tbody').off('click', '.btn-actualizar-sistema');
        $('#data-table-datos-sistemas-especiales tbody').on('click', '.btn-actualizar-sistema', function (e) {
            var idSistema = $(this).data('id-sistema');
            var variablesGlobales = viewGlobals();
            actualizarDatosSistemas(idSistema, variablesGlobales[7], variablesGlobales[5]);
        });
    }

    var botonActualizarDependiente = function () {
        $('#data-table-datos-dependientes-economicos tbody').off('click', '.btn-actualizar-dependiente');
        $('#data-table-datos-dependientes-economicos tbody').on('click', '.btn-actualizar-dependiente', function (e) {
            var idDependiente = $(this).data('id-dependiente');
            actualizarDatosDependientes(idDependiente);
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

    var botonEliminarAcademico = function () {
        $('#data-table-datos-academicos tbody').off('click', '.btn-eliminar-academico');
        $('#data-table-datos-academicos tbody').on('click', '.btn-eliminar-academico', function (e) {
            var idAcademico = $(this).data('id-academico');
            eliminarDatos(idAcademico, 'academicos');
        });
    }

    var botonEliminarIdioma = function () {
        $('#data-table-datos-idiomas tbody').off('click', '.btn-eliminar-idioma');
        $('#data-table-datos-idiomas tbody').on('click', '.btn-eliminar-idioma', function (e) {
            var idIdioma = $(this).data('id-idioma');
            eliminarDatos(idIdioma, 'idiomas');
        });
    }

    var botonEliminarSoftware = function () {
        $('#data-table-datos-computacionales tbody').off('click', '.btn-eliminar-software');
        $('#data-table-datos-computacionales tbody').on('click', '.btn-eliminar-software', function (e) {
            var idSoftware = $(this).data('id-software');
            eliminarDatos(idSoftware, 'software');
        });
    }

    var botonEliminarSistema = function () {
        $('#data-table-datos-sistemas-especiales tbody').off('click', '.btn-eliminar-sistema');
        $('#data-table-datos-sistemas-especiales tbody').on('click', '.btn-eliminar-sistema', function (e) {
            var idSistema = $(this).data('id-sistema');
            eliminarDatos(idSistema, 'sistemas');
        });
    }

    var botonEliminarDependiente = function () {
        $('#data-table-datos-dependientes-economicos tbody').off('click', '.btn-eliminar-dependiente');
        $('#data-table-datos-dependientes-economicos tbody').on('click', '.btn-eliminar-dependiente', function (e) {
            var idDependiente = $(this).data('id-dependiente');
            eliminarDatos(idDependiente, 'dependientes');
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
            var botones = '<a href="javascript:;" class="btn btn-success btn-xs btn-actualizar-academico" data-id-academico="' + item.Id + '"><i class="fa fa-pencil"></i> Actualizar</a>  <a href="javascript:;" class="btn btn-danger btn-xs btn-eliminar-academico" data-id-academico="' + item.Id + '"><i class="fa fa-trash-o"></i> Eliminar</a>';
            tabla.agregarFila('#data-table-datos-academicos', [item.Id, item.NivelEstudio, item.Institucion, item.Desde, item.Hasta, item.Documento, botones, item.IdNivelEstudio, item.IdDocumento]);
        });
    };

    var recargandoTablaIdiomas = function (datosIdiomas) {
        tabla.limpiarTabla('#data-table-datos-idiomas');
        $.each(datosIdiomas, function (key, item) {
            var botones = '<a href="javascript:;" class="btn btn-success btn-xs btn-actualizar-idioma" data-id-idioma="' + item.Id + '"><i class="fa fa-pencil"></i> Actualizar</a> <a href="javascript:;" class="btn btn-danger btn-xs btn-eliminar-idioma" data-id-idioma="' + item.Id + '"><i class="fa fa-trash-o"></i> Eliminar</a>';
            tabla.agregarFila('#data-table-datos-idiomas', [item.Id, item.NombreIdioma, item.NivelComprension, item.NivelLectura, item.NivelEscritura, item.Comentarios, botones, item.Idioma, item.Comprension, item.Lectura, item.Escritura]);
        });
    };

    var recargandoTablaSoftware = function (datosSoftware) {
        tabla.limpiarTabla('#data-table-datos-computacionales');
        $.each(datosSoftware, function (key, item) {
            var botones = '<a href="javascript:;" class="btn btn-success btn-xs btn-actualizar-software" data-id-software="' + item.Id + '"><i class="fa fa-pencil"></i> Actualizar</a> <a href="javascript:;" class="btn btn-danger btn-xs btn-eliminar-software" data-id-software="' + item.Id + '"></i> Eliminar</a>';
            tabla.agregarFila('#data-table-datos-computacionales', [item.Id, item.Software, item.Nivel, item.Comentarios, botones, item.IdSoftware, item.IdNivelHabilidad]);
        });
    };

    var recargandoTablaSistemas = function (datosSistemas) {
        tabla.limpiarTabla('#data-table-datos-sistemas-especiales');
        $.each(datosSistemas, function (key, item) {
            var botones = '<a href="javascript:;" class="btn btn-success btn-xs btn-actualizar-sistema" data-id-sistema="' + item.Id + '"><i class="fa fa-pencil"></i> Actualizar</a> <a href="javascript:;" class="btn btn-danger btn-xs btn-eliminar-sistema" data-id-sistema="' + item.Id + '"></i> Eliminar</a>';
            tabla.agregarFila('#data-table-datos-sistemas-especiales', [item.Id, item.Sistema, item.Nivel, item.Comentarios, botones, item.IdSistema, item.IdNivelHabilidad]);
        });
    };

    var recargandoTablaDependientes = function (datosDependientes) {
        tabla.limpiarTabla('#data-table-datos-dependientes-economicos');
        $.each(datosDependientes, function (key, item) {
            var botones = '<a href="javascript:;" class="btn btn-success btn-xs btn-actualizar-dependiente" data-id-dependiente="' + item.Id + '"><i class="fa fa-pencil"></i> Actualizar</a> <a href="javascript:;" class="btn btn-danger btn-xs btn-eliminar-dependiente" data-id-dependiente="' + item.Id + '"></i> Eliminar</a>';
            tabla.agregarFila('#data-table-datos-dependientes-economicos', [item.Id, item.Nombre, item.Parentesco, item.FechaNacimiento, botones]);
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
        calendario.crearFecha('.calendario');

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
                    respuestaAcademicos(respuesta);
                });
            }
        });

        cerrarModalCambios();
    }

    var actualizarDatosIdiomas = function () {
        var idIdioma = arguments[0];
        var catalogoHabilidadesIdioma = arguments[1];
        var catalogoNivelHabilidades = arguments[2];

        var anteriorIdioma = '';
        var anteriorComprension = '';
        var anteriorLectura = '';
        var anteriorEscritura = '';
        var anteriorComentarios = '';
        var tablaDatosIdiomas = $('#data-table-datos-idiomas').DataTable().data();

        $.each(tablaDatosIdiomas, function (key, valor) {
            if (idIdioma == valor[0]) {
                anteriorIdioma = valor[7];
                anteriorComprension = valor[8];
                anteriorLectura = valor[9];
                anteriorEscritura = valor[10];
                anteriorComentarios = valor[5];
            }
        });

        var html = '<div class="row">\n\
                        <div class="col-md-6">\n\
                            <label for="actualizarIdiomaUsuario">Idioma *</label>\n\
                            <select id="actualizarIdiomaUsuario" class="form-control" style="width: 100%" data-parsley-required="true">\n\
                                <option value="">Seleccionar...</option>\n\
                            </select>\n\
                        </div>\n\
                        <div class="col-md-6">\n\
                            <label for="actualizarComprensionUsuario">Comprensión *</label>\n\
                            <select id="actualizarComprensionUsuario" class="form-control" style="width: 100%" data-parsley-required="true">\n\
                                <option value="">Seleccionar...</option>\n\
                            </select>\n\
                        </div>\n\
                    </div>\n\
                    <div class="row m-t-10">\n\
                        <div class="col-md-6">\n\
                            <label for="actualizarLecturaUsuario">Lectura *</label>\n\
                            <select id="actualizarLecturaUsuario" class="form-control" style="width: 100%" data-parsley-required="true">\n\
                                <option value="">Seleccionar...</option>\n\
                            </select>\n\
                        </div>\n\
                        <div class="col-md-6">\n\
                            <label for="actualizarEscrituraUsuario">Escritura *</label>\n\
                            <select id="actualizarEscrituraUsuario" class="form-control" style="width: 100%" data-parsley-required="true">\n\
                                <option value="">Seleccionar...</option>\n\
                            </select>\n\
                        </div>\n\
                    </div>\n\
                    <div class="row m-t-10">\n\
                        <div class="col-md-12">\n\
                            <label for="actualizarComantariosIdiomasUsuario">Comentarios</label>\n\
                            <textarea id="actualizarComantariosIdiomasUsuario" class="form-control entregaGarantia" placeholder="Ingrese los comentarios" rows="3" ></textarea>\n\
                        </div>\n\
                    </div>\n\
                    <div class="row m-t-10">\n\
                        <div class="col-md-12">\n\
                            <div id="errorIdiomasUsuario"></div>\n\
                        </div>\n\
                    </div>';
        evento.iniciarModal('#modalEdit', 'Editar Idioma', html);

        $.each(catalogoHabilidadesIdioma, function (key, valor) {
            $("#actualizarIdiomaUsuario").append('<option value=' + valor.Id + '>' + valor.Nombre + '</option>');
        });

        $.each(catalogoNivelHabilidades, function (key, valor) {
            $("#actualizarComprensionUsuario").append('<option value=' + valor.Id + '>' + valor.Nombre + '</option>');
            $("#actualizarLecturaUsuario").append('<option value=' + valor.Id + '>' + valor.Nombre + '</option>');
            $("#actualizarEscrituraUsuario").append('<option value=' + valor.Id + '>' + valor.Nombre + '</option>');
        });

        select.crearSelect('#actualizarIdiomaUsuario');
        select.crearSelect('#actualizarComprensionUsuario');
        select.crearSelect('#actualizarLecturaUsuario');
        select.crearSelect('#actualizarEscrituraUsuario');

        select.cambiarOpcion('#actualizarIdiomaUsuario', anteriorIdioma);
        select.cambiarOpcion('#actualizarComprensionUsuario', anteriorComprension);
        select.cambiarOpcion('#actualizarLecturaUsuario', anteriorLectura);
        select.cambiarOpcion('#actualizarEscrituraUsuario', anteriorEscritura);

        $('#actualizarComantariosIdiomasUsuario').val(anteriorComentarios);

        $('#btnGuardarCambios').off('click');
        $('#btnGuardarCambios').on('click', function () {
            var nivelIdioma = $('#actualizarIdiomaUsuario').val();
            var comprension = $('#actualizarComprensionUsuario').val();
            var lectura = $('#actualizarLecturaUsuario').val();
            var escritura = $('#actualizarEscrituraUsuario').val();
            var comentarios = $('#actualizarComantariosIdiomasUsuario').val();

            var data = {
                id: idIdioma,
                nivelIdioma: nivelIdioma,
                comprension: comprension,
                lectura: lectura,
                escritura: escritura,
                comentarios: comentarios
            };

            var arrayCampos = [
                {'objeto': '#actualizarIdiomaUsuario', 'mensajeError': 'Falta seleccionar el campo Nivel de Idioma.'},
                {'objeto': '#actualizarComprensionUsuario', 'mensajeError': 'Falta seleccionarel campo Comprensión.'},
                {'objeto': '#actualizarLecturaUsuario', 'mensajeError': 'Falta seleccionar el campo Lectura.'},
                {'objeto': '#actualizarEscrituraUsuario', 'mensajeError': 'Falta seleccionar el campo Escritura.'}
            ];

            var camposFormularioValidados = evento.validarCamposObjetos(arrayCampos, '#errorIdiomasUsuario');

            mostrarCargaPaginaInformacionUsuario('#idiomas');

            if (camposFormularioValidados) {
                evento.enviarEvento('PerfilUsuario/ActualizarDatosIdiomasUsuario', data, '#modalEdit', function (respuesta) {
                    respuestaIdiomas(respuesta);
                });
            }
        });

        cerrarModalCambios();
    }

    var actualizarDatosSoftware = function () {
        var idSoftware = arguments[0];
        var catalogoHabilidadesSoftware = arguments[1];
        var catalogoNivelHabilidades = arguments[2];

        var anteriorSoftware = '';
        var anteriorNivel = '';
        var anteriorComentarios = '';
        var tablaDatosSoftware = $('#data-table-datos-computacionales').DataTable().data();

        $.each(tablaDatosSoftware, function (key, valor) {
            if (idSoftware == valor[0]) {
                anteriorSoftware = valor[5];
                anteriorNivel = valor[6];
                anteriorComentarios = valor[3];
            }
        });

        var html = '<div class="row">\n\
                        <div class="col-md-6">\n\
                            <label for="actualizarSoftwareUsuario">Software *</label>\n\
                            <select id="actualizarSoftwareUsuario" class="form-control" style="width: 100%" data-parsley-required="true">\n\
                                <option value="">Seleccionar...</option>\n\
                            </select>\n\
                        </div>\n\
                        <div class="col-md-6">\n\
                            <label for="actualizarNivelComputacionalesUsuario">Nivel *</label>\n\
                            <select id="actualizarNivelComputacionalesUsuario" class="form-control" style="width: 100%" data-parsley-required="true">\n\
                                <option value="">Seleccionar...</option>\n\
                            </select>\n\
                        </div>\n\
                    </div>\n\
                    <div class="row m-t-10">\n\
                        <div class="col-md-12">\n\
                            <label for="actualizarComentariosComputacionalesUsuario">Comentarios</label>\n\
                            <textarea id="actualizarComentariosComputacionalesUsuario" class="form-control entregaGarantia" placeholder="Ingrese los comentarios" rows="3" ></textarea>\n\
                        </div>\n\
                    </div>\n\
                    <div class="row m-t-10">\n\
                        <div class="col-md-12">\n\
                            <div id="errorSoftwareUsuario"></div>\n\
                        </div>\n\
                    </div>';
        evento.iniciarModal('#modalEdit', 'Editar Dato Computacional', html);

        $.each(catalogoHabilidadesSoftware, function (key, valor) {
            $("#actualizarSoftwareUsuario").append('<option value=' + valor.Id + '>' + valor.Nombre + '</option>');
        });

        $.each(catalogoNivelHabilidades, function (key, valor) {
            $("#actualizarNivelComputacionalesUsuario").append('<option value=' + valor.Id + '>' + valor.Nombre + '</option>');
        });

        select.crearSelect('#actualizarSoftwareUsuario');
        select.crearSelect('#actualizarNivelComputacionalesUsuario');

        select.cambiarOpcion('#actualizarSoftwareUsuario', anteriorSoftware);
        select.cambiarOpcion('#actualizarNivelComputacionalesUsuario', anteriorNivel);

        $('#actualizarComentariosComputacionalesUsuario').val(anteriorComentarios);

        $('#btnGuardarCambios').off('click');
        $('#btnGuardarCambios').on('click', function () {
            var nivelSoftware = $('#actualizarSoftwareUsuario').val();
            var nivel = $('#actualizarNivelComputacionalesUsuario').val();
            var comentarios = $('#actualizarComentariosComputacionalesUsuario').val();

            var data = {
                id: idSoftware,
                nivelSoftware: nivelSoftware,
                nivel: nivel,
                comentarios: comentarios
            };

            var arrayCampos = [
                {'objeto': '#actualizarSoftwareUsuario', 'mensajeError': 'Falta seleccionar el campo Software.'},
                {'objeto': '#actualizarNivelComputacionalesUsuario', 'mensajeError': 'Falta seleccionarel campo Nivel.'}
            ];

            var camposFormularioValidados = evento.validarCamposObjetos(arrayCampos, '#errorSoftwareUsuario');

            mostrarCargaPaginaInformacionUsuario('#computacionales');

            if (camposFormularioValidados) {
                evento.enviarEvento('PerfilUsuario/ActualizarDatosSoftwareUsuario', data, '#modalEdit', function (respuesta) {
                    respuestaSoftware(respuesta);
                });
            }
        });

        cerrarModalCambios();
    }

    var actualizarDatosSistemas = function () {
        var idSistema = arguments[0];
        var catalogoHabilidadesSistema = arguments[1];
        var catalogoNivelHabilidades = arguments[2];

        var anteriorSistema = '';
        var anteriorNivel = '';
        var anteriorComentarios = '';
        var tablaDatosSistema = $('#data-table-datos-sistemas-especiales').DataTable().data();

        $.each(tablaDatosSistema, function (key, valor) {
            if (idSistema == valor[0]) {
                anteriorSistema = valor[5];
                anteriorNivel = valor[6];
                anteriorComentarios = valor[3];
            }
        });

        var html = '<div class="row">\n\
                        <div class="col-md-6">\n\
                            <label for="actualizarSistemasUsuario">Sistemas *</label>\n\
                            <select id="actualizarSistemasUsuario" class="form-control" style="width: 100%" data-parsley-required="true">\n\
                                <option value="">Seleccionar...</option>\n\
                            </select>\n\
                        </div>\n\
                        <div class="col-md-6">\n\
                            <label for="actualizarNivelSistemasUsuario">Nivel *</label>\n\
                            <select id="actualizarNivelSistemasUsuario" class="form-control" style="width: 100%" data-parsley-required="true">\n\
                                <option value="">Seleccionar...</option>\n\
                            </select>\n\
                        </div>\n\
                    </div>\n\
                    <div class="row m-t-10">\n\
                        <div class="col-md-12">\n\
                            <label for="actualizarComnetariosSistemasUsuario">Comentarios</label>\n\
                            <textarea id="actualizarComnetariosSistemasUsuario" class="form-control entregaGarantia" placeholder="Ingrese los comentarios" rows="3" ></textarea>\n\
                        </div>\n\
                    </div>\n\
                    <div class="row m-t-10">\n\
                        <div class="col-md-12">\n\
                            <div id="errorSistemaUsuario"></div>\n\
                        </div>\n\
                    </div>';
        evento.iniciarModal('#modalEdit', 'Editar Dato Computacional', html);

        $.each(catalogoHabilidadesSistema, function (key, valor) {
            $("#actualizarSistemasUsuario").append('<option value=' + valor.Id + '>' + valor.Nombre + '</option>');
        });

        $.each(catalogoNivelHabilidades, function (key, valor) {
            $("#actualizarNivelSistemasUsuario").append('<option value=' + valor.Id + '>' + valor.Nombre + '</option>');
        });

        select.crearSelect('#actualizarSistemasUsuario');
        select.crearSelect('#actualizarNivelSistemasUsuario');

        select.cambiarOpcion('#actualizarSistemasUsuario', anteriorSistema);
        select.cambiarOpcion('#actualizarNivelSistemasUsuario', anteriorNivel);

        $('#actualizarComnetariosSistemasUsuario').val(anteriorComentarios);

        $('#btnGuardarCambios').off('click');
        $('#btnGuardarCambios').on('click', function () {
            var nivelSistema = $('#actualizarSistemasUsuario').val();
            var nivel = $('#actualizarNivelSistemasUsuario').val();
            var comentarios = $('#actualizarComnetariosSistemasUsuario').val();

            var data = {
                id: idSistema,
                nivelSistema: nivelSistema,
                nivel: nivel,
                comentarios: comentarios
            };

            var arrayCampos = [
                {'objeto': '#actualizarSistemasUsuario', 'mensajeError': 'Falta seleccionar el campo Sitemas.'},
                {'objeto': '#actualizarNivelSistemasUsuario', 'mensajeError': 'Falta seleccionarel campo Nivel.'}
            ];

            var camposFormularioValidados = evento.validarCamposObjetos(arrayCampos, '#errorSistemaUsuario');

            mostrarCargaPaginaInformacionUsuario('#sistemasEspeciales');

            if (camposFormularioValidados) {
                evento.enviarEvento('PerfilUsuario/ActualizarDatosSistemasUsuario', data, '#modalEdit', function (respuesta) {
                    respuestaSistemas(respuesta);
                });
            }
        });

        cerrarModalCambios();
    }

    var actualizarDatosDependientes = function () {
        var idDependiente = arguments[0];

        var anteriorNombre = '';
        var anteriorParentesco = '';
        var anteriorFechaNacimiento = '';
        var tablaDatosDependientes = $('#data-table-datos-dependientes-economicos').DataTable().data();

        $.each(tablaDatosDependientes, function (key, valor) {
            if (idDependiente == valor[0]) {
                anteriorNombre = valor[1];
                anteriorParentesco = valor[2];
                anteriorFechaNacimiento = valor[3];
            }
        });

        var html = '<div class="row">\n\
                        <div class="col-md-4">\n\
                            <label for="actualizarNombreDependienteUsuario">Nombre *</label>\n\
                            <input type="tel" class="form-control" id="actualizarNombreDependienteUsuario" style="width: 100%"/>\n\
                        </div>\n\
                        <div class="col-md-4">\n\
                            <label for="actualizarParentescoUsuario">Parentesco *</label>\n\
                            <input type="tel" class="form-control" id="actualizarParentescoUsuario" style="width: 100%"/>\n\
                        </div>\n\
                        <div class="col-md-4">\n\
                            <label for="actualizarParentescoVigenciaUsuario">Fecha de Nacimiento *</label>\n\
                            <div id="fechaParentescoVigencia" class="input-group date calendario" >\n\
                                <input id="actualizarParentescoVigenciaUsuario" type="text" class="form-control"/>\n\
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>\n\
                            </div>\n\
                        </div>\n\
                    </div>\n\
                    <div class="row m-t-10">\n\
                        <div class="col-md-12">\n\
                            <div id="errorDependienteUsuario"></div>\n\
                        </div>\n\
                    </div>';
        evento.iniciarModal('#modalEdit', 'Editar Dependiente', html);

        calendario.crearFecha('.calendario');

        $('#actualizarNombreDependienteUsuario').val(anteriorNombre);
        $('#actualizarParentescoUsuario').val(anteriorParentesco);
        $('#actualizarParentescoVigenciaUsuario').val(anteriorFechaNacimiento);

        $('#btnGuardarCambios').off('click');
        $('#btnGuardarCambios').on('click', function () {
            var nombre = $('#actualizarNombreDependienteUsuario').val();
            var parentesco = $('#actualizarParentescoUsuario').val();
            var fechaNacimiento = $('#actualizarParentescoVigenciaUsuario').val();

            var data = {
                id: idDependiente,
                nombre: nombre,
                parentesco: parentesco,
                fechaNacimiento: fechaNacimiento
            };

            var arrayCampos = [
                {'objeto': '#actualizarNombreDependienteUsuario', 'mensajeError': 'Falta seleccionar el campo Nombre.'},
                {'objeto': '#actualizarParentescoUsuario', 'mensajeError': 'Falta seleccionarel campo Parentesco.'},
                {'objeto': '#actualizarParentescoVigenciaUsuario', 'mensajeError': 'Falta seleccionarel campo Fecha de Nacimiento.'}
            ];

            var camposFormularioValidados = evento.validarCamposObjetos(arrayCampos, '#errorDependienteUsuario');

            mostrarCargaPaginaInformacionUsuario('#dependientesEconomicos');

            if (camposFormularioValidados) {
                evento.enviarEvento('PerfilUsuario/ActualizarDatosDependientesUsuario', data, '#modalEdit', function (respuesta) {
                    respuestaDependientes(respuesta);
                });
            }
        });

        cerrarModalCambios();
    }

    var eliminarDatos = function () {
        var id = arguments[0];
        var tabla = arguments[1];

        var data = {id: id, tabla: tabla};

        switch (tabla) {
            case 'academicos':
                mostrarCargaPaginaInformacionUsuario('#academicos');
                break;
            case 'idiomas':
                mostrarCargaPaginaInformacionUsuario('#idiomas');
                break;
            case 'software':
                mostrarCargaPaginaInformacionUsuario('#computacionales');
                break;
            case 'sistemas':
                mostrarCargaPaginaInformacionUsuario('#sistemasEspeciales');
                break;
            case 'dependientes':
                mostrarCargaPaginaInformacionUsuario('#dependientesEconomicos');
                break;
        }

        evento.enviarEvento('PerfilUsuario/EliminarDatos', data, '#modalEdit', function (respuesta) {
            switch (tabla) {
                case 'academicos':
                    respuestaAcademicos(respuesta);
                    break;
                case 'idiomas':
                    respuestaIdiomas(respuesta);
                    break;
                case 'software':
                    respuestaSoftware(respuesta);
                    break;
                case 'sistemas':
                    respuestaSistemas(respuesta);
                    break;
                case 'dependientes':
                    respuestaDependientes(respuesta);
                    break;
            }

        });
    }

    var respuestaAcademicos = function (respuesta) {
        ocultarCargaPaginaInformacionUsuario('#academicos');
        if (respuesta instanceof Array || respuesta instanceof Object) {
            recargandoTablaAcademicos(respuesta);
            evento.terminarModal('#modalEdit');
            botonActualizarAcademico();
            botonEliminarAcademico();
        } else {
            mensajeModal('Hubo un error contacte al administrador de AdIST.', 'Error', '#dependientesEconomicos');
        }
    }

    var respuestaIdiomas = function (respuesta) {
        ocultarCargaPaginaInformacionUsuario('#idiomas');
        if (respuesta instanceof Array || respuesta instanceof Object) {
            recargandoTablaIdiomas(respuesta);
            evento.terminarModal('#modalEdit');
            botonActualizarIdioma();
            botonEliminarIdioma();
        } else {
            mensajeModal('Hubo un error contacte al administrador de AdIST.', 'Error', '#dependientesEconomicos');
        }
    }

    var respuestaSoftware = function (respuesta) {
        ocultarCargaPaginaInformacionUsuario('#computacionales');
        if (respuesta instanceof Array || respuesta instanceof Object) {
            recargandoTablaSoftware(respuesta);
            evento.terminarModal('#modalEdit');
            botonActualizarSoftware();
            botonEliminarSoftware();
        } else {
            mensajeModal('Hubo un error contacte al administrador de AdIST.', 'Error', '#dependientesEconomicos');
        }
    }

    var respuestaSistemas = function (respuesta) {
        ocultarCargaPaginaInformacionUsuario('#sistemasEspeciales');
        if (respuesta instanceof Array || respuesta instanceof Object) {
            recargandoTablaSistemas(respuesta);
            evento.terminarModal('#modalEdit');
            botonActualizarSistema();
            botonEliminarSistema();
        } else {
            mensajeModal('Hubo un error contacte al administrador de AdIST.', 'Error', '#dependientesEconomicos');
        }
    }

    var respuestaDependientes = function (respuesta) {
        ocultarCargaPaginaInformacionUsuario('#dependientesEconomicos');
        if (respuesta instanceof Array || respuesta instanceof Object) {
            recargandoTablaDependientes(respuesta);
            evento.terminarModal('#modalEdit');
            botonActualizarDependiente();
            botonEliminarDependiente();
        } else {
            mensajeModal('Hubo un error contacte al administrador de AdIST.', 'Error', '#dependientesEconomicos');
        }
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

    var mensajeModal = function () {
        var mensaje = arguments[0];
        var titulo = arguments[1];
        var div = arguments[2];
        var html = '<div class="row">\n\
                            <div id="mensaje-modal" class="col-md-12 text-center">\n\
                                <h3>' + mensaje + '</h3>\n\
                            </div>\n\
                      </div>';
        evento.mostrarModal(titulo, html);
        $('#btnModalConfirmar').addClass('hidden');
        $('#btnModalAbortar').removeClass('hidden');
        $('#btnModalAbortar').empty().append('Cerrar');
        $('#btnModalAbortar').on('click', function () {
            ocultarCargaPaginaInformacionUsuario(div);
            evento.cerrarModal();
        });
    }
});
