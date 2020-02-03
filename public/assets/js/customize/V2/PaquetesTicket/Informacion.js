class Informacion{
    
    constructor(){
        this.formulario = null;
        this.selects = {};
        this.inputs = {};
    }
    
    iniciarElementos(){
        this.crearSelects();
    }
    
    crearSelects(){
        let _this = this;
        let selects = [
            'selectSucursalesCorrectivo',
            'selectAreaPuntoCorrectivo'
        ];        
        $.each(selects, function(index, value){
            _this.selects[value] = new SelectBasico(value); 
        });
        
        $.each(_this.selects, function(index, value){
            value.iniciarSelect(); 
        });
    }        
    
    
}

