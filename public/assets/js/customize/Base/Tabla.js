
//Constructor del la clase Tabla
function Tabla() {

    this.getIdioma = function () {
        return {
            "sProcessing": "Procesando...",
            "sLengthMenu": "Mostrar _MENU_ registros",
            "sZeroRecords": "No se encontraron resultados",
            "sEmptyTable": "Ningún dato disponible en esta tabla",
            "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
            "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
            "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
            "sInfoPostFix": "",
            "sSearch": "Buscar:",
            "sUrl": "",
            "sInfoThousands": ",",
            "sLoadingRecords": "Cargando...",
            "oPaginate": {
                "sFirst": "Primero",
                "sLast": "Último",
                "sNext": "Siguiente",
                "sPrevious": "Anterior"
            },
            "oAria": {
                "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
                "sSortDescending": ": Activar para ordenar la columna de manera descendente"
            }
        };
    };

}

Tabla.prototype.agregarFila = function () {
    if (arguments.length >= 1) {
        var tabla = $(arguments[0]).DataTable();
        if (arguments[1] instanceof Array) {
            tabla.row.add(arguments[1]).draw(false);
        } else {
            console.log('Error al agregar la fila en la tabla.');
        }
    } else {
        console.log('No se ingreso ningun argumento');
    }

};

Tabla.prototype.agregarFilaHtml = function () {
    if (arguments.length >= 1) {
        var tabla = $(arguments[0]).DataTable();
        tabla.row.add($(arguments[1])[0]).draw(false);
    } else {
        console.log('No se ingreso ningun argumento');
    }

};

Tabla.prototype.reordenarTabla = function (elemento, order) {
    $(elemento).DataTable().order(order).draw();
}

Tabla.prototype.filtrarColumna = function (elemento, col, valor) {
    if ($(elemento).DataTable().column(col).search() !== valor) {
        $(elemento).DataTable()
            .column(col)
            .search(valor)
            .draw();
    }
}

Tabla.prototype.buscarEnTabla = function (elemento, valor, exacto) {
    if (exacto) {
        $(elemento).DataTable()
            .search('^\\s' + valor +'\\s*$', true, false, true)
            .draw();
    } else {
        $(elemento).DataTable()
            .search(valor)
            .draw();
    }

}

//Se encarga de limpiar la tabla
Tabla.prototype.limpiarTabla = function (elemento) {
    $(elemento).DataTable().clear().draw();
};

//
/*
 * Genera una tabla donde se debe definir los datos (data, responsive, bDestroy, columns)
 * 
 * @param {string} arguments[0] obtiene el nombre de la tabla a la que va crear el plugin 
 * @param {array} arguments[1] obtiene un arreglo con los datos que se van a cargar a la tabla
 * @param {array objetos} arguments[2] obtiene un arreglo de objetos con el que las columnas se van a cargar a la tabla. esto es util
 *                                      si se desea redenrizar un icono a la tabla y tenga funcionalidad.
 * @param {boolean} arguments[3] obtiene un valor para indicar si la tabla es reponsiva con un true o false.Por default es true.
 * @param {boolean} arguments[4] obtiene un valor para indicar si el boton de columnas ocultas se muesrta. Por default se muestra.
 * @param (array) arguments[5] obtiene un array conformado de otro array donde los valores del array son dos el primero es el numero de la 
 *                             columna y el segundo pude definirse con lo valors asc y desc. 
 *                             Ejemplo de un valor :[[0,'asc']] Ejemplo de varios valores [[0,'asc'],[1,'asc']]
 * @param {boolean} arguments[6] Define si la tabla va poder generar el evento click. Por de fault es true                                     
 * @param {boolean} arguments[6] Define el DOM de la tabla, es decir, los elementos que contendrá como filtro e información. Por default es ''                                     
 */
