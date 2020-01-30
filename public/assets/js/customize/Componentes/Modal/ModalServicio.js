class ModalServicio extends Modal {

    constructor(nombreModal = '') {
        super(nombreModal);
    }

    mostrarModal() {

        let titulo = 'Iniciar Servicio';

        var contenido = `<div id="confirmacionServicioPoliza">  
                            <div class="row">
                                <div id="mensaje-modal" class="col-md-12 text-center">
                                    <h3>¿Quieres atender el servicio?</h3>
                                </div>
                            </div>                    
                         </div>   
                         <div class="row m-t-20">
                            <div class="col-md-12 text-center">
                               <button id="btnIniciarServicio" type="button" class="btn btn-sm btn-success"><i class="fa fa-check"></i> Aceptar</button>
                                <button id="btnCancelarIniciarServicio" type="button" class="btn btn-sm btn-danger"><i class="fa fa-times"></i> Cerrar</button>
                            </div>
                         </div>
                         <div id="error" class="row m-t-20"></div>   `;
        super.ocultarBotonAceptar();
        super.ocultarBotonCanelar();
        super.mostrarModal(titulo, contenido);
    }

    eventoIniciar(callback) {
        $('#btnIniciarServicio').on('click', callback);
    }

    eventoCancelar() {
        let _this = this;
        $('#btnCancelarIniciarServicio').on('click', function () {
            _this.cerrarModal();
        });
    }

}


