$(function () {

    var evento = new Base();
    var websocket = new Socket();
    var file = new Upload();
    var file3 = new Upload();
    var tabla = new Tabla();
    var select = new Select();
    var servicios = new Servicio();
    var nota = new Nota();
    var dataCategoria;
    eventoAuxiliar = new Base();
    tablaAuxiliar = new Tabla();
    servicioAuxiliar = new Servicio();
    //Evento que maneja las peticiones del socket
    websocket.socketMensaje();
    //Muestra la hora en el sistema
    evento.horaServidor($('#horaServidor').val());
    //Evento para cerra la session
    evento.cerrarSesion();
    //Creando tabla de areas
    tabla.generaTablaPersonal('#data-table-poliza', null, null, true, true, [[0, 'desc']]);
    //Evento para mostrar la ayuda del sistema
    evento.mostrarAyuda('Ayuda_Proyectos');
    //Inicializa funciones de la plantilla
    App.init();

    $('#btnBuscarFolio').off('click');
    $('#btnBuscarFolio').on('click', function () {
        var folio = $('#inputBuscarFolio').val();
        if (folio !== '') {
            var data = {departamento: '11', folio: folio};
            evento.enviarEvento('Seguimiento/MostrarServicios', data, '#panelSeguimientoPoliza', function (respuesta) {
                if (respuesta !== false) {
                    recargandoTablaPoliza(respuesta);
                } else {
                    evento.mostrarMensaje('.errorListaPoliza', false, 'No hay servicios con ese folio.', 4000);
                }
            });
        } else {
            evento.mostrarMensaje('.errorListaPoliza', false, 'Agrega el folio.', 3000);
        }
    });

    $('#btnMostrarServicios').off('click');
    $('#btnMostrarServicios').on('click', function () {
        $('#inputBuscarFolio').val('');
        var data = {departamento: '11'};
        evento.enviarEvento('Seguimiento/MostrarServicios', data, '#panelSeguimientoPoliza', function (respuesta) {
            recargandoTablaPoliza(respuesta);
        });
    });

//Evento que carga la seccion de seguimiento de un servicio de tipo Poliza
    $('#data-table-poliza tbody').on('click', 'tr', function () {
        var datos = $('#data-table-poliza').DataTable().row(this).data();
        if (datos !== undefined) {
            var servicio = datos[0];
            var operacion = datos[7];
            if (operacion === '1') {
                var html = '<div id="confirmacionServicioPoliza">\n\
                                <div class="row">\n\
                                    <div id="mensaje-modal" class="col-md-12 text-center">\n\
                                        <h3>¿Quieres atender el servicio?</h3>\n\
                                    </div>\n\
                                </div>\n\
                            </div>';
                html += '<div class="row m-t-20">\n\
                                <div class="col-md-12 text-center">\n\
                                    <button id="btnIniciarServicio" type="button" class="btn btn-sm btn-success"><i class="fa fa-check"></i> Aceptar</button>\n\
                                    <button id="btnCancelarIniciarServicio" type="button" class="btn btn-sm btn-danger"><i class="fa fa-times"></i> Cerrar</button>\n\
                                </div>\n\
                            </div>';
                $('#btnModalConfirmar').addClass('hidden');
                $('#btnModalAbortar').addClass('hidden');
                evento.mostrarModal('Iniciar Servicio', html);
                $('#btnModalConfirmar').empty().append('Eliminar');
                $('#btnModalConfirmar').off('click');
                $('#btnIniciarServicio').off('click');
                $('#btnIniciarServicio').on('click', function () {
                    $(this).addClass('disabled');
                    $('#btnCancelarIniciarServicio').addClass('disabled');
                    var data = {servicio: servicio, operacion: '1'};
                    evento.enviarEvento('Seguimiento/Servicio_Datos', data, '#modal-dialogo', function (respuesta) {
                        evento.cerrarModal();
                        data = {servicio: servicio, operacion: '2'};
                        cargarFormularioSeguimiento(data, datos, '#panelSeguimientoPoliza');
                        recargandoTablaPoliza(respuesta.informacion);
                    });
                });
                //Envento para concluir con la cancelacion
                $('#btnCancelarIniciarServicio').on('click', function () {
                    evento.cerrarModal();
                });
            } else if (operacion === '2' || operacion === '3' || operacion === '12' || operacion === '10') {
                var data = {servicio: servicio, operacion: '2'};
                cargarFormularioSeguimiento(data, datos, '#panelSeguimientoPoliza');
            }
        }
    });
    var cargarFormularioSeguimiento = function () {
        var data = arguments[0];
        var datosTabla = arguments[1];
        var panel = arguments[2];
        evento.enviarEvento('Seguimiento/Servicio_Datos', data, panel, function (respuesta) {
            var datosDelServicio = respuesta.datosServicio;
            var formulario = respuesta.formulario;
            var archivo = respuesta.archivo;
            var avanceServicio = respuesta.avanceServicio;
            var datosSD = respuesta.datosSD;
            if (datosDelServicio.tieneSeguimiento === '0') {
                var idSucursal = respuesta.idSucursal[0].IdSucursal;
                var idPerfil = respuesta.idPerfil;
                servicios.ServicioSinClasificar(
                        formulario,
                        '#listaPoliza',
                        '#seccionSeguimientoServicio',
                        datosTabla[0],
                        datosDelServicio,
                        'Seguimiento',
                        archivo,
                        '#panelSeguimientoPoliza',
                        datosTabla[1],
                        avanceServicio,
                        idSucursal,
                        datosSD,
                        datosTabla[3],
                        idPerfil
                        );
            } else {
                switch (datosDelServicio.IdTipoServicio) {
                    //Servicio Censo
                    case '11':
                        iniciarElementosPaginaSeguimientoCenso(respuesta, datosTabla);
                        eventosParaSeccionSeguimientoCenso(datosTabla, respuesta);
                        personalizarDependiendoSucursalCenso(respuesta);
                        colocarBotonGuardarCambiosCenso(respuesta.datosServicio);
                        break;
                        //Servicio Mantemiento
                    case '12':
                        iniciarElementosPaginaSeguimientoMantenimiento(respuesta, datosTabla);
                        eventosParaSeccionSeguimientoMantenimiento(datosTabla, respuesta);
                        personalizarDependiendoSucursalMantenimiento(respuesta);
                        colocarBotonGuardarCambiosMantenimiento(respuesta.datosServicio);
                        break;
                    case '20':
                        iniciarElementosPaginaSeguimientoCorrectivo(respuesta, datosTabla);
                        eventosParaSeccionSeguimientoCorrectivo(datosTabla, respuesta);
                        personalizarDependiendoSucursalCorrectivo(respuesta);
                        break;
                        //Servicio Checklist
                    case '27':
                        iniciarVistaChecklist(data, datosTabla, respuesta);
                        eventosChecklist(datosTabla, respuesta);
                        break;
                }
            }
        });
    };
    // inicia servicio Checklist
    var iniciarVistaChecklist = function (data, datosTabla, respuesta) {
        var sucursal = respuesta.informacion.sucursal;
        $('#listaPoliza').addClass('hidden');
        $('#seccionSeguimientoServicio').removeClass('hidden').empty().append(respuesta.formulario);
        select.crearSelect('#selectSucursales');
        tabla.generaTablaPersonal('#tabla-categorias', null, null, true, true, [], true, 'lfrtip', false);
        if (sucursal !== "0" || sucursal !== null) {
            mostrarTabla(sucursal, respuesta, datosTabla);
        }

        $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
            var target = $(e.target).attr("href");
            switch (target) {
                case "#revisionTecnica":
                    iniciarRevisionTecnica();
                    limpiarRevisionArea();
                    limpiarRevisionPunto();
                    break;
                case "#informacionRevision":
                    limpiarRevisionArea();
                    limpiarRevisionPunto();
                    break;
                case "#revisionPunto":
                    limpiarRevisionArea();
                    break;
                case "#revisionArea":
                    limpiarRevisionPunto();
                    break;
            }
        });
        $('#concluirServicioChecklist').on('click', function () {
            var servicio = $('#hiddenServicio').val();
            var sucursal = $('#selectSucursales option:selected').val();
            if (sucursal !== '') {
                var datosServicio = {'servicio': servicio};
                evento.enviarEvento('Seguimiento/MostrarDatosServicio', datosServicio, '#seguimiento-checklist', function (respuesta) {
                    if (respuesta.sucursal) {
                        concluirServicioChecklist(servicio, datosTabla, sucursal);
                    } else {
                        evento.mostrarMensaje('#errorInformacionGeneral', false, 'Tienes informacion incompleta', 3000);
                    }
                });
            } else {
                evento.mostrarMensaje('#errorInformacionGeneral', false, 'Agrega la sucursal', 3000);
            }
        });
    };
    var limpiarRevisionArea = function () {

        $('.categoriaRevisionArea li').each(function () {
            $(this).removeClass('active');
        });
        $('.categoriaRevisionArea li').parent().siblings('#guardarListaPregunta').addClass('hidden');
        $('.categoriaRevisionArea li').parent().siblings('#listaPregunta').addClass('hidden');
    };
    var limpiarRevisionPunto = function () {
        $('.categoriaRevisionPunto li').each(function () {
            $(this).removeClass('active');
        });
        $('.categoriaRevisionPunto li').parent().siblings('#checklistRevisionPunto').addClass('hidden');
    };
    var concluirServicioChecklist = function () {
        var servicio = arguments[0];
        var datosTabla = arguments[1];
        var sucursal = arguments[2];
        var ticket = datosTabla[1];
        var htmlFirmaExtra = htmlCampoTecnicoFirma();
        evento.mostrarModal('Firma', servicios.modalCampoFirmaExtra(htmlFirmaExtra, 'Firma'));
        $('#btnModalConfirmar').addClass('hidden');
        var myBoardFirma = servicios.campoLapiz('campoLapiz');
        var myBoardTecnico = servicios.campoLapiz('campoLapizTecnico');
        $('#btnGuardarFirma').off('click');
        $('#btnGuardarFirma').on('click', function () {
            var img = myBoardFirma.getImg();
            var imgInput = (myBoardFirma.blankCanvas == img) ? '' : img;
            var imgTecnico = myBoardTecnico.getImg();
            var imgInputTecnico = (myBoardTecnico.blankCanvas == imgTecnico) ? '' : imgTecnico;
            var personaRecibe = $('#inputRecibeFirma').val();
            var correo = $("#tagValor").tagit("assignedTags");
            if (personaRecibe !== '') {
                if (correo.length > 0) {
                    if (servicios.validarCorreoArray(correo)) {
                        if (imgInput !== '') {
                            if (imgInputTecnico !== '') {
                                if ($('#terminos').attr('checked')) {
                                    var dataInsertar = {'ticket': ticket, 'servicio': servicio, 'img': img, imgFirmaTecnico: imgTecnico, 'correo': correo, 'recibe': personaRecibe, 'sucursal': sucursal};
                                    evento.enviarEvento('Seguimiento/GuardarConclusionChecklist', dataInsertar, '#modal-dialogo', function (respuesta) {
                                        if (respuesta) {
                                            servicios.mensajeModal('Servicio concluido.', 'Correcto');
                                        } else {
                                            evento.mostrarMensaje('.errorFirma', false, 'Tienes informacion sin concluir', 3000);
                                        }
                                    });
                                } else {
                                    evento.mostrarMensaje('.errorFirma', false, 'Debes aceptar terminos.', 3000);
                                }
                            } else {
                                evento.mostrarMensaje('.errorFirma', false, 'Debes llenar el campo Firma del Técnico.', 3000);
                            }
                        } else {
                            evento.mostrarMensaje('.errorFirma', false, 'Debes llenar el campo Firma del gerente de conformidad.', 3000);
                        }
                    } else {
                        evento.mostrarMensaje('.errorFirma', false, 'Algun Correo no es correcto.', 3000);
                    }
                } else {
                    evento.mostrarMensaje('.errorFirma', false, 'Debe insertar al menos un correo.', 3000);
                }
            } else {
                evento.mostrarMensaje('.errorFirma', false, 'Debe escribir el nombre del gerente.', 3000);
            }
        });
    };
    var mostrarTabla = function (sucursal, respuesta, datosTabla) {
        $('#tab-checklist > li').removeClass('disabled');
        $('#tab-checklist > li > a[href="#revisionArea"]').attr('data-toggle', 'tab');
        $('[id*=categoria-]').off('click');
        $('[id*=categoria-]').on('click', function () {

            $('#listaPregunta').removeClass('hidden');
            $('#guardarListaPregunta').removeClass('hidden');
            dataCategoria = $(this).data('id-categoria');
            mostrarPreguntaPorCategoria(dataCategoria, sucursal, respuesta);
        });
        $('#tab-checklist > li > a[href="#revisionPunto"]').attr('data-toggle', 'tab');
        $('[id*=categoriaPunto-]').off('click');
        $('[id*=categoriaPunto-]').on('click', function () {

            $('#checklistRevisionPunto').removeClass('hidden');
            dataCategoria = $(this).data('id-categoria-punto');
            mostrarPuntoPorCategoria(dataCategoria, sucursal, respuesta, datosTabla);
        });
        $('#tab-checklist > li > a[href="#revisionTecnica"]').attr('data-toggle', 'tab');
    };
    var iniciarRevisionTecnica = function () {
        var servicio = $('#hiddenServicio').val();
        evento.enviarEvento('Seguimiento/RevisionTecnica', {'servicio': servicio}, '#seguimiento-checklist', function (respuesta) {
            $('#revisionTecnica').empty().append(respuesta.html);
            var _sucursal = respuesta.sucursal;
            var _tipoDiagnostico;
            select.crearSelect('#selectAreaPunto');
            select.crearSelect('#selectEquipo');
            select.crearSelect('#selectImpericiaTipoFallaEquipoCorrectivo');
            select.crearSelect('#selectTipoFallaEquipoCorrectivo');
            select.crearSelect('#selectComponenteDiagnosticoCorrectivo');
            select.crearSelect('#selectImpericiaFallaDiagnosticoCorrectivo');
            select.crearSelect('#selectFallaDiagnosticoCorrectivo');
            select.crearSelect('#selectTipoFallaComponenteCorrectivo');
            file.crearUpload('#evidenciasImpericiaCorrectivo', 'Seguimiento/GuardarRevisionTecnicaChecklist', null, null, null, null, 'evidenciasImpericiaCorrectivo');
            file.crearUpload('#evidenciasFallaEquipoCorrectivo', 'Seguimiento/GuardarRevisionTecnicaChecklist', null, null, null, null, 'evidenciasFallaEquipoCorrectivo');
            file.crearUpload('#evidenciasFallaComponenteCorrectivo', 'Seguimiento/GuardarRevisionTecnicaChecklist', null, null, null, 'evidenciasFallaComponenteCorrectivo');
            file.crearUpload('#evidenciasReporteMultimediaCorrectivo', 'Seguimiento/GuardarRevisionTecnicaChecklist', null, null, null, 'evidenciasReporteMultimediaCorrectivo');
            tabla.generaTablaPersonal('#tablaFallasTecnicas', null, null, true, true, [], true, 'lfrtip', false);
            $('#selectAreaPunto').on('change', function () {
                var _this = $('#selectAreaPunto option:selected');
                var _value = _this.val();
                $('#selectEquipo').empty().append('<option value="">Seleccionar</option>');
                if (_value !== '') {

                    var datos = {'sucursal': _sucursal, 'area': _this.attr('data-area'), 'punto': _this.attr('data-punto')};
                    evento.enviarEvento('Seguimiento/ConsultaEquipoXAreaPuntoUltimoCenso', datos, '#seguimiento-checklist', function (respuesta) {

                        $.each(respuesta, function (k, v) {
                            $('#selectEquipo').append('<option data-terminal="' + v.Extra + '"  data-serie="' + v.Serie + '" data-modelo="' + v.IdModelo + '" value="' + v.Serie + '">' + v.Equipo + ' (' + v.Serie + ')</option>');
                        });
                    });
                    $('#selectEquipo').removeAttr('disabled');
                } else {
                    $('#selectEquipo').attr('disabled', 'disabled');
                    select.cambiarOpcion('#selectEquipo', '');
                }
            });
            $('#clasificacionesFalla li a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
                var target = $(e.target).attr("href");
                initDiagnosticoEquipo(target, servicio);
            });
            $('#selectEquipo').on('change', function () {
                var _this = $('#selectEquipo option:selected');
                var _value = _this.val();
                if (_value !== '') {
                    $('#clasificacionesFalla li').each(function () {
                        $(this).removeClass('active');
                    });
                    $('#contentClasificacionesFalla .tab-pane').each(function () {
                        $(this).removeClass('active in');
                    });
                    evento.mostraDiv('#divDiagnosticoEquipo');
                } else {
                    evento.ocultarDiv('#divDiagnosticoEquipo');
                    select.cambiarOpcion('#selectImpericiaTipoFallaEquipoCorrectivo', '');
                    select.cambiarOpcion('#selectTipoFallaEquipoCorrectivo', '');
                    select.cambiarOpcion('#selectComponenteDiagnosticoCorrectivo', '');
                }
            });
            $('#selectImpericiaTipoFallaEquipoCorrectivo').on('change', function () {
                var _modelo = $('#selectEquipo option:selected').attr('data-modelo');
                var tipoFalla = $(this).val();
                var datos = {'tipoFalla': tipoFalla, 'equipo': _modelo};
                $('#selectImpericiaFallaDiagnosticoCorrectivo').empty().append('<option value="">Seleccionar</option>').attr('disabled', 'disabled');
                select.cambiarOpcion('#selectImpericiaFallaDiagnosticoCorrectivo', '');
                evento.enviarEvento('Seguimiento/ConsultaFallasEquiposXTipoFallaYEquipo', datos, '#seguimiento-checklist', function (respuesta) {
                    $.each(respuesta, function (k, v) {
                        $('#selectImpericiaFallaDiagnosticoCorrectivo').append('<option value="' + v.Id + '">' + v.Nombre + '</option>');
                    });
                    if (respuesta.length > 0) {
                        $('#selectImpericiaFallaDiagnosticoCorrectivo').removeAttr('disabled');
                    }
                });
            });
            $('#selectTipoFallaEquipoCorrectivo').on('change', function () {
                var _modelo = $('#selectEquipo option:selected').attr('data-modelo');
                var tipoFalla = $(this).val();
                var datos = {'tipoFalla': tipoFalla, 'equipo': _modelo};
                $('#selectFallaDiagnosticoCorrectivo').empty().append('<option value="">Seleccionar</option>').attr('disabled', 'disabled');
                select.cambiarOpcion('#selectFallaDiagnosticoCorrectivo', '');
                evento.enviarEvento('Seguimiento/ConsultaFallasEquiposXTipoFallaYEquipo', datos, '#seguimiento-checklist', function (respuesta) {
                    $.each(respuesta, function (k, v) {
                        $('#selectFallaDiagnosticoCorrectivo').append('<option value="' + v.Id + '">' + v.Nombre + '</option>');
                    });
                    if (respuesta.length > 0) {
                        $('#selectFallaDiagnosticoCorrectivo').removeAttr('disabled');
                    }

                });
            });
            $('#selectComponenteDiagnosticoCorrectivo').on('change', function () {
                var _componente = $(this).val();
                var _modelo = $('#selectEquipo option:selected').attr('data-modelo');
                var datos = {'equipo': _modelo};
                $('#selectTipoFallaComponenteCorrectivo').empty().append('<option value="">Seleccionar</option>').attr('disabled', 'disabled');
                select.cambiarOpcion('#selectTipoFallaComponenteCorrectivo', '');
                if (_componente !== '') {
                    evento.enviarEvento('Seguimiento/ConsultaTiposFallasEquipos', datos, '#seguimiento-checklist', function (respuesta) {
                        $.each(respuesta, function (k, v) {
                            $('#selectTipoFallaComponenteCorrectivo').append('<option value="' + v.Id + '">' + v.Nombre + '</option>');
                        });
                        if (respuesta.length > 0) {
                            $('#selectTipoFallaComponenteCorrectivo').removeAttr('disabled');
                        }
                    });
                }
            });
            $('#selectTipoFallaComponenteCorrectivo').on('change', function () {
                var _componente = $('#selectComponenteDiagnosticoCorrectivo').val();
                var tipoFalla = $(this).val();
                var datos = {'tipoFalla': tipoFalla, 'componente': _componente};
                $('#selectFallaComponenteDiagnosticoCorrectivo').empty().append('<option value="">Seleccionar</option>').attr('disabled', 'disabled');
                select.cambiarOpcion('#selectFallaComponenteDiagnosticoCorrectivo', '');
                evento.enviarEvento('Seguimiento/ConsultaFallasRefacionXTipoFallaChecklist', datos, '#seguimiento-checklist', function (respuesta) {
                    $.each(respuesta, function (k, v) {
                        $('#selectFallaComponenteDiagnosticoCorrectivo').append('<option value="' + v.Id + '">' + v.Nombre + '</option>');
                    });
                    if (respuesta.length > 0) {
                        $('#selectFallaComponenteDiagnosticoCorrectivo').removeAttr('disabled');
                    }
                });
            });
            $('#btnGuardarImpericiaChecklist').on('click');
            $('#btnGuardarImpericiaChecklist').on('click', function () {

                var tipoFalla = $('#selectImpericiaTipoFallaEquipoCorrectivo option:selected').val();
                var idFalla = $('#selectImpericiaFallaDiagnosticoCorrectivo option:selected').val();
                var evidencia = $('#evidenciasImpericiaCorrectivo').val();
                guardarRevisionTecnicaChecklist(2, null, tipoFalla, idFalla, 'evidenciasImpericiaCorrectivo', evidencia);
            });
            $('#btnGuardarFallaEquipoChecklist').on('click');
            $('#btnGuardarFallaEquipoChecklist').on('click', function () {
                var tipoFalla = $('#selectTipoFallaEquipoCorrectivo option:selected').val();
                var idFalla = $('#selectFallaDiagnosticoCorrectivo option:selected').val();
                var evidencia = $('#evidenciasFallaEquipoCorrectivo').val();
                guardarRevisionTecnicaChecklist(3, null, tipoFalla, idFalla, 'evidenciasFallaEquipoCorrectivo', evidencia);
            });
            $('#btnGuardarFallaComponenteChecklist').off('click');
            $('#btnGuardarFallaComponenteChecklist').on('click', function () {
                var componente = $('#selectComponenteDiagnosticoCorrectivo option:selected').val();
                var tipoFalla = $('#selectTipoFallaComponenteCorrectivo option:selected').val();
                var idFalla = $('#selectFallaComponenteDiagnosticoCorrectivo option:selected').val();
                var evidencia = $('#evidenciasFallaComponenteCorrectivo').val();
                guardarRevisionTecnicaChecklist(4, componente, tipoFalla, idFalla, 'evidenciasFallaComponenteCorrectivo', evidencia);
            });
            $('#btnGuardarReporteMultimediaChecklist').off('click');
            $('#btnGuardarReporteMultimediaChecklist').on('click', function () {
                var evidencia = $('#evidenciasReporteMultimediaCorrectivo').val();
                guardarRevisionTecnicaChecklist(5, null, null, null, 'evidenciasReporteMultimediaCorrectivo', evidencia);
            });
            evento.enviarEvento('Seguimiento/MostrarFallasTecnicasChecklist', {'servicio': servicio}, '#seguimiento-checklist', function (listaFallas) {
                tabla.limpiarTabla("#tablaFallasTecnicas");
                $.each(listaFallas, function (key, value) {
                    tabla.agregarFila("#tablaFallasTecnicas", [value.Id, value.AreaPunto, value.Equipo, value.Serie, value.Componente, value.TipoDiagnostico, value.Falla, value.Fecha]);
                });
            });
            $('#tablaFallasTecnicas tbody').on('click', 'tr', function () {
                let _this = this;
                var datosTabla = $('#tablaFallasTecnicas').DataTable().row(_this).data();
                var dato = {'idRevisionTecnica': datosTabla[0], 'servicio': servicio};
                evento.enviarEvento('Seguimiento/ActualizarRevisionTecnica', dato, '#panel-catalogo-checklist', function (respuestaRevision) {
                    evento.iniciarModal('#modalEditRevisionChecklist', 'Editar Revisión Tecnica', respuestaRevision.modal);
                    $('#editarEstatus').off('click');
                    $('#editarEstatus').on('click', function () {
                        var estatusRevision = $('#editarEstatus').val();
                        if (estatusRevision === `1`) {
                            estatusRevision = 0;
                            $('#editarEstatus').addClass('btn-danger');
                            $('#editarEstatus').text('Inhabiliatado');
                        }

                        var datos = {'idRevisionTecnica': dato.idRevisionTecnica, 'servicio': servicio, 'estatusRevision': estatusRevision};
                        evento.enviarEvento('Seguimiento/EditarRevisionTecnicaChecklist', datos, '', function (consultaRevision) {
                            if (consultaRevision) {
                                evento.terminarModal('#modalEditRevisionChecklist');
                                evento.mostrarMensaje("#mensajeRevisionTecnica", true, "Datos modificados", 4000);
                                tabla.eliminarFila('#tablaFallasTecnicas', _this);
                            }
                        });
                    });
                });
            });
        });
    };
    var initDiagnosticoEquipo = function () {
        var _tab = arguments[0];
        var _servicio = arguments[1];
        var _modelo = $('#selectEquipo option:selected').attr('data-modelo');
        var metodo = '';
        var enviar = true;
        var datos = {'equipo': _modelo};
        $('#selectImpericiaTipoFallaEquipoCorrectivo').empty().append('<option value="">Seleccionar</option>').attr('disabled', 'disabled');
        $('#selectTipoFallaEquipoCorrectivo').empty().append('<option value="">Seleccionar</option>').attr('disabled', 'disabled');
        $('#selectComponenteDiagnosticoCorrectivo').empty().append('<option value="">Seleccionar</option>').attr('disabled', 'disabled');
        switch (_tab) {
            case '#impericia':
                metodo = 'ConsultaTiposFallasEquiposImpericia';
                break;
            case '#falla-equipo':
                metodo = 'ConsultaTiposFallasEquipos';
                break;
            case '#falla-componente':
                metodo = 'ConsultaRefacionXEquipo';
                break;
            case '#reporte-multimedia':
                enviar = false;
                break;
            default:
                enviar = false;
                break;
        }
        limpiarRevisionTecnica(false, _tab);
        if (enviar) {

            evento.enviarEvento('Seguimiento/' + metodo, datos, '#seguimiento-checklist', function (respuesta) {
                switch (_tab) {
                    case '#impericia':

                        $.each(respuesta, function (k, v) {
                            $('#selectImpericiaTipoFallaEquipoCorrectivo').append('<option value="' + v.Id + '">' + v.Nombre + '</option>');
                        });
                        $('#selectImpericiaTipoFallaEquipoCorrectivo').removeAttr('disabled');
                        break;
                    case '#falla-equipo':
                        metodo = 'ConsultaTiposFallasEquipos';
                        $.each(respuesta, function (k, v) {
                            $('#selectTipoFallaEquipoCorrectivo').append('<option value="' + v.Id + '">' + v.Nombre + '</option>');
                        });
                        $('#selectTipoFallaEquipoCorrectivo').removeAttr('disabled');
                        break;
                    case '#falla-componente':
                        metodo = 'ConsultaRefacionXEquipo';
                        $.each(respuesta, function (k, v) {
                            $('#selectComponenteDiagnosticoCorrectivo').append('<option value="' + v.Id + '">' + v.Nombre + '</option>');
                        });
                        $('#selectComponenteDiagnosticoCorrectivo').removeAttr('disabled');
                        break;
                }
            });
        }
    };
    var guardarRevisionTecnicaChecklist = function () {
        var _servicio = $('#hiddenServicio').val();
        var _tipoDiagnostico = arguments[0];
        var _componente = arguments[1] || null;
        var _tipoFalla = arguments[2];
        var _idFalla = arguments[3];
        var _fileInput = arguments[4];
        var _evidencia = arguments[5];
        var areaPunto = $('#selectAreaPunto option:selected');
        var equipo = $('#selectEquipo option:selected');
        var datos = {'servicio': _servicio,
            'area': areaPunto.attr('data-area'),
            'punto': areaPunto.attr('data-punto'),
            'modelo': equipo.attr('data-modelo'),
            'serie': equipo.attr('data-serie'),
            'terminal': equipo.attr('data-terminal'),
            'tipoDiagnostico': _tipoDiagnostico,
            'componente': _componente,
            'tipoFalla': _tipoFalla,
            'idFalla': _idFalla,
            'fileInput': _fileInput,
            'equipo': equipo.val()
        };
        if (datos.idFalla !== '' && datos.tipoFalla && _evidencia !== '') {
            file.enviarArchivos('#' + _fileInput, 'Seguimiento/GuardarRevisionTecnicaChecklist', '#seguimiento-checklist', datos, function (respuesta) {
                if (respuesta.code === 200) {
                    tabla.limpiarTabla("#tablaFallasTecnicas");
                    $.each(respuesta.listaFallas, function (key, value) {
                        tabla.agregarFila("#tablaFallasTecnicas", [value.Id, value.AreaPunto, value.Equipo, value.Serie, value.Componente, value.TipoDiagnostico, value.Falla, value.Fecha]);
                        limpiarRevisionTecnica(true, '');
                        evento.mostrarMensaje('#mensajeRevisionTecnica', true, "Información guardada correctamente", 3000);
                    });
                }
            });
        } else if (datos.fileInput === 'evidenciasReporteMultimediaCorrectivo') {
            file.enviarArchivos('#' + _fileInput, 'Seguimiento/GuardarRevisionTecnicaChecklist', '#seguimiento-checklist', datos, function (respuesta) {
                if (respuesta.code === 200) {
                    tabla.limpiarTabla("#tablaFallasTecnicas");
                    $.each(respuesta.listaFallas, function (key, value) {
                        tabla.agregarFila("#tablaFallasTecnicas", [value.Id, value.AreaPunto, value.Equipo, value.Serie, value.Componente, value.TipoDiagnostico, value.Falla, value.Fecha]);
                        limpiarRevisionTecnica(true, '');
                    });
                    evento.mostrarMensaje('#mensajeRevisionTecnica', true, "Información guardada correctamente", 3000);
                }
            });
        } else {
            evento.mostrarMensaje('#errorRevisionTecnica', false, "No se a podido guardar la información. Intente de nuevo o contacte al administrador", 3000);
        }

    };
    var limpiarRevisionTecnica = function (limpiarFormulario, tipoDiagnostico) {

        if (limpiarFormulario) {
            select.cambiarOpcion('#selectAreaPunto', '');
            select.cambiarOpcion('#selectEquipo', '');
            select.cambiarOpcion('#selectImpericiaTipoFallaEquipoCorrectivo', '');
            select.cambiarOpcion('#selectImpericiaFallaDiagnosticoCorrectivo', '');
            file.limpiar('#evidenciasImpericiaCorrectivo');
            select.cambiarOpcion('#selectTipoFallaEquipoCorrectivo', '');
            select.cambiarOpcion('#selectFallaDiagnosticoCorrectivo', '');
            file.limpiar('#evidenciasFallaEquipoCorrectivo');
            select.cambiarOpcion('#selectComponenteDiagnosticoCorrectivo', '');
            select.cambiarOpcion('#selectTipoFallaComponenteCorrectivo', '');
            select.cambiarOpcion('#selectFallaComponenteDiagnosticoCorrectivo', '');
            file.limpiar('#evidenciasFallaComponenteCorrectivo');
            file.limpiar('#evidenciasReporteMultimediaCorrectivo');
        }

        switch (tipoDiagnostico) {
            case '#impericia':
                select.cambiarOpcion('#selectTipoFallaEquipoCorrectivo', '');
                select.cambiarOpcion('#selectComponenteDiagnosticoCorrectivo', '');
                file.limpiar('#evidenciasFallaEquipoCorrectivo');
                file.limpiar('#evidenciasFallaComponenteCorrectivo');
                file.limpiar('#evidenciasReporteMultimediaCorrectivo');
                break;
            case '#falla-equipo':
                select.cambiarOpcion('#selectImpericiaTipoFallaEquipoCorrectivo', '');
                select.cambiarOpcion('#selectComponenteDiagnosticoCorrectivo', '');
                file.limpiar('#evidenciasImpericiaCorrectivo');
                file.limpiar('#evidenciasFallaComponenteCorrectivo');
                file.limpiar('#evidenciasReporteMultimediaCorrectivo');
                break;
            case '#falla-componente':
                select.cambiarOpcion('#selectTipoFallaEquipoCorrectivo', '');
                select.cambiarOpcion('#selectImpericiaTipoFallaEquipoCorrectivo', '');
                file.limpiar('#evidenciasImpericiaCorrectivo');
                file.limpiar('#evidenciasFallaEquipoCorrectivo');
                file.limpiar('#evidenciasReporteMultimediaCorrectivo');
                break;
            case '#reporte-multimedia':
                select.cambiarOpcion('#selectTipoFallaEquipoCorrectivo', '');
                select.cambiarOpcion('#selectComponenteDiagnosticoCorrectivo', '');
                select.cambiarOpcion('#selectImpericiaTipoFallaEquipoCorrectivo', '');
                file.limpiar('#evidenciasImpericiaCorrectivo');
                file.limpiar('#evidenciasFallaEquipoCorrectivo');
                file.limpiar('#evidenciasFallaComponenteCorrectivo');
                break;
            default:
                break;
        }
    };
    var mostrarPreguntaPorCategoria = function (dataCategoria, sucursal, respuesta) {
        var datos = {'IdCategoria': dataCategoria, 'sucursal': sucursal};
        evento.enviarEvento('Seguimiento/MostrarPreguntas', datos, '#seguimiento-checklist', function (preguntasCategoria) {
            var lista = preguntasCategoria[0];
            tabla.limpiarTabla('#tabla-categorias');
            if (!preguntasCategoria) {
                evento.mostrarMensaje('#errorRevisionFisica', false, preguntasCategoria.error, 3000);
            } else {
                $.each(lista, function (clave, valor) {

                    if (valor.IdArea) {
                        var decicionUno = '<form><fieldset id="group-' + valor.IdArea + '"><input type="radio" id="uno" name="desicion-' + valor.IdArea + '" value="1" style="height: 16px;width: 20px;margin: 0px 5px 0px 5px;"/><spam style="margin: 0 11px 0 0px;">Si</spam> <input type="radio" name="desicion-' + valor.IdArea + '" id="cero" value="0" style="height: 15px;width: 17px;margin: 0px 5px 0px 5px;"/><spam style="margin: 0 11px 0 0px;">No</spam> </fieldset></form>';
                        tabla.agregarFila('#tabla-categorias', [
                            valor.Id,
                            valor.Nombre,
                            valor.IdArea,
                            valor.Concepto,
                            decicionUno
                        ]);
                    }
                });
                iniciarElementosChecklist(respuesta);
            }
        });
    };
    var mostrarPuntoPorCategoria = function (dataCategoria, sucursal, respuesta, datosTabla) {
        var servicio = $('#hiddenServicio').val();
        var datos = {'servicio': servicio, 'sucursal': sucursal, 'categoria': dataCategoria};
        evento.enviarEvento('Seguimiento/MostrarPuntoRevision', datos, '#seguimiento-checklist', function (datosPuntos) {
            mostrarInformacionPuntos(datosPuntos, dataCategoria, servicio);
        });
    };
    var mostrarInformacionPuntos = function (datosPuntos, categoria, servicio) {
        var puntos = null;
        var html = '';
        var idArea = null;
        $(".area").empty();
        $(".punto").empty();
        $.each(datosPuntos.pushArea, function (area, pregunta) {
            idArea = area.replace(/\s/g, "-");
            puntos = imprimirAreaPregunta(area, pregunta, html);
            html = imprimirPuntos(puntos[0], puntos[1], idArea, datosPuntos.pushChecklist, categoria, servicio, area);
        });
        $(".area").append(html);
        agregarNuevaImagen(dataCategoria, servicio);
        elimininarEvidenciaPunto(dataCategoria, servicio);
        eventoChecklist(servicio);
    };
    var imprimirAreaPregunta = function (area, pregunta, html) {
        var puntos = null;
        $.each(pregunta, function (etiqueta, listaPuntos) {
            html += '<div class="m-t-30"><p>' + area + '</p><div class="underline m-b-15"></div><p>' + etiqueta + '</p>';
            puntos = listaPuntos;
        });
        return [puntos, html];
    };
    var imprimirPuntos = function (puntos, html, idArea, checkList, categoria, servicio, area) {

        $.each(puntos, function (key, punto) {
            var checked = '';
            $.each(checkList, function (key, valor) {
                if (area === valor.Area && punto === valor.Punto) {
                    checked = 'checked';
                }
            });
            html += `<div  class="row area-${idArea}">
                        <div class="col-md-12 m-t-15">
                            <input class="punto checkPunto" data-datos="${servicio + '-' + categoria + '-' + area + '-' + punto}"  type="checkbox" name="punto" style="width: 17px; height: 15px; margin: 0;" value="" ${checked}/> Punto ${punto}
                            <a href="javascript:;" class="btn btn-success btn-xs m-l-10 nuevaImagenPunto" data-idpunto="${servicio + '-' + categoria + '-' + area + '-' + punto}">
                                <i class="fa fa-plus"></i> Imagen
                            </a>
                        </div>
                        <div class="col-md-12 m-t-10 imagen-punto-${punto}">
                            ${cargarEvidencias(checkList, idArea, categoria, punto, servicio, area)}
                        </div>
                     </div>`;
        });
        html += `</div>`;
        return html;
    };
    var cargarEvidencias = function (checkList, idArea, categoria, punto, servicio, area) {
        var html = '';
        $.each(checkList, function (index, checked) {
            var area = checked.Area.replace(/\s/g, "-");
            var idImagen = area + '-' + punto;
            if (area === idArea && checked.IdCategoria === `${categoria}` && checked.Punto === punto && checked.Evidencia !== '') {
                html += obtenerHtmlEvidencias(checked.Evidencia, idImagen, punto, servicio, checked.Area, categoria);
            }

        });
        return html;
    };
    var obtenerHtmlEvidencias = function (evidencias, idImagen, punto, servicio, area, categoria) {
        var html = '';
        var listaEvidencias = evidencias.split(',');
        $.each(listaEvidencias, function (index, evidencia) {
            html += `<div class = "evidencia">
                        <a href="${evidencia}" data-lightbox="${idImagen}" ><img src="${evidencia}" alt="" /></a>
                        <a href="javascript:;" class="btn btn-danger btn-xs eliminarImagenPunto" data-evidencia="${evidencia}" data-ideliminar="${servicio + '-' + categoria + '-' + area + '-' + punto}">
                            <i class="fa fa-trash-o"></i>
                        </a>
                    </div>`;
        });
        return html;
    };
    var agregarNuevaImagen = function (dataCategoria, servicio) {
        var datos = null;
        var evidenciaHtml = `<div class="row">
                                <div class="col-md-12">
                                   <p>Agrega la evidencia para el punto:</p>
                                </div>
                                <div class="col-md-12">
                                   <input id="evidenciaPunto" data-area="" class="inputArchivoPunto" name="inputArchivoPunto[]" type="file" multiple/>
                                </div>
                            </div>`;
        $('.nuevaImagenPunto').off('click');
        $('.nuevaImagenPunto').on('click', function () {
            evento.mostrarModal('Agregar evidencia', evidenciaHtml);
            file.crearUpload('#evidenciaPunto', 'Seguimiento/GuardarRevisionPunto', null, null, null, null, null, 'inputArchivoPunto');
            datos = $(this).attr('data-idpunto');
            datos = datos.split('-');
            datos = {servicio: datos[0], idCategoria: datos[1], idRevisionArea: datos[2], punto: datos[3]};
        });
        $('#btnModalConfirmar').off('click');
        $('#btnModalConfirmar').on('click', function () {
            datos.evidencia = $('#evidenciaPunto').val();
            file.enviarArchivos('#evidenciaPunto', 'Seguimiento/GuardarRevisionPunto', '#modal-dialog', datos, function (datosPuntos) {
                mostrarInformacionPuntos(datosPuntos, dataCategoria, servicio);
                evento.cerrarModal();
            });
        });
    };
    var elimininarEvidenciaPunto = function (dataCategoria, servicio) {
        var urlEvidencia = null;
        var datos = null;
        $('.eliminarImagenPunto').off('click');
        $('.eliminarImagenPunto').on('click', function () {
            urlEvidencia = $(this).attr('data-evidencia');
            datos = $(this).attr('data-ideliminar');
            datos = datos.split('-');
            datos = {servicio: datos[0], idCategoria: datos[1], idRevisionArea: datos[2], punto: datos[3], url: urlEvidencia};
            evento.enviarEvento('Seguimiento/EliminarEvidenciaChecklist', datos, '#seguimiento-checklist', function (datosPuntos) {
                mostrarInformacionPuntos(datosPuntos, dataCategoria, servicio);
            });
        });
    };
    var eventoChecklist = function (servicio) {
        var datos = null;
        var evidenciaHtml = `<div class="row">
                                <div class="col-md-12">
                                   <p>Agrega la evidencia para el punto:</p>
                                </div>
                                <div class="col-md-12">
                                   <input id="evidenciaPunto" data-area="" class="inputArchivoPunto" name="inputArchivoPunto[]" type="file" multiple/>
                                </div>
                            </div>`;
        $('.checkPunto').off('click');
        $('.checkPunto').on('click', function () {
            var _this = $(this);
            datos = $(this).attr('data-datos');
            datos = datos.split('-');
            datos = {servicio: datos[0], idCategoria: datos[1], idRevisionArea: datos[2], punto: datos[3]};
            if ($(this).is(':checked')) {

                evento.mostrarModal('Agregar evidencia', evidenciaHtml);
                file.crearUpload('#evidenciaPunto', 'Seguimiento/GuardarRevisionPunto', null, null, null, null, null, 'inputArchivoPunto');
                datos = $(this).attr('data-datos');
                datos = datos.split('-');
                datos = {servicio: datos[0], idCategoria: datos[1], idRevisionArea: datos[2], punto: datos[3]};
                $('#btnModalConfirmar').off('click');
                $('#btnModalConfirmar').on('click', function () {
                    file.enviarArchivos('#evidenciaPunto', 'Seguimiento/GuardarRevisionPunto', '#modal-dialog', datos, function (datosPuntos) {
                        mostrarInformacionPuntos(datosPuntos, dataCategoria, servicio);
                        evento.cerrarModal();
                    });
                });
            } else {
                var area = datos.idRevisionArea.replace(/\s/g, "-");
                evento.enviarEvento('Seguimiento/ActualizarRevisionPunto', datos, '', function (respuesta) {
                    if (respuesta) {
                        $(_this.parents('.area-' + area)).find('.imagen-punto-' + datos.punto).empty();
                        evento.mostrarMensaje('#errorRevisionPunto', true, 'Punto deshabilitado', 3000);
                    }
                });
            }
        });
    };
    var iniciarElementosChecklist = function (respuesta) {
        var servicio = $('#hiddenServicio').val();
        var datos = {'servicio': servicio, 'idCategoria': dataCategoria};
        evento.enviarEvento('Seguimiento/ConsultarRevisonArea', datos, '#seguimiento-checklist', function (respuesta) {
            $.each(respuesta, function (key, valor) {
                if (dataCategoria == valor.IdCategoria) {
                    if (valor.Flag === `1`) {
                        var $radios = $('#group-' + valor.IdAreaAtencion + ' input:radio[id=uno]');
                        $radios.filter('[value=1]').attr('checked', true);
                    } else {
                        var $radios = $('#group-' + valor.IdAreaAtencion + ' input:radio[id=cero]');
                        $radios.filter('[value=0]').attr('checked', true);
                    }
                }
            });
        });
    };
    var guardarInformacionChecklist = function (datos) {

        evento.enviarEvento('Seguimiento/GuardarInformacionChecklist', datos, '#seguimiento-checklist', function (respuesta) {
            if (respuesta) {
                var sucursalSelect = $('#selectSucursales option:selected').val();
                mostrarTabla(sucursalSelect);
                evento.mostrarMensaje('#errorInformacionGeneral', true, 'Información guardada', 3000);
            } else {
                evento.mostrarMensaje('#errorInformacionGeneral', false, respuesta.error, 3000);
            }
        });
    };
    var eventosChecklist = function () {
        var datosTabla = arguments[0];
        var respuesta = arguments[1];
        var servicio = datosTabla[0];
        var ticket = datosTabla[1];
        $('#guardarRevisionFisicaArea').off('click');
        $('#guardarRevisionFisicaArea').on('click', function () {
            guardarRevisionArea(servicio);
        });
        $('#selectSucursales').on('change', function () {
            $('#listaPregunta').addClass('hidden');
            $('#guardarListaPregunta').addClass('hidden');
        });
        $('#guardarSucursalChecklist').off('click');
        $('#guardarSucursalChecklist').on('click', function () {
            var datos = {'sucursal': $('#selectSucursales').val(), 'servicio': servicio, 'guardarTipo': 1};
            guardarInformacionChecklist(datos);
        });
        $('#detallesServicioChecklist').off('click');
        $('#detallesServicioChecklist').on('click', function (e) {
            if ($('#masDetalles').hasClass('hidden')) {
                $('#masDetalles').removeClass('hidden');
                $('#detallesServicioChecklist').empty().html('<a>- Detalles</a>');
            } else {
                $('#masDetalles').addClass('hidden');
                $('#detallesServicioChecklist').empty().html('<a>+ Detalles</a>');
            }
        });
        //Evento que vuelve a mostrar la lista de servicios de Poliza
        $('#btnRegresarSeguimientoCenso').off('click');
        $('#btnRegresarSeguimientoCenso').on('click', function () {
            $('#seccionSeguimientoServicio').empty().addClass('hidden');
            $('#listaPoliza').removeClass('hidden');
        });
        //Encargado de crear un nuevo servicio
        $('#btnNuevoServicio').off('click');
        $('#btnNuevoServicio').on('click', function () {
            var data = {servicio: servicio};
            servicios.nuevoServicio(
                    data,
                    respuesta.datosServicio.Ticket,
                    respuesta.datosServicio.IdSolicitud,
                    'Seguimiento/Servicio_Nuevo_Modal',
                    '#seccion-servicio-censo',
                    'Seguimiento/Servicio_Nuevo'
                    );
        });
        //Encargado de cancelar servicio
        $('#btnCancelarServicioChecklist').off('click');
        $('#btnCancelarServicioChecklist').on('click', function () {
            var data = {servicio: servicio, ticket: respuesta.datosServicio.Ticket};
            servicios.cancelarServicio(
                    data,
                    'Seguimiento/Servicio_Cancelar_Modal',
                    '#seguimiento-checklist',
                    'Seguimiento/Servicio_Cancelar'
                    );
        });
        //Encargado de generar el archivo Pdf
        $('#btnGeneraPdfServicio').off('click');
        $('#btnGeneraPdfServicio').on('click', function () {
            var data = {'servicio': datosTabla[0], 'ticket': ticket, 'generarPDF': true};
            evento.enviarEvento('Seguimiento/GenerarPDF', data, '', function (respuesta) {
                window.open(respuesta, '_blank');
            });
        });
        $('#btnRegresarSeguimiento').off('click');
        $('#btnRegresarSeguimiento').on('click', function () {
            $('#seccionSeguimientoServicio').empty().addClass('hidden');
            $('#listaPoliza').removeClass('hidden');
        });
        servicios.eventosFolio(datosTabla[2], '#informacionRevision', servicio);
    };
    var guardarRevisionArea = function (servicio) {
        var listaConceptos = $('#tabla-categorias').DataTable().rows().data();
        var IdCategoria = dataCategoria;
        var servicio = servicio;
        var datosConcepto = "[";
        for (var i = 0; i < listaConceptos.length; i++) {
            var desicion = $("fieldset[id*=group-" + listaConceptos[i][2] + "] input[name*='desicion-" + listaConceptos[i][2] + "']:checked").val();
            datosConcepto += '{"IdConceptoFisico" : "' + listaConceptos[i][0] + '", "IdAreaAtencion" : "' + listaConceptos[i][2] + '", "Flag" : ' + desicion + '},';
        }
        datosConcepto = datosConcepto.slice(0, -1);
        datosConcepto += "]";
        var datos = {'servicio': servicio, 'datosTabla': datosConcepto, 'idCategoria': IdCategoria, 'guardarTipo': 2};
        evento.enviarEvento('Seguimiento/GuardarInformacionChecklist', datos, '#seguimiento-checklist', function (respuesta) {
            if (respuesta) {
                evento.mostrarMensaje('#errorRevisionArea', true, "Información guardada correctamente", 3000);
            } else {
                evento.mostrarMensaje('#errorRevisionArea', false, "Información incorrecta", 3000);
            }
        });
    };
    var recargandoTablaPoliza = function (informacionServicio) {
        tabla.limpiarTabla('#data-table-poliza');
        $.each(informacionServicio.serviciosAsignados, function (key, item) {
            tabla.agregarFila('#data-table-poliza', [item.Id, item.Ticket, item.IdSolicitud, item.Servicio, item.FechaCreacion, item.Descripcion, item.NombreEstatus, item.IdEstatus, item.Folio]);
        });
    };
    //Servicio Censo
    var iniciarElementosPaginaSeguimientoCenso = function () {
        var respuesta = arguments[0];
        var datosTabla = arguments[1];
        $('#listaPoliza').addClass('hidden');
        $('#seccionSeguimientoServicio').removeClass('hidden').empty().append(respuesta.formulario);
        $('#inputNumeroTerminalCenso').mask("aaaaaa99");
        select.crearSelect('#selectSucursales');
        select.crearSelect('#selectAreasAtencion');
        select.crearSelect('#selectModelosCenso');
        tabla.generaTablaPersonal('#data-table-censo-modelos', null, null, true, true);
        var columnas = servicios.datosTablaDocumentacionFirmada();
        tabla.generaTablaPersonal('#data-table-documetacion-firmada', respuesta.documentacionFirmada, columnas, null, null, [[0, 'desc']]);
        $("#contentAreaPuntos").empty();
        $("#contentEquiposPunto").empty();
        $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
            var target = $(e.target).attr("href");
            switch (target) {
                case "#AreaPuntos":
                    cargaAreasPuntosCenso(respuesta.servicio);
                    break;
                case "#EquiposPunto":
                    cargaEquiposPuntoCenso(respuesta.servicio);
                    break;
            }
        });
        $("#divNotasServicio").slimScroll({height: '400px'});
        nota.initButtons({servicio: datosTabla[0]}, 'Seguimiento');
    };
    function cargaEquiposPuntoCenso() {
        var servicio = arguments[0];
        $("#formularioCapturaCenso").empty().hide();
        $("#contentEquiposPunto").show();
        evento.enviarEvento('Seguimiento/CargaEquiposPuntoCenso', {'servicio': servicio}, '#seccion-servicio-censo', function (respuesta) {
            $("#contentEquiposPunto").empty().append(respuesta.html);
            $(".btnPuntoArea").off("click");
            $(".btnPuntoArea").on("click", function () {
                var buttonPuntoArea = $(this);
                var data = {
                    'servicio': servicio,
                    'area': $(this).attr("data-area"),
                    'punto': $(this).attr("data-punto")
                };
                evento.enviarEvento('Seguimiento/CargaFormularioCapturaCenso', data, '#seccion-servicio-censo', function (respuesta) {
                    $("#formularioCapturaCenso").empty().append(respuesta.html);
                    $("#formularioCapturaCenso").show();
                    $("#contentEquiposPunto").hide();
                    $(".btnCancelarCapturaCenso").off("click");
                    $(".btnCancelarCapturaCenso").on("click", function () {
                        $("#formularioCapturaCenso").empty().hide();
                        $("#contentEquiposPunto").show();
                    });
                    $(".existeModelosEstandar").off("click");
                    $(".existeModelosEstandar").on("click", function () {
                        var fila = $(this).closest("tr.registroEquiposEstandar");
                        if ($(this).is(":checked")) {
                            fila.find(".listModelosEstandar").removeAttr("disabled");
                            fila.find(".serieModelosEstandar").removeAttr("disabled");
                            fila.find(".ilegibleModelosEstandar").removeAttr("disabled");
                            fila.find(".danadoModelosEstandar").removeAttr("disabled");
                            fila.removeClass("table-border-red").addClass("table-border-green");
                        } else {
                            fila.find(".listModelosEstandar").attr('disabled', true);
                            fila.find(".serieModelosEstandar").attr('disabled', true);
                            fila.find(".ilegibleModelosEstandar").attr('disabled', true);
                            fila.find(".danadoModelosEstandar").attr('disabled', true);
                            fila.removeClass("table-border-green").addClass("table-border-red");
                        }
                    });
                    $(".ilegibleModelosEstandar").off("click");
                    $(".ilegibleModelosEstandar").on("click", function () {
                        var fila = $(this).closest("tr.registroEquiposEstandar");
                        var campoSerie = fila.find(".serieModelosEstandar");
                        if ($(this).is(":checked")) {
                            campoSerie.attr("disabled", true);
                            campoSerie.val("ILEGIBLE");
                        } else {
                            campoSerie.removeAttr("disabled");
                            campoSerie.val("");
                        }
                    });
                    $("#checkIlegibleEquipoAdicional").off("click");
                    $("#checkIlegibleEquipoAdicional").on("click", function () {
                        var campoSerie = $("#txtSerieEquipoAdicional");
                        if ($(this).is(":checked")) {
                            campoSerie.attr("disabled", true);
                            campoSerie.val("ILEGIBLE");
                        } else {
                            campoSerie.removeAttr("disabled");
                            campoSerie.val("");
                        }
                    });
                    $(".ilegibleEquiposAdicionales").off("click");
                    $(".ilegibleEquiposAdicionales").on("click", function () {
                        var fila = $(this).closest("tr.registrosAdicionales");
                        var campoSerie = fila.find(".serieEquiposAdicionales");
                        if ($(this).is(":checked")) {
                            campoSerie.attr("disabled", true);
                            campoSerie.val("ILEGIBLE");
                        } else {
                            campoSerie.removeAttr("disabled");
                            campoSerie.val("");
                        }
                    });
                    select.crearSelect("#listModelosEquipoAdicional");
                    $("#listModelosEquipoAdicional").on("change", function () {
                        var modelo = $("#listModelosEquipoAdicional option:selected").text();
                        if ($("#txtEtiquetaEquipoAdicional").length) {

                            var regionSucursal = $("#txtRegionSucursalClave").val();
                            var clave = regionSucursal;
                            if ($("#listModelosEquipoAdicional").val() != '') {
                                var linea = $("#listModelosEquipoAdicional option:selected").attr("data-linea");
                                var sublinea = $("#listModelosEquipoAdicional option:selected").attr("data-sublinea");
                                var area = $("#txtIdAreaClave").val();
                                clave += '-L' + linea + 'S' + sublinea + '-' + area + '-';
                                $("#txtEtiquetaEquipoAdicional").removeAttr("disabled");
                            } else {
                                $("#txtEtiquetaEquipoAdicional").attr("disabled", "disabled");
                            }

                            $("#txtEtiquetaEquipoAdicional").val(clave);
                            if (modelo.indexOf("COMPUTADORA") >= 0) {
                                $("#txtMACEquipoAdicional").removeAttr("disabled");
                                $("#listSOEquipoAdicional").removeAttr("disabled");
                                $("#txtNombreRedEquipoAdicional").removeAttr("disabled");
                                $("#listRQEquipoAdicional").removeAttr("disabled");
                            } else {
                                $("#txtMACEquipoAdicional").val("");
                                $("#txtMACEquipoAdicional").attr("disabled", "disabled");
                                $("#listSOEquipoAdicional").val("");
                                $("#listSOEquipoAdicional").attr("disabled", "disabled");
                                $("#txtNombreRedEquipoAdicional").val("");
                                $("#txtNombreRedEquipoAdicional").attr("disabled", "disabled");
                                $("#listRQEquipoAdicional").val("");
                                $("#listRQEquipoAdicional").attr("disabled", "disabled");
                            }
                        }

                    });
                    $(".listModelosEquiposAdicionales").on("change", function () {
                        var modelo = $("option:selected", this).text();
                        var fila = $(this).closest("tr.registrosAdicionales");
                        if (fila.find(".macEquiposAdicionales").length) {
                            var campoMAC = fila.find(".macEquiposAdicionales");
                            var campoSO = fila.find(".listSOEquiposAdicionales");
                            var campoNombreRed = fila.find(".nombreRedEquiposAdicionales")
                            var campoRQ = fila.find(".listRQEquiposAdicionales")
                            if (modelo.indexOf("COMPUTADORA") >= 0) {
                                campoMAC.removeAttr("disabled");
                                campoSO.removeAttr("disabled");
                                campoNombreRed.removeAttr("disabled");
                                campoRQ.removeAttr("disabled");
                            } else {
                                campoMAC.val("");
                                campoMAC.attr("disabled", "disabled");
                                campoSO.val("");
                                campoSO.attr("disabled", "disabled");
                                campoNombreRed.val("");
                                campoNombreRed.attr("disabled", "disabled");
                                campoRQ.val("");
                                campoRQ.attr("disabled", "disabled");
                            }
                        }

                    });
                    $(".btnGuardarCapturaCenso").off("click");
                    $(".btnGuardarCapturaCenso").on("click", function () {

                        data = {
                            'servicio': servicio,
                            'area': data.area,
                            'punto': data.punto,
                            'nuevosEstandar': [],
                            'activosEstandar': []
                        }

                        $(".registroEquiposEstandar").each(function () {
                            var fila = $(this);
                            if (fila.hasClass('registroNuevo')) {
                                var checkExiste = fila.find(".existeModelosEstandar");
                                if (checkExiste.is(":checked")) {
                                    var datosEquipo = {
                                        'modelo': fila.find(".listModelosEstandar").val(),
                                        'serie': fila.find(".serieModelosEstandar").val(),
                                        'ilegible': (fila.find(".ilegibleModelosEstandar").is(":checked")) ? 1 : 0,
                                        'existe': 1,
                                        'danado': (fila.find(".danadoModelosEstandar").is(":checked")) ? 1 : 0,
                                    };
                                    if (datosEquipo.modelo == "") {
                                        evento.mostrarMensaje(".divErrorCapturaCensoEstandar", false, "Falta seleccionar el modelo en alguno de sus registros. Desmarque la casilla 'Existe' en caso de que el equipo no exista", 6000);
                                        return true;
                                    }

                                    if (datosEquipo.serie == "" && datosEquipo.ilegible == 0) {
                                        evento.mostrarMensaje(".divErrorCapturaCensoEstandar", false, "Falta el número de serie en alguno de sus registros. Marque la casilla 'Ilegible' en caso de que la serie no sea alcanzable o no se encuentre en el equipo.", 6000);
                                        return true;
                                    }

                                    data.nuevosEstandar.push(datosEquipo);
                                }
                            } else if (fila.hasClass('registroActivo')) {
                                var datosEquipo = {
                                    'id': fila.attr("data-id"),
                                    'modelo': fila.find(".listModelosEstandar").val(),
                                    'serie': fila.find(".serieModelosEstandar").val(),
                                    'ilegible': (fila.find(".ilegibleModelosEstandar").is(":checked")) ? 1 : 0,
                                    'existe': (fila.find(".existeModelosEstandar").is(":checked")) ? 1 : 0,
                                    'danado': (fila.find(".danadoModelosEstandar").is(":checked")) ? 1 : 0,
                                };
                                if (datosEquipo.existe == 1 && datosEquipo.modelo == "") {
                                    evento.mostrarMensaje(".divErrorCapturaCensoEstandar", false, "Falta seleccionar el modelo en alguno de sus registros. Desmarque la casilla 'Existe' en caso de que el equipo no exista", 6000);
                                    return true;
                                }

                                if (datosEquipo.existe == 1 && datosEquipo.serie == "" && datosEquipo.ilegible == 0) {
                                    evento.mostrarMensaje(".divErrorCapturaCensoEstandar", false, "Falta el número de serie en alguno de sus registros. Marque la casilla 'Ilegible' en caso de que la serie no sea alcanzable o no se encuentre en el equipo.", 6000);
                                    return true;
                                }
                                data.activosEstandar.push(datosEquipo);
                            }
                        });
                        evento.enviarEvento('Seguimiento/GuardaEquiposPuntoCenso', data, '#seccion-servicio-censo', function (respuesta) {
                            if (respuesta.code == 200) {
                                cargaEquiposPuntoCenso(servicio);
                            } else {
                                evento.mostrarMensaje(".divErrorCapturaCensoEstandar", false, "Ocurrió un error al guardar los registros. Por favor contácte al administrador.", 6000);
                            }
                        });
                    });
                    $("#btnAgregarEquipoAdicional").off("click");
                    $("#btnAgregarEquipoAdicional").on("click", function () {
                        data = {
                            'servicio': servicio,
                            'area': data.area,
                            'punto': data.punto,
                            'modelo': $("#listModelosEquipoAdicional").val(),
                            'modeloTexto': $("#listModelosEquipoAdicional option:selected").text(),
                            'serie': $.trim($("#txtSerieEquipoAdicional").val()),
                            'ilegible': $("#checkIlegibleEquipoAdicional").is(":checked") ? 1 : 0,
                            'danado': $("#checkDanadoEquipoAdicional").is(":checked") ? 1 : 0,
                            'etiqueta': ($("#txtEtiquetaEquipoAdicional").length) ? $("#txtEtiquetaEquipoAdicional").val() : '',
                            'estado': ($("#listEstadosEquipoAdicional").length) ? $("#listEstadosEquipoAdicional").val() : '',
                            'mac': ($("#txtMACEquipoAdicional").length) ? $("#txtMACEquipoAdicional").val() : '',
                            'so': ($("#listSOEquipoAdicional").length) ? $("#listSOEquipoAdicional").val() : '',
                            'nombreRed': ($("#txtNombreRedEquipoAdicional").length) ? $("#txtNombreRedEquipoAdicional").val() : '',
                            'rq': ($("#listRQEquipoAdicional").length) ? $("#listRQEquipoAdicional").val() : ''
                        }

                        if (data.modelo == "") {
                            evento.mostrarMensaje(".divErrorEquipoAdicional", false, "Falta seleccionar el modelo para agregar el equipo.", 4000);
                            return true;
                        }

                        if (data.serie == "" && data.ilegible == 0) {
                            evento.mostrarMensaje(".divErrorEquipoAdicional", false, "Falta el número de serie. Marque la casilla 'Ilegible' en caso de que la serie no sea alcanzable o no se encuentre en el equipo.", 6000);
                            return true;
                        }

                        if ($("#txtEtiquetaEquipoAdicional").length) {
                            var regexEtiqueta = /^[0-9]{3}-[A-Z]{3}[0-9]{3}-L[0-9]?[0-9]?[0-9]S[0-9]?[0-9]?[0-9]-[0-9]?[0-9]{2}-[0-9]?[0-9]?[0-9]?[0-9]$/;
                            if (data.etiqueta == "" || !regexEtiqueta.test(data.etiqueta)) {
                                evento.mostrarMensaje(".divErrorEquipoAdicional", false, "Al parecer hay un error con el formato de la etiqueta.", 6000);
                                return true;
                            }

                            if (data.estado == "") {
                                evento.mostrarMensaje(".divErrorEquipoAdicional", false, "Falta seleccionar el estado del equipo.", 6000);
                                return true;
                            }

                            if (data.modeloTexto.indexOf("COMPUTADORA") >= 0) {
                                var regexMacAddress = /^([0-9A-F]{2}[:-]){5}([0-9A-F]{2})$/;
                                if (data.mac == "" || !regexMacAddress.test(data.mac)) {
                                    evento.mostrarMensaje(".divErrorEquipoAdicional", false, "Al parecer hay un error con el formato de la MAC Address", 6000);
                                    return true;
                                }

                                if (data.so == "") {
                                    evento.mostrarMensaje(".divErrorEquipoAdicional", false, "Falta seleccionar el S.O. del equipo.", 6000);
                                    return true;
                                }
                                if (data.nombreRed == "") {
                                    evento.mostrarMensaje(".divErrorEquipoAdicional", false, "Al parecer falta el nombre de red para el equipo.", 6000);
                                    return true;
                                }

                                if (data.rq == "") {
                                    evento.mostrarMensaje(".divErrorEquipoAdicional", false, "Falta seleccionar el estatus del software RQ", 6000);
                                    return true;
                                }
                            }
                        }

                        evento.enviarEvento('Seguimiento/GuardarEquipoAdicionalCenso', data, '#seccion-servicio-censo', function (respuesta) {
                            if (respuesta.code == 200) {
                                buttonPuntoArea.click();
                            } else {
                                evento.mostrarMensaje(".divErrorEquipoAdicional", false, "Error:" + respuesta.error + ". <br />Por favor contácte al administrador.", 6000);
                            }
                        });
                    });
                    $(".btnEliminarEquiposAdicionalesCenso").off("click");
                    $(".btnEliminarEquiposAdicionalesCenso").on("click", function () {
                        var fila = $(this).closest("tr.registrosAdicionales");
                        var datos = {
                            'id': $(this).attr("data-id")
                        };
                        evento.enviarEvento('Seguimiento/EliminarEquiposAdicionalesCenso', datos, '#seccion-servicio-censo', function (respuesta) {
                            if (respuesta.code == 200) {
                                fila.remove();
                            } else {
                                evento.mostrarMensaje(".divErrorEquipoAdicional", false, "Ocurrió un error al eliminar el equipo. Por favor contácte al administrador.", 6000);
                            }
                        });
                    });
                    $(".btnGuardarCambiosEquiposAdicionalesCenso").off("click");
                    $(".btnGuardarCambiosEquiposAdicionalesCenso").on("click", function () {
                        var fila = $(this).closest("tr.registrosAdicionales");
                        data = {
                            'servicio': servicio,
                            'area': data.area,
                            'punto': data.punto,
                            'id': fila.attr("data-id"),
                            'modelo': fila.find(".listModelosEquiposAdicionales").val(),
                            'modeloTexto': fila.find(".listModelosEquiposAdicionales option:selected").text(),
                            'serie': fila.find(".serieEquiposAdicionales").val(),
                            'ilegible': (fila.find(".ilegibleEquiposAdicionales").is(":checked")) ? 1 : 0,
                            'existe': 1,
                            'danado': (fila.find(".danadoEquiposAdicionales").is(":checked")) ? 1 : 0,
                            'etiqueta': (fila.find(".etiquetaEquiposAdicionales").length) ? fila.find(".etiquetaEquiposAdicionales").val() : '',
                            'estado': (fila.find(".listEstadosEquiposAdicionales").length) ? fila.find(".listEstadosEquiposAdicionales").val() : '',
                            'mac': (fila.find(".macEquiposAdicionales").length) ? fila.find(".macEquiposAdicionales").val() : '',
                            'so': (fila.find(".listSOEquiposAdicionales").length) ? fila.find(".listSOEquiposAdicionales").val() : '',
                            'nombreRed': (fila.find(".nombreRedEquiposAdicionales").length) ? fila.find(".nombreRedEquiposAdicionales").val() : '',
                            'rq': (fila.find(".listRQEquiposAdicionales").length) ? fila.find(".listRQEquiposAdicionales").val() : ''
                        }

                        if (data.modelo == "") {
                            evento.mostrarMensaje(".divErrorEquipoAdicional", false, "Falta seleccionar el modelo.", 4000);
                            return true;
                        }

                        if (data.serie == "" && datosEquipo.ilegible == 0) {
                            evento.mostrarMensaje(".divErrorEquipoAdicional", false, "Falta el número de serie. Marque la casilla 'Ilegible' en caso de que la serie no sea alcanzable o no se encuentre en el equipo.", 6000);
                            return true;
                        }

                        if ($("#txtEtiquetaEquipoAdicional").length) {
                            var regexEtiqueta = /^[0-9]{3}-[A-Z]{3}[0-9]{3}-L[0-9]?[0-9]?[0-9]S[0-9]?[0-9]?[0-9]-[0-9]?[0-9]{2}-[0-9]?[0-9]?[0-9]?[0-9]$/;
                            if (data.etiqueta == "" || !regexEtiqueta.test(data.etiqueta)) {
                                evento.mostrarMensaje(".divErrorEquipoAdicional", false, "Al parecer hay un error con el formato de la etiqueta.", 6000);
                                return true;
                            }

                            if (data.estado == "") {
                                evento.mostrarMensaje(".divErrorEquipoAdicional", false, "Falta seleccionar el estado del equipo.", 6000);
                                return true;
                            }

                            if (data.modeloTexto.indexOf("COMPUTADORA") >= 0) {
                                var regexMacAddress = /^([0-9A-F]{2}[:-]){5}([0-9A-F]{2})$/;
                                if (data.mac == "" || !regexMacAddress.test(data.mac)) {
                                    evento.mostrarMensaje(".divErrorEquipoAdicional", false, "Al parecer hay un error con el formato de la MAC Address", 6000);
                                    return true;
                                }

                                if (data.so == "") {
                                    evento.mostrarMensaje(".divErrorEquipoAdicional", false, "Falta seleccionar el S.O. del equipo.", 6000);
                                    return true;
                                }

                                if (data.nombreRed == "") {
                                    evento.mostrarMensaje(".divErrorEquipoAdicional", false, "Al parecer falta el nombre de red para el equipo.", 6000);
                                    return true;
                                }

                                if (data.rq == "") {
                                    evento.mostrarMensaje(".divErrorEquipoAdicional", false, "Falta seleccionar el estatus del software RQ", 6000);
                                    return true;
                                }
                            }
                        }

                        evento.enviarEvento('Seguimiento/GuardaCambiosEquiposAdicionalesCenso', data, '#seccion-servicio-censo', function (respuesta) {
                            if (respuesta.code == 200) {
                                evento.mostrarMensaje(".divErrorEquipoAdicional", true, "Todos los cambios han sido guardados.", 4000);
                            } else {
                                evento.mostrarMensaje(".divErrorEquipoAdicional", false, "Error:" + respuesta.error + ".<br />Por favor contácte al administrador.", 6000);
                            }
                        });
                    });
                });
            });
        });
    }

    function cargaAreasPuntosCenso() {
        var servicio = arguments[0];
        evento.enviarEvento('Seguimiento/CargaAreasPuntosCenso', {'servicio': servicio}, '#seccion-servicio-censo', function (respuesta) {
            $("#contentAreaPuntos").empty().append(respuesta.html);
            $("#btnAgregarAreaPuntos").off("click");
            $("#btnAgregarAreaPuntos").on("click", function () {
                var data = {
                    'servicio': servicio,
                    'area': $("#listAreasAtencion").val(),
                    'puntos': $.trim($("#txtCantidadPuntos").val())
                };
                if (data.area == "" || parseInt(data.puntos) <= 0) {
                    evento.mostrarMensaje(".divError", false, "El área y puntos son obligatorios", 4000);
                } else {
                    evento.enviarEvento('Seguimiento/AgregaAreaPuntosCenso', data, '#seccion-servicio-censo', function (respuesta) {
                        if (respuesta.code == 200) {
                            cargaAreasPuntosCenso(servicio);
                        } else {
                            evento.mostrarMensaje(".divError", false, "Ocurrió un error al guardar la información.", 4000);
                        }
                    });
                }
            });
            $(".btnGuardarCambiosAreasPuntos").off("click");
            $(".btnGuardarCambiosAreasPuntos").on("click", function () {
                var areasPuntos = [];
                $(".cantidadPuntosAreas").each(function () {
                    areasPuntos.push({
                        'Id': $(this).attr("data-id"),
                        'Cantidad': $.trim($(this).val())
                    });
                });
                evento.enviarEvento('Seguimiento/GuardaCambiosAreasPuntos', {'areasPuntos': areasPuntos}, '#seccion-servicio-censo', function (respuesta) {
                    if (respuesta.code == 200) {
                        cargaAreasPuntosCenso(servicio);
                    } else {
                        evento.mostrarMensaje(".divError", false, "Ocurrió un error al guardar la información.", 4000);
                    }
                });
            });
        });
    }

    var eventosParaSeccionSeguimientoCenso = function () {
        var datosTabla = arguments[0];
        var respuesta = arguments[1];
        var servicio = datosTabla[0];
        //evento para mostrar los detalles de las descripciones
        $('#detallesServicioCenso').on('click', function (e) {
            if ($('#masDetalles').hasClass('hidden')) {
                $('#masDetalles').removeClass('hidden');
                $('#detallesServicioCenso').empty().html('<a>- Detalles</a>');
            } else {
                $('#masDetalles').addClass('hidden');
                $('#detallesServicioCenso').empty().html('<a>+ Detalles</a>');
            }
        });
        $('#btnGuardarDatosGenerales').on('click', function (e) {
            guardarFormularioDatosGeneralesCenso(datosTabla);
        });
        $('#btnAgregaEquipoCenso').on('click', function (e) {
            if (validarFormularioDatosCenso()) {
                agregandoModeloCensoTabla();
                limpiarFormularioDatosCenso();
            }
        });
        $('#btnGuardarServicioCenso').on('click', function (e) {
            var datosTablaModelos = $('#data-table-censo-modelos').DataTable().rows().data();
            if (datosTablaModelos.length > 0) {
                guardarFormularioDatosCenso(datosTablaModelos, servicio);
            } else {
                evento.mostrarMensaje('.errorDatosCenso', false, 'Para guardar los Censos debe haber agregado un registro en la tabla un Censo.', 3000);
            }
        });
        $('#btnConcluirServicioCenso').on('click', function (e) {
            concluirServicioCenso([], datosTabla);
        });
        $('#btnGuardarCambiosServicioCenso').on('click', function (e) {
            guardarCambiosConcluirServicioCenso([], datosTabla);
        });
        //Evento encargado de eliminar un fila de la tabla censos
        $('#data-table-censo-modelos tbody').on('click', 'tr', function () {
            var datosTablaModelos = $('#data-table-censo-modelos').DataTable().rows(this).data();
            if (datosTablaModelos.length > 0) {
                eliminarFilaTablaModelos(datosTablaModelos, servicio);
            }
        });
        //Evento que vuelve a mostrar la lista de servicios de Poliza
        $('#btnRegresarSeguimientoCenso').off('click');
        $('#btnRegresarSeguimientoCenso').on('click', function () {
            $('#seccionSeguimientoServicio').empty().addClass('hidden');
            $('#listaPoliza').removeClass('hidden');
        });
        //Encargado de crear un nuevo servicio
        $('#btnNuevoServicioSeguimiento').off('click');
        $('#btnNuevoServicioSeguimiento').on('click', function () {
            var data = {servicio: servicio};
            servicios.nuevoServicio(
                    data,
                    respuesta.datosServicio.Ticket,
                    respuesta.datosServicio.IdSolicitud,
                    'Seguimiento/Servicio_Nuevo_Modal',
                    '#seccion-servicio-censo',
                    'Seguimiento/Servicio_Nuevo'
                    );
        });
        //Encargado de cancelar servicio
        $('#btnCancelarServicioSeguimiento').off('click');
        $('#btnCancelarServicioSeguimiento').on('click', function () {
            var data = {servicio: servicio, ticket: respuesta.datosServicio.Ticket};
            servicios.cancelarServicio(
                    data,
                    'Seguimiento/Servicio_Cancelar_Modal',
                    '#seccion-servicio-censo',
                    'Seguimiento/Servicio_Cancelar'
                    );
        });
        //Encargado de generar el archivo Pdf
        $('#btnGeneraPdfServicio').off('click');
        $('#btnGeneraPdfServicio').on('click', function () {
            var data = {servicio: datosTabla[0]};
            evento.enviarEvento('Seguimiento/Servicio_ToPdf', data, '#seccion-servicio-censo', function (respuesta) {
                window.open('/' + respuesta.link);
            });
        });
        if (respuesta.informacionDatosGenerales.length > 0) {
            servicios.initDocumentacionFirma(servicio, datosTabla[7], respuesta.informacionDatosGenerales[0].IdSucursal);
        }

        servicios.initBotonReasignarServicio(servicio, datosTabla[1], '#seccion-servicio-censo');
        //evento para crear nueva solicitud
        servicios.initBotonNuevaSolicitud(datosTabla[1], '#seccion-servicio-censo');
        servicios.eventosFolio(datosTabla[2], '#seccion-servicio-censo', servicio);
    };
    var personalizarDependiendoSucursalCenso = function () {
        var respuesta = arguments[0];
        if (respuesta.informacionDatosGenerales.length > 0) {
            select.cambiarOpcion('#selectSucursales', respuesta.informacionDatosGenerales[0].IdSucursal);
            $('#selectSucursales').attr('disabled', 'disabled');
            $('[href=#AreaPuntos]').parent('li').removeClass('hidden');
            $('[href=#EquiposPunto]').parent('li').removeClass('hidden');
        } else {
            if (respuesta.datosServicio.IdSucursal !== null) {
                select.cambiarOpcion('#selectSucursales', respuesta.datosServicio.IdSucursal);
            }
        }
    };
    var colocarBotonGuardarCambiosCenso = function (datosServicio) {
        if (datosServicio.Firma !== null) {
            $('#divBotonesServicioCenso').addClass('hidden');
            $('#divGuardarCambiosServicioCenso').removeClass('hidden');
        }
    }
    var guardarFormularioDatosGeneralesCenso = function () {
        var datosTabla = arguments[0];
        var sucursal = $('#selectSucursales').val();
        var descripcion = $('#inputDescripcionCenso').val();
        if (sucursal !== '' && descripcion !== '') {
            var data = {servicio: datosTabla[0], sucursal: sucursal, descripcion: descripcion};
            evento.enviarEvento('Seguimiento/GuardarDatosGeneralesCenso', data, '#seccion-servicio-censo', function (respuesta) {
                $('#selectSucursales').attr('disabled', 'disabled');
                $('[href=#AreaPuntos]').parent('li').removeClass('hidden');
                $('[href=#EquiposPunto]').parent('li').removeClass('hidden');
            });
        } else {
            evento.mostrarMensaje('.errorDatosGeneralesCenso', false, 'Debes llenar todos los campos para poder guardar la información', 3000)
        }
    };
    var validarFormularioDatosCenso = function () {
        var areaAtencion = $('#selectAreasAtencion').val();
        var punto = $('#inputPuntoCenso').val();
        var modelo = $('#selectModelosCenso').val();
        var serie = $('#inputSerieCenso').val();
        var numeroTerminal = $('#inputNumeroTerminalCenso').val();
        if (areaAtencion !== '' && punto !== '' && modelo !== '' && serie !== '' && numeroTerminal !== '') {
            if (punto > 0) {
                return validarCensoEnTabla();
            } else {
                evento.mostrarMensaje('.errorDatosCenso', false, 'Debes colocar un número positivo en el campo Punto.', 3000)
            }
        } else {
            evento.mostrarMensaje('.errorDatosCenso', false, 'Debes llenar todos los campos para poder agregar el Equipo.', 3000)
        }
    };
    var validarCensoEnTabla = function () {
        var filas = $('#data-table-censo-modelos').DataTable().rows().data();
        var nombreAreaAtencion = $('#selectAreasAtencion option:selected').text();
        var punto = $('#inputPuntoCenso').val();
        var serie = $('#inputSerieCenso').val();
        var repetidoSerie = false;
        if (filas.length > 0) {
            for (var i = 0; i < filas.length; i++) {
                if ($.trim(filas[i][0]) === nombreAreaAtencion && $.trim(filas[i][1]) === punto && $.trim(filas[i][3]) === serie) {
                    repetidoSerie = true;
                }
            }
        }

        if (!repetidoSerie) {
            return true;
        } else {
            evento.mostrarMensaje('.errorDatosCenso', false, 'Ya se agregó la Serie a esa Área de Atención y Punto, favor de eliminar el que esta registrado si quiere actualizarlo', 4000);
        }
    };
    var agregandoModeloCensoTabla = function () {
        var filas = [];
        var idAreaAtencion = $('#selectAreasAtencion').val();
        var nombreAreaAtencion = $('#selectAreasAtencion option:selected').text();
        var punto = $('#inputPuntoCenso').val();
        var idModelo = $('#selectModelosCenso').val();
        var nombreModelo = $('#selectModelosCenso option:selected').text();
        var serie = $('#inputSerieCenso').val();
        var numeroTerminal = $('#inputNumeroTerminalCenso').val();
        filas.push([nombreAreaAtencion, punto, nombreModelo, serie, numeroTerminal, idAreaAtencion, idModelo]);
        $.each(filas, function (key, value) {
            tabla.agregarFila('#data-table-censo-modelos', value);
            evento.mostrarMensaje('.errorDatosCenso', true, 'Datos agregados a la Tabla.', 3000);
        });
    };
    var limpiarFormularioDatosCenso = function () {
        var todos = arguments[0] || false;
        if (todos) {
            select.cambiarOpcion('#selectAreasAtencion', '');
            $('#inputPuntoCenso').val('');
        }

        select.cambiarOpcion('#selectModelosCenso', '');
        $('#inputSerieCenso').val('');
        $('#inputNumeroTerminalCenso').val('');
    };
    var guardarFormularioDatosCenso = function () {
        var datosTablaModelos = arguments[0];
        var servicio = arguments[1];
        var datosTabla = [];
        for (var i = 0; i < datosTablaModelos.length; i++) {
            datosTabla.push(datosTablaModelos[i]);
        }

        var data = {servicio: servicio, censos: datosTabla};
        evento.enviarEvento('Seguimiento/GuardarDatosCenso', data, '#seccion-servicio-censo', function (respuesta) {
            if (respuesta instanceof Array) {
                limpiarFormularioDatosCenso(true);
                recargandoTablaCensoModelos(respuesta, 'Datos guardados correctamente.', '.errorDatosCenso');
            } else {
                evento.mostrarMensaje('.errorDatosCenso', false, 'No se pudo guardar el Censo por favor de volver a intentarlo.', 3000);
            }
        });
    };
    var concluirServicioCenso = function () {
        var datosTablaModelos = arguments[0];
        var datosTablaPoliza = arguments[1];
        var servicio = datosTablaPoliza[0];
        var datosTabla = [];
        for (var i = 0; i < datosTablaModelos.length; i++) {
            datosTabla.push(datosTablaModelos[i]);
        }

        var data = {servicio: servicio, descripcion: '', censos: datosTabla, ticket: datosTablaPoliza[1], idSolicitud: datosTablaPoliza[2], operacion: '3'};
        servicios.validarTecnicoPoliza();
        servicios.modalCampoFirma(datosTablaPoliza[1], data);
    };
    var guardarCambiosConcluirServicioCenso = function () {
        var datosTablaModelos = arguments[0];
        var datosTablaPoliza = arguments[1];
        var servicio = datosTablaPoliza[0];
        var datosTabla = [];
        
        for (var i = 0; i < datosTablaModelos.length; i++) {
            datosTabla.push(datosTablaModelos[i]);
        }

        servicios.validarTecnicoPoliza();

        var data = {servicio: servicio, descripcion: '', censos: datosTabla, ticket: datosTablaPoliza[1], idSolicitud: datosTablaPoliza[2], seccion: '#seccion-servicio-censo'};

        var sucursal = $('#selectSucursales').val();
        var descripcion = $('#inputDescripcionCenso').val();
        if (sucursal !== '') {
            var dataGenerales = {servicio: servicio, sucursal: sucursal, descripcion: descripcion};
            evento.enviarEvento('Seguimiento/GuardarDatosGeneralesCenso', dataGenerales, '#seccion-servicio-censo', function (respuesta) {
                servicios.servicioValidacion(data, datosTablaPoliza[1]);
            });
        } else {
            servicios.servicioValidacion(data, datosTablaPoliza[1]);
        }
    }
    var eliminarFilaTablaModelos = function () {
        var datosTablaCensoModelos = arguments[0];
        var servicio = arguments[1];
        var mensaje = mensajeConfirmacionModal();
        evento.mostrarModal('Advertencia', mensaje);
        $('#btnModalConfirmar').addClass('hidden');
        $('#btnModalAbortar').addClass('hidden');
        $('#btnAceptarGuardarCambios').on('click', function () {
            evento.cerrarModal();
            var dataCensos = {servicio: servicio, serie: datosTablaCensoModelos[0][3], numeroTerminal: datosTablaCensoModelos[0][4]};
            evento.enviarEvento('Seguimiento/EliminarCenso', dataCensos, '#seccion-servicio-censo', function (respuesta) {
                if (!respuesta || respuesta instanceof Object) {
                    recargandoTablaCensoModelos(respuesta, 'Datos eliminados correctamente.', '.errorDatosCenso');
                } else {
                    evento.mostrarMensaje('.errorDatosCenso', false, 'No se pudo eliminar el Censo por favor de volver a intentarlo.', 3000);
                }
            });
        });
        $('#btnCancelarGuardarCambios').on('click', function () {
            evento.cerrarModal();
        });
    };
    var recargandoTablaCensoModelos = function () {
        var respuesta = arguments[0];
        var mensaje = arguments[1];
        var divError = arguments[2];
        tabla.limpiarTabla('#data-table-censo-modelos');
        $.each(respuesta, function (key, valor) {
            tabla.agregarFila('#data-table-censo-modelos', [valor.Sucursal, valor.Punto, valor.Linea + ' - ' + valor.Marca + ' - ' + valor.Modelo, valor.Serie, valor.Extra, valor.IdArea, valor.IdModelo], true);
        });
        evento.mostrarMensaje(divError, true, mensaje, 3000);
    };
    //Servicio Mantenimiento
    var iniciarElementosPaginaSeguimientoMantenimiento = function () {
        var respuesta = arguments[0];
        var datosTablaServicioPoliza = arguments[1];
        $('#listaPoliza').addClass('hidden');
        $('#seccionSeguimientoServicio').removeClass('hidden').empty().append(respuesta.formulario);
        select.crearSelect('#selectSucursalesMantenimiento');
        select.crearSelect('#selectAreasAtencionEquipoFaltante');
        select.crearSelect('#selectModeloEquipoFaltante');
        select.crearSelect('#selectAreasAtencionProblemasAdicionales');
        select.crearSelect('#selectAreaPuntoProblemasAdicionales');
        tabla.generaTablaPersonal('#data-table-puntos-censados', null, null, true, true);
        tabla.generaTablaPersonal('#data-table-problemas-adicionales');
        var columnas = servicios.datosTablaDocumentacionFirmada();
        tabla.generaTablaPersonal('#data-table-documetacion-firmada', respuesta.informacion.documentacionFirmada, columnas, null, null, [[0, 'desc']]);
        file.crearUpload('#evidenciasProblemasAdicionales', 'Seguimiento/guardarProblemasAdicionales');
        $("#divNotasServicio").slimScroll({height: '400px'});
        nota.initButtons({servicio: datosTablaServicioPoliza[0]}, 'Seguimiento');
    };
    var colocarBotonGuardarCambiosMantenimiento = function (datosServicio) {
        if (datosServicio.Firma !== null) {
            $('#divConcluirServicioMantenimiento').addClass('hidden');
            $('#divGuardarCambiosServicioMantenimiento').removeClass('hidden');
        }
    }
    var eventosParaSeccionSeguimientoMantenimiento = function () {
        var datosTablaPoliza = arguments[0];
        var respuesta = arguments[1];
        var servicio = datosTablaPoliza[0];
        //evento para mostrar los detalles de las descripciones
        $('#detallesServicioMantenimiento').on('click', function (e) {
            if ($('#masDetalles').hasClass('hidden')) {
                $('#masDetalles').removeClass('hidden');
                $('#detallesServicioMantenimiento').empty().html('<a>- Detalles</a>');
            } else {
                $('#masDetalles').addClass('hidden');
                $('#detallesServicioMantenimiento').empty().html('<a>+ Detalles</a>');
            }
        });
        $('#btnGuardarDatosMantenimiento').on('click', function (e) {
            guardarFormularioDatosMantenimiento(servicio);
        });
        $("#radioAreaAtencion").click(function () {
            $('#divAreaAtencion').removeClass('hidden');
            $('#divAreaPunto').addClass('hidden');
            select.cambiarOpcion('#selectAreaPuntoProblemasAdicionales', '');
        });
        $("#radioAreaPunto").click(function () {
            $('#divAreaPunto').removeClass('hidden');
            $('#divAreaAtencion').addClass('hidden');
            select.cambiarOpcion('#selectAreasAtencionProblemasAdicionales', '');
        });
        $('#btnAgregarProblemasAdicionales').on('click', function (e) {
            if (validarFormularioProblemasAdicionales()) {
                guardarFormularioProblemasAdicionales(servicio);
            }
        });
        //Evento encargado de eliminar un fila de la tabla censos
        $('#data-table-puntos-censados tbody').on('click', 'tr', function () {
            var datosTablaPuntosCensado = $('#data-table-puntos-censados').DataTable().rows(this).data();
            mostrarFormularioAntesYDespues(datosTablaPuntosCensado, servicio, datosTablaPoliza);
        });
        $('#btnConcluirServicioMantenimiento').on('click', function (e) {
            concluirServicioMantenimiento(servicio, datosTablaPoliza);
        });
        $('#btnGuardarCambiosServicioMantenimiento').on('click', function (e) {
            guardarCambiosConcluirServicioMantenimiento(servicio, datosTablaPoliza);
        });
        //Evento que vuelve a mostrar la lista de servicios de Poliza
        $('#btnRegresarSeguimientoMantenimiento').on('click', function () {
            location.reload();
        });
        //Encargado de crear un nuevo servicio
        $('#btnNuevoServicioSeguimiento').on('click', function () {
            var data = {servicio: servicio};
            servicios.nuevoServicio(
                    data,
                    respuesta.datosServicio.Ticket,
                    respuesta.datosServicio.IdSolicitud,
                    'Seguimiento/Servicio_Nuevo_Modal',
                    '#seccion-servicio-mantemiento',
                    'Seguimiento/Servicio_Nuevo'
                    );
        });
        //Encargado de cancelar el servicio
        $('#btnCancelarServicioSeguimiento').on('click', function () {
            var data = {servicio: servicio, ticket: respuesta.datosServicio.Ticket};
            servicios.cancelarServicio(
                    data,
                    'Seguimiento/Servicio_Cancelar_Modal',
                    '#seccion-servicio-mantemiento',
                    'Seguimiento/Servicio_Cancelar'
                    );
        });
        //Encargado de generar el archivo Pdf
        $('#btnGeneraPdfServicio').off('click');
        $('#btnGeneraPdfServicio').on('click', function () {
            var data = {servicio: servicio};
            evento.enviarEvento('Seguimiento/Servicio_ToPdf', data, '#seccion-servicio-mantemiento', function (respuesta) {
                window.open('/' + respuesta.link);
            });
        });
        if (respuesta.informacion.idSucursal !== undefined) {
            servicios.initDocumentacionFirma(servicio, datosTablaPoliza[7], respuesta.informacion.idSucursal);
        }
        servicios.initBotonReasignarServicio(servicio, datosTablaPoliza[1], '#seccion-servicio-mantemiento');
        //evento para crear nueva solicitud
        servicios.initBotonNuevaSolicitud(datosTablaPoliza[1], '#seccion-servicio-mantemiento');
        servicios.eventosFolio(datosTablaPoliza[2], '#seccion-servicio-mantemiento', servicio);
    };
    var guardarFormularioDatosMantenimiento = function () {
        var servicio = arguments[0];
        var sucursal = $('#selectSucursalesMantenimiento').val();
        if (sucursal !== '') {
            var data = {servicio: servicio, sucursal: sucursal};
            evento.enviarEvento('Seguimiento/guardarDatosMantenimiento', data, '#seccion-servicio-censo', function (respuesta) {
                if (respuesta instanceof Array) {
                    recargandoTablaPuntosCensados(respuesta, 'Datos guardados correctamente.', '.errorDatosMantenimiento');
                    datosMostrarSucursalGuardada();
                } else if (respuesta === 'noExisteCensoSucursal') {
                    evento.mostrarMensaje('.errorDatosMantenimiento', false, 'No hay un Censo leventado en esa Sucursal.', 3000);
                }
            });
        } else {
            evento.mostrarMensaje('.errorDatosMantenimiento', false, 'Debes llenar todos los campos para poder agregar el Equipo.', 3000);
        }
    };
    var recargandoTablaPuntosCensados = function () {
        var respuesta = arguments[0];
        var mensaje = arguments[1];
        var divError = arguments[2];
        tabla.limpiarTabla('#data-table-puntos-censados');
        $.each(respuesta, function (key, valor) {
            tabla.agregarFila('#data-table-puntos-censados', [valor.Area, valor.Punto, valor.Estatus, valor.IdArea, valor.IdServicio, valor.IdModelo, valor.Serie], true);
        });
        evento.mostrarMensaje(divError, true, mensaje, 3000);
    };
    var personalizarDependiendoSucursalMantenimiento = function () {
        var respuesta = arguments[0];
        if (respuesta.informacion.informacionDatosGeneralesMantenimiento.length > 0) {
            select.cambiarOpcion('#selectSucursalesMantenimiento', respuesta.informacion.informacionDatosGeneralesMantenimiento[0].IdSucursal);
            datosMostrarSucursalGuardada();
        } else {
            if (respuesta.datosServicio.IdSucursal !== null) {
                select.cambiarOpcion('#selectSucursalesMantenimiento', respuesta.datosServicio.IdSucursal);
            }
        }
    };
    var datosMostrarSucursalGuardada = function () {
        $('#selectSucursalesMantenimiento').attr('disabled', 'disabled');
        $('[href=#AntesDespues]').parent('li').removeClass('hidden');
        $('[href=#EquipoFaltante]').parent('li').removeClass('hidden');
        $('[href=#ProblemasAdicionales]').parent('li').removeClass('hidden');
        $('#divGuardarDatosMatenimiento').addClass('hidden');
        $('#divReporteFirmado').removeClass('hidden');
        $('#divConcluirServicioMantenimiento').removeClass('hidden');
    };
    var validarFormularioProblemasAdicionales = function () {
        var areaAtencion = $('#selectAreasAtencionProblemasAdicionales').val();
        var areaYPunto = $('#selectAreaPuntoProblemasAdicionales').val();
        var descripcion = $('#inputDescripcionProblemasAdicionales').val();
        var evidencias = $('#evidenciasProblemasAdicionales').val();
        if (areaAtencion !== '' || areaYPunto !== '') {
            if (descripcion !== '') {
                if (evidencias !== '') {
                    return true;
                } else {
                    evento.mostrarMensaje('.errorFormularioProblemasAdicionales', false, 'Debes seleccionar una imagen del problema.', 3000)
                }
            } else {
                evento.mostrarMensaje('.errorFormularioProblemasAdicionales', false, 'Debes escribir una descripción del problema.', 3000)
            }
        } else {
            evento.mostrarMensaje('.errorFormularioProblemasAdicionales', false, 'Debes de seleccionar el campo del Área del problema.', 3000)
        }
    };
    var guardarFormularioProblemasAdicionales = function () {
        var servicio = arguments[0];
        var arrayFormularioProblemasAdionales = agregandoProblemasAdicionalesArreglo()
        var data = {servicio: servicio, area: arrayFormularioProblemasAdionales[0][3], punto: arrayFormularioProblemasAdionales[0][1], descripcion: arrayFormularioProblemasAdionales[0][2]};
        file.enviarArchivos('#evidenciasProblemasAdicionales', 'Seguimiento/guardarProblemasAdicionales', '#seccion-servicio-mantemiento', data, function (respuesta) {
            if (respuesta instanceof Array) {
                limpiarFormularioProblemasAdicionales();
                recargandoTablaProblemasAdicionales(respuesta, 'Datos guardados correctamente.', '.errorFormularioProblemasAdicionales');
            } else {
                evento.mostrarMensaje('.errorFormularioProblemasAdicionales', false, 'No se pudo agregar el Problema Adicional por favor de volver a intentarlo.', 3000);
            }
        });
    };
    var agregandoProblemasAdicionalesArreglo = function () {
        var filas = [];
        var areaAtencion = $('#selectAreasAtencionProblemasAdicionales').val();
        var nombreAreaAtencion = $('#selectAreasAtencionProblemasAdicionales option:selected').text();
        var areaYPunto = $('#selectAreaPuntoProblemasAdicionales').val();
        var nombreAreaYPunto = $('#selectAreaPuntoProblemasAdicionales option:selected').text();
        var descripcionProblema = $('#inputDescripcionProblemasAdicionales').val();
        if (areaAtencion !== '') {
            var punto = '-'
            filas.push([nombreAreaAtencion, punto, descripcionProblema, areaAtencion]);
        }
        if (areaYPunto !== '') {
            var nombreAreaYPunto = nombreAreaYPunto.substring(0, nombreAreaYPunto.indexOf(' ', ) + 2);
            var ids = areaYPunto.split("|", 2);
            var idAreaAtencion = ids[0];
            var punto = ids[1];
            filas.push([nombreAreaYPunto, punto, descripcionProblema, idAreaAtencion]);
        }
        return filas;
    };
    var limpiarFormularioProblemasAdicionales = function () {
        select.cambiarOpcion('#selectAreasAtencionProblemasAdicionales', '');
        select.cambiarOpcion('#selectAreaPuntoProblemasAdicionales', '');
        $('#inputDescripcionProblemasAdicionales').val('');
        file.limpiar('#evidenciasProblemasAdicionales');
    };
    var recargandoTablaProblemasAdicionales = function () {
        var respuesta = arguments[0];
        var mensaje = arguments[1];
        var divError = arguments[2];
        var columnas = datosNuevosTablaProblemasAdicionales();
        tabla.limpiarTabla('#data-table-problemas-adicionales');
        tabla.generaTablaPersonal('#data-table-problemas-adicionales', respuesta, columnas, true, null, [[0, 'desc']]);
        evento.mostrarMensaje(divError, true, mensaje, 3000);
    };
    var datosNuevosTablaProblemasAdicionales = function () {
        var columnas = [
            {data: 'Sucursal'},
            {data: 'Punto',
                render: function (data, type, row, meta) {
                    if (data === '0') {
                        data = '-';
                    }
                    return data;
                }
            },
            {data: 'Descripcion'},
            {data: null,
                sClass: 'Evidencias',
                render: function (data, type, row, meta) {
                    var evidencias = data.Evidencias.split(',');
                    var filas = [];
                    $.each(evidencias, function (key, valor) {
                        filas.push(['<a href="' + valor + '" target="_blank"> <img src="' + valor + '" title="" style="max-height:150px"/> </a>']);
                    });
                    return filas;
                }
            },
            {data: 'Id'},
            {data: null,
                sClass: 'Acciones',
                render: function (data, type, row, meta) {
                    return '<a id="btnEliminarProblemaAdicional' + row.Id + '" onclick="eventoEliminarProblemaAdicional(' + row.Id + ',' + row.IdServicio + ');" class="btn btn-danger btn-xs "><i class="fa fa-trash-o"></i> Eliminar</a> </a>';
                }}
        ];
        return columnas;
    };
    var mostrarFormularioAntesYDespues = function () {
        var datosTablaPuntosCensados = arguments[0];
        var servicio = arguments[1];
        var datosTablaPoliza = arguments[2];
        var dataTabla = {servicio: servicio, area: datosTablaPuntosCensados[0][3], punto: datosTablaPuntosCensados[0][1], servicioCenso: datosTablaPuntosCensados[0][4], modelo: datosTablaPuntosCensados[0][5], serie: datosTablaPuntosCensados[0][6]};
        evento.enviarEvento('Seguimiento/mostrarFormularioAntesYDespues', dataTabla, '#seccion-servicio-mantemiento', function (respuesta) {
            iniciarAntesYDespues(respuesta, datosTablaPuntosCensados, servicio);
            eventosAntesYDespues(datosTablaPuntosCensados, servicio, respuesta, datosTablaPoliza);
        });
    };
    var iniciarAntesYDespues = function () {
        var file1 = new Upload();
        var file2 = new Upload();
        var respuesta = arguments[0];
        var datosTablaPuntosCensados = arguments[1];
        var servicio = arguments[2];
        $('#seccionSeguimientoServicio').addClass('hidden');
        $('#antesYDespues').removeClass('hidden').empty().append(respuesta.formulario);
        $("#wizard").bwizard(
                {backBtnText: '&larr; Anterior'},
                {nextBtnText: 'Siguiente &rarr;'});
        tabla.generaTablaPersonal('#data-table-problemas-equipo');
        tabla.generaTablaPersonal('#data-table-equipos-faltantes', null, null, true, true);
        select.crearSelect('#selectEquipoAntesYDespues');
        select.crearSelect('#selectUtilizadoEquipoFaltante');
        select.crearSelect('#selectEquipoAnteDespues');
        select.crearSelect('#selectMaterialAntesDespues');
        select.crearSelect('#selectEquipoRefaccionAntesDespues');
        select.crearSelect('#selectRefaccionAntesDespues');
        file1.crearUpload('#evidenciasAntes',
                'Seguimiento/guardarEvidenciasAntesYDespues',
                null,
                null,
                respuesta.evidenciaAntes,
                'Seguimiento/Eliminar_Evidencia',
                'evidenciasAntes',
                null,
                null,
                null,
                true,
                {servicio: servicio, area: datosTablaPuntosCensados[0][3], punto: datosTablaPuntosCensados[0][1], operacion: 'Antes'}
        );
        file2.crearUpload('#evidenciasDespues',
                'Seguimiento/guardarEvidenciasAntesYDespues',
                null,
                null,
                respuesta.evidenciaDespues,
                'Seguimiento/Eliminar_Evidencia',
                'evidenciasDespues',
                null,
                null,
                null,
                true,
                {servicio: servicio, area: datosTablaPuntosCensados[0][3], punto: datosTablaPuntosCensados[0][1], operacion: 'Despues'}
        );
        $('.kv-file-zoom').addClass('hidden');
    };
    var eventosAntesYDespues = function () {
        var datosTablaPuntosCensados = arguments[0];
        var servicio = arguments[1];
        var respuesta = arguments[2];
        var datosTablaPoliza = arguments[3];
        var sucursal = respuesta.idSucursal[0].IdSucursal;
        var nombreCampo = '';
        $('#btnGuardarAntes').on('click', function (e) {
            if (validarCampos($('#inputDescripcionAntes').val(), '.errorFormularioAntes', 'Debes llenar el campo de Observaciones.')) {
                guardarFormularioAntesYDespues(datosTablaPuntosCensados, servicio, 'Antes', sucursal);
            }
        });
        $('#btnGuardarDespues').on('click', function (e) {
            if (validarCampos($('#inputDescripcionDespues').val(), '.errorFormularioDespues', 'Debes llenar el campo de Observaciones.')) {
                guardarFormularioAntesYDespues(datosTablaPuntosCensados, servicio, 'Despues', sucursal);
            }
        });
        $('#btnRegresarAntesYDespues').on('click', function () {
            $('#antesYDespues').empty().addClass('hidden');
            $('#seccionSeguimientoServicio').removeClass('hidden');
        });
        $("#selectEquipoAntesYDespues").on("change", function () {
            $('#datosProblemaEquipo').removeClass('hidden');
            var serie = $('#selectEquipoAntesYDespues option:selected').attr('data-serie');
            var modelo = $('#selectEquipoAntesYDespues option:selected').attr('value');
            if ($('#selectEquipoAntesYDespues option:selected').text() === 'Seleccionar') {
                $('#datosProblemaEquipo').addClass('hidden');
            }

            file3.crearUpload('#evidenciasFallasEquipo',
                    'Seguimiento/guardarEvidenciasProblemasEquipo',
                    null,
                    null,
                    null,
                    null,
                    null,
                    null,
                    null,
                    null,
                    true,
                    {servicio: servicio, area: datosTablaPuntosCensados[0][3], punto: datosTablaPuntosCensados[0][1], servicioCenso: datosTablaPuntosCensados[0][4], modelo: modelo, serie: serie}
            );
        });
        $('#btnGuardarFallasEquipo').on('click', function () {
            guardarFormularioFallasEquipo(datosTablaPuntosCensados, servicio, datosTablaPoliza);
        });
        $('#selectUtilizadoEquipoFaltante').on('change', function () {
            var utilizar = $('#selectUtilizadoEquipoFaltante option:selected').attr('value');
            switch (utilizar) {
                case '1':
                    $('#seleccionEquipoAntesDespues').removeClass('hidden');
                    $('#seleccionMaterialAntesDespues').addClass('hidden');
                    $('#seleccionRefaccionAntesDespues').addClass('hidden');
                    nombreCampo = 'equipo';
                    break;
                case '2':
                    $('#seleccionEquipoAntesDespues').addClass('hidden');
                    $('#seleccionMaterialAntesDespues').removeClass('hidden');
                    $('#seleccionRefaccionAntesDespues').addClass('hidden');
                    nombreCampo = 'material';
                    break;
                case '3':
                    $('#seleccionEquipoAntesDespues').addClass('hidden');
                    $('#seleccionMaterialAntesDespues').addClass('hidden');
                    $('#seleccionRefaccionAntesDespues').removeClass('hidden');
                    nombreCampo = 'refaccion';
                    break;
                default:
                    $('#seleccionEquipoAntesdeDespues').addClass('hidden');
                    $('#seleccionMaterialAntesDespues').addClass('hidden');
                    $('#seleccionRefaccionAntesDespues').addClass('hidden');
            }
        });
        $('#selectEquipoRefaccionAntesDespues').on('change', function () {
            var objetoNuevoComponentesEquipo = {};
            $.each(respuesta.componentesEquipo, function (key, valor) {
                objetoNuevoComponentesEquipo[key] = {Id: valor.IdCom, Nombre: valor.Componente, IdMod: valor.IdMod};
            });
            select.setOpcionesSelect('#selectRefaccionAntesDespues', objetoNuevoComponentesEquipo, $('#selectEquipoRefaccionAntesDespues').val(), 'IdMod');
            if ($('#selectEquipoRefaccionAntesDespues').val() !== '') {
                $('#selectRefaccionAntesDespues').removeAttr('disabled');
            } else {
                $('#selectRefaccionAntesDespues').attr('disabled', 'disabled');
            }
        });
        $('#btnAgregarEquipoFaltanteMantenimiento').on('click', function (e) {
            redireccionEquipoFaltanteAntesDespues(nombreCampo);
        });
        $('#btnGuardarTablaEquiposMantenimiento').on('click', function (e) {
            var datosTablaModelos = $('#data-table-equipos-faltantes').DataTable().rows().data();
            if (datosTablaModelos.length > 0) {
                guardarDatosTablaEquipoFaltante(datosTablaModelos, servicio, datosTablaPoliza, sucursal, datosTablaPuntosCensados);
            } else {
                evento.mostrarMensaje('.errorTablaEquipoFaltante', false, 'Para guardar los Equipos faltantes debe haber agregado un registro en la tabla.', 4000);
            }
        });
        $('#data-table-equipos-faltantes tbody').on('click', 'tr', function () {
            var datosTablaEquiposFaltantes = $('#data-table-equipos-faltantes').DataTable().rows(this).data();
            eliminarFilaTablaEquiposFaltantes(datosTablaEquiposFaltantes, servicio, datosTablaPuntosCensados);
        });
    };
    var guardarFormularioFallasEquipo = function () {
        var datosTablaPuntosCensados = arguments[0];
        var servicio = arguments[1];
        var datosTablaPoliza = arguments[2];
        var serie = $('#selectEquipoAntesYDespues option:selected').attr('data-serie');
        var modelo = $('#selectEquipoAntesYDespues option:selected').attr('value');
        var descripcion = $('#inputDescripcionFallasEquipo').val();
        if (descripcion !== '') {
            var data = {servicio: servicio, area: datosTablaPuntosCensados[0][3], punto: datosTablaPuntosCensados[0][1], descripcion: descripcion, servicioCenso: datosTablaPuntosCensados[0][4], modelo: modelo, serie: serie, ticket: datosTablaPoliza[1], idSolicitud: datosTablaPoliza[2]};
            evento.enviarEvento('Seguimiento/guardarProblemasEquipo', data, '#seccion-servicio-mantemiento-puntos-censados', function (respuesta) {
                if (respuesta !== 'existeRegistro') {
                    if (respuesta !== 'faltaEvidencia') {
                        limpiarFormularioFallasEquipo();
                        recargandoTablaFallasEquipo(respuesta, 'Datos guardados correctamente.', '#errorGuardarEquipo');
                        file3.destruir('#evidenciasFallasEquipo');
                    } else {
                        evento.mostrarMensaje('#errorFallasEquipo', false, 'Falta seleccionar un Archivo o Evidencia.', 3000);
                    }
                } else {
                    evento.mostrarMensaje('#errorFallasEquipo', false, 'Ya se agrego un problema a ese equipo.', 3000);
                }
            });
        } else {
            evento.mostrarMensaje('#errorFallasEquipo', false, 'Debes llenar el campo de Observaciones del Problema.', 3000)
        }
    };
    var limpiarFormularioFallasEquipo = function () {
        select.cambiarOpcion('#selectEquipoAntesYDespues', '');
        $('#inputDescripcionFallasEquipo').val('');
        file.limpiar('#evidenciasFallasEquipo');
    };
    var validarCampos = function () {
        var descripcion = arguments[0];
        var divError = arguments[1];
        var mensajeError = arguments[2];
        if (descripcion !== '') {
            return true
        } else {
            evento.mostrarMensaje(divError, false, mensajeError, 3000)
        }
    };
    var guardarFormularioAntesYDespues = function () {
        var datosTablaPuntosCensados = arguments[0];
        var servicio = arguments[1];
        var operacion = arguments[2];
        var sucursal = arguments[3];
        var descripcion = $('#inputDescripcion' + operacion).val();
        var data = {servicio: servicio, area: datosTablaPuntosCensados[0][3], punto: datosTablaPuntosCensados[0][1], descripcion: descripcion, operacion: operacion, sucursal: sucursal};
        evento.enviarEvento('Seguimiento/guardarAntesYDespues', data, '#seccion-servicio-mantemiento-puntos-censados', function (respuesta) {
            if (respuesta !== 'faltaEvidencia') {
                $('#antesYDespues').empty().addClass('hidden');
                $('#seccionSeguimientoServicio').removeClass('hidden');
                recargandoTablaPuntosCensados(respuesta, 'Datos guardados correctamente.', '.errorPuntosCensados')
            } else {
                evento.mostrarMensaje('.errorFormulario' + operacion, false, 'Falta seleccionar un Archivo o Evidencia.', 3000);
            }
        });
    };
    var redireccionEquipoFaltanteAntesDespues = function () {
        var nombreCampo = arguments[0];
        var selectUtilizado = $('#selectUtilizadoEquipoFaltante').val();
        if (selectUtilizado !== '') {

            switch (nombreCampo) {
                case 'equipo':
                    var selectEquipo = $('#selectEquipoAnteDespues').val();
                    var cantidadEquipo = $('#inputEquipoEquipoFaltanteCantidad').val();
                    var nombreEquipo = $('#selectEquipoAnteDespues option:selected').text();
                    if (selectEquipo !== '') {
                        if (cantidadEquipo > 0) {
                            var data = {tipoItem: '1', item: selectEquipo, cantidad: cantidadEquipo, nombre: nombreEquipo};
                            if (validarEquipoFaltanteEnTabla(nombreEquipo)) {
                                agregandoEquipoFaltanteTabla(data);
                            }
                        } else {
                            evento.mostrarMensaje('.errorFormularioEquipoFaltante', false, 'Debes llenar el campo de Cantidad con un número positivo.', 3000)
                        }
                    } else {
                        evento.mostrarMensaje('.errorFormularioEquipoFaltante', false, 'Debes Selecionar un Equipo.', 3000)
                    }
                    break;
                case 'material':
                    var selectMaterial = $('#selectMaterialAntesDespues').val();
                    var cantidadMaterial = $('#inputMaterialEquipoFaltanteCantidad').val();
                    var nombreMaterial = $('#selectMaterialAntesDespues option:selected').text();
                    if (selectMaterial !== '') {
                        if (cantidadMaterial > 0) {
                            var data = {tipoItem: '2', item: selectMaterial, cantidad: cantidadMaterial, nombre: nombreMaterial};
                            if (validarEquipoFaltanteEnTabla(nombreMaterial)) {
                                agregandoEquipoFaltanteTabla(data);
                            }
                        } else {
                            evento.mostrarMensaje('.errorFormularioEquipoFaltante', false, 'Debes llenar el campo de Cantidad con un número positivo.', 3000)
                        }
                    } else {
                        evento.mostrarMensaje('.errorFormularioEquipoFaltante', false, 'Debes Selecionar un Material.', 3000)
                    }
                    break;
                case 'refaccion':
                    var selectEquipo = $('#selectEquipoRefaccionAntesDespues').val();
                    var selectRefaccion = $('#selectRefaccionAntesDespues').val();
                    var cantidadRefaccion = $('#inputRefaccionEquipoFaltanteCantidad').val();
                    var nombreRefaccion = $('#selectRefaccionAntesDespues option:selected').text();
                    if (selectEquipo !== '') {
                        if (selectRefaccion !== '') {
                            if (cantidadRefaccion > 0) {
                                var data = {tipoItem: '3', item: selectRefaccion, cantidad: cantidadRefaccion, nombre: nombreRefaccion};
                                if (validarEquipoFaltanteEnTabla(nombreRefaccion)) {
                                    agregandoEquipoFaltanteTabla(data);
                                }
                            } else {
                                evento.mostrarMensaje('.errorFormularioEquipoFaltante', false, 'Debes llenar el campo de Cantidad con un número positivo.', 3000)
                            }
                        } else {
                            evento.mostrarMensaje('.errorFormularioEquipoFaltante', false, 'Debes Selecionar una Refacción.', 3000)
                        }
                    } else {
                        evento.mostrarMensaje('.errorFormularioEquipoFaltante', false, 'Debes Selecionar un Equipo.', 3000)
                    }
                    break;
            }
        } else {
            evento.mostrarMensaje('.errorFormularioEquipoFaltante', false, 'Debes Selecionar un Equipo o Material.', 3000)
        }
    };
    var validarEquipoFaltanteEnTabla = function () {
        var nombre = arguments[0];
        var filas = $('#data-table-equipos-faltantes').DataTable().rows().data();
        var repetido = false;
        if (filas.length > 0) {
            for (var i = 0; i < filas.length; i++) {
                if ($.trim(filas[i][1]) === nombre) {
                    repetido = true;
                }
            }
        }

        if (!repetido) {
            return true;
        } else {
            evento.mostrarMensaje('.errorFormularioEquipoFaltante', false, 'Ya se agrego el Material o Equipo', 3000);
        }
    };
    var agregandoEquipoFaltanteTabla = function () {
        var data = arguments[0];
        var tipoItem = '';
        var filas = [];
        switch (data.tipoItem) {
            case '1':
                tipoItem = 'Equipo';
                break;
            case '2':
                tipoItem = 'Material';
                break;
            case '3':
                tipoItem = 'Refacción';
                break;
            default:
        }

        filas.push([tipoItem, data.nombre, data.cantidad, data.item, data.tipoItem]);
        $.each(filas, function (key, value) {
            tabla.agregarFila('#data-table-equipos-faltantes', value);
            select.cambiarOpcion('#selectUtilizadoEquipoFaltante', '');
            select.cambiarOpcion('#selectEquipoAnteDespues', '');
            select.cambiarOpcion('#selectMaterialAntesDespues', '');
            select.cambiarOpcion('#selectEquipoRefaccionAntesDespues', '');
            select.cambiarOpcion('#selectRefaccionAntesDespues', '');
            $('#inputEquipoEquipoFaltanteCantidad').val('');
            $('#inputMaterialEquipoFaltanteCantidad').val('');
            $('#inputRefaccionEquipoFaltanteCantidad').val('');
        });
        evento.mostrarMensaje('.errorFormularioEquipoFaltante', true, 'Datos Agregados a la tabla correctamente.', 3000);
    };
    var guardarDatosTablaEquipoFaltante = function () {
        var datosTablaEquipoFaltante = arguments[0];
        var servicio = arguments[1];
        var datosTablaPoliza = arguments[2];
        var sucursal = arguments[3];
        var datosTablaPuntosCensados = arguments[4];
        var datosTabla = [];
        for (var i = 0; i < datosTablaEquipoFaltante.length; i++) {
            datosTabla.push(datosTablaEquipoFaltante[i]);
        }

        var data = {servicio: servicio, equipoFaltante: datosTabla, ticket: datosTablaPoliza[1], solicitud: datosTablaPoliza[2], sucursal: sucursal, area: datosTablaPuntosCensados[0][3], punto: datosTablaPuntosCensados[0][1]};
        evento.enviarEvento('Seguimiento/guardarEquiposFaltantes', data, '#seccion-servicio-mantemiento-puntos-censados', function (respuesta) {
            if (respuesta instanceof Array) {
                recargandoTablaEquiposFaltantes(respuesta, 'Datos guardados correctamente.', '.errorTablaEquipoFaltante');
                select.cambiarOpcion('#selectAreasAtencionEquipoFaltante', '');
                select.cambiarOpcion('#selectModeloEquipoFaltante', '');
            } else {
                evento.mostrarMensaje('.errorTablaEquipoFaltante', false, 'No se pudo guardar el Censo por favor de volver a intentarlo.', 3000);
            }
        });
    };
    var eliminarFilaTablaEquiposFaltantes = function () {
        var datosTablaEquiposFaltantes = arguments[0];
        var servicio = arguments[1];
        var datosTablaPuntosCensados = arguments[2];
        var mensaje = mensajeConfirmacionModal();
        evento.mostrarModal('Advertencia', mensaje);
        $('#btnModalConfirmar').addClass('hidden');
        $('#btnModalAbortar').addClass('hidden');
        $('#btnAceptarGuardarCambios').on('click', function () {
            evento.cerrarModal();
            var dataEquiposFaltantes = {servicio: servicio, area: datosTablaPuntosCensados[0][3], punto: datosTablaPuntosCensados[0][1], modelo: datosTablaEquiposFaltantes[0][3], tipoItem: datosTablaEquiposFaltantes[0][4], };
            evento.enviarEvento('Seguimiento/EliminarEquipoFaltante', dataEquiposFaltantes, '#seccion-servicio-mantemiento-puntos-censados', function (respuesta) {
                if (respuesta instanceof Array) {
                    recargandoTablaEquiposFaltantes(respuesta, 'Datos eliminados correctamente.', '.errorTablaEquipoFaltante');
                } else if (respuesta === 'NoExiste') {
                    evento.mostrarMensaje('.errorTablaEquipoFaltante', false, 'No se puede eliminar este registro hasta que presione el botón de Guardar Tablas de Equipos.', 5000);
                } else {
                    recargandoTablaEquiposFaltantes(respuesta, 'Datos eliminados correctamente.', '.errorTablaEquipoFaltante');
                }
            });
        });
        $('#btnCancelarGuardarCambios').on('click', function () {
            evento.cerrarModal();
        });
    };
    var recargandoTablaEquiposFaltantes = function () {
        var respuesta = arguments[0];
        var mensaje = arguments[1];
        var divError = arguments[2];
        tabla.limpiarTabla('#data-table-equipos-faltantes');
        $.each(respuesta, function (key, valor) {
            tabla.agregarFila('#data-table-equipos-faltantes', [valor.NombreItem, valor.Equipo, valor.Cantidad, valor.IdModelo, valor.TipoItem], true);
        });
        evento.mostrarMensaje(divError, true, mensaje, 3000);
    };
    var concluirServicioMantenimiento = function () {
        var servicio = arguments[0];
        var datosTablaPoliza = arguments[1];
        var sucursal = $('#selectSucursalesMantenimiento').val();
        var data = {sucursal: sucursal, servicio: servicio};
        evento.enviarEvento('Seguimiento/verificarDocumentacion', data, '#seccion-servicio-mantemiento', function (respuesta) {
            if (respuesta !== 'faltaDocumentacion') {
                var dataConcluir = {servicio: servicio, descripcion: '', ticket: datosTablaPoliza[1], idSolicitud: datosTablaPoliza[2], sucursal: sucursal, operacion: '3'};
                servicios.validarTecnicoPoliza();
                servicios.modalCampoFirma(datosTablaPoliza[1], dataConcluir);
            } else {
                evento.mostrarMensaje('.errorDatosMantenimiento', false, 'Favor de Documentar todos los Puntos Censados en la Sección Antes y Después.', 5000);
            }
        });
    };
    var guardarCambiosConcluirServicioMantenimiento = function () {
        var servicio = arguments[0];
        var datosTablaPoliza = arguments[1];
        var sucursal = $('#selectSucursalesMantenimiento').val();
        var data = {sucursal: sucursal, servicio: servicio};
        evento.enviarEvento('Seguimiento/verificarDocumentacion', data, '#seccion-servicio-mantemiento', function (respuesta) {
            if (respuesta !== 'faltaDocumentacion') {
                var datavalicacion = {servicio: servicio, descripcion: '', ticket: datosTablaPoliza[1], idSolicitud: datosTablaPoliza[2], sucursal: sucursal, operacion: '3', seccion: '#seccion-servicio-mantemiento'};
                servicios.validarTecnicoPoliza();
                servicios.servicioValidacion(datavalicacion);
            } else {
                evento.mostrarMensaje('.errorDatosMantenimiento', false, 'Favor de Documentar todos los Puntos Censados en la Sección Antes y Después.', 5000);
            }
        });
    };
    //Servicio Correctivo
    var iniciarElementosPaginaSeguimientoCorrectivo = function () {
        var respuesta = arguments[0];
        var datosTabla = arguments[1];
        var evidenciaReporteFalso = [];
        var evidenciaImpericia = [];
        var evidenciaFallaEquipo = [];
        var evidenciaFallaComponente = [];
        var evidenciaReporteMultimedia = [];
        var evidenciaEnvioEquipo = [];
        var evidenciaEntregaEquipo = [];
        var evidenciaReparacionSinEquipo = [];
        var evidenciaReparacionConEquipo = [];
        var evidenciaCambioEquipo = [];
        $('#listaPoliza').addClass('hidden');
        $('#seccionSeguimientoServicio').removeClass('hidden').empty().append(respuesta.formulario);
        select.crearSelect('#selectSucursalesCorrectivo');
        select.crearSelect('#selectAreaPuntoCorrectivo');
        select.crearSelect('#selectEquipoCorrectivo');
        select.crearSelect('#selectTipoFallaEquipoCorrectivo');
        select.crearSelect('#selectImpericiaTipoFallaEquipoCorrectivo');
        select.crearSelect('#selectFallaDiagnosticoCorrectivo');
        select.crearSelect('#selectImpericiaFallaDiagnosticoCorrectivo');
        select.crearSelect('#selectComponenteDiagnosticoCorrectivo');
        select.crearSelect('#selectTipoFallaComponenteCorrectivo');
        select.crearSelect('#selectFallaComponenteDiagnosticoCorrectivo');
        select.crearSelect('#selectRefaccionSolicitud');
        select.crearSelect('#selectEquipoSolicitud');
        select.crearSelect('#selectGarantiaSolicitud');
        select.crearSelect('#selectEquipoRespaldo');
        select.crearSelect('#selectTipoEnvioGarantia');
        select.crearSelect('#selectListaTipoEnvioGarantia');
        select.crearSelect('#selectEquipoRespaldoEntregaEnvioGarantia');
        select.crearSelect('#selectSolucionReparacionSinEquipo');
        select.crearSelect('#selectRefaccionSolucionReparacionConRefaccion');
        select.crearSelect('#selectEquipoSolucionCambioEquipo');
        $('#inputNumeroTerminalCorrectivo').mask("aaaaaa99");
        tabla.generaTablaPersonal('#data-table-servicios-solicitudes-refacciones', null, null, true, true);
        tabla.generaTablaPersonal('#data-table-solicitud-refacciones', null, null, true, true);
        tabla.generaTablaPersonal('#data-table-servicios-solicitudes-equipos', null, null, true, true);
        tabla.generaTablaPersonal('#data-table-solicitud-equipos');
        tabla.generaTablaPersonal('#data-table-reparacion-refaccion');
        tabla.generaTablaPersonal('#data-table-reparacion-refaccion-stock', null, null, true, true, [], true, 'lfrtip', false);
        tabla.generaTablaPersonal('#data-table-reparacion-cambio-stock', null, null, true, true, [], true, 'lfrtip', false);
        $('#data-table-reparacion-refaccion-stock tbody').on('click', 'tr', function () {
            var check = $(this).find(".checkRefaccionesStock");
            if (check.hasClass("fa-square-o")) {
                check.removeClass("fa-square-o");
                check.addClass("fa-check-square-o");
            } else {
                check.removeClass("fa-check-square-o");
                check.addClass("fa-square-o");
            }
        });
        $('#data-table-reparacion-cambio-stock').on('click', 'tr', function () {
            var check = $(this).find(".checkEquipoStock");
            if (check.hasClass("fa-square-o")) {
                $(".checkEquipoStock").removeClass("fa-check-square-o");
                $(".checkEquipoStock").addClass("fa-square-o");
                check.removeClass("fa-square-o");
                check.addClass("fa-check-square-o");
            } else {
                $(".checkEquipoStock").removeClass("fa-check-square-o");
                $(".checkEquipoStock").addClass("fa-square-o");
            }
        });
        $('#entregaFechaGarantia').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss'
        });
        if (respuesta.informacion.diagnosticoEquipo !== null) {
            if (respuesta.informacion.diagnosticoEquipo.length > 0) {
                switch (respuesta.informacion.diagnosticoEquipo[0].IdTipoDiagnostico) {
                    case '1':
                        evidenciaReporteFalso = respuesta.informacion.evidenciasDiagnosticoEquipo;
                        break;
                    case '2':
                        evidenciaImpericia = respuesta.informacion.evidenciasDiagnosticoEquipo;
                        break;
                    case '3':
                        evidenciaFallaEquipo = respuesta.informacion.evidenciasDiagnosticoEquipo;
                        break;
                    case '4':
                        evidenciaFallaComponente = respuesta.informacion.evidenciasDiagnosticoEquipo;
                        break;
                    case '5':
                        evidenciaReporteMultimedia = respuesta.informacion.evidenciasDiagnosticoEquipo;
                        break;
                    default:
                }
            }
        }

        if (respuesta.informacion.evidenciasEnvioEquipo !== null) {
            if (respuesta.informacion.evidenciasEnvioEquipo !== undefined) {
                evidenciaEnvioEquipo = respuesta.informacion.evidenciasEnvioEquipo;
                evidenciaEntregaEquipo = respuesta.informacion.evidenciasEntregaEquipo;
            }
        }

        if (respuesta.informacion.correctivosSoluciones !== null) {
            if (respuesta.informacion.correctivosSoluciones.length > 0) {
                switch (respuesta.informacion.correctivosSoluciones[0].IdTipoSolucion) {
                    case '1':
                        evidenciaReparacionSinEquipo = respuesta.informacion.evidenciasCorrectivosSoluciones;
                        break;
                    case '2':
                        evidenciaReparacionConEquipo = respuesta.informacion.evidenciasCorrectivosSoluciones;
                        break;
                    case '3':
                        evidenciaCambioEquipo = respuesta.informacion.evidenciasCorrectivosSoluciones;
                        break;
                    default:
                }
            }
        }

        file.crearUpload('#evidenciasReporteFalsoCorrectivo',
                'Seguimiento/guardarDiagnosticoEquipo',
                null,
                null,
                evidenciaReporteFalso,
                'Seguimiento/Eliminar_EvidenciaDiagnostico',
                datosTabla[0]
                );
        file.descargarImagen(evidenciaReporteFalso);
        file.crearUpload('#evidenciasImpericiaCorrectivo',
                'Seguimiento/guardarDiagnosticoEquipo',
                null,
                null,
                evidenciaImpericia,
                'Seguimiento/Eliminar_EvidenciaDiagnostico',
                datosTabla[0],
                true,
                0,
                null,
                null,
                null,
                null,
                2
                );
        file.descargarImagen(evidenciaImpericia);
        file.crearUpload('#evidenciasFallaEquipoCorrectivo',
                'Seguimiento/guardarDiagnosticoEquipo',
                null,
                null,
                evidenciaFallaEquipo,
                'Seguimiento/Eliminar_EvidenciaDiagnostico',
                datosTabla[0],
                true,
                0,
                null,
                null,
                null,
                null,
                1
                );
        file.descargarImagen(evidenciaFallaEquipo);
        file.crearUpload('#evidenciasFallaComponenteCorrectivo',
                'Seguimiento/guardarDiagnosticoEquipo',
                null,
                null,
                evidenciaFallaComponente,
                'Seguimiento/Eliminar_EvidenciaDiagnostico',
                datosTabla[0],
                true,
                0,
                null,
                null,
                null,
                null,
                1
                );
        file.descargarImagen(evidenciaFallaComponente);
        file.crearUpload('#evidenciasReporteMultimediaCorrectivo',
                'Seguimiento/guardarDiagnosticoEquipo',
                null,
                null,
                evidenciaReporteMultimedia,
                'Seguimiento/Eliminar_EvidenciaDiagnostico',
                datosTabla[0],
                true,
                0,
                null,
                null,
                null,
                null,
                1
                );
        file.descargarImagen(evidenciaReporteMultimedia);
        file.crearUpload('#evidenciaEnvioGarantia',
                'Seguimiento/guardarEnvioGarantia',
                null,
                null,
                evidenciaEnvioEquipo,
                'Seguimiento/Eliminar_EvidenciaEnviosEquipo',
                {servicio: datosTabla[0], tipo: 'envio'}
        );
        file.descargarImagen(evidenciaEnvioEquipo);
        file.crearUpload('#evidenciaEntregaEnvioGarantia',
                'Seguimiento/guardarEntregaGarantia',
                null,
                null,
                evidenciaEntregaEquipo,
                'Seguimiento/Eliminar_EvidenciaEnviosEquipo',
                {servicio: datosTabla[0], tipo: 'entrega'}
        );
        file.descargarImagen(evidenciaEntregaEquipo);
        file.crearUpload('#evidenciasSolucionReparacionSinEquipo',
                'Seguimiento/guardarReparacionSinEquipo',
                null,
                null,
                evidenciaReparacionSinEquipo,
                'Seguimiento/Eliminar_EvidenciaSolucion',
                datosTabla[0]
                );
        file.descargarImagen(evidenciaReparacionSinEquipo);
        file.crearUpload('#evidenciasSolucionReparacionConRefaccion',
                'Seguimiento/guardarReparacionConRefaccion',
                null,
                null,
                evidenciaReparacionConEquipo,
                'Seguimiento/Eliminar_EvidenciaSolucion',
                datosTabla[0]
                );
        file.descargarImagen(evidenciaReparacionConEquipo);
        file.crearUpload('#evidenciasSolucionCambioEquipo',
                'Seguimiento/guardarCambioEquipo',
                null,
                null,
                evidenciaCambioEquipo,
                'Seguimiento/Eliminar_EvidenciaSolucion',
                datosTabla[0]
                );
        file.crearUpload('#archivosAgregarObservacionesReporteFalso',
                'Seguimiento/guardarObservacionesBitacora'
                );
        file.descargarImagen(evidenciaCambioEquipo);
        $("#divNotasServicio").slimScroll({height: '400px'});
        nota.initButtons({servicio: datosTabla[0]}, 'Seguimiento');
    };
    var personalizarDependiendoSucursalCorrectivo = function () {
        var respuesta = arguments[0];
        if (respuesta.informacion.informacionDatosGeneralesCorrectivo.length > 0) {
            select.cambiarOpcion('#selectSucursalesCorrectivo', respuesta.informacion.sucursal);
            $('#inputFallaReportadaDiagnostico').val(respuesta.informacion.informacionDatosGeneralesCorrectivo[0].FallaReportada);
            $('#selectComponenteDiagnosticoCorrectivo').removeAttr('disabled');
            $('#selectRefaccionSolicitud').removeAttr('disabled');
            $('#inputCantidadRefaccionSolicitud').removeAttr('disabled');
            $('#selectEquipoSolicitud').removeAttr('disabled');
            $('#selectEquipoRespaldo').removeAttr('disabled');
            $('#inputSerieRespaldo').removeAttr('disabled');
            $('#selectSolucionReparacionSinEquipo').removeAttr('disabled');
            $('#selectRefaccionSolucionReparacionConRefaccion').removeAttr('disabled');
            $('#inputCantidadRefaccionSolicitudReparacionConRefaccion').removeAttr('disabled');
            $('#selectEquipoSolucionCambioEquipo').removeAttr('disabled');
            $('#inputSerieSolucionCambioEquipo').removeAttr('disabled');
            $('#btnAutorizadoSinRespaldo').removeClass('disabled');
            $('#btnSolicitarEquipoRespaldo').removeClass('disabled');
            $('#mensajebotonesEquipoRespaldo').addClass('hidden');
        } else {
            if (respuesta.datosServicio.IdSucursal !== null) {
                select.cambiarOpcion('#selectSucursalesCorrectivo', respuesta.datosServicio.IdSucursal);
            }
        }

        if (respuesta.informacion.diagnosticoEquipo !== null) {
            if (respuesta.informacion.diagnosticoEquipo.length > 0) {
                switch (respuesta.informacion.diagnosticoEquipo[0].IdTipoDiagnostico) {
                    case '1':
                        $('#inputObservacionesReporteFalsoCorrectivo').val(respuesta.informacion.diagnosticoEquipo[0].Observaciones);
                        break;
                    case '2':
                        $('#inputObservacionesImpericiaCorrectivo').val(respuesta.informacion.diagnosticoEquipo[0].Observaciones);
                        select.cambiarOpcion('#selectImpericiaTipoFallaEquipoCorrectivo', respuesta.informacion.diagnosticoEquipo[0].IdTipoFalla);
                        $('[href=#reporte-falso]').parent('li').removeClass('active');
                        $('#reporte-falso').removeClass('active in');
                        $('[href=#impericia]').parent('li').addClass('active');
                        $('#impericia').addClass('active in');
                        break;
                    case '3':
                        $('#inputObservacionesFallaEquipoCorrectivo').val(respuesta.informacion.diagnosticoEquipo[0].Observaciones);
                        select.cambiarOpcion('#selectTipoFallaEquipoCorrectivo', respuesta.informacion.diagnosticoEquipo[0].IdTipoFalla);
                        $('[href=#reporte-falso]').parent('li').removeClass('active');
                        $('#reporte-falso').removeClass('active in');
                        $('[href=#falla-equipo]').parent('li').addClass('active');
                        $('#falla-equipo').addClass('active in');
                        break;
                    case '4':
                        $('#inputObservacionesFallaComponenteCorrectivo').val(respuesta.informacion.diagnosticoEquipo[0].Observaciones);
                        select.cambiarOpcion('#selectComponenteDiagnosticoCorrectivo', respuesta.informacion.diagnosticoEquipo[0].IdComponente);
                        $('[href=#reporte-falso]').parent('li').removeClass('active');
                        $('#reporte-falso').removeClass('active in');
                        $('[href=#falla-componente]').parent('li').addClass('active');
                        $('#falla-componente').addClass('active in');
                        break;
                    case '5':
                        $('#inputObservacionesReporteMultimediaCorrectivo').val(respuesta.informacion.diagnosticoEquipo[0].Observaciones);
                        $('[href=#reporte-falso]').parent('li').removeClass('active');
                        $('#reporte-falso').removeClass('active in');
                        $('[href=#reporte-multimedia]').parent('li').addClass('active');
                        $('#reporte-multimedia').addClass('active in');
                        break;
                    default:
                }
            }
        }

        if (respuesta.informacion.idTipoProblema !== null) {
            if (respuesta.informacion.idTipoProblema.length > 0) {
                switch (respuesta.informacion.idTipoProblema[0].IdTipoProblema) {
                    case '1':
                        recargandoTablaSolicitudRefaccion(respuesta.informacion.solicitudesRefaccionServicios);
                        break;
                    case '2':
                        recargandoTablaSolicitudEquipo(respuesta.informacion.solicitudesEquiposServicios);
                        $('[href=#solicitud-refaccion]').parent('li').removeClass('active');
                        $('#solicitud-refaccion').removeClass('active in');
                        $('[href=#solicitud-equipo]').parent('li').addClass('active');
                        $('#solicitud-equipo').addClass('active in');
                        break;
                    case '3':
                        if (respuesta.informacion.correctivoGarantiaRespaldo[0].EsRespaldo === '1') {
                            select.cambiarOpcion('#selectEquipoRespaldo', respuesta.informacion.correctivoGarantiaRespaldo[0].IdModelo);
                            $('#inputSerieRespaldo').val(respuesta.informacion.correctivoGarantiaRespaldo[0].Serie);
                            $("#dejarEquipoRespaldo").attr('checked', true);
                            $('#dejarEquipoGarantia').removeClass('hidden');
                            $('#selectEquipoRespaldo').attr('disabled', 'disabled');
                            $('#inputSerieRespaldo').attr('disabled', 'disabled');
                            $('#btnGuardarInformacionGarantia').addClass('disabled');
                        } else {
                            if (respuesta.informacion.correctivoGarantiaRespaldo[0].EsRespaldo === '0' && respuesta.informacion.correctivoGarantiaRespaldo[0].SolicitaEquipo === '0') {
                                $('#informacionAutorisacionSinRespaldo').removeClass('hidden');
                            } else {
                                $('#informacionSolicitudEquipoRespaldo').removeClass('hidden');
                            }
                            $("#noSeCuentaEquipoRespaldo").attr('checked', true);
                            $('#noEquipoGarantia').removeClass('hidden');
                        }
                        $('#entregaEnvioEquipo').removeClass('hidden');
                        $('[href=#solicitud-refaccion]').parent('li').removeClass('active');
                        $('#solicitud-refaccion').removeClass('active in');
                        $('[href=#equipo-garantia]').parent('li').addClass('active');
                        $('#equipo-garantia').addClass('active in');
                        break;
                    default:
                }
            }
        }

        if (respuesta.informacion.tiposFallasEquipos !== null) {
            $('#selectTipoFallaEquipoCorrectivo').removeAttr('disabled');
            $('#selectImpericiaTipoFallaEquipoCorrectivo').removeAttr('disabled');
        }

        var listaPaqueteria = [];
        var datosListaPaqueteria = respuesta.informacion.listaPaqueteria;
        $.each(datosListaPaqueteria, function (key, value) {
            listaPaqueteria.push({id: value.Id, text: value.Nombre});
        });
        select.cargaDatos('#selectListaTipoEnvioGarantia', listaPaqueteria);
        if (respuesta.informacion.envioEntrega !== null) {
            if (respuesta.informacion.envioEntrega.length > 0) {
                switch (respuesta.informacion.envioEntrega[0].Tipo) {
                    case 'Envio':
                        if (respuesta.informacion.envioEquipo !== null) {
                            if (respuesta.informacion.envioEquipo.length > 0) {
                                $('[href=#entrega-equipo]').parent('li').removeClass('active');
                                $('#entrega-equipo').removeClass('active in');
                                $('[href=#eviar-equipo]').parent('li').addClass('active');
                                $('#eviar-equipo').addClass('active in');
                                select.cambiarOpcion('#selectTipoEnvioGarantia', respuesta.informacion.envioEquipo[0].IdTipoEnvio);
                                select.cambiarOpcion('#selectListaTipoEnvioGarantia', respuesta.informacion.envioEquipo[0].IdPaqueteriaConsolidado);
                                select.cambiarOpcion('#selectEquipoRespaldoEntregaEnvioGarantia', respuesta.informacion.envioEquipo[0].Recibe);
                                if (respuesta.informacion.envioEquipo[0].IdUsuarioCapturaEnvio !== null) {
                                    $('.entregaGarantia').removeAttr('disabled');
                                    $('#mensajeEntregaGarantia').addClass('hidden');
                                }
                            }
                        }
                        break;
                    case 'Entrega':
                        if (respuesta.informacion.entregaEquipo !== null) {
                            if (respuesta.informacion.entregaEquipo.length > 0) {
                                if (respuesta.informacion.entregaEquipo[0].Recibe != null) {
                                    $('#firmaEntregaEquipo').removeClass('hidden');
                                    $('#botonEntregaEquipo').addClass('hidden');
                                } else {
                                    $('#firmaEntregaTI').removeClass('hidden');
                                    $('#botonEntregaTI').addClass('hidden');
                                    $('[href=#entrega-equipo]').parent('li').removeClass('active');
                                    $('#entrega-equipo').removeClass('active in');
                                    $('[href=#entrega-ti]').parent('li').addClass('active');
                                    $('#entrega-ti').addClass('active in');
                                }
                            }
                        }
                        break;
                    default:
                }
            }
        }

        if (respuesta.informacion.correctivosSoluciones !== null) {
            if (respuesta.informacion.correctivosSoluciones.length > 0) {
                switch (respuesta.informacion.correctivosSoluciones[0].IdTipoSolucion) {
                    case '1':
                        select.cambiarOpcion('#selectSolucionReparacionSinEquipo', respuesta.informacion.correctivosSinEquipo[0].IdSolucionEquipo);
                        $('#inputObservacionesSolucionReparacionSinEquipo').val(respuesta.informacion.correctivosSoluciones[0].Observaciones);
                        break;
                    case '2':
                        recargandoTablaReparacionRefaccion(respuesta.informacion.correctivosSolucionRefaccion);
                        $('#inputObservacionesSolucionReparacionConRefaccion').val(respuesta.informacion.correctivosSoluciones[0].Observaciones);
                        $('[href=#reparacion-sin-Equipo]').parent('li').removeClass('active');
                        $('#reparacion-sin-Equipo').removeClass('active in');
                        $('[href=#reparacion-con-refaccion]').parent('li').addClass('active');
                        $('#reparacion-con-refaccion').addClass('active in');
                        break;
                    case '3':
                        select.cambiarOpcion('#selectEquipoSolucionCambioEquipo', respuesta.informacion.correctivosSolucionCambio[0].IdModelo);
                        $('#inputSerieSolucionCambioEquipo').val(respuesta.informacion.correctivosSolucionCambio[0].Serie);
                        $('#inputObservacionesSolucionCambioEquipo').val(respuesta.informacion.correctivosSoluciones[0].Observaciones);
                        $('[href=#reparacion-sin-Equipo]').parent('li').removeClass('active');
                        $('#reparacion-sin-Equipo').removeClass('active in');
                        $('[href=#cambio-equipo]').parent('li').addClass('active');
                        $('#cambio-equipo').addClass('active in');
                        break;
                    default:
                }
            }
        }

        if (respuesta.informacion.tiposFallasEquiposImpericia === false) {
            $('.mensajeImpericia').removeClass('hidden');
        }

        if (respuesta.informacion.tiposFallasEquipos === false) {
            $('.mensajeTipoFalla').removeClass('hidden');
        }

        if (respuesta.informacion.catalogoComponentesEquipos === false) {
            $('.mensajeRefaccion').removeClass('hidden');
        }

        if (respuesta.informacion.catalogoSolucionesEquipo === false) {
            $('.mensajeSolucion').removeClass('hidden');
        }

        if (respuesta.informacion.informacionDatosGeneralesCorrectivo.length > 0) {
            if (respuesta.informacion.informacionDatosGeneralesCorrectivo[0].Multimedia === '1') {
                $('input:checkbox[name=inputMultimedia]').attr('checked', true);
            }
        }

        $('.kv-file-zoom').addClass('hidden');
    };
    var eventosParaSeccionSeguimientoCorrectivo = function () {
        var datosTabla = arguments[0];
        var respuesta = arguments[1];
        var servicio = datosTabla[0];
        var datosGeneralesCorrectivo = respuesta.informacion.informacionDatosGeneralesCorrectivo;
        if (datosGeneralesCorrectivo.length > 0) {
            var datosDiagnosticoEquipo = respuesta.informacion.diagnosticoEquipo[0];
        }

        //evento para mostrar los detalles de las descripciones
        $('#detallesServicioCorrectivo').off('click');
        $('#detallesServicioCorrectivo').on('click', function (e) {
            if ($('#masDetalles').hasClass('hidden')) {
                $('#masDetalles').removeClass('hidden');
                $('#detallesServicioCorrectivo').empty().html('<a>- Detalles</a>');
            } else {
                $('#masDetalles').addClass('hidden');
                $('#detallesServicioCorrectivo').empty().html('<a>+ Detalles</a>');
            }
        });
        $('#selectSucursalesCorrectivo').on('change', function (event, data) {
            $('#selectAreaPuntoCorrectivo').empty().append('<option data-punto="" value="">Seleccionar</option>');
            select.cambiarOpcion('#selectAreaPuntoCorrectivo', '');
            $('#divCamposExtraCorrectivo').addClass('hidden');
            $('#inputSerieCorrectivo').val('');
            $('#inputNumeroTerminalCorrectivo').val('');
            if ($('#selectSucursalesCorrectivo').val() !== '') {
                $('#selectAreaPuntoCorrectivo').removeAttr('disabled');
            } else {
                $('#selectAreaPuntoCorrectivo').attr('disabled', 'disabled');
                $('#selectEquipoCorrectivo').attr('disabled', 'disabled');
            }

            if ($('#selectSucursalesCorrectivo').val() != '') {
                var sucursal = $('#selectSucursalesCorrectivo').val();
                var dataSucursal = {sucursal: sucursal};
                evento.enviarEvento('Seguimiento/ConsultaAreaPuntoXSucursal', dataSucursal, '#seccion-servicio-correctivo', function (respuesta) {
                    if (respuesta !== false) {
                        $.each(respuesta, function (key, valor) {
                            $("#selectAreaPuntoCorrectivo").append('<option value="' + valor.IdArea + '-' + valor.Punto + '">' + valor.Area + ' ' + valor.Punto + '</option>');
                        });
                        if (datosGeneralesCorrectivo.length > 0) {
                            $('#selectAreaPuntoCorrectivo > option[value="' + datosGeneralesCorrectivo[0].IdArea + '-' + datosGeneralesCorrectivo[0].Punto + '"]').attr('selected', 'selected', 'selected', 'selected').trigger('change');
                        }
                    } else {
                        evento.mostrarMensaje('.errorDatosCorrectivo', false, 'No hay equipos reguistrados para el Area y Punto.', 5000);
                        $('#selectAreaPuntoCorrectivo').attr('disabled', 'disabled');
                    }
                });
            }
        });
        $('#selectAreaPuntoCorrectivo').on('change', function (event, data) {
            $('#selectEquipoCorrectivo').empty().append('<option data-serie="" data-terminal="" value="">Seleccionar</option>');
            select.cambiarOpcion('#selectEquipoCorrectivo', '');
            $('#divCamposExtraCorrectivo').addClass('hidden');
            $('#inputSerieCorrectivo').val('');
            $('#inputNumeroTerminalCorrectivo').val('');
            if ($('#selectAreaPuntoCorrectivo').val() !== '') {
                $('#selectEquipoCorrectivo').removeAttr('disabled');
            } else {
                $('#selectEquipoCorrectivo').attr('disabled', 'disabled');
            }

            var sucursal = $('#selectSucursalesCorrectivo').val();
            var areaPunto = $('#selectAreaPuntoCorrectivo').val();
            var aux = areaPunto.split("-");
            var punto = aux[1];
            var area = aux[0];
//            var numeroRenglon = areaPunto.search(/-/i);
//            var punto = areaPunto.substr(2, numeroRenglon);
//            var area = areaPunto.substr(0, numeroRenglon);
//            punto = Math.abs(punto);
            if (area !== '') {
                var dataEquipo = {sucursal: sucursal, area: area, punto: punto};
                evento.enviarEvento('Seguimiento/ConsultaEquipoXAreaPuntoUltimoCenso', dataEquipo, '#seccion-servicio-correctivo', function (respuesta) {
                    var nuevoArrayIdModelos = [];
                    $.each(respuesta, function (key, valor) {
                        $("#selectEquipoCorrectivo").append('<option data-serie="' + valor.Serie + '" data-terminal="' + valor.Extra + '" value=' + valor.IdModelo + '>' + valor.Equipo + ' (' + valor.Serie + ')</option>');
                        nuevoArrayIdModelos.push(valor.IdModelo);
                    });
                    if (datosGeneralesCorrectivo.length > 0) {
                        if (nuevoArrayIdModelos.length > 0) {
                            if ($.inArray(datosGeneralesCorrectivo[0].IdModelo, nuevoArrayIdModelos) !== -1) {
                                if (area === datosGeneralesCorrectivo[0].IdArea) {
                                    if (punto == datosGeneralesCorrectivo[0].Punto) {
                                        select.cambiarOpcion('#selectEquipoCorrectivo', datosGeneralesCorrectivo[0].IdModelo);
                                    }
                                }
                            }
                        }
                    }
                });
            }
        });
        $('#selectImpericiaTipoFallaEquipoCorrectivo').on('change', function () {
            var tipoFalla = $('#selectImpericiaTipoFallaEquipoCorrectivo').val();
            var equipo = $('#selectEquipoCorrectivo').val();
            if (equipo === '') {
                if (datosGeneralesCorrectivo.length > 0) {
                    equipo = datosGeneralesCorrectivo[0].IdModelo;
                }
            }
            var dataTipoFalla = {equipo: equipo, tipoFalla: tipoFalla};
            evento.enviarEvento('Seguimiento/ConsultaFallasEquiposXTipoFallaYEquipo', dataTipoFalla, '#seccion-servicio-correctivo', function (respuesta) {
                $('#selectImpericiaFallaDiagnosticoCorrectivo').removeAttr('disabled');
                $('#selectImpericiaFallaDiagnosticoCorrectivo').empty().append('<option value="">Seleccionar</option>');
                $.each(respuesta, function (key, valor) {
                    $("#selectImpericiaFallaDiagnosticoCorrectivo").append('<option value=' + valor.Id + '>' + valor.Nombre + '</option>');
                });
                if (datosDiagnosticoEquipo !== undefined) {
                    if (datosDiagnosticoEquipo.IdTipoDiagnostico === '2') {
                        select.cambiarOpcion('#selectImpericiaFallaDiagnosticoCorrectivo', datosDiagnosticoEquipo.IdFalla);
                    }
                }
            });
        });
        $('#selectTipoFallaEquipoCorrectivo').on('change', function () {
            var tipoFalla = $('#selectTipoFallaEquipoCorrectivo').val();
            var equipo = $('#selectEquipoCorrectivo').val();
            if (equipo === '') {
                if (datosGeneralesCorrectivo.length > 0) {
                    equipo = datosGeneralesCorrectivo[0].IdModelo;
                }
            }
            var dataTipoFalla = {equipo: equipo, tipoFalla: tipoFalla};
            evento.enviarEvento('Seguimiento/ConsultaFallasEquiposXTipoFallaYEquipo', dataTipoFalla, '#seccion-servicio-correctivo', function (respuesta) {
                $('#selectFallaDiagnosticoCorrectivo').removeAttr('disabled');
                $('#selectFallaDiagnosticoCorrectivo').empty().append('<option value="">Seleccionar</option>');
                $.each(respuesta, function (key, valor) {
                    $("#selectFallaDiagnosticoCorrectivo").append('<option value=' + valor.Id + '>' + valor.Nombre + '</option>');
                });
                if (datosDiagnosticoEquipo !== undefined) {
                    if (datosDiagnosticoEquipo.IdTipoDiagnostico === '3') {
                        select.cambiarOpcion('#selectFallaDiagnosticoCorrectivo', datosDiagnosticoEquipo.IdFalla);
                    }
                }
            });
        });
        $('#selectComponenteDiagnosticoCorrectivo').on('change', function () {
            var componente = $('#selectComponenteDiagnosticoCorrectivo').val();
            var dataFallaComponente = {componente: componente};
            select.cambiarOpcion('#selectTipoFallaComponenteCorrectivo', '');
            $('#selectTipoFallaComponenteCorrectivo').empty().append('<option value="">Seleccionar</option>');
            $('#selectFallaComponenteDiagnosticoCorrectivo').attr('disabled', 'disabled');
            evento.enviarEvento('Seguimiento/ConsultaTipoFallaXRefaccion', dataFallaComponente, '#seccion-servicio-correctivo', function (respuesta) {
                $('#selectTipoFallaComponenteCorrectivo').removeAttr('disabled');
                $.each(respuesta, function (key, valor) {
                    $("#selectTipoFallaComponenteCorrectivo").append('<option value=' + valor.IdTipoFalla + '>' + valor.NombreTipo + '</option>');
                });
                if (datosDiagnosticoEquipo !== undefined) {
                    if (datosDiagnosticoEquipo.IdTipoDiagnostico === '4') {
                        select.cambiarOpcion('#selectTipoFallaComponenteCorrectivo', datosDiagnosticoEquipo.IdTipoFalla);
                    }
                }
            });
        });
        $('#selectTipoFallaComponenteCorrectivo').on('change', function () {
            var tipoFalla = $('#selectTipoFallaComponenteCorrectivo').val();
            var dataTipoFalla = {tipoFalla: tipoFalla};
            select.cambiarOpcion('#selectFallaComponenteDiagnosticoCorrectivo', '');
            $('#selectFallaComponenteDiagnosticoCorrectivo').empty().append('<option value="">Seleccionar</option>');
            evento.enviarEvento('Seguimiento/ConsultaFallasRefacionXTipoFalla', dataTipoFalla, '#seccion-servicio-correctivo', function (respuesta) {
                if (respuesta) {
                    $('#selectFallaComponenteDiagnosticoCorrectivo').removeAttr('disabled');
                    $.each(respuesta, function (key, valor) {
                        $("#selectFallaComponenteDiagnosticoCorrectivo").append('<option value=' + valor.Id + '>' + valor.Nombre + '</option>');
                    });
                    if (datosDiagnosticoEquipo !== undefined) {
                        select.cambiarOpcion('#selectFallaComponenteDiagnosticoCorrectivo', datosDiagnosticoEquipo.IdFalla);
                    }
                }
            });
        });
        $('#camposExtrasCorrectivo').off('click');
        $('#camposExtrasCorrectivo').on('click', function (e) {
            if ($('#divCamposExtraCorrectivo').hasClass('hidden')) {
                $('#divCamposExtraCorrectivo').removeClass('hidden');
                $('#camposExtrasCorrectivo').empty().html('<a><strong> Mostrar sólo equipos censados</strong></a>');
                $('#selectEquipoCorrectivo').removeAttr('disabled');
                $('#selectEquipoCorrectivo').empty().append('<option value="">Seleccionar</option>');
                select.cambiarOpcion('#selectEquipoCorrectivo', '');
                evento.enviarEvento('Seguimiento/ConsultaModelosEquipos', [], '#seccion-servicio-correctivo', function (respuesta) {
                    $.each(respuesta, function (key, valor) {
                        $("#selectEquipoCorrectivo").append('<option data-serie="" data-terminal="" value=' + valor.IdMod + '>' + valor.Linea + ' - ' + valor.Marca + ' - ' + valor.Modelo + '</option>');
                    });
                });
            } else {
                $('#divCamposExtraCorrectivo').addClass('hidden');
                $('#camposExtrasCorrectivo').empty().html('<a><strong> Click aquí su el equipo no está en el registro</strong></a>');
                select.cambiarOpcion('#selectSucursalesCorrectivo', '');
                $('#selectAreaPuntoCorrectivo').empty().append('<option value="">Seleccionar</option>');
                select.cambiarOpcion('#selectAreaPuntoCorrectivo', '');
                $('#selectAreaPuntoCorrectivo').attr('disabled', 'disabled');
                $('#selectEquipoCorrectivo').empty().append('<option data-serie="" data-terminal="" value="">Seleccionar</option>');
                select.cambiarOpcion('#selectEquipoCorrectivo', '');
                $('#selectEquipoCorrectivo').attr('disabled', 'disabled');
            }
        });
        $('#btnGuardarDatosCorrectivo').off('click');
        $('#btnGuardarDatosCorrectivo').on('click', function (e) {
            validarFormularioDatosGeneralesCorrectivo(datosTabla);
        });
        $('#btnGuardarReporteFalsoCorrectivo').off('click');
        $('#btnGuardarReporteFalsoCorrectivo').on('click', function (e) {
            var data = {servicio: servicio};
            if (validarCampos($('#inputFallaReportadaDiagnostico').val(), '.errorFormularioReporteFalsoCorrectivo', 'Falla reportada en sitio.')) {
                evento.enviarEvento('Seguimiento/varifiarBitacora', data, '#seccion-servicio-correctivo', function (resultado) {
                    if (resultado.code === 200) {
                        if (respuesta.informacion.diagnosticoEquipo === null) {
                            if ($('#evidenciasReporteFalsoCorrectivo').val() !== '') {
                                guardarFormularioDiagnosticoEquipoCorrectivo(servicio, '1', $('#inputObservacionesReporteFalsoCorrectivo').val(), '#evidenciasReporteFalsoCorrectivo', datosTabla, '3', null, respuesta.informacion.diagnosticoEquipo, respuesta);
                            } else {
                                evento.mostrarMensaje('.errorFormularioReporteFalsoCorrectivo', false, 'Debes llenar el campo de Evidencias.', 3000);
                            }
                        } else if (respuesta.informacion.diagnosticoEquipo.length <= 0) {
                            if ($('#evidenciasReporteFalsoCorrectivo').val() !== '') {
                                guardarFormularioDiagnosticoEquipoCorrectivo(servicio, '1', $('#inputObservacionesReporteFalsoCorrectivo').val(), '#evidenciasReporteFalsoCorrectivo', datosTabla, '3', null, respuesta.informacion.diagnosticoEquipo, respuesta);
                            } else {
                                evento.mostrarMensaje('.errorFormularioReporteFalsoCorrectivo', false, 'Debes llenar el campo de Evidencias.', 3000);
                            }
                        } else if (respuesta.informacion.diagnosticoEquipo.length > 0) {
                            guardarFormularioDiagnosticoEquipoCorrectivo(servicio, '1', $('#inputObservacionesReporteFalsoCorrectivo').val(), '#evidenciasReporteFalsoCorrectivo', datosTabla, '3', null, respuesta.informacion.diagnosticoEquipo, respuesta);
                        } else {
                            evento.mostrarMensaje('.errorFormularioReporteFalsoCorrectivo', false, 'Debes llenar el campo de Evidencias.', 3000);
                        }
                    } else {
                        evento.mostrarMensaje('.errorFormularioReporteFalsoCorrectivo', false, resultado.message, 3000);
                    }
                });
            }
        });
        $('#btnGuardarImpericiaCorrectivo').off('click');
        $('#btnGuardarImpericiaCorrectivo').on('click', function (e) {
            if (validarCampos($('#inputFallaReportadaDiagnostico').val(), '.errorFormularioImpericiaCorrectivo', 'Falla reportada en sitio.')) {
                if (validarCampos($('#inputObservacionesImpericiaCorrectivo').val(), '.errorFormularioImpericiaCorrectivo', 'Debes llenar el campo de Observaciones.')) {
                    if (validarCampos($('#selectImpericiaTipoFallaEquipoCorrectivo').val(), '.errorFormularioImpericiaCorrectivo', 'Debes seleccionar el campo Tipo de Falla.')) {
                        if (validarCampos($('#selectImpericiaFallaDiagnosticoCorrectivo').val(), '.errorFormularioImpericiaCorrectivo', 'Debes seleccionar el campo Falla.')) {
                            if (respuesta.informacion.diagnosticoEquipo === null) {
                                if ($('#evidenciasImpericiaCorrectivo').val() !== '') {
                                    guardarFormularioDiagnosticoEquipoCorrectivo(servicio, '2', $('#inputObservacionesImpericiaCorrectivo').val(), '#evidenciasImpericiaCorrectivo', datosTabla, '1', 'Seguimiento/enviarReporteImpericia', respuesta.informacion.diagnosticoEquipo);
                                } else {
                                    evento.mostrarMensaje('.errorFormularioImpericiaCorrectivo', false, 'Debes llenar el campo de Evidencias.', 3000);
                                }
                            } else if (respuesta.informacion.diagnosticoEquipo.length <= 0) {
                                if ($('#evidenciasImpericiaCorrectivo').val() !== '') {
                                    guardarFormularioDiagnosticoEquipoCorrectivo(servicio, '2', $('#inputObservacionesImpericiaCorrectivo').val(), '#evidenciasImpericiaCorrectivo', datosTabla, '1', 'Seguimiento/enviarReporteImpericia', respuesta.informacion.diagnosticoEquipo);
                                } else {
                                    evento.mostrarMensaje('.errorFormularioImpericiaCorrectivo', false, 'Debes llenar el campo de Evidencias.', 3000);
                                }
                            } else if (respuesta.informacion.diagnosticoEquipo.length > 0) {
                                guardarFormularioDiagnosticoEquipoCorrectivo(servicio, '2', $('#inputObservacionesImpericiaCorrectivo').val(), '#evidenciasImpericiaCorrectivo', datosTabla, '1', 'Seguimiento/enviarReporteImpericia', respuesta.informacion.diagnosticoEquipo);
                            } else {
                                evento.mostrarMensaje('.errorFormularioImpericiaCorrectivo', false, 'Debes llenar el campo de Evidencias.', 3000);
                            }
                        }
                    }
                }
            }
        });
        $('#btnGuardarFallaEquipoCorrectivo').off('click');
        $('#btnGuardarFallaEquipoCorrectivo').on('click', function (e) {
            if (validarCampos($('#inputFallaReportadaDiagnostico').val(), '.errorFormularioFallaEquipoCorrectivo', 'Falla reportada en sitio.')) {
                if (validarCampos($('#inputObservacionesFallaEquipoCorrectivo').val(), '.errorFormularioFallaEquipoCorrectivo', 'Debes llenar el campo de Observaciones.')) {
                    if (validarCampos($('#selectTipoFallaEquipoCorrectivo').val(), '.errorFormularioFallaEquipoCorrectivo', 'Debes seleccionar el campo Tipo de Falla.')) {
                        if (validarCampos($('#selectFallaDiagnosticoCorrectivo').val(), '.errorFormularioFallaEquipoCorrectivo', 'Debes seleccionar el campo Falla.')) {
                            if (respuesta.informacion.diagnosticoEquipo === null) {
                                if ($('#evidenciasFallaEquipoCorrectivo').val() !== '') {
                                    guardarFormularioDiagnosticoEquipoCorrectivo(servicio, '3', $('#inputObservacionesFallaEquipoCorrectivo').val(), '#evidenciasFallaEquipoCorrectivo', datosTabla, '', '', respuesta.informacion.diagnosticoEquipo);
                                } else {
                                    evento.mostrarMensaje('.errorFormularioFallaEquipoCorrectivo', false, 'Debes llenar el campo de Evidencias.', 3000);
                                }
                            } else if (respuesta.informacion.diagnosticoEquipo.length <= 0) {
                                if ($('#evidenciasFallaEquipoCorrectivo').val() !== '') {
                                    guardarFormularioDiagnosticoEquipoCorrectivo(servicio, '3', $('#inputObservacionesFallaEquipoCorrectivo').val(), '#evidenciasFallaEquipoCorrectivo', datosTabla, '', '', respuesta.informacion.diagnosticoEquipo);
                                } else {
                                    evento.mostrarMensaje('.errorFormularioFallaEquipoCorrectivo', false, 'Debes llenar el campo de Evidencias.', 3000);
                                }
                            } else if (respuesta.informacion.diagnosticoEquipo.length > 0) {
                                guardarFormularioDiagnosticoEquipoCorrectivo(servicio, '3', $('#inputObservacionesFallaEquipoCorrectivo').val(), '#evidenciasFallaEquipoCorrectivo', datosTabla, '', '', respuesta.informacion.diagnosticoEquipo);
                            } else {
                                evento.mostrarMensaje('.errorFormularioFallaEquipoCorrectivo', false, 'Debes llenar el campo de Evidencias.', 3000);
                            }
                        }
                    }
                }
            }
        });
        $('#btnGuardarFallaComponenteCorrectivo').off('click');
        $('#btnGuardarFallaComponenteCorrectivo').on('click', function (e) {
            if (validarCampos($('#inputFallaReportadaDiagnostico').val(), '.errorFormularioFallaComponenteCorrectivo', 'Falla reportada en sitio.')) {
                if (validarCampos($('#inputObservacionesFallaComponenteCorrectivo').val(), '.errorFormularioFallaComponenteCorrectivo', 'Debes llenar el campo de Observaciones.')) {
                    if (validarCampos($('#selectComponenteDiagnosticoCorrectivo').val(), '.errorFormularioFallaComponenteCorrectivo', 'Debes seleccionar el campo Componente.')) {
                        if (validarCampos($('#selectTipoFallaComponenteCorrectivo').val(), '.errorFormularioFallaComponenteCorrectivo', 'Debes seleccionar el campo Tipo de Falla.')) {
                            if (validarCampos($('#selectFallaComponenteDiagnosticoCorrectivo').val(), '.errorFormularioFallaComponenteCorrectivo', 'Debes seleccionar el campo Falla.')) {
                                if (respuesta.informacion.diagnosticoEquipo === null) {
                                    if ($('#evidenciasFallaComponenteCorrectivo').val() !== '') {
                                        guardarFormularioDiagnosticoEquipoCorrectivo(servicio, '4', $('#inputObservacionesFallaComponenteCorrectivo').val(), '#evidenciasFallaComponenteCorrectivo', datosTabla, '', '', respuesta.informacion.diagnosticoEquipo);
                                    } else {
                                        evento.mostrarMensaje('.errorFormularioFallaComponenteCorrectivo', false, 'Debes llenar el campo de Evidencias.', 3000);
                                    }
                                } else if (respuesta.informacion.diagnosticoEquipo.length <= 0) {
                                    if ($('#evidenciasFallaComponenteCorrectivo').val() !== '') {
                                        guardarFormularioDiagnosticoEquipoCorrectivo(servicio, '4', $('#inputObservacionesFallaComponenteCorrectivo').val(), '#evidenciasFallaComponenteCorrectivo', datosTabla, '', '', respuesta.informacion.diagnosticoEquipo);
                                    } else {
                                        evento.mostrarMensaje('.errorFormularioFallaComponenteCorrectivo', false, 'Debes llenar el campo de Evidencias.', 3000);
                                    }
                                } else if (respuesta.informacion.diagnosticoEquipo.length > 0) {
                                    guardarFormularioDiagnosticoEquipoCorrectivo(servicio, '4', $('#inputObservacionesFallaComponenteCorrectivo').val(), '#evidenciasFallaComponenteCorrectivo', datosTabla, '', '', respuesta.informacion.diagnosticoEquipo);
                                } else {
                                    evento.mostrarMensaje('.errorFormularioFallaComponenteCorrectivo', false, 'Debes llenar el campo de Evidencias.', 3000);
                                }
                            }
                        }
                    }
                }
            }
        });
        $('#btnGuardarReporteMultimediaCorrectivo').off('click');
        $('#btnGuardarReporteMultimediaCorrectivo').on('click', function (e) {
            if (validarCampos($('#inputFallaReportadaDiagnostico').val(), '.errorFormularioReporteMultimediaCorrectivo', 'Falla reportada en sitio.')) {
                if (validarCampos($('#inputObservacionesReporteMultimediaCorrectivo').val(), '.errorFormularioReporteMultimediaCorrectivo', 'Debes llenar el campo de Observaciones.')) {
                    if (respuesta.informacion.diagnosticoEquipo === null) {
                        if ($('#evidenciasReporteMultimediaCorrectivo').val() !== '') {
                            guardarFormularioDiagnosticoEquipoCorrectivo(servicio, '5', $('#inputObservacionesReporteMultimediaCorrectivo').val(), '#evidenciasReporteMultimediaCorrectivo', datosTabla, '3', null, respuesta.informacion.diagnosticoEquipo, respuesta);
                        } else {
                            evento.mostrarMensaje('.errorFormularioReporteMultimediaCorrectivo', false, 'Debes llenar el campo de Evidencias.', 3000);
                        }
                    } else if (respuesta.informacion.diagnosticoEquipo.length <= 0) {
                        if ($('#evidenciasReporteMultimediaCorrectivo').val() !== '') {
                            guardarFormularioDiagnosticoEquipoCorrectivo(servicio, '5', $('#inputObservacionesReporteMultimediaCorrectivo').val(), '#evidenciasReporteMultimediaCorrectivo', datosTabla, '3', null, respuesta.informacion.diagnosticoEquipo, respuesta);
                        } else {
                            evento.mostrarMensaje('.errorFormularioReporteMultimediaCorrectivo', false, 'Debes llenar el campo de Evidencias.', 3000);
                        }
                    } else if (respuesta.informacion.diagnosticoEquipo.length > 0) {
                        guardarFormularioDiagnosticoEquipoCorrectivo(servicio, '5', $('#inputObservacionesReporteMultimediaCorrectivo').val(), '#evidenciasReporteMultimediaCorrectivo', datosTabla, '3', null, respuesta.informacion.diagnosticoEquipo, respuesta);
                    } else {
                        evento.mostrarMensaje('.errorFormularioReporteMultimediaCorrectivo', false, 'Debes llenar el campo de Evidencias.', 3000);
                    }
                }
            }
        });
        $('#btnAgregarSolicitudRefaccion').off('click');
        $('#btnAgregarSolicitudRefaccion').on('click', function (e) {
            if (validarCampos($('#selectRefaccionSolicitud').val(), '.errorRefaccionSolicitud', 'Debes seleccionar el campo Refacción.')) {
                if (validarCampos($('#inputCantidadRefaccionSolicitud').val(), '.errorRefaccionSolicitud', 'Debes llenar el campo de Cantidad.')) {
                    if ($('#inputCantidadRefaccionSolicitud').val() > 0) {
                        if (validarSolitud('#data-table-solicitud-refacciones', $('#selectRefaccionSolicitud').val(), '.errorRefaccionSolicitud')) {
                            agregandoSolcitudRefaccion();
                        }
                    } else {
                        evento.mostrarMensaje('.errorRefaccionSolicitud', false, 'Debe ser un número positivo.', 3000);
                    }
                }
            }
        });
        $('#btnGuardarSolicitudRefaccion').off('click');
        $('#btnGuardarSolicitudRefaccion').on('click', function (e) {
            var data = {servicio: servicio};
            var respuestaAnterior = respuesta;
            evento.enviarEvento('Seguimiento/verificarDiagnostico', data, '#seccion-servicio-correctivo', function (respuesta) {
                if (respuesta) {
                    var datosTablaRefaccionesSolicitudes = $('#data-table-solicitud-refacciones').DataTable().rows().data();
                    var solicitud = $('input[name=radioSolicitar]:checked').val();
                    if (datosTablaRefaccionesSolicitudes.length > 0) {
                        if (solicitud !== undefined) {
                            var sucursal = $('#selectSucursalesCorrectivo').val();
                            guardarDatosTablaRefaccionesSolicitudes(datosTablaRefaccionesSolicitudes, servicio, datosTabla, sucursal, respuestaAnterior, solicitud);
                        } else {
                            evento.mostrarMensaje('.errorRefaccionSolicitud', false, 'Para guardar las Refacciones debe seleccionar una opción.', 5000);
                        }
                    } else {
                        evento.mostrarMensaje('.errorRefaccionSolicitud', false, 'Para guardar las Refacciones debe haber agregado un registro en la tabla.', 3000);
                    }
                } else {
                    evento.mostrarMensaje('.errorRefaccionSolicitud', false, 'Para guardar las Refacciones debe guardar los datos del diagnostico.', 5000);
                }
            });
        });
        $('#data-table-servicios-solicitudes-refacciones tbody').on('click', 'tr', function () {
            var datosTablaRefaccionSolicitud = $('#data-table-servicios-solicitudes-refacciones').DataTable().rows(this).data();
            if (datosTablaRefaccionSolicitud[0] !== undefined) {
                evento.mostrarModal('Detalles de la Solicitud', vistaDetallesSolicitud(datosTablaRefaccionSolicitud));
                $('#btnModalConfirmar').addClass('hidden');
                $('#btnModalAbortar').empty().append('Cerrar');
                tabla.generaTablaPersonal('#data-table-detalles-solicitud', null, null, true, true);
                var nuevoArray = datosTablaRefaccionSolicitud[0][3].split('<br>');
                $.each(nuevoArray, function (key, item) {
                    var nuevoArray2 = item.split('-');
                    tabla.agregarFila('#data-table-detalles-solicitud', [datosTablaRefaccionSolicitud[0][5][key], nuevoArray2[0], nuevoArray2[1]]);
                });
                $('#data-table-detalles-solicitud tbody').on('click', 'tr', function () {
                    var datosTablaDetallesSolicitud = $('#data-table-detalles-solicitud').DataTable().rows(this).data();
                    eliminarFilaTablaDetallesSolicitud(datosTablaDetallesSolicitud[0][0], 'refaccion', servicio, datosTabla[1]);
                });
            }
        });
        $('#data-table-solicitud-refacciones tbody').on('click', 'tr', function () {
            tabla.eliminarFila('#data-table-solicitud-refacciones', this);
        });
        $('#data-table-reparacion-refaccion tbody').on('click', 'tr', function () {
            tabla.eliminarFila('#data-table-reparacion-refaccion', this);
        });
        $('#btnAgregarSolicitudEquipo').off('click');
        $('#btnAgregarSolicitudEquipo').on('click', function (e) {
            if (validarCampos($('#selectEquipoSolicitud').val(), '.errorEquipoSolicitud', 'Debes seleccionar el campo Equipo.')) {
                if (validarSolitud('#data-table-solicitud-equipos', $('#selectEquipoSolicitud').val(), '.errorEquipoSolicitud')) {
                    agregandoSolicitudEquipo();
                }
            }
        });
        $('#btnGuardarSolicitudEquipo').off('click');
        $('#btnGuardarSolicitudEquipo').on('click', function (e) {
            var data = {servicio: servicio};
            var respuestaAnterior = respuesta;
            evento.enviarEvento('Seguimiento/verificarDiagnostico', data, '#seccion-servicio-correctivo', function (respuesta) {
                if (respuesta) {
                    var datosTablaEquiposSolicitudes = $('#data-table-solicitud-equipos').DataTable().rows().data();
                    var solicitud = $('input[name=radioSolicitar]:checked').val();
                    if (datosTablaEquiposSolicitudes.length > 0) {
                        if (solicitud !== undefined) {
                            var sucursal = $('#selectSucursalesCorrectivo').val();
                            guardarDatosTablaEquiposSolicitudes(datosTablaEquiposSolicitudes, servicio, datosTabla, sucursal, respuestaAnterior, solicitud);
                        } else {
                            evento.mostrarMensaje('.errorEquipoSolicitud', false, 'Para guardar los Equipos debe seleccionar opción.', 5000);
                        }
                    } else {
                        evento.mostrarMensaje('.errorEquipoSolicitud', false, 'Para guardar los Equipos debe haber agregado un registro en la tabla.', 3000);
                    }
                } else {
                    evento.mostrarMensaje('.errorEquipoSolicitud', false, 'Para guardar los Equipos debe guardar los datos del diagnostico.', 5000);
                }
            });
        });
        $('#data-table-servicios-solicitudes-equipos tbody').on('click', 'tr', function () {
            var datosTablaEquiposSolicitud = $('#data-table-servicios-solicitudes-equipos').DataTable().rows(this).data();
            if (datosTablaEquiposSolicitud[0] !== undefined) {
                evento.mostrarModal('Detalles de la Solicitud', vistaDetallesSolicitud(datosTablaEquiposSolicitud));
                $('#btnModalConfirmar').addClass('hidden');
                $('#btnModalAbortar').empty().append('Cerrar');
                tabla.generaTablaPersonal('#data-table-detalles-solicitud', null, null, true, true);
                var nuevoArray = datosTablaEquiposSolicitud[0][3].split('<br>');
                $.each(nuevoArray, function (key, item) {
                    var nuevoArray2 = item.split('_');
                    tabla.agregarFila('#data-table-detalles-solicitud', [datosTablaEquiposSolicitud[0][5][key], nuevoArray2[0], nuevoArray2[1]]);
                });
                $('#data-table-detalles-solicitud tbody').on('click', 'tr', function () {
                    var datosTablaDetallesSolicitud = $('#data-table-detalles-solicitud').DataTable().rows(this).data();
                    eliminarFilaTablaDetallesSolicitud(datosTablaDetallesSolicitud[0][0], 'equipo', servicio, datosTabla[1]);
                });
            }
        });
        $('#data-table-solicitud-equipos tbody').on('click', 'tr', function () {
            tabla.eliminarFila('#data-table-solicitud-equipos', this);
        });
        $('input[name=radioEquipoRespaldo]').change(function () {
            var textoRadio = $(this).val();
            if (textoRadio === 'dejar') {
                $('#dejarEquipoGarantia').removeClass('hidden');
                $('#noEquipoGarantia').addClass('hidden');
                $('#informacionAutorisacionSinRespaldo').addClass('hidden');
                if (respuesta.informacion.correctivoGarantiaRespaldo !== null) {
                    if (respuesta.informacion.correctivoGarantiaRespaldo.length > 0) {
                        if (respuesta.informacion.correctivoGarantiaRespaldo[0].EsRespaldo !== '1') {
                            $('#informacionSolicitudEquipoRespaldo').addClass('hidden');
                        }
                    }
                }
            } else {
                $('#dejarEquipoGarantia').addClass('hidden');
                $('#noEquipoGarantia').removeClass('hidden');
                if (respuesta.informacion.correctivoGarantiaRespaldo !== null) {
                    if (respuesta.informacion.correctivoGarantiaRespaldo.length > 0) {
                        if (respuesta.informacion.correctivoGarantiaRespaldo[0].EsRespaldo === '0' && respuesta.informacion.correctivoGarantiaRespaldo[0].SolicitaEquipo === '0') {
                            $('#informacionAutorisacionSinRespaldo').removeClass('hidden');
                        } else if (respuesta.informacion.correctivoGarantiaRespaldo[0].SoliitaEquipo === '1') {
                            $('#informacionSolicitudEquipoRespaldo').removeClass('hidden');
                        }
                    }
                }
            }
        });
        $('#btnGuardarInformacionGarantia').off('click');
        $('#btnGuardarInformacionGarantia').on('click', function (e) {
            if (validarCampos($('#selectEquipoRespaldo').val(), '.errorEquipoRespaldo', 'Debes seleccionar el campo Equipo.')) {
                if (validarCampos($('#inputSerieRespaldo').val(), '.errorEquipoRespaldo', 'Debes ingresar datos en el campo Serie.')) {
                    var equipoRetirado = $("#selectEquipoCorrectivo option:selected").text();
                    var serieRetirado = $("#selectEquipoCorrectivo option:selected").attr("data-serie");
                    var equipoRespaldo = $("#selectEquipoRespaldo option:selected").text();
                    var serieRespaldo = $("#inputSerieRespaldo").val();
                    var html = '<div class="row">\n\
                        <div class="col-md-12 text-center">\n\
                            <div class="form-group">\n\
                                <h5> Equipo Retirado: <strong>' + equipoRetirado + '</strong>   Serie Equipo Retirado: <strong>' + serieRetirado + '</strong></h5>\n\
                            </div>\n\
                        </div>\n\
                        <div class="row">\n\
                            <div class="col-md-12 text-center">\n\
                                <div class="form-group">\n\
                                    <h5> Equipo Respaldo: <strong>' + equipoRespaldo + '</strong>   Serie Equipo Respaldo: <strong>' + serieRespaldo + '</strong></h5>\n\
                                </div>\n\
                            </div>\n\
                        </div>\n\
                    </div>';
                    evento.mostrarModal('Retiro a Garantía con Respaldo', modalCampoFirmaSolicitud(html, 'Nombre del Gerente'));
                    $('#btnModalConfirmar').addClass('hidden');
                    $('#btnModalConfirmar').off('click');
                    var data = {servicio: servicio, serie: serieRespaldo, serieRetirado: serieRetirado, ticket: datosTabla[1], operacion: '1'};
                    validarCamposFirma(data);
                }
            }
        });
        $('#btnSolicitarEquipoRespaldo').off('click');
        $('#btnSolicitarEquipoRespaldo').on('click', function (e) {
            evento.mostrarModal('Confirmar Solicitud de Equipo de Respaldo', formularioAsignacionSolicitud());
            select.crearSelect('#selectAtiendeSolcitud');
            evento.enviarEvento('Seguimiento/ConsultaAtiendeAlmacen', {}, '#confirmarSolicitud', function (respuesta) {
                $('#selectAtiendeSolcitud').removeAttr('disabled', 'disabled');
                $('#selectAtiendeSolcitud').empty().append('<option value="">Seleccionar</option>');
                $.each(respuesta, function (key, valor) {
                    $("#selectAtiendeSolcitud").append('<option value=' + valor.IdUsuario + '>' + valor.Nombre + '</option>');
                });
                select.cambiarOpcion('#selectAtiendeSolcitud', '12');
            });
            $('#btnModalConfirmar').off('click');
            $('#btnModalConfirmar').on('click', function () {
                var sucursal = $('#selectSucursalesCorrectivo').val();
                var data = {servicio: servicio, sucursal: sucursal, datosTabla: datosTabla};
                guardarSolicitudEquipoRespaldo(data);
            });
        });
        $('#btnAutorizadoSinRespaldo').off('click');
        $('#btnAutorizadoSinRespaldo').on('click', function (e) {
            evento.mostrarModal('Autorizado sin Respaldo', formularioPersonalAutoriza());
            file.crearUpload('#evidenciasAutorizacion',
                    'Seguimiento/guardarInformacionEquipoRespaldo'
                    );
            $('#btnModalConfirmar').addClass('hidden');
            $('#btnModalAbortar').addClass('hidden');
            $('#btnGuardarAutorizacion').off('click');
            $('#btnGuardarAutorizacion').on('click', function (e) {
                if (validarCampos($('#inputAutoriza').val(), '.errorAtorizacion', 'Debes llenar el campo de la Persona que autoriza.')) {
                    if (validarCampos($('#evidenciasAutorizacion').val(), '.errorAtorizacion', 'Debes llenar el campo de la evidencia de la autorizacion.')) {
                        var equipo = $("#selectEquipoRespaldo").val();
                        var serieRespaldo = $("#inputSerieRespaldo").val();
                        var autoriza = $("#inputAutoriza").val();
                        var data = {servicio: servicio, equipo: equipo, serie: serieRespaldo, autoriza: autoriza, operacion: '2'};
                        guardarInformacionRespaldoEvidencia(data);
                    }
                }
            });
            $('#btnCerrarAutorizacion').off('click');
            $('#btnCerrarAutorizacion').on('click', function (e) {
                evento.cerrarModal();
            });
        });
        $('#btnGuardarEntregarEquipo').off('click');
        $('#btnGuardarEntregarEquipo').on('click', function (e) {
            if (validarCampos($('#selectEntregaGarantia').val(), '.errorEntregaEquipo', 'Debes seleccionar el campo de quien se entrega.')) {
                var equipoRetirado = $("#selectEquipoCorrectivo option:selected").text();
                var serieRetirado = $("#selectEquipoCorrectivo option:selected").attr("data-serie");
                var html = '<div class="table-responsive">\n\
                                <table id="data-table-acuse-entrega" class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%">\n\
                                    <thead>\n\
                                        <tr>\n\
                                            <th class="all">Equipo</th>\n\
                                            <th class="all">Serie</th>\n\
                                        </tr>\n\
                                    </thead>\n\
                                    <tbody>\n\
                                        <tr>\n\
                                            <td>' + equipoRetirado + '</td>\n\
                                            <td>' + serieRetirado + '</td>\n\
                                         </tr>\n\
                                    </tbody>\n\
                                </table>\n\
                            </div>\n\
                            <div class="row">\n\
                                <div class="col-md-12">\n\
                                    <div class="form-group">\n\
                                        <label for="selectEntregaGarantia">Se entrega a *</label>\n\
                                        <select id="selectEntregaGarantia" class="form-control" style="width: 100%">\n\
                                            <option value="">Seleccionar</option>\n\
                                        </select>\n\
                                    </div>\n\
                                </div>\n\
                            </div>';
                evento.mostrarModal('Acuse de Entrega', modalCampoFirmaSolicitud(html, 'Nombre del Gerente'));
                $('#btnModalConfirmar').addClass('hidden');
                $('#btnModalConfirmar').off('click');
                $('#formFirmaPoliza').addClass('hidden');
                select.crearSelect('#selectEntregaGarantia');
                $('#campoCorreo').empty().html('Enviar copia a *');
                var atiendeLaboratorio = [];
                var datosAtiendeLaboratorio = respuesta.informacion.atiendeLaboratorio;
                $.each(datosAtiendeLaboratorio, function (key, value) {
                    atiendeLaboratorio.push({id: value.IdUsuario, text: value.Nombre});
                });
                select.cargaDatos('#selectEntregaGarantia', atiendeLaboratorio);
                select.cambiarOpcion('#selectEntregaGarantia', '12');
                var data = {servicio: servicio, ticket: datosTabla[1], serie: serieRetirado, operacion: '2'};
                validarCamposFirma(data);
            }
        });
        $('#btnGuardarEntregarTI').off('click');
        $('#btnGuardarEntregarTI').on('click', function (e) {
            if (validarCampos($('#selectEntregaGarantia').val(), '.errorEntregaEquipo', 'Debes seleccionar el campo de quien se entrega.')) {
                var equipoRetirado = $("#selectEquipoCorrectivo option:selected").text();
                var serieRetirado = $("#selectEquipoCorrectivo option:selected").attr("data-serie");
                var html = '<div class="table-responsive">\n\
                                <table id="data-table-acuse-entrega" class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%">\n\
                                    <thead>\n\
                                        <tr>\n\
                                            <th class="all">Equipo</th>\n\
                                            <th class="all">Serie</th>\n\
                                        </tr>\n\
                                    </thead>\n\
                                    <tbody>\n\
                                        <tr>\n\
                                            <td>' + equipoRetirado + '</td>\n\
                                            <td>' + serieRetirado + '</td>\n\
                                         </tr>\n\
                                    </tbody>\n\
                                </table>\n\
                            </div>';
                evento.mostrarModal('Acuse de Entrega a TI', modalCampoFirmaSolicitud(html, 'Nombre personal TI'));
                $('#btnModalConfirmar').addClass('hidden');
                $('#btnModalConfirmar').off('click');
                $('#campoCorreo').empty().html('Enviar copia a *');
                var data = {servicio: servicio, ticket: datosTabla[1], serie: serieRetirado, operacion: '3'};
                validarCamposFirma(data);
            }
        });
        $('#selectTipoEnvioGarantia').on('change', function (event, data) {
            var lista = [];
            var datos = null;
            if ($(this).val() !== '') {
                $('#selectListaTipoEnvioGarantia').removeAttr('disabled');
                if ($(this).val() === '2') {
                    datos = respuesta.informacion.listaPaqueteria;
                } else {
                    datos = respuesta.informacion.listaConsolidados;
                }
                $.each(datos, function (key, value) {
                    lista.push({id: value.Id, text: value.Nombre});
                });
                select.cargaDatos('#selectListaTipoEnvioGarantia', lista);
            } else {
                $('#selectListaTipoEnvioGarantia').attr('disabled', 'disabled');
            }
        });
        $('#btnGuardarEnvioGarantia').off('click');
        $('#btnGuardarEnvioGarantia').on('click', function () {
            if (validarCampos($('#selectTipoEnvioGarantia').val(), '#errorGuardarEnvioGarantia', 'Debes seleccionar el campo como en envia.')) {
                if (validarCampos($('#selectListaTipoEnvioGarantia').val(), '#errorGuardarEnvioGarantia', 'Debes selecionar el campo paqueteria o consolidado.')) {
                    if (validarCampos($('#inputGuiaGarantia').val(), '#errorGuardarEnvioGarantia', 'Debes llenar el campo de guía.')) {
                        if (validarCampos($('#evidenciaEnvioGarantia').val(), '#errorGuardarEnvioGarantia', 'Debes seleccionar la evidencias de envio.')) {
                            guardarEnvioGarantia(servicio);
                        }
                    }
                }
            }
        });
        $('#btnGuardarEnvioEntregaGarantia').off('click');
        $('#btnGuardarEnvioEntregaGarantia').on('click', function () {
            if (validarCampos($('#entregaFechaEnvioGarantia').val(), '#errorGuardarEnvioEntregaGarantia', 'Debes llenar el campo Fecha y Hora.')) {
                if (validarCampos($('#selectEquipoRespaldoEntregaEnvioGarantia').val(), '#errorGuardarEnvioEntregaGarantia', 'Debes seleccionar el campo de quien recibe.')) {
                    if (validarCampos($('#evidenciaEntregaEnvioGarantia').val(), '#errorGuardarEnvioEntregaGarantia', 'Debes seleccionar la evidencias de envio.')) {
                        guardarEntregaGarantia(servicio);
                    }
                }
            }
        });
        $('#btnAgregarReparacionConRefaccion').off('click');
        $('#btnAgregarReparacionConRefaccion').on('click', function (e) {
            if (validarCampos($('#selectRefaccionSolucionReparacionConRefaccion').val(), '.errorFormularioSolucionReparacionConRefaccion', 'Debes seleccionar el campo Refacción.')) {
                if (validarCampos($('#inputCantidadRefaccionSolicitudReparacionConRefaccion').val(), '.errorFormularioSolucionReparacionConRefaccion', 'Debes llenar el campo de Cantidad.')) {
                    if ($('#inputCantidadRefaccionSolicitudReparacionConRefaccion').val() > 0) {
                        if (validarSolitud('#data-table-reparacion-refaccion', $('#selectRefaccionSolucionReparacionConRefaccion').val(), '.errorFormularioSolucionReparacionConRefaccion')) {
                            agregandoSolicitudRefaccion();
                        }
                    } else {
                        evento.mostrarMensaje('.errorFormularioSolucionReparacionConRefaccion', false, 'Debe ser un número positivo.', 3000);
                    }
                }
            }
        });
        $('#btnEnviarReporteProblema').off('click');
        $('#btnEnviarReporteProblema').on('click', function (e) {
            var data = {servicio: servicio};
            evento.enviarEvento('Seguimiento/verificarDiagnostico', data, '#seccion-servicio-correctivo', function (respuesta) {
                if (respuesta) {
                    evento.enviarEvento('/Generales/Servicio/VerificarFolioServicio', data, '#seccion-servicio-correctivo', function (respuesta) {
                        if (respuesta === true) {
                            var sucursal = $('#selectSucursalesCorrectivo').val();
                            var dataConcluir = {servicio: servicio, operacion: '1', sucursal: sucursal};
                            servicios.modalCampoFirma(datosTabla[1], dataConcluir, 'Seguimiento/enviarReporteImpericia');
                        } else {
                            servicios.mensajeModal('No existe Folio para este servicio', 'Advertencia', true);
                        }
                    });
                } else {
                    servicios.mensajeModal('No se ha guardado el Diagnostico', 'Advertencia', true);
                }
            });
        });
        $('#btnGuardarReparacionSinEquipo').off('click');
        $('#btnGuardarReparacionSinEquipo').on('click', function (e) {
            if (validarCampos($('#selectSolucionReparacionSinEquipo').val(), '.errorFormularioSolucionReparacionSinEquipo', 'Debes seleccionar el campo Solución.')) {
                if ($('#evidenciasSolucionReparacionSinEquipo').val() !== '' || respuesta.informacion.evidenciasCorrectivosSoluciones !== null) {
                    guardarConcluirCorrectivoReparacionSinEquipo(servicio, datosTabla, respuesta.informacion.correctivosSoluciones, '1');
                } else if ($('#evidenciasSolucionReparacionSinEquipo').val() !== '' && respuesta.informacion.evidenciasCorrectivosSoluciones !== null) {
                    guardarConcluirCorrectivoReparacionSinEquipo(servicio, datosTabla, respuesta.informacion.correctivosSoluciones, '1');
                } else {
                    evento.mostrarMensaje('.errorFormularioSolucionReparacionSinEquipo', false, 'Debes llenar el campo de Evidencias.', 3000);
                }
            }
        });
        $('#btnGuardarConcluirReparacionSinEquipo').off('click');
        $('#btnGuardarConcluirReparacionSinEquipo').on('click', function (e) {
            if (validarCampos($('#inputObservacionesSolucionReparacionSinEquipo').val(), '.errorFormularioSolucionReparacionSinEquipo', 'Debes llenar el campo de Observaciones.')) {
                if (validarCampos($('#selectSolucionReparacionSinEquipo').val(), '.errorFormularioSolucionReparacionSinEquipo', 'Debes seleccionar el campo Solución.')) {
                    if ($('#evidenciasSolucionReparacionSinEquipo').val() !== '') {
                        guardarConcluirCorrectivoReparacionSinEquipo(servicio, datosTabla, respuesta.informacion.correctivosSoluciones, '2', respuesta);
                    } else {
                        var data = {servicio: servicio, idTipoSolucion: '1'};
                        var respuestaAnterior = respuesta;
                        evento.enviarEvento('Seguimiento/ConsultaCorrectivosSolucionesServicio', data, '#seccion-servicio-correctivo', function (respuesta) {
                            if (respuesta) {
                                guardarConcluirCorrectivoReparacionSinEquipo(servicio, datosTabla, respuestaAnterior.informacion.correctivosSoluciones, '2', respuestaAnterior);
                            } else {
                                evento.mostrarMensaje('.errorFormularioSolucionReparacionSinEquipo', false, 'Debes llenar el campo de Evidencias.', 3000);
                            }
                        });
                    }
                }
            }
        });
        $('#btnGuardarSolucionReparacionConRefaccion').off('click');
        $('#btnGuardarSolucionReparacionConRefaccion').on('click', function (e) {
            if ($("#data-table-reparacion-refaccion-stock").length) {
                var _refacciones = '';
                $(".checkRefaccionesStock").each(function () {
                    if ($(this).hasClass("fa-check-square-o")) {
                        _refacciones += ',' + $(this).attr("data-id");
                    }
                });
                _refacciones = (_refacciones !== '') ? _refacciones.substring(1) : _refacciones;
                _evidencias = $('#evidenciasSolucionReparacionConRefaccion').val(); //              
                if (_refacciones !== '' && (_evidencias !== '' || respuesta.informacion.evidenciasCorrectivosSoluciones.length > 0)) {
                    guardarConcluirCorrectivoReparacionConRefaccion(servicio, datosTabla, _refacciones, respuesta.informacion.correctivosSoluciones, '1');
                } else {
                    var _mensaje = 'Para guardar la información debes seleccionar al menos una refacción y tener una evidencia';
                    evento.mostrarMensaje('.errorFormularioSolucionReparacionConRefaccion', false, _mensaje, 3000);
                }


            } else {
                var datosTablaReparacionRefaccion = $('#data-table-reparacion-refaccion').DataTable().rows().data();
                if (datosTablaReparacionRefaccion.length > 0) {
                    if ($('#evidenciasSolucionReparacionConRefaccion').val() !== '' || respuesta.informacion.evidenciasCorrectivosSoluciones != null) {
                        guardarConcluirCorrectivoReparacionConRefaccion(servicio, datosTabla, datosTablaReparacionRefaccion, respuesta.informacion.correctivosSoluciones, '1');
                    } else if ($('#evidenciasSolucionReparacionConRefaccion').val() !== '' && respuesta.informacion.evidenciasCorrectivosSoluciones.length !== null) {
                        guardarConcluirCorrectivoReparacionConRefaccion(servicio, datosTabla, datosTablaReparacionRefaccion, respuesta.informacion.correctivosSoluciones, '1');
                    } else {
                        evento.mostrarMensaje('.errorFormularioSolucionReparacionConRefaccion', false, 'Debes llenar el campo de Evidencias.', 3000);
                    }
                } else {
                    evento.mostrarMensaje('.errorFormularioSolucionReparacionConRefaccion', false, 'Para guardar los Equipos debe haber agregado un registro en la tabla.', 3000);
                }
            }
        });
        $('#btnGuardarConcluirSolucionReparacionConRefaccion').off('click');
        $('#btnGuardarConcluirSolucionReparacionConRefaccion').on('click', function (e) {
            if (validarCampos($('#inputObservacionesSolucionReparacionConRefaccion').val(), '.errorFormularioSolucionReparacionConRefaccion', 'Debes llenar el campo de Observaciones.')) {
                if ($("#data-table-reparacion-refaccion-stock").length) {
                    var _refacciones = '';
                    $(".checkRefaccionesStock").each(function () {
                        if ($(this).hasClass("fa-check-square-o")) {
                            _refacciones += ',' + $(this).attr("data-id");
                        }
                    });
                    _refacciones = (_refacciones !== '') ? _refacciones.substring(1) : _refacciones;
                    _evidencias = $('#evidenciasSolucionReparacionConRefaccion').val(); //              
                    if (_refacciones !== '' && (_evidencias !== '' || (respuesta.informacion.evidenciasCorrectivosSoluciones !== null && respuesta.informacion.evidenciasCorrectivosSoluciones.length > 0))) {
                        guardarConcluirCorrectivoReparacionConRefaccion(servicio, datosTabla, _refacciones, respuesta.informacion.correctivosSoluciones, '2', respuesta);
                    } else {
                        var _mensaje = 'Para concluir el servicio debes seleccionar al menos una refacción y tener una evidencia';
                        evento.mostrarMensaje('.errorFormularioSolucionReparacionConRefaccion', false, _mensaje, 3000);
                    }


                } else {
                    var datosTablaReparacionRefaccion = $('#data-table-reparacion-refaccion').DataTable().rows().data();
                    if (datosTablaReparacionRefaccion.length > 0) {
                        if ($('#evidenciasSolucionReparacionConRefaccion').val() !== '') {
                            guardarConcluirCorrectivoReparacionConRefaccion(servicio, datosTabla, datosTablaReparacionRefaccion, respuesta.informacion.correctivosSoluciones, '2', respuesta);
                        } else {
                            var data = {servicio: servicio, idTipoSolucion: '2'};
                            var respuestaAnterior = respuesta;
                            evento.enviarEvento('Seguimiento/ConsultaCorrectivosSolucionesServicio', data, '#seccion-servicio-correctivo', function (respuesta) {
                                if (respuesta) {
                                    guardarConcluirCorrectivoReparacionConRefaccion(servicio, datosTabla, datosTablaReparacionRefaccion, respuestaAnterior.informacion.correctivosSoluciones, '2', respuestaAnterior);
                                } else {
                                    evento.mostrarMensaje('.errorFormularioSolucionReparacionConRefaccion', false, 'Debes llenar el campo de Evidencias.', 3000);
                                }
                            });
                        }
                    } else {
                        evento.mostrarMensaje('.errorFormularioSolucionReparacionConRefaccion', false, 'Para guardar los Equipos debe haber agregado un registro en la tabla.', 3000);
                    }
                }
            }
        });
        $('#btnGuardarSolucionCambioEquipo').off('click');
        $('#btnGuardarSolucionCambioEquipo').on('click', function (e) {
            if ($("#data-table-reparacion-cambio-stock").length) {
                var _equipos = '';
                var _dataEquipo = [];
                $(".checkEquipoStock").each(function () {
                    if ($(this).hasClass("fa-check-square-o")) {
                        _equipos += ',' + $(this).attr("data-id");
                        _dataEquipo['equipo'] = $(this).attr("data-id-producto");
                        _dataEquipo['serie'] = $(this).attr("data-serie");
                    }
                });
                _equipos = (_equipos !== '') ? _equipos.substring(1) : _equipos;
                _evidencias = $('#evidenciasSolucionCambioEquipo').val(); //              
                if (_equipos !== '' && (_evidencias !== '' || (respuesta.informacion.evidenciasCorrectivosSoluciones !== null && respuesta.informacion.evidenciasCorrectivosSoluciones.length > 0))) {
                    guardarConcluirCorrectivoCambioEquipo(servicio, datosTabla, respuesta.informacion.correctivosSoluciones, '1', null, _equipos, _dataEquipo);
                } else {
                    var _mensaje = 'Para guardar la información debes seleccionar al menos un equipo y tener una evidencia';
                    evento.mostrarMensaje('.errorFormularioSolucionCambioEquipo', false, _mensaje, 3000);
                }


            } else {

                if (validarCampos($('#selectEquipoSolucionCambioEquipo').val(), '.errorFormularioSolucionCambioEquipo', 'Debes seleccionar el campo Equipo.')) {
                    if (validarCampos($('#inputSerieSolucionCambioEquipo').val(), '.errorFormularioSolucionCambioEquipo', 'Debes llenar el campo de Número de Serie.')) {
                        if ($('#evidenciasSolucionCambioEquipo').val() !== '' || respuesta.informacion.evidenciasCorrectivosSoluciones !== null) {
                            guardarConcluirCorrectivoCambioEquipo(servicio, datosTabla, respuesta.informacion.correctivosSoluciones, '1');
                        } else if ($('#evidenciasSolucionCambioEquipo').val() !== '' && respuesta.informacion.evidenciasCorrectivosSoluciones !== null) {
                            guardarConcluirCorrectivoCambioEquipo(servicio, datosTabla, respuesta.informacion.correctivosSoluciones, '1');
                        } else {
                            evento.mostrarMensaje('.errorFormularioSolucionCambioEquipo', false, 'Debes llenar el campo de Evidencias.', 3000);
                        }
                    }
                }
            }
        });
        $('#btnGuardarConcluirSolucionCambioEquipo').off('click');
        $('#btnGuardarConcluirSolucionCambioEquipo').on('click', function (e) {
            if (validarCampos($('#inputObservacionesSolucionCambioEquipo').val(), '.errorFormularioSolucionCambioEquipo', 'Debes llenar el campo de Observaciones.')) {
                if ($("#data-table-reparacion-cambio-stock").length) {
                    var _equipos = '';
                    var _dataEquipo = [];
                    $(".checkEquipoStock").each(function () {
                        if ($(this).hasClass("fa-check-square-o")) {
                            _equipos += ',' + $(this).attr("data-id");
                            _dataEquipo['equipo'] = $(this).attr("data-id-producto");
                            _dataEquipo['serie'] = $(this).attr("data-serie");
                        }
                    });
                    _equipos = (_equipos !== '') ? _equipos.substring(1) : _equipos;
                    _evidencias = $('#evidenciasSolucionCambioEquipo').val(); //              
                    if (_equipos !== '' && (_evidencias !== '' || (respuesta.informacion.evidenciasCorrectivosSoluciones !== null && respuesta.informacion.evidenciasCorrectivosSoluciones.length > 0))) {
                        guardarConcluirCorrectivoCambioEquipo(servicio, datosTabla, respuesta.informacion.correctivosSoluciones, '2', respuesta, _equipos, _dataEquipo);
                    } else {
                        var _mensaje = 'Para guardar la información debes seleccionar al menos un equipo y tener una evidencia';
                        evento.mostrarMensaje('.errorFormularioSolucionCambioEquipo', false, _mensaje, 3000);
                    }
                } else {
                    if (validarCampos($('#selectEquipoSolucionCambioEquipo').val(), '.errorFormularioSolucionCambioEquipo', 'Debes seleccionar el campo Equipo.')) {
                        if (validarCampos($('#inputSerieSolucionCambioEquipo').val(), '.errorFormularioSolucionCambioEquipo', 'Debes llenar el campo de Número de Serie.')) {
                            if ($('#evidenciasSolucionCambioEquipo').val() !== '') {
                                guardarConcluirCorrectivoCambioEquipo(servicio, datosTabla, respuesta.informacion.correctivosSoluciones, '2', respuesta);
                            } else {
                                var data = {servicio: servicio, idTipoSolucion: '3'};
                                var respuestaAnterior = respuesta;
                                evento.enviarEvento('Seguimiento/ConsultaCorrectivosSolucionesServicio', data, '#seccion-servicio-correctivo', function (respuesta) {
                                    if (respuesta) {
                                        guardarConcluirCorrectivoCambioEquipo(servicio, datosTabla, respuestaAnterior.informacion.correctivosSoluciones, '2', respuestaAnterior);
                                    } else {
                                        evento.mostrarMensaje('.errorFormularioSolucionCambioEquipo', false, 'Debes llenar el campo de Evidencias.', 3000);
                                    }
                                });
                            }
                        }
                    }
                }
            }
        });
        //Evento que vuelve a mostrar la lista de servicios de Poliza
        $('#btnRegresarSeguimientoCorrectivo').off('click');
        $('#btnRegresarSeguimientoCorrectivo').on('click', function () {
            $('#seccionSeguimientoServicio').empty().addClass('hidden');
            $('#listaPoliza').removeClass('hidden');
        });
        //Encargado de crear un nuevo servicio
        $('#btnNuevoServicioSeguimiento').off('click');
        $('#btnNuevoServicioSeguimiento').on('click', function () {
            var data = {servicio: servicio};
            servicios.nuevoServicio(
                    data,
                    respuesta.datosServicio.Ticket,
                    respuesta.datosServicio.IdSolicitud,
                    'Seguimiento/Servicio_Nuevo_Modal',
                    '#seccion-servicio-correctivo',
                    'Seguimiento/Servicio_Nuevo'
                    );
        });
        //Encargado de cancelar servicio
        $('#btnCancelarServicioSeguimiento').off('click');
        $('#btnCancelarServicioSeguimiento').on('click', function () {
            var data = {servicio: servicio, ticket: respuesta.datosServicio.Ticket};
            servicios.cancelarServicio(
                    data,
                    'Seguimiento/Servicio_Cancelar_Modal',
                    '#seccion-servicio-correctivo',
                    'Seguimiento/Servicio_Cancelar'
                    );
        });
        //Encargado de generar el archivo Pdf
        $('#btnGeneraPdfServicio').off('click');
        $('#btnGeneraPdfServicio').on('click', function () {
            var data = {servicio: servicio};
            evento.enviarEvento('Seguimiento/Servicio_ToPdf', data, '#seccion-servicio-correctivo', function (respuesta) {
                window.open('/' + respuesta.link);
            });
        });

        $("#btnAgregarObservacionesReporteFalso").off("click");
        $("#btnAgregarObservacionesReporteFalso").on("click", function () {
            $(this).addClass('hidden');
            $("#divFormAgregarObservaciones").removeClass('hidden');
        });

        $("#btnCancelarAgregarObservacionesReporteFalso").off("click");
        $("#btnCancelarAgregarObservacionesReporteFalso").on("click", function () {
            $("#divFormAgregarObservaciones").addClass('hidden');
            $("#btnAgregarObservacionesReporteFalso").removeClass('hidden');
            file.limpiar('#archivosAgregarObservacionesReporteFalso');
            evento.limpiarFormulario("#formAgregarObservacionesReporteFalso");
        });

        $('#btnConfirmarAgregarObservacionesReporteFalso').off('click');
        $('#btnConfirmarAgregarObservacionesReporteFalso').on('click', function () {
            var observaciones = $('#txtAgregarObservacion').val();
            var data = {servicio: servicio, observaciones: observaciones};
            if (observaciones !== '') {
                file.enviarArchivos('#archivosAgregarObservacionesReporteFalso', 'Seguimiento/guardarObservacionesBitacora', '#seccion-servicio-correctivo', data, function (respuesta) {
                    if (respuesta.code === 200) {
                        $('#divBitacoraReporteFalso').empty().append(respuesta.message);
                        file.limpiar('#archivosAgregarObservacionesReporteFalso');
                        $('#txtAgregarObservacion').val('');
                        servicios.mensajeModal('Se agrego la observación.', 'Correcto', true);
                        limpiarFormulariosDiagnostico('1');
                        $("#divFormAgregarObservaciones").addClass('hidden');
                        $("#btnAgregarObservacionesReporteFalso").removeClass('hidden');
                    }
                });
            } else {
                evento.mostrarMensaje('#errorAgregarObservacionesReporteFalso', false, 'Debe capturar la observación.', 3000);
            }
        });

        servicios.initBotonReasignarServicio(servicio, datosTabla[1], '#seccion-servicio-correctivo');
        //evento para crear nueva solicitud
        servicios.initBotonNuevaSolicitud(datosTabla[1], '#seccion-servicio-correctivo');
        servicios.eventosFolio(datosTabla[2], '#seccion-servicio-correctivo', servicio);
        servicios.subirInformacionSD(servicio, '#seccion-servicio-correctivo');
        servicios.botonAgregarVuelta({servicio: servicio}, '#seccion-servicio-correctivo');
        servicios.botonAgregarAvance(servicio, 'Correctivo');
        servicios.botonAgregarProblema(servicio, 'Correctivo');
        servicios.botonEliminarAvanceProblema(servicio);
        servicios.botonEditarAvanceProblema(servicio);
    };
    var validarFormularioDatosGeneralesCorrectivo = function () {
        var datosTabla = arguments[0];
        var sucursal = $('#selectSucursalesCorrectivo').val();
        var areaPunto = $('#selectAreaPuntoCorrectivo').val();
        var equipo = $('#selectEquipoCorrectivo').val();
        var numeroRenglon = areaPunto.search(/-/i);
        var punto = areaPunto.substr(2, numeroRenglon);
        var area = areaPunto.substr(0, numeroRenglon);
        punto = Math.abs(punto);
        var serie = $('#inputSerieCorrectivo').val();
        var numTerminal = $('#inputNumeroTerminalCorrectivo').val();
        var multimedia = $('input:checkbox[name=inputMultimedia]:checked').val();
        if (sucursal !== '') {
            if (areaPunto !== '') {
                if (equipo !== '') {
                    if ($('#divCamposExtraCorrectivo').hasClass('hidden')) {
                        serie = $("#selectEquipoCorrectivo option:selected").attr("data-serie");
                        numTerminal = $("#selectEquipoCorrectivo option:selected").attr("data-terminal");
                        var data = {servicio: datosTabla[0], sucursal: sucursal, area: area, punto: punto, equipo: equipo, serie: serie, numTerminal: numTerminal, multimedia: multimedia};
                        guardarFormularioDatosGeneralesCorrectivo(data)
                    } else {
                        if (serie !== '') {
                            if (numTerminal !== '') {
                                var serie = $('#inputSerieCorrectivo').val();
                                var numTerminal = $('#inputNumeroTerminalCorrectivo').val();
                                var data = {servicio: datosTabla[0], sucursal: sucursal, area: area, punto: punto, equipo: equipo, serie: serie, numTerminal: numTerminal, multimedia: multimedia};
                                guardarFormularioDatosGeneralesCorrectivo(data)
                            } else {
                                evento.mostrarMensaje('.errorDatosCorrectivo', false, 'Debes llenar el campo Número de Terminal.', 3000);
                            }
                        } else {
                            evento.mostrarMensaje('.errorDatosCorrectivo', false, 'Debes selecionar el campo Serie.', 3000);
                        }
                    }
                } else {
                    evento.mostrarMensaje('.errorDatosCorrectivo', false, 'Debes selecionar el campo Equipo.', 3000);
                }
            } else {
                evento.mostrarMensaje('.errorDatosCorrectivo', false, 'Debes seleccionar el campo Área y Punto.', 3000);
            }
        } else {
            evento.mostrarMensaje('.errorDatosCorrectivo', false, 'Debes seleccionar el campo Sucursal.', 3000);
        }
    };
    var guardarFormularioDatosGeneralesCorrectivo = function () {
        var data = arguments[0];
        evento.enviarEvento('Seguimiento/GuardarDatosGeneralesCorrectivo', data, '#seccion-servicio-correctivo', function (respuesta) {
            var equipo = $('#selectEquipoCorrectivo').val();
            var dataEquipo = {equipo: equipo};
            if (respuesta === true) {
                $('#mensajebotonesEquipoRespaldo').addClass('hidden');
                $('#btnAutorizadoSinRespaldo').removeClass('disabled');
                $('#btnSolicitarEquipoRespaldo').removeClass('disabled');
                evento.enviarEvento('Seguimiento/ConsultaTiposFallasEquiposImpericia', dataEquipo, '#seccion-servicio-correctivo', function (respuesta) {
                    if (respuesta instanceof Array || respuesta instanceof Object) {
                        $('#selectImpericiaTipoFallaEquipoCorrectivo').removeAttr('disabled');
                        $('#selectImpericiaTipoFallaEquipoCorrectivo').empty().append('<option value="">Seleccionar</option>');
                        $.each(respuesta, function (key, valor) {
                            $("#selectImpericiaTipoFallaEquipoCorrectivo").append('<option value=' + valor.Id + '>' + valor.Nombre + '</option>');
                        });
                        $('.mensajeImpericia').addClass('hidden');
                    } else {
                        $('.mensajeImpericia').removeClass('hidden');
                    }
                });
                evento.enviarEvento('Seguimiento/ConsultaTiposFallasEquipos', dataEquipo, '#seccion-servicio-correctivo', function (respuesta) {
                    if (respuesta instanceof Array || respuesta instanceof Object) {
                        $('#selectTipoFallaEquipoCorrectivo').removeAttr('disabled');
                        $('#selectTipoFallaEquipoCorrectivo').empty().append('<option value="">Seleccionar</option>');
                        $.each(respuesta, function (key, valor) {
                            $("#selectTipoFallaEquipoCorrectivo").append('<option value=' + valor.Id + '>' + valor.Nombre + '</option>');
                        });
                        $('.mensajeTipoFalla').addClass('hidden');
                    } else {
                        $('.mensajeTipoFalla').removeClass('hidden');
                    }
                });
                evento.enviarEvento('Seguimiento/ConsultaRefacionXEquipo', dataEquipo, '#seccion-servicio-correctivo', function (respuesta) {
                    if (respuesta instanceof Array || respuesta instanceof Object) {
                        $('#selectComponenteDiagnosticoCorrectivo').removeAttr('disabled');
                        $('#selectRefaccionSolicitud').removeAttr('disabled');
                        $('#selectRefaccionSolucionReparacionConRefaccion').removeAttr('disabled');
                        $('#inputCantidadRefaccionSolicitud').removeAttr('disabled');
                        $('#inputCantidadRefaccionSolicitudReparacionConRefaccion').removeAttr('disabled');
                        $('#selectComponenteDiagnosticoCorrectivo').empty().append('<option value="">Seleccionar</option>');
                        $('#selectRefaccionSolicitud').empty().append('<option value="">Seleccionar</option>');
                        $('#selectRefaccionSolucionReparacionConRefaccion').empty().append('<option value="">Seleccionar</option>');
                        $.each(respuesta, function (key, valor) {
                            $("#selectComponenteDiagnosticoCorrectivo").append('<option value=' + valor.Id + '>' + valor.Nombre + '</option>');
                            $("#selectRefaccionSolicitud").append('<option value=' + valor.Id + '>' + valor.Nombre + '</option>');
                            $("#selectRefaccionSolucionReparacionConRefaccion").append('<option value=' + valor.Id + '>' + valor.Nombre + '</option>');
                        });
                        $('.mensajeRefaccion').addClass('hidden');
                    } else {
                        $('.mensajeRefaccion').removeClass('hidden');
                    }
                });
                evento.enviarEvento('Seguimiento/ConsultaEquiposXLinea', dataEquipo, '#seccion-servicio-correctivo', function (respuesta) {
                    if (respuesta instanceof Array || respuesta instanceof Object) {
                        $('#selectEquipoSolicitud').removeAttr('disabled');
                        $('#selectEquipoRespaldo').removeAttr('disabled');
                        $('#selectEquipoSolucionCambioEquipo').removeAttr('disabled');
                        $('#inputSerieSolucionCambioEquipo').removeAttr('disabled');
                        $('#inputSerieRespaldo').removeAttr('disabled');
                        $('#selectEquipoSolicitud').empty().append('<option value="">Seleccionar</option>');
                        $('#selectEquipoRespaldo').empty().append('<option value="">Seleccionar</option>');
                        $('#selectEquipoSolucionCambioEquipo').empty().append('<option value="">Seleccionar</option>');
                        $.each(respuesta, function (key, valor) {
                            $("#selectEquipoSolicitud").append('<option value=' + valor.IdMod + '>' + valor.Linea + ' - ' + valor.Marca + ' - ' + valor.Modelo + '</option>');
                            $("#selectEquipoRespaldo").append('<option value=' + valor.IdMod + '>' + valor.Linea + ' - ' + valor.Marca + ' - ' + valor.Modelo + '</option>');
                            $("#selectEquipoSolucionCambioEquipo").append('<option value=' + valor.IdMod + '>' + valor.Linea + ' - ' + valor.Marca + ' - ' + valor.Modelo + '</option>');
                        });
                    }
                });
                evento.enviarEvento('Seguimiento/ConsultaCatalogoSolucionesEquipoXEquipo', dataEquipo, '#seccion-servicio-correctivo', function (respuesta) {
                    if (respuesta instanceof Array || respuesta instanceof Object) {
                        $('#selectSolucionReparacionSinEquipo').removeAttr('disabled');
                        $('#selectSolucionReparacionSinEquipo').empty().append('<option value="">Seleccionar</option>');
                        $.each(respuesta, function (key, valor) {
                            $("#selectSolucionReparacionSinEquipo").append('<option value=' + valor.Id + '>' + valor.Nombre + '</option>');
                        });
                        $('.mensajeSolucion').addClass('hidden');
                    } else {
                        $('.mensajeSolucion').removeClass('hidden');
                    }
                });
                evento.mostrarMensaje('.errorDatosCorrectivo', true, 'Datos guardados Correctamente.', 3000);
            }
        });
    };
    var guardarFormularioDiagnosticoEquipoCorrectivo = function () {
        var servicio = arguments[0];
        var tipoDiagnostico = arguments[1];
        var observaciones = arguments[2];
        var nombreEvidencias = arguments[3];
        var datosTablaPoliza = arguments[4];
        var diagnosticoEquipo = arguments[7] || null;
        var respuestaAnterior = arguments[8] || null;
        var data = {};
        var divErrorMensaje = '';
        var evidencias = '';
        var tipoDiagnosticoAnterior = null;
        var estatus;
        var fallaReportada = $('#inputFallaReportadaDiagnostico').val();
        if (diagnosticoEquipo !== null) {
            if (diagnosticoEquipo.length > 0) {
                evidencias = diagnosticoEquipo[0].Evidencias;
                tipoDiagnosticoAnterior = diagnosticoEquipo[0].IdTipoDiagnostico;
            }
        }
        switch (tipoDiagnostico) {
            case '1':
                data = {servicio: servicio, tipoDiagnostico: tipoDiagnostico, observaciones: observaciones, evidencias: evidencias, tipoDiagnosticoAnterior: tipoDiagnosticoAnterior};
                estatus = "concluir"
                divErrorMensaje = '.errorFormularioReporteFalsoCorrectivo';
                break;
            case'2':
                var tipoFalla = $('#selectImpericiaTipoFallaEquipoCorrectivo').val();
                var falla = $('#selectImpericiaFallaDiagnosticoCorrectivo').val();
                data = {servicio: servicio, tipoDiagnostico: tipoDiagnostico, observaciones: observaciones, evidencias: evidencias, tipoFalla: tipoFalla, falla: falla, tipoDiagnosticoAnterior: tipoDiagnosticoAnterior};
                divErrorMensaje = '.errorFormularioImpericiaCorrectivo';
                break;
            case '3':
                var tipoFalla = $('#selectTipoFallaEquipoCorrectivo').val();
                var falla = $('#selectFallaDiagnosticoCorrectivo').val();
                data = {servicio: servicio, tipoDiagnostico: tipoDiagnostico, observaciones: observaciones, tipoFalla: tipoFalla, falla: falla, evidencias: evidencias, tipoDiagnosticoAnterior: tipoDiagnosticoAnterior};
                divErrorMensaje = '.errorFormularioFallaEquipoCorrectivo';
                break;
            case '4':
                var componente = $('#selectComponenteDiagnosticoCorrectivo').val();
                var tipoFalla = $('#selectTipoFallaComponenteCorrectivo').val();
                var falla = $('#selectFallaComponenteDiagnosticoCorrectivo').val();
                data = {servicio: servicio, tipoDiagnostico: tipoDiagnostico, observaciones: observaciones, componente: componente, tipoFalla: tipoFalla, falla: falla, evidencias: evidencias, tipoDiagnosticoAnterior: tipoDiagnosticoAnterior};
                divErrorMensaje = '.errorFormularioFallaComponenteCorrectivo';
                break;
            case '5':
                divErrorMensaje = '.errorFormularioReporteMultimediaCorrectivo';
                data = {servicio: servicio, tipoDiagnostico: tipoDiagnostico, observaciones: observaciones, evidencias: evidencias, tipoDiagnosticoAnterior: tipoDiagnosticoAnterior};
                break;
            default:
        }

        data.fallaReportada = fallaReportada;

        if ($(nombreEvidencias).val() !== '') {
            file.enviarArchivos(nombreEvidencias, 'Seguimiento/guardarDiagnosticoEquipo', '#seccion-servicio-correctivo', data, function (respuesta) {
                if (respuesta !== 'faltaDatosGenerales') {
                    if (tipoDiagnostico === '5' || tipoDiagnostico === '1') {
                        var dataSD = {servicio: servicio, ticket: datosTablaPoliza[1], servicioConcluir: true};
                        evento.enviarEvento('Seguimiento/enviarSolucionCorrectivoSD', dataSD, '#seccion-servicio-correctivo', function (respuesta) {
                            if (respuesta.code === 200) {
                                if (respuesta.message === 'serviciosConcluidos') {
                                    modalCampoFirma(respuesta, datosTablaPoliza[1], servicio, divErrorMensaje, respuestaAnterior, true, '4');
                                } else {
                                    concluirServicio(servicio);
                                }
                            } else {
                                servicios.mensajeModal(respuesta.message, 'ERROR SD', true);
                            }
                        });
                    } else if (tipoDiagnostico === '2' || tipoDiagnostico === '3' || tipoDiagnostico === '4') {
                        evento.mostrarMensaje(divErrorMensaje, true, 'Datos Guardados Correctamente.', 5000);
                    }
                    limpiarFormulariosDiagnostico(tipoDiagnostico);
                } else {
                    file.limpiar(nombreEvidencias);
                    evento.mostrarMensaje(divErrorMensaje, false, 'Falta llenar los datos de Información General.', 5000);
                }
            });
        } else if (diagnosticoEquipo !== null) {
            evento.enviarEvento('Seguimiento/guardarDiagnosticoEquipo', data, '#seccion-servicio-correctivo', function (respuesta) {
                if (respuesta !== 'faltaDatosGenerales') {
                    if (tipoDiagnostico === '5' || tipoDiagnostico === '1') {
                        var dataSD = {servicio: servicio, ticket: datosTablaPoliza[1], servicioConcluir: true};
                        evento.enviarEvento('Seguimiento/enviarSolucionCorrectivoSD', dataSD, '#seccion-servicio-correctivo', function (respuesta) {
                            if (respuesta.code === 200) {
                                if (respuesta.message === 'serviciosConcluidos') {
                                    modalCampoFirma(respuesta, datosTablaPoliza[1], servicio, divErrorMensaje, respuestaAnterior, true, '4');
                                } else {
                                    concluirServicio(servicio);
                                }
                            } else {
                                servicios.mensajeModal(respuesta.message, 'ERROR SD', true);
                            }
                        });
                    } else if (tipoDiagnostico === '2' || tipoDiagnostico === '3' || tipoDiagnostico === '4') {
                        evento.mostrarMensaje(divErrorMensaje, true, 'Datos Guardados Correctamente.', 5000);
                    }
                    limpiarFormulariosDiagnostico(tipoDiagnostico);
                } else {
                    file.limpiar(nombreEvidencias);
                    evento.mostrarMensaje(divErrorMensaje, false, 'Falta llenar los datos de Información General.', 5000);
                }
            });
        } else {
            evento.mostrarMensaje(divErrorMensaje, false, 'Debes llenar el campo de Evidencias.', 5000);
        }
    };
    var limpiarFormulariosDiagnostico = function (tipoDiagnostico) {
        switch (tipoDiagnostico) {
            case '1':
                file.limpiar('#evidenciasImpericiaCorrectivo');
                file.limpiar('#evidenciasFallaEquipoCorrectivo');
                file.limpiar('#evidenciasFallaComponenteCorrectivo');
                file.limpiar('#evidenciasReporteMultimediaCorrectivo');
                select.cambiarOpcion('#selectImpericiaTipoFallaEquipoCorrectivo', '');
                select.cambiarOpcion('#selectImpericiaFallaDiagnosticoCorrectivo', '');
                select.cambiarOpcion('#selectTipoFallaEquipoCorrectivo', '');
                select.cambiarOpcion('#selectFallaDiagnosticoCorrectivo', '');
                select.cambiarOpcion('#selectComponenteDiagnosticoCorrectivo', '');
                select.cambiarOpcion('#selectTipoFallaComponenteCorrectivo', '');
                select.cambiarOpcion('#selectFallaComponenteDiagnosticoCorrectivo', '');
                $('#selectFallaDiagnosticoCorrectivo').attr('disabled', 'disabled');
                $('#selectFallaDiagnosticoCorrectivo').attr('disabled', 'disabled');
                $('#selectFallaComponenteDiagnosticoCorrectivo').attr('disabled', 'disabled');
                $('#inputObservacionesImpericiaCorrectivo').val('');
                $('#selectTipoFallaComponenteCorrectivo').val('');
                $('#inputObservacionesFallaComponenteCorrectivo').val('');
                $('#inputObservacionesReporteMultimediaCorrectivo').val('');
                break;
            case '2':
                file.limpiar('#evidenciasReporteFalsoCorrectivo');
                file.limpiar('#evidenciasFallaEquipoCorrectivo');
                file.limpiar('#evidenciasFallaComponenteCorrectivo');
                file.limpiar('#evidenciasReporteMultimediaCorrectivo');
                select.cambiarOpcion('#selectTipoFallaEquipoCorrectivo', '');
                select.cambiarOpcion('#selectFallaDiagnosticoCorrectivo', '');
                select.cambiarOpcion('#selectComponenteDiagnosticoCorrectivo', '');
                select.cambiarOpcion('#selectTipoFallaComponenteCorrectivo', '');
                select.cambiarOpcion('#selectFallaComponenteDiagnosticoCorrectivo', '');
                $('#selectFallaDiagnosticoCorrectivo').attr('disabled', 'disabled');
                $('#selectTipoFallaComponenteCorrectivo').attr('disabled', 'disabled');
                $('#selectFallaComponenteDiagnosticoCorrectivo').attr('disabled', 'disabled');
                $('#inputObservacionesReporteFalsoCorrectivo').val('');
                $('#inputObservacionesFallaEquipoCorrectivo').val('');
                $('#inputObservacionesFallaComponenteCorrectivo').val('');
                $('#inputObservacionesReporteMultimediaCorrectivo').val('');
                break;
            case '3':
                file.limpiar('#evidenciasReporteFalsoCorrectivo');
                file.limpiar('#evidenciasImpericiaCorrectivo');
                file.limpiar('#evidenciasFallaComponenteCorrectivo');
                file.limpiar('#evidenciasReporteMultimediaCorrectivo');
                select.cambiarOpcion('#selectImpericiaTipoFallaEquipoCorrectivo', '');
                select.cambiarOpcion('#selectImpericiaFallaDiagnosticoCorrectivo', '');
                select.cambiarOpcion('#selectComponenteDiagnosticoCorrectivo', '');
                select.cambiarOpcion('#selectTipoFallaComponenteCorrectivo', '');
                select.cambiarOpcion('#selectFallaComponenteDiagnosticoCorrectivo', '');
                $('#selectTipoFallaComponenteCorrectivo').attr('disabled', 'disabled');
                $('#selectFallaComponenteDiagnosticoCorrectivo').attr('disabled', 'disabled');
                $('#inputObservacionesReporteFalsoCorrectivo').val('');
                $('#inputObservacionesImpericiaCorrectivo').val('');
                $('#inputObservacionesFallaComponenteCorrectivo').val('');
                break;
            case '4':
                file.limpiar('#evidenciasReporteFalsoCorrectivo');
                file.limpiar('#evidenciasImpericiaCorrectivo');
                file.limpiar('#evidenciasFallaEquipoCorrectivo');
                file.limpiar('#evidenciasReporteMultimediaCorrectivo');
                select.cambiarOpcion('#selectImpericiaTipoFallaEquipoCorrectivo', '');
                select.cambiarOpcion('#selectImpericiaFallaDiagnosticoCorrectivo', '');
                select.cambiarOpcion('#selectTipoFallaEquipoCorrectivo', '');
                select.cambiarOpcion('#selectFallaDiagnosticoCorrectivo', '');
                $('#selectFallaDiagnosticoCorrectivo').attr('disabled', 'disabled');
                $('#inputObservacionesReporteFalsoCorrectivo').val('');
                $('#inputObservacionesImpericiaCorrectivo').val('');
                $('#inputObservacionesFallaEquipoCorrectivo').val('');
                $('#inputObservacionesReporteMultimediaCorrectivo').val('');
                break;
            case '5':
                file.limpiar('#evidenciasReporteFalsoCorrectivo');
                file.limpiar('#evidenciasImpericiaCorrectivo');
                file.limpiar('#evidenciasFallaEquipoCorrectivo');
                file.limpiar('#evidenciasFallaComponenteCorrectivo');
                select.cambiarOpcion('#selectImpericiaTipoFallaEquipoCorrectivo', '');
                select.cambiarOpcion('#selectImpericiaFallaDiagnosticoCorrectivo', '');
                select.cambiarOpcion('#selectTipoFallaEquipoCorrectivo', '');
                select.cambiarOpcion('#selectFallaDiagnosticoCorrectivo', '');
                select.cambiarOpcion('#selectComponenteDiagnosticoCorrectivo', '');
                select.cambiarOpcion('#selectTipoFallaComponenteCorrectivo', '');
                select.cambiarOpcion('#selectFallaComponenteDiagnosticoCorrectivo', '');
                $('#selectFallaDiagnosticoCorrectivo').attr('disabled', 'disabled');
                $('#selectFallaDiagnosticoCorrectivo').attr('disabled', 'disabled');
                $('#selectFallaComponenteDiagnosticoCorrectivo').attr('disabled', 'disabled');
                $('#inputObservacionesReporteFalsoCorrectivo').val('');
                $('#inputObservacionesImpericiaCorrectivo').val('');
                $('#inputObservacionesFallaEquipoCorrectivo').val('');
                $('#inputObservacionesFallaComponenteCorrectivo').val('');
                break;
        }
    };
    var agregandoSolcitudRefaccion = function () {
        var filas = [];
        var idRefaccion = $('#selectRefaccionSolicitud').val();
        var nombreRefaccion = $('#selectRefaccionSolicitud option:selected').text();
        var cantidad = $('#inputCantidadRefaccionSolicitud').val();
        filas.push([idRefaccion, nombreRefaccion, cantidad]);
        $.each(filas, function (key, value) {
            tabla.agregarFila('#data-table-solicitud-refacciones', value);
        });
        evento.mostrarMensaje('.errorRefaccionSolicitud', true, 'Datos agregados a la Tabla.', 3000);
        select.cambiarOpcion('#selectRefaccionSolicitud', '');
        $('#inputCantidadRefaccionSolicitud').val('');
    };
    var agregandoSolicitudRefaccion = function () {
        var filas = [];
        var idRefaccion = $('#selectRefaccionSolucionReparacionConRefaccion').val();
        var nombreRefaccion = $('#selectRefaccionSolucionReparacionConRefaccion option:selected').text();
        var cantidad = $('#inputCantidadRefaccionSolicitudReparacionConRefaccion').val();
        filas.push([idRefaccion, nombreRefaccion, cantidad]);
        $.each(filas, function (key, value) {
            tabla.agregarFila('#data-table-reparacion-refaccion', value);
        });
        evento.mostrarMensaje('.errorFormularioSolucionReparacionConRefaccion', true, 'Datos agregados a la Tabla.', 3000);
        select.cambiarOpcion('#selectRefaccionSolucionReparacionConRefaccion', '');
        $('#inputCantidadRefaccionSolicitudReparacionConRefaccion').val('');
    };
    var guardarDatosTablaRefaccionesSolicitudes = function () {
        var datosTablaRefaccionesSolicitudes = arguments[0];
        var servicio = arguments[1];
        var datosTablaPoliza = arguments[2];
        var sucursal = arguments[3];
        var respuestaDatos = arguments[4];
        var tipoSolicitud = arguments[5];
        var datosTabla = [];
        for (var i = 0; i < datosTablaRefaccionesSolicitudes.length; i++) {
            datosTabla.push(datosTablaRefaccionesSolicitudes[i]);
        }

        if (tipoSolicitud === 'almacen' || tipoSolicitud === 'ti') {
            evento.mostrarModal('Confirmar Solicitud de Refacción', formularioAsignacionSolicitud());
            select.crearSelect('#selectAtiendeSolcitud');
        } else {
            var data = {servicio: servicio, tipoSolicitud: 'refaccion', equiposSolicitudes: datosTabla};
            evento.enviarEvento('Seguimiento/SolicitarMultimedia', data, '#seccion-servicio-correctivo', function (respuesta) {
                if (respuesta instanceof Array || respuesta instanceof Object) {
                    select.cambiarOpcion('#selectEquipoRespaldo', '');
                    $('#inputSerieRespaldo').val('');
                    $("#dejarEquipoRespaldo").attr('checked', false);
                    $("#noSeCuentaEquipoRespaldo").attr('checked', false);
                    tabla.limpiarTabla('#data-table-servicios-solicitudes-refacciones');
                    $('#dejarEquipoGarantia').addClass('hidden');
                    $('#entregaEnvioEquipo').addClass('hidden');
                    recargandoTablaSolicitudRefaccion(respuesta);
                    tabla.limpiarTabla('#data-table-solicitud-refacciones');
                    evento.mostrarMensaje('.errorRefaccionSolicitud', true, 'Se ha reasignado con éxito el servicio a multimedia.', 3000);
                } else {
                    evento.mostrarMensaje('.errorRefaccionSolicitud', false, 'Para asignar a Multimedia debe guardar el Folio.', 3000);
                }
            });
        }

        if (tipoSolicitud === 'almacen') {
            $.each(respuestaDatos.informacion.atiende, function (key, valor) {
                $("#selectAtiendeSolcitud").append('<option value=' + valor.IdUsuario + '>' + valor.Nombre + '</option>');
            });
            select.cambiarOpcion('#selectAtiendeSolcitud', '12');
            $("#selectAtiendeSolcitud").removeAttr('disabled');
        } else if (tipoSolicitud === 'ti') {
            var data = {servicio: servicio};
            evento.enviarEvento('Seguimiento/ConsultaCorrectivoTI', data, '#confirmarSolicitud', function (respuesta) {
                $.each(respuesta, function (key, valor) {
                    $("#selectAtiendeSolcitud").append('<option value=' + valor.userId + '>' + valor.userName + '</option>');
                });
                $("#selectAtiendeSolcitud").removeAttr('disabled');
            });
        }

        $('#btnModalConfirmar').off('click');
        $('#btnModalConfirmar').on('click', function () {
            if ($("#selectAtiendeSolcitud").val() !== '') {
                var atiende = $('#selectAtiendeSolcitud').val();
                var nombreSucursal = $('#selectSucursalesCorrectivo option:selected').text();
                var data = {servicio: servicio, refaccionesSolicitudes: datosTabla, ticket: datosTablaPoliza[1], solicitud: datosTablaPoliza[2], sucursal: sucursal, atiende: atiende, nombreSucursal: nombreSucursal, tipoSolicitud: tipoSolicitud};
                evento.enviarEvento('Seguimiento/guardarRefaccionesSolicitud', data, '#modal-dialogo', function (respuesta) {
                    if (respuesta instanceof Array || respuesta instanceof Object) {
                        select.cambiarOpcion('#selectEquipoRespaldo', '');
                        $('#inputSerieRespaldo').val('');
                        $("#dejarEquipoRespaldo").attr('checked', false);
                        $("#noSeCuentaEquipoRespaldo").attr('checked', false);
                        tabla.limpiarTabla('#data-table-servicios-solicitudes-refacciones');
                        $('#dejarEquipoGarantia').addClass('hidden');
                        $('#entregaEnvioEquipo').addClass('hidden');
                        recargandoTablaSolicitudRefaccion(respuesta);
                        tabla.limpiarTabla('#data-table-solicitud-refacciones');
                        evento.mostrarMensaje('.errorRefaccionSolicitud', true, 'Datos GuardadosCorrectamente.', 3000);
                    } else if (respuesta === 'faltaFolio') {
                        evento.mostrarMensaje('.errorRefaccionSolicitud', false, 'El servicio no tiene Folio.', 3000);
                    } else {
                        evento.mostrarMensaje('.errorRefaccionSolicitud', false, 'No se pudo guardar los datos por favor de contates al Área de Desarrollo.', 3000);
                    }
                    evento.cerrarModal();
                });
            } else {
                evento.mostrarMensaje('.errorAtiendeSolicitud', false, 'Debe seleccionar para quien va la solicitud.', 3000);
            }
        });
    };
    var recargandoTablaSolicitudRefaccion = function (solicitudesRefaccion) {
        tabla.limpiarTabla('#data-table-servicios-solicitudes-refacciones');
        if (solicitudesRefaccion.length > 0) {
            var refaccionCantidad;
            $.each(solicitudesRefaccion, function (key, item) {
                var idSolicitudes = solicitudesRefaccion[key]['Id'];
                var arrayIdSolicitudes = idSolicitudes.split(",");
                refaccionCantidad = item.RefaccionCantidad.replace(/,/g, "<br>");
                tabla.agregarFila('#data-table-servicios-solicitudes-refacciones', [item.Servicio, item.Solicitante, item.FechaCreacion, refaccionCantidad, item.Estatus, arrayIdSolicitudes]);
            });
        }
    };
    var recargandoTablaSolicitudEquipo = function (solicitudesEquipo) {

        tabla.limpiarTabla('#data-table-servicios-solicitudes-equipos');
        if (solicitudesEquipo.length > 0) {
            var equipoCantidad = '';
            $.each(solicitudesEquipo, function (key, item) {
                var idSolicitudes = solicitudesEquipo[key]['Id'];
                var arrayIdSolicitudes = idSolicitudes.split(",");
                equipoCantidad = item.EquipoCantidad.replace(/,/g, "<br>");
                tabla.agregarFila('#data-table-servicios-solicitudes-equipos', [item.Servicio, item.Solicitante, item.FechaCreacion, equipoCantidad, item.Estatus, arrayIdSolicitudes]);
            });
        }
    };
    var recargandoTablaReparacionRefaccion = function (ReparacionRefaccion) {
        tabla.limpiarTabla('#data-table-reparacion-refaccion');
        $.each(ReparacionRefaccion, function (key, item) {
            tabla.agregarFila('#data-table-reparacion-refaccion', [item.IdRefaccion, item.NombreRefaccion, item.Cantidad]);
        });
    };
    var agregandoSolicitudEquipo = function () {
        var filas = [];
        var idEquipo = $('#selectEquipoSolicitud').val();
        var nombreEquipo = $('#selectEquipoSolicitud option:selected').text();
        var cantidad = '1';
        filas.push([idEquipo, nombreEquipo, cantidad]);
        $.each(filas, function (key, value) {
            tabla.agregarFila('#data-table-solicitud-equipos', value);
        });
        evento.mostrarMensaje('.errorEquipoSolicitud', true, 'Datos agregados a la Tabla.', 3000);
        select.cambiarOpcion('#selectEquipoSolicitud', '');
    };
    var guardarDatosTablaEquiposSolicitudes = function () {
        var datosTablaEquiposSolicitudes = arguments[0];
        var servicio = arguments[1];
        var datosTablaPoliza = arguments[2];
        var sucursal = arguments[3];
        var respuestaDatos = arguments[4];
        var tipoSolicitud = arguments[5];
        var datosTabla = [];
        for (var i = 0; i < datosTablaEquiposSolicitudes.length; i++) {
            datosTabla.push(datosTablaEquiposSolicitudes[i]);
        }

        if (tipoSolicitud === 'almacen' || tipoSolicitud === 'ti') {
            evento.mostrarModal('Confirmar Solicitud de Equipo', formularioAsignacionSolicitud());
            select.crearSelect('#selectAtiendeSolcitud');
        } else {
            var data = {servicio: servicio, tipoSolicitud: 'equipo', equiposSolicitudes: datosTabla};
            evento.enviarEvento('Seguimiento/SolicitarMultimedia', data, '#seccion-servicio-correctivo', function (respuesta) {
                if (respuesta instanceof Array || respuesta instanceof Object) {
                    tabla.limpiarTabla('#data-table-solicitud-equipos');
                    $('#inputSerieRespaldo').removeAttr('disabled');
                    $('#selectEquipoRespaldo').removeAttr('disabled');
                    select.cambiarOpcion('#selectEquipoRespaldo', '');
                    $('#inputSerieRespaldo').val('');
                    $("#dejarEquipoRespaldo").attr('checked', false);
                    $("#noSeCuentaEquipoRespaldo").attr('checked', false);
                    tabla.limpiarTabla('#data-table-servicios-solicitudes-refacciones');
                    $('#dejarEquipoGarantia').addClass('hidden');
                    $('#entregaEnvioEquipo').addClass('hidden');
                    recargandoTablaSolicitudEquipo(respuesta);
                    evento.mostrarMensaje('.errorEquipoSolicitud', true, 'Se ha reasignado con éxito el servicio a multimedia', 5000);
                } else {
                    evento.mostrarMensaje('.errorEquipoSolicitud', false, 'Para asignar a Multimedia debe guardar el Folio.', 3000);
                }
            });
        }

        if (tipoSolicitud === 'almacen') {
            $.each(respuestaDatos.informacion.atiende, function (key, valor) {
                $("#selectAtiendeSolcitud").append('<option value=' + valor.IdUsuario + '>' + valor.Nombre + '</option>');
            });
            select.cambiarOpcion('#selectAtiendeSolcitud', '12');
            $("#selectAtiendeSolcitud").removeAttr('disabled');
        } else if (tipoSolicitud === 'ti') {
            var data = {servicio: servicio};
            evento.enviarEvento('Seguimiento/ConsultaCorrectivoTI', data, '#confirmarSolicitud', function (respuesta) {
                $.each(respuesta, function (key, valor) {
                    $("#selectAtiendeSolcitud").append('<option value=' + valor.userId + '>' + valor.userName + '</option>');
                });
                $("#selectAtiendeSolcitud").removeAttr('disabled');
            });
        }

        $('#btnModalConfirmar').off('click');
        $('#btnModalConfirmar').on('click', function () {
            if ($("#selectAtiendeSolcitud").val() !== '') {
                var atiende = $('#selectAtiendeSolcitud').val();
                var nombreSucursal = $('#selectSucursalesCorrectivo option:selected').text();
                var data = {servicio: servicio, equiposSolicitudes: datosTabla, ticket: datosTablaPoliza[1], solicitud: datosTablaPoliza[2], sucursal: sucursal, atiende: atiende, nombreSucursal: nombreSucursal, tipoSolicitud: tipoSolicitud};
                evento.enviarEvento('Seguimiento/guardarEquiposSolicitud', data, '#modal-dialogo', function (respuesta) {
                    if (respuesta instanceof Array || respuesta instanceof Object) {
                        tabla.limpiarTabla('#data-table-solicitud-equipos');
                        $('#inputSerieRespaldo').removeAttr('disabled');
                        $('#selectEquipoRespaldo').removeAttr('disabled');
                        select.cambiarOpcion('#selectEquipoRespaldo', '');
                        $('#inputSerieRespaldo').val('');
                        $("#dejarEquipoRespaldo").attr('checked', false);
                        $("#noSeCuentaEquipoRespaldo").attr('checked', false);
                        tabla.limpiarTabla('#data-table-servicios-solicitudes-refacciones');
                        $('#dejarEquipoGarantia').addClass('hidden');
                        $('#entregaEnvioEquipo').addClass('hidden');
                        recargandoTablaSolicitudEquipo(respuesta);
                        evento.mostrarMensaje('.errorEquipoSolicitud', true, 'Datos GuardadosCorrectamente.', 3000);
                    } else if (respuesta === 'faltaFolio') {
                        evento.mostrarMensaje('.errorEquipoSolicitud', false, 'El servicio no tiene Folio.', 3000);
                    } else {
                        evento.mostrarMensaje('.errorEquipoSolicitud', false, 'No se pudo guardar los datos por favor de contates al Área de Desarrollo.', 3000);
                    }
                    evento.cerrarModal();
                });
            } else {
                evento.mostrarMensaje('.errorAtiendeSolicitud', false, 'Debe seleccionar para quien va la solicitud.', 3000);
            }
        });
    };
    var guardarInformacionRespaldo = function () {
        var data = arguments[0];
        $('#btnGuardarFirma').addClass('disabled');
        evento.enviarEvento('Seguimiento/guardarInformacionEquipoRespaldo', data, '#modal-dialogo', function (respuesta) {
            $('#btnGuardarFirma').removeClass('disabled');
            if (respuesta === true) {
                file.limpiar('#evidenciaEnvioGarantia');
                file.limpiar('#evidenciaEntregaEnvioGarantia');
                tabla.limpiarTabla('#data-table-servicios-solicitudes-refacciones');
                tabla.limpiarTabla('#data-table-servicios-solicitudes-equipos');
                $('#inputSerieRespaldo').attr('disabled', 'disabled');
                $('#selectEquipoRespaldo').attr('disabled', 'disabled');
                $('#entregaEnvioEquipo').removeClass('hidden');
                $('#informacionSolicitudEquipoRespaldo').addClass('hidden');
                $('#botonEntregaEquipo').removeClass('hidden');
                $('#btnGuardarInformacionGarantia').addClass('disabled');
                $('#informacionAutorisacionSinRespaldo').addClass('hidden');
                select.cambiarOpcion('#selectTipoEnvioGarantia', '');
                select.cambiarOpcion('#selectListaTipoEnvioGarantia', '');
                select.cambiarOpcion('#selectEquipoRespaldoEntregaEnvioGarantia', '');
                $('#inputGuiaGarantia').val('');
                $('#inputComentarioEntregaEnvioGarantia').val('');
                $('#entregaFechaEnvioGarantia').val('');
                $('#inputComentariosEnvioGarantia').val('');
                $('.entregaGarantia').attr('disabled', 'disabled');
                $('[href=#eviar-equipo]').parent('li').removeClass('active');
                $('#eviar-equipo').removeClass('active in');
                $('[href=#entrega-equipo]').parent('li').addClass('active');
                $('#entrega-equipo').addClass('active in');
                $('[href=#entregaEquipo]').parent('li').removeClass('active');
                $('#entregaEquipo').removeClass('active in');
                $('[href=#formaEnvio]').parent('li').addClass('active');
                $('#formaEnvio').addClass('active in');
                evento.cerrarModal();
                evento.mostrarMensaje('.errorInformacionRespaldo', true, 'Datos Guardados Correctamente.', 3000);
            }
        });
    };
    var guardarInformacionRespaldoEvidencia = function () {
        var data = arguments[0];
        $('#btnGuardarAutorizacion').addClass('disabled');
        file.enviarArchivos('#evidenciasAutorizacion', 'Seguimiento/guardarInformacionEquipoRespaldo', '#formularioPersonalAutoriza', data, function (respuesta) {
            $('#btnGuardarAutorizacion').removeClass('disabled');
            if (respuesta instanceof Array || respuesta instanceof Object) {
                var html = '';
                var evidencia = respuesta[0].Evidencia.split(',');
                $.each(evidencia, function (key, value) {
                    html += '<div class = "evidencia2">\n\
                                <a href = "' + value + '" data-lightbox="image-' + value + respuesta[0].Id + '">\n\
                                    <img src = "' + value + '" alt = "Lights" style = "width:100%">\n\
                                </a>\n\
                            </div>';
                });
                file.limpiar('#evidenciaEnvioGarantia');
                file.limpiar('#evidenciaEntregaEnvioGarantia');
                tabla.limpiarTabla('#data-table-servicios-solicitudes-refacciones');
                tabla.limpiarTabla('#data-table-servicios-solicitudes-equipos');
                $('#inputSerieRespaldo').removeAttr('disabled');
                $('#selectEquipoRespaldo').removeAttr('disabled');
                select.cambiarOpcion('#selectEquipoRespaldo', '');
                select.cambiarOpcion('#selectTipoEnvioGarantia', '');
                select.cambiarOpcion('#selectListaTipoEnvioGarantia', '');
                select.cambiarOpcion('#selectEquipoRespaldoEntregaEnvioGarantia', '');
                $('#inputSerieRespaldo').val('');
                $('#inputGuiaGarantia').val('');
                $('#inputComentarioEntregaEnvioGarantia').val('');
                $('#entregaFechaEnvioGarantia').val('');
                $('.entregaGarantia').attr('disabled', 'disabled');
                $('#inputComentariosEnvioGarantia').val('');
                $('#divAutoriza').empty().html(respuesta[0].Autoriza);
                $('#divEvidenciasAtorizacion').empty().html(html);
                $('#firmaEntregaEquipo').addClass('hidden');
                $('#informacionSolicitudEquipoRespaldo').addClass('hidden');
                $('#informacionAutorisacionSinRespaldo').removeClass('hidden');
                $('#botonEntregaEquipo').removeClass('hidden');
                $('#entregaEnvioEquipo').removeClass('hidden');
                $('#btnGuardarInformacionGarantia').removeClass('disabled');
                $('[href=#eviar-equipo]').parent('li').removeClass('active');
                $('#eviar-equipo').removeClass('active in');
                $('[href=#entrega-equipo]').parent('li').addClass('active');
                $('#entrega-equipo').addClass('active in');
                $('[href=#entregaEquipo]').parent('li').removeClass('active');
                $('#entregaEquipo').removeClass('active in');
                $('[href=#formaEnvio]').parent('li').addClass('active');
                $('#formaEnvio').addClass('active in');
                evento.cerrarModal();
                evento.mostrarMensaje('.errorInformacionRespaldo', true, 'Datos Guardados Correctamente.', 3000);
            } else {
                $('#entregaEnvioEquipo').removeClass('hidden');
                evento.cerrarModal();
                evento.mostrarMensaje('.errorInformacionRespaldo', false, 'No se guardo la evidencia contacte al equipo de desarrollo.', 5000);
            }
        });
    };
    var guardarSolicitudEquipoRespaldo = function () {
        var data = arguments[0];
        $('#btnModalConfirmar').addClass('disabled');
        if (validarCampos($('#selectAtiendeSolcitud').val(), '.errorAtiendeSolicitud', 'Debes seleccionar el campo confirmar y asignar a.')) {
            var dataEquipoRespaldo = {
                servicio: data.servicio,
                sucursal: data.sucursal,
                ticket: data.datosTabla[1],
                servicioAnterior: data.datosTabla[0],
                solicitud: data.datosTabla[2],
                asignar: $('#selectAtiendeSolcitud').val(),
                equipo: $('#selectEquipoCorrectivo').val(),
                cantidad: '1'
            };
            evento.enviarEvento('Seguimiento/guardarCrearSolicitarEquipoRespaldo', dataEquipoRespaldo, '#confirmarSolicitud', function (respuesta) {
                $('#btnModalConfirmar').removeClass('disabled');
                if (respuesta instanceof Array || respuesta instanceof Object) {
                    file.limpiar('#evidenciaEnvioGarantia');
                    tabla.limpiarTabla('#data-table-servicios-solicitudes-refacciones');
                    tabla.limpiarTabla('#data-table-servicios-solicitudes-equipos');
                    $('#entregaEnvioEquipo').removeClass('hidden');
                    $('#informacionAutorisacionSinRespaldo').addClass('hidden');
                    $('#firmaEntregaEquipo').addClass('hidden');
                    $('#informacionSolicitudEquipoRespaldo').removeClass('hidden');
                    $('#botonEntregaEquipo').removeClass('hidden');
                    $('#selectEquipoRespaldo').removeAttr('disabled');
                    $('#inputSerieRespaldo').removeAttr('disabled');
                    select.cambiarOpcion('#selectEquipoRespaldo', '');
                    select.cambiarOpcion('#selectTipoEnvioGarantia', '');
                    select.cambiarOpcion('#selectListaTipoEnvioGarantia', '');
                    select.cambiarOpcion('#selectEquipoRespaldoEntregaEnvioGarantia', '');
                    $('#inputGuiaGarantia').val('');
                    $('#inputComentarioEntregaEnvioGarantia').val('');
                    $('#entregaFechaEnvioGarantia').val('');
                    $('.entregaGarantia').attr('disabled', 'disabled');
                    $('#inputSerieRespaldo').val('');
                    $('#inputComentariosEnvioGarantia').val('');
                    $('#btnGuardarInformacionGarantia').removeClass('disabled');
                    $('[href=#eviar-equipo]').parent('li').removeClass('active');
                    $('#eviar-equipo').removeClass('active in');
                    $('[href=#entrega-equipo]').parent('li').addClass('active');
                    $('#entrega-equipo').addClass('active in');
                    $('[href=#entregaEquipo]').parent('li').removeClass('active');
                    $('#entregaEquipo').removeClass('active in');
                    $('[href=#formaEnvio]').parent('li').addClass('active');
                    $('#formaEnvio').addClass('active in');
                    $('#divAtiende').empty().html(respuesta[0].Atiende);
                    $('#divFechaAtiende').empty().html(respuesta[0].FechaCreacion);
                    evento.cerrarModal();
                    evento.mostrarMensaje('.errorInformacionRespaldo', true, 'Datos Guardados Correctamente.', 3000);
                    file.limpiar('#evidenciaEntregaEnvioGarantia');
                }
            });
        }
    };
    var guardarEntregaEquipoGarantia = function () {
        var data = arguments[0];
        var htmlDatosFirma = '';
        $('#btnGuardarFirma').addClass('disabled');
        evento.enviarEvento('Seguimiento/enviarEntregaEquipoGarantia', data, '#modal-dialogo', function (respuesta) {
            $('#btnGuardarFirma').removeClass('disabled');
            if (respuesta instanceof Array || respuesta instanceof Object) {
                select.cambiarOpcion('#selectTipoEnvioGarantia', '');
                select.cambiarOpcion('#selectListaTipoEnvioGarantia', '');
                select.cambiarOpcion('#selectEquipoRespaldoEntregaEnvioGarantia', '');
                $('#inputGuiaGarantia').val('');
                $('#inputComentariosEnvioGarantia').val('');
                $('#entregaFechaEnvioGarantia').val('');
                $('#inputComentarioEntregaEnvioGarantia').val('');
                $('.entregaGarantia').attr('disabled', 'disabled');
                $('#entregaEnvioEquipo').removeClass('hidden');
                $('#botonEntregaEquipo').addClass('hidden');
                $('#botonEntregaTI').removeClass('hidden');
                $('#firmaEntregaEquipo').removeClass('hidden');
                $('#firmaEntregaTI').addClass('hidden');
                htmlDatosFirma = '<div class="col-md-offset-4 col-md-4 col-sm-offset-3 col-sm-6 col-xs-offset-12 col-xs-12">\n\
                                    <h5 class="f-w-700 text-center">Firma de Entrega</h5>\n\
                                    <img style="max-height: 120px;" src="' + respuesta[0].Firma + '" alt="Firma de Entrega" />\n\
                                    <h6 class="f-w-700 text-center">' + respuesta[0].Recibe + '</h6>\n\
                                    <h6 class="f-w-700 text-center">' + respuesta[0].Fecha + '</h6>\n\
                                </div>';
                $('#firmaEntregaEquipo').empty().html(htmlDatosFirma);
                file.limpiar('#evidenciaEnvioGarantia');
                file.limpiar('#evidenciaEntregaEnvioGarantia');
                $('#btnGuardarFirma').removeClass('disabled');
                evento.cerrarModal();
                evento.mostrarMensaje('.errorEntregaEquipo', true, 'Datos Guardados Correctamente.', 3000);
            }
        });
    };
    var guardarEntregaTIGarantia = function () {
        var data = arguments[0];
        var htmlDatosFirma = '';
        $('#btnGuardarFirma').addClass('disabled');
        evento.enviarEvento('Seguimiento/enviarEntregaEquipoGarantia', data, '#modal-dialogo', function (respuesta) {
            $('#btnGuardarFirma').removeClass('disabled');
            if (respuesta instanceof Array || respuesta instanceof Object) {
                select.cambiarOpcion('#selectTipoEnvioGarantia', '');
                select.cambiarOpcion('#selectListaTipoEnvioGarantia', '');
                select.cambiarOpcion('#selectEquipoRespaldoEntregaEnvioGarantia', '');
                $('#inputGuiaGarantia').val('');
                $('#inputComentariosEnvioGarantia').val('');
                $('#entregaFechaEnvioGarantia').val('');
                $('#inputComentarioEntregaEnvioGarantia').val('');
                $('.entregaGarantia').attr('disabled', 'disabled');
                $('#entregaEnvioEquipo').removeClass('hidden');
                $('#botonEntregaTI').addClass('hidden');
                $('#botonEntregaEquipo').removeClass('hidden');
                $('#firmaEntregaTI').removeClass('hidden');
                $('#firmaEntregaEquipo').addClass('hidden');
                htmlDatosFirma = '<div class="col-md-offset-4 col-md-4 col-sm-offset-3 col-sm-6 col-xs-offset-12 col-xs-12">\n\
                                    <h5 class="f-w-700 text-center">Firma de Entrega</h5>\n\
                                    <img style="max-height: 120px;" src="' + respuesta[0].Firma + '" alt="Firma de Entrega" />\n\
                                    <h6 class="f-w-700 text-center">' + respuesta[0].NombreRecibe + '</h6>\n\
                                    <h6 class="f-w-700 text-center">' + respuesta[0].Fecha + '</h6>\n\
                                </div>';
                $('#firmaEntregaTI').empty().html(htmlDatosFirma);
                $('#firmaEntregaEquipo').empty().html('');
                file.limpiar('#evidenciaEnvioGarantia');
                file.limpiar('#evidenciaEntregaEnvioGarantia');
                $('#btnGuardarFirma').removeClass('disabled');
                evento.cerrarModal();
                evento.mostrarMensaje('.errorEntregaTI', true, 'Datos Guardados Correctamente.', 3000);
            }
        });
    };
    var guardarEnvioGarantia = function () {
        var servicio = arguments[0];
        var envia = $('#selectTipoEnvioGarantia').val();
        var paqueteriaConsolidado = $('#selectListaTipoEnvioGarantia').val();
        var guia = $('#inputGuiaGarantia').val();
        var comentarios = $('#inputComentariosEnvioGarantia').val();
        var data = {servicio: servicio, envia: envia, paqueteriaConsolidado: paqueteriaConsolidado, guia: guia, comentarios: comentarios};
        file.enviarArchivos('#evidenciaEnvioGarantia', 'Seguimiento/guardarEnvioGarantia', '#seccion-servicio-correctivo', data, function (respuesta) {
            if (respuesta === true) {
                $('.entregaGarantia').removeAttr('disabled');
                $('#mensajeEntregaGarantia').addClass('hidden');
                $('#firmaEntregaEquipo').addClass('hidden');
                $('#botonEntregaEquipo').removeClass('hidden');
                evento.mostrarMensaje('#errorGuardarEnvioGarantia', true, 'Datos Guardados Correctamente.', 3000);
            } else {
                evento.mostrarMensaje('#errorGuardarEnvioGarantia', false, respuesta, 6000);
            }
        });
    };
    var guardarEntregaGarantia = function () {
        var servicio = arguments[0];
        var fecha = $('#entregaFechaEnvioGarantia').val();
        var recibe = $('#selectEquipoRespaldoEntregaEnvioGarantia').val();
        var comentarios = $('#inputComentarioEntregaEnvioGarantia').val();
        var data = {servicio: servicio, fecha: fecha, recibe: recibe, comentarios: comentarios};
        file.enviarArchivos('#evidenciaEntregaEnvioGarantia', 'Seguimiento/guardarEntregaGarantiafff', '#seccion-servicio-correctivo', data, function (respuesta) {
            if (respuesta === true) {
                evento.mostrarMensaje('#errorGuardarEnvioEntregaGarantia', true, 'Datos Guardados Correctamente.', 3000);
            } else {
                evento.mostrarMensaje('#errorGuardarEnvioEntregaGarantia', false, respuesta, 6000);
            }
        });
    };
    var guardarConcluirCorrectivoReparacionSinEquipo = function () {
        var servicio = arguments[0];
        var datosTablaPoliza = arguments[1];
        var correctivosSoluciones = arguments[2];
        //Si es 1 solo guarda, si es 2 se se concluye el servicio
        var operacion = arguments[3];
        var respuestaAnterior = arguments[4] || null;
        var solucion = $('#selectSolucionReparacionSinEquipo').val();
        var observaciones = $('#inputObservacionesSolucionReparacionSinEquipo').val();
        var data = {};
        var evidencias = '';
        var idTipoSolucion = '1';
        if (correctivosSoluciones !== null) {
            if (correctivosSoluciones.length > 0) {
                evidencias = correctivosSoluciones[0].Evidencias;
                idTipoSolucion = correctivosSoluciones[0].IdTipoSolucion;
            }
        }

        data = {servicio: servicio, solucion: solucion, observaciones: observaciones, evidencias: evidencias, idTipoSolucion: idTipoSolucion, operacion: operacion, ticket: datosTablaPoliza[1], idSolicitud: datosTablaPoliza[2]};
        if ($('#evidenciasSolucionReparacionSinEquipo').val() !== '') {
            file.enviarArchivos('#evidenciasSolucionReparacionSinEquipo', 'Seguimiento/guardarReparacionSinEquipo', '#seccion-servicio-correctivo', data, function (respuesta) {
                if (respuesta !== 'faltaDatosGenerales') {
                    if (respuesta !== 'faltaDatosDiagnostico') {
                        var dataSD = {servicio: servicio, ticket: datosTablaPoliza[1], servicioConcluir: true};
                        if (operacion === '2' && respuesta !== 'faltanServicios') {
                            evento.enviarEvento('Seguimiento/enviarSolucionCorrectivoSD', dataSD, '#seccion-servicio-correctivo', function (respuesta) {
                                if (respuesta.code === 200) {
                                    if (respuesta.message === 'serviciosConcluidos') {
                                        modalCampoFirma(respuesta.message, datosTablaPoliza[1], servicio, '.errorFormularioSolucionReparacionSinEquipo', respuestaAnterior, true, '4');
                                    } else {
                                        concluirServicio(servicio);
                                    }
                                } else {
                                    servicios.mensajeModal(respuesta.message, 'ERROR SD', true);
                                }
                            });
                        } else {
                            select.cambiarOpcion('#selectEquipoSolucionCambioEquipo', '');
                            $('#inputSerieSolucionCambioEquipo').val('');
                            $('#inputObservacionesSolucionCambioEquipo').val('');
                            $('#inputObservacionesSolucionReparacionConRefaccion').val('');
                            tabla.limpiarTabla('#data-table-reparacion-refaccion');
                            file.limpiar('#evidenciasSolucionCambioEquipo');
                            file.limpiar('#evidenciasSolucionReparacionConRefaccion');
                            evento.mostrarMensaje('.errorFormularioSolucionReparacionSinEquipo', true, 'Datos Guardados Correctamente.', 3000);
                        }
                    } else {
                        file.limpiar('#evidenciasSolucionReparacionSinEquipo');
                        evento.mostrarMensaje('.errorFormularioSolucionReparacionSinEquipo', false, 'Debe guardar los datos del dignostico.', 5000);
                    }
                } else {
                    file.limpiar('#evidenciasSolucionReparacionSinEquipo');
                    evento.mostrarMensaje('.errorFormularioSolucionReparacionSinEquipo', false, 'Falta llenar los datos de Información General.', 5000);
                }
            });
        } else {
            evento.enviarEvento('Seguimiento/guardarReparacionSinEquipo', data, '#seccion-servicio-correctivo', function (respuesta) {
                if (respuesta !== 'faltaDatosGenerales') {
                    if (respuesta !== 'faltaDatosDiagnostico') {
                        var dataSD = {servicio: servicio, ticket: datosTablaPoliza[1], servicioConcluir: true};
                        if (operacion === '2' && respuesta !== 'faltanServicios') {
                            evento.enviarEvento('Seguimiento/enviarSolucionCorrectivoSD', dataSD, '#seccion-servicio-correctivo', function (respuesta) {
                                if (respuesta.code === 200) {
                                    if (respuesta.message === 'serviciosConcluidos') {
                                        modalCampoFirma(respuesta.message, datosTablaPoliza[1], servicio, '.errorFormularioSolucionReparacionSinEquipo', respuestaAnterior, true, '4');
                                    } else {
                                        concluirServicio(servicio);
                                    }
                                } else {
                                    servicios.mensajeModal(respuesta.message, 'ERROR SD', true);
                                }
                            });
                        } else {
                            select.cambiarOpcion('#selectEquipoSolucionCambioEquipo', '');
                            $('#inputSerieSolucionCambioEquipo').val('');
                            $('#inputObservacionesSolucionCambioEquipo').val('');
                            $('#inputObservacionesSolucionReparacionConRefaccion').val('');
                            tabla.limpiarTabla('#data-table-reparacion-refaccion');
                            file.limpiar('#evidenciasSolucionCambioEquipo');
                            file.limpiar('#evidenciasSolucionReparacionConRefaccion');
                            evento.mostrarMensaje('.errorFormularioSolucionReparacionSinEquipo', true, 'Datos Guardados Correctamente.', 3000);
                        }
                    } else {
                        file.limpiar('#evidenciasSolucionReparacionSinEquipo');
                        evento.mostrarMensaje('.errorFormularioSolucionReparacionSinEquipo', false, 'Debe guardar los datos del dignostico.', 5000);
                    }
                } else {
                    file.limpiar('#evidenciasSolucionReparacionSinEquipo');
                    evento.mostrarMensaje('.errorFormularioSolucionReparacionSinEquipo', false, 'Falta llenar los datos de Información General.', 5000);
                }
            });
        }
    };
    var guardarConcluirCorrectivoReparacionConRefaccion = function () {
        var servicio = arguments[0];
        var datosTablaPoliza = arguments[1];
        var datosTablaReparacionRefaccion = arguments[2];
        var correctivosSoluciones = arguments[3];
        //Si es 1 solo guarda, si es 2 se se concluye el servicio
        var operacion = arguments[4];
        var respuestaAnterior = arguments[5] || null;
        var observaciones = $('#inputObservacionesSolucionReparacionConRefaccion').val();
        var datosTabla = [];
        var data = {};
        var evidencias = '';
        var idTipoSolucion = '2';
        var usaStock = false;
        if (correctivosSoluciones !== null) {
            if (correctivosSoluciones.length > 0) {
                evidencias = correctivosSoluciones[0].Evidencias;
                idTipoSolucion = correctivosSoluciones[0].IdTipoSolucion;
            }
        }

        if ($("#data-table-reparacion-refaccion-stock").length) {
            datosTabla = datosTablaReparacionRefaccion;
            usaStock = true;
        } else {
            for (var i = 0; i < datosTablaReparacionRefaccion.length; i++) {
                datosTabla.push(datosTablaReparacionRefaccion[i]);
            }
        }

        data = {
            servicio: servicio,
            datosTablaReparacionRefaccion: datosTabla,
            observaciones: observaciones,
            evidencias: evidencias,
            idTipoSolucion: idTipoSolucion,
            operacion: operacion,
            ticket: datosTablaPoliza[1],
            idSolicitud: datosTablaPoliza[2],
            usaStock: usaStock
        };
        if ($('#evidenciasSolucionReparacionConRefaccion').val() !== '') {
            file.enviarArchivos('#evidenciasSolucionReparacionConRefaccion', 'Seguimiento/guardarReparacionConRefaccion', '#seccion-servicio-correctivo', data, function (respuesta) {
                if (respuesta !== 'faltaDatosGenerales') {
                    if (respuesta !== 'faltaDatosDiagnostico') {
                        if (operacion === '2' && respuesta !== 'faltanServicios') {
                            var dataSD = {servicio: servicio, ticket: datosTablaPoliza[1], servicioConcluir: true};
                            evento.enviarEvento('Seguimiento/enviarSolucionCorrectivoSD', dataSD, '#seccion-servicio-correctivo', function (respuesta) {
                                if (respuesta.code === 200) {
                                    if (respuesta.message === 'serviciosConcluidos') {
                                        modalCampoFirma(respuesta.message, datosTablaPoliza[1], servicio, '.evidenciasSolucionReparacionConRefaccion', respuestaAnterior, true, '4');
                                    } else {
                                        concluirServicio(servicio);
                                    }
                                } else {
                                    servicios.mensajeModal(respuesta.message, 'ERROR SD', true);
                                }
                            });
                        } else {
                            select.cambiarOpcion('#selectSolucionReparacionSinEquipo', '');
                            select.cambiarOpcion('#selectEquipoSolucionCambioEquipo', '');
                            $('#inputObservacionesSolucionReparacionSinEquipo').val('');
                            $('#inputSerieSolucionCambioEquipo').val('');
                            $('#inputObservacionesSolucionCambioEquipo').val('');
                            file.limpiar('#evidenciasSolucionReparacionSinEquipo');
                            file.limpiar('#evidenciasSolucionCambioEquipo');
                            evento.mostrarMensaje('.errorFormularioSolucionReparacionConRefaccion', true, 'Datos Guardados Correctamente.', 3000);
                        }
                    } else {
                        file.limpiar('#evidenciasSolucionReparacionConRefaccion');
                        evento.mostrarMensaje('.errorFormularioSolucionReparacionConRefaccion', false, 'Debe guardar los datos del dignostico.', 5000);
                    }
                } else {
                    file.limpiar('#evidenciasSolucionReparacionConRefaccion');
                    evento.mostrarMensaje('.errorFormularioSolucionReparacionConRefaccion', false, 'Falta llenar los datos de Información General.', 5000);
                }
            });
        } else {
            evento.enviarEvento('Seguimiento/guardarReparacionConRefaccion', data, '#seccion-servicio-correctivo', function (respuesta) {
                if (respuesta !== 'faltaDatosGenerales') {
                    if (respuesta !== 'faltaDatosDiagnostico') {
                        if (operacion === '2' && respuesta !== 'faltanServicios') {
                            var dataSD = {servicio: servicio, ticket: datosTablaPoliza[1], servicioConcluir: true};
                            evento.enviarEvento('Seguimiento/enviarSolucionCorrectivoSD', dataSD, '#seccion-servicio-correctivo', function (respuesta) {
                                if (respuesta.code === 200) {
                                    if (respuesta.message === 'serviciosConcluidos') {
                                        modalCampoFirma(respuesta.message, datosTablaPoliza[1], servicio, '.evidenciasSolucionReparacionConRefaccion', respuestaAnterior, true, '4');
                                    } else {
                                        concluirServicio(servicio);
                                    }
                                } else {
                                    servicios.mensajeModal(respuesta.message, 'ERROR SD', true);
                                }
                            });
                        } else {
                            select.cambiarOpcion('#selectSolucionReparacionSinEquipo', '');
                            select.cambiarOpcion('#selectEquipoSolucionCambioEquipo', '');
                            $('#inputObservacionesSolucionReparacionSinEquipo').val('');
                            $('#inputSerieSolucionCambioEquipo').val('');
                            $('#inputObservacionesSolucionCambioEquipo').val('');
                            file.limpiar('#evidenciasSolucionReparacionSinEquipo');
                            file.limpiar('#evidenciasSolucionCambioEquipo');
                            evento.mostrarMensaje('.errorFormularioSolucionReparacionConRefaccion', true, 'Datos Guardados Correctamente.', 3000);
                        }
                    } else {
                        file.limpiar('#evidenciasSolucionReparacionConRefaccion');
                        evento.mostrarMensaje('.errorFormularioSolucionReparacionConRefaccion', false, 'Debe guardar los datos del dignostico.', 5000);
                    }
                } else {
                    file.limpiar('#evidenciasSolucionReparacionConRefaccion');
                    evento.mostrarMensaje('.errorFormularioSolucionReparacionConRefaccion', false, 'Falta llenar los datos de Información General.', 5000);
                }
            });
        }
    };
    var guardarConcluirCorrectivoCambioEquipo = function () {
        var servicio = arguments[0];
        var datosTablaPoliza = arguments[1];
        var correctivosSoluciones = arguments[2];
        //Si es 1 solo guarda, si es 2 se concluye el servicio
        var operacion = arguments[3];
        var respuestaAnterior = arguments[4] || null;
        var idsInventario = arguments[5] || '';
        var dataEquipoInventario = arguments[6] || [];
        var equipo = $('#selectEquipoSolucionCambioEquipo').val();
        var serie = $('#inputSerieSolucionCambioEquipo').val();
        var observaciones = $('#inputObservacionesSolucionCambioEquipo').val();
        var data = {};
        var evidencias = '';
        var idTipoSolucion = '3';
        var usaStock = false;
        if (correctivosSoluciones !== null) {
            if (correctivosSoluciones.length > 0) {
                evidencias = correctivosSoluciones[0].Evidencias;
                idTipoSolucion = correctivosSoluciones[0].IdTipoSolucion;
            }
        }

        if ($("#data-table-reparacion-cambio-stock").length) {
            equipo = dataEquipoInventario['equipo'];
            serie = dataEquipoInventario['serie'];
            usaStock = true;
        }

        data = {
            servicio: servicio,
            equipo: equipo,
            serie: serie,
            observaciones: observaciones,
            evidencias: evidencias,
            idTipoSolucion: idTipoSolucion,
            operacion: operacion,
            ticket: datosTablaPoliza[1],
            idSolicitud: datosTablaPoliza[2],
            usaStock: usaStock,
            idsInventario: idsInventario
        };
        if ($('#evidenciasSolucionCambioEquipo').val() !== '') {
            file.enviarArchivos('#evidenciasSolucionCambioEquipo', 'Seguimiento/guardarCambioEquipo', '#seccion-servicio-correctivo', data, function (respuesta) {
                if (respuesta !== 'faltaDatosGenerales') {
                    if (respuesta !== 'faltaDatosDiagnostico') {
                        if (operacion === '2' && respuesta !== 'faltanServicios') {
                            var dataSD = {servicio: servicio, ticket: datosTablaPoliza[1], servicioConcluir: true};
                            evento.enviarEvento('Seguimiento/enviarSolucionCorrectivoSD', dataSD, '#seccion-servicio-correctivo', function (respuesta) {
                                if (respuesta.code === 200) {
                                    if (respuesta.message === 'serviciosConcluidos') {
                                        modalCampoFirma(respuesta.message, datosTablaPoliza[1], servicio, '.errorFormularioSolucionCambioEquipo', respuestaAnterior, true, '4');
                                    } else {
                                        concluirServicio(servicio);
                                    }
                                } else {
                                    servicios.mensajeModal(respuesta.message, 'ERROR SD', true);
                                }
                            });
                        } else {
                            select.cambiarOpcion('#selectSolucionReparacionSinEquipo', '');
                            $('#inputObservacionesSolucionReparacionSinEquipo').val('');
                            $('#inputObservacionesSolucionReparacionConRefaccion').val('');
                            tabla.limpiarTabla('#data-table-reparacion-refaccion');
                            file.limpiar('#evidenciasSolucionReparacionSinEquipo');
                            file.limpiar('#evidenciasSolucionReparacionConRefaccion');
                            evento.mostrarMensaje('.errorFormularioSolucionCambioEquipo', true, 'Datos Guardados Correctamente.', 3000);
                        }
                    } else {
                        file.limpiar('#evidenciasSolucionCambioEquipo');
                        evento.mostrarMensaje('.errorFormularioSolucionCambioEquipo', false, 'Debe guardar los datos del dignostico.', 5000);
                    }
                } else {
                    file.limpiar('#evidenciasSolucionCambioEquipo');
                    evento.mostrarMensaje('.errorFormularioSolucionCambioEquipo', false, 'Falta llenar los datos de Información General.', 5000);
                }
            });
        } else {
            evento.enviarEvento('Seguimiento/guardarCambioEquipo', data, '#seccion-servicio-correctivo', function (respuesta) {
                if (respuesta !== 'faltaDatosGenerales') {
                    if (respuesta !== 'faltaDatosDiagnostico') {
                        if (operacion === '2' && respuesta !== 'faltanServicios') {
                            var dataSD = {servicio: servicio, ticket: datosTablaPoliza[1], servicioConcluir: true};
                            evento.enviarEvento('Seguimiento/enviarSolucionCorrectivoSD', dataSD, '#seccion-servicio-correctivo', function (respuesta) {
                                if (respuesta.code === 200) {
                                    if (respuesta.message === 'serviciosConcluidos') {
                                        modalCampoFirma(respuesta.message, datosTablaPoliza[1], servicio, '.errorFormularioSolucionCambioEquipo', respuestaAnterior, true, '4');
                                    } else {
                                        concluirServicio(servicio);
                                    }
                                } else {
                                    servicios.mensajeModal(respuesta.message, 'ERROR SD', true);
                                }
                            });
                        } else {
                            select.cambiarOpcion('#selectSolucionReparacionSinEquipo', '');
                            $('#inputObservacionesSolucionReparacionSinEquipo').val('');
                            $('#inputObservacionesSolucionReparacionConRefaccion').val('');
                            tabla.limpiarTabla('#data-table-reparacion-refaccion');
                            file.limpiar('#evidenciasSolucionReparacionSinEquipo');
                            file.limpiar('#evidenciasSolucionReparacionConRefaccion');
                            evento.mostrarMensaje('.errorFormularioSolucionCambioEquipo', true, 'Datos Guardados Correctamente.', 3000);
                        }
                    } else {
                        file.limpiar('#evidenciasSolucionCambioEquipo');
                        evento.mostrarMensaje('.errorFormularioSolucionCambioEquipo', false, 'Debe guardar los datos del dignostico.', 5000);
                    }
                } else {
                    file.limpiar('#evidenciasSolucionCambioEquipo');
                    evento.mostrarMensaje('.errorFormularioSolucionCambioEquipo', false, 'Falta llenar los datos de Información General.', 5000);
                }
            });
        }

    };
    var concluirServicio = function () {
        var servicio = arguments[0];
        var dataConclusion = {servicio: servicio, estatus: '5'};
        evento.enviarEvento('Seguimiento/CambiarEstatus', dataConclusion, '#seccion-servicio-correctivo', function (respuesta) {
            servicios.mensajeModal('Servicio Concluido', 'Correcto');
        });
    };
    var modalCampoFirma = function () {
        var idCliente = '0';
        var respuesta = arguments[0];
        var ticket = arguments[1];
        var servicio = arguments[2];
        var idMensaje = arguments[3];
        var encargadosTI = arguments[4].informacion.listaCinemexValidadores;
        var concluirServicio = arguments[5] || false;
        var estatus = arguments[6] || false;
        var html = htmlCampoTecnicoFirma();
        if (arguments[4].informacion.idCliente !== null) {
            idCliente = arguments[4].informacion.idCliente[0].IdCliente;
            if (idCliente === '1') {
                html += '<div class="row" m-t-10">\n\
                        <div class="col-md-8 col-md-offset-2 col-xs-8 col-xs-offset-2">\n\
                            <div class="form-group">\n\
                                <label for="selectTI">Encargado TI *</label>\n\
                                <select id="selectTI" class="form-control" style="width: 100%" data-parsley-required="true">\n\
                                   <option value="">Seleccionar</option>\n\
                                </select>\n\
                            </div>\n\
                        </div>\n\
                    </div>';
            }
            evento.mostrarModal('Firma', servicios.modalCampoFirmaExtra(html, 'Firma'));
            $.each(encargadosTI, function (key, valor) {
                $("#selectTI").append('<option value=' + valor.Id + '>' + valor.Nombre + '</option>');
            });

            select.crearSelect('#selectTI');

            $('#btnModalConfirmar').addClass('hidden');
            $('#btnModalConfirmar').off('click');
            servicios.validarTecnicoPoliza();
            servicios.validarCamposFirma(ticket, servicio, true, concluirServicio, estatus);
        } else {
            var data = {servicio: servicio};
            evento.enviarEvento('/Generales/Servicio/ConsultaIdClienteSucursal', data, '#seccion-servicio-correctivo', function (respuesta) {
                idCliente = respuesta[0].IdCliente;
                if (idCliente === '1') {
                    html += '<div class="row" m-t-10">\n\
                        <div class="col-md-8 col-md-offset-2 col-xs-8 col-xs-offset-2">\n\
                            <div class="form-group">\n\
                                <label for="selectTI">Encargado TI *</label>\n\
                                <select id="selectTI" class="form-control" style="width: 100%" data-parsley-required="true">\n\
                                   <option value="">Seleccionar</option>\n\
                                </select>\n\
                            </div>\n\
                        </div>\n\
                    </div>';
                }
                evento.mostrarModal('Firma', servicios.modalCampoFirmaExtra(html, 'Firma'));
                $.each(encargadosTI, function (key, valor) {
                    $("#selectTI").append('<option value=' + valor.Id + '>' + valor.Nombre + '</option>');
                });

                select.crearSelect('#selectTI');

                $('#btnModalConfirmar').addClass('hidden');
                $('#btnModalConfirmar').off('click');
                servicios.validarTecnicoPoliza();
                servicios.validarCamposFirma(ticket, servicio, true, concluirServicio, estatus);
            });
        }

    };
    var htmlCampoTecnicoFirma = function () {
        var html = '<div class="row" m-t-10">\n\
                        <div id="divcampoLapizTecnico" class="col-md-12 text-center">\n\
                            <div id="campoLapizTecnico"></div>\n\
                        </div>\n\
                    </div>\n\
                    <div class="row m-t-20">\n\
                        <div class="col-md-12 text-center">\n\
                            <br>\n\
                            <label>Firma del técnico</label><br>\n\
                        </div>\n\
                    </div>\n\
                    <br>';
        return html;
    }

    var formularioAsignacionSolicitud = function () {
        var mensajeConfirmacion = '<div id="confirmarSolicitud"\n\
                                        <div class="row">\n\
                                            <div class="col-md-12">\n\
                                                <div class="form-group">\n\
                                                    <label for="selectAtiendeSolcitud">Confirmar y Asignar a *</label>\n\
                                                    <select id="selectAtiendeSolcitud" class="form-control" style="width: 100%" data-parsley-required="true" disabled>\n\
                                                       <option value="">Seleccionar</option>\n\
                                                    </select>\n\
                                                </div>\n\
                                            </div>\n\
                                            <div class="col-md-12">\n\
                                                <div class="errorAtiendeSolicitud"></div>\n\
                                            </div>\n\
                                        </div>\n\
                                </div> ';
        return mensajeConfirmacion;
    };
    var formularioPersonalAutoriza = function () {
        var formularioAutoriza = '<div id="formularioPersonalAutoriza">\n\
                                    <div class="row">\n\
                                        <div class="col-md-12">\n\
                                        <div class="form-group">\n\
                                            <label for="inputAutoriza">Personal que autoriza *</label>\n\
                                            <input type="text" class="form-control" id="inputAutoriza" style="width: 100%"/>\n\
                                        </div>\n\
                                        </div>\n\
                                    </div>\n\
                                    <div class="row">\n\
                                        <div class="col-md-12">\n\
                                            <div class="form-group">\n\
                                                <label for="evidenciasAutorizacion">Evidencias de Autorización *</label>\n\
                                                <input id="evidenciasAutorizacion"  name="evidenciasAutorizacion[]" type="file" multiple/>\n\
                                            </div>\n\
                                        </div>\n\
                                    </div>\n\
                                    <div class="row">\n\
                                        <div class="col-md-12">\n\
                                            <div class="errorAtorizacion"></div>\n\
                                        </div>\n\
                                        <div class="col-md-12 text-center">\n\
                                            <button type="button" class="btn btn-sm btn-primary" id="btnGuardarAutorizacion"><i class="fa fa-save"></i> Guardar</button>\n\
                                            <button type="button" class="btn btn-sm btn-default" id="btnCerrarAutorizacion"><i class="fa fa-times"></i> Cerrar</button>\n\
                                        </div>\n\
                                    </div>\n\
                                </div> ';
        return formularioAutoriza;
    };
    var vistaDetallesSolicitud = function () {
        var datosTablaDetallesSolicitud = arguments[0];
        var tipoAlerta = '';
        var textoSolicitud = '';
        if (datosTablaDetallesSolicitud[0] !== undefined) {
            switch (datosTablaDetallesSolicitud[0][4]) {
                case'ABIERTO':
                    tipoAlerta = 'warning';
                    textoSolicitud = 'Su Solicitud aún no ha sido atendida por el Usuario. Porfavor póngase en contacto con el área correspondiente.'
                    break;
                case 'EN ATENCIÓN':
                    tipoAlerta = 'info';
                    textoSolicitud = 'Su Solicitud está siendo atendida por el <strong>Encargado de Refacciones</strong>.'
                    break;
                case 'CONCLUIDO':
                    tipoAlerta = 'success';
                    textoSolicitud = 'Se han entregado las refacciones solicitadas.'
                    break;
                case 'EN TRÁNSITO':
                    tipoAlerta = 'info';
                    textoSolicitud = 'Su Solicitud está siendo antendida por el <strong>Departamento de Logística</strong>.'
                    break;
                default:
            }

            var vistaSolicitud = '<div class="row">\n\
                                <div class="col-sm-6 col-md-6">\n\
                                    <div class="form-group">\n\
                                        <label> Solicita: <strong>' + datosTablaDetallesSolicitud[0][1] + '</strong></label>\n\
                                    </div>\n\
                                </div>\n\
                                <div class="col-sm-6 col-md-6">\n\
                                    <div class="form-group">\n\
                                        <label> Fecha de Solicitud: <strong>' + datosTablaDetallesSolicitud[0][2] + '</strong></label>\n\
                                    </div>\n\
                                </div>\n\
                            </div>\n\
                            <div class="table-responsive">\n\
                                <table id="data-table-detalles-solicitud" class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%">\n\
                                    <thead>\n\
                                        <tr>\n\
                                            <th class="never">Id</th>\n\
                                            <th class="all">Refacción</th>\n\
                                            <th class="all">Cantidad</th>\n\
                                        </tr>\n\
                                    </thead>\n\
                                    <tbody>\n\
                                    </tbody>\n\
                                </table>\n\
                            </div>\n\
                            <div class="alert alert-' + tipoAlerta + ' fade in m-t-15">\n\
                                Solicitud <strong>' + datosTablaDetallesSolicitud[0][4] + '!</strong>\n\
                                ' + textoSolicitud + '\n\
                            </div>\n\
                            <div class="row">\n\
                                <div class="col-md-12 m-t-20">\n\
                                    <div class="alert alert-warning fade in m-b-15">\n\
                                        Para eliminar el registro de la tabla solo tiene que dar click sobre fila para eliminarlo.\n\
                                    </div>\n\
                                </div>\n\
                            </div>';
            return vistaSolicitud;
        }
    };
    var validarSolitud = function () {
        var tabla = arguments[0];
        var id = arguments[1];
        var divError = arguments[2];
        var filas = $(tabla).DataTable().rows().data();
        var repetido = false;
        if (filas.length > 0) {
            for (var i = 0; i < filas.length; i++) {
                if ($.trim(filas[i][0]) === id) {
                    repetido = true;
                }
            }
        }

        if (!repetido) {
            return true;
        } else {
            evento.mostrarMensaje(divError, false, 'Ya se agregó ese valor, favor de eliminar el que esta registrado si quiere actualizarlo', 4000);
        }
    };
    var modalCampoFirmaSolicitud = function () {
        var textoExtra = arguments[0];
        var campoInput = arguments[1];
        $('#btnModalAbortar').removeClass('hidden');
        var html = ' <div id="campo_firma_poliza">';
        html += textoExtra;
        html += '        <form class="margin-bottom-0" id="formFirmaPoliza" data-parsley-validate="true">\n\
                            <div class="row m-t-10">\n\
                                <div class="col-md-8 col-md-offset-2 col-xs-8 col-xs-offset-2">\n\
                                    <div class="form-group">\n\
                                        <label for="inputRecibeFirma">' + campoInput + ' *</label>\n\
                                        <input type="text" class="form-control" id="inputRecibeFirma" style="width: 100%" data-parsley-required="true"/>\n\
                                    </div>\n\
                                </div>\n\
                            </div>\n\
                        </form>\n\
                        <div class="row">\n\
                            <div class="col-md-8 col-md-offset-2 col-xs-8 col-xs-offset-2">\n\
                                <div class="form-group">\n\
                                    <label id="campoCorreo">Correo(s) *</label>\n\
                                    <ul id="tagValor" class="inverse"></ul>\n\
                                </div>\n\
                            </div>\n\
                        </div>\n\
                        <div class="row">\n\
                                <div id="col-md-12 text-center">\n\
                                    <div id="campoLapiz"></div>\n\
                                </div>\n\
                        </div>';
        html += '           <div class="row m-t-20">\n\
                            <div class="col-md-12 text-center">\n\
                                <br>\n\
                                <label><input type="checkbox" id="terminos" value="first_checkbox"> He leído y acepto los <a href="">Términos de Uso</a> y <a href="">Declaración de Privacidad</a> para SICCOB</label><br>\n\
                            </div>\n\
                        </div>';
        html += '           <div class="row m-t-10">\n\
                            <div class="col-md-12">\n\
                                <div class="errorFirma"></div>\n\
                            </div>\n\
                        </div>\n\
                        <div class="row m-t-20">\n\
                            <div class="col-md-12 text-center">\n\
                                <button id="btnGuardarFirma" type="button" class="btn btn-sm btn-primary"><i class="fa fa-save"></i> Guardar</button>\n\
                            </div>\n\
                        </div>\n\
                </div>';
        return html;
    };
    var validarCamposFirma = function () {
        var data = arguments[0];
//        var myBoard = null;
//        var ancho = Math.max(document.documentElement.clientWidth, window.innerWidth || 0);
//        var alto = Math.max(document.documentElement.clientHeight, window.innerHeight || 0);
        var myBoard = new DrawingBoard.Board('campoLapiz', {
            background: "#fff",
            color: "#000",
            size: 1,
            controlsPosition: "right",
            controls: [
                {Navigation: {
                        back: false,
                        forward: false
                    }
                }
            ],
            webStorage: false
        });
        $("#tagValor").tagit({
            allowSpaces: false
        });
        myBoard.ev.trigger('board:reset', 'what', 'up');
//        $(window).resize(function () {
//            servicios.ajusteCanvasFirma()
//            myBoard = servicios.campoLapiz('campoLapiz');
//        });
//
//        var arrayMedidas = servicios.ajusteCanvasMedidas(ancho, alto);
//
//        $('#campoLapiz').css({"margin": "0 auto", "width": arrayMedidas[0] + "px", "height": arrayMedidas[1] + "px"});
//
//        myBoard = servicios.campoLapiz('campoLapiz');

        $('#btnGuardarFirma').on('click', function () {
            var img = myBoard.getImg();
            var imgInput = (myBoard.blankCanvas == img) ? '' : img;
            var correo = $("#tagValor").tagit("assignedTags");
            if (imgInput !== '') {
                if ($('#terminos').attr('checked')) {
                    if (correo.length > 0) {
                        if (servicios.validarCorreoArray(correo)) {
                            var recibe = $('#inputRecibeFirma').val();
                            switch (data.operacion) {
                                case'1':
                                    var equipo = $("#selectEquipoRespaldo").val();
                                    var equipoRetirado = $("#selectEquipoCorrectivo").val();
                                    var sucursal = $("#selectSucursalesCorrectivo").val();
                                    var dataRetiroGarantiaRespaldo = {
                                        servicio: data.servicio,
                                        ticket: data.ticket,
                                        equipo: equipo,
                                        serie: data.serie,
                                        equipoRetirado: equipoRetirado,
                                        serieRetirado: data.serieRetirado,
                                        correo: correo,
                                        recibe: recibe,
                                        operacion: data.operacion,
                                        img: img,
                                        sucursal: sucursal};
                                    if (evento.validarFormulario('#formFirmaPoliza')) {
                                        guardarInformacionRespaldo(dataRetiroGarantiaRespaldo);
                                    }
                                    break;
                                case '2':
                                    var equipo = $("#selectEquipoCorrectivo").val();
                                    var recibe = $('#selectEntregaGarantia').val();
                                    var sucursal = $("#selectSucursalesCorrectivo").val();
                                    var dataAcuseEntrega = {
                                        servicio: data.servicio,
                                        ticket: data.ticket,
                                        equipo: equipo,
                                        serie: data.serie,
                                        correo: correo,
                                        recibe: recibe,
                                        operacion: data.operacion,
                                        img: img,
                                        sucursal: sucursal};
                                    if (validarCampos($('#selectEntregaGarantia').val(), '.errorFirma', 'Debes seleccionar aquien se entrega.')) {
                                        guardarEntregaEquipoGarantia(dataAcuseEntrega);
                                    }
                                    break;
                                case '3':
                                    var equipo = $("#selectEquipoCorrectivo").val();
                                    var sucursal = $("#selectSucursalesCorrectivo").val();
                                    var dataAcuseEntregaTI = {
                                        servicio: data.servicio,
                                        ticket: data.ticket,
                                        equipo: equipo,
                                        serie: data.serie,
                                        correo: correo,
                                        recibe: recibe,
                                        operacion: data.operacion,
                                        img: img,
                                        sucursal: sucursal};
                                    if (evento.validarFormulario('#formFirmaPoliza')) {
                                        guardarEntregaTIGarantia(dataAcuseEntregaTI);
                                    }
                                    break;
                                default:
                            }

                        } else {
                            evento.mostrarMensaje('.errorFirma', false, 'Algun Correo no es correcto.', 3000);
                        }
                    } else {
                        evento.mostrarMensaje('.errorFirma', false, 'Debe insertar al menos un correo.', 3000);
                    }
                } else {
                    evento.mostrarMensaje('.errorFirma', false, 'Debes aceptar los Terminos y Declaración de Privacidad.', 4000);
                }
                myBoard.clearWebStorage();
                return img;
            } else {
                evento.mostrarMensaje('.errorFirma', false, 'Debes llenar el campo Firma.', 3000);
            }
        });
    };
    // funciones Generales
    var mensajeConfirmacionModal = function () {
        var mensajeConfirmacion = '<div class="row">\n\
                            <div class="col-md-12 text-center">\n\
                                <p>¿Estas seguro de querer eliminar este registro?</p>\n\
                            </div>\n\
                        </div>\n\
                        <div class="row">\n\
                            <div class="col-md-12 text-center">\n\
                                <button type="button" class="btn btn-sm btn-success" id="btnAceptarGuardarCambios"><i class="fa fa-check"></i> Aceptar</button>\n\
                                <button type="button" class="btn btn-sm btn-danger" id="btnCancelarGuardarCambios"><i class="fa fa-times"></i> Cancelar</button>\n\
                            </div>\n\
                        </div> ';
        return mensajeConfirmacion;
    };
    var eliminarFilaTablaDetallesSolicitud = function () {
        var idSolicitud = arguments[0];
        var tipoSolicitud = arguments[1];
        var servicio = arguments[2];
        var ticket = arguments[3];
        var mensaje = mensajeConfirmacionModal();
        evento.mostrarModal('Advertencia', mensaje);
        $('#btnModalConfirmar').addClass('hidden');
        $('#btnModalAbortar').addClass('hidden');
        $('#btnAceptarGuardarCambios').on('click', function () {
            evento.cerrarModal();
            var data = {servicio: servicio, idSolicitud: idSolicitud, tipoSolicitud: tipoSolicitud, ticket: ticket};
            evento.enviarEvento('Seguimiento/EliminarDetallesSolicitud', data, '#modal-dialogo', function (respuesta) {
                if (respuesta instanceof Array) {
                    if (tipoSolicitud === 'refaccion') {
                        recargandoTablaSolicitudRefaccion(respuesta);
                    } else {
                        recargandoTablaSolicitudEquipo(respuesta);
                    }
                    evento.mostrarMensaje('.errorTablaSolicitudes', true, 'Solicitud eliminada correctamente', 3000);
                } else {
                    evento.mostrarMensaje('.errorTablaSolicitudes', false, 'Hubo un error contacte al Área correspondiente', 3000);
                }
            });
        });
        $('#btnCancelarGuardarCambios').on('click', function () {
            evento.cerrarModal();
        });
    };
    // catalogo Poliza equipo almacen y laborario
    //Fecha y hora
    $('#fechaValidacion').datetimepicker({
        format: 'YYYY-MM-DD HH:mm:ss'
    });
    $('#fechaEnvio').datetimepicker({
        format: 'YYYY-MM-DD HH:mm:ss'
    });
    $('#fechaRecepcionAlmacen').datetimepicker({
        format: 'YYYY-MM-DD HH:mm:ss'
    });
    $('#fechaRecepcionLab').datetimepicker({
        format: 'YYYY-MM-DD HH:mm:ss'
    });
    $('#fechaRecepcionLogistica').datetimepicker({
        format: 'YYYY-MM-DD HH:mm:ss'
    });
    $('#fechaRecepcion').datetimepicker({
        format: 'YYYY-MM-DD HH:mm:ss'
    });
    $('#fechaRecepcionTecnico').datetimepicker({
        format: 'YYYY-MM-DD HH:mm:ss'
    });
    //obtener valor fecha
    $("#fechaValidacion").find("input").val();
    $("#fechaEnvio").find("input").val();
    $("#fechaRecepcionAlmacen").find("input").val();
    $("#fechaRecepcionLab").find("input").val();
    $("#fechaRecepcionLogistica").find("input").val();
    $("#fechaRecepcion").find("input").val();
    $("#fechaRecepcionTecnico").find("input").val();
    //radio inputs valor
    $('input:radio[name=optionsRadios]:checked').val();
    //tablas
    tabla.generaTablaPersonal('#lista-equipos-enviados-solicitados', null, null, true, true, [[0, 'desc']]);
    tabla.generaTablaPersonal('#listaRefaccionUtilizada', null, null, true, true, [[0, 'desc']]);
    //Iniciar input archivos
    file.crearUpload('#archivosProblemaGuia', 'Seguimiento/subirProblema');
    file.crearUpload('#evidenciaEnvio', 'Seguimiento/subirEvidenciaEnvio');
    file.crearUpload('#evidenciaRecepcionAlmacen', 'Seguimiento/subirEvidenciaRecepcion');
    file.crearUpload('#evidenciaRecepcionLab', 'Seguimiento/subirEvidenciaRecepcion');
    file.crearUpload('#archivosLabHistorial', 'Seguimiento/subirAdjuntosLabHistorial');
    file.crearUpload('#evidenciaRecepcionLogistica', 'Seguimiento/subirAdjuntosLabHistorial');
    file.crearUpload('#evidenciaEntrega', 'Seguimiento/subirAdjuntosLabHistorial');
    file.crearUpload('#evidenciaRecepcionTecnico', 'Seguimiento/subirAdjuntosLabHistorial');
});
var eventoAuxiliar;
var tablaAuxiliar;
var servicioAuxiliar;
var eventoEliminarProblemaAdicional = function () {
    var id = arguments[0];
    var servicio = arguments[1];
    var mensaje = '<div class="row">\n\
                            <div class="col-md-12 text-center">\n\
                                <p>¿Estas seguro de querer eliminar este registro?</p>\n\
                            </div>\n\
                        </div>\n\
                        <div class="row">\n\
                            <div class="col-md-12 text-center">\n\
                                <button type="button" class="btn btn-sm btn-success" id="btnAceptarGuardarCambios"><i class="fa fa-check"></i> Aceptar</button>\n\
                                <button type="button" class="btn btn-sm btn-danger" id="btnCancelarGuardarCambios"><i class="fa fa-times"></i> Cancelar</button>\n\
                            </div>\n\
                        </div> ';
    eventoAuxiliar.mostrarModal('Advertencia', mensaje);
    $('#btnModalConfirmar').addClass('hidden');
    $('#btnModalAbortar').addClass('hidden');
    $('#btnAceptarGuardarCambios').on('click', function () {
        eventoAuxiliar.cerrarModal();
        var dataProblemaAdicional = {servicio: servicio, id: id};
        eventoAuxiliar.enviarEvento('Seguimiento/Eliminar_ProblemaAdicional', dataProblemaAdicional, '#seccion-servicio-mantemient', function (respuesta) {
            recargandoTablaProblemasAdicionales(respuesta, 'Datos Eliminados correctamente.', '.errorFormularioProblemasAdicionales');
        });
    });
    $('#btnCancelarGuardarCambios').on('click', function () {
        eventoAuxiliar.cerrarModal();
    });
};
var datosNuevosTablaProblemasAdicionales = function () {
    var columnas = [
        {data: 'Sucursal'},
        {data: 'Punto',
            render: function (data, type, row, meta) {
                if (data === '0') {
                    data = '-';
                }
                return data;
            }
        },
        {data: 'Descripcion'},
        {data: null,
            sClass: 'Evidencias',
            render: function (data, type, row, meta) {
                var evidencias = data.Evidencias.split(',');
                var filas = [];
                $.each(evidencias, function (key, valor) {
                    filas.push(['<a href="' + valor + '" target="_blank"> <img src="' + valor + '" title="" style="max-height:150px"/> </a>']);
                });
                return filas;
            }
        },
        {data: 'Id'},
        {data: null,
            sClass: 'Acciones',
            render: function (data, type, row, meta) {
                return '<a id="btnEliminarProblemaAdicional' + row.Id + '" onclick="eventoEliminarProblemaAdicional(' + row.Id + ',' + row.IdServicio + ');" class="btn btn-danger btn-xs "><i class="fa fa-trash-o"></i> Eliminar</a> </a>';
            }}
    ];
    return columnas;
};
var recargandoTablaProblemasAdicionales = function () {
    var respuesta = arguments[0];
    var mensaje = arguments[1];
    var divError = arguments[2];
    var columnas = datosNuevosTablaProblemasAdicionales();
    tablaAuxiliar.limpiarTabla('#data-table-problemas-adicionales');
    tablaAuxiliar.generaTablaPersonal('#data-table-problemas-adicionales', respuesta, columnas, true, null, [[0, 'desc']]);
    eventoAuxiliar.mostrarMensaje(divError, true, mensaje, 3000);
};
var eventoEliminarProblemaEquipo = function () {
    var area = arguments[0];
    var modelo = arguments[1];
    var punto = arguments[2];
    var servicio = arguments[3];
    var mensaje = '<div class="row">\n\
                            <div class="col-md-12 text-center">\n\
                                <p>¿Estas seguro de querer eliminar este registro?</p>\n\
                            </div>\n\
                        </div>\n\
                        <div class="row">\n\
                            <div class="col-md-12 text-center">\n\
                                <button type="button" class="btn btn-sm btn-success" id="btnAceptarGuardarCambios"><i class="fa fa-check"></i> Aceptar</button>\n\
                                <button type="button" class="btn btn-sm btn-danger" id="btnCancelarGuardarCambios"><i class="fa fa-times"></i> Cancelar</button>\n\
                            </div>\n\
                        </div> ';
    eventoAuxiliar.mostrarModal('Advertencia', mensaje);
    $('#btnModalConfirmar').addClass('hidden');
    $('#btnModalAbortar').addClass('hidden');
    $('#btnAceptarGuardarCambios').on('click', function () {
        eventoAuxiliar.cerrarModal();
        var dataProblemaAdicional = {servicio: servicio, area: area, modelo: modelo, punto: punto};
        eventoAuxiliar.enviarEvento('Seguimiento/Eliminar_ProblemaEquipo', dataProblemaAdicional, '#seccion-servicio-mantemiento-puntos-censados', function (respuesta) {
            recargandoTablaFallasEquipo(respuesta, 'Datos Eliminados correctamente.', '#errorGuardarEquipo');
        });
    });
    $('#btnCancelarGuardarCambios').on('click', function () {
        eventoAuxiliar.cerrarModal();
    });
};
var recargandoTablaFallasEquipo = function () {
    var respuesta = arguments[0];
    var mensaje = arguments[1];
    var divError = arguments[2];
    var columnas = datosNuevosTablaFallasEquipo();
    tablaAuxiliar.limpiarTabla('#data-table-problemas-equipo');
    tablaAuxiliar.generaTablaPersonal('#data-table-problemas-equipo', respuesta, columnas, true, null, [[0, 'desc']]);
    eventoAuxiliar.mostrarMensaje(divError, true, mensaje, 3000);
};
var datosNuevosTablaFallasEquipo = function () {
    var columnas = [
        {data: 'Area'},
        {data: 'Punto'},
        {data: 'Equipo'},
        {data: 'Observaciones'},
        {data: null,
            sClass: 'Evidencias',
            render: function (data, type, row, meta) {
                var evidencias = data.Evidencias.split(',');
                var filas = [];
                $.each(evidencias, function (key, valor) {
                    filas.push(['<a href="' + valor + '" target="_blank"> <img src="' + valor + '" title="" style="max-height:150px"/> </a>']);
                });
                return filas;
            }
        },
        {data: null,
            sClass: 'Acciones',
            render: function (data, type, row, meta) {
                return '<a onclick="eventoEliminarProblemaEquipo(' + row.IdArea + ', ' + row.IdModelo + ', ' + row.Punto + ', ' + row.IdServicio + ');" class="btn btn-danger btn-xs "><i class="fa fa-trash-o"></i> Eliminar</a> </a>';
            }},
        {data: 'IdArea'},
        {data: 'IdModelo'},
        {data: 'IdServicio'}
    ];
    return columnas;
};
