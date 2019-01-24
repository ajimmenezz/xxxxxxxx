$(function () {

    var evento = new Base();
    var websocket = new Socket();
    var tabla = new Tabla();
    var select = new Select();
    var servicios = new Servicio();
    var nota = new Nota();
    var dataCategoria;
    var file = new Upload();

    //Evento que maneja las peticiones del socket
    websocket.socketMensaje();

    //Muestra la hora en el sistema
    evento.horaServidor($('#horaServidor').val());

    //Evento para cerra la session
    evento.cerrarSesion();

    //Creando tabla de areas
    tabla.generaTablaPersonal('#data-table-poliza', null, null, true, true, [[0, 'desc']]);

    //Evento para mostrar la ayuda del sistema
    evento.mostrarAyuda('Ayuda_Proyectos');

    //Inicializa funciones de la plantilla
    App.init();

    tabla.generaTablaPersonal('#lista-equipos-enviados-solicitados', null, null, true, true, [[0, 'desc']]);
    var panel = '#panelTablaEquiposEnviados';

    var idPerfil = $('#IdPerfil').val();
    if (idPerfil === `57`) {
        $('#botonNuevoValidacion').removeClass('hidden');
    }

    var incioEtiquetas = function () {
        select.crearSelect('#listaTicket');
        select.crearSelect('#listaServicio');
        select.crearSelect('#listaTipoPersonal');
        select.crearSelect('#listaNombrePersonal');
        select.crearSelect('#listaSolicitarEquipo');
        select.crearSelect('#listaSolicitarRefaccion');
        select.crearSelect('#listPaqueteria');
        select.crearSelect('.listUsuarioRecibe');
        select.crearSelect('#listSucursal');
        select.crearSelect('#listRefaccionUtil');
        select.crearSelect('#listDondeRecibe');


        //obtener valor fecha
        $("#fechaEnvio").val();
        $("#fechaRecepcionAlmacen").val();
        $("#fechaRecepcionLab").val();
        $("#fechaRecepcionLogistica").val();
        $("#fechaRecepcion").val();
        $("#fechaRecepcionTecnico").val();

        //radio inputs valor
        $('input:radio[name=optionsRadios]:checked').val();

        //tablas
        tabla.generaTablaPersonal('#listaRefaccionUtilizada', null, null, true, true, [[0, 'desc']]);

        //Iniciar input archivos
        file.crearUpload('#archivosProblemaGuia', 'Seguimiento/subirProblema');
        file.crearUpload('#evidenciaEnvio', 'Seguimiento/GuardarEnvioAlmacen');
        file.crearUpload('#evidenciaRecepcionAlmacen', 'Seguimiento/subirEvidenciaRecepcion');
        file.crearUpload('#evidenciaRecepcionLab', 'Seguimiento/subirEvidenciaRecepcion');
        file.crearUpload('#archivosLabHistorial', 'Seguimiento/subirAdjuntosLabHistorial');
        file.crearUpload('#evidenciaRecepcionLogistica', 'Seguimiento/subirAdjuntosLabHistorial');
        file.crearUpload('#evidenciaEntrega', 'Seguimiento/subirAdjuntosLabHistorial');
        file.crearUpload('#evidenciaRecepcionTecnico', 'Seguimiento/subirAdjuntosLabHistorial');
        file.crearUpload('#evidenciaRecepcionlog', 'Seguimiento/subirAdjuntosLabHistorial');
        
        file.crearUpload('#adjuntosProblemaAlm', 'Seguimiento/subirAdjuntosLabHistorial');
        file.crearUpload('#adjuntosProblemaLab', 'Seguimiento/subirAdjuntosLabHistorial');
        file.crearUpload('#adjuntosProblemaLog', 'Seguimiento/subirAdjuntosLabHistorial');
        file.crearUpload('#evidenciaEntregaLog', 'Seguimiento/subirAdjuntosLabHistorial');
        file.crearUpload('#evidenciaEnvioGuia', 'Seguimiento/subirAdjuntosLabHistorial');

    };
    
    $('#agregarEquipo').off('click');
    $('#agregarEquipo').on('click', function () {
        var IdServicio = "";
        formulario(IdServicio);
    });

    $('#lista-equipos-enviados-solicitados tbody').on('click', 'tr', function () {
        var IdServicio = "";
        var IdRefaccion = "";
        var datos = $('#lista-equipos-enviados-solicitados').DataTable().row(this).data();
        if (datos !== undefined) {
            IdServicio = datos[1];
            IdRefaccion = datos[8];
            formulario(IdServicio,IdRefaccion);
        }
    });

    var formulario = function () {
        var idServicio = arguments[0];
        var IdRefaccion = arguments[1];
        var datos = {"idServicio" : idServicio, 'IdRefaccion' : IdRefaccion};
        evento.enviarEvento('Seguimiento/VistaPorPerfil', datos, panel, function (respuesta) {
            console.log(respuesta);

                $('#panelTablaEquiposEnviados').addClass('hidden');
//                $('#seccionFormulariosRecepcionTecnico').removeClass('hidden').empty().append(respuesta.formulario.formularioRecepcionTecnico);
//                $('#seccionFormulariosEnvSegLogistica').removeClass('hidden').empty().append(respuesta.formulario.formularioEnvioSeguimientoLogistica);
                $('#seccionFormulariosRecepcionLogistica').removeClass('hidden').empty().append(respuesta.formularioRecepcionLog.formularioRecepcionLogistica);
                $('#seccionFormulariosRevisionHistorial').removeClass('hidden').empty().append(respuesta.formularioHistorialRefaccion.formularioRevisionHistorial);
                $('#seccionFormulariosRecepcionLaboratorio').removeClass('hidden').empty().append(respuesta.formularioRecepcionLab.formularioRecepcionLaboratorio);
                $('#seccionFormulariosRecepcionAlmacen').removeClass('hidden').empty().append(respuesta.formularioRecepcionAlmacen.formularioRecepcionAlmacen);
//                $('#seccionFormulariosAsignacionGuiaLogistica').removeClass('hidden').empty().append(respuesta.formulario.formularioAsignacionGuiaLogistica);
//                $('#seccionFormulariosAsignacionGuia').removeClass('hidden').empty().append(respuesta.formulario.formularioAsignacionGuia);
                $('#seccionPanelEspera').removeClass('hidden').empty().append(respuesta.PanelEspera.panelEspera);
                $('#seccionFormulariosGuia').removeClass('hidden').empty().append(respuesta.formularioEnvioAlmacen.formularioGuia);
                $('#seccionFormulariosValidacion').removeClass('hidden').empty().append(respuesta.formularioValidacion.formularioValidacion);
                incioEtiquetas();

                $('#btnRegresarTabla').off('click');
                $('#btnRegresarTabla').on('click', function () {
                    $('#panelTablaEquiposEnviados').removeClass('hidden');
                    $('#seccionFormulariosValidacion').addClass('hidden');
                });
                
        });
    };

});