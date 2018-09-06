$(function () {

    App.init();

    var objetosPagina = new Map();

    var tablas = {
        'data-table-proyecto-tareas-asignadas': {
            tipoTabla: 'columnasOcultas',
            datos: []
        },
        'data-table-dias-actividad-tarea': {
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

    var formularios = {
        'form-nueva-actividad-tarea': {
            fechas: {
                'fecha-proyectada-actividad': '',
                'fecha-real-actividad': ''
            },
            inputs: {
                'textArea-descripcion-actividad': ''
            },
            'filesUpload': {
                'file-evidencia-actividad': {
                    tipo: 'basico',
                    url: '/Proyectos/Tareas/Generar_Actividad'
                }
            }
        },
        'form-definiendo-nodo': {
            selects: {
                'select-nodo': 'basico'
            }
        },
        'form-definiendo-material-nodo': {
            selects: {
                'select-material': 'basico'
            },
            inputs: {
                'input-utilizado-material-nodo': '',
                'input-solicitado-nodo': ''
            },
            'filesUpload': {
                'file-evidencia-material-utilizado': {
                    tipo: 'basico',
                    url: '/Proyectos/Tareas/Agregar_Nodo_Actividad'
                }
            }
        },
        'form-justificar-material': {
            inputs: {
                'textarea-justificar': ''
            }
        }
    };

    objetosPagina.set('tablas', tablas);
    objetosPagina.set('formularios', formularios);

    var vistaTareas = new PaginaTareas(objetosPagina);

    vistaTareas.obtenerDatoFilaTabla('data-table-proyecto-tareas-asignadas', function (datos) {
        vistaTareas.mostrarTarea(datos);
    });

    $('#btn-regresar-lista-tareas').on('click', function () {
        vistaTareas.mostrarApartado('Tareas');
    });

    $('#btn-nueva-actividad').on('click', function () {        
        vistaTareas.agregarActividad();        
    });

    $('#btn-generar-nueva-actividad').on('click', function () {
        vistaTareas.generarActividad();
    });

    $('#btn-cancelar-nueva-actividad').on('click', function () {
        vistaTareas.mostrarSeccion('seccion-tabla-dias-actividad');
    });

    $('#btn-agregar-nodo-actividad').on('click', function () {
        vistaTareas.mostrarFormularioNodo();
    });

    $('#btn-mostrar-formulario-material-nodo').on('click', function () {
        vistaTareas.agregarNodo();
    });

    $('#btn-agregar-material-nodo').on('click', function () {
        vistaTareas.agregarMaterialNodo();
    });
    
    $('#btn-guardar-material-nodo').on('click', function () {
        vistaTareas.guardarNodoActividad();
    });

    $('#btn-cancelar-material-nodo').on('click', function () {
        vistaTareas.mostrarSeccion('seccion-formulario-actividad');
    });

    vistaTareas.obtenerDatoFilaTabla('datatable-nodos-capturados-actividad', function (datos) {
        vistaTareas.mostrarDatosMaterialCapturado(datos);
    });

    $('#btn-regresar-actividad').on('click', function () {
        vistaTareas.mostrarSeccion('seccion-formulario-actividad');
    });

    $('#btn-eliminar-nodo').on('click', function () {
        vistaTareas.eliminarMaterialUtilizadoDeActividad();
    });

    $('#btn-regresar-tabla-actividades').on('click', function () {
        vistaTareas.mostrarSeccion('seccion-tabla-dias-actividad');
    });

    vistaTareas.obtenerDatoFilaTabla('data-table-dias-actividad-tarea', function (datos) {
        vistaTareas.cargarDatosDeDiaDeActividad(datos);
    });
//
//    $('#btn-actualizar-nueva-actividad').on('click', function () {
//        vistaTareas.actualizarActividad();
//    });
//
    $('#btn-eliminar-actividad').on('click', function () {
        vistaTareas.eliminarActividad();
    });

    $('#cerrar-sesion').on('click', function () {
        vistaTareas.cerrarSesion();
    });

});


