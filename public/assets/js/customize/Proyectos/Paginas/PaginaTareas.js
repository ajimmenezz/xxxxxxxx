
class PaginaTareas extends PaginaProyecto {

    constructor(objetos = new Map) {
        super(objetos);
        this.formularioActividad;
        this.formularioNodo;
        this.formularioMaterialNodo;
        this.formularioJustificarMaterial;
        this.tablaTareas;
        this.tablaNodosTarea;
        this.tablaActividades;
        this.tablaNodosActividades;
        this.tablaMaterialNodo;
    }

    mostrarTarea(datos = []) {
        try {
            let _this = this;
            _this.tareaSeleccionada = datos[1];
            this.enviarPeticionServidor('panel-table-tareas-asignadas', '/Proyectos/Tareas/Datos_Proyecto', {idProyecto: datos[0]}, function (respuesta) {
                _this.datosGeneralesProyecto = respuesta;                
                _this.definirElementosPrincipales();
                _this.mostrarApartado('Tarea');
                _this.definirDatosTarea();
                _this.cargarInformacionTituloTarea();
                _this.cargarInformacionSubtituloTarea();
                _this.cargarDatosTablaActividades();
                _this.cargarDatosTablaNodos();
                _this.cargarDatosFormularioNodo();
            });
        } catch (exception) {

    }
    }

    mostrarPanel(panel = '') {
        if (panel === 'panel-table-tareas-asignadas') {
            this.mostrarElemento('panel-table-tareas-asignadas');
            this.ocultarElemento('panel-seccion-detalles-tarea');
        } else {
            this.mostrarElemento('panel-seccion-detalles-tarea');
            this.ocultarElemento('panel-table-tareas-asignadas');
    }
    }

    definirElementosPrincipales() {
        this.formularioActividad = this.formularios.get('form-nueva-actividad-tarea');
        this.formularioNodo = this.formularios.get('form-definiendo-nodo');
        this.formularioMaterialNodo = this.formularios.get('form-definiendo-material-nodo');
        this.formularioJustificarMaterial = this.formularios.get('form-justificar-material');
        this.tablaTareas = this.tablas.get('data-table-proyecto-tareas-asignadas');
        this.tablaNodosTarea = this.tablas.get('data-table-nodos-tarea');
        this.tablaActividades = this.tablas.get('data-table-dias-actividad-tarea');
        this.tablaNodosActividades = this.tablas.get('datatable-nodos-capturados-actividad');
        this.tablaMaterialNodo = this.tablas.get('datatable-material-nodo');
    }

    definirDatosTarea() {
        let _this = this;
        let tareas = _this.datosGeneralesProyecto.datosProyecto.tareas;

        $.each(tareas, function (key, value) {
            if (_this.tareaSeleccionada === key) {
                _this.datosTarea = value;
                return false;
            }
        });
    }

    cargarInformacionTituloTarea() {
        this.asignarValorElementoPagina('#nombre-tarea', this.datosTarea.Nombre.toUpperCase());
        this.asignarValorElementoPagina('#porcentaje-tarea', (this.datosTarea.Porcentaje !== '0%') ? this.datosTarea.Porcentaje : this.datosTarea.PorcentajeNodos);
        this.asignarValorElementoPagina('#area-tarea', this.datosTarea.NombreArea.toUpperCase());
        this.asignarValorElementoPagina('#duración-tarea', `${this.datosTarea.FechaInicio} HASTA ${this.datosTarea.FechaFin}`);
        this.asignarValorElementoPagina('#titulo-lider', this.datosTarea.Lider.toUpperCase());
    }

    cargarInformacionSubtituloTarea() {
        this.asignarValorElementoPagina('#titulo-proyecto', this.datosGeneralesProyecto.Nombre.toUpperCase());
        this.asignarValorElementoPagina('#titulo-complejo', this.datosGeneralesProyecto.NombreComplejo.toUpperCase());
        this.asignarValorElementoPagina('#titulo-ticket', this.datosGeneralesProyecto.Ticket.toUpperCase());
    }

    cargarDatosTablaActividades() {
        let _this = this;
        _this.tablaActividades.limpiartabla();

        $.each(_this.datosTarea.Actividades, function (key, value) {
            _this.tablaActividades.agregarDatosFila([key, value.FechaReal, value.Descripcion, value.NombreUsuario]);
        });
    }

