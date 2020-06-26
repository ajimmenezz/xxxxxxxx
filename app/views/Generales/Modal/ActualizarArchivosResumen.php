<!-- Empezando panel actualizar archivo -->
<div id="seccionActualizarMinuta" class="panel panel-inverse">
    <!--Empezando cuerpo del panel-->
    <div class="panel-body">
        <form class="margin-bottom-0" id="formActualizarArchivo" data-parsley-validate="true" >
            <div class="row m-t-10"> 
                <h4 class="m-t-10">Información de Archivo</h4>
                <!--Empezando Separador-->
                <div class="col-md-12">
                    <div class="underline m-b-15 m-t-15"></div>
                </div>
            </div> 
            <div class="row m-t-10"> 
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="resumenArchivo">Tipo de Archivo</label>
                        <select id="selectActualizarTiposArchivos" class="form-control" style="width: 100%" data-parsley-required="true">
                            <option value="">Seleccionar</option>
                            <?php
                            foreach ($SelectTipo as $item) {
                                echo '<option value="' . $item['Id'] . '">' . $item['Nombre'] . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="ActualizarArchivo">Nombre</label>
                        <input type="text" class="form-control" id="inputActualizarNombreArchivo" placeholder="Ingresa nombre del Cliente" style="width: 100%" data-parsley-required="true"/>                            
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="ActualizarArchivo">Fecha</label>
                        <br>
                        <label for="ActualizarArchivo"><h5><strong><p id="ActualizarFechaArchivo"></p></strong></h5></label>
                    </div>
                </div>
            </div>
            <div class="row m-t-10"> 
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="ActualizarArchivo">Descripción</label>
                        <textarea class="form-control" id="inputActualizarDescripcionArchivo" placeholder="Descripción breve de que trata el perfil" style="width: 100%" data-parsley-required="true"/> </textarea>                               
                    </div>
                </div>
            </div>
            <div class="row m-t-10"> 
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="ActualizarArchivo">Ultima Versión</label>
                        <div class="evidenciasSolicitud">
                            <?php
                            echo '<div class = "evidencia">';
                            echo '<a href = "' . $archivo[0]["Url"] . '">';
                            echo '<img src = "\assets\img\Iconos\word_icon.png" alt = "Lights" style = "width:100%">';
                            echo '</a>';
                            echo '<div id = "idP">';
                            echo '<p>' . substr($archivo[0]["Url"], 30, -5) . '</p>';
                            echo '</div>';
                            echo '</div>';
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            <!--</div>-->
            <!-- Empezando #contenido -->
            <!--<div class="col-md-12">-->
            <div class="row"> 
                <div class="col-md-12">                        
                    <div class="form-group">
                        <h4 class="m-t-10">Archivos Adicionales</h4>
                        <!--Empezando Separador-->
                        <div class="col-md-12">
                            <div class="underline m-b-15 m-t-15"></div>
                        </div>
                        <div class="row m-t-10"> 
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="actualizarMinuta">Agregar otro Archivo</label>
                                    <input id="inputActualizarEvidenciasArchivo" name="evidenciasActualizarArchivo[]" type="file"/>
                                </div>
                            </div>
                            <!--Empezando error--> 
                            <div class="col-md-12">
                                <div class="errorActualizarArchivo"></div>
                            </div>
                            <!--Finalizando Error-->
                            <div class="col-md-12">
                                <div class="form-group text-center">
                                    <br>
                                    <a href="javascript:;" class="btn btn-primary m-r-5 " id="btnActualizarArchivo"><i class="fa fa-save"></i> Guardar</a>
                                    <a href="javascript:;" class="btn btn-default m-r-5 " id="btnRegresarArchivo"><i class="fa fa-reply"></i> Regresar</a>
                                </div>
                            </div>
                        </div>
                        <table id="data-table-actualizarArchivo" class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%">
                            <thead>
                                <tr>
                                    <th class="never">Id</th>
                                    <th class="all">Nombre</th>
                                    <th class="all">Fecha</th>
                                    <th class="all">Archivo</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($historico as $key => $value) {
                                    echo '<tr>';
                                    echo '<td>' . $value['Id'] . '</td>';
                                    echo '<td>' . $value['Nombre'] . '</td>';
                                    echo '<td>' . $value['Fecha'] . '</td>';
                                    echo '<td><a href = "' . $value['Url'] . '">' . substr($value['Url'], 30, -5) . '</a></td>';
                                    echo '</tr>';
                                }
                                ?>                                        
                            </tbody>
                        </table>
                    </div>    
                </div> 
            </div>
        </form>  
    </div>
</div>
<!-- Finalizando panel actualizar archivo -->
