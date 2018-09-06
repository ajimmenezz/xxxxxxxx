$(function () {
    App.init();

    $('#btnGuardar').on('click', function (e) {
        e.preventDefault();
        window.location.href = "prototipo_login";
    });
    
    $('#btnlimpiar').on('click', function (e){
        $('#formNuevoPsw')[0].reset();
    });
});