    cargarDatosTablaNodos() {
        let _this = this;
        let tareas = _this.datosGeneralesProyecto.datosProyecto.tareas;
        _this.idAlcanceNodosTarea = null;

        _this.tablaNodosTarea.limpiartabla();
        $.each(tareas, function (key, value) {            
            if (key === _this.tareaSeleccionada) {                
                if (Object.keys(value.Nodos).length > 0) {
                    _this.idAlcanceNodosTarea = value.Alcance;
                    _this.insertarNodos(value.Nodos);
                    _this.mostrarElemento('seccion-tabla-nodos-tarea');
                }                
                return false;
            }
        });
    }

    insertarNodos(nodos = []) {
        let _this = this;
        $.each(nodos, function (key, value) {
            _this.insertarNodoTabla([value.Ubicacion, _this.obtenerTipoNodo(value.Tipo), value.Nombre, value.Avance]);
        });
    }

    cargarDatosFormularioNodo() {
        let _this = this;
        let nodos = _this.datosTarea.Nodos;
        let selectNodo = this.formularioNodo.obtenerElemento('select-nodo');
        let selectMaterial = this.formularioMaterialNodo.obtenerElemento('select-material');

        _this.listaNodos = [];

        $.each(nodos, function (key, value) {
            if (value.Actividad === '0') {
                _this.listaNodos.push({id: key, text: value.Nombre, material: value.Material});
            }
        });

        selectNodo.cargaDatosEnSelect(_this.listaNodos);

        selectMaterial.evento('change', function () {
            _this.asignarValorElementoPagina('input-solicitado-nodo', _this.obtenerCantidadMaterial(selectNodo.obtenerValor(), selectMaterial.obtenerValor()));
            _this.asignarValorElementoPagina('input-utilizado-material-nodo', '');
        });

        _this.tablaMaterialNodo.evento(function () {
            let fila = this;
            
            if (_this.tablaMaterialNodo.validarNumeroFilas() && _this.nodoSeleccionado === null) {
                _this.tablaMaterialNodo.eliminarFila(fila);
            }
        });

    }

    obtenerCantidadMaterial(nodo, materialSeleccionado) {
        let cantidad = '';

        $.each(this.listaNodos, function (key, value) {
            if (value.id === nodo) {
                $.each(value.material, function (k, v) {
                    if (v.Material === materialSeleccionado) {
                        cantidad = v.Cantidad;
                        return false;
                    }
                });
                return false;
            }
        });
        return cantidad;
    }

    mostrarApartado(apartado = '') {
        if (apartado === 'Tarea') {
            this.restablecerApartado(apartado);
            this.mostrarPanel('panel-seccion-detalles-tarea');
        } else if (apartado === 'Actividad') {
            this.restablecerApartado(apartado);
            this.mostrarSeccion('seccion-formulario-actividad');
        } else if (apartado === 'Nodo') {
            this.restablecerApartado(apartado);
            this.mostrarSeccion('seccion-agregar-nodo');
        } else if (apartado === 'Tareas') {
            this.mostrarPanel('panel-table-tareas-asignadas');
            this.mostrarSeccion('seccion-tabla-dias-actividad');
    }
    }

    mostrarSeccion(seccion = '') {
        if (seccion === 'seccion-formulario-actividad') {
            let formulario = this.formularios.get('form-nueva-actividad-tarea');
            let fechaProyectada = formulario.obtenerElemento('fecha-proyectada-actividad');
            this.nodoSeleccionado = null;
            this.ocultarElemento('seccion-tabla-dias-actividad');
            this.mostrarElemento('seccion-formulario-actividad');
            fechaProyectada.actualizarFecha(this.datosTarea.FechaInicio);
            fechaProyectada.bloquearElemento();
            
            if (this.actividadSeleccionada === undefined) {
                if (this.idAlcanceNodosTarea === null) {
                    this.mostrarElemento('elemento-evidencia-actividad');
                } else {
                    this.ocultarElemento('elemento-evidencia-actividad');
                }
            } else {
                this.mostrarBotonesCabeceraActividad('modificar-actividad');
                this.mostrarElemento('subseccion-nodos-utilizados');
                this.ocultarElemento('seccion-agregar-nodo');
            }

        } else if (seccion === 'seccion-tabla-dias-actividad') {
            this.actividadSeleccionada = undefined;
            this.datosActividad = null;
            this.nodoSeleccionado = null;
            this.restablecerApartado('Actividad');
            this.mostrarElemento('seccion-tabla-dias-actividad');
            this.ocultarElemento('seccion-formulario-actividad');
            this.ocultarElemento('seccion-agregar-nodo');
        } else if (seccion === 'seccion-agregar-nodo') {
            this.mostrarElemento('seccion-agregar-nodo');
            this.ocultarElemento('seccion-formulario-actividad');
            this.ocultarElemento('seccion-tabla-dias-actividad');
    }
    }

