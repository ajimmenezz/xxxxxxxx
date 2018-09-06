<!--Empezando seccion de Seguimiento de tareas-->
<!--Empezando separador-->
<div class="row">        
    <div class="col-md-12">
        <div class="subtitulo-contenido"><h5 class="elemento-1">Proyecto: <strong id="nombreProyecto" class=""></strong></h5><h5 class="elemento-2">Ticket:</h5></div>
        <div class="underline m-b-15"></div>
    </div>  
</div>
<!--Finalizando separador-->
<!--Empezando Informacion de la tarea-->
<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label for="tarea">Tarea</label>
            <p></p>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="Estatus">Area</label>
            <p></p>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="Duracion">Estatus</label>
            <p></p>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label for="Asistentes">Responsable</label>
            <p></p>            
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="Asistentes">Lider </label>
            <p></p>            
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="Asistentes">Asistentes</label>
            <p></p>            
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label for="Asistentes">Inicio</label>
            <p></p>            
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="Asistentes">Termino</label>
            <p></p>            
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="Asistentes">Duración</label>
            <p></p>            
        </div>
    </div>
</div>
<!--Finalizando Informacion de la tarea-->

<!--Empezando separador-->
<div class="row">        
    <div class="col-md-12">
        <div class="subtitulo-contenido"><h5 class="elemento-1">Días de Actividad</h5><h5 class="elemento-2">Avance: <strong id="avanceDiasActividad"></strong></h5></div>
        <div class="underline m-b-15"></div>
    </div>  
</div>
<!--Finalizando separador-->

<!--Empezando tabla de dias de actividad-->
<div class="row">
    <div class="col-md-12">        
        <table id="data-table-tarea-diasActividad" class="table table-hover table-striped table-bordered no-wrap " style="cursor:pointer" width="100%">
            <thead>
                <tr>
                    <th class="all">Id</th>
                    <th class="all">Fecha</th>
                    <th class="all">Estatus</th>
                    <th class="all">Descripcion</th>                                 
                </tr>
            </thead>
            <tbody>
                <?php
//                if (!empty($informacion['SolicitudMaterial'])) {
//                    foreach ($informacion['SolicitudMaterial'] as $value) {
//                        if (gettype($value) !== 'string') {
//                            echo '<tr>';
//                            echo '<td>' . $value['material'] . '</td>';
//                            echo '<td>' . $value['numParte'] . '</td>';
//                            echo '<td>' . $value['cantidad'] . '</td>';
//                            echo '<td>0</td>';
//                            echo '<td>0 </td>';
//                            echo '</tr>';
//                        }
//                    }
//                }
                ?>
            </tbody>
        </table>
    </div>
</div>
<!--Finalizando tabla de dias de actividad-->

<!--Empezando Botones -->
<div class="row m-t-20">                              
    <div class="col-md-12 text-center">            
        <button id="btnAgregarDia" type="button" class="btn btn-sm btn-success"><i class="fa fa-plus"></i> Agregar Día de actividad</button>
        <button id="btnConcluirTarea" type="button" class="btn btn-sm btn-primary">Concluir Tarea</button>
        <button id="btnRegresarProyecto" type="button" class="btn btn-sm btn-danger">Regresar Proyecto</button>
    </div>        
</div>
<!--Finalizando Botones-->

<!--Finalizando seccion de Seguimiento de tareas-->
