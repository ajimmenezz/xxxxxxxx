class TablaColumnaOculta extends Tabla {
    
    iniciarTabla() {
        let tabla = $(`#${this.tabla}`).DataTable({
            responsive : {
                details: false
            },
            language: super.obtenerIdioma()            
        });
        
        tabla.draw();
    }
};

