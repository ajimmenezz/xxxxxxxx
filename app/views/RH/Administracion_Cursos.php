<!-- Empezando #contenido -->
<div id="administracion-cursos" class="content">
    <!-- begin page-header -->
    <h1 class="page-header">Administración de Cursos</h1>
    <!-- end page-header -->

    <div class="panel panel-inverse" data-sortable-id="ui-widget-1" >
        <div class="panel-heading">
            <h4 class="panel-title">Cursos</h4>
        </div>
        <div class="panel-body">
            <div class="row" style="margin-top: 20px;">
                <div class="col-md-9">
                   Este modulo tiene el objetivo de administrar los cursos en linea que tomará el personal de la empresa.<br>
                   Aquí se cuenta con las funcionalidades para la creación, edición y eliminación de un curso, de igual forma, 
                   se puede ver el avance general de los cursos y el de cada uno. También se peude dar seguimiento al avance de 
                   cada uno de los participantes que se encuentran asignados al curso.
                </div>
                <div class="col-md-3">
                    <button id="btn-nuevo-curso" type="button" class="btn btn-primary m-r-5 m-b-5" style="float: right;">Nuevo Curso</button>
                    <button id="btn-subir-cursos" type="button" class="btn btn-info m-r-5 m-b-5" style="float: right;">Subir Cursos</button>
                   
                </div>
              
            </div>

            <!-- begin tabla cursos -->
            <div class="row" style="margin-top:50px;">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table id="tabla-cursos" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                  <td>Nombre</td>
                                  <td>Descripción</td>
                                  <td>#Participantes</td>
                                  <td>Estatus</td>
                                  <td>Acciones</td>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                            var_dump($datos['cursos']);
                                
                                foreach ($datos['cursos'] as $value) {
                                    echo "<tr>";
                                        echo "<td>".$value["nombre"]."</td>";
                                        echo "<td>".$value["descripcion"]."</td>";
                                        
                                        echo "<td>0</td>";
                                        $estado="Activo";
                                        if($value["estatus"]==0){
                                            $estado="Inactivo";
                                        }
                                        echo "<td>".$estado."</td>";
                                        echo "<td> 
                                                <div style='text-align: center;'>
                                                <i class='fa fa-eye' style='cursor: pointer; margin: 5px; font-size: 17px;  color: #348fe2;' id='btn-adminVerCurso'></i>
                                                <i class='fa fa-pencil' style='cursor: pointer; margin: 5px; font-size: 17px; color: orange;'' id='btn-adminEditarCurso' ></i>
                                                <i class='fa fa-trash' style='cursor: pointer; margin: 5px; font-size: 17px;  color: red;' id='btn-adminEliminarCurso'></i>
                                                </div>
                                            </td>";
                                    echo "</tr>";
                                }

                               

                                // foreach ($datos['cursos'] as $value) {
                                //     echo '<tr>';
                                //     foreach ($value as $dato) {
                                //         echo '<td>' . $dato . '</td>';
                                //     }
                                //     echo '</tr>';
                                // }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- end tabla cursos -->
        </div>
    </div>

    <!--SandBox SmartResponse--->
    <div class="panel panel-inverse" data-sortable-id="ui-widget-1">
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12">
                    <button id="btn-demo-smartresponse" type="button" class="btn btn-info m-r-5 m-b-5">Demo SmartResponse</button>
                    <button id="btn-demo-smartresponse-error" type="button" class="btn btn-info m-r-5 m-b-5">Demo SmartResponse Error</button>
                    </div>
                </div>

                <div class="row">
                <div id="sandbox-result" class="col-md-12"></div>
                </div>
            </div>
    </div>
</div>
<!-- Finalizando #contenido  PRINCIPAL-->


