<!--Empezando formulario-->
<form id="form-eliminar-proyecto" data-parsley-validate="true">

    <!--Empezando fila 1-->
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">                
                <p id="mensaje-eliminar"></p>
            </div>
        </div>
    </div>
    <!--Finalizando fila 1-->
        
    <!--Empezando fila 2-->
    <div class="row">    
        <div class="col-md-12">
            <div class="form-group">
                <label >Describe la causa de la cancelación.</label>
                <textarea id="textarea-eliminar-proyecto" class="form-control" placeholder="Ingresa aqui la causa" rows="5" data-parsley-required="true"></textarea>
            </div>
        </div>        
    </div>
    <!--Finalizando fila 2-->
</form>
<!--Finalizando formulario-->

<!--Empezando alerta-->
<div class="row">
    <div id="errorAgregarComplejo" class="col-md-12">
    </div>
</div>
<!--Finalizando alerta-->