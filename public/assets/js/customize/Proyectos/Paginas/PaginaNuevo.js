
class PaginaNuevo extends PaginaProyecto {

    desbloquearFormulario(formulario = '') {

        let objetoFormulario = this.formularios.get(formulario);
        objetoFormulario.habilitarFormulario();
    }

    generarNuevoProyecto() {

        try {
            let _this = this;
            let formulario = _this.formularios.get('form-nuevo-proyecto');
            let datos = formulario.validarFormulario();
            let tablaModal;
            let contenido;
            _this.enviarPeticionServidor('panel-seccion-proyecto', '/Proyectos/Nuevo/Nuevo_Proyecto', datos, function (respuesta) {
                tablaModal = _this.tablas.get('data-table-nuevoProyecto');
                contenido = `<div class="row">
                                <div class="col-md-12 text-center">
                                    <p>Se genero el proyecto <strong><span class="text-success">${formulario.obtenerDato('input-nombre-proyecto')}<span></strong>.</p>
                                    <p>Se asignaron los siguientes tickets por complejo:</p>
                                </div>
                            </div>
                            ${tablaModal.crearTablaDinamica(['Ticket', 'Sucursal'])}                            
                            <div class="row m-t-10">
                                <div class="col-md-12 text-center">
                                    <button id="btn-continuar" type="button" class="btn btn-sm btn-success" >Siguiente</button>
                                </div>
                            </div>`;
                _this.modal.mostrarModal('Proyecto Nuevo', contenido);
                tablaModal.iniciarTabla();
                _this.modal.ocultarBotonAceptar();
                _this.modal.ocultarBotonCanelar();
                $.each(respuesta.datosProyectoNuevo, function (key, valor) {
                    if (key !== 'clave') {
                        tablaModal.agregarDatosFila([valor.ticket, key]);
                    }
                });
                _this.actulizarTablaProyectosSinIniciar(respuesta.listaProyectos);
                $('#btn-continuar').on('click', function () {
                    _this.modal.borrarContenido();
                    contenido = `<div class="row">
                                <div class="col-md-12 text-center">
                                    <p>¿Quieres definir el alcance del proyecto?</p>                                    
                                </div>
                            </div>`;
                    _this.modal.agregarContenido(contenido);
                    _this.modal.funcionalidadBotonAceptar(null, function () {
                        _this.enviarPeticionServidor('modal-dialogo', '/Proyectos/Nuevo/Datos_Proyecto', {idProyecto: respuesta.idPrimerProyecto}, function (datosProyecto) {
                            _this.datosGeneralesProyecto = datosProyecto;
                            if (datos['select-complejo'] !== null) {
                                _this.datosGeneralesProyecto.IdProyecto = respuesta.idPrimerProyecto;
                                _this.mostrarElemenstosdeFormularios(formulario);
                                _this.cargarDatosAFormularioGenerales(datosProyecto);
                                _this.ocultarPestanaAlcance();
                            } else {
                                _this.ocultarElemenstosdeFormularios(formulario);
                            }
                            _this.cargarDatosMaterialTotal();
                            _this.cargarDatosAFormularioPersonalTemporal();
                            _this.cargarDatosTareas();
                            _this.mostrarListaTareas();
                            _this.modal.cerrarModal();
                        });
                    });
                    _this.modal.funcionalidadBotonCancelar(null, function () {
                        _this.mostrarTablaProyectosSinIniciar();
                        _this.modal.cerrarModal();
                    });
                });
            });
        } catch (exception) {
            this.alerta.mostrarAlerta('Error ', exception);
        }
    }

    mostrarTablaProyectosSinIniciar() {
        this.mostrarPanel('panel-table-proyectos');
        this.limpiarFormulario('form-nuevo-proyecto');
        this.ocultarElemento('btn-guardar-cambios');
        this.ocultarElemento('btn-cancelar');
        this.mostrarBtnsAlcance();
    }

    ocultarBtnsAlcance() {
        this.ocultarElemento('btn-generar-solicitud-material');
        this.ocultarElemento('btn-nuevo-nodo');
        this.ocultarElemento('btn-lista-nodos');
        this.mostrarElemento('alerta-solicitud-material-generada');
    }

    mostrarBtnsAlcance() {
        this.mostrarElemento('btn-generar-solicitud-material');
        this.mostrarElemento('btn-nuevo-nodo');
        this.mostrarElemento('btn-lista-nodos');
        this.ocultarElemento('alerta-solicitud-material-generada');
    }

    actulizarTablaProyectosSinIniciar(listaProyectos = []) {

        let _this = this;
        let tablaListaProyectos;
        tablaListaProyectos = _this.tablas.get('data-table-sinIniciar');
        tablaListaProyectos.limpiartabla();
        $.each(listaProyectos, function (key, valor) {
            let fechaInicio = (valor.FechaInicio !== '0000-00-00') ? valor.FechaInicio : '';
            let fechafin = (valor.FechaTermino !== '0000-00-00') ? valor.FechaTermino : '';
            let ticket = (valor.Ticket !== '0') ? valor.Ticket : '';
            tablaListaProyectos.agregarDatosFila([valor.Id, ticket, valor.Nombre, valor.Complejo, valor.Estado, fechaInicio, fechafin]);
        });
    }

