$(function () {

    peticion = new Utileria();

    evento = new Base();
    evento.horaServidor($('#horaServidor').val());
    evento.cerrarSesion();
    evento.mostrarAyuda('Ayuda_Proyectos');

    //Inicializa funciones de la plantilla
    App.init();
    PageWithTwoSidebar.init();

    //Globales
    let tablaTipoProyecto = new TablaBasica('data-table-tipo-proyectos');
    let tablaProyectos = new TablaBasica('data-table-proyectos');
    let graficaPrincipal = new GraficaGoogle('graphDashboard', tablaTipoProyecto.datosTabla());
    tablaTipoProyecto.reordenarTabla(2, 'desc');
    tablaProyectos.reordenarTabla(3, 'desc');
    graficaPrincipal.inicilizarGrafica({
        title: 'Moneda en Pesos',
        titleTextStyle: {
            fontSize: 18,
            bold: true,
            italic: true
        },
        is3D: true
    });

    let graficaPrincipalUSD = null;
    let tablaProyecto = null;
    let tablaServicio = null;
    let tablaSucursal = null;
    let tablaCategoria = null;
    let tablaSubCategoria = null;
    let tablaConcepto = null;
    let graficaProyecto = null;
    let graficaServicio = null;
    let graficaSucursal = null;
    let graficaCategoria = null;
    let graficaSubCategoria = null;
    let graficaConcepto = null;

    let datosProyectos = null;
    let datosServicios = null;
    let datosSucursales = null;
    let datosCategoria = null;
    let datosSubCategoria = null;
    let datosConceptos = null;
    let datosCompras = null;

    let alertaFiltros = null;
    let selectorproyecto = null;
    let selectorservicio = null;
    let selectorsucursal = null;
    let selectorcategoria = null;
    let selectorsubcategoria = null;
    let selectorconcepto = null;
    let tablaDetalles = null;

    let datosProyecto = Array();
    let datosFiltros = {
        tipoProyecto: null,
        moneda: 'MN',
        fechaInicio: null,
        fechaFinal: null,
        proyecto: null,
        sucursal: null,
        servicio: null,
        categoria: null,
        subcategoria: null,
        concepto: null
    };
    let anterioresFiltros = {
        tipoProyecto: null,
        moneda: 'MN',
        fechaInicio: null,
        fechaFinal: null,
        proyecto: null,
        sucursal: null,
        servicio: null,
        categoria: null,
        subcategoria: null,
        concepto: null
    };
    let fecha = new Date();

    filtroFechas();
    filtroMoneda();
    setDastosProyectos();
    function setDastosProyectos() {
        let temporal = tablaProyectos.datosTabla();
        datosProyecto = Array();
        $.each(temporal, function (key, value) {
            datosProyecto.push({
                id: value[1],
                nombre: value[2],
                tipo: value[0],
                gasto: value[3],
                fecha: value[4],
                fechaFin: value[5]
            });
        });
    }

    tablaTipoProyecto.evento(function () {
        let datosfila = tablaTipoProyecto.datosFila(this);
        let datosFiltradosProyecto = null;
        tablaProyectos.limpiartabla();
        datosFiltradosProyecto = filtrarDatos(datosProyecto, {condicion: 'tipo', valor: datosfila[0]});
        $.each(datosFiltradosProyecto, function (key, value) {
            tablaProyectos.agregarDatosFila([
                value.tipo,
                value.id,
                value.nombre,
                value.gasto,
                value.fecha,
                value.fechaFin
            ]);
        });
        $('html, body').animate({
            scrollTop: $("#titulo-tabla-proyectos").offset().top - 60
        }, 600);
    });

    function filtrarDatos(datos, filtros) {
        let datosFiltrados = [];
        $.each(datos, function (key, value) {
            if (filtros.valor === value[filtros.condicion]) {
                datosFiltrados.push(value);
            }
        });
        return datosFiltrados;
    }

    graficaPrincipal.agregarListener(function (dato) {
        datosFiltros.tipoProyecto = dato;
        datosFiltros.moneda = datosFiltros['moneda'];
        enviarInformacionFiltros('panelDashboardGapsi', datosFiltros);
    });

    tablaProyectos.evento(function () {
        let datosfila = tablaProyectos.datosFila(this);
        datosFiltros.tipoProyecto = datosfila[0];
        datosFiltros.proyecto = datosfila[1];
        datosFiltros.moneda = datosFiltros['moneda'];
        enviarInformacionFiltros('panelDashboardGapsi', datosFiltros);
    });

    function filtroFechas() {
        $('#desdePrincipal').datepicker({
            format: 'yyyy-mm-dd',
            startDate: new Date('2016-07-08'),
            endDate: fecha
        });
        $('#hastaPrincipal').datepicker({
            format: 'yyyy-mm-dd',
            startDate: new Date('2016-07-08'),
            endDate: fecha
        });
        $('#desdePrincipal').datepicker('setDate', '2016-07-07');
        $('#hastaPrincipal').datepicker('setDate', fecha.getFullYear + '-' + fecha.getMonth() + 1);
        $("#btnFiltrarDashboardPrincipal").on('click', function () {
            datosFiltros.fechaInicio = $('#fechaComienzoPrincipal').val() + "T00:00:00.000";
            datosFiltros.fechaFinal = $('#fechaFinPrincipal').val() + "T23:59:59.999";
            enviarFiltrosPrincipal('panelDashboardGapsiFilters', datosFiltros);
        });

        $('#desde').datepicker({
            format: 'yyyy-mm-dd',
            startDate: new Date('2016-07-08'),
            endDate: fecha
        });
        $('#hasta').datepicker({
            format: 'yyyy-mm-dd',
            startDate: new Date('2016-07-08'),
            endDate: fecha
        });
        $("#btnFiltrarDashboard").on('click', function () {
            datosFiltros.fechaInicio = $("#fechaComienzo").val() + "T00:00:00.000";
            datosFiltros.fechaFinal = $("#fechaFin").val() + "T23:59:59.999";
            enviarInformacionFiltros('panelDashboardGapsiFilters', datosFiltros);
        });
    }

    function filtroMoneda() {
        $("input[name='optionsRadiosMonedaPrincipal").click(function () {
            var radioValueFiltrosP = $("input[name='optionsRadiosMonedaPrincipal']:checked").val();
            datosFiltros.moneda = radioValueFiltrosP;
            enviarFiltrosPrincipal('panelDashboardGapsiFilters', datosFiltros);
        });
        $("input[name='optionsRadiosMoneda").click(function () {
            var radioValueFiltros = $("input[name='optionsRadiosMoneda']:checked").val();
            datosFiltros.moneda = radioValueFiltros;
            enviarInformacionFiltros('panelDashboardGapsiFilters', datosFiltros);
        });
    }

    function enviarFiltrosPrincipal(objeto, datosFiltros) {
        peticion.enviar(objeto, 'Dashboard_Gapsi/filtroPrincipal', datosFiltros, function (respuesta) {
            if (respuesta.tipoProyectos.length !== 0) {
                anterioresFiltros = JSON.parse(JSON.stringify(datosFiltros));
                actualizarObjetosPrincipal(respuesta);
            } else {
                modalUndefined();
            }
        });
    }

    function actualizarObjetosPrincipal(respuesta) {
        tablaTipoProyecto.limpiartabla();
        $.each(respuesta.tipoProyectos, function (key, value) {
            tablaTipoProyecto.agregarDatosFila([
                value.Tipo,
                value.Proyectos,
                '$ ' + formatoNumero(value.Importe.toFixed(2))
            ]);
        });
        tablaProyectos.limpiartabla();
        $.each(respuesta.listaProyectos, function (key, value) {
            tablaProyectos.agregarDatosFila([
                value.Tipo,
                value.IdProyecto,
                value.Descripcion,
                '$ ' + formatoNumero(value.Gasto.toFixed(2)),
                value.FCreacion,
                value.UltimoRegistro
            ]);
        });
        setDastosProyectos();
        if (datosFiltros['moneda'] !== 'MN' && datosFiltros['moneda'] !== null) {
            $('#graficaMN').addClass('hidden');
            $('#graficaUSD').removeClass('hidden');
            graficaPrincipalUSD = new GraficaGoogle('graphDashboardUSD', tablaTipoProyecto.datosTabla());
            graficaPrincipalUSD.inicilizarGrafica({
                title: 'Moneda en Dolar',
                titleTextStyle: {
                    fontSize: 18,
                    bold: true,
                    italic: true
                },
                is3D: true
            });
            graficaPrincipalUSD.agregarListener(function (dato) {
                datosFiltros.tipoProyecto = dato;
                enviarInformacionFiltros('panelDashboardGapsi', datosFiltros);
            });
        } else {
            $('#graficaMN').removeClass('hidden');
            $('#graficaUSD').addClass('hidden');
        }
    }

    function incializarObjetos() {
        tablaProyecto = new TablaBasica('data-tipo-proyecto');
        tablaServicio = new TablaBasica('data-tipo-servicio');
        tablaSucursal = new TablaBasica('data-tipo-sucursal');
        tablaCategoria = new TablaBasica('data-tipo-categoria');
        tablaSubCategoria = new TablaBasica('data-tipo-subCategoria');
        tablaConcepto = new TablaBasica('data-tipo-concepto');
        tablaProyecto.reordenarTabla(2, 'desc');
        tablaServicio.reordenarTabla(2, 'desc');
        tablaSucursal.reordenarTabla(2, 'desc');
        tablaCategoria.reordenarTabla(2, 'desc');
        tablaSubCategoria.reordenarTabla(2, 'desc');
        tablaConcepto.reordenarTabla(2, 'desc');
        graficaProyecto = new GraficaGoogle('chart_proyecto', filtrarDatosGraficaGoogle(datosProyectos, 'Proyecto', 'Gasto'));
        graficaServicio = new GraficaGoogle('chart_servicios', filtrarDatosGraficaGoogle(datosServicios, 'TipoServicio', 'Gasto'));
        graficaSucursal = new GraficaGoogle('chart_sucursal', filtrarDatosGraficaGoogle(datosSucursales, 'Sucursal', 'Gasto'));
        graficaCategoria = new GraficaGoogle('chart_categoria', filtrarDatosGraficaGoogle(datosCategoria, 'Categoria', 'Gasto'));
        graficaSubCategoria = new GraficaGoogle('chart_subCategoria', filtrarDatosGraficaGoogle(datosSubCategoria, 'SubCategoria', 'Gasto'));
        graficaConcepto = new GraficaGoogle('chart_concepto', filtrarDatosGraficaGoogle(datosConceptos, 'Concepto', 'Gasto'));
        graficaProyecto.inicilizarGrafica();
        graficaServicio.inicilizarGrafica();
        graficaSucursal.inicilizarGrafica();
        graficaCategoria.inicilizarGrafica();
        graficaSubCategoria.inicilizarGrafica();
        graficaConcepto.inicilizarGrafica();
        alertaFiltros = new Alertas('seccionFiltros');
        selectorproyecto = new SelectBasico('selectproyecto');
        selectorservicio = new SelectBasico('selectservicio');
        selectorsucursal = new SelectBasico('selectsucursal');
        selectorcategoria = new SelectBasico('selectcategoria');
        selectorsubcategoria = new SelectBasico('selectsubcategoria');
        selectorconcepto = new SelectBasico('selectconcepto');
        selectorproyecto.iniciarSelect();
        selectorservicio.iniciarSelect();
        selectorsucursal.iniciarSelect();
        selectorcategoria.iniciarSelect();
        selectorsubcategoria.iniciarSelect();
        selectorconcepto.iniciarSelect();
        selectorproyecto.cargaDatosEnSelect(filtrarDatosSelects(datosProyectos, 'IdProyecto', 'Proyecto'));
        selectorservicio.cargaDatosEnSelect(filtrarDatosSelects(datosServicios, 'TipoServicio', 'TipoServicio'));
        selectorsucursal.cargaDatosEnSelect(filtrarDatosSelects(datosSucursales, 'idSucursal', 'Sucursal'));
        selectorcategoria.cargaDatosEnSelect(filtrarDatosSelects(datosCategoria, 'Categoria', 'Categoria'));
        selectorsubcategoria.cargaDatosEnSelect(filtrarDatosSelects(datosSubCategoria, 'SubCategoria', 'SubCategoria'));
        selectorconcepto.cargaDatosEnSelect(filtrarDatosSelects(datosConceptos, 'Concepto', 'Concepto'));
    }

    function eventosObjetos() {
        listenerEventosObjetos(selectorproyecto, 'proyecto');
        listenerEventosObjetos(tablaProyecto, 'proyecto');
        listenerEventosObjetos(graficaProyecto, 'proyecto');
        listenerEventosObjetos(selectorservicio, 'servicio');
        listenerEventosObjetos(tablaServicio, 'servicio');
        listenerEventosObjetos(graficaServicio, 'servicio');
        listenerEventosObjetos(selectorsucursal, 'sucursal');
        listenerEventosObjetos(tablaSucursal, 'sucursal', );
        listenerEventosObjetos(graficaSucursal, 'sucursal');
        listenerEventosObjetos(selectorcategoria, 'categoria');
        listenerEventosObjetos(tablaCategoria, 'categoria');
        listenerEventosObjetos(graficaCategoria, 'categoria');
        listenerEventosObjetos(selectorsubcategoria, 'subcategoria');
        listenerEventosObjetos(tablaSubCategoria, 'subcategoria');
        listenerEventosObjetos(graficaSubCategoria, 'subcategoria')
        listenerEventosObjetos(selectorconcepto, 'concepto');
        listenerEventosObjetos(tablaConcepto, 'concepto');
        listenerEventosObjetos(graficaConcepto, 'concepto');
        listenerEventosObjetos(tablaDetalles, 'concepto');
    }

    function listenerEventosObjetos(objeto, filtro) {
        let datos = null;
        if (objeto instanceof TablaBasica) {
            objeto.evento(function () {
                datos = objeto.datosFila(this);
                datosFiltros[filtro] = datos[0];
                enviarInformacionFiltros('panelDashboardGapsiFilters', datosFiltros);
            });
        } else if (objeto instanceof SelectBasico) {
            objeto.evento('change', function () {
                datos = objeto.obtenerValor();
                datosFiltros[filtro] = datos;
                enviarInformacionFiltros('panelDashboardGapsiFilters', datosFiltros);
            });
        } else if (objeto instanceof GraficaGoogle) {
            switch (filtro) {
                case 'proyecto':
                    objeto.agregarListener(function (dato) {
                        for (var i = 0; i < datosProyectos.length; i++) {
                            if (dato === datosProyectos[i].Proyecto) {
                                datosFiltros[filtro] = datosProyectos[i].IdProyecto;
                            }
                        }
                        enviarInformacionFiltros('panelDashboardGapsiFilters', datosFiltros);
                    });
                    break;
                case 'sucursal':
                    objeto.agregarListener(function (dato) {
                        for (var i = 0; i < datosSucursales.length; i++) {
                            if (dato === datosSucursales[i].Sucursal) {
                                datosFiltros[filtro] = datosSucursales[i].idSucursal;
                            }
                        }
                        enviarInformacionFiltros('panelDashboardGapsiFilters', datosFiltros);
                    });
                    break
                default:
                    objeto.agregarListener(function (dato) {
                        datosFiltros[filtro] = dato;
                        enviarInformacionFiltros('panelDashboardGapsiFilters', datosFiltros);
                    });
                    break;
            }
        }
    }

    function enviarInformacionFiltros(objeto, datosFiltros) {
        peticion.enviar(objeto, 'Dashboard_Gapsi/tipoProyecto', datosFiltros, function (respuesta) {
            if (respuesta.consulta.proyectos.length !== 0) {
                incializarDatos(respuesta.consulta);
                setSecciones(respuesta.formulario);
                incializarObjetos();
                eventosObjetos();
                tablasCostosFiltros();
                $("input[name='optionsRadiosMoneda'][value='" + datosFiltros['moneda'] + "']").attr('checked', true);
                anterioresFiltros = JSON.parse(JSON.stringify(datosFiltros));
                $('html, body').animate({
                    scrollTop: $("#contentDashboardGapsiFilters").offset().top - 40
                }, 600);

            } else {
                modalUndefined();
            }
        });
    }

    function incializarDatos(datos) {
        datosProyectos = datos.proyectos;
        datosServicios = datos.servicios;
        datosSucursales = datos.sucursales;
        datosCategoria = datos.categorias;
        datosSubCategoria = datos.subcategorias;
        datosConceptos = datos.concepto;
        datosCompras = datos.gastosCompras;
    }

    function setSecciones(formulario) {
        $('#dashboardGapsiFilters').removeClass('hidden').empty().append(formulario);
        $('#contentDashboardGapsi').addClass('hidden');
        $('#filtroFechas').addClass('hidden');
        $('#page-container').addClass('page-with-two-sidebar');
        $('#sidebar-right').removeClass('hidden');
        $('[data-click=right-sidebar-toggled]').removeClass('hidden');
        $('[data-devider=right-sidebar-toggled]').removeClass('hidden');
        $('[data-click=sidebar-toggled]').addClass('pull-left');

        $.each(datosFiltros, function (key, value) {
            if (value === null) {
                peticion.mostrarElemento(key);
                if (key !== 'tipoProyecto' && key !== 'moneda') {
                    peticion.mostrarElemento('hide' + key)
                }
            } else {
                peticion.ocultarElemento(key);
                if (key !== 'tipoProyecto' && key !== 'moneda') {
                    peticion.ocultarElemento('hide' + key)
                }
            }
        });

        if (datosFiltros.fechaInicio === null) {
            $('#desde').datepicker('setDate', '2016-07-07');
        } else {
            var nuevaFecha = datosFiltros['fechaInicio'].split('T');
            $('#desde').datepicker('setDate', nuevaFecha[0]);
        }
        if (datosFiltros.fechaFinal === null) {
            $('#hasta').datepicker('setDate', fecha.getFullYear + '-' + fecha.getMonth() + 1);
        } else {
            var nuevaFecha = datosFiltros['fechaFinal'].split('T');
            $('#hasta').datepicker('setDate', nuevaFecha[0]);
        }
    }

    function filtrarDatosGraficaGoogle(datos, clave, valor) {
        let datosFiltrados = [];
        $.each(datos, function (key, value) {
            datosFiltrados.push([value[clave], value[valor]]);
        });
        return datosFiltrados;
    }

    function filtrarDatosSelects(datos, clave, valor) {
        let datosFiltrados = [];
        $.each(datos, function (key, value) {
            datosFiltrados.push({id: value[clave], text: value[valor]});
        });
        return datosFiltrados;
    }

    function tablasCostosFiltros() {
        $("#data-tipo-gastos").find("tr:gt(0)").remove();
        $.each(datosCompras, function (key, value) {
            let table = document.getElementById("data-tipo-gastos");
            let row = table.insertRow(1);
            let cell1 = row.insertCell(0);
            let cell2 = row.insertCell(1);
            cell1.innerHTML = value.TipoTrans;
            cell2.innerHTML = value.Gasto.toFixed(2);
        });

        alertaFiltros.quitarAlert();
        $('#verDetalles').addClass('hidden');
        $('#ocultarDetalles').addClass('hidden');
        $.each(datosFiltros, function (key, value) {
            if (datosFiltros[key] !== null && datosFiltros[key] !== 'MN' && datosFiltros[key] !== 'USD'
                    && key !== 'fechaInicio' && key !== 'fechaFinal') {
                switch (key) {
                    case 'tipoProyecto':
                        alertaFiltros.iniciarAlerta('msg-' + datosFiltros[key], value);
                        break;
                    case 'proyecto':
                        alertaFiltros.iniciarAlerta('msg-' + datosFiltros[key], datosProyectos[0]['Proyecto'], 'data-msg="' + datosFiltros[key] + '" data-value="' + key + '"');
                        $('#verDetalles').removeClass('hidden');
                        break;
                    case 'sucursal':
                        alertaFiltros.iniciarAlerta('msg-' + datosFiltros[key], datosSucursales[0]['Sucursal'], 'data-msg="' + datosFiltros[key] + '" data-value="' + key + '"');
                        break;
                    default:
                        alertaFiltros.iniciarAlerta('msg-' + datosFiltros[key], value, 'data-msg="' + datosFiltros[key] + '" data-value="' + key + '"');
                        break;
                }
            }
        });

        if (datosCategoria.length === 1 && datosFiltros['categoria'] === null) {
            peticion.ocultarElemento('categoria');
            peticion.ocultarElemento('hidecategoria');
            alertaFiltros.iniciarAlerta('msg-categoria', datosCategoria['0']['Categoria']);
        }
        if (datosSubCategoria.length === 1 && datosFiltros['subcategoria'] === null) {
            peticion.ocultarElemento('subcategoria');
            peticion.ocultarElemento('hidesubcategoria');
            alertaFiltros.iniciarAlerta('msg-categoria', datosSubCategoria['0']['SubCategoria']);
        }
        if (datosConceptos.length === 1 && datosFiltros['concepto'] === null) {
            peticion.ocultarElemento('concepto');
            peticion.ocultarElemento('hideconcepto');
            alertaFiltros.iniciarAlerta('msg-concepto', datosSubCategoria['0']['Concepto']);
        }

        $('[id*=msg-] .close').on('click', function () {
            let llave = $(this).attr('data-value');
            let dato = $(this).attr('data-msg');
            $.each(datosFiltros, function (key, value) {
                if (datosFiltros[key] == dato && key == llave) {
                    datosFiltros[key] = null;
                }
            });
            enviarInformacionFiltros('panelDashboardGapsiFilters', datosFiltros);
        });

        $('#verDetalles').on('click', function () {
            seccionDetalles();
        });
    }

    function seccionDetalles() {
        peticion.enviar('panelDashboardGapsiFilters', 'Dashboard_Gapsi/listaRegistros', datosFiltros, function (respuesta) {
            $('#dashboardGapsiDetalles').removeClass('hidden').empty().append(respuesta.formulario);
            $('#dashboardGapsiFilters').addClass('hidden');
            $('#verDetalles').addClass('hidden');
            $('#ocultarDetalles').removeClass('hidden');
            tablaDetalles = new TablaBasica('data-table-detalles');
            tablaDetalles.evento(function () {
                let claveDetalle = tablaDetalles.datosFila(this)[0];
                modalDetalles(claveDetalle);
            });
        });

        $('#ocultarDetalles').on('click', function () {
            $('#dashboardGapsiDetalles').addClass('hidden');
            $('#dashboardGapsiFilters').removeClass('hidden');
            $('#verDetalles').removeClass('hidden');
            $('#ocultarDetalles').addClass('hidden');
        });
    }

    function modalDetalles(clave) {
        peticion.enviar('panelDashboardGapsiFilters', 'Dashboard_Gapsi/infoRegistro', {'id':parseInt(clave)}, function (respuesta) {
            console.log(respuesta)
        });
    }

    function modalUndefined() {
        var html = '<div class="row m-t-20">\n\
        <form id="idUndefined" class="margin-bottom-0" enctype="multipart/form-data">\n\
            <div id="modal-dialogo" class="col-md-12">\n\
                <div class="col-md-3" style="text-align: right;">\n\
                    <i class="fa fa-exclamation-triangle fa-4x text-warning"></i>\n\
                </div>\n\
                <div class="col-md-9">\n\
                    <h4>La Información que solicita no es posible obtenerla<br>\n\
                    Contacte con el área correspondiente o vuelva a intentarlo</h4><br>\n\
                    <button id="btnAceptar" type="button" class="btn btn-sm btn-warning">Aceptar</button>\n\
                </div>\n\
            </div>\n\
        </form>\n\
        </div>';
        $('#btnModalConfirmar').addClass('hidden');
        $('#btnModalAbortar').addClass('hidden');
        evento.mostrarModal('Fallo la solicitud', html);
        $('#btnAceptar').on('click', function () {
            evento.cerrarModal();
            datosFiltros = JSON.parse(JSON.stringify(anterioresFiltros));
            if (datosFiltros.tipoProyecto !== null) {
                enviarInformacionFiltros('panelDashboardGapsiFilters', datosFiltros);
            } else {
                enviarFiltrosPrincipal('panelDashboardGapsiFilters', datosFiltros);
            }
        });
    }

    function formatoNumero(numero) {
        var temporalEntero, entero, decimal;
        numero += '';
        temporalEntero = numero.split('.');
        entero = temporalEntero[0];
        decimal = temporalEntero.length > 1 ? '.' + temporalEntero[1] : '';
        var rgx = /(\d+)(\d{3})/;
        while (rgx.test(entero)) {
            entero = entero.replace(rgx, '$1' + ',' + '$2');
        }
        return entero + decimal;
    }
});
