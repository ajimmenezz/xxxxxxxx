function Usuario_perfil() {
    this.tabla = new Tabla();
    this.select = new Select();
    this.calendario = new Fecha();
}

//Herencia del objeto Base
Usuario_perfil.prototype = new Base();
Usuario_perfil.prototype.constructor = Usuario_perfil;

Usuario_perfil.prototype.SelectNacimiento = function (datosPersonal) {
    var evento = new Base();
    var select = new Select();

    $("#selectActualizarEstadoUsuario").on("change", function () {
        $("#selectActualizarMunicipioUsuario").empty().append('<option value="">Seleccionar...</option>');
        select.cambiarOpcion("#selectActualizarMunicipioUsuario", '');
        var pais = $(this).val();
        if (pais !== '') {
            var data = {IdEstado: pais};
            evento.enviarEvento('/Configuracion/PerfilUsuario/MostrarDatosMunicipio', data, '#seccion-informacion-usuario', function (respuesta) {
                $.each(respuesta, function (k, v) {
                    $("#selectActualizarMunicipioUsuario").append('<option value="' + v.Id + '">' + v.Nombre + '</option>')
                });
                $("#selectActualizarMunicipioUsuario").removeAttr("disabled");
                select.cambiarOpcion('#selectActualizarMunicipioUsuario', datosPersonal.MunicipioNac);
            });
        } else {
            $("#selectActualizarMunicipioUsuario").attr("disabled", "disabled");
        }
    });
};