    mostrarDatosProyecto(datos) {

        let _this = this;
        let formulario = _this.formularios.get('form-nuevo-proyecto');
        try {
            super.enviarPeticionServidor('panel-table-proyectos', '/Proyectos/Nuevo/Datos_Proyecto', {idProyecto: datos[0]}, function (respuesta) {
                _this.datosGeneralesProyecto = respuesta;
                $("#divListaProyectos").fadeOut(400, function () {
                    $("#divDetallesProyecto").fadeIn(400);
                });
                _this.activarPestanaGenerales();
                _this.mostrarListaTareas();
                if (datos[3] !== null && datos[3] !== '') {
                    _this.ocultarPestanaAlcance();
                    _this.mostrarElemenstosdeFormularios(formulario);
                    _this.cargarDatosAFormularioGenerales(respuesta);
                    _this.cargarDatosMaterialTotal();
                    _this.cargarDatosAFormularioPersonalTemporal();
                    _this.cargarDatosTareas();
                    _this.mostrarOcultarBtnIniciarProyecto();
                    _this.mostrarOcultarBtnsAlcance();
                    _this.initAlcanceListaUbicaciones();
                } else {
                    _this.ocultarElemenstosdeFormularios(formulario);
                    _this.cargarDatosAFormularioGenerales(respuesta);
                }
            });
        } catch (exception) {
            this.modal.mostrarAlerta('Error', exception);
        }

    }

    initAlcanceListaUbicaciones() {
        try {
            let _this = this;
            let alcance = _this.datosGeneralesProyecto.datosProyecto.alcance;
            let tabla = _this.tablas.get('data-table-nodos-capturados');
            let tablaMaterial = _this.tablas.get('data-table-material-nodo');
            let formularioAgregarNodo = _this.formularios.get('form-nuevo-nodo-proyecto');
            let selectConcepto = formularioAgregarNodo.obtenerElemento('select-concepto');
            let selectArea = formularioAgregarNodo.obtenerElemento('select-area');
            let selectUbicacion = formularioAgregarNodo.obtenerElemento('select-ubicacion');
            $("#divUbicaciones").empty().append(_this.datosGeneralesProyecto.Formularios.formularioListaNodosCapturados);
//            _this.modal.mostrarModal('Nodos Capturados', _this.datosGeneralesProyecto.Formularios.formularioListaNodosCapturados);
            _this.modal.eliminarBotonAModal('#btn-definir-nodo-proyecto');
            _this.modal.eliminarBotonAModal('#btn-eliminar-nodo-proyecto');
            _this.modal.ocultarBotonAceptar();
            tabla.iniciarTabla();
            tabla.evento(function () {
                let fila = this;
                let datos = tabla.datosFila(fila);
                let datosNodo;
                $.each(alcance, function (key, value) {
                    if (key === datos[0]) {
                        datosNodo = value;
                    }
                });
                $("#alcance-ubicaciones").append(_this.datosGeneralesProyecto.Formularios.formularioNuevoNodo);
                _this.modal.agregarBotonAModal('<a id="btn-definir-nodo-proyecto" href="javascript:;" class="btn btn-sm btn-success">Generar Nodo</a>');
                _this.modal.agregarBotonAModal('<a id="btn-eliminar-nodo-proyecto" href="javascript:;" class="btn btn-sm btn-danger"><i class="fa fa-trash-o"></i> Eliminar</a>');
                _this.funcionalidadFormularioNodo(datos[0]);
                selectConcepto.definirValor(datosNodo.IdConcepto);
                selectArea.definirValor(datosNodo.IdArea);
                selectUbicacion.definirValor(datosNodo.IdUbicacion);
                $('#btn-definir-nodo-proyecto').trigger('click');
                $.each(datosNodo.Puntos, function (key, value) {
                    tablaMaterial.agregarDatosFila([_this.obtenerTipoNodo(value.Tipo), value.Nombre, value.Accesorio, value.Material, value.Cantidad, value.Tipo, value.IdAccesorio, value.IdMaterial]);
                });
            });
        } catch (exception) {
            this.alerta.mostrarMensajeError('errorNodos', exception);
        }
    }

    ocultarPestanaAlcance() {
        if (this.datosGeneralesProyecto.Tipo === '3') {
            this.ocultarElemento('pestana-alcance');
        }
    }

    mostrarElemenstosdeFormularios(formulario) {
        this.mostrarElemento('pestana-alcance');
        this.mostrarElemento('pestana-material');
        this.mostrarElemento('pestana-personal');
        this.mostrarElemento('pestana-tareas');
        this.mostrarElemento('cabecera-generales');
        this.ocultarElemento('info-sin-complejo');
        this.mostrarElemento('btn-actualizar-proyecto');
        this.ocultarElemento('btn-generar-proyecto');
        this.ocultarElemento('btn-limpiar-formulario');
        this.ocultarElemento('contenedor-select-complejo');
        formulario.bloquearFormulario();
    }

