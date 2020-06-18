$(function () {

    App.init();

    var objetosPagina = new Map();

    var tablas = {
        'data-table-sinIniciar': {
            tipoTabla: 'columnasOcultas',
            datos: []
        },
        'data-table-nuevoProyecto': {
            tipoTabla: 'basica',
            datos: []
        },
        'data-table-material-alcance': {
            tipoTabla: 'columnasOcultas',
            datos: []
        },
        'data-table-material-nodo': {
            tipoTabla: 'columnasOcultas',
            datos: []
        },
        'data-table-asistentes': {
            tipoTabla: 'columnasOcultas',
            datos: []
        },
        'data-table-tareas': {
            tipoTabla: 'columnasOcultas',
            datos: []
        },
        'data-table-nodos-capturados': {
            tipoTabla: 'columnasOcultas',
            datos: []
        },
        'data-table-nodos-tarea': {
            tipoTabla: 'columnasOcultas',
            datos: []
        }
    };

    var gantt = {
        'gantt_here': ''
    };

    var formularios = {
        'form-nuevo-proyecto': {
            selects: {
                'select-sistemas': 'basico',
                'select-tipo-proyecto': 'basico',
                'select-complejo': 'multiple',
                'select-lideres': 'multiple'
            },
            fechas: {
                'fecha-inicio-proyecto': '',
                'fecha-final-proyecto': ''
            },
            inputs: {
                'input-nombre-proyecto': '',
                'textArea-observaciones': ''
            }
        },
        'form-nuevo-nodo-proyecto': {
            selects: {
                'select-concepto': 'basico',
                'select-area': 'basico',
                'select-ubicacion': 'basico'
            }
        },
        'form-define-material-nodo': {
            selects: {
                'select-tipo-nodo': 'basico',
                'select-accesorio': 'basico',
                'select-material-nodo': 'basico'
            },
            inputs: {
                'input-nombre-nodo': '',
                'input-cantidad-material': ''
            }
        },
        'form-agregar-asistente': {
            selects: {
                'select-asistente': 'basico'
            }
        },
        'form-solicitud-personal': {
            inputs: {
                'textarea-perfil-personal': ''
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
        'form-agregar-complejo-a-proyecto': {
            selects: {
                'select-agregar-complejo': 'multiple'
            }
        },
        'form-eliminar-proyecto': {
            inputs: {
                'textarea-eliminar-proyecto': ''
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

    var vistaNuevo = new PaginaNuevo(objetosPagina);

    $('#btn-proyecto-nuevo').off('click');
    $('#btn-proyecto-nuevo').on('click', function () {
        vistaNuevo.activarPestanaGenerales();
        vistaNuevo.desbloquearFormulario('form-nuevo-proyecto');
        vistaNuevo.mostrarElemento('btn-generar-proyecto');
        vistaNuevo.mostrarElemento('contenedor-select-complejo');
        vistaNuevo.mostrarElemento('btn-limpiar-formulario');
        vistaNuevo.ocultarElemento('btn-actualizar-proyecto');
        vistaNuevo.ocultarElemento('pestana-alcance');
        vistaNuevo.ocultarElemento('pestana-material');
        vistaNuevo.ocultarElemento('pestana-personal');
        vistaNuevo.ocultarElemento('pestana-tareas');
        vistaNuevo.ocultarElemento('cabecera-generales');
        vistaNuevo.ocultarElemento('info-sin-complejo');
        $("#divListaProyectos").fadeOut(400, function () {
            $("#divDetallesProyecto").fadeIn(400);
        });
    });

    $('#divDetallesProyecto #btnRegresar').off('click');
    $('#divDetallesProyecto #btnRegresar').on('click', function () {
        $("#divDetallesProyecto").fadeOut(400, function () {
            $("#divListaProyectos").fadeIn(400);
        });
    });

    $('#btn-limpiar-formulario').on('click', function () {
        vistaNuevo.limpiarFormulario('form-nuevo-proyecto');
    });

    $('#btn-generar-proyecto').on('click', function () {
        vistaNuevo.generarNuevoProyecto();
    });

    vistaNuevo.obtenerDatoFilaTabla('data-table-sinIniciar', function (datos) {
        vistaNuevo.mostrarDatosProyecto(datos);
    });

    $('#btn-actualizar-proyecto').on('click', function () {
        vistaNuevo.activarFormularioDeDatosGenerales();
    });

    $('#btn-guardar-cambios').on('click', function () {
        vistaNuevo.actualizarDatosGenerales();
    });

    $('#btn-cancelar').on('click', function () {
        vistaNuevo.cancelarActualizacionDeDatosGenerales();
    });

    $('#btn-nuevo-nodo').on('click', function () {
        vistaNuevo.mostrarFormularioNuevoNodo();
    });

    $('#btn-lista-nodos').off('click');
    $('#btn-lista-nodos').on('click', function () {
        vistaNuevo.mostrarListaNodosCapturados();
    });    

    $('#btn-generar-solicitud-material').on('click', function () {
        vistaNuevo.generarSolicitudMaterial();
    });

    $('#btn-agregar-asistente').on('click', function () {
        vistaNuevo.agregarAsistenteProyecto();
    });

    vistaNuevo.obtenerDatoFilaTabla('data-table-asistentes', function (datos) {
        vistaNuevo.quitarAsistenteProyecto(datos);
    });

    $('#btn-solicitud-personal').on('click', function () {
        vistaNuevo.mostraFormularioSolicitudPersonal();
    });

    $('#btn-nueva-tarea').on('click', function () {
        vistaNuevo.mostrarFormularioNuevaTarea();
    });

    vistaNuevo.obtenerDatoFilaTabla('data-table-tareas', function (datos) {
        vistaNuevo.actualizarTarea(datos);
    });

    $('#btn-gantt').on('click', function () {
        vistaNuevo.mostrarGantt();
    });

    $('#btn-tabla-tareas').on('click', function () {
        vistaNuevo.mostrarListaTareas();
    });

    $('.btn-nuevo-complejo-proyecto').on('click', function () {
        vistaNuevo.mostrarFormularioNuevoComplejo();
    });

    $('.btn-eliminar-complejo').on('click', function () {
        vistaNuevo.eliminarComplejoDeProyecto('Eliminar_Complejo');
    });

    $('.btn-eliminar-proyecto').on('click', function () {
        vistaNuevo.eliminarComplejoDeProyecto('Eliminar_Proyecto');
    });

    $('.btn-iniciar-proyecto-complejo').on('click', function () {
        vistaNuevo.iniciarProyecto();
    });

    $('#cerrar-sesion').on('click', function () {
        vistaNuevo.cerrarSesion();
    });

    $('.btn-reporte-inicio-proyecto').on('click', function () {
        vistaNuevo.obtenerReporteInicioProyecto();
    });

    $('.btn-reporte-material').on('click', function () {
        vistaNuevo.obtenerReporteMaterial();
    });

});


