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
    let tablaMotivoCancelacion = new TablaBasica('table-catalogo-cancelacion');
    let selectPefiles = new SelectBasico('editarNivelCancelacion');
    selectPefiles.iniciarSelect();
    let datos = {
        id: null,
        nombre: null,
        observaciones: null,
        flag: null
    }

    /**Empieza sección de eventos para el catalogo de Asistencia**/
    $('#agregarMotivo').on('click', function () {
        if (evento.validarFormulario('#formAgregarMotivo')) {
            datos.nombre = $('#inputMotivo').val();
            datos.observaciones = $('#inputObservaciones').val();
            peticionBackend('Nuevo_Registro/Motivo', datos);
            limpiarCampos();
        }
    });

    tablaMotivoAusencia.evento(function () {
        let datosFila = tablaMotivoAusencia.datosFila(this);
        if (!jQuery.isEmptyObject(datosFila)) {
            modalEditar('Actualizar_Registro/Motivo', datosFila);
        }
    });
    /**Finaliza sección de eventos para el catalogo de Asistencia**/


    /**Empieza sección de eventos para el catalogo de Motivos de Rechazo**/
    $('#agregarRechazo').on('click', function () {
        if (evento.validarFormulario('#formAgregarRechazo')) {
            datos.nombre = $('#inputMotivoRechazo').val();
            datos.observaciones = $('#inputObservacionesRechazo').val();
            peticionBackend('Nuevo_Registro/Rechazo', datos);
            limpiarCampos();
        }
    });

    tablaMotivoRechazo.evento(function () {
        let datosFila = tablaMotivoRechazo.datosFila(this);
        if (!jQuery.isEmptyObject(datosFila)) {
            modalEditar('Actualizar_Registro/Rechazo', datosFila);
        }
    });
    /**Finaliza sección de eventos para el catalogo de Motivos de Rechazo**/

    /**Empieza sección de eventos para el catalogo de Motivos de Cancelacion**/
    $('#agregarCancelacion').on('click', function () {
        if (evento.validarFormulario('#formAgregarCancelacion')) {
            datos.nombre = $('#inputMotivoCancelacion').val();
            datos.observaciones = $('#inputObservacionesCancelacion').val();
            peticionBackend('Nuevo_Registro/Cancelacion', datos);
            limpiarCampos();
        }
    });

    tablaMotivoCancelacion.evento(function () {
        let datosFila = tablaMotivoCancelacion.datosFila(this);
        if (!jQuery.isEmptyObject(datosFila)) {
            modalEditar('Actualizar_Registro/Cancelacion', datosFila);
        }
    });
    /**Finaliza sección de eventos para el catalogo de Motivos de Cancelacion**/

    function peticionBackend(ruta, datosAEnviar) {
        peticion.enviar('panelCatalogoAusencia', 'Catalogos_Permisos/' + ruta, datosAEnviar, function (respuesta) {
            if (respuesta) {
                location.reload();
            } else {
                modal.mostrarModal("Error", "Error del servidor, intenta otra vez")
            }
        });
    }

    function modalEditar(ruta, infoTabla) {
        $('#modalEditarMotivo').modal();
        $('#editarMotivo').val(infoTabla[1]);
        $('#editarObservaciones').val(infoTabla[2]);
        if (infoTabla[3] === 'Habilitado') {
            $('#editarEstado').select2().val(1).trigger('change');
        } else {
            $('#editarEstado').select2().val(2).trigger('change');
        }
        if (typeof infoTabla[4] !== 'undefined') {
            $('#posibleCancelacion').removeClass('hidden');
            $('#documentacion').removeClass('hidden');
            if (infoTabla[4] === 'Si') {
                $('#editarEstadoCancelacion').select2().val(1).trigger('change');
            } else {
                $('#editarEstadoCancelacion').select2().val(0).trigger('change');
            }
            if (infoTabla[6] !== '') {
                $('#editarNivelCancelacion').select2().val(infoTabla[6]).trigger('change');
            } else {
                $('#editarNivelCancelacion').select2().val(null).trigger('change');
            }
            if (infoTabla[7] === 'Si') {
                $('#editarArchivoExtra').select2().val(1).trigger('change');
            } else {
                $('#editarArchivoExtra').select2().val(0).trigger('change');
            }
        } else {
            $('#posibleCancelacion').addClass('hidden');
        }
        $('#btnAceptar').on('click', function () {
            if (evento.validarFormulario('#formEditarMotivo')) {
                datos.id = infoTabla[0];
                $(".inputEditarMotivo").each(function () {
                    datos.nombre = $(this).val();
                });
                $(".inputEditarObservaciones").each(function () {
                    datos.observaciones = $(this).val();
                });
                $(".selectEditarEstado").each(function () {
                    datos.flag = $(this).val();
                });
                if (typeof infoTabla[4] !== 'undefined') {
                    $(".selectEditarEstadoCancelacion").each(function () {
                        datos.cancelacion = $(this).val();
                    });
                    $(".selectEditarNivelCancelacion").each(function () {
                        if ($(this).val() !== '') {
                            datos.nivelCancelacion = $(this).val();
                        } else {
                            datos.nivelCancelacion = 'null';
                        }
                    });
                    $(".selectArchivoExtra").each(function () {
                        datos.archivo = $(this).val();
                    });
                } else {
                    datos.cancelacion = '';
                    datos.nivelCancelacion = '';
                    datos.archivo = '';
                }
                peticionBackend(ruta, datos);
            }
        });
    }

    $('.limpiarCampos').on('click', function () {
        limpiarCampos();
    });
    function limpiarCampos() {
        $('#inputMotivo').val('');
        $('#inputObservaciones').val('');
        $('#inputMotivoRechazo').val('');
        $('#inputObservacionesRechazo').val('');
        $('#inputMotivoCancelacion').val('');
        $('#inputObservacionesCancelacion').val('');
    }
});