    ocultarElemenstosdeFormularios(formulario) {
        this.ocultarElemento('pestana-alcance');
        this.ocultarElemento('pestana-personal');
        this.ocultarElemento('pestana-tareas');
        this.ocultarElemento('cabecera-generales');
        this.mostrarElemento('info-sin-complejo');
        this.mostrarElemento('contenedor-select-complejo');
        this.mostrarElemento('btn-actualizar-proyecto');
        this.ocultarElemento('btn-generar-proyecto');
        this.ocultarElemento('btn-limpiar-formulario');
        formulario.bloquearFormulario();
    }

    cargarDatosAFormularioGenerales(respuesta) {

        let lideres = [];
        $.each(respuesta.personal, function (key, value) {
            if (value.Perfil === 'Lider') {
                lideres.push(value.Usuario);
            }
        });
        this.definirDatosCabeceraFormulario(respuesta.Nombre, respuesta.NombreComplejo, respuesta.Ticket);
        this.asignarValorElementoPagina('input-nombre-proyecto', respuesta.Nombre);
        this.asignarValorElementoPagina('select-sistemas', respuesta.Sistema);
        this.asignarValorElementoPagina('select-tipo-proyecto', respuesta.Tipo);
        this.asignarValorElementoPagina('select-complejo', respuesta.Complejo);
        this.asignarValorElementoPagina('select-lideres', lideres);
        this.asignarValorElementoPagina('textArea-observaciones', respuesta.Observaciones);
        this.asignarValorElementoPagina('fecha-inicio-proyecto', (respuesta.FechaInicio !== '0000-00-00') ? respuesta.FechaInicio : '');
        this.asignarValorElementoPagina('fecha-final-proyecto', (respuesta.FechaFin !== '0000-00-00') ? respuesta.FechaFin : '');
    }

    cargarDatosMaterialTotal() {

        let tabla = this.tablas.get('data-table-material-alcance');
        tabla.limpiartabla();
        $.each(this.datosGeneralesProyecto.datosProyecto.materiales, function (key, value) {
            let unidad = (value.unidad === 'BOBINA') ? 'MTS' : value.unidad;
            tabla.agregarDatosFila([key, value.nombre, value.numParte, value.total, unidad]);
        });
    }

    cargarDatosAFormularioPersonalTemporal() {
        let tabla = this.tablas.get('data-table-asistentes');
        tabla.limpiartabla();
        $.each(this.datosGeneralesProyecto.personal, function (key, value) {
            if (value.Perfil === 'Asistente') {
                tabla.agregarDatosFila([key, value.Usuario, value.Nombre, value.NSS]);
            }
        });
    }

    mostrarOcultarBtnsAlcance() {

        let _this = this;
        let material = _this.datosGeneralesProyecto.datosProyecto.materiales;

        if (Object.keys(material).length > 0) {
            let solicitud = _this.validarSolicitudMaterial(material);
            if (solicitud.generada) {
                _this.ocultarBtnsAlcance();
                _this.asignarValorElementoPagina('#num-solicitud-material', solicitud.id);
            } else {
                _this.mostrarBtnsAlcance();
            }
        } else {
            _this.mostrarBtnsAlcance();
        }
    }

    validarSolicitudMaterial(material = {}) {

        let solicitudGenerada = false;
        let solicitud = '';

        $.each(material, function (key, value) {
            if (value.solicitud !== '' && value.solicitud !== '0') {
                solicitudGenerada = true;
                solicitud = value.solicitud;
            }
            return false;
        });

        return {'generada': solicitudGenerada, 'id': solicitud};
    }

    definirDatosCabeceraFormulario(nombre, complejo, ticket) {
        this.asignarValorElementoPagina('.nombre-proyecto', nombre);
        this.asignarValorElementoPagina('.nombre-complejo', complejo);
        this.asignarValorElementoPagina('.ticket-proyecto', ticket);
    }

    activarFormularioDeDatosGenerales() {

        let formulario = this.formularios.get('form-nuevo-proyecto');
        formulario.habilitarFormulario();
        this.mostrarElemento('btn-guardar-cambios');
        this.mostrarElemento('btn-cancelar');
        this.ocultarElemento('btn-actualizar-proyecto');
    }

