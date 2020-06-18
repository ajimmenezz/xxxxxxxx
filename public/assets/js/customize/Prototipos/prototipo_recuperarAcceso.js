$(function () {
    App.init();

    $('#btnRegresar').on('click', function (e) {
        e.preventDefault();
        window.location.href = "prototipo_login";
    });

    $('#btnRecuperar').on('click', function (e) {
        e.preventDefault();
        window.location.href = "prototipo_nuevoPsw";
    });
});


