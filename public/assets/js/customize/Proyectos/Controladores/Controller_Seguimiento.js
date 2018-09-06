$(function () {

    App.init();

    var objetosPagina = new Map();

    var tablas = {
        'data-table-proyectos-iniciados': {
            tipoTabla: 'columnasOcultas',
            datos: []
        },
        'data-table-tareas': {
            tipoTabla: 'columnasOcultas',
            datos: []
        },
        'data-table-actividades': {
            tipoTabla: 'columnasOcultas',
            datos: []
        },
        'datatable-nodos-capturados-actividad': {
            tipoTabla: 'columnasOcultas',
            datos: []
        },
        'data-table-nodos-tarea': {
            tipoTabla: 'columnasOcultas',
            datos: []
        },
        'datatable-material-nodo': {
            tipoTabla: 'columnasOcultas',
            datos: []
        }
    };

    var gantt = {
        'gantt_here': ''
    };

    var formularios = {
        'form-proyecto-iniciado': {
            fechas: {
                'fecha-inicio-proyecto': '',
                'fecha-final-proyecto': ''
            }
        },
        'form-nueva-tarea': {
            selects: {
                'select-area-tarea': 'basico',
                'select-lider-tarea': 'basico',
                'select-asistente-tarea': 'multiple'
            },
            fechas: {
                'fecha-inicio-tarea': '',
                'fecha-fin-tarea': ''
            },
            inputs: {
                'input-nombre-tarea': '',
                'checbox-repetir-tarea': ''
            }
        },
        'form-nueva-actividad-tarea': {
            fechas: {
                'fecha-proyectada-actividad': '',
                'fecha-real-actividad': ''
            },
            inputs: {
                'textArea-descripcion-actividad': ''
            }
        },
        'form-definiendo-material-utilizado': {
            selects: {
                'select-material': 'basico',
                'select-ubicacion': 'basico',
                'select-nodo': 'basico'
            },
            inputs: {
                'input-utilizado-material-actividad': '',
                'input-solicitado-nodo': ''
            }
        },
        'form-nodos-tarea': {
            selects: {
                'select-ubicacion-nodo-tarea': 'basico',
                'select-nodo-tarea': 'basico'
            }
        }
    };

    objetosPagina.set('tablas', tablas);
    objetosPagina.set('formularios', formularios);
    objetosPagina.set('gantt', gantt);

    var vistaSeguimiento = new PaginaSeguimiento(objetosPagina);

    vistaSeguimiento.obtenerDatoFilaTabla('data-table-proyectos-iniciados', function (datos) {
        vistaSeguimiento.mostrarDatosProyecto(datos);
    });

    $('#btn-regresar-lista-proyectos').on('click', function () {
        vistaSeguimiento.mostrarPanel('panel-table-proyectos');
    });

    $('#btn-actualizar-proyecto').on('click', function () {
        vistaSeguimiento.habilitarFormularioDatosGenerelas();
    });

    $('#btn-guardar-actualizacion').on('click', function () {
        vistaSeguimiento.actualizarDatosGenerales();
    });

    $('#btn-cancelar-actualizar').on('click', function () {
        vistaSeguimiento.cancelarActualizarDatosGenerales();
    });

    $('#btn-nueva-tarea').on('click', function () {
        vistaSeguimiento.mostrarFormularioNuevaTarea();
    });

    $('#btn-gantt').on('click', function () {
        vistaSeguimiento.mostrarGantt();
    });

    $('#btn-tabla-tareas').on('click', function () {
        vistaSeguimiento.mostrarListaTareas();
    });

    vistaSeguimiento.obtenerDatoFilaTabla('data-table-tareas', function (datos) {
        vistaSeguimiento.mostrarDatosTarea(datos);
    });

    vistaSeguimiento.obtenerDatoFilaTabla('data-table-actividades', function (datos) {
        vistaSeguimiento.mostrarDatosDiaActividad(datos);
    });

    vistaSeguimiento.obtenerDatoFilaTabla('datatable-nodos-capturados-actividad', function (datos) {
        vistaSeguimiento.mostrarDatosNodoActividad(datos);
    });

    $('#btn-regresar-dia-actividad').on('click', function () {
        vistaSeguimiento.mostrarSeccion('seccion-actividad');
    });

    $('#btn-regresar-tarea').on('click', function () {
        vistaSeguimiento.mostrarSeccion('seccion-tarea');
    });

    $('#btn-regresar-tareas-lista').on('click', function () {
        vistaSeguimiento.mostrarSeccion('seccion-tabla-tareas');
    });

    $('#btn-actualizar-tarea').on('click', function () {
        vistaSeguimiento.habilitarFormularioTarea();
    });

    $('#btn-cancelar-actualizar-tarea').on('click', function () {
        vistaSeguimiento.cancelarActualizarTarea();
    });

    $('#btn-confirmar-actualizar-tarea').on('click', function () {
        vistaSeguimiento.actualizarTarea();
    });

    $('#btn-confirmar-eliminar-tarea').on('click', function () {
        vistaSeguimiento.confirmarEliminarTarea();
    });

    $('#cerrar-sesion').on('click', function () {
        vistaSeguimiento.cerrarSesion();
    });

});


