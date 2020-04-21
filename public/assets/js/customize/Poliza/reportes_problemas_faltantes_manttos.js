$(function () {

    //Objetos
    var evento = new Base();
    var websocket = new Socket();
    var tabla = new Tabla();
    var select = new Select();
    var fecha = new Fecha();

    //Evento que maneja las peticiones del socket
    websocket.socketMensaje();

    //Muestra la hora en el sistema
    evento.horaServidor($('#horaServidor').val());

    //Evento para cerra la session
    evento.cerrarSesion();

    //Inicializa funciones de la plantilla
    App.init();

    select.crearSelectMultiple("#selectFiltroZonaSucursalesReportesPoliza", "Seleccionar");
    select.crearSelectMultiple("#selectFiltroSucursalReportePoliza", "Seleccionar");


    $('#selectFiltroZonaSucursalesReportesPoliza').on('change', function (event, data) {
        var zona = $(this).val();
        var data = {};
        data = {zona: zona};
        evento.enviarEvento('/Poliza/ReportesPoliza/consultaSucursalXRegionCliente', data, '#seccion-reportes-problemas-faltantes', function (respuesta) {
            if (respuesta instanceof Array || respuesta instanceof Object) {
                select.eliminarOptionSeleccionar('#selectFiltroSucursalReportePoliza');
                $('#selectFiltroSucursalReportePoliza').empty();
                $.each(respuesta, function (key, valor) {
                    $("#selectFiltroSucursalReportePoliza").append('<option value="' + valor.Id + '">' + valor.Nombre + '</option>');
                });
            }
        });
    });

    $('#btnMostrarReporteProblemasFaltantesMantemientos').off('click');
    $("#btnMostrarReporteProblemasFaltantesMantemientos").on("click", function () {
        var desde = $("#txtDesdeProblemasFaltantesMantenimiento").val();
        var hasta = $("#txtHastaProblemasFaltantesMantenimiento").val();
        var zonas = $("#selectFiltroZonaSucursalesReportesPoliza").val();
        var sucursales = $("#selectFiltroSucursalReportePoliza").val();
        if (desde !== '') {
            if (hasta !== '') {
                if (zonas !== null) {
                    var data = {desde: desde, hasta: hasta, zonas: zonas, sucursales: sucursales};
                    mostrarReporteProblemasFaltantesMantenimientos(data);
                } else {
                    evento.mostrarMensaje('#errorMostrarReporteProblemasFaltantesMantemientos', false, 'Debe seleccionar el campo Zona(s).', 3000);
                }
            } else {
                evento.mostrarMensaje('#errorMostrarReporteProblemasFaltantesMantemientos', false, 'Debe llenar el campo Hasta.', 3000);
            }
        } else {
            evento.mostrarMensaje('#errorMostrarReporteProblemasFaltantesMantemientos', false, 'Debe llenar el campo Desde.', 3000);
        }
    });

    var mostrarReporteProblemasFaltantesMantenimientos = function (data) {
        evento.enviarEvento('/Poliza/ReportesPoliza/mostrarReporteProblemasFaltantesMantenimientos', data, '#seccion-reportes-problemas-faltantes', function (respuesta) {
            $('#seccion-reportes-problemas-faltantes').addClass('hidden');
            $('#seccionReporteProblemasFaltantesMantemientos').removeClass('hidden').empty().append(respuesta.formulario);
            tabla.generaTablaPersonal('#data-table-problemas-sucursal', null, null, true, true);
            tabla.generaTablaPersonal('#data-table-problemas-zona', null, null, true, true);
            tabla.generaTablaPersonal('#data-table-problemas-area-atencion', null, null, true, true);
            tabla.generaTablaPersonal('#data-table-problemas-equipo', null, null, true, true);
            tabla.generaTablaPersonal('#data-table-problemas-sucursal-equipo', null, null, true, true);
            tabla.generaTablaPersonal('#data-table-faltantes-sucursal', null, null, true, true);
            tabla.generaTablaPersonal('#data-table-faltantes-zona', null, null, true, true);
            tabla.generaTablaPersonal('#data-table-faltantes-area-atencion', null, null, true, true);
            tabla.generaTablaPersonal('#data-table-faltantes-equipo', null, null, true, true);
            tabla.generaTablaPersonal('#data-table-faltantes-sucursal-equipo', null, null, true, true);
            tabla.limpiarTabla('#data-table-problemas-sucursal');
            tabla.limpiarTabla('#data-table-problemas-zona');
            tabla.limpiarTabla('#data-table-problemas-area-atencion');
            tabla.limpiarTabla('#data-table-problemas-equipo');
            tabla.limpiarTabla('#data-table-problemas-sucursal-equipo');
            tabla.limpiarTabla('#data-table-faltantes-sucursal');
            tabla.limpiarTabla('#data-table-faltantes-zona');
            tabla.limpiarTabla('#data-table-faltantes-area-atencion');
            tabla.limpiarTabla('#data-table-faltantes-equipo');
            tabla.limpiarTabla('#data-table-faltantes-sucursal-equipo');

            $.each(respuesta.datos.ProblemasXSucursal, function (key, valor) {
                tabla.agregarFila('#data-table-problemas-sucursal', [valor.Nombre, valor.Total], true);
            });

            $.each(respuesta.datos.ProblemasXZona, function (key, valor) {
                tabla.agregarFila('#data-table-problemas-zona', [valor.Zona, valor.Problemas, valor.Tickets], true);
            });

            $.each(respuesta.datos.ProblemasXAreaAtencion, function (key, valor) {
                tabla.agregarFila('#data-table-problemas-area-atencion', [valor.Area, valor.Total], true);
            });

            $.each(respuesta.datos.ProblemasXEquipo, function (key, valor) {
                tabla.agregarFila('#data-table-problemas-equipo', [valor.Equipo, valor.Total, valor.Zona1, valor.Zona2, valor.Zona3, valor.Zona4], true);
            });

            if (respuesta.datos.ProblemasXSucursalEquipo.length > 0) {
                $('#thProblemaModelo1').empty().html(respuesta.datos.ProblemasXSucursalEquipo[0]['NombreEquipo1']);
                $('#thProblemaModelo2').empty().html(respuesta.datos.ProblemasXSucursalEquipo[0]['NombreEquipo2']);
                $('#thProblemaModelo3').empty().html(respuesta.datos.ProblemasXSucursalEquipo[0]['NombreEquipo3']);
            }

            $.each(respuesta.datos.ProblemasXSucursalEquipo, function (key, valor) {
                tabla.agregarFila('#data-table-problemas-sucursal-equipo', [valor.Sucursal, valor.mod1, valor.mod2, valor.mod3], true);
            });

            $.each(respuesta.datos.FaltantesXSucursal, function (key, valor) {
                tabla.agregarFila('#data-table-faltantes-sucursal', [valor.Nombre, valor.Total], true);
            });

            $.each(respuesta.datos.FaltantesXZona, function (key, valor) {
                tabla.agregarFila('#data-table-faltantes-zona', [valor.Zona, valor.Faltantes, valor.Tickets], true);
            });

            $.each(respuesta.datos.FaltantesXAreaAtencion, function (key, valor) {
                tabla.agregarFila('#data-table-faltantes-area-atencion', [valor.Area, valor.Total], true);
            });

            $.each(respuesta.datos.FaltantesXEquipo, function (key, valor) {
                tabla.agregarFila('#data-table-faltantes-equipo', [valor.Equipo, valor.Total, valor.Zona1, valor.Zona2, valor.Zona3, valor.Zona4], true);
            });

            if (respuesta.datos.FaltantesXSucursalEquipo.length > 0) {
                $('#thFaltanteModelo1').empty().html(respuesta.datos.FaltantesXSucursalEquipo[0]['NombreEquipo1']);
                $('#thFaltanteModelo2').empty().html(respuesta.datos.FaltantesXSucursalEquipo[0]['NombreEquipo2']);
                $('#thFaltanteModelo3').empty().html(respuesta.datos.FaltantesXSucursalEquipo[0]['NombreEquipo3']);
            }

            $.each(respuesta.datos.FaltantesXSucursalEquipo, function (key, valor) {
                tabla.agregarFila('#data-table-faltantes-sucursal-equipo', [valor.Sucursal, valor.mod1, valor.mod2, valor.mod3], true);
            });

            $('#btnRegresarProblemasFaltantesMantenimientos').off('click');
            $("#btnRegresarProblemasFaltantesMantenimientos").on("click", function () {
                $("#seccionReporteProblemasFaltantesMantemientos").addClass("hidden");
                $("#seccion-reportes-problemas-faltantes").removeClass("hidden");
            });

            $('#btnGeneraPdfReportesProblemasFaltantesMantenimientos').off('click');
            $("#btnGeneraPdfReportesProblemasFaltantesMantenimientos").on("click", function () {
                var problemasSucursal = $('#data-table-problemas-sucursal').DataTable().rows({search: 'applied'}).data();
                var problemasZona = $('#data-table-problemas-zona').DataTable().rows({search: 'applied'}).data();
                var problemasAreaAtencion = $('#data-table-problemas-area-atencion').DataTable().rows({search: 'applied'}).data();
                var problemasEquipo = $('#data-table-problemas-equipo').DataTable().rows({search: 'applied'}).data();
                var problemasSucursalEquipo = $('#data-table-problemas-sucursal-equipo').DataTable().rows({search: 'applied'}).data();
                var faltantesSucursal = $('#data-table-faltantes-sucursal').DataTable().rows({search: 'applied'}).data();
                var faltantesZona = $('#data-table-faltantes-zona').DataTable().rows({search: 'applied'}).data();
                var faltantesAreaAtencion = $('#data-table-faltantes-area-atencion').DataTable().rows({search: 'applied'}).data();
                var faltantesEquipo = $('#data-table-faltantes-equipo').DataTable().rows({search: 'applied'}).data();
                var faltantesSucursalEquipo = $('#data-table-faltantes-sucursal-equipo').DataTable().rows({search: 'applied'}).data();
                var realProblemasSucursal = new Array();
                var realProblemasZona = new Array();
                var realProblemasAreaAtencion = new Array();
                var realProblemasEquipo = new Array();
                var realProblemasSucursalEquipo = new Array();
                var realFaltantesSucursal = new Array();
                var realFaltantesZona = new Array();
                var realFaltantesAreaAtencion = new Array();
                var realFaltantesEquipo = new Array();
                var realFaltantesSucursalEquipo = new Array();

                $.each(problemasSucursal, function (k, v) {
                    if (!isNaN(k)) {
                        realProblemasSucursal.push(v);
                    }
                });

                $.each(problemasZona, function (k, v) {
                    if (!isNaN(k)) {
                        realProblemasZona.push(v);
                    }
                });

                $.each(problemasAreaAtencion, function (k, v) {
                    if (!isNaN(k)) {
                        realProblemasAreaAtencion.push(v);
                    }
                });

                $.each(problemasEquipo, function (k, v) {
                    if (!isNaN(k)) {
                        realProblemasEquipo.push(v);
                    }
                });

                $.each(problemasSucursalEquipo, function (k, v) {
                    if (!isNaN(k)) {
                        realProblemasSucursalEquipo.push(v);
                    }
                });

                $.each(faltantesSucursal, function (k, v) {
                    if (!isNaN(k)) {
                        realFaltantesSucursal.push(v);
                    }
                });
                $.each(faltantesZona, function (k, v) {
                    if (!isNaN(k)) {
                        realFaltantesZona.push(v);
                    }
                });

                $.each(faltantesAreaAtencion, function (k, v) {
                    if (!isNaN(k)) {
                        realFaltantesAreaAtencion.push(v);
                    }
                });

                $.each(faltantesEquipo, function (k, v) {
                    if (!isNaN(k)) {
                        realFaltantesEquipo.push(v);
                    }
                });

                $.each(faltantesSucursalEquipo, function (k, v) {
                    if (!isNaN(k)) {
                        realFaltantesSucursalEquipo.push(v);
                    }
                });

                var data = {
                    problemasSucursal: realProblemasSucursal,
                    problemasZona: realProblemasZona,
                    problemasAreaAtencion: realProblemasAreaAtencion,
                    problemasEquipo: realProblemasEquipo,
                    problemasSucursalEquipo: realProblemasSucursalEquipo,
                    faltantesSucursal: realFaltantesSucursal,
                    faltantesZona: realFaltantesZona,
                    faltantesAreaAtencion: realFaltantesAreaAtencion,
                    faltantesEquipo: realFaltantesEquipo,
                    faltantesSucursalEquipo: realFaltantesSucursalEquipo,
                    thProblemaSucursalEquipo1: $('#thProblemaModelo1').text(),
                    thProblemaSucursalEquipo2: $('#thProblemaModelo2').text(),
                    thProblemaSucursalEquipo3: $('#thProblemaModelo3').text(),
                    thFaltanteSucursalEquipo1: $('#thFaltanteModelo1').text(),
                    thFaltanteSucursalEquipo2: $('#thFaltanteModelo2').text(),
                    thFaltanteSucursalEquipo3: $('#thFaltanteModelo3').text()
                };

                if (realProblemasSucursal.length > 0) {
                    if (realProblemasZona.length > 0) {
                        if (realProblemasAreaAtencion.length > 0) {
                            if (realProblemasEquipo.length > 0) {
                                if (realProblemasSucursalEquipo.length > 0) {
                                    if (realFaltantesSucursal.length > 0) {
                                        if (realFaltantesZona.length > 0) {
                                            if (realFaltantesAreaAtencion.length > 0) {
                                                if (realFaltantesEquipo.length > 0) {
                                                    if (realFaltantesSucursalEquipo.length > 0) {
                                                        evento.enviarEvento('/Poliza/ReportesPoliza/exportaReporteProblemasFaltantesMantenimientos', data, '#seccion-reportes-problemas-faltantes', function (respuesta) {
                                                            window.open(respuesta.ruta, '_blank');
                                                        });
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            });
        });
    };

    fecha.rangoFechas('#desdeProblemasFaltantesMantenimiento', '#hastaProblemasFaltantesMantenimiento');
    
    $('#reporteAnual').on('click', function () {
        evento.enviarEvento('/Generales/Reportes/solicitudAnual', {}, '#seccion-reportes-problemas-faltantes', function (respuesta) {
            if (respuesta) {
                window.open(respuesta.ruta, '_blank');
            }
        });
    });
    
    $('#reporteSemanal').on('click', function () {
        evento.enviarEvento('/Generales/Reportes/solicitudSemanal', {}, '#seccion-reportes-problemas-faltantes', function (respuesta) {
            if (respuesta) {
                window.open(respuesta.ruta, '_blank');
            }
        });
    });
    
    $('#compararFolios').on('click', function () {
        evento.enviarEvento('/Poliza/Tester/solicitarFolios', {}, '#seccion-reportes-problemas-faltantes', function (respuesta) {
            if (respuesta) {
                window.open(respuesta.ruta, '_blank');
            }
        });
    });
    
    $('#equiposRefacciones').on('click', function () {
        evento.enviarEvento('/Generales/Reportes/EquiposRefaccionesCorrectivo', {}, '#seccion-reportes-problemas-faltantes', function (respuesta) {
            if (respuesta) {
                window.open(respuesta.ruta, '_blank');
            }
        });
    });
});