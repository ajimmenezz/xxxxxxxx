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
    let tablaGastos = null;
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

    let selectorProyectos = null;
    let selectorServicios = null;
    let selectorSucursales = null;
    let selectorCategorias = null;
    let selectorSubCategorias = null;

    let datosProyecto = Array();
    let datosFiltros = {
            tipoProyecto: null, 
            moneda: null,
            fechaInicio : null,
            fechaFinal: null,
            proyecto : null,
            sucursal: null,
            servicio : null,
            categoria: null,
            subcategoria : null,
            concepto : null
        };

    graficaPrincipal.inicilizarGrafica();
    setDastosProyectos();
    graficaPrincipal.agregarListener(function (dato) {
        datosFiltros.tipoProyecto = dato;
        datosFiltros.moneda = 'MN';
        enviarInformacionFiltros('panelDashboardGapsi', datosFiltros);
    });
    filtroFechas();

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

    function incializarDatos(datos) {
        datosProyectos = datos.proyectos;
        datosServicios = datos.servicios;
        datosSucursales = datos.sucursales;
        datosCategoria = datos.categorias;
        datosSubCategoria = datos.subcategorias;
        datosConceptos = datos.concepto;
    }

    function filtroFechas() {
        let fecha = new Date();
        $('#desde').datetimepicker({
            format: 'YYYY/MM/DD',
            maxDate: new Date(),
            minDate: new Date('07/07/2016'),
            date: datosFiltros.fechaInicio
        });
        $('#hasta').datetimepicker({
            format: 'YYYY/MM/DD',
            useCurrent: false,
            maxDate: new Date(),
            minDate: new Date('07/07/2016'),
            date: datosFiltros.fechaFinal
        });
        $("#desde").on("dp.change", function (e) {
            $('#hasta').data("DateTimePicker").minDate(e.date);
        });
        $("#hasta").on("dp.change", function (e) {
            $('#desde').data("DateTimePicker").maxDate(e.date);
        });
        $("#btnFiltrarDashboard").on('click', function () {
            datosFiltros.fechaInicio = $("#fechaComienzo").val();
            datosFiltros.fechaFinal = $("#fechaFinal").val();
            enviarInformacionFiltros('panelDashboardGapsiFilters', datosFiltros);
        });
        $("input[name='optionsRadiosMoneda").click(function () {
            var radioValue = $("input[name='optionsRadiosMoneda']:checked").val();
            datosFiltros.moneda = radioValue;
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
//        tablaGastos = new TablaBasica('tableGastos');
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
        selectorProyectos = new SelectBasico('selectProyecto');
        selectorServicios = new SelectBasico('selectServicio');
        selectorSucursales = new SelectBasico('selectSucursal');
        selectorCategorias = new SelectBasico('selectCategoria');
        selectorSubCategorias = new SelectBasico('selectSubCategoria');
        selectorProyectos.iniciarSelect();
        selectorProyectos.cargaDatosEnSelect(filtrarDatosSelects(datosProyectos, 'IdProyecto', 'Proyecto'));
        selectorServicios.iniciarSelect();
        selectorServicios.cargaDatosEnSelect(filtrarDatosSelects(datosServicios, 'TipoServicio', 'TipoServicio'));
        selectorSucursales.iniciarSelect();
        selectorSucursales.cargaDatosEnSelect(filtrarDatosSelects(datosSucursales, 'idSucursal', 'Sucursal'));
        selectorCategorias.iniciarSelect();
        selectorCategorias.cargaDatosEnSelect(filtrarDatosSelects(datosCategoria, 'Categoria', 'Categoria'));
        selectorSubCategorias.iniciarSelect();
        selectorSubCategorias.cargaDatosEnSelect(filtrarDatosSelects(datosSubCategoria, 'SubCategoria', 'SubCategoria'));
        filtroFechas();

    }

    function eventosObjetos() {
        listenerEventosObjetos(selectorProyectos, 'proyecto');
        listenerEventosObjetos(tablaProyecto, 'proyecto');
        listenerEventosObjetos(graficaProyecto, 'proyecto');
        listenerEventosObjetos(selectorServicios, 'servicio');
        listenerEventosObjetos(tablaServicio, 'servicio');
        listenerEventosObjetos(graficaServicio, 'servicio');
        listenerEventosObjetos(selectorSucursales, 'sucursal');
        listenerEventosObjetos(tablaSucursal, 'sucursal', );
        listenerEventosObjetos(graficaSucursal, 'sucursal');
        listenerEventosObjetos(selectorCategorias, 'categoria');
        listenerEventosObjetos(tablaCategoria, 'categoria');
        listenerEventosObjetos(graficaCategoria, 'categoria');
        listenerEventosObjetos(selectorSubCategorias, 'subcategoria');
        listenerEventosObjetos(tablaSubCategoria, 'subcategoria');
        listenerEventosObjetos(graficaSubCategoria, 'subcategoria');
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
//        enviarInformacionFiltros('panelDashboardGapsiFilters', datosFiltros);
    }

    function enviarInformacionFiltros(objeto, datosFiltros) {
        peticion.enviar(objeto, 'Dashboard_Gapsi/tipoProyecto', datosFiltros, function (respuesta) {
            if (respuesta.consulta.proyectos.length !== 0) {
                setSecciones(respuesta.formulario);
                incializarDatos(respuesta.consulta);
                incializarObjetos();                
                eventosObjetos();                
                $('html, body').animate({
                    scrollTop: $("#contentDashboardGapsiFilters").offset().top - 40
                }, 600);

            } else {
                setTimeout(modalUndefined, 1000);
                console.log("error");
                console.log(datosFiltros);
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
        
        $.each(datosFiltros, function(key,value){
             if(value === null){
                 peticion.mostrarElemento(key);
             }else{
                 peticion.ocultarElemento(key);
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
            datosFiltrados.push({id:value[clave], text:value[valor]});
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
                <h5>No hay información para esta Consulta<br>\n\
                Revisa tus Filtros seleccionados</h5><br>\n\
                <button id="btnAceptar" type="button" class="btn btn-sm btn-success"><i class="fa fa-check"></i> Aceptar</button>\n\
            </div>\n\
        </form>\n\
        </div>';
        $('#btnModalConfirmar').addClass('hidden');
        $('#btnModalAbortar').addClass('hidden');
        evento.mostrarModal('Sin Datos', html);
        $('#btnAceptar').on('click', function () {
            evento.cerrarModal();
        });
    }
});
