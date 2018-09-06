<!--Empezando formulario--> 
<form id="form-nueva-tarea" data-parsley-validate="true">

    <!--Empezando Fila 1-->
    <div class="row fila-1">
        <div class="col-md-6">
            <div class="form-group">
                <label>Nombre Tarea *</label>
                <input id="input-nombre-tarea" type="text" class="form-control" placeholder="Escribir Nombre de la tarea" data-parsley-required="true"/>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label>Área *</label>
                <select id="select-area-tarea"class="form-control" style="width: 100%" data-parsley-required="true">
                    <option value="">Seleccionar</option>
                    <?php
                    foreach ($listasSelects['Areas'] as $item) {
                        echo '<option value="' . $item['Id'] . '">' . $item['NombreConcepto'] . ' - ' . $item['Nombre'] . '</option>';
                    }
                    ?>
                </select>
            </div>
        </div>
    </div>
    <!--Finalizando Fila 1-->

    <!--Empezando Fila 2-->
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label>Lider *</label>
                <select id="select-lider-tarea" class="form-control" style="width: 100%" data-parsley-required="true">
                    <option value="">Seleccionar</option>                    
                </select>
            </div>
        </div>
        <div id="contendor-checkbox-tarea" class="col-md-6 hidden">
            <div class="form-group">
                <label>&nbsp;</label>
                <div class="checkbox">
                    <label >
                        <input id="checbox-repetir-tarea" type="checkbox" value="repetir"/>
                        Repetir tarea en los demas complejos
                    </label>
                </div>
            </div>
        </div>
    </div>
    <!--Finalizando Fila 2-->

    <!--Empezando Fila 3-->
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label>Técnicos *</label>
                <select id="select-asistente-tarea" class="form-control" style="width: 100%" multiple="multiple" data-parsley-required="true">
                    <option value="">Seleccionar</option>                    
                </select>
            </div>
        </div>        
    </div>
    <!--Finalizando Fila 3-->

    <!--Empezando Fila 5-->
    <div class="row">
        <div class="col-md-offset-3 col-md-3 text-center">
            <div class="form-group">
                <label >Fecha Inicio *</label>
                <div id="fecha-inicio-tarea" class="input-group date" data-date-format="yyyy-mm-dd">
                    <input type="text" class="form-control" placeholder="Inicio" readonly />
                    <span class="input-group-addon" ><i class="fa fa-calendar"></i></span>
                </div>
            </div>
        </div>
        <div class="col-md-3 text-center">
            <div class="form-group">
                <label >Fecha Final *</label>
                <div id="fecha-fin-tarea" class="input-group date " data-date-format="yyyy-mm-dd">
                    <input type="text" class="form-control" placeholder="Final" readonly />
                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                </div>
            </div>
        </div>
    </div>
    <!--Finalizando Fila 5-->
</form>
<!--Finalizando formulario-->

<!--Empezando fila 5 subtitulo-->
<div class="row seccion-ubicacion-capturada sin-nodos">
    <div class="col-md-7">
        <h5 class="titulo-pestaña f-w-700">Nodos </h5>
    </div>
    <div class="col-md-5 text-right">
        <h5>
            <button id="btn-mostrar-formulario-nodos-tarea" type="button" class="btn btn-success btn-xs"><i class="fa fa-eye"></i> Ver Nodos</button>
            <button id="btn-ocultar-formulario-nodos-tarea" type="button" class="btn btn-success btn-xs hidden"><i class="fa fa-eye-slash"></i> Ocultar Nodos</button>
        </h5>
    </div>
</div>
<!--Finalizando fila 5 subtitulo-->  

<!--Empezando fila 6 Separador-->
<div class="row seccion-ubicacion-capturada sin-nodos">
    <div class="col-md-12">
        <div class="underline m-b-15"></div>
    </div>
</div>
<!--Finalizando fila 6 Separador--> 

<!--Empezando seccion nodos tareas-->
<div id="seccion-nodos-tarea" class="hidden seccion-ubicacion-capturada">

    <!--Empezando fila 7 formulario -->
    <form id="form-nodos-tarea" data-parsley-validate="true">
        <div class="row form-ubicacion-nodos">
            <div class="col-md-4">
                <label>Ubicación</label>
                <div class="form-group">                          
                    <select id="select-ubicacion-nodo-tarea" class="form-control" style="width: 100%" data-parsley-required="true">
                        <option value="">Seleccionar</option>
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <label>Nodo</label>
                <div class="form-group">                          
                    <select id="select-nodo-tarea" class="form-control" style="width: 100%" data-parsley-required="true">
                        <option value="">Seleccionar</option>
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <label>&nbsp;</label>
                <div class="form-group">
                    <button id="btn-agregar-nodos-tarea" type="button" class="btn btn-success btn-sm" ><i class="fa fa-plus"></i> Agregar</button>
                    <button id="btn-limpiar-tabla" type="button" class="btn btn-danger btn-sm"><i class="fa fa-trash-o"></i> Quitar Nodos</button>
                </div>
            </div>                
        </div>
    </form>    
    <!--Finalizando fila 7 formulario -->

    <!--Empezando alerta-->
    <div class="row">
        <div id="errorAgregarNodo" class="col-md-12">
        </div>
    </div>
    <!--Finalizando alerta-->

    <!--Empezando fila 8 tabla-->
    <div class="row m-b-15">
        <div class="col-md-12">
            <table id="data-table-nodos-tarea" class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%">
                <thead>
                    <tr>
                        <th class="not-mobile-l">Ubicacion</th>
                        <th class="all">Tipo</th>
                        <th class="all">Nodo</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div> 
    </div>
    <!--Finalizando fila 8 tabla-->

</div>
<!--Finalizando seccion nodos tareas-->

<!--Empezando alerta-->
<div class="row">
    <div id="errorTareaNueva" class="col-md-12">
    </div>
</div>
<!--Finalizando alerta-->

<!--Empezando fila 9-->
<div class="row info-agregar-form-nueva-tarea">
    <div class="col-md-12">
        <div class="alert alert-info fade in ">
            <strong>Informacion : </strong>
            Los campos con <strong>*</strong> son obligatorios.
        </div>
    </div>
</div>
<!--Finalizando fila 9-->