Usuario_perfil.prototype.BotonesActualizar = function (idUsuario) {
    var _this = this;
    var evento = new Base();
    var select = new Select();
    var idUsuario = arguments[0];
    var respuesta = arguments[1];
    var catalogoNivelEstudio = respuesta.datos.nivelEstudio;
    var catalogoDocumentoEstudio = respuesta.datos.documentosEstudio;
    var catalogoHabilidadesIdiomas = respuesta.datos.habilidadesIdioma;
    var catalogoNivelHabilidades = respuesta.datos.nivelHabilidades;
    var catalogoHabilidadesSoftware = respuesta.datos.habilidadesSoftware;
    var catalogoHabilidadesSistema = respuesta.datos.habilidadesSistema;

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
        var institutoAfore = $('#inputActualizarInstitutoAforeUsuario').val();
        var numeroAfore = $('#inputActualizarNumeroAforeUsuario').val();

        _this.MostrarCargaPaginaInformacionUsuario('#nav-tab-datos-personales');

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
            institutoAfore: institutoAfore,
            numeroAfore: numeroAfore,
            id: idUsuario
        };

        evento.enviarEvento('EventoAltaPersonal/ActualizarDatosPersonal', data, '', function (respuesta) {
            if (respuesta) {
                mensajeModal('Se actualizo correctamente.', 'Correcto', '#nav-tab-datos-personales');
            } else {
                mensajeModal('No hay ningún campo modificado.', 'Advertencia', '#nav-tab-datos-personales');
            }
        });
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
            hasta: hasta,
            id: idUsuario
        };

        var arrayCampos = [
            {'objeto': '#selectActualizarNivelEstudioUsuario', 'mensajeError': 'Falta seleccionar el campo Nivel de Estudio.'},
            {'objeto': '#selectActualizarNombreInstitutoUsuario', 'mensajeError': 'Falta seleccionarel campo Nombre de la Institución.'},
            {'objeto': '#selectActualizarDocumentoRecibidoUsuario', 'mensajeError': 'Falta seleccionar el campo DocumentoRecibido.'},
            {'objeto': '#inputActualizarDesdeUsuario', 'mensajeError': 'Falta seleccionar el campo Desde.'},
            {'objeto': '#inputActualizarHastaUsuario', 'mensajeError': 'Falta escribir el campo Hasta.'}
        ];

        var camposFormularioValidados = evento.validarCamposObjetos(arrayCampos, '#errorGuardarAcademicosUsuario');

        _this.MostrarCargaPaginaInformacionUsuario('#nav-tab-academicos');

        if (camposFormularioValidados) {
            evento.enviarEvento('/Configuracion/PerfilUsuario/GuardarDatosAcademicosUsuario', data, '#seccion-informacion-usuario', function (respuesta) {
                _this.OcultarCargaPaginaInformacionUsuario('#nav-tab-academicos');
                if (respuesta instanceof Array || respuesta instanceof Object) {
                    select.cambiarOpcion('#selectActualizarNivelEstudioUsuario', '');
                    select.cambiarOpcion('#selectActualizarDocumentoRecibidoUsuario', '');
                    $('#selectActualizarNombreInstitutoUsuario').val('');
                    $('#inputActualizarDesdeUsuario').val('');
                    $('#inputActualizarHastaUsuario').val('');
                    _this.RecargandoTablaAcademicos(respuesta);
                    _this.BotonActualizarAcademico(catalogoNivelEstudio, catalogoDocumentoEstudio, idUsuario);
                    _this.BotonEliminarAcademico(idUsuario, catalogoNivelEstudio, catalogoDocumentoEstudio);
                } else {
                    evento.mostrarMensaje("#errorGuardarAcademicosUsuario", false, "Hubo un error contacte al administrador de AdIST", 4000);
                }
            });
        } else {
            _this.OcultarCargaPaginaInformacionUsuario('#nav-tab-academicos');
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
            comentarios: comentarios,
            idUsuario: idUsuario
        };

        var arrayCampos = [
            {'objeto': '#selectActualizarIdiomaUsuario', 'mensajeError': 'Falta seleccionar el campo Idioma.'},
            {'objeto': '#selectActualizarComprensionUsuario', 'mensajeError': 'Falta seleccionarel campo Comprensión.'},
            {'objeto': '#selectActualizarLecturaUsuario', 'mensajeError': 'Falta seleccionar el campo Lectura.'},
            {'objeto': '#selectActualizarEscrituraUsuario', 'mensajeError': 'Falta seleccionar el campo Escritura.'}
        ];

        var camposFormularioValidados = evento.validarCamposObjetos(arrayCampos, '#errorGuardarIdiomasUsuario');

        _this.MostrarCargaPaginaInformacionUsuario('#nav-tab-idiomas');

        if (camposFormularioValidados) {
            evento.enviarEvento('/Configuracion/PerfilUsuario/GuardarDatosIdiomasUsuario', data, '', function (respuesta) {
                _this.OcultarCargaPaginaInformacionUsuario('#nav-tab-idiomas');
                if (respuesta instanceof Array || respuesta instanceof Object) {
                    select.cambiarOpcion('#selectActualizarIdiomaUsuario', '');
                    select.cambiarOpcion('#selectActualizarComprensionUsuario', '');
                    select.cambiarOpcion('#selectActualizarLecturaUsuario', '');
                    select.cambiarOpcion('#selectActualizarEscrituraUsuario', '');
                    $('#inputActualizarComantariosIdiomasUsuario').val('');
                    _this.RecargandoTablaIdiomas(respuesta);
                    _this.BotonActualizarIdioma(catalogoHabilidadesIdiomas, catalogoNivelHabilidades, idUsuario);
                    _this.BotonEliminarIdioma(idUsuario, catalogoHabilidadesIdiomas, catalogoNivelHabilidades);
                } else {
                    evento.mostrarMensaje("#errorGuardarIdiomasUsuario", false, "Hubo un error contacte al administrador de AdIST", 4000);
                }
            });
        } else {
            _this.OcultarCargaPaginaInformacionUsuario('#nav-tab-idiomas');
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
            comentarios: comentarios,
            idUsuario: idUsuario
        };

        var arrayCampos = [
            {'objeto': '#selectActualizarSoftwareUsuario', 'mensajeError': 'Falta seleccionar el campo Software.'},
            {'objeto': '#selectActualizarNivelComputacionalesUsuario', 'mensajeError': 'Falta seleccionarel campo Nivel.'}
        ];

        var camposFormularioValidados = evento.validarCamposObjetos(arrayCampos, '#errorGuardarComputacionalesUsuario');

        _this.MostrarCargaPaginaInformacionUsuario('#nav-tab-computacionales');

        if (camposFormularioValidados) {
            evento.enviarEvento('/Configuracion/PerfilUsuario/GuardarDatosComputacionalesUsuario', data, '', function (respuesta) {
                _this.OcultarCargaPaginaInformacionUsuario('#nav-tab-computacionales');
                if (respuesta instanceof Array || respuesta instanceof Object) {
                    select.cambiarOpcion('#selectActualizarSoftwareUsuario', '');
                    select.cambiarOpcion('#selectActualizarNivelComputacionalesUsuario', '');
                    $('#inputActualizarComentariosComputacionalesUsuario').val('');
                    _this.RecargandoTablaSoftware(respuesta);
                    _this.BotonActualizarSoftware(catalogoHabilidadesSoftware, catalogoNivelHabilidades, idUsuario);
                    _this.BotonEliminarSoftware(idUsuario, catalogoHabilidadesSoftware, catalogoNivelHabilidades);
                } else {
                    evento.mostrarMensaje("#errorGuardarComputacionalesUsuario", false, "Hubo un error contacte al administrador de AdIST", 4000);
                }
            });
        } else {
            _this.OcultarCargaPaginaInformacionUsuario('#nav-tab-computacionales');
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
            comentarios: comentarios,
            idUsuario: idUsuario
        };

        var arrayCampos = [
            {'objeto': '#selectActualizarSistemasUsuario', 'mensajeError': 'Falta seleccionar el campo Sistema.'},
            {'objeto': '#selectActualizarNivelSistemasUsuario', 'mensajeError': 'Falta seleccionarel campo Nivel.'}
        ];

        var camposFormularioValidados = evento.validarCamposObjetos(arrayCampos, '#errorGuardarEspecialesUsuario');

        _this.MostrarCargaPaginaInformacionUsuario('#nav-tab-sistemas-especiales');

        if (camposFormularioValidados) {
            evento.enviarEvento('/Configuracion/PerfilUsuario/GuardarDatosSistemasEspecialesUsuario', data, '', function (respuesta) {
                _this.OcultarCargaPaginaInformacionUsuario('#nav-tab-sistemas-especiales');
                if (respuesta instanceof Array || respuesta instanceof Object) {
                    select.cambiarOpcion('#selectActualizarSistemasUsuario', '');
                    select.cambiarOpcion('#selectActualizarNivelSistemasUsuario', '');
                    $('#inputActualizarComnetariosSistemasUsuario').val('');
                    _this.RecargandoTablaSistemas(respuesta);
                    _this.BotonActualizarSistema(catalogoHabilidadesSistema, catalogoNivelHabilidades, idUsuario);
                    _this.BotonEliminarSistema(idUsuario, catalogoHabilidadesSistema, catalogoNivelHabilidades);
                } else {
                    evento.mostrarMensaje("#errorGuardarEspecialesUsuario", false, "Hubo un error contacte al administrador de AdIST", 4000);
                }
            });
        } else {
            _this.OcultarCargaPaginaInformacionUsuario('#nav-tab-sistemas-especiales');
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

        _this.MostrarCargaPaginaInformacionUsuario('#nav-tab-automovil');

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
                idUsuario: idUsuario
            };
            evento.enviarEvento('/Configuracion/PerfilUsuario/GuardarDatosAutomovilUsuario', data, '', function (respuesta) {
                _this.OcultarCargaPaginaInformacionUsuario('#nav-tab-automovil');
                if (respuesta) {
                    evento.mostrarMensaje("#errorGuardarAutomovilUsuario", true, 'Se guardo correctamente.', 4000);
                } else {
                    evento.mostrarMensaje("#errorGuardarAutomovilUsuario", false, 'No hay ningún campo modificado.', 4000);
                }
            });
        } else {
            _this.OcultarCargaPaginaInformacionUsuario('#nav-tab-automovil');
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
            vigencia: vigencia,
            idUsuario: idUsuario
        };

        var arrayCampos = [
            {'objeto': '#inputActualizarNombreDependienteUsuario', 'mensajeError': 'Falta seleccionar el campo Nombre.'},
            {'objeto': '#inputActualizarParentescoUsuario', 'mensajeError': 'Falta seleccionarel campo Parentesco.'},
            {'objeto': '#inputActualizarParentescoVigenciaUsuario', 'mensajeError': 'Falta seleccionarel campo Vigencia.'}
        ];

        var camposFormularioValidados = evento.validarCamposObjetos(arrayCampos, '#errorGuardarDependientesUsuario');

        _this.MostrarCargaPaginaInformacionUsuario('#nav-tab-dependientes-economicos');

        if (camposFormularioValidados) {
            evento.enviarEvento('/Configuracion/PerfilUsuario/GuardarDatosDependientesEconomicosUsuario', data, '', function (respuesta) {
                _this.OcultarCargaPaginaInformacionUsuario('#nav-tab-dependientes-economicos');
                if (respuesta instanceof Array || respuesta instanceof Object) {
                    $('#inputActualizarNombreDependienteUsuario').val('');
                    $('#inputActualizarParentescoUsuario').val('');
                    $('#inputActualizarParentescoVigenciaUsuario').val('');
                    _this.RecargandoTablaDependientes(respuesta);
                    _this.BotonActualizarDependiente(idUsuario);
                    _this.BotonEliminarDependiente(idUsuario);
                } else {
                    evento.mostrarMensaje("#errorGuardarDependientesUsuario", false, "Hubo un error contacte al administrador de AdIST", 4000);
                }
            });
        } else {
            _this.OcultarCargaPaginaInformacionUsuario('#nav-tab-dependientes-economicos');
        }
    });

    var mensajeModal = function () {
        var mensaje = arguments[0];
        var titulo = arguments[1];
        var divCarga = arguments[2];

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
            _this.OcultarCargaPaginaInformacionUsuario(divCarga);
            evento.cerrarModal();
        });
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
};