    actualizarDatosGenerales() {
        try {
            let _this = this, contenido, listaComplejos = [];
            let formulario = _this.formularios.get('form-nuevo-proyecto');
            let tablaModal = _this.tablas.get('data-table-nuevoProyecto');
            let datos = formulario.validarFormulario();
            datos.idProyecto = _this.datosGeneralesProyecto.IdProyecto;
            let complejo = formulario.obtenerDato('select-complejo');
            super.enviarPeticionServidor('panel-seccion-proyecto', '/Proyectos/Nuevo/Actualizar_Datos_Proyecto', datos, function (respuesta) {
                _this.datosGeneralesProyecto = respuesta.datosProyecto;
                $.each(respuesta.datosProyectoActualizado, function (key, valor) {
                    if (key !== 'clave') {
                        listaComplejos.push([valor.ticket, key]);
                    }
                });
                if (listaComplejos.length > 1) {

                    contenido = `<div class="row">
                                <div class="col-md-12 text-center">
                                   <p>Se agregaron los siguientes complejos al proyecto <strong><span class="text-success">${formulario.obtenerDato('input-nombre-proyecto')}<span></strong>.</p>                                    
                                </div>
                            </div>
                            ${tablaModal.crearTablaDinamica(['Ticket', 'Sucursal'])}`;
                } else {
                    contenido = `<div class="row">
                                    <div class="col-md-12 text-center">
                                        <p>Se actualizo la información con exito.</p>                                    
                                    </div>
                                </div>`;
                }
                _this.modal.mostrarModal('Proyecto Actualizado', contenido);
                tablaModal.iniciarTabla();
                _this.modal.ocultarBotonAceptar();
                _this.modal.funcionalidadBotonCancelar('Cerrar', function () {
                    _this.modal.cerrarModal();
                });
                $.each(listaComplejos, function (key, valor) {
                    if (key !== 'clave') {
                        tablaModal.agregarDatosFila(valor);
                    }
                });
                if (listaComplejos.length > 0) {
                    _this.definirDatosCabeceraFormulario(formulario.obtenerDato('input-nombre-proyecto'), listaComplejos[0][1], listaComplejos[0][0]);
                    _this.asignarValorElementoPagina('select-complejo', complejo[0]);
                    _this.mostrarElemenstosdeFormularios(formulario);
                    _this.ocultarPestanaAlcance();
                }
                _this.actulizarTablaProyectosSinIniciar(respuesta.listaProyectos);
                _this.mostrarBotonActualizarDatosGenerales();
                _this.mostrarListaTareas();
            });
        } catch (exception) {
            this.modal.mostrarAlerta('Error ', exception);
        }
    }

    cancelarActualizacionDeDatosGenerales() {

        this.cargarDatosAFormularioGenerales(this.datosGeneralesProyecto);
        this.mostrarBotonActualizarDatosGenerales();
    }

    mostrarBotonActualizarDatosGenerales() {

        let formulario = this.formularios.get('form-nuevo-proyecto');
        formulario.bloquearFormulario();
        this.ocultarElemento('btn-guardar-cambios');
        this.ocultarElemento('btn-cancelar');
        this.mostrarElemento('btn-actualizar-proyecto');
    }

    mostrarFormularioNuevoNodo() {
        let _this = this;
        $("#divAgregarUbicacion").empty().append(this.datosGeneralesProyecto.Formularios.formularioNuevoNodo);
        $("#divDetallesProyecto").fadeOut(400, function () {
            $("#divAgregarUbicacion").fadeIn(400, function () {


                $("#select-concepto").select2();
                $("#select-area").attr("disabled", "disabled").select2();
                $("#select-ubicacion").attr("disabled", "disabled").select2();




                $("#select-concepto").on("change", function () {
                    if ($(this).val() !== '') {
                        $("#select-area").empty().append("<option value=''>Seleccionar</option>").removeAttr("disabled").change();
                        $("#select-ubicacion").empty().append("<option value=''>Seleccionar</option>").attr("disabled", "disabled").change();
                        try {
                            var datos = {
                                'concepto': $(this).val()
                            }
                            _this.enviarPeticionServidor('panel-nueva-ubicacion', '/Proyectos/Nuevo/Carga_Areas_By_Concepto', datos, function (respuesta) {
                                if (typeof respuesta.areas !== 'undefined') {
                                    $.each(respuesta.areas, function (k, v) {
                                        $("#select-area").append("<option value='" + v.Id + "'>" + v.Nombre + "</option>");
                                    });
                                }
                            });
                        } catch (e) {
                            _this.modal.mostrarError('errorFormularioUbicacion', 'Ocurrió un error al solicitar las áreas. ' + e);
                        }
                    } else {
                        $("#select-area").empty().append("<option value=''>Seleccionar</option>").attr("disabled", "disabled").change();
                        $("#select-ubicacion").empty().append("<option value=''>Seleccionar</option>").attr("disabled", "disabled").change();
                    }

                });

                $("#select-area").on("change", function () {
                    if ($(this).val() !== '') {
                        $("#select-ubicacion").empty().append("<option value=''>Seleccionar</option>").removeAttr("disabled").change();
                        try {
                            var datos = {
                                'area': $(this).val()
                            }
                            _this.enviarPeticionServidor('panel-nueva-ubicacion', '/Proyectos/Nuevo/Carga_Ubicaciones_By_Area', datos, function (respuesta) {
                                if (typeof respuesta.ubicaciones !== 'undefined') {
                                    $.each(respuesta.ubicaciones, function (k, v) {
                                        $("#select-ubicacion").append("<option value='" + v.Id + "'>" + v.Nombre + "</option>");
                                    });
                                }
                            });
                        } catch (e) {
                            _this.modal.mostrarError('errorFormularioUbicacion', 'Ocurrió un error al solicitar las áreas. ' + e);
                        }
                    } else {
                        $("#select-ubicacion").empty().append("<option value=''>Seleccionar</option>").attr("disabled", "disabled").change();
                    }
                });
            });
        });

        $('#divAgregarUbicacion #btnRegresar').off('click');
        $('#divAgregarUbicacion #btnRegresar').on('click', function () {
            $("#divAgregarUbicacion").fadeOut(400, function () {
                $("#divDetallesProyecto").fadeIn(400);
            });
        });

//        this.modal.mostrarModal('Nueva Ubicación', this.datosGeneralesProyecto.Formularios.formularioNuevoNodo);
//        this.modal.eliminarBotonAModal('#btn-definir-nodo-proyecto');
//        this.modal.eliminarBotonAModal('#btn-eliminar-nodo-proyecto');
//        this.modal.agregarBotonAModal('<a id="btn-definir-nodo-proyecto" href="javascript:;" class="btn btn-sm btn-success">Generar Ubicación</a>');
//        this.funcionalidadFormularioNodo();
    }

