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
    selectEstatus.iniciarSelect();

    /**Empieza sección de eventos para el catalogo de Asistencia**/
    $('#agregarMotivo').on('click', function () {
        if (evento.validarFormulario('#formAgregarMotivo')) {
            console.log('agregarMotivo');
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
        console.log('agregarRechazo');
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