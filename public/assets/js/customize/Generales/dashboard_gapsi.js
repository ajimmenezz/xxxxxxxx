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
    tablaTipoProyecto.reordenarTabla(2, 'desc');
    let graficaPrincipal = new GraficaGoogle('graphDashboard', tablaTipoProyecto.datosTabla());
    let tablaProyectos = new TablaBasica('data-table-proyectos');
    tablaProyectos.reordenarTabla(3, 'desc');
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

    let selectorproyecto = null;
    let selectorservicio = null;
    let selectorsucursal = null;
    let selectorcategoria = null;
    let selectorsubcategoria = null;
    let selectorconcepto = null;

    let datosProyecto = Array();
    let datosFiltros = {
        tipoProyecto: null,
        moneda: null,
        fechaInicio: null,
        fechaFinal: null,
        proyecto: null,
        sucursal: null,
        servicio: null,
        categoria: null,
        subcategoria: null,
        concepto: null
    };
    let anterioresFiltros;
    let radioValue = $("input[name='optionsRadiosMonedaPrincipal']:checked").val();

    graficaPrincipal.inicilizarGrafica({title: 'Moneda en '+radioValue, titleTextStyle:{fontSize:18,bold:true,italic:true}, is3D: true});
    setDastosProyectos();
    graficaPrincipal.agregarListener(function (dato) {
        datosFiltros.tipoProyecto = dato;
        datosFiltros.moneda = 'MN';
        enviarInformacionFiltros('panelDashboardGapsi', datosFiltros);
    });

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
                value.fecha
            ]);
        });

        $('html, body').animate({
            scrollTop: $("#titulo-tabla-proyectos").offset().top - 60
        }, 600);
    });

    tablaProyectos.evento(function () {
        let datosfila = tablaProyectos.datosFila(this);
        datosFiltros.tipoProyecto = datosfila[0];
        datosFiltros.moneda = 'MN';
        datosFiltros.proyecto = datosfila[1];
        enviarInformacionFiltros('panelDashboardGapsi', datosFiltros);
    });

    filtroFechas();
    filtroMoneda();
    function incializarDatos(datos) {
        datosProyectos = datos.proyectos;
        datosServicios = datos.servicios;
        datosSucursales = datos.sucursales;
        datosCategoria = datos.categorias;
        datosSubCategoria = datos.subcategorias;
        datosConceptos = datos.concepto;
        datosCompras = datos.gastosCompras;
    }

    function filtroFechas() {
        let fecha = new Date();
        $('#desde').datepicker({
            format: 'yyyy-mm-dd',
            maxDate: fecha,
            startDate: new Date('2016-07-07')
            //date: datosFiltros.fechaInicio
        });
//        $('#hasta').datetimepicker({
//            format: 'YYYY-MM-DD',
//            useCurrent: false,
//            maxDate: new Date(),
//            minDate: new Date('2016-07-07'),
//            date: datosFiltros.fechaFinal
//        });
//        $("#desde").on("click", function (e) {
//           document.getElementById('desde').style.position = 'fixed';
//        });
//        $("#hasta").on("click", function (e) {
//            console.log($("#fechaFinal").siblings().zIndex())
////            $("#fechaFinal").siblings().position({position:'absolute'});
//            $("#fechaFinal").siblings().zIndex(20000);
////           document.getElementById('hasta').style.zIndex = 2000;
////           document.getElementById('hasta').style.position = 'fixed';
//        });
//        $("#desde").on("dp.change", function (e) {
//            $('#hasta').data("DateTimePicker").minDate(e.date);
//            document.getElementById('desde').style.position = 'relative';
//        });
//        $("#hasta").on("dp.change", function (e) {
//            $('#desde').data("DateTimePicker").maxDate(e.date);
//        });
        $("#btnFiltrarDashboard").on('click', function () {
//            datosFiltros.fechaInicio = $("#fechaComienzo").val();
            console.log($("#fechaComienzo").val());
//            datosFiltros.fechaFinal = $("#fechaFinal").val();
//            enviarInformacionFiltros('panelDashboardGapsiFilters', datosFiltros);
        });
    }

    function filtroMoneda(){
        $("input[name='optionsRadiosMonedaPrincipal").click(function () {
            var radioValueFiltros = $("input[name='optionsRadiosMoneda']:checked").val();
            datosFiltros.moneda = radioValueFiltros;
            enviarInformacionFiltros('panelDashboardGapsiFilters', datosFiltros);
        });
        $("input[name='optionsRadiosMoneda").click(function () {
            var radioValueFiltros = $("input[name='optionsRadiosMoneda']:checked").val();
            datosFiltros.moneda = radioValueFiltros;
            enviarInformacionFiltros('panelDashboardGapsiFilters', datosFiltros);
        });
    }

    function incializarObjetos() {
        tablaProyecto = new TablaBasica('data-tipo-proyecto');
        tablaProyecto.reordenarTabla(2, 'desc');
        tablaServicio = new TablaBasica('data-tipo-servicio');
        tablaServicio.reordenarTabla(2, 'desc');
        tablaSucursal = new TablaBasica('data-tipo-sucursal');
        tablaSucursal.reordenarTabla(2, 'desc');
        tablaCategoria = new TablaBasica('data-tipo-categoria');
        tablaCategoria.reordenarTabla(2, 'desc');
        tablaSubCategoria = new TablaBasica('data-tipo-subCategoria');
        tablaSubCategoria.reordenarTabla(2, 'desc');
        tablaConcepto = new TablaBasica('data-tipo-concepto');
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
        selectorproyecto = new SelectBasico('selectproyecto');
        selectorservicio = new SelectBasico('selectservicio');
        selectorsucursal = new SelectBasico('selectsucursal');
        selectorcategoria = new SelectBasico('selectcategoria');
        selectorsubcategoria = new SelectBasico('selectsubcategoria');
        selectorconcepto = new SelectBasico('selectconcepto');
        selectorproyecto.iniciarSelect();
        selectorproyecto.cargaDatosEnSelect(filtrarDatosSelects(datosProyectos, 'IdProyecto', 'Proyecto'));
        selectorservicio.iniciarSelect();
        selectorservicio.cargaDatosEnSelect(filtrarDatosSelects(datosServicios, 'TipoServicio', 'TipoServicio'));
        selectorsucursal.iniciarSelect();
        selectorsucursal.cargaDatosEnSelect(filtrarDatosSelects(datosSucursales, 'idSucursal', 'Sucursal'));
        selectorcategoria.iniciarSelect();
        selectorcategoria.cargaDatosEnSelect(filtrarDatosSelects(datosCategoria, 'Categoria', 'Categoria'));
        selectorsubcategoria.iniciarSelect();
        selectorsubcategoria.cargaDatosEnSelect(filtrarDatosSelects(datosSubCategoria, 'SubCategoria', 'SubCategoria'));
        selectorconcepto.iniciarSelect();
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
                setSecciones(respuesta.formulario);
                incializarDatos(respuesta.consulta);
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

    function setDastosProyectos() {
        let temporal = tablaProyectos.datosTabla();
        $.each(temporal, function (key, value) {
            datosProyecto.push({
                id: value[1],
                nombre: value[2],
                tipo: value[0],
                gasto: value[3],
                fecha: value[4]
            });
        });
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

        $(".remover").remove();
        $.each(datosFiltros, function (key, value) {
            if (datosFiltros[key] !== null && datosFiltros[key] !== 'MN' && datosFiltros[key] !== 'USD' && key !== 'tipoProyecto') {
                switch (key) {
                    case 'proyecto':
                        $("#seccionFiltros").append('<div id="msg-filtros-' + datosFiltros[key] + '" class="alert alert-info fade in m-b-12 remover" style="color: black">\n\
                            ' + datosProyectos[0]['Proyecto'] + '\n\
                            <span class="close" data-dismiss="alert" data-filter="' + datosFiltros[key] + '" data-key="' + key + '">&times;</span>\n\
                        </div>');
                        break;
                    case 'sucursal':
                        $("#seccionFiltros").append('<div id="msg-filtros-' + datosFiltros[key] + '" class="alert alert-info fade in m-b-12 remover" style="color: black">\n\
                            ' + datosSucursales[0]['Sucursal'] + '\n\
                            <span class="close" data-dismiss="alert" data-filter="' + datosFiltros[key] + '" data-key="' + key + '">&times;</span>\n\
                        </div>');
                        break;
                    default:
                        $("#seccionFiltros").append('<div id="msg-filtros-' + datosFiltros[key] + '" class="alert alert-info fade in m-b-12 remover" style="color: black">\n\
                            ' + value + '\n\
                            <span class="close" data-dismiss="alert" data-filter="' + datosFiltros[key] + '" data-key="' + key + '">&times;</span>\n\
                        </div>');
                        break;
                }
            }
        });

        $('[id*=msg-filtros-] .close').on('click', function () {
            let llave = $(this).attr('data-key');
            let dato = $(this).attr('data-filter');
            $.each(datosFiltros, function (key, value) {
                if (datosFiltros[key] == dato && key == llave) {
                    datosFiltros[key] = null;
                }
            });
            enviarInformacionFiltros('panelDashboardGapsiFilters', datosFiltros);
        });
    }

    function filtrarDatos(datos, filtros) {
        let datosFiltrados = [];
        $.each(datos, function (key, value) {
            if (filtros.valor === value[filtros.condicion]) {
                datosFiltrados.push(value);
            }
        });
        return datosFiltrados;
    }

    function modalUndefined() {
        var html = '<div class="row m-t-20">\n\
        <form id="idUndefined" class="margin-bottom-0" enctype="multipart/form-data">\n\
            <div id="modal-dialogo" class="col-md-12 text-center">\n\
                <h4>La Información que solicita no es posible obtenerla<br>\n\
                Contacte con el area correspondiente o vualva a intentarlo</h4><br>\n\
                <button id="btnAceptar" type="button" class="btn btn-sm btn-warning"><i class="fa fa-exclamation-triangle"></i> Aceptar</button>\n\
            </div>\n\
        </form>\n\
        </div>';
        $('#btnModalConfirmar').addClass('hidden');
        $('#btnModalAbortar').addClass('hidden');
        evento.mostrarModal('Error', html);
        $('#btnAceptar').on('click', function () {
            evento.cerrarModal();
            datosFiltros = JSON.parse(JSON.stringify(anterioresFiltros));
            enviarInformacionFiltros('panelDashboardGapsiFilters', datosFiltros);
        });
    }
});