    restablecerApartado(apartado = '') {
        if (apartado === 'Tarea') {
            this.tablaActividades.limpiartabla();
            this.tablaNodosTarea.limpiartabla();
            this.ocultarElemento('seccion-tabla-nodos-tarea');
        } else if (apartado === 'Actividad') {
            this.formularioActividad.limpiarElementos();
            this.tablaNodosActividades.limpiartabla();
            this.ocultarElemento('subseccion-nodos-utilizados');
            this.formularioActividad.habilitarFormulario();
            this.mostrarEvidencias('nueva-evidencia');
            this.mostrarBotonesCabeceraActividad('nueva-actividad');
            this.ocultarElemento('btn-actualizar-nueva-actividad');
            this.asignarValorElementoPagina('.evidenciasSubidas', '');
            this.asignarValorElementoPagina('.evidenciasMaterialUtilizado', '');
        } else if (apartado === 'Nodo') {
            this.formularioNodo.limpiarElementos();
            this.formularioNodo.habilitarFormulario();
            this.formularioMaterialNodo.limpiarElementos();
            this.mostrarNodoActividad('nuevo-nodo');
            this.habilitarBoton('btn-mostrar-formulario-material-nodo');
            this.ocultarElemento('subseccion-material-nodo');
            this.mostrarElemento('form-definiendo-material-nodo');
            this.tablaMaterialNodo.limpiartabla();
            this.asignarValorElementoPagina('.evidenciasSubidas', '');
            this.asignarValorElementoPagina('.evidenciasMaterialUtilizado', '');
    }
    }

    mostrarBotonesCabeceraActividad(clase = '') {
        if (clase === 'nueva-actividad') {
            this.mostrarElemento('nueva-actividad');
            this.ocultarElemento('modificar-actividad');
        } else if (clase === 'modificar-actividad') {
            this.mostrarElemento('modificar-actividad');
            this.ocultarElemento('nueva-actividad');
            if (this.idAlcanceNodosTarea === null) {
                this.ocultarElemento('btn-actualizar-nueva-actividad');
            } else {
//                this.mostrarElemento('btn-actualizar-nueva-actividad');
            }
    }
    }

    agregarActividad() {
        if (this.listaNodos.length === 0 && this.idAlcanceNodosTarea !== null) {
            let contenido = `<div class="row">
                                <div class="col-md-12 text-center">
                                   <p>Todos los nodos ya fueron capturados en las actividades. Por lo que ya no es posible crear mas actividades.</p>
                                </div>
                            </div>`;

            this.modal.mostrarModal('Agregar Actividad', contenido);
            this.modal.ocultarBotonAceptar();
            this.modal.cambiarValorBotonCanelar('Cerrar');
        } else {
            this.mostrarApartado('Actividad');
        }
    }

    cargarDatosDeDiaDeActividad(datos = {}){
        if (datos[0] !== undefined) {
            this.actividadSeleccionada = datos[0];
            this.mostrarApartado('Actividad');
            let textAreaDescripcion = this.formularioActividad.obtenerElemento('textArea-descripcion-actividad');
            let inputFechaProyectada = this.formularioActividad.obtenerElemento('fecha-proyectada-actividad');
            let inputFechaReal = this.formularioActividad.obtenerElemento('fecha-real-actividad');
            this.definirDatosActividad();
            this.cargarDatosTablaNodosActividad();
            textAreaDescripcion.definirValor(this.datosActividad.Descripcion);
            inputFechaProyectada.definirValor(this.datosActividad.FechaProyectada);
            inputFechaReal.definirValor(this.datosActividad.FechaReal);

            if (this.idAlcanceNodosTarea === null) {
                this.mostrarDatosActividadSinNodos();
            } else {
                this.mostrarDatosActividadConNodos();
            }
    }
    }

    definirDatosActividad() {
        let _this = this;
        let tarea = _this.datosTarea;

        $.each(tarea.Actividades, function (key, value) {
            if (_this.actividadSeleccionada === key) {
                _this.datosActividad = value;
                return false;
            }
        });
    }