    mostrarFormularioNuevaUbicacion() {

    }

    mostrarListaNodosCapturados() {
        try {
            let _this = this;
            let alcance = _this.datosGeneralesProyecto.datosProyecto.alcance;
            let tabla = _this.tablas.get('data-table-nodos-capturados');
            let tablaMaterial = _this.tablas.get('data-table-material-nodo');
            let formularioAgregarNodo = _this.formularios.get('form-nuevo-nodo-proyecto');
            let selectConcepto = formularioAgregarNodo.obtenerElemento('select-concepto');
            let selectArea = formularioAgregarNodo.obtenerElemento('select-area');
            let selectUbicacion = formularioAgregarNodo.obtenerElemento('select-ubicacion');
            _this.modal.mostrarModal('Nodos Capturados', _this.datosGeneralesProyecto.Formularios.formularioListaNodosCapturados);
            _this.modal.eliminarBotonAModal('#btn-definir-nodo-proyecto');
            _this.modal.eliminarBotonAModal('#btn-eliminar-nodo-proyecto');
            _this.modal.ocultarBotonAceptar();
            tabla.iniciarTabla();
            tabla.evento(function () {
                let fila = this;
                let datos = tabla.datosFila(fila);
                let datosNodo;
                $.each(alcance, function (key, value) {
                    if (key === datos[0]) {
                        datosNodo = value;
                    }
                });
                _this.modal.agregarContenido(_this.datosGeneralesProyecto.Formularios.formularioNuevoNodo);
                _this.modal.agregarBotonAModal('<a id="btn-definir-nodo-proyecto" href="javascript:;" class="btn btn-sm btn-success">Generar Nodo</a>');
                _this.modal.agregarBotonAModal('<a id="btn-eliminar-nodo-proyecto" href="javascript:;" class="btn btn-sm btn-danger"><i class="fa fa-trash-o"></i> Eliminar</a>');
                _this.funcionalidadFormularioNodo(datos[0]);
                selectConcepto.definirValor(datosNodo.IdConcepto);
                selectArea.definirValor(datosNodo.IdArea);
                selectUbicacion.definirValor(datosNodo.IdUbicacion);
                $('#btn-definir-nodo-proyecto').trigger('click');
                $.each(datosNodo.Puntos, function (key, value) {
                    tablaMaterial.agregarDatosFila([_this.obtenerTipoNodo(value.Tipo), value.Nombre, value.Accesorio, value.Material, value.Cantidad, value.Tipo, value.IdAccesorio, value.IdMaterial]);
                });
            });
        } catch (exception) {
            this.alerta.mostrarMensajeError('errorNodos', exception);
        }
    }

