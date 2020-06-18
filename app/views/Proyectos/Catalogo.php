<!-- Empezando #contenido -->
<div id="content" class="content">
    <!-- Empezando titulo de la pagina -->
    <h1 class="page-header">Catálogos <small>de proyectos</small></h1>
    <!-- Finalizando titulo de la pagina -->

    <!-- Empezando panel catálogo de sistemas especiales -->
    <div id="seccionTipoServicio" class="panel panel-inverse">
        <!--Empezando cabecera del panel-->
        <div class="panel-heading">
            <div class="panel-heading-btn">
                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>                            
                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>                            
            </div>
            <h4 class="panel-title">Sistemas Especiales</h4>
        </div>
        <!--Finalizando cabecera del panel-->
        <!--Empezando cuerpo del panel-->
        <div class="panel-body">
            <!--Inicia formulario para catálogo de sistema especial-->
            <fieldset>                                                                                                                                                                                        
                <!--Empezando campos fila 1 -->
                <div class="row m-t-10">                                  
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="nombreSistema">Nombre</label>
                            <input type="text" class="form-control" id="inputNombreSistemaEspecial" placeholder="Ingresa el nuevo sistema" style="width: 100%"/>                            
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="form-group">
                            <label for="nombreSistema">Descripción</label>
                            <div class="form-inline">
                                <input type="text" class="form-control" id="inputDescripcionSistemaEspecial" placeholder="Descripción breve de que trata el sistema" style="width: 60%"/>                                
                                <a href="javascript:;" class="btn btn-success m-r-5 " id="btnNuevoTipo"><i class="fa fa-plus"></i> Agregar</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <div class="form-inline muestraCarga"></div>
                        </div>
                    </div>

                    <!--Empezando error--> 
                    <div class="col-md-12">
                        <div class="errorTipoProyecto"></div>
                    </div>
                    <!--Finalizando Error-->

                    <!--Empezando Separador-->
                    <div class="col-md-12">
                        <div class="underline m-b-15 m-t-15"></div>
                    </div>
                    <!--Finalizando Separador-->


                </div>
                <!--Finalizando campos fila 1-->                               
                <!--Empezando tabla fila 2 -->
                <div class="row"> 
                    <div class="col-md-12">                        
                        <div class="form-group">
                            <table id="data-table-sistemasEspeciales" class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%">
                                <thead>
                                    <tr>
                                        <th class="never">Id</th>
                                        <th class="all">Nombre</th>
                                        <th class="all">Descripción</th>
                                        <th class="all">Estatus</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    foreach ($datos['TiposProyectos'] as $key => $value) {
                                        echo '<tr>';
                                        echo '<td>' . $value['Id'] . '</td>';
                                        echo '<td>' . $value['Nombre'] . '</td>';
                                        echo '<td>' . $value['Descripcion'] . '</td>';
                                        if ($value['Flag'] === '1') {
                                            echo '<td data-flag="' . $value['Flag'] . '">Activo</td>';
                                        } else {
                                            echo '<td data-flag="' . $value['Flag'] . '">Desactivado</td>';
                                        }
                                        echo '</tr>';
                                    }
                                    ?>                                        
                                </tbody>
                            </table>
                        </div>    
                    </div> 
                </div>
                <!--Finalizando tabla fila 2-->
            </fieldset>
            <!-- Finaliza formulario para catálogo de sistema especial-->
        </div>
        <!--Finalizando cuerpo del panel-->
    </div>
    <!-- Finalizando panel catálogo de sistemas especiales -->

    <!-- Empezando panel tareas de proyectos-->
    <div id="seccionTareas" class="panel panel-inverse">
        <!--Empezando cabecera del panel-->
        <div class="panel-heading">
            <div class="panel-heading-btn">
                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>                            
                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>                            
            </div>
            <h4 class="panel-title">Tareas de proyectos</h4>
        </div>
        <!--Finalizando cabecera del panel-->
        <!--Empezando cuerpo del panel-->
        <div class="panel-body">
            <!--Empezando fila 1-->
            <div class="row m-t-10">
                <div class="col-md-5">
                    <div class="form-group">
                        <label for="selectSistemaEspecial">Sistema Especial</label>
                        <select id="selectSistemaEspecial" class="form-control" style="width: 100%" required>
                            <option value="">Seleccionar</option>
                            <?php
                            foreach ($datos['TiposProyectos'] as $item) {
                                echo '<option value="' . $item['Id'] . '">' . $item['Nombre'] . '</option>';
                            }
                            ?>        
                        </select>   
                    </div>
                </div>
                <div class="col-md-7">
                    <div class="form-group">
                        <label for="nombreTareaProyecto">Nombre</label>
                        <div class="form-inline">
                            <input type="text" class="form-control" id="inputNombreTareaProyecto" placeholder="Ingresa el nueva tarea" style="width: 60%"/>
                            <a href="javascript:;" class="btn btn-success m-r-5 " id="btnNuevaTarea"><i class="fa fa-plus"></i> Agregar</a>
                        </div>
                    </div>
                </div>
                <!--Empezando error--> 
                <div class="col-md-12">
                    <div class="errorTarea"></div>
                </div>
                <!--Finalizando Error-->
                <!--Empezando Separador-->
                <div class="col-md-12">
                    <div class="underline m-b-15 m-t-15"></div>
                </div>
                <!--Finalizando Separador-->
            </div>
            <!--Finalizando fila 1-->
            <!--Empezando tabla fila 2-->
            <div class="row">
                <div class="col-md-12">          
                    <div class="form-group">
                        <table id="data-table-tarea" class="table table-hover table-striped table-bordered no-wrap " style="cursor:pointer" width="100%">
                            <thead>
                                <tr>
                                    <th class="never">Id</th>
                                    <th class="all">Nombre</th>
                                    <th class="all">Sistema</th>
                                    <th class="all">Estatus</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($datos['Tareas'] as $key => $value) {
                                    echo '<tr>';
                                    echo '<td>' . $value['Id'] . '</td>';
                                    echo '<td>' . $value['Nombre'] . '</td>';
                                    echo '<td>' . $value['Tipo'] . '</td>';
                                    if ($value['Flag'] === '1') {
                                        echo '<td>Activo</td>';
                                    } else {
                                        echo '<td>Desactivado</td>';
                                    }
                                    echo '</tr>';
                                }
                                ?>    
                            </tbody>
                        </table>
                    </div>    
                </div> 
            </div>
            <!--Finalizando tabla fila 2-->
        </div>
        <!--Finalizando cuerpo del panel-->
    </div>
    <!-- Finalizando panel tareas de proyectos -->

</div>
<!-- Finalizando #contenido -->