    cargarDatosTablaNodosActividad() {
        let _this = this;

        _this.tablaNodosActividades.limpiartabla();
        $.each(_this.datosActividad.Nodos, function (key, value) {
            _this.tablaNodosActividades.agregarDatosFila([value.Actividad, value.Ubicacion, value.Nombre]);
        });
    }

    mostrarDatosActividadSinNodos() {
        this.ocultarElemento('subseccion-nodos-utilizados');
        this.mostrarBotonesCabeceraActividad('modificar-actividad');
        this.cargarEvidenciasSubidas(this.datosActividad.Evidencia);
        this.formularioActividad.bloquearFormulario();
    }

    mostrarDatosActividadConNodos() {
        this.formularioActividad.bloquearFormulario();
        this.ocultarElemento('elemento-evidencia-actividad');
        /* 
         * El boton "btn-actualizar-nueva-actividad" se mantendra oculto hasta saber si se permite la actualización de la información
         * de la actividad.
         */
        this.ocultarElemento('btn-actualizar-nueva-actividad');
    }

    cargarEvidenciasSubidas(archivos = []) {
        let evidencias = ``;

        this.mostrarEvidencias('archivos-subidos');
        $.each(archivos, function (key, value) {
            evidencias += `<div class="evidencia"><a href="${value}" data-lightbox="evidencias"><img src ="${value}" /></a><p>Evidencia_${key + 1}</p></div>`;
        });

        this.asignarValorElementoPagina('.evidenciasSubidas', evidencias);
    }

    mostrarEvidencias(clase = '') {
        if (clase === 'nueva-evidencia') {
            this.mostrarElemento('nueva-evidencia');
            this.ocultarElemento('archivos-subidos');
        } else if (clase === 'archivos-subidos') {
            this.mostrarElemento('archivos-subidos');
            this.ocultarElemento('nueva-evidencia');
    }
    }

    mostrarFormularioNodo() {
        if (this.listaNodos.length === 0) {

            let contenido = `<div class="row">
                                <div class="col-md-12 text-center">
                                    <p>Todos los nodos ya fueron capturados en las actividades. Por lo que ya no es posible agregar más.</p>
                                </div>
                            </div>`;

            this.modal.mostrarModal('Agregar Nodo', contenido);
            this.modal.ocultarBotonAceptar();
            this.modal.cambiarValorBotonCanelar('Cerrar');
        } else {
            this.mostrarApartado('Nodo');
        }
    }

    agregarNodo() {
        try {
            let _this = this, material = [];
            let selectNodo = _this.formularioNodo.obtenerElemento('select-nodo');
            let selectMaterial = _this.formularioMaterialNodo.obtenerElemento('select-material');
            _this.formularioNodo.validarFormulario();
            let idNombreNodo = selectNodo.obtenerTexto();
            let materialNodo = _this.datosTarea.Nodos[idNombreNodo].Material;
            let listaMaterial = _this.datosGeneralesProyecto.Formularios.listasSelectsMaterial.Material;

            $.each(listaMaterial, function (key, value) {
                $.each(materialNodo, function (k, v) {
                    if (value.Id === v.Material) {
                        material.push({id: value.Id, text: value.Nombre});
                    }
                });
            });

            selectMaterial.cargaDatosEnSelect(material);
            _this.formularioNodo.bloquearFormulario();
            _this.mostrarElemento('subseccion-material-nodo');
            _this.bloquearBoton('btn-mostrar-formulario-material-nodo');
        } catch (exception) {
            this.alerta.mostrarAlerta('Error', exception);
        }
    }

    agregarMaterialNodo() {
        try {
            let _this = this;
            let selectMaterial = _this.formularioMaterialNodo.obtenerElemento('select-material');
            let datos = _this.formularioMaterialNodo.validarFormulario();
            datos.justificacion = '';
            datos.IdMaterial = selectMaterial.obtenerValor();

            $.each(_this.tablaMaterialNodo.datosTabla(), function (key, value) {
                if (datos['IdMaterial'] === value[0]) {
                    throw 'Ya se definio el material. Favor de ingresar otro material o eliminelo para volver a capturarlo.';
                }
            });

            if (parseInt(datos['input-utilizado-material-nodo']) > parseInt(datos['input-solicitado-nodo'])) {
                _this.modal.mostrarModal('Justificar Material Adicional', _this.datosGeneralesProyecto.Formularios.formularioMaterialUtilizado);
                _this.formularioJustificarMaterial.iniciarElementos();

                _this.modal.funcionalidadBotonAceptar(null, function () {
                    let dato = _this.formularioJustificarMaterial.validarFormulario();
                    datos.justificacion = dato['textarea-justificar'];
                    _this.insertarDatosTablaMaterial(datos);
                    _this.formularioMaterialNodo.limpiarElementos();
                    _this.modal.cerrarModal();
                });

            } else {
                _this.insertarDatosTablaMaterial(datos);
                _this.formularioMaterialNodo.limpiarElementos();
            }

        } catch (exception) {
            this.alerta.mostrarAlerta('Error', exception);
        }

    }

