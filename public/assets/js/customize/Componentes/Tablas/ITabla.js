class ITabla {

    constructor(tabla = '', datos = [], scroll = false) {

        this.tabla = tabla;
        this.objetoTabla = $(`#${this.tabla}`);
        this.datos = datos;

        if (scroll) {
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

        let tabla = $(`#${this.tabla}`).DataTable();

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

        let tabla = $(`#${this.tabla}`).DataTable();

        if (datos instanceof Array) {
            tabla.row.add(datos).draw(false);
        } else {
            throw 'Error los datos que ingresas no concuerdan con las columnas de las tablas. Por lo que no se puede agregar la fila';
    }

    }

    datosFila(fila = '') {

        let filaDatos = $(`#${this.tabla}`).DataTable().row(fila).data();
        let datos = {};

        if (filaDatos !== undefined) {
            $.each(filaDatos, function (key, value) {
                datos[key] = value;
            });
        }
        return datos;
    }

    evento(callback) {
        $(`#${this.tabla} tbody`).on('click', 'tr', callback);
    }

    eliminarFila(fila) {
        let tabla = $(`#${this.tabla}`).DataTable();
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
        return $(`#${this.tabla}`).DataTable().rows().data();
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
        return $(`#${this.tabla}`).DataTable();
    }

    reordenarTabla(column, order) {
        $(`#${this.tabla}`).DataTable().order([column, order]).draw();
    }
}
