$(function () {

    let peticion = new Utileria();
    let modal = new Modal();

    let evento = new Base();
    evento.horaServidor($('#horaServidor').val());
    evento.cerrarSesion();
    evento.mostrarAyuda('Ayuda_Proyectos');
    App.init();

    let tablaSwitch = new TablaBasica('table-catalogo-switch');
    let selectMarca = new SelectBasico('marcaEquipo');
    selectMarca.iniciarSelect();

    $('#agregarSwitch').on('click', function () {
        if (evento.validarFormulario('#formAgregarSwitch')) {
            let data = {
                linea: 6,
                sublinea: 28,
                marca: $("#marcaEquipo").val(),
                nombre: $('#nombreEquipo').val(),
                parte: $('#noParteEquipo').val()
            };
            evento.enviarEvento('SeguimientoCE/Catalogo/NuevoModelo', data, '#panelCatalogoSwitch', function (respuesta) {
                modal.mostrarModal('Exito', '<h3>Se ha agregado el Switch al catálogo</h3>');
                $('#btnAceptar').addClass('hidden');
                $('#btnCerrar').on('click', function () {
                    location.reload();
                });
            });
        }
    });

    $('#limpiarCampos').on('click', function () {
        selectMarca.limpiarElemento();
        $('#nombreEquipo').val('');
        $('#noParteEquipo').val('');
    });
    
});
