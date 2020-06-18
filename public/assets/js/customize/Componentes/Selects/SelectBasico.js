class SelectBasico extends ISelect {
    
    iniciarSelect() {        
        this.objetoSelect.select2();
    }
    
    obtenerTexto(){        
        return $(`#${this.select} option:selected`).text();
    }
}
