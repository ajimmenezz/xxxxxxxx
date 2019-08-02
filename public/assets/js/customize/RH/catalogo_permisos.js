$(function () {

    peticion = new Utileria();
    modal = new Modal();

    evento = new Base();
    evento.horaServidor($('#horaServidor').val());
    evento.cerrarSesion();
    evento.mostrarAyuda('Ayuda_Proyectos');
    App.init();

    let tablaMotivoAusencia = new TablaBasica('table-catalogo-ausencia');
    let tablaMotivoRechazo = new TablaBasica('table-catalogo-rechazos');
    let selectEstatus = new SelectBasico('selectEditarEstado');
    let datosMotivo = {
        motivo: null,
        observaciones: null
    }
    selectEstatus.iniciarSelect();

    /**Empieza sección de eventos para el catalogo de Asistencia**/
    $('#agregarMotivo').on('click', function () {
        if (evento.validarFormulario('#formAgregarMotivo')) {
            datosMotivo.motivo = $('#inputMotivo').val();
            datosMotivo.observaciones = $('#inputObservaciones').val();
            peticion.enviar('panelCatalogoAusencia', 'Catalogos_Permisos/Nuevo_Registro/Motivos', datosMotivo, function (respuesta) {
                console.log(respuesta);
            });
            limpiarCampos();
        }
    });
    $('#limpiarCampos').on('click', function () {
        limpiarCampos();
    });
    $('.editarMotivo').on('click', function () {
        let estado, row = $(this).closest("tr");
        let datosFila = tablaMotivoAusencia.datosFila(row);
        modal.mostrarModalBotonTabla("editarMotivo", '#modalEditarMotivo');
        $('#inputEditarMotivo').val(datosFila[1]);
        $('#inputEditarObservaciones').val(datosFila[2]);
        if (datosFila[3] == 'Habilitado') {
            estado = 1;
        } else {
            estado = 2;
        }
        selectEstatus.definirValor(estado);
    });
    $('#btnAceptarEdicion').on('click', function () {
        if (evento.validarFormulario('#formEditarMotivo')) {
            console.log('peticion para guardar cambios')
        }
    });
    /**Finaliza sección de eventos para el catalogo de Asistencia**/


    /**Empieza sección de eventos para el catalogo de Motivos de Rechazo**/
    $('#agregarRechazo').on('click', function () {
        if (evento.validarFormulario('#formAgregarRechazo')) {
            datosMotivo.motivo = $('#inputMotivoRechazo').val();
            peticion.enviar('panelCatalogoAusencia', 'Catalogos_Permisos/Nuevo_Registro/Rechazos', datosMotivo, function (respuesta) {
                console.log(respuesta);
            });
            limpiarCampos();
        }
    });
    $('#editarRechazo').on('click', function () {
        console.log('editarRechazo');
    });
    /**Finaliza sección de eventos para el catalogo de Motivos de Rechazo**/

    function limpiarCampos() {
        $('#inputMotivo').val('');
        $('#inputObservaciones').val('');
    }
});