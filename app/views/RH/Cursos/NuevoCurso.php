<div class="panel panel-inverse" data-sortable-id="ui-widget-1" >
    <div class="panel-heading">
        <h4 class="panel-title">Nuevo curso</h4>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-12">
                <!--                <div class="col-sm-12  messageAccionesWizard"></div>-->
                <form id="formDatosNewCurso"  enctype="multipart/form-data" data-parsley-validate="true" name="form-wizard">
                    <div id="wizard">
                        <ol>
                            <li><!-- id="showContent_1" -->
                                Datos del curso
                                <small>Establece la Información del curso.</small>
                            </li>
                            <li> <!-- id="showContent_2" -->
                                Temario
                                <small>Establece los temas que se estarán evaluando en el curso.</small>
                            </li>
                            <li> <!-- id="showContent_3"-->
                                Participantes
                                <small>Indican los puestos que tendrán que tomar el curso.</small>
                            </li>

                        </ol>
                        <!-- begin wizard step-1 -->
                        <div class="wizard-step-1">
                            <fieldset>
                                <legend class="width-full">
                                    <h4 class="pull-left">Datos curso</h4>
                                    <button type="button" class="btn btn-danger m-r-5 m-b-5 btn-cancel-wizard pull-right">Cancelar</button>
                                </legend>
                                <!-- begin formulario -->
                                <div class="row">
                                    <div class="col-md-offset-1 col-md-3 p-l-40 p-r-40">
                                        <!-- begin profile-image -->
                                        <div id="img-curso" class="profile-image">
                                            <img src="/assets/img/Iconos/no-thumbnail.jpg" />