Usuario_perfil.prototype.RecargandoTablaAcademicos = function (datosAcademicos) {
    var tabla = new Tabla();

    tabla.limpiarTabla('#data-table-datos-academicos');
    $.each(datosAcademicos, function (key, item) {
        var botones = '<a href="javascript:;" class="btn btn-success btn-xs btn-actualizar-academico" data-id-academico="' + item.Id + '"><i class="fa fa-pencil"></i> Actualizar</a>  <a href="javascript:;" class="btn btn-danger btn-xs btn-eliminar-academico" data-id-academico="' + item.Id + '"><i class="fa fa-trash-o"></i> Eliminar</a>';
        tabla.agregarFila('#data-table-datos-academicos', [item.Id, item.NivelEstudio, item.Institucion, item.Desde, item.Hasta, item.Documento, botones, item.IdNivelEstudio, item.IdDocumento]);
    });
};

Usuario_perfil.prototype.RecargandoTablaIdiomas = function (datosIdiomas) {
    var tabla = new Tabla();
    tabla.limpiarTabla('#data-table-datos-idiomas');
    $.each(datosIdiomas, function (key, item) {
        var botones = '<a href="javascript:;" class="btn btn-success btn-xs btn-actualizar-idioma" data-id-idioma="' + item.Id + '"><i class="fa fa-pencil"></i> Actualizar</a> <a href="javascript:;" class="btn btn-danger btn-xs btn-eliminar-idioma" data-id-idioma="' + item.Id + '"><i class="fa fa-trash-o"></i> Eliminar</a>';
        tabla.agregarFila('#data-table-datos-idiomas', [item.Id, item.NombreIdioma, item.NivelComprension, item.NivelLectura, item.NivelEscritura, item.Comentarios, botones, item.Idioma, item.Comprension, item.Lectura, item.Escritura]);
    });
};

