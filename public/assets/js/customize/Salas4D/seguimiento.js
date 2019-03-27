$(function () {

    var evento = new Base();
    var websocket = new Socket();
    var tabla = new Tabla();
    var servicios = new Servicio();
    var select = new Select();
    var file = new Upload();
    var nota = new Nota();
    var _padre = [];
    var actualizoInformeGeneral = false;
    var bloquearSolucion = false;
    var solucionGuardada = false;
    var SubelementosDanado;
    var SubelementosUtilizados;

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

            } else if (operacion === '2' || operacion === '3' || operacion === '12' || operacion === '10') {
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
                        break;
                    case '7':
                        iniciarPaginaSeguimientoMantenimientoCorrectivo(respuesta, datosTabla);
                        eventosParaSeccionSeguimientoMantenimiento(datosTabla, respuesta);
                        listaElementos(respuesta);
                        listaSubelementos();
                        guardarServicioCorrectivo(datosTabla, respuesta);
                        break;
                }
            }
        });
    };

    var iniciarPaginaSeguimientoMantenimientoCorrectivo = function (respuesta, datosTabla) {
        $('#listaSalasX4D').addClass('hidden');
        $('#seccionSeguimientoServicio').removeClass('hidden').empty().append(respuesta.formulario);
        select.crearSelect('#sucursalesCorrectivo');
        select.crearSelect('#selectFalla');

        tabla.generaTablaPersonal('#tabla-Elementos', null, null, true, true, [], true, 'lfrtip', false);

        if (respuesta.informacion.sucursal !== null) {
            select.cambiarOpcion('#sucursalesCorrectivo', respuesta.informacion.sucursal);
        }
        if (respuesta.consultarServicio) {
            $('#btnGuardarMantenimientoCorrectivo').addClass('hidden');
            $('#btnEditarMantenimientoCorrectivo').removeClass('hidden');
            editarServicioCorrectivo(datosTabla);
        }
        var opcion = {};
        if (respuesta.consultarServicio.tipoFalla !== null) {
            select.cambiarOpcion('#selectFalla', respuesta.consultarServicio.tipoFalla);
            var sucursal = respuesta.informacion.sucursal;
            var tipoFalla = respuesta.consultarServicio.tipoFalla;
            var radioElemento = respuesta.consultarServicio.elementoRadio;
            var dato = {sucursales: sucursal, tipoFalla: tipoFalla};
            var elemento = "";
            evento.enviarEvento('Seguimiento/MostrarElementosSucursal', dato, '', function (resultado2) {
                $('#selectFalla').removeAttr('disabled');
                tabla.limpiarTabla('#tabla-Elementos');
                $.each(resultado2, function (key, value) {
                    if (radioElemento === value.Id) {
                        var radio = '<input type="radio" name="radioElemento" value="' + value.Id + '" checked/>';
                    } else {
                        var radio = '<input type="radio" name="radioElemento" value="' + value.Id + '"/>';
                    }

                    if (respuesta.consultarServicio.tipoFalla === "1") {
                        elemento = value.Elemento;
                    } else {
                        elemento = value.Subelemento;
                        if (radioElemento === value.Id) {
                            opcion = {'id': value.Id, 'Subelemento': value.Subelemento, 'Serie': value.Serie};
                        }
                    }
                    tabla.agregarFila('#tabla-Elementos', [
                        value.Id,
                        elemento,
                        value.Serie,
                        value.ClaveCinemex,
                        value.Ubicacion,
                        value.Sistema,
                        radio
                    ]);
                });

            });

        }

        $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
            var target = $(e.target).attr("href");
            switch (target) {
                case "#solucionCorrectivo":
                    formularioSolucionCorrectivo(respuesta, datosTabla);
                    break;
            }
        });
    };

    var formularioSolucionCorrectivo = function (respuesta, datosTabla) {
        var sucursal = respuesta.informacion.sucursal;
        var servicio = $('#hiddenServicio').val();
        var tipoFalla = $('#selectFalla').val();
        var dato = {'servicio': servicio, 'sucursales': sucursal, 'tipoFalla': tipoFalla};
        var archivo = null;
        var evidencia;

        evento.enviarEvento('Seguimiento/MostrarSolucionCorrectivo4D', dato, '#seccion-servicio-mantto-correctivo', function (resultado) {
            $("#solucionCorrectivo > .panel-body").empty().append(resultado.formulario);
            tabla.generaTablaPersonal('#tablaSubelementosCorrectivo', null, null, true, true, [], true, 'lfrtip', false);
            file.crearUpload('#inputArchivoCorrectivo', 'Seguimiento/GuardarMantenimientoCorrectivo', ['jpg', 'bmp', 'jpeg', 'gif', 'png', 'pdf', 'doc', 'docx', 'xls', 'xlsx'], null, [], '', null, null, 0, false, false, false, false, 0, false);
            select.crearSelect('#elementoUtilizado');
            select.crearSelect('#subelementoDanado');
            select.crearSelect('#subelementoUtilizado');
            var informeGeneral = resultado.informeGeneral;
            var solucionServicio = resultado.solucionServicio;

            $('#tipoSolucion').on('change', function () {
                var solucion = $('#tipoSolucion').val();
                if (solucion === "1") {
                    $('#divSubelementoUtilizado').attr('style', 'display:none');
                    $('#divElementoUtilizado').attr('style', 'display:none');
                } else if (solucion === "2") {
                    $('#divElementoUtilizado').attr('style', 'display:block');
                    $('#divSubelementoUtilizado').attr('style', 'display:none');
                } else if (solucion === "3") {
                    $('#divElementoUtilizado').attr('style', 'display:none');
                    $('#divSubelementoUtilizado').attr('style', 'display:block');
                }
            });

            if (informeGeneral !== false) {
                $('#tipoSolucion').removeAttr('disabled');
                mostrarSubelementos(respuesta, resultado);
            }

            if (solucionServicio.length !== 0) {
                $('#notasSolucion').val(solucionServicio[0].Observaciones);

                if (actualizoInformeGeneral === true) {
                    archivo = null;
                } else {
                    archivo = resultado.solucionServicio[0].Archivos;
                }

                if (archivo !== null) {
                    evidencia = archivo.split(',');
                } else {
                    evidencia = [];
                }
            }

            $('.btnEliminarEvidencia').off('click');
            $('.btnEliminarEvidencia').on('click', function () {
                var archivoEliminado = $(this).attr('data-urlarchivo');
                var index = null;
                $(this).parent().addClass('hidden');

                if ($.inArray(archivoEliminado, evidencia) !== -1) {
                    $.each(evidencia, function (key, value) {
                        if (archivoEliminado === value) {
                            index = key;
                        }
                    });
                    if (index !== null) {
                        evidencia.splice(index, 1);
                    }
                } else {
                    evento.mostrarMensaje('#errorSolucion', false, 'No puedes eliminar evidencia', 3000);
                }
            });

            if (actualizoInformeGeneral) {
                limpiarFormulario(resultado);
            }
            if (bloquearSolucion === true) {
                select.cambiarOpcion('#tipoSolucion', "");
                $('#tipoSolucion').attr('disabled', 'disabled');
            }
            guardarSolucionCorrectivo(datosTabla, evidencia);
        });
    };

    var mostrarSubelementos = function (respuesta, resultado) {
        var datos = null;
        var tipoProducto = null;
        var subFiltrados2 = new Array();
        var agregadoDanado = new Array();
        var agregadoUtilizado = new Array();
        var datosActualizadoDanado = new Array();
        var datosActualizadoUtilizado = new Array();

        tipoProducto = $('#selectFalla').val();
        tipoProducto = (tipoProducto === "1") ? "3" : "4";
        var datoElemento = {"IdElemento": $('input:radio[name=radioElemento]:checked').val(), 'servicio': $('#hiddenServicio').val(), 'tipoFalla': tipoProducto};

        evento.enviarEvento('Seguimiento/MostrarSubelementoCorrectivo', datoElemento, '#seccion-servicio-mantto-correctivo', function (resultado2) {
            SubelementosDanado = resultado2[0];
            SubelementosUtilizados = resultado2[1];
            agregadoDanado = resultado2[2];
            var arraySelectTemp = [];
            var listaTablaSubelemento = [];
            $.each(SubelementosDanado, function (key, value) {
                if (jQuery.inArray(value.id, resultado2[2]) === -1) {
                    arraySelectTemp.push(value);
                } else {
                    listaTablaSubelemento.push([value.id, value.text]);
                }
            });
            select.cargaDatos("#subelementoDanado", arraySelectTemp);

            $.each(SubelementosUtilizados, function (key, value) {
                if (value.flag === 0) {
                    subFiltrados2.push(value);
                } else {
                    $.each(listaTablaSubelemento, function (k, v) {
                        if (value.idDanado === v[0]) {
                            listaTablaSubelemento[k].push(value.id, value.text);
                        }
                    });
                }
            });
            select.cargaDatos("#subelementoUtilizado", subFiltrados2);

            if (actualizoInformeGeneral) {
                var index = null;
                var idSubDanado = null;
                var subelementoDanadoUtil = resultado.subdanado;

                $.each(subelementoDanadoUtil, function (clave, valor) {
                    idSubDanado = valor.IdSubelementoDañado;
                    $.each(agregadoDanado, function (key, value) {
                        if (value === idSubDanado) {
                            index = key;
                        }
                    });
                    tabla.eliminarFila('#tablaSubelementosCorrectivo');
                    agregadoDanado.splice(index, 1);
                    agregadoUtilizado.splice(index, 1);
                    actualizarSelectsSubelementos(agregadoDanado, datosActualizadoDanado);
                    select.cargaDatos('#subelementoDanado', datosActualizadoDanado);
                    actualizarSelectsSubelementos(agregadoUtilizado, datosActualizadoUtilizado, true);
                    select.cargaDatos('#subelementoUtilizado', datosActualizadoUtilizado);
                });
                tabla.eliminarFila('#tablaSubelementosCorrectivo', this);
            } else {
                $.each(listaTablaSubelemento, function (key, value) {
                    tabla.agregarFila("#tablaSubelementosCorrectivo", value);
                });
            }

        });

        $('#btnAgregarSubelementoSolucion').off('click');
        $('#btnAgregarSubelementoSolucion').on('click', function () {
            var IdDanado = $('#subelementoDanado option:selected').val();
            var nombreDanado = $('#subelementoDanado option:selected').text();
            var IdUtilizado = $('#subelementoUtilizado option:selected').val();
            var nombreUtilizado = $('#subelementoUtilizado option:selected').text();

            if (IdDanado !== '' && IdUtilizado !== '') {
                if ($.inArray(IdDanado, agregadoDanado) === -1) {
                    tabla.agregarFila('#tablaSubelementosCorrectivo', [IdDanado, nombreDanado, IdUtilizado, nombreUtilizado]);
                    agregadoDanado.push(IdDanado);
                    agregadoUtilizado.push(IdUtilizado);
                    actualizarSelectsSubelementos(agregadoDanado, datosActualizadoDanado);
                    actualizarSelectsSubelementos(agregadoUtilizado, datosActualizadoUtilizado, true);
                    select.cargaDatos('#subelementoDanado', datosActualizadoDanado);
                    select.cargaDatos('#subelementoUtilizado', datosActualizadoUtilizado);
                }
            } else {
                evento.mostrarMensaje('#errorSubelementoSolucio', false, 'Te falta seleccionar algun subelemento dañado o utilizado', 3000);
            }
        });

        $('#tablaSubelementosCorrectivo tbody').on('dblclick', 'tr', function () {
            datos = $('#tablaSubelementosCorrectivo').DataTable().row(this).data();
            var index = null;
            $.each(agregadoDanado, function (key, value) {
                if (value === datos[0]) {
                    index = key;
                }
            });

            if (index !== null) {
                tabla.eliminarFila('#tablaSubelementosCorrectivo', this);
                agregadoDanado.splice(index, 1);
                agregadoUtilizado.splice(index, 1);
                actualizarSelectsSubelementos(agregadoDanado, datosActualizadoDanado);
                select.cargaDatos('#subelementoDanado', datosActualizadoDanado);
                actualizarSelectsSubelementos(agregadoUtilizado, datosActualizadoUtilizado, true);
                select.cargaDatos('#subelementoUtilizado', datosActualizadoUtilizado);
            }
        });
    };

    var limpiarFormulario = function (resultado) {
        var arrayElementos = [];

        select.cambiarOpcion('#subelementoDanado', "");
        select.cambiarOpcion('#subelementoUtilizado', "");
        $('#notasSolucion').val("");
        $('.evidenciaMantoCorrectivo').remove();

        $.each(resultado.elementosAlmacen, function (key, value) {
            arrayElementos.push(value);
        });
        select.cargaDatos("#elementoUtilizado", arrayElementos);
    };

    var listaElementos = function () {
        $('#sucursalesCorrectivo').on('change', function () {
            var _this = $(this);
            var datos = {'sucursales': _this.val(), 'tipoFalla': '0'};
            bloquearSolucion = true;
            tabla.limpiarTabla('#tabla-Elementos');
            if (datos.sucursales !== "") {
                evento.enviarEvento('Seguimiento/MostrarElementosSucursal', datos, '#formServicioPreventivoSalas4xd', function (resultado) {
                    if (resultado.length) {
                        $('#selectFalla').removeAttr('disabled');
                        select.cambiarOpcion('#selectFalla', "");
                        listaSubelementos();
                    } else {
                        $('#selectFalla').attr('disabled', 'disabled');
                        select.cambiarOpcion('#selectFalla', "");
                        evento.mostrarMensaje('#errorElementos', false, 'No existen elementos(o Subelementos) para esta sucursal', 3000);
                    }
                });
            } else {
                $('#selectFalla').attr('disabled', 'disabled');
                select.cambiarOpcion('#selectFalla', "");
            }
        });
    };

    var listaSubelementos = function () {
        $('#selectFalla').on('change', function () {
            var falla = $('#selectFalla').val();
            var dato = {'sucursales': $('#sucursalesCorrectivo').val(), 'tipoFalla': $('#selectFalla').val()};
            actualizoInformeGeneral = true;
            bloquearSolucion = true;

            if ($('#selectFalla').val() === "") {
                tabla.limpiarTabla('#tabla-Elementos');
            }

            if (falla === "1") {
                $('#tipoProducto').empty().append("Elemento");
            } else if (falla === "2") {
                $('#tipoProducto').empty().append("Subelemennto");
            } else if (falla === "") {
                $('#tipoProducto').empty().append("Producto");
            }

            evento.enviarEvento('Seguimiento/MostrarElementosSucursal', dato, '', function (resultado2) {
                if (resultado2.length === 0) {
                    evento.mostrarMensaje('#errorElementos', false, 'No hay informacion para mostrar', 3000);
                }
                tabla.limpiarTabla('#tabla-Elementos');
                var elemento = "";
                $.each(resultado2, function (key, value) {
                    if (dato.tipoFalla === "1") {
                        elemento = value.Elemento;
                    } else {
                        elemento = value.Subelemento;

                    }
                    var radio = '<input type="radio" name="radioElemento" value="' + value.Id + '" />';
                    tabla.agregarFila('#tabla-Elementos', [
                        value.Id,
                        elemento,
                        value.Serie,
                        value.ClaveCinemex,
                        value.Ubicacion,
                        value.Sistema,
                        radio
                    ]);
                });
            });
        });
    };

    var guardarServicioCorrectivo = function (datosTabla) {
        var datosTabla = arguments[0];
        var servicio = datosTabla[0];
        $('#btnGuardarMantenimientoCorrectivo').off('click');
        $('#btnGuardarMantenimientoCorrectivo').on('click', function () {
            if (evento.validarFormulario('#formServicioCorrectivoSalas4xd')) {
                if ($("#formServicioCorrectivoSalas4xd input[name='radioElemento']:radio").is(':checked')) {
                    var datos = {
                        'IdServicio': servicio,
                        'tipoFalla': $('#selectFalla').val(),
                        'IdElemento': $('input:radio[name=radioElemento]:checked').val(),
                        'sucursal': $('#sucursalesCorrectivo').val()
                    };
                    evento.enviarEvento('Seguimiento/GuardarServicioCorrectivo', datos, '#seccion-servicio-mantto-correctivo', function (resultado) {
                        if (resultado) {
                            evento.mostrarMensaje('#errorElementos', true, 'Datos guardados Correctamente.', 3000);
                            $('#btnGuardarMantenimientoCorrectivo').addClass('hidden');
                            $('#btnEditarMantenimientoCorrectivo').removeClass('hidden');
                            bloquearSolucion = false;
                            editarServicioCorrectivo(datosTabla);
                        }
                    });
                } else {
                    evento.mostrarMensaje('#errorElementos', false, 'Selecciona el elemento', 3000);
                }
            }
            $("#parsley-id-multiple-radioElemento").css("display", "none");
        });
    };

    var editarServicioCorrectivo = function (datosTabla) {
        var datosTabla = arguments[0];
        var servicio = datosTabla[0];
        $('#btnEditarMantenimientoCorrectivo').off('click');
        $('#btnEditarMantenimientoCorrectivo').on('click', function () {
            if (evento.validarFormulario('#formServicioCorrectivoSalas4xd')) {
                if ($("#formServicioCorrectivoSalas4xd input[name='radioElemento']:radio").is(':checked')) {
                    var datos = {
                        'IdServicio': servicio,
                        'tipoFalla': $('#selectFalla').val(),
                        'IdElemento': $('input:radio[name=radioElemento]:checked').val(),
                        'sucursal': $('#sucursalesCorrectivo').val()
                    };
                    evento.enviarEvento('Seguimiento/EditarServicioCorrectivo', datos, '#seccion-servicio-mantto-correctivo', function (resultado) {
                        if (resultado) {
                            actualizoInformeGeneral = true;
                            bloquearSolucion = false;
                            evento.mostrarMensaje('#errorElementos', true, 'Datos guardados Correctamente.', 3000);
                        }
                    });
                } else {
                    evento.mostrarMensaje('#errorElementos', false, 'Selecciona el elemento', 3000);
                }
            }
            $("#parsley-id-multiple-radioElemento").css("display", "none");
        });
    };

    var actualizarSelectsSubelementos = function (elementos, datosActualizado, origen = null) {
        var temp = new Array();
        var elementoBorrar = null;
        var index = null;
        var SubElementos = (origen === null) ? SubelementosDanado : SubelementosUtilizados;
        $.each(datosActualizado, function (key, value) {
            temp.push(value.id);
        });
        $.each(SubElementos, function (k, valor) {
            if ($.inArray(valor.id, elementos) === -1 && $.inArray(valor.id, temp) === -1) {
                datosActualizado.push(valor);
            } else if ($.inArray(valor.id, elementos) !== -1 && $.inArray(valor.id, temp) !== -1) {
                elementoBorrar = valor.id;
            }
        });

        $.each(datosActualizado, function (key, value) {
            if (elementoBorrar === value.id) {
                index = key;
            }
        });

        if (index !== null) {
            datosActualizado.splice(index, 1);
    }
    };

    var guardarSolucionCorrectivo = function () {
        var datosTabla = arguments[0];
        var nuevaEvidencia = arguments[1];
        var servicio = $('#hiddenServicio').val();

        $('#btnAgregarSolucion').off('click');
        $('#btnAgregarSolucion').on('click', function () {
            var tipoSolucion = $('#tipoSolucion').val();
            var notasSolucion = $('#notasSolucion').val();
            var elementoUtilizado = $('#elementoUtilizado').val();
            var tablaProductos = $('#tablaSubelementosCorrectivo').DataTable().rows().data();
            var evidencia2 = $('#inputArchivoCorrectivo').val();

            if (evidencia2.substring(0, 2) === "C:") {
                var evidencias = $('#inputArchivoCorrectivo').val();
            } else if (nuevaEvidencia !== undefined) {
                evidencias = nuevaEvidencia.join();
            } else if (evidencia2.substring(0, 2) === "") {
                evidencias = null;
            }

            var datosTabla = "[";
            for (var i = 0; i < tablaProductos.length; i++) {
                datosTabla += '{"IdDanado" : "' + tablaProductos[i][0] + '", "IdUtilizado" : "' + tablaProductos[i][2] + '"},';
            }
            datosTabla = datosTabla.slice(0, -1);
            datosTabla += "]";

            var data = {
                'servicio': servicio,
                'tipoSolucion': tipoSolucion,
                'Observaciones': notasSolucion,
                'elementoUtilizado': elementoUtilizado,
                'datosTabla': datosTabla,
                'evidencias': evidencias
            };
            actualizoInformeGeneral = false;
            if (tipoSolucion === "1") {
                if (notasSolucion !== '') {
                    guardarMantenimientoCorrectivo(data);
                } else {
                    evento.mostrarMensaje('#errorSolucion', false, 'Informacion incopleta.', 3000);
                }
            } else if (tipoSolucion === '2') {
                if (notasSolucion !== '') {
                    if (elementoUtilizado !== '') {
                        guardarMantenimientoCorrectivo(data);
                    } else {
                        evento.mostrarMensaje('#errorSolucion', false, 'Selecciona el elemento a utilizar.', 3000);
                    }
                } else {
                    evento.mostrarMensaje('#errorSolucion', false, 'Informacion incopleta.', 3000);
                }
            } else if (tipoSolucion === '3') {
                if (notasSolucion !== '') {
                    if (datosTabla !== []) {
                        guardarMantenimientoCorrectivo(data);
                    } else {
                        evento.mostrarMensaje('#errorSolucion', false, 'No has seleccionado los productos.', 3000);
                    }
                } else {
                    evento.mostrarMensaje('#errorSolucion', false, 'Informacion incopleta.', 3000);
                }
            } else {
                evento.mostrarMensaje('#errorSolucion', false, 'Selecciona el tipo de solucion', 3000);
            }
        });

    };

    var guardarMantenimientoCorrectivo = function () {
        var data = arguments[0];
        if (data.evidencias !== "") {
            file.enviarArchivos('#inputArchivoCorrectivo', 'Seguimiento/GuardarMantenimientoCorrectivo', '#seccion-servicio-mantto-correctivo', data, function (respuesta1) {
                if (respuesta1) {
                    evento.mostrarMensaje('#errorSolucion', true, 'Datos guardados Correctamente.', 3000);
                } else {
                    evento.mostrarMensaje('#errorSolucion', false, 'Datos incorrectos.', 3000);
                }
            });
        } else {
            evento.enviarEvento('Seguimiento/GuardarMantenimientoCorrectivo', data, '#seccion-servicio-mantto-correctivo', function (respuesta2) {
                if (respuesta2) {
                    evento.mostrarMensaje('#errorSolucion', true, 'Datos guardados Correctamente.', 3000);
                } else {
                    evento.mostrarMensaje('#errorSolucion', false, 'Datos incorrectos.', 3000);
                }
            });
        }
    };

    var recargandoTablaSalasX4D = function (informacionServicio) {
        tabla.limpiarTabla('#data-table-salasX4D');
        $.each(informacionServicio.serviciosAsignados, function (key, item) {
            tabla.agregarFila('#data-table-salasX4D', [item.Id, item.IdSolicitud, item.Ticket, item.Servicio, item.FechaCreacion, item.Descripcion, item.NombreEstatus, item.IdEstatus, item.Folio]);
        });
    };

    var iniciarElementosPaginaSeguimientoMantenimiento = function (respuesta, datosTabla) {
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
                var dato = {'idActividad': intActi, "idServicio": idServicio};
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
            actualizoInformeGeneral = false;
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
            var data = {servicio: servicio};
            var flagCorrectivo = null;
            modalConcluirServicio(flagCorrectivo, datosTabla, servicio);
        });

        $('#btnconcluirServicioCorrectivo').off('click');
        $('#btnconcluirServicioCorrectivo').on('click', function () {
            var solucicon = respuesta.getSolucionByServicio;
            var infoGeneral = respuesta.consultarServicio;
            var flagCorrectivo = 1;

            if (!jQuery.isEmptyObject(infoGeneral) && !jQuery.isEmptyObject(solucicon)) {
                modalConcluirServicio(flagCorrectivo, datosTabla, servicio);
            } else {
                servicios.mensajeModal("Aun no puedes conluir el servicio, falta informacion", "Advertencia", true);
            }
        });

        $('#btnCancelarServicioSeguimiento').on('click', function () {
            var data = {servicio: datosTabla[0], ticket: datosTabla[1]};
            servicios.cancelarServicio(
                    data,
                    'Seguimiento/Servicio_Cancelar_Modal',
                    '#seccion-datos-logistica',
                    'Seguimiento/Servicio_Cancelar'
                    );
        });

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
        servicios.initBotonNuevaSolicitud(datosTabla[1]);
        servicios.eventosFolio(datosTabla[2], '#seccion-servicio-mantto-salas', servicio);

    };

    var modalConcluirServicio = function (flagCorrectivo = null, datosTabla, servicio) {
        var ticket = datosTabla[1];
        servicios.mostrarModal('Firma', servicios.formConcluirServicio());
        $('#btnModalConfirmar').addClass('hidden');
        var myBoardFirma = null;
        var ancho = Math.max(document.documentElement.clientWidth, window.innerWidth || 0);
        var alto = Math.max(document.documentElement.clientHeight, window.innerHeight || 0);
//        var arrayMedidas = servicios.ajusteCanvasMedidas(ancho, alto);

//        var myBoardFirma = new DrawingBoard.Board('campoFirma', {
//            background: "#fff",
//            color: "#000",
//            size: 1,
//            controlsPosition: "right",
//            controls: [
//                {Navigation: {
//                        back: false,
//                        forward: false
//                    }
//                }
//            ],
//            webStorage: false
//        });
//        $("#tagValor").tagit({
//            allowSpaces: false
//        });
//        myBoardFirma.ev.trigger('board:reset', 'what', 'up');

//        servicios.mostrarModal('Firma', servicios.formConcluirServicio());
//        $('#btnModalConfirmar').addClass('hidden');

        $(window).resize(function () {
            servicios.ajusteCanvasFirma('campoFirma');
            myBoardFirma = servicios.campoLapiz('campoFirma');
        });

//        $('#campoFirma').css({"margin": "0 auto", "width": arrayMedidas[0] + "px", "height": arrayMedidas[1] + "px"});

        myBoardFirma = servicios.campoLapiz('campoFirma');

        $('#btnConcluirServicio').off('click');
        $('#btnConcluirServicio').on('click', function () {
            var img = myBoardFirma.getImg();
            var imgInput = (myBoardFirma.blankCanvas == img) ? '' : img;
            if (evento.validarFormulario('#formConcluirServicioFirma')) {
                var personaRecibe = $('#inputPersonaRecibe').val();
                var correo = $("#tagValor").tagit("assignedTags");
                if (correo.length > 0) {
                    if (servicios.validarCorreoArray(correo)) {
                        if (imgInput !== '') {
                            if ($('#terminos').attr('checked')) {
                                var dataInsertar = {'ticket': ticket, 'servicio': servicio, 'img': img, 'correo': correo, 'nombreFirma': personaRecibe, 'flagCorrectivo': flagCorrectivo, 'sucursal': $('#sucursalesCorrectivo').val()};
                                evento.enviarEvento('Seguimiento/concluirServicoFirma', dataInsertar, '#modal-dialogo', function (respuesta) {
                                    if (respuesta) {
                                        servicios.mensajeModal('Servicio concluido.', 'Correcto');
                                    } else {
                                        evento.mostrarMensaje('.errorConcluirServicio', false, 'Tienes informacion sin concluir', 3000);
                                    }
                                });
                            } else {
                                evento.mostrarMensaje('.errorConcluirServicio', false, 'Debes aceptar terminos', 3000);
                            }
                        } else {
                            evento.mostrarMensaje('.errorConcluirServicio', false, 'Debes llenar el campo Firma de conformidad.', 3000);
                        }
                    } else {
                        evento.mostrarMensaje('.errorConcluirServicio', false, 'Algun Correo no es correcto.', 3000);

                    }
                } else {
                    evento.mostrarMensaje('.errorConcluirServicio', false, 'Debe insertar al menos un correo.', 3000);
                }
            }
        });
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
    };

    function cargaJsonActividadesSeguimientoMantenimiento() {
        var datos = {
            'servicio': arguments[0]
        };
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
    };

    var iniciarElementosSeguimientoActividad = function () {
        select.crearSelect('#selectTipoProducto');
        select.crearSelect('#selectProducto');
        select.crearSelect('#selectUbicacionSeguimientoActividad');
        file.crearUpload('#archivosSeguimientoActividad', 'Seguimiento/Guardar_Mantenimiento_General');
        tabla.generaTablaPersonal('#data-table-productos-seguimiento-actividad', null, null, true, true);
        $("#divNotasServicio").slimScroll({height: '400px'});
    };

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
                        if (textoRadio === 'elemento') {
                            if (elemento !== '') {
                                guardarMantenimientoGeneral(data);
                            } else {
                                evento.mostrarMensaje('#errorMantenimientoGeneralSeguimientoActividad', false, 'El select Elemento esta vacio.', 3000);
                            }
                        } else {
                            guardarMantenimientoGeneral(data);
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
                                $("#selectElementoSeguimientoActividad").append('<option value=' + valor.Id + '>' + valor.Nombre + " - " + valor.Marca + " - " + valor.Serie + '</option>');
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
                            $("#selectSubelementoSeguimientoActividad").append('<option value=' + valor.Id + '>' + valor.Nombre + " - " + valor.Marca + " - " + valor.Serie + '</option>');
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
    };

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
    };

    var eventoSelectTiposProductos = function () {
        var data = arguments[0];

        $('#selectTipoProducto').empty().append('<option value="">Seleccionar</option>');
        select.cambiarOpcion('#selectTipoProducto', '');

        evento.enviarEvento('Seguimiento/SelectTiposProductos', data, '#panelSeguimientoActividad', function (respuesta) {
            if (respuesta instanceof Array || respuesta instanceof Object) {
                $.each(respuesta, function (key, valor) {
                    $("#selectTipoProducto").append('<option value=' + valor.Id + '>' + valor.Nombre + '</option>');
                });
            }
        });
    };

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

    };

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
    };

    var actualizaTablaAvtividadesAsignadas = function () {
        var arrayActividadesAsignadas = arguments[0];

        tabla.limpiarTabla('#data-table-actividades-asignadas');

        $.each(arrayActividadesAsignadas, function (key, item) {
            tabla.agregarFila('#data-table-actividades-asignadas', [item.IdManttoActividades, item.Actividad, item.ActividadPadre, item.NombreAtiende, item.Fecha, item.Estatus, item.IdSistema]);
        });

    };

    var ActualizarElementoSeguimientomantenimiento = function () {
        var arrayMantenimiento = arguments[0];
        $.each(arrayMantenimiento, function (key, item) {
            $('#list_usuarios_' + item.Id).val(item.IdAtiende).trigger('change');
        });


    };

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
    };

    var actualizaTablaAvtividadesAsignadas = function () {

        var arrayActividadesAsignadas = arguments[0];
        tabla.limpiarTabla('#data-table-actividades-asignadas');
        $.each(arrayActividadesAsignadas, function (key, item) {
            tabla.agregarFila('#data-table-actividades-asignadas', [item.IdManttoActividades, item.Actividad, item.ActividadPadre, item.NombreAtiende, item.Fecha, item.Estatus, item.IdSistema]);
        });


    };


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
    };

});
