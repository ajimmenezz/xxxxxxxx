
class usuario_perfil {

    mostrarDatosProyecto(datos) {
        let _this = this;
        _this.pagina = 'seguimiento';

        try {
            _this.enviarPeticionServidor('panel-table-proyectos', '/Proyectos/Seguimiento/Datos_Proyecto', {idProyecto: datos[0]}, function (respuesta) {
                _this.datosGeneralesProyecto = respuesta;                
                _this.mostrarPanel('panel-seccion-proyecto');
                _this.restablecerSeccionTareas();
                _this.activarPestanaGenerales();
                _this.cargarDatosCabecera();
                _this.cargarDatosGenerales();
                _this.cargarDatosTareas();
                _this.mostrarListaTareas();
            });
        } catch (exception) {
            this.modal.mostrarAlerta('Error', exception);
        }

    }

    prueba(){
        console.log('pumas');
    }
    
    cargarDatosCabecera() {
        this.asignarValorElementoPagina('.nombre-proyecto', this.datosGeneralesProyecto.Nombre);
        this.asignarValorElementoPagina('.nombre-complejo', this.datosGeneralesProyecto.NombreComplejo);
        this.asignarValorElementoPagina('.ticket-proyecto', this.datosGeneralesProyecto.Ticket);
    }

    cargarDatosGenerales() {

        let formulario = this.formularios.get('form-proyecto-iniciado');

        this.asignarValorElementoPagina('#tipo-sistema', this.datosGeneralesProyecto.NombreSistema);
        this.asignarValorElementoPagina('#tipo-proyecto', this.datosGeneralesProyecto.NombreTipo);
        this.asignarValorElementoPagina('#observaciones-proyecto', this.datosGeneralesProyecto.Nombre);
        this.asignarValorElementoPagina('fecha-inicio-proyecto', this.datosGeneralesProyecto.FechaInicio);
        this.asignarValorElementoPagina('fecha-final-proyecto', this.datosGeneralesProyecto.FechaFin);
        formulario.bloquearFormulario();
    }

    restablecerSeccionTareas() {
        this.ocultarElemento('seccion-actividad');
        this.ocultarElemento('cabecera-actividad');
        this.ocultarElemento('seccion-material-actividad');
        this.ocultarElemento('cabecera-material-actividad');
        this.asignarValorElementoPagina('#formulario-actividad', '');
        this.mostrarBtnsEditarTarea('btns-informacion-tarea');
        this.mostrarSeccion('seccion-tabla-tareas');
    }

    habilitarFormularioDatosGenerelas() {
        let formulario = this.formularios.get('form-proyecto-iniciado');

        formulario.habilitarFormulario();
        this.mostrarElemento('btn-guardar-actualizacion');
        this.mostrarElemento('btn-cancelar-actualizar');
        this.ocultarElemento('btn-actualizar-proyecto');
    }

    deshabilitarFormularioDatosGenerelas() {
        let formulario = this.formularios.get('form-proyecto-iniciado');

        formulario.bloquearFormulario();
        this.mostrarElemento('btn-actualizar-proyecto');
        this.ocultarElemento('btn-cancelar-actualizar');
        this.ocultarElemento('btn-guardar-actualizacion');
    }

    cancelarActualizarDatosGenerales() {
        this.deshabilitarFormularioDatosGenerelas();
        this.cargarDatosGenerales();
    }

    actualizarDatosGenerales() {
        try {
            let _this = this;
            let formulario = _this.formularios.get('form-proyecto-iniciado');
            let datos = formulario.validarFormulario();
            datos.idProyecto = _this.datosGeneralesProyecto.IdProyecto;

            if (datos['fecha-inicio-proyecto'] === '') {
                throw 'Debes definir la fecha de inicio del proyecto';
            } else if (datos['fecha-final-proyecto'] === '') {
                throw 'Debes definir la fecha de final del proyecto';
            }

            _this.enviarPeticionServidor('panel-seccion-proyecto-iniciado', '/Proyectos/Seguimiento/Actualizar_Datos_Proyecto', datos, function (respuesta) {
                _this.datosGeneralesProyecto = respuesta.datosProyecto;
                _this.deshabilitarFormularioDatosGenerelas();
                _this.actualizarTablaProyectosIniciados(respuesta.listaProyectos);
            });
        } catch (exception) {
            this.alerta.mostrarAlerta('Error', exception);
        }
    }

    actualizarTablaProyectosIniciados(datosTabla = []) {
        let tabla = this.tablas.get('data-table-proyectos-iniciados');

        tabla.limpiartabla();
        $.each(datosTabla, function (key, valor) {
            tabla.agregarDatosFila([valor.Id, valor.Ticket, valor.Nombre, valor.Complejo, valor.FechaInicio, valor.FechaTermino, '0%']);
        });
    }

