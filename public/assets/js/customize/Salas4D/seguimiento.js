$(function () {

    var evento = new Base();
    var websocket = new Socket();
    var tabla = new Tabla();
    var servicios = new Servicio();
    var select = new Select();
    var file = new Upload();
    var nota = new Nota();
    var _padre = [];
    //Evento que maneja las peticiones del socket
    websocket.socketMensaje();

    //Muestra la hora en el sistema
    evento.horaServidor($('#horaServidor').val());

    //Evento para cerra la session
    evento.cerrarSesion();

    //Creando tabla de areas
    tabla.generaTablaPersonal('#data-table-salasX4D', null, null, true, true, [[0, 'desc']]);

    //Evento para mostrar la ayuda del sistema
    evento.mostrarAyuda('Ayuda_Proyectos');

    //Inicializa funciones de la plantilla
    App.init();

    //Evento que carga la seccion de seguimiento de un servicio de tipo Logistica
    $('#data-table-salasX4D tbody').on('click', 'tr', function () {
        var datos = $('#data-table-salasX4D').DataTable().row(this).data();
        
        if (datos !== undefined) {
            var servicio = datos[0];
            var operacion = datos[7];
            
            if (operacion === '1') {
                var html = '<div class="row">\n\
                            <div id="mensaje-modal" class="col-md-12 text-center">\n\
                                <h3>¿Quieres atender el servicio?</h3>\n\
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

                $('#btnIniciarServicio').on('click', function () {
                    var data = {servicio: servicio, operacion: '1'};
                    evento.enviarEvento('Seguimiento/Servicio_Datos', data, '#modal-dialogo', function (respuesta) {
                        evento.cerrarModal();
                        data = {servicio: servicio, operacion: '2'};
                        cargarFormularioSeguimiento(data, datos);
                        recargandoTablaSalasX4D(respuesta.informacion);
                    });
                });

                //Envento para concluir con la cancelacion
                $('#btnCancelarIniciarServicio').on('click', function () {
                    evento.cerrarModal();
                });

            } else if (operacion === '2' || operacion === '12' || operacion === '10') {
                var data = {servicio: servicio, operacion: '2'};
                cargarFormularioSeguimiento(data, datos, '#panelSeguimientoSalasX4D');
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
            var idSucursal = datosDelServicio.IdSucursal;
            var datosSD = respuesta.datosSD;

            if (datosDelServicio.tieneSeguimiento === '0') {
                servicios.ServicioSinClasificar(
                        formulario,
                        '#listaSalasX4D',
                        '#seccionSeguimientoServicio',
                        datosTabla[0],
                        datosDelServicio,
                        'Seguimiento',
                        archivo,
                        '#panelSeguimientoRedes',
                        datosTabla[1],
                        avanceServicio,
                        idSucursal,
                        datosSD
                        );
            } else {
                switch (datosDelServicio.IdTipoServicio) {
                    //Servicio Mantenimiento Preventivo
                    case '6':
                        iniciarElementosPaginaSeguimientoMantenimiento(respuesta, datosTabla);
                        eventosParaSeccionSeguimientoMantenimiento(datosTabla, respuesta);
                        cargaJsonActividadesSeguimientoMantenimiento(datosTabla[0]);
                }
            }
        });
    };

    var recargandoTablaSalasX4D = function (informacionServicio) {
        tabla.limpiarTabla('#data-table-salasX4D');
        $.each(informacionServicio.serviciosAsignados, function (key, item) {
            tabla.agregarFila('#data-table-salasX4D', [item.Id, item.IdSolicitud, item.Ticket, item.Servicio, item.FechaCreacion, item.Descripcion, item.NombreEstatus, item.IdEstatus, item.Folio]);
        });
    };

    var iniciarElementosPaginaSeguimientoMantenimiento = function (respuesta, datosTabla ) {
        $('#listaSalasX4D').addClass('hidden');
        $('#seccionSeguimientoServicio').removeClass('hidden').empty().append(respuesta.formulario);
        select.crearSelect('#selectSucursalesPreventivo');
        tabla.generaTablaPersonal('#data-table-actividades-asignadas', null, null, true, true, [[1, 'asc']]);

        $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
            var target = $(e.target).attr("href") // activated tab
            if (target == "#AsignacionActividades") {
                cargaActividadesAsignacion(datosTabla[0]);
            }
        });

        if (respuesta.informacion.sucursal !== null) {
            select.cambiarOpcion('#selectSucursalesPreventivo', respuesta.informacion.sucursal);
        }

        if (respuesta.informacion.permisoActividades) {
            $('[href=#DefinicionActividades]').parent('li').removeClass('hidden');
            $('[href=#AsignacionActividades]').parent('li').removeClass('hidden');
        }
    };

    function cargaActividadesAsignacion() {
        var data = {
            'servicio': arguments[0],
            'idsPadre': _padre
        };
        $("#AsignacionActividades > .panel-body").empty();
        evento.enviarEvento('Seguimiento/cargarActividadesSeguimiento', data, '#seccion-servicio-mantto-salas', function (respuesta) {
            $("#AsignacionActividades > .panel-body").empty().append(respuesta.formulario);
            select.crearSelect('select');
            $('.btn-reabrir-actividad').off('click');
            $('.btn-reabrir-actividad').on('click', function () {
                var idActividad = $(this).attr('data-id-actividad');
                reasignarAsignaciondeActividadesSeguimiento(idActividad);
            });
            $('.btn-guardar-actividad').off('click');
            $('.btn-guardar-actividad').on('click', function () {
                var idActividadAsignada = $(this).attr('data-guardar-actividad');
                guardarAsignaciondeActividadesSeguimiento(idActividadAsignada);
            });
            //informacion
            $('.mostrar-informe').off('click');
            $('.mostrar-informe').on('click', function () {
                var idActi = $(this).attr('data-informe');
                var intActi = [idActi];
                var idServicio = $(this).attr('data-servicio');
                var idServicio = [idServicio];
                var dato = {'idActividad': intActi, "idServicio" : idServicio};
                evento.enviarEvento('Seguimiento/InformeActividades', dato, '#AsigActividades', function (respuesta) {
                    $('#informacion-actividades').empty().append(respuesta.informe);
                    $('#AsigActividades').fadeOut(400, function () {
                        $('#informacion-actividades').fadeIn(400);
                    });

                    //Boton regresar
                    $('#informacion-actividades #btnRegresar').off('click');
                    $('#informacion-actividades #btnRegresar').on('click', function () {
                        $("#informacion-actividades").fadeOut(400, function () {
                            $("#AsigActividades").fadeIn(400, function () {
                                $("#informacion-actividades").empty();
                            });
                        });
                    });

                    if (!respuesta.datos['actividad'].length) {
                        evento.mostrarMensaje('#errorInforme', false, 'No existe un informe', 3000);
                    } 
                });
            });
        });
    }

    // oculta y muestra las opciones 
    var eventosParaSeccionSeguimientoMantenimiento = function () {
        var datosTabla = arguments[0];
        var respuesta = arguments[1];
        var servicio = datosTabla[0];

        $('#detallesServicioPreventivoSalas4xd').off('click');
        $('#detallesServicioPreventivoSalas4xd').on('click', function (e) {
            if ($('#masDetalles').hasClass('hidden')) {
                $('#masDetalles').removeClass('hidden');
                $('#detallesServicioPreventivoSalas4xd').empty().html('<a>- Detalles</a>');
            } else {
                $('#masDetalles').addClass('hidden');
                $('#detallesServicioPreventivoSalas4xd').empty().html('<a>+ Detalles</a>');
            }
        });

        $('#btnGuardarDatosPreventivoSalas4xd').off('click');
        $('#btnGuardarDatosPreventivoSalas4xd').on('click', function () {
            if ($('#selectSucursalesPreventivo').val() !== '') {
                var sucursal = $('#selectSucursalesPreventivo').val();
                var data = {servicio: servicio, sucursal: sucursal};
                evento.enviarEvento('Seguimiento/Guardar_Datos_Generales', data, '#seccion-servicio-mantto-salas', function (respuesta) {
                    if (respuesta === true) {
                        evento.mostrarMensaje('#errorDatosPreventivoSalas4xd', true, 'Datos guardados Correctamente.', 3000);
                    } else {
                        evento.mostrarMensaje('#errorDatosPreventivoSalas4xd', false, 'Contacte al Área correspondiente.', 3000);
                    }
                });
            } else {
                evento.mostrarMensaje('#errorDatosPreventivoSalas4xd', false, 'Seleccione una Sucursal.', 3000);
            }
        });

        $('#btnGuardarActividades').off('click');
        $('#btnGuardarActividades').on('click', function () {
            guardarDatosseguimiento(respuesta, datosTabla);
        });

        $('#btnGuardarActividadesAsignadas').off('click');
        $('#btnGuardarActividadesAsignadas').on('click', function () {
            guardarAsignaciondeActividadesSeguimiento(respuesta, datosTabla);
            cargarActividadesUsuarios();
        });

        $('#btnGuardarActividadesAsignadas').off('click');
        $('#btnGuardarActividadesAsignadas').on('click', function () {
            guardarAsignaciondeActividadesSeguimiento(respuesta, datosTabla);
            cargarActividadesUsuarios();
        });

        //Evento que vuelve a mostrar la lista de servicios de Salas 4XD
        $('#btnRegresarSeguimientoMantenimientoSalas').off('click');
        $('#btnRegresarSeguimientoMantenimientoSalas').on('click', function () {
            $('#seccionSeguimientoServicio').empty().addClass('hidden');
            $('#listaSalasX4D').removeClass('hidden');
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
                    '#seccion-servicio-mantto-salas',
                    'Seguimiento/Servicio_Nuevo'
                    );
        });

        //Encargado de concluir servicio con firma
        $('#btnconcluirServicio').off('click');
        $('#btnconcluirServicio').on('click', function () {
            var data = {servicio : servicio };
            modalConcluirServicio();
        });

    var modalConcluirServicio = function(){
       var ticket = datosTabla[1];
        servicios.mostrarModal('Firma',servicios.formConcluirServicio());
        $('#btnModalConfirmar').addClass('hidden');
        var myBoardFirma = new DrawingBoard.Board('campoFirma', {
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
        $("#tagCorreo").tagit({
            allowSpaces: false
        });
        myBoardFirma.ev.trigger('board:reset', 'what', 'up');
    
            $('#btnConcluirServicio').off('click');
            $('#btnConcluirServicio').on('click',function(){
                
                var img = myBoardFirma.getImg();
                var imgInput = (myBoardFirma.blankCanvas == img) ? '' : img;
                if(evento.validarFormulario('#formConcluirServicioFirma')){
                   var personaRecibe = $('#inputPersonaRecibe').val();
                   var correo = $("#tagCorreo").tagit("assignedTags");
                   if (correo.length > 0) {
                       if(servicios.validarCorreoArray(correo)){
                           if(imgInput !== ''){
                               if ($('#terminos').attr('checked')) {
                                   var dataInsertar = {ticket: ticket, servicio: servicio, img : img, correo : correo, nombreFirma : personaRecibe};
                                   evento.enviarEvento('Seguimiento/concluirServicoFirma', dataInsertar, '#modal-concluir-servicio', function (respuesta){
                                       if(respuesta){
                                           servicios.mensajeModal('Servicio concluido.', 'Correcto');
                                       }else{
                                           evento.mostrarMensaje('.errorConcluirServicio', false, 'Tienes actividades sin concluir', 3000);
                                       }
                                   });
                               }else{
                                   evento.mostrarMensaje('.errorConcluirServicio', false, 'Debes aceptar terminos', 3000);
                               }
                           }else{
                              evento.mostrarMensaje('.errorConcluirServicio', false, 'Debes llenar el campo Firma de conformidad.', 3000); 
                           }
                       }else{
                           evento.mostrarMensaje('.errorConcluirServicio', false, 'Algun Correo no es correcto.', 3000);

                       }
                   }else{
                       evento.mostrarMensaje('.errorConcluirServicio', false, 'Debe insertar al menos un correo.', 3000);
                   }
                }
            });
    }

        //Encargado de concluir servicio
        $('#btnGeneralConcluirservicio').off('click');
        $('#btnGeneralConcluirservicio').on('click', function () {
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
            evento.enviarEvento('Seguimiento/Servicio_ToPdf', data, '#seccion-servicio-mantto-salas', function (respuesta) {
                window.open('/' + respuesta.link);
            });
        });

        //Evento que carga la seccion de seguimiento de un servicio de tipo Logistica
        $('#data-table-actividades-asignadas tbody').on('click', 'tr', function () {
            var datos = $('#data-table-actividades-asignadas').DataTable().row(this).data();
            var data = {servicio: servicio};
            evento.enviarEvento('Seguimiento/VerificarSucursal', data, '#seccion-servicio-mantto-salas', function (respuesta) {
                if (respuesta) {
                    if (datos[5] === 'ABIERTO') {
                        var html = '<div class="row">\n\
                            <div id="mensaje-modal" class="col-md-12 text-center">\n\
                                <h3>¿Quieres atender la actividad?</h3>\n\
                            </div>\n\
                      </div>';
                        html += '<div class="row m-t-20">\n\
                                <div class="col-md-12 text-center">\n\
                                    <button id="btnIniciarActividad" type="button" class="btn btn-sm btn-success"><i class="fa fa-check"></i> Aceptar</button>\n\
                                    <button id="btnCancelarIniciarActividad" type="button" class="btn btn-sm btn-danger"><i class="fa fa-times"></i> Cerrar</button>\n\
                                </div>\n\
                            </div>';
                        $('#btnModalConfirmar').addClass('hidden');
                        $('#btnModalAbortar').addClass('hidden');
                        evento.mostrarModal('Iniciar Actividad', html);
                        $('#btnModalConfirmar').empty().append('Eliminar');
                        $('#btnModalConfirmar').off('click');

                        $('#btnIniciarActividad').on('click', function () {
                            evento.cerrarModal();
                            seguimientoActividad(servicio, datos);
                        });

                        //Envento para concluir con la cancelacion
                        $('#btnCancelarIniciarActividad').on('click', function () {
                            evento.cerrarModal();
                        });
                    } else {
                        seguimientoActividad(servicio, datos);
                    }
                } else {
                    servicios.mensajeModal('Debe guardar la Sucursal. (Pestaña: Información General)', 'Advertencia', true);
                }
            });
        });

        servicios.initBotonReasignarServicio(servicio, datosTabla[1], '#seccion-servicio-mantto-salas');
        //evento para crear nueva solicitud
        servicios.initBotonNuevaSolicitud(datosTabla[1], '#seccion-servicio-mantto-salas');
        servicios.eventosFolio(datosTabla[2], '#seccion-servicio-mantto-salas', servicio);

    };

    var seguimientoActividad = function () {
        var servicio = arguments[0];
        var datos = arguments[1];
        var data = {servicio: servicio, estatus: datos[5], actividad: datos[0], idSistema: datos[6]};
        evento.enviarEvento('Seguimiento/MostrarFormularioSeguimientoActividad', data, '#seccion-servicio-mantto-salas', function (respuesta) {
            $('#seccionSeguimientoServicio').addClass('hidden');
            $('#seccionActividadesAsignadas').removeClass('hidden').empty().append(respuesta.formulario);
            $('#tituloSeguimientoActividad').empty().html('Avance de "' + datos[2] + '"');

            iniciarElementosSeguimientoActividad(respuesta);
            eventosParaSeccionSeguimientoActividad(datos, respuesta, servicio, datos[6]);

            if (respuesta.datos.seguimientoActividades !== null) {
                actualizaTablaAvtividadesAsignadas(respuesta.datos.seguimientoActividades);
            }
        });
    }

    function cargaJsonActividadesSeguimientoMantenimiento() {
        var datos = {
            'servicio': arguments[0]
        }
        evento.enviarEvento('Seguimiento/ActividadesSeguimientoMantenimientoJson', datos, '#seccion-servicio-mantto-salas', function (respuesta) {
            $('#jstree-default').jstree({
                'plugins': ["wholerow", "checkbox", "types"],
                'core': {
                    "themes": {
                        "responsive": false
                    },
                    'data': respuesta.json
                },
                "types": {
                    "default": {
                        "icon": "fa fa-file text-primary fa-lg"
                    },
                    "file": {
                        "icon": "fa fa-file text-success fa-lg"
                    }
                }
            }).bind("loaded.jstree", function (e, data) {
                $.each(respuesta.autorizadas, function (k, v) {
                    $('#jstree-default').jstree("select_node", v, true);
                });
            });
            $("#jstree-default").on("model.jstree", function (e, data) {
                $.each(data.nodes, function (key, value) {
                    var padre = value;
                    var isParent = $('#jstree-default').jstree('is_parent', padre);
                    if (isParent == false) {
                        _padre.push(padre);
                    }
                });
            });
        });
    }

    var guardarDatosseguimiento = function () {
        var datatabla = arguments[1];
        var idServicio = datatabla[0];
        var arrayvacio = [];
        var arrayid = $('#jstree-default').jstree("get_selected", true);

        $.each(arrayid, function () {
            if (this.id.indexOf('sistema') === -1) {
                arrayvacio.push(this.id);
            }
        });

        var data = {tipoServicio: idServicio, arrayIds: arrayvacio};
        evento.enviarEvento('Seguimiento/GuardarActividadSeguimiento', data, '#seccion-servicio-mantto-salas', function (respuesta) {
            if (respuesta.estatus) {
                evento.mostrarMensaje('.errorDefinicionActividades', true, 'Datos guardados Correctamente.', 3000);
            } else {
                evento.mostrarMensaje('.errorDefinicionActividades', false, 'La definición de actividades no se logró guardar correctamente. Intente de nuevo o recargue su página.', 3000);
            }
        });
    }

    var iniciarElementosSeguimientoActividad = function () {
        select.crearSelect('#selectTipoProducto');
        select.crearSelect('#selectProducto');
        select.crearSelect('#selectUbicacionSeguimientoActividad');
        file.crearUpload('#archivosSeguimientoActividad', 'Seguimiento/Guardar_Mantenimiento_General');
        tabla.generaTablaPersonal('#data-table-productos-seguimiento-actividad', null, null, true, true);
        $("#divNotasServicio").slimScroll({height: '400px'});
    }

    var eventosParaSeccionSeguimientoActividad = function () {
        var datos = arguments[0];
        var respuesta = arguments[1];
        var servicio = arguments[2];
        var idSistema = arguments[3];
        var sucursal = respuesta.datos.sucursal;
        var textoRadio = '';

        $("#radioMantemientoGeneral").attr('checked', true);

        $('input[name=radioMantenimiento]').change(function () {
            textoRadio = $(this).val();
            if (textoRadio === 'general') {
                $('#divMantimientoElemento').addClass('hidden');
                select.cambiarOpcion('#selectUbicacionSeguimientoActividad', '');
                $('#selectTipoProducto').removeAttr('disabled');
                $('#selectElementoSeguimientoActividad').empty().append('<option value="">Seleccionar</option>');
                select.cambiarOpcion('#selectElementoSeguimientoActividad', '');
                $('#selectSubelementoSeguimientoActividad').empty().append('<option value="">Seleccionar</option>');
                $('#selectProducto').empty().append('<option value="">Seleccionar</option>');
                select.cambiarOpcion('#selectProducto', '');
                select.cambiarOpcion('#selectSubelementoSeguimientoActividad', '');
                tabla.limpiarTabla('#data-table-productos-seguimiento-actividad');

                eventoSelectTiposProductos({id: '5'});
            } else {
                $('#divMantimientoElemento').removeClass('hidden');
                $('#selectTipoProducto').attr('disabled', 'disabled');
                select.cambiarOpcion('#selectUbicacionSeguimientoActividad', '');
                $('#selectTipoProducto').empty().append('<option value="">Seleccionar</option>');
                select.cambiarOpcion('#selectTipoProducto', '');
                tabla.limpiarTabla('#data-table-productos-seguimiento-actividad');
            }
        });

        $("#selectTipoProducto").on("change", function () {
            var tipoProducto = $(this).val();
            var data = {tipoProducto: tipoProducto};
            var serie = '';

            $('#selectProducto').empty().append('<option value="">Seleccionar</option>');
            select.cambiarOpcion('#selectProducto', '');
            $('#selectProducto').attr('disabled', 'disabled');
            $('#divCantidadSeguimientoActividad').addClass('hidden');

            if (tipoProducto !== '') {
                evento.enviarEvento('Seguimiento/MostrarTipoProducto', data, '#panelSeguimientoActividad', function (respuesta) {
                    $('#divCantidadSeguimientoActividad').addClass('hidden');
                    if (respuesta instanceof Array || respuesta instanceof Object) {
                        $.each(respuesta, function (key, valor) {

                            if (valor.Serie !== '') {
                                serie = " - " + valor.Serie;
                            } else {
                                if (tipoProducto !== '5') {
                                    serie = "- Sin Serie";
                                }
                            }

                            $("#selectProducto").append('<option data-cantidad=' + valor.Cantidad + ' value=' + valor.IdRegistroInventario + '>' + valor.Producto + ' ' + serie + '</option>');
                            restringirSelect();
                        });
                        $('#selectProducto').removeAttr('disabled');

                        $("#selectProducto").on("change", function () {
                            if (tipoProducto === '5') {
                                $('#divCantidadSeguimientoActividad').removeClass('hidden');
                            } else {
                                $('#divCantidadSeguimientoActividad').addClass('hidden');
                            }
                        });
                    } else {
                        $('#selectProducto').attr('disabled', 'disabled');
                        $('#divCantidadSeguimientoActividad').addClass('hidden');
                        evento.mostrarMensaje('#errorProductoSeguimientoActividad', false, 'No hay producto para este tipo.', 3000);
                    }
                });
            }
        });

        $('#btnAgregarProductoSeguimientoActividad').off('click');
        $('#btnAgregarProductoSeguimientoActividad').on('click', function () {
            var idTipoProducto = $("#selectTipoProducto").val();
            var idProducto = $("#selectProducto").val();
            var tipoProducto = $('#selectTipoProducto option:selected').text();
            var producto = $('#selectProducto option:selected').text();
            var cantidad = '1';

            if ($("#selectTipoProducto").val() !== '') {
                if ($("#selectProducto").val() !== '') {
                    if (idTipoProducto === '5') {
                        if ($("#inputCantidadSeguimientoActividad").val() !== '' && $("#inputCantidadSeguimientoActividad").val() !== '0') {
                            cantidad = $('#inputCantidadSeguimientoActividad').val();
                            colocarTablaProductos(idTipoProducto, tipoProducto, producto, cantidad, idProducto);
                        } else {
                            evento.mostrarMensaje('#errorProductoSeguimientoActividad', false, 'El campo cantidad esta vacio.', 3000);
                        }
                    } else {
                        colocarTablaProductos(idTipoProducto, tipoProducto, producto, cantidad, idProducto);
                    }

                } else {
                    evento.mostrarMensaje('#errorProductoSeguimientoActividad', false, 'Seleccione un Producto.', 3000);
                }
            } else {
                evento.mostrarMensaje('#errorProductoSeguimientoActividad', false, 'Seleccione un Tipo de Producto.', 3000);
            }
        });

        $("#inputCantidadSeguimientoActividad").focusout(function () {
            var _cantidad = $('#inputCantidadSeguimientoActividad').val();
            var max = $('#selectProducto option:selected').attr('data-cantidad');

            if (parseFloat(_cantidad) > parseFloat(max)) {
                $('#inputCantidadSeguimientoActividad').val(max);
            }

            if (parseFloat(_cantidad) < parseFloat(0)) {
                $('#inputCantidadSeguimientoActividad').val(0);
            }

        }).bind(function () {
            var _cantidad = $('#inputCantidadSeguimientoActividad').val();
            var max = $('#selectProducto option:selected').attr('data-cantidad');

            if (parseFloat(_cantidad) > parseFloat(max)) {
                $('#inputCantidadSeguimientoActividad').val(max);
            }

            if (parseFloat(_cantidad) < parseFloat(0)) {
                $('#inputCantidadSeguimientoActividad').val(0);
            }
        });

        $('#btnGuardarMantenimientoGeneralSeguimientoActividad').off('click');
        $('#btnGuardarMantenimientoGeneralSeguimientoActividad').on('click', function () {
            var observaciones = $('#inputObservacionesSeguimientoActividad').val();
            var evidencias = $('#archivosSeguimientoActividad').val();
            var ubicacion = $('#selectUbicacionSeguimientoActividad').val();
            var elemento = $('#selectElementoSeguimientoActividad').val();
            var subelemento = $('#selectSubelementoSeguimientoActividad').val();
            var tablaProductos = $('#data-table-productos-seguimiento-actividad').DataTable().rows().data();

            if (observaciones !== '') {
                if (evidencias !== '') {
                    if (ubicacion !== '') {
                        var datosTabla = [];

                        for (var i = 0; i < tablaProductos.length; i++) {
                            datosTabla.push(tablaProductos[i]);
                        }

                        var data = {
                            servicio: servicio,
                            actividad: datos[0],
                            observaciones: observaciones,
                            ubicacion: ubicacion,
                            elemento: elemento,
                            subelemento: subelemento,
                            datosTabla: datosTabla,
                            sucursal: sucursal,
                            idSistema: idSistema};
                            console.log(data.datosTabla);
                        if (textoRadio === 'elemento') {
                            if (elemento !== '') {
                                guardarMantenimientoGeneral(data);
                            } else {
                                evento.mostrarMensaje('#errorMantenimientoGeneralSeguimientoActividad', false, 'El select Elemento esta vacio.', 3000);
                            }
                        } else {
                            guardarMantenimientoGeneral(data)
                        }

                    } else {
                        evento.mostrarMensaje('#errorMantenimientoGeneralSeguimientoActividad', false, 'El select Ubicación esta vacio.', 3000);
                    }
                } else {
                    evento.mostrarMensaje('#errorMantenimientoGeneralSeguimientoActividad', false, 'El campo Evidencias esta vacio.', 3000);
                }
            } else {
                evento.mostrarMensaje('#errorMantenimientoGeneralSeguimientoActividad', false, 'El campo Observaciones esta vacio.', 3000);
            }
        });

        $('#data-table-productos-seguimiento-actividad tbody').on('click', 'tr', function () {
            tabla.eliminarFila('#data-table-productos-seguimiento-actividad', this);
        });

        $('#btnRegresarSeguimientoActivadSalas4XD').off('click');
        $('#btnRegresarSeguimientoActivadSalas4XD').on('click', function () {
            $('#seccionActividadesAsignadas').empty().addClass('hidden');
            $('#seccionSeguimientoServicio').removeClass('hidden');
        });

        $("#selectUbicacionSeguimientoActividad").on("change", function () {
            if (textoRadio === 'elemento') {
                var ubicacion = $(this).val();
                var data = {ubicacion: ubicacion, sucursal: sucursal, idSistema: datos[6]};

                $('#selectElementoSeguimientoActividad').empty().append('<option value="">Seleccionar</option>');
                select.cambiarOpcion('#selectElementoSeguimientoActividad', '');
                $('#selectElementoSeguimientoActividad').attr('disabled', 'disabled');
                $('#selectSubelementoSeguimientoActividad').attr('disabled', 'disabled');

                if (ubicacion !== '') {
                    evento.enviarEvento('Seguimiento/ElementosSeguimientoActividad', data, '#panelSeguimientoActividad', function (respuesta) {
                        if (respuesta instanceof Array || respuesta instanceof Object) {
                            $.each(respuesta, function (key, valor) {
                                $("#selectElementoSeguimientoActividad").append('<option value=' + valor.Id + '>' + valor.Nombre + " - "+ valor.Marca+" - "+ valor.Serie+ '</option>');
                            });
                            $('#selectElementoSeguimientoActividad').removeAttr('disabled');
                        } else {
                            $('#selectElementoSeguimientoActividad').attr('disabled', 'disabled');
                            evento.mostrarMensaje('#errorProductoSeguimientoActividad', false, 'No hay elementos para esta ubicación.', 3000);
                        }
                    });
                }
            }
        });

        $("#selectElementoSeguimientoActividad").on("change", function () {
            var elemento = $(this).val();
            var data = {elemento: elemento};

            $('#selectSubelementoSeguimientoActividad').empty().append('<option value="">Seleccionar</option>');
            select.cambiarOpcion('#selectSubelementoSeguimientoActividad', '');
            $('#selectSubelementoSeguimientoActividad').attr('disabled', 'disabled');

            if (elemento !== '') {
                evento.enviarEvento('Seguimiento/SubelementosSeguimientoActividad', data, '#panelSeguimientoActividad', function (respuesta) {
                    if (respuesta instanceof Array || respuesta instanceof Object) {
                        $.each(respuesta, function (key, valor) {
                            $("#selectSubelementoSeguimientoActividad").append('<option value=' + valor.Id + '>' + valor.Nombre + " - "+ valor.Marca +" - " + valor.Serie+'</option>');
                        });
                        $('#selectTipoProducto').removeAttr('disabled');
                        $('#selectSubelementoSeguimientoActividad').removeAttr('disabled');
                    } else {
                        $('#selectSubelementoSeguimientoActividad').attr('disabled', 'disabled');
                        $('#selectTipoProducto').attr('disabled', false);
                        evento.mostrarMensaje('#errorProductoSeguimientoActividad', false, 'No hay subelemento para este elemento.', 3000);
                    }
                });
            }
        });

        $("#selectSubelementoSeguimientoActividad").on("change", function () {
            var subelemento = $(this).val();

            if (subelemento !== '') {
                var data = {id: '4'};
                eventoSelectTiposProductos(data);
            } else {

                if (textoRadio !== 'general') {
                    eventoSelectTiposProductos({id: '3'});
                }
            }
        });

        var valor = $('.media-heading').html();
        if (!valor) {
            $('#btnConcluirActividadSeguimientoActividad').off('click');
            $('#btnConcluirActividadSeguimientoActividad').on('click', function () {
                evento.mostrarMensaje('#errorConcluir', false, 'No existe un informe', 3000);
                evento.mostrarMensaje('#errorConcluirAvance', false, 'No existe un informe', 2000);
            });
        } else {
            $('#btnConcluirActividadSeguimientoActividad').off('click');
            $('#btnConcluirActividadSeguimientoActividad').on('click', function () {
                var html = '<div class="row">\n\
                            <div id="mensaje-modal" class="col-md-12 text-center">\n\
                                <h3>¿Quieres Concluir la actividad?</h3>\n\
                            </div>\n\
                      </div>';
                html += '<div class="row m-t-20">\n\
                                <div class="col-md-12 text-center">\n\
                                    <button id="btnConcluirActividad" type="button" class="btn btn-sm btn-success"><i class="fa fa-check"></i> Aceptar</button>\n\
                                    <button id="btnCancelarConcluirActividad" type="button" class="btn btn-sm btn-danger"><i class="fa fa-times"></i> Cerrar</button>\n\
                                </div>\n\
                            </div>';
                $('#btnModalConfirmar').addClass('hidden');
                $('#btnModalAbortar').addClass('hidden');
                evento.mostrarModal('Iniciar Actividad', html);
                $('#btnModalConfirmar').empty().append('Eliminar');
                $('#btnModalConfirmar').off('click');

                $('#btnConcluirActividad').on('click', function () {
                    var data = {actividad: datos[0], servicio: servicio};
                    evento.enviarEvento('Seguimiento/ConcluirActividad', data, '#panelSeguimientoActividad', function (respuesta) {
                        if (respuesta instanceof Array || respuesta instanceof Object) {
                            evento.cerrarModal();
                            actualizaTablaAvtividadesAsignadas(respuesta);
                            $('#seccionActividadesAsignadas').empty().addClass('hidden');
                            $('#seccionSeguimientoServicio').removeClass('hidden');
                        }
                    });
                });

                $('#btnCancelarConcluirActividad').on('click', function () {
                    evento.cerrarModal();
                });
            });
        }
    }

    var guardarMantenimientoGeneral = function () {
        var data = arguments[0];

        file.enviarArchivos('#archivosSeguimientoActividad', 'Seguimiento/Guardar_Mantenimiento_General', '#panelSeguimientoActividad', data, function (respuesta) {
//            console.log(respuesta);
            if (respuesta) {
                $('#seccionActividadesAsignadas').empty().addClass('hidden');
                $('#seccionSeguimientoServicio').removeClass('hidden');
                servicios.mensajeModal('Datos guardados Correctamente.', 'Correcto', true);
            } else {
                servicios.mensajeModal('Datos no guardados, contacte al Área correspondiente.', 'Error', true);
            }
        });
    }

    var eventoSelectTiposProductos = function () {
        var data = arguments[0]

        $('#selectTipoProducto').empty().append('<option value="">Seleccionar</option>');
        select.cambiarOpcion('#selectTipoProducto', '');

        evento.enviarEvento('Seguimiento/SelectTiposProductos', data, '#panelSeguimientoActividad', function (respuesta) {
            if (respuesta instanceof Array || respuesta instanceof Object) {
                $.each(respuesta, function (key, valor) {
                    $("#selectTipoProducto").append('<option value=' + valor.Id + '>' + valor.Nombre + '</option>');
                });
            }
        });
    }

    var colocarTablaProductos = function () {
        var idTipoProducto = arguments[0];
        var tipoProducto = arguments[1];
        var producto = arguments[2];
        var cantidad = arguments[3];
        var idProducto = arguments[4];
        var filas = [];

        var producto = producto.replace(/,/g, '');

        filas.push(['@', idTipoProducto, tipoProducto, producto, cantidad, idProducto]);

        $.each(filas, function (key, value) {
            tabla.agregarFila('#data-table-productos-seguimiento-actividad', value);
            select.cambiarOpcion('#selectTipoProducto', '');
            select.cambiarOpcion('#selectProducto', '');
            $('#selectProducto').empty().append('<option value="">Seleccionar</option>');
            $('#selectProducto').attr('disabled', 'disabled');
            $('#inputCantidadSeguimientoActividad').val('');
            $('#divCantidadSeguimientoActividad').addClass('hidden');
        });

        evento.mostrarMensaje('#errorProductoSeguimientoActividad', true, 'Datos insertados en la lista correctamente.', 3000);

    }

    var restringirSelect = function () {
        var datosTablaProductos = $('#data-table-productos-seguimiento-actividad').DataTable().rows().data();

        if (datosTablaProductos.length > 0) {
            $.each(datosTablaProductos, function (key, value) {
                switch (value[1]) {
                    case '1':
                        $("#selectProducto").find("option[value='" + value[5] + "']").attr('disabled', 'disabled');
                        break;
                    case '2':
                        $("#selectProducto").find("option[value='" + value[5] + "']").attr('disabled', 'disabled');
                        break;
                    case '3':
                        $("#selectProducto").find("option[value='" + value[5] + "']").attr('disabled', 'disabled');
                        break;
                    case '4':
                        $("#selectProducto").find("option[value='" + value[5] + "']").attr('disabled', 'disabled');
                        break;
                    case '5':
                        $("#selectProducto").find("option[value='" + value[5] + "']").attr('disabled', 'disabled');
                        break;
                }
            });
        }
    }

    var actualizaTablaAvtividadesAsignadas = function () {
        var arrayActividadesAsignadas = arguments[0];

        tabla.limpiarTabla('#data-table-actividades-asignadas');

        $.each(arrayActividadesAsignadas, function (key, item) {
            tabla.agregarFila('#data-table-actividades-asignadas', [item.IdManttoActividades, item.Actividad, item.ActividadPadre, item.NombreAtiende, item.Fecha, item.Estatus, item.IdSistema]);
        });

    }

    var ActualizarElementoSeguimientomantenimiento = function () {
        var arrayMantenimiento = arguments[0];
        $.each(arrayMantenimiento, function (key, item) {
            $('#list_usuarios_' + item.Id).val(item.IdAtiende).trigger('change');
        });


    }

    var guardarAsignaciondeActividadesSeguimiento = function () {
        var idActividad = arguments[0];
        var idAtiende = $('#list_usuarios_' + idActividad).val();
        var idServicio = $('#list_usuarios_' + idActividad + ' option:selected').attr('data-servicio');
        var idEstatus = $('#list_usuarios_' + idActividad + ' option:selected').attr('data-estatus');
//        console.log(idEstatus);

        var data = {actividad: idActividad, servicio: idServicio, atiende: idAtiende, estatus: idEstatus};

        if (idAtiende !== '') {
            evento.enviarEvento('Seguimiento/GuardarIdSeguimiento', data, '#seccion-servicio-mantto-salas', function (respuesta) {

//                console.log(respuesta);

                if (respuesta) {
                    ActualizarElementoSeguimientomantenimiento(respuesta);
                    actualizaTablaAvtividadesAsignadas(respuesta);


                    servicios.mensajeModal('Datos guardados Correctamente.', 'Correcto', true);
                } else {
                    servicios.mensajeModal('Datos no guardados, contacte al Área correspondiente.', 'Error', true);
                }
            });
        } else {
            servicios.mensajeModal('Seleccione quien atiende.', 'Error', true);
        }
    }

    var actualizaTablaAvtividadesAsignadas = function () {

        var arrayActividadesAsignadas = arguments[0];
        tabla.limpiarTabla('#data-table-actividades-asignadas');
        $.each(arrayActividadesAsignadas, function (key, item) {
            tabla.agregarFila('#data-table-actividades-asignadas', [item.IdManttoActividades, item.Actividad, item.ActividadPadre, item.NombreAtiende, item.Fecha, item.Estatus, item.IdSistema]);
        });


    }


    var reasignarAsignaciondeActividadesSeguimiento = function () {

        var idActividad = arguments[0];
        var idAtiende = $('#list_usuarios_' + idActividad).attr('data-atiende');
        //var idAtiende = $('#list_usuarios_' + idActividad).val();
        var idservicio = $('#list_usuarios_' + idActividad).attr('data-servicio');
//    var idEstatus = $('#list_usuarios_' + idActividad).attr('data-estatus');

        // console.log(idAtiende);

        var data = {idActividad: idActividad, idAtiende: idAtiende, servicio: idservicio};

        var html = '<div class="row">\n\
                            <div id="mensaje-modal" class="col-md-12 text-center">\n\
                                <h3>¿Quieres Reabrir la actividad?</h3>\n\
                            </div>\n\
                      </div>';
        html += '<div class="row m-t-20">\n\
                                <div class="col-md-12 text-center">\n\
                                    <button id="btnIniciarReabrirActividad" type="button" class="btn btn-sm btn-success"><i class="fa fa-check"></i> Aceptar</button>\n\
                                    <button id="btnCancelarReabrirActividad" type="button" class="btn btn-sm btn-danger"><i class="fa fa-times"></i> Cerrar</button>\n\
                                </div>\n\
                            </div>';
        $('#btnModalConfirmar').addClass('hidden');
        $('#btnModalAbortar').addClass('hidden');
        evento.mostrarModal('Reabrir Actividad', html);
        $('#btnModalConfirmar').empty().append('Eliminar');
        $('#btnModalConfirmar').off('click');

        $('#btnIniciarReabrirActividad').on('click', function () {

            evento.enviarEvento('Seguimiento/ActualizaEstatus', data, '#seccion-servicio-mantto-salas', function (respuesta) {
                cargaActividadesAsignacion(idservicio);
                actualizaTablaAvtividadesAsignadas(respuesta);
                evento.cerrarModal();
            });


        });
        //Envento para concluir con la cancelacion
        $('#btnCancelarReabrirActividad').on('click', function () {
            evento.cerrarModal();
        });
    }
    
});
