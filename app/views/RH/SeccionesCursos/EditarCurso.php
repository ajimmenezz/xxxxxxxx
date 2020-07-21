<div id="editarCurso">    
    <div class="row">
        <div class="col-sm-12">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#datos-curso" data-toggle="tab">Datos del curso</a></li>
                <li class=""><a href="#temarios" data-toggle="tab">Temario</a></li>
                <li class=""><a href="#participantes" data-toggle="tab">Participantes</a></li>
                <div class="pull-right m-5">
                    <button id="btn-regresar-cursos" type="button" class="btn btn-primary btn-sm">Regresar a cursos</button>
                </div>
            </ul>
            <div class="tab-content">
                <div id="datos-curso" class="tab-pane fade active in">
                    <form id="form-edit-datos-curso"  enctype="multipart/form-data" data-parsley-validate="true">
                        <div class="row m-t-30 m-b-30">
                            <div class="col-md-offset-1 col-md-3 p-l-40 p-r-40">
                                <!-- begin profile-image -->
                                <div id="contenedor-edit-imagen" class="profile-image">
                                    <img src="/assets/img/Iconos/no-thumbnail.jpg" />
                                </div>
                                <!-- end profile-image -->
                                <div class="m-b-10">
                                    <a id="btn-edit-imagen" href="#" class="btn btn-warning btn-block btn-sm disabled" disabled>Establecer Imagen</a>
                                </div>
                                <!-- begin profile-highlight -->
                                <input id="file-edit-imagen" type="file" class="hidden"/>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Nombre del curso *</label>
                                    <input id="input-edit-nombre" type="text" name="Nombre" placeholder="Nombre" class="form-control"  data-parsley-group="wizard-step-1" required disabled/>
                                </div>
                                <div class="form-group">
                                    <label for="nuevoArchivo">Descripción *</label>
                                    <textarea id="textarea-edit-descripcion" class="form-control" name="textareaDescripcionCurso" placeholder="Ingresa una descripción del curso" rows="6"  data-parsley-group="wizard-step-1" required disabled/></textarea>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Url *</label>
                                    <input id="input-edit-url" type="text" name="url" placeholder="http://" class="form-control"  data-parsley-group="wizard-step-1" required  disabled/>
                                </div>
                                <div class="form-group">
                                    <label for="nuevoArchivo">Certificado </label>
                                    <select id="select-edit-certificado" class="form-control" style="width: 100%" disabled>
                                        <?php
                                        foreach ($certificados as $value) {

                                            echo '<option value="' . $value['id'] . '">' . $value['nombre'] . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Costo </label>
                                    <input id="input-edit-costo" type="text" name="costo" placeholder="$00.00" class="form-control"  disabled/>
                                </div>
                            </div>
                        </div>
                        <div class="row m-t-30 m-b-30">
                            <div class="text-right col-md-11">
                                <button id="btn-edit-cancelar" type="button" class="btn btn-white m-r-5 m-b-5 hidden">Cancelar</button>
                                <button id="btn-edit-guardar" type="button" class="btn btn-success m-r-5 m-b-5 hidden">Guardar</button>
                                <button id="btn-edit-habilitar" type="button" class="btn btn-info m-r-5 m-b-5">Editar Datos</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div id="temarios" class="tab-pane fade">
                    <div class="row m-30">
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
                </div>
                <div id="participantes" class="tab-pane fade" >
                    <div class="row m-30">
                        <div class="col-md-4">
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
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group ">
                                <div class="col-md-12">
                                    <div class="input-group">
                                        <select id="select-participante" class="form-control" style="width: 100%">
                                            <option value="">Seleccionar</option>                                          
                                        </select>
                                        <div class="input-group-btn">
                                            <button id="btn-nuevo-puestoParticipante" type="button" class="btn btn-success btn-block"><i class="fa fa-plus"></i> Agregar</button>
                                        </div>
                                    </div>
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
            </div>

        </div>
    </div>

</div>