    mostrarDatosTarea(datos = []) {
        try {
            let _this = this;
            _this.tareaSeleccionada = datos[0];
            _this.datosTarea = _this.datosGeneralesProyecto.datosProyecto.tareas[datos[0]];            
            _this.mostrarSeccion('seccion-tarea');
            _this.cargarCabeceraTarea();
            _this.cargarFormularioTarea();
            _this.cargarDatosTablaActividades();
        } catch (exception) {
            this.alerta.mostrarAlerta('Error', exception);
    }
    }

    mostrarSeccion(seccion = '') {
        if (seccion === 'seccion-tabla-tareas') {
            this.mostrarElemento('seccion-tabla-tareas');
            this.mostrarElemento('cabecera-tareas');
            this.ocultarElemento('seccion-tarea');
            this.ocultarElemento('cabecera-tarea');
            this.asignarValorElementoPagina('#formulario-tarea', '');
        } else if (seccion === 'seccion-tarea') {
            this.mostrarElemento('seccion-tarea');
            this.mostrarElemento('cabecera-tarea');
            this.ocultarElemento('seccion-tabla-tareas');
            this.ocultarElemento('cabecera-tareas');
            this.ocultarElemento('seccion-actividad');
            this.ocultarElemento('cabecera-actividad');
            this.asignarValorElementoPagina('#formulario-actividad', '');
            this.mostrarBtnsEditarTarea('btns-informacion-tarea');
            this.mostrarElemento('contenedor-tabla-actividades');
        } else if (seccion === 'seccion-actividad') {
            this.mostrarElemento('seccion-actividad');
            this.mostrarElemento('cabecera-actividad');
            this.ocultarElemento('seccion-tarea');
            this.ocultarElemento('cabecera-tarea');
            this.ocultarElemento('seccion-material-actividad');
            this.ocultarElemento('cabecera-material-actividad');
        } else if (seccion === 'seccion-material-actividad') {
            this.mostrarElemento('seccion-material-actividad');
            this.mostrarElemento('cabecera-material-actividad');
            this.ocultarElemento('seccion-actividad');
            this.ocultarElemento('cabecera-actividad');
    }
    }

    cargarCabeceraTarea() {
        this.asignarValorElementoPagina('.tarea-nombre', this.datosTarea.Nombre);
        this.asignarValorElementoPagina('.tarea-porcentaje', (this.datosTarea.Porcentaje !== '0%') ? this.datosTarea.Porcentaje : this.datosTarea.PorcentajeNodos);
        this.asignarValorElementoPagina('.tarea-area', this.datosTarea.NombreArea);
    }

    cargarFormularioTarea() {
        let formulario = this.formularios.get('form-nueva-tarea');
        let tabla = this.tablas.get('data-table-nodos-tarea');
        this.asignarValorElementoPagina('#formulario-tarea', this.datosGeneralesProyecto.Formularios.formularioNuevaTarea);
        this.agregarElemento('data-table-nodos-tarea thead tr', '<th class="all">Avance</th>');
        formulario.iniciarElementos();
        tabla.iniciarTabla();
        this.cargarSelectsTarea(formulario);
        this.escucharEventosDeElementosTarea(formulario);
        this.asignarValorElementoPagina('input-nombre-tarea', this.datosTarea.Nombre);
        this.asignarValorElementoPagina('select-area-tarea', this.datosTarea.Area);
        this.asignarValorElementoPagina('select-lider-tarea', this.datosTarea.IdLider);
        this.asignarValorElementoPagina('select-asistente-tarea', this.datosTarea.Asistente);
        this.asignarValorElementoPagina('fecha-inicio-tarea', this.datosTarea.FechaInicio);
        this.asignarValorElementoPagina('fecha-fin-tarea', this.datosTarea.FechaFin);
        this.ocultarElemento('contendor-checkbox-tarea');
        this.ocultarElemento('fila-1');        
        (this.datosTarea.Alcance !== '0' )? this.cargarNodosTabla(this.datosTarea): this.ocultarElemento('sin-nodos');
        this.ocultarElemento('form-ubicacion-nodos');
        this.ocultarElemento('info-agregar-form-nueva-tarea');
        formulario.bloquearFormulario();
    }

    cargarDatosTablaActividades() {
        let _this = this;
        let tabla = _this.tablas.get('data-table-actividades');
        tabla.limpiartabla();

        $.each(_this.datosTarea.Actividades, function (key, value) {
            tabla.agregarDatosFila([key, value.FechaReal, value.Descripcion, value.NombreUsuario]);
        });
    }

