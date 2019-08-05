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
    let datos = {
        nombre: null,
        observaciones: null,
        estado: null
    }
    selectEstatus.iniciarSelect();

    /**Empieza sección de eventos para el catalogo de Asistencia**/
    $('#agregarMotivo').on('click', function () {
        if (evento.validarFormulario('#formAgregarMotivo')) {
            datos.nombre = $('#inputMotivo').val();
            datos.observaciones = $('#inputObservaciones').val();
            peticion.enviar('panelCatalogoAusencia', 'Catalogos_Permisos/Nuevo_Registro/Motivo', datos, function (respuesta) {
                console.log(respuesta);
//                location.reload();
            });
            limpiarCampos();
        }
    });
    $('#limpiarCampos').on('click', function () {
        limpiarCampos();
    });
    tablaMotivoAusencia.evento(function () {
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
    });
    $('#btnAceptarEdicion').on('click', function () {
        if (evento.validarFormulario('#formEditarMotivo')) {
            datos.nombre = $('#inputEditarMotivo').val();
            datos.observaciones = $('#inputEditarObservaciones').val();
            datos.estado = selectEstatus.obtenerValor();
            peticion.enviar('panelCatalogoAusencia', 'Catalogos_Permisos/Actualizar_Registro/Motivo', datos, function (respuesta) {
                console.log(respuesta);
            });
        }
    });
    /**Finaliza sección de eventos para el catalogo de Asistencia**/


    /**Empieza sección de eventos para el catalogo de Motivos de Rechazo**/
    $('#agregarRechazo').on('click', function () {
        if (evento.validarFormulario('#formAgregarRechazo')) {
            datos.nombre = $('#inputMotivoRechazo').val();
            datos.observaciones = 'Aqui van las observaciones';
            peticion.enviar('panelCatalogoAusencia', 'Catalogos_Permisos/Nuevo_Registro/Rechazo', datos, function (respuesta) {
                console.log(respuesta);
            });
            limpiarCampos();
        }
    });
    $('#editarRechazo').on('click', function () {
        console.log('editarRechazo');
        peticion.enviar('panelCatalogoAusencia', 'Catalogos_Permisos/Actualizar_Registro/Rechazo', datos, function (respuesta) {

        });
    });
    /**Finaliza sección de eventos para el catalogo de Motivos de Rechazo**/

    function limpiarCampos() {
        $('#inputMotivo').val('');
        $('#inputObservaciones').val('');
    }
});