function Fecha() {

    $('.calendario').datepicker({
        todayHighlight: true,
        format: 'yyyy-mm-dd',
        language: 'es',
        autoclose: true,
        clearBtn: true
    });

    $('.calendario span').off('click');
}

Fecha.prototype.diferenciaFechas = function (f1, f2) {
    var aFecha1 = f1.split('-');
    var aFecha2 = f2.split('-');
    var fFecha1 = Date.UTC(aFecha1[0], aFecha1[1] - 1, aFecha1[2]);
    var fFecha2 = Date.UTC(aFecha2[0], aFecha2[1] - 1, aFecha2[2]);
    var dif = fFecha2 - fFecha1;
    var dias = Math.floor(dif / (1000 * 60 * 60 * 24));
    return dias;
};

Fecha.prototype.crearFecha = function (elemento) {
    $(elemento).datepicker({
        todayHighlight: true,
        format: 'yyyy-mm-dd',
        language: 'es',
        autoclose: true,
        clearBtn: true
    });

    $(elemento + ' span').off('click');

};

Fecha.prototype.rangoFechas = function (campoFecha1, campoFecha2) {
    $(campoFecha1).datetimepicker({
        format: 'YYYY-MM-DD',
        widgetPositioning: {
            horizontal: 'left',
            vertical: 'bottom'
        }
    });
    $(campoFecha2).datetimepicker({
        format: 'YYYY-MM-DD',
        widgetPositioning: {
            horizontal: 'left',
            vertical: 'bottom'
        },
        useCurrent: false //Important! See issue #1075
    });
    $(campoFecha1).on("dp.change", function (e) {
        $(campoFecha2).data("DateTimePicker").minDate(e.date);
    });
    $(campoFecha2).on("dp.change", function (e) {
        $(campoFecha1).data("DateTimePicker").maxDate(e.date);
    });
};

Fecha.prototype.dateRange = function (campoFecha1, campoFecha2, format) {
    $(campoFecha1).datetimepicker({
        format: format,
        widgetPositioning: {
            horizontal: 'left',
            vertical: 'bottom'
        }
    });
    $(campoFecha2).datetimepicker({
        format: format,
        widgetPositioning: {
            horizontal: 'left',
            vertical: 'bottom'
        },
        useCurrent: false //Important! See issue #1075
    });
    $(campoFecha1).on("dp.change", function (e) {
        $(campoFecha2).data("DateTimePicker").minDate(e.date);
    });
    $(campoFecha2).on("dp.change", function (e) {
        $(campoFecha1).data("DateTimePicker").maxDate(e.date);
    });
};