    insertarDatosTablaMaterial(datos = []) {
        this.tablaMaterialNodo.agregarDatosFila([
            datos['IdMaterial'],
            datos.selectTexto['select-material'],
            datos['input-solicitado-nodo'],
            datos['input-utilizado-material-nodo'],
            datos['justificacion']]);
    }

    generarActividad() {
        try {
            let _this = this;
            let evidencias = _this.formularioActividad.obtenerElemento('file-evidencia-actividad');
            let datos = _this.formularioActividad.validarFormulario();
            datos.idProyecto = _this.datosGeneralesProyecto.IdProyecto;
            datos.idTarea = _this.tareaSeleccionada;
            datos.idAlcanceNodos = _this.idAlcanceNodosTarea;

            if (datos['fecha-proyectada-actividad'] === '') {
                throw 'Debes definir la fecha de proyectada de la actividad';
            } else if (datos['fecha-real-actividad'] === '') {
                throw 'Debes definir la fecha real de la actividad';
            } else if (_this.idAlcanceNodosTarea === null) {
                if (evidencias.validarArchivos()) {
                    evidencias.enviarPeticionServidor('panel-seccion-detalles-tarea', datos, function (respuesta) {
                        _this.mostrarRespuestaNuevaActividad(respuesta);
                    });
                }
            } else {
                _this.enviarPeticionServidor('panel-seccion-detalles-tarea', '/Proyectos/Tareas/Generar_Actividad', datos, function (respuesta) {
                    _this.mostrarRespuestaNuevaActividad(respuesta);
                });
            }

        } catch (exception) {
            this.alerta.mostrarAlerta('Error', exception);
        }

    }

    mostrarRespuestaNuevaActividad(respuesta) {
        let _this = this, contenido = '', etiqueta = '';
        _this.datosGeneralesProyecto = respuesta.datosProyecto;
        _this.actividadSeleccionada = respuesta.actividadNueva.toString();

        _this.definirDatosTarea();
        _this.cargarInformacionTituloTarea();
        _this.definirDatosActividad();
        _this.cargarDatosTablaActividades();
        _this.cargarInformacionTituloTarea();

        if (_this.idAlcanceNodosTarea === null) {
            contenido = `<div class="row">
                                <div class="col-md-12 text-center">
                                    <p>Se genero la actividad con exito</p>
                                </div>
                            </div>`;
            _this.modal.ocultarBotonAceptar();
            _this.mostrarDatosActividadSinNodos();
            etiqueta = 'Cerrar';
        } else {
            contenido = `<div class="row">
                                <div class="col-md-12 text-center">
                                    <p>Se genero la actividad con exito</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 text-center">
                                    <p>¿ Quieres definir el material de la actividad?</p>
                                </div>
                            </div>`;
            _this.modal.funcionalidadBotonAceptar(null, function () {
                _this.mostrarApartado('Nodo');
                _this.mostrarElemento('subseccion-nodos-utilizados');
                _this.formularioActividad.bloquearFormulario();
                _this.modal.cerrarModal();
            });
            etiqueta = 'Cancelar';
        }
        _this.modal.mostrarModal('Nueva Actividad', contenido);

        _this.modal.funcionalidadBotonCancelar(etiqueta, function () {
            _this.mostrarBotonesCabeceraActividad('modificar-actividad');
            if (_this.idAlcanceNodosTarea !== null) {
                _this.mostrarElemento('subseccion-nodos-utilizados');
                _this.formularioActividad.bloquearFormulario();
            }
            _this.modal.cerrarModal();
            _this.actualizarTareasAsignadasTecnico();
        });
    }

