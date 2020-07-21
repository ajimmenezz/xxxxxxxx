class TablaBasica extends ITabla {
  iniciarTabla() {
    let _this = this;
    let tabla = $(`#${this.tabla}`).DataTable({
      responsive: true,
      language: super.obtenerIdioma(),
      order: _this.order,
    });
    tabla.draw();
  }

  iniciarTablaClass() {
    let tabla = $(`.${this.tabla}`).DataTable({
      responsive: true,
      language: super.obtenerIdioma(),
    });
    tabla.draw();
  }

  iniciarTablaScroll() {
    let tabla = $(`#${this.tabla}`).DataTable({
      responsive: true,
      language: super.obtenerIdioma(),
      paging: false,
    });
    tabla.draw();
  }
}
