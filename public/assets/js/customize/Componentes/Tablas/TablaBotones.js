class TablaBotones extends ITabla {

    iniciarTabla() {        
        let tabla = $(`#${this.tabla}`).DataTable({
            responsive: true,
            language: super.obtenerIdioma()
        });               
        tabla.draw();
    }
};