    funcionalidadFormularioNodo(IdNodo = '') {

        let _this = this, datosUbicacion;
        let formularioAgregarNodo = _this.formularios.get('form-nuevo-nodo-proyecto');
        let formularioMaterialNodo = _this.formularios.get('form-define-material-nodo');
        let tabla = _this.tablas.get('data-table-material-nodo');
        formularioAgregarNodo.iniciarElementos();
        formularioMaterialNodo.iniciarElementos();
        tabla.iniciarTabla();
        _this.escucharEventosDeElementosAlcance();

        $('#btn-definir-nodo-proyecto').on('click', function () {
            try {
                datosUbicacion = formularioAgregarNodo.validarFormulario();
                (IdNodo === '') ? _this.validarUbicacion(formularioAgregarNodo) : null;
                _this.mostrarElemento('contenedor-formulario-nodo-material');
                formularioAgregarNodo.bloquearFormulario();
                _this.modal.eliminarBotonAModal('#btn-definir-nodo-proyecto');
                _this.modal.funcionalidadBotonAceptar('<i class="fa fa-floppy-o"></i> Guardar', function () {
                    try {
                        let datos = {};
                        let nodos = [];
                        $.each(tabla.datosTabla(), function (key, value) {
                            nodos.push(value);
                        });
                        datos.idProyecto = _this.datosGeneralesProyecto.IdProyecto;
                        datos.ubicacion = datosUbicacion;
                        datos.nodos = nodos;
                        datos.idNodo = IdNodo;
                        if (datos.nodos.length > 0) {
                            _this.enviarPeticionServidor('modal-dialogo', '/Proyectos/Nuevo/Guardar_Nodo_Alcance', datos, function (respuesta) {
                                _this.datosGeneralesProyecto = respuesta;
                                _this.cargarDatosMaterialTotal();
                                _this.modal.cerrarModal();
                            });
                        } else {
                            throw 'Debes ingresar al menos un punto del nodo';
                        }
                    } catch (exception) {
                        _this.alerta.mostrarMensajeError('errorGuardarFormulario ', exception);
                    }
                });
            } catch (exception) {
                _this.alerta.mostrarMensajeError('errorFormulario ', exception);
            }
        });

        $('#btn-agregar-material').on('click', function (e) {
            try {
                e.preventDefault();
                let datosMaterial = formularioMaterialNodo.validarFormulario();
                tabla.validarFilaRepetida([
                    datosMaterial.selectTexto['select-tipo-nodo'],
                    datosMaterial['input-nombre-nodo'],
                    datosMaterial.selectTexto['select-accesorio'],
                    datosMaterial.selectTexto['select-material-nodo'],
                    datosMaterial['input-cantidad-material'],
                    datosMaterial['select-tipo-nodo'],
                    datosMaterial['select-accesorio'],
                    datosMaterial['select-material-nodo']
                ], [0, 1, 2]);
                formularioMaterialNodo.limpiarElemento('select-accesorio');
                formularioMaterialNodo.limpiarElemento('select-material-nodo');
                formularioMaterialNodo.limpiarElemento('input-cantidad-material');
            } catch (exception) {
                _this.alerta.mostrarMensajeError('mensajeError', exception);
            }
        });
        tabla.evento(function () {

            let fila = this;
            if (tabla.validarNumeroFilas()) {
                tabla.eliminarFila(fila);
            }
        });
        _this.modal.ocultarBotonAceptar();
        _this.modal.funcionalidadBotonCancelar('Cancelar', function () {
            _this.modal.eliminarBotonAModal('#btn-definir-nodo-proyecto');
            _this.modal.eliminarBotonAModal('#btn-eliminar-nodo-proyecto');
            _this.modal.cerrarModal();
        });
        $('#btn-eliminar-nodo-proyecto').on('click', function () {
            try {
                let datos = {idProyecto: _this.datosGeneralesProyecto.IdProyecto, Nodo: IdNodo};
                _this.enviarPeticionServidor('modal-dialogo', '/Proyectos/Nuevo/Eliminar_Nodo_Alcance', datos, function (respuesta) {
                    _this.datosGeneralesProyecto = respuesta;
                    _this.cargarDatosMaterialTotal();
                    _this.modal.eliminarBotonAModal('#btn-eliminar-nodo-proyecto');
                    _this.modal.cerrarModal();
                });
            } catch (exception) {
                _this.alerta.mostrarMensajeError('mensajeError', exception);
            }
        });
    }

    validarUbicacion(formulario) {
        let _this = this;
        let alcance = _this.datosGeneralesProyecto.datosProyecto.alcance;
        let selectConcepto = formulario.obtenerElemento('select-concepto');
        let selectArea = formulario.obtenerElemento('select-area');
        let selectUbicacion = formulario.obtenerElemento('select-ubicacion');
        let valorConcepto = selectConcepto.obtenerValor();
        let valorArea = selectArea.obtenerValor();
        let valorUbicacion = selectUbicacion.obtenerValor();
        let repetido = false;

        $.each(alcance, function (key, value) {
            if (valorConcepto === value.IdConcepto && valorArea === value.IdArea && valorUbicacion === value.IdUbicacion) {
                repetido = true;
                return false;
            }
        });

        if (repetido) {
            throw 'La ubicación ya fue definida. Ingrese una ubicación diferente.';
        }
    }

    escucharEventosDeElementosAlcance() {
        let _this = this;
        let formularioAgregarNodo = _this.formularios.get('form-nuevo-nodo-proyecto');
        let formularioMaterialNodo = _this.formularios.get('form-define-material-nodo');
        let selectConcepto = formularioAgregarNodo.obtenerElemento('select-concepto');
        let selectArea = formularioAgregarNodo.obtenerElemento('select-area');
        let selectAccesorio = formularioMaterialNodo.obtenerElemento('select-accesorio');

        selectConcepto.evento('change', function () {
            selectConcepto.cargarElementosASelect('select-area', _this.datosGeneralesProyecto.Formularios.listasSelects.Areas, 'Concepto');
        });

        selectArea.evento('change', function () {
            selectArea.cargarElementosASelect('select-ubicacion', _this.datosGeneralesProyecto.Formularios.listasSelects.Ubicaciones, 'Area');
        });

        selectAccesorio.evento('change', function () {
            selectAccesorio.cargarElementosASelect('select-material-nodo', _this.datosGeneralesProyecto.Formularios.listasSelectsMaterial.Material, 'Accesorio');
        });


    }

    agregarAsistenteProyecto() {
        try {

            let _this = this;
            let formulario = _this.formularios.get('form-agregar-asistente');
            let tabla = _this.tablas.get('data-table-asistentes');
            let datos = formulario.validarFormulario();
            datos.idProyecto = _this.datosGeneralesProyecto.IdProyecto;
            $.each(tabla.datosTabla(), function (key, value) {
                if (datos['select-asistente'] === value[1]) {
                    throw 'Ya existe el asistente en el proyecto.';
                }
            });
            _this.enviarPeticionServidor('panel-seccion-proyecto', '/Proyectos/Nuevo/Guardar_Asistente', datos, function (respuesta) {
                _this.datosGeneralesProyecto = respuesta;
                _this.cargarDatosAFormularioPersonalTemporal();
            });
        } catch (exception) {
            this.alerta.mostrarAlerta('Error', exception);
        }

    }