    actualizarTareasAsignadasTecnico() {
        try {
            let _this = this;
            let datos = {idProyecto: _this.datosGeneralesProyecto.IdProyecto};
            _this.enviarPeticionServidor('panel-seccion-detalles-tarea', '/Proyectos/Tareas/Obtener_Tareas_Asignadas', datos, function (respuesta) {
                _this.cargarDatosTablaTareas(respuesta);
            });

        } catch (exception) {
            this.alerta.mostrarAlerta('Error', exception);
        }

    }

    cargarDatosTablaTareas(lista = []) {
        let _this = this;
        _this.tablaTareas.limpiartabla();
        $.each(lista, function (key, value) {
            let avance = (value.Avance !== '0%') ? value.Avance : value.AvanceNodos;
            _this.tablaTareas.agregarDatosFila([value.IdProyecto, value.IdTarea, value.Tarea, value.Proyecto, value.Complejo, value.FechaInicio, value.FechaTermino, avance]);
        });
    }

    eliminarActividad() {

        let _this = this, contenido = '';
        let datos = {idProyecto: _this.datosGeneralesProyecto.IdProyecto, idActividad: _this.actividadSeleccionada, idTarea: _this.tareaSeleccionada, idAlcanceNodos: _this.idAlcanceNodosTarea};

        contenido = `<div class="row">
                            <div class="col-md-12 text-center">
                                <p>Al eliminar la actividad ya no puede recupera la información</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <p>¿Quieres realmente eliminar la actividad?</p>
                            </div>
                        </div>`;

        _this.modal.mostrarModal('Eliminar Actividad', contenido);

        _this.modal.funcionalidadBotonAceptar(null, function () {
            try {
                _this.enviarPeticionServidor('panel-seccion-detalles-tarea', '/Proyectos/Tareas/Eliminar_Actividad', datos, function (respuesta) {
                    _this.datosGeneralesProyecto = respuesta;
                    _this.actividadSeleccionada = null;
                    _this.definirDatosTarea();
                    _this.cargarDatosTablaActividades();
                    _this.cargarInformacionTituloTarea();
                    _this.cargarDatosTablaNodos();
                    _this.cargarDatosFormularioNodo();
                    _this.mostrarSeccion('seccion-tabla-dias-actividad');
                    _this.actualizarTareasAsignadasTecnico();
                    _this.modal.cerrarModal();
                });
            } catch (exception) {
                _this.modal.cerrarModal();
                _this.alerta.mostrarAlerta('Error', exception);
            }
        });

        _this.modal.funcionalidadBotonCancelar(null, function () {
            _this.modal.cerrarModal();
        });
    }

    guardarNodoActividad() {
        try {
            let _this = this, materiales = {};
            let archivos = _this.formularioMaterialNodo.obtenerElemento('file-evidencia-material-utilizado');
            let datos = _this.formularioNodo.validarFormulario();
            let material = _this.tablaMaterialNodo.datosTabla();
            datos.idProyecto = _this.datosGeneralesProyecto.IdProyecto;
            datos.idTarea = _this.tareaSeleccionada;
            datos.idActividad = _this.actividadSeleccionada;
            datos.idAlcanceNodos = _this.idAlcanceNodosTarea;

            if (material.length === 0) {
                throw 'Debes definir el material utilizado en el nodo';
            } else if (archivos.validarArchivos()) {
                $.each(material, function (key, value) {
                    materiales[key] = {IdMaterial: value[0], Utilizado: value[3], Justificacion: value[4]};
                });
                datos.materialNodo = JSON.stringify(materiales);
                _this.guardarNodo(datos);
            }
        } catch (exception) {
            this.alerta.mostrarAlerta('Error', exception);
        }
    }

    guardarNodo(datos = {}) {
        try {
            let _this = this;
            let archivos = _this.formularioMaterialNodo.obtenerElemento('file-evidencia-material-utilizado');
            archivos.enviarPeticionServidor('panel-seccion-detalles-tarea', datos, function (respuesta) {
                _this.datosGeneralesProyecto = respuesta;
                _this.definirDatosTarea();
                _this.cargarInformacionTituloTarea();
                _this.cargarDatosTablaActividades();
                _this.definirDatosActividad();
                _this.cargarDatosTablaNodos();
                _this.cargarDatosTablaNodosActividad();
                _this.cargarDatosFormularioNodo();
                _this.mostrarSeccion('seccion-formulario-actividad');
                _this.actualizarTareasAsignadasTecnico();
            });
        } catch (exception) {
            this.alerta.mostrarAlerta('Error', exception);
    }
    }

