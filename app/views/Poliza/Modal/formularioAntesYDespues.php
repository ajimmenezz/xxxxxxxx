<!-- Empezando titulo de la pagina -->
<h1 class="page-header">Seguimiento Póliza</h1>
<!-- Finalizando titulo de la pagina -->
<!-- begin panel -->
<div id="seccion-servicio-mantemiento-puntos-censados" class="panel panel-inverse">
    <div class="panel-heading">
        <div class="panel-heading-btn">
            <label id="btnRegresarAntesYDespues" class="btn btn-success btn-xs">
                <i class="fa fa fa-reply"></i> Regresar
            </label>                                    
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-expand"><i class="fa fa-expand"></i></a>
        </div>
        <h4 class="panel-title">Antes y Después del Punto</h4>
    </div>
    <div class="panel-body">
        <form>
            <div id="wizard">
                <ol>
                    <li>
                        Información del Antes 
                        <small>Contiene la descripción del antes del mantenimiento al punto, asi como las evidencias.</small>
                    </li>
                    <li>
                        Información del Después
                        <small>Contiene la descripción del despúes del mantenimiento al punto, asi como las evidencias que lo respaldan.</small>
                    </li>
                    <li>
                        Fallas por Equipo
                        <small>Contiene las fallas de los equipos censados en el área y punto.</small>
                    </li>
                    <li>
                        Equipo Faltante
                        <small>Contiene los equipos faltantes por área y punto.</small>
                    </li>
                </ol>
                <!-- begin wizard step-1 -->
                <div>
                    <fieldset>
                        <legend class="pull-left width-full">Información del Antes</legend>

                        <!--Empezando Decripcion-->
                        <div class="row">
                            <div class="col-md-12">                                    
                                <div class="form-group">
                                    <label for="seguimientoMantenimiento">Observaciones del Antes *</label>
                                    <?php (empty($informacionPuntoCensado[0]['ObservacionesAntes'])) ? $observacionesAntes = '' : $observacionesAntes = $informacionPuntoCensado[0]['ObservacionesAntes']; ?>
                                    <textarea id="inputDescripcionAntes" class="form-control " placeholder="Observaciones correspondientes al mantenimiento del punto." rows="3" ><?php echo $observacionesAntes; ?></textarea>
                                </div>
                            </div>
                        </div>
                        <!--Finalizando-->

                        <!--Empezando Reporte Firmado-->
                        <div class="row">
                            <div class="col-md-12">                                    
                                <div class="form-group">
                                    <label for="seguimientoMantenimiento">Archivos o Evidencias del Antes *</label>
                                    <input id="evidenciasAntes"  name="evidenciasAntes[]" type="file" multiple/>
                                </div>
                            </div>
                        </div>
                        <!--Finalizando-->

                        <div class="row m-t-10">
                            <!--Empezando error--> 
                            <div class="col-md-12">
                                <div class="errorFormularioAntes"></div>
                            </div>
                            <!--Finalizando Error-->
                            <div class="col-md-12">
                                <div class="form-group text-center">
                                    <br>
                                    <a id="btnGuardarAntes" href="javascript:;" class="btn btn-primary m-r-5 "><i class="fa fa-floppy-o"></i> Guardar</a>                            
                                </div>
                            </div>
                        </div>

                    </fieldset>
                </div>
                <!-- end wizard step-1 -->
                <!-- begin wizard step-2 -->
                <div>
                    <fieldset>
                        <legend class="pull-left width-full">Información del Después</legend>

                        <!--Empezando Decripcion-->
                        <div class="row">
                            <div class="col-md-12">                                    
                                <div class="form-group">
                                    <label for="seguimientoMantenimiento">Observaciones del Depués *</label>
                                    <?php (empty($informacionPuntoCensado[0]['ObservacionesDespues'])) ? $observacionesDespues = '' : $observacionesDespues = $informacionPuntoCensado[0]['ObservacionesDespues']; ?>
                                    <textarea id="inputDescripcionDespues" class="form-control " placeholder="Observaciones correspondientes al mantenimiento del punto." rows="3" ><?php echo $observacionesDespues; ?></textarea>
                                </div>
                            </div>
                        </div>
                        <!--Finalizando-->

                        <!--Empezando Reporte Firmado-->
                        <div class="row">
                            <div class="col-md-12">                                    
                                <div class="form-group">
                                    <label for="seguimientoMantenimiento">Archivos o Evidencias del Después *</label>
                                    <input id="evidenciasDespues"  name="evidenciasDespues[]" type="file" multiple/>
                                </div>
                            </div>
                        </div>
                        <!--Finalizando-->

                        <div class="row m-t-10">
                            <!--Empezando error--> 
                            <div class="col-md-12">
                                <div class="errorFormularioDespues"></div>
                            </div>
                            <!--Finalizando Error-->
                            <div class="col-md-12">
                                <div class="form-group text-center">
                                    <br>
                                    <a id="btnGuardarDespues" href="javascript:;" class="btn btn-primary m-r-5 "><i class="fa fa-floppy-o"></i> Guardar</a>                            
                                </div>
                            </div>
                        </div>

                    </fieldset>
                </div>
                <div>
                    <fieldset>
                        <legend class="pull-left width-full">Fallas por equipo</legend>

                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="seguimientoMantenimiento">Equipos *</label>
                                    <select id="selectEquipoAntesYDespues" class="form-control" style="width: 100%">
                                        <option data-serie="" value="">Seleccionar</option>
                                        <?php
                                        foreach ($equiposSucursal as $item) {
                                            echo '<option data-serie="' . $item['Serie'] . '" value="' . $item['Modelo'] . '">' . $item['Equipo'] . ' (' . $item['Serie'] . ')</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div id="datosProblemaEquipo" class="hidden">

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="seguimientoMantenimiento">Archivos o Evidencias del Problema *</label>
                                        <input id="evidenciasFallasEquipo"  name="evidenciasFallasEquipo[]" type="file" multiple/>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="seguimientoMantenimiento">Observaciones del Problema *</label>
                                        <textarea id="inputDescripcionFallasEquipo" class="form-control " placeholder="Observaciones correspondientes al mantenimiento del punto." rows="3" ></textarea>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row m-t-10">
                                <div class="col-md-12">
                                    <div id="errorFallasEquipo"></div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group text-center">
                                        <br>
                                        <a id="btnGuardarFallasEquipo" href="javascript:;" class="btn btn-primary m-r-5 "><i class="fa fa-floppy-o"></i> Guardar Problema</a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!--Empezando Titulo de Tabla Fallas por Equip-->
                        <div class="row m-t-10">
                            <div class="col-md-12">
                                <div id="errorGuardarEquipo"></div>
                            </div>
                        </div>
                        <!--Finalizando--> 

                        <!--Empezando Titulo de Tabla Fallas por Equipo-->
                        <div class="row m-r-10">
                            <div class="col-md-12">
                                <h3 class="m-t-10">Lista de Fallas por Equipo</h3>
                            </div>
                        </div>
                        <!--Finalizando-->

                        <!--Empezando Separador-->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="underline m-b-15 m-t-15"></div>
                            </div>
                        </div>
                        <!--Finalizando--> 

                        <!--Empezando Tabla de Problemas de equipo-->
                        <div class="table-responsive">
                            <table id="data-table-problemas-equipo" class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%">
                                <thead>
                                    <tr>                                
                                        <th class="all">Área Atención</th>
                                        <th class="all">Punto</th>
                                        <th class="all">Equipo</th>                             
                                        <th class="all">Obervaciones</th>                             
                                        <th class="none">Evidencias</th>                                                      
                                        <th class="all">Acciones</th>                                                      
                                        <th class="never">IdArea</th>                                                      
                                        <th class="never">IdModelo</th>                                                      
                                        <th class="never">IdServicio</th>                                                      
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (!empty($problemasEquipo)) {
                                        foreach ($problemasEquipo as $key => $value) {
                                            $evidencias = explode(',', $value['Evidencias']);
                                            echo '<tr>';
                                            echo '<td>' . $value['Area'] . '</td>';
                                            echo '<td>' . $value['Punto'] . '</td>';
                                            echo '<td>' . $value['Equipo'] . '</td>';
                                            echo '<td>' . $value['Observaciones'] . '</td>';
                                            echo '<td>';
                                            foreach ($evidencias as $key => $valor) {
                                                echo '<a href="' . $valor . '" target="_blank"> <img src="' . $valor . '" title="" style="max-height:150px"/> </a>';
                                            }
                                            echo '</td>';
                                            echo '<td><a onclick="eventoEliminarProblemaEquipo(' . $value['IdArea'] . ',' . $value['IdModelo'] . ',' . $value['Punto'] . ',' . $value['IdServicio'] . ');" class="btn btn-danger btn-xs "><i class="fa fa-trash-o"></i> Eliminar</a> </td>';
                                            echo '<td>' . $value['IdArea'] . '</td>';
                                            echo '<td>' . $value['IdModelo'] . '</td>';
                                            echo '<td>' . $value['IdServicio'] . '</td>';
                                            echo '</tr>';
                                        }
                                    }
                                    ?>                                        
                                </tbody>
                            </table>
                        </div>
                    </fieldset>
                </div>
                <div>
                    <fieldset>
                        <legend class="pull-left width-full">Equipos Faltantes</legend>
                        
                        <!--Empezando formulario servicio mantenimiento-->
                        <form class="margin-bottom-0" id="formEquiposFaltantesAntesDespues" data-parsley-validate="true">
                            <!-- Empezando Seleccion Material o Equipo  -->
                            <div class="row ">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Equipo o Material</label>
                                        <select id="selectUtilizadoEquipoFaltante" class="form-control" style="width: 100%">
                                            <option value="">Seleccionar</option>
                                            <option value="1">Equipo</option>';
                                            <option value="2">Material</option>';
                                            <option value="3">Refacción</option>';
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <!-- Finalizando -->

                            <!-- Empezando Seleccion Equipo -->
                            <div id="seleccionEquipoAntesDespues" class="row hidden">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Equipo</label>
                                        <select id="selectEquipoAnteDespues" class="form-control" style="width: 100%">
                                            <option value="">Seleccionar</option>
                                            <?php
                                            foreach ($equipos as $item) {
                                                echo '<option value="' . $item['Id'] . '">' . $item['Equipo'] . '</option>';
                                            }
                                            ?>
                                        </select>                                           
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Cantidad</label>
                                        <input id="inputEquipoEquipoFaltanteCantidad" type="number" class="form-control"  placeholder="Cantidad"/>
                                    </div>
                                </div>                                
                            </div>
                            <!-- Finalizando -->

                            <!-- Empezando Seleccion Material -->
                            <div id="seleccionMaterialAntesDespues" class="row hidden">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Material</label>
                                        <select id="selectMaterialAntesDespues" class="form-control" style="width: 100%">
                                            <option value="">Seleccionar</option>
                                            <?php
                                            foreach ($equiposSAE as $item) {
                                                echo '<option value="' . $item['Id'] . '">' . $item['Nombre'] . '</option>';
                                            }
                                            ?>
                                        </select>                                           
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Cantidad</label>
                                        <input id="inputMaterialEquipoFaltanteCantidad" type="number" class="form-control"  placeholder="Cantidad"/>
                                    </div>
                                </div>                               
                            </div>
                            <!-- Finalizando -->

                            <!-- Empezando Seleccion Refaccion-->
                            <div id="seleccionRefaccionAntesDespues" class="row hidden">
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label>Equipo *</label>
                                        <select id="selectEquipoRefaccionAntesDespues" class="form-control" style="width: 100%">
                                            <option value="">Seleccionar</option>
                                            <?php
                                            foreach ($equipos as $item) {
                                                echo '<option value="' . $item['Id'] . '">' . $item['Equipo'] . '</option>';
                                            }
                                            ?>
                                        </select>                                           
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label>Refacción *</label>
                                        <select id="selectRefaccionAntesDespues" class="form-control" style="width: 100%" disabled>
                                            <option value="">Seleccionar</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Cantidad</label>
                                        <input id="inputRefaccionEquipoFaltanteCantidad" type="number" class="form-control"  placeholder="Cantidad"/>
                                    </div>
                                </div>                               
                            </div>
                            <!--Finalizando -->

                            <!--Empezando boton de agregar y mensaje de error-->
                            <div class="row m-t-10">
                                <!--Empezando error--> 
                                <div class="col-md-12">
                                    <div class="errorFormularioEquipoFaltante"></div>
                                </div>
                                <!--Finalizando Error-->
                                <div class="col-md-12">
                                    <div class="form-group text-center">
                                        <br>
                                        <a id="btnAgregarEquipoFaltanteMantenimiento" href="javascript:;" class="btn btn-success m-r-5 "><i class="fa fa-plus"></i> Agregar Equipo</a>                            
                                    </div>
                                </div>
                            </div>
                            <!--Finalizando-->

                        </form>
                        <!--Finalizando formulario servicio mantenimiento-->



                        <!--Empezando Titulo de Tabla Equipo Faltante-->
                        <div class="row m-r-10">
                            <div class="col-md-12">
                                <h3 class="m-t-10">Lista de Equipo Faltante</h3>
                            </div>
                        </div>
                        <!--Finalizando-->

                        <!--Empezando Separador-->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="underline m-b-15 m-t-15"></div>
                            </div>
                        </div>
                        <!--Finalizando--> 

                        <!--Empezando la tabla de puntos censados-->
                        <div class="row"> 
                            <!--Empezando Tabla Equipo Faltante-->
                            <div class="col-md-12">
                                <table id="data-table-equipos-faltantes" class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%">
                                    <thead>
                                        <tr>                                
                                            <th class="all">Equipo o Material</th>                                                         
                                            <th class="all">Nombre</th>                                                         
                                            <th class="all">Cantidad</th>                                                         
                                            <th class="never">IdEquipo</th>                                                         
                                            <th class="never">TipoItem</th>                                                         
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if (!empty($equipoFaltante)) {
                                            foreach ($equipoFaltante as $key => $value) {
                                                echo '<tr>';
                                                echo '<td>' . $value['NombreItem'] . '</td>';
                                                echo '<td>' . $value['Equipo'] . '</td>';
                                                echo '<td>' . $value['Cantidad'] . '</td>';
                                                echo '<td>' . $value['IdModelo'] . '</td>';
                                                echo '<td>' . $value['TipoItem'] . '</td>';
                                                echo '</tr>';
                                            }
                                        }
                                        ?>                                        
                                    </tbody>
                                </table>
                            </div>
                            <!--Finalizando-->
                        </div>
                        <!--Finalizando-->

                        <?php
                        if (!empty($equipoFaltante)) {
                            ?>
                            <!--Empezando mensaje de tabla-->
                            <div class="row">
                                <div class="col-md-12 m-t-20">
                                    <div class="alert alert-warning fade in m-b-15">                            
                                        Para eliminar el registro de la tabla solo tiene que dar click sobre la fila para eliminarlo.                            
                                    </div>                        
                                </div>
                            </div>
                            <!--Finalizando mensaje de tabla-->
                            <?php
                        }
                        ?> 

                        <!--Empezando boton de guardar tabla y mensaje de error-->
                        <div class="row m-t-10">
                            <!--Empezando error--> 
                            <div class="col-md-12">
                                <div class="errorTablaEquipoFaltante"></div>
                            </div>
                            <!--Finalizando Error-->
                            <div class="col-md-12">
                                <div class="form-group text-center">
                                    <br>
                                    <a id="btnGuardarTablaEquiposMantenimiento" href="javascript:;" class="btn btn-primary m-r-5 "><i class="fa fa-floppy-o"></i> Guardar Tabla de Equipos</a>                            
                                </div>
                            </div>
                        </div>
                        <!--Finalizando-->

                    </fieldset>
                </div>
                <!--Finalizando-->

            </div>
        </form>
    </div>
</div>
<!-- end panel -->
