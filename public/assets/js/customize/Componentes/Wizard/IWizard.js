class IWizard {
    constructor(wizard = '', config = {}){
        this.wizard = wizard;
        this.config = config;
        this.iniciar();
    }

    showMensaje(mensaje, index) {
        if(!$(`.wizard-step-${index + 1} fieldset .message-alert`).length){
            $(`.wizard-step-${index + 1} fieldset`).append(`<div class="col-md-12 message-alert m-t-20"><div class="alert alert-danger fade in m-b-15">
                        <strong>Error!</strong>${mensaje}<span class="close" data-dismiss="alert">&times;</span>
                      </div></div>`);
            $(`.message-alert`).hide().fadeIn("slow");
        }
        
        setTimeout(function(){
            $(`.message-alert`).fadeOut("slow",function(){
                $(`.message-alert`).remove();              
            });
        }, 3000);
    }
    
    activePanel(panel = '0'){
        $(`#${this.wizard}`).bwizard('show', panel);
    }
    
    resetPanel(){
        $(`#${this.wizard}`).bwizard({'validating':false});
        $(`#${this.wizard}`).bwizard('show',0);
    }

}