    mostrarDatosMaterialCapturado(datos = {}) {

        if (datos[0] !== undefined) {
            let _this = this, evidencias = '';
            _this.nodoSeleccionado = datos[2];            
            this.mostrarApartado('Nodo');
            let datosNodo = _this.datosActividad.Nodos[_this.nodoSeleccionado];
            _this.mostrarNodoActividad('info-nodo');
            _this.asignarValorElementoPagina('#nombre-nodo', _this.nodoSeleccionado);

            _this.tablaMaterialNodo.limpiartabla();
            $.each(datosNodo.Material, function (key, value) {
                _this.tablaMaterialNodo.agregarDatosFila([value.IdMaterial, value.Material, value.Solicitado, value.Utilizado, value.Justificacion]);
            });
            $.each(datosNodo.Evidencia, function (key, value) {
                evidencias += `<div class="evidencia"><a href="${value}" data-lightbox="evidencias"><img src ="${value}" /></a><p>Evidencia_${key + 1}</p></div>`;
            });
            _this.asignarValorElementoPagina('.evidenciasMaterialUtilizado', evidencias);
        }
    }

    mostrarNodoActividad(clase) {
        if (clase === 'nuevo-nodo') {
            this.mostrarElemento('nuevo-nodo');
            this.ocultarElemento('info-nodo');
        } else if (clase === 'info-nodo') {
            this.mostrarElemento('info-nodo');
            this.mostrarElemento('subseccion-material-nodo');
            this.ocultarElemento('nuevo-nodo');
            this.ocultarElemento('form-definiendo-material-nodo');
        }
    }

