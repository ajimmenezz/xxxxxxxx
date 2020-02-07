class Solucion{
    
    constructor(){
        this.formulario = null;
        this.selects = {};
        this.tablas = {};
        this.inputs = {};
    }
    
    iniciarElementos(){
        this.crearSelects();
        this.crearTablas();
    }
    
    crearSelects(){
        let _this = this;
        let selects = [
            'selectOperacionInstalaciones',
            'selectModeloInstalaciones'
        ];        
        $.each(selects, function(index, value){
            _this.selects[value] = new SelectBasico(value); 
        });
        
        $.each(_this.selects, function(index, value){
            value.iniciarSelect(); 
        });
    }  
    
    crearTablas(){
        let _this = this;
        let tablas = [
            'data-table-equipos-instalaciones'
        ];   
        
        $.each(tablas, function(index, value){
            _this.tablas[value] = new TablaBasica(value); 
        });
        
        $.each(_this.tablas, function(index, value){
            value.iniciarTabla(); 
        });
    }  
    
    
}