<!-- Empezando #contenido NUEVO CURSO-->
<div id="administracion-cursos_nuevoCurso" class="content" style="display:none;">
    <!-- begin page-header -->
    <h1 class="page-header">Administración de Cursos</h1>
    <!-- end page-header -->

    <div class="panel panel-inverse" data-sortable-id="ui-widget-1" >
        <div class="panel-heading">
            <h4 class="panel-title">Nuevo curso</h4>
        </div>
        <div class="panel-body">
            
            <div class="row">

                <form action="/" method="POST" data-parsley-validate="true" name="form-wizard">
                    <div id="wizard">
                        <ol>
                            <li>
                                Datos del curso
                                <small>Establece la Información del curso.</small>
                            </li>
                            <li>
                                Temario
                                <small>Establece los temas que se estarán evaluando en el curso.</small>
                            </li>
                            <li>
                                Participantes
                                <small>Indican los puestos que tendrán que tomar el curso.</small>
                            </li>
                           
                        </ol>
                        <!-- begin wizard step-1 -->
                        <div>
                            <fieldset>

                                <div class="row">
                                    <div class=" col-xs-12 col-md-8">
                                        <h4 class="pull-left width-full">Datos curso</h4>
                                    </div>
                                    <div class=" col-xs-12 col-md-4">
                                        <button id="btn-cancel_nuevo-curso" type="button" class="btn btn-danger m-r-5 m-b-5 btn-cancel_wizard" style="float: right;">Cancelar</button>
                                    </div>
                                    <div class=" col-xs-12 col-md-12"><hr style="width:100%;"></div>
                                </div>
                        
                                <div class="row">
                                    <div class="col-xs-4">img 
                                    <span class="btn btn-primary btn-file" style="position: absolute; margin-right: -45px;">
                                        <i class="" style="position: absolute; padding-top: 12px;  margin-left: -18px;">
                                            <!-- <input type="file" id="imgLogo" name="imgLogo" @change="subirLogo()"> -->
                                            <input id="idInpinputfile" @change="onSelectedFiles" ref="file" type="file" name="files" style="display: none">
                                        </i>

                                    </span>
                              </div>
                                    <div class="col-xs-8">
                                       
                                        <!-- begin row -->
                                        <div class="row">
                                            <!-- begin col-4 -->
                                            <div class=" col-xs-12 col-md-6">
                                                <div class="form-group">
                                                    <label>Nombre del curso *</label>
                                                    <input type="text" name="Nombre" placeholder="Nombre" class="form-control" />
                                                </div>
                                            </div>
                                            <!-- end col-4 -->
                                            <!-- begin col-4 -->
                                            <div class=" col-xs-12 col-md-6">
                                                <div class="form-group">
                                                    <label>Url *</label>
                                                    <input type="text" name="url" placeholder="http://" class="form-control" />
                                                </div>
                                            </div>
                                            <!-- end col-4 -->
                                            <!-- begin col-4 -->
                                            <div class=" col-xs-12 col-md-6">
                                                <div class="form-group">
                                                    <label for="nuevoArchivo">Descripción *</label>
                                                    <textarea id="textareaDescripcionArchivo" class="form-control" name="descripcionArchivo" placeholder="Ingresa una descripción del curso" rows="6" data-parsley-required="true"/></textarea>
                                                </div>
                                            </div>
                                            <!-- end col-4 -->
                                            <!-- begin col-4 -->
                                            <div class=" col-xs-12 col-md-6">
                                                <div class="form-group">
                                                    <label for="nuevoArchivo">Certificado </label>
                                                    <select id="selectTiposArchivos" class="form-control" style="width: 100%" data-parsley-required="true">
                                                        <option value="1">Sin certificado</option>
                                                        <option value="1">Con certificado</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <!-- end col-4 -->
                                            <!-- begin col-4 -->
                                            <div class=" col-xs-12 col-md-6">
                                                <div class="form-group">
                                                    <label>Costo </label>
                                                    <input type="text" name="costo" placeholder="$00.00" class="form-control" />
                                                </div>
                                            </div>
                                            <!-- end col-4 -->
                                        </div>
                                        <!-- end row -->
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                        <!-- end wizard step-1 -->
                        <!-- begin wizard step-2 -->
                        <div>
                            <fieldset>
                                <div class="row">
                                    <div class=" col-xs-12 col-md-8">
                                        <h4 class="pull-left width-full">Temario</h4>
                                    </div>
                                    <div class=" col-xs-12 col-md-4">
                                        <button id="btn-cancel_temario" type="button" class="btn btn-danger m-r-5 m-b-5 btn-cancel_wizard" style="float: right;">Cancelar</button>
                                    </div>
                                    <div class=" col-xs-12 col-md-12"><hr style="width:100%;"></div>
                                </div>

                                <div class="row">
                                    <div class="col-xs-12 col-md-6">
                                        <div class="row">
                                            <div class="col-xs-9">
                                                <div class="form-group">
                                                    <label>Nombre del curso *</label>
                                                    <input type="text" name="Nombre" placeholder="Nombre" class="form-control" />
                                                </div>
                                            </div>
                                            <div class="col-xs-3">
                                                <button style="margin-top: 21px;" id="btn-agregar-nuevo-temario" type="button" class="btn btn-success m-r-5 m-b-5" style="float: right;"><i class="fa fa-plus"></i> Agregar</button>
                                            </div>

                                            <div class="col-xs-12">
                                                Aqui defines el temario que lleva el curso que tomara el personal de la empresa.<br>
                                                Cada temario que se ingrese tendrá un valor porcentual del 100%, esto quiere 
                                                decir, que si yo ingreso 10 temas cada uno tendrá un valor del 10%, por lo que es 
                                                importante que tome esto en consideración al definirlo.
                                                <br><br>
                                                <b>Nota: Es importante que se deba definir al menos un temario al curso ya que 
                                                    no se podrá crear.
                                                </b>
                                            </div>
                                            <div class="col-xs-12" style="text-align: center; margin-top:30px;" >
                                            <button id="btn-loadExcel-temario" type="button" class="btn btn-success m-r-5 m-b-5" ><i class="fa fa-file"></i> Subir Excel</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-md-6">
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <div class="table-responsive">
                                                    <table id="tabla-cursos-temario" class="table table-striped table-bordered">
                                                        <thead>
                                                            <tr>
                                                            <td>Temario</td>
                                                            <td>Porcentaje</td>
                                                            <td>Nombre</td>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            foreach ($datos['filas'] as $value) {
                                                                echo '<tr>';
                                                                foreach ($value as $dato) {
                                                                    echo '<td>' . $dato . '</td>';
                                                                }
                                                                echo '</tr>';
                                                            }
                                                            ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                        <!-- end wizard step-2 -->
                        <!-- begin wizard step-3 -->
                        <div>
                            <fieldset>
                            <div class="row">
                                    <div class=" col-xs-12 col-md-8">
                                        <h4 class="pull-left width-full">Participantes</h4>
                                    </div>
                                    <div class=" col-xs-12 col-md-4">
                                        <button id="btn-cancel_participantes" type="button" class="btn btn-danger m-r-5 m-b-5 btn-cancel_wizard" style="float: right;">Cancelar</button>
                                    </div>
                                    <div class=" col-xs-12 col-md-12"><hr style="width:100%;"></div>
                                </div>

                                <div class="row">
                                    <div class="col-xs-12 col-md-6">
                                        <div class="row">
                                            <div class="col-md-9">
                                                <div class="form-group">
                                                    <label for="puesto">Puesto </label>
                                                    <select id="puesto" class="form-control" style="width: 100%" data-parsley-required="true">
                                                        <option value="">Seleccionar</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <button style="margin-top: 21px;"  id="btn-nuevo-puestoParticipante" type="button" class="btn btn-success m-r-5 m-b-5" style="float: right;"><i class="fa fa-plus"></i> Agregar</button>
                                            </div>

                                            <div class="col-xs-12">
                                                Indica los puestos que deben tomar el curso.<br>
                                                Cuando generes el curso el sistema notificará por correo al personal que cubra el 
                                                puesto que esta asignado en el curso y también le aparecerá en la sección de CURSOS ASIGNADOS.
                                                
                                                <br><br>
                                                <b>Nota: Es importante que se deba definir al menos un puesto. </b>
                                            </div>
                                            <div class="col-xs-12" style="text-align: center; margin-top:30px;">
                                            <button id="btn-save-curso" type="button" class="btn btn-success m-r-5 m-b-5" ><i class="fa fa-save"></i> Guardar curso</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-md-6">
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <div class="table-responsive">
                                                    <table id="tabla-cursos-participantes" class="table table-striped table-bordered">
                                                        <thead>
                                                            <tr>
                                                            <td>Puesto</td>
                                                            <td>Acciones</td>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            foreach ($datos['filas'] as $value) {
                                                                echo '<tr>';
                                                                foreach ($value as $dato) {
                                                                    echo '<td>' . $dato . '</td>';
                                                                }
                                                                echo '</tr>';
                                                            }
                                                            ?>
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
<!-- Finalizando #contenido -->


