class SelectBasico extends Select {
    
    iniciarSelect() {        
        this.objetoSelect.select2();
    }
    
    obtenerTexto(){        
        return $(`#${this.select} option:selected`).text();
    }
}