    eliminarMaterialUtilizadoDeActividad() {
        try {
            let _this = this, contenido = '';
            contenido = `<div class="row">
                            <div class="col-md-12 text-center">
                                <p>
                                    Al eliminar el nodo de la actividad se borrar las evidencias por lo que
                                    ya no va a ser posible recuperarla.
                                </p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <p>¿Estas seguro de querer eliminar el nodo capturado?</p>
                            </div>
                        </div>`;

            _this.modal.mostrarModal('Eliminar Material', contenido);

            _this.modal.funcionalidadBotonAceptar(null, function () {
                let datos = {}, archivosBorrados = [];
                let datosNodo = _this.datosActividad.Nodos[_this.nodoSeleccionado];
                datos.idProyecto = _this.datosGeneralesProyecto.IdProyecto;
                datos.idTarea = _this.tareaSeleccionada;
                datos.idActividad = _this.actividadSeleccionada;
                datos.idAlcanceNodosTarea = _this.idAlcanceNodosTarea;
                datos.nodo = _this.nodoSeleccionado;

                $.each(datosNodo.Evidencia, function (key, value) {
                    archivosBorrados.push(value);
                });
                datos.archivosEliminados = archivosBorrados;

                _this.enviarPeticionServidor('modal-dialogo', '/Proyectos/Tareas/Eliminar_Nodo_Actividad', datos, function (respuesta) {
                    _this.datosGeneralesProyecto = respuesta;
                    _this.nodoSeleccionado = null;
                    _this.definirDatosTarea();
                    _this.definirDatosActividad();
                    _this.cargarDatosTablaActividades();
                    _this.cargarDatosTablaNodosActividad();
                    _this.cargarDatosTablaNodos();
                    _this.cargarInformacionTituloTarea();
                    _this.cargarDatosFormularioNodo();
                    _this.mostrarSeccion('seccion-formulario-actividad');
                    _this.actualizarTareasAsignadasTecnico();
                    _this.modal.cerrarModal();
                });

            });

            _this.modal.funcionalidadBotonCancelar(null, function () {
                _this.modal.cerrarModal();
            });

        } catch (exception) {
            this.modal.cerrarModal();
            this.alerta.mostrarAlerta('Error', exception);
        }

    }

//    generarListaUbicaciones(material = '') {
//        let _this = this;
//        let ubicacion = [];
//        let alcance = _this.datosGeneralesProyecto.datosProyecto.alcance;
//        _this.listaUbicacion = [];
//
//        $.each(alcance, function (key, value) {
//            $.each(value.Puntos, function (clave, valor) {
//                if (valor.IdMaterial === material && ubicacion.indexOf(value.Ubicacion) === -1) {
//                    ubicacion.push(value.Ubicacion);
//                    _this.listaUbicacion.push({id: value.IdUbicacion, text: value.Ubicacion, material: valor.IdMaterial, puntos: value.Puntos});
//                }
//            });
//        });
//    }
//
//    generarListaNodos(ubicacion = '') {
//        let _this = this;
//        _this.listaNodos = [];
//
//        $.each(_this.listaUbicacion, function (key, value) {
//            if (value.id === ubicacion) {
//                $.each(value.puntos, function (clave, valor) {
//                    if (valor.IdMaterial === value.material) {
//                        _this.listaNodos.push({id: clave, text: valor.Nombre, cantidad: valor.Cantidad});
//                    }
//
//                });
//            }
//        });
//    }
//

//
//    mostrarFormularioNuevaActividad() {
//        this.limpiarTablaMaterial();
//        this.mostrarSeccion('seccion-formulario-actividad');
//        this.ocultarElemento('subseccion-material-utilizado');
//        this.mostrarBotonesCabeceraActividad('nueva-actividad');
//
//        if (this.idAlcanceNodosTarea === null) {
//            this.mostrarElemento('elemento-evidencia-actividad');
//        } else {
//            this.ocultarElemento('elemento-evidencia-actividad');
//        }
//    }
//

//

//

//

//

//

//

//

//
//    eliminarMaterialNodo(datos = []) {
//        let tabla = this.tablas.get('datatable-material-nodo');
//    }
//

//

//
//    diferenciaDeMaterialSolicitado(datos = {}){
//        let _this = this;
//        let formularioJustificar = _this.formularios.get('form-justificar-material');
//        let utilizado = parseInt(datos['input-utilizado-material-actividad']);
//        let solicitado = parseInt(datos['input-solicitado-nodo']);
//        let diferencia = solicitado - utilizado;
//        datos.justificacion = '';
//
//        if (diferencia < 0) {
//            _this.modal.mostrarModal('Justificar Material Adicional', _this.datosGeneralesProyecto.Formularios.formularioMaterialUtilizado);
//            formularioJustificar.iniciarElementos();
//
//            _this.modal.funcionalidadBotonAceptar(null, function () {
//                try {
//                    let dato = formularioJustificar.validarFormulario();
//                    datos.justificacion = dato['textarea-justificar'];
//                    _this.modal.cerrarModal();
//                    _this.guardandoMaterialUtilizadoActividad(datos);
//                } catch (exception) {
//                }
//            });
//
//            _this.modal.funcionalidadBotonCancelar(null, function () {
//                _this.modal.cerrarModal();
//            });
//
//        } else {
//            _this.guardandoMaterialUtilizadoActividad(datos);
//    }
//    }
//

//

//

//

//
//    regresarTablaActividades() {
//        this.actividadSeleccionada = null;
//        this.datosActividad = null;
//        this.nodoSeleccionado = null;
//        this.mostrarSeccion('seccion-tabla-dias-actividad');
//    }
//

//
//    actualizarActividad() {
//        try {
//            let _this = this, contenido = '';
//            let formulario = _this.formularios.get('form-nueva-actividad-tarea');
//            let datos = formulario.validarFormulario();
//            datos.idProyecto = _this.datosGeneralesProyecto.IdProyecto;
//            datos.idTarea = _this.tareaSeleccionada;
//            datos.idActividad = _this.actividadSeleccionada;
//
//            _this.enviarPeticionServidor('panel-seccion-detalles-tarea', '/Proyectos/Tareas/Actualizar_Actividad', datos, function (respuesta) {
//                _this.datosGeneralesProyecto = respuesta;
//                _this.actividadSeleccionada = null;
//                _this.definirDatosTarea();
//                _this.cargarDatosTablaActividades();
//
//                contenido = `<div class="row">
//                                    <div class="col-md-12 text-center">
//                                        <p>Se actualizo la actividad con exito</p>
//                                    </div>
//                                </div>`;
//
//                _this.modal.mostrarModal('Actualización Actividad', contenido);
//                _this.modal.ocultarBotonAceptar();
//                _this.modal.funcionalidadBotonCancelar('Cerrar', function () {
//                    _this.mostrarSeccion('seccion-tabla-dias-actividad');
//                    _this.modal.cerrarModal();
//                });
//
//            });
//
//        } catch (exception) {
//            this.alerta.mostrarAlerta('Error', exception);
//        }
//    }
//

//

//

//
//    limpiarTablaMaterial() {
//        let tabla = this.tablas.get('datatable-nodos-capturados-actividad');
//        tabla.limpiartabla();
//    }
//
}