Tabla.prototype.generaTablaPersonal = function () {
    var _this = this;
    var datos = arguments[1] || null;
    var columnas = arguments[2] || null;
    var responsive = arguments[3] || true;
    var columnasOcultas = arguments[4] || false;
    var orden = arguments[5] || [];
    var habilitar = (typeof arguments[6] !== 'undefined' && arguments[6] !== null) ? arguments[6] : true;
    var domOrder = (typeof arguments[7] !== 'undefined' && arguments[7] !== null) ? arguments[7] : 'lfrtip';
    var paging = (typeof arguments[8] !== 'undefined' && arguments[8] !== null) ? arguments[8] : true;

    if (columnasOcultas) {
        responsive = {
            details: false
        };
    }

    if (typeof datos === null && typeof columnas === null) {
        var table = $(arguments[0]).DataTable({
            dom: domOrder,
            responsive: responsive,
            language: _this.getIdioma(),
            order: orden,
            paging: paging
        });
    } else {
        var table = $(arguments[0]).DataTable({
            data: datos,
            responsive: responsive,
            bDestroy: true,
            columns: columnas,
            language: _this.getIdioma(),
            order: orden,
            dom: domOrder,
            paging: paging
        });
    }

    table.draw();
    $(arguments[0]).attr('data-editar', habilitar);
};

//

Tabla.prototype.eliminarRegistroDB = function () {
    var callback;
    var objeto;
    var url = null;
    var datos = null;

    if (arguments.length >= 1 && arguments.length < 5) {
        url = arguments[0] || '';
        datos = arguments[1] || {};
        objeto = arguments[2] || null;
        callback = arguments[3] || null;
        $.ajax({
            url: url,
            method: 'post',
            data: datos,
            dataType: 'json',
            beforeSend: function () {
                if (objeto !== null) {
                    var recargando = '<div class="alert fade in m-b-15 text-center " id="iconCargando">\n\
                                    <i class="fa fa-2x fa-refresh fa-spin"></i></div><div class="hidden-xs">\n\
                                </div>';
                    $(objeto).before(recargando);
                }
            }
        }).done(function (data) {
            $('#iconCargando').remove();
            if (callback !== null) {
                callback(data);
            }
        });
    } else if (arguments.length === 0 || arguments.length >= 5) {
        console.log('Error: en definir los argumentos');
    }

};

//Carga el nombre de las cabeceras de forma dinamica

Tabla.prototype.definirCabeceras = function (elemento, cabeceras) {
    var _this = this;
    $(elemento).DataTable({
        responsive: true,
        language: _this.getIdioma(),
        "columns": cabeceras
    });
};

/*
 * Metodo que valida si la tabla puede generar el evento click en el renglon
 * 
 * @param {string} arguments[0] recibe el nombre de la tabla el cual vilida si esta activada o no
 * @returns {Boolean}
 */
Tabla.prototype.validarClickRenglon = function () {
    if ($(arguments[0]).attr('data-editar') === 'true') {
        return true;
    } else {
        return false;
    }
};

/*
 * Metodo para eliminar una fila de la tabla
 * 
 * @param {string} arguments[0] recibe el nombre de la tabla para eliminar la fila
 * @param {objeto} arguments[1] recibe le objeto fila (tr) que sera eliminado en la tabla
 */

Tabla.prototype.eliminarFila = function () {
    var tabla = $(arguments[0]).DataTable();
    tabla.row(arguments[1]).remove().draw(false);
};

//Bloquea la tabla para que no ejecute ninguna accion al dar click sobre el renglon
Tabla.prototype.bloquearTabla = function () {
    var nombreTabla = arguments[0];
    $(nombreTabla).attr('data-editar', 'false');
};


//Obtiene la información de la tabla
Tabla.prototype.getTableData = function () {
    var filtered = (typeof arguments[1] !== 'undefined' && arguments[1] !== null) ? arguments[1] : false;
    if (filtered) {
        var datos = $(arguments[0]).DataTable().rows({ filter: 'applied' }).data();
    } else {
        var datos = $(arguments[0]).DataTable().rows().data();
    }
    return datos;
};


//Retorna la tabla como objeto de DtaTable
Tabla.prototype.getTableObject = function () {
    return $(arguments[0]).DataTable();
};