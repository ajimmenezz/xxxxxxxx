<!--Empezando formulario datos principales-->
<form id="form-nueva-actividad-tarea" data-parsley-validate="true">
    <!--Empezando fila 1-->
    <div class="row">
        <div class="col-md-12">
            <label>Descripción de lo que hizo el técnico en el día.</label>
            <div class="form-group">
                <textarea id="textArea-descripcion-actividad" class="form-control" placeholder="Ingresa aqui tus descripcion..." rows="5" data-parsley-required="true"></textarea>
            </div>
        </div>
    </div>
    <!--Finalizando fila 1-->

    <!--Empezando fila 2-->
    <div class="row">
        <div class="col-md-offset-3 col-md-3 text-center" disable>
            <div class="form-group">
                <label >Fecha Proyectada</label>
                <div id="fecha-proyectada-actividad" class="input-group date" >
                    <input type="text" class="form-control" placeholder="Inicio" readonly />
                    <span class="input-group-addon" ><i class="fa fa-calendar"></i></span>
                </div>
            </div>
        </div>
        <div class="col-md-3 text-center">
            <div class="form-group">
                <label >Fecha Real</label>
                <div id="fecha-real-actividad" class="input-group date " >
                    <input type="text" class="form-control" placeholder="Final" readonly />
                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                </div>
            </div>
        </div>
    </div>
    <!--Finalizando fila 2-->
</form>                
<!--Finalizando formulario datos principales-->
