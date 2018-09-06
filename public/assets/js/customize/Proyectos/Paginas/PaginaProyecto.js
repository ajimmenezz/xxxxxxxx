class PaginaProyecto extends Pagina {

    constructor(objetos = new Map) {
        super(objetos);
        this.datosGeneralesProyecto;
        this.pagina;
        this.tareaSeleccionada;
        this.datosTarea;
        this.actividadSeleccionada;
        this.datosActividad;
        this.nodoSeleccionado;
        this.idAlcanceNodosTarea;
        this.listaMaterial = [];
        this.listaUbicacion = [];
        this.listaNodos = [];
    }

    activarPestanaGenerales() {
        $('#pestana-generales a').trigger('click');
    }

    mostrarPanel(panel = '') {
        if (panel === 'panel-table-proyectos') {
            this.mostrarElemento('panel-table-proyectos');
            this.ocultarElemento('panel-seccion-proyecto');
        } else if (panel === 'panel-seccion-proyecto') {
            this.mostrarElemento('panel-seccion-proyecto');
            this.ocultarElemento('panel-table-proyectos');
    }
    }

    cargarDatosTareas() {
        let _this = this;
        let tabla = _this.tablas.get('data-table-tareas');
        let tareas = _this.datosGeneralesProyecto.datosProyecto.tareas;
        let avance = null;

        tabla.limpiartabla();
        $.each(tareas, function (key, value) {
            if (_this.pagina !== 'seguimiento') {
                tabla.agregarDatosFila([key, value.Nombre, value.Lider, value.FechaInicio, value.FechaFin]);
            } else {
                avance = (value.Porcentaje !== '0%') ? value.Porcentaje : value.PorcentajeNodos;
                tabla.agregarDatosFila([key, value.Nombre, value.Lider, value.FechaInicio, value.FechaFin, avance]);
            }
        });
    }

    mostrarFormularioNuevaTarea() {
        let _this = this;
        _this.idAlcanceNodosTarea = null;
        let formulario = _this.formularios.get('form-nueva-tarea');
        let fechaIncio = formulario.obtenerElemento('fecha-inicio-tarea');
        let fechaFin = formulario.obtenerElemento('fecha-fin-tarea');
        _this.tareaSeleccionada = null;

        _this.mostrarFormularioTarea('Nueva Tarea', formulario);
        fechaIncio.actualizarFecha(_this.datosGeneralesProyecto.FechaInicio);
        fechaFin.actualizarFecha(_this.datosGeneralesProyecto.FechaInicio);
        _this.escucharEventosDeElementosTarea(formulario);

        _this.modal.funcionalidadBotonAceptar('<i class="fa fa-floppy-o"></i> Guardar', function () {
            try {
                let datos = formulario.validarFormulario();
                datos.nodos = _this.obtenerListaNodosAsginadosATarea();
                datos.idProyecto = _this.datosGeneralesProyecto.IdProyecto;
                datos.idAlcanceNodos = _this.idAlcanceNodosTarea;
                _this.idAlcanceNodosTarea = null;
                if (datos['fecha-inicio-tarea'] === '') {
                    throw 'Debes definir la fecha de inicio de la tarea';
                } else if (datos['fecha-fin-tarea'] === '') {
                    throw 'Debes definir la fecha de fin de la tarea';
                }
                _this.enviarPeticionServidor('modal-dialogo', '/Proyectos/Nuevo/Nueva_Tarea', datos, function (respuesta) {
                    _this.datosGeneralesProyecto = respuesta;
                    _this.cargarDatosTareas();
                    _this.mostrarOcultarBtnIniciarProyecto();
                    _this.crearGraficaGantt();
                    _this.modal.cerrarModal();
                });
            } catch (exception) {
                _this.alerta.mostrarMensajeError('errorTareaNueva ', exception);
            }
        });
    }

    escucharEventosDeElementosTarea(formulario) {

        let _this = this;
        let formularioNodos = _this.formularios.get('form-nodos-tarea');
        let selectArea = formulario.obtenerElemento('select-area-tarea');
        let selectLider = formulario.obtenerElemento('select-lider-tarea');
        let selectAsistente = formulario.obtenerElemento('select-asistente-tarea');
        let selectUbicacion = formularioNodos.obtenerElemento('select-ubicacion-nodo-tarea');
        let selectNodo = formularioNodos.obtenerElemento('select-nodo-tarea');
        let inputFechaInicio = formulario.obtenerElemento('fecha-inicio-tarea');
        let inputFechaFin = formulario.obtenerElemento('fecha-fin-tarea');
        let tabla = _this.tablas.get('data-table-nodos-tarea');

        selectArea.evento('change', function () {
            let datosUbicaciones = _this.obtenerUbicacionesDeAlcance(selectArea.obtenerValor());
            selectArea.cargarElementosASelect('select-ubicacion-nodo-tarea', datosUbicaciones, 'Area');
            selectNodo.cargaDatosEnSelect();
            tabla.limpiartabla();
        });

        selectLider.evento('change', function () {
            _this.llamarValidarDatosTarea(formulario);
        });

        selectAsistente.evento('change', function () {
            _this.llamarValidarDatosTarea(formulario);
        });

        inputFechaInicio.evento('changeDate', function () {
            _this.llamarValidarDatosTarea(formulario);
        });

        inputFechaFin.evento('changeDate', function () {
            _this.llamarValidarDatosTarea(formulario);
        });

        selectUbicacion.evento('change', function () {
            let puntos = _this.generarNodosTarea();
            selectNodo.cargaDatosEnSelect(puntos);
        });

        $('#btn-agregar-nodos-tarea').on('click', function () {
            try {
                formularioNodos.validarFormulario();
                let nodo = {Ubicacion: selectUbicacion.obtenerTexto(), Nodo: selectNodo.obtenerDatosSeleccionado()};
                _this.agregarNodo(nodo);
                _this.bloquearBoton('select-ubicacion-nodo-tarea');
//                selectNodo.definirValor(); //Tiene un error donde no borra el tipo cuando se cambia la opcion por este evento que es el metodo del plugin por tal motivo no se muestra en la tabla el tipo.
            } catch (exception) {
                _this.alerta.mostrarMensajeError('errorAgregarNodo ', exception);
            }
        });

        $('#btn-limpiar-tabla').on('click', function () {
            tabla.limpiartabla();
            _this.idAlcanceNodosTarea = null;
            _this.habilitarBoton('select-ubicacion-nodo-tarea');
        });

        $('#btn-mostrar-formulario-nodos-tarea').on('click', function () {
            _this.mostrarElemento('seccion-nodos-tarea');
            _this.mostrarElemento('btn-ocultar-formulario-nodos-tarea');
            _this.ocultarElemento('btn-mostrar-formulario-nodos-tarea');
        });

        $('#btn-ocultar-formulario-nodos-tarea').on('click', function () {
            _this.mostrarElemento('btn-mostrar-formulario-nodos-tarea');
            _this.ocultarElemento('seccion-nodos-tarea');
            _this.ocultarElemento('btn-ocultar-formulario-nodos-tarea');
        });

        tabla.evento(function () {
            if (_this.pagina !== 'seguimiento') {
                tabla.eliminarFila(this);
            }
            let datos = tabla.datosTabla();
            if (datos.length === 0) {
                _this.habilitarBoton('select-ubicacion-nodo-tarea');
            }
        });
    }

    obtenerUbicacionesDeAlcance(areaSeleccionada = '') {
        let ubicaciones = [], ubicacionesAlcance = [];

        $.each(this.datosGeneralesProyecto.datosProyecto.alcance, function (key, value) {
            if (areaSeleccionada === value.IdArea) {
                ubicacionesAlcance.push(value.IdUbicacion);
            }
        });

        $.each(this.datosGeneralesProyecto.Formularios.listasSelects.Ubicaciones, function (key, value) {
            if ($.inArray(value.Id, ubicacionesAlcance) !== -1) {
                ubicaciones.push(value);
            }
        });

        return ubicaciones;
    }

    llamarValidarDatosTarea(formulario) {
        try {
            let datos = formulario.obtenerDatosFormulario();
            this.validarFechaDentroProyecto(datos, formulario);
            this.validarTareaRepetida(datos);
        } catch (exception) {
            this.alerta.mostrarMensajeError('errorTareaNueva ', exception);
        }
    }

    validarFechaDentroProyecto(datos = [], formulario) {
        let _this = this;
        let inputFechaInicio = formulario.obtenerElemento('fecha-inicio-tarea');
        let inputFechaFin = formulario.obtenerElemento('fecha-fin-tarea');
        let inicio = _this.datosGeneralesProyecto.FechaInicio;
        let dias = _this.diferenciaFechas(inicio, datos['fecha-inicio-tarea']);

        if (dias < 0) {
            inputFechaInicio.definirValor('');
            inputFechaFin.definirValor('');
            throw `La tarea debe tener una fecha mayor a la de ${inicio}`;
    }
    }

    validarTareaRepetida(datos = []) {

        let _this = this;
        let tareaConMismaFecha = [];
        let listaLideres = null, listaAsistentes = null, mensaje = '';
        let tareas = _this.datosGeneralesProyecto.datosProyecto.tareas;

        $.each(tareas, function (key, value) {
            if (value.FechaFin === datos['fecha-fin-tarea'] && value.FechaInicio === datos['fecha-inicio-tarea']) {
                tareaConMismaFecha.push(value);
            }
        });

        listaLideres = _this.validarLiderEnTareasExistentes(tareaConMismaFecha, datos['select-lider-tarea']);
        listaAsistentes = _this.validarAsistenteEnTareasExistentes(tareaConMismaFecha, datos['select-asistente-tarea']);

        if (tareaConMismaFecha.length > 0) {
            mensaje = _this.generarMensajeAsistentes(listaAsistentes);
            if (listaLideres.length > 0 && listaAsistentes.length === 0) {
                throw `El lider ${listaLideres[0]} ya esta asignado a otra tarea con las mismas fechas.`;
            } else if (listaLideres.length > 0 && listaAsistentes.length > 0) {
                throw `El lider ${listaLideres[0]} y ${mensaje} ya estan asignado a otra tarea con las mismas fechas`;
            } else if (listaAsistentes.length > 0) {
                throw `${mensaje} ya estan asignado a otra tarea con las mismas fechas.`;
            }
    }
    }

    validarLiderEnTareasExistentes(tareasExistentes = [], liderNuevaTarea = '') {

        let lider = [];
        $.each(tareasExistentes, function (key, value) {
            if (liderNuevaTarea === value.IdLider) {
                lider.push(value.Lider);
            }
        });
        return lider;
    }

    validarAsistenteEnTareasExistentes(tareasExistentes = [], asistentesNuevaTarea = []) {

        let _this = this;
        let existeAsistente = [], asistente = '';
        $.each(tareasExistentes, function (key, value) {
            $.each(value.Asistente, function (key2, item) {
                if ($.inArray(item, asistentesNuevaTarea) !== -1) {
                    asistente = _this.obtenerDatosDeAsistente(item);
                    if (existeAsistente.length === 0) {
                        existeAsistente.push(asistente);
                    } else {
                        let existe = false;
                        $.each(existeAsistente, function (k, dato) {
                            if (dato === asistente) {
                                existe = true;
                            }
                        });
                        if (!existe) {
                            existeAsistente.push(asistente);
                        }
                    }
                }
            });
        });
        return existeAsistente;
    }

    obtenerDatosDeAsistente(idUsuario = '') {

        let personal = this.datosGeneralesProyecto.personal;
        let nombre = '';
        $.each(personal, function (key, value) {
            if (value.Usuario === idUsuario) {
                nombre = value.Nombre;
                return false;
            }
        });
        return nombre;
    }

    generarNodosTarea() {
        try {
            let _this = this, nodos = [], lista = [];
            let puntos = _this.obtenerPuntosAlcance();

            $.each(puntos, function (key, value) {
                if ($.inArray(value.Nombre, nodos) === -1) {
                    nodos.push(value.Nombre);
                    lista.push({id: nodos.length, text: value.Nombre, tipo: value.Tipo});
                }
            });
            return lista;

        } catch (exception) {
            this.alerta.mostrarMensajeError('errorAgregarNodo ', exception);
        }
    }

    obtenerPuntosAlcance() {
        let _this = this;
        let formulario = _this.formularios.get('form-nueva-tarea');
        let formularioNodos = _this.formularios.get('form-nodos-tarea');
        let selectArea = formulario.obtenerElemento('select-area-tarea');
        let selectUbicacion = formularioNodos.obtenerElemento('select-ubicacion-nodo-tarea');
        let area = selectArea.obtenerValor();
        let ubicacion = selectUbicacion.obtenerValor();
        let puntos = [];

        $.each(_this.datosGeneralesProyecto.datosProyecto.alcance, function (key, value) {
            if (area === value.IdArea && ubicacion === value.IdUbicacion) {
                puntos = value.Puntos;
                _this.idAlcanceNodosTarea = key;
                return false;
            }
        });

        if (puntos.length > 0) {
            return puntos;
        } else {
            throw 'No tiene definido nodos para esta ubicación';
        }
    }

    obtenerNodosTarea() {
        let _this = this, nodosTarea = [];
        if (_this.idAlcanceNodosTarea !== null) {
            $.each(_this.datosGeneralesProyecto.datosProyecto.tareas, function (key, value) {
                if (value.Alcance === _this.idAlcanceNodosTarea) {
                    if (_this.tareaSeleccionada === null) {
                        $.each(value.Nodos, function (k, v) {
                            nodosTarea.push(v.Nombre);
                        });
                    } else if (_this.tareaSeleccionada !== key) {
                        $.each(value.Nodos, function (k, v) {
                            nodosTarea.push(v.Nombre);
                        });
                    }
                }
            });
        }
        return nodosTarea;
    }

    agregarNodo(datos = {}){
        let _this = this, mensaje = '';
        let nodosTareas = _this.obtenerNodosTarea();

        if (nodosTareas.length > 0) {
            if ($.inArray(datos.Nodo[0].text, nodosTareas) !== -1) {
                mensaje = 'Este nodo ya esta definido en otra tarea';
            } else {
                mensaje = _this.validarNodoEnTabla(datos);
            }
        } else {
            mensaje = _this.validarNodoEnTabla(datos);
        }
        if (mensaje !== '') {
            throw mensaje;
        } else {
            _this.insertarNodoTabla([datos.Ubicacion, _this.obtenerTipoNodo(datos.Nodo[0].tipo), datos.Nodo[0].text, '']);
    }
    }

    validarNodoEnTabla(datos = {}) {
        let _this = this, mensaje = '';
        let tabla = _this.tablas.get('data-table-nodos-tarea');
        let datosTabla = tabla.datosTabla();

        $.each(datosTabla, function (key, value) {
            if (datos.Ubicacion === value[0] && datos.Nodo[0].text === value[2]) {
                mensaje = 'Ya se agrego el nodo a la tarea.';
                return false;
            }
        });
        return mensaje;
    }

    insertarNodoTabla(punto = []) {
        let _this = this;
        let tabla = _this.tablas.get('data-table-nodos-tarea');
        tabla.agregarDatosFila(punto);
    }

    obtenerListaNodosAsginadosATarea() {
        let _this = this, lista = [];
        let tabla = _this.tablas.get('data-table-nodos-tarea');
        $.each(tabla.datosTabla(), function (key, value) {
            lista.push(value[2]);
        });
        return lista;
    }

    generarMensajeAsistentes(asistente = []) {

        let mensaje = '';
        if (asistente.length > 0) {
            $.each(asistente, function (key, item) {
                if (key === 0) {
                    mensaje += ` los técnicos ${item} `;
                } else {
                    mensaje += `, ${item}`;
                }
            });
        }

        return mensaje;
    }

    actualizarTarea(datos = []) {
        let _this = this;
        let data;
        let tarea = _this.datosGeneralesProyecto.datosProyecto.tareas[datos[0]];
        let formulario = _this.formularios.get('form-nueva-tarea');
        let fechaIncio = formulario.obtenerElemento('fecha-inicio-tarea');
        let fechaFin = formulario.obtenerElemento('fecha-fin-tarea');
        _this.tareaSeleccionada = datos[0];
        _this.mostrarFormularioTarea('Actualizar Tarea', formulario);
        _this.escucharEventosDeElementosTarea(formulario);
        _this.modal.agregarBotonAModal('<a id="btn-eliminar-tarea" href="javascript:;" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i> Eliminar</a>');
        _this.asignarValorElementoPagina('input-nombre-tarea', tarea.Nombre);
        _this.asignarValorElementoPagina('select-area-tarea', tarea.Area);
        _this.asignarValorElementoPagina('select-lider-tarea', tarea.IdLider);
        _this.asignarValorElementoPagina('select-asistente-tarea', tarea.Asistente);
        fechaIncio.actualizarFecha(tarea.FechaInicio);
        fechaFin.actualizarFecha(tarea.FechaFin);
        _this.ocultarElemento('contendor-checkbox-tarea');
        _this.cargarNodosTabla(tarea);
        _this.modal.funcionalidadBotonAceptar('<i class="fa fa-floppy-o"></i> Guardar Cambios', function () {
            try {
                data = _this.validarFormularioTarea(formulario);
                data.nodos = _this.obtenerListaNodosAsginadosATarea();
                data.idAlcanceNodos = _this.idAlcanceNodosTarea;
                _this.idAlcanceNodosTarea = null;

                _this.enviarPeticionServidor('modal-dialogo', '/Proyectos/Nuevo/Actualizar_Tarea', data, function (respuesta) {
                    _this.datosGeneralesProyecto = respuesta;
                    _this.cargarDatosTareas();
                    _this.modal.eliminarBotonAModal('#btn-eliminar-tarea');
                    _this.mostrarOcultarBtnIniciarProyecto();
                    _this.modal.cerrarModal();
                });
            } catch (exception) {
                _this.alerta.mostrarMensajeError('errorTareaNueva ', exception);
            }

        });
        _this.modal.funcionalidadBotonCancelar('Cancelar', function () {
            _this.modal.eliminarBotonAModal('#btn-eliminar-tarea');
        });
        $('#btn-eliminar-tarea').on('click', function () {
            try {
                data = {idProyecto: _this.datosGeneralesProyecto.IdProyecto, idTarea: datos[0], idAlcanceNodos: null};
                _this.enviarPeticionServidor('modal-dialogo', '/Proyectos/Nuevo/Eliminar_Tarea', data, function (respuesta) {
                    _this.datosGeneralesProyecto = respuesta;
                    _this.cargarDatosTareas();
                    _this.modal.eliminarBotonAModal('#btn-eliminar-tarea');
                    _this.modal.ocultarBotonAceptar();
                    let contenido = `<div class="row">
                                    <div class="col-md-12 text-center">
                                        <p>Se elimino la tarea con exito.</p>
                                    </div>
                                </div>`;
                    _this.modal.agregarContenido(contenido);
                    _this.mostrarOcultarBtnIniciarProyecto();
                });
            } catch (exception) {
                _this.alerta.mostrarMensajeError('errorTareaNueva ', exception);
            }
        });
    }

    validarFormularioTarea(formulario) {
        let datos = formulario.validarFormulario();
        datos.idProyecto = this.datosGeneralesProyecto.IdProyecto;
        datos.idTarea = this.tareaSeleccionada;
        if (datos['fecha-inicio-tarea'] === '') {
            throw 'Debes definir la fecha de inicio de la tarea';
        } else if (datos['fecha-fin-tarea'] === '') {
            throw 'Debes definir la fecha de fin de la tarea';
        }
        return datos;
    }

    mostrarFormularioTarea(titulo = '', formulario) {
        let tabla = this.tablas.get('data-table-nodos-tarea');
        let formularioNodos = this.formularios.get('form-nodos-tarea');

        this.modal.mostrarModal(titulo, this.datosGeneralesProyecto.Formularios.formularioNuevaTarea);
        this.modal.eliminarBotonAModal('#btn-eliminar-nodo-proyecto');
        formulario.iniciarElementos();
        formularioNodos.iniciarElementos();
        tabla.iniciarTabla();
        this.cargarSelectsTarea(formulario);
    }

    cargarSelectsTarea(formulario) {
        let lideres = [], asistentes = [];
        let selectLideres = formulario.obtenerElemento('select-lider-tarea');
        let selectAsistentes = formulario.obtenerElemento('select-asistente-tarea');

        $.each(this.datosGeneralesProyecto.personal, function (key, value) {
            if (value.Perfil === 'Lider') {
                lideres.push({id: value.Usuario, text: value.Nombre});
            } else if (value.Perfil === 'Asistente') {
                asistentes.push({id: value.Usuario, text: value.Nombre});
            }
        });
        selectLideres.cargaDatosEnSelect(lideres);
        selectAsistentes.cargaDatosEnSelect(asistentes);
    }

    cargarNodosTabla(tarea = {}) {
        let _this = this;
        let tabla = _this.tablas.get('data-table-nodos-tarea');
        let formularioNodos = _this.formularios.get('form-nodos-tarea');
        let selectUbicacion = formularioNodos.obtenerElemento('select-ubicacion-nodo-tarea');
        let alcance = _this.datosGeneralesProyecto.datosProyecto.alcance;
        _this.idAlcanceNodosTarea = null;
        tabla.limpiartabla();
        $.each(alcance, function (key, value) {
            if (key === tarea.Alcance && Object.keys(tarea.Nodos).length > 0) {
                selectUbicacion.definirValor(value.IdUbicacion);
                _this.bloquearBoton('select-ubicacion-nodo-tarea');
                $('#btn-mostrar-formulario-nodos-tarea').trigger('click');
                return false;
            }
        });

        if (_this.pagina !== 'seguimiento') {           
            $.each(tarea.Nodos, function (key, value) {
                _this.insertarNodoTabla([selectUbicacion.obtenerTexto(), _this.obtenerTipoNodo(value.Tipo), value.Nombre]);
            });
        } else {             
            _this.idAlcanceNodosTarea = (tarea.Alcance !== '0') ? tarea.Alcance : null;
            $.each(tarea.Nodos, function (key, value) {
                _this.insertarNodoTabla([value.Ubicacion, _this.obtenerTipoNodo(value.Tipo), value.Nombre, value.Avance]);
            });
    }
    }

    mostrarOcultarBtnIniciarProyecto() {
        let tareas = this.datosGeneralesProyecto.datosProyecto.tareas;
        if (Object.keys(tareas).length > 0) {
            this.mostrarElemento('btn-iniciar-proyecto-complejo');
        } else {
            this.ocultarElemento('btn-iniciar-proyecto-complejo');
        }
    }

    crearGraficaGantt() {
        let _this = this;
        let graficaGantt = _this.gantts.get('gantt_here');
        let tareas = _this.datosGeneralesProyecto.datosProyecto.tareas;
        let datos = [];

        datos.push({
            id: 1,
            text: _this.datosGeneralesProyecto.Nombre,
            start_date: _this.convertirFormatoFecha(_this.datosGeneralesProyecto.FechaInicio),
            duration: _this.diferenciaFechas(_this.datosGeneralesProyecto.FechaInicio, _this.datosGeneralesProyecto.FechaFin),
            progress: 0,
            open: true
        });
        $.each(tareas, function (key, value) {
            datos.push({
                id: key,
                text: value.Nombre,
                start_date: _this.convertirFormatoFecha(value.FechaInicio),
                duration: _this.diferenciaFechas(value.FechaInicio, value.FechaFin),
                progress: 0,
                parent: 1,
                open: true
            });
        });

        let datosGantt = {
            data: datos
        };
        graficaGantt.iniciarGantt(datosGantt);
    }

    convertirFormatoFecha(string) {
        let info = string.split('-');
        return info[2] + '-' + info[1] + '-' + info[0];
    }

    diferenciaFechas(fecha1, fecha2) {
        var aFecha1 = fecha1.split('-');
        var aFecha2 = fecha2.split('-');
        var fFecha1 = Date.UTC(aFecha1[0], aFecha1[1] - 1, aFecha1[2]);
        var fFecha2 = Date.UTC(aFecha2[0], aFecha2[1] - 1, aFecha2[2]);
        var dif = fFecha2 - fFecha1;
        var dias = Math.floor(dif / (1000 * 60 * 60 * 24));
        return dias;
    }

    mostrarGantt() {
        this.mostrarElemento('contenedor-gantt');
        this.mostrarElemento('btn-tabla-tareas');
        this.mostrarElemento('btn-pdf-gantt');
        this.ocultarElemento('contenedor-tabla-tareas');
        this.ocultarElemento('btn-gantt');
        this.crearGraficaGantt();
    }

    mostrarListaTareas() {
        this.ocultarElemento('contenedor-gantt');
        this.ocultarElemento('btn-tabla-tareas');
        this.ocultarElemento('btn-pdf-gantt');
        this.mostrarElemento('contenedor-tabla-tareas');
        this.mostrarElemento('btn-gantt');
    }

    obtenerTipoNodo(Tipo = '') {
        let tipo = '';
        switch (Tipo) {
            case '1':
                tipo = 'Datos';
                break;
            case '2':
                tipo = 'Voz';
                break;
            case '3':
                tipo = 'Video';
                break;
        }
        return tipo;
    }
}

