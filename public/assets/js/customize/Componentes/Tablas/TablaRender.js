class TablaRender extends ITabla {

    iniciarTabla() {
        let _this = this;
        let tabla = $(`#${this.tabla}`).DataTable({
            info: _this.config.hasOwnProperty('info') ? _this.config.info : true,
            pageLength: _this.config.hasOwnProperty('pageLength') ? _this.config.pageLength : 10,
            searching: _this.config.hasOwnProperty('searching') ? _this.config.searching : true,
            lengthChange: _this.config.hasOwnProperty('lengthChange') ? _this.config.lengthChange : true,
            responsive: true,
            language: super.obtenerIdioma(),
            columnDefs: _this.config.columnas
        });
        tabla.draw();
    }

    addListenerOnclik(selector, callback) {
        let _this = this;
        $(document).on('click', selector, function () {
            let tr = $(this).closest('tr');
            let dataTable = _this.objetoDataTable();
            let dataRow = dataTable.row(tr[0]).data();
            callback(dataRow,tr[0]);
        });
    }
}