Usuario_perfil.prototype.RecargandoTablaSoftware = function (datosSoftware) {
    var tabla = new Tabla();
    tabla.limpiarTabla('#data-table-datos-computacionales');
    $.each(datosSoftware, function (key, item) {
        var botones = '<a href="javascript:;" class="btn btn-success btn-xs btn-actualizar-software" data-id-software="' + item.Id + '"><i class="fa fa-pencil"></i> Actualizar</a> <a href="javascript:;" class="btn btn-danger btn-xs btn-eliminar-software" data-id-software="' + item.Id + '"></i> Eliminar</a>';
        tabla.agregarFila('#data-table-datos-computacionales', [item.Id, item.Software, item.Nivel, item.Comentarios, botones, item.IdSoftware, item.IdNivelHabilidad]);
    });
};

Usuario_perfil.prototype.RecargandoTablaSistemas = function (datosSistemas) {
    var tabla = new Tabla();
    tabla.limpiarTabla('#data-table-datos-sistemas-especiales');
    $.each(datosSistemas, function (key, item) {
        var botones = '<a href="javascript:;" class="btn btn-success btn-xs btn-actualizar-sistema" data-id-sistema="' + item.Id + '"><i class="fa fa-pencil"></i> Actualizar</a> <a href="javascript:;" class="btn btn-danger btn-xs btn-eliminar-sistema" data-id-sistema="' + item.Id + '"></i> Eliminar</a>';
        tabla.agregarFila('#data-table-datos-sistemas-especiales', [item.Id, item.Sistema, item.Nivel, item.Comentarios, botones, item.IdSistema, item.IdNivelHabilidad]);
    });
};

Usuario_perfil.prototype.RecargandoTablaDependientes = function (datosDependientes) {
    var tabla = new Tabla();
    tabla.limpiarTabla('#data-table-datos-dependientes-economicos');
    $.each(datosDependientes, function (key, item) {
        var botones = '<a href="javascript:;" class="btn btn-success btn-xs btn-actualizar-dependiente" data-id-dependiente="' + item.Id + '"><i class="fa fa-pencil"></i> Actualizar</a> <a href="javascript:;" class="btn btn-danger btn-xs btn-eliminar-dependiente" data-id-dependiente="' + item.Id + '"></i> Eliminar</a>';
        tabla.agregarFila('#data-table-datos-dependientes-economicos', [item.Id, item.Nombre, item.Parentesco, item.FechaNacimiento, botones]);
    });
};

Usuario_perfil.prototype.BotonActualizarAcademico = function () {
    var nivelEstudio = arguments[0];
    var documentoEstudio = arguments[1];
    var idUsuario = arguments[2];
    var _this = this;

    $('#data-table-datos-academicos tbody').on('click', '.btn-actualizar-academico', function (e) {
        var idAcademico = $(this).data('id-academico');
        _this.ActualizarDatosAcademicos(idAcademico, nivelEstudio, documentoEstudio, idUsuario);
    });
};

