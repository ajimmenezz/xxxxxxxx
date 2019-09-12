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
//    let selectEstatus = new SelectBasico('selectEditarEstado');
    let datos = {
        id: null,
        nombre: null,
        observaciones: null,
        flag: null
    }
//    selectEstatus.iniciarSelect();

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
        modalEditar('Actualizar_Registro/Motivo', datosFila);
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
        let datosFila = tablaMotivoAusencia.datosFila(this);
        modalEditar('Actualizar_Registro/Rechazo', datosFila);
    });
    /**Finaliza sección de eventos para el catalogo de Motivos de Rechazo**/

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
        let contenidoModal = $('#modalEditarMotivo').html();
        modal.mostrarModalBasico("Editar", contenidoModal);
        $('.inputEditarMotivo').val(infoTabla[1]);
        $('.inputEditarObservaciones').val(infoTabla[2]);
        if (infoTabla[3] === 'Habilitado') {
            $('.selectEditarEstado').select2().val(1).trigger('change');
        } else {
            $('.selectEditarEstado').select2().val(2).trigger('change');
        }
        $('#btnAceptar').on('click', function () {
//            if (evento.validarFormulario('.formEditarMotivo')) {
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
                peticionBackend(ruta, datos);
//            }
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
    }
});