<!--                                                <i class="fa fa-file-image-o"></i>-->
                                        </div>
                                        <!-- end profile-image -->
                                        <div class="m-b-10">
                                            <a id="btn-imagen-curso" href="#" class="btn btn-warning btn-block btn-sm">Establecer Imagen</a>
                                        </div>
                                        <!-- begin profile-highlight -->
                                        <input id="agregar-imagen" type="file" class="hidden"/>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Nombre del curso *</label>
                                            <input type="text" id="input-nombreCurso" name="Nombre" placeholder="Nombre" class="form-control"  data-parsley-group="wizard-step-1" required/>
                                        </div>
                                        <div class="form-group">
                                            <label for="nuevoArchivo">Descripción *</label>
                                            <textarea id="textarea-descripcionCurso" class="form-control" name="textareaDescripcionCurso" placeholder="Ingresa una descripción del curso" rows="6"  data-parsley-group="wizard-step-1" required /></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Url *</label>
                                            <input type="text" id="input-urlCurso" name="url" placeholder="http://" class="form-control"  data-parsley-group="wizard-step-1" required />
                                        </div>
                                        <div class="form-group">
                                            <label for="nuevoArchivo">Certificado </label>
                                            <select id="select-certificado" class="form-control" style="width: 100%" >
                                                <?php
                                                foreach ($certificados as $value) {

                                                    echo '<option value="' . $value['id'] . '">' . $value['nombre'] . '</option>';
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Costo </label>
                                            <input type="text" id="input-costoCurso" name="costo" placeholder="$00.00" class="form-control" />
                                        </div>
                                    </div>
                                </div>
                                <!-- end formulario -->
                            </fieldset>
                        </div>
                        <!-- end wizard step-1 -->
                        <!-- begin wizard step-2 -->
                        <div class="wizard-step-2">
                            <fieldset>
                                <legend class="pull-left width-full">
                                    <h4 class="pull-left">Temario</h4>
                                    <button type="button" class="btn btn-danger m-r-5 m-b-5 btn-cancel-wizard pull-right">Cancelar</button>
                                </legend>

                                <div class="row">
                                    <div class="col-md-5">
                                        <div class="form-group">                                                
                                            <div class="col-md-12">
                                                <p>
                                                    Aqui defines el temario que lleva el curso que tomara el personal de la empresa.<br>
                                                    Cada temario que se ingrese tendrá un valor porcentual del 100%, esto quiere 
                                                    decir, que si yo ingreso 10 temas cada uno tendrá un valor del 10%, por lo que es 
                                                    importante que tome esto en consideración al definirlo.
                                                    <br><br>
                                                    <b>Nota: Es importante que se deba definir al menos un temario al curso ya que 
                                                        no se podrá crear.
                                                    </b>
                                                </p>
                                            </div>
                                            <div class="col-sm-4 col-sm-offset-4 col-md-4 col-md-offset-4 m-t-30 hidden">
                                                <button type="button" class="btn btn-success m-r-5 m-b-5 visible-md-block  visible-lg-block btn-subir-excel-temario" ><i class="fa fa-file"></i> Subir Excel</button>
                                                <button type="button" class="btn btn-success m-r-5 m-b-5 visible-sm-block btn-subir-excel-temario" ><i class="fa fa-file"></i> Subir Excel</button>
                                                <button type="button" class="btn btn-block btn-success m-r-5 m-b-5 visible-xs-block btn-subir-excel-temario" ><i class="fa fa-file"></i> Subir Excel</button>
                                            </div>                                               
                                        </div>
                                    </div>
                                    <div class="col-md-7">
                                        <div class="form-group">
                                            <div class="col-md-12">
                                                <div class="input-group">
                                                    <input type="text" id="input-temario" name="Nombre" placeholder="Nombre del temario" class="form-control"/>
                                                    <div class="input-group-btn">
                                                        <label>&nbsp;</label> 
                                                        <button type="button" id="btn-agregar-nuevo-temario"  class="btn btn-success"><i class="fa fa-plus"></i> Agregar</button>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <table id="tabla-nuevo-curso-temarios" class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%">
                                                    <thead>
                                                        <tr>
                                                            <th>Temario</th>
                                                            <th>Porcentaje</th>
                                                            <th>Acciones</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                        <!-- end wizard step-2 -->
                        <!-- begin wizard step-3 -->
                        <div class="wizard-step-3">
                            <fieldset>
                                <legend class="width-full">
                                    <h4 class="pull-left">Participantes</h4>
                                    <button type="button" class="btn btn-danger m-r-5 m-b-5 btn-cancel-wizard pull-right">Cancelar</button>
                                </legend>             

                                <div class="row">
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <div class="col-md-12">                                                    
                                                <p>
                                                    Indica los puestos que deben tomar el curso.<br>
                                                    Cuando generes el curso el sistema notificará por correo al personal que cubra el 
                                                    puesto que esta asignado en el curso y también le aparecerá en la sección de CURSOS ASIGNADOS.

                                                    <br><br>
                                                    <b>Nota: Es importante que se deba definir al menos un puesto. </b>
                                                </p>
                                            </div>
                                            <div class="col-sm-4 col-sm-offset-4 col-md-4 col-md-offset-4 m-t-30">
                                                <button id="btn-generar-curso" type="button" class="btn btn-success" ><i class="fa fa-save"></i> Guardar curso</button>
                                            </div>  
                                        </div>
                                    </div>
                                    <div class="col-md-7">
                                        <div class="form-inline">
                                            <div class="form-group">
                                                <div class="col-md-9 m-r-0">
                                                    <select id="select-participante" class="form-control" style="width: 100%">
                                                        <option value="">Seleccionar</option>
                                                        <?php
                                                        foreach ($perfiles as $value) {

                                                            echo '<option value="' . $value['Id'] . '">' . $value['Nombre'] . '</option>';
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="col-md-3 p-l-0">
                                                    <button id="btn-nuevo-puestoParticipante" type="button" class="btn btn-success btn-block"><i class="fa fa-plus"></i> Agregar</button>
                                                </div>
                                                <div class="col-md-12">
                                                    <table id="tabla-nuevo-cursos-participantes" class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%">
                                                        <thead>
                                                            <tr>
                                                                <th class="never">id</th>
                                                                <th class="all">Puesto</th>
                                                                <th class="all">Acciones</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                        <!-- end wizard step-3 -->
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>