Usuario_perfil.prototype.BotonActualizarIdioma = function () {
    var catalogoIdiomas = arguments[0];
    var catalogoNivelHabilidades = arguments[1];
    var idUsuario = arguments[2];
    var _this = this;

    $('#data-table-datos-idiomas tbody').on('click', '.btn-actualizar-idioma', function (e) {
        var idIdioma = $(this).data('id-idioma');
        _this.ActualizarDatosIdiomas(idIdioma, catalogoIdiomas, catalogoNivelHabilidades, idUsuario);
    });
};

Usuario_perfil.prototype.BotonActualizarSoftware = function () {
    var catalogoHabilidadesSoftware = arguments[0];
    var catalogoNivelHabilidades = arguments[1];
    var idUsuario = arguments[2];
    var _this = this;

    $('#data-table-datos-computacionales tbody').on('click', '.btn-actualizar-software', function (e) {
        var idSoftware = $(this).data('id-software');
        _this.ActualizarDatosSoftware(idSoftware, catalogoHabilidadesSoftware, catalogoNivelHabilidades, idUsuario);
    });
};

Usuario_perfil.prototype.BotonActualizarSistema = function () {
    var catalogoHabilidadesSistema = arguments[0];
    var catalogoNivelHabilidades = arguments[1];
    var idUsuario = arguments[2];
    var _this = this;

    $('#data-table-datos-sistemas-especiales tbody').on('click', '.btn-actualizar-sistema', function (e) {
        var idSistema = $(this).data('id-sistema');
        _this.ActualizarDatosSistemas(idSistema, catalogoHabilidadesSistema, catalogoNivelHabilidades, idUsuario);
    });
};

Usuario_perfil.prototype.BotonActualizarDependiente = function () {
    var _this = this;
    var idUsuario = arguments[0];

    $('#data-table-datos-dependientes-economicos tbody').on('click', '.btn-actualizar-dependiente', function (e) {
        var idDependiente = $(this).data('id-dependiente');
        _this.ActualizarDatosDependientes(idDependiente, idUsuario);
    });
};

Usuario_perfil.prototype.BotonEliminarAcademico = function () {
    var _this = this;
    var idUsuario = arguments[0];
    var catalogoNivelEstudio = arguments[1];
    var catalogoDocumentoEstudio = arguments[2];


    $('#data-table-datos-academicos tbody').on('click', '.btn-eliminar-academico', function (e) {
        var idAcademico = $(this).data('id-academico');
        _this.EliminarDatos(idAcademico, 'academicos', idUsuario, catalogoNivelEstudio, catalogoDocumentoEstudio);
    });
}

Usuario_perfil.prototype.BotonEliminarIdioma = function () {
    var _this = this;
    var idUsuario = arguments[0];
    var catalogoIdiomas = arguments[1];
    var catalogoNivelHabilidades = arguments[2];


    $('#data-table-datos-idiomas tbody').on('click', '.btn-eliminar-idioma', function (e) {
        var idIdioma = $(this).data('id-idioma');
        _this.EliminarDatos(idIdioma, 'idiomas', idUsuario, catalogoIdiomas, catalogoNivelHabilidades);
    });
}

Usuario_perfil.prototype.BotonEliminarSoftware = function () {
    var _this = this;
    var idUsuario = arguments[0];
    var catalogoSoftware = arguments[1];
    var catalogoNivelHabilidades = arguments[2];


    $('#data-table-datos-computacionales tbody').on('click', '.btn-eliminar-software', function (e) {
        var idSoftware = $(this).data('id-software');
        _this.EliminarDatos(idSoftware, 'software', idUsuario, catalogoSoftware, catalogoNivelHabilidades);
    });
}

Usuario_perfil.prototype.BotonEliminarSistema = function () {
    var _this = this;
    var idUsuario = arguments[0];
    var catalogoSistema = arguments[1];
    var catalogoNivelHabilidades = arguments[2];

    $('#data-table-datos-sistemas-especiales tbody').on('click', '.btn-eliminar-sistema', function (e) {
        var idSistema = $(this).data('id-sistema');
        _this.EliminarDatos(idSistema, 'sistemas', idUsuario, catalogoSistema, catalogoNivelHabilidades);
    });
}

Usuario_perfil.prototype.BotonEliminarDependiente = function () {
    var _this = this;
    var idUsuario = arguments[0];

    $('#data-table-datos-dependientes-economicos tbody').on('click', '.btn-eliminar-dependiente', function (e) {
        var idDependiente = $(this).data('id-dependiente');
        _this.EliminarDatos(idDependiente, 'dependientes', idUsuario, [], []);
    });
};

Usuario_perfil.prototype.MostrarCargaPaginaInformacionUsuario = function (objeto) {
    $('#cargandoInformacionUsuario').removeClass('hidden');
    $(objeto).addClass('hidden');
};

Usuario_perfil.prototype.OcultarCargaPaginaInformacionUsuario = function (objeto) {
    $('#cargandoInformacionUsuario').addClass('hidden');
    $(objeto).removeClass('hidden');
};