    mostrarDatosDiaActividad(datos = []) {
        try {
            if (Object.keys(datos).length > 0) {
                let _this = this;
                _this.actividadSeleccionada = datos[0];                
                _this.definirDatosActividad();
                _this.mostrarSeccion('seccion-actividad');
                _this.cargarCabeceraActividad();
                _this.cargarFormularioActividad();

                if (_this.datosTarea.Alcance !== '0') {
                    _this.mostrarElemento('nodos-actividad');
                    _this.ocultarElemento('archivos-subidos');
                    _this.cargarDatosTablaMaterialActividad();
                } else {                    
                    _this.cargarEvidenciasSubidas(_this.datosActividad.Evidencia);
                    _this.mostrarElemento('archivos-subidos');
                    _this.ocultarElemento('nodos-actividad');
                }
            }
        } catch (exception) {
            this.alerta.mostrarAlerta('Error', exception);
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

    cargarCabeceraActividad() {
        this.asignarValorElementoPagina('.actividad-capturo', this.datosActividad.NombreUsuario);
        this.asignarValorElementoPagina('.actividad-fecha-captura', this.datosActividad.FechaCaptura);
    }

    cargarFormularioActividad() {
        let formulario = this.formularios.get('form-nueva-actividad-tarea');
        this.asignarValorElementoPagina('#formulario-actividad', this.datosGeneralesProyecto.Formularios.formularioNuevaActividad);
        formulario.iniciarElementos();
        formulario.bloquearFormulario();
        this.asignarValorElementoPagina('textArea-descripcion-actividad', this.datosActividad.Descripcion);
        this.asignarValorElementoPagina('fecha-proyectada-actividad', this.datosActividad.FechaProyectada);
        this.asignarValorElementoPagina('fecha-real-actividad', this.datosActividad.FechaReal);
    }

    cargarDatosTablaMaterialActividad() {
        let _this = this;
        let tabla = _this.tablas.get('datatable-nodos-capturados-actividad');
        tabla.limpiartabla();

        $.each(_this.datosActividad.Nodos, function (key, value) {
            tabla.agregarDatosFila([value.Actividad, value.Ubicacion, value.Nombre]);
        });
    }

    cargarEvidenciasSubidas(archivos = []) {
        let evidencias = ``;

        $.each(archivos, function (key, value) {
            evidencias += `<div class="evidencia"><a href="${value}" data-lightbox="evidencias"><img src ="${value}" /></a><p>Evidencia_${key + 1}</p></div>`;
        });

        this.asignarValorElementoPagina('.evidenciasSubidas', evidencias);
    }

    mostrarDatosNodoActividad(datos = []) {
        try {
            if (Object.keys(datos).length > 0) {
                let _this = this, evidencias = '';
                _this.nodoSeleccionado = datos[2];
                let tabla = _this.tablas.get('datatable-material-nodo');
                let datosNodo = _this.datosActividad.Nodos[_this.nodoSeleccionado];
                _this.asignarValorElementoPagina('#nombre-nodo', _this.nodoSeleccionado);
                _this.mostrarSeccion('seccion-material-actividad');
                tabla.limpiartabla();
                $.each(datosNodo.Material, function (key, value) {
                    tabla.agregarDatosFila([value.IdMaterial, value.Material, value.Solicitado, value.Utilizado, value.Justificacion]);
                });
                $.each(datosNodo.Evidencia, function (key, value) {
                    evidencias += `<div class="evidencia"><a href="${value}" data-lightbox="evidencias"><img src ="${value}" /></a><p>Evidencia_${key + 1}</p></div>`;
                });
                _this.asignarValorElementoPagina('.evidenciasMaterialUtilizado', evidencias);
            }

        } catch (exception) {
            this.alerta.mostrarAlerta('Error', exception);
    }
    }

    cargarDatosSelectAFormularioMaterial(formulario) {
        let _this = this;
        let selectMaterial = formulario.obtenerElemento('select-material');
        let selectUbicacion = formulario.obtenerElemento('select-ubicacion');
        let selectNodo = formulario.obtenerElemento('select-nodo');

        this.generarListaMaterialTotal();
        selectMaterial.cargaDatosEnSelect(_this.listaMaterial);

        selectMaterial.evento('change', function () {
            _this.generarListaUbicaciones(selectMaterial.obtenerValor());
            selectUbicacion.cargaDatosEnSelect(_this.listaUbicacion);
            selectNodo.cargaDatosEnSelect([]);
            selectNodo.limpiarElemento();
        });

        selectUbicacion.evento('change', function () {
            _this.generarListaNodos(selectUbicacion.obtenerValor());
            selectNodo.cargaDatosEnSelect(_this.listaNodos);
        });

        selectNodo.evento('change', function () {
            _this.asignarValorElementoPagina('input-solicitado-nodo', _this.obtenerCantidadDeNodo(selectNodo.obtenerValor()));
        });
    }

    generarListaMaterialTotal() {

        let _this = this;
        let material = _this.datosGeneralesProyecto.datosProyecto.materiales;
        _this.listaMaterial = [];

        $.each(material, function (key, value) {
            _this.listaMaterial.push({id: key, text: value.nombre});
        });
    }

    generarListaUbicaciones(material = '') {
        let _this = this;
        let ubicacion = [];
        let alcance = _this.datosGeneralesProyecto.datosProyecto.alcance;
        _this.listaUbicacion = [];

        $.each(alcance, function (key, value) {
            $.each(value.Puntos, function (clave, valor) {
                if (valor.IdMaterial === material && ubicacion.indexOf(value.Ubicacion) === -1) {
                    ubicacion.push(value.Ubicacion);
                    _this.listaUbicacion.push({id: value.IdUbicacion, text: value.Ubicacion, material: valor.IdMaterial, puntos: value.Puntos});
                }
            });
        });
    }

    generarListaNodos(ubicacion = '') {
        let _this = this;
        _this.listaNodos = [];

        $.each(_this.listaUbicacion, function (key, value) {
            if (value.id === ubicacion) {
                $.each(value.puntos, function (clave, valor) {
                    if (valor.IdMaterial === value.material) {
                        _this.listaNodos.push({id: clave, text: valor.Nombre, cantidad: valor.Cantidad});
                    }

                });
            }
        });
    }

    obtenerCantidadDeNodo(nodo) {
        let cantidad = '';

        $.each(this.listaNodos, function (key, value) {
            if (value.id === parseInt(nodo)) {
                cantidad = value.cantidad;
            }
        });
        return cantidad;
    }

    habilitarFormularioTarea() {
        let formulario = this.formularios.get('form-nueva-tarea');

        formulario.habilitarFormulario();
        this.mostrarBtnsEditarTarea('btns-editar-tarea');
        this.ocultarElemento('contenedor-tabla-actividades');
        this.ocultarElemento('seccion-ubicacion-capturada');
    }

    mostrarBtnsEditarTarea(clase = '') {
        if (clase === 'btns-editar-tarea') {
            this.mostrarElemento('btns-editar-tarea');
            this.ocultarElemento('btns-informacion-tarea');
        } else if (clase === 'btns-informacion-tarea') {
            this.mostrarElemento('btns-informacion-tarea');
            this.ocultarElemento('btns-editar-tarea');
    }
    }

    cancelarActualizarTarea() {
        this.cargarFormularioTarea();
        this.mostrarBtnsEditarTarea('btns-informacion-tarea');
        this.mostrarElemento('contenedor-tabla-actividades');
        this.mostrarElemento('seccion-ubicacion-capturada');
    }

    actualizarTarea() {
        try {
            let _this = this;
            let formulario = _this.formularios.get('form-nueva-tarea');
            let datos = _this.validarFormularioTarea(formulario);
            datos.idAlcanceNodos = _this.idAlcanceNodosTarea;
            _this.enviarPeticionServidor('panel-seccion-proyecto', '/Proyectos/Seguimiento/Actualizar_Tarea', datos, function (respuesta) {
                _this.datosGeneralesProyecto = respuesta;
                _this.datosTarea = _this.datosGeneralesProyecto.datosProyecto.tareas[_this.tareaSeleccionada];
                _this.cargarDatosTareas();
                _this.cancelarActualizarTarea();
            });
        } catch (exception) {
            this.alerta.mostrarAlerta('Error', exception);
        }
    }

    confirmarEliminarTarea() {

        let _this = this;
        let datos = {idProyecto: _this.datosGeneralesProyecto.IdProyecto, idTarea: _this.tareaSeleccionada}
        let contenido = `<div class="row">
                                <div class="col-md-12 text-center">
                                    <p>Al realizar esta acción se borrara toda la información y no se podra recuperar.</p>
                                    <p>¿Estas seguro de querer eliminar la tarea del proyecto?</p>
                                </div>
                            </div>`;
        _this.modal.mostrarModal('Eliminar Tarea', contenido);
        _this.modal.funcionalidadBotonAceptar(null, function () {
            try {
                _this.enviarPeticionServidor('modal-dialogo', '/Proyectos/Seguimiento/Eliminar_Tarea', datos, function (respuesta) {
                    _this.datosGeneralesProyecto = respuesta;
                    _this.cargarDatosTareas();
                    _this.mostrarSeccion('seccion-tabla-tareas');
                    _this.modal.ocultarBotonAceptar();
                    let contenido = `<div class="row">
                                    <div class="col-md-12 text-center">
                                        <p>Se elimino la tarea con exito.</p>
                                    </div>
                                </div>`;
                    _this.modal.agregarContenido(contenido);
                    _this.modal.cambiarValorBotonCanelar('Cerrar');
                });
            } catch (exception) {
                _this.alerta.mostrarMensajeError('errorTareaNueva ', exception);
            }
        });
    }
}