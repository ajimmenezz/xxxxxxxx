<?php // var_dump($informacion);                      ?>
<form id="formularioEditarProyecto" class="margin-bottom-0"  data-parsley-validate="true" >
    <!--Empezando campos Tipo Proyecto, Nombre Proyecto, Ticket-->
    <div class="row ">
        <div class="col-md-4">
            <div class="form-group">
                <label for="nombreProyecto">Tipo Proyecto</label>
                <select id="selectTipoProyecto" class="form-control" style="width: 100%" data-parsley-required="true" disabled>
                    <option value="">Seleccionar</option>
                    <?php
                    foreach ($TiposProyectos as $item) {
                        echo '<option value="' . $item['Id'] . '">' . $item['Nombre'] . '</option>';
                    }
                    ?>
                </select>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="nombreProyecto">Nombre del Proyecto</label>
                <input id="inputNomProyecto" type="text" class="form-control nuevoProyecto" placeholder="Nombre del proyecto" data-parsley-required="true" value="<?php echo $informacion['datosProyecto'][0]['Nombre']; ?>" disabled/>
            </div>
        </div>                        
        <div class="col-md-4">
            <div class="form-group">
                <label for="ticketProyecto">Ticket</label>
                <input id="inputTicketProyecto" type="text" data-id="" class="form-control" placeholder="Numero de ticket" value="<?php echo $informacion['datosProyecto'][0]['Ticket']; ?>" disabled/>
            </div>
        </div>                        
    </div>
    <!--Finalizando campos Tipo Proyecto, Nombre Proyecto, Ticket-->

    <!--Empezando campos Complejo y Lideres-->
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="selectComplejo">Complejo</label>
                <select id="selectComplejo" class="form-control" style="width: 100%" multiple="multiple" data-parsley-required="true" disabled>
                    <?php
                    foreach ($Sucursales as $item) {
                        echo '<option value="' . $item['Id'] . '">' . $item['Nombre'] . '</option>';
                    }
                    ?>
                </select>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="lideresProyecto">Líderes</label>
                <select id="selectLideres" class="form-control nuevoProyecto" style="width: 100%" multiple="multiple" data-parsley-required="true" disabled>
                    <?php
                    foreach ($Lideres as $item) {
                        echo '<option value="' . $item['Id'] . '">' . $item['Nombre'] . '</option>';
                    }
                    ?>
                </select>                                            
            </div>
        </div>
    </div>
    <!--Finalizando campos Complejo y Lideres-->

    <!--Empezando campos observaciones -->
    <div class="row">                            
        <div class="col-md-12">
            <div class="form-group">
                <label for="observacionesProyecto">Observaciones</label>                                            
                <textarea id="textareaObservaciones" class="form-control nuevoProyecto" placeholder="Ingresa tus observaciones aqui ...." rows="3" disabled><?php echo $informacion['datosProyecto'][0]['Observaciones']; ?></textarea>
            </div>
        </div>   
    </div>
    <!--Finalizando campos observaciones -->

    <!--Empezando campos fecha inicio y fecha fin -->
    <div class="row">
        <div class="col-md-offset-3 col-md-3 text-center">
            <div class="form-group">
                <label for="control-label"> Fecha inicio </label>
                <div id="fecha-inicial" class="input-group date calendario"  >
                    <input type="text" class="form-control nuevoProyecto" placeholder="Fecha Inicio" readonly disabled/>
                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                </div>
            </div>
        </div> 
        <div class="col-md-3 text-center ">
            <div class="form-group">
                <label for="control-label"> Fecha Termino </label>
                <div id="fecha-termino" class="input-group date calendario" >
                    <input type="text" class="form-control nuevoProyecto" placeholder="Fecha Termino" readonly disabled/>
                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                </div>
            </div>
        </div>
    </div>
    <!--Finalizando campos fecha inicio y fecha fin-->

    <!--Empezando mensaje de eror de fechas-->
    <div class="row">
        <div class="col-md-offset-3 col-md-6">
            <div class="errorDiferenciaFecha"></div>
        </div>
    </div>
    <!--Finalizando mensaje de eror de fechas-->

    <!--Empezando Botones -->
    <div class="row m-t-20">                              
        <div id="btnSeccionNuevoProyecto" class="col-md-offset-4 col-md-4 text-center">
            <button type="button" class="btn btn-sm btn-success m-r-5 nuevoProyecto" id="btnActualizarProyecto">Actualizar</button>
            <button type="button" class="btn btn-sm btn-danger nuevoProyecto" id="btnRegresarProyectos">Regresar</button>
        </div>
        <div class="col-md-12 m-t-15"></div>
        <div id="btnSeccionActualizarProyecto" class="col-md-offset-2 col-md-8 text-center hidden">                          
            <button type="button" class="btn btn-sm btn-primary m-r-5" id="btnGuardarActualizar">Guardar</button>
            <button type="button" class="btn btn-sm btn-danger m-r-5" id="btnCancelarActualizar">Cancelar</button>                                
        </div>       
    </div>
    <!--Finalizando Botones-->
</form>

<!--Empezando separador-->
<div class="row">        
    <div class="col-md-12">
        <div class="underline m-b-15 m-t-15"></div>
    </div>  
</div>
<!--Finalizando separador-->