Usuario_perfil.prototype.EliminarDatos = function () {
    var _this = this;
    var evento = new Base();
    var id = arguments[0];
    var tabla = arguments[1];
    var idUsuario = arguments[2];
    var catalogo1 = arguments[3];
    var catalogo2 = arguments[4];
    var data = {id: id, tabla: tabla, idUsuario: idUsuario};

    switch (tabla) {
        case 'academicos':
            _this.MostrarCargaPaginaInformacionUsuario('#nav-tab-academicos');
            break;
        case 'idiomas':
            _this.MostrarCargaPaginaInformacionUsuario('#nav-tab-idiomas');
            break;
        case 'software':
            _this.MostrarCargaPaginaInformacionUsuario('#computacionales');
            break;
        case 'sistemas':
            _this.MostrarCargaPaginaInformacionUsuario('#sistemasEspeciales');
            break;
        case 'dependientes':
            _this.MostrarCargaPaginaInformacionUsuario('#dependientesEconomicos');
            break;
    }

    evento.enviarEvento('/Configuracion/PerfilUsuario/EliminarDatos', data, '#modalEdit', function (respuesta) {
        switch (tabla) {
            case 'academicos':
                _this.RespuestaAcademicos(respuesta, catalogo1, catalogo2, idUsuario);
                break;
            case 'idiomas':
                _this.RespuestaIdiomas(respuesta, catalogo1, catalogo2, idUsuario);
                break;
            case 'software':
                _this.RespuestaSoftware(respuesta, catalogo1, catalogo2, idUsuario);
                break;
            case 'sistemas':
                _this.RespuestaSistemas(respuesta, catalogo1, catalogo2, idUsuario);
                break;
            case 'dependientes':
                _this.RespuestaDependientes(respuesta, idUsuario);
                break;
        }

    });
};

Usuario_perfil.prototype.ActualizarDatosAcademicos = function () {
    var idAcademico = arguments[0];
    var catalogoNivelEstudio = arguments[1];
    var catalogoDocumentoEstudio = arguments[2];
    var idUsuario = arguments[3];
    var _this = this;
    var evento = new Base();
    var select = new Select();
    var calendario = new Fecha();

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
            hasta: hasta,
            idUsuario: idUsuario
        };

        var arrayCampos = [
            {'objeto': '#actualizarNivelEstudioUsuario', 'mensajeError': 'Falta seleccionar el campo Nivel de Estudio.'},
            {'objeto': '#actualizarNombreInstitutoUsuario', 'mensajeError': 'Falta seleccionarel campo Nombre de la Institución.'},
            {'objeto': '#actualizarDocumentoRecibidoUsuario', 'mensajeError': 'Falta seleccionar el campo DocumentoRecibido.'},
            {'objeto': '#actualizarDesdeUsuario', 'mensajeError': 'Falta seleccionar el campo Desde.'},
            {'objeto': '#actualizarHastaUsuario', 'mensajeError': 'Falta escribir el campo Hasta.'}
        ];

        var camposFormularioValidados = evento.validarCamposObjetos(arrayCampos, '#errorAcademicosUsuario');

        _this.MostrarCargaPaginaInformacionUsuario('#nav-tab-academicos');

        if (camposFormularioValidados) {
            evento.enviarEvento('/Configuracion/PerfilUsuario/ActualizarDatosAcademicosUsuario', data, '#modalEdit', function (respuesta) {
                _this.RespuestaAcademicos(respuesta, catalogoNivelEstudio, catalogoDocumentoEstudio, idUsuario);
            });
        }
    });

    _this.CerrarModalCambios();

};

Usuario_perfil.prototype.ActualizarDatosIdiomas = function () {
    var idIdioma = arguments[0];
    var catalogoHabilidadesIdioma = arguments[1];
    var catalogoNivelHabilidades = arguments[2];
    var idUsuario = arguments[3];
    var _this = this;
    var evento = new Base();
    var select = new Select();

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
            comentarios: comentarios,
            idUsuario: idUsuario
        };

        var arrayCampos = [
            {'objeto': '#actualizarIdiomaUsuario', 'mensajeError': 'Falta seleccionar el campo Nivel de Idioma.'},
            {'objeto': '#actualizarComprensionUsuario', 'mensajeError': 'Falta seleccionarel campo Comprensión.'},
            {'objeto': '#actualizarLecturaUsuario', 'mensajeError': 'Falta seleccionar el campo Lectura.'},
            {'objeto': '#actualizarEscrituraUsuario', 'mensajeError': 'Falta seleccionar el campo Escritura.'}
        ];

        var camposFormularioValidados = evento.validarCamposObjetos(arrayCampos, '#errorIdiomasUsuario');

        _this.MostrarCargaPaginaInformacionUsuario('#nav-tab-idiomas');

        if (camposFormularioValidados) {
            evento.enviarEvento('/Configuracion/PerfilUsuario/ActualizarDatosIdiomasUsuario', data, '#modalEdit', function (respuesta) {
                _this.RespuestaIdiomas(respuesta, catalogoHabilidadesIdioma, catalogoNivelHabilidades, idUsuario);
            });
        }
    });

    _this.CerrarModalCambios();
};

