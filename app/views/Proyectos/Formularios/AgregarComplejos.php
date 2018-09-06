<!--Empezando formulario-->
<form id="form-agregar-complejo-a-proyecto" data-parsley-validate="true">

    <!--Empezando fila 1-->
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">                
                <p >Indica que otros complejos se agregaran al proyecto.</p>
            </div>
        </div>
    </div>
    <!--Finalizando fila 1-->
        
    <!--Empezando fila 2-->
    <div class="row">    
        <div class="col-md-12">
            <div class="form-group">
                <label >Complejo(s)</label>
                <select id="select-agregar-complejo" class="form-control" style="width: 100%" multiple="multiple" data-parsley-required="true">                     
                    <?php
                    foreach ($listaComplejos as $item) {
                        echo '<option value="' . $item['Id'] . '">' . $item['Nombre'] . '</option>';
                    }
                    ?>
                </select>
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