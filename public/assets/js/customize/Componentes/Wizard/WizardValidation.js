class WizardValidation extends IWizard {

    iniciar() {
        let _this = this;
        let index = _this.config.index;
        let mensajeError = '';

        $(`#${this.wizard}`).bwizard({validating: function (e, ui) {
                if (index.indexOf(ui.index) !== -1) {                    
                    if (!$('form[name="form-wizard"]').parsley().validate('wizard-step-' + (ui.index + 1))) {
                        return false;
                    }
                    
                    if(!_this.validacionExtra(ui.index)){
                        _this.showMensaje(_this.mensajeError,ui.index);
                        return false;
                    }
                }
            }
        });
    }

    validacionExtra(index) {
        let listaElementos = null;

        if (this.config.hasOwnProperty('validate')) {
            listaElementos = this.config.validate[index];
        }

        if (listaElementos) {
            try{
              this.validacionTabla(listaElementos);                  
            }catch (exception) {
                this.mensajeError = exception;
                return false;
            }            
        }
        
        return true;
    }

    validacionTabla(elementos) {

        let tablas = elementos.filter(element => element instanceof ITabla);
        tablas.forEach(tabla => {
            let datos = tabla.datosTabla();
            if (datos.length === 0) {
                throw ' Debes ingresar al menos un dato';
            }
        });
    }
}