Usuario_perfil.prototype.ActualizarDatosSoftware = function () {
    var _this = this;
    var evento = new Base();
    var select = new Select();
    var idSoftware = arguments[0];
    var catalogoHabilidadesSoftware = arguments[1];
    var catalogoNivelHabilidades = arguments[2];
    var idUsuario = arguments[3];

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
            comentarios: comentarios,
            idUsuario: idUsuario
        };

        var arrayCampos = [
            {'objeto': '#actualizarSoftwareUsuario', 'mensajeError': 'Falta seleccionar el campo Software.'},
            {'objeto': '#actualizarNivelComputacionalesUsuario', 'mensajeError': 'Falta seleccionarel campo Nivel.'}
        ];

        var camposFormularioValidados = evento.validarCamposObjetos(arrayCampos, '#errorSoftwareUsuario');

        _this.MostrarCargaPaginaInformacionUsuario('#nav-tab-computacionales');

        if (camposFormularioValidados) {
            evento.enviarEvento('/Configuracion/PerfilUsuario/ActualizarDatosSoftwareUsuario', data, '#modalEdit', function (respuesta) {
                _this.RespuestaSoftware(respuesta, catalogoHabilidadesSoftware, catalogoNivelHabilidades, idUsuario);
            });
        }
    });

    _this.CerrarModalCambios();
};

Usuario_perfil.prototype.ActualizarDatosSistemas = function () {
    var _this = this;
    var evento = new Base();
    var select = new Select();
    var idSistema = arguments[0];
    var catalogoHabilidadesSistema = arguments[1];
    var catalogoNivelHabilidades = arguments[2];
    var idUsuario = arguments[3];

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
            comentarios: comentarios,
            idUsuario: idUsuario
        };

        var arrayCampos = [
            {'objeto': '#actualizarSistemasUsuario', 'mensajeError': 'Falta seleccionar el campo Sitemas.'},
            {'objeto': '#actualizarNivelSistemasUsuario', 'mensajeError': 'Falta seleccionarel campo Nivel.'}
        ];

        var camposFormularioValidados = evento.validarCamposObjetos(arrayCampos, '#errorSistemaUsuario');

        _this.MostrarCargaPaginaInformacionUsuario('#nav-tab-sistemas-especiales');

        if (camposFormularioValidados) {
            evento.enviarEvento('/Configuracion/PerfilUsuario/ActualizarDatosSistemasUsuario', data, '#modalEdit', function (respuesta) {
                _this.RespuestaSistemas(respuesta, catalogoHabilidadesSistema, catalogoNivelHabilidades, idUsuario);
            });
        }
    });

    _this.CerrarModalCambios();
};

Usuario_perfil.prototype.ActualizarDatosDependientes = function () {
    var _this = this;
    var evento = new Base();
    var calendario = new Fecha();
    var select = new Select();
    var idDependiente = arguments[0];
    var idUsuario = arguments[1];

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
            fechaNacimiento: fechaNacimiento,
            idUsuario: idUsuario
        };

        var arrayCampos = [
            {'objeto': '#actualizarNombreDependienteUsuario', 'mensajeError': 'Falta seleccionar el campo Nombre.'},
            {'objeto': '#actualizarParentescoUsuario', 'mensajeError': 'Falta seleccionarel campo Parentesco.'},
            {'objeto': '#actualizarParentescoVigenciaUsuario', 'mensajeError': 'Falta seleccionarel campo Fecha de Nacimiento.'}
        ];

        var camposFormularioValidados = evento.validarCamposObjetos(arrayCampos, '#errorDependienteUsuario');

        _this.MostrarCargaPaginaInformacionUsuario('#nav-tab-dependientes-economicos');

        if (camposFormularioValidados) {
            evento.enviarEvento('/Configuracion/PerfilUsuario/ActualizarDatosDependientesUsuario', data, '#modalEdit', function (respuesta) {
                _this.RespuestaDependientes(respuesta, idUsuario);
            });
        }
    });

    _this.CerrarModalCambios();
};

Usuario_perfil.prototype.CerrarModalCambios = function () {
    var _this = this;
    var evento = new Base();

    $('#btnCerrarCambios').off('click');
    $('#btnCerrarCambios').on('click', function () {
        evento.terminarModal('#modalEdit');
        $('#cargando').addClass('hidden');
    });
};