<!--Empezando informacion del proyecto-->
<div class="row">
    <div class="col-md-12">
        <ul class="nav nav-pills">
            <li class="active"><a href="#nav-pills-tab-1" data-toggle="tab">Alcance</a></li>
            <li><a href="#nav-pills-tab-2" data-toggle="tab">Material</a></li>
            <li><a href="#nav-pills-tab-3" data-toggle="tab">Personal</a></li>
            <li><a href="#nav-pills-tab-4" data-toggle="tab">Tareas</a></li>
        </ul>
        <div class="tab-content">
            <!--Empezando seccion de alacance-->
            <div class="tab-pane fade active in" id="nav-pills-tab-1">
                <div class="row">
                    <div class="col-md-12">
                        <h3 class="m-t-10">Alcance del Proyecto</h3>
                        <table id="data-table-proyectos-alcance" class="table table-hover table-striped table-bordered no-wrap " style="cursor:pointer" width="100%">
                            <thead>
                                <tr>                            
                                    <th class="all">Accesorio</th>
                                    <th class="all">Cantidad</th>                            
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($informacion['totalAlcance'] as $value) {
                                    echo '<tr>';
                                    echo '<td>' . $value['Accesorio'] . '</td>';
                                    echo '<td>' . $value['Total'] . '</td>';
                                    echo '</tr>';
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!--Finalizando seccion de alacance-->
            <!--Empezando seccion de material-->
            <div class="tab-pane fade" id="nav-pills-tab-2">
                <div class="row">
                    <div class="col-md-12">
                        <h3 class="m-t-10">Material del Proyecto</h3>
                        <table id="data-table-proyectos-material" class="table table-hover table-striped table-bordered no-wrap " style="cursor:pointer" width="100%">
                            <thead>
                                <tr>
                                    <th class="all">Material</th>
                                    <th class="all">Numero de parte</th>
                                    <th class="all">Solicitado</th>
                                    <th class="all">Disponible</th>             
                                    <th class="all">Utilizado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (!empty($informacion['SolicitudMaterial'])) {
                                    foreach ($informacion['SolicitudMaterial'] as $value) {
                                        if (gettype($value) !== 'string') {
                                            echo '<tr>';
                                            echo '<td>' . $value['material'] . '</td>';
                                            echo '<td>' . $value['numParte'] . '</td>';
                                            echo '<td>' . $value['cantidad'] . '</td>';
                                            echo '<td>0</td>';
                                            echo '<td>0 </td>';
                                            echo '</tr>';
                                        }
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row m-t-20">
                    <div class="col-md-12 text-right">                       
                        <button type="button" class="btn btn-sm btn-primary ">Ver solicitudes de material</button>
                    </div>
                </div>
            </div>
            <!--Finalizando seccion de material-->
            <!--Empezando seccion de personal-->
            <div class="tab-pane fade" id="nav-pills-tab-3">
                <div class="row">
                    <div class="col-md-12">
                        <h3 class="m-t-10 m-b-20">Personal asignado al proyecto</h3>
                        <table id="data-table-proyectos-personal" class="table table-hover table-striped table-bordered no-wrap " style="cursor:pointer" width="100%">
                            <thead>
                                <tr>
                                    <th class="none">Id</th>
                                    <th class="all">Nombre</th>
                                    <th class="all">NSS</th>
                                    <th class="all">Perfil</th>
                                    <th class="all">Estatus</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($LideresProyecto as $value) {
                                    echo '<tr>';
                                    echo '<td>' . $value['Id'] . '</td>';
                                    echo '<td>' . $value['Nombre'] . '</td>';
                                    echo '<td>' . $value['NSS'] . '</td>';
                                    echo '<td>' . $value['Perfil'] . '</td>';
                                    if ($value['Flag'] === '1') {
                                        echo '<td>Activo</td>';
                                    } else {
                                        echo '<td>Baja</td>';
                                    }

                                    echo '</tr>';
                                }
                                if (!empty($AsistentesProyecto)) {
                                    foreach ($AsistentesProyecto as $value) {
                                        echo '<tr>';
                                        echo '<td>' . $value['Id'] . '</td>';
                                        echo '<td>' . $value['Nombre'] . '</td>';
                                        echo '<td>' . $value['NSS'] . '</td>';
                                        echo '<td>' . $value['Perfil'] . '</td>';
                                        if ($value['Flag'] === '1') {
                                            echo '<td>Activo</td>';
                                        } else {
                                            echo '<td>Baja</td>';
                                        }

                                        echo '</tr>';
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>                
                </div>                
            </div>
            <!--Finalizando seccion de personal-->
            <!--Empezando seccion de tareas-->
            <div class="tab-pane fade" id="nav-pills-tab-4">
                <div class="row">
                    <div class="col-md-12">
                        <h3 class="m-t-10">Tareas del Proyecto</h3>
                        <table id="data-table-proyectos-tareas" class="table table-hover table-striped table-bordered no-wrap " style="cursor:pointer" width="100%">
                            <thead>
                                <tr>
                                    <th class="none">Id</th>
                                    <th class="all">Concepto</th>
                                    <th class="all">Area</th>
                                    <th class="all">Tarea</th>
                                    <th class="all">Líder</th>
                                    <th class="all">Inicio</th>
                                    <th class="all">Termino</th>           
                                    <th class="all">Avance</th>           
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (!empty($informacion['tareas'])) {
                                    foreach ($informacion['tareas'] as $value) {
                                        echo '<tr>';
                                        echo '<td>' . $value['Id'] . '</td>';
                                        echo '<td>' . $value['Concepto'] . '</td>';
                                        echo '<td>' . $value['Area'] . '</td>';
                                        echo '<td>' . $value['Tarea'] . '</td>';
                                        echo '<td>' . $value['Lider'] . '</td>';
                                        echo '<td>' . $value['Inicio'] . '</td>';
                                        echo '<td>' . $value['Termino'] . '</td>';
                                        echo '<td>0%</td>';
                                        echo '</tr>';
                                    }
                                }
                                ?>
                            </tbody>
                        </table>    
                    </div>                
                </div>                
            </div>
            <!--Finalizando seccion de tareas-->
        </div>
    </div>    
</div>
<!--Finalizando informacion del proyecto-->