    quitarAsistenteProyecto(datos = []) {
        try {
            let _this = this;
            datos.idProyecto = _this.datosGeneralesProyecto.IdProyecto;
            _this.enviarPeticionServidor('panel-seccion-proyecto', '/Proyectos/Nuevo/Eliminar_Asistente', datos, function (respuesta) {
                _this.datosGeneralesProyecto = respuesta;
                _this.cargarDatosAFormularioPersonalTemporal();
            });
        } catch (exception) {
            this.modal.mostrarAlerta('Error', exception);
    }

    }

    mostraFormularioSolicitudPersonal() {

        let _this = this;
        let formulario = _this.formularios.get('form-solicitud-personal');
        _this.modal.mostrarModal('Solicitud Personal', _this.datosGeneralesProyecto.Formularios.formularioSolicitudPersonal);
        formulario.iniciarElementos();
        _this.modal.funcionalidadBotonAceptar('<i class="fa fa-file"></i> Generar Solicitud', function () {
            try {
                let datos = formulario.validarFormulario();
                datos.idProyecto = _this.datosGeneralesProyecto.IdProyecto;
                _this.enviarPeticionServidor('modal-dialogo', '/Proyectos/Nuevo/Generar_Solicitud', datos, function (respuesta) {
                    _this.datosGeneralesProyecto = respuesta.datos;
                    _this.modal.agregarContenido(`<div class="row">
                                                    <div class="col-md-12 text-center">
                                                        <p>Se genero la solicitud ${respuesta.solicitud} con éxito. Se notifico al departamento de recursos humanos para la contratación del personal.</p>
                                                    </div>
                                                </div>`);
                    _this.modal.ocultarBotonAceptar();
                    _this.modal.cambiarValorBotonCanelar('Cerrar');
                });
            } catch (exception) {

            }
        });
    }

    mostrarFormularioNuevoComplejo() {

        let _this = this;
        let formulario = _this.formularios.get('form-agregar-complejo-a-proyecto');
        _this.modal.mostrarModal('Nuevo Complejo', _this.datosGeneralesProyecto.Formularios.formularioNuevoComplejo);
        formulario.iniciarElementos();
        _this.modal.funcionalidadBotonAceptar(null, function () {
            try {
                let datos = formulario.validarFormulario();
                datos.idProyecto = _this.datosGeneralesProyecto.IdProyecto;
                _this.enviarPeticionServidor('modal-dialogo', '/Proyectos/Nuevo/Agregar_Complejo', datos, function (respuesta) {
                    _this.datosGeneralesProyecto = respuesta;
                    let tablaModal = _this.tablas.get('data-table-nuevoProyecto');
                    let contenido = `<div class="row">
                                <div class="col-md-12 text-center">
                                    <p>Se agregaron al proyecto <strong><span class="text-success">${_this.datosGeneralesProyecto.Nombre}<span></strong> los siguientes complejos.</p>                                    
                                </div>
                            </div>
                            ${tablaModal.crearTablaDinamica(['Ticket', 'Sucursal'])}`;
                    _this.modal.agregarContenido(contenido);
                    tablaModal.iniciarTabla();
                    _this.modal.ocultarBotonAceptar();
                    _this.modal.cambiarValorBotonCanelar('Cerrar');
                    $.each(_this.datosGeneralesProyecto.NuevosComplejos, function (key, valor) {
                        if (key !== 'clave') {
                            tablaModal.agregarDatosFila([valor.ticket, key]);
                        }
                    });
                    _this.actulizarTablaProyectosSinIniciar(respuesta.listaProyectos);
                });
            } catch (exception) {

            }
        });
    }

    eliminarComplejoDeProyecto(peticion = '') {
        let _this = this;
        let formulario = _this.formularios.get('form-eliminar-proyecto');
        let titulo = (peticion === 'Eliminar_Complejo') ? 'Eliminar Complejo De Proyecto' : 'Eliminar Proyecto';
        let mensaje = (peticion === 'Eliminar_Complejo') ? 'Al realizar esta acción ya no se podra recuperar la información y se eleminara el complejo del proyecto.'
                : 'Al realizar esta acción ya no se podra recuperar la información y se eleminara todos los complejos del proyecto.';

        _this.modal.mostrarModal(titulo, _this.datosGeneralesProyecto.Formularios.formularioEliminarComplejoProyecto);
        formulario.iniciarElementos();
        _this.asignarValorElementoPagina('#mensaje-eliminar', mensaje);

        _this.modal.funcionalidadBotonAceptar(null, function () {
            try {
                let datos = formulario.validarFormulario();
                datos.idProyecto = _this.datosGeneralesProyecto.IdProyecto;

                _this.enviarPeticionServidor('modal-dialogo', `/Proyectos/Nuevo/${peticion}`, datos, function (respuesta) {
                    _this.actulizarTablaProyectosSinIniciar(respuesta.listaProyectos);
                    _this.mostrarTablaProyectosSinIniciar();
                    _this.modal.cerrarModal();
                });
            } catch (exception) {

            }
        });

    }