<!-- Empezando #contenido MODALS-->

<!-- subir temarios -->
<div id="modalSubirTemarios" class="modal fade " tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered " role="document">
          <div class="modal-content">
              <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalCenterTitle" >Subir temarios</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              </div>
              <div class="modal-body ">

                <div class="container">
                  <div class="row">
                   
                    <div class="col-11">
                         Para poder subir los cursos a través de un archivo de Excel es necesario seguir los siguientes pasos: <br><br>
                                1.- Debes descargar la plantilla de Excel en el botón descargar plantilla.<br>
                                2.- LLenar la plantilla con los datos solicitados.<br>
                                3.- Subir la platilla con el botón archivo (solo formato Excel).<br>
                                4.- Una vez cargado el archivo solo dar clic en subir archivo.<br><br>
                    </div>
                   
                  </div>
                </div>

              </div>
              <div class="modal-footer">
                    <a href="javascript:;" class="btn btn-white m-r-5 " id="cerrar" data-dismiss="modal" aria-label="Close"> Cerrar</a>
                    <a href="javascript:;" class="btn btn-primary m-r-5 " id="desPlantilla">Descargar plantilla</a>
                    <a href="javascript:;" class="btn btn-success m-r-5 " id="save"> Subir plantilla</a>
              </div>
              <div id="alertasGeocercas"></div>

          </div>
      </div>
    </div>

