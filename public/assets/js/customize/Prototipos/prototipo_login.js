$(function () {
    App.init();

    $('#resetearPws').on('click', function (e) {
        window.location.href = "prototipo_recuperarAcceso";
    });

    $('#btnIngresar').on('click', function (e) {
        window.location.href = "prototipo_nuevo";
    });
});


