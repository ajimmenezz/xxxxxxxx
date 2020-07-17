class ITabla {

    constructor(tabla = '', datos = [], config = {}) {
        let objeto = $(`#${this.tabla}`);
        
        if (objeto[0] === undefined) {
            objeto = $(`.${this.tabla}`);
        }

        this.tabla = tabla;
        this.objetoTabla = objeto;
        this.datos = datos;
        this.config = config;

        if (config.scroll) {
            this.iniciarTablaScroll();
        } else {
            this.iniciarTabla();
        }
        this.agregarContenidoTabla(datos);
    }

    obtenerIdioma() {
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
    }

    agregarContenidoTabla(filas) {
        let tabla = this.objetoTabla.DataTable();

        $.each(filas, function (key, value) {
            tabla.row.add(value).draw(false);
        });
    }

    limpiartabla() {
        let objeto = $(`#${this.tabla}`);

        if (objeto[0] === undefined) {
            objeto = $(`.${this.tabla}`);
        }

        let tabla = objeto.DataTable();

        tabla.clear().draw();
    }

    crearTablaDinamica(titulos = []) {
        let columnas = '';
        let tablaNueva;

        for (let titulo of titulos) {
            columnas += `<th class="all">${titulo}</th>`;
        }

        tablaNueva = `<div class="row m-t-10">
                        <div class="col-md-12">
                            <table id="${this.tabla}" class="table table-hover table-striped table-bordered no-wrap">
                                <thead>
                                    <tr>${columnas}</tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                      </div>`;
        return tablaNueva;
    }

    agregarDatosFila(datos = []) {
        let objeto = $(`#${this.tabla}`);

        if (objeto[0] === undefined) {
            objeto = $(`.${this.tabla}`);
        }

        let tabla = objeto.DataTable();

        if (datos instanceof Array) {
            tabla.row.add(datos).draw(false);
        } else {
            throw 'Error los datos que ingresas no concuerdan con las columnas de las tablas. Por lo que no se puede agregar la fila';
    }

    }

    datosFila(fila = '') {
        let objeto = $(`#${this.tabla}`);

        if (objeto[0] === undefined) {
            objeto = $(`.${this.tabla}`);
        }

        let filaDatos = objeto.DataTable().row(fila).data();
        let datos = {};

        if (filaDatos !== undefined) {
            $.each(filaDatos, function (key, value) {
                datos[key] = value;
            });
        }
        return datos;
    }

    evento(callback) {
        let objeto = $(`#${this.tabla} tbody`);

        if (objeto[0] === undefined) {
            objeto = $(`.${this.tabla} tbody`);
        }

        objeto.on('click', 'tr', callback);
    }

    eliminarFila(fila) {
        let objeto = $(`#${this.tabla}`);

        if (objeto[0] === undefined) {
            objeto = $(`.${this.tabla}`);
        }

        let tabla = objeto.DataTable();
        tabla.row(fila).remove().draw(false);
    }

    validarNumeroFilas() {

        let filas = this.datosTabla();

        if (filas.length > 0) {
            return true;
        } else {
            return false;
        }

    }

    datosTabla() {
        let objeto = $(`#${this.tabla}`);

        if (objeto[0] === undefined) {
            objeto = $(`.${this.tabla}`);
        }

        return objeto.DataTable().rows().data();
    }

    validarFilaRepetida(datos = [], elementosComparar = []) {
        let _this = this;
        let filas = _this.datosTabla();
        let filaNueva = [];
        let agregarFila = true;

        if (_this.validarNumeroFilas()) {
            $.each(filas, function (key, arreglo) {
                filaNueva.push(_this.validarColumna(arreglo, datos, elementosComparar));
            });

            $.each(filaNueva, function (key, value) {
                if (value) {
                    agregarFila = false;
                    throw 'Ya existe el elemento en la tabla tienes que eliminarlo para poder agregarlo';
                    return false;
                }

            });

            if (agregarFila) {
                _this.agregarDatosFila(datos);
            }
        } else {
            _this.agregarDatosFila(datos);
    }
    }

    validarColumna(arreglo = [], datos = [], elementosComparar = []) {

        let repetido = true;
        let columnaDiferente = [];

        $.each(arreglo, function (key, value) {
            $.each(elementosComparar, function (key2, index) {
                if (key === index) {

                    if (value !== datos[index]) {
                        columnaDiferente.push(true);
                    } else {
                        columnaDiferente.push(false);
                    }
                }
            });
        });

        $.each(columnaDiferente, function (key, value) {
            if (value) {
                repetido = false;
                return false;
            }
        });

        return repetido;
    }

    objetoDataTable() {
        let objeto = $(`#${this.tabla}`);

        if (objeto[0] === undefined) {
            objeto = $(`.${this.tabla}`);
        }

        return objeto.DataTable();
    }

    reordenarTabla(column, order) {
        let objeto = $(`#${this.tabla}`);

        if (objeto[0] === undefined) {
            objeto = $(`.${this.tabla}`);
        }

        objeto.DataTable().order([column, order]).draw();
    }
}