    generarSolicitudMaterial() {

        let _this = this;
        let contenido = `<div class="row">
                            <div class="col-md-12 text-center">
                                <p>Se va ha generar una solicitud de material para el área de almacén. Al realizar esta acción ya no podras agregar mas nodos al proyecto.</p>
                                <p>¿Deseas generar la solicitud ?</p>                                
                            </div>
                        </div>`;
        _this.modal.mostrarModal('Solicitud Material', contenido);

        _this.modal.funcionalidadBotonAceptar(null, function () {
            try {
                let datos = {idProyecto: _this.datosGeneralesProyecto.IdProyecto};
                _this.validarMaterial();
                _this.enviarPeticionServidor('modal-dialogo', `/Proyectos/Nuevo/Solicitud_Material`, datos, function (respuesta) {
                    _this.datosGeneralesProyecto = respuesta.datos;
                    contenido = `<div class="row">
                            <div class="col-md-12 text-center">
                                <p>Se genero la solicitud ${respuesta.solicitud} al departamento de almacén.</p>
                            </div>
                        </div>`;
                    _this.modal.agregarContenido(contenido);
                    _this.modal.ocultarBotonAceptar();
                    _this.ocultarElemento('btn-generar-solicitud-material');
                    _this.ocultarElemento('btn-nuevo-nodo');
                    _this.ocultarElemento('btn-lista-nodos');
                    _this.modal.cambiarValorBotonCanelar('Cerrar');
                });
            } catch (exception) {
                _this.modal.ocultarBotonAceptar();
                _this.modal.cambiarValorBotonCanelar('Cerrar');
            }
        });

    }

    validarMaterial() {

        let material = this.datosGeneralesProyecto.datosProyecto.materiales;

        if (!Object.keys(material).length) {
            this.mostrarMensajeError('No tienes definido ningun material para generar la solicitud');
            throw 0;
        }
    }

    iniciarProyecto() {
        let _this = this;
        let contenido = `<div class="row">
                            <div class="col-md-12 text-center">
                                <p>Al realizar esta acción se iniciara el complejo para su seguimiento.</p>
                                <p>¿Estas seguro de iniciar el complejo del proyecto?</p>
                            </div>
                        </div>`;
        _this.modal.mostrarModal('Iniciar Proyecto', contenido);

        _this.modal.funcionalidadBotonAceptar(null, function () {
            try {

                let alcance = _this.datosGeneralesProyecto.datosProyecto.alcance;

                if (Object.keys(alcance).length) {
                    _this.validarSolicitudGeneradaEnProyecto();
                    let datos = {idProyecto: _this.datosGeneralesProyecto.IdProyecto};
                    _this.enviarPeticionServidor('modal-dialogo', `/Proyectos/Nuevo/Iniciar_Proyecto`, datos, function (respuesta) {
                        contenido = `<div class="row">
                            <div class="col-md-12 text-center">
                                <p>Se inicio el proyecto con exito.</p>                                
                            </div>
                        </div>`;
                        _this.actulizarTablaProyectosSinIniciar(respuesta.listaProyectos);
                        _this.mostrarTablaProyectosSinIniciar();
                        _this.modal.agregarContenido(contenido);
                        _this.modal.ocultarBotonAceptar();
                        _this.modal.cambiarValorBotonCanelar('Cerrar');
                    });
                } else {
                    _this.mostrarMensajeError('Para poder iniciar el proyecto es necesario que definias al menos un nodo en el alcance.');
                    throw 0;
                }
            } catch (exception) {
                _this.modal.ocultarBotonAceptar();
                _this.modal.cambiarValorBotonCanelar('Cerrar');
            }
        });
    }

    validarSolicitudGeneradaEnProyecto() {

        let _this = this;
        let material = _this.datosGeneralesProyecto.datosProyecto.materiales;

        if (Object.keys(material).length) {

            let solicitud = _this.validarSolicitudMaterial(material);

            if (!solicitud.generada) {
                _this.mostrarMensajeError('Para poder iniciar el proyecto debes generar la solicitud de material a almacen.');
                throw 0;
            }
        }
    }

    mostrarMensajeError(mensaje = '') {
        let contenido = `<div class="row">
                                    <div class="col-md-12 text-center">
                                        <p>${mensaje}</p>
                                    </div>
                                </div>`;
        this.modal.agregarContenido(contenido);
    }

    obtenerReporteMaterial() {
        try {
            let _this = this;
            this.enviarPeticionServidor('panel-seccion-proyecto', '/Proyectos/Nuevo/Reporte_Material', {idProyecto: _this.datosGeneralesProyecto.IdProyecto}, function (respuesta) {
                window.open(respuesta, '_blank');
            });
        } catch (exception) {
            this.alerta.mostrarAlerta('Error ', exception);
        }

    }

    obtenerReporteInicioProyecto() {
        try {
            let _this = this;
            this.enviarPeticionServidor('panel-seccion-proyecto', '/Proyectos/Nuevo/Reporte_Inicio_Proyecto', {idProyecto: _this.datosGeneralesProyecto.IdProyecto}, function (respuesta) {
                window.open(respuesta, '_blank');
            });
        } catch (exception) {
            this.alerta.mostrarAlerta('Error ', exception);
        }
    }

}