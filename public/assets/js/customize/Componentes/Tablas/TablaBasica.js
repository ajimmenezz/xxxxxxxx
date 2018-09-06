class TablaBasica extends Tabla {

    iniciarTabla() {        
        let tabla = $(`#${this.tabla}`).DataTable({
            responsive: true,
            language: super.obtenerIdioma()
        });               
        tabla.draw();
    }
};


