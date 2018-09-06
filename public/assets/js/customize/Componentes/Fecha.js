class Fecha {

    constructor(nombreFecha) {
        this.fecha = nombreFecha;
        this.objetoFecha = $(`#${this.fecha}`);
        this.objetoInput = $(`#${this.fecha} input`);
        this.iniciarFecha();
    }

    iniciarFecha() {
        this.objetoFecha.datepicker({
            todayHighlight: true,
            disableTouchKeyboard: true,
            format: 'yyyy-mm-dd',
            language: 'es',
            autoclose: true,
            clearBtn: true
        });
    }

    limpiarElemento() {
        this.objetoInput.val(null);
    }

    diferenciaFechas(fecha1, fecha2) {
        var aFecha1 = fecha1.split('-');
        var aFecha2 = fecha2.split('-');
        var fFecha1 = Date.UTC(aFecha1[0], aFecha1[1] - 1, aFecha1[2]);
        var fFecha2 = Date.UTC(aFecha2[0], aFecha2[1] - 1, aFecha2[2]);
        var dif = fFecha2 - fFecha1;
        var dias = Math.floor(dif / (1000 * 60 * 60 * 24));
        return dias;
    }

    obtenerValor() {
        return this.objetoInput.val();
    }

    bloquearElemento() {
        this.objetoInput.attr('disabled', 'disabled');
        this.objetoFecha.datepicker('remove');
    }

    habilitarElemento() {
        this.objetoInput.removeAttr('disabled');
        this.iniciarFecha();
    }

    definirValor(valor = '') {
        this.objetoInput.val(valor);
    }

    iniciarPlugin() {
        this.objetoFecha = $(`#${this.fecha}`);
        this.objetoInput = $(`#${this.fecha} input`);
        this.iniciarFecha();
    }
    
    evento(evento = '', callback) {
        this.objetoFecha.datepicker().on(evento, callback);
    }
    
    actualizarFecha(fecha = ''){
        this.objetoFecha.datepicker('update',fecha);
    }

}