<!-- fin subir temarios -->

<!--  temarios por lo menos 1 -->
<div id="modalValidateTemario" class="modal fade " tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered " role="document">
          <div class="modal-content">
              <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalCenterTitle" >Temarios</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              </div>
              <div class="modal-body ">

                <div class="container">
                  <div class="row">
                   
                    <div class="col-11">
                        Debes registrar al menos un temario para poder continuar la creación del curso.
                    </div>
                   
                  </div>
                </div>

              </div>
              <div class="modal-footer">
                    <a href="javascript:;" class="btn btn-white m-r-5 " id="cerrar" data-dismiss="modal" aria-label="Close"> Cerrar</a>
              </div>
            

          </div>
      </div>
    </div>

<!-- fin  temarios 1-->


<!--  participantes por lo menos 1 -->
<div id="modalValidateParticipantes" class="modal fade " tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
      <div class="modal-dialog-sm modal-dialog-centered " role="document">
          <div class="modal-content">
              <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalCenterTitle" >Participantes</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              </div>
              <div class="modal-body ">

                <div class="container">
                  <div class="row">
                   
                    <div class="col-11">
                        Debes registrar al menos un participante para poder continuar la creación del curso.
                    </div>
                   
                  </div>
                </div>

              </div>
              <div class="modal-footer">
                    <a href="javascript:;" class="btn btn-white m-r-5 " id="cerrar" data-dismiss="modal" aria-label="Close"> Cerrar</a>
              </div>
            

          </div>
      </div>
    </div>

<!-- fin participantes 1-->

<!--  guardar curso  -->
<div id="modalresponseSave" class="modal fade " tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
      <div class="modal-dialog-sm modal-dialog-centered " role="document">
          <div class="modal-content">
              <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalCenterTitle" >Nuevo curso</h5>
                  <button type="button" class="close btn-cancel_wizard" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              </div>
              <div class="modal-body ">

                <div class="container">
                  <div class="row">
                   
                    <div class="col-11">
                        Se genero el curso {title} con éxito.
                    </div>
                   
                  </div>
                </div>

              </div>
              <div class="modal-footer">
                    <a href="javascript:;" class="btn btn-white m-r-5 btn-cancel_wizard" id="cerrar" data-dismiss="modal" aria-label="Close"> Cerrar</a>
              </div>
              

          </div>
      </div>
    </div>

<!-- fin guardar curso-->

<!-- FIN #contenido MODALS-->