Usuario_perfil.prototype.RespuestaAcademicos = function (respuesta) {
    var _this = this;
    var evento = new Base();
    var respuesta = arguments[0];
    var catalogoNivelEstudio = arguments[1];
    var catalogoDocumentoEstudio = arguments[2];
    var idUsuario = arguments[3];

    _this.OcultarCargaPaginaInformacionUsuario('#nav-tab-academicos');
    if (respuesta instanceof Array || respuesta instanceof Object) {
        _this.RecargandoTablaAcademicos(respuesta);
        evento.terminarModal('#modalEdit');
        _this.BotonActualizarAcademico(catalogoNivelEstudio, catalogoDocumentoEstudio, idUsuario);
        _this.BotonEliminarAcademico(idUsuario, catalogoNivelEstudio, catalogoDocumentoEstudio);
    } else {
        evento.mostrarMensaje("#errorAcademicosUsuario", false, "Hubo un error contacte al administrador de AdIST.", 4000);
    }
};

Usuario_perfil.prototype.RespuestaIdiomas = function (respuesta) {
    var _this = this;
    var evento = new Base();
    var respuesta = arguments[0];
    var catalogoHabilidadesIdioma = arguments[1];
    var catalogoNivelHabilidades = arguments[2];
    var idUsuario = arguments[3];

    _this.OcultarCargaPaginaInformacionUsuario('#nav-tab-idiomas');
    if (respuesta instanceof Array || respuesta instanceof Object) {
        _this.RecargandoTablaIdiomas(respuesta);
        evento.terminarModal('#modalEdit');
        _this.BotonActualizarIdioma(catalogoHabilidadesIdioma, catalogoNivelHabilidades, idUsuario);
        _this.BotonEliminarIdioma(idUsuario, catalogoHabilidadesIdioma, catalogoNivelHabilidades);
    } else {
        evento.mostrarMensaje("#errorIdiomasUsuario", false, "Hubo un error contacte al administrador de AdIST.", 4000);
    }
};

Usuario_perfil.prototype.RespuestaSoftware = function (respuesta) {
    var _this = this;
    var evento = new Base();
    var respuesta = arguments[0];
    var catalogoHabilidadesSoftware = arguments[1];
    var catalogoNivelHabilidades = arguments[2];
    var idUsuario = arguments[3];

    _this.OcultarCargaPaginaInformacionUsuario('#nav-tab-computacionales');

    if (respuesta instanceof Array || respuesta instanceof Object) {
        _this.RecargandoTablaSoftware(respuesta);
        evento.terminarModal('#modalEdit');
        _this.BotonActualizarSoftware(catalogoHabilidadesSoftware, catalogoNivelHabilidades, idUsuario);
        _this.BotonEliminarSoftware(idUsuario, catalogoHabilidadesSoftware, catalogoNivelHabilidades);
    } else {
        evento.mostrarMensaje("#errorSoftwareUsuario", false, "Hubo un error contacte al administrador de AdIST.", 4000);
    }
};

Usuario_perfil.prototype.RespuestaSistemas = function (respuesta) {
    var _this = this;
    var evento = new Base();
    var respuesta = arguments[0];
    var catalogoHabilidadesSistema = arguments[1];
    var catalogoNivelHabilidades = arguments[2];
    var idUsuario = arguments[3];

    this.OcultarCargaPaginaInformacionUsuario('#nav-tab-sistemas-especiales');

    if (respuesta instanceof Array || respuesta instanceof Object) {
        _this.RecargandoTablaSistemas(respuesta);
        evento.terminarModal('#modalEdit');
        _this.BotonActualizarSistema(catalogoHabilidadesSistema, catalogoNivelHabilidades, idUsuario);
        _this.BotonEliminarSistema(idUsuario, catalogoHabilidadesSistema, catalogoNivelHabilidades);
    } else {
        evento.mostrarMensaje("#errorSistemaUsuario", false, "Hubo un error contacte al administrador de AdIST.", 4000);
    }
};

Usuario_perfil.prototype.RespuestaDependientes = function (respuesta) {
    var _this = this;
    var evento = new Base();
    var respuesta = arguments[0];
    var idUsuario = arguments[1];

    _this.OcultarCargaPaginaInformacionUsuario('#nav-tab-dependientes-economicos');
    if (respuesta instanceof Array || respuesta instanceof Object) {
        _this.RecargandoTablaDependientes(respuesta);
        evento.terminarModal('#modalEdit');
        _this.BotonActualizarDependiente(idUsuario);
        _this.BotonEliminarDependiente(idUsuario);
    } else {
        evento.mostrarMensaje("#errorDependienteUsuario", false, "Hubo un error contacte al administrador de AdIST.", 4000);
    }
};