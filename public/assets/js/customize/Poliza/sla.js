$(function () {
    //Objetos
    var evento = new Base();
    var websocket = new Socket();
    var tabla = new Tabla();
    var fecha = new Fecha();

    //Creando tabla proyectos sin iniciar
    tabla.generaTablaPersonal('#data-table-SLA', null, null, {details: false});

    //Evento que maneja las peticiones del socket
    websocket.socketMensaje();

    //Muestra la hora en el sistema
    evento.horaServidor($('#horaServidor').val());

    //Evento para cerra la session
    evento.cerrarSesion();

    //Evento para mostrar la ayuda del sistema
    evento.mostrarAyuda('Ayuda_Proyectos');

    //Inicializa funciones de la plantilla
    App.init();

    $('#reporteExcel').off('click');
    $('#reporteExcel').on('click', function () {
        evento.iniciarModal('#modalEdit', 'Generar Excel', htmlFormularioExcel);
        fecha.rangoFechas('#desdeSLA', '#hastaSLA');

        $('#reporteExcel').off('click');
        $('#btnAceptar').on('click', function () {
            var desde = $("#txtDesdeSLA").val();
            var hasta = $("#txtHastaSLA").val();

            if (desde !== '') {
                if (hasta !== '') {
                    var data = {desde: desde, hasta: hasta};
                    evento.enviarEvento('SLA/ReporteExcel', data, '#modalEdit', function (respuesta) {
                        if (respuesta) {
                            window.open(respuesta.ruta, '_blank');
                            evento.terminarModal("#modalEdit");
                        }
                    });
                } else {
                    evento.mostrarMensaje('#errorModal', false, 'Debe llenar el campo Hasta.', 3000);
                }
            } else {
                evento.mostrarMensaje('#errorModal', false, 'Debe llenar el campo Desde.', 3000);
            }
        });

    });

    var htmlFormularioExcel = function () {
        let html = `<div class="row">                                          
                        <div class="col-md-6 col-xs-6">
                            <div class="form-group">
                                <label>Desde *</label>
                                <div class='input-group date' id='desdeSLA'>
                                    <input type='text' id="txtDesdeSLA" class="form-control" value="" />
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                </div>
                            </div>                
                        </div>                                                        
                        <div class="col-md-6 col-xs-6">
                            <div class="form-group">
                                <label>Hasta *</label>
                                <div class='input-group date' id='hastaSLA'>
                                    <input type='text' id="txtHastaSLA" class="form-control" value="" />
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                </div>
                            </div>                
                        </div>
                    </div>`;

        return html;
    }


});