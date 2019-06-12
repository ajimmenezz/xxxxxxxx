$(function () {

    peticion = new Utileria();

    evento = new Base();
    evento.horaServidor($('#horaServidor').val());
    evento.cerrarSesion();
    evento.mostrarAyuda('Ayuda_Proyectos');

    //Inicializa funciones de la plantilla
    App.init();

    //Globales
    let tablaTipoProyecto = new TablaBasica('data-table-tipo-proyectos');
    tablaTipoProyecto.reordenarTabla(2,'desc');
    let graficaPrincipal = new GraficaGoogle('graphDashboard', tablaTipoProyecto.datosTabla());
    let tablaProyectos = new TablaBasica('data-table-proyectos');
    tablaProyectos.reordenarTabla(3,'desc');
    let tablaProyecto = null;
    let tablaServicio = null;
    let tablaSucursal = null;
    let tablaCategoria = null;
    let tablaSubCategoria = null;
    let tablaConcepto = null;
    //let tablaGastos = null;
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
    let datosFiltros = Array();

    graficaPrincipal.inicilizarGrafica();
    setDastosProyectos();
    graficaPrincipal.agregarListener(function (dato) {
        datosFiltros = {tipoProyecto: dato, moneda: 'MN'};
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
        datosFiltros = {tipoProyecto: datosfila[0], moneda: 'MN', proyecto: datosfila[1]};
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
        $("input[name='optionsRadiosMoneda").click(function(){
            var radioValue = $("input[name='optionsRadiosMoneda']:checked").val();
            datosFiltros.moneda = radioValue;
            enviarInformacionFiltros('panelDashboardGapsiFilters', datosFiltros);
        });
    }

    function incializarObjetos() {
        tablaProyecto = new TablaBasica('data-tipo-proyecto');
        tablaProyecto.reordenarTabla(2,'desc');
        tablaServicio = new TablaBasica('data-tipo-servicio');
        tablaServicio.reordenarTabla(2,'desc');
        tablaSucursal = new TablaBasica('data-tipo-sucursal');
        tablaSucursal.reordenarTabla(2,'desc');
        tablaCategoria = new TablaBasica('data-tipo-categoria');
        tablaCategoria.reordenarTabla(2,'desc');
        tablaSubCategoria = new TablaBasica('data-tipo-subCategoria');
        tablaSubCategoria.reordenarTabla(2,'desc');
        tablaConcepto = new TablaBasica('data-tipo-concepto');
        tablaConcepto.reordenarTabla(2,'desc');
        //tablaGastos = new TablaBasica('tableGastos');
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
        selectorServicios.iniciarSelect();
        selectorSucursales.iniciarSelect();
        selectorCategorias.iniciarSelect();
        selectorSubCategorias.iniciarSelect();
        filtroFechas();
    }

    function eventosObjetos() {
        selectorProyectos.evento('change', function () {
            listenerEventosObjetos(selectorProyectos, 'proyecto');
        });
        tablaProyecto.evento(function () {
            listenerEventosObjetos(tablaProyecto, 'proyecto', this);
        });
        graficaProyecto.agregarListener(function (dato) {
            listenerEventosObjetos(graficaProyecto, 'proyecto', this, dato);
        });
        
        selectorServicios.evento('change', function () {
            listenerEventosObjetos(selectorServicios, 'servicio');
        });
        tablaServicio.evento(function () {
            listenerEventosObjetos(tablaServicio, 'servicio', this);
        });
        graficaServicio.agregarListener(function (dato) {
            listenerEventosObjetos(graficaServicio, 'servicio', this, dato);
        });
        
        selectorSucursales.evento('change', function () {
            listenerEventosObjetos(selectorSucursales, 'sucursal');
        });
        tablaSucursal.evento(function () {
            listenerEventosObjetos(tablaSucursal, 'sucursal', this);
        });
        graficaSucursal.agregarListener(function (dato) {
            listenerEventosObjetos(graficaSucursal, 'sucursal', this, dato);
        });
        
        selectorCategorias.evento('change', function () {
            listenerEventosObjetos(selectorCategorias, 'categoria');
        });
        tablaCategoria.evento(function () {
            listenerEventosObjetos(tablaCategoria, 'categoria', this);
        });
        graficaCategoria.agregarListener(function (dato) {
            listenerEventosObjetos(graficaCategoria, 'categoria', this, dato);
        });
        
        selectorSubCategorias.evento('change', function () {
            listenerEventosObjetos(selectorSubCategorias, 'subcategoria');
        });
        tablaSubCategoria.evento(function () {
            listenerEventosObjetos(tablaSubCategoria, 'subcategoria', this);
        });
        graficaSubCategoria.agregarListener(function (dato) {
            listenerEventosObjetos(selectorSubCategorias, 'subcategoria', this, dato);
        });
        
        tablaConcepto.evento(function(){
            listenerEventosObjetos(tablaConcepto, 'concepto', this )
        });
        graficaConcepto.agregarListener(function (dato) {
            listenerEventosObjetos(selectorSubCategorias, 'concepto', this, dato);
        });
    }
    
    function listenerEventosObjetos(objeto, filtro, _this = null, dato = null) {
        let datos = null;
        if (objeto instanceof TablaBasica) {
            datos = objeto.datosFila(_this)[0];
            datosFiltros[filtro] = datos;

        } else if (objeto instanceof SelectBasico) {
                datos = objeto.obtenerValor();
                datosFiltros[filtro] = datos;

        } else if (objeto instanceof GraficaGoogle) {
            switch (filtro) {
                case 'proyecto':
                    for (var i = 0; i < datosProyectos.length; i++) {
                        if (dato === datosProyectos[i].Proyecto) {
                            datosFiltros[filtro] = datosProyectos[i].IdProyecto;
                        }
                    }
                    break;
                case 'sucursal':
                    for (var i = 0; i < datosSucursales.length; i++) {
                        if (dato === datosSucursales[i].Sucursal) {
                            datosFiltros[filtro] = datosSucursales[i].idSucursal;
                        }
                    }
                    break
                default:
                    datosFiltros[filtro] = dato;
                    break;
            }
        }
        enviarInformacionFiltros('panelDashboardGapsiFilters', datosFiltros);
    }

    function enviarInformacionFiltros(objeto, datosFiltros) {
        peticion.enviar(objeto, 'Dashboard_Gapsi/tipoProyecto', datosFiltros, function (respuesta) {
            if (respuesta.consulta.proyectos.length !== 0) {
                $('#dashboardGapsiFilters').removeClass('hidden').empty().append(respuesta.formulario);
                $('#contentDashboardGapsi').addClass('hidden');
                $('#filtroFechas').addClass('hidden');
                incializarDatos(respuesta.consulta);
                incializarObjetos();
                eventosObjetos();
                $('html, body').animate({
                    scrollTop: $("#contentDashboardGapsiFilters").offset().top - 40
                }, 600);
            } else {
                setTimeout(modalUndefined, 1000);
                console.log("error")
                console.log(datosFiltros)
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