<div class="col-md-12">           
    <fieldset>                                                                                                                                                                                        
        <form class="margin-bottom-0" id="formActualizarMinuta" data-parsley-validate="true" >
            <div id="datosActualizar" > 
                <div class="row m-t-10">
                    <h4 class="m-t-10">Información de Minutas</h4>
                    <!--Empezando Separador-->
                    <div class="col-md-12">
                        <div class="underline m-b-15 m-t-15"></div>
                    </div>
                </div>
                <div class="row m-t-10">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="actualizarMinuta">Nombre</label>
                            <br>
                            <label for="actualizarMinuta"><h5><strong><p id="ActualizarNombreMinuta" ></p></strong></h5></label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="actualizarMinuta">Fecha</label>
                            <br>
                            <label for="actualizarMinuta"><h5><strong><p id="ActualizarFechaMinuta"></p></strong></h5></label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="actualizarMinuta">Ubicación</label>
                            <br>
                            <label for="actualizarMinuta"><h5><strong><p id="ActualizarUbicacionMinuta"></p></strong></h5></label>
                        </div>
                    </div>
                </div>
                <div class="row m-t-10"> 
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="catalogoActualizarPermisos">Miembros</label>
                            <select id="selectActualizarPermisos" class="form-control" style="width: 100%" multiple="multiple" disabled data-parsley-required="true">
                                <?php
                                foreach ($miembros as $item) {
                                    echo '<option value="' . $item['Id'] . '">' . $item['Nombre'] . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div> 
                </div>
                <div class="row m-t-10"> 
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="actualizarMinuta">Descripción</label>
                            <br>
                            <label for="actualizarMinuta"><h5><strong><p id="ActualizarDescripcionMinuta"></p></h5></strong></label>
                        </div>
                    </div>
                </div>
                <div id="minutaOriginal" class="row m-t-10"> 
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="actualizarMinuta">Minuta Original</label>
                            <div class="evidenciasMinuta">
                                <?php
                                foreach ($archivo as $item) {
                                    echo '<div class = "evidencia">';
                                    echo '<a href = "' . $item . '">';
                                    echo '<img src = "\assets\img\Iconos\word_icon.png" alt = "Lights" style = "width:100%">';
                                    echo '</a>';
                                    $posicion = strrpos($item, '/'); 
                                    echo '<p class="nombreArchivo">' . substr($item, $posicion + 1) . '</p>';
                                    echo '<p class="botonEliminar">                            
                                            <button id="btnEliminarEvidenciaMinuta" type="button" class="btn btn-sm btn-danger btn-xs hidden" data-nombrearchivo="' . $item . '"><i class="fa fa-trash"></i></button>
                                        </p>';
                                    echo '</div>';
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <!--Empezando input para evidencias -->
                <div id="inputEvidencias" class="row hidden">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="evidenciaMinuta">Minuta Original</label>
                            <input id="inputActualizarMinuta" name="actualizaEvidencia[]" type="file" multiple>
                        </div>
                    </div>
                </div>
                <!--Empezando error--> 
                <div class="row">
                    <div class="col-md-12">
                        <div class="errorActualizacionMinuta"></div>
                    </div>
                </div>
                <!--Finalizando Error-->
                <!--Finalizando input para evidencias -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group text-center">
                            <button id="btnActualizar" type="button" class="btn btn-sm btn-success m-r-5 hidden" ><i class="fa fa-edit"></i> Actualizar</button>
                            <button id="btnGuardar" type="button" class="btn btn-sm btn-primary m-r-5 hidden"><i class="fa fa-save"></i> Guardar</button>
                            <button id="btnCancelarActualizacion" type="button" class="btn btn-sm btn-danger m-r-5 hidden"><i class="fa fa-times"></i> Cancelar</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <!--Empezando Formulario para nueva minuta -->   
        <form id="formNuevaMinuta" class="margin-bottom-0" data-parsley-validate="true" enctype="multipart/form-data">
            <div class="row m-t-10">
                <div class="col-md-12">
                    <h3 class="m-t-10">Nueva Minuta</h3>
                    <!--Empezando Separador-->
                    <div class="col-md-12">
                        <div class="underline m-b-15 m-t-15"></div>
                    </div>
                    <!--Finalizando Separador-->
                </div>
                <div class="col-md-8">
                    <div class="form-group">
                        <label for="nuevaMinuta">Nombre *</label>
                        <input type="text" class="form-control" id="inputNombreMinuta" placeholder="Ingresa nombre de la Minuta" style="width: 100%" data-parsley-required="true"/>                            
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="nuevaMinuta"> Fecha *</label>
                        <div id="inputFecha" class="input-group date calendario" >
                            <input id="inputFechaMinuta" type="text" class="form-control nuevaMinuta" placeholder="Fecha" />
                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                        </div>
                    </div>
                </div>  
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="nuevaMinuta">Ubicación *</label>
                        <input type="text" class="form-control" id="inputUbicacionMinuta" placeholder="Ingresa ubicación de la Minuta" style="width: 100%" data-parsley-required="true"/>                            
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="nuevaMinuta">Miembros *</label>
                        <select id="selectMiembrosMinuta" class="form-control" style="width: 100%" multiple="multiple" data-parsley-required="true">
                            <?php
                            foreach ($miembros as $item) {
                                echo '<option value="' . $item['Id'] . '">' . $item['Nombre'] . ' ' . $item['ApPaterno'] . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="nuevaMinuta">Descripción *</label>
                        <textarea id="textareaDescripcionMinuta" class="form-control nuevoProyecto" name="descricpcionMinuta" placeholder="Ingresa una breve descripción de la minuta" rows="3" data-parsley-required="true" ></textarea>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="nuevaMinuta">Archivos de Minuta *</label>
                        <input id="inputEvidenciasMinuta" name="evidenciasMinuta[]" type="file" />
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="errorNuevaMinuta"></div>
                </div>
                <div class="col-md-12">
                    <div class="form-group text-center">
                        <br>
                        <a href="javascript:;" class="btn btn-success m-r-5 " id="btnNuevaMinuta"><i class="fa fa-save"></i> Guardar</a>
                        <a href="javascript:;" class="btn btn-default m-r-5 " id="btnRegresarMinutaNueva"><i class="fa fa-reply"></i> Regresar</a>
                    </div>
                </div>
            </div>
        </form>  
    </fieldset>
</div>
<!-- Empezando #contenido -->
<div class="col-md-12"
     <!-- Empezando panel actualizar minuta -->
     <div id="seccionActualizarMinuta" class="panel panel-inverse">
        <!--Empezando cuerpo del panel-->
        <div class="panel-body">
            <div class="row"> 
                <div id="evidenciasAdicionales" class="col-md-12">                        
                    <div class="form-group">
                        <h4 class="m-t-10">Archivos Adicionales</h4>
                        <!--Empezando Separador-->
                        <div class="col-md-12">
                            <div class="underline m-b-15 m-t-15"></div>
                        </div>
                        <form id="formActualizarAA">
                            <div class="row m-t-10"> 
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="actualizarMinuta">Agregar Archivo *</label>
                                        <input id="inputActualizarEvidenciasMinuta" name="evidenciasActualizarMinuta[]" type="file" multiple data-parsley-required="true" />
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="actualizarMinuta">Nombre *</label>
                                        <input type="text" class="form-control" id="inputNombreAA" placeholder="Ingresa Nombre del Archivo Adicional" style="width: 100%" data-parsley-required="true"/>                            
                                    </div>
                                </div>
                                <!--Empezando error--> 
                                <div class="col-md-12">
                                    <div class="errorMinutasAdicionales"></div>
                                </div>
                                <!--Finalizando Error-->
                                <div class="col-md-12">
                                    <div class="form-group text-center">
                                        <br>
                                        <a href="javascript:;" class="btn btn-success m-r-5 " id="btnActualizarMinuta"><i class="fa fa-plus"></i> Agregar Evidencia</a>
                                        <a href="javascript:;" class="btn btn-default m-r-5 " id="btnRegresarMinuta"><i class="fa fa-reply"></i> Regresar</a>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <table id="data-table-actualizarMinuta" class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%">
                            <thead>
                                <tr>
                                    <th class="never">Id</th>
                                    <th class="all">Miembro</th>
                                    <th class="all">Nombre del Archivo</th>
                                    <th class="all">Fecha</th>
                                    <th class="all">Acción</th>
                                </tr>
                            </thead>
                            <tbody>                                      
                            </tbody>
                        </table>
                    </div> 
                </div> 
            </div>
            <!--Empezando mensaje de tabla-->
            <div id="mensajeEliminar" class="row hidden">
                <div class="col-md-12 m-t-20">
                    <div class="alert alert-warning fade in m-b-15">                            
                        Para eliminar el registro de la tabla solo tiene que dar click sobre fila para eliminarlo.                            
                    </div>                        
                </div>
            </div>
            <!--Finalizando mensaje de tabla-->
        </div>
    </div>
    <!-- Finalizando panel actualizar minuta -->
</div>
<!-- Finalizando #contenido -->
