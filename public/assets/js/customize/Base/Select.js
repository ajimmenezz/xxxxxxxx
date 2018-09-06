//Constructor de la clase
function Select() {
    $('select').select2();
}

//Herencia del objeto Base
Select.prototype = new Base();
Select.prototype.constructor = Select;

//Crea un select 
Select.prototype.crearSelect = function (elemento) {
    $(elemento).select2();
};

//Permite crear un select multiple
Select.prototype.crearSelectMultiple = function (elemento, texto) {
    $(elemento).select2({
        placeholder: texto
    });
};

//Carga los datos para el select
Select.prototype.cargaDatos = function () {
    var elemento = arguments[0];
    var datos = arguments[1];
    var adicional = arguments[2];

    $(elemento).empty().append('<option value="">Seleccionar</option>');
    if (typeof adicional === 'object' && adicional !== null) {
        $(elemento).append('<option value="' + adicional.id + '">' + adicional.text + '</option>');
    }
    $(elemento).select2({
        data: datos
    });
};

//Cambia lo opcion del select
Select.prototype.cambiarOpcion = function (elemento, opcion) {
    $(elemento).select2().val(opcion).trigger('change');
};

//Obtiene el valor del elemento del select
Select.prototype.obtenerDatosSelect = function (elemento, valor) {
    var datos = $(elemento).select2('data');
    return datos[0][valor];
};

//Destruye el plugin del select
Select.prototype.destruirSelect = function (elemento) {
    $(elemento).select2('destroy');
};

//Selecciona todos los opciones del select
Select.prototype.seleccionarTodos = function (checkbox, select) {
    if ($(checkbox).is(':checked')) {
        $(select.selector + '> option').prop("selected", "selected");
        $(select).trigger("change");
    } else {
        $(select.selector + '> option').removeAttr("selected");
        $(select).trigger("change");
    }
};

/*
 * Metodo que carga las opciones dependiendo de un select anterior
 * 
 * @param {string} objeto    Al select que se le van a cargar los datos 
 * @param {array} contenido  Recibe el continido que se le va ha cargar al objeto
 * @param {string} seleccion  El valor del Id del select anterior
 * @param {string} comparacion  El nombre delparametro del contenido que se va a compara con el de seleccion
 * @param {string} adicional  Recibe un objeto con lo parametros id y text. Sirve para agregar una opcion 
 *                             adicional al select la cual no viene el contenido.
 */
Select.prototype.setOpcionesSelect = function () {
    var _this = this;
    var datos = [];
    var contador = 0;
    var objeto = arguments[0];
    var contenido = arguments[1] || [];
    var seleccion = arguments[2] || '';
    var comparacion = arguments[3] || null;
    var opcionAdicional = arguments[4] || null;
    var personalizadoDatos = arguments[5] || false;

    $.each(contenido, function (key, valor) {
        if (seleccion === valor[comparacion]) {
            datos[contador] = {id: valor.Id, text: valor.Nombre};
            contador++;
        }
    });

    if (!personalizadoDatos) {
        _this.cargaDatos(objeto, datos, opcionAdicional);
    } else {
        _this.cargaDatos(objeto, personalizadoDatos, opcionAdicional);
    }
};

//Carga los datos del select de forma remota por ajax
Select.prototype.setOpcionesSelectAjax = function () {
    var _this = this;
    var objeto = arguments[0];
    var url = arguments[1];
    var cargarselect = arguments[2];
    var callback = arguments[3] || null;
    var data = {opcion: $(objeto).val()};

    _this.enviarEvento(url[0], data, url[1], function (respuesta) {
        if (typeof respuesta === 'object') {
            _this.setOpcionesSelect(cargarselect[0], respuesta, $(objeto).val(), cargarselect[1]);
            _this.regresarDatos(callback, true);
        } else {
            _this.regresarDatos(callback, false);
        }
    });
};

//Destruye el plugin del select
Select.prototype.eliminarOptionSeleccionar = function (elemento) {
    $(elemento + ' > option').removeAttr("selected");
    $(elemento).trigger("change");
};

//Limpia la sección del select
Select.prototype.limpiarSelecccion = function (select) {
    $(select + '> option').removeAttr("selected");
    $(select).trigger("change");
};